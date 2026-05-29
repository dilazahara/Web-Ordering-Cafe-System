<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Addon;
use App\Models\AddonGroup;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminAddOnsTest extends TestCase
{
    use RefreshDatabase;

    // ─────────────────────────────────────────
    // Helper
    // ─────────────────────────────────────────

    private function adminUser(): User
    {
        return User::factory()->create(['role' => 'admin']);
    }

    private function buatGroup(): AddonGroup
    {
        return AddonGroup::create([
            'name'     => 'Topping',
            'required' => 0,
            'max'      => 3,
        ]);
    }

    // ═══════════════════════════════════════════
    // INDEX
    // ═══════════════════════════════════════════

    public function test_admin_bisa_akses_halaman_addons(): void
    {
        $admin = $this->adminUser();

        $response = $this->actingAs($admin)->get('/admin/addons');

        $response->assertStatus(200);
    }

    public function test_guest_tidak_bisa_akses_halaman_addons(): void
    {
        $response = $this->get('/admin/addons');

        $response->assertRedirect('/login');
    }

    public function test_kasir_tidak_bisa_akses_halaman_addons(): void
    {
        $kasir = User::factory()->create(['role' => 'kasir']);

        $response = $this->actingAs($kasir)->get('/admin/addons');

        $response->assertStatus(403);
    }

    // ═══════════════════════════════════════════
    // STORE — Tambah add-on
    // ═══════════════════════════════════════════

    public function test_admin_bisa_tambah_addon(): void
    {
        $admin = $this->adminUser();
        $group = $this->buatGroup();

        $response = $this->actingAs($admin)->post('/admin/addons/store', [
            'name'           => 'Keju Extra',
            'price'          => 3000,
            'description'    => 'Tambahan keju',
            'addon_group_id' => $group->id,
            'status'         => '1',
        ]);

        $response->assertRedirect('/admin/addons');
        $this->assertDatabaseHas('addons', ['name' => 'Keju Extra', 'price' => 3000]);
    }

    public function test_tambah_addon_gagal_jika_nama_kosong(): void
    {
        $admin = $this->adminUser();
        $group = $this->buatGroup();

        $response = $this->actingAs($admin)->post('/admin/addons/store', [
            'name'           => '',
            'price'          => 3000,
            'addon_group_id' => $group->id,
            'status'         => '1',
        ]);

        $response->assertSessionHasErrors(['name']);
    }

    public function test_tambah_addon_gagal_jika_harga_negatif(): void
    {
        $admin = $this->adminUser();
        $group = $this->buatGroup();

        $response = $this->actingAs($admin)->post('/admin/addons/store', [
            'name'           => 'Keju Extra',
            'price'          => -1000,
            'addon_group_id' => $group->id,
            'status'         => '1',
        ]);

        $response->assertSessionHasErrors(['price']);
    }

    public function test_tambah_addon_gagal_jika_group_tidak_valid(): void
    {
        $admin = $this->adminUser();

        $response = $this->actingAs($admin)->post('/admin/addons/store', [
            'name'           => 'Keju Extra',
            'price'          => 3000,
            'addon_group_id' => 9999,
            'status'         => '1',
        ]);

        $response->assertSessionHasErrors(['addon_group_id']);
    }

    public function test_tambah_addon_gagal_jika_nama_terlalu_panjang(): void
    {
        $admin = $this->adminUser();
        $group = $this->buatGroup();

        $response = $this->actingAs($admin)->post('/admin/addons/store', [
            'name'           => str_repeat('A', 101),
            'price'          => 3000,
            'addon_group_id' => $group->id,
            'status'         => '1',
        ]);

        $response->assertSessionHasErrors(['name']);
    }

    // ═══════════════════════════════════════════
    // UPDATE — Edit add-on
    // ═══════════════════════════════════════════

    public function test_admin_bisa_update_addon(): void
    {
        $admin = $this->adminUser();
        $group = $this->buatGroup();

        $addon = Addon::create([
            'name'           => 'Keju Extra',
            'price'          => 3000,
            'addon_group_id' => $group->id,
            'status'         => 1,
        ]);

        $response = $this->actingAs($admin)->put("/admin/addons/update/{$addon->id}", [
            'name'           => 'Keju Double',
            'price'          => 5000,
            'addon_group_id' => $group->id,
            'status'         => '1',
        ]);

        $response->assertRedirect('/admin/addons');
        $this->assertDatabaseHas('addons', ['name' => 'Keju Double', 'price' => 5000]);
        $this->assertDatabaseMissing('addons', ['name' => 'Keju Extra']);
    }

    public function test_update_addon_gagal_jika_nama_kosong(): void
    {
        $admin = $this->adminUser();
        $group = $this->buatGroup();

        $addon = Addon::create([
            'name'           => 'Keju Extra',
            'price'          => 3000,
            'addon_group_id' => $group->id,
            'status'         => 1,
        ]);

        $response = $this->actingAs($admin)->put("/admin/addons/update/{$addon->id}", [
            'name'           => '',
            'price'          => 3000,
            'addon_group_id' => $group->id,
            'status'         => '1',
        ]);

        $response->assertSessionHasErrors(['name']);
        $this->assertDatabaseHas('addons', ['name' => 'Keju Extra']);
    }

    public function test_update_addon_gagal_jika_harga_negatif(): void
    {
        $admin = $this->adminUser();
        $group = $this->buatGroup();

        $addon = Addon::create([
            'name'           => 'Keju Extra',
            'price'          => 3000,
            'addon_group_id' => $group->id,
            'status'         => 1,
        ]);

        $response = $this->actingAs($admin)->put("/admin/addons/update/{$addon->id}", [
            'name'           => 'Keju Extra',
            'price'          => -500,
            'addon_group_id' => $group->id,
            'status'         => '1',
        ]);

        $response->assertSessionHasErrors(['price']);
    }

    public function test_update_addon_gagal_jika_group_tidak_valid(): void
    {
        $admin = $this->adminUser();
        $group = $this->buatGroup();

        $addon = Addon::create([
            'name'           => 'Keju Extra',
            'price'          => 3000,
            'addon_group_id' => $group->id,
            'status'         => 1,
        ]);

        $response = $this->actingAs($admin)->put("/admin/addons/update/{$addon->id}", [
            'name'           => 'Keju Extra',
            'price'          => 3000,
            'addon_group_id' => 9999,
            'status'         => '1',
        ]);

        $response->assertSessionHasErrors(['addon_group_id']);
    }

    public function test_update_addon_yang_tidak_ada_return_404(): void
    {
        $admin = $this->adminUser();
        $group = $this->buatGroup();

        $response = $this->actingAs($admin)->put('/admin/addons/update/9999', [
            'name'           => 'Test',
            'price'          => 1000,
            'addon_group_id' => $group->id,
            'status'         => '1',
        ]);

        $response->assertStatus(404);
    }

    // ═══════════════════════════════════════════
    // DESTROY — Hapus add-on
    // ═══════════════════════════════════════════

    public function test_admin_bisa_hapus_addon(): void
    {
        $admin = $this->adminUser();
        $group = $this->buatGroup();

        $addon = Addon::create([
            'name'           => 'Keju Extra',
            'price'          => 3000,
            'addon_group_id' => $group->id,
            'status'         => 1,
        ]);

        $response = $this->actingAs($admin)->post("/admin/addons/delete/{$addon->id}");

        $response->assertRedirect('/admin/addons');
        $this->assertDatabaseMissing('addons', ['id' => $addon->id]);
    }

    public function test_hapus_addon_yang_tidak_ada_return_404(): void
    {
        $admin = $this->adminUser();

        $response = $this->actingAs($admin)->post('/admin/addons/delete/9999');

        $response->assertStatus(404);
    }
}