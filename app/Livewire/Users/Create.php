<?php

namespace App\Livewire\Users;

use App\Livewire\Traits\Alert;
use App\Livewire\Traits\WithLogging;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Livewire\Component;

class Create extends Component
{
    use Alert, WithLogging;

    public User $user;

    public ?string $password = null;

    public ?string $password_confirmation = null;

    public bool $modal = false;

    public function mount(): void
    {
        $this->user = new User([
            'is_buyer' => false,
            'is_seller' => false,
            'is_supplier' => false,
            'is_active' => true,
            'verified_seller' => false,
        ]);
    }

    public function render(): View
    {
        return view('livewire.users.create');
    }

    public function rules(): array
    {
        return [
            // Basic Information
            'user.name' => ['required', 'string', 'max:255'],
            'user.email' => ['required', 'string', 'email', 'max:255', Rule::unique('users', 'email')],

            // Role flags
            'user.is_buyer' => ['boolean'],
            'user.is_seller' => ['boolean'],
            'user.is_supplier' => ['boolean'],

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
            'user.supplier_code' => ['nullable', 'string', 'max:255', Rule::unique('users', 'supplier_code')],
            'user.duns_number' => ['nullable', 'string', 'max:255'],
            'user.ariba_network_id' => ['nullable', 'string', 'max:255'],
            'user.currency' => ['nullable', 'string', 'max:10'],
            'user.credit_limit' => ['nullable', 'numeric', 'min:0'],
            'user.supplier_status' => ['nullable', 'in:pending,active,inactive,blocked'],

            // Seller Fields
            'user.commission_rate' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'user.verified_seller' => ['boolean'],

            // Password
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ];
    }

    public function save(): void
    {
        // Check permission
        if (!Auth::user()->hasPermission('create_users')) {
            $this->error('You do not have permission to create users.');

            return;
        }

        try {
            $this->validate();
        } catch (\Illuminate\Validation\ValidationException $e) {
            $firstError = collect($e->errors())->flatten()->first();
            $this->error($firstError);
            throw $e;
        }

        // Hash the password
        $this->user->password = bcrypt($this->password);
        $this->user->email_verified_at = now();
        if (empty($this->user->currency)) {
            $this->user->currency = 'USD';
        }

        // Auto-generate supplier code if supplier
        if ($this->user->is_supplier && empty($this->user->supplier_code)) {
            $this->user->supplier_code = 'SUP-' . strtoupper(substr(uniqid(), -8));
        }

        // Set initial supplier status
        if ($this->user->is_supplier && empty($this->user->supplier_status)) {
            $this->user->supplier_status = 'pending';
        }
        if (!$this->user->is_supplier && empty($this->user->supplier_status)) {
            $this->user->supplier_status = 'inactive';
        }

        // Save the user
        $this->user->save();

        // Log the creation
        $this->logCreate(
            User::class,
            $this->user->id,
            [
                'name' => $this->user->name,
                'email' => $this->user->email,
                'roles' => $this->user->getRoles(),
                'company_name' => $this->user->company_name,
            ]
        );

        // Dispatch event
        $this->dispatch('created');

        // Reset form
        $this->reset();
        $this->mount();

        // Success message
        $this->success(__('User created successfully.'));

        // Close modal
        $this->modal = false;
    }
}
