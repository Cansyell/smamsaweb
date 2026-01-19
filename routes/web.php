<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\StudentController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');


    Route::resource('students', StudentController::class);
    Route::get('students-pending', [StudentController::class, 'pending'])->name('students.pending');
    Route::post('students/{student}/validate', [StudentController::class, 'validate'])->name('students.validate');
    Route::post('students/bulk-validate', [StudentController::class, 'bulkValidate'])->name('students.bulk-validate');
    Route::get('students-export', [StudentController::class, 'export'])->name('students.export');
});

require __DIR__.'/auth.php';
