<?php
require __DIR__ . '/../vendor/autoload.php';

$app = require __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Http\Livewire\Admin\KepalaList;

$component = new KepalaList();
$html = $component->render()->render();

file_put_contents(__DIR__ . '/kepala_render.html', $html);
echo "Wrote kepala_render.html\n";
