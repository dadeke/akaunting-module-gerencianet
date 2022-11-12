<?php

namespace Modules\Gerencianet\Models;

use App\Abstracts\Model;

class Transaction extends Model
{
    protected $table = 'gerencianet_transactions';

    protected $appends = ['status_label'];

    protected $fillable = [
        'company_id',
        'document_id',
        'location_id',
        'txid'
    ];

    public $sortable = [
        'document_id',
        'document.due_at',
        'document.issued_at',
        'document.status',
        'document.contact_name',
        'document.document_number',
        'document.amount'
    ];

    public function document()
    {
        return $this->belongsTo('App\Models\Document\Document');
    }

    /**
     * Get the status label.
     *
     * @return string
     */
    public function getStatusLabelAttribute()
    {
        return match($this->status) {
            'paid'      => 'status-success',
            'partial'   => 'status-partial',
            'sent'      => 'status-danger',
            'viewed'    => 'status-sent',
            default     => 'status-draft',
        };
    }

    /**
     * Get the line actions.
     *
     * @return array
     */
    public function getLineActionsAttribute()
    {
        $actions = [];

        $group = config('type.document.invoice.group');
        $prefix = config('type.document.invoice.route.prefix');
        $permission_prefix = config('type.document.invoice.permission.prefix');

        $actions[] = [
            'title' => trans('general.show'),
            'icon' => 'visibility',
            'url' => route('preview.invoices.show', [$this->document_id]),
            'permission' => 'read-' . $group . '-' . $permission_prefix,
            'attributes' => [
                'target' => '_blank',
                'onclick' => 'event.cancelBubble=true;'
            ],
        ];

        if($this->document->status != 'paid') {
            $actions[] = [
                'title' => trans('general.edit'),
                'icon' => 'edit',
                'url' => route($prefix . '.edit', $this->document_id),
                'permission' => 'update-' . $group . '-' . $permission_prefix,
                'attributes' => [
                    'onclick' => 'event.cancelBubble=true;'
                ],
            ];
        }

        return $actions;
    }
}
