<?php

namespace App\Http\Controllers\Admin;

use Alert;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Yajra\DataTables\Facades\DataTables;

class UserController extends Controller implements HasMiddleware
{

    public static function middleware(): array
    {
        return [
            'auth', // Require authentication for all actions
            new Middleware('permission:user.view', only: ['index', 'getdata']),
            new Middleware('permission:user.create', only: ['store']),
            new Middleware('permission:user.edit', only: ['update']),
            new Middleware('permission:user.delete', only: ['distroy']),
        ];
    }

    public function index()
    {
        $roles = Role::all();
        return view('backend.user.index', compact('roles'));
    }

    public function getdata(Request $request)
    {
        if ($request->ajax()) {
            $data = User::orderBy('created_at', 'desc')->get();
            return DataTables::of($data)
                ->addColumn('role', function ($row) {
                    return $row->roles->pluck('name')->implode(', ') ?: '-';
                })
                ->addColumn('action', function ($row) {
                    $deleteBtn = '';
                    $editBtn = '';


                    $editUrl = route('user.edit', $row->id);
                    $deleteUrl = route('user.distroy', $row->id);
                    $csrfToken = csrf_field();
                    $method = method_field('DELETE');
                    $editBtn = ' <button data-id="' . $row->id . '" type="button" class="edit action-icon-btn action-edit me-2" title="Edit">
                    <i class="fa-solid fa-pen-to-square"></i>
                </button>';

                    $deleteBtn = '<form action="' . $deleteUrl . '" method="POST">
                    ' . $csrfToken . '
                    ' . $method . '
                  <button type="submit" class="delete action-icon-btn action-delete" title="Delete">
                        <i class="bi bi-trash-fill"></i>
                    </button>
                </form>';
                    return '<div class="d-flex align-items-center  mb-2">'
                        . $editBtn . $deleteBtn .
                        '</div>';
                })
                ->rawColumns(['action'])
                ->make(true);
        }
    }


    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email',
            'password' => 'required',
            'role' => 'required|array',
            'role.*' => 'exists:roles,name',
            'image' => 'nullable|file|image|max:2048',
        ]);

        try {
            DB::beginTransaction();
            $imagePath = null;
            if ($request->hasFile('image')) {
                $file = $request->file('image');
                $extension = $file->getClientOriginalExtension();
                $filename = time() . '_' . uniqid() . '.' . $extension;
                $file->move(public_path('image/'), $filename);
                $imagePath = $filename;
            }

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'image' => $imagePath,
            ]);

            $user->assignRole($request->role);

            DB::commit();
            return response()->json(['success' => true, 'message' => 'User Created Successfully!']);
        } catch (\Exception $e) {
            DB::rollBack();

            // AJAX request er jonno proper JSON error response
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong! Please try again.',
            ], 500);
        }
    }
    public function edit($id)
    {
        $data = User::findOrFail($id);
        $roles = Role::all();
        return view('backend.user.edit', compact('data', 'roles'));
    }

    public function update(Request $request, $id)
    {
        $find = User::find($id);

        if (!$find) {
            return response()->json([
                'success' => false,
                'message' => 'User not found!',
            ], 404);
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'password' => 'nullable',
            'role' => 'required|array',
            'role.*' => 'exists:roles,name',
            'image' => 'nullable|file|image|max:2048',
        ]);

        try {
            DB::beginTransaction();

            $data = [
                'name' => $request->name,
                'email' => $request->email,
            ];

            if ($request->hasFile('image')) {
                if ($find->image !== null) {
                    $imagePath = public_path('image/' . $find->image);
                    if (file_exists($imagePath)) {
                        unlink($imagePath);
                    }
                }
                $file = $request->file('image');
                $extension = $file->getClientOriginalExtension();
                $filename = time() . '_' . uniqid() . '.' . $extension;
                $file->move(public_path('image/'), $filename);
                $data += ['image' => $filename];
            }

            if (!empty($request->password)) {
                $data += ['password' => Hash::make($request->password)];
            }

            $find->update($data);
            $find->syncRoles($request->role);

            DB::commit();
            return response()->json(['success' => true, 'message' => 'User Updated Successfully!']);
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
        $find = User::find($id);
        if ($find->image != null) {
            $imagePath = public_path('image/' . $find->image);
            if (file_exists($imagePath)) {
                unlink($imagePath); // Delete the image file
            }
        }

        $find->delete();
        Alert::success('Success', 'User deleted Successful!');
        return  redirect()->route('user.index');
    }
}
