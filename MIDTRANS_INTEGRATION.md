# Integrasi Midtrans Payment Gateway

## Overview
Sistem pembayaran SPP terintegrasi dengan Midtrans Payment Gateway menggunakan Core API (bukan Snap Token) untuk mempertahankan UI/UX website yang sudah ada.

## Setup & Konfigurasi

### 1. Environment Variables (.env)
```env
MIDTRANS_MERCHANT_ID=G699511196
MIDTRANS_CLIENT_KEY=SB-Mid-client-JV1hxBKvK54RC4PV
MIDTRANS_SERVER_KEY=SB-Mid-server-vtes-EfWHAlMBGLjEXF06HtG
MIDTRANS_IS_PRODUCTION=false
MIDTRANS_SANITIZED=true
MIDTRANS_3DS=true
```

### 2. Database Migration
Migration telah ditambahkan untuk kolom Midtrans di tabel `pembayaran_spp`:
- `midtrans_order_id` - Unique order ID untuk tracking
- `midtrans_transaction_id` - Transaction ID dari Midtrans
- `midtrans_payment_type` - Jenis pembayaran (bank_transfer, gopay, shopeepay)
- `midtrans_transaction_status` - Status transaksi (pending, settlement, expire, cancel, deny)
- `midtrans_response` - Full response dari Midtrans (JSON)
- `midtrans_paid_at` - Timestamp kapan pembayaran berhasil

Jalankan migration:
```bash
php artisan migrate
```

## Struktur File

### 1. Config File
- `config/midtrans.php` - Konfigurasi Midtrans

### 2. Service Layer
- `app/Services/MidtransService.php` - Service class untuk Midtrans API
  - `createVirtualAccount()` - Generate VA untuk Transfer Bank
  - `createEwalletPayment()` - Generate payment untuk GoPay/ShopeePay
  - `checkStatus()` - Cek status pembayaran
  - `cancel()` - Cancel transaksi
  - `handleNotification()` - Handle webhook dari Midtrans

### 3. Model
- `app/Models/PembayaranSpp.php` - Model untuk tabel pembayaran_spp

### 4. Controller
- `app/Http/Controllers/Siswa/PembayaranController.php` - Controller untuk pembayaran siswa
  - `index()` - List tagihan belum lunas
  - `tagihanSudahDibayar()` - List tagihan sudah lunas
  - `detailTagihan()` - Detail tagihan belum dibayar
  - `detailTagihanSudahDibayar()` - Detail tagihan sudah dibayar
  - `createPayment()` - Create payment via Midtrans
  - `checkStatus()` - Check payment status
  - `handleNotification()` - Handle Midtrans notification/webhook

### 5. Views (Siswa)
- `resources/views/siswa/tagihan.blade.php` - List tagihan belum dibayar
- `resources/views/siswa/tagihanSudahDibayar.blade.php` - List tagihan sudah dibayar
- `resources/views/siswa/detailTagihan.blade.php` - Detail & form pembayaran
- `resources/views/siswa/detailTagihanSudahDibayar.blade.php` - Detail tagihan lunas

## Flow Pembayaran

### A. Untuk Siswa

#### 1. Melihat Tagihan
- Akses `/siswa/tagihan`
- Sistem menampilkan list tagihan yang belum dibayar
- Klik tombol "Bayar" untuk membayar

#### 2. Memilih Metode Pembayaran
Pada halaman detail tagihan, pilih metode:
- **Transfer Bank** (Virtual Account BCA)
- **GoPay** (E-wallet)
- **ShopeePay** (E-wallet)

#### 3. Proses Pembayaran

**A. Transfer Bank (Virtual Account)**
- Klik "Bayar Sekarang"
- Sistem generate nomor VA BCA
- Nomor VA ditampilkan di halaman
- Siswa transfer ke nomor VA tersebut
- Klik "Cek Status Pembayaran" untuk update status

**B. E-Wallet (GoPay/ShopeePay)**
- Klik "Bayar Sekarang"
- Sistem generate QR Code
- Scan QR Code dengan aplikasi atau klik "Buka Aplikasi"
- Selesaikan pembayaran di aplikasi
- Klik "Cek Status Pembayaran" untuk update status

#### 4. Konfirmasi Pembayaran
- Midtrans akan kirim notifikasi ke sistem (webhook)
- Status tagihan otomatis berubah menjadi "Lunas"
- Siswa bisa melihat di "Sudah Dibayar"

### B. Webhook Notification (Otomatis)

Midtrans akan send POST request ke:
```
https://sman12-connect.test/payment/midtrans/notification
```

Sistem akan:
1. Validate notification dari Midtrans
2. Update status pembayaran di database
3. Update `tgl_bayar` dan `midtrans_paid_at`
4. Ubah status tagihan menjadi "Lunas"

## API Endpoints

### Siswa Routes
```php
GET  /siswa/tagihan                              - List tagihan belum dibayar
GET  /siswa/tagihan/sudah-dibayar                - List tagihan sudah dibayar
GET  /siswa/tagihan/{id}                         - Detail tagihan belum dibayar
GET  /siswa/tagihan/{id}/sudah-dibayar           - Detail tagihan sudah dibayar
POST /siswa/tagihan/{id}/bayar                   - Create payment
GET  /siswa/tagihan/{id}/check-status            - Check payment status
GET  /siswa/tagihan/callback                     - E-wallet callback
```

### Webhook (Public)
```php
POST /payment/midtrans/notification              - Midtrans webhook handler
```

## Request/Response Examples

### 1. Create Payment (POST /siswa/tagihan/{id}/bayar)

**Request:**
```json
{
  "metode_pembayaran": "bank_transfer" // or "gopay", "shopeepay"
}
```

**Response (Virtual Account):**
```json
{
  "success": true,
  "message": "Pembayaran berhasil dibuat",
  "data": {
    "order_id": "SPP-123-1700000000",
    "transaction_status": "pending",
    "payment_type": "bank_transfer",
    "va_number": "70012345678901",
    "qr_code": null,
    "deeplink": null
  }
}
```

**Response (E-Wallet):**
```json
{
  "success": true,
  "message": "Pembayaran berhasil dibuat",
  "data": {
    "order_id": "SPP-123-1700000000",
    "transaction_status": "pending",
    "payment_type": "gopay",
    "va_number": null,
    "qr_code": "https://api.midtrans.com/v2/qris/...",
    "deeplink": "gojek://gopay/..."
  }
}
```

### 2. Check Status (GET /siswa/tagihan/{id}/check-status)

**Response:**
```json
{
  "success": true,
  "data": {
    "transaction_status": "settlement",
    "payment_type": "bank_transfer",
    "status_tagihan": "Lunas"
  }
}
```

## Transaction Status Flow

### Midtrans Status → Database Status

| Midtrans Status | Fraud Status | Database Status | Description |
|----------------|--------------|-----------------|-------------|
| `pending` | - | Belum Lunas | Menunggu pembayaran |
| `capture` | `accept` | Lunas | Pembayaran berhasil (kartu kredit) |
| `settlement` | - | Lunas | Pembayaran berhasil (non kartu kredit) |
| `deny` | - | Belum Lunas | Pembayaran ditolak |
| `expire` | - | Belum Lunas | Pembayaran expired |
| `cancel` | - | Belum Lunas | Pembayaran dibatalkan |

## Testing

### Sandbox Testing (Development)

**Virtual Account BCA:**
1. Gunakan VA yang digenerate sistem
2. Lakukan pembayaran di Midtrans Simulator:
   - Login ke dashboard.sandbox.midtrans.com
   - Atau gunakan Postman untuk simulate payment

**GoPay/ShopeePay:**
1. Scan QR Code atau klik deeplink
2. Complete payment di simulator

### Production Checklist

Sebelum production:
1. ✅ Ubah `MIDTRANS_IS_PRODUCTION=true` di `.env`
2. ✅ Ganti `MIDTRANS_SERVER_KEY` dengan production key
3. ✅ Ganti `MIDTRANS_CLIENT_KEY` dengan production key
4. ✅ Setup webhook URL di Midtrans Dashboard:
   ```
   https://yourdomain.com/payment/midtrans/notification
   ```
5. ✅ Test semua payment methods
6. ✅ Verifikasi notifikasi webhook berfungsi

## Security

1. **Server Key Protection**: Never expose server key di frontend
2. **Webhook Verification**: Semua notification divalidasi dengan signature
3. **HTTPS**: Production harus menggunakan HTTPS
4. **CSRF Token**: Semua POST request protected dengan CSRF

## Troubleshooting

### Payment Tidak Ter-update Otomatis
- Cek webhook URL di Midtrans Dashboard
- Verify server bisa terima POST dari Midtrans
- Check log di `storage/logs/laravel.log`

### VA Tidak Generate
- Cek Midtrans credentials di `.env`
- Verify Midtrans package installed: `composer show midtrans/midtrans-php`
- Check API error di response

### QR Code Tidak Muncul
- Pastikan payment_type correct (`gopay` atau `shopeepay`)
- Check response dari Midtrans di `midtrans_response` column

## Support

Untuk issue atau pertanyaan:
1. Check Midtrans documentation: https://docs.midtrans.com
2. Check Laravel logs: `storage/logs/laravel.log`
3. Contact Midtrans support untuk production issues

## Notes

- Sistem menggunakan **Core API** Midtrans, bukan Snap Token
- UI/UX tetap custom, tidak menggunakan popup Midtrans
- Semua payment history tersimpan di database
- Support multiple payment methods (VA, GoPay, ShopeePay)
- Auto-update via webhook notification
