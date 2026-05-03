@if($rank == 1)
    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-bold bg-yellow-100 text-yellow-800">🥇 #1</span>
@elseif($rank == 2)
    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-bold bg-gray-200 text-gray-700">🥈 #2</span>
@elseif($rank == 3)
    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-bold bg-orange-100 text-orange-700">🥉 #3</span>
@else
    <span class="px-2 py-1 rounded-full text-xs font-bold bg-{{ $color }}-100 text-{{ $color }}-800">#{{ $rank }}</span>
@endif