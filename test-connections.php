<?php

use Illuminate\Support\Facades\DB;

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "Testing Database Connections:\n";
echo str_repeat('=', 50) . "\n\n";

// Test mysql_admin
try {
    DB::connection('mysql_admin')->getPdo();
    echo "✅ mysql_admin connection: OK\n";
    $count = DB::connection('mysql_admin')->table('users')->count();
    echo "   Users count: $count\n";
} catch (Exception $e) {
    echo "❌ mysql_admin connection: FAILED\n";
    echo "   Error: " . $e->getMessage() . "\n";
}

echo "\n";

// Test mysql_guru
try {
    DB::connection('mysql_guru')->getPdo();
    echo "✅ mysql_guru connection: OK\n";
    $count = DB::connection('mysql_guru')->table('guru')->count();
    echo "   Guru count: $count\n";
} catch (Exception $e) {
    echo "❌ mysql_guru connection: FAILED\n";
    echo "   Error: " . $e->getMessage() . "\n";
}

echo "\n";

// Test mysql_siswa
try {
    DB::connection('mysql_siswa')->getPdo();
    echo "✅ mysql_siswa connection: OK\n";
    $count = DB::connection('mysql_siswa')->table('siswa')->count();
    echo "   Siswa count: $count\n";
} catch (Exception $e) {
    echo "❌ mysql_siswa connection: FAILED\n";
    echo "   Error: " . $e->getMessage() . "\n";
}

echo "\n" . str_repeat('=', 50) . "\n";
