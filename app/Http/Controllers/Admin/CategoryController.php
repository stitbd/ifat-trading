<?php

namespace App\Http\Controllers\Admin;

use Alert;
use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class CategoryController extends Controller
{
    public static function middleware(): array
    {
        return [
            'auth',

            new Middleware(
                'permission:category.view',
                only: ['index', 'getdata']
            ),

            new Middleware(
                'permission:category.create',
                only: ['store']
            ),

            new Middleware(
                'permission:category.edit',
                only: ['update']
            ),

            new Middleware(
                'permission:category.delete',
                only: ['destroy']
            ),
        ];
    }

    public function index()
    {
        return view('backend.categories.index');
    }


    public function getdata(Request $request)
    {
        if ($request->ajax()) {

            $data = Category::orderBy('created_at', 'desc')->get();

            return DataTables::of($data)

                ->addColumn('image', function ($row) {

                    if (
                        $row->image &&
                        file_exists(
                            public_path('categories/image/' . $row->image)
                        )
                    ) {
                        return '<img src="' .
                            asset('categories/image/' . $row->image) .
                            '"
                            alt="Category Image"
                            style="
                                width:60px;
                                height:60px;
                                object-fit:cover;
                                border-radius:5px;
                                border:1px solid #ddd;
                            ">';
                    }

                    return '<span class="text-muted">No Image</span>';
                })
                ->addColumn('status', function ($row) {
                    return $row->status
                        ? '<span class="status-pill status-active"><i class="bi bi-circle-fill"></i> Active</span>'
                        : '<span class="status-pill status-inactive"><i class="bi bi-circle-fill"></i> Inactive</span>';
                })
                ->addColumn('action', function ($row) {

                    $editUrl = route(
                        'category.edit',
                        $row->id
                    );

                    $deleteUrl = route(
                        'category.destroy',
                        $row->id
                    );

                    $csrfToken = csrf_field();

                    $method = method_field('DELETE');

                    /*
                    |--------------------------------------------------------------------------
                    | Edit Button
                    |--------------------------------------------------------------------------
                    */

                    $editBtn =
                        '<button data-id="' . $row->id . '" type="button" class="edit action-icon-btn action-edit me-2" title="Edit">
                    <i class="fa-solid fa-pen-to-square"></i>
                </button>';

                    /*
                    |--------------------------------------------------------------------------
                    | Delete Button
                    |--------------------------------------------------------------------------
                    */

                    $deleteBtn =
                        '<form
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

                    return
                        '<div class="d-flex align-items-center mb-2">' .
                        $editBtn .
                        $deleteBtn .
                        '</div>';
                })

                ->rawColumns([
                    'image',
                    'status',
                    'action'
                ])

                ->make(true);
        }
    }

    /**
     * Show category
     */
    public function show(string $id)
    {
        $data = Category::findOrFail($id);

        return view(
            'backend.categories.show',
            compact('data')
        );
    }

    /**
     * Create category
     */
    public function create()
    {
        return view('backend.categories.create');
    }

    /**
     * Store category
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100|unique:categories,name',
            'image' => 'nullable|file|image|max:2048',
            'description' => 'nullable|string',
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
                    time() . '_' .
                    uniqid() . '.' .
                    $extension;

                $path = 'categories/image/';

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
            | Create Category
            |--------------------------------------------------------------------------
            */

            Category::create([
                'name' => $request->name,
                'image' => $imagePath,
                'description' => $request->description,
                'created_by' => Auth::id(),
                'updated_by' => Auth::id(),
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' =>
                'Category Created Successfully!',
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

    /**
     * Edit category
     */
    public function edit(string $id)
    {
        $data = Category::findOrFail($id);

        return view(
            'backend.categories.edit',
            compact('data')
        );
    }

    /**
     * Update category
     */
    public function update(
        Request $request,
        string $id
    ) {
        $find = Category::findOrFail($id);

        $request->validate([
            'name' => [
                'required',
                'string',
                'max:100',
                'unique:categories,name,' . $id,
            ],

            'image' =>
            'nullable|file|image|max:2048',

            'description' =>
            'nullable|string',
        ]);

        try {

            DB::beginTransaction();

            $data = [
                'name' => $request->name,
                'description' =>
                $request->description,
                'updated_by' => Auth::id(),
            ];

            /*
            |--------------------------------------------------------------------------
            | Update Image
            |--------------------------------------------------------------------------
            */

            if ($request->hasFile('image')) {

                if ($find->image !== null) {

                    $imagePath = public_path(
                        'categories/image/' .
                            $find->image
                    );

                    if (file_exists($imagePath)) {
                        unlink($imagePath);
                    }
                }

                $file = $request->file('image');

                $extension =
                    $file->getClientOriginalExtension();

                $filename =
                    time() . '_' .
                    uniqid() . '.' .
                    $extension;

                $path = 'categories/image/';

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

                $data['image'] = $filename;
            }

            $find->update($data);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' =>
                'Category Updated Successfully!',
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

    /**
     * Delete category
     */
    public function destroy(string $id)
    {
        $find = Category::findOrFail($id);

        /*
        |--------------------------------------------------------------------------
        | Delete Image
        |--------------------------------------------------------------------------
        */

        if ($find->image !== null) {

            $imagePath = public_path(
                'categories/image/' .
                    $find->image
            );

            if (file_exists($imagePath)) {
                unlink($imagePath);
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Update Deleted By
        |--------------------------------------------------------------------------
        */

        $find->update([
            'updated_by' => Auth::id(),
        ]);

        /*
        |--------------------------------------------------------------------------
        | Soft Delete
        |--------------------------------------------------------------------------
        */

        $find->delete();

        Alert::success(
            'Success',
            'Category deleted successfully!'
        );

        return redirect()->route(
            'category.index'
        );
    }
}
