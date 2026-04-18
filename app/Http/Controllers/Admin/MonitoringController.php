<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

class MonitoringController extends Controller
{
    public function index()
    {
        // CPU Load
        $cpuLoad = 'Unavailable';

        if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
            @exec('wmic cpu get loadpercentage', $output);
            $cpuLoad = !empty($output[1]) ? trim($output[1]) . ' %' : 'Unknown';
        } else {
            // Linux/Mac
            $load    = sys_getloadavg();
            $cpuLoad = $load ? round($load[0], 2) . ' %' : 'Unavailable';
        }

        $memory     = $this->formatBytes(memory_get_usage(true));
        $memoryPeak = $this->formatBytes(memory_get_peak_usage(true));

        return view('admin.monitoring.index', compact('cpuLoad', 'memory', 'memoryPeak'));
    }

    private function formatBytes(int $bytes): string
    {
        if ($bytes >= 1073741824) return round($bytes / 1073741824, 2) . ' GB';
        if ($bytes >= 1048576)    return round($bytes / 1048576, 2)    . ' MB';
        if ($bytes >= 1024)       return round($bytes / 1024, 2)       . ' KB';
        return $bytes . ' B';
    }
}
