<?php

return [
    'components' => [
        'db' => [
            'dsn' => 'mysql:host=localhost;dbname=pricekzp',
            'username' => 'root',
            'password' => '',
            'tablePrefix' => ''
        ],
        'mailer' => [
            'useFileTransport' => true,
        ],
    ],
];
