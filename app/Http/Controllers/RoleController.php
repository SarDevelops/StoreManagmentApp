<?php

namespace App\Http\Controllers;

use App\Models\Role;
use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\RoleStoreRequest;
use App\Http\Requests\RoleUpdateRequest;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
         return Inertia::render('roles/Index',[
              'roles' => Role::select(
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
    public function store(RoleStoreRequest $request)
    {
      $role =   Role::create([
            'name'=>trim($request->name),
            'guard_name'=>'web'
        ]);
        return back()->with('success','Role created successfully');
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
    public function update(RoleUpdateRequest  $request, Role $role)
    {
        $role->update([
            'name'=>trim($request->name)
        ]);

        return back()->with('success','Role updated');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Role $role)
    {
         if($role->name==='admin'){
            abort(403);
        }

        $role->delete();

        return back()->with('success','Role deleted');
    }
}
