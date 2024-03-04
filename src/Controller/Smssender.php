<?php

namespace App\Service;

use Twilio\Rest\Client;

class SmsSender {
    private $accountSid;
    private $authToken;

    public function __construct(string $accountSid, string $authToken)
    {
        $this->accountSid = $accountSid;
        $this->authToken = $authToken;
    }

    public function sendSms(string $to, string $message)
    {
        $client = new Client($this->accountSid, $this->authToken);

        try {
            // Use the Twilio SDK to send the SMS
            $client->messages->create(
                $to,
                [
                    'from' => '14437753032', // Your Twilio phone number
                    'body' => $message
                ]
            );

            // SMS sent successfully
            return true;
        } catch (\Exception $e) {
            // Failed to send SMS
            return false;
        }
    }
}
