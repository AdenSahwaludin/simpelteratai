<?php

use App\Models\OrangTua;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('generates first unique id correctly', function () {
    $id = OrangTua::generateUniqueId();
    expect($id)->toMatch('/^OT\d{3}$/');
    expect($id)->toBe('OT001');
});

it('increments id correctly after saving record', function () {
    // Create first record without using generateUniqueId
    OrangTua::forceCreate([
        'id_orang_tua' => 'OT001',
        'nama' => 'Test User 1',
        'email' => 'test1@gmail.com',
        'password' => bcrypt('password'),
        'no_telpon' => '08123456789',
    ]);

    $id = OrangTua::generateUniqueId();
    expect($id)->toBe('OT002');
});
