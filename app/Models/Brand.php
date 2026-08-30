<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\User;

class Brand extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'image',
        'description',
        'status',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}