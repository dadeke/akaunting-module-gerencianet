<?php

return [
    'Modules\Gerencianet\Models\Transaction' => [
        'columns' => [
            'id',
            'document' => [
                'relationship' => true,
                'route' => 'invoices.index'
            ]
        ]
    ]
];
