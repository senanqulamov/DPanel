# Admin User Flow Cleanup + Market Vendor Users (market_users)

**Date:** 2026-01-06  
**Status:** Planning / Spec (no code changes yet)

This doc captures three upcoming changes (in order):

0. Consolidate authorization and role checks to use **pivot-table roles** (`role_user`) as the source of truth (booleans become legacy).
1. Add seller **workers** and market assignment (`market_users`).
2. Clean up the **Admin “Add User / Update User”** flow so creating a user is minimal and details are filled *after* creation.

---

# Key decisions (locked)

- **Users can have multiple roles** (via `role_user`).
- Seller worker accounts must have a distinct role named **`market_worker`**.
  - When a seller creates a worker, the app must automatically assign the `market_worker` role.
  - Role seeders and factories must be updated to include this role.
- After an admin creates a new user, redirect to `users.show` **and auto-open the Update modal** so admin can immediately fill role-specific fields.

---

# Task 0 — Make pivot roles the source of truth (replace booleans)

## Goal
Use `role_user` (and `roles.name`) as the single, canonical way to determine whether a user is:
- admin
- buyer
- seller
- supplier

…and stop relying on `users.is_seller`, `users.is_supplier`, `users.is_admin`, etc.

## Why this is required before Task 1 & Task 2
- Seller-panel access (“only seller can enter seller panel”) must be based on pivot roles, not legacy flags.
- Admin user creation (“pick a role”) should attach pivot roles deterministically.
- Worker accounts (Task 1) are easiest to reason about if their role is explicit in pivot tables.

## Current risk / reality
- Some code currently uses boolean checks like `User::isSeller()` which returns `$this->is_seller`.
- Some routes use middleware like `['seller', ...]`.

So the migration must be planned and staged.

## Proposed approach (staged)

### Stage A — Standardize role reads (code checks)
- Update all role checks (policies, middleware, UI conditionals) to use pivot roles:
  - `user->hasRole('seller')`, `user->hasRole('admin')`, etc.
- Keep booleans temporarily, but stop using them for authorization.

### Stage B — Standardize role writes (creating/updating user)
- When a user’s role changes, write to `role_user` only.
- Optionally, during transition, also keep booleans in sync (until removed), but they are not authoritative.

### Stage C — Data migration / backfill
- Add a one-time migration or command that:
  - For each user, inspects legacy booleans and attaches matching pivot roles.
  - Ensures there is exactly one “primary” role if we decide to enforce it, or multiple roles if allowed.

### Stage D — Deprecate booleans
- After the app no longer depends on them, remove `is_buyer/is_seller/is_supplier/is_admin` columns, OR keep them as computed/accessors only.

## Decisions (updated)
- Users have **multiple** roles via `role_user`.
- Introduce role: `market_worker`.
  - It is **not** the same as `seller`.
  - Only `seller` users can enter the seller panel.

---

# Task 1 — Seller workers + market assignment (`market_users`)

## Clarified definition
`market_users` are **market workers** (employees) of a seller (Amazon-style). A seller user can create worker accounts and assign them to one or more markets.

## Roles
- Seller owner user must have role: `seller`
- Worker user must have role: `market_worker`

### Seeders / factories requirement
When we implement this task, we must also update data generation:
- Add `market_worker` to the roles seeding (where roles like `admin`, `buyer`, `seller`, `supplier` are seeded).
- Update user factories (and any seeders that create users) so worker users can be created with the `market_worker` role when needed.

## Seller creates/manages workers (required UX)
Workers are managed as a **separate module under seller panel** (like Products/Markets).

Seller owner can:
- Create worker (name, email, password)
- Edit worker
- Delete worker
- Assign worker to one or more markets owned by the seller

When seller owner creates the worker:
- The new user is created with:
  - role `market_worker` via `role_user`
  - `seller_id = current seller owner id`

---

# Task 2 — Clean Admin “Add user / Update user” flow

## Goal (updated for multi-role)
Admin user creation should ask for **only**:
1. Name
2. Email
3. Password
4. User role(s) (multi-select)

Users can have multiple roles, so the role picker must support selecting **one or more** roles from `roles`.

### Role selection rules
- Admin can assign any of the standard roles (`admin`, `buyer`, `seller`, `supplier`).
- `market_worker` is normally **not** created by admins (it’s auto-assigned for seller-created workers), unless you explicitly decide to allow admin creation of workers too.

## Redirect + auto-open update modal (updated / concrete)
After admin creates a user:

1) Redirect to `route('users.show', $user)`
2) Include query parameters to control the UI state, e.g.:
- `?edit=1` → auto-open Update modal
- `&tab=supplier` / `&tab=seller` / `&tab=business` → focus the relevant section

Example redirect intents:
- Supplier created: `users.show?id=<id>&edit=1&tab=supplier`
- Seller created: `users.show?id=<id>&edit=1&tab=seller`

### Behavior expectation
- The Update modal opens automatically on first load after redirect.
- The modal focuses the relevant tab/section based on the query string.
- If multiple roles were selected, pick the most “specific” tab first (recommended priority):
  1) supplier
  2) seller
  3) buyer/business

### Implementation direction (later)
Because `users.show` renders `<livewire:users.update />` and the update modal already opens via a Livewire event (`load::user`), implement this by:

- Reading `request()->query('edit')` (or passing it into the Livewire component) on page load.
- If `edit=1`, dispatch the event used by the Edit button:
  - `load::user` with `{ user: <id>, tab: <tab> }` *(tab payload is a suggested extension)*

---

## Implementation readiness checklist (updated)

### Task 0: Pivot roles as source of truth
- [ ] Inventory and update all role checks to use pivot roles
- [ ] Ensure all role assignment writes go through `role_user`
- [ ] Backfill pivot roles from legacy booleans
- [ ] Add `market_worker` role to seeders and factories
- [ ] Keep booleans in sync only during transition (optional)

### Task 1: Seller workers + market assignments
- [ ] Add `seller_id` (or equivalent) to `users` to model seller→worker ownership
- [ ] Migration created for `market_users` and applied
- [ ] Seller panel UI/pages for worker CRUD
- [ ] Market assignment UI (multi-market)
- [ ] Ensure seller-created workers auto-get `market_worker` role

### Task 2: Admin user flow cleanup
- [ ] Simplified create form fields (name/email/password/roles)
- [ ] Create writes roles via `role_user` (multi-role)
- [ ] Redirect after create to `users.show?edit=1&tab=...`
- [ ] Auto-open update modal after redirect
- [ ] Tab/section focusing in update modal based on query (or event payload)
- [ ] Role-specific detail forms/sections in update modal
