<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ComparativeStatement;
use App\Models\ComparativeStatementDetail;
use App\Models\ComparativeStatementItem;
use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderDetail;
use App\Models\Requisition;
use App\Models\RequisitionDetail;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class ComparativeStatementController extends Controller implements HasMiddleware
{
    // Status numbers matching RequisitionController:
    // 1  = Pending
    // 2  = Forwarded to SCI
    // 3  = Rejected by SCI
    // 4  = Forwarded to OM
    // 5  = Rejected by OM
    // 6  = Forwarded to MD
    // 7  = Approved by MD
    // 8  = Rejected by MD
    // 9  = CS Generated Partially
    // 10 = CS Generated
    public static function middleware(): array
    {
        return [
            'auth',
            new Middleware('permission:comparative_statement.view', only: ['index', 'getdata', 'view', 'print']),
            new Middleware('permission:comparative_statement.create', only: ['create', 'store']),
            new Middleware('permission:comparative_statement.edit', only: ['edit', 'update']),
        ];
    }
    // status
    public function forward(ComparativeStatement $comparativeStatement)
    {
        $comparativeStatement->update([
            'forwarded_by' => Auth::id(),
            'forwarded_at' => now(),
            'status'       => 2, // Forwarded to SCI
        ]);
        return back()->with('success', 'Comparative Statement forwarded to Supply Chain Incharge');
    }

    // Step 2: SCI approve
    public function sciApprove(Request $request, ComparativeStatement $comparativeStatement)
    {
        $comparativeStatement->update([
            'sci_approved_by' => Auth::id(),
            'sci_approved_at' => now(),
            'sci_remarks'     => $request->remarks,
            'status'          => 4, // Forwarded to OM
        ]);
        return back()->with('success', 'Approved and forwarded to Operation Manager');
    }

    // Step 2: SCI reject
    public function sciReject(Request $request, ComparativeStatement $comparativeStatement)
    {
        $comparativeStatement->update([
            'sci_rejected_by' => Auth::id(),
            'sci_rejected_at' => now(),
            'sci_remarks'     => $request->remarks,
            'status'          => 3, // Rejected by SCI
        ]);
        return back()->with('success', 'Comparative Statement rejected');
    }

    // Step 3: OM approve
    public function omApprove(Request $request, ComparativeStatement $comparativeStatement)
    {
        $comparativeStatement->update([
            'om_approved_by' => Auth::id(),
            'om_approved_at' => now(),
            'om_remarks'     => $request->remarks,
            'status'         => 6, // Forwarded to MD
        ]);
        return back()->with('success', 'Approved and forwarded to MD');
    }

    // Step 3: OM reject
    public function omReject(Request $request, ComparativeStatement $comparativeStatement)
    {
        $comparativeStatement->update([
            'om_rejected_by' => Auth::id(),
            'om_rejected_at' => now(),
            'om_remarks'     => $request->remarks,
            'status'         => 5, // Rejected by OM
        ]);
        return back()->with('success', 'Comparative Statement rejected');
    }

    // Step 4: MD approve
    public function mdApprove(Request $request, ComparativeStatement $comparativeStatement)
    {
        $comparativeStatement->update([
            'md_approved_by' => Auth::id(),
            'md_approved_at' => now(),
            'md_remarks'     => $request->remarks,
            'status'         => 7, // Approved by MD
        ]);
        return back()->with('success', 'Comparative Statement approved by MD');
    }

    // Step 4: MD reject
    public function mdReject(Request $request, ComparativeStatement $comparativeStatement)
    {
        $comparativeStatement->update([
            'md_rejected_by' => Auth::id(),
            'md_rejected_at' => now(),
            'md_remarks'     => $request->remarks,
            'status'         => 8, // Rejected by MD
        ]);
        return back()->with('success', 'Comparative Statement rejected');
    }

    /*
    |--------------------------------------------------------------------------
    | CS LIST
    |--------------------------------------------------------------------------
    */
    public function index()
    {
        $requisitions = Requisition::orderByDesc('id')->get(['id', 'requisition_no']);

        return view('backend.comparative_statements.index', compact('requisitions'));
    }

    public function getdata(Request $request)
    {
        $query = ComparativeStatement::with(['requisition', 'createdBy', 'details.supplier'])
            ->select('comparative_statements.*');

        if ($request->filled('requisition_id')) {
            $query->where('requisition_id', $request->requisition_id);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('cs_date', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('cs_date', '<=', $request->date_to);
        }

        return DataTables::of($query)
            ->addColumn('requisition_no', function (ComparativeStatement $cs) {
                return $cs->requisition->requisition_no ?? '-';
            })
            ->addColumn('requisition_date', function (ComparativeStatement $cs) {
                return $cs->requisition->date
                    ? \Carbon\Carbon::parse($cs->requisition->date)->format('d-M')
                    : '-';
            })
            ->addColumn('status', function ($row) {

                $map = [
                    1  => ['label' => 'Pending',            'class' => 'status-inactive'],
                    2  => ['label' => 'Forwarded to SCI',   'class' => 'status-active'],
                    3  => ['label' => 'Rejected by SCI',    'class' => 'status-rejected'],
                    4  => ['label' => 'Forwarded to OM',    'class' => 'status-active'],
                    5  => ['label' => 'Rejected by OM',     'class' => 'status-rejected'],
                    6  => ['label' => 'Forwarded to MD',    'class' => 'status-active'],
                    7  => ['label' => 'Approved by MD',     'class' => 'status-active'],
                    8  => ['label' => 'Rejected by MD',     'class' => 'status-rejected'],
                    9  => ['label' => 'PO Created',         'class' => 'status-active'],
                    10 => ['label' => 'PI Created',         'class' => 'status-active'],
                ];

                $current = $map[(int) $row->status] ?? ['label' => 'Pending', 'class' => 'status-inactive'];

                return '<span class="status-pill ' . $current['class'] . '">
                <i class="bi bi-circle-fill"></i> ' . e($current['label']) . '
            </span>';
            })
            ->addColumn('wing', function (ComparativeStatement $cs) {
                return $cs->requisition->wing->name ?? '-';
            })

            ->addColumn('best_amount', function (ComparativeStatement $cs) {
                $min = $cs->details->min('total_amount');
                return $min !== null ? number_format((float) $min, 2) : '-';
            })
            ->addColumn('created_by_name', function (ComparativeStatement $cs) {
                return $cs->createdBy->name ?? '-';
            })
            ->addColumn('cs_date_formatted', function (ComparativeStatement $cs) {
                return $cs->cs_date ? \Carbon\Carbon::parse($cs->cs_date)->format('d-m-Y') : '-';
            })
            ->addColumn('action', function (ComparativeStatement $cs) {
                $user = Auth::user();
                $status = (int) $cs->status;

                $workflowBtn = '';

                if ($status === 1 && $cs->created_by === $user->id) {
                    $workflowBtn = '<button data-id="' . $cs->id . '" type="button" class="cs-forward-btn forword-icon-btn action-workflow-btn me-2" title="Forward to SCI">
                            <i class="bi bi-send-fill"></i> <span>Forward</span>
                        </button>';
                } elseif ($status === 2 && $user->user_type === 'sci') {
                    $workflowBtn = '
                        <button data-id="' . $cs->id . '" type="button" class="cs-sci-approve-btn forword-icon-btn action-workflow-btn text-success me-2" title="Approve">
                            <i class="bi bi-check-circle-fill"></i> <span>Approve</span>
                        </button>
                        <button data-id="' . $cs->id . '" type="button" class="cs-sci-reject-btn forword-icon-btn action-workflow-btn text-danger me-2" title="Reject">
                            <i class="bi bi-x-circle-fill"></i> <span>Reject</span>
                        </button>';
                } elseif ($status === 4 && $user->user_type === 'om') {
                    $workflowBtn = '
            <button data-id="' . $cs->id . '" type="button" class="cs-om-approve-btn forword-icon-btn action-workflow-btn text-success me-2" title="Approve">
                <i class="bi bi-check-circle-fill"></i> <span>Approve</span>
            </button>
            <button data-id="' . $cs->id . '" type="button" class="cs-om-reject-btn forword-icon-btn action-workflow-btn text-danger me-2" title="Reject">
                <i class="bi bi-x-circle-fill"></i> <span>Reject</span>
            </button>';
                } elseif ($status === 6 && $user->user_type === 'md') {
                    $workflowBtn = '
            <button data-id="' . $cs->id . '" type="button" class="cs-md-approve-btn forword-icon-btn action-workflow-btn text-success me-2" title="Approve">
                <i class="bi bi-check-circle-fill"></i> <span>Approve</span>
            </button>
            <button data-id="' . $cs->id . '" type="button" class="cs-md-reject-btn forword-icon-btn action-workflow-btn text-danger me-2" title="Reject">
                <i class="bi bi-x-circle-fill"></i> <span>Reject</span>
            </button>';
                } elseif ($status === 7 && $cs->created_by === $user->id) {
                    // MD approved -> requisition_type onujayi PO ba PI create korar button
                    $requisitionType = $cs->requisition->requisition_type ?? null;

                    if ($requisitionType === 'local') {
                        $workflowBtn = '<a href="' . route('comparative-statement.create-po', $cs->id) . '" class="cs-create-po-btn forword-icon-btn action-workflow-btn me-2" title="Create PO">
                                <i class="bi bi-file-earmark-plus-fill"></i> <span>Create PO</span>
                            </a>';
                    } elseif ($requisitionType === 'import') {
                        $workflowBtn = '<button data-id="' . $cs->id . '" type="button" class="cs-create-pi-btn forword-icon-btn action-workflow-btn me-2" title="Create PI">
                                <i class="bi bi-file-earmark-plus-fill"></i> <span>Create PI</span>
                            </button>';
                    }
                } elseif (in_array($status, [9, 10], true)) {
                    // PO/PI already created -> aro kaj pora hobe, ekhon kono button nai
                    $workflowBtn = '';
                }

                $view = '<button type="button" class="view action-icon-btn action-view me-2" data-id="' . $cs->id . '" title="View">
                <i class="bi bi-eye"></i>
             </button>';
                $printBtn = '<a href="' . route('comparative-statement.print', $cs->id) . '" target="_blank" class="print action-icon-btn action-print me-2" title="Print">
                <i class="bi bi-printer-fill"></i>
            </a>';
                $delete = '<form action="' . route('comparative-statement.destroy', $cs->id) . '" method="POST" class="d-inline delete-form">'
                    . csrf_field() . method_field('DELETE')
                    . '<button type="submit" class="delete action-icon-btn action-delete" title="Delete">
                    <i class="bi bi-trash text-danger"></i>
               </button>'
                    . '</form>';
                $edit = '<a href="' . route('comparative-statement.edit', $cs->id) . '" class="edit action-icon-btn action-edit me-2" title="Edit">
        <i class="bi bi-pencil"></i>
     </a>';

                return '<div class="d-flex align-items-center gap-2" style="flex-wrap: wrap;">'
                    . $workflowBtn . $view . $edit . $printBtn . $delete
                    . '</div>';
            })
            ->rawColumns(['suppliers', 'action', 'wing', 'status'])

            ->make(true);
    }

    public function view(ComparativeStatement $comparativeStatement)
    {
        $comparativeStatement->load([
            'requisition',
            'createdBy',
            'details.supplier',
            'details.items.product',
            'details.items.requisitionDetail', // Required Qty দেখানোর জন্য
        ]);

        return view('backend.comparative_statements.view', compact('comparativeStatement'));
    }

    public function printView(ComparativeStatement $comparativeStatement)
    {
        $comparativeStatement->load([
            'requisition',
            'createdBy',
            'details.supplier',
            'details.items.product',
            'details.items.requisitionDetail',
        ]);

        return view('backend.comparative_statements.print', compact('comparativeStatement'));
    }
    public function destroy(ComparativeStatement $comparativeStatement)
    {
        try {
            DB::beginTransaction();

            $requisition = $comparativeStatement->requisition;

            foreach ($comparativeStatement->details as $detail) {
                $detail->items()->delete();
                $detail->delete();
            }
            $comparativeStatement->delete();

            // এই CS delete হওয়ার পর requisition-এর coverage আবার হিসাব করে status ঠিক করা
            if ($requisition) {
                $totalCoveredQty = ComparativeStatementItem::whereHas('comparativeStatementDetail.comparativeStatement', function ($q) use ($requisition) {
                    $q->where('requisition_id', $requisition->id);
                })
                    ->selectRaw('requisition_detail_id, SUM(cs_qty) as covered_qty')
                    ->groupBy('requisition_detail_id')
                    ->pluck('covered_qty', 'requisition_detail_id');

                $isFullyCovered = $requisition->details->isNotEmpty()
                    && $requisition->details->every(function (RequisitionDetail $detail) use ($totalCoveredQty) {
                        $covered = (float) ($totalCoveredQty[$detail->id] ?? 0);
                        return $covered >= (float) $detail->required_stock;
                    });

                $hasAnyCs = ComparativeStatement::where('requisition_id', $requisition->id)->exists();

                $requisition->update([
                    'status' => $isFullyCovered ? 10 : ($hasAnyCs ? 9 : 7),
                ]);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Comparative Statement deleted successfully!',
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('CS Delete Error: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);

            return response()->json([
                'success' => false,
                'message' => 'Something went wrong! Please try again.',
            ], 500);
        }
    }

    /*
    |--------------------------------------------------------------------------
    | CREATE / STORE (আগের logic, শুধু store()-এর transaction bug ঠিক করা হয়েছে)
    |--------------------------------------------------------------------------
    */
    public function create(Requisition $requisition)
    {
        abort_unless(
            in_array((int) $requisition->status, [7, 9]),
            403,
            'Requisition must be approved by MD or partially generated to create CS.'
        );

        $requisition->load([
            'createdBy',
            'details.product.category',
            'details.product.brand',
            'details.product.productSize',
        ]);

        $alreadyCoveredQty = ComparativeStatementItem::whereHas('comparativeStatementDetail.comparativeStatement', function ($q) use ($requisition) {
            $q->where('requisition_id', $requisition->id);
        })
            ->selectRaw('requisition_detail_id, SUM(cs_qty) as covered_qty')
            ->groupBy('requisition_detail_id')
            ->pluck('covered_qty', 'requisition_detail_id');

        $suppliers = Supplier::orderBy('name')->get();
        $csNoPreview = ComparativeStatement::generateCsNo();

        $items = $requisition->details->map(function (RequisitionDetail $detail) use ($alreadyCoveredQty) {
            $product = $detail->product;

            $specification = $product?->description
                ?? collect([
                    $product?->brand?->name,
                    $product?->product_size,
                    $product?->category?->name,
                ])->filter()->implode(', ');

            $covered   = (float) ($alreadyCoveredQty[$detail->id] ?? 0);
            $remaining = max(0, (float) $detail->required_stock - $covered);

            return [
                'requisition_detail_id' => $detail->id,
                'product_id'            => $detail->product_id,
                'name'                  => $product?->name ?? 'Unknown Product',
                'code'                  => $product?->product_code ?? '-',
                'size'                  => $product?->product_size ?? '-',
                'specification'         => $specification ?: '-',
                'required_qty'          => (float) $detail->required_stock,
                'already_covered_qty'   => $covered,
                'remaining_qty'         => $remaining,
            ];
        })->values();

        return view('backend.comparative_statements.create', compact(
            'requisition',
            'suppliers',
            'csNoPreview',
            'items'
        ));
    }
    public function edit(ComparativeStatement $comparativeStatement)
    {
        $requisition = $comparativeStatement->requisition()->with([
            'createdBy',
            'details.product.category',
            'details.product.brand',
            'details.product.productSize',
        ])->firstOrFail();

        $comparativeStatement->load(['details.items']);

        // এই CS বাদে requisition-এর বাকি সব CS থেকে covered qty (edit করার সময় নিজের qty
        // remaining_qty থেকে বাদ পড়া ঠিক না, তাই এই CS-কে exclude করে হিসাব করা হচ্ছে)
        $alreadyCoveredQty = ComparativeStatementItem::whereHas('comparativeStatementDetail.comparativeStatement', function ($q) use ($requisition, $comparativeStatement) {
            $q->where('requisition_id', $requisition->id)
                ->where('id', '!=', $comparativeStatement->id);
        })
            ->selectRaw('requisition_detail_id, SUM(cs_qty) as covered_qty')
            ->groupBy('requisition_detail_id')
            ->pluck('covered_qty', 'requisition_detail_id');

        // এই CS-এর নিজের item গুলো থেকে qty per requisition_detail_id (সব supplier-এ same qty থাকে)
        $currentCsQty = $comparativeStatement->details
            ->flatMap(fn($detail) => $detail->items)
            ->groupBy('requisition_detail_id')
            ->map(fn($rows) => (float) $rows->first()->cs_qty);

        $suppliers = Supplier::orderBy('name')->get();

        $items = $requisition->details->map(function (RequisitionDetail $detail) use ($alreadyCoveredQty, $currentCsQty) {
            $product = $detail->product;

            $specification = $product?->description
                ?? collect([
                    $product?->brand?->name,
                    $product?->product_size,
                    $product?->category?->name,
                ])->filter()->implode(', ');

            $covered   = (float) ($alreadyCoveredQty[$detail->id] ?? 0);
            $remaining = max(0, (float) $detail->required_stock - $covered);

            return [
                'requisition_detail_id' => $detail->id,
                'product_id'            => $detail->product_id,
                'name'                  => $product?->name ?? 'Unknown Product',
                'code'                  => $product?->product_code ?? '-',
                'size'                  => $product?->product_size ?? '-',
                'specification'         => $specification ?: '-',
                'required_qty'          => (float) $detail->required_stock,
                'already_covered_qty'   => $covered,
                'remaining_qty'         => $remaining,
                'current_cs_qty'        => (float) ($currentCsQty[$detail->id] ?? 0),
            ];
        })->values();

        // Existing groups JS-এ pre-load করার জন্য supplier_id key করে সাজানো হচ্ছে
        $existingGroups = $comparativeStatement->details->map(function (ComparativeStatementDetail $detail) {
            return [
                'supplier_id'      => (string) $detail->supplier_id,
                'supplier_name'    => $detail->supplier->name ?? '-',
                'quotation_number' => $detail->quotation_number,
                'quotation_date' => $detail->quotation_date
                    ? \Carbon\Carbon::parse($detail->quotation_date)->format('Y-m-d')
                    : null,
                'place_of_supply'  => $detail->place_of_supply,
                'time_of_supply' => $detail->time_of_supply
                    ? \Carbon\Carbon::parse($detail->time_of_supply)->format('Y-m-d')
                    : null,
                'note'             => $detail->note,
                'prices'           => $detail->items->pluck('unit_price', 'requisition_detail_id'),
            ];
        })->values();

        return view('backend.comparative_statements.edit', compact(
            'requisition',
            'comparativeStatement',
            'suppliers',
            'items',
            'existingGroups'
        ));
    }

    public function update(Request $request, ComparativeStatement $comparativeStatement)
    {
        $requisition = $comparativeStatement->requisition;

        abort_unless(
            in_array((int) $requisition->status, [7, 9]),
            403,
            'Requisition must be approved by MD or partially generated to edit CS.'
        );

        $request->validate([
            'cs_date' => 'required|date',
            'remarks' => 'nullable|string',
            'items'   => 'required|json',
            'groups'  => 'required|json',
        ]);

        $items  = json_decode($request->items, true);
        $groups = json_decode($request->groups, true);

        if (empty($items) || !is_array($items)) {
            return response()->json([
                'success' => false,
                'message' => 'Please select at least one item with a CS Qty!',
            ], 422);
        }

        if (empty($groups) || !is_array($groups)) {
            return response()->json([
                'success' => false,
                'message' => 'Please add at least one supplier quotation!',
            ], 422);
        }

        try {
            DB::beginTransaction();

            // এই CS বাদে বাকি সব CS থেকে covered qty (নিজের পুরনো qty exclude করে হিসাব)
            $alreadyCoveredQty = ComparativeStatementItem::whereHas('comparativeStatementDetail.comparativeStatement', function ($q) use ($requisition, $comparativeStatement) {
                $q->where('requisition_id', $requisition->id)
                    ->where('id', '!=', $comparativeStatement->id);
            })
                ->selectRaw('requisition_detail_id, SUM(cs_qty) as covered_qty')
                ->groupBy('requisition_detail_id')
                ->pluck('covered_qty', 'requisition_detail_id');

            $itemMap = [];
            foreach ($items as $item) {
                $csQty = (float) ($item['cs_qty'] ?? 0);

                if (empty($item['requisition_detail_id']) || $csQty <= 0) {
                    continue;
                }

                $detail = RequisitionDetail::find($item['requisition_detail_id']);
                if (!$detail || $detail->requisition_id !== $requisition->id) {
                    continue;
                }

                $covered   = (float) ($alreadyCoveredQty[$detail->id] ?? 0);
                $remaining = (float) $detail->required_stock - $covered;

                if ($remaining <= 0) {
                    continue;
                }

                $csQty = min($csQty, $remaining);
                if ($csQty <= 0) {
                    continue;
                }

                $itemMap[$detail->id] = [
                    'requisition_detail_id' => $detail->id,
                    'product_id'            => $detail->product_id,
                    'cs_qty'                => $csQty,
                ];
            }

            if (empty($itemMap)) {
                DB::rollBack();
                return response()->json([
                    'success' => false,
                    'message' => 'No valid items to save. All items may already be fully covered.',
                ], 422);
            }

            // পুরনো সব detail/items মুছে নতুন করে save করা হচ্ছে
            foreach ($comparativeStatement->details as $oldDetail) {
                $oldDetail->items()->delete();
                $oldDetail->delete();
            }

            $anyGroupSaved = false;

            foreach ($groups as $group) {
                if (empty($group['supplier_id']) || empty($group['quotes']) || !is_array($group['quotes'])) {
                    continue;
                }

                $csDetail = ComparativeStatementDetail::create([
                    'cs_id'            => $comparativeStatement->id,
                    'supplier_id'      => $group['supplier_id'],
                    'quotation_number' => $group['quotation_number'] ?? null,
                    'quotation_date'   => $group['quotation_date'] ?? null,
                    'note'             => $group['note'] ?? null,
                    'total_amount'     => 0,
                    'time_of_supply'   => $group['time_of_supply'] ?? null,
                    'place_of_supply'  => $group['place_of_supply'] ?? null,
                ]);

                $detailTotal = 0;

                foreach ($group['quotes'] as $quote) {
                    $detailId  = $quote['requisition_detail_id'] ?? null;
                    $unitPrice = (float) ($quote['unit_price'] ?? 0);

                    if (!$detailId || $unitPrice <= 0 || !isset($itemMap[$detailId])) {
                        continue;
                    }

                    $itemData = $itemMap[$detailId];
                    $total    = $itemData['cs_qty'] * $unitPrice;
                    $detailTotal += $total;

                    ComparativeStatementItem::create([
                        'cs_detail_id'           => $csDetail->id,
                        'requisition_detail_id'  => $itemData['requisition_detail_id'],
                        'product_id'             => $itemData['product_id'],
                        'cs_qty'                 => $itemData['cs_qty'],
                        'unit_price'             => $unitPrice,
                        'total'                  => $total,
                    ]);

                    $anyGroupSaved = true;
                }

                $csDetail->update(['total_amount' => $detailTotal]);
            }

            if (!$anyGroupSaved) {
                DB::rollBack();
                return response()->json([
                    'success' => false,
                    'message' => 'No valid supplier quotations to save. Please check unit prices.',
                ], 422);
            }

            $comparativeStatement->update([
                'cs_date' => $request->cs_date,
                'remarks' => $request->remarks,
            ]);

            // পুরো requisition এর coverage আবার হিসাব করে status ঠিক করা (এই CS সহ)
            $totalCoveredQty = ComparativeStatementItem::whereHas('comparativeStatementDetail.comparativeStatement', function ($q) use ($requisition) {
                $q->where('requisition_id', $requisition->id);
            })
                ->selectRaw('requisition_detail_id, SUM(cs_qty) as covered_qty')
                ->groupBy('requisition_detail_id')
                ->pluck('covered_qty', 'requisition_detail_id');

            $isFullyCovered = $requisition->details->every(function (RequisitionDetail $detail) use ($totalCoveredQty) {
                $covered = (float) ($totalCoveredQty[$detail->id] ?? 0);
                return $covered >= (float) $detail->required_stock;
            });

            $requisition->update([
                'status' => $isFullyCovered ? 10 : 9,
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => $isFullyCovered
                    ? 'CS Updated Successfully!'
                    : 'CS Updated Partially! Remaining quantity still needs a CS.',
                'cs_no'   => $comparativeStatement->cs_no,
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('CS Update Error: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);

            return response()->json([
                'success' => false,
                'message' => 'Something went wrong! Please try again.',
                'error'   => config('app.debug') ? $e->getMessage() : null,
            ], 500);
        }
    }
    public function store(Request $request, Requisition $requisition)
    {
        abort_unless(
            in_array((int) $requisition->status, [7, 9]),
            403,
            'Requisition must be approved by MD or partially generated to create CS.'
        );

        $request->validate([
            'cs_date' => 'required|date',
            'remarks' => 'nullable|string',
            'items'   => 'required|json',
            'groups'  => 'required|json',
        ]);

        $items  = json_decode($request->items, true);
        $groups = json_decode($request->groups, true);

        if (empty($items) || !is_array($items)) {
            return response()->json([
                'success' => false,
                'message' => 'Please select at least one item with a CS Qty!',
            ], 422);
        }

        if (empty($groups) || !is_array($groups)) {
            return response()->json([
                'success' => false,
                'message' => 'Please add at least one supplier quotation!',
            ], 422);
        }
        $cs = ComparativeStatement::create([
            'requisition_id' => $requisition->id,
            'cs_no'          => ComparativeStatement::generateCsNo(),
            'cs_date'        => $request->cs_date,
            'remarks'        => $request->remarks,
            'status'         => 1,
            'created_by'     => Auth::id(),
        ]);

        $alreadyCoveredQty = ComparativeStatementItem::whereHas('comparativeStatementDetail.comparativeStatement', function ($q) use ($requisition) {
            $q->where('requisition_id', $requisition->id);
        })
            ->selectRaw('requisition_detail_id, SUM(cs_qty) as covered_qty')
            ->groupBy('requisition_detail_id')
            ->pluck('covered_qty', 'requisition_detail_id');

        $itemMap = [];
        foreach ($items as $item) {
            $csQty = (float) ($item['cs_qty'] ?? 0);

            if (empty($item['requisition_detail_id']) || $csQty <= 0) {
                continue;
            }

            $detail = RequisitionDetail::find($item['requisition_detail_id']);
            if (!$detail || $detail->requisition_id !== $requisition->id) {
                continue;
            }

            $covered   = (float) ($alreadyCoveredQty[$detail->id] ?? 0);
            $remaining = (float) $detail->required_stock - $covered;

            if ($remaining <= 0) {
                continue;
            }

            $csQty = min($csQty, $remaining);
            if ($csQty <= 0) {
                continue;
            }

            $itemMap[$detail->id] = [
                'requisition_detail_id' => $detail->id,
                'product_id'            => $detail->product_id,
                'cs_qty'                => $csQty,
            ];
        }

        if (empty($itemMap)) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'No valid items to save. All items may already be fully covered.',
            ], 422);
        }

        $anyGroupSaved = false;

        foreach ($groups as $group) {
            if (empty($group['supplier_id']) || empty($group['quotes']) || !is_array($group['quotes'])) {
                continue;
            }

            // Fix: Place of Supply এবং Time of Supply এখন প্রতিটা supplier group এর নিজস্ব ভ্যালু থেকে আসছে,
            // আগের মতো $request->time_of_supply বা $requisition->place_of_supply থেকে না।
            $csDetail = ComparativeStatementDetail::create([
                'cs_id'            => $cs->id,
                'supplier_id'      => $group['supplier_id'],
                'quotation_number' => $group['quotation_number'] ?? null,
                'quotation_date'   => $group['quotation_date'] ?? null,
                'note'             => $group['note'] ?? null,
                'total_amount'     => 0,
                'time_of_supply'   => $group['time_of_supply'] ?? null,
                'place_of_supply'  => $group['place_of_supply'] ?? null,
            ]);

            $detailTotal = 0;

            foreach ($group['quotes'] as $quote) {
                $detailId  = $quote['requisition_detail_id'] ?? null;
                $unitPrice = (float) ($quote['unit_price'] ?? 0);

                if (!$detailId || $unitPrice <= 0 || !isset($itemMap[$detailId])) {
                    continue;
                }

                $itemData = $itemMap[$detailId];
                $total    = $itemData['cs_qty'] * $unitPrice;
                $detailTotal += $total;

                ComparativeStatementItem::create([
                    'cs_detail_id'           => $csDetail->id,
                    'requisition_detail_id'  => $itemData['requisition_detail_id'],
                    'product_id'             => $itemData['product_id'],
                    'cs_qty'                 => $itemData['cs_qty'],
                    'unit_price'             => $unitPrice,
                    'total'                  => $total,
                ]);

                $anyGroupSaved = true;
            }

            $csDetail->update(['total_amount' => $detailTotal]);
        }

        if (!$anyGroupSaved) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'No valid supplier quotations to save. Please check unit prices.',
            ], 422);
        }

        $totalCoveredQty = ComparativeStatementItem::whereHas('comparativeStatementDetail.comparativeStatement', function ($q) use ($requisition) {
            $q->where('requisition_id', $requisition->id);
        })
            ->selectRaw('requisition_detail_id, SUM(cs_qty) as covered_qty')
            ->groupBy('requisition_detail_id')
            ->pluck('covered_qty', 'requisition_detail_id');

        $isFullyCovered = $requisition->details->every(function (RequisitionDetail $detail) use ($totalCoveredQty) {
            $covered = (float) ($totalCoveredQty[$detail->id] ?? 0);
            return $covered >= (float) $detail->required_stock;
        });

        $requisition->update([
            'cs_generated_by' => Auth::id(),
            'cs_generated_at' => now(),
            'status'          => $isFullyCovered ? 10 : 9,
        ]);

        // DB::commit();

        return response()->json([
            'success' => true,
            'message' => $isFullyCovered
                ? 'CS Generated Successfully!'
                : 'CS Generated Partially! Remaining quantity still needs a CS.',
            'cs_no'   => $cs->cs_no,
        ]);
        try {
            DB::beginTransaction();

            $cs = ComparativeStatement::create([
                'requisition_id' => $requisition->id,
                'cs_no'          => ComparativeStatement::generateCsNo(),
                'cs_date'        => $request->cs_date,
                'remarks'        => $request->remarks,
                'status'         => 1,
                'created_by'     => Auth::id(),
            ]);

            $alreadyCoveredQty = ComparativeStatementItem::whereHas('comparativeStatementDetail.comparativeStatement', function ($q) use ($requisition) {
                $q->where('requisition_id', $requisition->id);
            })
                ->selectRaw('requisition_detail_id, SUM(cs_qty) as covered_qty')
                ->groupBy('requisition_detail_id')
                ->pluck('covered_qty', 'requisition_detail_id');

            $itemMap = [];
            foreach ($items as $item) {
                $csQty = (float) ($item['cs_qty'] ?? 0);

                if (empty($item['requisition_detail_id']) || $csQty <= 0) {
                    continue;
                }

                $detail = RequisitionDetail::find($item['requisition_detail_id']);
                if (!$detail || $detail->requisition_id !== $requisition->id) {
                    continue;
                }

                $covered   = (float) ($alreadyCoveredQty[$detail->id] ?? 0);
                $remaining = (float) $detail->required_stock - $covered;

                if ($remaining <= 0) {
                    continue;
                }

                $csQty = min($csQty, $remaining);
                if ($csQty <= 0) {
                    continue;
                }

                $itemMap[$detail->id] = [
                    'requisition_detail_id' => $detail->id,
                    'product_id'            => $detail->product_id,
                    'cs_qty'                => $csQty,
                ];
            }

            if (empty($itemMap)) {
                DB::rollBack();
                return response()->json([
                    'success' => false,
                    'message' => 'No valid items to save. All items may already be fully covered.',
                ], 422);
            }

            $anyGroupSaved = false;

            foreach ($groups as $group) {
                if (empty($group['supplier_id']) || empty($group['quotes']) || !is_array($group['quotes'])) {
                    continue;
                }

                // Fix: Place of Supply এবং Time of Supply এখন প্রতিটা supplier group এর নিজস্ব ভ্যালু থেকে আসছে,
                // আগের মতো $request->time_of_supply বা $requisition->place_of_supply থেকে না।
                $csDetail = ComparativeStatementDetail::create([
                    'cs_id'            => $cs->id,
                    'supplier_id'      => $group['supplier_id'],
                    'quotation_number' => $group['quotation_number'] ?? null,
                    'quotation_date'   => $group['quotation_date'] ?? null,
                    'note'             => $group['note'] ?? null,
                    'total_amount'     => 0,
                    'time_of_supply'   => $group['time_of_supply'] ?? null,
                    'place_of_supply'  => $group['place_of_supply'] ?? null,
                ]);

                $detailTotal = 0;

                foreach ($group['quotes'] as $quote) {
                    $detailId  = $quote['requisition_detail_id'] ?? null;
                    $unitPrice = (float) ($quote['unit_price'] ?? 0);

                    if (!$detailId || $unitPrice <= 0 || !isset($itemMap[$detailId])) {
                        continue;
                    }

                    $itemData = $itemMap[$detailId];
                    $total    = $itemData['cs_qty'] * $unitPrice;
                    $detailTotal += $total;

                    ComparativeStatementItem::create([
                        'cs_detail_id'           => $csDetail->id,
                        'requisition_detail_id'  => $itemData['requisition_detail_id'],
                        'product_id'             => $itemData['product_id'],
                        'cs_qty'                 => $itemData['cs_qty'],
                        'unit_price'             => $unitPrice,
                        'total'                  => $total,
                    ]);

                    $anyGroupSaved = true;
                }

                $csDetail->update(['total_amount' => $detailTotal]);
            }

            if (!$anyGroupSaved) {
                DB::rollBack();
                return response()->json([
                    'success' => false,
                    'message' => 'No valid supplier quotations to save. Please check unit prices.',
                ], 422);
            }

            $totalCoveredQty = ComparativeStatementItem::whereHas('comparativeStatementDetail.comparativeStatement', function ($q) use ($requisition) {
                $q->where('requisition_id', $requisition->id);
            })
                ->selectRaw('requisition_detail_id, SUM(cs_qty) as covered_qty')
                ->groupBy('requisition_detail_id')
                ->pluck('covered_qty', 'requisition_detail_id');

            $isFullyCovered = $requisition->details->every(function (RequisitionDetail $detail) use ($totalCoveredQty) {
                $covered = (float) ($totalCoveredQty[$detail->id] ?? 0);
                return $covered >= (float) $detail->required_stock;
            });

            $requisition->update([
                'cs_generated_by' => Auth::id(),
                'cs_generated_at' => now(),
                'status'          => $isFullyCovered ? 10 : 9,
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => $isFullyCovered
                    ? 'CS Generated Successfully!'
                    : 'CS Generated Partially! Remaining quantity still needs a CS.',
                'cs_no'   => $cs->cs_no,
            ]);
        } catch (\Exception $e) {
            DB::rollBack();


            return response()->json([
                'success' => false,
                'message' => 'Something went wrong! Please try again.',
                $e->getMessage()
            ], 500);
        }
    }

    public function createPO(ComparativeStatement $comparativeStatement)
    {
        abort_unless(
            (int) $comparativeStatement->status === 7,
            403,
            'Comparative Statement must be approved by MD to create PO.'
        );

        $comparativeStatement->load([
            'requisition',
            'createdBy',
            'details.supplier',
            'details.items.product',
            'details.items.requisitionDetail',
        ]);


        return view('backend.purchase_orders.create', compact('comparativeStatement'));
    }

    public function storePO(Request $request, ComparativeStatement $comparativeStatement)
    {
        abort_unless(
            (int) $comparativeStatement->status === 7,
            403,
            'Comparative Statement must be approved by MD to create PO.'
        );

        $request->validate([
            'cs_details_id'   => 'required|exists:comparative_statement_details,id',
            'po_date'         => 'required|date',
            'time_of_supply'  => 'required|date',
            'place_of_supply' => 'required|string|max:255',
            'contact_person'  => 'required|string|max:255',
            'remarks'         => 'nullable|string',
        ]);

        $csDetail = ComparativeStatementDetail::with('items')
            ->where('cs_id', $comparativeStatement->id)
            ->find($request->cs_details_id);


        if (!$csDetail) {
            return response()->json([
                'success' => false,
                'message' => 'Selected supplier quotation not found for this Comparative Statement.',
            ], 422);
        }

        if ($csDetail->items->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'Selected quotation has no items.',
            ], 422);
        }

        try {
            DB::beginTransaction();

            $po = PurchaseOrder::create([
                'requisition_id'  => $comparativeStatement->requisition_id,
                'cs_id'           => $comparativeStatement->id,
                'supplier_id'           => $csDetail->supplier_id,
                'cs_details_id'   => $csDetail->id,
                'po_no'           => PurchaseOrder::generatePoNo(),
                'po_date'         => $request->po_date,
                'time_of_supply'  => $request->time_of_supply ?? $csDetail->time_of_supply,
                'place_of_supply' => $request->place_of_supply ?? $csDetail->place_of_supply,
                'contact_person'  => $request->contact_person,
                'status'          => 1,
                'remarks'         => $request->remarks,
                'created_by'      => Auth::id(),
            ]);

            foreach ($csDetail->items as $item) {
                PurchaseOrderDetail::create([
                    'po_id'                 => $po->id,
                    'requisition_detail_id' => $item->requisition_detail_id,
                    'product_id'            => $item->product_id,
                    'po_qty'                => $item->cs_qty,
                    'unit_price'            => $item->unit_price,
                    'total'                 => $item->total,
                ]);
            }

            // Mark this CS as converted to PO
            $comparativeStatement->update(['status' => 9]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Purchase Order created successfully!',
                'po_no'   => $po->po_no,
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('PO Store Error: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);

            return response()->json([
                'success' => false,
                'message' => 'Something went wrong! Please try again.',
                'error'   => config('app.debug') ? $e->getMessage() : null,
            ], 500);
        }
    }
}
