<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateWorkspaceRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class WorkspaceController extends Controller
{
    /**
     * Display the current workspace details.
     */
    public function edit(): View
    {
        $tenant = tenant();

        return view('tenant.workspace.edit', [
            'tenant' => $tenant,
            'subdomain' => $tenant->subdomain(),
            'customDomain' => $tenant->customDomain(),
        ]);
    }

    /**
     * Update the current workspace details and custom domain.
     */
    public function update(UpdateWorkspaceRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        $tenant = tenant();

        $tenant->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
        ]);

        $currentCustomDomain = $tenant->customDomain();

        if ($currentCustomDomain !== null) {
            $tenant->domains()->where('domain', $currentCustomDomain)->delete();
        }

        if (($validated['custom_domain'] ?? null) !== null) {
            $tenant->domains()->create(['domain' => $validated['custom_domain']]);
        }

        $tenant->unsetRelation('domains');

        return redirect()->route('settings.workspace.edit')
            ->with('status', __('Workspace updated.'));
    }
}
