@props(['label','value','icon'=>null])
<div class="p-4 rounded border bg-white shadow-sm">
    <div class="flex items-center gap-2 mb-1">
        @if($icon)
            <x-icon name="{{$icon}}" class="w-4 h-4 text-gray-500" />
        @endif
        <span class="text-xs uppercase tracking-wide text-gray-500">{{ $label }}</span>
    </div>
    <div class="text-xl font-semibold">{{ $value }}</div>
</div>
