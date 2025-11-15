<?php

use App\Models\Admin;
use App\Models\Guru;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    // Create an admin user for testing
    $this->admin = Admin::create([
        'id_admin' => 'A01',
        'nama' => 'Test Admin',
        'email' => 'admin@test.com',
        'password' => bcrypt('password'),
        'no_telpon' => '08123456789',
    ]);
});

test('admin can view guru index page', function () {
    $response = $this->actingAs($this->admin, 'admin')->get(route('admin.guru.index'));

    $response->assertStatus(200);
    $response->assertViewIs('admin.guru.index');
});

test('admin can view create guru page', function () {
    $response = $this->actingAs($this->admin, 'admin')->get(route('admin.guru.create'));

    $response->assertStatus(200);
    $response->assertViewIs('admin.guru.create');
});

test('admin can create a new guru', function () {
    $guruData = [
        'id_guru' => 'G01',
        'nama' => 'Test Guru',
        'email' => 'guru@test.com',
        'no_telpon' => '08123456789',
        'password' => 'password123',
        'password_confirmation' => 'password123',
    ];

    $response = $this->actingAs($this->admin, 'admin')->post(route('admin.guru.store'), $guruData);

    $response->assertRedirect(route('admin.guru.index'));
    $response->assertSessionHas('success', 'Data guru berhasil ditambahkan.');

    $this->assertDatabaseHas('guru', [
        'id_guru' => 'G01',
        'nama' => 'Test Guru',
        'email' => 'guru@test.com',
    ]);
});

test('admin can view guru details', function () {
    $guru = Guru::create([
        'id_guru' => 'G01',
        'nama' => 'Test Guru',
        'email' => 'guru@test.com',
        'password' => bcrypt('password'),
        'no_telpon' => '08123456789',
    ]);

    $response = $this->actingAs($this->admin, 'admin')->get(route('admin.guru.show', $guru->id_guru));

    $response->assertStatus(200);
    $response->assertViewIs('admin.guru.show');
    $response->assertViewHas('guru', $guru);
});

test('admin can view edit guru page', function () {
    $guru = Guru::create([
        'id_guru' => 'G01',
        'nama' => 'Test Guru',
        'email' => 'guru@test.com',
        'password' => bcrypt('password'),
        'no_telpon' => '08123456789',
    ]);

    $response = $this->actingAs($this->admin, 'admin')->get(route('admin.guru.edit', $guru->id_guru));

    $response->assertStatus(200);
    $response->assertViewIs('admin.guru.edit');
    $response->assertViewHas('guru', $guru);
});

test('admin can update guru', function () {
    $guru = Guru::create([
        'id_guru' => 'G01',
        'nama' => 'Test Guru',
        'email' => 'guru@test.com',
        'password' => bcrypt('password'),
        'no_telpon' => '08123456789',
    ]);

    $updateData = [
        'nama' => 'Updated Guru Name',
        'email' => 'updated@test.com',
        'no_telpon' => '08987654321',
    ];

    $response = $this->actingAs($this->admin, 'admin')
        ->put(route('admin.guru.update', $guru->id_guru), $updateData);

    $response->assertRedirect(route('admin.guru.index'));
    $response->assertSessionHas('success', 'Data guru berhasil diperbarui.');

    $this->assertDatabaseHas('guru', [
        'id_guru' => 'G01',
        'nama' => 'Updated Guru Name',
        'email' => 'updated@test.com',
        'no_telpon' => '08987654321',
    ]);
});

test('admin can delete guru', function () {
    $guru = Guru::create([
        'id_guru' => 'G01',
        'nama' => 'Test Guru',
        'email' => 'guru@test.com',
        'password' => bcrypt('password'),
        'no_telpon' => '08123456789',
    ]);

    $response = $this->actingAs($this->admin, 'admin')
        ->delete(route('admin.guru.destroy', $guru->id_guru));

    $response->assertRedirect(route('admin.guru.index'));
    $response->assertSessionHas('success', 'Data guru berhasil dihapus.');

    $this->assertDatabaseMissing('guru', [
        'id_guru' => 'G01',
    ]);
});

test('guest cannot access guru pages', function () {
    $response = $this->get(route('admin.guru.index'));
    $response->assertRedirect(route('login'));
});

test('guru validation requires all fields', function () {
    $response = $this->actingAs($this->admin, 'admin')->post(route('admin.guru.store'), []);

    $response->assertSessionHasErrors(['id_guru', 'nama', 'email', 'no_telpon', 'password']);
});

test('guru email must be unique', function () {
    Guru::create([
        'id_guru' => 'G01',
        'nama' => 'Existing Guru',
        'email' => 'existing@test.com',
        'password' => bcrypt('password'),
        'no_telpon' => '08123456789',
    ]);

    $response = $this->actingAs($this->admin, 'admin')->post(route('admin.guru.store'), [
        'id_guru' => 'G02',
        'nama' => 'New Guru',
        'email' => 'existing@test.com',
        'no_telpon' => '08123456789',
        'password' => 'password123',
        'password_confirmation' => 'password123',
    ]);

    $response->assertSessionHasErrors(['email']);
});
