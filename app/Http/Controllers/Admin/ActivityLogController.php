<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\View\View;
use Spatie\Activitylog\Models\Activity;

class ActivityLogController extends Controller
{
    /**
     * Show central super-admin actions.
     */
    public function index(): View
    {
        $activities = Activity::query()
            ->inLog('admin')
            ->with('causer')
            ->orderByDesc('created_at')
            ->orderByDesc('id')
            ->paginate(20);

        return view('admin.activity-logs', compact('activities'));
    }
}
