<?php

namespace App\Http\Controllers\Admin;

use Alert;
use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\ProductSize;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class ProductSizeController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            'auth',
            new Middleware('permission:product_size.view', only: ['index', 'getdata']),
            new Middleware('permission:product_size.create', only: ['store']),
            new Middleware('permission:product_size.edit', only: ['update', 'statusUpdate']),
            new Middleware('permission:product_size.delete', only: ['distroy']),
        ];
    }

    public function statusUpdate(Request $request, $id)
    {
        $find = ProductSize::find($id);

        if (!$find) {
            return response()->json(['success' => false, 'message' => 'Product Size not found!'], 404);
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
        $categories = Category::where('status', 1)->orderBy('name')->get();
        return view('backend.product_size.index', compact('categories'));
    }

    public function getdata(Request $request)
    {
        if ($request->ajax()) {
            $data = ProductSize::with('category')->orderBy('created_at', 'desc')->get();

            return DataTables::of($data)
                ->addColumn('category', function ($row) {
                    return $row->category->name ?? '-';
                })
                ->addColumn('status', function ($row) {
                    return $row->status
                        ? '<span class="badge badge-success">Active</span>'
                        : '<span class="badge badge-danger">Inactive</span>';
                })
                ->addColumn('action', function ($row) {
                    $editBtn = '<button data-id="' . $row->id . '" type="button" class="edit mx-2 action-icon-btn action-edit" title="Edit">
                    <i class="fa-solid fa-pen-to-square"></i>
                </button>';

                    $deleteUrl = route('product-size.distroy', $row->id);
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
                    return '<div class="d-flex align-items-center mb-2">' . $statusBtn . $editBtn . $deleteBtn .   '</div>';
                })
                ->rawColumns(['action', 'category', 'status'])
                ->make(true);
        }
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'status' => 'nullable|boolean',
        ]);

        try {
            DB::beginTransaction();

            ProductSize::create([
                'name' => $request->name,
                'category_id' => $request->category_id,
                'status' => $request->status ?? 1,
                'created_by' => auth()->user()->id,
                'updated_by' => auth()->user()->id,
            ]);

            DB::commit();
            return response()->json(['success' => true, 'message' => 'Product Size Created Successfully!']);
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
        $data = ProductSize::findOrFail($id);
        $categories = Category::where('status', 1)->orderBy('name')->get();
        return view('backend.product_size.edit', compact('data', 'categories'));
    }

    public function update(Request $request, $id)
    {
        $find = ProductSize::find($id);

        if (!$find) {
            return response()->json(['success' => false, 'message' => 'Product Size not found!'], 404);
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'status' => 'nullable|boolean',
        ]);

        try {
            DB::beginTransaction();

            $find->update([
                'name' => $request->name,
                'category_id' => $request->category_id,
                'status' => $request->status ?? $find->status,
                'updated_by' => auth()->user()->id,
            ]);

            DB::commit();
            return response()->json(['success' => true, 'message' => 'Product Size Updated Successfully!']);
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
        $find = ProductSize::find($id);

        if (!$find) {
            Alert::error('Error', 'Product Size not found!');
            return redirect()->route('product-size.index');
        }

        $find->update([
            'deleted_by' => auth()->user()->id,
        ]);

        $find->delete(); // soft delete

        Alert::success('Success', 'Product Size deleted Successful!');
        return redirect()->route('product-size.index');
    }
}
