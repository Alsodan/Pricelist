<?php
return [
    'roleSiteEditor' => [
        'type' => 1,
        'description' => 'Редактор сайта',
        'children' => [
            'permPageEdit',
        ],
    ],
    'roleUser' => [
        'type' => 1,
        'description' => 'Менеджер',
        'children' => [
            'permPriceEdit',
        ],
    ],
    'roleEditor' => [
        'type' => 1,
        'description' => 'Руководитель',
        'children' => [
            'permGroupEdit',
            'roleUser',
        ],
    ],
    'roleAdmin' => [
        'type' => 1,
        'description' => 'Администратор',
        'children' => [
            'permAdmininstration',
            'roleEditor',
            'roleSiteEditor',
        ],
    ],
    'permPageEdit' => [
        'type' => 2,
        'description' => 'Редактировать страницы',
    ],
    'permPriceEdit' => [
        'type' => 2,
        'description' => 'Редактировать цены',
    ],
    'permGroupEdit' => [
        'type' => 2,
        'description' => 'Редактировать группы',
    ],
    'permAdmininstration' => [
        'type' => 2,
        'description' => 'Администрирование',
    ],
];
