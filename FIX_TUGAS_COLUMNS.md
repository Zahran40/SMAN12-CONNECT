# Fix Tugas Column Migration - Complete ✅

## Problem
Tugas (assignments) were not displaying time correctly and showed "Belum Dibuka" status incorrectly because:
- Database schema uses `waktu_dibuka` (datetime), `waktu_ditutup` (datetime), `deskripsi` (text)
- Views and some code were trying to access old columns: `tanggal_dibuka`, `jam_buka`, `jam_tutup`, `deskripsi_tugas`

## Database Schema (Correct)
```sql
-- tugas table columns
id_tugas
jadwal_id
pertemuan_id
semester
judul_tugas
deskripsi          -- NOT deskripsi_tugas
file_path
waktu_dibuka       -- datetime, NOT tanggal_dibuka + jam_buka
waktu_ditutup      -- datetime, NOT tanggal_ditutup + jam_tutup
deadline
```

## Changes Made

### 1. Model - `app/Models/Tugas.php` ✅
- Updated `$fillable` to use: `deskripsi`, `waktu_dibuka`, `waktu_ditutup`
- Added backward compatibility accessors:
  - `getDeskripsiTugasAttribute()` - returns `$this->deskripsi`
  - `setDeskripsiTugasAttribute()` - sets `$this->deskripsi`

### 2. Controller - `app/Http/Controllers/Guru/MateriController.php` ✅
**storeTugas()** method (lines ~475):
```php
'deskripsi' => $validated['deskripsi'],
'waktu_dibuka' => $tanggalDibuka . ' ' . $validated['jam_buka'],
'waktu_ditutup' => $tanggalDitutup . ' ' . $validated['jam_tutup'],
```

**updateTugas()** method (lines ~615):
```php
$tugas->deskripsi = $validated['deskripsi'];
$tugas->waktu_dibuka = $validated['tanggal_dibuka'] . ' ' . $validated['jam_buka'];
$tugas->waktu_ditutup = $validated['tanggal_ditutup'] . ' ' . $validated['jam_tutup'];
```

### 3. Views Fixed

#### `resources/views/siswa/detailMateri.blade.php` ✅
**Before:**
```php
$tanggalDibuka = \Carbon\Carbon::parse($t->tanggal_dibuka)->startOfDay();
$jamBukaParts = explode(':', $t->jam_buka);
$waktuBuka->setTime((int)$jamBukaParts[0], (int)$jamBukaParts[1]);
```

**After:**
```php
// Parse datetime langsung dari kolom waktu_dibuka dan waktu_ditutup
$waktuBuka = $t->waktu_dibuka ? \Carbon\Carbon::parse($t->waktu_dibuka) : now();
$waktuTutup = $t->waktu_ditutup ? \Carbon\Carbon::parse($t->waktu_ditutup) : now();
```

**Display time:**
```blade
<span><strong>Dibuka:</strong> {{ $waktuBuka->translatedFormat('l, d F Y') }} - {{ $waktuBuka->format('H:i') }}</span>
<span><strong>Ditutup:</strong> {{ $waktuTutup->translatedFormat('l, d F Y') }} - {{ $waktuTutup->format('H:i') }}</span>
```

**Late submission check:**
```php
$batasWaktu = \Carbon\Carbon::parse($t->waktu_ditutup);
$isLate = $tglKumpul->greaterThan($batasWaktu);
```

**Deskripsi field:**
```blade
{{ $t->deskripsi ?? '-' }}
```

#### `resources/views/Guru/editTugas.blade.php` ✅
**Extract date/time from waktu_dibuka/waktu_ditutup:**
```php
$waktuDibuka = $tugas->waktu_dibuka ? \Carbon\Carbon::parse($tugas->waktu_dibuka) : now();
$waktuDitutup = $tugas->waktu_ditutup ? \Carbon\Carbon::parse($tugas->waktu_ditutup) : now();

$tanggalDibuka = $waktuDibuka->format('Y-m-d');
$tanggalDitutup = $waktuDitutup->format('Y-m-d');
$jamBuka = $waktuDibuka->format('H:i');
$jamTutup = $waktuDitutup->format('H:i');
```

**Deskripsi textarea:**
```blade
{{ old('deskripsi', $tugas->deskripsi) }}
```

#### `resources/views/Guru/detailMateri.blade.php` ✅
**Display time:**
```blade
Waktu: {{ \Carbon\Carbon::parse($t->waktu_dibuka)->format('H:i') }} - {{ \Carbon\Carbon::parse($t->waktu_ditutup)->format('H:i') }}
```

**Deskripsi field:**
```blade
{{ $t->deskripsi }}
```

#### `resources/views/Guru/detailTugas.blade.php` ✅
**Deskripsi check and display:**
```blade
@if($tugas->deskripsi)
    <p class="text-sm text-slate-500 whitespace-pre-wrap">{{ $tugas->deskripsi }}</p>
@endif
```

## Form Inputs (No Change Required)
Forms still send separate fields which controller combines:
```html
<input type="date" name="tanggal_dibuka">
<input type="time" name="jam_buka">
<input type="date" name="tanggal_ditutup">
<input type="time" name="jam_tutup">
```

Controller combines them:
```php
'waktu_dibuka' => $validated['tanggal_dibuka'] . ' ' . $validated['jam_buka']
```

## Testing Checklist
- [x] Model uses correct column names
- [x] Controller storeTugas() creates correct datetime
- [x] Controller updateTugas() updates correct datetime
- [x] Siswa view displays time correctly (e.g., "08:00" not "-")
- [x] Siswa view shows correct status (Terbuka/Belum Dibuka/Sudah Ditutup)
- [x] Guru edit form loads existing time correctly
- [x] Guru detail view shows time correctly
- [x] Late submission detection works correctly
- [x] Deskripsi displays correctly in all views

## Result
✅ Tugas now displays: "Monday, 01 December 2025 - 08:00" instead of "Monday, 01 December 2025 -"
✅ Status shows "Terbuka" when waktu_dibuka ≤ now ≤ waktu_ditutup
✅ Status shows "Belum Dibuka" when now < waktu_dibuka
✅ Status shows "Sudah Ditutup" when now > waktu_ditutup
✅ All deskripsi fields display correctly

## Database Migration Status
**No migration needed** - Database already has correct columns:
- `waktu_dibuka` (datetime)
- `waktu_ditutup` (datetime)  
- `deskripsi` (text)

Old columns (`tanggal_dibuka`, `jam_buka`, `jam_tutup`, `deskripsi_tugas`) never existed in production.

## Files Modified
1. `app/Models/Tugas.php` - Updated fillable, added accessors
2. `app/Http/Controllers/Guru/MateriController.php` - Fixed storeTugas() and updateTugas()
3. `resources/views/siswa/detailMateri.blade.php` - Fixed time display and status logic
4. `resources/views/Guru/editTugas.blade.php` - Fixed datetime extraction
5. `resources/views/Guru/detailMateri.blade.php` - Fixed time display
6. `resources/views/Guru/detailTugas.blade.php` - Fixed deskripsi display
