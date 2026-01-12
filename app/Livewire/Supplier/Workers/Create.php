<?php

namespace App\Livewire\Supplier\Workers;

use App\Livewire\Traits\Alert;
use App\Models\Role;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Livewire\Component;

class Create extends Component
{
    use Alert;

    public User $worker;

    public ?string $password = null;

    public ?string $password_confirmation = null;

    public bool $modal = false;

    public function mount(): void
    {
        $this->worker = new User([
            'is_active' => true,
        ]);
    }

    public function render(): View
    {
        return view('livewire.supplier.workers.create');
    }

    public function rules(): array
    {
        return [
            'worker.name' => ['required', 'string', 'max:255'],
            'worker.email' => ['required', 'string', 'email', 'max:255', Rule::unique('users', 'email')],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ];
    }

    public function save(): void
    {
        $supplier = Auth::user();

        if (!$supplier->hasRole('supplier')) {
            $this->error(__('Only suppliers can create workers.'));
            return;
        }

        $this->validate();

        $this->worker->password = Hash::make((string) $this->password);
        $this->worker->email_verified_at = now();
        $this->worker->is_active = true;
        $this->worker->supplier_id = $supplier->id;

        // Legacy flags false (transition)
        $this->worker->is_admin = false;
        $this->worker->is_buyer = false;
        $this->worker->is_seller = false;
        $this->worker->is_supplier = false;
        $this->worker->role = 'supplier_worker';

        $this->worker->save();

        $supplierWorkerRole = Role::where('name', 'supplier_worker')->first();
        if ($supplierWorkerRole) {
            $this->worker->roles()->syncWithoutDetaching([$supplierWorkerRole->id]);
        }

        $this->dispatch('created');

        $this->reset(['password', 'password_confirmation', 'modal']);
        $this->mount();

        $this->success(__('Worker created successfully.'));
    }
}
