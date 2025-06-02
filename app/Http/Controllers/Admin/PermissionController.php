<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Inertia\Inertia;
use Spatie\Permission\Models\Permission;
use Illuminate\Http\Request;

class PermissionController extends Controller
{
    public function index(Request $request)
    {
        $permissions = Permission::query()
            ->when($request->search, fn ($query, $search) =>
            $query->where('name', 'like', "%{$search}%")
            )
            ->orderBy($request->sort_by ?? 'name', $request->sort_dir ?? 'asc')
            ->paginate($request->per_page ?? 10);

        if ($request->ajax) {
            return response()->json($permissions);
        }

        return Inertia::render('Admin/Permission/Index', [
            'permissions' => $permissions,
            'filters' => $request->only(['search', 'sort_by', 'sort_dir', 'per_page']),
        ]);
    }

    public function create()
    {
        return Inertia::render('Admin/Permission/Form', [
            'type' => 'create'
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|unique:permissions'
        ]);

        Permission::create(['name' => $validated['name']]);

        return redirect()->route('admin.permissions.index')->with('success', 'Permission created successfully');
    }

    public function show(Permission $permission)
    {
        return Inertia::render('Admin/Permission/Show', [
            'permission' => $permission
        ]);
    }

    public function edit(Permission $permission)
    {
        return Inertia::render('Admin/Permission/Form', [
            'permission' => $permission,
            'type' => 'edit'
        ]);
    }

    public function update(Request $request, Permission $permission)
    {
        $validated = $request->validate([
            'name' => 'required|string|unique:permissions,name,'.$permission->id
        ]);

        $permission->update(['name' => $validated['name']]);

        return redirect()->route('admin.permissions.index')->with('success', 'Permission updated successfully');
    }

    public function destroy(Permission $permission)
    {
        $permission->delete();
        return redirect()->route('admin.permissions.index')->with('success', 'Permission deleted successfully');
    }
}
