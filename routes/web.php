<?php

use App\Http\Controllers\NoteController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TelegramController;
use Illuminate\Support\Facades\Route;

// Redirect home to login or notes
Route::get('/', function () {
    if (auth()->check()) {
        return redirect()->route('notes.index');
    }
    return redirect()->route('login');
});

// Dashboard redirects to notes
Route::get('/dashboard', function () {
    return redirect()->route('notes.index');
})->middleware(['auth', 'verified'])->name('dashboard');

// Protected routes
Route::middleware('auth')->group(function () {
    // Notes
    Route::resource('notes', NoteController::class)->except(['create', 'edit']);
    Route::patch('/notes/{note}/toggle', [NoteController::class, 'toggleComplete'])->name('notes.toggle');

    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Telegram
    Route::prefix('telegram')->name('telegram.')->group(function () {
        Route::get('/status', [TelegramController::class, 'status'])->name('status');
        Route::post('/generate-code', [TelegramController::class, 'generateCode'])->name('generate-code');
        Route::post('/unlink', [TelegramController::class, 'unlink'])->name('unlink');
        Route::post('/test', [TelegramController::class, 'test'])->name('test');
    });
});



// Webhook para Telegram (Público y sin CSRF)
Route::post('/telegram/webhook', [App\Http\Controllers\TelegramWebhookController::class, 'handle'])
    ->withoutMiddleware([\Illuminate\Foundation\Http\Middleware\ValidateCsrfToken::class]);

// Ruta temporal para migrar DB en Vercel
Route::get('/migrate-db', function () {
    try {
        \Illuminate\Support\Facades\Artisan::call('migrate', ['--force' => true]);
        return 'Migración exitosa: <br>' . nl2br(\Illuminate\Support\Facades\Artisan::output());
    } catch (\Exception $e) {
        return 'Error al migrar: ' . $e->getMessage();
    }
});

// Ruta para ejecutar el scheduler (Cron Job externo)
Route::get('/run-schedule', function () {
    try {
        // Ejecutamos directamente el comando de recordatorios para evitar problemas con subprocessos en Vercel
        \Illuminate\Support\Facades\Artisan::call('notes:send-reminders');
        return 'Resultado: <br>' . nl2br(\Illuminate\Support\Facades\Artisan::output());
    } catch (\Exception $e) {
        return 'Error fatal: ' . $e->getMessage() . '<br>Traza:<br><pre>' . $e->getTraceAsString() . '</pre>';
    }
});

require __DIR__ . '/auth.php';
