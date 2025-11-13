@php
    $currentLocale = app()->getLocale();
    $languages = config('languages.supported');
@endphp

<x-dropdown position="bottom-start">
    <x-slot:action>
        <button type="button" class="flex items-center gap-2 px-3 py-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors">
            <span class="text-xl">{{ $languages[$currentLocale]['flag'] ?? '🌐' }}</span>
            <span class="text-sm font-medium">{{ $languages[$currentLocale]['name'] ?? __('Language') }}</span>
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
            </svg>
        </button>
    </x-slot:action>

    @foreach($languages as $code => $language)
        <x-dropdown.items
            :text="$language['flag'] . ' ' . $language['name']"
            :href="route('lang.switch', $code)"
            :separator="$loop->last"
        />
    @endforeach
</x-dropdown>
