@extends('layouts.app')

@section('title', 'Monitoring Server')

@section('content')

<!-- Header Section -->
<div class="mb-6">
    <div class="flex flex-col md:flex-row md:items-center md:justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Monitoring Server</h1>
            <p class="text-gray-600 mt-1">Pantau performa CPU dan memori secara real-time</p>
        </div>
        <div class="mt-4 md:mt-0">
            <button onclick="window.location.reload()"
                    class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                </svg>
                Refresh
            </button>
        </div>
    </div>
</div>

<!-- Stats Cards -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">

    <!-- CPU Load -->
    <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-indigo-500">
        <div class="flex items-center justify-between mb-4">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-indigo-100 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 3H5a2 2 0 00-2 2v4m6-6h10a2 2 0 012 2v4M9 3v18m0 0h10a2 2 0 002-2V9M9 21H5a2 2 0 01-2-2V9m0 0h18"/>
                    </svg>
                </div>
                <div>
                    <p class="text-sm text-gray-500 font-medium">CPU Load</p>
                    <p class="text-xs text-gray-400">Beban prosesor saat ini</p>
                </div>
            </div>
            <span class="text-xs px-2 py-1 rounded-full bg-indigo-100 text-indigo-700 font-medium">Live</span>
        </div>
        <p class="text-3xl font-bold text-gray-800">{{ $cpuLoad }}</p>

        @php
            $cpuNum = (float) filter_var($cpuLoad, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
            $cpuColor = $cpuNum >= 80 ? 'bg-red-500' : ($cpuNum >= 50 ? 'bg-yellow-500' : 'bg-indigo-500');
            $cpuWidth = is_numeric($cpuNum) && $cpuNum > 0 ? min($cpuNum, 100) : 0;
        @endphp

        <div class="mt-4">
            <div class="flex justify-between text-xs text-gray-500 mb-1">
                <span>0%</span>
                <span>100%</span>
            </div>
            <div class="w-full bg-gray-200 rounded-full h-2">
                <div class="{{ $cpuColor }} h-2 rounded-full transition-all duration-700"
                     style="width: {{ $cpuWidth }}%"></div>
            </div>
        </div>
    </div>

    <!-- Memory Usage -->
    <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-emerald-500">
        <div class="flex items-center justify-between mb-4">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-emerald-100 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                    </svg>
                </div>
                <div>
                    <p class="text-sm text-gray-500 font-medium">Memory Usage</p>
                    <p class="text-xs text-gray-400">Penggunaan memori aplikasi</p>
                </div>
            </div>
            <span class="text-xs px-2 py-1 rounded-full bg-emerald-100 text-emerald-700 font-medium">Live</span>
        </div>
        <p class="text-3xl font-bold text-gray-800">{{ $memory }}</p>
        <p class="text-xs text-gray-500 mt-1">Memori yang sedang digunakan PHP</p>
    </div>

    <!-- Peak Memory -->
    <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-amber-500">
        <div class="flex items-center justify-between mb-4">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-amber-100 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                    </svg>
                </div>
                <div>
                    <p class="text-sm text-gray-500 font-medium">Peak Memory</p>
                    <p class="text-xs text-gray-400">Puncak penggunaan memori</p>
                </div>
            </div>
            <span class="text-xs px-2 py-1 rounded-full bg-amber-100 text-amber-700 font-medium">Max</span>
        </div>
        <p class="text-3xl font-bold text-gray-800">{{ $memoryPeak }}</p>
        <p class="text-xs text-gray-500 mt-1">Memori tertinggi yang pernah digunakan</p>
    </div>

</div>

<!-- System Info & Status -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">

    <!-- System Info -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
            <h3 class="text-sm font-semibold text-gray-700 uppercase tracking-wide flex items-center gap-2">
                <svg class="w-4 h-4 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14M5 12a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v4a2 2 0 01-2 2M5 12a2 2 0 00-2 2v4a2 2 0 002 2h14a2 2 0 002-2v-4a2 2 0 00-2-2m-2-4h.01M17 16h.01"/>
                </svg>
                Informasi Sistem
            </h3>
        </div>
        <div class="divide-y divide-gray-100">
            <div class="px-6 py-3 flex justify-between items-center">
                <span class="text-sm text-gray-500">Sistem Operasi</span>
                <span class="text-sm font-medium text-gray-900">{{ PHP_OS }}</span>
            </div>
            <div class="px-6 py-3 flex justify-between items-center">
                <span class="text-sm text-gray-500">Versi PHP</span>
                <span class="text-sm font-medium text-gray-900">{{ PHP_VERSION }}</span>
            </div>
            <div class="px-6 py-3 flex justify-between items-center">
                <span class="text-sm text-gray-500">Versi Laravel</span>
                <span class="text-sm font-medium text-gray-900">{{ app()->version() }}</span>
            </div>
            <div class="px-6 py-3 flex justify-between items-center">
                <span class="text-sm text-gray-500">Environment</span>
                <span class="text-sm font-medium">
                    <span class="px-2 py-1 text-xs rounded-full {{ app()->isProduction() ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                        {{ app()->environment() }}
                    </span>
                </span>
            </div>
            <div class="px-6 py-3 flex justify-between items-center">
                <span class="text-sm text-gray-500">Debug Mode</span>
                <span class="text-sm font-medium">
                    <span class="px-2 py-1 text-xs rounded-full {{ config('app.debug') ? 'bg-red-100 text-red-800' : 'bg-green-100 text-green-800' }}">
                        {{ config('app.debug') ? 'ON' : 'OFF' }}
                    </span>
                </span>
            </div>
            <div class="px-6 py-3 flex justify-between items-center">
                <span class="text-sm text-gray-500">Memory Limit PHP</span>
                <span class="text-sm font-medium text-gray-900">{{ ini_get('memory_limit') }}</span>
            </div>
            <div class="px-6 py-3 flex justify-between items-center">
                <span class="text-sm text-gray-500">Max Execution Time</span>
                <span class="text-sm font-medium text-gray-900">{{ ini_get('max_execution_time') }} detik</span>
            </div>
            <div class="px-6 py-3 flex justify-between items-center">
                <span class="text-sm text-gray-500">Waktu Server</span>
                <span class="text-sm font-medium text-gray-900">{{ now()->format('d F Y, H:i:s') }}</span>
            </div>
        </div>
    </div>

    <!-- Status Layanan -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
            <h3 class="text-sm font-semibold text-gray-700 uppercase tracking-wide flex items-center gap-2">
                <svg class="w-4 h-4 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                Status Layanan
            </h3>
        </div>
        <div class="divide-y divide-gray-100">

            @php
                // Database check
                try {
                    \DB::connection()->getPdo();
                    $dbStatus = true;
                } catch (\Exception $e) {
                    $dbStatus = false;
                }

                // Cache check
                try {
                    cache()->put('monitoring_ping', true, 5);
                    $cacheStatus = cache()->get('monitoring_ping') === true;
                } catch (\Exception $e) {
                    $cacheStatus = false;
                }

                // Storage check
                $storageStatus = is_writable(storage_path());

                // Queue check (simple: check if horizon/queue config exists)
                $queueDriver = config('queue.default');
            @endphp

            <div class="px-6 py-4 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center">
                        <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582 4 8 4"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-800">Database</p>
                        <p class="text-xs text-gray-500">{{ config('database.default') }}</p>
                    </div>
                </div>
                <span class="flex items-center gap-1.5 text-sm font-medium {{ $dbStatus ? 'text-green-600' : 'text-red-600' }}">
                    <span class="w-2 h-2 rounded-full {{ $dbStatus ? 'bg-green-500' : 'bg-red-500' }} animate-pulse"></span>
                    {{ $dbStatus ? 'Terhubung' : 'Error' }}
                </span>
            </div>

            <div class="px-6 py-4 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 bg-purple-100 rounded-lg flex items-center justify-center">
                        <svg class="w-4 h-4 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-800">Cache</p>
                        <p class="text-xs text-gray-500">{{ config('cache.default') }}</p>
                    </div>
                </div>
                <span class="flex items-center gap-1.5 text-sm font-medium {{ $cacheStatus ? 'text-green-600' : 'text-red-600' }}">
                    <span class="w-2 h-2 rounded-full {{ $cacheStatus ? 'bg-green-500' : 'bg-red-500' }} animate-pulse"></span>
                    {{ $cacheStatus ? 'Aktif' : 'Error' }}
                </span>
            </div>

            <div class="px-6 py-4 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 bg-amber-100 rounded-lg flex items-center justify-center">
                        <svg class="w-4 h-4 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-800">Storage</p>
                        <p class="text-xs text-gray-500">{{ storage_path() }}</p>
                    </div>
                </div>
                <span class="flex items-center gap-1.5 text-sm font-medium {{ $storageStatus ? 'text-green-600' : 'text-red-600' }}">
                    <span class="w-2 h-2 rounded-full {{ $storageStatus ? 'bg-green-500' : 'bg-red-500' }} animate-pulse"></span>
                    {{ $storageStatus ? 'Writable' : 'Read Only' }}
                </span>
            </div>

            <div class="px-6 py-4 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 bg-teal-100 rounded-lg flex items-center justify-center">
                        <svg class="w-4 h-4 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-800">Queue Driver</p>
                        <p class="text-xs text-gray-500">Antrian pekerjaan latar belakang</p>
                    </div>
                </div>
                <span class="px-2 py-1 text-xs rounded-full bg-teal-100 text-teal-800 font-medium">
                    {{ strtoupper($queueDriver) }}
                </span>
            </div>

        </div>
    </div>

</div>

<!-- Auto Refresh Info -->
<div class="bg-blue-50 border border-blue-200 rounded-lg px-6 py-4 flex items-center gap-3">
    <svg class="w-5 h-5 text-blue-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
    </svg>
    <p class="text-sm text-blue-700">
        Data diperbarui setiap kali halaman di-refresh. Klik tombol <strong>Refresh</strong> untuk mendapatkan data terbaru, atau halaman akan otomatis refresh setiap
        <strong id="countdown">60</strong> detik.
    </p>
</div>

@endsection

@push('scripts')
<script>
    // Auto refresh countdown
    let seconds = 60;
    const countdown = document.getElementById('countdown');

    setInterval(() => {
        seconds--;
        if (countdown) countdown.textContent = seconds;
        if (seconds <= 0) window.location.reload();
    }, 1000);
</script>
@endpush