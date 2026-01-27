<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreDocumentRequest;
use App\Http\Requests\UpdateDocumentRequest;
use App\Models\Document;
use App\Models\Student;
use App\Service\DocumentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DocumentController extends Controller
{
    protected DocumentService $documentService;

    public function __construct(DocumentService $documentService)
    {
        $this->documentService = $documentService;
    }

    public function index(Request $request)
    {
        $student = Student::where('user_id', auth()->id())->first();

        if (!$student) {
            return redirect()->route('student.profile.index')
                ->with('error', 'Silakan lengkapi data pribadi terlebih dahulu');
        }

        $filter = $request->get('filter', 'all');
        $type = $request->get('type');

        if ($type && in_array($type, ['certificate', 'report'])) {
            $documents = $this->documentService->getDocumentsByType($type);
        } elseif (in_array($filter, ['pending', 'valid', 'invalid'])) {
            $documents = $this->documentService->getDocumentsByStatus($filter);
        } else {
            $documents = $this->documentService->getStudentDocuments();
        }

        $statistics = $this->documentService->getDocumentStatistics();
        
        // GUNAKAN METHOD DARI MODEL
        $progress = $student->getRegistrationProgress();

        return view('student.documents.index', compact('documents', 'statistics', 'filter', 'type', 'progress'));
    }

    public function create()
    {
        $student = Student::where('user_id', auth()->id())->first();

        if (!$student) {
            return redirect()->route('student.profile.index')
                ->with('error', 'Silakan lengkapi data pribadi terlebih dahulu');
        }

        $certificateLimit = $this->documentService->canUploadDocument('certificate');
        $reportLimit = $this->documentService->canUploadDocument('report');
        
        // GUNAKAN METHOD DARI MODEL
        $progress = $student->getRegistrationProgress();

        return view('student.documents.create', compact('certificateLimit', 'reportLimit', 'progress'));
    }

    public function store(StoreDocumentRequest $request)
    {
        $student = Student::where('user_id', auth()->id())->first();

        if (!$student) {
            return redirect()->route('student.profile.index')
                ->with('error', 'Silakan lengkapi data pribadi terlebih dahulu');
        }

        try {
            $uploadCheck = $this->documentService->canUploadDocument($request->document_type);
            
            if (!$uploadCheck['can_upload']) {
                return redirect()
                    ->back()
                    ->withInput()
                    ->with('error', "Anda telah mencapai batas maksimal upload untuk dokumen {$request->document_type}. Limit: {$uploadCheck['limit']} dokumen.");
            }

            $this->documentService->createDocument(
                $request->validated(),
                $request->file('file')
            );

            return redirect()
                ->route('student.documents.index')
                ->with('success', 'Dokumen berhasil diunggah dan menunggu validasi.');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan saat mengunggah dokumen: ' . $e->getMessage());
        }
    }

    public function show(Document $document)
    {
        $student = Student::where('user_id', auth()->id())->first();

        if (!$student) {
            return redirect()->route('student.profile.index')
                ->with('error', 'Silakan lengkapi data pribadi terlebih dahulu');
        }

        if ($document->student_id !== $student->id) {
            abort(403, 'Unauthorized action.');
        }

        // GUNAKAN METHOD DARI MODEL
        $progress = $student->getRegistrationProgress();

        return view('student.documents.show', compact('document', 'progress'));
    }

    public function edit(Document $document)
    {
        $student = Student::where('user_id', auth()->id())->first();

        if (!$student) {
            return redirect()->route('student.profile.index')
                ->with('error', 'Silakan lengkapi data pribadi terlebih dahulu');
        }

        if ($document->student_id !== $student->id) {
            abort(403, 'Unauthorized action.');
        }

        if ($document->isValid()) {
            return redirect()
                ->route('student.documents.show', $document)
                ->with('warning', 'Dokumen yang sudah tervalidasi tidak dapat diedit.');
        }

        // GUNAKAN METHOD DARI MODEL
        $progress = $student->getRegistrationProgress();

        return view('student.documents.edit', compact('document', 'progress'));
    }

    public function update(UpdateDocumentRequest $request, Document $document)
    {
        $student = Student::where('user_id', auth()->id())->first();

        if (!$student) {
            abort(403, 'Unauthorized action.');
        }

        if ($document->student_id !== $student->id) {
            abort(403, 'Unauthorized action.');
        }

        if ($document->isValid()) {
            return redirect()
                ->route('student.documents.show', $document)
                ->with('warning', 'Dokumen yang sudah tervalidasi tidak dapat diedit.');
        }

        try {
            $this->documentService->updateDocument(
                $document,
                $request->validated(),
                $request->file('file')
            );

            return redirect()
                ->route('student.documents.show', $document)
                ->with('success', 'Dokumen berhasil diperbarui.');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan saat memperbarui dokumen: ' . $e->getMessage());
        }
    }

    public function destroy(Document $document)
    {
        $student = Student::where('user_id', auth()->id())->first();

        if (!$student) {
            abort(403, 'Unauthorized action.');
        }

        if ($document->student_id !== $student->id) {
            abort(403, 'Unauthorized action.');
        }

        if ($document->isValid()) {
            return redirect()
                ->back()
                ->with('warning', 'Dokumen yang sudah tervalidasi tidak dapat dihapus.');
        }

        try {
            $this->documentService->deleteDocument($document);

            return redirect()
                ->route('student.documents.index')
                ->with('success', 'Dokumen berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('error', 'Terjadi kesalahan saat menghapus dokumen: ' . $e->getMessage());
        }
    }

    // HAPUS METHOD calculateProgress() KARENA SUDAH ADA DI MODEL
}