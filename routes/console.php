<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Cek tiket PIC yang belum dikerjakan lebih dari 3 jam — jalankan setiap jam
Schedule::command('app:check-pic-overdue')->hourly()->runInBackground();
