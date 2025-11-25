# User-Market Relationship Implementation

## Overview
This document describes the implementation of the relationship between Users (Sellers) and Markets in the dpanel system.

## Business Logic

### User Roles
- **Buyer**: Regular users who can purchase products (default role)
- **Seller**: Users who own and manage one or more markets
- **Supplier**: Users who supply products to markets
- **Admin**: Users with all roles enabled

### Seller-Market Relationship
- Each market MUST belong to a seller (user with `is_seller = true`)
- A seller can own multiple markets (one-to-many relationship)
- When a user is a seller, their markets are displayed in the users list
- Buyers have no special relationships or actions - they are simply users with the buyer flag

## Database Changes

### Migration
Created migration: `2025_11_24_134109_add_user_id_to_markets_table.php`

Changes to `markets` table:
- Added `user_id` foreign key column
- Nullable to allow existing data migration
- Cascades on delete (if user is deleted, their markets are also deleted)
- Added after `id` column for logical ordering

```php
$table->foreignId('user_id')->nullable()->after('id')->constrained()->onDelete('cascade');
```

## Model Updates

### User Model (`app/Models/User.php`)
Added relationship method:
```php
public function markets(): HasMany
{
    return $this->hasMany(Market::class, 'user_id');
}
```

### Market Model (`app/Models/Market.php`)
Added fillable field and relationship methods:
```php
protected $fillable = [
    'user_id',
    'name',
    'location',
    'image_path',
];

public function seller()
{
    return $this->belongsTo(User::class, 'user_id');
}

public function owner()
{
    return $this->seller();
}
```

## Livewire Component Updates

### Users Index (`app/Livewire/Users/Index.php`)
- Added eager loading of markets relationship: `->with('markets')`
- Displays market names for sellers in the roles column

### Markets Create (`app/Livewire/Markets/Create.php`)
- Added `user_id` to validation rules
- Automatically assigns current authenticated user if not specified
- Provides dropdown list of available sellers
- Passes `$sellers` collection to view

### Markets Update (`app/Livewire/Markets/Update.php`)
- Added `user_id` to validation rules
- Provides dropdown list of available sellers
- Allows reassigning market to different seller

### Markets Index (`app/Livewire/Markets/Index.php`)
- Added "Owner" column to headers
- Eager loads seller relationship: `->with('seller')`
- Displays seller name with badge in markets list

## View Updates

### Users Index View (`resources/views/livewire/users/index.blade.php`)
For sellers, displays their markets:
```blade
@if($row->is_seller)
    <x-badge color="green" text="Seller" sm />
    @if($row->markets->isNotEmpty())
        <div class="text-xs text-gray-600 dark:text-gray-400 w-full mt-1">
            Markets: {{ $row->markets->pluck('name')->join(', ') }}
        </div>
    @endif
@endif
```

### Markets Create View (`resources/views/livewire/markets/create.blade.php`)
Added owner selector:
```blade
<x-select.native 
    label="{{ __('Owner (Seller)') }} *" 
    wire:model="market.user_id" 
    :options="$sellers"
    select="label:name|value:id"
    required 
/>
```

### Markets Update View (`resources/views/livewire/markets/update.blade.php`)
Added owner selector with same structure as create form

### Markets Index View (`resources/views/livewire/markets/index.blade.php`)
Added owner column:
```blade
@interact('column_owner', $row)
    @if($row->seller)
        <x-badge color="green" :text="$row->seller->name" icon="user" position="left" sm />
    @else
        <span class="text-gray-400">-</span>
    @endif
@endinteract
```

## Factory Updates

### MarketFactory (`database/factories/MarketFactory.php`)
Added automatic user creation:
```php
public function definition(): array
{
    return [
        'user_id' => \App\Models\User::factory(),
        'name' => $this->faker->city(),
        'location' => $this->faker->city().', '.$this->faker->country(),
        'image_path' => null,
    ];
}
```

## Seeder Updates

### DatabaseSeeder (`database/seeders/DatabaseSeeder.php`)
Updated to create realistic market ownership:
- Each seller gets 1-3 random markets
- Admin user gets 2 markets
- Markets are properly assigned to seller users

```php
$markets = collect();
foreach ($sellers as $seller) {
    $marketsForSeller = Market::factory(rand(1, 3))->create(['user_id' => $seller->id]);
    $markets = $markets->merge($marketsForSeller);
}

$adminMarkets = Market::factory(2)->create(['user_id' => $admin->id]);
$markets = $markets->merge($adminMarkets);
```

## Usage Examples

### Creating a Market
When creating a market, you must select an owner (seller):
1. Open Markets page
2. Click "Create New Market"
3. Select Owner from dropdown (only users with `is_seller = true` appear)
4. Fill in market details
5. Save

### Viewing User's Markets
In the Users list:
- Sellers show a green "Seller" badge
- Below the badge, their markets are listed by name
- Example: "Markets: Downtown Market, Uptown Market"

### Viewing Market Owner
In the Markets list:
- "Owner" column shows the seller's name with a green badge
- Click to see owner details

## Migration Instructions

To apply these changes to an existing database:

1. Run the migration:
   ```bash
   php artisan migrate
   ```

2. Update existing markets to assign them to sellers:
   ```php
   // Example: Assign all markets to the admin
   Market::whereNull('user_id')->update(['user_id' => 1]);
   ```

3. Or start fresh:
   ```bash
   php artisan migrate:fresh --seed
   ```

## Testing

After implementation, verify:
- [ ] Markets show owner in the markets list
- [ ] Users who are sellers show their markets in users list
- [ ] Creating a market requires selecting an owner
- [ ] Updating a market allows changing the owner
- [ ] Deleting a seller cascades to delete their markets
- [ ] Buyers show no markets (they don't own any)
