<div class="max-w-5xl w-full mx-auto pb-10">
    <x-table :paginate="$roles">
        <x-slot:header>
            <header class="flex items-center gap-2 py-6">
                <h1 class="text-lg lg:text-xl font-semibold flex-1">{{ __('Roles & Permissions') }}</h1>
                <x-input type="text" placeholder="{{ __('Search') }} .."
                         class="border-transparent bg-transparent rounded-full max-w-xs hidden lg:block"
                         iconLeading="search" size="sm"
                         wire:model.live.debounce.400ms="search" />
                <x-button variant="outline" size="sm" wire:click="create" class="rounded-full pr-4">
                    <x-icon name="add" class="size-4" />
                    <span>{{ __('New role') }}</span>
                </x-button>
            </header>
        </x-slot:header>

        <x-table.columns>
            <x-table.column>{{ __('Role') }}</x-table.column>
            <x-table.column>{{ __('Permissions') }}</x-table.column>
            <x-table.column>{{ __('Users') }}</x-table.column>
            <x-table.column></x-table.column>
        </x-table.columns>

        <x-table.rows>
            @forelse ($roles as $role)
                <x-table.row :key="$role->id">
                    <x-table.cell>
                        <span class="font-medium capitalize">{{ $role->name }}</span>
                    </x-table.cell>
                    <x-table.cell>
                        <span class="text-sm text-muted-foreground">{{ $role->permissions_count }}</span>
                    </x-table.cell>
                    <x-table.cell>
                        <span class="text-sm text-muted-foreground">{{ $role->users_count }}</span>
                    </x-table.cell>
                    <x-table.cell align="end" class="-space-x-1">
                        <x-button wire:click="edit({{ $role->id }})" variant="ghost" size="icon-sm">
                            <x-icon name="edit" class="size-4" />
                        </x-button>
                        <x-button wire:click="delete({{ $role->id }})"
                                  wire:confirm="{{ __('Delete this role?') }}"
                                  variant="ghost" size="icon-sm" class="text-destructive hover:text-destructive">
                            <x-icon name="delete" class="size-4" />
                        </x-button>
                    </x-table.cell>
                </x-table.row>
            @empty
                <x-table.row>
                    <x-table.cell colspan="4" class="py-12 text-center text-muted-foreground">
                        {{ __('No roles found.') }}
                    </x-table.cell>
                </x-table.row>
            @endforelse
        </x-table.rows>
    </x-table>

    {{-- Inline form dialog --}}
    <x-dialog wire:model="showForm">
        <x-dialog.content class="max-w-lg">
            <div class="p-6 space-y-4">
                <h2 class="text-base font-semibold">{{ $editingId ? __('Edit Role') : __('New Role') }}</h2>

                <x-field>
                    <x-label>{{ __('Role name') }}</x-label>
                    <x-input wire:model="roleName" placeholder="{{ __('e.g. editor') }}" />
                    <x-error field="roleName" />
                </x-field>

                <div>
                    <x-label class="mb-2">{{ __('Permissions') }}</x-label>
                    <div class="space-y-3">
                        @foreach ($permissions as $group => $perms)
                            <div>
                                <div class="text-xs font-medium text-muted-foreground uppercase mb-1">{{ $group }}</div>
                                <div class="flex flex-wrap gap-2">
                                    @foreach ($perms as $perm)
                                        <label class="flex items-center gap-1.5 text-sm cursor-pointer">
                                            <x-checkbox wire:model="selectedPermissions" value="{{ $perm->name }}" />
                                            {{ Str::before($perm->name, ' '.$group) ?: $perm->name }}
                                        </label>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="flex justify-end gap-3 pt-2">
                    <x-button wire:click="$set('showForm', false)" variant="ghost" size="sm">
                        {{ __('Cancel') }}
                    </x-button>
                    <x-button wire:click="save" size="sm">
                        {{ __('Save') }}
                    </x-button>
                </div>
            </div>
        </x-dialog.content>
    </x-dialog>
</div>
