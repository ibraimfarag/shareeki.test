<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SmsService
{
    protected bool $enabled;
    protected string $clientId;
    protected string $clientSecret;
    protected string $username;
    protected array $senders;
    protected string $apiUrl;

    public function __construct()
    {
        $config = config('services.sms');
        $this->enabled = $config['enabled'];
        $this->clientId = $config['client_id'];
        $this->clientSecret = $config['client_secret'];
        $this->username = $config['username'];
        $this->senders = $config['senders'];
        $this->apiUrl = $config['api_url'];
    }

    /**
     * إرسال رسالة SMS
     * @param string $to رقم الجوال
     * @param string $message نص الرسالة
     * @param string $type نوع الرسالة: 'default' أو 'ads'
     * @return array نتيجة الإرسال
     */
    public function send(string $to, string $message, string $type = 'default'): array
    {
        // إذا كان SMS غير مُفعل، نرجع نتيجة وهمية بنجاح
        if (!$this->enabled) {
            Log::info('SMS مُعطل - إرسال وهمي', [
                'to' => $to,
                'message' => $message,
                'type' => $type,
                'enabled' => $this->enabled
            ]);

            return [
                'success' => true,
                'status' => 200,
                'body' => [
                    'message' => 'تم الإرسال بنجاح (وضع الاختبار)',
                    'fake_mode' => true
                ],
            ];
        }

        // إرسال حقيقي
        $sender = $this->senders[$type] ?? $this->senders['default'];
        $payload = [
            'user' => $this->username,
            'secret_key' => $this->clientSecret,
            'sender' => $sender,
            'to' => $to,
            'message' => $message,
        ];

        Log::info('SMS مُفعل - إرسال حقيقي', [
            'to' => $to,
            'sender' => $sender,
            'enabled' => $this->enabled
        ]);

        $response = Http::asForm()->post($this->apiUrl, $payload);

        return [
            'success' => $response->successful(),
            'status' => $response->status(),
            'body' => $response->json(),
        ];
    }

    /**
     * التحقق من حالة تفعيل SMS
     */
    public function isEnabled(): bool
    {
        return $this->enabled;
    }
}
