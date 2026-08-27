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
            new Middleware('permission:role.list', only: ['index', 'getdata']),
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

                    $editBtn = '<a href="' . $editUrl . '" class="btn btn-sm btn-primary me-2 rounded" style="padding:8px;">
                        <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" style="width: 20px; height: 20px;">
                            <path fill-rule="evenodd" clip-rule="evenodd" d="M21.1213 2.70705C19.9497 1.53548 18.0503 1.53547 16.8787 2.70705L15.1989 4.38685L7.29289 12.2928C7.16473 12.421 7.07382 12.5816 7.02986 12.7574L6.02986 16.7574C5.94466 17.0982 6.04451 17.4587 6.29289 17.707C6.54127 17.9554 6.90176 18.0553 7.24254 17.9701L11.2425 16.9701C11.4184 16.9261 11.5789 16.8352 11.7071 16.707L19.5556 8.85857L21.2929 7.12126C22.4645 5.94969 22.4645 4.05019 21.2929 2.87862L21.1213 2.70705ZM18.2929 4.12126C18.6834 3.73074 19.3166 3.73074 19.7071 4.12126L19.8787 4.29283C20.2692 4.68336 20.2692 5.31653 19.8787 5.70705L18.8622 6.72357L17.3068 5.10738L18.2929 4.12126ZM15.8923 6.52185L17.4477 8.13804L10.4888 15.097L8.37437 15.6256L8.90296 13.5112L15.8923 6.52185ZM4 7.99994C4 7.44766 4.44772 6.99994 5 6.99994H10C10.5523 6.99994 11 6.55223 11 5.99994C11 5.44766 10.5523 4.99994 10 4.99994H5C3.34315 4.99994 2 6.34309 2 7.99994V18.9999C2 20.6568 3.34315 21.9999 5 21.9999H16C17.6569 21.9999 19 20.6568 19 18.9999V13.9999C19 13.4477 18.5523 12.9999 18 12.9999C17.4477 12.9999 17 13.4477 17 13.9999V18.9999C17 19.5522 16.5523 19.9999 16 19.9999H5C4.44772 19.9999 4 19.5522 4 18.9999V7.99994Z" fill="#ffffff"></path>
                        </svg>
                    </a>';

                    $deleteBtn = '<form action="' . $deleteUrl . '" method="POST" class="d-inline">
                        ' . $csrfToken . $method . '
                        <button type="submit" class="delete btn btn-danger btn-sm" style="padding:8px;">
                            <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" style="width: 20px; height: 20px;">
                                <path d="M6 7V18C6 19.1046 6.89543 20 8 20H16C17.1046 20 18 19.1046 18 18V7M6 7H5M6 7H8M18 7H19M18 7H16M10 11V16M14 11V16M8 7V5C8 3.89543 8.89543 3 10 3H14C15.1046 3 16 3.89543 16 5V7M8 7H16" stroke="#ffffff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                            </svg>
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
