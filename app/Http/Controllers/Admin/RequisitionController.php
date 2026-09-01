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
use Yajra\DataTables\Facades\DataTables;

class RequisitionController extends Controller implements HasMiddleware
{


    public static function middleware(): array
    {
        return [
            'auth',
            new Middleware('permission:requisition.view', only: ['index', 'getdata', 'view', 'print']),
            new Middleware('permission:requisition.create', only: ['create', 'store']),
            new Middleware('permission:requisition.edit', only: ['edit', 'update']),
        ];
    }

    /**
     * Standalone print page (Ispahani Indent Form style)
     */
    public function print($id)
    {
        $data = Requisition::with([
            'wing',
            'warehouse',
            'createdBy',
            'details.product',
        ])->findOrFail($id);

        return view('backend.requisitions.print', compact('data'));
    }
    public function index()
    {
        $wings = Wing::where('status', 1)
            ->orderBy('name')
            ->get();
        $warehouses = Warehouse::where('status', 1)
            ->orderBy('name')
            ->get();
        return view('backend.requisitions.index', compact('wings', 'warehouses'));
    }

    public function getdata(Request $request)
    {
        if ($request->ajax()) {

            $data = Requisition::with([
                'wing',
                'warehouse',
                'details.product',
            ])
                ->when($request->filled('wing_id'), function ($query) use ($request) {
                    $query->where('wing_id', $request->wing_id);
                })
                ->when($request->filled('warehouse_id'), function ($query) use ($request) {
                    $query->where('warehouse_id', $request->warehouse_id);
                })
                ->when($request->filled('requisition_type'), function ($query) use ($request) {
                    $query->where(
                        'requisition_type',
                        $request->requisition_type
                    );
                })
                ->when($request->filled('date_from'), function ($query) use ($request) {
                    $query->whereDate('date', '>=', $request->date_from);
                })
                ->when($request->filled('date_to'), function ($query) use ($request) {
                    $query->whereDate('date', '<=', $request->date_to);
                })
                ->orderBy('created_at', 'desc')
                ->get();

            return DataTables::of($data)

                ->addIndexColumn()

                ->addColumn('requisition_no', function ($row) {
                    return $row->requisition_no ?? '-';
                })

                ->addColumn('wing_name', function ($row) {
                    return $row->wing?->name ?? '-';
                })

                ->addColumn('warehouse_name', function ($row) {
                    return $row->warehouse?->name ?? '-';
                })

                ->addColumn('requisition_type_name', function ($row) {

                    return $row->requisition_type ?? '-';
                })

                ->addColumn('total_quantity', function ($row) {
                    return $row->total_quantity ?? 0;
                })

                ->addColumn('date', function ($row) {
                    return $row->date
                        ? \Carbon\Carbon::parse($row->date)->format('d-m-Y')
                        : '-';
                })

                ->addColumn('place_of_supply', function ($row) {
                    return $row->place_of_supply ?? '-';
                })

                ->addColumn('products', function ($row) {

                    if ($row->details->isEmpty()) {
                        return '<span class="text-muted">No Product</span>';
                    }

                    $html = '<div class="d-flex flex-column gap-1">';

                    foreach ($row->details as $detail) {

                        $productName = $detail->product?->name ?? 'Unknown Product';

                        $html .= '
                        <div>
                            <strong>' . e($productName) . '</strong>
                            <span class="text-muted">
                                (Qty: ' . e($detail->quantity) . ')
                            </span>
                        </div>
                    ';
                    }

                    $html .= '</div>';

                    return $html;
                })

                ->addColumn('status', function ($row) {

                    return $row->status
                        ? '<span class="status-pill status-active">
                            <i class="bi bi-circle-fill"></i> On created User
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

                    $printBtn =
                        '
                        <a    href="' . route('requisition.print', $row->id) . '"
                            target="_blank"
                            class="print action-icon-btn action-print me-2"
                            title="Print">
                            <i class="bi bi-printer-fill"></i>
                        </a>';

                    $editBtn =
                        '<button
                            data-id="' . $row->id . '"
                            type="button"
                            class="edit action-icon-btn action-edit me-2"
                            title="Edit">
                            <i class="fa-solid fa-pen-to-square"></i>
                        </button>';

                    $deleteUrl = route(
                        'requisition.destroy',
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

                    return '
                    <div class="d-flex align-items-center gap-2">
                        ' . $viewBtn . '
                        ' . $printBtn . '
                        ' . $editBtn . '
                        ' . $deleteBtn . '
                    </div>
                 ';
                })

                ->rawColumns([
                    'products',
                    'status',
                    'action',
                ])

                ->make(true);
        }
    }
    public function view($id)
    {
        $data = Requisition::with([
            'wing',
            'warehouse',
            'createdBy',
            'details.product',
        ])->findOrFail($id);

        return view(
            'backend.requisitions.view',
            compact('data')
        );
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
                'contact_person_info' => $request->contact_person_info,
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
                'contact_person_info' => $request->contact_person_info,
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