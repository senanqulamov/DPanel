# TallStackUI Dark System Integration

## Overview
This document explains how the OKLCH dark system colors from `app.css` are applied to TallStackUI components (Slide, Modal, Dialog) via the `AppServiceProvider.php`.

---

## Color Mapping Reference

### Dark Scale (from app.css)
```css
--dark-0: oklch(0.14 0.015 255);   /* Base canvas (almost black, not pure) */
--dark-1: oklch(0.16 0.015 255);
--dark-2: oklch(0.18 0.013 255);
--dark-3: oklch(0.21 0.012 255);   /* Default surface */
--dark-4: oklch(0.24 0.012 255);
--dark-5: oklch(0.28 0.012 255);   /* Raised surface */
--dark-6: oklch(0.33 0.011 255);
--dark-7: oklch(0.38 0.010 255);   /* Subtle borders */
--dark-8: oklch(0.45 0.010 255);   /* Muted text */
--dark-9: oklch(0.58 0.009 255);   /* Secondary text */
--dark-10: oklch(0.70 0.008 255);  /* Primary text */
--dark-11: oklch(0.80 0.005 255);  /* High emphasis */
--dark-12: oklch(0.92 0.002 255);  /* Inverse surfaces / Elevated text */
```

### Semantic Tokens (from app.css)
```css
--color-bg: var(--dark-0);                    /* Base background */
--color-surface: var(--dark-3);                /* Surface containers */
--color-surface-raised: var(--dark-5);         /* Elevated surfaces */
--color-border: var(--dark-7);                 /* Default borders */
--color-text: var(--dark-10);                  /* Primary text */
--color-text-high: var(--dark-11);             /* High emphasis text */
--color-text-muted: var(--dark-8);             /* Muted/hint text */
--color-backdrop: oklch(0.14 0.015 255 / 0.65); /* Modal/overlay backdrop */
--color-backdrop-strong: oklch(0.14 0.015 255 / 0.85); /* Dialog backdrop */
--backdrop-blur-sm: 4px;
--backdrop-blur-md: 12px;
--backdrop-blur-lg: 24px;
--shadow-pop: 0 8px 28px -6px oklch(0.18 0.02 255 / 0.55), 0 12px 40px -8px oklch(0.18 0.02 255 / 0.4);
--z-modal: 1300;
```

---

## Applied Customizations

### 1. Slide Component

#### Background Overlay
```php
'wrapper.first' => 'fixed inset-0 bg-[var(--color-backdrop)] backdrop-blur-[var(--backdrop-blur-md)] transform transition-opacity'
```
- **Uses**: `--color-backdrop` (65% opacity dark-0) + 12px blur
- **Effect**: Semi-transparent backdrop with medium blur

#### Panel Surface
```php
'wrapper.fifth' => 'flex flex-col bg-[var(--color-surface-raised)] py-6 shadow-[var(--shadow-pop)] dark:bg-[var(--dark-5)]'
```
- **Uses**: `--color-surface-raised` / `--dark-5` (oklch 0.28)
- **Effect**: Elevated surface with strong shadow

#### Text Elements
```php
'title.text' => '... text-[var(--color-text-high)] dark:text-[var(--dark-11)]'
'body' => '... dark:text-[var(--dark-10)] ... text-[var(--color-text)]'
'title.close' => '... text-[var(--color-text-muted)] hover:text-[var(--color-text)]'
```
- **Title**: High emphasis text (--dark-11, oklch 0.80)
- **Body**: Primary text (--dark-10, oklch 0.70)
- **Close icon**: Muted text (--dark-8, oklch 0.45) with hover

#### Borders
```php
'footer' => '... border-t-[var(--color-border)] ... dark:border-t-[var(--dark-7)]'
```
- **Uses**: `--dark-7` (oklch 0.38) for subtle separation

---

### 2. Modal Component

#### Background Overlay
```php
'wrapper.first' => 'fixed inset-0 bg-[var(--color-backdrop)] backdrop-blur-[var(--backdrop-blur-md)] transform transition-opacity'
```
- **Uses**: Same as Slide - `--color-backdrop` + 12px blur

#### Modal Card
```php
'wrapper.fourth' => '... dark:bg-[var(--dark-5)] ... bg-[var(--color-surface-raised)] ... shadow-[var(--shadow-pop)]'
```
- **Uses**: `--dark-5` (oklch 0.28) - raised surface
- **Effect**: Elevated modal with dramatic shadow

#### Header/Footer Borders
```php
'title.wrapper' => 'dark:border-b-[var(--dark-7)] ... border-b-[var(--color-border)]'
'footer' => 'dark:border-t-[var(--dark-7)] ... border-t-[var(--color-border)]'
```
- **Uses**: `--dark-7` (oklch 0.38) for visual separation

#### Text Hierarchy
```php
'title.text' => 'text-[var(--color-text-high)] dark:text-[var(--dark-11)]'  // High emphasis
'body' => 'dark:text-[var(--dark-10)] ... text-[var(--color-text)]'         // Primary
'title.close' => 'text-[var(--color-text-muted)] hover:text-[var(--color-text)]' // Muted
```

#### Z-Index
```php
'wrapper.second' => 'fixed inset-0 z-[var(--z-modal)]'
```
- **Uses**: `--z-modal: 1300`

---

### 3. Dialog Component

#### Background Overlay (Stronger)
```php
'background' => 'fixed inset-0 bg-[var(--color-backdrop-strong)] backdrop-blur-[var(--backdrop-blur-sm)] transform transition-opacity'
```
- **Uses**: `--color-backdrop-strong` (85% opacity) + 4px blur
- **Why stronger**: Dialogs require more focus/urgency than modals

#### Dialog Card
```php
'wrapper.third' => '... bg-[var(--color-surface-raised)] ... shadow-[var(--shadow-pop)] ... dark:bg-[var(--dark-5)]'
```
- **Uses**: `--dark-5` (oklch 0.28) with strong shadow

#### Text Elements
```php
'text.title' => 'text-[var(--color-text-high)] dark:text-[var(--dark-11)]'        // High emphasis
'text.description.text' => 'text-[var(--color-text-muted)] dark:text-[var(--dark-9)]' // Secondary
'buttons.close.icon' => 'text-[var(--color-text-muted)] hover:text-[var(--color-text)]'
```
- **Title**: --dark-11 (oklch 0.80) - most prominent
- **Description**: --dark-9 (oklch 0.58) - secondary information
- **Icons**: --dark-8 → --dark-10 on hover

---

## Benefits of This Approach

### 1. **Consistent Visual Hierarchy**
- All components use the same luminance scale (OKLCH)
- Predictable contrast ratios across all surfaces

### 2. **Centralized Color Management**
- Change colors in `app.css` → automatically applies to all components
- No need to update multiple PHP files

### 3. **Semantic Naming**
- `--color-surface-raised` is more maintainable than raw OKLCH values
- Easier to understand intent (e.g., "raised surface" vs "oklch 0.28")

### 4. **Dark Mode Optimization**
- OKLCH ensures perceptually uniform brightness
- Blue tint (255° hue) provides modern, cool aesthetic
- Proper contrast for accessibility

---

## How to Customize Further

### Change Background Opacity
In `app.css`, adjust:
```css
--color-backdrop: oklch(0.14 0.015 255 / 0.65);  /* Change 0.65 to 0.50 for lighter */
```

### Change Blur Amount
In `app.css`, adjust:
```css
--backdrop-blur-md: 12px;  /* Change to 8px for less blur */
```

### Change Surface Colors
In `app.css`, adjust the dark scale:
```css
--dark-5: oklch(0.28 0.012 255);  /* Increase 0.28 to 0.32 for lighter surfaces */
```

### Use Different Colors for Specific Components
In `AppServiceProvider.php`, you can override specific blocks:
```php
->slide()
->block('wrapper.fifth', 'flex flex-col bg-[var(--dark-6)] py-6') // Use darker surface
```

---

## Testing Your Changes

1. **Clear cache**:
   ```bash
   php artisan config:clear
   php artisan view:clear
   ```

2. **Rebuild assets**:
   ```bash
   npm run build
   # or for development
   npm run dev
   ```

3. **Test each component**:
   - Open a Slide panel
   - Open a Modal
   - Trigger a Dialog confirmation

4. **Check contrast**:
   - Ensure text is readable on all surfaces
   - Test with browser DevTools color picker
   - Verify WCAG contrast ratios (4.5:1 minimum for text)

---

## Color Usage Summary

| Component | Background | Surface | Text (Primary) | Text (High) | Border |
|-----------|-----------|---------|----------------|-------------|--------|
| **Slide** | `--color-backdrop` | `--dark-5` | `--dark-10` | `--dark-11` | `--dark-7` |
| **Modal** | `--color-backdrop` | `--dark-5` | `--dark-10` | `--dark-11` | `--dark-7` |
| **Dialog** | `--color-backdrop-strong` | `--dark-5` | `--dark-10` | `--dark-11` | n/a |
| **Toast** | n/a | `--dark-5` | `--dark-11` | `--dark-11` | `--dark-7` |
| **Card** | n/a | `--dark-5` | `--dark-10` | `--dark-11` | `--dark-7` |
| **Alert** | n/a | n/a | `--dark-10` | `--dark-11` | n/a |
| **Banner** | n/a | n/a | `--dark-10` | n/a | n/a |
| **Dropdown** | n/a | `--dark-5` | `--dark-10` | n/a | `--dark-7` |
| **Form Input** | n/a | `--dark-4` | `--dark-10` | n/a | `--dark-7` |

All components share the same color tokens for consistency!

---

## Complete Component List

The following TallStackUI components have been personalized with the OKLCH dark system:

### 🔹 Overlays & Modals
- **Slide** - Side panels with backdrop blur
- **Modal** - Centered dialogs with backdrop blur
- **Dialog** - Confirmation dialogs with strong backdrop

### 🔹 Notifications
- **Toast** - Temporary notifications
- **Banner** - Persistent top banners  
- **Alert** - Inline alert messages

### 🔹 Containers
- **Card** - Content containers with headers/footers
- **Dropdown** - Floating dropdown menus

### 🔹 Forms
- **Input** - Text input fields with icons and clearable buttons

---

## Extended Customizations

### 4. Toast Component
```php
'wrapper.third' => 'dark:bg-[var(--dark-5)]...'
'content.text' => 'dark:text-[var(--dark-11)]...'
'content.description' => 'dark:text-[var(--dark-9)]...'
'buttons.close.class' => '...dark:text-[var(--dark-8)] dark:hover:text-[var(--dark-10)]...'
'progress.wrapper' => 'dark:bg-[var(--dark-7)]...'
'progress.bar' => '...dark:bg-[var(--dark-9)]...'
```
- **Surface**: --dark-5 (raised surface)
- **Primary text**: --dark-11 (high emphasis)
- **Description**: --dark-9 (secondary text)
- **Progress bar**: --dark-9 (visible against --dark-7)

### 5. Card Component
```php
'wrapper.second' => 'dark:bg-[var(--dark-5)]...'
'header.wrapper.base' => 'dark:border-b-[var(--dark-7)]...'
'header.text.color' => '...dark:text-[var(--dark-11)]'
'body' => '...dark:text-[var(--dark-10)]...'
'footer.wrapper' => '...dark:border-t-[var(--dark-7)]...'
```
- **Surface**: --dark-5 (raised from page background)
- **Headers**: --dark-11 (highest emphasis)
- **Body text**: --dark-10 (readable primary)

### 6. Alert Component
```php
'text.title' => '...dark:text-[var(--dark-11)]'
'text.description' => '...dark:text-[var(--dark-10)]'
'close.size' => '...dark:text-[var(--dark-8)] dark:hover:text-[var(--dark-10)]'
```
- Alert uses color variants (primary, success, danger, etc.)
- Text hierarchy maintained: title (--dark-11), body (--dark-10)

### 7. Banner Component
```php
'text' => '...text-[var(--color-text)] dark:text-[var(--dark-10)]'
'slot.left' => '...dark:text-[var(--dark-9)]'
'close' => '...dark:text-[var(--dark-8)] dark:hover:text-[var(--dark-10)]'
```
- **Primary text**: --dark-10 (center message)
- **Secondary text**: --dark-9 (left slot)
- **Icons**: --dark-8 with hover to --dark-10

### 8. Dropdown Component
```php
'floating.default' => '...dark:bg-[var(--dark-5)]...dark:ring-[var(--dark-7)]...'
'action.text' => '...dark:text-[var(--dark-10)]...'
'action.icon' => '...dark:text-[var(--dark-8)]...dark:hover:text-[var(--dark-10)]'
```
- **Surface**: --dark-5 (floating menu)
- **Ring/Border**: --dark-7 (subtle outline)
- **Text**: --dark-10 (readable)

### 9. Form Input Component
```php
'input.base' => '...dark:border-[var(--dark-7)] dark:bg-[var(--dark-4)] text-[var(--color-text)] dark:text-[var(--dark-10)]...'
'icon.wrapper' => '...dark:text-[var(--dark-8)]'
'icon.color' => '...dark:text-[var(--dark-8)]'
'clearable.color' => '...dark:hover:text-red-400'
```
- **Input background**: --dark-4 (less elevated than cards)
- **Input text**: --dark-10 (primary readable)
- **Icons**: --dark-8 (muted, non-distracting)
- **Border**: --dark-7 (subtle outline)

---

## Additional Notes

- **Blur effects** are progressive: Dialog (sm) < Modal/Slide (md)
- **Backdrop opacity** is stronger for Dialog (85%) vs Modal/Slide (65%)
- **Shadows** use `--shadow-pop` for dramatic elevation
- **Z-index** controlled via `--z-modal: 1300`
- **CSS custom properties** are used with `bg-[var(--name)]` syntax for Tailwind v4

## Live Example Usage

```blade
{{-- Slide with your dark system --}}
<x-slide wire="showSlide" title="Settings">
    <p>Your content here will use --dark-10 text on --dark-5 background</p>
</x-slide>

{{-- Modal with your dark system --}}
<x-modal wire="showModal" title="Confirmation">
    <p>Modal content with consistent colors</p>
</x-modal>

{{-- Dialog with your dark system --}}
<x-button wire:click="$dialog('confirm', { ... })">
    Trigger Dialog
</x-button>
```

All will automatically use your OKLCH dark system! 🎨
