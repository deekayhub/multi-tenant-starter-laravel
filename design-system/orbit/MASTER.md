# Design System Master File

> **LOGIC:** When building a specific page, first check `design-system/orbit/pages/[page-name].md`.
> If that file exists, its rules **override** this Master file.
> If not, strictly follow the rules below.
> Full handoff (wireframes, component roadmap, accessibility, CSS variables): `design-system/orbit/HANDOFF.md`.

---

**Project:** Orbit
**Generated:** 2026-11-19 12:25:59 (aligned to implementation 2026-11-19)
**Category:** SaaS (General) — product-neutral multi-tenant starter kit

---

## Global Rules

### Color Palette

| Role | Hex | CSS Variable | Tailwind token |
|------|-----|--------------|----------------|
| Primary | `#2563EB` | `--color-primary` | `primary` |
| On Primary | `#FFFFFF` | `--color-on-primary` | `text-white` |
| Secondary | `#3B82F6` | `--color-secondary` | `secondary` |
| Accent / CTA | `#EA580C` | `--color-accent` | `accent` |
| On Accent / CTA | `#000000` | `--color-on-accent` | `text-black` |
| Background | `#F8FAFC` | `--color-background` | `canvas` / `paper` |
| Foreground | `#1E293B` | `--color-foreground` | `foreground` / `ink` |
| Card | `#FFFFFF` | `--color-card` | `card` / `surface` |
| Muted | `#E9EFF8` | `--color-muted` | `muted` |
| Muted Foreground | `#475569` | `--color-muted-foreground` | `muted-foreground` / `ink-2` |
| Border | `#E2E8F0` | `--color-border` | `line` |
| Destructive | `#DC2626` | `--color-destructive` | `destructive` |
| Success | `#059669` | — | `success` / `positive` |
| Warning | `#D97706` | — | `warning` |
| Caution (text on tint) | `#B45309` | — | `caution` |
| Ring | `#2563EB` | `--color-ring` | `ring` |

**Color notes:** Trust blue + orange CTA. White on `#2563EB` ≈ 5.2:1; **black on `#EA580C` ≈ 7:1**, so the orange CTA must always use black text. Metadata (`ink-3 #5C6779`) stays ≥4.5:1 on canvas.

### Typography

- **Font:** Plus Jakarta Sans (300–800) — headings and body.
- **Mood:** friendly, modern, clean, approachable, professional.
- **Loaded via:** `fonts.bunny.net` (see `layouts/guest.blade.php`, `layouts/app.blade.php`, `welcome.blade.php`).

```css
<link rel="preconnect" href="https://fonts.bunny.net">
<link href="https://fonts.bunny.net/css?family=plus-jakarta-sans:300,400,500,600,700,800&display=swap" rel="stylesheet">
```

- Title scale: `text-3xl/4xl/5xl`, `font-extrabold`, `tracking-tight`.
- Metadata: `text-xs`, uppercase, `tracking-[0.12em–0.18em]`.

### Spacing Variables

| Token | Value | Usage |
|-------|-------|-------|
| `--space-2xs` | `8px` / `0.5rem` | Icon gaps, tight inline spacing |
| `--space-xs` | `12px` / `0.75rem` | Control padding |
| `--space-s` | `16px` / `1rem` | Standard padding |
| `--space-m` | `24px` / `1.5rem` | Section padding |
| `--space-l` | `32px` / `2rem` | Large gaps |
| `--space-xl` | `48px` / `3rem` | Hero / page rhythm |

### Shadow Depths

| Token | Value | Usage |
|-------|-------|-------|
| `--shadow-sm` | `0 1px 2px rgba(15,23,42,.05)` | Subtle lift |
| `shadow-glass` | `0 8px 32px -8px rgba(15,23,42,.16)` | Cards |
| `shadow-glass-lg` | `0 24px 60px -20px rgba(15,23,42,.35)` | Modals, dropdowns, panels |
| `shadow-blue-glow` | `0 12px 30px -10px rgba(37,99,235,.55)` | Primary CTA |
| `shadow-accent-glow` | `0 12px 30px -10px rgba(234,88,12,.45)` | Orange CTA |

---

## Component Specs

### Buttons

```css
.btn-primary {
  background: #2563EB; color: #fff; padding: 12px 20px;
  border-radius: 12px; font-weight: 600;
  box-shadow: 0 12px 30px -10px rgba(37,99,235,.55);
  transition: all 150ms ease;
}
.btn-primary:hover { background: #1D4ED8; }
.btn-primary:focus-visible { outline: 2px solid #2563EB; outline-offset: 2px; }

.btn-accent { background: #EA580C; color: #000; } /* black label for 7:1 */
.btn-secondary { background: rgba(255,255,255,.8); border: 1px solid #E2E8F0; color: #1E293B; }
.btn-quiet { color: #475569; background: transparent; }
```

### Cards

```css
.glass-card {
  border: 1px solid rgba(255,255,255,.8);
  background: rgba(255,255,255,.8);
  border-radius: 16px;
  backdrop-filter: blur(16px);
  box-shadow: 0 8px 32px -8px rgba(15,23,42,.16);
}
.surface-card { @apply glass-card p-5; }
```

### Inputs

```css
.field-input {
  width: 100%; padding: 12px 16px; border: 1px solid #E2E8F0;
  border-radius: 12px; background: rgba(255,255,255,.8);
  font-size: 14px; color: #1E293B; transition: all 150ms ease;
}
.field-input:focus {
  border-color: #2563EB; outline: none;
  box-shadow: 0 0 0 4px rgba(37,99,235,.15);
}
.field-input-error { border-color: #DC2626; }
```

### Modals

```css
/* scrim */ background: rgba(2,6,23,.6); backdrop-filter: blur(4px);
/* dialog */ background: rgba(255,255,255,.95); border: 1px solid rgba(255,255,255,.8);
border-radius: 16px; box-shadow: 0 24px 60px -20px rgba(15,23,42,.35);
max-width: 42rem;
```

---

## Style Guidelines

**Style:** Glassmorphism (light theme)

**Keywords:** Frosted glass, transparent, blurred background, layered, vibrant background, light source, depth.

**Best For:** Modern SaaS, financial dashboards, high-end corporate, modal overlays, navigation.

**Key Effects:** Backdrop blur 10–20px, 1px white/70–80 border, light reflection, Z-depth. Gradient used sparingly for brand panels/hero only — not on body text.

### Page Pattern

**Pattern Name:** Hero + Features + CTA (marketing page). Dashboard/auth screens use their own layouts — see page overrides.

- **Conversion Strategy:** Deep CTA placement. Verify CTA label contrast ≥4.5:1 (7:1 when targeting AAA). Keep focus and component boundaries independently visible. Disable parallax under reduced motion.
- **CTA Placement:** Hero + bottom of page.
- **Section Order:** Hero → value prop → 3–5 features → CTA → footer.

---

## Anti-Patterns (Do NOT Use)

- ❌ Excessive animation
- ❌ Dark mode by default
- ❌ Emojis as icons — use the single SVG icon set
- ❌ Missing cursor pointer on clickable elements
- ❌ Layout-shifting hovers — keep transforms ≤2px
- ❌ Low contrast text — maintain 4.5:1 minimum
- ❌ Instant state changes — use 150–300ms transitions
- ❌ Invisible focus states — always visible for keyboard users
- ❌ White text on the orange CTA — use black

---

## Pre-Delivery Checklist

- [ ] No emojis used as icons; consistent SVG icon set
- [ ] `cursor-pointer` on all clickable elements
- [ ] Hover states with 150–300ms transitions
- [ ] Light mode: text contrast 4.5:1 minimum
- [ ] Focus states visible for keyboard navigation
- [ ] `prefers-reduced-motion` respected
- [ ] Responsive: 375px, 768px, 1024px, 1440px
- [ ] No content hidden behind fixed navbars (`scroll-padding-top`)
- [ ] No horizontal scroll on mobile
