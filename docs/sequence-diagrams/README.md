# Sequence Diagrams - Sistem PPDB SMA MSA

Dokumentasi ini berisi sequence diagram untuk semua alur utama dalam Sistem Penerimaan Peserta Didik Baru (PPDB) SMA MSA menggunakan metode SAW (Simple Additive Weighting) dan AHP (Analytic Hierarchy Process).

## 📋 Daftar Diagram

### 1. Authentication Flow
**File:** `01-authentication-flow.mmd`

**Deskripsi:** Alur autentikasi pengguna meliputi:
- Proses registrasi siswa baru
- Login untuk semua role (Admin, Committee, Student)
- Redirect otomatis ke dashboard sesuai role
- Proses logout

**Aktor:**
- User (Admin/Committee/Student)
- AuthController
- Middleware
- Database

---

### 2. Student Registration Flow
**File:** `02-student-registration-flow.mmd`

**Deskripsi:** Alur lengkap pendaftaran siswa (4 langkah):
1. **Input Data Pribadi** - NISN, nama, alamat, orang tua, dll
2. **Input Nilai Rapor** - PAI, Bahasa Indonesia, Bahasa Inggris
3. **Upload Dokumen** - KTP, Ijazah, Foto, dll
4. **Pilih Peminatan** - Tahfiz atau Language

**Progress:** Setiap langkah menambah 25% progress (total 100%)

**Aktor:**
- Student
- ProfileController
- ReportGradeController
- DocumentController
- SpecializationController

---

### 3. Validation Flow
**File:** `03-validation-flow.mmd`

**Deskripsi:** Alur validasi data siswa oleh panitia:
- Melihat daftar siswa pending
- Melihat detail data siswa
- Validasi dokumen satu per satu (Terima/Tolak)
- Pengecekan kondisi approval:
  - Data harus lengkap (100%)
  - Semua dokumen harus valid
  - Tidak ada dokumen ditolak
  - Tidak ada dokumen pending
- Approve atau reject siswa

**Fitur Baru:** Tombol validasi hanya aktif jika semua dokumen valid

**Aktor:**
- Committee
- ValidationController
- ValidationService
- DocumentController

---

### 4. SAW & AHP Calculation Flow
**File:** `04-saw-ahp-calculation-flow.mmd`

**Deskripsi:** Alur perhitungan ranking menggunakan SAW & AHP:

**Step 1: Setup Criteria (Admin)**
- Buat kriteria penilaian (benefit/cost)
- Tentukan bobot untuk setiap kriteria

**Step 2: Calculate AHP Weights (Admin)**
- Input pairwise comparison matrix
- Hitung eigenvector
- Validasi consistency ratio (CR ≤ 0.1)
- Simpan bobot kriteria

**Step 3: Input Criterion Values (Committee)**
- Input nilai untuk setiap kriteria per siswa
- Atau sync otomatis dari nilai rapor

**Step 4: Calculate SAW (Committee)**
- Normalisasi nilai (benefit/cost)
- Kalikan dengan bobot
- Hitung total score
- Simpan hasil SAW

**Step 5: Determine Acceptance**
- Ranking berdasarkan SAW score
- Bandingkan dengan kuota
- Tentukan status (accepted/rejected)

**Aktor:**
- Admin
- Committee
- AhpController
- CriterionController
- AhpService
- SawService
- RankingService

---

### 5. Resubmission Flow
**File:** `05-resubmission-flow.mmd`

**Deskripsi:** Alur perbaikan data setelah ditolak:
- Siswa menerima notifikasi penolakan dengan catatan
- Siswa melihat detail penolakan
- Siswa memperbaiki:
  - Data pribadi (jika perlu)
  - Nilai rapor (jika perlu)
  - Dokumen yang ditolak (wajib)
- Submit perbaikan
- Panitia validasi ulang
- Approve atau reject lagi

**Fitur:**
- Resubmission counter (Pengajuan ke-X)
- Flag `has_pending_resubmission`
- Resubmitted students muncul di atas list

**Aktor:**
- Student
- Committee
- ResubmissionController
- ValidationController
- ValidationService

---

### 6. Result Publication Flow
**File:** `06-result-publication-flow.mmd`

**Deskripsi:** Alur publikasi hasil seleksi:

**Step 1: Preview Results**
- Lihat statistik penerimaan
- Lihat ranking per peminatan
- Lihat penggunaan kuota

**Step 2: Set to Reviewing (Optional)**
- Lock hasil untuk review
- Siswa belum bisa lihat

**Step 3: Publish Results**
- Publikasikan hasil
- Kirim email notifikasi ke semua siswa
- Siswa dapat melihat hasil

**Step 4: Students View Results**
- Lihat status penerimaan
- Lihat ranking & score
- Lihat peminatan yang diterima

**Step 5: View Detailed Results**
- Breakdown perhitungan SAW
- Perbandingan dengan siswa lain
- Percentile rank

**Step 6: Download Result Card**
- Download kartu hasil (PDF)
- Berisi info lengkap & stempel resmi

**Optional: Unpublish**
- Tarik kembali publikasi jika perlu

**Aktor:**
- Committee
- Student
- PublishController
- RankingService
- NotificationJob

---

### 7. Data Locking Flow
**File:** `07-data-locking-flow.mmd`

**Deskripsi:** Alur penguncian data setelah perhitungan SAW:

**Before SAW:**
- Siswa dapat edit semua data
- Tombol edit/delete terlihat
- Form dapat disubmit

**After SAW:**
- Siswa tidak dapat edit data apapun
- Alert kuning muncul di semua halaman
- Tombol edit/delete disembunyikan
- Form inputs disabled (gray)
- Submit button hidden

**Affected Pages:**
- Profile (Data Pribadi)
- Report Grades (Nilai Rapor)
- Specialization (Peminatan)
- Documents (Dokumen)

**Validation Check:**
- Committee tidak dapat hitung SAW jika ada siswa pending
- Error message jelas dengan jumlah siswa pending

**Logic:**
```php
canEditData() {
    if (hasSawCalculation()) {
        return [
            'can_edit' => false,
            'reason' => 'Data terkunci karena ranking telah dihitung'
        ];
    }
    return ['can_edit' => true, 'reason' => null];
}
```

**Aktor:**
- Student
- Controller (Profile, ReportGrade, Specialization, Document)
- StudentModel

---

## 🎨 Cara Melihat Diagram

### Menggunakan VS Code

1. **Install Extension:**
   - Buka VS Code
   - Install extension: **Markdown Preview Mermaid Support**
   - Atau: **Mermaid Preview**

2. **View Diagram:**
   - Buka file `.mmd`
   - Klik kanan → "Open Preview" atau tekan `Ctrl+Shift+V`
   - Diagram akan ter-render otomatis

### Menggunakan Online Editor

1. Buka [Mermaid Live Editor](https://mermaid.live/)
2. Copy-paste isi file `.mmd`
3. Diagram akan ter-render secara real-time
4. Bisa export ke PNG/SVG

### Menggunakan GitHub

1. Upload file `.mmd` ke GitHub
2. GitHub otomatis render Mermaid diagram
3. Bisa dilihat langsung di browser

---

## 📊 Statistik Diagram

| Diagram | Participants | Interactions | Complexity |
|---------|-------------|--------------|------------|
| Authentication | 6 | ~15 | Low |
| Student Registration | 6 | ~25 | Medium |
| Validation | 6 | ~30 | High |
| SAW/AHP Calculation | 8 | ~40 | Very High |
| Resubmission | 6 | ~25 | Medium |
| Result Publication | 6 | ~30 | High |
| Data Locking | 5 | ~20 | Medium |

---

## 🔄 Alur Lengkap Sistem

```
1. Authentication (Login/Register)
   ↓
2. Student Registration (4 steps)
   ↓
3. Validation by Committee
   ↓ (if rejected)
4. Resubmission by Student → back to step 3
   ↓ (if approved)
5. SAW/AHP Calculation
   ↓
6. Data Locking (automatic)
   ↓
7. Result Publication
   ↓
8. Students View Results
```

---

## 🎯 Key Features Highlighted

### Data Integrity
- ✅ Data locked after SAW calculation
- ✅ Cannot calculate SAW with pending students
- ✅ Cannot approve student with rejected/pending documents

### User Experience
- ✅ Clear progress indicator (25%, 50%, 75%, 100%)
- ✅ Visual feedback (alerts, badges, disabled states)
- ✅ Informative error messages

### Workflow Control
- ✅ Step-by-step validation
- ✅ Resubmission tracking
- ✅ Publication control (preview → review → publish)

### Transparency
- ✅ Detailed SAW calculation breakdown
- ✅ Comparison with other students
- ✅ Validation history timeline

---

## 📝 Catatan Teknis

### Mermaid Syntax
- `sequenceDiagram`: Tipe diagram
- `participant`: Aktor dalam sistem
- `->`: Synchronous call
- `-->>`: Response/return
- `rect rgb(r,g,b)`: Grouping dengan warna
- `alt/else/end`: Conditional logic
- `loop/end`: Iterasi
- `Note`: Komentar/penjelasan

### Color Coding
- 🔵 Blue (`rgb(200, 220, 255)`): Read/View operations
- 🟠 Orange (`rgb(255, 220, 200)`): Create/Input operations
- 🟢 Green (`rgb(220, 255, 200)`): Update/Edit operations
- 🟡 Yellow (`rgb(255, 255, 200)`): Calculate/Process operations
- 🔴 Red (`rgb(255, 200, 200)`): Delete/Reject operations
- 🟣 Purple (`rgb(255, 200, 255)`): Special operations

---

## 🔗 Related Documentation

- [Database Schema](../database-schema.md)
- [API Documentation](../api-docs.md)
- [User Manual](../user-manual.md)
- [Changelog](../../CHANGELOG_SAW_AHP_RESTRICTIONS.md)

---

**Created by:** Kiro AI Assistant  
**Date:** 9 Mei 2026  
**Version:** 1.0  
**Status:** ✅ Complete
