<?php

use App\Http\Controllers\Admin\ApplicationController;
use App\Http\Controllers\Admin\BrandController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\CountryOfOriginController;
use App\Http\Controllers\Admin\LoginController;
use App\Http\Controllers\Admin\ManufacturerController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\ProductSizeController;
use App\Http\Controllers\Admin\ProductTypeController;
use App\Http\Controllers\Admin\RequisitionController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\SubcategoryController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\VatPercentageController;
use App\Http\Controllers\Admin\VehicleTypeController;
use App\Http\Controllers\Admin\WarehouseController;
use App\Http\Controllers\Admin\WarrantyPeriodController;
use App\Http\Controllers\Admin\WingController;
use Illuminate\Support\Facades\Route;

Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('login', [LoginController::class, 'login']);
Route::middleware(['auth'])->group(function () {

    Route::get('/', [\App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');
    Route::get('application', [ApplicationController::class, 'index'])->name('applications.index');
    Route::put('application/update/{id}', [ApplicationController::class, 'update'])->name('applications.update');
    Route::post('logout', [LoginController::class, 'logout'])->name('logout');


    Route::get('user', [UserController::class, 'index'])->name('user.index');
    Route::get('user/getdata', [UserController::class, 'getdata'])->name('user.getdata');
    Route::get('user/create', [UserController::class, 'create'])->name('user.create');
    Route::post('user/store', [UserController::class, 'store'])->name('user.store');
    Route::delete('user/distroy/{id}', [UserController::class, 'distroy'])->name('user.distroy');
    Route::get('user/edit/{id}', [UserController::class, 'edit'])->name('user.edit');
    Route::put('user/update/{id}', [UserController::class, 'update'])->name('user.update');



    // routes/web.php (admin group er vitore)
    Route::get('roles', [RoleController::class, 'index'])->name('role.index');
    Route::get('roles/getdata', [RoleController::class, 'getdata'])->name('role.getdata');
    Route::get('roles/create', [RoleController::class, 'create'])->name('role.create');
    Route::post('roles/store', [RoleController::class, 'store'])->name('role.store');
    Route::get('roles/edit/{id}', [RoleController::class, 'edit'])->name('role.edit');
    Route::put('roles/update/{id}', [RoleController::class, 'update'])->name('role.update');
    Route::delete('roles/distroy/{id}', [RoleController::class, 'distroy'])->name('role.distroy');

    Route::get('product-type', [ProductTypeController::class, 'index'])->name('product-type.index');
    Route::get('product-type/getdata', [ProductTypeController::class, 'getdata'])->name('product-type.getdata');
    Route::post('product-type/store', [ProductTypeController::class, 'store'])->name('product-type.store');
    Route::delete('product-type/distroy/{id}', [ProductTypeController::class, 'distroy'])->name('product-type.distroy');
    Route::get('product-type/edit/{id}', [ProductTypeController::class, 'edit'])->name('product-type.edit');
    Route::put('product-type/update/{id}', [ProductTypeController::class, 'update'])->name('product-type.update');
    Route::put('product-type/status/{id}', [ProductTypeController::class, 'statusUpdate'])->name('product-type.status');

    Route::get('manufacturer', [ManufacturerController::class, 'index'])->name('manufacturer.index');
    Route::get('manufacturer/getdata', [ManufacturerController::class, 'getdata'])->name('manufacturer.getdata');
    Route::post('manufacturer/store', [ManufacturerController::class, 'store'])->name('manufacturer.store');
    Route::delete('manufacturer/distroy/{id}', [ManufacturerController::class, 'distroy'])->name('manufacturer.distroy');
    Route::get('manufacturer/edit/{id}', [ManufacturerController::class, 'edit'])->name('manufacturer.edit');
    Route::put('manufacturer/update/{id}', [ManufacturerController::class, 'update'])->name('manufacturer.update');
    Route::put('manufacturer/status/{id}', [ManufacturerController::class, 'statusUpdate'])->name('manufacturer.status');

    Route::get('country-of-origin', [CountryOfOriginController::class, 'index'])->name('country-of-origin.index');
    Route::get('country-of-origin/getdata', [CountryOfOriginController::class, 'getdata'])->name('country-of-origin.getdata');
    Route::post('country-of-origin/store', [CountryOfOriginController::class, 'store'])->name('country-of-origin.store');
    Route::delete('country-of-origin/distroy/{id}', [CountryOfOriginController::class, 'distroy'])->name('country-of-origin.distroy');
    Route::get('country-of-origin/edit/{id}', [CountryOfOriginController::class, 'edit'])->name('country-of-origin.edit');
    Route::put('country-of-origin/update/{id}', [CountryOfOriginController::class, 'update'])->name('country-of-origin.update');
    Route::put('country-of-origin/status/{id}', [CountryOfOriginController::class, 'statusUpdate'])->name('country-of-origin.status');

    Route::get('vehicle-type', [VehicleTypeController::class, 'index'])->name('vehicle-type.index');
    Route::get('vehicle-type/getdata', [VehicleTypeController::class, 'getdata'])->name('vehicle-type.getdata');
    Route::post('vehicle-type/store', [VehicleTypeController::class, 'store'])->name('vehicle-type.store');
    Route::delete('vehicle-type/distroy/{id}', [VehicleTypeController::class, 'distroy'])->name('vehicle-type.distroy');
    Route::get('vehicle-type/edit/{id}', [VehicleTypeController::class, 'edit'])->name('vehicle-type.edit');
    Route::put('vehicle-type/update/{id}', [VehicleTypeController::class, 'update'])->name('vehicle-type.update');
    Route::put('vehicle-type/status/{id}', [VehicleTypeController::class, 'statusUpdate'])->name('vehicle-type.status');


    Route::get('product-size', [ProductSizeController::class, 'index'])->name('product-size.index');
    Route::get('product-size/getdata', [ProductSizeController::class, 'getdata'])->name('product-size.getdata');
    Route::post('product-size/store', [ProductSizeController::class, 'store'])->name('product-size.store');
    Route::delete('product-size/distroy/{id}', [ProductSizeController::class, 'distroy'])->name('product-size.distroy');
    Route::get('product-size/edit/{id}', [ProductSizeController::class, 'edit'])->name('product-size.edit');
    Route::put('product-size/update/{id}', [ProductSizeController::class, 'update'])->name('product-size.update');
    Route::put('product-size/status/{id}', [ProductSizeController::class, 'statusUpdate'])->name('product-size.status');


    Route::get('vat-percentage', [VatPercentageController::class, 'index'])->name('vat-percentage.index');
    Route::get('vat-percentage/getdata', [VatPercentageController::class, 'getdata'])->name('vat-percentage.getdata');
    Route::post('vat-percentage/store', [VatPercentageController::class, 'store'])->name('vat-percentage.store');
    Route::delete('vat-percentage/distroy/{id}', [VatPercentageController::class, 'distroy'])->name('vat-percentage.distroy');
    Route::get('vat-percentage/edit/{id}', [VatPercentageController::class, 'edit'])->name('vat-percentage.edit');
    Route::put('vat-percentage/update/{id}', [VatPercentageController::class, 'update'])->name('vat-percentage.update');
    Route::put('vat-percentage/status/{id}', [VatPercentageController::class, 'statusUpdate'])->name('vat-percentage.status');

    Route::get('warranty-period', [WarrantyPeriodController::class, 'index'])->name('warranty-period.index');
    Route::get('warranty-period/getdata', [WarrantyPeriodController::class, 'getdata'])->name('warranty-period.getdata');
    Route::post('warranty-period/store', [WarrantyPeriodController::class, 'store'])->name('warranty-period.store');
    Route::delete('warranty-period/distroy/{id}', [WarrantyPeriodController::class, 'distroy'])->name('warranty-period.distroy');
    Route::get('warranty-period/edit/{id}', [WarrantyPeriodController::class, 'edit'])->name('warranty-period.edit');
    Route::put('warranty-period/update/{id}', [WarrantyPeriodController::class, 'update'])->name('warranty-period.update');
    Route::put('warranty-period/status/{id}', [WarrantyPeriodController::class, 'statusUpdate'])->name('warranty-period.status');

    Route::get('warehouse', [WarehouseController::class, 'index'])->name('warehouse.index');
    Route::get('warehouse/getdata', [WarehouseController::class, 'getdata'])->name('warehouse.getdata');
    Route::post('warehouse/store', [WarehouseController::class, 'store'])->name('warehouse.store');
    Route::delete('warehouse/distroy/{id}', [WarehouseController::class, 'distroy'])->name('warehouse.distroy');
    Route::get('warehouse/edit/{id}', [WarehouseController::class, 'edit'])->name('warehouse.edit');
    Route::put('warehouse/update/{id}', [WarehouseController::class, 'update'])->name('warehouse.update');
    Route::put('warehouse/status/{id}', [WarehouseController::class, 'statusUpdate'])->name('warehouse.status');

     Route::get('requisition/getdata', [RequisitionController::class, 'getdata'])
        ->name('requisition.getdata');
    Route::get('requisition', [RequisitionController::class, 'index'])->name('requisition.index');
    Route::get('requisition/create', [RequisitionController::class, 'create'])->name('requisition.create');
    Route::get('requisition/products-by-category/{id}', [RequisitionController::class, 'getProductsByCategory'])->name('requisition.products-by-category');
    Route::post('requisition/store', [RequisitionController::class, 'store'])->name('requisition.store');
    Route::get('requisition/edit/{id}', [RequisitionController::class, 'edit'])->name('requisition.edit');
     Route::get(
        '/requisition/{id}/view',
        [RequisitionController::class, 'view']
    )->name('requisition.view');
    Route::delete('requisition/distroy/{id}', [RequisitionController::class, 'distroy'])->name('requisition.destroy');
    Route::put('requisition/{id}', [RequisitionController::class, 'update'])->name('requisition.update');
    //asraf

    Route::get('wing/getdata', [WingController::class, 'getdata'])
        ->name('wing.getdata');
    Route::resource('wing', WingController::class);
    Route::get('category/getdata', [CategoryController::class, 'getdata'])
        ->name('category.getdata');
    Route::resource('category', CategoryController::class);
    Route::put('category/status/{id}', [CategoryController::class, 'statusUpdate']);

    Route::get('subcategory/getdata', [SubcategoryController::class, 'getdata'])
        ->name('subcategory.getdata');
    Route::resource('subcategory', SubcategoryController::class);

    Route::get('brand/getdata', [BrandController::class, 'getdata'])
        ->name('brand.getdata');
    Route::resource('brand', BrandController::class);

    Route::put('product/status/{id}', [ProductController::class, 'statusUpdate']);
    Route::get('product/getdata', [ProductController::class, 'getdata'])
        ->name('product.getdata');
    Route::get(
        '/product/{id}/view',
        [ProductController::class, 'view']
    )->name('product.view');
    Route::resource('product', ProductController::class);
    Route::get('/product/subcategories/{categoryId}', [
        ProductController::class,
        'getSubcategoriesByCategory'
    ])->name('product.subcategories');

    Route::get('/product/product-types/{categoryId}', [
        ProductController::class,
        'getProductTypesByCategory'
    ])->name('product.product-types');

    Route::get('/product/product-sizes/{categoryId}', [
        ProductController::class,
        'getProductSizesByCategory'
    ])->name('product.product-sizes');
});