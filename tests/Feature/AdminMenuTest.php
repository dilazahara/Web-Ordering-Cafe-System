<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Menu;
use App\Models\Kategori;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AdminMenuTest extends TestCase
{
    use RefreshDatabase;

    // ─────────────────────────────────────────
    // Helper — buat user admin & kategori dummy
    // ─────────────────────────────────────────

    private function adminUser(): User
    {
        return User::factory()->create(['role' => 'admin']);
    }

    private function buatKategori(): Kategori
    {
        return Kategori::create(['name' => 'Minuman']);
    }

    // ═══════════════════════════════════════════
    // INDEX — Halaman daftar menu
    // ═══════════════════════════════════════════

    public function test_admin_bisa_akses_halaman_daftar_menu(): void
    {
        $admin = $this->adminUser();

        $response = $this->actingAs($admin)->get('/admin/menu');

        $response->assertStatus(200);
    }

    public function test_guest_tidak_bisa_akses_halaman_daftar_menu(): void
    {
        $response = $this->get('/admin/menu');

        $response->assertRedirect('/login');
    }

    public function test_kasir_tidak_bisa_akses_halaman_daftar_menu(): void
    {
        $kasir = User::factory()->create(['role' => 'kasir']);

        $response = $this->actingAs($kasir)->get('/admin/menu');

        $response->assertStatus(403);
    }

    // ═══════════════════════════════════════════
    // CREATE — Tambah menu baru
    // ═══════════════════════════════════════════

    public function test_admin_bisa_tambah_menu_baru(): void
    {
        Storage::fake('public');

        $admin    = $this->adminUser();
        $kategori = $this->buatKategori();

        $response = $this->actingAs($admin)->post('/admin/menu', [
            'name'        => 'Es Teh Manis',
            'kategori_id' => $kategori->id,
            'price'       => 8000,
            'description' => 'Teh manis dingin segar',
            'status'      => '1',
            'image'       => UploadedFile::fake()->create('esteh.jpg', 100, 'image/jpeg'),
        ]);

        $response->assertRedirect('/admin/menu');
        $this->assertDatabaseHas('menus', ['name' => 'Es Teh Manis']);
    }

    public function test_tambah_menu_gagal_jika_nama_kosong(): void
    {
        $admin    = $this->adminUser();
        $kategori = $this->buatKategori();

        $response = $this->actingAs($admin)->post('/admin/menu', [
            'name'        => '',
            'kategori_id' => $kategori->id,
            'price'       => 8000,
            'status'      => '1',
        ]);

        $response->assertSessionHasErrors(['name']);
        $this->assertDatabaseMissing('menus', ['kategori_id' => $kategori->id]);
    }

    public function test_tambah_menu_gagal_jika_nama_duplikat(): void
    {
        $admin    = $this->adminUser();
        $kategori = $this->buatKategori();

        // Buat menu pertama
        Menu::create([
            'name'        => 'Es Teh Manis',
            'kategori_id' => $kategori->id,
            'price'       => 8000,
            'status'      => 1,
        ]);

        // Coba tambah menu dengan nama yang sama
        $response = $this->actingAs($admin)->post('/admin/menu', [
            'name'        => 'Es Teh Manis',
            'kategori_id' => $kategori->id,
            'price'       => 10000,
            'status'      => '1',
        ]);

        $response->assertSessionHasErrors(['name']);
    }

    public function test_tambah_menu_gagal_jika_harga_negatif(): void
    {
        $admin    = $this->adminUser();
        $kategori = $this->buatKategori();

        $response = $this->actingAs($admin)->post('/admin/menu', [
            'name'        => 'Menu Aneh',
            'kategori_id' => $kategori->id,
            'price'       => -1000,
            'status'      => '1',
        ]);

        $response->assertSessionHasErrors(['price']);
    }

    public function test_tambah_menu_gagal_jika_kategori_tidak_valid(): void
    {
        $admin = $this->adminUser();

        $response = $this->actingAs($admin)->post('/admin/menu', [
            'name'        => 'Menu Test',
            'kategori_id' => 9999, // ID tidak ada
            'price'       => 8000,
            'status'      => '1',
        ]);

        $response->assertSessionHasErrors(['kategori_id']);
    }

    // ═══════════════════════════════════════════
    // UPDATE — Edit menu
    // ═══════════════════════════════════════════

    public function test_admin_bisa_update_menu(): void
    {
        $admin    = $this->adminUser();
        $kategori = $this->buatKategori();

        $menu = Menu::create([
            'name'        => 'Es Teh Manis',
            'kategori_id' => $kategori->id,
            'price'       => 8000,
            'status'      => 1,
        ]);

        $response = $this->actingAs($admin)->put("/admin/menu/{$menu->id}", [
            'name'        => 'Es Teh Tawar',
            'kategori_id' => $kategori->id,
            'price'       => 7000,
            'status'      => '1',
        ]);

        $response->assertRedirect('/admin/menu');
        $this->assertDatabaseHas('menus', ['name' => 'Es Teh Tawar', 'price' => 7000]);
        $this->assertDatabaseMissing('menus', ['name' => 'Es Teh Manis']);
    }

    public function test_update_menu_gagal_jika_nama_kosong(): void
    {
        $admin    = $this->adminUser();
        $kategori = $this->buatKategori();

        $menu = Menu::create([
            'name'        => 'Es Teh Manis',
            'kategori_id' => $kategori->id,
            'price'       => 8000,
            'status'      => 1,
        ]);

        $response = $this->actingAs($admin)->put("/admin/menu/{$menu->id}", [
            'name'        => '',
            'kategori_id' => $kategori->id,
            'price'       => 7000,
            'status'      => '1',
        ]);

        $response->assertSessionHasErrors(['name']);
        // Nama lama harus tetap ada
        $this->assertDatabaseHas('menus', ['name' => 'Es Teh Manis']);
    }

    // ═══════════════════════════════════════════
    // DESTROY — Hapus menu
    // ═══════════════════════════════════════════

    public function test_admin_bisa_hapus_menu(): void
    {
        Storage::fake('public');

        $admin    = $this->adminUser();
        $kategori = $this->buatKategori();

        $menu = Menu::create([
            'name'        => 'Es Teh Manis',
            'kategori_id' => $kategori->id,
            'price'       => 8000,
            'status'      => 1,
        ]);

        $response = $this->actingAs($admin)->delete("/admin/menu/{$menu->id}");

        $response->assertRedirect('/admin/menu');
        $this->assertDatabaseMissing('menus', ['id' => $menu->id]);
    }

    public function test_hapus_menu_yang_tidak_ada_return_404(): void
    {
        $admin = $this->adminUser();

        $response = $this->actingAs($admin)->delete('/admin/menu/9999');

        $response->assertStatus(404);
    }

}