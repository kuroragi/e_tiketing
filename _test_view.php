<?php
define('LARAVEL_START', microtime(true));
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();
try {
    $view = view('pages.admin.skpd', [
        'departments' => new \Illuminate\Pagination\LengthAwarePaginator(collect(), 0, 20),
        'petugasList' => collect(),
    ]);
    $rendered = $view->render();
    echo 'OK - Length: ' . strlen($rendered) . PHP_EOL;
} catch (\Throwable $e) {
    echo 'ERROR: ' . $e->getMessage() . PHP_EOL;
    echo 'File: ' . $e->getFile() . ':' . $e->getLine() . PHP_EOL;
}
