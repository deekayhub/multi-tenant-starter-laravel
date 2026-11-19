# Register Page Overrides

> **PROJECT:** Orbit
> **Page Type:** Authentication (split-screen)

> ⚠️ This file **overrides** `design-system/orbit/MASTER.md` for the registration screen.
> Everything not listed here follows the Master file.

---

## Page-Specific Rules

### Layout Overrides

- Uses the exact same `guest` shell and split-screen layout as `login.md`.
- Form card holds four stacked fields: full name, email, password, confirm password (`space-y-5`).
- **No** multi-step wizard and **no** progress indicator — this is a single submit.

### Spacing Overrides

- Medium density, `max-w-[30rem]` card, `py-8` page padding.
- Link to sign-in sits below a `border-t` divider.

### Typography Overrides

- Same title scale as login (`text-3xl sm:text-4xl font-extrabold`).
- Helper copy at `text-sm leading-6 text-muted-foreground`.

### Color Overrides

- Same as login: `bg-canvas` form side, `bg-brand-gradient` brand side.
- Submit uses `btn-primary` (blue `#2563EB`, white label) — the orange `accent` color is reserved for high-emphasis CTAs elsewhere, not the primary form action.

### Component Overrides

- `x-text-input` automatically adds `field-input-error`, `aria-invalid="true"` and `aria-describedby="<name>-error"` when the field fails validation.
- `x-form-error-summary` links each error to the corresponding field id.
- Password fields use `autocomplete="new-password"` so password managers can generate and fill them.

### Accessibility Overrides

- Identical error summary, focus, and reduced-motion behavior to `login.md`.
- Placeholder text is supplementary only — every field has a persistent visible `<label>`.
