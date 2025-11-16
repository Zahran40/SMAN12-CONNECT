@extends('layouts.guru.app')

@section('title', 'Presensi')

@section('content')

  <h2 class="text-3xl font-bold text-slate-800 mb-6">Kelola Presensi Semua Kelas</h2>

    <div class="bg-white rounded-xl shadow-lg p-8">
        <!-- Filter Hari -->
        <div class="mb-6">
            <label class="block text-sm font-medium text-slate-700 mb-2">Filter Hari (Opsional)</label>
            <div class="flex flex-wrap gap-2">
                <a href="{{ route('guru.presensi') }}" 
                   class="px-4 py-2 rounded-lg text-sm font-semibold transition-colors
                          {{ !$hariFilter ? 'bg-blue-500 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                    Semua Hari
                </a>
                @foreach($daftarHari as $hari)
                    <a href="{{ route('guru.presensi', ['hari' => $hari]) }}" 
                       class="px-4 py-2 rounded-lg text-sm font-semibold transition-colors
                              {{ $hariFilter === $hari ? 'bg-blue-500 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                        {{ $hari }}
                    </a>
                @endforeach
            </div>
        </div>

        <h2 class="text-3xl font-bold text-slate-900 mb-2">
            Daftar Jadwal Mengajar {{ $hariFilter ? '- ' . $hariFilter : '(Semua Hari)' }}
        </h2>
        <p class="text-sm text-slate-500 mb-8">Anda dapat membuat pertemuan & presensi kapan saja, tidak harus menunggu hari mengajar</p>
        
        @if($jadwalList && $jadwalList->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                @foreach($jadwalList as $jadwal)
                    @php
                        // Hitung jumlah siswa
                        $jumlahSiswa = DB::table('siswa')->where('kelas_id', $jadwal->kelas_id)->count();
                    @endphp
                    
                                        <div class="bg-white rounded-2xl shadow-lg p-6 hover:shadow-xl transition-shadow border-2 border-slate-200">
                        <div class="flex justify-between items-start mb-4">
                            <span class="inline-block px-3 py-1 rounded-full text-sm font-bold bg-blue-100 text-blue-700">
                                {{ $jadwal->hari }}
                            </span>
                        </div>
                        
                        <div class="flex justify-center mb-6">
                            <img src="{{ asset('images/Schedule.png') }}" alt="Ikon Presensi" class="w-32 h-32 object-contain">
                        </div>
                        
                        <div class="text-left mb-6">
                            <p class="text-slate-600 mb-1">{{ $jadwal->kelas->nama_kelas }}</p>
                            <h3 class="text-xl font-bold text-blue-600 mb-2">{{ $jadwal->mataPelajaran->nama_mapel }}</h3>
                            <p class="text-sm text-slate-500 mb-2">{{ $jadwal->jam_mulai }} - {{ $jadwal->jam_selesai }}</p>
                            <div class="flex items-center text-slate-500 text-sm">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-5 h-5 mr-2">
                                    <path d="M11.7 2.805a.75.75 0 01.6 0A60.65 60.65 0 0122.83 8.72a.75.75 0 01-.231 1.337 49.949 49.949 0 00-9.902 3.912l-.003.002-.34.18a.75.75 0 01-.707 0A50.009 50.009 0 007.5 12.174v-.224c0-.131.067-.248.182-.311a3.376 3.376 0 002.246-2.976 60.646 60.646 0 01-9.9-5.86a.75.75 0 010-1.337A60.653 60.653 0 0111.7 2.805z" />
                                </svg>
                                {{ $jumlahSiswa }} Siswa
                            </div>
                        </div>
                        
                        <div class="space-y-2">
                            <button onclick="openBuatPertemuanModal({{ $jadwal->id_jadwal }}, '{{ $jadwal->mataPelajaran->nama_mapel }}', '{{ $jadwal->kelas->nama_kelas }}', '{{ $jadwal->hari }}', '{{ $jadwal->jam_mulai }}', '{{ $jadwal->jam_selesai }}')"
                                    class="w-full bg-green-500 text-white text-center font-bold text-lg py-3 rounded-full hover:bg-green-600 transition-colors">
                                + Buat Pertemuan Baru
                            </button>
                            
                            <a href="{{ route('guru.list_pertemuan', $jadwal->id_jadwal) }}" 
                               class="block w-full bg-purple-500 text-white text-center font-bold text-lg py-3 rounded-full hover:bg-purple-600 transition-colors">
                                📋 Lihat Semua Pertemuan (1-16)
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-16 text-slate-500">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-20 h-20 mx-auto mb-4 opacity-50">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5" />
                </svg>
                <p class="font-medium text-lg">{{ $hariFilter ? 'Tidak ada jadwal mengajar pada hari ' . $hariFilter : 'Anda belum memiliki jadwal mengajar' }}</p>
                <p class="text-sm mt-1">{{ $hariFilter ? 'Silakan pilih hari lain atau lihat semua jadwal' : 'Hubungi admin untuk menambahkan jadwal' }}</p>
            </div>
        @endif
    </div>

    <!-- Modal Buat Pertemuan -->
    <div id="buatPertemuanModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
        <div class="bg-white rounded-2xl shadow-2xl p-6 w-full max-w-3xl max-h-[90vh] overflow-y-auto">
            <h3 class="text-xl font-bold text-slate-900 mb-3">Buat Pertemuan Baru</h3>
            
            <form id="buatPertemuanForm" method="POST" action="">
                @csrf
                
                <!-- Info Header - Compact 4-Column Grid -->
                <div class="mb-3 p-3 bg-gradient-to-r from-blue-50 to-purple-50 rounded-lg border border-blue-200">
                    <div class="grid grid-cols-4 gap-2 text-xs">
                        <div><span class="font-semibold text-slate-600">📘 Mapel:</span> <span id="modalMapel" class="text-slate-800"></span></div>
                        <div><span class="font-semibold text-slate-600">🎓 Kelas:</span> <span id="modalKelas" class="text-slate-800"></span></div>
                        <div><span class="font-semibold text-slate-600">📅 Hari:</span> <span id="modalHari" class="text-slate-800"></span></div>
                        <div><span class="font-semibold text-slate-600">⏰ Jam:</span> <span id="modalJam" class="text-slate-800"></span></div>
                    </div>
                </div>

                <!-- Pertemuan & Tanggal - 2 Columns -->
                <div class="grid grid-cols-2 gap-3 mb-3">
                    <div>
                        <label class="block text-xs font-medium text-slate-700 mb-1">
                            🔢 Pilih Pertemuan ke- <span class="text-red-500">*</span>
                        </label>
                        <select name="nomor_pertemuan" id="nomorPertemuanSelect" required
                                class="w-full px-3 py-2 text-sm border-2 border-blue-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">-- Pilih Slot Kosong --</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-slate-700 mb-1">
                            📅 Tanggal <span class="text-red-500">*</span>
                        </label>
                        <input type="date" name="tanggal_pertemuan" id="tanggalPertemuanInput" required
                               class="w-full px-3 py-2 text-sm border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>
                </div>

                <!-- Waktu Absensi - 2 Columns -->
                <div class="mb-3">
                    <label class="block text-xs font-medium text-slate-700 mb-2">
                        ⏰ Waktu Absensi (Format 24 Jam) <span class="text-red-500">*</span>
                    </label>
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block mb-1 text-xs font-semibold text-green-600">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-3 h-3 inline mr-1">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 1 0 0-16 8 8 0 0 0 0 16Zm.75-13a.75.75 0 0 0-1.5 0v5c0 .414.336.75.75.75h4a.75.75 0 0 0 0-1.5h-3.25V5Z" clip-rule="evenodd" />
                                </svg>
                                Jam Buka
                            </label>
                            <div class="flex gap-1">
                                <select id="jam_buka_hour" class="w-1/2 border-2 border-green-300 rounded-lg py-1.5 px-2 text-slate-700 text-sm font-semibold focus:outline-none focus:border-green-500">
                                    @for($h = 0; $h < 24; $h++)
                                        <option value="{{ sprintf('%02d', $h) }}">{{ sprintf('%02d', $h) }}</option>
                                    @endfor
                                </select>
                                <select id="jam_buka_minute" class="w-1/2 border-2 border-green-300 rounded-lg py-1.5 px-2 text-slate-700 text-sm font-semibold focus:outline-none focus:border-green-500">
                                    <option value="00">00</option>
                                    <option value="15">15</option>
                                    <option value="30">30</option>
                                    <option value="45">45</option>
                                </select>
                                <input type="hidden" id="jam_absen_buka" name="jam_absen_buka" required>
                            </div>
                        </div>
                        <div>
                            <label class="block mb-1 text-xs font-semibold text-red-600">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-3 h-3 inline mr-1">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 1 0 0-16 8 8 0 0 0 0 16Zm.75-13a.75.75 0 0 0-1.5 0v5c0 .414.336.75.75.75h4a.75.75 0 0 0 0-1.5h-3.25V5Z" clip-rule="evenodd" />
                                </svg>
                                Jam Tutup
                            </label>
                            <div class="flex gap-1">
                                <select id="jam_tutup_hour" class="w-1/2 border-2 border-red-300 rounded-lg py-1.5 px-2 text-slate-700 text-sm font-semibold focus:outline-none focus:border-red-500">
                                    @for($h = 0; $h < 24; $h++)
                                        <option value="{{ sprintf('%02d', $h) }}">{{ sprintf('%02d', $h) }}</option>
                                    @endfor
                                </select>
                                <select id="jam_tutup_minute" class="w-1/2 border-2 border-red-300 rounded-lg py-1.5 px-2 text-slate-700 text-sm font-semibold focus:outline-none focus:border-red-500">
                                    <option value="00">00</option>
                                    <option value="15">15</option>
                                    <option value="30">30</option>
                                    <option value="45">45</option>
                                </select>
                                <input type="hidden" id="jam_absen_tutup" name="jam_absen_tutup" required>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Materi - Full Width -->
                <div class="mb-4">
                    <label class="block text-xs font-medium text-slate-700 mb-1">
                        📚 Materi / Topik Bahasan <span class="text-xs text-slate-500">(opsional)</span>
                    </label>
                    <textarea name="topik_bahasan" id="topikBahasan" rows="2"
                              placeholder="Contoh: Pengenalan Array dan Looping"
                              class="w-full px-3 py-2 text-sm border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 resize-none"></textarea>
                </div>

                <div class="flex gap-3 mt-4">
                    <button type="button" onclick="closeBuatPertemuanModal()"
                            class="flex-1 px-5 py-2.5 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors font-semibold text-sm">
                        Batal
                    </button>
                    <button type="submit"
                            class="flex-1 px-5 py-2.5 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition-colors font-semibold text-sm">
                        Buat Pertemuan
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openBuatPertemuanModal(jadwalId, mapel, kelas, hari, jamMulai, jamSelesai) {
            document.getElementById('buatPertemuanModal').classList.remove('hidden');
            document.getElementById('modalMapel').textContent = mapel;
            document.getElementById('modalKelas').textContent = kelas;
            document.getElementById('modalHari').textContent = hari;
            document.getElementById('modalJam').textContent = jamMulai + ' - ' + jamSelesai;
            
            // Fetch slot yang tersedia
            fetch(`/guru/presensi/slot-tersedia/${jadwalId}`)
                .then(res => res.json())
                .then(data => {
                    const select = document.getElementById('nomorPertemuanSelect');
                    select.innerHTML = '<option value="">-- Pilih Slot Kosong --</option>';
                    if (data.slotKosong.length === 0) {
                        select.innerHTML = '<option value="">Semua slot sudah terisi (0/16)</option>';
                        select.disabled = true;
                    } else {
                        select.disabled = false;
                        data.slotKosong.forEach(slot => {
                            select.innerHTML += `<option value="${slot}">Pertemuan ${slot}</option>`;
                        });
                    }
                })
                .catch(err => {
                    console.error('Error fetching slots:', err);
                    alert('Gagal memuat slot pertemuan. Silakan refresh halaman.');
                });
            
            // Set tanggal default ke hari ini
            const today = new Date().toISOString().split('T')[0];
            document.getElementById('tanggalPertemuanInput').value = today;
            
            // Set default waktu absen (30 menit sebelum - 15 menit setelah jam mulai)
            const [jamMulaiHour, jamMulaiMinute] = jamMulai.split(':');
            const jamBukaHour = parseInt(jamMulaiHour);
            const jamBukaMinute = parseInt(jamMulaiMinute) - 30;
            const jamTutupHour = parseInt(jamMulaiHour);
            const jamTutupMinute = parseInt(jamMulaiMinute) + 15;
            
            // Handle negative minutes for jam buka
            let finalBukaHour = jamBukaHour;
            let finalBukaMinute = jamBukaMinute;
            if (jamBukaMinute < 0) {
                finalBukaHour = jamBukaHour - 1;
                finalBukaMinute = 60 + jamBukaMinute;
            }
            if (finalBukaHour < 0) {
                finalBukaHour = 0;
                finalBukaMinute = 0;
            }
            
            // Handle overflow minutes for jam tutup
            let finalTutupHour = jamTutupHour;
            let finalTutupMinute = jamTutupMinute;
            if (jamTutupMinute >= 60) {
                finalTutupHour = jamTutupHour + 1;
                finalTutupMinute = jamTutupMinute - 60;
            }
            if (finalTutupHour >= 24) {
                finalTutupHour = 23;
                finalTutupMinute = 59;
            }
            
            // Set dropdown values (round to nearest 15 min interval)
            document.getElementById('jam_buka_hour').value = String(finalBukaHour).padStart(2, '0');
            const bukaMinuteRounded = Math.floor(finalBukaMinute / 15) * 15;
            document.getElementById('jam_buka_minute').value = String(bukaMinuteRounded).padStart(2, '0');
            
            document.getElementById('jam_tutup_hour').value = String(finalTutupHour).padStart(2, '0');
            const tutupMinuteRounded = Math.floor(finalTutupMinute / 15) * 15;
            document.getElementById('jam_tutup_minute').value = String(tutupMinuteRounded).padStart(2, '0');
            
            // Update hidden inputs
            updateJamBuka();
            updateJamTutup();
            
            // Set form action
            document.getElementById('buatPertemuanForm').action = 
                '{{ url("guru/presensi/buat-pertemuan") }}/' + jadwalId;
        }

        function closeBuatPertemuanModal() {
            document.getElementById('buatPertemuanModal').classList.add('hidden');
        }

        // Update hidden input jam buka
        function updateJamBuka() {
            const hour = document.getElementById('jam_buka_hour').value;
            const minute = document.getElementById('jam_buka_minute').value;
            document.getElementById('jam_absen_buka').value = hour + ':' + minute;
        }

        // Update hidden input jam tutup
        function updateJamTutup() {
            const hour = document.getElementById('jam_tutup_hour').value;
            const minute = document.getElementById('jam_tutup_minute').value;
            document.getElementById('jam_absen_tutup').value = hour + ':' + minute;
        }

        // Event listeners untuk dropdown
        document.addEventListener('DOMContentLoaded', function() {
            ['jam_buka_hour', 'jam_buka_minute'].forEach(id => {
                const el = document.getElementById(id);
                if (el) el.addEventListener('change', updateJamBuka);
            });
            ['jam_tutup_hour', 'jam_tutup_minute'].forEach(id => {
                const el = document.getElementById(id);
                if (el) el.addEventListener('change', updateJamTutup);
            });
            
            // Initialize values
            updateJamBuka();
            updateJamTutup();
        });

        // Close modal saat klik di luar
        document.getElementById('buatPertemuanModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeBuatPertemuanModal();
            }
        });
    </script>

@endsection