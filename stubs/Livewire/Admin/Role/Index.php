<?php

namespace App\Livewire\Admin\Role;

use Livewire\Component;
use Livewire\WithPagination;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class Index extends Component
{
    use WithPagination;

    public string $search = '';
    public bool $showForm = false;
    public ?int $editingId = null;
    public string $roleName = '';
    public array $selectedPermissions = [];

    public function updatedSearch(): void { $this->resetPage(); }

    public function create(): void
    {
        $this->reset('editingId', 'roleName', 'selectedPermissions');
        $this->showForm = true;
    }

    public function edit(int $id): void
    {
        $role = Role::with('permissions')->findOrFail($id);
        $this->editingId = $id;
        $this->roleName = $role->name;
        $this->selectedPermissions = $role->permissions->pluck('name')->toArray();
        $this->showForm = true;
    }

    public function save(): void
    {
        $this->validate(['roleName' => 'required|string|max:255']);

        $role = $this->editingId
            ? Role::findOrFail($this->editingId)
            : Role::create(['name' => $this->roleName]);

        if ($this->editingId) {
            $role->update(['name' => $this->roleName]);
        }

        $role->syncPermissions($this->selectedPermissions);

        $this->showForm = false;
        $this->dispatch('toast', message: __('Role saved.'), type: 'success');
    }

    public function delete(int $id): void
    {
        Role::findOrFail($id)->delete();
        $this->dispatch('toast', message: __('Role deleted.'), type: 'success');
    }

    public function render()
    {
        return view('admin.role.index', [
            'roles'       => Role::withCount('permissions', 'users')
                ->when($this->search, fn ($q) => $q->where('name', 'like', '%'.$this->search.'%'))
                ->paginate(15),
            'permissions' => Permission::all()->groupBy(fn ($p) => explode(' ', $p->name)[1] ?? 'other'),
        ])->layout('layouts.admin');
    }
}
