<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rekap Pembayaran SPP - {{ $siswa->nama_lengkap }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @media print {
            body {
                print-color-adjust: exact;
                -webkit-print-color-adjust: exact;
            }
            .no-print {
                display: none !important;
            }
            @page {
                margin: 1cm;
            }
        }
    </style>
</head>
<body class="bg-white p-8">
    
    <!-- Header Sekolah -->
    <div class="text-center border-b-4 border-blue-600 pb-4 mb-6">
        <div class="flex items-center justify-center mb-2">
            <img src="{{ asset('images/logo-sekolah.png') }}" alt="Logo" class="h-16 mr-4" onerror="this.style.display='none'">
            <div>
                <h1 class="text-2xl font-bold text-blue-800">SMA NEGERI 12 MEDAN</h1>
                <p class="text-sm text-slate-600">Jl. Pendidikan No. 12, Medan</p>
                <p class="text-sm text-slate-600">Telp: (061) 1234567 | Email: sman12medan@gmail.com</p>
            </div>
        </div>
    </div>

    <!-- Judul Laporan -->
    <div class="text-center mb-6">
        <h2 class="text-xl font-bold text-slate-800">REKAP PEMBAYARAN SPP</h2>
        <p class="text-sm text-slate-600">Tahun Ajaran: {{ $tahunAjaran->tahun_mulai }}/{{ $tahunAjaran->tahun_selesai }} - {{ $tahunAjaran->semester }}</p>
        <p class="text-xs text-slate-500 mt-1">Dicetak pada: {{ \Carbon\Carbon::now()->translatedFormat('d F Y, H:i') }} WIB</p>
    </div>

    <!-- Data Siswa -->
    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
        <div class="grid grid-cols-2 gap-4">
            <div>
                <p class="text-xs text-slate-600 mb-1">NISN</p>
                <p class="text-sm font-bold text-slate-800">{{ $siswa->nisn }}</p>
            </div>
            <div>
                <p class="text-xs text-slate-600 mb-1">NIS</p>
                <p class="text-sm font-bold text-slate-800">{{ $siswa->nis }}</p>
            </div>
            <div>
                <p class="text-xs text-slate-600 mb-1">Nama Lengkap</p>
                <p class="text-sm font-bold text-slate-800">{{ $siswa->nama_lengkap }}</p>
            </div>
            <div>
                <p class="text-xs text-slate-600 mb-1">Kelas</p>
                <p class="text-sm font-bold text-slate-800">{{ $siswa->kelasHistory->first()->nama_kelas ?? '-' }}</p>
            </div>
        </div>
    </div>

    <!-- Ringkasan Pembayaran -->
    <div class="grid grid-cols-4 gap-3 mb-6">
        <div class="bg-green-50 border-2 border-green-300 rounded-lg p-3 text-center">
            <p class="text-xs text-green-700 mb-1">Bulan Lunas</p>
            <p class="text-2xl font-bold text-green-600">{{ $totalLunas }}</p>
        </div>
        <div class="bg-red-50 border-2 border-red-300 rounded-lg p-3 text-center">
            <p class="text-xs text-red-700 mb-1">Bulan Belum Lunas</p>
            <p class="text-2xl font-bold text-red-600">{{ $totalBelumLunas }}</p>
        </div>
        <div class="bg-blue-50 border-2 border-blue-300 rounded-lg p-3 text-center">
            <p class="text-xs text-blue-700 mb-1">Total Terbayar</p>
            <p class="text-lg font-bold text-blue-600">Rp {{ number_format($totalBayar, 0, ',', '.') }}</p>
        </div>
        <div class="bg-purple-50 border-2 border-purple-300 rounded-lg p-3 text-center">
            <p class="text-xs text-purple-700 mb-1">Total Tagihan</p>
            <p class="text-lg font-bold text-purple-600">Rp {{ number_format($totalTagihan, 0, ',', '.') }}</p>
        </div>
    </div>

    <!-- Tabel Detail Pembayaran Per Bulan -->
    <div class="border border-slate-300 rounded-lg overflow-hidden">
        <table class="w-full">
            <thead>
                <tr class="bg-blue-600 text-white">
                    <th class="px-4 py-3 text-left text-xs font-semibold">NO</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold">PERIODE</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold">NAMA TAGIHAN</th>
                    <th class="px-4 py-3 text-right text-xs font-semibold">NOMINAL</th>
                    <th class="px-4 py-3 text-center text-xs font-semibold">STATUS</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold">TANGGAL BAYAR</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $bulanText = ['', 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
                @endphp
                
                @forelse($pembayaranList as $index => $pembayaran)
                    <tr class="border-b border-slate-200 {{ $pembayaran->status === 'Lunas' ? 'bg-green-50' : 'bg-red-50' }}">
                        <td class="px-4 py-3 text-sm">{{ $index + 1 }}</td>
                        <td class="px-4 py-3 text-sm font-medium">
                            {{ $bulanText[$pembayaran->bulan] }} {{ $pembayaran->tahun }}
                        </td>
                        <td class="px-4 py-3 text-sm">{{ $pembayaran->nama_tagihan }}</td>
                        <td class="px-4 py-3 text-sm text-right font-semibold">
                            Rp {{ number_format($pembayaran->jumlah_bayar, 0, ',', '.') }}
                        </td>
                        <td class="px-4 py-3 text-center">
                            @if($pembayaran->status === 'Lunas')
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-bold bg-green-200 text-green-800">
                                    ✓ LUNAS
                                </span>
                            @else
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-bold bg-red-200 text-red-800">
                                    ✗ BELUM LUNAS
                                </span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-sm">
                            {{ $pembayaran->tanggal_bayar ? \Carbon\Carbon::parse($pembayaran->tanggal_bayar)->translatedFormat('d M Y') : '-' }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-4 py-8 text-center text-slate-500">
                            Tidak ada data pembayaran
                        </td>
                    </tr>
                @endforelse
            </tbody>
            <tfoot>
                <tr class="bg-slate-100 font-bold">
                    <td colspan="3" class="px-4 py-3 text-sm text-right">TOTAL:</td>
                    <td class="px-4 py-3 text-sm text-right text-blue-600">
                        Rp {{ number_format($totalTagihan, 0, ',', '.') }}
                    </td>
                    <td colspan="2" class="px-4 py-3 text-sm">
                        <span class="text-green-600">Terbayar: Rp {{ number_format($totalBayar, 0, ',', '.') }}</span>
                    </td>
                </tr>
            </tfoot>
        </table>
    </div>

    <!-- Catatan -->
    @if($totalBelumLunas > 0)
    <div class="mt-4 p-3 bg-yellow-50 border-l-4 border-yellow-400 rounded">
        <p class="text-xs text-yellow-800">
            <strong>Catatan:</strong> Terdapat {{ $totalBelumLunas }} bulan pembayaran yang belum lunas. 
            Mohon segera melakukan pembayaran untuk menghindari tunggakan.
        </p>
    </div>
    @endif

    <!-- Footer -->
    <div class="mt-8 text-right">
        <div class="inline-block text-center">
            <p class="text-sm mb-16">Medan, {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}</p>
            <p class="text-sm font-bold border-t border-slate-800 pt-1">Kepala Sekolah</p>
        </div>
    </div>

    <!-- Tombol Print (disembunyikan saat print) -->
    <div class="no-print fixed bottom-6 right-6 flex gap-3">
        <button onclick="window.close()" class="bg-slate-500 hover:bg-slate-600 text-white px-6 py-3 rounded-lg shadow-lg transition">
            Tutup
        </button>
        <button onclick="window.print()" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg shadow-lg transition">
            🖨️ Cetak
        </button>
    </div>

    <script>
        // Auto print on load (optional)
        // window.onload = function() { window.print(); }
    </script>

</body>
</html>
