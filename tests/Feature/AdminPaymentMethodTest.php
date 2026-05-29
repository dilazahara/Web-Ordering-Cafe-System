<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\PaymentMethod;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class AdminPaymentMethodTest extends TestCase
{
    use RefreshDatabase;

    // =========================================
    // HELPER: Login sebagai admin
    // =========================================
    private function actingAsAdmin()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        return $this->actingAs($admin);
    }

    // =========================================
    // INDEX
    // =========================================

    #[Test]
    public function index_menampilkan_halaman_pembayaran_dengan_data()
    {
        PaymentMethod::factory()->count(3)->create();

        $this->actingAsAdmin()
            ->get(route('admin.pembayaran.index'))
            ->assertStatus(200)
            ->assertViewIs('admin.pembayaran')
            ->assertViewHas('paymentMethods');
    }

    #[Test]
    public function index_menampilkan_halaman_kosong_saat_belum_ada_data()
    {
        $this->actingAsAdmin()
            ->get(route('admin.pembayaran.index'))
            ->assertStatus(200)
            ->assertViewHas('paymentMethods', fn ($data) => $data->isEmpty());
    }

    #[Test]
    public function index_redirect_ke_login_jika_belum_autentikasi()
    {
        $this->get(route('admin.pembayaran.index'))
            ->assertRedirect(route('login'));
    }

    // =========================================
    // STORE
    // =========================================

    #[Test]
    public function store_berhasil_menyimpan_metode_pembayaran_baru()
    {
        $this->actingAsAdmin()
            ->post(route('admin.pembayaran.store'), [
                'nama'  => 'Transfer BCA',
                'kode'  => 'bca',
                'aktif' => '1',
            ])
            ->assertRedirect()
            ->assertSessionHas('success');

        $this->assertDatabaseHas('payment_methods', [
            'nama'  => 'Transfer BCA',
            'kode'  => 'bca',
            'aktif' => true,
        ]);
    }

    #[Test]
    public function store_berhasil_dengan_kode_custom()
    {
        $this->actingAsAdmin()
            ->post(route('admin.pembayaran.store'), [
                'nama'        => 'Dana',
                'kode'        => 'lain',
                'kode_custom' => 'dana_ewallet',
                'aktif'       => '1',
            ])
            ->assertRedirect()
            ->assertSessionHas('success');

        $this->assertDatabaseHas('payment_methods', ['kode' => 'dana_ewallet']);
    }

    #[Test]
    public function store_gagal_jika_kode_custom_kosong_ketika_tipe_lain()
    {
        $this->actingAsAdmin()
            ->post(route('admin.pembayaran.store'), [
                'nama'        => 'Dana',
                'kode'        => 'lain',
                'kode_custom' => '',
                'aktif'       => '1',
            ])
            ->assertRedirect()
            ->assertSessionHasErrors(['kode_custom']);
    }

    #[Test]
    public function store_gagal_jika_kode_sudah_digunakan()
    {
        PaymentMethod::factory()->create(['kode' => 'bca']);

        $this->actingAsAdmin()
            ->post(route('admin.pembayaran.store'), [
                'nama'  => 'BCA Lain',
                'kode'  => 'bca',
                'aktif' => '1',
            ])
            ->assertRedirect()
            ->assertSessionHasErrors(['kode']);
    }

    #[Test]
    public function store_gagal_validasi_nama_kosong()
    {
        $this->actingAsAdmin()
            ->post(route('admin.pembayaran.store'), [
                'nama'  => '',
                'kode'  => 'bni',
                'aktif' => '1',
            ])
            ->assertRedirect()
            ->assertSessionHasErrors(['nama']);
    }

    #[Test]
    public function store_gagal_validasi_kode_custom_format_salah()
    {
        $this->actingAsAdmin()
            ->post(route('admin.pembayaran.store'), [
                'nama'        => 'OVO',
                'kode'        => 'lain',
                'kode_custom' => 'OVO Ewallet!!',
                'aktif'       => '1',
            ])
            ->assertRedirect()
            ->assertSessionHasErrors(['kode_custom']);
    }

    #[Test]
    public function store_gagal_validasi_aktif_tidak_valid()
    {
        $this->actingAsAdmin()
            ->post(route('admin.pembayaran.store'), [
                'nama'  => 'Mandiri',
                'kode'  => 'mandiri',
                'aktif' => '99',
            ])
            ->assertRedirect()
            ->assertSessionHasErrors(['aktif']);
    }

    // =========================================
    // UPDATE
    // =========================================

    #[Test]
    public function update_berhasil_mengubah_nama_dan_kode()
    {
        $method = PaymentMethod::factory()->create(['nama' => 'BCA', 'kode' => 'bca']);

        $this->actingAsAdmin()
            ->put(route('admin.pembayaran.update', $method->id), [
                'nama'  => 'BCA Updated',
                'kode'  => 'bca_updated',
                'aktif' => '1',
            ])
            ->assertRedirect()
            ->assertSessionHas('success');

        $this->assertDatabaseHas('payment_methods', [
            'id'   => $method->id,
            'nama' => 'BCA Updated',
            'kode' => 'bca_updated',
        ]);
    }

    #[Test]
    public function update_berhasil_tanpa_mengubah_kode()
    {
        $method = PaymentMethod::factory()->create(['nama' => 'BCA', 'kode' => 'bca']);

        $this->actingAsAdmin()
            ->put(route('admin.pembayaran.update', $method->id), [
                'nama' => 'BCA Renamed',
            ])
            ->assertRedirect()
            ->assertSessionHas('success');

        $this->assertDatabaseHas('payment_methods', [
            'id'   => $method->id,
            'kode' => 'bca',
        ]);
    }

    #[Test]
    public function update_gagal_jika_kode_baru_sudah_digunakan_metode_lain()
    {
        PaymentMethod::factory()->create(['kode' => 'mandiri']);
        $method = PaymentMethod::factory()->create(['kode' => 'bca']);

        $this->actingAsAdmin()
            ->put(route('admin.pembayaran.update', $method->id), [
                'nama' => 'BCA',
                'kode' => 'mandiri',
            ])
            ->assertRedirect()
            ->assertSessionHasErrors(['kode']);
    }

    #[Test]
    public function update_berhasil_jika_kode_tidak_berubah()
    {
        $method = PaymentMethod::factory()->create(['kode' => 'bca']);

        $this->actingAsAdmin()
            ->put(route('admin.pembayaran.update', $method->id), [
                'nama' => 'BCA Renamed',
                'kode' => 'bca',
            ])
            ->assertRedirect()
            ->assertSessionHas('success');
    }

    #[Test]
    public function update_gagal_validasi_nama_kosong()
    {
        $method = PaymentMethod::factory()->create();

        $this->actingAsAdmin()
            ->put(route('admin.pembayaran.update', $method->id), [
                'nama' => '',
            ])
            ->assertRedirect()
            ->assertSessionHasErrors(['nama']);
    }

    #[Test]
    public function update_404_jika_id_tidak_ditemukan()
    {
        $this->actingAsAdmin()
            ->put(route('admin.pembayaran.update', 9999), [
                'nama' => 'Test',
            ])
            ->assertStatus(404);
    }

    // =========================================
    // TOGGLE
    // =========================================

    #[Test]
    public function toggle_mengaktifkan_metode_yang_nonaktif()
    {
        $method = PaymentMethod::factory()->create(['aktif' => false]);

        $this->actingAsAdmin()
            ->post(route('admin.pembayaran.toggle', $method->id))
            ->assertRedirect()
            ->assertSessionHas('success');

        $this->assertDatabaseHas('payment_methods', ['id' => $method->id, 'aktif' => true]);
    }

    #[Test]
    public function toggle_menonaktifkan_metode_yang_aktif()
    {
        $method = PaymentMethod::factory()->create(['aktif' => true]);

        $this->actingAsAdmin()
            ->post(route('admin.pembayaran.toggle', $method->id))
            ->assertRedirect()
            ->assertSessionHas('success');

        $this->assertDatabaseHas('payment_methods', ['id' => $method->id, 'aktif' => false]);
    }

    #[Test]
    public function toggle_404_jika_id_tidak_ditemukan()
    {
        $this->actingAsAdmin()
            ->post(route('admin.pembayaran.toggle', 9999))
            ->assertStatus(404);
    }

    // =========================================
    // UPDATE QRIS
    // =========================================

    #[Test]
    public function update_qris_berhasil_menyimpan_konfigurasi()
    {
        Storage::fake('public');

        $method = PaymentMethod::factory()->create(['kode' => 'qris']);

        $this->actingAsAdmin()
            ->post(route('admin.pembayaran.qris', $method->id), [
                'nama_merchant'  => 'Toko Saya',
                'nomor_merchant' => '1234567890',
                'image'          => UploadedFile::fake()->image('qris.png', 500, 500),
            ])
            ->assertRedirect()
            ->assertSessionHas('success');

        $method->refresh();

        $this->assertEquals('Toko Saya', $method->nama_rekening);
        $this->assertEquals('1234567890', $method->no_rekening);
        $this->assertNotNull($method->qris_image);
        Storage::disk('public')->assertExists($method->qris_image);
    }

    #[Test]
    public function update_qris_menghapus_gambar_lama_saat_upload_baru()
    {
        Storage::fake('public');

        $oldImage = 'qris/old_qris.png';
        Storage::disk('public')->put($oldImage, 'dummy');

        $method = PaymentMethod::factory()->create([
            'kode'       => 'qris',
            'qris_image' => $oldImage,
        ]);

        $this->actingAsAdmin()
            ->post(route('admin.pembayaran.qris', $method->id), [
                'nama_merchant' => 'Toko Baru',
                'image'         => UploadedFile::fake()->image('new_qris.png'),
            ])
            ->assertRedirect()
            ->assertSessionHas('success');

        Storage::disk('public')->assertMissing($oldImage);
    }

    #[Test]
    public function update_qris_berhasil_tanpa_upload_gambar()
    {
        $method = PaymentMethod::factory()->create(['kode' => 'qris']);

        $this->actingAsAdmin()
            ->post(route('admin.pembayaran.qris', $method->id), [
                'nama_merchant'  => 'Merchant Tanpa Gambar',
                'nomor_merchant' => '0987654321',
            ])
            ->assertRedirect()
            ->assertSessionHas('success');

        $this->assertDatabaseHas('payment_methods', [
            'id'            => $method->id,
            'nama_rekening' => 'Merchant Tanpa Gambar',
            'no_rekening'   => '0987654321',
        ]);
    }

    #[Test]
    public function update_qris_gagal_validasi_file_bukan_gambar()
    {
        Storage::fake('public');

        $method = PaymentMethod::factory()->create(['kode' => 'qris']);

        $this->actingAsAdmin()
            ->post(route('admin.pembayaran.qris', $method->id), [
                'image' => UploadedFile::fake()->create('file.pdf', 100, 'application/pdf'),
            ])
            ->assertRedirect()
            ->assertSessionHasErrors(['image']);
    }

    #[Test]
    public function update_qris_gagal_validasi_gambar_terlalu_besar()
    {
        Storage::fake('public');

        $method = PaymentMethod::factory()->create(['kode' => 'qris']);

        $this->actingAsAdmin()
            ->post(route('admin.pembayaran.qris', $method->id), [
                'image' => UploadedFile::fake()->image('besar.png')->size(3000),
            ])
            ->assertRedirect()
            ->assertSessionHasErrors(['image']);
    }

    // =========================================
    // DESTROY
    // =========================================

    #[Test]
    public function destroy_berhasil_menghapus_metode_tanpa_order_aktif()
    {
        $method = PaymentMethod::factory()->create(['kode' => 'gopay']);

        $this->actingAsAdmin()
            ->delete(route('admin.pembayaran.destroy', $method->id))
            ->assertRedirect()
            ->assertSessionHas('success');

        $this->assertDatabaseMissing('payment_methods', ['id' => $method->id]);
    }

    #[Test]
    public function destroy_menghapus_qris_image_dari_storage()
    {
        Storage::fake('public');

        $imagePath = 'qris/test.png';
        Storage::disk('public')->put($imagePath, 'dummy');

        $method = PaymentMethod::factory()->create([
            'kode'       => 'qris',
            'qris_image' => $imagePath,
        ]);

        $this->actingAsAdmin()
            ->delete(route('admin.pembayaran.destroy', $method->id))
            ->assertRedirect()
            ->assertSessionHas('success');

        Storage::disk('public')->assertMissing($imagePath);
    }

    #[Test]
    public function destroy_gagal_jika_masih_ada_order_aktif()
    {
        $method = PaymentMethod::factory()->create(['kode' => 'bca']);

        Order::factory()->create([
            'payment_method' => 'bca',
            'status'         => 'pending',
        ]);

        $this->actingAsAdmin()
            ->delete(route('admin.pembayaran.destroy', $method->id))
            ->assertRedirect()
            ->assertSessionHas('error');

        $this->assertDatabaseHas('payment_methods', ['id' => $method->id]);
    }

    #[Test]
    public function destroy_gagal_jika_ada_order_dengan_status_process()
    {
        $method = PaymentMethod::factory()->create(['kode' => 'mandiri']);

        Order::factory()->create([
            'payment_method' => 'mandiri',
            'status'         => 'process',
        ]);

        $this->actingAsAdmin()
            ->delete(route('admin.pembayaran.destroy', $method->id))
            ->assertRedirect()
            ->assertSessionHas('error');
    }

    #[Test]
    public function destroy_berhasil_jika_order_ada_tapi_berstatus_cancelled()
    {
        $method = PaymentMethod::factory()->create(['kode' => 'bri']);

        Order::factory()->create([
            'payment_method' => 'bri',
            'status'         => 'cancelled',
        ]);

        $this->actingAsAdmin()
            ->delete(route('admin.pembayaran.destroy', $method->id))
            ->assertRedirect()
            ->assertSessionHas('success');

        $this->assertDatabaseMissing('payment_methods', ['id' => $method->id]);
    }

    #[Test]
    public function destroy_404_jika_id_tidak_ditemukan()
    {
        $this->actingAsAdmin()
            ->delete(route('admin.pembayaran.destroy', 9999))
            ->assertStatus(404);
    }
}