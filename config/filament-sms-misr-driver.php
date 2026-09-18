<?php

return [
    /**
     * ---------------------------------------
     * SMS Misr Account Username
     * ---------------------------------------
     */
    'username' => env('SMS_MISR_USERNAME'),

    /**
     * ---------------------------------------
     * SMS Misr Account Password
     * ---------------------------------------
     */
    'password' => env('SMS_MISR_PASSWORD'),

    /**
     * ---------------------------------------
     * SMS Misr Approved Sender ID
     * ---------------------------------------
     */
    'sender' => env('SMS_MISR_SENDER'),

    /**
     * ---------------------------------------
     * Environment, `live` really sends, `test` only validates
     * ---------------------------------------
     */
    'environment' => env('SMS_MISR_ENVIRONMENT', 'live'),

    /**
     * ---------------------------------------
     * Message Language, 1 English, 2 Arabic, 3 Unicode
     * ---------------------------------------
     */
    'language' => env('SMS_MISR_LANGUAGE', '1'),

    /**
     * ---------------------------------------
     * Allow Sending SMS Misr Messages
     * ---------------------------------------
     */
    'active' => env('SMS_MISR_ACTIVE', true),

    /**
     * ---------------------------------------
     * SMS Misr API v2 Endpoint
     * ---------------------------------------
     */
    'endpoint' => env('SMS_MISR_ENDPOINT', 'https://smsmisr.com/api/SMS/'),

    /**
     * ---------------------------------------
     * The Notifiable Column Holding The Phone Number
     * ---------------------------------------
     */
    'phone_column' => env('SMS_MISR_PHONE_COLUMN', 'phone'),
];
