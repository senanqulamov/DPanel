<div>
    <x-card>
        {{-- Header --}}
        <div class="mb-6">
            <div class="flex items-center justify-between mb-2">
                <h1 class="text-2xl font-bold text-gray-900 dark:text-gray-100">
                    {{ __('Field Assessment') }}
                </h1>
                <x-button icon="arrow-left" href="{{ route('supplier.rfq.show', $request) }}" color="secondary" size="sm">
                    {{ __('Back to RFQ') }}
                </x-button>
            </div>
            <p class="text-sm text-gray-600 dark:text-gray-400">
                {{ $request->title }} - {{ $request->request_number }}
            </p>
            <p class="text-xs text-yellow-600 dark:text-yellow-400 mt-1">
                {{ __('This RFQ requires you to submit a field assessment before quoting.') }}
            </p>
        </div>

        <form wire:submit="save" class="space-y-6">
            {{-- Location Information --}}
            <div class="bg-blue-50 dark:bg-blue-900/20 rounded-lg p-4">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4 flex items-center gap-2">
                    <x-icon name="map-pin" class="w-5 h-5" />
                    {{ __('Site Location') }}
                </h3>

                <div class="space-y-4">
                    <x-input
                        wire:model="assessment.site_location"
                        label="{{ __('Location Address') }}"
                        placeholder="{{ __('Enter the site address') }}"
                        hint="{{ __('Required') }}"
                        required
                    />

                    <x-textarea
                        wire:model="assessment.accessibility_notes"
                        label="{{ __('Access Notes') }}"
                        placeholder="{{ __('How to access the site, parking, etc.') }}"
                        rows="2"
                    />

                    {{-- GPS Capture Button --}}
                    <div>
                        <x-button
                            type="button"
                            color="secondary"
                            icon="map-pin"
                            x-data
                            @click="
                                if (navigator.geolocation) {
                                    navigator.geolocation.getCurrentPosition(
                                        (position) => {
                                            $wire.latitude = position.coords.latitude;
                                            $wire.longitude = position.coords.longitude;
                                            alert('{{ __('Location captured!') }}');
                                        },
                                        (error) => {
                                            alert('{{ __('Error getting location') }}');
                                        }
                                    );
                                } else {
                                    alert('{{ __('Geolocation not supported') }}');
                                }
                            "
                        >
                            {{ __('Capture GPS Location') }}
                        </x-button>
                        @if($latitude && $longitude)
                            <p class="text-xs text-green-600 dark:text-green-400 mt-2">
                                ✓ {{ __('Location captured') }}: {{ number_format($latitude, 6) }}, {{ number_format($longitude, 6) }}
                            </p>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Site Condition --}}
            <div class="bg-green-50 dark:bg-green-900/20 rounded-lg p-4">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">
                    {{ __('Site Condition') }}
                </h3>

                <x-textarea
                    wire:model="assessment.current_condition"
                    label="{{ __('Current Condition') }}"
                    placeholder="{{ __('Describe what you see at the site...') }}"
                    rows="4"
                    hint="{{ __('Required') }}"
                    required
                />
            </div>

            {{-- Technical Assessment --}}
            <div class="bg-purple-50 dark:bg-purple-900/20 rounded-lg p-4">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">
                    {{ __('Technical Assessment') }}
                </h3>

                <div class="space-y-4">
                    <x-textarea
                        wire:model="assessment.technical_feasibility"
                        label="{{ __('Technical Feasibility') }}"
                        placeholder="{{ __('Can this project be completed? Any challenges?') }}"
                        rows="3"
                        hint="{{ __('Required') }}"
                        required
                    />

                    <x-textarea
                        wire:model="assessment.technical_compliance"
                        label="{{ __('Technical Compliance') }}"
                        placeholder="{{ __('Does it meet technical standards/requirements?') }}"
                        rows="2"
                    />

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <x-input
                            wire:model="assessment.estimated_duration"
                            type="number"
                            min="1"
                            label="{{ __('Estimated Duration') }}"
                        />

                        <x-select.styled
                            label="{{ __('Duration Unit') }}"
                            wire:model="assessment.duration_unit"
                            :options="[
                                ['label' => __('Hours'), 'value' => 'hours'],
                                ['label' => __('Days'), 'value' => 'days'],
                                ['label' => __('Weeks'), 'value' => 'weeks'],
                            ]"
                            select="label:label|value:value"
                        />
                    </div>
                </div>
            </div>

            {{-- Price Recommendation --}}
            <div class="bg-yellow-50 dark:bg-yellow-900/20 rounded-lg p-4">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">
                    {{ __('Price Recommendation') }}
                </h3>

                <div class="space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <x-input
                            wire:model="assessment.recommended_price"
                            type="number"
                            step="0.01"
                            min="0"
                            label="{{ __('Recommended Price') }}"
                            hint="{{ __('Required') }}"
                            required
                        />

                        <x-select.styled
                            label="{{ __('Currency') }}"
                            wire:model="assessment.currency"
                            :options="[
                                ['label' => 'AZN', 'value' => 'AZN'],
                                ['label' => 'USD', 'value' => 'USD'],
                                ['label' => 'EUR', 'value' => 'EUR'],
                            ]"
                            select="label:label|value:value"
                        />
                    </div>

                    <x-textarea
                        wire:model="assessment.price_justification"
                        label="{{ __('Price Justification') }}"
                        placeholder="{{ __('Explain why you recommend this price based on your site assessment') }}"
                        rows="3"
                        hint="{{ __('Required') }}"
                        required
                    />
                </div>
            </div>

            {{-- Risk Assessment --}}
            <div class="bg-red-50 dark:bg-red-900/20 rounded-lg p-4">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">
                    {{ __('Risk Assessment') }}
                </h3>

                <div class="space-y-4">
                    <x-textarea
                        wire:model="assessment.risks_identified"
                        label="{{ __('Risks Identified') }}"
                        placeholder="{{ __('List any potential risks or concerns') }}"
                        rows="3"
                    />

                    <x-textarea
                        wire:model="assessment.mitigation_recommendations"
                        label="{{ __('How to Mitigate Risks') }}"
                        placeholder="{{ __('Suggestions to reduce or eliminate identified risks') }}"
                        rows="3"
                    />
                </div>
            </div>

            {{-- Photos --}}
            <div class="bg-gray-50 dark:bg-gray-800 rounded-lg p-4">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">
                    {{ __('Site Photos') }}
                </h3>

                <div>
                    <input
                        type="file"
                        wire:model="photos"
                        multiple
                        accept="image/*"
                        capture="environment"
                        class="block w-full text-sm text-gray-900 dark:text-gray-100 border border-gray-300 dark:border-gray-700 rounded-lg cursor-pointer bg-white dark:bg-gray-800"
                    />
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                        {{ __('Upload photos from site (max 5MB each)') }}
                    </p>
                </div>

                @if ($photos)
                    <div class="mt-4 grid grid-cols-2 md:grid-cols-4 gap-2">
                        @foreach ($photos as $photo)
                            <div class="relative">
                                <img src="{{ $photo->temporaryUrl() }}" class="w-full h-32 object-cover rounded">
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            {{-- Additional Notes --}}
            <div>
                <x-textarea
                    wire:model="assessment.notes"
                    label="{{ __('Additional Notes') }}"
                    placeholder="{{ __('Any other relevant information') }}"
                    rows="3"
                />
            </div>

            {{-- Actions --}}
            <div class="flex items-center justify-between pt-4 border-t border-gray-200 dark:border-gray-700">
                <x-button type="button" href="{{ route('supplier.rfq.show', $request) }}" color="secondary">
                    {{ __('Cancel') }}
                </x-button>

                <x-button type="submit" color="primary" icon="paper-airplane">
                    {{ __('Submit Assessment') }}
                </x-button>
            </div>
        </form>
    </x-card>
</div>
