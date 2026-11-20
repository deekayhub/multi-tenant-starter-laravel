<x-settings-layout
    :title="__('Team Members')"
    :description="__('Manage who can sign in to this workspace and the roles they hold.')"
>
    <div class="space-y-5">
        <div class="flex flex-wrap items-center justify-between gap-4">
            <div class="flex items-center gap-2 text-xs font-semibold text-muted-foreground">
                <x-icon name="users" class="h-4 w-4" />
                <span>{{ trans_choice(':count member|:count members', $members->total()) }}</span>
            </div>

            @can('users.create')
                <a href="{{ route('settings.members.create') }}" class="btn-primary">
                    <x-icon name="plus" class="h-4 w-4" />
                    {{ __('Add member') }}
                </a>
            @endcan
        </div>

        @if (session('status'))
            <div role="status" class="rounded-xl border border-success/25 bg-success/10 px-4 py-3 text-sm font-semibold text-success">
                {{ session('status') }}
            </div>
        @endif

        <section class="glass-card overflow-hidden" aria-labelledby="members-heading">
            <div class="flex flex-col gap-2 border-b border-line px-5 py-5 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h3 id="members-heading" class="text-xl font-extrabold tracking-tight text-foreground">{{ __('Workspace members') }}</h3>
                    <p class="mt-1 text-xs text-muted-foreground">{{ __('At least one member must keep the Admin role.') }}</p>
                </div>
                <span class="text-xs font-semibold text-muted-foreground">{{ __('Showing page :page', ['page' => $members->currentPage()]) }}</span>
            </div>

            @if ($members->isEmpty())
                <div class="flex flex-col items-center gap-3 px-5 py-16 text-center">
                    <span class="flex h-14 w-14 items-center justify-center rounded-2xl bg-primary/10 text-primary">
                        <x-icon name="users" class="h-7 w-7" />
                    </span>
                    <p class="text-sm font-bold text-foreground">{{ __('No members yet') }}</p>
                    <p class="max-w-sm text-xs leading-5 text-muted-foreground">{{ __('Add a member to give someone access to this workspace.') }}</p>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="min-w-full text-left text-sm">
                        <caption class="sr-only">{{ __('Workspace members') }}</caption>
                        <thead>
                            <tr class="text-[10px] font-bold uppercase tracking-[0.12em] text-muted-foreground">
                                <th scope="col" class="px-5 py-3 font-bold">{{ __('Member') }}</th>
                                <th scope="col" class="px-5 py-3 font-bold">{{ __('Email') }}</th>
                                <th scope="col" class="px-5 py-3 font-bold">{{ __('Role') }}</th>
                                <th scope="col" class="px-5 py-3 font-bold">{{ __('Joined') }}</th>
                                <th scope="col" class="px-5 py-3 text-right font-bold">{{ __('Actions') }}</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-line">
                            @foreach ($members as $member)
                                @php
                                    $initials = \Illuminate\Support\Str::of($member->name)
                                        ->explode(' ')
                                        ->filter()
                                        ->take(2)
                                        ->map(fn (string $part): string => \Illuminate\Support\Str::upper(\Illuminate\Support\Str::substr($part, 0, 1)))
                                        ->implode('');
                                    $isCurrentMember = $member->is(auth()->user());
                                    $isLastAdmin = $member->hasRole(\Database\Seeders\PermissionSeeder::ADMIN_ROLE) && $adminCount <= 1;
                                    $removalTitle = match (true) {
                                        $isCurrentMember && $isLastAdmin => __('You cannot delete your own account.').' '.__('The workspace must keep at least one Admin.'),
                                        $isCurrentMember => __('You cannot delete your own account.'),
                                        $isLastAdmin => __('The workspace must keep at least one Admin.'),
                                        default => null,
                                    };
                                @endphp
                                <tr class="transition-colors hover:bg-muted/40">
                                    <td class="px-5 py-3">
                                        <div class="flex items-center gap-3">
                                            <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-primary/10 text-xs font-extrabold text-primary" aria-hidden="true">
                                                {{ $initials }}
                                            </span>
                                            <div class="min-w-0">
                                                <div class="flex flex-wrap items-center gap-2">
                                                    <span class="text-sm font-bold text-foreground">{{ $member->name }}</span>
                                                    @if ($isCurrentMember)
                                                        <span class="rounded-md border border-primary/20 bg-primary/10 px-2 py-0.5 text-[10px] font-bold uppercase tracking-[0.08em] text-primary">{{ __('You') }}</span>
                                                    @endif
                                                </div>
                                                <span class="text-[11px] text-muted-foreground">{{ __('Team member') }}</span>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-5 py-3 text-xs text-muted-foreground">{{ $member->email }}</td>
                                    <td class="px-5 py-3">
                                        <div class="flex flex-wrap gap-1.5">
                                            @forelse ($member->roles as $role)
                                                <span @class([
                                                    'rounded-md border px-2 py-0.5 text-[10px] font-bold uppercase tracking-[0.06em]',
                                                    'border-warning/25 bg-warning/10 text-caution' => $role->name === \Database\Seeders\PermissionSeeder::ADMIN_ROLE,
                                                    'border-line bg-muted/60 text-foreground' => $role->name !== \Database\Seeders\PermissionSeeder::ADMIN_ROLE,
                                                ])>
                                                    {{ $role->name }}
                                                </span>
                                            @empty
                                                <span class="text-xs text-muted-foreground">{{ __('No role') }}</span>
                                            @endforelse
                                        </div>
                                    </td>
                                    <td class="px-5 py-3">
                                        @if ($member->created_at)
                                            <time datetime="{{ $member->created_at->toIso8601String() }}" class="text-xs text-muted-foreground">
                                                {{ $member->created_at->format('M j, Y') }}
                                            </time>
                                        @else
                                            <span class="text-xs text-muted-foreground">&mdash;</span>
                                        @endif
                                    </td>
                                    <td class="px-5 py-3">
                                        <div class="flex flex-wrap items-center justify-end gap-2">
                                            @can('users.edit')
                                                <a href="{{ route('settings.members.edit', $member) }}" class="btn-quiet">{{ __('Edit') }}</a>
                                            @endcan
                                            @can('users.delete')
                                                <form method="POST" action="{{ route('settings.members.destroy', $member) }}">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button
                                                        type="submit"
                                                        class="btn-quiet text-destructive hover:text-destructive"
                                                        @if ($removalTitle) disabled title="{{ $removalTitle }}" @endif
                                                    >
                                                        {{ __('Remove') }}
                                                    </button>
                                                </form>
                                            @endcan
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="border-t border-line px-5 py-4">
                    {{ $members->links() }}
                </div>
            @endif
        </section>
    </div>
</x-settings-layout>
