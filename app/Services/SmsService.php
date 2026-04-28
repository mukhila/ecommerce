<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SmsService
{
    /**
     * Send an SMS message. Supported providers: fast2sms, msg91.
     * Set SMS_PROVIDER and the matching credentials in .env.
     * In local/testing environments, OTPs are only logged (no real SMS sent).
     */
    public static function send(string $phone, string $message): bool
    {
        $provider = config('services.sms.provider');

        // Never send real SMS in local/testing — just log the message
        if (app()->environment(['local', 'testing'])) {
            Log::info("[SMS-DEV] To: {$phone} | Message: {$message}");
            return true;
        }

        if (!$provider) {
            Log::error('[SMS] No SMS_PROVIDER configured. OTP not delivered.', ['phone' => $phone]);
            return false;
        }

        return match ($provider) {
            'fast2sms' => self::sendFast2Sms($phone, $message),
            'msg91'    => self::sendMsg91($phone, $message),
            default    => self::unsupportedProvider($provider, $phone),
        };
    }

    private static function sendFast2Sms(string $phone, string $message): bool
    {
        $apiKey = config('services.sms.fast2sms_api_key');

        if (!$apiKey) {
            Log::error('[SMS] FAST2SMS_API_KEY not configured.');
            return false;
        }

        // Extract 6-digit OTP from message for Fast2SMS OTP route
        preg_match('/\b(\d{6})\b/', $message, $matches);
        $otp = $matches[1] ?? null;

        try {
            $response = Http::withHeaders(['authorization' => $apiKey])
                ->get('https://www.fast2sms.com/dev/bulkV2', [
                    'route'            => 'otp',
                    'variables_values' => $otp ?? $message,
                    'flash'            => 0,
                    'numbers'          => $phone,
                ]);

            $body = $response->json();

            if ($response->successful() && ($body['return'] ?? false)) {
                Log::info('[SMS] Fast2SMS delivered.', ['phone' => $phone]);
                return true;
            }

            Log::error('[SMS] Fast2SMS delivery failed.', [
                'phone'    => $phone,
                'response' => $body,
            ]);
        } catch (\Exception $e) {
            Log::error('[SMS] Fast2SMS exception.', ['phone' => $phone, 'error' => $e->getMessage()]);
        }

        return false;
    }

    private static function sendMsg91(string $phone, string $message): bool
    {
        $authKey    = config('services.sms.msg91_auth_key');
        $templateId = config('services.sms.msg91_template_id');
        $senderId   = config('services.sms.msg91_sender_id', 'JNGKDS');

        if (!$authKey || !$templateId) {
            Log::error('[SMS] MSG91_AUTH_KEY or MSG91_TEMPLATE_ID not configured.');
            return false;
        }

        preg_match('/\b(\d{6})\b/', $message, $matches);
        $otp = $matches[1] ?? null;

        try {
            $response = Http::withHeaders([
                'authkey'      => $authKey,
                'content-type' => 'application/json',
            ])->post('https://api.msg91.com/api/v5/otp', [
                'template_id' => $templateId,
                'mobile'      => '91' . $phone,
                'otp'         => $otp,
                'sender'      => $senderId,
            ]);

            $body = $response->json();

            if ($response->successful() && ($body['type'] ?? '') === 'success') {
                Log::info('[SMS] MSG91 delivered.', ['phone' => $phone]);
                return true;
            }

            Log::error('[SMS] MSG91 delivery failed.', ['phone' => $phone, 'response' => $body]);
        } catch (\Exception $e) {
            Log::error('[SMS] MSG91 exception.', ['phone' => $phone, 'error' => $e->getMessage()]);
        }

        return false;
    }

    private static function unsupportedProvider(string $provider, string $phone): bool
    {
        Log::error("[SMS] Unknown SMS_PROVIDER: {$provider}.", ['phone' => $phone]);
        return false;
    }
}
