<?php

return [
    'settings' => [
        'sms_misr' => [
            'title' => 'SMS Misr Integration',
            'description' => 'Configure your SMS Misr settings.',
            'username' => 'Account Username',
            'password' => 'Account Password',
            'sender' => 'Sender ID',
            'sender_help' => 'The sender name approved on your SMS Misr account.',
            'environment' => 'Environment',
            'environments' => [
                'live' => 'Live',
                'test' => 'Test',
            ],
            'active' => 'SMS Misr Notifications Active',
            'keep_secret' => 'Leave it empty to keep the saved value.',
        ],
    ],
];
