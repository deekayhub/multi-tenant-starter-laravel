# Orbit Design Specification

This document describes the design currently implemented in this Laravel application. It supersedes older `design-system/orbit/` notes where they describe a blue `#2563EB` accent, Plus Jakarta Sans, or glass-heavy surfaces. The source of truth for tokens is `tailwind.config.js` and `resources/css/app.css`.

## 1. Product and visual direction

Orbit is a multi-tenant SaaS starter with three distinct surfaces:

| Surface | Layout | Audience |
| --- | --- | --- |
| Guest | Split-screen authentication shell | Visitors signing in or registering |
| Tenant app | Dark sidebar + white sticky topbar | Workspace members |
| Central admin | Technical operations console | Central users and super admins |

The current visual language is a neutral, Shadcn-inspired SaaS system: near-black primary, white surfaces, cool zinc borders, restrained shadows, Inter typography, and no decorative accent hue. The interface should feel operational, quiet, and dense enough for admin work while remaining readable on small screens.

## 2. Foundations

### 2.1 Color tokens

| Token | Value | Use |
| --- | --- | --- |
| `primary` | `#18181B` | Primary buttons, focus rings, sign-in bars |
| `primary.dark` | `#3F3F46` | Hover/adjoining neutral depth |
| `primary.soft` | `#F4F4F5` | Soft neutral fill |
| `canvas` / `paper` / `secondary` | `#FAFAFA` | Page background |
| `card` / `surface` | `#FFFFFF` | Cards, panels, table containers |
| `foreground` / `ink` | `#09090B` | Body and heading text |
| `muted-foreground` / `ink-2` | `#71717A` | Supporting copy, labels, table meta |
| `ink-3` | `#A1A1AA` | Tertiary text |
| `line` | `#E4E4E7` | Borders, dividers, table rules |
| `muted` / `accent` | `#F4F4F5` | Hover backgrounds, icon chips |
| `destructive` / `critical` | `#DC2626` | Delete and error states |
| `success` / `positive` | `#16A34A` | Verified and positive status |
| `warning` | `#D97706` | Pending or caution status |
| `caution` | `#B45309` | Pending text contrast |
| `accent-dark` | `#E4E4E7` | Stronger neutral hover |
| brand gradient | `#18181B` → `#27272A` → `#3F3F46` | Guest brand panel |

Legacy aliases such as `paper`, `surface`, `ink`, `ink-2`, `ink-3`, `positive`, `caution`, and `critical` remain supported, but new views should prefer the semantic token names.

### 2.2 Typography

- Font family: `Inter`, then the Tailwind sans stack.
- Guest layouts load weights `300,400,500,600,700,800`; admin layouts load `400,500,600,700,800`.
- Page titles: `text-2xl font-semibold tracking-tight`, escalating to `sm:text-3xl`.
- Section headings: `text-base font-semibold`.
- Eyebrows and table headers: uppercase, small, wide tracking, `muted-foreground`.
- Body copy: `text-sm leading-6`; secondary copy uses `muted-foreground`.

### 2.3 Spacing, shape, and depth

- Spacing scale: `0.5rem`, `0.75rem`, `1rem`, `1.5rem`, `2rem`, `3rem`.
- Page content typically uses `space-y-6`, with `px-4 sm:px-6 lg:px-8` gutters.
- Card radius: `0.75rem`; small controls use `rounded-lg` or `rounded-md`.
- Cards use `glass-card`: white surface, `border-line`, and `shadow-glass`. Despite the name, there is no blur; `--glass-blur` is `0px`.
- Motion: `150ms` for fast interaction, `220ms` for standard transitions, `cubic-bezier(0.2, 0, 0, 1)`.
- Focus: `2px` ring using `primary`, `2px` offset. Sidebar controls use white rings for contrast on dark surfaces.

## 3. Shared component language

| Class / component | Purpose |
| --- | --- |
| `glass-card` | Default surface for forms, KPIs, tables, and settings cards |
| `glass-panel` | Higher-elevation panel for emphasized content |
| `surface-card` | `glass-card` with `p-5`, used for dashboard tiles |
| `eyebrow` | Short uppercase section label |
| `btn-primary` | Main action, near-black fill |
| `btn-secondary` | Secondary action, white with `line` border |
| `btn-accent` | Neutral filled action using `accent` |
| `btn-quiet` | Low-emphasis text button |
| `field-label`, `field-input`, `field-input-error` | Form controls using the shared 44px input height |
| `form-grid`, `form-field`, `form-field-full` | Six-column responsive form grid |
| `<x-icon>` | Named inline SVG icons; prefer it over ad-hoc SVGs |
| `<x-dropdown>` | User/account menus with keyboard-friendly triggers |
| `<x-sidebar-menu-item>` | Single dark-nav item with active state |
| `<x-sidebar-submenu>` | Collapsible dark-nav group with active child tracking |
| `<x-app-sidebar>` / `<x-admin-sidebar>` | Tenant and central navigation rails |
| `<x-app-header>` / `<x-admin-header>` | Tenant and central topbars |

Buttons use `gap-2`, medium weight, `rounded-lg`, `transition-colors duration-150`, and `active:translate-y-px`. Disabled buttons are `cursor-not-allowed opacity-50`.

## 4. Shells and navigation

### 4.1 Tenant app shell

Files: `resources/views/layouts/app.blade.php`, `resources/views/components/app-sidebar.blade.php`, `resources/views/components/app-header.blade.php`.

- Dark rail: `#09090B` with a subtle white radial glow; width `17rem`, collapsing to `4.5rem`.
- Collapse state is persisted in `localStorage` under `orbit:sidebar-collapsed`.
- Mobile drawer uses `role="dialog"`, `aria-modal="true"`, a zinc overlay, and closes on Escape or a `close-sidebar` event.
- Navigation groups are `Workspace` and `Account`.
- Base workspace items: Overview and Activity, with Activity gated by `activity-logs.view` in tenant context.
- Account/Settings children: Profile, Workspace (`workspace.view`), Team Members (`users.view`), and Roles & Permissions (`roles.view`).
- Active items use `.app-sidebar-active`: translucent white fill, white text, no colored border.
- Active children use `.app-sidebar-subactive`.
- Collapsed items expose `title` and `aria-label` from `data-label`.
- Topbar is sticky, white, `border-line`, and `h-16`. It shows context, workspace name, current section, and an account dropdown.
- The dropdown uses signed logout URLs: `logout` in tenant context, `central.logout` outside it.

### 4.2 Central admin console

Files: `resources/views/components/admin-layout.blade.php`, `resources/views/components/admin-sidebar.blade.php`, `resources/views/components/admin-header.blade.php`.

- Dark sidebar, white topbar, white content surface on `#FAFAFA` canvas.
- Sidebar width is `16rem`, collapsing to `4.5rem`; state is persisted under `orbit:admin-sidebar-collapsed`.
- Super admin links: Dashboard, Tenants, Activity Logs, Profile.
- Central user links: Dashboard, Activity, Profile.
- Profile is always appended and is active for `central.profile.*` in both sidebar and account dropdown.
- Signed logout always uses `central.logout`.
- Main content is capped at `90rem` with `px-4 pb-16 pt-8 sm:px-6 lg:px-8`.
- Status messages use the neutral `.admin-status-message` style; page headers use `.admin-page-header`.
- On mobile, primary navigation becomes a horizontally scrollable strip below the topbar.

### 4.3 Guest shell

File: `resources/views/layouts/guest.blade.php`.

- Split layout on `lg`: form column on the left, fixed dark brand panel on the right.
- Ambient zinc blur circles and a subtle grid live behind the form; they are decorative and `aria-hidden`.
- Form content sits in a centered `glass-card` with `max-w-[34rem]`.
- Header contains brand mark and Home link; footer contains Privacy, Terms, and Help links.
- Brand panel uses the neutral brand gradient, uppercase kicker, large headline, and a translucent workspace-directory illustration.

## 5. Page patterns

### 5.1 Tenant dashboard

File: `resources/views/dashboard.blade.php`.

- Header: eyebrow `Workspace overview`, personalized greeting, short description, and profile action.
- KPI grid: four `surface-card` tiles — Team members, Active roles, Sign-ins · 30 days, Domains — using `sm:grid-cols-2 xl:grid-cols-4`.
- Sign-in chart: seven-day CSS bar chart, accessible as `role="img"` with a descriptive label; zero-count days render a muted minimum-height bar.
- Shortcuts: permission-aware list of profile, workspace, team, roles, and activity links.
- Team table: newest tenant-scoped users with role badge, verified/pending status badge, and relative joined time.
- Recent activity table: sign-in/sign-out events with success/warning badges.
- Links and data must respect tenant context and the same permission gates used by navigation.

### 5.2 Profile

- Tenant profile uses `resources/views/profile/edit.blade.php` inside `<x-settings-layout>`.
- Central profile uses `resources/views/admin/profile.blade.php` inside `<x-admin-layout>`.
- Both reuse the same three partials: update profile information, update password, delete user.
- Central profile overrides route names to `central.verification.send`, `central.profile.update`, `central.password.update`, and `central.profile.destroy`.
- Destructive card uses `border-destructive/20`; the delete button stays `destructive`.

### 5.3 Settings, activity, and admin tables

- Settings pages use `<x-settings-layout>` with a title and description.
- Tables use `min-w-full`, `divide-y divide-line`, `border-line` headers, and `hover:bg-muted/50` rows.
- Status badges are small uppercase chips with tinted borders and text.
- Empty states are centered in the table body with muted copy.
- Admin data tables keep the same alignment and hierarchy but use the admin console class family for surrounding chrome.

## 6. Tenancy and permission-aware UI

- Tenant context is detected with `tenant() !== null`.
- Central profile routes are prefixed with `central.`; tenant profile routes are not.
- Navigation labels and dashboard shortcuts are derived from `workspace.view`, `users.view`, `roles.view`, and `activity-logs.view`.
- Super-admin-only admin navigation is derived from `is_super_admin`.
- Dashboard queries are scoped by the current user's tenant key, including role counts via `Role::withoutGlobalScope(TenantRoleScope::class)` plus an explicit `tenant_id` filter.
- Never render cross-tenant names, emails, roles, or activity in tenant views.

## 7. Accessibility rules

- Every layout provides a skip link to the main landmark.
- Use semantic landmarks: `header`, `nav`, `main`, `section`, and `table` with `scope="col"`.
- Active navigation uses `aria-current="page"`.
- Disclosure controls use `aria-expanded` and `aria-controls`; keyboard activation focuses the first child link.
- Sidebar collapse controls use `aria-controls` and `aria-expanded`.
- Decorative icons and avatar initials use `aria-hidden="true"`.
- Charts need `role="img"` and a meaningful `aria-label`.
- Form errors use the `updatePassword` and `userDeletion` error bags where appropriate.
- Maintain visible focus rings on every interactive element.
- Respect `prefers-reduced-motion` by keeping transitions short and non-essential.

## 8. Responsive behavior

- Base breakpoint is mobile; layouts expand at `sm`, `md`, `lg`, and `xl`.
- Sidebar rails hide below `lg`; the drawer takes over.
- KPI cards: one column → `sm:grid-cols-2` → `xl:grid-cols-4`.
- Dashboard chart and shortcuts: one column → `xl:grid-cols-3` with the chart spanning two columns.
- Tables scroll horizontally inside `overflow-x-auto` wrappers; nonessential columns are hidden on small screens.
- Settings forms use `form-grid`; most fields are `form-field` and full-width fields are `form-field-full`.

## 9. Anti-patterns

- Do not reintroduce blue, violet, or other brand accents; the system is intentionally neutral.
- Do not use colored left borders for active navigation; use translucent fills and text contrast.
- Do not add blur to `glass-card`; the name is historical.
- Do not hardcode route names where central and tenant variants differ; pass overrides into shared partials.
- Do not display unscoped counts or records in tenant views.
- Do not add new dependency or token systems without updating `tailwind.config.js` and `resources/css/app.css`.
- Do not write ad-hoc SVG icons when `<x-icon>` already supports the name.

## 10. Source of truth

| Concern | File |
| --- | --- |
| Tailwind tokens | `tailwind.config.js` |
| CSS variables and component classes | `resources/css/app.css` |
| Tenant shell | `resources/views/layouts/app.blade.php` |
| Guest shell | `resources/views/layouts/guest.blade.php` |
| Tenant sidebar/topbar | `resources/views/components/app-sidebar.blade.php`, `resources/views/components/app-header.blade.php` |
| Admin shell | `resources/views/components/admin-layout.blade.php`, `resources/views/components/admin-sidebar.blade.php`, `resources/views/components/admin-header.blade.php` |
| Dashboard | `resources/views/dashboard.blade.php`, `app/Http/Controllers/DashboardController.php` |
| Profile | `resources/views/profile/edit.blade.php`, `resources/views/admin/profile.blade.php`, `app/Http/Controllers/ProfileController.php` |
| Navigation regression coverage | `tests/Feature/NavigationAccessibilityTest.php` |
| Dashboard regression coverage | `tests/Feature/DashboardActivityTest.php` |

## 11. Pre-delivery checklist

1. Confirm the correct shell: guest, tenant app, or central admin.
2. Use semantic tokens and shared classes before adding utilities.
3. Gate links and data by tenant and permission.
4. Check active states in both sidebar and topbar/dropdown where applicable.
5. Verify mobile drawer, collapsed rail, focus rings, and table overflow.
6. Run `npm run build`, the narrow Pest tests, `vendor/bin/pint --dirty --format agent`, and `php artisan test --compact` for broader changes.
7. Check request lenses and browser events after browser-facing changes.
