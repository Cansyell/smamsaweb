<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;

// Admin Controllers
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Admin\StudentController;
use App\Http\Controllers\Admin\CriteriaController;
use App\Http\Controllers\Admin\AhpMatrixController;
use App\Http\Controllers\Admin\SpecializationQuotaController;
use App\Http\Controllers\Admin\AcademicYearController;
use App\Http\Controllers\Admin\AhpResultController;


// Panitia Controllers
use App\Http\Controllers\Committee\CommitteeDashboardController;
use App\Http\Controllers\Committee\TestScoreController;
use App\Http\Controllers\Committee\ValidationController;
use App\Http\Controllers\Committee\CriterionValueController;



// Student Controllers
use App\Http\Controllers\Student\ProfileController as StudentProfileController;
use App\Http\Controllers\Student\ReportGradeController;
use App\Http\Controllers\Student\DocumentController;
use App\Http\Controllers\Student\StudentDashboardController;
use App\Http\Controllers\Student\SpecializationController;
use App\Http\Controllers\Student\ResultController;


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

        // AHP Results - Hasil Perhitungan
        Route::get('ahp-results', [AhpResultController::class, 'index'])->name('ahp-results.index');

        //academic-years
        Route::resource('academic-years', AcademicYearController::class);
        Route::patch('academic-years/{academicYear}/toggle-active', [AcademicYearController::class, 'toggleActive'])
        ->name('academic-years.toggle-active');
        
        // Specialization Quotas Routes
        Route::resource('specialization-quotas', SpecializationQuotaController::class);
        Route::patch('specialization-quotas/{specializationQuota}/toggle-active', 
            [SpecializationQuotaController::class, 'toggleActive'])
            ->name('specialization-quotas.toggle-active');
        
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

    // TAMBAHKAN VALIDATION ROUTES DI SINI
    Route::prefix('validation')->name('validation.')->group(function () {
        Route::get('/', [ValidationController::class, 'index'])->name('index');
        Route::get('/{student}', [ValidationController::class, 'show'])->name('show');
        Route::post('/{student}/approve', [ValidationController::class, 'approve'])->name('approve');
        Route::post('/{student}/reject', [ValidationController::class, 'reject'])->name('reject');
        Route::post('/batch/approve', [ValidationController::class, 'batchApprove'])->name('batch.approve');
        Route::get('/{student}/check-completeness', [ValidationController::class, 'checkCompleteness'])->name('check-completeness');
        Route::post('/documents/{document}/validate', [ValidationController::class, 'validateDocument'])->name('documents.validate');
    });
    
    // Criterion Values Input Routes
    Route::prefix('criterion-values')->name('criterion-values.')->group(function () {
        Route::get('/', [CriterionValueController::class, 'index'])->name('index');
        Route::get('/create/{student}', [CriterionValueController::class, 'create'])->name('create');
        Route::post('/store/{student}', [CriterionValueController::class, 'store'])->name('store');
        Route::get('/show/{student}', [CriterionValueController::class, 'show'])->name('show');
        
        // Bulk input
        Route::get('/bulk-create', [CriterionValueController::class, 'bulkCreate'])->name('bulk-create');
        Route::post('/bulk-store', [CriterionValueController::class, 'bulkStore'])->name('bulk-store');
        
        // Calculate SAW
        Route::post('/calculate-saw', [CriterionValueController::class, 'calculateSaw'])->name('calculate-saw'); 
     });

    Route::prefix('saw-results')->name('saw-results.')->group(function () {
        Route::get('/', [CriterionValueController::class, 'sawResultsIndex'])->name('index');
        Route::get('/{sawResult}', [CriterionValueController::class, 'sawResultsShow'])->name('show');
    });
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
    Route::resource('report-grades', ReportGradeController::class);
    
    // Documents
    Route::resource('documents', DocumentController::class);
    
    // Specialization
    Route::prefix('specialization')->name('specialization.')->group(function () {
        Route::get('/', [SpecializationController::class, 'index'])->name('index');
        Route::get('/create', [SpecializationController::class, 'create'])->name('create');
        Route::post('/', [SpecializationController::class, 'store'])->name('store');
        Route::get('/show', [SpecializationController::class, 'show'])->name('show');
        Route::get('/edit', [SpecializationController::class, 'edit'])->name('edit');
        Route::put('/', [SpecializationController::class, 'update'])->name('update');
    });
    
    // Result Routes (Ranking)
    Route::prefix('result')->name('result.')->group(function () {
        Route::get('/', [ResultController::class, 'index'])->name('index');
        Route::get('/detail', [ResultController::class, 'show'])->name('show');
        Route::get('/comparison', [ResultController::class, 'comparison'])->name('comparison');
        Route::get('/card', [ResultController::class, 'card'])->name('card');
    });
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