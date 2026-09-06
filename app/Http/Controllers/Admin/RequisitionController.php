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
use App\Exports\RequisitionExport;
use Maatwebsite\Excel\Facades\Excel;
use RealRashid\SweetAlert\Facades\Alert;

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

    // Step 1: GU forward korbe SCI ka
    public function forward(Requisition $requisition)
    {
        $requisition->update([
            'forwarded_by'    => Auth::id(),
            'forwarded_at'    => now(),
            'workflow_status' => 'forwarded_to_sci',
        ]);
        return back()->with('success', 'Requisition forwarded to Supply Chain Incharge');
    }

    // Step 2: SCI approve
    public function sciApprove(Request $request, Requisition $requisition)
    {
        $requisition->update([
            'sci_approved_by' => Auth::id(),
            'sci_approved_at' => now(),
            'sci_remarks'     => $request->remarks,
            'workflow_status' => 'forwarded_to_om',
        ]);
        return back()->with('success', 'Approved and forwarded to Operation Manager');
    }

    // Step 2: SCI reject
    public function sciReject(Request $request, Requisition $requisition)
    {
        $requisition->update([
            'sci_rejected_by' => Auth::id(),
            'sci_rejected_at' => now(),
            'sci_remarks'     => $request->remarks,
            'workflow_status' => 'sci_rejected',
        ]);
        return back()->with('success', 'Requisition rejected');
    }

    // Step 3: OM approve
    public function omApprove(Request $request, Requisition $requisition)
    {
        $requisition->update([
            'om_approved_by'  => Auth::id(),
            'om_approved_at'  => now(),
            'om_remarks'      => $request->remarks,
            'workflow_status' => 'forwarded_to_md',
        ]);
        return back()->with('success', 'Approved and forwarded to MD');
    }

    // Step 3: OM reject
    public function omReject(Request $request, Requisition $requisition)
    {
        $requisition->update([
            'om_rejected_by'  => Auth::id(),
            'om_rejected_at'  => now(),
            'om_remarks'      => $request->remarks,
            'workflow_status' => 'om_rejected',
        ]);
        return back()->with('success', 'Requisition rejected');
    }

    // Step 4: MD approve
    public function mdApprove(Request $request, Requisition $requisition)
    {
        $requisition->update([
            'md_approved_by'  => Auth::id(),
            'md_approved_at'  => now(),
            'md_remarks'      => $request->remarks,
            'workflow_status' => 'md_approved',
        ]);
        return back()->with('success', 'Approved. General User can now generate CS.');
    }

    // Step 4: MD reject
    public function mdReject(Request $request, Requisition $requisition)
    {
        $requisition->update([
            'md_rejected_by'  => Auth::id(),
            'md_rejected_at'  => now(),
            'md_remarks'      => $request->remarks,
            'workflow_status' => 'md_rejected',
        ]);
        return back()->with('success', 'Requisition rejected');
    }

    // Step 5: MD approve korle GU CS generate korte parbe
    public function generateCs(Requisition $requisition)
    {
        abort_unless($requisition->workflow_status === 'md_approved', 403);

        $requisition->update([
            'cs_generated_by' => Auth::id(),
            'cs_generated_at' => now(),
            'workflow_status' => 'cs_generated',
        ]);
        // CS generation logic ekhane
        return back()->with('success', 'CS Generated');
    }

    public function export($id)
    {
        $data = Requisition::with([
            'wing',
            'warehouse',
            'createdBy',
            'details.product.category',
            'details.product.brand',
            'details.product.productType',
            'details.product.productSize',
        ])->findOrFail($id);

        $fileName = 'requisition-' . ($data->requisition_no ?? $id) . '.xlsx';

        return Excel::download(new RequisitionExport($data), $fileName);
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
            'details.product.category',
            'details.product.brand',
            'details.product.productType',
            'details.product.productSize',
        ])->findOrFail($id);

        return view('backend.requisitions.print', compact('data'));
    }
    public function index()
    {
        $user = Auth::user();
        $wings = $user->wings()->where('status', 1)->orderBy('name')->get();
        $warehouses = Warehouse::where('status', 1)
            ->orderBy('name')
            ->get();
        return view('backend.requisitions.index', compact('wings', 'warehouses'));
    }
    public function distroy($id)
    {
        $find = Requisition::find($id);

        if (!$find) {
            Alert::error('Error', 'Requisition not found!');
            return redirect()->route('requisition.index');
        }

        // deleted_by field set kora, delete() call korar AGE
        $find->update([
            'deleted_by' => auth()->user()->id,
        ]);

        $find->delete(); // soft delete, deleted_at set hobe

        Alert::success('Success', 'Requisition deleted Successful!');
        return redirect()->route('requisition.index');
    }
    public function getdata(Request $request)
    {
        if ($request->ajax()) {
            $user = Auth::user();
            $assignedWingIds = $user->wings()->pluck('wings.id');

            $data = Requisition::with([
                'wing',
                'warehouse',
                'details.product',
                // CHANGE: workflow user relations eager load kora holo,
                // jate history dekhate N+1 query na hoy
                'forwardedBy',
                'sciApprovedBy',
                'sciRejectedBy',
                'omApprovedBy',
                'omRejectedBy',
                'mdApprovedBy',
                'mdRejectedBy',
                'csGeneratedBy',
            ])
                ->whereIn('wing_id', $assignedWingIds)
                ->when($request->filled('wing_id'), function ($query) use ($request) {
                    $query->where('wing_id', $request->wing_id);
                })
                ->when($request->filled('warehouse_id'), function ($query) use ($request) {
                    $query->where('warehouse_id', $request->warehouse_id);
                })
                ->when($request->filled('requisition_type'), function ($query) use ($request) {
                    $query->where('requisition_type', $request->requisition_type);
                })
                // CHANGE: naya filter - workflow_status diye o filter kora jabe (optional)
                ->when($request->filled('workflow_status'), function ($query) use ($request) {
                    $query->where('workflow_status', $request->workflow_status);
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

                // CHANGE: puraton 'status' column ekhon 'workflow_status' dekhabe,
                // age eta bool (active/inactive) chilo, ekhon workflow stage dekhabe
                ->addColumn('status', function ($row) {

                    $map = [
                        'pending'          => ['label' => 'Pending',              'class' => 'status-inactive'],
                        'forwarded_to_sci' => ['label' => 'Forwarded to SCI',     'class' => 'status-active'],
                        'sci_rejected'     => ['label' => 'Rejected by SCI',      'class' => 'status-rejected'],
                        'forwarded_to_om'  => ['label' => 'Forwarded to OM',      'class' => 'status-active'],
                        'om_rejected'      => ['label' => 'Rejected by OM',       'class' => 'status-rejected'],
                        'forwarded_to_md'  => ['label' => 'Forwarded to MD',      'class' => 'status-active'],
                        'md_approved'      => ['label' => 'Approved by MD',       'class' => 'status-active'],
                        'md_rejected'      => ['label' => 'Rejected by MD',       'class' => 'status-rejected'],
                        'cs_generated'     => ['label' => 'CS Generated',         'class' => 'status-active'],
                    ];

                    $current = $map[$row->workflow_status] ?? ['label' => 'Pending', 'class' => 'status-inactive'];

                    return '<span class="status-pill ' . $current['class'] . '">
                        <i class="bi bi-circle-fill"></i> ' . e($current['label']) . '
                    </span>';
                })

                ->addColumn('workflow_history', function ($row) {

                    $lines = [];

                    if ($row->forwarded_by) {
                        $lines[] = e($row->forwardedBy?->name ?? 'User') . ' forwarded on '
                            . optional($row->forwarded_at)->format('d-m-Y h:i A');
                    }
                    if ($row->sci_approved_by) {
                        $lines[] = e($row->sciApprovedBy?->name ?? 'SCI') . ' approved on '
                            . optional($row->sci_approved_at)->format('d-m-Y h:i A');
                    }
                    if ($row->sci_rejected_by) {
                        $lines[] = e($row->sciRejectedBy?->name ?? 'SCI') . ' rejected on '
                            . optional($row->sci_rejected_at)->format('d-m-Y h:i A');
                    }
                    if ($row->om_approved_by) {
                        $lines[] = e($row->omApprovedBy?->name ?? 'OM') . ' approved on '
                            . optional($row->om_approved_at)->format('d-m-Y h:i A');
                    }
                    if ($row->om_rejected_by) {
                        $lines[] = e($row->omRejectedBy?->name ?? 'OM') . ' rejected on '
                            . optional($row->om_rejected_at)->format('d-m-Y h:i A');
                    }
                    if ($row->md_approved_by) {
                        $lines[] = e($row->mdApprovedBy?->name ?? 'MD') . ' approved on '
                            . optional($row->md_approved_at)->format('d-m-Y h:i A');
                    }
                    if ($row->md_rejected_by) {
                        $lines[] = e($row->mdRejectedBy?->name ?? 'MD') . ' rejected on '
                            . optional($row->md_rejected_at)->format('d-m-Y h:i A');
                    }
                    if ($row->cs_generated_by) {
                        $lines[] = e($row->csGeneratedBy?->name ?? 'User') . ' generated CS on '
                            . optional($row->cs_generated_at)->format('d-m-Y h:i A');
                    }

                    if (empty($lines)) {
                        return '<span class="text-muted">No action yet</span>';
                    }

                    return '<div class="d-flex flex-column gap-1 small">'
                        . implode('<br>', array_map('e', $lines))
                        . '</div>';
                })

                ->addColumn('action', function ($row) use ($user) {

                    $viewBtn = '<button data-id="' . $row->id . '" type="button" class="view action-icon-btn action-view me-2" title="View">
                        <i class="bi bi-eye-fill"></i>
                    </button>';

                    $printBtn = '<a href="' . route('requisition.print', $row->id) . '" target="_blank" class="print action-icon-btn action-print me-2" title="Print">
                            <i class="bi bi-printer-fill"></i>
                        </a>';

                    $exportBtn = '<a href="' . route('requisition.export', $row->id) . '"
                            class="export action-icon-btn action-export me-2 js-download-btn"
                            title="Export to Excel" >
                            <i class="bi bi-file-earmark-excel-fill"></i>
                        </a>';

                    $editBtn = '<button data-id="' . $row->id . '" type="button" class="edit action-icon-btn action-edit me-2" title="Edit">
                            <i class="fa-solid fa-pen-to-square"></i>
                        </button>';

                    $deleteUrl = route('requisition.destroy', $row->id);
                    $csrfToken = csrf_field();
                    $method = method_field('DELETE');

                    $deleteBtn = '<form action="' . $deleteUrl . '" method="POST" style="display:inline;">
                                    ' . $csrfToken . '
                                    ' . $method . '
                                    <button type="submit" class="delete action-icon-btn action-delete" title="Delete">
                                        <i class="bi bi-trash-fill"></i>
                                    </button>
                                </form>';

                    // ---- CHANGE: workflow action buttons - icon + text soho ----
                    $workflowBtn = '';

                    if ($row->workflow_status === 'pending' && $row->created_by === $user->id) {

                        $workflowBtn = '<button data-id="' . $row->id . '" type="button" class="forward-btn forword-icon-btn action-workflow-btn me-2" title="Forward to SCI">
                                            <i class="bi bi-send-fill"></i> <span>Forward</span>
                                        </button>';
                    } elseif ($row->workflow_status === 'forwarded_to_sci' && $user->user_type === 'sci') {

                        $workflowBtn = '
                            <button data-id="' . $row->id . '" type="button" class="sci-approve-btn forword-icon-btn action-workflow-btn text-success me-2" title="Approve">
                                <i class="bi bi-check-circle-fill"></i> <span>Approve</span>
                            </button>
                            <button data-id="' . $row->id . '" type="button" class="sci-reject-btn forword-icon-btn action-workflow-btn text-danger me-2" title="Reject">
                                <i class="bi bi-x-circle-fill"></i> <span>Reject</span>
                            </button>';
                    } elseif ($row->workflow_status === 'forwarded_to_om' && $user->user_type === 'om') {

                        $workflowBtn = '
        <button data-id="' . $row->id . '" type="button" class="om-approve-btn forword-icon-btn action-workflow-btn text-success me-2" title="Approve">
            <i class="bi bi-check-circle-fill"></i> <span>Approve</span>
        </button>
        <button data-id="' . $row->id . '" type="button" class="om-reject-btn forword-icon-btn action-workflow-btn text-danger me-2" title="Reject">
            <i class="bi bi-x-circle-fill"></i> <span>Reject</span>
        </button>';
                    } elseif ($row->workflow_status === 'forwarded_to_md' && $user->user_type === 'md') {

                        $workflowBtn = '
        <button data-id="' . $row->id . '" type="button" class="md-approve-btn forword-icon-btn action-workflow-btn text-success me-2" title="Approve">
            <i class="bi bi-check-circle-fill"></i> <span>Approve</span>
        </button>
        <button data-id="' . $row->id . '" type="button" class="md-reject-btn forword-icon-btn action-workflow-btn text-danger me-2" title="Reject">
            <i class="bi bi-x-circle-fill"></i> <span>Reject</span>
        </button>';
                    } elseif ($row->workflow_status === 'md_approved' && $row->created_by === $user->id) {

                        $workflowBtn = '<button data-id="' . $row->id . '" type="button" class="generate-cs-btn forword-icon-btn action-workflow-btn me-2" title="Generate CS">
            <i class="bi bi-file-earmark-plus-fill"></i> <span>Generate CS</span>
        </button>';
                    }
                    // ---- END workflow action buttons ----

                    // CHANGE: workflowBtn ekhon sobar age boshano holo
                    return '
        <div class="d-flex align-items-center gap-2" style="flex-wrap: wrap;">
            ' . $workflowBtn . '
            ' . $viewBtn . '
            ' . $printBtn . '
            ' . $exportBtn . '
            ' . $editBtn . '
            ' . $deleteBtn . '
        </div>
    ';
                })

                ->rawColumns([
                    'products',
                    'status',
                    'workflow_history',
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
            'details.product.category',
            'details.product.brand',
            'details.product.productType',
            'details.product.productSize',
        ])->findOrFail($id);

        return view('backend.requisitions.view', compact('data'));
    }
    /**
     * Show Edit Requisition Page
     */
    public function edit(string $id)
    {
        $data = Requisition::with([
            'details.product.category',
            'details.product.brand',
            'details.product.productSize',
        ])->findOrFail($id);

        $wings = Wing::where('status', 1)->orderBy('name')->get();
        $warehouses = Warehouse::where('status', 1)->orderBy('name')->get();
        $categories = Category::where('status', 1)->orderBy('name')->get();

        // Existing items formatted for JS (matches actual schema)
        $existingItems = $data->details->map(function ($detail) {
            return [
                'product_id' => $detail->product_id,
                'name' => $detail->product?->name ?? '-',
                'code' => $detail->product?->product_code ?? '-',
                'brand' => $detail->product?->brand?->name ?? '-',
                'size' => $detail->product?->productSize?->name ?? '-',
                'category' => $detail->product?->category?->name ?? 'Uncategorized',
                'physical_stock' => (float) $detail->physical_stock,
                'in_transit' => (float) $detail->in_transit_stock,
                'lc_pending' => (float) $detail->lc_pending_stock,
                'pi' => (float) $detail->pi_stock,
                'sale_one' => (float) $detail->sale_one_stock,
                'sale_two' => (float) $detail->sale_two_stock,
                'sale_three' => (float) $detail->sale_three_stock,
                'quantity' => (float) $detail->required_stock, // Requirement
            ];
        })->values();

        return view('backend.requisitions.edit', compact('data', 'wings', 'warehouses', 'categories', 'existingItems'));
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
            'contact_person_info' => 'nullable|string|max:255',
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

            $totalQuantity = collect($items)->sum(function ($item) {
                return $item['quantity'] ?? $item['required_stock'] ?? 0;
            });

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

                $requiredStock = $item['quantity'] ?? $item['required_stock'] ?? 0;

                if (empty($item['product_id']) || $requiredStock <= 0) {
                    continue;
                }

                RequisitionDetail::create([
                    'requisition_id' => $requisition->id,
                    'product_id' => $item['product_id'],
                    'physical_stock' => $item['physical_stock'] ?? 0,
                    'in_transit_stock' => $item['in_transit'] ?? $item['in_transit_stock'] ?? 0,
                    'lc_pending_stock' => $item['lc_pending'] ?? $item['lc_pending_stock'] ?? 0,
                    'pi_stock' => $item['pi'] ?? $item['pi_stock'] ?? 0,
                    'sale_one_stock' => $item['sale_one'] ?? $item['sale_one_stock'] ?? 0,
                    'sale_two_stock' => $item['sale_two'] ?? $item['sale_two_stock'] ?? 0,
                    'sale_three_stock' => $item['sale_three'] ?? $item['sale_three_stock'] ?? 0,
                    'required_stock' => $requiredStock,
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
                    'size_name' => $product->product_size ?? '-',
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
            'contact_person_info' => 'nullable|string|max:255',
            'note' => 'nullable|string',
            'items' => 'required|json', // 'json' rule confirms it's valid JSON before decoding
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

            $totalQuantity = collect($items)->sum(function ($item) {
                return (float) ($item['quantity'] ?? $item['required_stock'] ?? 0);
            });

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

                $requiredStock = (float) ($item['quantity'] ?? $item['required_stock'] ?? 0);

                if (empty($item['product_id']) || $requiredStock <= 0) {
                    continue;
                }

                RequisitionDetail::create([
                    'requisition_id'   => $requisition->id,
                    'product_id'       => $item['product_id'],
                    'physical_stock'   => (float) ($item['physical_stock'] ?? 0),
                    'in_transit_stock' => (float) ($item['in_transit'] ?? $item['in_transit_stock'] ?? 0),
                    'lc_pending_stock' => (float) ($item['lc_pending'] ?? $item['lc_pending_stock'] ?? 0),
                    'pi_stock'         => (float) ($item['pi'] ?? $item['pi_stock'] ?? 0),
                    'sale_one_stock'   => (float) ($item['sale_one'] ?? $item['sale_one_stock'] ?? 0),
                    'sale_two_stock'   => (float) ($item['sale_two'] ?? $item['sale_two_stock'] ?? 0),
                    'sale_three_stock' => (float) ($item['sale_three'] ?? $item['sale_three_stock'] ?? 0),
                    'required_stock'   => $requiredStock,
                    'note'             => $item['note'] ?? null,
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
