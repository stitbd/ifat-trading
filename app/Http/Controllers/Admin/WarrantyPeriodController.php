<?php

namespace App\Http\Controllers\Admin;

use Alert;
use App\Http\Controllers\Controller;
use App\Models\WarrantyPeriod;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class WarrantyPeriodController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            'auth',
            new Middleware('permission:warranty_period.view', only: ['index', 'getdata']),
            new Middleware('permission:warranty_period.create', only: ['store']),
            new Middleware('permission:warranty_period.edit', only: ['update', 'statusUpdate']),
            new Middleware('permission:warranty_period.delete', only: ['distroy']),
        ];
    }

    public function statusUpdate(Request $request, $id)
    {
        $find = WarrantyPeriod::find($id);

        if (!$find) {
            return response()->json(['success' => false, 'message' => 'Warranty Period not found!'], 404);
        }

        try {
            $find->update([
                'status' => $find->status ? 0 : 1, // toggle kora
                'updated_by' => auth()->user()->id,
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

    public function index()
    {
        return view('backend.warranty_period.index');
    }

    public function getdata(Request $request)
    {
        if ($request->ajax()) {
            $data = WarrantyPeriod::orderBy('created_at', 'desc')->get();

            return DataTables::of($data)
                ->addColumn('status', function ($row) {
                    return $row->status
                        ? '<span class="badge badge-success">Active</span>'
                        : '<span class="badge badge-danger">Inactive</span>';
                })
                ->addColumn('action', function ($row) {
                    $editBtn = '<button data-id="' . $row->id . '" type="button" class="edit mx-2 action-icon-btn action-edit" title="Edit">
                    <i class="fa-solid fa-pen-to-square"></i>
                </button>';

                    $deleteUrl = route('warranty-period.distroy', $row->id);
                    $csrfToken = csrf_field();
                    $method = method_field('DELETE');

                    $deleteBtn = '<form action="' . $deleteUrl . '" method="POST" class="d-inline">
                        ' . $csrfToken . '
                        ' . $method . '
                        <button type="submit" class="delete action-icon-btn action-delete" title="Delete">
                        <i class="bi bi-trash-fill"></i>
                    </button>
                    </form>';
                    if ($row->status) {
                        $statusBtn = '<button data-id="' . $row->id . '" type="button" class="status-toggle action-icon-btn action-status-on" title="Active - click to deactivate">
                        <i class="bi bi-toggle-on"></i>
                    </button>';
                    } else {
                        $statusBtn = '<button data-id="' . $row->id . '" type="button" class="status-toggle action-icon-btn action-status-off" title="Inactive - click to activate">
                        <i class="bi bi-toggle-off"></i>
                    </button>';
                    }
                    return '<div class="d-flex align-items-center mb-2">' . $statusBtn   . $editBtn . $deleteBtn . '</div>';
                })
                ->rawColumns(['action', 'status'])
                ->make(true);
        }
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'value' => 'required',
            'status' => 'nullable|boolean',
        ]);

        try {
            DB::beginTransaction();

            WarrantyPeriod::create([
                'title' => $request->title,
                'value' => $request->value,
                'status' => $request->status ?? 1,
                'created_by' => auth()->user()->id,
                'updated_by' => auth()->user()->id,
            ]);

            DB::commit();
            return response()->json(['success' => true, 'message' => 'Warranty Period Created Successfully!']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong! Please try again.',
            ], 500);
        }
    }

    public function edit($id)
    {
        $data = WarrantyPeriod::findOrFail($id);
        return view('backend.warranty_period.edit', compact('data'));
    }

    public function update(Request $request, $id)
    {
        $find = WarrantyPeriod::find($id);

        if (!$find) {
            return response()->json(['success' => false, 'message' => 'Warranty Period not found!'], 404);
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'value' => 'required',
            'status' => 'nullable|boolean',
        ]);

        try {
            DB::beginTransaction();

            $find->update([
                'title' => $request->title,
                'value' => $request->value,
                'status' => $request->status ?? $find->status,
                'updated_by' => auth()->user()->id,
            ]);

            DB::commit();
            return response()->json(['success' => true, 'message' => 'Warranty Period Updated Successfully!']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong! Please try again.',
            ], 500);
        }
    }

    public function distroy($id)
    {
        $find = WarrantyPeriod::find($id);

        if (!$find) {
            Alert::error('Error', 'Warranty Period not found!');
            return redirect()->route('warranty-period.index');
        }

        $find->update([
            'deleted_by' => auth()->user()->id,
        ]);

        $find->delete(); // soft delete

        Alert::success('Success', 'Warranty Period deleted Successful!');
        return redirect()->route('warranty-period.index');
    }
}
