<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ $title }}</title>
</head>
<x-layouts.header :$title />
@isset($cur_user)
    <x-forms.logout-button />
@endisset
<body>
    {{ $slot }}
</body>
</html>