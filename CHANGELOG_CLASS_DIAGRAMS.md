# Changelog: Class Diagram Documentation

**Date:** 11 Mei 2026  
**Author:** Kiro AI Assistant  
**Type:** Documentation  
**Status:** ✅ Complete

---

## 📋 Summary

Telah dibuat dokumentasi lengkap class diagram untuk Sistem PPDB SMA MSA menggunakan Mermaid chart. Semua entitas, relasi, dan service layer digabungkan dalam **satu file comprehensive** yang menunjukkan hubungan lengkap antar komponen sistem.

---

## 📊 Class Diagram Content

### **Single Comprehensive Diagram** (`docs/class-diagram.mmd`)

Diagram ini menggabungkan semua entitas dalam satu file untuk menunjukkan hubungan lengkap:

#### **1. Core Entities (4 entities)**
- **User** - Pengguna sistem dengan role-based access (admin, committee, student)
- **Student** - Data siswa pendaftar dengan 30+ atribut (central entity)
- **AcademicYear** - Tahun ajaran dan periode pendaftaran
- **SpecializationQuota** - Kuota penerimaan (Tahfiz, Language, Regular)

#### **2. Student Data (4 entities)**
- **ReportGrade** - Nilai rapor 9 mata pelajaran dengan rata-rata otomatis
- **Document** - Dokumen pendukung dengan sistem validasi (pending/valid/invalid)
- **TestScore** - Nilai tes masuk 5 komponen (Quran, Interview, Speaking, Dialogue)
- **ValidationLog** - Riwayat validasi dan resubmission tracking

#### **3. AHP & Criteria (4 entities)**
- **Criteria** - Kriteria penilaian (benefit/cost) per specialization
- **AhpMatrix** - Matriks perbandingan berpasangan dengan CR calculation
- **CriterionWeight** - Bobot kriteria hasil eigenvector AHP
- **StudentCriterionValue** - Nilai siswa per kriteria (raw & normalized)

#### **4. SAW Results (2 entities)**
- **SawResult** - Final score, ranking, dan detail calculation (JSON)
- **FinalScore** - Skor akhir siswa (academic + test)

#### **5. Website Content (8 entities)**
- **Announcement** - Pengumuman dengan scheduling dan file attachment
- **HeroSection** & **HeroStat** - Hero section homepage dengan statistik
- **PpdbSetting** - Pengaturan PPDB (periode, kontak, link)
- **PpdbBiaya** - Daftar biaya pendaftaran
- **PpdbPersyaratan** - Persyaratan pendaftaran
- **JadwalPpdb** - Jadwal kegiatan PPDB
- **GaleriItem** - Galeri foto/video

#### **6. Process Tracking (1 entity)**
- **SelectionProcessLog** - Log proses seleksi dan perhitungan

#### **7. Service Layer (4 services - conceptual)**
- **ValidationService** - Validasi siswa, dokumen, resubmission (15+ methods)
- **AhpService** - Implementasi algoritma AHP dengan CR checking (12+ methods)
- **SawService** - Implementasi algoritma SAW dengan normalization (10+ methods)
- **RankingService** - Manajemen ranking dan acceptance (8+ methods)

---

## 📁 Files Created

### Single Diagram File
```
docs/
└── class-diagram.mmd (comprehensive diagram dengan semua entitas)
```

### Documentation Files
```
CHANGELOG_CLASS_DIAGRAMS.md (changelog dan dokumentasi)
```

---

## 📊 Statistics

| Metric | Count |
|--------|-------|
| **Total Diagram Files** | 1 (comprehensive) |
| **Total Entities** | 27 |
| **Total Relationships** | 50+ |
| **Total Methods** | 200+ |
| **Service Classes** | 4 |
| **Lines of Code (diagram)** | 800+ |

---

## 🎨 Cara Melihat Diagram

### **Opsi 1: VS Code Extension** ⭐ (RECOMMENDED)
1. Install extension: **Markdown Preview Mermaid Support** atau **Mermaid Preview**
2. Buka file: `docs/class-diagram.mmd`
3. Klik kanan → "Open Preview" atau tekan `Ctrl+Shift+V`
4. Diagram akan ter-render dengan semua relasi terlihat

### **Opsi 2: Online Editor**
1. Buka https://mermaid.live/
2. Copy-paste isi file `docs/class-diagram.mmd`
3. Diagram akan ter-render secara real-time
4. Bisa export ke PNG/SVG untuk dokumentasi

### **Opsi 3: GitHub**
1. Upload file `.mmd` ke GitHub
2. GitHub otomatis render Mermaid diagram
3. Bisa dilihat langsung di browser

---

## 🎯 Key Features Documented

### 1. **Entity Relationships**
- **One-to-One:** User ↔ Student, Student ↔ ReportGrade, Student ↔ TestScore
- **One-to-Many:** Student → Documents, AcademicYear → Students, Criteria → StudentValues
- **Many-to-Many:** Student ↔ Criteria (through StudentCriterionValue)
- **Self-Referencing:** Criteria ↔ Criteria (through AhpMatrix for pairwise comparison)

### 2. **Business Logic**
- **Registration Flow:** User → Student → ReportGrade → Documents → Specialization
- **Validation Flow:** Document validation → Student approval → ValidationLog
- **AHP Calculation:** Criteria → AhpMatrix (pairwise) → CriterionWeight
- **SAW Calculation:** StudentCriterionValue → Normalization → SawResult → Ranking
- **Result Publication:** SawResult → Review → Publish → Student views

### 3. **Data Integrity**
- Data locking after SAW calculation (Student.hasSawCalculation())
- Validation requirements (all documents must be valid before approval)
- Consistency checking (AHP CR ≤ 0.1)
- Resubmission tracking (ValidationLog with counter)

### 4. **Access Control**
- Role-based access (Admin, Committee, Student)
- Permission checks in service layer
- Audit logging (ValidationLog, SelectionProcessLog)
- Actor tracking (created_by, validated_by, calculated_by)

---

## 🔄 Main Data Flows

### 1. **Registration Flow**
```
User (register) 
  → Student (create)
  → ReportGrade (input)
  → Document (upload)
  → Specialization (choose)
  → Validation (pending)
```

### 2. **Validation Flow**
```
Committee (review)
  → Document (validate each)
  → Student (check completeness)
  → Student (approve/reject)
  → ValidationLog (track)
```

### 3. **AHP Calculation Flow**
```
Admin (setup)
  → Criteria (define)
  → AhpMatrix (pairwise comparison)
  → Calculate CR (consistency check)
  → CriterionWeight (save weights)
```

### 4. **SAW Calculation Flow**
```
Committee (input values)
  → StudentCriterionValue (raw values)
  → Normalize (benefit/cost)
  → Apply weights
  → SawResult (final score)
  → Ranking (assign rank)
  → Acceptance (compare with quota)
```

### 5. **Result Publication Flow**
```
Committee (preview)
  → Review results
  → Publish
  → AcademicYear (update status)
  → Student (view result)
  → SelectionProcessLog (track)
```

---

## 💡 Design Patterns

### **Service Layer Pattern**
```
Controller → Service → Model
```
- Controllers handle HTTP requests
- Services contain business logic
- Models represent database entities

### **Repository Pattern (Implicit)**
- Eloquent ORM acts as repository
- Models have query scopes
- Relationships defined in models

### **Observer Pattern (Implicit)**
- Model events (creating, created, updating, updated)
- ValidationLog tracks all changes
- SelectionProcessLog tracks processes

---

## 🔍 Key Entities Explained

### **Student (Central Entity)**
- **30+ attributes:** Personal data, validation status, ranking, etc.
- **8 relationships:** User, AcademicYear, ReportGrade, Documents, TestScore, SawResults, CriterionValues, ValidationLogs
- **25+ methods:** Registration progress, validation checks, ranking info, acceptance status
- **Business rules:** Data locking after SAW, resubmission tracking, completeness checking

### **SawResult (Ranking Entity)**
- **Stores:** Final score, rank, specialization, detail calculation (JSON)
- **Determines:** Acceptance status based on rank vs quota
- **Tracks:** Calculator (User), calculation timestamp
- **Immutable:** Data locked after calculation

### **AhpMatrix (Weighting Entity)**
- **Stores:** Pairwise comparison values (1-9 scale)
- **Calculates:** Consistency Ratio (CR ≤ 0.1)
- **Extracts:** Priority weights (eigenvector)
- **Validates:** Matrix completeness before calculation

### **ValidationLog (Audit Entity)**
- **Tracks:** All validation actions (approved, rejected, resubmitted)
- **Records:** Actor, previous status, new status, notes
- **Counts:** Resubmission attempts
- **Metadata:** Additional context (JSON)

---

## 📝 Naming Conventions

### **Tables**
- Plural snake_case: `students`, `report_grades`, `saw_results`
- Pivot tables: `student_criterion_values`, `ahp_matrices`

### **Models**
- Singular PascalCase: `Student`, `ReportGrade`, `SawResult`
- Match table name (singular form)

### **Relationships**
- hasOne/hasMany: plural for many, singular for one
- belongsTo: singular
- Example: `student->documents()`, `document->student()`

### **Services**
- Suffix "Service": `ValidationService`, `AhpService`, `SawService`
- Descriptive method names: `calculateConsistencyRatio()`, `normalizeValues()`

---

## 🚀 Performance Considerations

### **Indexes**
- Primary keys: `id` (auto-indexed)
- Foreign keys: `user_id`, `student_id`, `academic_year_id`, etc.
- Unique indexes: `student_id`, `nisn`, `email`
- Composite indexes: `(academic_year_id, specialization)` for SAW queries

### **Eager Loading**
```php
// Avoid N+1 queries
Student::with(['user', 'reportGrade', 'documents', 'testScore'])->get();
SawResult::with(['student', 'academicYear', 'calculator'])->get();
Criteria::with(['weights', 'studentValues'])->get();
```

### **Caching Opportunities**
- Active Academic Year
- Active Specialization Quotas
- Criteria & Weights per specialization
- Published SAW Results
- AHP Matrix data

---

## 🔗 Integration with Existing Documentation

Class diagram melengkapi dokumentasi lain:

1. **Sequence Diagrams** (`docs/sequence-diagrams/`)
   - Sequence: Shows flow over time (when, who, what)
   - Class: Shows structure and relationships (what, how)

2. **Database Schema** (if exists)
   - Schema: Shows tables, columns, and constraints
   - Class: Shows models, methods, and business logic

3. **API Documentation** (if exists)
   - API: Shows endpoints, requests, and responses
   - Class: Shows controllers, services, and data flow

---

## ✅ Benefits

### **For Development Team**
✅ Clear understanding of system structure  
✅ Easy onboarding for new developers  
✅ Quick reference during development  
✅ Better code organization  
✅ Reduced coupling between modules  
✅ Easier refactoring and maintenance  

### **For Project Management**
✅ Visual system overview  
✅ Impact analysis for changes  
✅ Better estimation  
✅ Clear documentation  
✅ Easier communication with stakeholders  

### **For Quality Assurance**
✅ Understanding data flow  
✅ Test coverage planning  
✅ Integration test scenarios  
✅ Validation requirements  
✅ Edge case identification  

---

## 🎓 Learning Resources

### **Mermaid Syntax**
- Official Docs: https://mermaid.js.org/
- Class Diagram: https://mermaid.js.org/syntax/classDiagram.html
- Live Editor: https://mermaid.live/

### **UML Class Diagrams**
- Relationships: Association, Aggregation, Composition, Inheritance
- Multiplicity: 1, 0..1, 0..*, 1..*, *
- Visibility: + public, - private, # protected, ~ package

### **Laravel Patterns**
- Eloquent Relationships: hasOne, hasMany, belongsTo, belongsToMany
- Service Layer Pattern: Separate business logic from controllers
- Repository Pattern: Abstract data access layer
- Request Validation: Form requests for input validation

---

## 🔄 Maintenance

### **When to Update Diagram**
- ✅ Adding new model/entity
- ✅ Changing relationships
- ✅ Adding significant methods
- ✅ Modifying business logic
- ✅ Refactoring architecture

### **How to Update**
1. Open `docs/class-diagram.mmd`
2. Add/modify entity definition
3. Update relationships
4. Add notes if needed
5. Test rendering in VS Code or Mermaid Live
6. Commit changes with descriptive message

---

## 📞 Support

For questions or updates:
1. Review this CHANGELOG
2. Check the diagram file: `docs/class-diagram.mmd`
3. Consult sequence diagrams for flow: `docs/sequence-diagrams/`
4. Update diagram when code changes

---

**Status:** ✅ Complete  
**File Location:** `docs/class-diagram.mmd`  
**Next Steps:** Maintain diagram as system evolves  
**Related:** See `docs/sequence-diagrams/` for flow diagrams

