# Orbit — multi-tenancy starter kit

Orbit is a Laravel starter kit for building tenant-aware SaaS applications. It provides the shared plumbing — authentication, subdomain tenancy, workspaces, roles and permissions, settings, and an activity log — while keeping the product screens intentionally generic.

This is **not** a CRM, billing system, project manager, or finished dashboard. The workspace names, metrics, and preview copy are placeholders meant to be replaced.

## What is included

- **Single-database tenancy** with `stancl/tenancy`. Tenants are identified by subdomain or custom domain; tenant-owned rows are scoped with `tenant_id`.
- **Authentication** based on Laravel Breeze: registration, login, password reset, password confirmation, and email verification.
- **Tenant onboarding**: registration creates the workspace, its subdomain, and an owner with the protected `Admin` role.
- **Permission-aware settings**: Profile, Workspace, Team Members, and Roles & Permissions under one collapsible navigation.
- **Spatie Permission teams**: roles and permissions are scoped by tenant, with a dynamic permission matrix and `Select All` controls.
- **Protected Admin safeguards**: the Admin role cannot be renamed, edited, or deleted; the last Admin cannot be removed.
- **Activity log**: sign-ins and sign-outs are recorded with `spatie/laravel-activitylog` and shown in a filterable table.
- **Placeholder UI**: compact forms, real navigation, and no fake KPI cards or unrelated product content.

## Tech stack

| Package | Version | Purpose |
| --- | --- | --- |
| PHP | 8.3+ (verified on 8.4) | Runtime |
| Laravel | 13.x | Application framework, Eloquent, routing, validation, queues |
| `stancl/tenancy` | 3.x | Domain/subdomain tenant identification and tenant scoping |
| `spatie/laravel-permission` | 8.x | Tenant-scoped roles and permissions |
| `spatie/laravel-activitylog` | 5.x | Authentication activity log |
| `laravel/breeze` | 2.x | Blade authentication scaffolding |
| Tailwind CSS | 3.x | Utility-first styling and shared component classes |
| Alpine.js | 3.x | Menus, modals, and collapsible navigation |
| Vite | 8.x | Frontend asset build |
| Pest | 5.x | Feature and unit tests |
| Laravel Pint / Boost | 1.x / 2.x | Formatting and AI-assisted development tooling |

Exact installed versions are pinned in `composer.json`, `composer.lock`, and `package.json`.

## Quick start

```bash
composer install
cp .env.example .env
php artisan key:generate
```

Configure your database in `.env`, then set `APP_URL` to the **central** domain. For local development with `saas.test`:

```dotenv
APP_URL=http://saas.test
DB_CONNECTION=mysql
DB_DATABASE=orbit
```

The central domain list is derived from `APP_URL` plus `localhost` and `127.0.0.1` in `config/tenancy.php`. Add a wildcard host entry for subdomains on your machine, for example `*.saas.test` pointing to `127.0.0.1`.

Then install and run:

```bash
php artisan migrate
npm install
npm run build
php artisan serve
```

Visit `http://saas.test/register`, create a workspace, and you will be redirected to the tenant subdomain.

## How tenancy works

- Registration requires a **subdomain**; a custom domain is not requested at signup.
- `domains` stores the short subdomain label (for example `acme`) and any custom hostnames.
- `Tenant::preferredHost()` returns the custom domain first, then the full subdomain.
- Tenant routes live in `routes/tenant.php`; central routes live in `routes/web.php` and `routes/auth.php`.
- Profile URLs are canonical tenant routes, so `route('profile.edit')` resolves to the active tenant domain.
- Logout posts to the tenant domain using a signed URL, then redirects through a central handoff so both tenant and central sessions are cleared.
- Cache, filesystem, and queue services are made tenant-aware by the package bootstrappers. Database connections are not switched: this is a single-database application.

## Settings and permissions

Permission groups are seeded by `PermissionSeeder`:

- **Workspace**: `workspace.view`, `workspace.update`
- **Roles**: `roles.view`, `roles.create`, `roles.edit`, `roles.delete`
- **Members**: `users.view`, `users.create`, `users.edit`, `users.delete`
- **Activity**: `activity-logs.view`

The settings navigation only shows links the current user can access. The `Admin` role receives every seeded permission and is protected from destructive changes.

## Custom domains

Open **Settings → Workspace** on the tenant domain to edit the workspace name, workspace email, and custom domain. Leave the custom domain field blank to remove it. The subdomain is read-only there.

Point the custom hostname to the application and add it to the tenant's `domains` records through that form. Custom domains take priority when resolving where a user lands after authentication.

## Development commands

```bash
php artisan test --compact   # run the test suite
vendor/bin/pint --dirty --format agent  # format changed PHP files
npm run build                # build CSS and JavaScript
```

## Placeholder note

The dashboard, landing page, and sidebar are starter screens. Their labels and values demonstrate where product content belongs; they are not backed by domain-specific data. Replace them with your own views and keep only the infrastructure your product needs.
