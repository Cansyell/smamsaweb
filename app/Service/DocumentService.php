<?php

namespace App\Service;

use App\Models\Document;
use App\Models\Student;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class DocumentService
{
    /**
     * Get student_id from current authenticated user
     */
    private function getStudentId(): ?int
    {
        $student = Student::where('user_id', Auth::id())->first();
        return $student ? $student->id : null;
    }

    /**
     * Get all documents for current student
     */
    public function getStudentDocuments()
    {
        $studentId = $this->getStudentId();
        
        if (!$studentId) {
            return collect();
        }

        return Document::where('student_id', $studentId)
            ->latest()
            ->get();
    }

    /**
     * Get documents by status
     */
    public function getDocumentsByStatus(string $status)
    {
        $studentId = $this->getStudentId();
        
        if (!$studentId) {
            return collect();
        }

        return Document::where('student_id', $studentId)
            ->where('validation_status', $status)
            ->latest()
            ->get();
    }

    /**
     * Get documents by type
     */
    public function getDocumentsByType(string $type)
    {
        $studentId = $this->getStudentId();
        
        if (!$studentId) {
            return collect();
        }

        return Document::where('student_id', $studentId)
            ->where('document_type', $type)
            ->latest()
            ->get();
    }

    /**
     * Create new document
     */
    public function createDocument(array $data, UploadedFile $file): Document
    {
        DB::beginTransaction();
        
        try {
            $studentId = $this->getStudentId();
            
            if (!$studentId) {
                throw new \Exception('Student data not found. Please complete your profile first.');
            }

            // Upload file
            $filePath = $this->uploadFile($file, $data['document_type'], $studentId);
            
            // Create document record
            $document = Document::createDocument([
                'student_id' => $studentId, // PERBAIKAN: Gunakan student_id yang benar
                'document_type' => $data['document_type'],
                'file_name' => $file->getClientOriginalName(),
                'file_path' => $filePath,
                'notes' => $data['notes'] ?? null,
            ]);

            DB::commit();
            
            return $document;
        } catch (\Exception $e) {
            DB::rollBack();
            
            // Delete uploaded file if exists
            if (isset($filePath) && Storage::exists($filePath)) {
                Storage::delete($filePath);
            }
            
            throw $e;
        }
    }

    /**
     * Update document
     */
    public function updateDocument(Document $document, array $data, ?UploadedFile $file = null): bool
    {
        DB::beginTransaction();
        
        try {
            $studentId = $this->getStudentId();
            
            if (!$studentId) {
                throw new \Exception('Student data not found.');
            }

            $updateData = [];
            
            // Update document type if provided
            if (isset($data['document_type'])) {
                $updateData['document_type'] = $data['document_type'];
            }
            
            // Update notes if provided
            if (isset($data['notes'])) {
                $updateData['notes'] = $data['notes'];
            }
            
            // Handle file upload if new file provided
            if ($file) {
                $oldFilePath = $document->file_path;
                
                // Upload new file
                $newFilePath = $this->uploadFile($file, $data['document_type'] ?? $document->document_type, $studentId);
                
                $updateData['file_name'] = $file->getClientOriginalName();
                $updateData['file_path'] = $newFilePath;
                
                // Delete old file after successful upload
                if (Storage::exists($oldFilePath)) {
                    Storage::delete($oldFilePath);
                }
            }
            
            // Reset validation status to pending if file or type changed
            if ($file || isset($data['document_type'])) {
                $updateData['validation_status'] = 'pending';
            }
            
            $result = $document->updateDocument($updateData);
            
            DB::commit();
            
            return $result;
        } catch (\Exception $e) {
            DB::rollBack();
            
            // Delete new uploaded file if exists
            if (isset($newFilePath) && Storage::exists($newFilePath)) {
                Storage::delete($newFilePath);
            }
            
            throw $e;
        }
    }

    /**
     * Delete document
     */
    public function deleteDocument(Document $document): bool
    {
        DB::beginTransaction();
        
        try {
            $result = $document->deleteDocument();
            
            DB::commit();
            
            return $result;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Upload file to storage
     */
    private function uploadFile(UploadedFile $file, string $documentType, int $studentId): string
    {
        $timestamp = now()->format('YmdHis');
        $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $extension = $file->getClientOriginalExtension();
        
        // Generate unique filename
        $fileName = "{$documentType}_{$studentId}_{$timestamp}_{$originalName}.{$extension}";
        
        // Store file in documents/{student_id}/{document_type} folder
        $path = $file->storeAs(
            "documents/{$studentId}/{$documentType}",
            $fileName,
            'public'
        );
        
        return $path;
    }

    /**
     * Check if student can upload more documents
     */
    public function canUploadDocument(string $documentType): array
    {
        $studentId = $this->getStudentId();
        
        if (!$studentId) {
            return [
                'can_upload' => false,
                'current_count' => 0,
                'limit' => 0,
                'remaining' => 0,
            ];
        }

        $maxDocuments = [
            'certificate' => 3,
            'report' => 6,
        ];

        $currentCount = Document::where('student_id', $studentId)
            ->where('document_type', $documentType)
            ->count();

        $limit = $maxDocuments[$documentType] ?? 10;
        $canUpload = $currentCount < $limit;

        return [
            'can_upload' => $canUpload,
            'current_count' => $currentCount,
            'limit' => $limit,
            'remaining' => $limit - $currentCount,
        ];
    }

    /**
     * Get document statistics for student
     */
    public function getDocumentStatistics(): array
    {
        $studentId = $this->getStudentId();
        
        if (!$studentId) {
            return [
                'total' => 0,
                'pending' => 0,
                'valid' => 0,
                'invalid' => 0,
                'certificates' => 0,
                'reports' => 0,
            ];
        }

        return [
            'total' => Document::where('student_id', $studentId)->count(),
            'pending' => Document::where('student_id', $studentId)->pending()->count(),
            'valid' => Document::where('student_id', $studentId)->valid()->count(),
            'invalid' => Document::where('student_id', $studentId)->invalid()->count(),
            'certificates' => Document::where('student_id', $studentId)->certificates()->count(),
            'reports' => Document::where('student_id', $studentId)->reports()->count(),
        ];
    }
}