<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreSpecializationRequest;
use App\Http\Requests\UpdateSpecializationRequest;
use App\Models\Student;
use App\Service\SpecializationService;
use Illuminate\Http\Request;

class SpecializationController extends Controller
{
    protected $specializationService;

    public function __construct(SpecializationService $specializationService)
    {
        $this->specializationService = $specializationService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $student = Student::where('user_id', auth()->id())->first();

        if (!$student) {
            return redirect()->route('student.profile.index')
                ->with('error', 'Silakan lengkapi data pribadi terlebih dahulu');
        }

        // Cek apakah sudah memilih peminatan
        $hasSpecialization = !empty($student->specialization);

        // Ambil informasi kuota per kelas
        $quotaInfo = $this->specializationService->getQuotaInformation($student->academic_year_id);

        // Ambil rekomendasi berdasarkan nilai
        $recommendation = $this->specializationService->getRecommendation($student);

        // Ambil ranking jika sudah ada
        $ranking = null;
        if ($hasSpecialization) {
            $ranking = $this->specializationService->getStudentRanking($student);
        }

        $progress = $student->getRegistrationProgress();

        return view('student.specialization.index', compact(
            'student',
            'hasSpecialization',
            'quotaInfo',
            'recommendation',
            'ranking',
            'progress'
        ));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $student = Student::where('user_id', auth()->id())->first();

        if (!$student) {
            return redirect()->route('student.profile.index')
                ->with('error', 'Silakan lengkapi data pribadi terlebih dahulu');
        }

        // Cek apakah sudah ada peminatan
        if (!empty($student->specialization)) {
            return redirect()->route('student.specialization.index')
                ->with('info', 'Anda sudah memilih peminatan. Silakan edit jika ingin mengubah.');
        }

        // Cek apakah data lain sudah lengkap
        $canChoose = $this->specializationService->canChooseSpecialization($student);
        
        if (!$canChoose['can_choose']) {
            $errorMessage = 'Silakan lengkapi data berikut: ' . implode(', ', $canChoose['errors']);
            return redirect()->route('student.profile.index')
                ->with('error', $errorMessage);
        }

        // Ambil rekomendasi
        $recommendation = $this->specializationService->getRecommendation($student);

        // Ambil informasi kuota
        $quotaInfo = $this->specializationService->getQuotaInformation($student->academic_year_id);

        $progress = $student->getRegistrationProgress();

        return view('student.specialization.create', compact(
            'student',
            'recommendation',
            'quotaInfo',
            'progress'
        ));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreSpecializationRequest $request)
    {
        $student = Student::where('user_id', auth()->id())->first();

        if (!$student) {
            return redirect()->route('student.profile.index')
                ->with('error', 'Silakan lengkapi data pribadi terlebih dahulu');
        }

        // Cek apakah sudah memilih
        if (!empty($student->specialization)) {
            return redirect()->route('student.specialization.index')
                ->with('error', 'Anda sudah memilih peminatan');
        }

        // Store menggunakan service
        $result = $this->specializationService->storeSpecialization($student, $request->specialization);

        if (!$result['success']) {
            return redirect()->back()
                ->withInput()
                ->with('error', $result['message']);
        }

        return redirect()->route('student.specialization.index')
            ->with('success', $result['message']);
    }

    /**
     * Display the specified resource.
     */
    public function show()
    {
        $student = Student::where('user_id', auth()->id())->first();

        if (!$student) {
            return redirect()->route('student.profile.index')
                ->with('error', 'Silakan lengkapi data pribadi terlebih dahulu');
        }

        if (empty($student->specialization)) {
            return redirect()->route('student.specialization.create')
                ->with('error', 'Anda belum memilih peminatan');
        }

        // Ambil hasil ranking SAW
        $ranking = $this->specializationService->getStudentRanking($student);

        // Ambil statistik peminatan
        $statistics = $this->specializationService->getSpecializationStatistics(
            $student->academic_year_id, 
            $student->specialization
        );

        $progress = $student->getRegistrationProgress();

        return view('student.specialization.show', compact(
            'student',
            'ranking',
            'statistics',
            'progress'
        ));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit()
    {
        $student = Student::where('user_id', auth()->id())->first();

        if (!$student) {
            return redirect()->route('student.profile.index')
                ->with('error', 'Silakan lengkapi data pribadi terlebih dahulu');
        }

        if (empty($student->specialization)) {
            return redirect()->route('student.specialization.create')
                ->with('error', 'Anda belum memilih peminatan');
        }

        // Cek apakah masih bisa edit
        $canEdit = $this->specializationService->canEditSpecialization($student);
        
        if (!$canEdit['can_edit']) {
            return redirect()->route('student.specialization.index')
                ->with('warning', $canEdit['reason']);
        }

        // Ambil rekomendasi
        $recommendation = $this->specializationService->getRecommendation($student);

        // Ambil informasi kuota
        $quotaInfo = $this->specializationService->getQuotaInformation($student->academic_year_id);

        $progress = $student->getRegistrationProgress();

        return view('student.specialization.edit', compact(
            'student',
            'recommendation',
            'quotaInfo',
            'progress'
        ));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateSpecializationRequest $request)
    {
        $student = Student::where('user_id', auth()->id())->first();

        if (!$student) {
            return redirect()->route('student.profile.index')
                ->with('error', 'Silakan lengkapi data pribadi terlebih dahulu');
        }

        if (empty($student->specialization)) {
            return redirect()->route('student.specialization.create')
                ->with('error', 'Anda belum memilih peminatan');
        }

        // Update menggunakan service
        $result = $this->specializationService->updateSpecialization($student, $request->specialization);

        if (!$result['success']) {
            return redirect()->back()
                ->withInput()
                ->with('error', $result['message']);
        }

        return redirect()->route('student.specialization.index')
            ->with('success', $result['message']);
    }
}