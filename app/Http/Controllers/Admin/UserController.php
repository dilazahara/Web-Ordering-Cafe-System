<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{
    public function index()
    {
        $users = User::all();

        return view('admin.user.index', compact('users'));
    }

    public function create()
    {
        return view('admin.user.create');
    }

    public function store(Request $request)
    {
        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'role' => $request->role
        ]);

        return redirect('/admin/user')
            ->with('success', 'User berhasil ditambahkan!');
    }

    public function edit(int $id)
    {
        $item = User::findOrFail($id);

        return view('admin.user.edit', compact('item'));
    }

    public function update(Request $request, int $id)
    {
        $request->validate([
            'role' => 'required'
        ]);

        $user = User::findOrFail($id);

        if ($user->email == 'admin@gmail.com') {
            return back()->withErrors(
                'Role admin utama tidak boleh diubah'
            );
        }

        $user->role = $request->role;

        $user->save();

        return redirect('/admin/user')
            ->with('success', 'Role berhasil diupdate!');
    }

    public function delete(int $id)
    {
        User::findOrFail($id)->delete();

        return redirect('/admin/user')
            ->with('success', 'User berhasil dihapus!');
    }
}