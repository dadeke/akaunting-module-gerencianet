<?php

namespace Modules\Gerencianet\Http\Controllers;

use App\Abstracts\Http\Controller;
use App\Traits\DateTime as TraitDateTime;
use Modules\Gerencianet\Models\Transaction;
use Modules\Gerencianet\Traits\Gerencianet;

class Transactions extends Controller
{
    use TraitDateTime, Gerencianet;

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
            ->collect(['document.issued_at' => 'desc']);

        // Eager loading
        $transactions->load(
            'document.contact',
            'document.items',
            'document.transactions'
        );

        return $this->response('gerencianet::admin.index', [
            'transactions' => $transactions,
            'emptyPageButtons' => $emptyPageButtons,
            'certExpiry' => $this->getCertExpiry()
        ]);
    }
}
