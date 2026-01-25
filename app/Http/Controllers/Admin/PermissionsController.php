<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Permission;

class PermissionsController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'verified']);
    }

    public function index()
    {
        abort_if(!auth()->user()->isAdmin(), 403);
        $permissions = Permission::with('roles')->withCount('roles')->orderBy('name')->get();
        return view('admin.permissions.index', compact('permissions'));
    }

    public function create()
    {
        abort_if(!auth()->user()->isAdmin(), 403);
        return view('admin.permissions.create');
    }

    public function store(Request $request)
    {
        abort_if(!auth()->user()->isAdmin(), 403);
        $data = $request->validate(['name' => 'required|string|unique:permissions,name']);
        Permission::create(['name' => $data['name']]);
        return redirect()->route('admin.permissions.index')->with('success', 'Permission created');
    }

    public function edit(Permission $permission)
    {
        abort_if(!auth()->user()->isAdmin(), 403);
        return view('admin.permissions.edit', compact('permission'));
    }

    public function update(Request $request, Permission $permission)
    {
        abort_if(!auth()->user()->isAdmin(), 403);
        $data = $request->validate(['name' => 'required|string|unique:permissions,name,' . $permission->id]);
        $permission->update(['name' => $data['name']]);
        return redirect()->route('admin.permissions.index')->with('success', 'Permission updated');
    }

    public function destroy(Permission $permission)
    {
        abort_if(!auth()->user()->isAdmin(), 403);
        $permission->delete();
        return redirect()->route('admin.permissions.index')->with('success', 'Permission deleted');
    }
}
