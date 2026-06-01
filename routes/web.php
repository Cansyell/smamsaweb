<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\WelcomeController;

// Admin Controllers
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Admin\StudentController;
use App\Http\Controllers\Admin\CriteriaController;
use App\Http\Controllers\Admin\AhpMatrixController;
use App\Http\Controllers\Admin\SpecializationQuotaController;
use App\Http\Controllers\Admin\AcademicYearController;
use App\Http\Controllers\Admin\AhpResultController;
use App\Http\Controllers\Admin\MonitoringController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\ReportGradeController as AdminReportGradeController;
use App\Http\Controllers\AnnouncementController;
use App\Http\Controllers\Admin\GaleriController;
use App\Http\Controllers\Admin\HeroSectionController;
use App\Http\Controllers\Admin\JadwalPpdbController;
use App\Http\Controllers\Admin\WelcomeSettingController;


// Panitia Controllers
use App\Http\Controllers\Committee\CommitteeDashboardController;
use App\Http\Controllers\Committee\TestScoreController;
use App\Http\Controllers\Committee\ValidationController;
use App\Http\Controllers\Committee\CriterionValueController;
use App\Http\Controllers\Committee\SawResultController;
use App\Http\Controllers\Committee\CommitteeStudentController;
use App\Http\Controllers\Committee\SelectionResultController;
use App\Http\Controllers\Committee\PublishResultController;


// Student Controllers
use App\Http\Controllers\Student\ProfileController as StudentProfileController;
use App\Http\Controllers\Student\ReportGradeController;
use App\Http\Controllers\Student\DocumentController;
use App\Http\Controllers\Student\StudentDashboardController;
use App\Http\Controllers\Student\SpecializationController;
use App\Http\Controllers\Student\ResultController;
use App\Http\Controllers\Student\ResubmissionController;


// ============================================================
// PUBLIC ROUTES
// ============================================================
Route::get('/', [WelcomeController::class, 'index'])->name('home');



// ============================================================
// AUTHENTICATED ROUTES - Redirect to appropriate dashboard
// ============================================================
Route::middleware(['auth', 'verified'])->group(function () {
    // Auto redirect based on role
    Route::get('/dashboard', function () {
        $role = auth()->user()->role;
        
        switch ($role) {
            case 'admin':
                return redirect()->route('dashboard');
            case 'committee':
                return redirect()->route('committee.dashboard');
            case 'student':
                return redirect()->route('student.dashboard');
            default:
                return redirect()->route('login');
        }
    })->name('home');
});

// ============================================================
// ADMIN ROUTES - Hanya untuk role 'admin'
// ============================================================
Route::middleware(['auth', 'role:admin'])->group(function () {
    
    //monitoring
    Route::get('/admin/monitoring', [MonitoringController::class, 'index'])->name('admin.monitoring');

    // Dashboard Admin
    Route::get('/admin/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Profile Admin
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    // Group Admin dengan prefix dan name
    Route::prefix('admin')->name('admin.')->group(function () {
        
        /* =====================================================
         | Data Siswa
         ===================================================== */
        Route::prefix('students')->name('students.')->group(function () {
            Route::get('/', [StudentController::class, 'index'])->name('index');
            Route::get('/create', [StudentController::class, 'create'])->name('create');
            Route::post('/', [StudentController::class, 'store'])->name('store');
            Route::get('/{student}', [StudentController::class, 'show'])->name('show');
            Route::get('/{student}/edit', [StudentController::class, 'edit'])->name('edit');
            Route::put('/{student}', [StudentController::class, 'update'])->name('update');
            Route::delete('/{student}', [StudentController::class, 'destroy'])->name('destroy');
            Route::get('students/export', [StudentController::class, 'export'])->name('export');
        });
        
        /* =====================================================
         | Kriteria Penilaian
         ===================================================== */
        Route::resource('criterias', CriteriaController::class);
        
        // Route tambahan untuk Criteria
        Route::patch('criterias/{criteria}/toggle-status', [CriteriaController::class, 'toggleStatus'])
            ->name('criterias.toggle-status');
        
        Route::post('criterias/{specialization}/reorder', [CriteriaController::class, 'reorder'])
            ->name('criterias.reorder')
            ->whereIn('specialization', ['tahfiz', 'language']);

        /* =====================================================
         | AHP Matrix (Perhitungan Kriteria)
         ===================================================== */
        Route::resource('ahp-matrices', AhpMatrixController::class)->only(['index', 'store', 'show']);
        Route::post('ahp-matrices/calculate-weights', [AhpMatrixController::class, 'calculateWeights'])
            ->name('ahp-matrices.calculate-weights');
        Route::delete('ahp-matrices/reset', [AhpMatrixController::class, 'reset'])
            ->name('ahp-matrices.reset');

        /* =====================================================
         | AHP Results (Hasil Perhitungan)
         ===================================================== */
        Route::get('ahp-results', [AhpResultController::class, 'index'])
            ->name('ahp-results.index');

        /* =====================================================
         | Academic Years (Tahun Ajaran)
         ===================================================== */
        Route::resource('academic-years', AcademicYearController::class);
        Route::patch('academic-years/{academicYear}/toggle-active', [AcademicYearController::class, 'toggleActive'])
            ->name('academic-years.toggle-active');
        
        /* =====================================================
         | Specialization Quotas (Kuota Peminatan)
         ===================================================== */
        Route::resource('specialization-quotas', SpecializationQuotaController::class);
        Route::patch('specialization-quotas/{specializationQuota}/toggle-active', 
            [SpecializationQuotaController::class, 'toggleActive'])
            ->name('specialization-quotas.toggle-active');
        
        /* =====================================================
         | Announcements (Pengumuman)
         ===================================================== */
        Route::resource('announcements', AnnouncementController::class);
        
        // Additional announcement routes
        Route::patch('announcements/{announcement}/toggle-status', [AnnouncementController::class, 'toggleStatus'])
            ->name('announcements.toggle-status');
        
        Route::patch('announcements/{announcement}/publish', [AnnouncementController::class, 'publish'])
            ->name('announcements.publish');
        
        Route::patch('announcements/{announcement}/unpublish', [AnnouncementController::class, 'unpublish'])
            ->name('announcements.unpublish');
        
        Route::delete('announcements/{announcement}/delete-image', [AnnouncementController::class, 'deleteImage'])
            ->name('announcements.delete-image');
        
        Route::delete('announcements/{announcement}/delete-file', [AnnouncementController::class, 'deleteFile'])
            ->name('announcements.delete-file');

        /* ===== NILAI RAPOR ===== */
        Route::get('report-grades',             [AdminReportGradeController::class, 'index'])  ->name('report-grades.index');
        Route::get('report-grades/export',      [AdminReportGradeController::class, 'export']) ->name('report-grades.export');
        Route::get('report-grades/{student}',   [AdminReportGradeController::class, 'show'])   ->name('report-grades.show');
        Route::get('report-grades/{student}/edit', [AdminReportGradeController::class, 'edit'])->name('report-grades.edit');
        Route::put('report-grades/{student}',   [AdminReportGradeController::class, 'update']) ->name('report-grades.update');
        Route::delete('report-grades/{student}',[AdminReportGradeController::class, 'destroy'])->name('report-grades.destroy');
    
        //kelolapengguna
        Route::resource('users', UserController::class);

        // ── Welcome Setting (halaman utama tab) ────────────────
        Route::get('welcome/settings', [WelcomeSettingController::class, 'index']) ->name('welcome.setting');
       
        // ── HERO SECTION ──────────────────────────────────────────────
        Route::get   ('hero',                  [HeroSectionController::class, 'index'])        ->name('hero.index');
        Route::get   ('hero/create',           [HeroSectionController::class, 'create'])       ->name('hero.create');
        Route::post  ('hero',                  [HeroSectionController::class, 'store'])        ->name('hero.store');
        Route::get   ('hero/{hero}/edit',      [HeroSectionController::class, 'edit'])         ->name('hero.edit');
        Route::put   ('hero/{hero}',           [HeroSectionController::class, 'update'])       ->name('hero.update');
        Route::delete('hero/{hero}',           [HeroSectionController::class, 'destroy'])      ->name('hero.destroy');
        Route::patch ('hero/{hero}/toggle',    [HeroSectionController::class, 'toggleActive']) ->name('hero.toggle');
    
        // ── GALERI ────────────────────────────────────────────────────
        Route::get   ('galeri',                  [GaleriController::class, 'index'])        ->name('galeri.index');
        Route::get   ('galeri/create',           [GaleriController::class, 'create'])       ->name('galeri.create');
        Route::post  ('galeri',                  [GaleriController::class, 'store'])        ->name('galeri.store');
        Route::get   ('galeri/{galeri}/edit',    [GaleriController::class, 'edit'])         ->name('galeri.edit');
        Route::put   ('galeri/{galeri}',         [GaleriController::class, 'update'])       ->name('galeri.update');
        Route::delete('galeri/{galeri}',         [GaleriController::class, 'destroy'])      ->name('galeri.destroy');
        Route::patch ('galeri/{galeri}/toggle',  [GaleriController::class, 'toggleActive']) ->name('galeri.toggle');
        Route::post  ('galeri/urutan',           [GaleriController::class, 'updateUrutan']) ->name('galeri.urutan');  // AJAX
    
        // ── PPDB (Jadwal, Setting, Biaya, Persyaratan) ────────────────
        Route::get('ppdb', [JadwalPpdbController::class, 'index'])->name('ppdb.index');
    
        // Jadwal
        Route::get   ('ppdb/jadwal/create',           [JadwalPpdbController::class, 'createJadwal'])  ->name('ppdb.jadwal.create');
        Route::post  ('ppdb/jadwal',                  [JadwalPpdbController::class, 'storeJadwal'])   ->name('ppdb.jadwal.store');
        Route::get   ('ppdb/jadwal/{jadwal}/edit',    [JadwalPpdbController::class, 'editJadwal'])    ->name('ppdb.jadwal.edit');
        Route::put   ('ppdb/jadwal/{jadwal}',         [JadwalPpdbController::class, 'updateJadwal'])  ->name('ppdb.jadwal.update');
        Route::delete('ppdb/jadwal/{jadwal}',         [JadwalPpdbController::class, 'destroyJadwal']) ->name('ppdb.jadwal.destroy');
        Route::post  ('ppdb/jadwal/sync-status',      [JadwalPpdbController::class, 'syncStatusJadwal'])->name('ppdb.jadwal.sync');
    
        // Setting
        Route::get   ('ppdb/setting/create',          [JadwalPpdbController::class, 'createSetting']) ->name('ppdb.setting.create');
        Route::post  ('ppdb/setting',                 [JadwalPpdbController::class, 'storeSetting'])  ->name('ppdb.setting.store');
        Route::get   ('ppdb/setting/{setting}/edit',  [JadwalPpdbController::class, 'editSetting'])   ->name('ppdb.setting.edit');
        Route::put   ('ppdb/setting/{setting}',       [JadwalPpdbController::class, 'updateSetting']) ->name('ppdb.setting.update');
    
        // Biaya
        Route::post  ('ppdb/biaya',            [JadwalPpdbController::class, 'storeBiaya'])   ->name('ppdb.biaya.store');
        Route::put   ('ppdb/biaya/{biaya}',    [JadwalPpdbController::class, 'updateBiaya'])  ->name('ppdb.biaya.update');
        Route::delete('ppdb/biaya/{biaya}',    [JadwalPpdbController::class, 'destroyBiaya']) ->name('ppdb.biaya.destroy');
    
        // Persyaratan
        Route::post  ('ppdb/persyaratan',                 [JadwalPpdbController::class, 'storePersyaratan'])   ->name('ppdb.persyaratan.store');
        Route::put   ('ppdb/persyaratan/{persyaratan}',   [JadwalPpdbController::class, 'updatePersyaratan'])  ->name('ppdb.persyaratan.update');
        Route::delete('ppdb/persyaratan/{persyaratan}',   [JadwalPpdbController::class, 'destroyPersyaratan']) ->name('ppdb.persyaratan.destroy');
        });

});

// ============================================================
// PANITIA ROUTES - Hanya untuk role 'committee'
// ============================================================
Route::middleware(['auth', 'role:committee'])
    ->prefix('committee')
    ->name('committee.')
    ->group(function () {

    /* =====================================================
     | Dashboard
     ===================================================== */
    Route::get('/dashboard', [CommitteeDashboardController::class, 'index'])
        ->name('dashboard');


    /* =====================================================
     | Profile
     ===================================================== */
    Route::get('/profile', [ProfileController::class, 'edit'])
        ->name('profile');

    Route::patch('/profile', [ProfileController::class, 'update'])
        ->name('profile.update');


    Route::prefix('validation')->name('validation.')->group(function () {
        Route::get('/',                           [ValidationController::class, 'index'])             ->name('index');
        Route::get('/{student}',                  [ValidationController::class, 'show'])              ->name('show');
        Route::post('/{student}/approve',         [ValidationController::class, 'approve'])           ->name('approve');
        Route::post('/{student}/reject',          [ValidationController::class, 'reject'])            ->name('reject');
        Route::post('/documents/{document}/validate', [ValidationController::class, 'validateDocument']) ->name('document.validate');
        Route::post('/batch-approve',             [ValidationController::class, 'batchApprove'])      ->name('batch-approve');
        Route::get('/{student}/completeness',     [ValidationController::class, 'checkCompleteness']) ->name('completeness');
    });

    /* =====================================================
     | Criterion Values (Input & Manajemen Nilai SAW)
     ===================================================== */
    Route::prefix('criterion-values')->name('criterion-values.')->group(function () {

        // List siswa
        Route::get('/', [CriterionValueController::class, 'index'])->name('index');

        // Input per siswa
        Route::get('/create/{student}', [CriterionValueController::class, 'create'])->name('create');
        Route::post('/store/{student}', [CriterionValueController::class, 'store'])->name('store');

        // Detail nilai siswa
        Route::get('/show/{student}', [CriterionValueController::class, 'show'])->name('show');

        // Sync nilai rapor → kriteria
        Route::post('/sync-report-grade/{student}', [CriterionValueController::class, 'syncFromReportGrade'])
            ->name('sync-report-grade');

        // Batch sync semua siswa
        Route::post('/batch-sync-report-grade', [CriterionValueController::class, 'batchSyncReportGrades'])
            ->name('batch-sync-report-grade');

        // Hitung SAW (Tahfiz & Language)
        Route::post('/calculate-saw', [CriterionValueController::class, 'calculateSaw'])
            ->name('calculate-saw');

        // Tentukan status penerimaan
        Route::post('/determine-acceptance', [CriterionValueController::class, 'determineAcceptance'])
            ->name('determine-acceptance');
    });


    /* =====================================================
     | SAW Results (Ranking & Hasil Akhir)
     ===================================================== */
    Route::prefix('saw-results')->name('saw-results.')->group(function () {
        Route::get('/', [SawResultController::class, 'index'])->name('index');
        Route::get('/{student}', [SawResultController::class, 'show'])->name('show');
    });

    Route::prefix('students')->name('students.')->group(function () {
            Route::get('/', [CommitteeStudentController::class, 'index'])->name('index');
            Route::get('/create', [CommitteeStudentController::class, 'create'])->name('create');
            Route::post('/', [CommitteeStudentController::class, 'store'])->name('store');
            Route::get('/{student}', [CommitteeStudentController::class, 'show'])->name('show');
            Route::get('/{student}/edit', [CommitteeStudentController::class, 'edit'])->name('edit');
            Route::put('/{student}', [CommitteeStudentController::class, 'update'])->name('update');
            Route::delete('/{student}', [CommitteeStudentController::class, 'destroy'])->name('destroy');
            Route::get('students/export', [CommitteeStudentController::class, 'export'])->name('export');
        });
    /* =====================================================
    | Publish Result (Pengumuman Hasil Seleksi)
    ===================================================== */
    Route::prefix('publish-result')->name('publish-result.')->group(function () {

        // Halaman preview sebelum publish
        Route::get('/preview', [PublishResultController::class, 'preview'])
            ->name('preview');

        // Set status ke reviewing
        Route::post('/set-reviewing', [PublishResultController::class, 'setReviewing'])
            ->name('set-reviewing');

        // Publish hasil
        Route::post('/publish', [PublishResultController::class, 'publish'])
            ->name('publish');

        // Unpublish (tarik kembali)
        Route::post('/unpublish', [PublishResultController::class, 'unpublish'])
            ->name('unpublish');
    });
    
     /* =====================================================
     | Announcements (View Only)
     ===================================================== */
    Route::get('announcements/{announcement}', [AnnouncementController::class, 'show'])
        ->name('announcements.show');

    // SELECTION-RESULT
    Route::get('selection-results', [SelectionResultController::class, 'index'])
    ->name('selection-results.index');

    Route::get('selection-results/export-pdf', [SelectionResultController::class, 'exportPdf'])
    ->name('selection-results.export-pdf');
});


// ============================================================
// SISWA ROUTES - Hanya untuk role 'student'
// ============================================================
Route::middleware(['auth', 'role:student'])->prefix('student')->name('student.')->group(function () {
    
    /* =====================================================
     | Dashboard
     ===================================================== */
    Route::get('/dashboard', [StudentDashboardController::class, 'index'])->name('dashboard');
    
    /* =====================================================
     | Profile
     ===================================================== */
    Route::get('/profile', [StudentProfileController::class, 'index'])->name('profile.index');
    Route::post('/profile', [StudentProfileController::class, 'store'])->name('profile.store');
    Route::put('/profile/{student}', [StudentProfileController::class, 'update'])->name('profile.update');
    
    /* =====================================================
     | Report Grades
     ===================================================== */
    Route::resource('report-grades', ReportGradeController::class);
    
    /* =====================================================
     | Documents
     ===================================================== */
    Route::resource('documents', DocumentController::class);
    
    Route::prefix('resubmission/')->name('resubmission.')->group(function () {
    Route::get('/',                  [ResubmissionController::class, 'show'])           ->name('show');
    Route::put('/report-grade',      [ResubmissionController::class, 'updateReportGrade'])->name('update-grade');
    Route::post('/upload-document', [ResubmissionController::class, 'uploadDocument'])->name('upload-document');
    Route::post('/submit',           [ResubmissionController::class, 'submit'])         ->name('submit');
    });

    /* =====================================================
     | Specialization
     ===================================================== */
    Route::prefix('specialization')->name('specialization.')->group(function () {
        Route::get('/', [SpecializationController::class, 'index'])->name('index');
        Route::get('/create', [SpecializationController::class, 'create'])->name('create');
        Route::post('/', [SpecializationController::class, 'store'])->name('store');
        Route::get('/show', [SpecializationController::class, 'show'])->name('show');
        Route::get('/edit', [SpecializationController::class, 'edit'])->name('edit');
        Route::put('/', [SpecializationController::class, 'update'])->name('update');
    });
    
    /* =====================================================
     | Result Routes (Ranking)
     ===================================================== */
    Route::prefix('result')->name('result.')->group(function () {
        Route::get('/', [ResultController::class, 'index'])->name('index');
        Route::get('/detail', [ResultController::class, 'show'])->name('show');
        Route::get('/comparison', [ResultController::class, 'comparison'])->name('comparison');
        Route::get('/card', [ResultController::class, 'card'])->name('card');
    });

    /* =====================================================
     | Announcements (View Only)
     ===================================================== */
    Route::get('announcements/{announcement}', [AnnouncementController::class, 'show'])
        ->name('announcements.show');
});

// ============================================================
// LOGOUT ROUTE - Untuk semua role
// ============================================================
Route::post('/logout', function () {
    auth()->logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();
    return redirect('/')->with('success', 'Berhasil logout');
})->middleware('auth')->name('logout');

// ============================================================
// AUTH ROUTES (Login, Register, Password Reset)
// ============================================================
require __DIR__.'/auth.php';