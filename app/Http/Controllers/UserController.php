<?php

namespace App\Http\Controllers;

use App\Models\User;
use Inertia\Inertia;
use App\Traits\SecureCrud;
use Illuminate\Http\Request;
use App\Http\Requests\UserRequest;
use Spatie\Permission\Models\Role;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    use SecureCrud;
    public function index()
    {
        return Inertia::render('users/Index', [
            'users' => User::with('roles:id,name')
                ->select('id', 'name', 'email', 'created_at')
                ->latest()
                ->get(),

            'roles' => Role::select('id', 'name')
                ->latest()
                ->get(),
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
    public function store(UserRequest $request)
    {
        $user = $this->secureStore(
            User::class,
            [
                'name' => trim($request->name),
                'email' => trim($request->email),
                'password' => Hash::make($request->password),
            ]
        );

        $user->assignRole($request->role);

        return back()->with(
            'success',
            "{$user->name} created successfully."
        );
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(
        UserRequest $request,
        User $user
    ) {
        $data = [
            'name' => trim($request->name),
            'email' => trim($request->email),
        ];

        // only update password if entered
        if ($request->filled('password')) {
            $data['password'] =
                Hash::make(
                    $request->password
                );
        }

        $this->secureUpdate(
            $user,
            $data
        );

        $user->syncRoles([
            $request->role
        ]);

        return back()->with(
            'success',
            "{$user->name} updated successfully."
        );
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        $this->secureDelete($user);

        return back()->with(
            'success',
            "{$user->name} deleted successfully."
        );
    }
}
