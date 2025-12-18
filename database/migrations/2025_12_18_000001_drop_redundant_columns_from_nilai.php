<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * Menghapus kolom nilai_akhir dan nilai_huruf dari tabel nilai
     * karena merupakan data redundan yang bisa dihitung menggunakan function.
     * 
     * Alasan:
     * - nilai_akhir = (30% * nilai_tugas) + (30% * nilai_uts) + (40% * nilai_uas)
     * - nilai_huruf = konversi dari nilai_akhir (A/B/C/D/E)
     * 
     * Keduanya adalah derived/computed values yang tidak seharusnya disimpan di database
     * sesuai prinsip normalisasi database (MSBD).
     */
    public function up(): void
    {
        Schema::table('nilai', function (Blueprint $table) {
            // Drop kolom redundan
            $table->dropColumn(['nilai_akhir', 'nilai_huruf']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('nilai', function (Blueprint $table) {
            // Restore kolom jika rollback
            $table->decimal('nilai_akhir', 5)->nullable()->after('nilai_uas');
            $table->char('nilai_huruf', 1)->nullable()->comment('Nilai huruf A-E, auto-calculated dari nilai_akhir')->after('nilai_akhir');
        });
    }
};
