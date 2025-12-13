<?php

namespace App\Http\Controllers;

use App\Models\FestiveCampRegistration;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use App\Services\WhatsAppService;

class FestiveCampController extends Controller
{
    /**
     * WhatsApp service for notifications
     */
    protected WhatsAppService $whatsApp;

    public function __construct(WhatsAppService $whatsApp)
    {
        $this->whatsApp = $whatsApp;
    }

    /**
     * Small helper: parent must own the registration OR be admin.
     */
    protected function authorizeOwnerOrAdmin(FestiveCampRegistration $registration): array
    {
        $user = auth()->user();
        if (! $user) {
            abort(403);
        }

        $isAdmin = (bool) ($user->is_admin ?? false);

        if (! $isAdmin && (int) $registration->user_id !== (int) $user->id) {
            abort(403);
        }

        return [$user, $isAdmin];
    }

    /**
     * Small helper: admin only.
     */
    protected function authorizeAdmin(): void
    {
        $user = auth()->user();
        $isAdmin = (bool) ($user->is_admin ?? false);

        if (! $user || ! $isAdmin) {
            abort(403);
        }
    }

    /**
     * Show registration form (for logged-in parent)
     */
    public function create()
    {
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
        $data['verification_token'] = Str::uuid()->toString(); // important for QR

        // Save registration
        $registration = FestiveCampRegistration::create($data);

        // WhatsApp confirmation to parent
        $this->whatsApp->sendRegistrationConfirmation($registration);

        return redirect()
            ->route('festive-camp.my')
            ->with('success', 'Registration submitted! Please pay 50,000 RWF to MoMo 0788448596. We will approve your spot after confirming payment.');
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
     * Parent: edit a registration (only if pending)
     */
    public function edit(FestiveCampRegistration $registration)
    {
        [$user, $isAdmin] = $this->authorizeOwnerOrAdmin($registration);

        // Keep integrity: do not allow edits after approval (unless you want admin to edit later)
        if (! $isAdmin && $registration->status === 'approved') {
            return redirect()
                ->route('festive-camp.my')
                ->with('error', 'This registration is already approved and cannot be edited.');
        }

        return view('festive-camp.edit', compact('registration'));
    }

    /**
     * Parent: update a registration (only if pending)
     */
    public function update(Request $request, FestiveCampRegistration $registration)
    {
        [$user, $isAdmin] = $this->authorizeOwnerOrAdmin($registration);

        if (! $isAdmin && $registration->status === 'approved') {
            return redirect()
                ->route('festive-camp.my')
                ->with('error', 'This registration is already approved and cannot be edited.');
        }

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

        // Preserve these server-controlled fields:
        unset($data['user_id'], $data['status'], $data['payment_method'], $data['verification_token']);

        $registration->update($data);

        return redirect()
            ->route('festive-camp.my')
            ->with('success', 'Registration updated successfully.');
    }

    /**
     * Parent: delete a registration (only if pending)
     */
    public function destroy(FestiveCampRegistration $registration)
    {
        [$user, $isAdmin] = $this->authorizeOwnerOrAdmin($registration);

        if (! $isAdmin && $registration->status === 'approved') {
            return redirect()
                ->route('festive-camp.my')
                ->with('error', 'Approved registrations cannot be deleted.');
        }

        $player = $registration->player_name;

        $registration->delete();

        return redirect()
            ->route('festive-camp.my')
            ->with('success', 'Registration for ' . $player . ' has been deleted.');
    }

    /**
     * Printable receipt for one registration
     * (parent must own it OR be admin)
     */
    public function receipt(FestiveCampRegistration $registration)
    {
        [$user, $isAdmin] = $this->authorizeOwnerOrAdmin($registration);

        // Default festive camp fee (used if amount_paid is empty)
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
        if (! class_exists(\SimpleSoftwareIO\QrCode\Generator::class)) {
            abort(500, 'QR library not installed. Run: composer require simplesoftwareio/simple-qrcode "^4.4"');
        }

        if (empty($registration->verification_token)) {
            $registration->verification_token = Str::uuid()->toString();
            $registration->save();
        }

        // absolute URL (better for production / https)
        $verifyUrl = url()->route('festive-camp.verify', $registration->verification_token, true);

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

        // Default fee per kid
        $campFee = 50000;

        // Expected total = all registered * default fee
        $expectedTotal = $registered * $campFee;

        // Paid amount = SUM(amount_paid) for approved; fallback to approved*fee
        $paidAmount = (int) FestiveCampRegistration::where('status', 'approved')->sum('amount_paid');
        if ($paidAmount <= 0) {
            $paidAmount = $approved * $campFee;
        }

        $unpaidAmount = max($expectedTotal - $paidAmount, 0);

        // List of campers
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
     * ✅ ADMIN: update amount paid (editable) without approving
     * Route name: admin.festive-camp.amount
     * Method: PATCH
     */
    public function updateAmount(Request $request, FestiveCampRegistration $registration)
    {
        $this->authorizeAdmin();

        $data = $request->validate([
            'amount_paid' => 'nullable|integer|min:0',
        ]);

        // If blank, keep null (or keep existing if you prefer)
        // Here: blank sets null
        $registration->amount_paid = $data['amount_paid'] ?? null;
        $registration->save();

        return back()->with('success', 'Amount paid updated for ' . $registration->player_name . '.');
    }

    /**
     * ADMIN: approve a registration (marks as PAID in receipt)
     * - amount_paid is editable (admin only)
     */
    public function approve(FestiveCampRegistration $registration, Request $request)
    {
        $this->authorizeAdmin();

        $defaultFee = 50000;

        // Admin can set amount_paid, otherwise default
        $amountPaid = $request->input('amount_paid');

        if ($amountPaid === null || $amountPaid === '') {
            $amountPaid = $defaultFee;
        } else {
            $amountPaid = (int) preg_replace('/[^0-9]/', '', (string) $amountPaid);
            if ($amountPaid <= 0) {
                $amountPaid = $defaultFee;
            }
        }

        $registration->status = 'approved';
        $registration->amount_paid = $amountPaid;

        if (empty($registration->verification_token)) {
            $registration->verification_token = Str::uuid()->toString();
        }

        $registration->save();

        // WhatsApp receipt link to parent
        $this->whatsApp->sendApprovalReceipt($registration);

        return back()->with(
            'success',
            'Registration for '.$registration->player_name.' has been approved. The parent can now download the receipt.'
        );
    }
}
