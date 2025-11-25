<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
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
            'password' => static::$password ??= 'password',
            'remember_token' => Str::random(10),
            'two_factor_secret' => Str::random(10),
            'two_factor_recovery_codes' => Str::random(10),
            'two_factor_confirmed_at' => now(),
            // Role defaults - by default just a buyer
            'is_buyer' => true,
            'is_seller' => false,
            'is_supplier' => false,
            // Business info
            'company_name' => fake()->optional(0.6)->company(),
            'tax_id' => fake()->optional()->numerify('TAX-########'),
            'business_type' => fake()->optional()->randomElement(['Individual', 'Company', 'Corporation']),
            'business_description' => fake()->optional()->sentence(),
            // Contact
            'phone' => fake()->optional()->phoneNumber(),
            'mobile' => fake()->optional()->phoneNumber(),
            'website' => fake()->optional()->url(),
            // Address
            'address_line1' => fake()->streetAddress(),
            'address_line2' => fake()->optional(0.3)->secondaryAddress(),
            'city' => fake()->city(),
            'state' => fake()->state(),
            'postal_code' => fake()->postcode(),
            'country' => fake()->country(),
            // Performance
            'rating' => fake()->optional()->randomFloat(2, 3.0, 5.0),
            'total_orders' => 0,
            'completed_orders' => 0,
            'cancelled_orders' => 0,
            'is_active' => true,
        ];
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
     * Indicate that the model does not have two-factor authentication configured.
     */
    public function withoutTwoFactor(): static
    {
        return $this->state(fn (array $attributes) => [
            'two_factor_secret' => null,
            'two_factor_recovery_codes' => null,
            'two_factor_confirmed_at' => null,
        ]);
    }

    /**
     * Create a user that is a supplier.
     */
    public function supplier(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_supplier' => true,
            'company_name' => fake()->company(),
            'supplier_code' => 'SUP-' . strtoupper(Str::random(8)),
            'duns_number' => fake()->numerify('#########'),
            'ariba_network_id' => fake()->optional(0.3)->numerify('AN##########'),
            'payment_terms' => ['net_days' => fake()->randomElement([30, 60, 90]), 'type' => 'net'],
            'currency' => fake()->randomElement(['USD', 'EUR', 'GBP']),
            'credit_limit' => fake()->randomFloat(2, 10000, 500000),
            'supplier_status' => 'active',
            'supplier_approved_at' => now(),
            'rating' => fake()->randomFloat(2, 3.5, 5.0),
        ]);
    }

    /**
     * Create a user that is a seller.
     */
    public function seller(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_seller' => true,
            'commission_rate' => fake()->randomFloat(2, 5, 20),
            'verified_seller' => true,
            'verified_at' => now(),
            'rating' => fake()->randomFloat(2, 4.0, 5.0),
        ]);
    }

    /**
     * Create a user that is strictly a buyer (default).
     */
    public function buyer(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_buyer' => true,
            'is_seller' => false,
            'is_supplier' => false,
        ]);
    }

    /**
     * Create a pending supplier (not yet approved).
     */
    public function pendingSupplier(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_supplier' => true,
            'supplier_status' => 'pending',
            'supplier_approved_at' => null,
            'company_name' => fake()->company(),
        ]);
    }

    /**
     * Create a blocked supplier.
     */
    public function blockedSupplier(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_supplier' => true,
            'supplier_status' => 'blocked',
            'company_name' => fake()->company(),
        ]);
    }

    /**
     * Create a user with all roles (buyer, seller, supplier).
     */
    public function allRoles(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_buyer' => true,
            'is_seller' => true,
            'is_supplier' => true,
            'company_name' => fake()->company(),
            'supplier_code' => 'SUP-' . strtoupper(Str::random(8)),
            'supplier_status' => 'active',
            'supplier_approved_at' => now(),
            'verified_seller' => true,
            'verified_at' => now(),
            'commission_rate' => fake()->randomFloat(2, 5, 15),
            'rating' => fake()->randomFloat(2, 4.0, 5.0),
        ]);
    }
}
