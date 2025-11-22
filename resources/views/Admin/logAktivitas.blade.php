@extends('layouts.admin.app')

@section('content')
<div class="content-wrapper p-4 sm:p-6">

    <div class="flex items-center justify-between mb-4 sm:mb-6">
        <div class="flex items-center space-x-4">
            <h1 class="text-2xl font-bold text-blue-700">📊 Manajemen Log Aktivitas</h1>
        </div>
    </div>

    {{-- Success/Error Messages --}}
    @if (session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mb-4">
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg mb-4">
            {{ session('error') }}
        </div>
    @endif

    {{-- Statistik --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6 mb-4 sm:mb-6">
        <div class="bg-white rounded-2xl shadow p-4 sm:p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm">Total Log</p>
                    <p class="text-3xl font-bold text-blue-600">{{ number_format($statistik['total']) }}</p>
                </div>
                <div class="bg-blue-100 p-3 rounded-full">
                    <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow p-4 sm:p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm">Hari Ini</p>
                    <p class="text-3xl font-bold text-green-600">{{ number_format($statistik['hari_ini']) }}</p>
                </div>
                <div class="bg-green-100 p-3 rounded-full">
                    <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow p-4 sm:p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm">Minggu Ini</p>
                    <p class="text-3xl font-bold text-yellow-600">{{ number_format($statistik['minggu_ini']) }}</p>
                </div>
                <div class="bg-yellow-100 p-3 rounded-full">
                    <svg class="w-8 h-8 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow p-4 sm:p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm">Bulan Ini</p>
                    <p class="text-3xl font-bold text-purple-600">{{ number_format($statistik['bulan_ini']) }}</p>
                </div>
                <div class="bg-purple-100 p-3 rounded-full">
                    <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 8v8m-4-5v5m-4-2v2m-2 4h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    {{-- Filter & Action Buttons --}}
    <div class="bg-white rounded-2xl shadow p-4 sm:p-6 mb-4 sm:mb-6">
        <form method="GET" action="{{ route('admin.log-aktivitas.index') }}" class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">
                {{-- Filter Jenis Aktivitas --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Jenis Aktivitas</label>
                    <select name="jenis" class="w-full border border-gray-300 rounded-lg p-2 focus:ring focus:ring-blue-200">
                        <option value="">Semua</option>
                        <option value="raport" {{ request('jenis') == 'raport' ? 'selected' : '' }}>Raport</option>
                        <option value="pembayaran_spp" {{ request('jenis') == 'pembayaran_spp' ? 'selected' : '' }}>Pembayaran SPP</option>
                        <option value="absensi" {{ request('jenis') == 'absensi' ? 'selected' : '' }}>Absensi</option>
                        <option value="tugas" {{ request('jenis') == 'tugas' ? 'selected' : '' }}>Tugas</option>
                    </select>
                </div>

                {{-- Filter Role --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Role</label>
                    <select name="role" class="w-full border border-gray-300 rounded-lg p-2 focus:ring focus:ring-blue-200">
                        <option value="">Semua</option>
                        <option value="guru" {{ request('role') == 'guru' ? 'selected' : '' }}>Guru</option>
                        <option value="siswa" {{ request('role') == 'siswa' ? 'selected' : '' }}>Siswa</option>
                        <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                        <option value="sistem" {{ request('role') == 'sistem' ? 'selected' : '' }}>Sistem</option>
                    </select>
                </div>

                {{-- Filter Tanggal Dari --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Dari Tanggal</label>
                    <input type="date" name="dari" value="{{ request('dari') }}" class="w-full border border-gray-300 rounded-lg p-2 focus:ring focus:ring-blue-200">
                </div>

                {{-- Filter Tanggal Sampai --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Sampai Tanggal</label>
                    <input type="date" name="sampai" value="{{ request('sampai') }}" class="w-full border border-gray-300 rounded-lg p-2 focus:ring focus:ring-blue-200">
                </div>

                {{-- Search --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Cari</label>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari deskripsi..." class="w-full border border-gray-300 rounded-lg p-2 focus:ring focus:ring-blue-200">
                </div>
            </div>

            <div class="flex items-center justify-between">
                <div class="flex gap-2">
                    <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg shadow transition">
                        <div class="flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path>
                            </svg>
                            <span>Filter</span>
                        </div>
                    </button>

                    <a href="{{ route('admin.log-aktivitas.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg shadow transition">
                        Reset
                    </a>
                </div>

                <div class="flex gap-2">
                    <a href="{{ route('admin.log-aktivitas.export', request()->all()) }}" class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg shadow transition">
                        <div class="flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            <span>Export CSV</span>
                        </div>
                    </a>

                    <button type="button" onclick="openCleanupModal()" class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg shadow transition">
                        <div class="flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                            </svg>
                            <span>Cleanup</span>
                        </div>
                    </button>
                </div>
            </div>
        </form>
    </div>

    {{-- Tabel Log --}}
    <div class="bg-white rounded-2xl shadow overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-blue-600 text-white">
                    <tr>
                        <th class="px-4 py-3 text-left text-sm font-semibold">Waktu</th>
                        <th class="px-4 py-3 text-left text-sm font-semibold">Jenis</th>
                        <th class="px-4 py-3 text-left text-sm font-semibold">Deskripsi</th>
                        <th class="px-4 py-3 text-left text-sm font-semibold">User</th>
                        <th class="px-4 py-3 text-left text-sm font-semibold">Role</th>
                        <th class="px-4 py-3 text-left text-sm font-semibold">Aksi</th>
                        <th class="px-4 py-3 text-left text-sm font-semibold">IP Address</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($logs as $log)
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-3 text-sm text-gray-600">
                                {{ $log->waktu->format('d/m/Y H:i:s') }}
                            </td>
                            <td class="px-4 py-3 text-sm">
                                <span class="px-2 py-1 rounded-full text-xs font-semibold
                                    @if($log->jenis_aktivitas == 'raport') bg-blue-100 text-blue-800
                                    @elseif($log->jenis_aktivitas == 'pembayaran_spp') bg-green-100 text-green-800
                                    @elseif($log->jenis_aktivitas == 'absensi') bg-yellow-100 text-yellow-800
                                    @elseif($log->jenis_aktivitas == 'tugas') bg-purple-100 text-purple-800
                                    @else bg-gray-100 text-gray-800
                                    @endif">
                                    {{ ucfirst(str_replace('_', ' ', $log->jenis_aktivitas)) }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-sm text-gray-700">
                                {{ $log->deskripsi }}
                            </td>
                            <td class="px-4 py-3 text-sm text-gray-600">
                                {{ $log->user ? $log->user->name : '-' }}
                            </td>
                            <td class="px-4 py-3 text-sm">
                                <span class="px-2 py-1 rounded-full text-xs font-semibold
                                    @if($log->role == 'guru') bg-blue-100 text-blue-800
                                    @elseif($log->role == 'siswa') bg-green-100 text-green-800
                                    @elseif($log->role == 'admin') bg-red-100 text-red-800
                                    @else bg-gray-100 text-gray-800
                                    @endif">
                                    {{ ucfirst($log->role) }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-sm">
                                <span class="px-2 py-1 rounded-full text-xs font-semibold
                                    @if($log->aksi == 'insert') bg-green-100 text-green-800
                                    @elseif($log->aksi == 'update') bg-yellow-100 text-yellow-800
                                    @elseif($log->aksi == 'delete') bg-red-100 text-red-800
                                    @else bg-gray-100 text-gray-800
                                    @endif">
                                    {{ ucfirst($log->aksi) }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-sm text-gray-600">
                                {{ $log->ip_address ?? '-' }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-4 py-8 text-center text-gray-500">
                                Tidak ada log aktivitas ditemukan
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        <div class="px-6 py-4 bg-gray-50">
            {{ $logs->links() }}
        </div>
    </div>

</div>

{{-- Modal Cleanup --}}
<div id="cleanup-modal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
    <div class="bg-white rounded-2xl shadow-xl p-4 sm:p-6 w-full max-w-md">
        <h3 class="text-xl font-bold text-gray-800 mb-4">Cleanup Log Aktivitas</h3>
        <p class="text-gray-600 mb-4 sm:mb-6">Hapus semua log yang lebih lama dari berapa hari?</p>
        
        <form method="POST" action="{{ route('admin.log-aktivitas.cleanup') }}">
            @csrf
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">Jumlah Hari</label>
                <input type="number" name="hari" value="30" min="1" max="365" required class="w-full border border-gray-300 rounded-lg p-3 focus:ring focus:ring-blue-200">
                <p class="text-xs text-gray-500 mt-1">Log yang lebih tua dari tanggal ini akan dihapus</p>
            </div>

            <div class="flex justify-end gap-3">
                <button type="button" onclick="closeCleanupModal()" class="px-4 py-2 bg-gray-300 hover:bg-gray-400 text-gray-800 rounded-lg transition">
                    Batal
                </button>
                <button type="submit" class="px-4 py-2 bg-red-500 hover:bg-red-600 text-white rounded-lg transition">
                    Hapus Log
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function openCleanupModal() {
        document.getElementById('cleanup-modal').classList.remove('hidden');
    }

    function closeCleanupModal() {
        document.getElementById('cleanup-modal').classList.add('hidden');
    }
    
    // Close modal when clicking outside
    document.getElementById('cleanup-modal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeCleanupModal();
        }
    });
</script>

@endsection



