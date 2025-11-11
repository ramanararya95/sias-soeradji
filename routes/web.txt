<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ForgotPasswordController;
use App\Http\Controllers\CaptchaController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\ArsipController;
use App\Http\Controllers\ArsipValidationController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\SuratTugasController;
use App\Http\Controllers\LogSuratTugasController;
use App\Http\Controllers\WatermarkController;
use App\Http\Controllers\WatermarkLogController;
use App\Http\Controllers\BeritaAcaraPemindahanController;
use App\Http\Controllers\BeritaAcaraPemusnahanController;
use App\Http\Controllers\BeritaAcaraAlihmediaController;
use App\Http\Controllers\LaporanArsipController;
use App\Http\Controllers\LaporanAktivitasController;
use App\Http\Controllers\Admin\BackgroundController;
use App\Http\Controllers\Admin\PengaturanNomorController;

// Include API v2 routes
require __DIR__.'/api_v2.php';

// -------------------------------------------------
// ROUTE UTAMA (PUBLIC)
// -------------------------------------------------
Route::get('/', function () {
    return redirect()->route('login');
});

// --- Autentikasi ---
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);

// --- Lupa Password ---
Route::get('/lupa-password', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
Route::post('/lupa-password', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
Route::get('/reset-password/{token}', [ForgotPasswordController::class, 'showResetForm'])->name('password.reset');
Route::post('/reset-password', [ForgotPasswordController::class, 'reset'])->name('password.update');

// --- Captcha ---
Route::get('/captcha', [CaptchaController::class, 'generate'])->name('captcha.generate');
Route::get('/captcha/refresh', [CaptchaController::class, 'refresh'])->name('captcha.refresh');

// -------------------------------------------------
// ROUTE YANG MEMBUTUHKAN LOGIN
// -------------------------------------------------
Route::middleware(['auth'])->group(function () {

    // ======================
    // DASHBOARD
    // ======================
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/online-users', [DashboardController::class, 'getOnlineUsers'])->name('dashboard.online-users');
    Route::get('/dashboard/today-activities', [DashboardController::class, 'getTodayActivities'])->name('dashboard.today-activities');

    // ======================
    // PROFIL PENGGUNA
    // ======================
    Route::middleware('auth')->group(function () {
        Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index');
        // Update foto
        Route::post('/profile/photo', [ProfileController::class, 'updatePhoto'])->name('profile.updatePhoto');
        // Update tema & bahasa
        Route::post('/profile/settings', [ProfileController::class, 'updateSettings'])->name('profile.updateSettings');
        // Update password
        Route::post('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.update-password');
    });

    // ======================
    // NOTIFIKASI
    // ======================
    Route::middleware(['auth'])->prefix('notifications')->name('notifications.')->group(function () {
        Route::get('/', [NotificationController::class, 'index'])->name('index');
        Route::get('/{id}', [NotificationController::class, 'show'])->name('show');
        Route::post('/{id}/read', [NotificationController::class, 'markAsRead'])->name('markAsRead');
        Route::post('/mark-all-read', [NotificationController::class, 'markAllAsRead'])->name('markAllAsRead');
        Route::delete('/{id}', [NotificationController::class, 'destroy'])->name('destroy');
    });

    // ======================
    // CRUD ARSIP (1 CONTROLLER)
    // ======================
    Route::prefix('arsip')->name('arsip.')->group(function () {
        Route::get('aktif/create', [ArsipController::class, 'createArsipAktif'])->name('aktif.create');
        Route::post('aktif/store', [ArsipController::class, 'storeArsipAktif'])->name('aktif.store');

        Route::get('inaktif/create', [ArsipController::class, 'createArsipInaktif'])->name('inaktif.create');
        Route::post('inaktif/store', [ArsipController::class, 'storeArsipInaktif'])->name('inaktif.store');

        Route::get('vital/create', [ArsipController::class, 'createArsipVital'])->name('vital.create');
        Route::post('vital/store', [ArsipController::class, 'storeArsipVital'])->name('vital.store');

        Route::get('alihmedia/create', [ArsipController::class, 'createArsipAlihmedia'])->name('alihmedia.create');
        Route::post('alihmedia/store', [ArsipController::class, 'storeArsipAlihmedia'])->name('alihmedia.store');
    });

    // ======================
    // SURAT TUGAS
    // ======================
    Route::prefix('surat-tugas')->group(function () {
        Route::get('/form', [SuratTugasController::class, 'index'])->name('surat_tugas.form');
        Route::get('/search-pegawai', [SuratTugasController::class, 'searchPegawai'])->name('surat_tugas.search_pegawai');
        Route::post('/preview', [SuratTugasController::class, 'preview'])->name('surat_tugas.preview');
        Route::get('/preview-page', [SuratTugasController::class, 'showPreview'])->name('surat_tugas.preview_page');
        Route::get('/generate-word', [SuratTugasController::class, 'generateWord'])->name('surat_tugas.generate_word');
        Route::post('/save', [SuratTugasController::class, 'save'])->name('surat_tugas.save');
    });
    
    // Route untuk form surat tugas >2 orang
    Route::get('/surat-tugas/buat-banyak', [LogSuratTugasController::class, 'createMultiple'])->name('surat_tugas.form_multiple');

    // Route untuk menyimpan data surat tugas >2 orang
    Route::post('/surat-tugas/simpan-banyak', [LogSuratTugasController::class, 'storeMultiple'])->name('surat_tugas.store_multiple');

    // ======================
    // LOG SURAT TUGAS
    // ======================
    Route::get('/log-surat-tugas', [LogSuratTugasController::class, 'index'])->name('log_surat_tugas.index');
    Route::get('/view/{id}', [LogSuratTugasController::class, 'view'])->name('log_surat_tugas.view');
    Route::get('/download/{id}', [LogSuratTugasController::class, 'download'])->name('log_surat_tugas.download');
    Route::post('/generate', [LogSuratTugasController::class, 'generate'])->name('log_surat_tugas.generate');
    Route::post('/update-filename', [LogSuratTugasController::class, 'updateFilename'])->name('log_surat_tugas.update_filename');
    Route::post('/delete', [LogSuratTugasController::class, 'delete'])->name('log_surat_tugas.delete');

    // ======================
    // BERITA ACARA PEMINDAHAN
    // ======================
    Route::middleware(['auth'])->prefix('berita-acara/pemindahan')->name('berita_acara.pemindahan.')->group(function () {
        Route::get('/', [BeritaAcaraPemindahanController::class, 'index'])->name('index');
        Route::get('/create', [BeritaAcaraPemindahanController::class, 'create'])->name('create');
        Route::post('/', [BeritaAcaraPemindahanController::class, 'store'])->name('store');
        Route::get('/{id}', [BeritaAcaraPemindahanController::class, 'show'])->name('show');
        Route::get('/{id}/edit', [BeritaAcaraPemindahanController::class, 'edit'])->name('edit');
        Route::put('/{id}', [BeritaAcaraPemindahanController::class, 'update'])->name('update');
        Route::delete('/{id}', [BeritaAcaraPemindahanController::class, 'destroy'])->name('destroy');
    });

    // ======================
    // BERITA ACARA PEMUSNAHAN
    // ======================
    Route::middleware(['auth'])->prefix('berita-acara/pemusnahan')->name('berita_acara.pemusnahan.')->group(function () {
        Route::get('/', [BeritaAcaraPemusnahanController::class, 'index'])->name('index');
        Route::get('/create', [BeritaAcaraPemusnahanController::class, 'create'])->name('create');
        Route::post('/', [BeritaAcaraPemusnahanController::class, 'store'])->name('store');
        Route::get('/{id}', [BeritaAcaraPemusnahanController::class, 'show'])->name('show');
        Route::get('/{id}/edit', [BeritaAcaraPemusnahanController::class, 'edit'])->name('edit');
        Route::put('/{id}', [BeritaAcaraPemusnahanController::class, 'update'])->name('update');
        Route::delete('/{id}', [BeritaAcaraPemusnahanController::class, 'destroy'])->name('destroy');
    });

    // ======================
    // BERITA ACARA ALIH MEDIA
    // ======================
    Route::middleware(['auth'])->prefix('berita-acara/alihmedia')->name('berita_acara.alihmedia.')->group(function () {
        Route::get('/', [BeritaAcaraAlihmediaController::class, 'index'])->name('index');
        Route::get('/create', [BeritaAcaraAlihmediaController::class, 'create'])->name('create');
        Route::post('/', [BeritaAcaraAlihmediaController::class, 'store'])->name('store');
        Route::get('/{id}', [BeritaAcaraAlihmediaController::class, 'show'])->name('show');
        Route::get('/{id}/edit', [BeritaAcaraAlihmediaController::class, 'edit'])->name('edit');
        Route::put('/{id}', [BeritaAcaraAlihmediaController::class, 'update'])->name('update');
        Route::delete('/{id}', [BeritaAcaraAlihmediaController::class, 'destroy'])->name('destroy');
    });

    // ======================
    // WATERMARK
    // ======================
    Route::prefix('watermark')->name('watermark.')->middleware('auth')->group(function () {
        // Image Watermark
        Route::get('/image', [WatermarkController::class, 'imageIndex'])->name('image.index');
        Route::post('/image/process', [WatermarkController::class, 'processImage'])->name('image.process');
        Route::post('/image/save-preview', [WatermarkController::class, 'saveImagePreview'])->name('image.save-preview');
        
        // Text Watermark
        Route::get('/text', [WatermarkController::class, 'textIndex'])->name('text.index');
        Route::post('/text/process', [WatermarkController::class, 'processText'])->name('text.process');
        
        // Watermark Logs
        Route::get('/logs', [WatermarkLogController::class, 'index'])->name('logs.index');
        Route::delete('/logs/{id}', [WatermarkLogController::class, 'destroy'])->name('logs.destroy');
    });

    // ======================
    // LAPORAN ARSIP
    // ======================
    Route::middleware(['auth'])->prefix('laporan/arsip')->name('laporan.arsip.')->group(function () {
        Route::get('/', [LaporanArsipController::class, 'index'])->name('index');
        Route::post('/filter', [LaporanArsipController::class, 'filter'])->name('filter');
        Route::get('/pdf', [LaporanArsipController::class, 'pdf'])->name('pdf');
        Route::get('/excel', [LaporanArsipController::class, 'excel'])->name('excel');
    });

    // ======================
    // LAPORAN AKTIVITAS
    // ======================
    Route::middleware(['auth'])->prefix('laporan/aktivitas')->name('laporan.aktivitas.')->group(function () {
        Route::get('/', [LaporanAktivitasController::class, 'index'])->name('index');
        Route::post('/filter', [LaporanAktivitasController::class, 'filter'])->name('filter');
        Route::get('/pdf', [LaporanAktivitasController::class, 'pdf'])->name('pdf');
        Route::get('/excel', [LaporanAktivitasController::class, 'excel'])->name('excel');
    });

    // ======================
    // ADMIN AREA
    // ======================
    Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
        // === User Management ===
        Route::get('/users', [UserController::class, 'index'])->name('users.index');
        Route::post('/users', [UserController::class, 'store'])->name('users.store');
        Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
        Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');
        Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');
        Route::post('/users/{user}/toggle-status', [UserController::class, 'toggleStatus'])->name('users.toggle-status');
        Route::post('/users/{user}/duplicate', [UserController::class, 'duplicate'])->name('users.duplicate');

        // === Pengaturan ===
        Route::get('/pengaturan/background', [BackgroundController::class, 'index'])->name('pengaturan.background');
        Route::post('/pengaturan/background', [BackgroundController::class, 'update'])->name('pengaturan.background.update');

        Route::get('/pengaturan/nomor_arsip', [PengaturanNomorController::class, 'index'])->name('pengaturan.nomor');
        Route::post('/pengaturan/nomor_arsip', [PengaturanNomorController::class, 'update'])->name('pengaturan.nomor.update');
    });

    // ======================
    // API VALIDASI NOMOR ARSIP (AJAX)
    // ======================
    Route::prefix('api')->name('api.')->group(function () {
        Route::post('/cek_nomor_arsip_aktif', [ArsipValidationController::class, 'checkNomorArsipAktif'])->name('cek_nomor_arsip_aktif');
        Route::post('/cek_nomor_arsip_inaktif', [ArsipValidationController::class, 'checkNomorArsipInaktif'])->name('cek_nomor_arsip_inaktif');
        Route::post('/cek_nomor_arsip_vital', [ArsipValidationController::class, 'checkNomorArsipVital'])->name('cek_nomor_arsip_vital');
        Route::post('/cek_nomor_arsip_alihmedia', [ArsipValidationController::class, 'checkNomorArsipAlihmedia'])->name('cek_nomor_arsip_alihmedia');
        Route::post('/cek_nomor_arsip', [ArsipValidationController::class, 'checkNomorArsipUniversal'])->name('cek_nomor_arsip');
    });

    // ======================
    // LOGOUT
    // ======================
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});

// ======================
// API ROUTES
// ======================

Route::middleware('auth:sanctum')->prefix('api')->group(function () {

    // -------------------------------------------
    // 📊 Dashboard API Routes
    // -------------------------------------------
    Route::prefix('dashboard')->name('api.dashboard.')->group(function () {
        Route::get('/stats', [DashboardController::class, 'getStatsApi']);
        Route::get('/activities', [DashboardController::class, 'getActivitiesApi']);
        Route::get('/online-users', [DashboardController::class, 'getOnlineUsersApi']);
        Route::get('/recent-archives', [DashboardController::class, 'getRecentArchivesApi']);
    });

    // -------------------------------------------
    // 🔔 Notifications
    // -------------------------------------------
    Route::prefix('notifications')->group(function () {
        Route::get('/unread', [NotificationController::class, 'getUnread']);
        Route::post('/{id}/read', [NotificationController::class, 'markAsRead']);
        Route::post('/read-all', [NotificationController::class, 'markAllAsRead']);
    });

    // -------------------------------------------
    // 👥 Online Users
    // -------------------------------------------
    Route::get('/users/online', [UserController::class, 'getOnlineUsers']);

    // -------------------------------------------
    // 🕒 Activities
    // -------------------------------------------
    Route::prefix('activities')->group(function () {
        Route::get('/today', [ActivityController::class, 'getTodayActivities']);
    });

    // -------------------------------------------
    // 💬 Chat
    // -------------------------------------------
    Route::prefix('chat')->group(function () {
        Route::get('/{userId}', [ChatController::class, 'getChatMessages']);
        Route::post('/send', [ChatController::class, 'sendMessage']);
    });
});
