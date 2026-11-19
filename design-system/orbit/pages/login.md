# Login Page Overrides

> **PROJECT:** Orbit
> **Page Type:** Authentication (split-screen)

> ⚠️ This file **overrides** `design-system/orbit/MASTER.md` for the login screen.
> Everything not listed here follows the Master file.

---

## Page-Specific Rules

### Layout Overrides

- **Desktop (≥1024px):** two columns — form column `minmax(0,1fr)`, brand column `minmax(0,1.05fr)`. The brand column is `sticky top-0` and full viewport height.
- **Mobile / tablet:** single column; the brand panel is hidden and only the frosted form card is shown.
- **Form measure:** `max-w-[30rem]` centered in a `glass-card` (`p-6 sm:p-9`). Do **not** use the Master's 1400px data-view width here.
- **Header/footer:** compact brand bar above the card; legal links and copyright below it inside the form column.

### Spacing Overrides

- Auth density is **medium**, not high: `space-y-6` between fields, `py-8` around the card.
- Section divider above the sign-in link (`border-t border-line pt-6`).

### Typography Overrides

- Page title: Plus Jakarta Sans `text-3xl sm:text-4xl font-extrabold tracking-tight`.
- Eyebrow (`ACCOUNT ACCESS`): `text-xs font-semibold uppercase tracking-[0.14em] text-muted-foreground`.
- Brand panel headline: `text-5xl xl:text-6xl font-extrabold leading-[1.05]`.

### Color Overrides

- Form side: `bg-canvas #F8FAFC`, `text-foreground #1E293B`.
- Brand side: `bg-brand-gradient` (blue → indigo → violet) with `bg-brand-sheen` highlight and a white/10 frosted workspace list.

### Component Overrides

- Two fields only: email (`autocomplete="username"`) and password (`autocomplete="current-password"`).
- Password control is a **button** with dynamic `:aria-label` and `:aria-pressed` — keyboard accessible, no icon-only ambiguity.
- Single-step auth: **no** step indicator or progress bar (the generator's generic "feedback" recommendation does not apply).
- Decorative input icons are `aria-hidden="true"`.

### Accessibility Overrides

- A linked `<x-form-error-summary>` is focused on failed submit; each message is an in-page link to the invalid field.
- Inline errors use `role="alert"` plus a stable `id` (`email-error`, `password-error`); inputs get `aria-invalid` and `aria-describedby`.
- Focus ring uses `--color-ring` with 2px offset; `prefers-reduced-motion` disables transitions.
