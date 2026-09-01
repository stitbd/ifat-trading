<?php

namespace App\Http\Controllers\Admin;

use Alert;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class RoleController extends Controller implements HasMiddleware
{

    public static function middleware(): array
    {
        return [
            'auth', // Require authentication for all actions
            new Middleware('permission:role.view', only: ['index', 'getdata']),
            new Middleware('permission:role.create', only: ['store']),
            new Middleware('permission:role.edit', only: ['update']),
            new Middleware('permission:role.delete', only: ['distroy']),
        ];
    }
    public function index()
    {
        return view('backend.role.index');
    }

    public function getdata(Request $request)
    {
        if ($request->ajax()) {
            $data = Role::orderBy('created_at', 'desc')->get();

            return DataTables::of($data)
                ->addColumn('permission', function ($row) {
                    $badges = '';
                    foreach ($row->permissions as $permission) {
                        $badges .= '<span class="badge bg-primary me-1 mb-1">' . $permission->name . '</span>';
                    }
                    return $badges;
                })
                ->addColumn('action', function ($row) {
                    $editUrl = route('role.edit', $row->id);
                    $deleteUrl = route('role.distroy', $row->id);
                    $csrfToken = csrf_field();
                    $method = method_field('DELETE');

                    $editBtn = '<a href="' . $editUrl . '" class="action-icon-btn action-edit me-2" style="padding:8px;">
                         <i class="fa-solid fa-pen-to-square"></i>
                    </a>';

                    $deleteBtn = '<form action="' . $deleteUrl . '" method="POST" class="d-inline">
                        ' . $csrfToken . $method . '
                          <button type="submit" class="delete action-icon-btn action-delete" title="Delete">
                        <i class="bi bi-trash-fill"></i>
                    </button>
                    </form>';

                    return '<div class="d-flex align-items-center mb-2">' . $editBtn . $deleteBtn . '</div>';
                })
                ->rawColumns(['permission', 'action'])
                ->make(true);
        }
    }

    public function create()
    {
        $permissions = Permission::all()->groupBy('group_name');
        return view('backend.role.create', compact('permissions'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:roles,name',
            'permissions' => 'nullable|array',
        ]);

        try {
            DB::beginTransaction();

            $role = Role::create([
                'name' => $request->name,
                'guard_name' => 'web',
            ]);

            if ($request->has('permissions')) {
                $role->syncPermissions($request->permissions);
            }

            DB::commit();
            Alert::success('Success', 'Role Created Successfully!');
            return redirect()->route('role.index');
        } catch (\Exception $e) {
            DB::rollBack();
            Alert::error('Error', 'Something went wrong! Please try again.');
            return redirect()->back();
        }
    }

    public function edit($id)
    {
        $data = Role::findOrFail($id);
        $permissions = Permission::all()->groupBy('group_name');
        $rolePermissions = $data->permissions->pluck('name')->toArray();

        return view('backend.role.edit', compact('data', 'permissions', 'rolePermissions'));
    }

    public function update(Request $request, $id)
    {
        $role = Role::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255|unique:roles,name,' . $id,
            'permissions' => 'nullable|array',
        ]);

        try {
            DB::beginTransaction();

            $role->update(['name' => $request->name]);
            $role->syncPermissions($request->permissions ?? []);

            DB::commit();
            Alert::success('Success', 'Role Updated Successfully!');
            return redirect()->route('role.index');
        } catch (\Exception $e) {
            DB::rollBack();
            Alert::error('Error', 'Something went wrong! Please try again.');
            return redirect()->back();
        }
    }

    public function distroy($id)
    {
        $role = Role::findOrFail($id);

        // Super Admin delete howa theke protect
        if ($role->name === 'Super Admin') {
            Alert::error('Error', 'Super Admin role cannot be deleted!');
            return redirect()->route('role.index');
        }

        // Check if any user has this role assigned
        $usersCount = DB::table('model_has_roles')
            ->where('role_id', $role->id)
            ->count();

        if ($usersCount > 0) {
            Alert::error('Error', 'This role is assigned to ' . $usersCount . ' user(s). Please remove it from users first before deleting!');
            return redirect()->route('role.index');
        }

        $role->delete();
        Alert::success('Success', 'Role deleted Successfully!');
        return redirect()->route('role.index');
    }
}