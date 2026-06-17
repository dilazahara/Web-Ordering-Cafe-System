<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AddonGroup;

class AddonGroupController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $group = AddonGroup::create([
            'name'     => $request->name,
            'required' => $request->required ?? 0,
            'max'      => $request->max ?? null,
        ]);

        return response()->json([
            'success' => true,
            'group'   => $group
        ]);
    }

    // ==========================
    // EDIT GROUP
    // ==========================
    public function edit($id)
    {
        $group = AddonGroup::findOrFail($id);

        return response()->json([
            'success' => true,
            'group'   => $group
        ]);
    }

    public function update(Request $request, $id)
{
    $request->validate([
        'name' => 'required|string|max:255',
    ]);

    $group = AddonGroup::findOrFail($id);

    $group->update([
        'name'     => $request->name,
        'required' => $request->required ?? 0,
        'max'      => $request->max ?: null,
    ]);

    return response()->json([
        'success' => true,
        'group'   => $group,
        'message' => 'Group berhasil diperbarui.'
    ]);
}
     
    

    // ==========================
    // HAPUS GROUP
    // ==========================
    public function destroy($id)
    {
        $group = AddonGroup::findOrFail($id);

        // Cegah hapus jika masih dipakai add-on
        if ($group->addons()->count() > 0) {
            return back()->with(
                'error',
                'Group masih digunakan oleh add-on dan tidak dapat dihapus.'
            );
        }

        $group->delete();

        return back()->with(
            'success',
            'Group berhasil dihapus.'
        );
    }
}
