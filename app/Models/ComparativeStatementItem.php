<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ComparativeStatementItem extends Model
{
    protected $guarded = [];

    public function comparativeStatementDetail(): BelongsTo
    {
        return $this->belongsTo(ComparativeStatementDetail::class, 'cs_detail_id');
    }

    public function requisitionDetail(): BelongsTo
    {
        return $this->belongsTo(RequisitionDetail::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}