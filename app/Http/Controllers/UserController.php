<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{
    // ===============================
    // TAMPILKAN DATA
    // ===============================
    public function index()
    {
        $users = User::all();

        return view('admin.user.index', compact('users'));
    }

    // ===============================
    // FORM TAMBAH
    // ===============================
    public function create()
    {
        return view('admin.user.create');
    }

    // ===============================
    // SIMPAN DATA
    // ===============================
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

    // ===============================
    // FORM EDIT
    // ===============================
    public function edit(int $id)
    {
        $item = User::findOrFail($id);

        return view('admin.user.edit', compact('item'));
    }

    // ===============================
    // UPDATE
    // ===============================
    public function update(Request $request, int $id)
    {
        $request->validate([
            'role' => 'required'
        ]);

        $user = User::findOrFail($id);

        // proteksi admin utama
        if ($user->email == 'admin@gmail.com') {
            return back()->withErrors(
                'Role admin utama tidak boleh diubah'
            );
        }

        // update role saja
        $user->role = $request->role;

        $user->save();

        return redirect('/admin/user')
            ->with('success', 'Role berhasil diupdate!');
    }

    // ===============================
    // DELETE
    // ===============================
    public function delete(int $id)
    {
        User::findOrFail($id)->delete();

        return redirect('/admin/user')
            ->with('success', 'User berhasil dihapus!');
    }
}