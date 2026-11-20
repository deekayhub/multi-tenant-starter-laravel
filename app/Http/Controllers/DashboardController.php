<?php

namespace App\Http\Controllers;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Spatie\Activitylog\Models\Activity;

class DashboardController extends Controller
{
    /**
     * Display the dashboard with recent activity for the current group.
     */
    public function index(Request $request): View
    {
        $user = $request->user();

        $recentActivity = Activity::query()
            ->inLog('authentication')
            ->whereHas('causer', function (Builder $query) use ($user): void {
                $query->where('tenant_id', $user->tenant_id);
            })
            ->with('causer')
            ->latest()
            ->orderByDesc('id')
            ->limit(5)
            ->get();

        return view('dashboard', [
            'recentActivity' => $recentActivity,
        ]);
    }
}
