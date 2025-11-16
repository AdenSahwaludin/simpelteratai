@extends('layouts.dashboard')

@section('title', 'Catatan Perilaku')
@section('dashboard-title', 'Catatan Perilaku')

@section('sidebar-menu')
    <x-sidebar-menu guard="orangtua" :currentRoute="request()->route()->getName()" />
@endsection

@section('content')
    <div class="container mx-auto px-4 py-6">
        <!-- Header -->
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-gray-800">Catatan Perilaku Anak</h1>
            <p class="text-gray-600 text-sm mt-1">Lihat catatan perilaku anak dari guru</p>
        </div>

        <!-- Filter -->
        <div class="bg-white rounded-lg shadow-md p-4 mb-6">
            <form action="{{ route('orangtua.perilaku.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Pilih Anak</label>
                    <select name="anak_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500">
                        <option value="">Semua Anak</option>
                        @foreach ($anakList as $child)
                            <option value="{{ $child->id_siswa }}" {{ $anakId == $child->id_siswa ? 'selected' : '' }}>
                                {{ $child->nama }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Cari</label>
                    <input type="text" name="search" value="{{ $search }}" placeholder="Cari catatan..."
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500">
                </div>
                <div class="flex items-end gap-2">
                    <button type="submit" class="flex-1 bg-purple-500 hover:bg-purple-600 text-white px-6 py-2 rounded-lg transition">
                        <i class="fas fa-search"></i> Filter
                    </button>
                    <a href="{{ route('orangtua.perilaku.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-2 rounded-lg transition">
                        <i class="fas fa-redo"></i>
                    </a>
                </div>
            </form>
        </div>

        <!-- Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            @forelse ($perilaku as $item)
                <div class="bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition">
                    <div class="flex items-start gap-4 mb-4">
                        <div class="bg-yellow-100 p-3 rounded-full shrink-0">
                            <i class="fas fa-star text-yellow-600 text-2xl"></i>
                        </div>
                        <div class="flex-1">
                            <h3 class="text-lg font-bold text-gray-800">{{ $item->siswa->nama }}</h3>
                            <p class="text-sm text-gray-600">{{ $item->siswa->kelas }}</p>
                            <p class="text-xs text-gray-500 mt-1">
                                <i class="fas fa-clock"></i> {{ $item->created_at->format('d M Y H:i') }}
                            </p>
                        </div>
                    </div>
                    <div class="bg-gray-50 rounded-lg p-4 mb-4">
                        <p class="text-sm text-gray-700">{{ $item->catatan_perilaku }}</p>
                    </div>
                    <a href="{{ route('orangtua.perilaku.show', $item->id_perilaku) }}"
                        class="w-full bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded-lg transition text-center flex items-center justify-center gap-2">
                        <i class="fas fa-eye"></i> Lihat Detail
                    </a>
                </div>
            @empty
                <div class="col-span-2 bg-white rounded-lg shadow-md p-12 text-center text-gray-500">
                    <i class="fas fa-inbox text-6xl mb-4 text-gray-300"></i>
                    <p class="text-lg">Belum ada catatan perilaku</p>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if ($perilaku->hasPages())
            <div class="mt-6">
                {{ $perilaku->links() }}
            </div>
        @endif
    </div>
@endsection
