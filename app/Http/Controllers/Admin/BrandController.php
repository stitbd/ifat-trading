<?php

namespace App\Http\Controllers\Admin;

use Alert;
use App\Http\Controllers\Controller;
use App\Models\Brand;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class BrandController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [

            'auth',

            new Middleware(
                'permission:brand.view',
                only: ['index', 'getdata']
            ),

            new Middleware(
                'permission:brand.create',
                only: ['store']
            ),

            new Middleware(
                'permission:brand.edit',
                only: ['update']
            ),

            new Middleware(
                'permission:brand.delete',
                only: ['destroy']
            ),
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Index
    |--------------------------------------------------------------------------
    */

    public function index()
    {
        return view('backend.brands.index');
    }

    /*
    |--------------------------------------------------------------------------
    | DataTable
    |--------------------------------------------------------------------------
    */

    public function statusUpdate(Request $request, $id)
    {

        $find = Brand::find($id);

        if (!$find) {
            return response()->json(['success' => false, 'message' => 'Brand not found!'], 404);
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

    public function getdata(Request $request)
    {
        if (!$request->ajax()) {
            abort(404);
        }

        $data = Brand::orderBy('created_at', 'desc')
            ->get();

        return DataTables::of($data)
            ->addColumn('status', function ($row) {
                return $row->status
                    ? '<span class="badge badge-success">Active</span>'
                    : '<span class="badge badge-danger">Inactive</span>';
            })
            ->addColumn('image', function ($row) {

                if (
                    $row->image &&
                    file_exists(
                        public_path(
                            'brands/image/' . $row->image
                        )
                    )
                ) {

                    return '<img
                        src="' .
                        asset(
                            'brands/image/' . $row->image
                        ) .
                        '"
                        alt="Brand Image"
                        style="
                            width:60px;
                            height:60px;
                            object-fit:cover;
                            border-radius:5px;
                            border:1px solid #ddd;
                        "
                    >';
                }

                return '<span class="text-muted">
                    No Image
                </span>';
            })

            ->addColumn('action', function ($row) {

                $editUrl = route(
                    'brand.edit',
                    $row->id
                );

                $deleteUrl = route(
                    'brand.destroy',
                    $row->id
                );

                $csrfToken = csrf_field();

                $method = method_field('DELETE');

                $editBtn = '
                  <button data-id="' . $row->id . '" type="button" class="edit action-icon-btn action-edit mx-2" title="Edit">
                    <i class="fa-solid fa-pen-to-square"></i>
                </button>
                ';

                $deleteBtn = '
                    <form
                        action="' . $deleteUrl . '"
                        method="POST"
                        style="display:inline;"
                    >

                        ' . $csrfToken . '

                        ' . $method . '

                       <button type="submit" class="delete action-icon-btn action-delete" title="Delete">
                        <i class="bi bi-trash-fill"></i>
                    </button>

                    </form>
                ';

                if ($row->status) {
                    $statusBtn = '<button data-id="' . $row->id . '" type="button" class="status-toggle action-icon-btn action-status-on" title="Active - click to deactivate">
                        <i class="bi bi-toggle-on"></i>
                    </button>';
                } else {
                    $statusBtn = '<button data-id="' . $row->id . '" type="button" class="status-toggle action-icon-btn action-status-off" title="Inactive - click to activate">
                        <i class="bi bi-toggle-off"></i>
                    </button>';
                }

                return
                    '<div class="d-flex align-items-center mb-2">' .
                    $statusBtn .  $editBtn .
                    $deleteBtn .
                    '</div>';
            })

            ->rawColumns([
                'image',
                'status',
                'action',
            ])

            ->make(true);
    }

    /*
    |--------------------------------------------------------------------------
    | Create
    |--------------------------------------------------------------------------
    */

    public function create()
    {
        return view('backend.brands.create');
    }

    /*
    |--------------------------------------------------------------------------
    | Store
    |--------------------------------------------------------------------------
    */

    public function store(Request $request)
    {
        $request->validate([

            'name' =>
            'required|string|max:100|unique:brands,name',

            'image' =>
            'nullable|file|image|max:2048',

            'description' =>
            'nullable|string',
        ]);

        try {

            DB::beginTransaction();

            $imagePath = null;

            /*
            |--------------------------------------------------------------------------
            | Upload Image
            |--------------------------------------------------------------------------
            */

            if ($request->hasFile('image')) {

                $file = $request->file('image');

                $extension =
                    $file->getClientOriginalExtension();

                $filename =
                    time() .
                    '_' .
                    uniqid() .
                    '.' .
                    $extension;

                $path =
                    'brands/image/';

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

            /*
            |--------------------------------------------------------------------------
            | Create Brand
            |--------------------------------------------------------------------------
            */

            Brand::create([

                'name' =>
                $request->name,

                'image' =>
                $imagePath,

                'description' =>
                $request->description,

                'created_by' =>
                Auth::id(),

                'updated_by' =>
                Auth::id(),
            ]);

            DB::commit();

            return response()->json([

                'success' => true,

                'message' =>
                'Brand Created Successfully!',
            ]);
        } catch (\Exception $e) {

            DB::rollBack();

            return response()->json([

                'success' => false,

                'message' =>
                'Something went wrong! Please try again.',
            ], 500);
        }
    }

    /*
    |--------------------------------------------------------------------------
    | Edit
    |--------------------------------------------------------------------------
    */

    public function edit(string $id)
    {
        $data =
            Brand::findOrFail($id);

        return view(
            'backend.brands.edit',
            compact('data')
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Update
    |--------------------------------------------------------------------------
    */

    public function update(
        Request $request,
        string $id
    ) {

        $find =
            Brand::findOrFail($id);

        $request->validate([

            'name' => [
                'required',
                'string',
                'max:100',
                'unique:brands,name,' . $id,
            ],

            'image' =>
            'nullable|file|image|max:2048',

            'description' =>
            'nullable|string',
        ]);

        try {

            DB::beginTransaction();

            $data = [

                'name' =>
                $request->name,

                'description' =>
                $request->description,

                'updated_by' =>
                Auth::id(),
            ];

            /*
            |--------------------------------------------------------------------------
            | Update Image
            |--------------------------------------------------------------------------
            */

            if ($request->hasFile('image')) {

                if ($find->image !== null) {

                    $imagePath =
                        public_path(
                            'brands/image/' .
                                $find->image
                        );

                    if (file_exists($imagePath)) {

                        unlink($imagePath);
                    }
                }

                $file =
                    $request->file('image');

                $extension =
                    $file->getClientOriginalExtension();

                $filename =
                    time() .
                    '_' .
                    uniqid() .
                    '.' .
                    $extension;

                $path =
                    'brands/image/';

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

                $data['image'] =
                    $filename;
            }

            $find->update($data);

            DB::commit();

            return response()->json([

                'success' => true,

                'message' =>
                'Brand Updated Successfully!',
            ]);
        } catch (\Exception $e) {

            DB::rollBack();

            return response()->json([

                'success' => false,

                'message' =>
                'Something went wrong! Please try again.',
            ], 500);
        }
    }

    /*
    |--------------------------------------------------------------------------
    | Destroy
    |--------------------------------------------------------------------------
    */

    public function destroy(string $id)
    {
        $find =
            Brand::findOrFail($id);

        /*
        |--------------------------------------------------------------------------
        | Delete Image
        |--------------------------------------------------------------------------
        */

        if ($find->image !== null) {

            $imagePath =
                public_path(
                    'brands/image/' .
                        $find->image
                );

            if (file_exists($imagePath)) {

                unlink($imagePath);
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Updated By
        |--------------------------------------------------------------------------
        */

        $find->update([

            'updated_by' =>
            Auth::id(),
        ]);

        /*
        |--------------------------------------------------------------------------
        | Soft Delete
        |--------------------------------------------------------------------------
        */

        $find->delete();

        Alert::success(
            'Success',
            'Brand deleted successfully!'
        );

        return redirect()->route(
            'brand.index'
        );
    }
}