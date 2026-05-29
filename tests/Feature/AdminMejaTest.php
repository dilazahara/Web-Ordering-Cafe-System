<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Meja;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminMejaTest extends TestCase
{
    use RefreshDatabase;

    // ─────────────────────────────────────────
    // Helper
    // ─────────────────────────────────────────

    private function adminUser(): User
    {
        return User::factory()->create(['role' => 'admin']);
    }

    private function buatMeja(string $nomor = 'A1', string $status = 'kosong'): Meja
    {
        return Meja::create([
            'nomor_meja' => $nomor,
            'status'     => $status,
        ]);
    }

    // ═══════════════════════════════════════════
    // INDEX
    // ═══════════════════════════════════════════

    public function test_admin_bisa_akses_halaman_meja(): void
    {
        $admin = $this->adminUser();

        $response = $this->actingAs($admin)->get('/admin/meja');

        $response->assertStatus(200);
    }

    public function test_guest_tidak_bisa_akses_halaman_meja(): void
    {
        $response = $this->get('/admin/meja');

        $response->assertRedirect('/login');
    }

    public function test_kasir_tidak_bisa_akses_halaman_meja(): void
    {
        $kasir = User::factory()->create(['role' => 'kasir']);

        $response = $this->actingAs($kasir)->get('/admin/meja');

        $response->assertStatus(403);
    }

    // ═══════════════════════════════════════════
    // STORE — Tambah meja
    // ═══════════════════════════════════════════

    public function test_admin_bisa_tambah_meja(): void
    {
        $admin = $this->adminUser();

        $response = $this->actingAs($admin)->post('/admin/meja/store', [
            'nomor_meja' => 'A1',
            'status'     => 'kosong',
        ]);

        $response->assertRedirect('/admin/meja');
        $this->assertDatabaseHas('mejas', ['nomor_meja' => 'A1', 'status' => 'kosong']);
    }

    public function test_tambah_meja_default_status_kosong(): void
    {
        $admin = $this->adminUser();

        $response = $this->actingAs($admin)->post('/admin/meja/store', [
            'nomor_meja' => 'B2',
        ]);

        $response->assertRedirect('/admin/meja');
        $this->assertDatabaseHas('mejas', ['nomor_meja' => 'B2', 'status' => 'kosong']);
    }

    public function test_tambah_meja_gagal_jika_nomor_kosong(): void
    {
        $admin = $this->adminUser();

        $response = $this->actingAs($admin)->post('/admin/meja/store', [
            'nomor_meja' => '',
        ]);

        $response->assertSessionHasErrors(['nomor_meja']);
        $this->assertDatabaseCount('mejas', 0);
    }

    public function test_tambah_meja_gagal_jika_nomor_duplikat(): void
    {
        $admin = $this->adminUser();
        $this->buatMeja('A1');

        $response = $this->actingAs($admin)->post('/admin/meja/store', [
            'nomor_meja' => 'A1',
        ]);

        $response->assertSessionHasErrors(['nomor_meja']);
        $this->assertEquals(1, Meja::where('nomor_meja', 'A1')->count());
    }

    public function test_tambah_meja_gagal_jika_nomor_terlalu_panjang(): void
    {
        $admin = $this->adminUser();

        $response = $this->actingAs($admin)->post('/admin/meja/store', [
            'nomor_meja' => str_repeat('A', 11),
        ]);

        $response->assertSessionHasErrors(['nomor_meja']);
    }

    public function test_tambah_meja_gagal_jika_status_tidak_valid(): void
    {
        $admin = $this->adminUser();

        $response = $this->actingAs($admin)->post('/admin/meja/store', [
            'nomor_meja' => 'C3',
            'status'     => 'rusak',
        ]);

        $response->assertSessionHasErrors(['status']);
    }

    // ═══════════════════════════════════════════
    // UPDATE — Edit meja
    // ═══════════════════════════════════════════

    public function test_admin_bisa_update_meja(): void
    {
        $admin = $this->adminUser();
        $meja  = $this->buatMeja('A1', 'kosong');

        $response = $this->actingAs($admin)->put("/admin/meja/update/{$meja->id}", [
            'nomor_meja' => 'A2',
            'status'     => 'reserved',
        ]);

        $response->assertRedirect('/admin/meja');
        $this->assertDatabaseHas('mejas', ['nomor_meja' => 'A2', 'status' => 'reserved']);
        $this->assertDatabaseMissing('mejas', ['nomor_meja' => 'A1']);
    }

    public function test_update_meja_tidak_conflict_dengan_dirinya_sendiri(): void
    {
        $admin = $this->adminUser();
        $meja  = $this->buatMeja('A1', 'kosong');

        $response = $this->actingAs($admin)->put("/admin/meja/update/{$meja->id}", [
            'nomor_meja' => 'A1',
            'status'     => 'reserved',
        ]);

        $response->assertRedirect('/admin/meja');
        $this->assertDatabaseHas('mejas', ['nomor_meja' => 'A1', 'status' => 'reserved']);
    }

    public function test_update_meja_gagal_jika_nomor_kosong(): void
    {
        $admin = $this->adminUser();
        $meja  = $this->buatMeja('A1');

        $response = $this->actingAs($admin)->put("/admin/meja/update/{$meja->id}", [
            'nomor_meja' => '',
        ]);

        $response->assertSessionHasErrors(['nomor_meja']);
        $this->assertDatabaseHas('mejas', ['nomor_meja' => 'A1']);
    }

    public function test_update_meja_gagal_jika_nomor_duplikat(): void
    {
        $admin = $this->adminUser();
        $this->buatMeja('A1');
        $meja2 = $this->buatMeja('B2');

        $response = $this->actingAs($admin)->put("/admin/meja/update/{$meja2->id}", [
            'nomor_meja' => 'A1',
            'status'     => 'kosong',
        ]);

        $response->assertSessionHasErrors(['nomor_meja']);
        $this->assertDatabaseHas('mejas', ['nomor_meja' => 'B2']);
    }

    public function test_update_meja_yang_tidak_ada_return_404(): void
    {
        $admin = $this->adminUser();

        $response = $this->actingAs($admin)->put('/admin/meja/update/9999', [
            'nomor_meja' => 'Z9',
            'status'     => 'kosong',
        ]);

        $response->assertStatus(404);
    }

    // ═══════════════════════════════════════════
    // DESTROY — Hapus meja
    // ═══════════════════════════════════════════

    public function test_admin_bisa_hapus_meja(): void
    {
        $admin = $this->adminUser();
        $meja  = $this->buatMeja('A1', 'kosong');

        $response = $this->actingAs($admin)->delete("/admin/meja/delete/{$meja->id}");

        $response->assertRedirect('/admin/meja');
        $this->assertDatabaseMissing('mejas', ['id' => $meja->id]);
    }

    public function test_hapus_meja_gagal_jika_status_terisi(): void
    {
        $admin = $this->adminUser();
        $meja  = $this->buatMeja('A1', 'terisi');

        $response = $this->actingAs($admin)->delete("/admin/meja/delete/{$meja->id}");

        $response->assertRedirect('/admin/meja');
        $response->assertSessionHas('error');
        $this->assertDatabaseHas('mejas', ['id' => $meja->id]);
    }

    public function test_hapus_meja_yang_tidak_ada_return_404(): void
    {
        $admin = $this->adminUser();

        $response = $this->actingAs($admin)->delete('/admin/meja/delete/9999');

        $response->assertStatus(404);
    }
}