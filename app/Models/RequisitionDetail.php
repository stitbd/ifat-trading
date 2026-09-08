<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RequisitionDetail extends Model
{
    protected $guarded = [];
    public function requisition()
    {
        return $this->belongsTo(Requisition::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function comparativeStatementDetails()
    {
        return $this->hasMany(\App\Models\ComparativeStatementDetail::class);
    }

    public function getCsCreatedQtyAttribute(): float
    {
        return (float) $this->comparativeStatementDetails()->sum('cs_qty');
    }
}