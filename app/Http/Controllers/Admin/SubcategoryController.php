<?php

namespace App\Http\Controllers\Admin;

use Alert;
use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Subcategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class SubcategoryController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [

            'auth',

            new Middleware(
                'permission:subcategory.view',
                only: ['index', 'getdata']
            ),

            new Middleware(
                'permission:subcategory.create',
                only: ['store']
            ),

            new Middleware(
                'permission:subcategory.edit',
                only: ['update']
            ),

            new Middleware(
                'permission:subcategory.delete',
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
        $categories = Category::orderBy('name', 'asc')->get();

        return view(
            'backend.subcategories.index',
            compact('categories')
        );
    }



    public function getdata(Request $request)
    {
        if (!$request->ajax()) {
            abort(404);
        }

        $data = Subcategory::with('category')
            ->orderBy('created_at', 'desc')
            ->get();

        return DataTables::of($data)

            ->addColumn('category_name', function ($row) {

                if ($row->category) {
                    return $row->category->name;
                }

                return 'N/A';
            })

            ->addColumn('image', function ($row) {

                if (
                    $row->image &&
                    file_exists(
                        public_path(
                            'subcategories/image/' . $row->image
                        )
                    )
                ) {

                    return '<img
                    src="' .
                        asset(
                            'subcategories/image/' . $row->image
                        ) .
                        '"
                    alt="Subcategory Image"
                    style="
                        width:60px;
                        height:60px;
                        object-fit:cover;
                        border-radius:5px;
                        border:1px solid #ddd;
                    "
                >';
                }

                return '<span class="text-muted">No Image</span>';
            })

            ->addColumn('action', function ($row) {

                $editUrl = route(
                    'subcategory.edit',
                    $row->id
                );

                $deleteUrl = route(
                    'subcategory.destroy',
                    $row->id
                );

                $csrfToken = csrf_field();

                $method = method_field('DELETE');

                $editBtn = '
                <button data-id="' . $row->id . '" type="button" class="edit action-icon-btn action-edit me-2" title="Edit">
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
                </form>';

                return '
                <div class="d-flex align-items-center mb-2">
                    ' .
                    $editBtn .
                    $deleteBtn .
                    '</div>';
            })

            ->rawColumns([
                'image',
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
        $categories = Category::orderBy('name', 'asc')->get();

        return view(
            'backend.subcategories.create',
            compact('categories')
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Store
    |--------------------------------------------------------------------------
    */

    public function store(Request $request)
    {
        $request->validate([

            'category_id' =>
            'required|exists:categories,id',

            'name' =>
            'required|string|max:100',

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
                    'subcategories/image/';

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
            | Create
            |--------------------------------------------------------------------------
            */

            Subcategory::create([

                'category_id' =>
                $request->category_id,

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
                'Subcategory Created Successfully!',
            ]);
        } catch (\Exception $e) {

            dd($e->getMessage());
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

        $data = Subcategory::findOrFail($id);


        $categories =
            Category::orderBy('name', 'asc')->get();

        return view(
            'backend.subcategories.edit',
            compact(
                'data',
                'categories'
            )
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
            Subcategory::findOrFail($id);

        $request->validate([

            'category_id' =>
            'required|exists:categories,id',

            'name' =>
            'required|string|max:100',

            'image' =>
            'nullable|file|image|max:2048',

            'description' =>
            'nullable|string',
        ]);

        try {

            DB::beginTransaction();

            $data = [

                'category_id' =>
                $request->category_id,

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
                            'subcategories/image/' .
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
                    'subcategories/image/';

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
                'Subcategory Updated Successfully!',
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
            Subcategory::findOrFail($id);

        /*
        |--------------------------------------------------------------------------
        | Delete Image
        |--------------------------------------------------------------------------
        */

        if ($find->image !== null) {

            $imagePath =
                public_path(
                    'subcategories/image/' .
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
            'Subcategory deleted successfully!'
        );

        return redirect()->route(
            'subcategory.index'
        );
    }
}
