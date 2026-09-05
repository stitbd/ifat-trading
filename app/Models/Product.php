<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'product_code',
        'name',
        'wing_id',
        'categories_id',
        'sub_categories_id',
        'brand_id',
        'manufacturer_id',
        'country_of_origin_id',
        'product_type_id',
        'vehicle_type_id',
        'product_size_id',
        'warranty_period_id',
        'vat_percentage_id',
        'position',
        'unit_of_measurement',
        'image',
        'min_alert_stock',
        'hs_code',
        'product_size',
        'status',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class, 'categories_id');
    }

    public function subCategory()
    {
        return $this->belongsTo(Subcategory::class, 'sub_categories_id');
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class, 'brand_id');
    }

    public function manufacturer()
    {
        return $this->belongsTo(Manufacturer::class, 'manufacturer_id');
    }

    public function countryOfOrigin()
    {
        return $this->belongsTo(CountryOfOrigin::class, 'country_of_origin_id');
    }

    public function productType()
    {
        return $this->belongsTo(ProductType::class, 'product_type_id');
    }

    public function vehicleType()
    {
        return $this->belongsTo(VehicleType::class, 'vehicle_type_id');
    }

    public function productSize()
    {
        return $this->belongsTo(ProductSize::class, 'product_size_id');
    }

    public function warrantyPeriod()
    {
        return $this->belongsTo(WarrantyPeriod::class, 'warranty_period_id');
    }

    public function vatPercentage()
    {
        return $this->belongsTo(VatPercentage::class, 'vat_percentage_id');
    }
        public function wing()
    {
        return $this->belongsTo(Wing::class, 'wing_id');
    }
}