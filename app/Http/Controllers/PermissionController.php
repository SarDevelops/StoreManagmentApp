<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Permission;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\PermissionStoreRequest;
use App\Http\Requests\PermissionUpdateRequest;

class PermissionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Inertia::render('permissions/Index',[
            'permissions' => Permission::select(
                'id',
                'name',
                'guard_name',
                'created_at'
            )->latest()->get()
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
   public function store(PermissionStoreRequest $request)
    {
        $permission = Permission::create([
            'name' => trim($request->name),
            'guard_name' => 'web'
        ]);
        return back()->with('success',"{$permission->name} permission Created successfully.");
    }

    /**
     * Display the specified resource.
     */
    public function show(Permission $permission)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Permission $permission)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(PermissionUpdateRequest $request, Permission $permission)
    {
        $permission->update([
            'name' => trim($request->name)
        ]);
        return back()->with('success',"{$permission->name} permission updated successfully.");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Permission $permission)
    {
        if ($permission->name === 'admin') {
            abort(403);
        }
        $permission->delete();
        return back()->with('success', 'Permission deleted');
    }
}
