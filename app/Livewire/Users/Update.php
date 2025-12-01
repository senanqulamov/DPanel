<?php

namespace App\Livewire\Users;

use App\Livewire\Traits\Alert;
use App\Livewire\Traits\WithLogging;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Livewire\Attributes\On;
use Livewire\Component;

class Update extends Component
{
    use Alert, WithLogging;

    public ?User $user;

    public ?string $password = null;

    public ?string $password_confirmation = null;

    public bool $modal = false;

    public function mount(): void
    {
        // Initialize with empty user to prevent Livewire entangle errors
        $this->user = new User;
    }

    public function render(): View
    {
        return view('livewire.users.update');
    }

    #[On('load::user')]
    public function load(User $user): void
    {
        $this->user = $user;

        $this->modal = true;
    }

    public function rules(): array
    {
        return [
            // Basic Information
            'user.name' => ['required', 'string', 'max:255'],
            'user.email' => ['required', 'string', 'email', 'max:255', Rule::unique('users', 'email')->ignore($this->user->id)],

            // Role flags
            'user.is_buyer' => ['boolean'],
            'user.is_seller' => ['boolean'],
            'user.is_supplier' => ['boolean'],
            'user.is_active' => ['boolean'],

            // Business Information
            'user.company_name' => ['nullable', 'string', 'max:255'],
            'user.tax_id' => ['nullable', 'string', 'max:255'],
            'user.business_type' => ['nullable', 'string', 'max:255'],
            'user.business_description' => ['nullable', 'string', 'max:1000'],

            // Contact Information
            'user.phone' => ['nullable', 'string', 'max:255'],
            'user.mobile' => ['nullable', 'string', 'max:255'],
            'user.website' => ['nullable', 'url', 'max:255'],

            // Address
            'user.address_line1' => ['nullable', 'string', 'max:255'],
            'user.address_line2' => ['nullable', 'string', 'max:255'],
            'user.city' => ['nullable', 'string', 'max:255'],
            'user.state' => ['nullable', 'string', 'max:255'],
            'user.postal_code' => ['nullable', 'string', 'max:255'],
            'user.country' => ['nullable', 'string', 'max:255'],

            // Supplier Fields
            'user.supplier_code' => ['nullable', 'string', 'max:255', Rule::unique('users', 'supplier_code')->ignore($this->user->id)],
            'user.duns_number' => ['nullable', 'string', 'max:255'],
            'user.ariba_network_id' => ['nullable', 'string', 'max:255'],
            'user.currency' => ['nullable', 'string', 'max:10'],
            'user.credit_limit' => ['nullable', 'numeric', 'min:0'],
            'user.supplier_status' => ['nullable', 'in:pending,active,inactive,blocked'],

            // Seller Fields
            'user.commission_rate' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'user.verified_seller' => ['boolean'],

            // Password
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
        ];
    }

    public function save(): void
    {
        // Check permission
        if (!Auth::user()->hasPermission('edit_users')) {
            $this->error('You do not have permission to edit users.');
            return;
        }

        $this->validate();
        $originalData = $this->user->getOriginal();

        $this->user->password = when($this->password !== null, bcrypt($this->password), $this->user->password);
        $this->user->save();

        // Log the update with changes
        $changes = [];
        if ($this->user->wasChanged('name')) {
            $changes['name'] = ['old' => $originalData['name'], 'new' => $this->user->name];
        }
        if ($this->user->wasChanged('email')) {
            $changes['email'] = ['old' => $originalData['email'], 'new' => $this->user->email];
        }
        if ($this->password !== null) {
            $changes['password'] = 'updated';
        }

        $this->logUpdate(User::class, $this->user->id, $changes);

        $this->dispatch('updated');

        $this->resetExcept('user');

        $this->success();
    }
}
