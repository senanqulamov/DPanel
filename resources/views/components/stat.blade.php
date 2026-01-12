@props(['label','value','icon'=>null])
{{--<div class="p-4 rounded-2xl border-0 bg-slate-800 dark:bg-slate-800 shadow-sm">--}}
{{--    <div class="flex items-center gap-2 mb-1">--}}
{{--        @if($icon)--}}
{{--            <x-icon name="{{$icon}}" class="w-4 h-4 text-slate-300"/>--}}
{{--        @endif--}}
{{--        <span class="text-sm uppercase tracking-wide text-slate-300">{{ $label }}</span>--}}
{{--    </div>--}}
{{--    <div class="text-xl font-semibold text-brand-primary">{{ $value }}</div>--}}
{{--</div>--}}

<div class="relative overflow-hidden rounded-2xl bg-white/80 dark:bg-slate-900/80 backdrop-blur-xl border border-gray-200/60 dark:border-slate-700/60 shadow-sm">
    <div class="p-5">
        <div class="flex items-start justify-between">
            <div>
                <div class="text-[10px] uppercase tracking-wider text-gray-500 dark:text-slate-400">{{ $label }}</div>
                <div class="text-2xl font-bold text-gray-900 dark:text-white">{{ $value }}</div>
            </div>
            <div class="w-12 h-12 rounded-xl bg-slate-900/5 dark:bg-white/5 border border-gray-200/60 dark:border-slate-700/60 flex items-center justify-center">
                <x-icon name="{{ $icon ?? 'chart-bar' }}" class="w-6 h-6 text-slate-200"/>
            </div>
        </div>
    </div>
</div>
