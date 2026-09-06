<?php

namespace App\Http\Controllers\Admin;

use Alert;
use App\Http\Controllers\Controller;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class SupplierController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            'auth',
            new Middleware('permission:supplier.view', only: ['index', 'getdata', 'view']),
            new Middleware('permission:supplier.create', only: ['store']),
            new Middleware('permission:supplier.edit', only: ['edit', 'update']),
            new Middleware('permission:supplier.delete', only: ['destroy']),
        ];
    }

    public function index()
    {
        return view('backend.supplier.index');
    }

    public function getdata(Request $request)
    {
        if ($request->ajax()) {
            $data = Supplier::orderBy('created_at', 'desc')->get();

            return DataTables::of($data)
                ->addColumn('image', function ($row) {
                    if ($row->image) {
                        return '<img src="' . asset('image/supplier/' . $row->image) . '" alt="' . $row->name . '">';
                    }
                    return '<img src="' . asset('user.avif') . '" alt="No Image">';
                })
                ->addColumn('action', function ($row) {

                    $viewBtn = '<button data-id="' . $row->id . '" type="button" class="view action-icon-btn action-view me-2" title="View">
                        <i class="bi bi-eye-fill"></i>
                    </button>';

                    $editBtn = '<button data-id="' . $row->id . '" type="button" class="edit action-icon-btn action-edit me-2" title="Edit">
                        <i class="fa-solid fa-pen-to-square"></i>
                    </button>';

                    $deleteUrl = route('supplier.destroy', $row->id);
                    $csrfToken = csrf_field();
                    $method = method_field('DELETE');

                    $deleteBtn = '<form action="' . $deleteUrl . '" method="POST" class="d-inline">
                        ' . $csrfToken . '
                        ' . $method . '
                        <button type="submit" class="delete action-icon-btn action-delete" title="Delete">
                            <i class="bi bi-trash-fill"></i>
                        </button>
                    </form>';

                    return '<div class="d-flex align-items-center gap-2">' . $viewBtn . $editBtn . $deleteBtn . '</div>';
                })
                ->rawColumns(['image', 'action'])
                ->make(true);
        }
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:50',
            'address' => 'nullable|string',
            'company_name' => 'nullable|string|max:255',
            'company_email' => 'nullable|email|max:255',
            'company_address' => 'nullable|string',
            'image' => 'nullable|file|image|max:2048',
        ]);

        try {
            DB::beginTransaction();

            $imagePath = null;

            if ($request->hasFile('image')) {
                $file = $request->file('image');
                $extension = $file->getClientOriginalExtension();
                $filename = time() . '_' . uniqid() . '.' . $extension;
                $file->move(public_path('image/supplier/'), $filename);
                $imagePath = $filename;
            }

            Supplier::create([
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'address' => $request->address,
                'company_name' => $request->company_name,
                'company_email' => $request->company_email,
                'company_address' => $request->company_address,
                'image' => $imagePath,
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Supplier Created Successfully!',
            ]);
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
        $data = Supplier::findOrFail($id);
        return view('backend.supplier.edit', compact('data'));
    }

    public function view($id)
    {
        $data = Supplier::findOrFail($id);
        return view('backend.supplier.view', compact('data'));
    }

    public function update(Request $request, $id)
    {
        $find = Supplier::find($id);

        if (!$find) {
            return response()->json(['success' => false, 'message' => 'Supplier not found!'], 404);
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:50',
            'address' => 'nullable|string',
            'company_name' => 'nullable|string|max:255',
            'company_email' => 'nullable|email|max:255',
            'company_address' => 'nullable|string',
            'image' => 'nullable|file|image|max:2048',
        ]);

        try {
            DB::beginTransaction();

            $data = [
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'address' => $request->address,
                'company_name' => $request->company_name,
                'company_email' => $request->company_email,
                'company_address' => $request->company_address,
            ];

            if ($request->hasFile('image')) {

                if ($find->image !== null) {
                    $oldImagePath = public_path('image/supplier/' . $find->image);
                    if (file_exists($oldImagePath)) {
                        unlink($oldImagePath);
                    }
                }

                $file = $request->file('image');
                $extension = $file->getClientOriginalExtension();
                $filename = time() . '_' . uniqid() . '.' . $extension;
                $file->move(public_path('image/supplier/'), $filename);
                $data += ['image' => $filename];
            }

            $find->update($data);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Supplier Updated Successfully!',
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong! Please try again.',
            ], 500);
        }
    }

    public function destroy($id)
    {
        $find = Supplier::find($id);

        if (!$find) {
            Alert::error('Error', 'Supplier not found!');
            return redirect()->route('supplier.index');
        }

        if ($find->image != null) {
            $imagePath = public_path('image/' . $find->image);
            if (file_exists($imagePath)) {
                unlink($imagePath);
            }
        }

        $find->delete();

        Alert::success('Success', 'Supplier deleted Successfully!');
        return redirect()->route('supplier.index');
    }
}
