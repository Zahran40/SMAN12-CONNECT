@extends('layouts.siswa.app')

@section('content')

    <div class="flex items-center space-x-4 mb-8">
        <a href="{{ route('siswa.tagihan') }}" class="w-12 h-12 flex items-center justify-center bg-blue-100 text-blue-700 rounded-full hover:bg-blue-200 transition-colors" title="Kembali">
           <img src="{{ asset('images/mingcute_back-fill.png') }}" fill="none" viewBox="0 0 26 26" stroke-width="2.5" stroke="currentColor" class="w-8 h-8">
              <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5" />
            </img>
        </a>
        <h2 class="text-3xl font-bold text-blue-500">{{ $tagihan->nama_tagihan }}</h2>
    </div>

    @if(session('success'))
        <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="mb-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg">
            {{ session('error') }}
        </div>
    @endif

    <div class="bg-white rounded-xl shadow-lg border-2 border-blue-300 p-6 md:p-8">
        
        <div class="mb-8">
            <div class="flex justify-between items-start mb-4">
                <div class="flex items-center space-x-3">
                    <span class="w-6 h-2 bg-yellow-400 rounded-sm"></span>
                    <h3 class="text-lg font-bold text-slate-800">Detail Siswa</h3>
                </div>
                <span class="bg-red-100 text-red-700 text-xs font-semibold px-3 py-1 rounded-full">
                    Belum dibayar
                </span>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-y-4 gap-x-6 pl-5">
                <div>
                    <p class="text-xs text-slate-500">Nama</p>
                    <p class="font-bold text-slate-900">{{ $siswa->nama_lengkap }}</p>
                </div>
                <div>
                    <p class="text-xs text-slate-500">NIS</p>
                    <p class="font-bold text-slate-900">{{ $siswa->nis }}</p>
                </div>
                <div>
                    <p class="text-xs text-slate-500">Kelas</p>
                    <p class="font-bold text-slate-900">{{ $siswa->kelas->nama_kelas ?? '-' }}</p>
                </div>
            </div>
        </div>

        <div class="mb-8">
            <div class="flex items-center space-x-3 mb-4">
                <span class="w-6 h-2 bg-yellow-400 rounded-sm"></span>
                <h3 class="text-lg font-bold text-slate-800">Detail Tagihan</h3>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-y-5 gap-x-6 pl-5">
                <div>
                    <p class="text-xs text-slate-500">Nama Tagihan</p>
                    <p class="font-bold text-slate-900">{{ $tagihan->nama_tagihan }}</p>
                </div>
                <div>
                    <p class="text-xs text-slate-500">Periode</p>
                    @php
                        $bulanText = [
                            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
                            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
                            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
                        ];
                    @endphp
                    <p class="font-bold text-slate-900">{{ $bulanText[$tagihan->bulan] }} {{ $tagihan->tahun }}</p>
                </div>
                <div>
                    <p class="text-xs text-slate-500">Nominal</p>
                    <p class="font-bold text-slate-900">Rp {{ number_format($tagihan->jumlah_bayar, 0, ',', '.') }}</p>
                </div>
                <div class="md:col-span-2">
                    <p class="text-xs text-slate-500">Metode Pembayaran</p>
                    <select id="metode_pembayaran" onchange="toggleBankSelect()" class="mt-1 block w-full py-2 px-3 border border-slate-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm disabled:bg-slate-100 disabled:text-slate-500 disabled:cursor-not-allowed">
                        <option value="">-- Pilih Metode --</option>
                        <option value="bank_transfer">Transfer Bank (Virtual Account)</option>
                        <option value="gopay">GoPay</option>
                        <option value="shopeepay">ShopeePay</option>
                    </select>
                </div>
                <div id="bank-select" class="hidden md:col-span-2">
                    <p class="text-xs text-slate-500">Pilih Bank</p>
                    <select id="bank" class="mt-1 block w-full py-2 px-3 border border-slate-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm disabled:bg-slate-100 disabled:text-slate-500 disabled:cursor-not-allowed">
                        <option value="">-- Pilih Bank --</option>
                        <option value="bca">BCA</option>
                        <option value="bni">BNI</option>
                        <option value="bri">BRI</option>
                        <option value="permata">Permata</option>
                    </select>
                </div>
            </div>

            <!-- Payment Information Area (Hidden initially) -->
            <div id="payment-info" class="hidden mt-6 p-4 bg-blue-50 border border-blue-200 rounded-lg">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div id="va-info" class="hidden">
                        <p class="text-xs text-slate-500">Nomor Virtual Account</p>
                        <div class="flex items-center space-x-2">
                            <p id="va-number" class="font-bold text-slate-900 text-lg"></p>
                            <button onclick="copyVA()" class="text-blue-600 hover:text-blue-800">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                    <path d="M8 3a1 1 0 011-1h2a1 1 0 110 2H9a1 1 0 01-1-1z" />
                                    <path d="M6 3a2 2 0 00-2 2v11a2 2 0 002 2h8a2 2 0 002-2V5a2 2 0 00-2-2 3 3 0 01-3 3H9a3 3 0 01-3-3z" />
                                </svg>
                            </button>
                        </div>
                        <p class="text-xs text-slate-500 mt-1">Bank BCA</p>
                    </div>
                    
                    <div id="ewallet-info" class="hidden">
                        <p class="text-xs text-slate-500 mb-2">QR Code Pembayaran</p>
                        <div id="qr-code-container" class="bg-white p-2 rounded inline-block">
                            <!-- QR Code will be displayed here -->
                        </div>
                        <a id="deeplink-button" href="#" target="_blank" class="hidden mt-2 inline-block bg-green-500 text-white px-4 py-2 rounded-lg hover:bg-green-600">
                            Buka Aplikasi
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="flex justify-end space-x-4 mt-12">
            <button id="btn-bayar" onclick="confirmPayment()" class="bg-blue-600 text-white font-semibold px-8 py-2.5 rounded-full hover:bg-blue-700 transition-colors shadow-lg shadow-blue-600/30">
                Bayar Sekarang
            </button>
            <button id="btn-cek-status" onclick="checkStatus()" class="hidden bg-green-500 text-white font-semibold px-8 py-2.5 rounded-full hover:bg-green-600 transition-colors shadow-lg shadow-green-500/30">
                Cek Status Pembayaran
            </button>
        </div>

    </div>

    <!-- Modal Konfirmasi Pembayaran -->
    <div id="confirmation-modal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white rounded-xl shadow-2xl p-8 max-w-md w-full mx-4">
            <div class="text-center mb-6">
                <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-yellow-100 mb-4">
                    <svg class="h-10 w-10 text-yellow-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-slate-900 mb-2">Konfirmasi Pembayaran</h3>
                <p class="text-sm text-slate-600">Pastikan informasi pembayaran Anda sudah benar</p>
            </div>

            <div class="bg-blue-50 rounded-lg p-4 mb-6 space-y-3">
                <div class="flex justify-between items-center">
                    <span class="text-sm text-slate-600">Nominal:</span>
                    <span class="font-bold text-slate-900">Rp {{ number_format($tagihan->jumlah_bayar, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-sm text-slate-600">Metode:</span>
                    <span id="modal-metode" class="font-bold text-slate-900"></span>
                </div>
                <div id="modal-bank-info" class="hidden flex justify-between items-center">
                    <span class="text-sm text-slate-600">Bank:</span>
                    <span id="modal-bank" class="font-bold text-slate-900"></span>
                </div>
            </div>

            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-3 mb-6">
                <p class="text-xs text-yellow-800">
                    <strong>Perhatian:</strong> Setelah konfirmasi, Anda tidak dapat mengubah metode pembayaran. Pastikan pilihan Anda sudah benar.
                </p>
            </div>

            <div class="flex space-x-3">
                <button onclick="closeModal()" class="flex-1 bg-slate-200 text-slate-700 font-semibold py-2.5 rounded-lg hover:bg-slate-300 transition-colors">
                    Batal
                </button>
                <button onclick="proceedPayment()" class="flex-1 bg-blue-600 text-white font-semibold py-2.5 rounded-lg hover:bg-blue-700 transition-colors">
                    Ya, Lanjutkan
                </button>
            </div>
        </div>
    </div>

    <script>
        const tagihanId = {{ $tagihan->id_pembayaran }};
        let currentOrderId = '{{ $tagihan->midtrans_order_id }}';

        function toggleBankSelect() {
            const metode = document.getElementById('metode_pembayaran').value;
            const bankSelect = document.getElementById('bank-select');
            
            if (metode === 'bank_transfer') {
                bankSelect.classList.remove('hidden');
            } else {
                bankSelect.classList.add('hidden');
            }
        }

        function confirmPayment() {
            const metodePembayaran = document.getElementById('metode_pembayaran').value;
            
            if (!metodePembayaran) {
                alert('Silakan pilih metode pembayaran terlebih dahulu');
                return;
            }

            // Check if bank selected for bank transfer
            if (metodePembayaran === 'bank_transfer') {
                const bank = document.getElementById('bank').value;
                if (!bank) {
                    alert('Silakan pilih bank terlebih dahulu');
                    return;
                }
            }

            // Show confirmation modal with payment details
            showConfirmationModal();
        }

        function showConfirmationModal() {
            const metodePembayaran = document.getElementById('metode_pembayaran').value;
            const bank = document.getElementById('bank').value;
            
            // Map metode to readable text
            const metodeText = {
                'bank_transfer': 'Transfer Bank (Virtual Account)',
                'gopay': 'GoPay',
                'shopeepay': 'ShopeePay'
            };

            const bankText = {
                'bca': 'BCA',
                'bni': 'BNI',
                'bri': 'BRI',
                'permata': 'Permata'
            };

            // Update modal content
            document.getElementById('modal-metode').textContent = metodeText[metodePembayaran] || metodePembayaran;
            
            if (metodePembayaran === 'bank_transfer' && bank) {
                document.getElementById('modal-bank-info').classList.remove('hidden');
                document.getElementById('modal-bank').textContent = bankText[bank] || bank.toUpperCase();
            } else {
                document.getElementById('modal-bank-info').classList.add('hidden');
            }

            // Show modal
            document.getElementById('confirmation-modal').classList.remove('hidden');
        }

        function closeModal() {
            document.getElementById('confirmation-modal').classList.add('hidden');
        }

        async function proceedPayment() {
            // Close modal
            closeModal();

            // Disable dropdowns
            document.getElementById('metode_pembayaran').disabled = true;
            document.getElementById('bank').disabled = true;

            const metodePembayaran = document.getElementById('metode_pembayaran').value;
            
            const requestBody = {
                metode_pembayaran: metodePembayaran
            };

            // Add bank if bank transfer selected
            if (metodePembayaran === 'bank_transfer') {
                const bank = document.getElementById('bank').value;
                requestBody.bank = bank;
            }

            const btnBayar = document.getElementById('btn-bayar');
            btnBayar.disabled = true;
            btnBayar.innerHTML = '<span class="animate-pulse">Memproses...</span>';

            try {
                const response = await fetch(`/siswa/tagihan/${tagihanId}/bayar`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify(requestBody)
                });

                const data = await response.json();

                if (data.success) {
                    currentOrderId = data.data.order_id;
                    showPaymentInfo(data.data);
                    
                    // Show check status button
                    document.getElementById('btn-cek-status').classList.remove('hidden');
                    btnBayar.classList.add('hidden');
                } else {
                    alert('Gagal membuat pembayaran: ' + data.message);
                    // Re-enable dropdowns on error
                    document.getElementById('metode_pembayaran').disabled = false;
                    document.getElementById('bank').disabled = false;
                    btnBayar.disabled = false;
                    btnBayar.innerHTML = 'Bayar Sekarang';
                }
            } catch (error) {
                console.error('Error:', error);
                alert('Terjadi kesalahan saat membuat pembayaran');
                // Re-enable dropdowns on error
                document.getElementById('metode_pembayaran').disabled = false;
                document.getElementById('bank').disabled = false;
                btnBayar.disabled = false;
                btnBayar.innerHTML = 'Bayar Sekarang';
            }
        }

        function showPaymentInfo(paymentData) {
            const paymentInfo = document.getElementById('payment-info');
            paymentInfo.classList.remove('hidden');

            if (paymentData.va_number) {
                // Virtual Account
                const vaInfo = document.getElementById('va-info');
                vaInfo.classList.remove('hidden');
                document.getElementById('va-number').textContent = paymentData.va_number;
            } else if (paymentData.qr_code || paymentData.deeplink) {
                // E-Wallet
                const ewalletInfo = document.getElementById('ewallet-info');
                ewalletInfo.classList.remove('hidden');
                
                if (paymentData.qr_code) {
                    // Display QR Code as image
                    const qrContainer = document.getElementById('qr-code-container');
                    qrContainer.innerHTML = `<img src="${paymentData.qr_code}" alt="QR Code" class="w-48 h-48">`;
                }
                
                if (paymentData.deeplink) {
                    const deeplinkBtn = document.getElementById('deeplink-button');
                    deeplinkBtn.href = paymentData.deeplink;
                    deeplinkBtn.classList.remove('hidden');
                }
            }
        }

        async function checkStatus() {
            const btnCekStatus = document.getElementById('btn-cek-status');
            btnCekStatus.disabled = true;
            btnCekStatus.innerHTML = '<span class="animate-pulse">Mengecek...</span>';

            try {
                const response = await fetch(`/siswa/tagihan/${tagihanId}/check-status`);
                const data = await response.json();

                if (data.success) {
                    if (data.data.status_tagihan === 'Lunas') {
                        alert('Pembayaran berhasil! Halaman akan dimuat ulang.');
                        window.location.href = '{{ route("siswa.tagihan") }}';
                    } else {
                        const statusText = data.data.transaction_status === 'pending' ? 
                            'Pembayaran masih menunggu' : 
                            'Status: ' + data.data.transaction_status;
                        alert(statusText);
                        btnCekStatus.disabled = false;
                        btnCekStatus.innerHTML = 'Cek Status Pembayaran';
                    }
                } else {
                    alert('Gagal mengecek status: ' + data.message);
                    btnCekStatus.disabled = false;
                    btnCekStatus.innerHTML = 'Cek Status Pembayaran';
                }
            } catch (error) {
                console.error('Error:', error);
                alert('Terjadi kesalahan saat mengecek status');
                btnCekStatus.disabled = false;
                btnCekStatus.innerHTML = 'Cek Status Pembayaran';
            }
        }

        function copyVA() {
            const vaNumber = document.getElementById('va-number').textContent;
            navigator.clipboard.writeText(vaNumber).then(() => {
                alert('Nomor VA berhasil disalin!');
            });
        }

        // Auto check status if payment already exists
        window.addEventListener('load', function() {
            if (currentOrderId) {
                document.getElementById('btn-bayar').classList.add('hidden');
                document.getElementById('btn-cek-status').classList.remove('hidden');
                // Disable dropdowns if payment already exists
                document.getElementById('metode_pembayaran').disabled = true;
                document.getElementById('bank').disabled = true;
            }
        });

        // Close modal when clicking outside
        document.getElementById('confirmation-modal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeModal();
            }
        });
    </script>

@endsection