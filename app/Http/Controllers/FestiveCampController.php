<?php

namespace App\Http\Controllers;

use App\Models\FestiveCampRegistration;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class FestiveCampController extends Controller
{
    /**
     * Show registration form (for logged-in parent)
     */
    public function create()
    {
        // Last registration by this user (can be used to prefill some fields if needed)
        $existing = FestiveCampRegistration::where('user_id', auth()->id())
            ->latest()
            ->first();

        return view('festive-camp.register', compact('existing'));
    }

    /**
     * Store a new festive camp registration
     * (allow multiple kids per parent account)
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'player_name'       => 'required|string|max:255',
            'age'               => 'nullable|integer|min:4|max:18',
            'category'          => 'nullable|string|max:50',
            'school'            => 'nullable|string|max:255',
            'location'          => 'nullable|string|max:255',
            'guardian_name'     => 'required|string|max:255',
            'guardian_phone'    => 'required|string|max:50',
            'guardian_email'    => 'nullable|email|max:255',
            'payment_phone'     => 'nullable|string|max:50',
            'payment_reference' => 'nullable|string|max:255',
            'notes'             => 'nullable|string',
        ]);

        $data['user_id']            = auth()->id();
        $data['payment_method']     = 'MoMo';
        $data['status']             = 'pending';
        $data['verification_token'] = Str::uuid()->toString();   // ✅ important for QR

        FestiveCampRegistration::create($data);

        return redirect()
            ->route('festive-camp.my')
            ->with('success', 'Registration submitted! Please pay 50,000 RWF to MoMo 07885448596. We will approve your spot after confirming payment.');
    }

    /**
     * Parent view of their festive camp registrations (multiple kids)
     */
    public function my()
    {
        $registrations = FestiveCampRegistration::where('user_id', auth()->id())
            ->orderBy('created_at', 'desc')
            ->get();

        return view('festive-camp.my', compact('registrations'));
    }

    /**
     * Printable receipt for one registration
     * (parent must own it OR be admin)
     */
    public function receipt(FestiveCampRegistration $registration)
    {
        $user = auth()->user();
        if (! $user) {
            abort(403);
        }

        $isAdmin = $user->is_admin ?? false;

        if (! $isAdmin && $registration->user_id !== $user->id) {
            abort(403);
        }

        // Fixed festive camp fee
        $campFee = 50000;

        return view('festive-camp.receipt', [
            'registration' => $registration,
            'campFee'      => $campFee,
        ]);
    }

    /**
     * QR PNG used inside the receipt
     * (public – so staff can scan printed receipts)
     */
    public function qr(FestiveCampRegistration $registration)
    {
        // Safety: ensure the library is available
        if (! class_exists(\SimpleSoftwareIO\QrCode\Generator::class)) {
            abort(500, 'QR library not installed. Run: composer require simplesoftwareio/simple-qrcode "^4.4"');
        }

        // Ensure we have a verification token
        if (empty($registration->verification_token)) {
            $registration->verification_token = Str::uuid()->toString();
            $registration->save();
        }

        // URL encoded inside the QR
        $verifyUrl = route('festive-camp.verify', $registration->verification_token);

        $png = QrCode::format('png')
            ->size(320)
            ->margin(1)
            ->generate($verifyUrl);

        return response($png)->header('Content-Type', 'image/png');
    }

    /**
     * Page shown after scanning QR
     */
    public function verify(string $token)
    {
        $registration = FestiveCampRegistration::where('verification_token', $token)->firstOrFail();

        return view('festive-camp.verify', compact('registration'));
    }

    /**
     * ADMIN: list all festive camp registrations + capacity / remaining + income
     */
    public function index()
    {
        // Capacity + basic counts
        $capacity   = 100;
        $registered = FestiveCampRegistration::count();
        $approved   = FestiveCampRegistration::where('status', 'approved')->count();
        $pending    = FestiveCampRegistration::where('status', 'pending')->count();
        $other      = FestiveCampRegistration::whereNotIn('status', ['approved', 'pending'])->count();
        $remaining  = max($capacity - $registered, 0);

        // Money (fixed fee per kid)
        $campFee       = 50000; // RWF per child
        $expectedTotal = $registered * $campFee;                 // all registered
        $paidAmount    = $approved   * $campFee;                 // approved = paid
        $unpaidAmount  = ($registered - $approved) * $campFee;   // pending + other

        // List of campers for the table
        $campers = FestiveCampRegistration::with('user')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('admin.festive-camp.index', compact(
            'campers',
            'capacity',
            'registered',
            'remaining',
            'approved',
            'pending',
            'other',
            'campFee',
            'expectedTotal',
            'paidAmount',
            'unpaidAmount'
        ));
    }

    /**
     * ADMIN: approve a registration (marks as PAID in receipt)
     */
    public function approve(FestiveCampRegistration $registration, Request $request)
    {
        $registration->status = 'approved';
        $registration->save();

        return back()->with(
            'success',
            'Registration for '.$registration->player_name.' has been approved. The parent can now download the receipt.'
        );
    }
}
