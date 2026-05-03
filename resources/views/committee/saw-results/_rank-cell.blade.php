@if($rank)
    <div class="flex flex-col items-center">
        @include('committee.saw-results._rank-badge', ['rank'=>$rank,'color'=>$color])
        @if($score)
            <span class="text-xs text-gray-400 mt-0.5">{{ number_format($score, 4) }}</span>
        @endif
    </div>
@else
    <span class="text-sm text-gray-300">-</span>
@endif