<header>
    <h1>{{ $title }}</h1>

    @if (session('msg'))
        {{ session('msg') }}
    @endif

    @isset($cur_user)
        @if ($cur_user->role === App\Enums\Models\User\Role::Admin)
            <x-layouts.admin-menu />
        @endif
    @endisset
</header>