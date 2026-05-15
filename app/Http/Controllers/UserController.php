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
        $users = User::all(); // 🔥 ambil dari database
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
            'password' => bcrypt($request->password), // 🔥 penting
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
    $user = User::findOrFail($id);

    $data = [
        'name' => $request->name,
        'email' => $request->email,
        'role' => $request->role
    ];

    // kalau isi password baru
    if ($request->password) {
        $data['password'] = bcrypt($request->password);
    }

    $user->update($data);

    return redirect('/admin/user')->with('success','User berhasil diupdate!');
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