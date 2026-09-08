<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ComparativeStatementDetail extends Model
{
    protected $guarded = [];

    public function comparativeStatement(): BelongsTo
    {
        // Fix: Use 'cs_id' instead of 'comparative_statement_id'
        return $this->belongsTo(ComparativeStatement::class, 'cs_id');
    }

    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(ComparativeStatementItem::class, 'cs_detail_id');
    }
}