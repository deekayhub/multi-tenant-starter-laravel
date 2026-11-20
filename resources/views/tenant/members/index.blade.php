<x-app-layout :title="__('Team Members')">
    <div class="space-y-7">
        <div class="flex flex-col gap-6 lg:flex-row lg:items-end lg:justify-between">
            <div>
                <p class="flex items-center gap-2 text-xs font-semibold uppercase tracking-[0.12em] text-muted-foreground">
                    <x-icon name="users" class="h-4 w-4" />
                    {{ __('Access control') }}
                </p>
                <h1 class="mt-3 text-4xl font-extrabold leading-tight tracking-tight text-foreground sm:text-5xl">{{ __('Team Members') }}</h1>
                <p class="mt-3 max-w-2xl text-sm leading-6 text-muted-foreground">
                    {{ __('Manage who can sign in to this workspace and the roles they hold.') }}
                </p>
            </div>

            @can('users.create')
                <a href="{{ route('tenant.members.create') }}" class="btn-primary self-start">
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
                    <h2 id="members-heading" class="text-xl font-extrabold tracking-tight text-foreground">{{ __('Workspace members') }}</h2>
                    <p class="mt-1 text-xs text-muted-foreground">{{ __('At least one member must keep the Admin role.') }}</p>
                </div>
                <span class="text-xs font-semibold text-muted-foreground">{{ trans_choice(':count member|:count members', $members->total()) }}</span>
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
                                <th scope="col" class="px-5 py-2.5 font-bold">{{ __('Member') }}</th>
                                <th scope="col" class="px-5 py-2.5 font-bold">{{ __('Email') }}</th>
                                <th scope="col" class="px-5 py-2.5 font-bold">{{ __('Roles') }}</th>
                                <th scope="col" class="px-5 py-2.5 text-right font-bold">{{ __('Actions') }}</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-line">
                            @foreach ($members as $member)
                                <tr class="transition-colors hover:bg-muted/40">
                                    <td class="px-5 py-3">
                                        <div class="flex flex-wrap items-center gap-2">
                                            <span class="text-xs font-bold text-foreground">{{ $member->name }}</span>
                                            @if ($member->is(auth()->user()))
                                                <span class="rounded-md border border-primary/20 bg-primary/10 px-2 py-0.5 text-[10px] font-bold uppercase tracking-[0.08em] text-primary">{{ __('You') }}</span>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="px-5 py-3 text-xs text-muted-foreground">{{ $member->email }}</td>
                                    <td class="px-5 py-3">
                                        <div class="flex flex-wrap gap-1.5">
                                            @forelse ($member->roles as $role)
                                                <span class="rounded-md border border-line bg-muted/60 px-2 py-0.5 text-[10px] font-bold text-foreground">
                                                    {{ $role->name }}
                                                </span>
                                            @empty
                                                <span class="text-xs text-muted-foreground">{{ __('No roles') }}</span>
                                            @endforelse
                                        </div>
                                    </td>
                                    <td class="px-5 py-3">
                                        <div class="flex flex-wrap items-center justify-end gap-2">
                                            @can('users.edit')
                                                <a href="{{ route('tenant.members.edit', $member) }}" class="btn-quiet">{{ __('Edit') }}</a>
                                            @endcan
                                            @can('users.delete')
                                                <form method="POST" action="{{ route('tenant.members.destroy', $member) }}">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button
                                                        type="submit"
                                                        class="btn-quiet text-destructive hover:text-destructive"
                                                        @if ($member->is(auth()->user())) disabled title="{{ __('You cannot delete your own account.') }}" @endif
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
</x-app-layout>
