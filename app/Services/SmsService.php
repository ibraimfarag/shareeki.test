<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class SmsService
{
    protected string $clientId = 'db063035104d84fa2997e7bb57b98cad';
    protected string $clientSecret = '62bb979cd02dc6ced1b8c25b02d2e878101ab9d8b9f3cfddafbb33b0706c00a5';
    protected string $username = 'Shareeki2030';
    protected array $senders = [
        'default' => 'Shareeki',
        'ads' => 'Shareeki-AD',
    ];
    protected string $apiUrl = 'https://www.dreams.sa/index.php/api/sendsms';

    /**
     * إرسال رسالة SMS
     * @param string $to رقم الجوال
     * @param string $message نص الرسالة
     * @param string $type نوع الرسالة: 'default' أو 'ads'
     * @return array نتيجة الإرسال
     */
    public function send(string $to, string $message, string $type = 'default'): array
    {
        $sender = $this->senders[$type] ?? $this->senders['default'];
        $payload = [
            'user' => $this->username,
            'secret_key' => $this->clientSecret,
            'sender' => $sender,
            'to' => $to,
            'message' => $message,
        ];

        $response = Http::asForm()->post($this->apiUrl, $payload);

        return [
            'success' => $response->successful(),
            'status' => $response->status(),
            'body' => $response->json(),
        ];
    }
}
