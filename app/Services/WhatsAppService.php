<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;

class WhatsAppService
{
    /**
     * Send a WhatsApp message.
     * Currently implemented as a dummy service logging the message.
     * Replace with actual API call (e.g. Fonnte, Twilio, Wablas) later.
     *
     * @param string $phoneNumber The recipient's phone number
     * @param string $message The message text
     */
    public function sendMessage(string $phoneNumber, string $message): void
    {
        $token = env('FONNTE_TOKEN');
        if (!$token) {
            Log::warning("Fonnte token is missing. Message not sent to {$phoneNumber}.");
            return;
        }

        $curl = curl_init();
        curl_setopt_array($curl, array(
          CURLOPT_URL => 'https://api.fonnte.com/send',
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => '',
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 0,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => 'POST',
          CURLOPT_POSTFIELDS => array(
            'target' => $phoneNumber,
            'message' => $message,
          ),
          CURLOPT_HTTPHEADER => array(
            'Authorization: ' . $token
          ),
        ));
        
        $response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);
        
        if ($err) {
            Log::error("Fonnte Error: " . $err);
        } else {
            Log::info("Fonnte Response: " . $response);
        }
    }
}
