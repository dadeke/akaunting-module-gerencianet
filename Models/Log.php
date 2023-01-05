<?php

namespace Modules\Gerencianet\Models;

use App\Abstracts\Model;

class Log extends Model
{
    protected $table = 'gerencianet_logs';

    protected $appends = ['status_label'];

    protected $fillable = [
        'company_id',
        'document_id',
        'action',
        'error',
        'message'
    ];

    public $sortable = [
        'action',
        'error',
        'created_at'
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
        return match($this->error) {
            0 => 'status-success',
            1 => 'status-danger',
            default => 'status-danger'
        };
    }
}
