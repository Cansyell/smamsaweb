@extends('layouts.app')

@section('title', ($student->reportGrade ? 'Edit' : 'Input') . ' Nilai Rapor – ' . $student->full_name)

@section('content')
{{-- Breadcrumb --}}
<nav class="mb-4 text-sm text-gray-500 flex items-center gap-1">
    <a href="{{ route('admin.report-grades.index') }}" class="hover:text-indigo-600 transition">Nilai Rapor</a>
    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
    </svg>
    <a href="{{ route('admin.report-grades.show', $student->id) }}"
       class="hover:text-indigo-600 transition">{{ $student->full_name }}</a>
    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
    </svg>
    <span class="text-gray-700 font-medium">{{ $student->reportGrade ? 'Edit Nilai' : 'Input Nilai' }}</span>
</nav>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

    {{-- Info Siswa --}}
    <div class="lg:col-span-1">
        <div class="bg-white rounded-lg shadow-md overflow-hidden sticky top-4">
            <div class="bg-indigo-600 px-5 py-4 flex items-center gap-3">
                <div class="w-10 h-10 rounded-full bg-white bg-opacity-20 flex items-center justify-center">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                </div>
                <div>
                    <h2 class="font-bold text-white leading-tight">{{ $student->full_name }}</h2>
                    <p class="text-indigo-200 text-xs">{{ $student->student_id }}</p>
                </div>
            </div>
            <div class="p-5 space-y-2 text-sm">
                <div class="flex justify-between">
                    <span class="text-gray-500">NISN</span>
                    <span class="font-medium">{{ $student->nisn }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-500">Jenis Kelamin</span>
                    <span class="font-medium">{{ $student->gender_label }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-500">Spesialisasi</span>
                    <span class="font-medium">{{ $student->specialization_label ?? '-' }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-500">Sekolah Asal</span>
                    <span class="font-medium text-right max-w-[140px]">{{ $student->previous_school ?? '-' }}</span>
                </div>
            </div>

            {{-- Tips --}}
            <div class="mx-4 mb-4 p-3 bg-blue-50 rounded-lg text-xs text-blue-700">
                <p class="font-semibold mb-1">💡 Petunjuk Pengisian</p>
                <ul class="space-y-1 list-disc list-inside">
                    <li>Nilai antara 0 – 100</li>
                    <li>Kosongkan jika mata pelajaran tidak ada</li>
                    <li>Rata-rata dihitung otomatis</li>
                </ul>
            </div>
        </div>
    </div>

    {{-- Form Input Nilai --}}
    <div class="lg:col-span-2">
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-800">
                    {{ $student->reportGrade ? 'Edit Nilai Rapor' : 'Input Nilai Rapor' }}
                </h3>
                <p class="text-gray-500 text-sm mt-1">Masukkan nilai tiap mata pelajaran dari rapor siswa</p>
            </div>

            <form action="{{ route('admin.report-grades.update', $student->id) }}"
                  method="POST" class="p-6 space-y-6">
                @csrf
                @method('PUT')

                @if($errors->any())
                    <div class="p-4 bg-red-50 border border-red-300 rounded-lg">
                        <p class="text-sm font-medium text-red-700 mb-1">Terdapat kesalahan pada input:</p>
                        <ul class="text-sm text-red-600 list-disc list-inside space-y-0.5">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @php
                    $grade    = $student->reportGrade;
                    $subjects = [
                        'islamic_studies'     => ['label' => 'Pendidikan Agama Islam (PAI)', 'icon' => '📖'],
                        'indonesian_language' => ['label' => 'Bahasa Indonesia',              'icon' => '🇮🇩'],
                        'english_language'    => ['label' => 'Bahasa Inggris',                'icon' => '🌐'],
                        'ppkn'                => ['label' => 'Pendidikan Kewarganegaraan',    'icon' => '🏛️'],
                        'mtk'                 => ['label' => 'Matematika',                    'icon' => '🔢'],
                        'ipa'                 => ['label' => 'Ilmu Pengetahuan Alam',         'icon' => '🔬'],
                        'seni_budaya'         => ['label' => 'Seni Budaya',                   'icon' => '🎨'],
                        'penjas'              => ['label' => 'Pendidikan Jasmani (Penjas)',   'icon' => '⚽'],
                        'prakarya'            => ['label' => 'Prakarya & Kewirausahaan',      'icon' => '🔧'],
                    ];
                @endphp

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    @foreach($subjects as $field => $info)
                        <div>
                            <label for="{{ $field }}"
                                   class="block text-sm font-medium text-gray-700 mb-1">
                                {{ $info['icon'] }} {{ $info['label'] }}
                            </label>
                            <div class="relative">
                                <input type="number"
                                       id="{{ $field }}"
                                       name="{{ $field }}"
                                       value="{{ old($field, $grade?->$field) }}"
                                       min="0" max="100" step="0.01"
                                       placeholder="0 – 100"
                                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 text-sm
                                              @error($field) border-red-400 @enderror">
                                @error($field)
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    @endforeach
                </div>

                {{-- Preview rata-rata (JS live) --}}
                <div class="p-4 bg-indigo-50 rounded-lg">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-indigo-800">Preview Rata-rata</p>
                            <p class="text-xs text-indigo-500">Dihitung otomatis dari nilai yang diisi</p>
                        </div>
                        <span id="live-avg" class="text-2xl font-bold text-indigo-700">—</span>
                    </div>
                </div>

                {{-- Tombol --}}
                <div class="flex gap-3 pt-2">
                    <button type="submit"
                            class="flex-1 flex items-center justify-center px-5 py-2.5 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition font-medium">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M5 13l4 4L19 7"/>
                        </svg>
                        {{ $grade ? 'Simpan Perubahan' : 'Simpan Nilai' }}
                    </button>
                    <a href="{{ route('admin.report-grades.show', $student->id) }}"
                       class="px-5 py-2.5 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition font-medium text-sm">
                        Batal
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Live average preview
    const fields = [
        'islamic_studies','indonesian_language','english_language',
        'ppkn','mtk','ipa','seni_budaya','penjas','prakarya'
    ];
    const liveAvg = document.getElementById('live-avg');

    function recalc() {
        const vals = fields
            .map(f => {
                const v = parseFloat(document.getElementById(f)?.value);
                return isNaN(v) ? null : v;
            })
            .filter(v => v !== null);

        if (vals.length === 0) {
            liveAvg.textContent = '—';
        } else {
            const avg = vals.reduce((a, b) => a + b, 0) / vals.length;
            liveAvg.textContent = avg.toFixed(2);
            liveAvg.style.color = avg >= 85 ? '#16a34a' : avg >= 75 ? '#2563eb' : avg >= 65 ? '#ca8a04' : '#dc2626';
        }
    }

    fields.forEach(f => {
        document.getElementById(f)?.addEventListener('input', recalc);
    });

    recalc(); // init
</script>
@endpush
@endsection