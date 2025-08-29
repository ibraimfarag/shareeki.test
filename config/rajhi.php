<?php

return [
    'terminal_id' => 'PG511000',
    'terminal_name' => 'Shareeki',
    'merchant_id' => '600000356',
    'terminal_alias' => 'PG511000',
    'tranportal_id' => '2iZubJ0EJ9l00Ko',
    'tranportal_password' => 'kf6CJ@R12@V7f!i',
    'resource_key' => '20204458918420204458918420204458',

    'currency' => 'SAR',
    'language' => 'AR',

    // عنوان الدفع المستضاف
    'payment_url' => 'https://digitalpayments.alrajhibank.com.sa/pg/payment/hosted.htm',

    // عنوان API للدفع المباشر
    'api_url' => 'https://digitalpayments.alrajhibank.com.sa/pg/api',

    // المسارات
    'success_url' => '/payments/rajhi/success',
    'error_url' => '/payments/rajhi/error',
    'webhook_url' => '/payments/rajhi/webhook',

    // إعدادات البيئة
    'environment' => env('RAJHI_ENVIRONMENT', 'test'), // 'test' or 'production'
    'test_cards' => [
        [
            'number' => '4111111111111111',
            'expiry' => '12/25',
            'cvv' => '123',
            'holder' => 'Test Card'
        ]
    ]
];
