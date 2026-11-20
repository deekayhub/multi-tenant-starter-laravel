<?php

declare(strict_types=1);

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Tenant\LoginController;
use App\Models\Role;
use App\Models\Tenant;
use App\Models\User;
use Database\Seeders\PermissionSeeder;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;
use Spatie\Permission\PermissionRegistrar;

class RegisteredUserController extends Controller
{
    /** @var list<string> */
    private const RESERVED_SUBDOMAINS = [
        'admin', 'api', 'app', 'assets', 'cdn', 'ftp', 'help', 'localhost', 'mail', 'static', 'support', 'www',
    ];

    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     */
    public function store(Request $request): RedirectResponse
    {
        $this->normalizeInput($request);

        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
            'tenant_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', Rule::unique('users', 'email')],
            'subdomain' => [
                'nullable',
                'string',
                'max:63',
                'regex:/^[a-z0-9](?:[a-z0-9-]{0,61}[a-z0-9])?$/',
                'not_in:'.implode(',', self::RESERVED_SUBDOMAINS),
                Rule::unique('domains', 'domain'),
            ],
            'domain' => [
                'nullable',
                'string',
                'max:255',
                'regex:/^(?:[a-z0-9](?:[a-z0-9-]{0,61}[a-z0-9])?\.)+[a-z]{2,}$/i',
                Rule::unique('domains', 'domain'),
            ],
            'password' => ['required', 'confirmed', Password::defaults()],
        ], $this->messages());

        $validator->after(function (\Illuminate\Validation\Validator $validator) use ($request): void {
            $centralDomain = (string) config('tenancy.central_domains')[0];
            $subdomain = (string) $request->input('subdomain');
            $domain = (string) $request->input('domain');

            if ($subdomain === '' && $domain === '') {
                $validator->errors()->add('subdomain', __('Choose a subdomain or enter a custom domain.'));

                return;
            }

            if ($domain !== '' && Str::endsWith($domain, '.'.$centralDomain)) {
                $validator->errors()->add('domain', __('Use the subdomain field for addresses under :domain.', ['domain' => $centralDomain]));
            }

            if ($subdomain !== '' && DB::table('domains')->where('domain', $subdomain.'.'.$centralDomain)->exists()) {
                $validator->errors()->add('subdomain', __('That subdomain is already in use.'));
            }
        });

        $validated = $validator->validate();

        [$tenant, $user] = DB::transaction(function () use ($validated): array {
            $tenant = Tenant::create([
                'name' => $validated['tenant_name'],
                'email' => $validated['email'],
            ]);

            foreach (array_filter([$validated['subdomain'] ?? null, $validated['domain'] ?? null]) as $hostname) {
                $tenant->domains()->create(['domain' => $hostname]);
            }

            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'tenant_id' => $tenant->getTenantKey(),
            ]);

            $this->assignOwnerRole($tenant, $user);

            return [$tenant, $user];
        });

        event(new Registered($user));

        Auth::login($user);
        $request->session()->regenerate();

        $host = $validated['domain'] ?: $validated['subdomain'].'.'.config('tenancy.central_domains')[0];

        return redirect(tenant_route($host, 'tenant.login', [
            'token' => LoginController::issueToken($user, $tenant),
        ]));
    }

    private function assignOwnerRole(Tenant $tenant, User $user): void
    {
        $registrar = app(PermissionRegistrar::class);
        $previousTeamId = $registrar->getPermissionsTeamId();
        $previousCacheKey = $registrar->cacheKey;

        $registrar->setPermissionsTeamId($tenant->getTenantKey());
        $registrar->initializeCache();
        $registrar->cacheKey = (string) config('permission.cache.key').'.tenant.'.$tenant->getTenantKey();

        try {
            $user->assignRole(Role::findByName(PermissionSeeder::ADMIN_ROLE));
        } finally {
            $registrar->setPermissionsTeamId($previousTeamId);
            $registrar->initializeCache();
            $registrar->cacheKey = $previousCacheKey;
        }
    }

    /**
     * @return array<string, string>
     */
    private function messages(): array
    {
        return [
            'subdomain.regex' => __('Use lowercase letters, numbers and hyphens only.'),
            'subdomain.not_in' => __('That subdomain is reserved.'),
            'subdomain.unique' => __('That subdomain is already in use.'),
            'domain.regex' => __('Enter a valid domain, for example example.com.'),
            'domain.unique' => __('That domain is already in use.'),
        ];
    }

    private function normalizeInput(Request $request): void
    {
        $subdomain = $request->string('subdomain')->toString();
        $domain = $request->string('domain')->toString();

        $request->merge([
            'subdomain' => $subdomain !== '' ? Str::lower(trim(Str::replace(' ', '-', $subdomain))) : null,
            'domain' => $domain !== '' ? $this->normalizeDomain($domain) : null,
        ]);
    }

    private function normalizeDomain(string $domain): string
    {
        $domain = Str::lower(trim($domain));
        $domain = Str::after($domain, 'https://');
        $domain = Str::after($domain, 'http://');
        $domain = Str::before($domain, '/');
        $domain = Str::before($domain, ':');

        return rtrim($domain, '.');
    }
}
