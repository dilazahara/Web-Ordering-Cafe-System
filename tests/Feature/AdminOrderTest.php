<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Menu;
use App\Models\Meja;
use App\Models\Order;
use App\Models\Kategori;
use App\Models\PaymentMethod;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminOrderTest extends TestCase
{
    use RefreshDatabase;

    // ─────────────────────────────────────────
    // Helper
    // ─────────────────────────────────────────

    private function adminUser(): User
    {
        return User::factory()->create(['role' => 'admin']);
    }

    private function buatPaymentMethod(string $kode = 'cash'): PaymentMethod
    {
        return PaymentMethod::create([
            'kode'  => $kode,
            'nama'  => ucfirst($kode),
            'aktif' => true,
        ]);
    }

    private function buatMenu(): Menu
    {
        $kategori = Kategori::create(['name' => 'Minuman']);
        return Menu::create([
            'name'        => 'Es Teh',
            'kategori_id' => $kategori->id,
            'price'       => 5000,
            'status'      => 1,
        ]);
    }

    private function buatOrder(string $status = 'pending', string $payment = 'cash'): Order
    {
        return Order::create([
            'queue_number'   => 'A-001',
            'table_number'   => '1',
            'payment_method' => $payment,
            'status'         => $status,
            'total'          => 52000,
            'note'           => null,
        ]);
    }

    // ═══════════════════════════════════════════
    // INDEX
    // ═══════════════════════════════════════════

    public function test_admin_bisa_akses_halaman_order(): void
    {
        $admin = $this->adminUser();

        $response = $this->actingAs($admin)->get('/admin/order');

        $response->assertStatus(200);
    }

    public function test_guest_tidak_bisa_akses_halaman_order(): void
    {
        $response = $this->get('/admin/order');

        $response->assertRedirect('/login');
    }

    public function test_kasir_tidak_bisa_akses_halaman_order(): void
    {
        $kasir = User::factory()->create(['role' => 'kasir']);

        $response = $this->actingAs($kasir)->get('/admin/order');

        $response->assertStatus(403);
    }

    // ═══════════════════════════════════════════
    // PROCESS — Ubah status ke 'process'
    // ═══════════════════════════════════════════

    public function test_admin_bisa_process_order_dari_pending(): void
    {
        $admin = $this->adminUser();
        $order = $this->buatOrder('pending');

        $response = $this->actingAs($admin)->post("/admin/order/process/{$order->id}");

        $response->assertRedirect();
        $this->assertDatabaseHas('orders', ['id' => $order->id, 'status' => 'process']);
    }

    public function test_admin_bisa_process_order_dari_paid(): void
    {
        $admin = $this->adminUser();
        $order = $this->buatOrder('paid');

        $response = $this->actingAs($admin)->post("/admin/order/process/{$order->id}");

        $response->assertRedirect();
        $this->assertDatabaseHas('orders', ['id' => $order->id, 'status' => 'process']);
    }

    public function test_process_order_gagal_jika_status_sudah_done(): void
    {
        $admin = $this->adminUser();
        $order = $this->buatOrder('done');

        $response = $this->actingAs($admin)->post("/admin/order/process/{$order->id}");

        $response->assertStatus(422);
        $this->assertDatabaseHas('orders', ['id' => $order->id, 'status' => 'done']);
    }

    public function test_process_order_gagal_jika_status_delivered(): void
    {
        $admin = $this->adminUser();
        $order = $this->buatOrder('delivered');

        $response = $this->actingAs($admin)->post("/admin/order/process/{$order->id}");

        $response->assertStatus(422);
    }

    public function test_process_order_yang_tidak_ada_return_404(): void
    {
        $admin = $this->adminUser();

        $response = $this->actingAs($admin)->post('/admin/order/process/9999');

        $response->assertStatus(404);
    }

    // ═══════════════════════════════════════════
    // DONE — Ubah status ke 'done'
    // ═══════════════════════════════════════════

    public function test_admin_bisa_done_order_dari_process(): void
    {
        $admin = $this->adminUser();
        $order = $this->buatOrder('process');

        $response = $this->actingAs($admin)->post("/admin/order/done/{$order->id}");

        $response->assertRedirect();
        $this->assertDatabaseHas('orders', ['id' => $order->id, 'status' => 'done']);
    }

    public function test_done_order_gagal_jika_status_bukan_process(): void
    {
        $admin = $this->adminUser();
        $order = $this->buatOrder('pending');

        $response = $this->actingAs($admin)->post("/admin/order/done/{$order->id}");

        $response->assertStatus(422);
        $this->assertDatabaseHas('orders', ['id' => $order->id, 'status' => 'pending']);
    }

    public function test_done_order_gagal_jika_status_sudah_done(): void
    {
        $admin = $this->adminUser();
        $order = $this->buatOrder('done');

        $response = $this->actingAs($admin)->post("/admin/order/done/{$order->id}");

        $response->assertStatus(422);
    }

    public function test_done_order_yang_tidak_ada_return_404(): void
    {
        $admin = $this->adminUser();

        $response = $this->actingAs($admin)->post('/admin/order/done/9999');

        $response->assertStatus(404);
    }
}