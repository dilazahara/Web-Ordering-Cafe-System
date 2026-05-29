<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Kategori;
use App\Models\Menu;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminKategoriTest extends TestCase
{
    use RefreshDatabase;

    // ─────────────────────────────────────────
    // Helper
    // ─────────────────────────────────────────

    private function adminUser(): User
    {
        return User::factory()->create(['role' => 'admin']);
    }

    // ═══════════════════════════════════════════
    // INDEX
    // ═══════════════════════════════════════════

    public function test_admin_bisa_akses_halaman_kategori(): void
    {
        $admin = $this->adminUser();

        $response = $this->actingAs($admin)->get('/admin/kategori');

        $response->assertStatus(200);
    }

    public function test_guest_tidak_bisa_akses_halaman_kategori(): void
    {
        $response = $this->get('/admin/kategori');

        $response->assertRedirect('/login');
    }

    public function test_kasir_tidak_bisa_akses_halaman_kategori(): void
    {
        $kasir = User::factory()->create(['role' => 'kasir']);

        $response = $this->actingAs($kasir)->get('/admin/kategori');

        $response->assertStatus(403);
    }

    // ═══════════════════════════════════════════
    // STORE — Tambah kategori
    // ═══════════════════════════════════════════

    public function test_admin_bisa_tambah_kategori(): void
    {
        $admin = $this->adminUser();

        $response = $this->actingAs($admin)->post('/admin/kategori/store', [
            'name' => 'Minuman',
        ]);

        $response->assertRedirect('/admin/kategori');
        $this->assertDatabaseHas('kategoris', ['name' => 'Minuman']);
    }

    public function test_tambah_kategori_gagal_jika_nama_kosong(): void
    {
        $admin = $this->adminUser();

        $response = $this->actingAs($admin)->post('/admin/kategori/store', [
            'name' => '',
        ]);

        $response->assertSessionHasErrors(['name']);
        $this->assertDatabaseMissing('kategoris', ['name' => '']);
    }

    public function test_tambah_kategori_gagal_jika_nama_duplikat(): void
    {
        $admin = $this->adminUser();

        Kategori::create(['name' => 'Makanan']);

        $response = $this->actingAs($admin)->post('/admin/kategori/store', [
            'name' => 'Makanan',
        ]);

        $response->assertSessionHasErrors(['name']);
        $this->assertEquals(1, Kategori::where('name', 'Makanan')->count());
    }

    public function test_tambah_kategori_gagal_jika_nama_terlalu_panjang(): void
    {
        $admin = $this->adminUser();

        $response = $this->actingAs($admin)->post('/admin/kategori/store', [
            'name' => str_repeat('A', 101),
        ]);

        $response->assertSessionHasErrors(['name']);
    }

    // ═══════════════════════════════════════════
    // UPDATE — Edit kategori
    // ═══════════════════════════════════════════

    public function test_admin_bisa_update_kategori(): void
    {
        $admin    = $this->adminUser();
        $kategori = Kategori::create(['name' => 'Minuman']);

        $response = $this->actingAs($admin)->put("/admin/kategori/update/{$kategori->id}", [
            'name' => 'Minuman Segar',
        ]);

        $response->assertRedirect('/admin/kategori');
        $this->assertDatabaseHas('kategoris', ['name' => 'Minuman Segar']);
        $this->assertDatabaseMissing('kategoris', ['name' => 'Minuman']);
    }

    public function test_update_kategori_gagal_jika_nama_kosong(): void
    {
        $admin    = $this->adminUser();
        $kategori = Kategori::create(['name' => 'Minuman']);

        $response = $this->actingAs($admin)->put("/admin/kategori/update/{$kategori->id}", [
            'name' => '',
        ]);

        $response->assertSessionHasErrors(['name']);
        $this->assertDatabaseHas('kategoris', ['name' => 'Minuman']);
    }

    public function test_update_kategori_gagal_jika_nama_duplikat(): void
    {
        $admin = $this->adminUser();

        Kategori::create(['name' => 'Makanan']);
        $kategori = Kategori::create(['name' => 'Minuman']);

        $response = $this->actingAs($admin)->put("/admin/kategori/update/{$kategori->id}", [
            'name' => 'Makanan',
        ]);

        $response->assertSessionHasErrors(['name']);
        $this->assertDatabaseHas('kategoris', ['name' => 'Minuman']);
    }

    public function test_update_kategori_tidak_conflict_dengan_dirinya_sendiri(): void
    {
        $admin    = $this->adminUser();
        $kategori = Kategori::create(['name' => 'Minuman']);

        // Update dengan nama yang sama (tidak boleh error)
        $response = $this->actingAs($admin)->put("/admin/kategori/update/{$kategori->id}", [
            'name' => 'Minuman',
        ]);

        $response->assertRedirect('/admin/kategori');
        $this->assertDatabaseHas('kategoris', ['name' => 'Minuman']);
    }

    public function test_update_kategori_yang_tidak_ada_return_404(): void
    {
        $admin = $this->adminUser();

        $response = $this->actingAs($admin)->put('/admin/kategori/update/9999', [
            'name' => 'Test',
        ]);

        $response->assertStatus(404);
    }

    // ═══════════════════════════════════════════
    // DESTROY — Hapus kategori
    // ═══════════════════════════════════════════

    public function test_admin_bisa_hapus_kategori(): void
    {
        $admin    = $this->adminUser();
        $kategori = Kategori::create(['name' => 'Minuman']);

        $response = $this->actingAs($admin)->delete("/admin/kategori/delete/{$kategori->id}");

        $response->assertRedirect('/admin/kategori');
        $this->assertDatabaseMissing('kategoris', ['id' => $kategori->id]);
    }

    public function test_hapus_kategori_gagal_jika_masih_dipakai_menu(): void
    {
        $admin    = $this->adminUser();
        $kategori = Kategori::create(['name' => 'Minuman']);

        Menu::create([
            'name'        => 'Es Teh',
            'kategori_id' => $kategori->id,
            'price'       => 5000,
            'status'      => 1,
        ]);

        $response = $this->actingAs($admin)->delete("/admin/kategori/delete/{$kategori->id}");

        $response->assertRedirect('/admin/kategori');
        $response->assertSessionHas('error');
        $this->assertDatabaseHas('kategoris', ['id' => $kategori->id]);
    }

    public function test_hapus_kategori_yang_tidak_ada_return_404(): void
    {
        $admin = $this->adminUser();

        $response = $this->actingAs($admin)->delete('/admin/kategori/delete/9999');

        $response->assertStatus(404);
    }
}