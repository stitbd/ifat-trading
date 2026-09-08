<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ComparativeStatement extends Model
{
    use SoftDeletes;
    protected $guarded = [];

    public static function generateCsNo()
    {
        $year = date('Y');
        $month = date('m');

        // Fix: withTrashed() ব্যবহার করা হয়েছে যাতে soft-deleted CS গুলোও
        // numbering হিসাব করার সময় ধরা হয় — নাহলে delete করা CS এর নাম্বার
        // আবার নতুন করে assign হয়ে duplicate cs_no তৈরি করে।
        $lastCs = self::withTrashed()
            ->whereYear('cs_date', $year)
            ->whereMonth('cs_date', $month)
            ->orderBy('id', 'desc')
            ->first();

        if ($lastCs) {
            $lastNumber = intval(substr($lastCs->cs_no, -4));
            $newNumber = str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);
        } else {
            $newNumber = '0001';
        }

        return "CS-{$year}{$month}-{$newNumber}";
    }

    public function requisition(): BelongsTo
    {
        return $this->belongsTo(Requisition::class);
    }

    public function details(): HasMany
    {
        // Fix: table এর আসল foreign key column হলো 'cs_id', default 'comparative_statement_id' না
        return $this->hasMany(ComparativeStatementDetail::class, 'cs_id');
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }



    public function forwardedBy()
    {
        return $this->belongsTo(\App\Models\User::class, 'forwarded_by');
    }
    public function sciApprovedBy()
    {
        return $this->belongsTo(\App\Models\User::class, 'sci_approved_by');
    }
    public function sciRejectedBy()
    {
        return $this->belongsTo(\App\Models\User::class, 'sci_rejected_by');
    }
    public function omApprovedBy()
    {
        return $this->belongsTo(\App\Models\User::class, 'om_approved_by');
    }
    public function omRejectedBy()
    {
        return $this->belongsTo(\App\Models\User::class, 'om_rejected_by');
    }
    public function mdApprovedBy()
    {
        return $this->belongsTo(\App\Models\User::class, 'md_approved_by');
    }
    public function mdRejectedBy()
    {
        return $this->belongsTo(\App\Models\User::class, 'md_rejected_by');
    }
}
