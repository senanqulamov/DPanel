<?php

namespace Database\Factories;

use App\Models\Role;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => static::$password ??= Hash::make('password'),
            'remember_token' => Str::random(10),
            'two_factor_secret' => null,
            'two_factor_recovery_codes' => null,
            'two_factor_confirmed_at' => null,
            'role' => 'buyer',
            'is_admin' => false,
            // Legacy flags - keep for backward compatibility
            'is_buyer' => false,
            'is_seller' => false,
            'is_supplier' => false,
            // Business info - always populated
            'company_name' => fake()->company(),
            'tax_id' => fake()->numerify('TAX-########'),
            'business_type' => fake()->randomElement(['Individual', 'Company', 'Corporation']),
            'business_description' => fake()->catchPhrase() . ' - ' . fake()->bs(),
            // Contact - always populated
            'phone' => fake()->phoneNumber(),
            'mobile' => fake()->phoneNumber(),
            'website' => fake()->url(),
            // Address - always populated
            'address_line1' => fake()->streetAddress(),
            'address_line2' => fake()->secondaryAddress(),
            'city' => fake()->city(),
            'state' => fake()->state(),
            'postal_code' => fake()->postcode(),
            'country' => fake()->country(),
            // Performance
            'rating' => fake()->randomFloat(2, 3.5, 5.0),
            'total_orders' => fake()->numberBetween(0, 50),
            'completed_orders' => fake()->numberBetween(0, 40),
            'cancelled_orders' => fake()->numberBetween(0, 5),
            'is_active' => true,
        ];
    }

    /**
     * Configure the factory.
     */
    public function configure(): static
    {
        return $this->afterCreating(function ($user) {
            $roleName = $user->role;

            if (!$roleName) {
                throw new \RuntimeException('UserFactory: role must be set before creating user.');
            }

            $role = Role::where('name', $roleName)->first();

            if (!$role) {
                throw new \RuntimeException(
                    "UserFactory: Role '{$roleName}' not found for user ID {$user->id}. " .
                    "Ensure RolesAndPermissionsSeeder runs first."
                );
            }

            // Attach role if not already attached
            if (!$user->roles()->where('roles.id', $role->id)->exists()) {
                $user->roles()->attach($role->id);
            }

            // Sync is_admin flag
            if ($roleName === 'admin' && !$user->is_admin) {
                $user->forceFill(['is_admin' => true])->saveQuietly();
            }
        });
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }

    /**
     * Indicate that the model has two-factor authentication configured.
     */
    public function withTwoFactor(): static
    {
        return $this->state(fn (array $attributes) => [
            'two_factor_secret' => Str::random(10),
            'two_factor_recovery_codes' => Str::random(10),
            'two_factor_confirmed_at' => now(),
        ]);
    }

    /**
     * Create a user that is a supplier.
     */
    public function supplier(): static
    {
        return $this->state(fn (array $attributes) => [
            'role' => 'supplier',
            'is_supplier' => true,
            'supplier_code' => 'SUP-' . strtoupper(Str::random(8)),
            'duns_number' => fake()->numerify('#########'),
            'ariba_network_id' => fake()->optional(0.3)->numerify('AN##########'),
            'payment_terms' => [
                'net_days' => fake()->randomElement([30, 60, 90]),
                'type' => 'net'
            ],
            'currency' => fake()->randomElement(['USD', 'EUR', 'GBP']),
            'credit_limit' => fake()->randomFloat(2, 50000, 500000),
            'supplier_status' => 'active',
            'supplier_approved_at' => now(),
            'rating' => fake()->randomFloat(2, 3.8, 5.0),
            'total_orders' => fake()->numberBetween(20, 100),
            'completed_orders' => fake()->numberBetween(15, 90),
        ]);
    }

    /**
     * Create a user that is a seller.
     */
    public function seller(): static
    {
        return $this->state(fn (array $attributes) => [
            'role' => 'seller',
            'is_seller' => true,
            'commission_rate' => fake()->randomFloat(2, 5, 20),
            'verified_seller' => true,
            'verified_at' => now(),
            'rating' => fake()->randomFloat(2, 4.0, 5.0),
            'total_orders' => fake()->numberBetween(30, 150),
            'completed_orders' => fake()->numberBetween(25, 140),
        ]);
    }

    /**
     * Create a user that is strictly a buyer (default).
     */
    public function buyer(): static
    {
        return $this->state(fn (array $attributes) => [
            'role' => 'buyer',
            'is_buyer' => true,
            'total_orders' => fake()->numberBetween(5, 30),
            'completed_orders' => fake()->numberBetween(3, 25),
        ]);
    }

    /**
     * Create a pending supplier (not yet approved).
     */
    public function pendingSupplier(): static
    {
        return $this->state(fn (array $attributes) => [
            'role' => 'supplier',
            'is_supplier' => true,
            'supplier_status' => 'pending',
            'supplier_approved_at' => null,
            'supplier_code' => 'SUP-' . strtoupper(Str::random(8)),
            'rating' => null,
        ]);
    }

    /**
     * Create a blocked supplier.
     */
    public function blockedSupplier(): static
    {
        return $this->state(fn (array $attributes) => [
            'role' => 'supplier',
            'is_supplier' => true,
            'supplier_status' => 'blocked',
            'supplier_code' => 'SUP-' . strtoupper(Str::random(8)),
            'is_active' => false,
        ]);
    }

    /**
     * Create an admin user.
     */
    public function admin(): static
    {
        return $this->state(fn (array $attributes) => [
            'role' => 'admin',
            'is_admin' => true,
            'is_buyer' => true,
            'is_seller' => true,
            'is_supplier' => true,
            'rating' => 5.0,
        ]);
    }
}
