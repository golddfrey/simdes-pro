<?php

require __DIR__ . '/../vendor/autoload.php';

$app = require __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

try {
    $registry = app(\Livewire\Mechanisms\ComponentRegistry::class);
    $name = $registry->getName(App\Http\Livewire\Admin\KepalaList::class);
    echo "Livewire name for App\\Http\\Livewire\\Admin\\KepalaList: ";
    echo $name . PHP_EOL;
} catch (Throwable $e) {
    echo "Error: " . $e->getMessage() . PHP_EOL;
}
