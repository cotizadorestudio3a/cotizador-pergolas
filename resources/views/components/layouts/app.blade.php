@auth
    @if(auth()->user()->hasRole('admin'))
        <x-layouts.app.sidebar.admin-sidebar :title="$title ?? null">
            <flux:main>
                {{ $slot }}
            </flux:main>
        </x-layouts.app.sidebar.admin-sidebar>
    @else
        <x-layouts.app.sidebar.vendedor-sidebar :title="$title ?? null">
            <flux:main>
                {{ $slot }}
            </flux:main>
        </x-layouts.app.sidebar.vendedor-sidebar>
    @endif
@endauth
