<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Role;
use App\Models\Permission;

class RolesController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'verified']);
    }

    public function index()
    {
        abort_if(!auth()->user()->isAdmin(), 403);
        $roles = Role::with('permissions')->orderBy('name')->get();
        return view('admin.roles.index', compact('roles'));
    }

    public function create()
    {
        abort_if(!auth()->user()->isAdmin(), 403);
        return view('admin.roles.create');
    }

    public function store(Request $request)
    {
        abort_if(!auth()->user()->isAdmin(), 403);
        $data = $request->validate(['name' => 'required|string|unique:roles,name']);
        Role::create(['name' => $data['name'], 'guard_name' => 'web']);
        return redirect()->route('admin.roles.index')->with('success', 'Role created successfully');
    }

    public function edit(Role $role)
    {
        abort_if(!auth()->user()->isAdmin(), 403);
        $permissions = Permission::orderBy('name')->get();
        $rolePermissions = $role->permissions->pluck('id')->toArray();
        return view('admin.roles.edit', compact('role', 'permissions', 'rolePermissions'));
    }

    public function update(Request $request, Role $role)
    {
        abort_if(!auth()->user()->isAdmin(), 403);
        $data = $request->validate(['name' => 'required|string|unique:roles,name,' . $role->id]);
        $role->update(['name' => $data['name']]);
        return redirect()->route('admin.roles.index')->with('success', 'Role updated successfully');
    }

    public function destroy(Role $role)
    {
        abort_if(!auth()->user()->isAdmin(), 403);
        
        // Check if role is assigned to any users
        if ($role->users()->count() > 0) {
            return redirect()->route('admin.roles.index')
                ->with('error', 'Cannot delete role that is assigned to users');
        }
        
        $role->delete();
        return redirect()->route('admin.roles.index')->with('success', 'Role deleted successfully');
    }

    /**
     * Show the form for managing role permissions
     */
    public function permissions(Role $role)
    {
        abort_if(!auth()->user()->isAdmin(), 403);
        $permissions = Permission::orderBy('name')->get();
        $rolePermissions = $role->permissions->pluck('id')->toArray();
        return view('admin.roles.permissions', compact('role', 'permissions', 'rolePermissions'));
    }

    /**
     * Update role permissions
     */
    public function updatePermissions(Request $request, Role $role)
    {
        abort_if(!auth()->user()->isAdmin(), 403);
        
        $validated = $request->validate([
            'permissions' => 'nullable|array',
            'permissions.*' => 'exists:permissions,id'
        ]);

        // Get permission models from IDs and sync
        $permissionIds = $validated['permissions'] ?? [];
        $permissions = Permission::whereIn('id', $permissionIds)->get();
        $role->syncPermissions($permissions);
        
        return redirect()->route('admin.roles.index')
            ->with('success', 'Role permissions updated successfully');
    }
}
