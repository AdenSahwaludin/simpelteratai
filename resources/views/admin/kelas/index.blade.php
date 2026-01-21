@extends('layouts.dashboard')

@section('title', 'Kelola Wali Kelas')
@section('nav-color', 'bg-blue-600')
@section('sidebar-color', 'bg-blue-600')
@section('dashboard-title', 'Kelola Wali Kelas')
@section('user-name', auth('admin')->user()->nama)
@section('user-role', 'Admin')

@section('sidebar-menu')
    <x-sidebar-menu :guard="'admin'" :currentRoute="request()->route()->getName()" />
@endsection

@section('content')
    <div class="max-w-7xl mx-auto">
        <div class="mb-6">
            <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center text-blue-600 hover:text-blue-800">
                <i class="fas fa-arrow-left mr-2"></i>
                <span>Kembali ke Dashboard</span>
            </a>
        </div>

        <div class="bg-white rounded-lg shadow-lg p-6">
            <div class="mb-6">
                <h2 class="text-2xl font-bold text-gray-800 flex items-center">
                    <i class="fas fa-school text-blue-600 mr-3"></i>
                    Kelola Wali Kelas
                </h2>
                <p class="text-sm text-gray-600 mt-1">Kelola penugasan wali kelas untuk setiap kelas</p>
            </div>

            @if (session('success'))
                <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded-lg">
                    <i class="fas fa-check-circle mr-2"></i>{{ session('success') }}
                </div>
            @endif

            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="border-b-2 border-gray-200">
                            <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">ID Kelas</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Wali Kelas</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Jumlah Siswa</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($kelasList as $kelas)
                            <tr class="border-b border-gray-200 hover:bg-gray-50">
                                <td class="px-6 py-4">
                                    <span class="px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-sm font-semibold">
                                        {{ $kelas->id_kelas }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    @if ($kelas->guruWali)
                                        <span class="text-gray-900">{{ $kelas->guruWali->nama }}</span>
                                    @else
                                        <span class="text-gray-400 italic">Belum ditugaskan</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    <span class="text-gray-700 font-medium">{{ $kelas->siswa()->count() }} siswa</span>
                                </td>
                                <td class="px-6 py-4">
                                    <a href="{{ route('admin.kelas.edit', $kelas->id_kelas) }}"
                                        class="inline-flex items-center px-3 py-1.5 bg-blue-100 text-blue-700 hover:bg-blue-200 rounded-lg transition"
                                        title="Edit">
                                        <i class="fas fa-edit mr-1"></i>
                                        <span class="text-xs">Edit</span>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-8 text-center text-gray-500">
                                    <i class="fas fa-inbox text-3xl mb-2"></i>
                                    <p class="mt-2">Belum ada kelas</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
