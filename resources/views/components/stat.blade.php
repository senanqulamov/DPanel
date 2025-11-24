@props(['label','value','icon'=>null])
<div class="p-4 rounded-2xl border-0 bg-black shadow-sm">
    <div class="flex items-center gap-2 mb-1">
        @if($icon)
            <x-icon name="{{$icon}}" class="w-4 h-4 text-white" />
        @endif
        <span class="text-xs uppercase tracking-wide text-gray-100">{{ $label }}</span>
    </div>
    <div class="text-xl font-semibold text-accent">{{ $value }}</div>
</div>
