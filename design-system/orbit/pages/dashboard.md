# Dashboard Page Overrides

> **PROJECT:** Orbit
> **Page Type:** Dashboard / Data View

> ⚠️ This file **overrides** `design-system/orbit/MASTER.md` for the dashboard screen.
> Everything not listed here follows the Master file.

---

## Page-Specific Rules

### Layout Overrides

- **Shell:** fixed 17rem (`w-[17rem]`) sidebar on `lg+`; content area uses `lg:pl-[17rem]`. Below `lg`, the sidebar becomes an off-canvas drawer (`role="dialog"`, `aria-modal="true"`).
- **Top bar:** sticky `h-16`, `bg-white/70 backdrop-blur-xl`, `z-30`; global search appears at `lg+`.
- **Content:** `max-w-[90rem]` centered, `px-4 sm:px-6 lg:px-8`, `py-8`, `pb-16`.
- **Grids:** metrics `grid-cols-1 sm:grid-cols-2 xl:grid-cols-4`; chart/attention `xl:grid-cols-3` (chart spans 2); activity/shortcuts `xl:grid-cols-3`.

### Spacing Overrides

- High density: `gap-4` between cards, `gap-7` between sections, `p-5` card padding.
- Respect `scroll-padding-top: 4rem` so the sticky header never covers focused content.

### Typography Overrides

- Greeting: Plus Jakarta Sans `text-4xl sm:text-5xl font-extrabold tracking-tight`.
- Metric values: `text-4xl font-extrabold`; labels: `text-xs font-bold uppercase tracking-[0.1em]`.
- Section headings: `text-xl font-extrabold`.

### Color Overrides

- Surfaces: `glass-card` (`bg-white/80 backdrop-blur-xl border-white/80 shadow-glass`).
- Positive notes use `text-success #059669`; warnings use `text-caution` on `bg-warning/10`; critical uses `text-destructive`.
- Accent orange `#EA580C` is used only for the "New tenant" CTA and the notification dot.

### Component Overrides

- Metric cards, activity table, attention list, quick-action tiles, connection list, and an inline accessible SVG chart (`role="img"` with `<title>`/`<desc>`).
- All sidebar navigation icons come from the single `x-icon` set (Heroicons-style outline, `stroke-width="1.7"`, `aria-hidden="true"`).
- `aria-current="page"` marks the active sidebar item; badges are text, not color-only status.

### Accessibility Overrides

- "Skip to content" link is the first focusable element.
- Mobile menu button exposes `aria-controls` and `:aria-expanded`; account dropdown does the same.
- Chart has a text alternative; table uses `<th scope="col">`.
- Hover translations are ≤2px and disabled under `prefers-reduced-motion`.
