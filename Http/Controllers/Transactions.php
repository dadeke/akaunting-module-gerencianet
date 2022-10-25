<?php

namespace Modules\Gerencianet\Http\Controllers;

use App\Abstracts\Http\Controller;
use Modules\Gerencianet\Models\Transaction;

class Transactions extends Controller
{
    public function __construct()
    {
        // This is to clear the middlewares.
    }

    /**
     * Index
     */
    public function index()
    {
        $emptyPageButtons = [
            [
                'url' => route('invoices.create'),
                'permission' => 'create-sales-invoices',
                'text' => trans(
                    'general.title.new',
                    ['type' => trans('gerencianet::general.create_name')]
                ),
                'description' => trans(
                    'general.empty.actions.new',
                    ['type' => trans('gerencianet::general.create_name')]
                ),
                'active_badge' => true
            ]
        ];

        $statuses = [
            'draft',
            'sent',
            'viewed',
            'partial',
            'paid'
        ];

        $transactions = Transaction::select(
                'id',
                'document_id'
            )
            ->whereHas('document',
                fn($query) => $query->select('id')
                    ->whereIn('status', $statuses)
            )
            ->collect(['document_id'=> 'desc']);

        // Eager loading
        $transactions->load(
            'document.contact',
            'document.items',
            'document.transactions'
        );

        return $this->response('gerencianet::index', [
            'transactions' => $transactions,
            'emptyPageButtons' => $emptyPageButtons
        ]);
    }
}
