<span>
    @if($count > 0)
        <span class="inline-flex items-center justify-center min-w-5 h-5 px-1.5 text-xs font-bold text-white bg-blue-600 rounded-full">
            {{ $count > 99 ? '99+' : $count }}
        </span>
    @endif
</span>
