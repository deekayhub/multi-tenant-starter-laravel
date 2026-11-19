# Orbit — UI/UX Handoff Spec

> **Status:** Implemented in the Laravel/Breeze app (Blade + Tailwind + Alpine). No React layer or new dependencies.
> **Source of truth for tokens:** `tailwind.config.js` and `resources/css/app.css`.
> **Page overrides:** `design-system/orbit/pages/{login,register,dashboard}.md`.

---

## 1. Visual design system

### Color tokens

| Token | Hex | Usage |
|---|---|---|
| `--color-primary` | `#2563EB` | Primary actions, active nav, links, focus ring |
| `--color-primary-dark` | `#1D4ED8` | Primary hover |
| `--color-primary-soft` | `#EFF6FF` | Tinted surfaces |
| `--color-secondary` | `#3B82F6` | Chart series, gradients |
| `--color-accent` | `#EA580C` | High-emphasis CTA ("New tenant"), notification dot |
| `--color-accent-dark` | `#C2410C` | Accent hover |
| `--color-background` / `paper` | `#F8FAFC` | App canvas |
| `--color-card` / `surface` | `#FFFFFF` | Card base (rendered at `/80` + blur) |
| `--color-foreground` / `ink` | `#1E293B` | Headings and body text |
| `--color-muted-foreground` / `ink-2` | `#475569` | Secondary text |
| `--color-ink-3` | `#5C6779` | Low-priority metadata (≥4.5:1 on canvas) |
| `--color-border` / `line` | `#E2E8F0` | Hairlines and dividers |
| `--color-destructive` | `#DC2626` | Errors and destructive actions |
| `--color-success` / `positive` | `#059669` | Positive deltas, success status |
| `--color-warning` | `#D97706` | Warning accents |
| `--color-caution` | `#B45309` | Warning text on tinted backgrounds |
| `--color-ring` | `#2563EB` | Universal keyboard focus ring |

**Contrast notes:** primary `#2563EB` on white ≈ 5.2:1; white label on primary ≈ 5.2:1. Accent `#EA580C` uses **black** label text (≈ 7:1) — never white on orange. Body and metadata text stay ≥ 4.5:1. Focus and component boundaries are independent of color alone.

### Typography

- **Family:** Plus Jakarta Sans (300–800), loaded from `fonts.bunny.net`, with a system sans fallback.
- **Scale:** `text-xs` metadata/eyebrows → `text-sm` body → `text-xl` card headings → `text-4xl/5xl` page titles → `text-5xl/6xl` brand headlines.
- **Weights:** 500–600 for UI labels, 700–800 for headings and CTAs.
- **Tracking:** `tracking-tight` on large headings, `tracking-[0.12em–0.18em]` on uppercase eyebrows.

### Spacing (4px base)

| Token | Value | Usage |
|---|---|---|
| `--space-2xs` | `0.5rem` / 8px | Icon gaps, tight inline spacing |
| `--space-xs` | `0.75rem` / 12px | Control padding |
| `--space-s` | `1rem` / 16px | Standard padding |
| `--space-m` | `1.5rem` / 24px | Section padding |
| `--space-l` | `2rem` / 32px | Section gaps |
| `--space-xl` | `3rem` / 48px | Hero / page rhythm |

Tailwind utilities use `gap-2/3/4`, `p-3/4/5/6`, `py-8`, `space-y-5/6/7` on top of this scale.

### Shape, depth, motion

- Radius: `rounded-lg` controls, `rounded-xl` inputs/buttons, `rounded-2xl` cards/modals.
- Frosted surfaces: `backdrop-blur-xl` (16px), 1px white/70–80 border, `shadow-glass` / `shadow-glass-lg`.
- Motion: `--dur-fast: 150ms`, `--dur: 220ms`, `--ease: cubic-bezier(0.2,0,0,1)`. Hover movement ≤2px; `prefers-reduced-motion` collapses all animation.

### Iconography

- One vector family: outline SVG icons via `<x-icon name="…">`, `stroke-width="1.7"`, sizes `h-4`, `h-[18px]`, `h-5`.
- Decorative icons beside text: `aria-hidden="true"` (set in the component).
- Standalone icon controls (password toggle, notifications, logout): explicit `aria-label`; toggle also exposes `aria-pressed`.
- No emoji as structural icons; no mixed filled/outline styles in the same layer.

---

## 2. Wireframes

### Login — desktop (≥1024px)

```
┌──────────────────────────────┬────────────────────────────────────┐
│ ◉ orbit | Starter kit   Home │ MULTI-TENANT FOUNDATION      v1.0  │
│                              │  (gradient + sheen + grid texture) │
│   ╔════════════════════════╗  │                                    │
│   ║ ACCOUNT ACCESS         ║  │  One app.                          │
│   ║ Welcome back.          ║  │  Every customer.                   │
│   ║ Sign in to your        ║  │  A clean starting point for SaaS…  │
│   ║ workspace.             ║  │                                    │
│   ║ ┌────────────────────┐ ║  │  ┌──────────────────────────────┐  │
│   ║ │ ✉ you@example.com  │ ║  │  │ WORKSPACE DIRECTORY      03  │  │
│   ║ └────────────────────┘ ║  │  │ AC  Acme Workspace   Active  │  │
│   ║ Forgot password?       ║  │  │ GL  Globex Labs      Trial   │  │
│   ║ ┌────────────────────┐ ║  │  │ IN  Initech Co.      Active  │  │
│   ║ │ 🔒 Enter password  👁│ ║  │  └──────────────────────────────┘  │
│   ║ └────────────────────┘ ║  │                                    │
│   ║ ☐ Keep me signed in    ║  │ Auth · Tenancy · Roles · Activity  │
│   ║ ┌────────────────────┐ ║  │ Placeholder content                │
│   ║ │ Sign in         →  │ ║  │                                    │
│   ║ └────────────────────┘ ║  │                                    │
│   ║ ─────────────────────  ║  │                                    │
│   ║ New to this starter?   ║  │                                    │
│   ║ Create an account      ║  │                                    │
│   ╚════════════════════════╝  │                                    │
│ © 2026 Orbit  Privacy Terms   │                                    │
└──────────────────────────────┴────────────────────────────────────┘
```

Mobile: brand panel is removed; the frosted card is centered in a single column with the compact header/footer retained. Validation errors appear as a focused, linked red summary at the top of the form plus inline messages under each field.

### Register — desktop

```
┌──────────────────────────────┬────────────────────────────────────┐
│ ◉ orbit | Starter kit   Home │  (same brand panel as login)       │
│   ╔════════════════════════╗  │                                    │
│   ║ GET STARTED             ║  │                                    │
│   ║ A clean slate.          ║  │                                    │
│   ║ Create an account to    ║  │                                    │
│   ║ explore the workspace.  ║  │                                    │
│   ║ Full name               ║  │                                    │
│   ║ [ Ada Lovelace        ] ║  │                                    │
│   ║ Email address           ║  │                                    │
│   ║ [ you@example.com     ] ║  │                                    │
│   ║ Password                ║  │                                    │
│   ║ [ At least 8 chars    ] ║  │                                    │
│   ║ Confirm password        ║  │                                    │
│   ║ [ Repeat your password] ║  │                                    │
│   ║ ┌────────────────────┐ ║  │                                    │
│   ║ │ Create account  →  │ ║  │                                    │
│   ║ └────────────────────┘ ║  │                                    │
│   ║ ─────────────────────  ║  │                                    │
│   ║ Already have an account?  ║                                    │
│   ║ Sign in                 ║  │                                    │
│   ╚════════════════════════╝  │                                    │
└──────────────────────────────┴────────────────────────────────────┘
```

### Dashboard — desktop

```
┌──────────────────┬──────────────────────────────────────────────────────┐
│ ◉ orbit     Kit  │ ☰  Acme Workspace / Overview   [Search] [🔔] [AL ▾] │
│ ┌──────────────┐ │                                    [+ New tenant]     │
│ │ AC Acme    ▾ │ ├──────────────────────────────────────────────────────┤
│ │ Demo·3 ten.  │ │ Thursday, 19 November 2026                          │
│ └──────────────┘ │ Good morning, Ada.                                   │
│ WORKSPACE       │ Snapshot of the starter workspace… [Export][+ New]    │
│ ▣ Overview      │ ┌────────┬────────┬────────┬────────┐                 │
│ □ Projects   4  │ │Active  │Monthly │Active  │Uptime  │                 │
│ □ Tasks     12  │ │tenants │revenue │members │        │                 │
│ □ Activity     │ │  12    │ $8,940 │  248   │99.98%  │                 │
│ TENANCY        │ └────────┴────────┴────────┴────────┘                 │
│ ▣ Tenants   3  │ ┌───────────────────────────┬────────────────┐        │
│ □ Members     │ │ Workspace activity   📈  │ Needs attention│        │
│ □ Billing     │ │  ┌───── chart ─────────┐  │ 3 pending…     │        │
│ □ Integrations│ │  └─────────────────────┘  │ Trial ending…  │        │
│ ACCOUNT       │ │ 31 tenants this week     │ [Review setup] │        │
│ ⚙ Settings    │ └───────────────────────────┴────────────────┘        │
│ ○ Profile     │ ┌───────────────────────────┬────────────────┐        │
│ Starter note  │ │ Recent activity (table)  │ Quick actions  │        │
│ [AL Ada   ⏻ ] │ │ Tenant created …         │ Connections    │        │
└───────────────┴─┴───────────────────────────┴────────────────┘────────┘
```

Mobile: sidebar becomes a drawer behind a scrim; top bar keeps menu, breadcrumb, notifications and account menu; cards stack to one column; the activity table scrolls horizontally.

### Shared shell / profile

The app shell is used by the dashboard and profile screens. Profile content is a centered `max-w-3xl` stack of three frosted cards: profile information, update password, delete account (destructive border). The same error-summary and inline-error patterns as auth apply to all forms.

---

## 3. UI component library roadmap

| Group | Component | Status | Notes |
|---|---|---|---|
| Foundations | Color/type/space tokens, focus, motion | ✅ Shipped | CSS variables + Tailwind theme |
| Actions | Primary, accent, secondary, quiet, danger buttons | ✅ Shipped | `btn-*` classes and Blade components |
| Actions | Icon button, loading/spinner state, split button | ⏭ Next | Add `aria-busy` and disabled semantics |
| Forms | Label, text input, inline error, error summary, checkbox | ✅ Shipped | ARIA wiring built into components |
| Forms | Select, textarea, toggle/switch, field hint | ⏭ Next | Reuse `.field-input` and error contracts |
| Feedback | Session status (success), inline/alert errors | ✅ Shipped | `role="status"` / `role="alert"` |
| Feedback | Toast, empty state, skeleton loader | ⏭ Next | Announce politely; respect reduced motion |
| Navigation | Sidebar, nav links, dropdown, mobile drawer, breadcrumb | ✅ Shipped | `aria-current`, `aria-expanded`, dialog semantics |
| Surfaces | Glass card, panel, modal dialog, table | ✅ Shipped | Trap focus, label dialogs, `th scope` |
| Surfaces | Tabs, accordion, popover | ⏭ Next | Keyboard arrows and `aria-selected` |
| Data display | Metric card, chart, badge, avatar, tenant switcher | ✅ Shipped | Chart has `<title>`/`<desc>` |
| Patterns | Profile forms, auth forms, starter dashboard | ✅ Shipped | Replaceable placeholder content |

---

## 4. Accessibility considerations

- **Landmarks & navigation:** `skip to content` link first in DOM; `<nav aria-label="Main navigation">`; sidebar link uses `aria-current="page"`.
- **Dialogs:** mobile drawer and delete-account modal use `role="dialog"`, `aria-modal="true"`, an accessible name, Escape-to-close, backdrop click, and a focus trap. Trigger exposes `aria-controls` + `aria-expanded` where applicable.
- **Forms:** every control has a persistent visible `<label>`. Invalid fields get `aria-invalid="true"` and `aria-describedby` pointing to an inline `role="alert"` message. Failed multi-error forms focus a `role="alert"` summary whose items are links to the fields.
- **Focus visibility:** global `:focus-visible` 2px `--color-ring` outline with 2px offset; `html { scroll-padding-top: 4rem }` stops the sticky header from covering focus.
- **Status messages:** `role="status"` for session/saved confirmations, `role="alert"` for errors. Status colors are paired with text, never color-only.
- **Icons:** decorative icons are `aria-hidden="true"`; icon-only controls have `aria-label`; the password toggle exposes `aria-pressed`.
- **Motion:** `prefers-reduced-motion: reduce` collapses animation and transition durations; hover translations are ≤2px.
- **Contrast:** primary and body/metadata text ≥4.5:1; accent CTA uses black text (≥7:1); focus and boundaries ≥3:1.
- **Responsive/zoom:** verified at 375px, 768px, 1024px, 1440px; no horizontal scroll on mobile; content reflows at 200% zoom.
- **Password managers & paste:** `autocomplete="username|current-password|new-password"` set correctly; nothing blocks paste.

---

## 5. Design spec / CSS variables

Authored in `resources/css/app.css`:

```css
:root {
  --color-primary: #2563EB;
  --color-on-primary: #FFFFFF;
  --color-secondary: #3B82F6;
  --color-accent: #EA580C;
  --color-on-accent: #000000;
  --color-background: #F8FAFC;
  --color-foreground: #1E293B;
  --color-card: #FFFFFF;
  --color-muted: #E9EFF8;
  --color-muted-foreground: #475569;
  --color-border: #E2E8F0;
  --color-destructive: #DC2626;
  --color-ring: #2563EB;
  --font-sans: 'Plus Jakarta Sans', ui-sans-serif, system-ui, sans-serif;
  --space-2xs: .5rem; --space-xs: .75rem; --space-s: 1rem;
  --space-m: 1.5rem; --space-l: 2rem; --space-xl: 3rem;
  --radius-card: 1rem;
  --glass-blur: 16px;
  --glass-border: 1px solid rgba(255,255,255,.7);
  --dur-fast: 150ms; --dur: 220ms;
  --ease: cubic-bezier(.2,0,0,1);
}
```

Composable classes: `.glass-card`, `.glass-panel`, `.surface-card`, `.field-label`, `.field-input`, `.field-input-error`, `.btn-primary`, `.btn-accent`, `.btn-secondary`, `.btn-quiet`, `.eyebrow`.

---

## 6. Minimal accessible login snippet (Blade + Tailwind)

The production version lives in `resources/views/auth/login.blade.php`. This is the reduced pattern:

```blade
<form method="POST" action="{{ route('login') }}" class="space-y-6">
    @csrf

    {{-- Focused, linked summary when validation fails --}}
    <x-form-error-summary :errors="$errors" />

    <div>
        <label for="email" class="field-label">{{ __('Email address') }}</label>
        <input id="email" name="email" type="email" value="{{ old('email') }}"
               required autofocus autocomplete="username" placeholder="you@example.com"
               @class(['field-input', 'field-input-error' => $errors->has('email')])
               @if ($errors->has('email')) aria-invalid="true" aria-describedby="email-error" @endif>
        @error('email')
            <p id="email-error" role="alert" class="mt-2 text-xs font-medium text-destructive">{{ $message }}</p>
        @enderror
    </div>

    <div x-data="{ visible: false }">
        <label for="password" class="field-label">{{ __('Password') }}</label>
        <div class="relative">
            <input id="password" name="password" :type="visible ? 'text' : 'password'"
                   type="password" required autocomplete="current-password"
                   @class(['field-input pr-12', 'field-input-error' => $errors->has('password')])
                   @if ($errors->has('password')) aria-invalid="true" aria-describedby="password-error" @endif>
            {{-- Keyboard-operable reveal control with state announced --}}
            <button type="button" @click="visible = !visible"
                    :aria-label="visible ? 'Hide password' : 'Show password'" :aria-pressed="visible"
                    class="absolute inset-y-0 right-0 flex w-12 items-center justify-center focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-primary">
                <x-icon name="eye" aria-hidden="true" />
            </button>
        </div>
        @error('password')
            <p id="password-error" role="alert" class="mt-2 text-xs font-medium text-destructive">{{ $message }}</p>
        @enderror
    </div>

    <button type="submit" class="btn-primary w-full">{{ __('Sign in') }}</button>
</form>
```

**Keyboard flow:** `Tab` moves label → input → reveal button → submit in DOM order; `Enter` submits from any field; on failure focus jumps to the error summary, and each error link jumps to its field; `Space`/`Enter` toggles password visibility with announced state.
