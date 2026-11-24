<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\Scopes\TenantRoleScope;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Spatie\Activitylog\Models\Activity;

class DashboardController extends Controller
{
    /**
     * Display the dashboard with metrics and recent activity for the current group.
     */
    public function index(Request $request): View
    {
        $user = $request->user();
        $tenant = tenant();
        $tenantKey = $user->tenant_id;
        $today = now();

        $tenantCauser = function (Builder $query) use ($tenantKey): void {
            $query->where('tenant_id', $tenantKey);
        };

        $memberStats = User::query()
            ->where('tenant_id', $tenantKey)
            ->toBase()
            ->selectRaw('COUNT(*) as total')
            ->selectRaw('COALESCE(SUM(email_verified_at IS NOT NULL), 0) as verified')
            ->first();

        $roleCount = Role::withoutGlobalScope(TenantRoleScope::class)
            ->where('tenant_id', $tenantKey)
            ->count();
        $domainCount = $tenant?->domains()->count() ?? 0;

        $loginActivity = Activity::query()
            ->inLog('authentication')
            ->where('event', 'login')
            ->whereHas('causer', $tenantCauser);

        $signIns30Days = (clone $loginActivity)
            ->where('created_at', '>=', $today->copy()->subDays(30))
            ->count();

        $dailyLogins = (clone $loginActivity)
            ->where('created_at', '>=', $today->copy()->subDays(6)->startOfDay())
            ->toBase()
            ->selectRaw('DATE(created_at) as day, COUNT(*) as total')
            ->groupBy('day')
            ->orderBy('day')
            ->pluck('total', 'day');

        $loginTrend = collect(range(6, 0))
            ->mapWithKeys(function (int $daysAgo) use ($today, $dailyLogins): array {
                $date = $today->copy()->subDays($daysAgo)->toDateString();

                return [$date => (int) ($dailyLogins[$date] ?? 0)];
            });

        $teamMembers = User::query()
            ->where('tenant_id', $tenantKey)
            ->with('roles:id,name')
            ->latest()
            ->limit(5)
            ->get(['id', 'name', 'email', 'email_verified_at', 'created_at']);

        $recentActivity = Activity::query()
            ->inLog('authentication')
            ->whereHas('causer', $tenantCauser)
            ->with('causer')
            ->latest()
            ->orderByDesc('id')
            ->limit(5)
            ->get();

        return view('dashboard', [
            'recentActivity' => $recentActivity,
            'memberStats' => $memberStats,
            'roleCount' => $roleCount,
            'domainCount' => $domainCount,
            'signIns30Days' => $signIns30Days,
            'loginTrend' => $loginTrend,
            'chartMax' => max(1, (int) $loginTrend->max()),
            'teamMembers' => $teamMembers,
        ]);
    }
}
