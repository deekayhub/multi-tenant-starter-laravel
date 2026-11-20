<?php

namespace App\Http\Controllers;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Spatie\Activitylog\Models\Activity;

class ActivityController extends Controller
{
    /**
     * Display the sign-in and sign-out history for the current tenant.
     */
    public function index(Request $request): View
    {
        $user = $request->user();

        $eventFilter = $request->string('event')->toString();
        $eventFilter = in_array($eventFilter, ['login', 'logout'], true) ? $eventFilter : 'all';

        $scope = function (Builder $query) use ($user): void {
            $query->where('tenant_id', $user->tenant_id);
        };

        $activities = Activity::query()
            ->inLog('authentication')
            ->whereHas('causer', $scope)
            ->when($eventFilter !== 'all', fn (Builder $query): Builder => $query->where('event', $eventFilter))
            ->with('causer')
            ->latest()
            ->orderByDesc('id')
            ->paginate(15)
            ->withQueryString();

        $counts = [
            'all' => Activity::query()->inLog('authentication')->whereHas('causer', $scope)->count(),
            'login' => Activity::query()->inLog('authentication')->whereHas('causer', $scope)->where('event', 'login')->count(),
            'logout' => Activity::query()->inLog('authentication')->whereHas('causer', $scope)->where('event', 'logout')->count(),
        ];

        return view('activity.index', [
            'activities' => $activities,
            'counts' => $counts,
            'eventFilter' => $eventFilter,
        ]);
    }
}
