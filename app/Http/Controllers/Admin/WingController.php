<?php

namespace App\Http\Controllers\Admin;

use Alert;
use App\Http\Controllers\Controller;
use App\Models\Wing;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class WingController extends Controller
{
    public static function middleware(): array
    {
        return [
            'auth', // Require authentication for all actions
            new Middleware('permission:wing.view', only: ['index', 'getdata']),
            new Middleware('permission:wing.create', only: ['store']),
            new Middleware('permission:wing.edit', only: ['update']),
            new Middleware('permission:wing.delete', only: ['distroy']),
        ];
    }
    public function index()
    {
        return view('backend.wings.index');
    }

    public function statusUpdate(Request $request, $id)
    {

        $find = Wing::find($id);

        if (!$find) {
            return response()->json(['success' => false, 'message' => 'Wing not found!'], 404);
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


    /**
     * Get wing data for DataTables.
     */
    public function getdata(Request $request)
    {
        if ($request->ajax()) {

            $data = Wing::orderBy('created_at', 'desc')->get();

            return DataTables::of($data)
                ->addColumn('status', function ($row) {
                    return $row->status
                        ? '<span class="badge badge-success">Active</span>'
                        : '<span class="badge badge-danger">Inactive</span>';
                })
                ->addColumn('image', function ($row) {

                    if ($row->image && file_exists(public_path('wings/image/' . $row->image))) {
                        return '<img src="' . asset('wings/image/' . $row->image) . '"
                                alt="Wing Image"
                                style="width:50px;height:50px;object-fit:cover;border-radius:5px;">';
                    }

                    return '<span class="text-muted">No Image</span>';
                })

                ->addColumn('action', function ($row) {

                    $editUrl = route('wing.edit', $row->id);
                    $deleteUrl = route('wing.destroy', $row->id);

                    $csrfToken = csrf_field();
                    $method = method_field('DELETE');

                    $editBtn = '<button data-id="' . $row->id . '" type="button" class="edit action-icon-btn action-edit mx-2" title="Edit">
                    <i class="fa-solid fa-pen-to-square"></i>
                </button>';

                    $deleteBtn = '<form action="' . $deleteUrl . '"
                                        method="POST"
                                        style="display:inline;">
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

                    return
                        '<div class="d-flex align-items-center mb-2">' .
                        $statusBtn .  $editBtn .
                        $deleteBtn .
                        '</div>';
                })

                ->rawColumns(['image', 'status', 'action'])
                ->make(true);
        }
    }

    public function show(string $id)
    {
        $data = Wing::findOrFail($id);

        return view('backend.wings.show', compact('data'));
    }
    public function create()
    {
        return view('backend.wings.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'nullable|string|max:100',
            'imported_number' => 'nullable|string|max:100|unique:wings,imported_number',
            'bin_number' => 'nullable|string|max:50|unique:wings,bin_number',
            'mobile_number' => 'nullable|string|max:50',
            'email' => 'nullable|email|max:50',
            'image' => 'nullable|file|image|max:2048',
            'authority_signature' => 'nullable|file|image|max:2048',
            'description' => 'nullable|string',
        ]);

        try {

            DB::beginTransaction();

            $imagePath = null;
            $signaturePath = null;

            if ($request->hasFile('image')) {

                $file = $request->file('image');

                $extension = $file->getClientOriginalExtension();

                $filename = time() . '_' . uniqid() . '.' . $extension;

                $path = 'wings/image/';

                $file->move(public_path($path), $filename);

                $imagePath = $filename;
            }


            if ($request->hasFile('authority_signature')) {

                $file = $request->file('authority_signature');

                $extension = $file->getClientOriginalExtension();

                $filename = time() . '_signature_' . uniqid() . '.' . $extension;

                $path = 'signature/';

                $file->move(public_path($path), $filename);

                $signaturePath = $filename;
            }


            Wing::create([
                'name' => $request->name,
                'imported_number' => $request->imported_number,
                'bin_number' => $request->bin_number,
                'mobile_number' => $request->mobile_number,
                'email' => $request->email,
                'image' => $imagePath,
                'authority_signature' => $signaturePath,
                'description' => $request->description,
                'created_by' => Auth::id(),
                'updated_by' => Auth::id(),
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Wing Created Successfully!',
            ]);
        } catch (\Exception $e) {

            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Something went wrong! Please try again.',
            ], 500);
        }
    }


    public function edit(string $id)
    {
        $data = Wing::findOrFail($id);

        return view('backend.wings.edit', compact('data'));
    }


    public function update(Request $request, string $id)
    {
        $find = Wing::findOrFail($id);

        $request->validate([
            'name' => 'nullable|string|max:100',

            'imported_number' => [
                'nullable',
                'string',
                'max:100',
                'unique:wings,imported_number,' . $id,
            ],

            'bin_number' => [
                'nullable',
                'string',
                'max:50',
                'unique:wings,bin_number,' . $id,
            ],

            'mobile_number' => 'nullable|string|max:50',
            'email' => 'nullable|email|max:50',
            'image' => 'nullable|file|image|max:2048',
            'authority_signature' => 'nullable|file|image|max:2048',
            'description' => 'nullable|string',
        ]);

        try {

            DB::beginTransaction();

            $data = [
                'name' => $request->name,
                'imported_number' => $request->imported_number,
                'bin_number' => $request->bin_number,
                'mobile_number' => $request->mobile_number,
                'email' => $request->email,
                'description' => $request->description,
                'updated_by' => Auth::id(),
            ];



            if ($request->hasFile('image')) {

                if ($find->image !== null) {

                    $imagePath = public_path('wings/image/' . $find->image);

                    if (file_exists($imagePath)) {
                        unlink($imagePath);
                    }
                }

                $file = $request->file('image');

                $extension = $file->getClientOriginalExtension();

                $filename = time() . '_' . uniqid() . '.' . $extension;

                $path = 'wings/image/';

                $file->move(public_path($path), $filename);

                $data['image'] = $filename;
            }



            if ($request->hasFile('authority_signature')) {

                if ($find->authority_signature !== null) {

                    $signaturePath = public_path(
                        'signature/' . $find->authority_signature
                    );

                    if (file_exists($signaturePath)) {
                        unlink($signaturePath);
                    }
                }

                $file = $request->file('authority_signature');

                $extension = $file->getClientOriginalExtension();

                $filename = time() . '_signature_' . uniqid() . '.' . $extension;

                $path = 'signature/';

                $file->move(public_path($path), $filename);

                $data['authority_signature'] = $filename;
            }

            $find->update($data);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Wing Updated Successfully!',
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
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $find = Wing::findOrFail($id);



        if ($find->image !== null) {

            $imagePath = public_path('wings/image/' . $find->image);

            if (file_exists($imagePath)) {
                unlink($imagePath);
            }
        }


        if ($find->authority_signature !== null) {

            $signaturePath = public_path(
                'signature/' . $find->authority_signature
            );

            if (file_exists($signaturePath)) {
                unlink($signaturePath);
            }
        }



        $find->update([
            'updated_by' => Auth::id(),
        ]);

        $find->delete();

        Alert::success('Success', 'Wing deleted successfully!');

        return redirect()->route('wing.index');
    }
}