<?php

namespace App\Http\Controllers;

// use App\Models\Role;
use Inertia\Inertia;
use App\Traits\SecureCrud;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use App\Http\Controllers\Controller;
use App\Http\Requests\RoleStoreRequest;
use App\Http\Requests\RoleUpdateRequest;
use Spatie\Permission\Models\Permission;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    use SecureCrud;
    public function index()
    {
        return Inertia::render('roles/Index', [
            'roles' => Role::select('id', 'name', 'guard_name', 'created_at')->latest()->get(),
            'permissions' => Permission::select('id', 'name')->latest()->get(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(RoleStoreRequest $request)
    {
        $role = $this->secureStore(
            Role::class,
            [
                'name' => trim($request->name),
                'guard_name' => 'web',
            ]
        );

        return back()->with(
            'success',
            "{$role->name} role created successfully."
        );
    }

    /**
     * Display the specified resource.
     */
    public function show(Role $role)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Role $role)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(RoleUpdateRequest $request, Role $role)
    {
        $role->update([
            'name' => trim($request->name)
        ]);
        return back()->with('success', "{$role->name} role updated successfully.");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Role $role)
    {
        if ($role->name === 'admin') {
            abort(403);
        }
        $role->delete();
        return back()->with('success', 'Role deleted');
    }

    public function permissions(Role $role)
    {
        return response()->json([

            'permissions' => Permission::select(
                'id',
                'name'
            )->get(),

            'selected' => $role
                ->permissions()
                ->pluck('name')
                ->toArray(),

        ]);
    }


    public function syncPermissions(
        Request $request,
        Role $role
    ) {

        $validated =
            $request->validate([

                'permissions' => [
                    'array'
                ],

                'permissions.*' => [
                    'exists:permissions,name'
                ]

            ]);


        $role->syncPermissions(

            $validated[
                'permissions'
            ] ?? []

        );


        return back()->with(
            'success',
            'Permissions updated'
        );

    }
}
