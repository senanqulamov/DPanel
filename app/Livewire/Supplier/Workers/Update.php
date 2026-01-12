<?php

namespace App\Livewire\Supplier\Workers;

use App\Livewire\Traits\Alert;
use App\Models\Role;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Livewire\Attributes\On;
use Livewire\Component;

class Update extends Component
{
    use Alert;

    public ?User $worker = null;

    public bool $modal = false;

    public ?string $password = null;

    public ?string $password_confirmation = null;

    public function render(): View
    {
        return view('livewire.supplier.workers.update');
    }

    #[On('supplier::workers::load')]
    public function load(User $worker): void
    {
        $supplier = Auth::user();

        if ((int) $worker->supplier_id !== (int) $supplier->id) {
            $this->error(__('You are not allowed to edit this worker.'));
            return;
        }

        $this->worker = $worker;
        $this->modal = true;
    }

    public function rules(): array
    {
        return [
            'worker.name' => ['required', 'string', 'max:255'],
            'worker.email' => ['required', 'string', 'email', 'max:255', Rule::unique('users', 'email')->ignore($this->worker?->id)],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
        ];
    }

    public function save(): void
    {
        $supplier = Auth::user();

        if (!$this->worker || (int) $this->worker->supplier_id !== (int) $supplier->id) {
            $this->error(__('You are not allowed to edit this worker.'));
            return;
        }

        $this->validate();

        if (!empty($this->password)) {
            $this->worker->password = Hash::make((string) $this->password);
        }

        $this->worker->save();

        $supplierWorkerRole = Role::where('name', 'supplier_worker')->first();
        if ($supplierWorkerRole) {
            $this->worker->roles()->syncWithoutDetaching([$supplierWorkerRole->id]);
        }

        $this->dispatch('updated');
        $this->success(__('Worker updated successfully.'));
        $this->modal = false;
    }

    public function delete(): void
    {
        $supplier = Auth::user();

        if (!$this->worker || (int) $this->worker->supplier_id !== (int) $supplier->id) {
            $this->error(__('You are not allowed to delete this worker.'));
            return;
        }

        $this->worker->roles()->detach();
        $this->worker->delete();

        $this->dispatch('deleted');
        $this->success(__('Worker deleted successfully.'));
        $this->modal = false;
    }
}
