<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use App\Http\Requests\AnnouncementRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AnnouncementController extends Controller
{
    /**
     * Display a listing of announcements.
     */
    public function index(Request $request)
    {
        $query = Announcement::with('creator');

        // Filter by status
        if ($request->filled('status')) {
            if ($request->status === 'active') {
                $query->active();
            } elseif ($request->status === 'inactive') {
                $query->where('is_active', false);
            } elseif ($request->status === 'published') {
                $query->published();
            }
        }

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('content', 'like', "%{$search}%");
            });
        }

        $announcements = $query->latestFirst()->paginate(10);

        return view('admin.announcements.index', compact('announcements'));
    }

    /**
     * Show the form for creating a new announcement.
     */
    public function create()
    {
        return view('admin.announcements.create');
    }

    /**
     * Store a newly created announcement.
     */
    public function store(AnnouncementRequest $request)
    {
        try {
            $data = $request->validated();

            // Handle image upload
            if ($request->hasFile('image')) {
                $imagePath = $request->file('image')->store('announcements/images', 'public');
                $data['image_path'] = $imagePath;
            }

            // Handle file upload (PDF)
            if ($request->hasFile('file')) {
                $filePath = $request->file('file')->store('announcements/files', 'public');
                $data['file_path'] = $filePath;
            }

            Announcement::createAnnouncement($data);

            return redirect()
                ->route('admin.announcements.index')
                ->with('success', 'Pengumuman berhasil ditambahkan.');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified announcement.
     */
    public function show(Announcement $announcement)
    {
        $announcement->load('creator');
        return view('admin.announcements.show', compact('announcement'));
    }

    /**
     * Show the form for editing the specified announcement.
     */
    public function edit(Announcement $announcement)
    {
        return view('admin.announcements.edit', compact('announcement'));
    }

    /**
     * Update the specified announcement.
     */
    public function update(AnnouncementRequest $request, Announcement $announcement)
    {
        try {
            $data = $request->validated();

            // Handle delete image request
            if ($request->boolean('delete_image')) {
                $announcement->deleteImage();
                $data['image_path'] = null;
            }

            // Handle delete file request
            if ($request->boolean('delete_file')) {
                $announcement->deleteFile();
                $data['file_path'] = null;
            }

            // Handle new image upload
            if ($request->hasFile('image')) {
                // Delete old image
                $announcement->deleteImage();
                
                // Upload new image
                $imagePath = $request->file('image')->store('announcements/images', 'public');
                $data['image_path'] = $imagePath;
            }

            // Handle new file upload
            if ($request->hasFile('file')) {
                // Delete old file
                $announcement->deleteFile();
                
                // Upload new file
                $filePath = $request->file('file')->store('announcements/files', 'public');
                $data['file_path'] = $filePath;
            }

            $announcement->updateAnnouncement($data);

            return redirect()
                ->route('admin.announcements.index')
                ->with('success', 'Pengumuman berhasil diperbarui.');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified announcement.
     */
    public function destroy(Announcement $announcement)
    {
        try {
            $title = $announcement->title;
            $announcement->delete(); // Files will be auto-deleted via model event

            return redirect()
                ->route('admin.announcements.index')
                ->with('success', "Pengumuman '{$title}' berhasil dihapus.");
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Toggle announcement status (active/inactive)
     */
    public function toggleStatus(Announcement $announcement)
    {
        try {
            $announcement->toggleStatus();

            $status = $announcement->is_active ? 'diaktifkan' : 'dinonaktifkan';
            
            return redirect()
                ->back()
                ->with('success', "Pengumuman '{$announcement->title}' berhasil {$status}.");
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Publish announcement immediately
     */
    public function publish(Announcement $announcement)
    {
        try {
            $announcement->publish();

            return redirect()
                ->back()
                ->with('success', "Pengumuman '{$announcement->title}' berhasil dipublikasikan.");
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Unpublish announcement
     */
    public function unpublish(Announcement $announcement)
    {
        try {
            $announcement->unpublish();

            return redirect()
                ->back()
                ->with('success', "Pengumuman '{$announcement->title}' berhasil ditarik dari publikasi.");
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Delete image attachment
     */
    public function deleteImage(Announcement $announcement)
    {
        try {
            $announcement->deleteImage();

            return redirect()
                ->back()
                ->with('success', 'Gambar berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Delete file attachment
     */
    public function deleteFile(Announcement $announcement)
    {
        try {
            $announcement->deleteFile();

            return redirect()
                ->back()
                ->with('success', 'File berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}