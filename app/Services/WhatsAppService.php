<?php

namespace App\Services;

use App\Models\FestiveCampRegistration;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WhatsAppService
{
    public function sendRegistrationConfirmation(FestiveCampRegistration $registration): void
    {
        $phone = $this->normalizePhone(
            $registration->guardian_phone ?? $registration->payment_phone
        );

        if (! $phone || ! $this->isEnabled()) {
            return;
        }

        $message = sprintf(
            "Hello %s,\n\nYour child %s has been registered for the Shoot For The Stars Festive Camp.\n\nStatus: %s\nFee: 50,000 RWF\nVenue: Green Hills Academy Indoor Gymnasium.\n\nYou will receive a confirmation once payment is approved.",
            $registration->guardian_name,
            $registration->player_name,
            ucfirst($registration->status ?? 'pending')
        );

        $this->sendText($phone, $message);
    }

    public function sendApprovalReceipt(FestiveCampRegistration $registration): void
    {
        $phone = $this->normalizePhone(
            $registration->guardian_phone ?? $registration->payment_phone
        );

        if (! $phone || ! $this->isEnabled()) {
            return;
        }

        // Link to the receipt page
        $receiptUrl = route('festive-camp.receipt', $registration);

        $message = sprintf(
            "Hello %s,\n\nPayment for %s has been approved.\n\nTotal paid: 50,000 RWF\nYou can view and present the official receipt here:\n%s",
            $registration->guardian_name,
            $registration->player_name,
            $receiptUrl
        );

        $this->sendText($phone, $message);
    }

    /* ----------------------------------
     *  Internal helpers
     * ---------------------------------*/

    protected function isEnabled(): bool
    {
        return (bool) config('services.whatsapp.enabled')
            && config('services.whatsapp.access_token')
            && config('services.whatsapp.phone_number_id');
    }

    protected function sendText(string $phone, string $message): void
    {
        try {
            $token = config('services.whatsapp.access_token');
            $phoneNumberId = config('services.whatsapp.phone_number_id');

            if (! $token || ! $phoneNumberId) {
                return;
            }

            Http::withToken($token)
                ->post("https://graph.facebook.com/v19.0/{$phoneNumberId}/messages", [
                    'messaging_product' => 'whatsapp',
                    'to'                => $phone,
                    'type'              => 'text',
                    'text'              => [
                        'body' => $message,
                    ],
                ]);
        } catch (\Throwable $e) {
            Log::error('WhatsApp send error: '.$e->getMessage(), [
                'phone'   => $phone,
                'message' => $message,
            ]);
        }
    }

    /**
     * Normalize Rwandan numbers like 0788xxxxxx → 250788xxxxxx
     */
    protected function normalizePhone(?string $phone): ?string
    {
        if (! $phone) {
            return null;
        }

        $digits = preg_replace('/\D+/', '', $phone);

        // Already in international format
        if (str_starts_with($digits, '250') && strlen($digits) >= 12) {
            return $digits;
        }

        // Local MTN style 07xxxxxxxx → 2507xxxxxxxx
        if (str_starts_with($digits, '07')) {
            return '250' . substr($digits, 1);
        }

        // Fallback: return raw digits
        return $digits;
    }
}
