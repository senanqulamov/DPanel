<div>
    <x-card>
        {{-- Header --}}
        <div class="mb-6">
            <div class="flex items-center justify-between mb-2">
                <h1 class="text-2xl font-bold text-gray-900 dark:text-gray-100">
                    {{ __('Your Field Assessment') }}
                </h1>
                <x-button icon="arrow-left" href="{{ route('supplier.rfq.show', $request) }}" color="secondary" size="sm">
                    {{ __('Back to RFQ') }}
                </x-button>
            </div>
            <p class="text-sm text-gray-600 dark:text-gray-400">
                {{ $request->title }} - {{ $request->request_number }}
            </p>
        </div>

        @if($fieldAssessment)
            <div class="space-y-6">
                {{-- Status --}}
                <div class="bg-blue-50 dark:bg-blue-900/20 rounded-lg p-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">
                                {{ __('Assessment Status') }}
                            </h3>
                            <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                                {{ __('Submitted') }}: {{ $fieldAssessment->submitted_at?->format('M d, Y H:i') }}
                            </p>
                        </div>
                        <x-badge
                            :text="ucfirst($fieldAssessment->status)"
                            :color="$fieldAssessment->status === 'submitted' ? 'green' : 'blue'"
                        />
                    </div>
                </div>

                {{-- Location --}}
                <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-4">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">
                        {{ __('Site Location') }}
                    </h3>
                    <div class="space-y-2 text-sm">
                        <div>
                            <span class="font-medium text-gray-700 dark:text-gray-300">{{ __('Address') }}:</span>
                            <span class="text-gray-900 dark:text-gray-100 ml-2">{{ $fieldAssessment->site_location }}</span>
                        </div>
                        @if($fieldAssessment->latitude && $fieldAssessment->longitude)
                            <div>
                                <span class="font-medium text-gray-700 dark:text-gray-300">{{ __('GPS') }}:</span>
                                <span class="text-gray-900 dark:text-gray-100 ml-2">
                                    {{ number_format($fieldAssessment->latitude, 6) }}, {{ number_format($fieldAssessment->longitude, 6) }}
                                </span>
                            </div>
                        @endif
                        @if($fieldAssessment->accessibility_notes)
                            <div>
                                <span class="font-medium text-gray-700 dark:text-gray-300">{{ __('Access Notes') }}:</span>
                                <p class="text-gray-900 dark:text-gray-100 mt-1">{{ $fieldAssessment->accessibility_notes }}</p>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Condition --}}
                <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-4">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">
                        {{ __('Site Condition') }}
                    </h3>
                    <p class="text-sm text-gray-900 dark:text-gray-100 whitespace-pre-line">{{ $fieldAssessment->current_condition }}</p>
                </div>

                {{-- Technical --}}
                <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-4">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">
                        {{ __('Technical Assessment') }}
                    </h3>
                    <div class="space-y-3 text-sm">
                        <div>
                            <span class="font-medium text-gray-700 dark:text-gray-300">{{ __('Feasibility') }}:</span>
                            <p class="text-gray-900 dark:text-gray-100 mt-1">{{ $fieldAssessment->technical_feasibility }}</p>
                        </div>
                        @if($fieldAssessment->technical_compliance)
                            <div>
                                <span class="font-medium text-gray-700 dark:text-gray-300">{{ __('Compliance') }}:</span>
                                <p class="text-gray-900 dark:text-gray-100 mt-1">{{ $fieldAssessment->technical_compliance }}</p>
                            </div>
                        @endif
                        @if($fieldAssessment->estimated_duration)
                            <div>
                                <span class="font-medium text-gray-700 dark:text-gray-300">{{ __('Duration') }}:</span>
                                <span class="text-gray-900 dark:text-gray-100 ml-2">
                                    {{ $fieldAssessment->estimated_duration }} {{ __($fieldAssessment->duration_unit) }}
                                </span>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Price --}}
                <div class="bg-yellow-50 dark:bg-yellow-900/20 rounded-lg border border-yellow-200 dark:border-yellow-700 p-4">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">
                        {{ __('Price Recommendation') }}
                    </h3>
                    <div class="space-y-3">
                        <div>
                            <span class="text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Recommended Price') }}:</span>
                            <span class="text-2xl font-bold text-gray-900 dark:text-gray-100 ml-2">
                                {{ $fieldAssessment->currency }} {{ number_format($fieldAssessment->recommended_price, 2) }}
                            </span>
                        </div>
                        <div>
                            <span class="text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Justification') }}:</span>
                            <p class="text-sm text-gray-900 dark:text-gray-100 mt-1">{{ $fieldAssessment->price_justification }}</p>
                        </div>
                    </div>
                </div>

                {{-- Risks --}}
                @if($fieldAssessment->risks_identified || $fieldAssessment->mitigation_recommendations)
                    <div class="bg-red-50 dark:bg-red-900/20 rounded-lg border border-red-200 dark:border-red-700 p-4">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">
                            {{ __('Risk Assessment') }}
                        </h3>
                        <div class="space-y-3 text-sm">
                            @if($fieldAssessment->risks_identified)
                                <div>
                                    <span class="font-medium text-gray-700 dark:text-gray-300">{{ __('Risks') }}:</span>
                                    <p class="text-gray-900 dark:text-gray-100 mt-1">{{ $fieldAssessment->risks_identified }}</p>
                                </div>
                            @endif
                            @if($fieldAssessment->mitigation_recommendations)
                                <div>
                                    <span class="font-medium text-gray-700 dark:text-gray-300">{{ __('Mitigation') }}:</span>
                                    <p class="text-gray-900 dark:text-gray-100 mt-1">{{ $fieldAssessment->mitigation_recommendations }}</p>
                                </div>
                            @endif
                        </div>
                    </div>
                @endif

                {{-- Photos --}}
                @if($fieldAssessment->photos && count($fieldAssessment->photos) > 0)
                    <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-4">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">
                            {{ __('Site Photos') }}
                        </h3>
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                            @foreach($fieldAssessment->photos as $photo)
                                <a href="{{ Storage::url($photo) }}" target="_blank">
                                    <img src="{{ Storage::url($photo) }}" alt="Site photo" class="w-full h-32 object-cover rounded hover:opacity-75">
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif

                {{-- Notes --}}
                @if($fieldAssessment->notes)
                    <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-4">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">
                            {{ __('Additional Notes') }}
                        </h3>
                        <p class="text-sm text-gray-900 dark:text-gray-100 whitespace-pre-line">{{ $fieldAssessment->notes }}</p>
                    </div>
                @endif
            </div>
        @else
            <div class="bg-gray-50 dark:bg-gray-800 rounded-lg p-8 text-center">
                <p class="text-gray-600 dark:text-gray-400">
                    {{ __('No field assessment found.') }}
                </p>
            </div>
        @endif
    </x-card>
</div>
