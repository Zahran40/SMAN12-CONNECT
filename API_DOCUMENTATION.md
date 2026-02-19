# API DOCUMENTATION - SISTEM SMAN12-CONNECT
## Dokumentasi Backend untuk 3 Role Baru

---

## 🔐 AUTHENTICATION
Semua endpoint API memerlukan autentikasi menggunakan **Laravel Sanctum**.

### Headers Required:
```
Authorization: Bearer {token}
Content-Type: application/json
Accept: application/json
```

---

## 👨‍👩‍👦 1. ORANG TUA (PARENT) API

Base URL: `/api/orangtua`

### 1.1 Dashboard Orang Tua
**GET** `/api/orangtua/dashboard`

Response:
```json
{
  "success": true,
  "data": {
    "siswa": {
      "id": 1,
      "nama": "Ahmad Fauzi",
      "nis": "12345",
      "kelas": "XII IPA 1",
      "foto": "path/to/foto.jpg"
    },
    "kehadiran": {
      "total_pertemuan": 60,
      "hadir": 55,
      "izin": 3,
      "sakit": 2,
      "alpa": 0,
      "persentase_kehadiran": 91.67
    },
    "akademik": {
      "tugas_pending": 3
    },
    "keuangan": {
      "pembayaran_terakhir": {...},
      "total_tunggakan": 0
    },
    "notifikasi": {
      "pengumuman_belum_dibaca": 2
    },
    "perilaku": {
      "total_poin": 85,
      "laporan_terbaru": [...]
    }
  }
}
```

### 1.2 Presensi Real-time
**GET** `/api/orangtua/presensi?start_date=2026-01-01&end_date=2026-01-31`

Response:
```json
{
  "success": true,
  "data": [...],
  "stats": {
    "total": 20,
    "hadir": 18,
    "izin": 1,
    "sakit": 1,
    "alpa": 0
  }
}
```

### 1.3 Monitoring Nilai
**GET** `/api/orangtua/nilai?tahun_ajaran_id=1&semester=Ganjil`

Response:
```json
{
  "success": true,
  "data": {
    "nilai": [...],
    "rata_rata": 85.5,
    "grafik_perkembangan": [...]
  }
}
```

### 1.4 Jadwal Pelajaran Anak
**GET** `/api/orangtua/jadwal`

### 1.5 Detail Tugas & Materi
**GET** `/api/orangtua/tugas-materi?status=pending`

### 1.6 Riwayat Pembayaran SPP
**GET** `/api/orangtua/pembayaran?status=Paid`

### 1.7 Laporan Perilaku
**GET** `/api/orangtua/perilaku?jenis=Positif`

### 1.8 Notifikasi Pengumuman
**GET** `/api/orangtua/pengumuman`

**POST** `/api/orangtua/pengumuman/{id}/read`

### 1.9 Perizinan Online
**GET** `/api/orangtua/perizinan`

**POST** `/api/orangtua/perizinan`
```json
{
  "jenis_izin": "Sakit",
  "tanggal_mulai": "2026-02-20",
  "tanggal_selesai": "2026-02-22",
  "alasan": "Demam tinggi",
  "dokumen": "file"
}
```

### 1.10 Grafik Perkembangan
**GET** `/api/orangtua/grafik-perkembangan?tahun_ajaran_id=1`

---

## 👔 2. PIMPINAN/KEPALA SEKOLAH API

Base URL: `/api/pimpinan`

### 2.1 Dashboard Executive
**GET** `/api/pimpinan/dashboard`

Response:
```json
{
  "success": true,
  "data": {
    "tahun_ajaran": {...},
    "kpi_siswa": {
      "total_siswa": 500,
      "siswa_aktif": 495,
      "persentase_kehadiran": 92.5,
      "nilai_rata_rata": 82.3
    },
    "kpi_guru": {
      "total_guru": 45,
      "guru_aktif": 44
    },
    "kpi_keuangan": {
      "total_pendapatan_tahun_ini": 500000000,
      "total_tunggakan": 25000000,
      "pembayaran_bulan_ini": 50000000,
      "persentase_pembayaran": 95.2
    },
    "target_sekolah": {
      "target_berjalan": 10,
      "target_tercapai": 7
    },
    "evaluasi_guru_terbaru": [...]
  }
}
```

### 2.2 Laporan Akademik Global
**GET** `/api/pimpinan/laporan-akademik?tahun_ajaran_id=1&semester=Ganjil`

Response:
```json
{
  "success": true,
  "data": {
    "rekap_per_kelas": [
      {
        "nama_kelas": "XII IPA 1",
        "jumlah_siswa": 30,
        "rata_rata_nilai": 85.5,
        "nilai_tertinggi": 98,
        "nilai_terendah": 65
      }
    ],
    "rekap_per_mapel": [...]
  }
}
```

### 2.3 Monitoring Presensi
**GET** `/api/pimpinan/monitoring-presensi?start_date=2026-01-01&end_date=2026-01-31&kelas_id=1`

### 2.4 Laporan Keuangan
**GET** `/api/pimpinan/laporan-keuangan?tahun=2026&bulan=2`

### 2.5 Evaluasi Kinerja Guru
**GET** `/api/pimpinan/evaluasi-guru?tahun_ajaran_id=1&guru_id=1`

### 2.6 Manajemen Pengumuman
**GET** `/api/pimpinan/pengumuman`

**PUT** `/api/pimpinan/pengumuman/{id}/approve`

### 2.7 Analisis Trending
**GET** `/api/pimpinan/analisis-trending?tahun=2026`

Response:
```json
{
  "success": true,
  "data": {
    "trend_kehadiran": [...],
    "trend_nilai": [...],
    "trend_pembayaran": [...],
    "comparative_kelas": [...]
  }
}
```

### 2.8 Target Sekolah
**GET** `/api/pimpinan/target-sekolah?tahun_ajaran_id=1&kategori=Akademik`

### 2.9 Export Reports
**GET** `/api/pimpinan/export-reports?tipe=akademik&format=pdf&tahun_ajaran_id=1`

### 2.10 Approve Dokumen Digital
**POST** `/api/pimpinan/dokumen/{id}/approve`

---

## 💰 3. BENDAHARA/ADMIN KEUANGAN API

Base URL: `/api/bendahara`

### 3.1 Dashboard Keuangan
**GET** `/api/bendahara/dashboard`

Response:
```json
{
  "success": true,
  "data": {
    "pemasukan": {
      "tahun_ini": 500000000,
      "bulan_ini": 45000000,
      "hari_ini": 25
    },
    "tunggakan": {
      "total_nominal": 25000000,
      "jumlah_siswa": 15
    },
    "transaksi_per_metode": [
      {
        "metode_pembayaran": "Transfer Bank",
        "jumlah": 150,
        "total": 150000000
      }
    ],
    "grafik_pemasukan": [...],
    "pending_tasks": {
      "diskon_aktif": 5,
      "refund_pending": 2,
      "rekonsiliasi_pending": 1
    }
  }
}
```

### 3.2 Manajemen Tagihan SPP
**GET** `/api/bendahara/tagihan?status=Pending&kelas_id=1&search=Ahmad`

**POST** `/api/bendahara/tagihan`
```json
{
  "siswa_id": 1,
  "tahun_ajaran_id": 1,
  "bulan": "Februari 2026",
  "jumlah_bayar": 500000,
  "tanggal_jatuh_tempo": "2026-02-10"
}
```

**PUT** `/api/bendahara/tagihan/{id}/verify`
```json
{
  "status": "Paid",
  "tanggal_bayar": "2026-02-15",
  "metode_pembayaran": "Transfer Bank",
  "bukti_transfer": "path/to/bukti.jpg"
}
```

### 3.3 Rekap Pembayaran
**GET** `/api/bendahara/rekap-pembayaran?tipe=bulanan&tahun=2026&bulan=2`

### 3.4 Tunggakan & Reminder
**GET** `/api/bendahara/tunggakan?kelas_id=1`

**POST** `/api/bendahara/send-reminder`
```json
{
  "siswa_ids": [1, 2, 3],
  "jenis_reminder": "Tagihan",
  "channel": "WhatsApp"
}
```

### 3.5 Manajemen Diskon/Beasiswa
**GET** `/api/bendahara/diskon-beasiswa?status=Aktif&jenis=Beasiswa`

**POST** `/api/bendahara/diskon-beasiswa`
```json
{
  "siswa_id": 1,
  "jenis": "Beasiswa",
  "nama_program": "Beasiswa Prestasi",
  "tipe_diskon": "Persentase",
  "nilai_diskon": 50,
  "tanggal_mulai": "2026-02-01",
  "tanggal_selesai": "2026-12-31",
  "catatan": "Prestasi akademik"
}
```

### 3.6 Refund Management
**GET** `/api/bendahara/refund?status=Pending`

**PUT** `/api/bendahara/refund/{id}/process`
```json
{
  "status": "Selesai",
  "catatan": "Refund telah ditransfer ke rekening siswa"
}
```

### 3.7 Rekonsiliasi Bank
**GET** `/api/bendahara/rekonsiliasi?status=Pending`

**POST** `/api/bendahara/rekonsiliasi`
```json
{
  "periode_dari": "2026-02-01",
  "periode_sampai": "2026-02-28",
  "total_pembayaran_bank": 45000000,
  "file_bank_statement": "file"
}
```

### 3.8 Financial Forecasting
**GET** `/api/bendahara/forecasting?bulan=6`

Response:
```json
{
  "success": true,
  "data": {
    "rata_rata_per_bulan": 45000000,
    "proyeksi_6_bulan": [
      {
        "bulan": "2026-03",
        "proyeksi_pemasukan": 45000000
      }
    ],
    "tunggakan_aktif": 25000000,
    "potensi_total_pemasukan": 270000000
  }
}
```

### 3.9 Revenue Analytics
**GET** `/api/bendahara/revenue-analytics?tahun=2026`

Response:
```json
{
  "success": true,
  "data": {
    "per_metode": [...],
    "per_kelas": [...],
    "trend_bulanan": [...]
  }
}
```

### 3.10 Generate QR Code Payment
**POST** `/api/bendahara/generate-qr`
```json
{
  "pembayaran_id": 123
}
```

Response:
```json
{
  "success": true,
  "message": "QR Code berhasil dibuat",
  "data": {
    "qr_code_url": "https://api.qrserver.com/v1/create-qr-code/?size=300x300&data=...",
    "pembayaran_id": 123,
    "jumlah": 500000
  }
}
```

---

## 📝 NOTES

### Error Responses
Semua endpoint menggunakan format error response yang konsisten:
```json
{
  "success": false,
  "message": "Error message here",
  "errors": {...}
}
```

### HTTP Status Codes
- `200` - Success
- `201` - Created
- `400` - Bad Request
- `401` - Unauthorized
- `403` - Forbidden
- `404` - Not Found
- `422` - Validation Error
- `500` - Internal Server Error

### Pagination
Endpoint yang mengembalikan list data mendukung pagination dengan parameter:
- `page` - Nomor halaman (default: 1)
- `per_page` - Jumlah data per halaman (default: 50)

### Date Format
Semua tanggal menggunakan format ISO 8601: `YYYY-MM-DD` atau `YYYY-MM-DD HH:mm:ss`

---

## 🔒 ROLE-BASED ACCESS CONTROL

### Roles yang tersedia:
1. `orangtua` - Orang Tua/Wali Siswa
2. `pimpinan` atau `kepala_sekolah` - Kepala Sekolah/Pimpinan
3. `bendahara` atau `admin_keuangan` - Bendahara/Admin Keuangan
4. `guru` - Guru (existing)
5. `siswa` - Siswa (existing)
6. `admin` - Super Admin (existing)

### Middleware:
- `auth:sanctum` - Memverifikasi user sudah login
- `role:{role_name}` - Memverifikasi user memiliki role yang sesuai
