<?php

namespace App\Livewire\Users;

use App\Livewire\Traits\Alert;
use App\Livewire\Traits\WithLogging;
use App\Models\User;
use Illuminate\Contracts\View\View;
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
            'user.name' => [
                'required',
                'string',
                'max:255',
            ],
            'user.email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($this->user->id),
            ],
            'password' => [
                'nullable',
                'string',
                'min:8',
                'confirmed',
            ],
        ];
    }

    public function save(): void
    {
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
