@extends('layouts.app')

@section('title', 'Manajemen Kriteria')

@section('content')
<!-- Header -->
<div class="mb-6 flex items-center justify-between">
    <div>
        <h2 class="text-2xl font-bold text-gray-800">Manajemen Kriteria</h2>
        <p class="text-sm text-gray-600 mt-1">Kelola kriteria penilaian untuk setiap spesializasi</p>
    </div>
    <a href="{{ route('admin.criterias.create') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg flex items-center gap-2 transition">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
        </svg>
        Tambah Kriteria
    </a>
</div>

<!-- Tabs untuk Specialization -->
<div class="mb-6 border-b border-gray-200">
    <nav class="-mb-px flex space-x-8">
        <button onclick="switchTab('tahfiz')" id="tab-tahfiz" class="tab-button border-b-2 border-indigo-500 py-4 px-1 text-sm font-medium text-indigo-600">
            Tahfiz
        </button>
        <button onclick="switchTab('language')" id="tab-language" class="tab-button border-b-2 border-transparent py-4 px-1 text-sm font-medium text-gray-500 hover:text-gray-700 hover:border-gray-300">
            Language
        </button>
    </nav>
</div>

<!-- Kriteria Tahfiz -->
<div id="content-tahfiz" class="tab-content">
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-800">Kriteria Peminatan Tahfiz</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kode</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Kriteria</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tipe Atribut</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Sumber Data</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Urutan</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($criteriasTahfiz as $criteria)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $criteria->code }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $criteria->name }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                            @if($criteria->attribute_type == 'benefit')
                                <span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-800">Benefit</span>
                            @else
                                <span class="px-2 py-1 text-xs rounded-full bg-red-100 text-red-800">Cost</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                            <code class="bg-gray-100 px-2 py-1 rounded text-xs">{{ $criteria->data_source ?? '-' }}</code>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $criteria->order }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($criteria->is_active)
                                <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Aktif</span>
                            @else
                                <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">Nonaktif</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm space-x-2">
                            <a href="{{ route('admin.criterias.edit', $criteria->id) }}" class="text-blue-600 hover:text-blue-800">Edit</a>
                            <form action="{{ route('admin.criterias.destroy', $criteria->id) }}" method="POST" class="inline" onsubmit="return confirm('Yakin ingin menghapus kriteria ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-800">Hapus</button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-8 text-center text-gray-500">
                            Belum ada kriteria untuk Tahfiz
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Kriteria Language -->
<div id="content-language" class="tab-content hidden">
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-800">Kriteria Peminatan Language</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kode</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Kriteria</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tipe Atribut</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Sumber Data</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Urutan</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($criteriasLanguage as $criteria)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $criteria->code }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $criteria->name }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                            @if($criteria->attribute_type == 'benefit')
                                <span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-800">Benefit</span>
                            @else
                                <span class="px-2 py-1 text-xs rounded-full bg-red-100 text-red-800">Cost</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                            <code class="bg-gray-100 px-2 py-1 rounded text-xs">{{ $criteria->data_source ?? '-' }}</code>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $criteria->order }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($criteria->is_active)
                                <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Aktif</span>
                            @else
                                <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">Nonaktif</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm space-x-2">
                            <a href="{{ route('admin.criterias.edit', $criteria->id) }}" class="text-blue-600 hover:text-blue-800">Edit</a>
                            <form action="{{ route('admin.criterias.destroy', $criteria->id) }}" method="POST" class="inline" onsubmit="return confirm('Yakin ingin menghapus kriteria ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-800">Hapus</button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-8 text-center text-gray-500">
                            Belum ada kriteria untuk Language
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@push('scripts')
<script>
function switchTab(tab) {
    // Hide all content
    document.querySelectorAll('.tab-content').forEach(el => el.classList.add('hidden'));
    // Remove active state from all tabs
    document.querySelectorAll('.tab-button').forEach(el => {
        el.classList.remove('border-indigo-500', 'text-indigo-600');
        el.classList.add('border-transparent', 'text-gray-500');
    });
    
    // Show selected content
    document.getElementById('content-' + tab).classList.remove('hidden');
    // Add active state to selected tab
    const selectedTab = document.getElementById('tab-' + tab);
    selectedTab.classList.remove('border-transparent', 'text-gray-500');
    selectedTab.classList.add('border-indigo-500', 'text-indigo-600');
}
</script>
@endpush
@endsection