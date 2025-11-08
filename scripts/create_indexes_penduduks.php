<?php
// One-off script: create indexes on penduduks for development SQLite DB.
// Run with: php scripts/create_indexes_penduduks.php

require __DIR__ . '/../vendor/autoload.php';

$app = require __DIR__ . '/../bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

try {
    DB::statement("CREATE INDEX IF NOT EXISTS idx_penduduks_nama ON penduduks (nama);");
    echo "Created (or exists) idx_penduduks_nama\n";
} catch (Throwable $e) {
    echo "Failed to create idx_penduduks_nama: " . $e->getMessage() . "\n";
}

try {
    DB::statement("CREATE INDEX IF NOT EXISTS idx_penduduks_nik ON penduduks (nik);");
    echo "Created (or exists) idx_penduduks_nik\n";
} catch (Throwable $e) {
    echo "Failed to create idx_penduduks_nik: " . $e->getMessage() . "\n";
}

