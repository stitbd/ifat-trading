<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Requisition extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    public function forwardedBy()
    {
        return $this->belongsTo(User::class, 'forwarded_by');
    }

    public function sciApprovedBy()
    {
        return $this->belongsTo(User::class, 'sci_approved_by');
    }
    public function sciRejectedBy()
    {
        return $this->belongsTo(User::class, 'sci_rejected_by');
    }

    public function omApprovedBy()
    {
        return $this->belongsTo(User::class, 'om_approved_by');
    }
    public function omRejectedBy()
    {
        return $this->belongsTo(User::class, 'om_rejected_by');
    }

    public function mdApprovedBy()
    {
        return $this->belongsTo(User::class, 'md_approved_by');
    }
    public function mdRejectedBy()
    {
        return $this->belongsTo(User::class, 'md_rejected_by');
    }

    public function csGeneratedBy()
    {
        return $this->belongsTo(User::class, 'cs_generated_by');
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($requisition) {
            $requisition->requisition_no = self::generateRequisitionNo();
        });
    }

    /**
     * Generate Auto Requisition No (REQ-0001, REQ-0002 ...)
     */
    public static function generateRequisitionNo(): string
    {
        $last = self::withTrashed()
            ->orderBy('id', 'desc')
            ->first();

        $nextNumber = $last ? ((int) substr($last->requisition_no, 4)) + 1 : 1;

        return 'REQ-' . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);
    }

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function wing()
    {
        return $this->belongsTo(Wing::class);
    }

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class);
    }

    public function details()
    {
        return $this->hasMany(RequisitionDetail::class);
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}
