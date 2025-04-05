<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>見本用ページ</title>
</head>
<h1>利用者入口（見本用）</h1>
<body>
    <h2>Laravel + Livewire + MySQL</h2>
    <div>
        <ul>
            @foreach (App\Enums\Models\User\Role::cases() as $role)
                <li><a href="{{ $role->loginPagePath() }}">{{ $role->ja_JP() }}はこちらから</a></li>
            @endforeach
        </ul>
    </div>
</body>
</html>
