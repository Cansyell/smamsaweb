{{-- resources/views/committee/selection-results/partials/table.blade.php --}}
{{-- Variables: $students, $showBadge, $emptyMessage, $headerColor, $title, $subtitle --}}

<div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r {{ $headerColor }}">
    <div class="flex items-center justify-between">
        <div>
            <h3 class="text-lg font-semibold text-gray-800">{{ $title }}</h3>
            <p class="text-sm text-gray-600 mt-0.5">{{ $subtitle }}</p>
        </div>
        <span class="px-3 py-1 bg-white/70 border border-gray-200 text-gray-700 rounded-full text-sm font-semibold shadow-sm">
            {{ count($students) }} Peserta
        </span>
    </div>
</div>

<div class="overflow-x-auto">
    <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider w-12">No</th>
                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No. Registrasi</th>
                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">NISN</th>
                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Siswa</th>
                @if($showBadge)
                <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Kelas Diterima</th>
                @endif
                <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Hasil Seleksi</th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
            @forelse($students as $i => $student)
            <tr class="hover:bg-gray-50 transition-colors">

                {{-- Nomor urut --}}
                <td class="px-4 py-3 text-center text-sm text-gray-500 font-mono">{{ $i + 1 }}</td>

                {{-- No Registrasi --}}
                <td class="px-4 py-3 whitespace-nowrap">
                    <span class="text-sm font-mono text-gray-800">{{ $student->student_id ?? '-' }}</span>
                </td>

                {{-- NISN --}}
                <td class="px-4 py-3 whitespace-nowrap">
                    <span class="text-sm font-mono text-gray-800">{{ $student->nisn }}</span>
                </td>

                {{-- Nama Siswa --}}
                <td class="px-4 py-3 whitespace-nowrap">
                    <div class="flex items-center gap-3">
                        <div class="h-9 w-9 rounded-full flex items-center justify-center font-bold text-sm text-white flex-shrink-0
                            @if($student->specialization === 'tahfiz') bg-gradient-to-br from-green-500 to-emerald-600
                            @elseif($student->specialization === 'language') bg-gradient-to-br from-blue-500 to-cyan-600
                            @else bg-gradient-to-br from-purple-500 to-pink-600
                            @endif">
                            {{ strtoupper(substr($student->full_name, 0, 1)) }}
                        </div>
                        <div>
                            <div class="text-sm font-medium text-gray-900">{{ $student->full_name }}</div>
                            <div class="text-xs text-gray-400">{{ $student->user->email ?? '-' }}</div>
                        </div>
                    </div>
                </td>

                {{-- Badge kelas (hanya di tab "Semua") --}}
                @if($showBadge)
                <td class="px-4 py-3 whitespace-nowrap text-center">
                    @if($student->final_status === 'accepted')
                        @if($student->specialization === 'tahfiz')
                            <span class="px-3 py-1 inline-flex items-center gap-1 text-xs font-semibold rounded-full bg-gradient-to-r from-green-500 to-emerald-500 text-white shadow">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                                </svg>
                                Tahfiz
                            </span>
                        @elseif($student->specialization === 'language')
                            <span class="px-3 py-1 inline-flex items-center gap-1 text-xs font-semibold rounded-full bg-gradient-to-r from-blue-500 to-cyan-500 text-white shadow">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5h12M9 3v2m1.048 9.5A18.022 18.022 0 016.412 9m6.088 9h7M11 21l5-10 5 10M12.751 5C11.783 10.77 8.07 15.61 3 18.129"/>
                                </svg>
                                Bahasa
                            </span>
                        @else
                            <span class="px-3 py-1 inline-flex items-center gap-1 text-xs font-semibold rounded-full bg-gradient-to-r from-purple-500 to-pink-500 text-white shadow">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                Regular
                            </span>
                        @endif
                    @else
                        <span class="text-sm text-gray-400">-</span>
                    @endif
                </td>
                @endif

                {{-- Status --}}
                <td class="px-4 py-3 whitespace-nowrap text-center">
                    @if($student->final_status === 'accepted')
                        <span class="px-3 py-1 inline-flex items-center gap-1 text-xs font-bold rounded-full bg-gradient-to-r from-green-500 to-emerald-500 text-white shadow-md">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            LULUS
                        </span>
                    @elseif($student->final_status === 'rejected')
                        <span class="px-3 py-1 inline-flex items-center gap-1 text-xs font-bold rounded-full bg-gradient-to-r from-red-500 to-rose-500 text-white shadow-md">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            TIDAK LULUS
                        </span>
                    @else
                        <span class="px-3 py-1 inline-flex items-center gap-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-500">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            Menunggu
                        </span>
                    @endif
                </td>

            </tr>
            @empty
            <tr>
                <td colspan="{{ $showBadge ? 6 : 5 }}" class="px-6 py-12 text-center text-gray-400">
                    <svg class="mx-auto h-12 w-12 mb-3 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    <p class="font-semibold text-gray-500">{{ $emptyMessage }}</p>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>