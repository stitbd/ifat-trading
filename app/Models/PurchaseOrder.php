<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PurchaseOrder extends Model
{
    use SoftDeletes;

    protected $guarded = [];

    protected $casts = [
        'po_date'        => 'date',
        'time_of_supply' => 'date',
    ];

    public function requisition(): BelongsTo
    {
        return $this->belongsTo(Requisition::class);
    }

    public function comparativeStatement(): BelongsTo
    {
        return $this->belongsTo(ComparativeStatement::class, 'cs_id');
    }

    public function comparativeStatementDetail(): BelongsTo
    {
        return $this->belongsTo(ComparativeStatementDetail::class, 'cs_details_id');
    }

    public function details(): HasMany
    {
        return $this->hasMany(PurchaseOrderDetail::class, 'po_id');
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(\App\Models\User::class, 'created_by');
    }

    public static function generatePoNo(): string
    {
        $last = static::withTrashed()->orderByDesc('id')->first();
        $next = $last ? ((int) substr($last->po_no, -5)) + 1 : 1;

        return 'PO-' . date('Y') . '-' . str_pad($next, 5, '0', STR_PAD_LEFT);
    }
}
