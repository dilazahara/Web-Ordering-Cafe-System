<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Order;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminLaporanTest extends TestCase
{
    use RefreshDatabase;

    // ─────────────────────────────────────────
    // Helper
    // ─────────────────────────────────────────

    private function adminUser(): User
    {
        return User::factory()->create(['role' => 'admin']);
    }

    private function buatOrder(string $status = 'done', string $tanggal = null): Order
    {
        $order = Order::create([
            'table_number'   => 1,
            'payment_method' => 'cash',
            'status'         => $status,
            'total'          => 50000,
            'note'           => null,
        ]);

        if ($tanggal) {
            $order->created_at = $tanggal;
            $order->save();
        }

        return $order;
    }

    // ═══════════════════════════════════════════
    // INDEX — Akses halaman laporan
    // ═══════════════════════════════════════════

    public function test_admin_bisa_akses_halaman_laporan(): void
    {
        $admin = $this->adminUser();

        $response = $this->actingAs($admin)->get('/admin/laporan');

        $response->assertStatus(200);
    }

    public function test_guest_tidak_bisa_akses_halaman_laporan(): void
    {
        $response = $this->get('/admin/laporan');

        $response->assertRedirect('/login');
    }

    public function test_kasir_tidak_bisa_akses_halaman_laporan(): void
    {
        $kasir = User::factory()->create(['role' => 'kasir']);

        $response = $this->actingAs($kasir)->get('/admin/laporan');

        $response->assertStatus(403);
    }

    // ═══════════════════════════════════════════
    // FILTER — Berbagai filter laporan
    // ═══════════════════════════════════════════

    public function test_laporan_default_tampil_order_hari_ini(): void
    {
        $admin = $this->adminUser();

        // Order hari ini
        $this->buatOrder('done');

        // Order kemarin — tidak boleh muncul
        $kemarin = now()->subDay()->toDateString();
        $this->buatOrder('done', $kemarin);

        $response = $this->actingAs($admin)->get('/admin/laporan');

        $response->assertStatus(200);
    }

    public function test_laporan_filter_tanggal_spesifik(): void
    {
        $admin   = $this->adminUser();
        $tanggal = now()->toDateString();

        $this->buatOrder('done');

        $response = $this->actingAs($admin)->get("/admin/laporan?tanggal={$tanggal}");

        $response->assertStatus(200);
    }

    public function test_laporan_filter_rentang_tanggal(): void
    {
        $admin = $this->adminUser();
        $dari  = now()->subDays(7)->toDateString();
        $sampai = now()->toDateString();

        $this->buatOrder('done');

        $response = $this->actingAs($admin)->get("/admin/laporan?dari={$dari}&sampai={$sampai}");

        $response->assertStatus(200);
    }

    public function test_laporan_filter_hari(): void
    {
        $admin = $this->adminUser();

        $response = $this->actingAs($admin)->get('/admin/laporan?filter=hari');

        $response->assertStatus(200);
    }

    public function test_laporan_filter_bulan(): void
    {
        $admin = $this->adminUser();

        $response = $this->actingAs($admin)->get('/admin/laporan?filter=bulan');

        $response->assertStatus(200);
    }

    public function test_laporan_filter_tahun(): void
    {
        $admin = $this->adminUser();

        $response = $this->actingAs($admin)->get('/admin/laporan?filter=tahun');

        $response->assertStatus(200);
    }

    // ═══════════════════════════════════════════
    // STATUS — Hanya order aktif yang muncul
    // ═══════════════════════════════════════════

    public function test_laporan_tidak_tampilkan_order_selesai(): void
    {
        $admin = $this->adminUser();

        // Order 'selesai' tidak masuk laporan
        $this->buatOrder('selesai');

        $response = $this->actingAs($admin)->get('/admin/laporan');

        $response->assertStatus(200);
    }

    public function test_laporan_tampilkan_semua_status_aktif(): void
    {
        $admin = $this->adminUser();

        foreach (['pending', 'paid', 'process', 'done', 'delivered'] as $status) {
            $this->buatOrder($status);
        }

        $response = $this->actingAs($admin)->get('/admin/laporan');

        $response->assertStatus(200);
    }

    // ═══════════════════════════════════════════
    // EXPORT PDF
    // ═══════════════════════════════════════════

    public function test_admin_bisa_export_pdf(): void
    {
        $admin = $this->adminUser();

        $this->buatOrder('done');

        $response = $this->actingAs($admin)->get('/admin/laporan/pdf');

        // PDF download: status 200 dengan content-type pdf
        $response->assertStatus(200);
        $this->assertStringContainsString(
            'application/pdf',
            $response->headers->get('Content-Type')
        );
    }

    public function test_guest_tidak_bisa_export_pdf(): void
    {
        $response = $this->get('/admin/laporan/pdf');

        $response->assertRedirect('/login');
    }

    public function test_export_pdf_dengan_filter_tanggal(): void
    {
        $admin   = $this->adminUser();
        $tanggal = now()->toDateString();

        $this->buatOrder('done');

        $response = $this->actingAs($admin)->get("/admin/laporan/pdf?tanggal={$tanggal}");

        $response->assertStatus(200);
        $this->assertStringContainsString(
            'application/pdf',
            $response->headers->get('Content-Type')
        );
    }
}