<?php
require __DIR__ . '/../vendor/autoload.php';

$app = require __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$html = view('admin.kepala.create')->render();
file_put_contents(__DIR__ . '/create_render.html', $html);
echo "Wrote create_render.html\n";
