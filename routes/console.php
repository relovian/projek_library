<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

// Bawaan Laravel — jangan dihapus
Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// ← Tambahkan ini di bawah
Schedule::command('trash:bersihkan --hari=7')->dailyAt('00:00');