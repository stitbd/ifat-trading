<?php

namespace App\Http\Controllers\Admin;

use Alert;
use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use App\Models\Subcategory;
use App\Models\Brand;
use App\Models\Manufacturer;
use App\Models\CountryOfOrigin;
use App\Models\ProductType;
use App\Models\VehicleType;
use App\Models\ProductSize;
use App\Models\WarrantyPeriod;
use App\Models\VatPercentage;
use App\Models\Wing;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class ProductController extends Controller
{
    public static function middleware(): array
    {
        return [
            'auth',

            new Middleware(
                'permission:product.view',
                only: ['index', 'getdata']
            ),

            new Middleware(
                'permission:product.create',
                only: ['store']
            ),

            new Middleware(
                'permission:product.edit',
                only: ['update', 'statusUpdate']
            ),

            new Middleware(
                'permission:product.delete',
                only: ['destroy']
            ),
        ];
    }

    /**
     * Product List
     */
    public function index()
    {
        $wings = Wing::where('status', 1)
            ->orderBy('name')
            ->get();
        $categories = Category::where('status', 1)
            ->orderBy('name')
            ->get();

        $subcategories = Subcategory::where('status', 1)
            ->orderBy('name')
            ->get();

        $brands = Brand::where('status', 1)
            ->orderBy('name')
            ->get();

        $manufacturers = Manufacturer::where('status', 1)
            ->orderBy('name')
            ->get();

        $countries = CountryOfOrigin::where('status', 1)
            ->orderBy('name')
            ->get();

        $productTypes = ProductType::where('status', 1)
            ->orderBy('name')
            ->get();

        $vehicleTypes = VehicleType::where('status', 1)
            ->orderBy('name')
            ->get();

        $productSizes = ProductSize::where('status', 1)
            ->orderBy('name')
            ->get();

        $warrantyPeriods = WarrantyPeriod::where('status', 1)
            ->orderBy('title')
            ->get();

        $vatPercentages = VatPercentage::where('status', 1)
            ->orderBy('title')
            ->get();

        return view(
            'backend.products.index',
            compact(
                'categories',
                'subcategories',
                'brands',
                'manufacturers',
                'countries',
                'productTypes',
                'vehicleTypes',
                'productSizes',
                'warrantyPeriods',
                'vatPercentages',
                'wings'
            )
        );
    }


    /**
     * Product DataTable
     */
    public function getdata(Request $request)
    {
        if ($request->ajax()) {

            $data = Product::with([
                'category',
                'subCategory',
                'brand',
                'manufacturer',
                'countryOfOrigin',
                'productType',
                'vehicleType',
                'productSize',
                'warrantyPeriod',
                'vatPercentage',
            ])
                ->when($request->filled('wing_id'), function ($query) use ($request) {
                    $query->where('wing_id', $request->wing_id);
                })
                ->when($request->filled('category_id'), function ($query) use ($request) {
                    $query->where('categories_id', $request->category_id);
                })
                ->when($request->filled('brand_id'), function ($query) use ($request) {
                    $query->where('brand_id', $request->brand_id);
                })
                ->orderBy('created_at', 'desc')
                ->get();

            return DataTables::of($data)

                ->addIndexColumn()

                ->addColumn('image', function ($row) {

                    if (
                        $row->image &&
                        file_exists(
                            public_path('products/image/' . $row->image)
                        )
                    ) {
                        return '<img src="' .
                            asset('products/image/' . $row->image) .
                            '"
                            alt="Product Image"
                            style="
                                width:60px;
                                height:60px;
                                object-fit:cover;
                                border-radius:5px;
                                border:1px solid #ddd;
                            ">';
                    }

                    return '<span class="text-muted">No Image</span>';
                })

                ->addColumn('wing_name', function ($row) {
                    return $row->wing?->name ?? '-';
                })
                ->addColumn('category_name', function ($row) {
                    return $row->category?->name ?? '-';
                })

                ->addColumn('sub_category_name', function ($row) {
                    return $row->subCategory?->name ?? '-';
                })

                ->addColumn('brand_name', function ($row) {
                    return $row->brand?->name ?? '-';
                })

                ->addColumn('manufacturer_name', function ($row) {
                    return $row->manufacturer?->name ?? '-';
                })

                ->addColumn('country_name', function ($row) {
                    return $row->countryOfOrigin?->name ?? '-';
                })

                ->addColumn('product_type_name', function ($row) {
                    return $row->productType?->name ?? '-';
                })

                ->addColumn('vehicle_type_name', function ($row) {
                    return $row->vehicleType?->name ?? '-';
                })

                ->addColumn('product_size_name', function ($row) {
                    return $row->productSize?->name ?? '-';
                })

                ->addColumn('warranty_name', function ($row) {
                    return $row->warrantyPeriod?->title ?? '-';
                })

                ->addColumn('vat_name', function ($row) {
                    return $row->vatPercentage?->title ?? '-';
                })

                ->addColumn('status', function ($row) {

                    return $row->status
                        ? '<span class="status-pill status-active">
                            <i class="bi bi-circle-fill"></i> Active
                        </span>'
                        : '<span class="status-pill status-inactive">
                            <i class="bi bi-circle-fill"></i> Inactive
                        </span>';
                })

                ->addColumn('action', function ($row) {

                    $viewBtn =
                        '<button
            data-id="' . $row->id . '"
            type="button"
            class="view action-icon-btn action-view me-2"
            title="View">
            <i class="bi bi-eye-fill"></i>
        </button>';

                    $editBtn =
                        '<button
                            data-id="' . $row->id . '"
                            type="button"
                            class="edit action-icon-btn action-edit me-2"
                            title="Edit">
                            <i class="fa-solid fa-pen-to-square"></i>
                        </button>';

                    $deleteUrl = route(
                        'product.destroy',
                        $row->id
                    );

                    $csrfToken = csrf_field();

                    $method = method_field('DELETE');

                    $deleteBtn =
                        '<form
                            action="' . $deleteUrl . '"
                            method="POST"
                            style="display:inline;"
                        >
                            ' . $csrfToken . '
                            ' . $method . '

                            <button
                                type="submit"
                                class="delete action-icon-btn action-delete"
                                title="Delete">
                                <i class="bi bi-trash-fill"></i>
                            </button>
                        </form>';

                    if ($row->status) {

                        $statusBtn =
                            '<button
                                data-id="' . $row->id . '"
                                type="button"
                                class="status-toggle action-icon-btn action-status-on"
                                title="Active - click to deactivate">
                                <i class="bi bi-toggle-on"></i>
                            </button>';
                    } else {

                        $statusBtn =
                            '<button
                                data-id="' . $row->id . '"
                                type="button"
                                class="status-toggle action-icon-btn action-status-off"
                                title="Inactive - click to activate">
                                <i class="bi bi-toggle-off"></i>
                            </button>';
                    }

                    return '
                        <div class="d-flex align-items-center gap-2">
                            ' . $statusBtn . '
                            ' . $viewBtn . '
                            ' . $editBtn . '
                            ' . $deleteBtn . '
                        </div>
                    ';
                })

                ->rawColumns([
                    'image',
                    'status',
                    'action',
                ])

                ->make(true);
        }
    }
    /**
     * Show Create Product Page
     */
    public function create()
    {
        $wings = Wing::where('status', 1)->orderBy('name')->get();
        $categories = Category::where('status', 1)->orderBy('name')->get();
        $subcategories = Subcategory::where('status', 1)->orderBy('name')->get();
        $brands = Brand::where('status', 1)->orderBy('name')->get();
        $manufacturers = Manufacturer::where('status', 1)->orderBy('name')->get();
        $countries = CountryOfOrigin::where('status', 1)->orderBy('name')->get();
        $productTypes = ProductType::where('status', 1)->orderBy('name')->get();
        $vehicleTypes = VehicleType::where('status', 1)->orderBy('name')->get();
        $productSizes = ProductSize::where('status', 1)->orderBy('name')->get();
        $warrantyPeriods = WarrantyPeriod::where('status', 1)->orderBy('title')->get();
        $vatPercentages = VatPercentage::where('status', 1)->orderBy('title')->get();

        return view(
            'backend.products.create',
            compact(
                'wings',
                'categories',
                'subcategories',
                'brands',
                'manufacturers',
                'countries',
                'productTypes',
                'vehicleTypes',
                'productSizes',
                'warrantyPeriods',
                'vatPercentages'
            )
        );
    }


    /**
     * Store Product
     */
    public function store(Request $request)
    {
        $request->validate([
            'product_code' => 'required|string|max:25|unique:products,product_code',
            'name' => 'required|string|max:100',

            'wing_id' => 'required|exists:wings,id',

            'categories_id' => 'required|exists:categories,id',
            'sub_categories_id' => 'nullable|exists:subcategories,id',
            'brand_id' => 'nullable|exists:brands,id',
            'manufacturer_id' => 'nullable|exists:manufacturers,id',
            'country_of_origin_id' => 'nullable|exists:country_of_origins,id',
            'product_type_id' => 'nullable|exists:product_types,id',
            'vehicle_type_id' => 'nullable|exists:vehicle_types,id',
            'product_size_id' => 'nullable|exists:product_sizes,id',
            'warranty_period_id' => 'nullable|exists:warranty_periods,id',
            'vat_percentage_id' => 'nullable|exists:vat_percentages,id',

            'position' => 'nullable|string|max:100',
            'unit_of_measurement' => 'nullable|string|max:100',

            'image' => 'nullable|file|image|max:2048',

            'min_alert_stock' => 'nullable|integer|min:0',

            'status' => 'nullable|boolean',
        ]);

        try {

            DB::beginTransaction();

            $imagePath = null;

            if ($request->hasFile('image')) {

                $file = $request->file('image');

                $extension = $file->getClientOriginalExtension();

                $filename =
                    time() . '_' .
                    uniqid() . '.' .
                    $extension;

                $path = 'products/image/';

                if (!file_exists(public_path($path))) {
                    mkdir(
                        public_path($path),
                        0755,
                        true
                    );
                }

                $file->move(
                    public_path($path),
                    $filename
                );

                $imagePath = $filename;
            }

            Product::create([
                'product_code' => $request->product_code,
                'name' => $request->name,

                'categories_id' => $request->categories_id,
                'sub_categories_id' => $request->sub_categories_id,
                'wing_id' => $request->wing_id,
                'brand_id' => $request->brand_id,
                'manufacturer_id' => $request->manufacturer_id,
                'country_of_origin_id' => $request->country_of_origin_id,
                'product_type_id' => $request->product_type_id,
                'vehicle_type_id' => $request->vehicle_type_id,
                'product_size_id' => $request->product_size_id,
                'warranty_period_id' => $request->warranty_period_id,
                'vat_percentage_id' => $request->vat_percentage_id,

                'position' => $request->position,
                'unit_of_measurement' => $request->unit_of_measurement,
                'image' => $imagePath,
                'min_alert_stock' => $request->min_alert_stock,

                'status' => $request->status ?? 1,

                'created_by' => Auth::id(),
                'updated_by' => Auth::id(),
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Product Created Successfully!',
            ]);
        } catch (\Exception $e) {

            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Something went wrong! Please try again.',
            ], 500);
        }
    }

    /**
     * Edit Product
     */
    public function edit(string $id)
    {
        $data = Product::findOrFail($id);

        $wings = Wing::where('status', 1)
            ->orderBy('name')
            ->get();

        $categories = Category::where('status', 1)
            ->orderBy('name')
            ->get();

        $subcategories = Subcategory::where('status', 1)
            ->orderBy('name')
            ->get();

        $brands = Brand::where('status', 1)
            ->orderBy('name')
            ->get();

        $manufacturers = Manufacturer::where('status', 1)
            ->orderBy('name')
            ->get();

        $countries = CountryOfOrigin::where('status', 1)
            ->orderBy('name')
            ->get();

        $productTypes = ProductType::where('status', 1)
            ->orderBy('name')
            ->get();

        $vehicleTypes = VehicleType::where('status', 1)
            ->orderBy('name')
            ->get();

        $productSizes = ProductSize::where('status', 1)
            ->orderBy('name')
            ->get();

        $warrantyPeriods = WarrantyPeriod::where('status', 1)
            ->orderBy('title')
            ->get();

        $vatPercentages = VatPercentage::where('status', 1)
            ->orderBy('title')
            ->get();

        return view(
            'backend.products.edit',
            compact(
                'data',
                'categories',
                'subcategories',
                'brands',
                'manufacturers',
                'countries',
                'productTypes',
                'vehicleTypes',
                'productSizes',
                'warrantyPeriods',
                'vatPercentages',
                'wings'
            )
        );
    }

    /**
     * View Product
     */
    public function view(string $id)
    {
        $data = Product::with([
            'wing',
            'category',
            'subCategory',
            'brand',
            'manufacturer',
            'countryOfOrigin',
            'productType',
            'vehicleType',
            'productSize',
            'warrantyPeriod',
            'vatPercentage',
        ])->findOrFail($id);

        return view(
            'backend.products.view',
            compact('data')
        );
    }


    /**
     * Update Product
     */
    public function update(
        Request $request,
        string $id
    ) {
        $find = Product::findOrFail($id);

        $request->validate([

            'product_code' => [
                'required',
                'string',
                'max:25',
                'unique:products,product_code,' . $id,
            ],

            'name' => 'required|string|max:100',
            'wing_id' => 'required|exists:wings,id',

            'categories_id' => 'nullable|exists:categories,id',
            'sub_categories_id' => 'nullable|exists:subcategories,id',
            'brand_id' => 'nullable|exists:brands,id',
            'manufacturer_id' => 'nullable|exists:manufacturers,id',
            'country_of_origin_id' => 'nullable|exists:country_of_origins,id',
            'product_type_id' => 'nullable|exists:product_types,id',
            'vehicle_type_id' => 'nullable|exists:vehicle_types,id',
            'product_size_id' => 'nullable|exists:product_sizes,id',
            'warranty_period_id' => 'nullable|exists:warranty_periods,id',
            'vat_percentage_id' => 'nullable|exists:vat_percentages,id',

            'position' => 'nullable|string|max:100',
            'unit_of_measurement' => 'nullable|string|max:100',

            'image' => 'nullable|file|image|max:2048',

            'min_alert_stock' => 'nullable|integer|min:0',

            'status' => 'nullable|boolean',
        ]);

        try {

            DB::beginTransaction();

            $data = [

                'product_code' => $request->product_code,
                'name' => $request->name,
                'wing_id' => $request->wing_id,

                'categories_id' => $request->categories_id,
                'sub_categories_id' => $request->sub_categories_id,
                'brand_id' => $request->brand_id,
                'manufacturer_id' => $request->manufacturer_id,
                'country_of_origin_id' => $request->country_of_origin_id,
                'product_type_id' => $request->product_type_id,
                'vehicle_type_id' => $request->vehicle_type_id,
                'product_size_id' => $request->product_size_id,
                'warranty_period_id' => $request->warranty_period_id,
                'vat_percentage_id' => $request->vat_percentage_id,

                'position' => $request->position,
                'unit_of_measurement' => $request->unit_of_measurement,
                'min_alert_stock' => $request->min_alert_stock,

                'status' => $request->status ?? 0,

                'updated_by' => Auth::id(),
            ];

            if ($request->hasFile('image')) {

                if ($find->image !== null) {

                    $imagePath = public_path(
                        'products/image/' . $find->image
                    );

                    if (file_exists($imagePath)) {
                        unlink($imagePath);
                    }
                }

                $file = $request->file('image');

                $extension =
                    $file->getClientOriginalExtension();

                $filename =
                    time() . '_' .
                    uniqid() . '.' .
                    $extension;

                $path = 'products/image/';

                if (!file_exists(public_path($path))) {
                    mkdir(
                        public_path($path),
                        0755,
                        true
                    );
                }

                $file->move(
                    public_path($path),
                    $filename
                );

                $data['image'] = $filename;
            }

            $find->update($data);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Product Updated Successfully!',
            ]);
        } catch (\Exception $e) {

            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Something went wrong! Please try again.',
            ], 500);
        }
    }

    /**
     * Status Update
     */
    public function statusUpdate(
        Request $request,
        $id
    ) {
        $find = Product::find($id);

        if (!$find) {
            return response()->json([
                'success' => false,
                'message' => 'Product not found!',
            ], 404);
        }

        try {

            $find->update([
                'status' => $find->status ? 0 : 1,
                'updated_by' => Auth::id(),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Status Updated Successfully!',
                'status' => $find->status,
            ]);
        } catch (\Exception $e) {

            return response()->json([
                'success' => false,
                'message' => 'Something went wrong! Please try again.',
            ], 500);
        }
    }

    /**
     * Delete Product
     */
    public function destroy(string $id)
    {
        $find = Product::findOrFail($id);

        if ($find->image !== null) {

            $imagePath = public_path(
                'products/image/' . $find->image
            );

            if (file_exists($imagePath)) {
                unlink($imagePath);
            }
        }

        $find->update([
            'deleted_by' => Auth::id(),
            'updated_by' => Auth::id(),
        ]);

        $find->delete();

        Alert::success(
            'Success',
            'Product deleted successfully!'
        );

        return redirect()->route(
            'product.index'
        );
    }
    public function getSubcategoriesByCategory($categoryId)
    {
        $subcategories = Subcategory::where('category_id', $categoryId)
            ->where('status', 1)
            ->orderBy('name')
            ->get(['id', 'name']);

        return response()->json($subcategories);
    }
    public function getProductTypesByCategory($categoryId)
    {
        $productTypes = ProductType::where('category_id', $categoryId)
            ->where('status', 1)
            ->orderBy('name')
            ->get(['id', 'name']);

        return response()->json($productTypes);
    }
    public function getProductSizesByCategory($categoryId)
    {
        $productSizes = ProductSize::where('category_id', $categoryId)
            ->where('status', 1)
            ->orderBy('name')
            ->get(['id', 'name']);

        return response()->json($productSizes);
    }
}