@php
    use App\Enums\Models\User\Role;
    use App\Enums\Models\EmployeePosition\Name;
@endphp
@props(['title' => 'ユーザーの表示'])
<x-layouts.main :$title>
    <label for="">ユーザー情報</label>
    <ul>
        <li>ユーザーID:{{ $user->id }}</li>
        <li>ログインID:{{ $user->loginId }}</li>
        <li>ロール:{{ $user->role }}</li>
    </ul>
    @if ($user->role === Role::Employee)
        <label for="">従業員情報</label>
        <ul>
            <li>ユーザーID:{{ $user->employee->user_id }}</li>
            <li>役職:{{ $user->employee->employee_position->name }}</li>
            <li>名前:{{ $user->employee->name }}</li>
            <li>ユーザーID:{{ $user->employee->user_id }}</li>
        </ul>
    @elseif ($user->role === Role::Customer)
        <label for="">顧客情報</label>
        <ul>
            <li>ユーザーID:{{ $user->customer->user_id }}</li>
            <li>名前:{{ $user->customer->name }}</li>
            <li>電話番号:{{ $user->customer->phoneNumber }}</li>
        </ul>
    @elseif ($user->role === Role::Admin)
        <label for="">管理者情報</label>
        <ul>
            <li>ユーザーID:{{ $user->admin->user_id }}</li>
            <li>名前:{{ $user->admin->name }}</li>
        </ul>
    @endif
</x-layouts.main>