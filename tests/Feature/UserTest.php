<?php

namespace Tests\Feature\Admin;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    // ──────────────────────────────────────────────────────────────
    // Helper: buat admin dan login
    // ──────────────────────────────────────────────────────────────

    private function actingAsAdmin(): User
    {
        $admin = User::factory()->create([
            'email' => 'admin@gmail.com',
            'role'  => 'admin',
        ]);

        $this->actingAs($admin);

        return $admin;
    }

    // ══════════════════════════════════════════════════════════════
    // INDEX
    // ══════════════════════════════════════════════════════════════

    #[Test]
    public function index_menampilkan_daftar_user(): void
    {
        $this->actingAsAdmin();

        User::factory()->count(3)->create();

        $response = $this->get('/admin/user');

        $response->assertStatus(200);
        $response->assertViewIs('admin.user.index');
        $response->assertViewHas('users');
    }

    #[Test]
    public function index_redirect_jika_belum_login(): void
    {
        $response = $this->get('/admin/user');

        $response->assertRedirect('/login');
    }

    // ══════════════════════════════════════════════════════════════
    // CREATE
    // ══════════════════════════════════════════════════════════════

    #[Test]
    public function create_menampilkan_form_tambah_user(): void
    {
        $this->actingAsAdmin();

        $response = $this->get('/admin/user/create');

        $response->assertStatus(200);
        $response->assertViewIs('admin.user.create');
    }

    // ══════════════════════════════════════════════════════════════
    // STORE
    // ══════════════════════════════════════════════════════════════

    #[Test]
    public function store_berhasil_menyimpan_user_baru(): void
    {
        $this->actingAsAdmin();

        $payload = [
            'name'     => 'Budi Santoso',
            'email'    => 'budi@example.com',
            'password' => 'secret123',
            'role'     => 'kasir',
        ];

        $response = $this->post('/admin/user/store', $payload);

        $response->assertRedirect('/admin/user');
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('users', [
            'name'  => 'Budi Santoso',
            'email' => 'budi@example.com',
            'role'  => 'kasir',
        ]);
    }

    #[Test]
    public function store_password_tersimpan_dalam_bentuk_hash(): void
    {
        $this->actingAsAdmin();

        $this->post('/admin/user/store', [
            'name'     => 'Citra',
            'email'    => 'citra@example.com',
            'password' => 'rahasiabanget',
            'role'     => 'pelayan',
        ]);

        $user = User::where('email', 'citra@example.com')->firstOrFail();

        $this->assertNotEquals('rahasiabanget', $user->password);
        $this->assertTrue(Hash::check('rahasiabanget', $user->password));
    }

    // ══════════════════════════════════════════════════════════════
    // EDIT
    // ══════════════════════════════════════════════════════════════

    #[Test]
    public function edit_menampilkan_form_edit_user(): void
    {
        $this->actingAsAdmin();

        $user = User::factory()->create(['role' => 'kasir']);

        $response = $this->get("/admin/user/edit/{$user->id}");

        $response->assertStatus(200);
        $response->assertViewIs('admin.user.edit');
        $response->assertViewHas('item', fn ($item) => $item->id === $user->id);
    }

    #[Test]
    public function edit_mengembalikan_404_jika_user_tidak_ditemukan(): void
    {
        $this->actingAsAdmin();

        $response = $this->get('/admin/user/edit/99999');

        $response->assertStatus(404);
    }

    // ══════════════════════════════════════════════════════════════
    // UPDATE
    // ══════════════════════════════════════════════════════════════

    #[Test]
    public function update_berhasil_mengubah_role_user(): void
    {
        $this->actingAsAdmin();

        $user = User::factory()->create([
            'email' => 'kasir1@example.com',
            'role'  => 'kasir',
        ]);

        $response = $this->post("/admin/user/update/{$user->id}", [
            'role' => 'pelayan',
        ]);

        $response->assertRedirect('/admin/user');
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('users', [
            'id'   => $user->id,
            'role' => 'pelayan',
        ]);
    }

    #[Test]
    public function update_gagal_jika_role_kosong(): void
    {
        $this->actingAsAdmin();

        $user = User::factory()->create(['role' => 'kasir']);

        $response = $this->post("/admin/user/update/{$user->id}", [
            'role' => '',
        ]);

        $response->assertSessionHasErrors('role');
    }

    #[Test]
    public function update_menolak_perubahan_role_admin_utama(): void
    {
        $this->actingAsAdmin();

        $admin = User::where('email', 'admin@gmail.com')->firstOrFail();

        $response = $this->post("/admin/user/update/{$admin->id}", [
            'role' => 'kasir',
        ]);

        $response->assertSessionHasErrors();

        $this->assertDatabaseHas('users', [
            'email' => 'admin@gmail.com',
            'role'  => 'admin',
        ]);
    }

    #[Test]
    public function update_mengembalikan_404_jika_user_tidak_ditemukan(): void
    {
        $this->actingAsAdmin();

        $response = $this->post('/admin/user/update/99999', [
            'role' => 'kasir',
        ]);

        $response->assertStatus(404);
    }

    // ══════════════════════════════════════════════════════════════
    // DELETE
    // ══════════════════════════════════════════════════════════════

    #[Test]
    public function delete_berhasil_menghapus_user(): void
    {
        $this->actingAsAdmin();

        $user = User::factory()->create(['role' => 'dapur']);

        $response = $this->delete("/admin/user/delete/{$user->id}");

        $response->assertRedirect('/admin/user');
        $response->assertSessionHas('success');

        $this->assertDatabaseMissing('users', ['id' => $user->id]);
    }

    #[Test]
    public function delete_mengembalikan_404_jika_user_tidak_ditemukan(): void
    {
        $this->actingAsAdmin();

        $response = $this->delete('/admin/user/delete/99999');

        $response->assertStatus(404);
    }

    // ══════════════════════════════════════════════════════════════
    // ACCESS CONTROL
    // ══════════════════════════════════════════════════════════════

    #[Test]
    public function non_admin_tidak_bisa_akses_halaman_user(): void
    {
        $kasir = User::factory()->create(['role' => 'kasir']);
        $this->actingAs($kasir);

        $response = $this->get('/admin/user');

        $response->assertStatus(403);
    }
}