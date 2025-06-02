<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Inertia\Inertia;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    public function index(Request $request)
    {
        $roles = Role::with('permissions')
            ->when($request->search, function ($query, $search) {
                $query->where('name', 'like', "%{$search}%")
                    ->orWhereHas('permissions', function ($q) use ($search) {
                        $q->where('name', 'like', "%{$search}%");
                    });
            })
            ->orderBy($request->sort_by ?? 'name', $request->sort_dir ?? 'asc')
            ->paginate($request->per_page ?? 10);

        if ($request->ajax) {
            return response()->json($roles);
        }

        return Inertia::render('Admin/Role/Index', [
            'roles' => $roles,
            'filters' => $request->only(['search', 'sort_by', 'sort_dir', 'per_page']),
        ]);
    }

    public function create()
    {
        return Inertia::render('Admin/Role/Form', [
            'permissions' => Permission::all(),
            'type' => 'create'
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|unique:roles',
            'permissions' => 'array'
        ]);

        $role = Role::create(['name' => $validated['name']]);
        $role->syncPermissions($validated['permissions'] ?? []);

        return redirect()->route('admin.roles.index')->with('success', 'Role created successfully');
    }

    public function show(Role $role)
    {
        return Inertia::render('Admin/Role/Show', [
            'role' => $role->load('permissions')
        ]);
    }

    public function edit(Role $role)
    {
        return Inertia::render('Admin/Role/Form', [
            'role' => $role->load('permissions'),
            'permissions' => Permission::all(),
            'type' => 'edit'
        ]);
    }

    public function update(Request $request, Role $role)
    {
        $validated = $request->validate([
            'name' => 'required|string|unique:roles,name,'.$role->id,
            'permissions' => 'array'
        ]);

        $role->update(['name' => $validated['name']]);
        $role->syncPermissions($validated['permissions'] ?? []);

        return redirect()->route('admin.roles.index')->with('success', 'Role updated successfully');
    }

    public function destroy(Role $role)
    {
        $role->delete();
        return redirect()->route('admin.roles.index')->with('success', 'Role deleted successfully');
    }
}
