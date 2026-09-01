<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Requisition extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'contact_person_info',
        'requisition_no',
        'wing_id',
        'warehouse_id',
        'requisition_type',
        'total_quantity',
        'date',
        'note',
        'place_of_supply',
        'status',
        'created_by',
        'updated_by',
        'deleted_by',
    ];



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
