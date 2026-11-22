<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Tenant;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Show the central SaaS overview.
     */
    public function index(): View
    {
        $kpis = [
            'total' => Tenant::query()->count(),
            'active' => Tenant::query()->active()->count(),
            'suspended' => Tenant::query()->suspended()->count(),
            'new_this_month' => Tenant::query()
                ->where('created_at', '>=', now()->startOfMonth())
                ->count(),
        ];

        $recentTenants = Tenant::query()
            ->with('domains')
            ->orderByDesc('created_at')
            ->orderByDesc('id')
            ->limit(6)
            ->get();

        return view('admin.dashboard', compact('kpis', 'recentTenants'));
    }
}
