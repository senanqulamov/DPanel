# TallStackUI OKLCH Dark System - Quick Reference

## 🎨 Color Scale Overview

```
--dark-0:  oklch(0.14) → Base canvas (almost black)
--dark-1:  oklch(0.16)
--dark-2:  oklch(0.18)
--dark-3:  oklch(0.21) → Default surface
--dark-4:  oklch(0.24) → Input backgrounds
--dark-5:  oklch(0.28) → Raised surfaces (cards, modals)
--dark-6:  oklch(0.33)
--dark-7:  oklch(0.38) → Borders & dividers
--dark-8:  oklch(0.45) → Muted text & icons
--dark-9:  oklch(0.58) → Secondary text
--dark-10: oklch(0.70) → Primary text
--dark-11: oklch(0.80) → High emphasis (titles)
--dark-12: oklch(0.92) → Inverse surfaces
```

## 🧩 Component Color Assignments

### Slide Panel
- **Background**: `--color-backdrop` (65% opacity + 12px blur)
- **Surface**: `--dark-5` (raised)
- **Title**: `--dark-11` (high emphasis)
- **Body**: `--dark-10` (primary text)
- **Border**: `--dark-7` (footer divider)
- **Close icon**: `--dark-8` → `--dark-10` (hover)

### Modal Dialog
- **Background**: `--color-backdrop` (65% opacity + 12px blur)
- **Surface**: `--dark-5` (raised)
- **Title**: `--dark-11` (high emphasis)
- **Body**: `--dark-10` (primary text)
- **Borders**: `--dark-7` (header/footer dividers)
- **Close icon**: `--dark-8` → `--dark-10` (hover)

### Confirmation Dialog
- **Background**: `--color-backdrop-strong` (85% opacity + 4px blur)
- **Surface**: `--dark-5` (raised)
- **Title**: `--dark-11` (high emphasis)
- **Description**: `--dark-9` (secondary text)
- **Close icon**: `--dark-8` → `--dark-10` (hover)

### Toast Notification
- **Surface**: `--dark-5` (raised)
- **Ring**: `--dark-7` (subtle outline)
- **Text**: `--dark-11` (high emphasis)
- **Description**: `--dark-9` (secondary)
- **Close icon**: `--dark-8` → `--dark-10` (hover)
- **Progress bar background**: `--dark-7`
- **Progress bar fill**: `--dark-9`

### Card Container
- **Surface**: `--dark-5` (raised)
- **Header border**: `--dark-7` (bottom divider)
- **Header text**: `--dark-11` (high emphasis)
- **Body text**: `--dark-10` (primary)
- **Footer border**: `--dark-7` (top divider)
- **Footer text**: `--dark-10` (primary)

### Alert Message
- **Title**: `--dark-11` (high emphasis)
- **Description**: `--dark-10` (primary text)
- **Close icon**: `--dark-8` → `--dark-10` (hover)
- Note: Backgrounds use color variants (primary, success, danger, etc.)

### Banner
- **Text**: `--dark-10` (primary)
- **Left slot**: `--dark-9` (secondary)
- **Close icon**: `--dark-8` → `--dark-10` (hover)

### Dropdown Menu
- **Surface**: `--dark-5` (floating)
- **Ring**: `--dark-7` (outline)
- **Text**: `--dark-10` (primary)
- **Icon**: `--dark-8` → `--dark-10` (hover)

### Form Input
- **Background**: `--dark-4` (input field)
- **Border**: `--dark-7` (outline)
- **Text**: `--dark-10` (primary)
- **Icons**: `--dark-8` (muted)
- **Clear button**: `--dark-8` → red-500 (hover)

---

## 🎯 Usage Patterns

### Surface Hierarchy
```
Page background:    --dark-0  (lowest)
Default surface:    --dark-3
Input fields:       --dark-4
Raised surfaces:    --dark-5  (cards, modals, dropdowns)
```

### Text Hierarchy
```
Muted/hint text:    --dark-8  (icons, placeholders)
Secondary text:     --dark-9  (descriptions, captions)
Primary text:       --dark-10 (body text)
High emphasis:      --dark-11 (titles, headers)
```

### Borders & Dividers
```
All borders:        --dark-7  (consistent subtle separation)
```

### Backdrops
```
Modal/Slide:        --color-backdrop (65% opacity + 12px blur)
Dialog:             --color-backdrop-strong (85% opacity + 4px blur)
```

---

## 🔧 CSS Variables Reference

### Semantic Tokens (in app.css)
```css
/* Surfaces */
--color-bg: var(--dark-0);
--color-surface: var(--dark-3);
--color-surface-raised: var(--dark-5);

/* Text */
--color-text: var(--dark-10);
--color-text-high: var(--dark-11);
--color-text-muted: var(--dark-8);

/* Borders */
--color-border: var(--dark-7);

/* Backdrops */
--color-backdrop: oklch(0.14 0.015 255 / 0.65);
--color-backdrop-strong: oklch(0.14 0.015 255 / 0.85);

/* Effects */
--backdrop-blur-sm: 4px;
--backdrop-blur-md: 12px;
--backdrop-blur-lg: 24px;
--shadow-pop: 0 8px 28px -6px oklch(0.18 0.02 255 / 0.55), 
              0 12px 40px -8px oklch(0.18 0.02 255 / 0.4);

/* Z-index */
--z-modal: 1300;
```

---

## 🚀 Quick Customization Tips

### Make backdrops more transparent
```css
--color-backdrop: oklch(0.14 0.015 255 / 0.40); /* Was 0.65 */
```

### Increase contrast for text
```css
--dark-10: oklch(0.75 0.008 255); /* Was 0.70 */
--dark-11: oklch(0.85 0.005 255); /* Was 0.80 */
```

### Lighter surfaces
```css
--dark-4: oklch(0.28 0.012 255); /* Was 0.24 */
--dark-5: oklch(0.32 0.012 255); /* Was 0.28 */
```

### More prominent borders
```css
--dark-7: oklch(0.42 0.010 255); /* Was 0.38 */
```

---

## 📋 Testing Checklist

After making changes, test these scenarios:

- [ ] **Slide panel** opens with correct backdrop and surface colors
- [ ] **Modal** centers properly with blur effect
- [ ] **Dialog** has stronger backdrop than modal
- [ ] **Toast** appears with shadow and ring
- [ ] **Card** has visible header/footer borders
- [ ] **Alert** text is readable on colored backgrounds
- [ ] **Banner** displays at top with border
- [ ] **Dropdown** floats with proper elevation
- [ ] **Form inputs** have clear borders and readable text
- [ ] **Text hierarchy** is visually distinct (muted → primary → high)
- [ ] **Icons** have hover states
- [ ] **Dark mode** applies all CSS variables correctly
- [ ] **Contrast ratios** meet WCAG standards (4.5:1 minimum)

---

## 💡 Best Practices

1. **Always use semantic tokens** (`--color-text`, not `--dark-10`) in new components
2. **Maintain hierarchy**: Reserve `--dark-11` for titles only
3. **Consistent borders**: Use `--dark-7` for all dividers
4. **Surface elevation**: Higher = lighter (--dark-3 → --dark-4 → --dark-5)
5. **Hover states**: Typically move up 2 steps (--dark-8 → --dark-10)
6. **Test in context**: Components should work together seamlessly

---

## 🔗 Related Files

- **Configuration**: `app/Providers/AppServiceProvider.php`
- **Styles**: `resources/css/app.css`
- **Full Documentation**: `docs/tallstackui-dark-system-integration.md`

---

**Last Updated**: 2025
**Version**: 1.0
**System**: OKLCH Dark System (2025)
