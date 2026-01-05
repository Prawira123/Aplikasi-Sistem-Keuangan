<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        $users = User::with('role')->get();
        return view('users.index', compact('users'));
    }

    public function show($id){
        $user = User::with('role')->findOrFail($id);
        return view('users.show', compact('user'));
    }

    public function create(){
        return view('users.create');
    }

    public function store(Request $request){
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:users',
            'password' => 'required|confirmed',
            'role' => 'required',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role
        ]);

        if ($request->action === 'save_next') {
            return redirect()
            ->route('users.create')
            ->with('success', 'Product saved. Add next product.');
        }

        return redirect()->route('users.index')->with('success', 'User created successfully');
    }

    public function edit(User $user){
        return view('users.edit', compact('user'));
    }

    public function update(Request $request, User $user){
        $request->validate([
            'name' => 'required|string',
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique(User::class)->ignore($user->id),
            ],
            'role' => 'required',
        ]);

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role
        ]);

        return redirect()->route('users.index')->with('success', 'User updated successfully');
    }

    public function destroy(User $user){
        $user->delete();
        return redirect()->route('users.index')->with('success', 'User deleted successfully');
    }




}
