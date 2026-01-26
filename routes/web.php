<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;

// Admin Controllers
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Admin\StudentController;
use App\Http\Controllers\Admin\CriteriaController;
use App\Http\Controllers\Admin\AhpMatrixController;
use App\Http\Controllers\Admin\SpecializationQuotaController;
// use App\Http\Controllers\Admin\CalculationController;
// use App\Http\Controllers\Admin\ResultController;
// use App\Http\Controllers\Admin\RankingController;
use App\Http\Controllers\Admin\AcademicYearController;
// use App\Http\Controllers\Admin\UserController;
// use App\Http\Controllers\Admin\SettingsController;

// Panitia Controllers
use App\Http\Controllers\Committee\CommitteeDashboardController;
// use App\Http\Controllers\Panitia\ValidationController;
use App\Http\Controllers\Committee\TestScoreController;
// use App\Http\Controllers\Panitia\PanitiaStudentController;

// Student Controllers
use App\Http\Controllers\Student\ProfileController as StudentProfileController;
use App\Http\Controllers\Student\ReportGradeController;
use App\Http\Controllers\Student\DocumentController;
use App\Http\Controllers\Student\StudentDashboardController;

// ============================================================
// PUBLIC ROUTES
// ============================================================
Route::get('/', function () {
    return view('welcome');
});

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
    
    // Dashboard Admin
    Route::get('/admin/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Profile Admin
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    // Group Admin dengan prefix dan name
    Route::prefix('admin')->name('admin.')->group(function () {
        
        // Data Siswa
        Route::prefix('students')->name('students.')->group(function () {
            Route::get('/', [StudentController::class, 'index'])->name('index');
            Route::get('/create', [StudentController::class, 'create'])->name('create');
            Route::post('/', [StudentController::class, 'store'])->name('store');
            Route::get('/{student}', [StudentController::class, 'show'])->name('show');
            Route::get('/{student}/edit', [StudentController::class, 'edit'])->name('edit');
            Route::put('/{student}', [StudentController::class, 'update'])->name('update');
            Route::delete('/{student}', [StudentController::class, 'destroy'])->name('destroy');
            Route::get('/export', [StudentController::class, 'export'])->name('export');
        });
        
        // Kriteria Penilaian - Resource route dengan custom routes
        Route::resource('criterias', CriteriaController::class);
        
        // Route tambahan untuk Criteria
        Route::patch('criterias/{criteria}/toggle-status', [CriteriaController::class, 'toggleStatus'])
            ->name('criterias.toggle-status');
        
        Route::post('criterias/{specialization}/reorder', [CriteriaController::class, 'reorder'])
            ->name('criterias.reorder')
            ->whereIn('specialization', ['tahfiz', 'language']);

        //ahp matrix
        Route::resource('ahp-matrices', AhpMatrixController::class)->only(['index', 'store', 'show']);
        Route::post('ahp-matrices/calculate-weights', [AhpMatrixController::class, 'calculateWeights'])->name('ahp-matrices.calculate-weights');
        Route::delete('ahp-matrices/reset', [AhpMatrixController::class, 'reset'])->name('ahp-matrices.reset');

        //academic-years
        Route::resource('academic-years', AcademicYearController::class);
        Route::patch('academic-years/{academicYear}/toggle-active', [AcademicYearController::class, 'toggleActive'])
        ->name('academic-years.toggle-active');
        
        // Specialization Quotas Routes
        Route::resource('specialization-quotas', SpecializationQuotaController::class);
        Route::patch('specialization-quotas/{specializationQuota}/toggle-active', 
            [SpecializationQuotaController::class, 'toggleActive'])
            ->name('specialization-quotas.toggle-active');
        // Perhitungan Kriteria
        // Route::prefix('calculation')->name('calculation.')->group(function () {
        //     Route::get('/', [CalculationController::class, 'index'])->name('index');
        //     Route::post('/process', [CalculationController::class, 'process'])->name('process');
        //     Route::post('/recalculate', [CalculationController::class, 'recalculate'])->name('recalculate');
        //     Route::get('/preview', [CalculationController::class, 'preview'])->name('preview');
        // });
        
        // Hasil Perhitungan
        // Route::prefix('results')->name('results.')->group(function () {
        //     Route::get('/', [ResultController::class, 'index'])->name('index');
        //     Route::get('/{student}', [ResultController::class, 'show'])->name('show');
        //     Route::get('/export/all', [ResultController::class, 'export'])->name('export');
        //     Route::get('/export/pdf', [ResultController::class, 'exportPdf'])->name('export.pdf');
        // });
        
        // Ranking
        // Route::prefix('rankings')->name('rankings.')->group(function () {
        //     Route::get('/tahfiz', [RankingController::class, 'tahfiz'])->name('tahfiz');
        //     Route::get('/bahasa', [RankingController::class, 'bahasa'])->name('bahasa');
        //     Route::get('/reguler', [RankingController::class, 'reguler'])->name('reguler');
        //     Route::post('/finalize', [RankingController::class, 'finalize'])->name('finalize');
        // });
        
        // Kuota Peminatan
        // Route::prefix('quota')->name('quota.')->group(function () {
        //     Route::get('/settings', [QuotaController::class, 'index'])->name('settings');
        //     Route::post('/update', [QuotaController::class, 'update'])->name('update');
        // });
        
        // Kelola Pengguna
        // Route::resource('users', UserController::class);
        // Route::post('users/{user}/reset-password', [UserController::class, 'resetPassword'])->name('users.reset-password');
        
        // Pengaturan Sistem
        // Route::prefix('settings')->name('settings.')->group(function () {
        //     Route::get('/', [SettingsController::class, 'index'])->name('index');
        //     Route::post('/update', [SettingsController::class, 'update'])->name('update');
        //     Route::get('/academic-year', [SettingsController::class, 'academicYear'])->name('academic-year');
        //     Route::post('/academic-year/activate', [SettingsController::class, 'activateYear'])->name('academic-year.activate');
        // });
    });
});

// ============================================================
// PANITIA ROUTES - Hanya untuk role 'panitia'
// ============================================================
Route::middleware(['auth', 'role:committee'])->prefix('committee')->name('committee.')->group(function () {
    
    // Dashboard Panitia
    Route::get('/dashboard', [CommitteeDashboardController::class, 'index'])->name('dashboard');
    
    // Profile Panitia
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    
    // Validasi Berkas Siswa
    // Route::prefix('validation')->name('students.')->group(function () {
    //     Route::get('/pending', [ValidationController::class, 'index'])->name('pending');
    //     Route::get('/{student}', [ValidationController::class, 'show'])->name('show');
    //     Route::post('/{student}/validate', [ValidationController::class, 'validate'])->name('validate');
    //     Route::post('/{student}/reject', [ValidationController::class, 'reject'])->name('reject');
    //     Route::post('/bulk-validate', [ValidationController::class, 'bulkValidate'])->name('bulk-validate');
    // });
    
    // Input Nilai Tes (Semua tes dalam satu halaman)
    // Route::prefix('tests')->name('tests.')->group(function () {
    //     Route::get('/input', [TestController::class, 'index'])->name('input');
    //     Route::get('/{student}/form', [TestController::class, 'form'])->name('form');
    //     Route::post('/{student}/save', [TestController::class, 'save'])->name('save');
    //     Route::put('/{student}/update', [TestController::class, 'update'])->name('update');
    // });
    
    // Daftar Siswa (View Only)
    // Route::get('/students', [PanitiaStudentController::class, 'index'])->name('students.list');
    // Route::get('/students/{student}', [PanitiaStudentController::class, 'show'])->name('students.detail');
});

// ============================================================
// SISWA ROUTES - Hanya untuk role 'siswa'
// ============================================================
Route::middleware(['auth', 'role:student'])->prefix('student')->name('student.')->group(function () {
    
   // Dashboard
    Route::get('/dashboard', [StudentDashboardController::class, 'index'])->name('dashboard');
    
    // Profile
    Route::get('/profile', [StudentProfileController::class, 'index'])->name('profile.index');
    Route::post('/profile', [StudentProfileController::class, 'store'])->name('profile.store');
    Route::put('/profile/{student}', [StudentProfileController::class, 'update'])->name('profile.update');
    
    // Report Grades
    Route::get('/grades', [ReportGradeController::class, 'index'])->name('grades.index');
    Route::post('/grades', [ReportGradeController::class, 'store'])->name('grades.store');
    Route::put('/grades/{reportGrade}', [ReportGradeController::class, 'update'])->name('grades.update');
    
    // Documents
    Route::get('/documents', [DocumentController::class, 'index'])->name('documents.index');
    Route::post('/documents', [DocumentController::class, 'store'])->name('documents.store');
    Route::delete('/documents/{document}', [DocumentController::class, 'destroy'])->name('documents.destroy');
    
    // Specialization
    Route::get('/specialization', [SpecializationController::class, 'index'])->name('specialization.index');
    Route::post('/specialization', [SpecializationController::class, 'store'])->name('specialization.store');
    
    // Input Nilai Rapor
    // Route::prefix('grades')->name('grades.')->group(function () {
    //     Route::get('/', [GradeController::class, 'index'])->name('index');
    //     Route::post('/store', [GradeController::class, 'store'])->name('store');
    //     Route::put('/update', [GradeController::class, 'update'])->name('update');
    // });
    
    // Upload Berkas
    // Route::prefix('documents')->name('documents.')->group(function () {
    //     Route::get('/', [DocumentController::class, 'index'])->name('index');
    //     Route::post('/upload', [DocumentController::class, 'upload'])->name('upload');
    //     Route::delete('/{document}', [DocumentController::class, 'destroy'])->name('destroy');
    // });
    
    // Pilih Peminatan
    // Route::prefix('specialization')->name('specialization.')->group(function () {
    //     Route::get('/', [SpecializationController::class, 'index'])->name('index');
    //     Route::post('/choose', [SpecializationController::class, 'choose'])->name('choose');
    //     Route::put('/update', [SpecializationController::class, 'update'])->name('update');
    // });
    
    // Hasil Seleksi (View Only)
    // Route::get('/result', [StudentResultController::class, 'index'])->name('result');
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