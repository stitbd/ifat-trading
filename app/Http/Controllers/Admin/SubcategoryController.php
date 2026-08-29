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
                <button
                    data-id="' . $row->id . '"
                    class="edit btn btn-sm btn-success me-2 rounded"
                    style="padding:8px;"
                >
                    <svg
                        viewBox="0 0 24 24"
                        fill="none"
                        xmlns="http://www.w3.org/2000/svg"
                        style="width:20px;height:20px;"
                    >
                        <path
                            fill-rule="evenodd"
                            clip-rule="evenodd"
                            d="M21.1213 2.70705C19.9497 1.53548 18.0503 1.53547 16.8787 2.70705L15.1989 4.38685L7.29289 12.2928C7.16473 12.421 7.07382 12.5816 7.02986 12.7574L6.02986 16.7574C5.94466 17.0982 6.04451 17.4587 6.29289 17.707C6.54127 17.9554 6.90176 18.0553 7.24254 17.9701L11.2425 16.9701C11.4184 16.9261 11.5789 16.8352 11.7071 16.707L19.5556 8.85857L21.2929 7.12126C22.4645 5.94969 22.4645 4.05019 21.2929 2.87862C21.1213 2.70705 21.1213 2.70705 21.1213 2.87862L21.1213 2.70705Z"
                            fill="#ffffff">
                        </path>
                    </svg>
                </button>';

                $deleteBtn = '
                <form
                    action="' . $deleteUrl . '"
                    method="POST"
                    style="display:inline;"
                >
                    ' . $csrfToken . '
                    ' . $method . '

                    <button
                        type="submit"
                        class="delete btn btn-danger btn-sm"
                        style="padding:8px;"
                    >
                        <svg
                            viewBox="0 0 24 24"
                            fill="none"
                            xmlns="http://www.w3.org/2000/svg"
                            style="width:20px;height:20px;"
                        >
                            <path
                                d="M6 7V18C6 19.1046 6.89543 20 8 20H16C17.1046 20 18 19.1046 18 18V7M6 7H5M6 7H8M18 7H19M18 7H16M10 11V16M14 11V16M8 7V5C8 3.89543 8.89543 3 10 3H14C15.1046 3 16 3.89543 16 5V7M8 7H16"
                                stroke="#ffffff"
                                stroke-width="2"
                                stroke-linecap="round"
                                stroke-linejoin="round">
                            </path>
                        </svg>
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
