<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Requisition;
use App\Models\RequisitionDetail;
use App\Models\Wing;
use App\Models\Warehouse;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class RequisitionController extends Controller implements HasMiddleware
{


    public static function middleware(): array
    {
        return [
            'auth',

            new Middleware('permission:requisition.view', only: ['index', 'getdata']),

            new Middleware('permission:requisition.create', only: ['create', 'store']),

            new Middleware('permission:requisition.edit', only: ['edit', 'update']),
        ];
    }
    public function index()
    {
        return redirect()->back();
    }
    /**
     * Show Edit Requisition Page
     */
    public function edit(string $id)
    {
        $data = Requisition::with('details.product.brand', 'details.product.productSize')
            ->findOrFail($id);

        $wings = Wing::where('status', 1)->orderBy('name')->get();
        $warehouses = Warehouse::where('status', 1)->orderBy('name')->get();
        $categories = Category::where('status', 1)->orderBy('name')->get();

        // Existing items formatted for JS
        $existingItems = $data->details->map(function ($detail) {
            return [
                'product_id' => $detail->product_id,
                'name' => $detail->product?->name ?? '-',
                'code' => $detail->product?->product_code ?? '-',
                'brand' => $detail->product?->brand?->name ?? '-',
                'size' => $detail->product?->productSize?->name ?? '-',
                'quantity' => $detail->quantity,
                'note' => $detail->note,
            ];
        })->values();

        return view(
            'backend.requisitions.edit',
            compact(
                'data',
                'wings',
                'warehouses',
                'categories',
                'existingItems'
            )
        );
    }

    /**
     * Update Requisition
     */
    public function update(Request $request, string $id)
    {
        $requisition = Requisition::findOrFail($id);

        $request->validate([
            'wing_id' => 'required|exists:wings,id',
            'warehouse_id' => 'required|exists:warehouses,id',
            'requisition_type' => 'required|in:local,import',
            'date' => 'required|date',
            'place_of_supply' => 'nullable|string|max:255',
            'note' => 'nullable|string',
            'items' => 'required|string',
        ]);

        $items = json_decode($request->items, true);

        if (empty($items) || !is_array($items)) {
            return response()->json([
                'success' => false,
                'message' => 'Please add at least one product to the requisition!',
            ], 422);
        }

        try {
            DB::beginTransaction();

            $totalQuantity = collect($items)->sum('quantity');

            $requisition->update([
                'wing_id' => $request->wing_id,
                'warehouse_id' => $request->warehouse_id,
                'requisition_type' => $request->requisition_type,
                'total_quantity' => $totalQuantity,
                'date' => $request->date,
                'note' => $request->note,
                'place_of_supply' => $request->place_of_supply,
                'updated_by' => Auth::id(),
            ]);

            // Remove old details and re-insert fresh ones
            $requisition->details()->delete();

            foreach ($items as $item) {

                if (empty($item['product_id']) || empty($item['quantity'])) {
                    continue;
                }

                RequisitionDetail::create([
                    'requisition_id' => $requisition->id,
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'note' => $item['note'] ?? null,
                ]);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Requisition Updated Successfully!',
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
     * Show Create Requisition Page
     */
    public function create()
    {
        $wings = Wing::where('status', 1)->orderBy('name')->get();
        $warehouses = Warehouse::where('status', 1)->orderBy('name')->get();
        $categories = Category::where('status', 1)->orderBy('name')->get();

        // Preview only — actual number is generated again on save
        $requisitionNoPreview = Requisition::generateRequisitionNo();

        return view(
            'backend.requisitions.create',
            compact(
                'wings',
                'warehouses',
                'categories',
                'requisitionNoPreview'
            )
        );
    }

    /**
     * Get Products by Category (AJAX)
     */
    public function getProductsByCategory($categoryId)
    {
        $products = Product::with(['brand', 'productSize'])
            ->where('categories_id', $categoryId)
            ->where('status', 1)
            ->orderBy('name')
            ->get()
            ->map(function ($product) {
                return [
                    'id' => $product->id,
                    'product_code' => $product->product_code,
                    'name' => $product->name,
                    'brand_name' => $product->brand?->name ?? '-',
                    'size_name' => $product->productSize?->name ?? '-',
                ];
            });

        return response()->json($products);
    }

    /**
     * Store Requisition
     */
    public function store(Request $request)
    {
        $request->validate([
            'wing_id' => 'required|exists:wings,id',
            'warehouse_id' => 'required|exists:warehouses,id',
            'requisition_type' => 'required|in:local,import',
            'date' => 'required|date',
            'place_of_supply' => 'nullable|string|max:255',
            'note' => 'nullable|string',
            'items' => 'required|string',
        ]);

        $items = json_decode($request->items, true);

        if (empty($items) || !is_array($items)) {
            return response()->json([
                'success' => false,
                'message' => 'Please add at least one product to the requisition!',
            ], 422);
        }

        try {
            DB::beginTransaction();

            $totalQuantity = collect($items)->sum('quantity');

            $requisition = Requisition::create([
                'wing_id' => $request->wing_id,
                'warehouse_id' => $request->warehouse_id,
                'requisition_type' => $request->requisition_type,
                'total_quantity' => $totalQuantity,
                'date' => $request->date,
                'note' => $request->note,
                'place_of_supply' => $request->place_of_supply,
                'status' => 1,
                'created_by' => Auth::id(),
                'updated_by' => Auth::id(),
            ]);

            foreach ($items as $item) {

                if (empty($item['product_id']) || empty($item['quantity'])) {
                    continue;
                }

                RequisitionDetail::create([
                    'requisition_id' => $requisition->id,
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'note' => $item['note'] ?? null,
                ]);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Requisition Created Successfully!',
                'requisition_no' => $requisition->requisition_no,
            ]);
        } catch (\Exception $e) {

            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Something went wrong! Please try again.',
            ], 500);
        }
    }
}