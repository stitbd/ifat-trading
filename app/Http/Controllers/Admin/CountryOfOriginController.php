<?php

namespace App\Http\Controllers\Admin;

use Alert;
use App\Http\Controllers\Controller;
use App\Models\CountryOfOrigin;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class CountryOfOriginController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            'auth',
            new Middleware('permission:country_of_origin.view', only: ['index', 'getdata']),
            new Middleware('permission:country_of_origin.create', only: ['store']),
            new Middleware('permission:country_of_origin.edit', only: ['update', 'statusUpdate']),
            new Middleware('permission:country_of_origin.delete', only: ['distroy']),
        ];
    }

    public function statusUpdate(Request $request, $id)
    {
        $find = CountryOfOrigin::find($id);

        if (!$find) {
            return response()->json(['success' => false, 'message' => 'Country of Origin not found!'], 404);
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
        return view('backend.country_of_origin.index');
    }

    public function getdata(Request $request)
    {
        if ($request->ajax()) {
            $data = CountryOfOrigin::orderBy('created_at', 'desc')->get();

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

                    $deleteUrl = route('country-of-origin.distroy', $row->id);
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

                    return '<div class="d-flex align-items-center mb-2">' . $statusBtn . $editBtn . $deleteBtn  . '</div>';
                })
                ->rawColumns(['action', 'status'])
                ->make(true);
        }
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'status' => 'nullable|boolean',
        ]);

        try {
            DB::beginTransaction();

            CountryOfOrigin::create([
                'name' => $request->name,
                'status' => $request->status ?? 1,
                'created_by' => auth()->user()->id,
                'updated_by' => auth()->user()->id,
            ]);

            DB::commit();
            return response()->json(['success' => true, 'message' => 'Country of Origin Created Successfully!']);
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
        $data = CountryOfOrigin::findOrFail($id);
        return view('backend.country_of_origin.edit', compact('data'));
    }

    public function update(Request $request, $id)
    {
        $find = CountryOfOrigin::find($id);

        if (!$find) {
            return response()->json(['success' => false, 'message' => 'Country of Origin not found!'], 404);
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'status' => 'nullable|boolean',
        ]);

        try {
            DB::beginTransaction();

            $find->update([
                'name' => $request->name,
                'status' => $request->status ?? $find->status,
                'updated_by' => auth()->user()->id,
            ]);

            DB::commit();
            return response()->json(['success' => true, 'message' => 'Country of Origin Updated Successfully!']);
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
        $find = CountryOfOrigin::find($id);

        if (!$find) {
            Alert::error('Error', 'Country of Origin not found!');
            return redirect()->route('country-of-origin.index');
        }

        $find->update([
            'deleted_by' => auth()->user()->id,
        ]);

        $find->delete(); // soft delete

        Alert::success('Success', 'Country of Origin deleted Successful!');
        return redirect()->route('country-of-origin.index');
    }
}
