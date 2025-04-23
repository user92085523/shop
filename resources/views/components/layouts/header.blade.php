<header>
    <h1>{{ $title }}</h1>

    @if (session('msg'))
        {{ session('msg') }}
    @endif

    @isset($cur_user)
        @livewire('header-menu')
        <br>
    @endisset
</header>