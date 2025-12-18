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

    public function mount(User $user = null): void
    {
        // If a user is passed from route/context, use it; otherwise, initialize
        $this->user = $user ?: new User([
            'is_active' => true,
        ]);
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

            //notes
            'user.notes' => ['nullable', 'string', 'max:2000']
        ];
    }

    public function save(): void
    {
        // Check permission
        if (!Auth::user()->hasPermission('edit_users')) {
            $this->error('You do not have permission to edit users.');
            return;
        }

        try {
            $this->validate();
        } catch (\Illuminate\Validation\ValidationException $e) {
            $firstError = collect($e->errors())->flatten()->first();
            $this->error($firstError);
            throw $e;
        }

        $originalData = $this->user->getOriginal();

        // Update password if provided
        if ($this->password !== null && !empty($this->password)) {
            $this->user->password = bcrypt($this->password);
        }

        // Auto-generate supplier code if supplier and code is empty
        if ($this->user->is_supplier && empty($this->user->supplier_code)) {
            $this->user->supplier_code = 'SUP-' . strtoupper(substr(uniqid(), -8));
        }

        $this->user->save();

        // Log the update with changes
        $changes = [];
        foreach (['name', 'email', 'company_name', 'supplier_status', 'is_supplier', 'is_buyer', 'is_seller', 'is_active'] as $field) {
            if ($this->user->wasChanged($field)) {
                $changes[$field] = ['old' => $originalData[$field] ?? null, 'new' => $this->user->$field];
            }
        }

        if ($this->password !== null && !empty($this->password)) {
            $changes['password'] = 'updated';
        }

        $this->logUpdate(User::class, $this->user->id, $changes);

        $this->dispatch('updated');

        $this->reset(['password', 'password_confirmation']);

        $this->success(__('User updated successfully.'));

        $this->modal = false;
    }
}
