<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\FestiveCampRegistration;

class EnsureCampApproved
{
    public function handle(Request $request, Closure $next)
    {
        $user = $request->user();

        // Admins bypass the restriction
        if ($user && ($user->is_admin ?? false)) {
            return $next($request);
        }

        if (!$user) {
            return redirect()->route('login');
        }

        $registration = FestiveCampRegistration::where('user_id', $user->id)->first();

        // No registration or not approved → redirect to camp pages
        if (!$registration || $registration->status !== 'approved') {
            return redirect()
                ->route('festive-camp.my')
                ->with('info', 'You can access the full portal after your Festive Camp registration is approved.');
        }

        return $next($request);
    }
}
