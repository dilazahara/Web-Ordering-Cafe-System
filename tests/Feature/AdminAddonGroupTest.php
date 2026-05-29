<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\AddonGroup;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminAddonGroupTest extends TestCase
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
    // STORE — Tambah addon group
    // ═══════════════════════════════════════════

    public function test_admin_bisa_tambah_addon_group(): void
    {
        $admin = $this->adminUser();

        $response = $this->actingAs($admin)->post('/admin/addon-groups/store', [
            'name'     => 'Topping',
            'required' => 1,
            'max'      => 3,
        ]);

        $response->assertStatus(200);
        $response->assertJson(['success' => true]);
        $this->assertDatabaseHas('addon_groups', ['name' => 'Topping']);
    }

    public function test_tambah_addon_group_berhasil_tanpa_required_dan_max(): void
    {
        $admin = $this->adminUser();

        $response = $this->actingAs($admin)->post('/admin/addon-groups/store', [
            'name' => 'Minuman Tambahan',
        ]);

        $response->assertStatus(200);
        $response->assertJson(['success' => true]);
        $this->assertDatabaseHas('addon_groups', [
            'name'     => 'Minuman Tambahan',
            'required' => 0,
        ]);
    }

    public function test_tambah_addon_group_gagal_jika_nama_kosong(): void
    {
        $admin = $this->adminUser();

        $response = $this->actingAs($admin)
            ->withHeaders(['Accept' => 'application/json'])
            ->post('/admin/addon-groups/store', [
                'name' => '',
            ]);

        $response->assertStatus(422);
        $this->assertDatabaseCount('addon_groups', 0);
    }

    public function test_tambah_addon_group_gagal_jika_nama_terlalu_panjang(): void
    {
        $admin = $this->adminUser();

        $response = $this->actingAs($admin)
            ->withHeaders(['Accept' => 'application/json'])
            ->post('/admin/addon-groups/store', [
                'name' => str_repeat('A', 256),
            ]);

        $response->assertStatus(422);
        $this->assertDatabaseCount('addon_groups', 0);
    }

    public function test_guest_tidak_bisa_tambah_addon_group(): void
    {
        $response = $this->post('/admin/addon-groups/store', [
            'name' => 'Topping',
        ]);

        $response->assertRedirect('/login');
        $this->assertDatabaseCount('addon_groups', 0);
    }

    public function test_kasir_tidak_bisa_tambah_addon_group(): void
    {
        $kasir = User::factory()->create(['role' => 'kasir']);

        $response = $this->actingAs($kasir)->post('/admin/addon-groups/store', [
            'name' => 'Topping',
        ]);

        $response->assertStatus(403);
        $this->assertDatabaseCount('addon_groups', 0);
    }

    public function test_response_berisi_data_group_yang_baru_dibuat(): void
    {
        $admin = $this->adminUser();

        $response = $this->actingAs($admin)->post('/admin/addon-groups/store', [
            'name'     => 'Saus',
            'required' => 0,
            'max'      => 2,
        ]);

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'success',
            'group' => ['id', 'name', 'required', 'max'],
        ]);
        $response->assertJsonPath('group.name', 'Saus');
    }
}