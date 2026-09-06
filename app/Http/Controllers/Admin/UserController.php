<?php

namespace App\Http\Controllers\Admin;

use Alert;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Wing;
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
            new Middleware('permission:user.create', only: ['create', 'store']),
            new Middleware('permission:user.edit', only: ['edit', 'update']),
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
                ->addColumn('user_type', function ($row) {
                    return User::USER_TYPES[$row->user_type] ?? '-';
                })
                ->addColumn('action', function ($row) {
                    $editUrl = route('user.edit', $row->id);
                    $deleteUrl = route('user.distroy', $row->id);
                    $csrfToken = csrf_field();
                    $method = method_field('DELETE');

                    // Edit is now a full page, so this is a plain link, not a modal trigger.
                    $editBtn = '<a href="' . $editUrl . '" class="action-icon-btn action-edit me-2" title="Edit">
                    <i class="fa-solid fa-pen-to-square"></i>
                </a>';

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

    public function create()
    {
        $roles = Role::all();
        $wings = Wing::all();
        $userTypes = User::USER_TYPES;

        return view('backend.user.create', compact('roles', 'wings', 'userTypes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email',
            'password' => 'required',
            'user_type' => 'required|in:' . implode(',', array_keys(User::USER_TYPES)),
            'role' => 'required|array',
            'role.*' => 'exists:roles,name',
            'wing' => 'nullable|array',
            'wing.*' => 'exists:wings,id',
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
                'user_type' => $request->user_type,
                'image' => $imagePath,
            ]);

            $user->assignRole($request->role);
            $user->wings()->sync($request->wing ?? []);

            DB::commit();
            return response()->json(['success' => true, 'message' => 'User Created Successfully!']);
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
        $data = User::with('wings')->findOrFail($id);

        $roles = Role::all();
        $wings = Wing::all();
        $userTypes = User::USER_TYPES;

        return view('backend.user.edit', compact('data', 'roles', 'wings', 'userTypes'));
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
            'user_type' => 'required|in:' . implode(',', array_keys(User::USER_TYPES)),
            'role' => 'required|array',
            'role.*' => 'exists:roles,name',
            'wing' => 'nullable|array',
            'wing.*' => 'exists:wings,id',
            'image' => 'nullable|file|image|max:2048',
        ]);

        try {
            DB::beginTransaction();

            $data = [
                'name' => $request->name,
                'email' => $request->email,
                'user_type' => $request->user_type,
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
            $find->wings()->sync($request->wing ?? []);

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
