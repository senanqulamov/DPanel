<div>
    <x-card>
        <x-alert color="black" icon="cog">
            @lang('Project Settings') <span class="text-xs ml-2 text-yellow-500">(Demo Mode)</span>
        </x-alert>

        <div class="mt-6 space-y-8">
            <!-- General Settings -->
            <div>
                <h3 class="text-lg font-semibold mb-4 flex items-center gap-2">
                    <x-icon name="adjustments-horizontal" class="w-5 h-5"/>
                    {{ __('General Settings') }}
                </h3>

                <form wire:submit="saveGeneral" class="space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <x-input
                            label="{{ __('Application Name') }}"
                            wire:model="app_name"
                            hint="{{ __('The name of your application') }}"
                        />

                        <x-input
                            label="{{ __('Application URL') }}"
                            wire:model="app_url"
                            hint="{{ __('The URL of your application') }}"
                        />

                        <x-select.styled
                            label="{{ __('Timezone') }}"
                            wire:model="app_timezone"
                            :options="[
                                ['label' => 'UTC', 'value' => 'UTC'],
                                ['label' => 'America/New_York', 'value' => 'America/New_York'],
                                ['label' => 'America/Chicago', 'value' => 'America/Chicago'],
                                ['label' => 'America/Los_Angeles', 'value' => 'America/Los_Angeles'],
                                ['label' => 'Europe/London', 'value' => 'Europe/London'],
                                ['label' => 'Europe/Paris', 'value' => 'Europe/Paris'],
                                ['label' => 'Asia/Tokyo', 'value' => 'Asia/Tokyo'],
                            ]"
                            select="label:label|value:value"
                            hint="{{ __('Application timezone') }}"
                            searchable
                        />

                        <x-select.styled
                            label="{{ __('Locale') }}"
                            wire:model="app_locale"
                            :options="[
                                ['label' => 'English', 'value' => 'en'],
                                ['label' => 'Spanish', 'value' => 'es'],
                                ['label' => 'French', 'value' => 'fr'],
                                ['label' => 'German', 'value' => 'de'],
                                ['label' => 'Turkish', 'value' => 'tr'],
                            ]"
                            select="label:label|value:value"
                            hint="{{ __('Default language') }}"
                            searchable
                        />
                    </div>

                    <x-button type="submit" color="primary" icon="check">
                        {{ __('Save General Settings') }}
                    </x-button>
                </form>
            </div>

            <div class="relative my-8">
                <div class="absolute inset-0 flex items-center">
                    <div class="w-full border-t border-gray-300 dark:border-gray-700"></div>
                </div>
                <div class="relative flex justify-center text-sm">
                    <span class="px-2 bg-white dark:bg-gray-900 text-gray-500">{{ __('Email Configuration') }}</span>
                </div>
            </div>

            <!-- Mail Settings -->
            <div>
                <h3 class="text-lg font-semibold mb-4 flex items-center gap-2">
                    <x-icon name="envelope" class="w-5 h-5"/>
                    {{ __('Mail Settings') }}
                </h3>

                <form wire:submit="saveMail" class="space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <x-input
                            label="{{ __('Mail Driver') }}"
                            wire:model="mail_driver"
                            hint="{{ __('smtp, sendmail, mailgun, etc.') }}"
                        />

                        <x-input
                            label="{{ __('Mail Host') }}"
                            wire:model="mail_host"
                            hint="{{ __('SMTP server address') }}"
                        />

                        <x-input
                            label="{{ __('Mail Port') }}"
                            wire:model="mail_port"
                            hint="{{ __('SMTP server port') }}"
                        />

                        <x-input
                            label="{{ __('Mail Username') }}"
                            wire:model="mail_username"
                            hint="{{ __('SMTP username') }}"
                        />

                        <x-password
                            label="{{ __('Mail Password') }}"
                            wire:model="mail_password"
                            hint="{{ __('SMTP password') }}"
                        />

                        <x-input
                            label="{{ __('From Address') }}"
                            wire:model="mail_from_address"
                            hint="{{ __('Default sender email') }}"
                        />

                        <x-input
                            label="{{ __('From Name') }}"
                            wire:model="mail_from_name"
                            hint="{{ __('Default sender name') }}"
                        />
                    </div>

                    <x-button type="submit" color="primary" icon="check">
                        {{ __('Save Mail Settings') }}
                    </x-button>
                </form>
            </div>

            <div class="relative my-8">
                <div class="absolute inset-0 flex items-center">
                    <div class="w-full border-t border-gray-300 dark:border-gray-700"></div>
                </div>
                <div class="relative flex justify-center text-sm">
                    <span class="px-2 bg-white dark:bg-gray-900 text-gray-500">{{ __('System') }}</span>
                </div>
            </div>

            <!-- Maintenance Mode -->
            <div>
                <h3 class="text-lg font-semibold mb-4 flex items-center gap-2">
                    <x-icon name="wrench-screwdriver" class="w-5 h-5"/>
                    {{ __('Maintenance Mode') }}
                </h3>

                <div class="flex items-center justify-between p-4 bg-gray-50 dark:bg-gray-800 rounded-lg">
                    <div>
                        <p class="font-medium">{{ __('Enable Maintenance Mode') }}</p>
                        <p class="text-sm text-gray-600 dark:text-gray-400">
                            {{ __('Temporarily disable public access to the application') }}
                        </p>
                    </div>
                    <x-toggle wire:model.live="maintenance_mode" wire:change="toggleMaintenance"/>
                </div>

                @if($maintenance_mode)
                    <x-banner
                        text="{{ __('Maintenance mode is currently enabled') }}"
                        color="yellow"
                        class="mt-4"
                    />
                @endif
            </div>

            <!-- Demo Warning -->
            <x-alert color="yellow" icon="exclamation-triangle">
                <div>
                    <strong>{{ __('Demo Mode Active') }}</strong>
                    <p class="text-sm mt-1">
                        {{ __('This is a demo interface. Changes will not be persisted to configuration files. In production, these settings would update your .env file or database configuration.') }}
                    </p>
                </div>
            </x-alert>
        </div>
    </x-card>
</div>
