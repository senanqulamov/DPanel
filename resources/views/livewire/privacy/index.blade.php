<div>
    <x-card>
        <x-alert color="purple" icon="shield-check">
            @lang('Privacy & Role Management')
        </x-alert>

        <div class="space-y-8">
            {{-- Users Management Section --}}
            <div class="mt-6">
                <div class="mb-4 flex items-center justify-between">
                    <h3 class="text-lg font-semibold">
                        @lang('Users & Roles')
                    </h3>
                </div>

                <x-table :$headers :$sort :rows="$this->rows" paginate :paginator="null" filter loading :quantity="[5, 10, 20, 'all']">
                    @interact('column_id', $row)
                    {{ $row->id }}
                    @endinteract

                    @interact('column_name', $row)
                    <div class="flex items-center gap-2">
                        <x-badge :text="$row->name" icon="user" position="left"/>
                        @if($row->is_admin)
                            <x-badge text="Admin" color="purple" sm />
                        @endif
                    </div>
                    @endinteract

                    @interact('column_email', $row)
                    {{ $row->email }}
                    @endinteract

                    @interact('column_roles', $row)
                    <div class="flex gap-1 flex-wrap">
                        @forelse($row->roles as $role)
                            <x-badge :text="$role->display_name" color="blue" sm />
                        @empty
                            <span class="text-gray-400">-</span>
                        @endforelse
                    </div>
                    @endinteract

                    @interact('column_created_at', $row)
                    {{ $row->created_at->diffForHumans() }}
                    @endinteract

                    @interact('column_action', $row)
                    <div class="flex gap-1">
                        <x-button.circle icon="pencil" wire:click="$dispatch('load::user-roles', { 'user' : '{{ $row->id }}'})"/>
                    </div>
                    @endinteract
                </x-table>
            </div>

            {{-- Roles Management Section --}}
            <div>
                <div class="mb-4 flex items-center justify-between">
                    <h3 class="text-lg font-semibold">
                        @lang('Roles & Permissions')
                    </h3>
                    <livewire:privacy.roles.create @created="$refresh"/>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach($this->roles as $role)
                        <x-card>
                            <div class="flex items-start justify-between mb-3">
                                <div class="flex-1">
                                    <div class="flex items-center gap-2 mb-1">
                                        <a href="{{ route('privacy.roles.show', $role) }}" class="font-semibold text-gray-900 dark:text-gray-100 hover:text-purple-600 dark:hover:text-purple-400">
                                            {{ $role->display_name }}
                                        </a>
                                        @if($role->is_system)
                                            <x-badge text="System" color="gray" sm />
                                        @endif
                                    </div>
                                    <p class="text-xs text-gray-600 dark:text-gray-400">
                                        {{ $role->description }}
                                    </p>
                                </div>
                            </div>

                            <div class="flex items-center justify-between pt-3 border-t border-gray-200 dark:border-gray-700">
                                <div class="flex items-center gap-2">
                                    <x-icon name="users" class="w-4 h-4 text-gray-500" />
                                    <span class="text-sm text-gray-600 dark:text-gray-400">
                                        {{ $role->users_count }} {{ Str::plural('user', $role->users_count) }}
                                    </span>
                                </div>

                                <div class="flex gap-1">
                                    <x-button.circle icon="key" color="purple" sm wire:click="$dispatch('load::role-permissions', { 'role' : '{{ $role->id }}'})"/>
                                    @if(!$role->is_system)
                                        <x-button.circle icon="pencil" sm wire:click="$dispatch('load::role', { 'role' : '{{ $role->id }}'})"/>
                                        <livewire:privacy.roles.delete :role="$role" :key="uniqid('', true)" @deleted="$refresh"/>
                                    @endif
                                </div>
                            </div>
                        </x-card>
                    @endforeach
                </div>
            </div>

            {{-- Permissions Overview Section --}}
            <div>
                <div class="mb-4">
                    <h3 class="text-lg font-semibold">
                        @lang('Permission Groups')
                    </h3>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                    @foreach($this->permissionsByGroup as $group => $groupPermissions)
                        <x-card>
                            <div class="flex items-center gap-2 mb-2">
                                <x-icon name="key" class="w-5 h-5 text-purple-600 dark:text-purple-400" />
                                <h4 class="font-medium text-gray-900 dark:text-gray-100">
                                    {{ $group }}
                                </h4>
                            </div>
                            <p class="text-sm text-gray-600 dark:text-gray-400">
                                {{ $groupPermissions->count() }} {{ Str::plural('permission', $groupPermissions->count()) }}
                            </p>
                            <div class="mt-3 space-y-1">
                                @foreach($groupPermissions as $permission)
                                    <div class="text-xs text-gray-500 dark:text-gray-400">
                                        • {{ $permission->display_name }}
                                    </div>
                                @endforeach
                            </div>
                        </x-card>
                    @endforeach
                </div>
            </div>
        </div>
    </x-card>

    <livewire:privacy.user-roles.update @updated="$refresh"/>
    <livewire:privacy.role-permissions.update @updated="$refresh"/>
    <livewire:privacy.roles.update @updated="$refresh"/>
</div>
