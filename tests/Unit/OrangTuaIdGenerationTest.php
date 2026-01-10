<?php

use App\Models\OrangTua;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('generates unique id with OT prefix and 3 digit counter', function () {
    $id = OrangTua::generateUniqueId();
    expect($id)->toMatch('/^OT\d{3}$/');
    expect($id)->toBe('OT001');
});

it('increments id correctly when data exists', function () {
    OrangTua::create([
        'id_orang_tua' => 'OT001',
        'nama' => 'Test User 1',
        'email' => 'test1@gmail.com',
        'password' => bcrypt('password'),
        'no_telpon' => '08123456789',
    ]);

    $id = OrangTua::generateUniqueId();
    expect($id)->toBe('OT002');
});

it('generates correct next id even after deletion', function () {
    // Create 5 records
    for ($i = 1; $i <= 5; $i++) {
        OrangTua::create([
            'id_orang_tua' => 'OT'.str_pad($i, 3, '0', STR_PAD_LEFT),
            'nama' => "Test User $i",
            'email' => "test$i@gmail.com",
            'password' => bcrypt('password'),
            'no_telpon' => '08123456789',
        ]);
    }

    // Delete record OT003
    OrangTua::where('id_orang_tua', 'OT003')->delete();

    // New ID should be OT006, not OT003
    $id = OrangTua::generateUniqueId();
    expect($id)->toBe('OT006');
});

it('prevents duplicate key error when creating after deletion', function () {
    OrangTua::create([
        'id_orang_tua' => 'OT001',
        'nama' => 'Test User 1',
        'email' => 'test1@gmail.com',
        'password' => bcrypt('password'),
        'no_telpon' => '08123456789',
    ]);

    OrangTua::create([
        'id_orang_tua' => 'OT002',
        'nama' => 'Test User 2',
        'email' => 'test2@gmail.com',
        'password' => bcrypt('password'),
        'no_telpon' => '08123456789',
    ]);

    // Delete OT002
    OrangTua::where('id_orang_tua', 'OT002')->delete();

    // Create new record - should not throw unique constraint violation
    $newOrangTua = OrangTua::create([
        'id_orang_tua' => OrangTua::generateUniqueId(),
        'nama' => 'Test User 3',
        'email' => 'test3@gmail.com',
        'password' => bcrypt('password'),
        'no_telpon' => '08123456789',
    ]);

    expect($newOrangTua->id_orang_tua)->toBe('OT003');
    expect(OrangTua::count())->toBe(2);
});
