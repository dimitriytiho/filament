<?php

return [
    'navigation' => [
        'group' => 'Логи',
        'label' => 'Логи',
    ],

    'page' => [
        'title' => 'Логи',

        'form' => [
            'placeholder' => 'Выберите лог файл...',
        ],
    ],

    'actions' => [
        'clear' => [
            'label' => 'Очистить',

            'modal' => [
                'heading' => 'Очистить логи сайта?',
                'description' => 'Вы уверены, что хотите очистить все логи сайта?',

                'actions' => [
                    'confirm' => 'Очистить',
                ],
            ],
        ],

        'jumpToStart' => [
            'label' => 'Перейти к началу',
        ],

        'jumpToEnd' => [
            'label' => 'Перейти к концу',
        ],

        'refresh' => [
            'label' => 'Обновить',
        ],
    ],
];
