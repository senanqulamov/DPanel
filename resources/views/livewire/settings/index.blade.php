<div>
    <!-- Header -->
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-slate-400 dark:text-slate-100 flex items-center gap-3">
            <x-icon name="cog-6-tooth" class="w-8 h-8 text-red-500"/>
            {{ __('System Settings') }}
        </h1>
        <p class="text-gray-500 dark:text-slate-400 mt-2">
            {{ __('Configure and manage your SAP procurement dashboard settings') }}
        </p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
        <!-- Sidebar Navigation -->
        <div class="lg:col-span-1">
            <div class="bg-white dark:bg-slate-900 rounded-xl border border-slate-200 dark:border-slate-700 overflow-hidden">
                <div class="p-4 border-b border-slate-200 dark:border-slate-700">
                    <h3 class="font-semibold text-slate-300 dark:text-slate-100">{{ __('Settings') }}</h3>
                </div>
                <nav class="p-2">
                    @php
                        $tabs = [
                            'general' => ['icon' => 'adjustments-horizontal', 'label' => 'General'],
                            'sap' => ['icon' => 'server-stack', 'label' => 'SAP Integration'],
                            'mail' => ['icon' => 'envelope', 'label' => 'Email'],
                            'database' => ['icon' => 'circle-stack', 'label' => 'Database'],
                            'cache' => ['icon' => 'bolt', 'label' => 'Cache & Queue'],
                            'security' => ['icon' => 'shield-check', 'label' => 'Security'],
                            'api' => ['icon' => 'code-bracket', 'label' => 'API'],
                            'notifications' => ['icon' => 'bell', 'label' => 'Notifications'],
                            'business' => ['icon' => 'briefcase', 'label' => 'Business Rules'],
                            'files' => ['icon' => 'document', 'label' => 'File Uploads'],
                            'system' => ['icon' => 'computer-desktop', 'label' => 'System Info'],
                        ];
                    @endphp

                    @foreach($tabs as $key => $tab)
                        <button
                            wire:click="$set('activeTab', '{{ $key }}')"
                            class="w-full flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition cursor-pointer {{ $activeTab === $key ? 'bg-red-500 text-white' : 'text-slate-300 dark:text-slate-400 hover:bg-slate-600 dark:hover:bg-slate-800' }}">
                            <x-icon name="{{ $tab['icon'] }}" class="w-5 h-5"/>
                            {{ $tab['label'] }}
                        </button>
                    @endforeach
                </nav>
            </div>

            <!-- Quick Actions -->
            <div class="mt-4 bg-white dark:bg-slate-900 rounded-xl border border-slate-200 dark:border-slate-700 p-4">
                <h4 class="font-semibold text-sm text-slate-300 dark:text-slate-100 mb-3">Quick Actions</h4>
                <div class="space-y-2">
                    <button wire:click="clearCache" class="w-full text-left px-3 py-2 rounded-lg text-sm text-slate-400 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800 transition">
                        <x-icon name="trash" class="w-4 h-4 inline mr-2"/>
                        Clear Cache
                    </button>
                    <button wire:click="clearLogs" class="w-full text-left px-3 py-2 rounded-lg text-sm text-slate-400 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800 transition">
                        <x-icon name="document-minus" class="w-4 h-4 inline mr-2"/>
                        Clear Logs
                    </button>
                    <button wire:click="toggleMaintenance"
                            class="w-full text-left px-3 py-2 rounded-lg text-sm {{ $maintenance_mode ? 'text-red-600 hover:bg-red-50' : 'text-slate-400 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800' }} transition">
                        <x-icon name="wrench-screwdriver" class="w-4 h-4 inline mr-2"/>
                        {{ $maintenance_mode ? 'Disable' : 'Enable' }} Maintenance
                    </button>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="lg:col-span-3">
            <x-card>

                @if($maintenance_mode)
                    <x-alert color="yellow" icon="exclamation-triangle" class="mb-4">
                        <strong>{{__('Maintenance Mode Active')}}</strong> -{{ __('Public access is currently restricted')}}
                    </x-alert>
                @endif

                <!-- Tab Content -->
                <div class="space-y-6">
                    {{-- General Settings --}}
                    @if($activeTab === 'general')
                        <div>
                            <h3 class="text-xl font-semibold mb-4">General Settings</h3>
                            <form wire:submit="saveGeneral" class="space-y-4">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <x-input label="Application Name" wire:model="app_name" hint="Display name of your application"/>
                                    <x-input label="Application URL" wire:model="app_url" hint="Base URL"/>
                                    <x-select.styled
                                        label="Timezone"
                                        wire:model="app_timezone"
                                        :options="[
                                            ['label' => 'UTC', 'value' => 'UTC'],
                                            ['label' => 'America/New_York', 'value' => 'America/New_York'],
                                            ['label' => 'Europe/London', 'value' => 'Europe/London'],
                                            ['label' => 'Europe/Berlin', 'value' => 'Europe/Berlin'],
                                            ['label' => 'Asia/Tokyo', 'value' => 'Asia/Tokyo'],
                                            ['label' => 'Asia/Dubai', 'value' => 'Asia/Dubai'],
                                        ]"
                                        select="label:label|value:value"
                                        searchable
                                    />
                                    <x-select.styled
                                        label="Default Language"
                                        wire:model="app_locale"
                                        :options="[
                                            ['label' => 'English', 'value' => 'en'],
                                            ['label' => 'Deutsch', 'value' => 'de'],
                                            ['label' => 'Español', 'value' => 'es'],
                                            ['label' => 'Français', 'value' => 'fr'],
                                            ['label' => 'Türkçe', 'value' => 'tr'],
                                            ['label' => 'Azərbaycanca', 'value' => 'az'],
                                        ]"
                                        select="label:label|value:value"
                                        searchable
                                    />
                                    <x-select.styled
                                        label="Environment"
                                        wire:model="app_env"
                                        :options="[
                                            ['label' => 'Production', 'value' => 'production'],
                                            ['label' => 'Staging', 'value' => 'staging'],
                                            ['label' => 'Development', 'value' => 'development'],
                                            ['label' => 'Local', 'value' => 'local'],
                                        ]"
                                        select="label:label|value:value"
                                    />
                                    <div class="flex items-center gap-3 p-4 bg-slate-50 dark:bg-slate-800 rounded-lg">
                                        <x-toggle wire:model.live="app_debug"/>
                                        <div>
                                            <p class="font-medium text-sm">Debug Mode</p>
                                            <p class="text-xs text-slate-500">Show detailed error messages</p>
                                        </div>
                                    </div>
                                </div>
                                <x-button type="submit" color="primary" icon="check">Save General Settings</x-button>
                            </form>
                        </div>
                    @endif

                    {{-- SAP Integration --}}
                    @if($activeTab === 'sap')
                        <div>
                            <h3 class="text-xl font-semibold mb-4">SAP Integration Settings</h3>
                            <form wire:submit="saveSap" class="space-y-4">
                                <div class="flex items-center gap-3 p-4 bg-slate-50 dark:bg-slate-800 rounded-lg mb-4">
                                    <x-toggle wire:model.live="sap_enabled"/>
                                    <div>
                                        <p class="font-medium text-sm">Enable SAP Integration</p>
                                        <p class="text-xs text-slate-500">Connect to SAP ERP system</p>
                                    </div>
                                </div>
                                @if($sap_enabled)
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <x-input label="SAP Host" wire:model="sap_host" hint="SAP server hostname"/>
                                        <x-input label="SAP Client" wire:model="sap_client" hint="SAP client number"/>
                                        <x-input label="SAP Username" wire:model="sap_username"/>
                                        <x-password label="SAP Password" wire:model="sap_password"/>
                                        <x-select.styled
                                            label="Language"
                                            wire:model="sap_language"
                                            :options="[
                                                ['label' => 'English', 'value' => 'EN'],
                                                ['label' => 'German', 'value' => 'DE'],
                                                ['label' => 'French', 'value' => 'FR'],
                                            ]"
                                            select="label:label|value:value"
                                        />
                                        <x-input label="Sync Interval (minutes)" wire:model="sap_sync_interval" type="number"/>
                                        <div class="flex items-center gap-3 p-4 bg-slate-50 dark:bg-slate-800 rounded-lg">
                                            <x-toggle wire:model.live="sap_auto_sync"/>
                                            <div>
                                                <p class="font-medium text-sm">Auto Sync</p>
                                                <p class="text-xs text-slate-500">Automatically sync data</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="flex gap-2">
                                        <x-button type="submit" color="primary" icon="check">Save SAP Settings</x-button>
                                        <x-button type="button" wire:click="testSapConnection" color="secondary" icon="signal">Test Connection</x-button>
                                    </div>
                                @else
                                    <x-alert color="blue" icon="information-circle">
                                        Enable SAP integration to configure connection settings
                                    </x-alert>
                                @endif
                            </form>
                        </div>
                    @endif

                    {{-- Mail Settings --}}
                    @if($activeTab === 'mail')
                        <div>
                            <h3 class="text-xl font-semibold mb-4">Email Configuration</h3>
                            <form wire:submit="saveMail" class="space-y-4">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <x-select.styled
                                        label="Mail Driver"
                                        wire:model="mail_driver"
                                        :options="[
                                            ['label' => 'SMTP', 'value' => 'smtp'],
                                            ['label' => 'Sendmail', 'value' => 'sendmail'],
                                            ['label' => 'Mailgun', 'value' => 'mailgun'],
                                            ['label' => 'SES', 'value' => 'ses'],
                                        ]"
                                        select="label:label|value:value"
                                    />
                                    <x-input label="SMTP Host" wire:model="mail_host"/>
                                    <x-input label="SMTP Port" wire:model="mail_port" type="number"/>
                                    <x-select.styled
                                        label="Encryption"
                                        wire:model="mail_encryption"
                                        :options="[
                                            ['label' => 'TLS', 'value' => 'tls'],
                                            ['label' => 'SSL', 'value' => 'ssl'],
                                        ]"
                                        select="label:label|value:value"
                                    />
                                    <x-input label="Username" wire:model="mail_username"/>
                                    <x-password label="Password" wire:model="mail_password"/>
                                    <x-input label="From Address" wire:model="mail_from_address" type="email"/>
                                    <x-input label="From Name" wire:model="mail_from_name"/>
                                </div>
                                <div class="flex gap-2">
                                    <x-button type="submit" color="primary" icon="check">Save Mail Settings</x-button>
                                    <x-button type="button" wire:click="testMailConnection" color="secondary" icon="envelope">Test Connection</x-button>
                                </div>
                            </form>
                        </div>
                    @endif

                    {{-- Database Settings --}}
                    @if($activeTab === 'database')
                        <div>
                            <h3 class="text-xl font-semibold mb-4">Database Configuration</h3>
                            <form wire:submit="saveDatabase" class="space-y-4">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <x-select.styled
                                        label="Connection"
                                        wire:model="db_connection"
                                        :options="[
                                            ['label' => 'MySQL', 'value' => 'mysql'],
                                            ['label' => 'PostgreSQL', 'value' => 'pgsql'],
                                            ['label' => 'SQL Server', 'value' => 'sqlsrv'],
                                        ]"
                                        select="label:label|value:value"
                                    />
                                    <x-input label="Host" wire:model="db_host"/>
                                    <x-input label="Port" wire:model="db_port" type="number"/>
                                    <x-input label="Database" wire:model="db_database"/>
                                    <x-input label="Username" wire:model="db_username"/>
                                    <x-password label="Password" wire:model="db_password"/>
                                </div>
                                <div class="flex gap-2">
                                    <x-button type="submit" color="primary" icon="check">Save Database Settings</x-button>
                                    <x-button type="button" wire:click="testDatabaseConnection" color="secondary" icon="circle-stack">Test Connection</x-button>
                                </div>
                            </form>
                        </div>
                    @endif

                    {{-- Cache & Queue --}}
                    @if($activeTab === 'cache')
                        <div>
                            <h3 class="text-xl font-semibold mb-4">Cache & Queue Settings</h3>
                            <div class="space-y-6">
                                <div>
                                    <h4 class="font-medium mb-3">Cache Configuration</h4>
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <x-select.styled
                                            label="Cache Driver"
                                            wire:model="cache_driver"
                                            :options="[
                                                ['label' => 'Redis', 'value' => 'redis'],
                                                ['label' => 'Memcached', 'value' => 'memcached'],
                                                ['label' => 'File', 'value' => 'file'],
                                            ]"
                                            select="label:label|value:value"
                                        />
                                        <x-input label="Default TTL (seconds)" wire:model="cache_ttl" type="number"/>
                                        <div class="flex items-center gap-3 p-4 bg-slate-50 dark:bg-slate-800 rounded-lg">
                                            <x-toggle wire:model.live="cache_enabled"/>
                                            <div>
                                                <p class="font-medium text-sm">Enable Caching</p>
                                                <p class="text-xs text-slate-500">Improve performance</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div>
                                    <h4 class="font-medium mb-3">Queue Configuration</h4>
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <x-select.styled
                                            label="Queue Driver"
                                            wire:model="queue_driver"
                                            :options="[
                                                ['label' => 'Redis', 'value' => 'redis'],
                                                ['label' => 'Database', 'value' => 'database'],
                                                ['label' => 'Sync', 'value' => 'sync'],
                                            ]"
                                            select="label:label|value:value"
                                        />
                                        <x-input label="Retry After (seconds)" wire:model="queue_retry_after" type="number"/>
                                        <x-input label="Number of Workers" wire:model="queue_workers" type="number"/>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                    {{-- Security Settings --}}
                    @if($activeTab === 'security')
                        <div>
                            <h3 class="text-xl font-semibold mb-4">Security Settings</h3>
                            <div class="space-y-4">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <x-input label="Session Lifetime (minutes)" wire:model="session_lifetime" type="number"/>
                                    <x-input label="Password Min Length" wire:model="password_min_length" type="number"/>
                                    <x-input label="Max Login Attempts" wire:model="max_login_attempts" type="number"/>
                                    <x-input label="Lockout Duration (minutes)" wire:model="lockout_duration" type="number"/>
                                </div>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div class="flex items-center gap-3 p-4 bg-slate-50 dark:bg-slate-800 rounded-lg">
                                        <x-toggle wire:model.live="force_https"/>
                                        <div>
                                            <p class="font-medium text-sm">Force HTTPS</p>
                                            <p class="text-xs text-slate-500">Redirect to HTTPS</p>
                                        </div>
                                    </div>
                                    <div class="flex items-center gap-3 p-4 bg-slate-50 dark:bg-slate-800 rounded-lg">
                                        <x-toggle wire:model.live="csrf_protection"/>
                                        <div>
                                            <p class="font-medium text-sm">CSRF Protection</p>
                                            <p class="text-xs text-slate-500">Enable CSRF tokens</p>
                                        </div>
                                    </div>
                                    <div class="flex items-center gap-3 p-4 bg-slate-50 dark:bg-slate-800 rounded-lg">
                                        <x-toggle wire:model.live="password_require_special"/>
                                        <div>
                                            <p class="font-medium text-sm">Require Special Characters</p>
                                            <p class="text-xs text-slate-500">In passwords</p>
                                        </div>
                                    </div>
                                    <div class="flex items-center gap-3 p-4 bg-slate-50 dark:bg-slate-800 rounded-lg">
                                        <x-toggle wire:model.live="password_require_number"/>
                                        <div>
                                            <p class="font-medium text-sm">Require Numbers</p>
                                            <p class="text-xs text-slate-500">In passwords</p>
                                        </div>
                                    </div>
                                    <div class="flex items-center gap-3 p-4 bg-slate-50 dark:bg-slate-800 rounded-lg">
                                        <x-toggle wire:model.live="password_require_uppercase"/>
                                        <div>
                                            <p class="font-medium text-sm">Require Uppercase</p>
                                            <p class="text-xs text-slate-500">In passwords</p>
                                        </div>
                                    </div>
                                </div>
                                <x-button wire:click="saveSecurity" color="primary" icon="check">Save Security Settings</x-button>
                            </div>
                        </div>
                    @endif

                    {{-- API Settings --}}
                    @if($activeTab === 'api')
                        <div>
                            <h3 class="text-xl font-semibold mb-4">API Settings</h3>
                            <form wire:submit="saveApi" class="space-y-4">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div class="flex items-center gap-3 p-4 bg-slate-50 dark:bg-slate-800 rounded-lg">
                                        <x-toggle wire:model.live="api_enabled"/>
                                        <div>
                                            <p class="font-medium text-sm">Enable API</p>
                                            <p class="text-xs text-slate-500">Allow API access</p>
                                        </div>
                                    </div>
                                    <div class="flex items-center gap-3 p-4 bg-slate-50 dark:bg-slate-800 rounded-lg">
                                        <x-toggle wire:model.live="api_key_required"/>
                                        <div>
                                            <p class="font-medium text-sm">Require API Key</p>
                                            <p class="text-xs text-slate-500">Authentication required</p>
                                        </div>
                                    </div>
                                    <x-input label="Rate Limit (per minute)" wire:model="api_rate_limit" type="number"/>
                                    <x-input label="API Version" wire:model="api_version"/>
                                </div>
                                <x-button type="submit" color="primary" icon="check">Save API Settings</x-button>
                            </form>
                        </div>
                    @endif

                    {{-- Notifications --}}
                    @if($activeTab === 'notifications')
                        <div>
                            <h3 class="text-xl font-semibold mb-4">Notification Settings</h3>
                            <form wire:submit="saveNotifications" class="space-y-4">
                                <h4 class="font-medium">Event Notifications</h4>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div class="flex items-center gap-3 p-4 bg-slate-50 dark:bg-slate-800 rounded-lg">
                                        <x-toggle wire:model.live="notify_rfq_created"/>
                                        <div>
                                            <p class="font-medium text-sm">RFQ Created</p>
                                            <p class="text-xs text-slate-500">Notify on new RFQ</p>
                                        </div>
                                    </div>
                                    <div class="flex items-center gap-3 p-4 bg-slate-50 dark:bg-slate-800 rounded-lg">
                                        <x-toggle wire:model.live="notify_quote_submitted"/>
                                        <div>
                                            <p class="font-medium text-sm">Quote Submitted</p>
                                            <p class="text-xs text-slate-500">Notify on quote</p>
                                        </div>
                                    </div>
                                    <div class="flex items-center gap-3 p-4 bg-slate-50 dark:bg-slate-800 rounded-lg">
                                        <x-toggle wire:model.live="notify_order_placed"/>
                                        <div>
                                            <p class="font-medium text-sm">Order Placed</p>
                                            <p class="text-xs text-slate-500">Notify on order</p>
                                        </div>
                                    </div>
                                    <div class="flex items-center gap-3 p-4 bg-slate-50 dark:bg-slate-800 rounded-lg">
                                        <x-toggle wire:model.live="notify_sla_breach"/>
                                        <div>
                                            <p class="font-medium text-sm">SLA Breach</p>
                                            <p class="text-xs text-slate-500">Notify on SLA issues</p>
                                        </div>
                                    </div>
                                </div>
                                <h4 class="font-medium mt-6">Notification Channels</h4>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div class="flex items-center gap-3 p-4 bg-slate-50 dark:bg-slate-800 rounded-lg">
                                        <x-toggle wire:model.live="notify_via_email"/>
                                        <div>
                                            <p class="font-medium text-sm">Email</p>
                                            <p class="text-xs text-slate-500">Send via email</p>
                                        </div>
                                    </div>
                                    <div class="flex items-center gap-3 p-4 bg-slate-50 dark:bg-slate-800 rounded-lg">
                                        <x-toggle wire:model.live="notify_via_sms"/>
                                        <div>
                                            <p class="font-medium text-sm">SMS</p>
                                            <p class="text-xs text-slate-500">Send via SMS</p>
                                        </div>
                                    </div>
                                    <div class="flex items-center gap-3 p-4 bg-slate-50 dark:bg-slate-800 rounded-lg">
                                        <x-toggle wire:model.live="notify_via_slack"/>
                                        <div>
                                            <p class="font-medium text-sm">Slack</p>
                                            <p class="text-xs text-slate-500">Send to Slack</p>
                                        </div>
                                    </div>
                                </div>
                                @if($notify_via_slack)
                                    <x-input label="Slack Webhook URL" wire:model="slack_webhook" type="url"/>
                                @endif
                                <x-button type="submit" color="primary" icon="check">Save Notification Settings</x-button>
                            </form>
                        </div>
                    @endif

                    {{-- Business Rules --}}
                    @if($activeTab === 'business')
                        <div>
                            <h3 class="text-xl font-semibold mb-4">Business Rules</h3>
                            <form wire:submit="saveBusinessRules" class="space-y-4">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <x-input label="RFQ Default Duration (days)" wire:model="rfq_default_duration" type="number"/>
                                    <x-input label="Quote Validity (days)" wire:model="quote_validity_days" type="number"/>
                                    <x-input label="Min Order Amount" wire:model="min_order_amount" type="number" step="0.01" prefix="$"/>
                                    <x-input label="Max Order Amount" wire:model="max_order_amount" type="number" step="0.01" prefix="$"/>
                                    <x-input label="Approval Threshold" wire:model="approval_threshold" type="number" step="0.01" prefix="$"/>
                                    <div class="flex items-center gap-3 p-4 bg-slate-50 dark:bg-slate-800 rounded-lg">
                                        <x-toggle wire:model.live="require_approval"/>
                                        <div>
                                            <p class="font-medium text-sm">Require Approval</p>
                                            <p class="text-xs text-slate-500">For high-value orders</p>
                                        </div>
                                    </div>
                                </div>
                                <x-button type="submit" color="primary" icon="check">Save Business Rules</x-button>
                            </form>
                        </div>
                    @endif

                    {{-- File Upload --}}
                    @if($activeTab === 'files')
                        <div>
                            <h3 class="text-xl font-semibold mb-4">File Upload Settings</h3>
                            <form wire:submit="saveFileUpload" class="space-y-4">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <x-input label="Max Upload Size (KB)" wire:model="max_upload_size" type="number"/>
                                    <x-input label="Allowed Extensions" wire:model="allowed_extensions" hint="Comma separated"/>
                                    <div class="flex items-center gap-3 p-4 bg-slate-50 dark:bg-slate-800 rounded-lg">
                                        <x-toggle wire:model.live="scan_uploads"/>
                                        <div>
                                            <p class="font-medium text-sm">Scan Uploads</p>
                                            <p class="text-xs text-slate-500">Virus scanning</p>
                                        </div>
                                    </div>
                                </div>
                                <x-button type="submit" color="primary" icon="check">Save File Upload Settings</x-button>
                            </form>
                        </div>
                    @endif

                    {{-- System Info --}}
                    @if($activeTab === 'system')
                        <div>
                            <h3 class="text-xl font-semibold mb-4">System Information</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                @foreach($systemInfo as $key => $value)
                                    <div class="p-4 bg-slate-50 dark:bg-slate-800 rounded-lg">
                                        <p class="text-xs text-slate-500 uppercase tracking-wide mb-1">{{ str_replace('_', ' ', $key) }}</p>
                                        <p class="font-semibold">{{ $value }}</p>
                                    </div>
                                @endforeach
                            </div>

                            <div class="mt-6">
                                <h4 class="font-medium mb-3">Feature Flags</h4>
                                <livewire:settings.feature-flags/>
                            </div>
                        </div>
                    @endif
                </div>
            </x-card>
        </div>
    </div>
</div>
