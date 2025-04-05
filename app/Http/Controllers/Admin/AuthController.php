<?php

namespace App\Http\Controllers\Admin;

use App\Enums\Models\User\Role;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showLogin()
    {
        if ($user = Auth::user()) {
            return redirect($user->role->homePath());
        }
        return view('admin.login-page');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'loginId' => 'required|alphanum|min:4|max:16',
            'password' => 'required|alphanum|min:4|max:16',
        ]);

        if (! Auth::attemptWhen($credentials, fn($user) => $user->role === Role::Admin)) {
            return to_route('admin.showLogin')->with('msg', 'ログインに失敗しました');
        }

        return to_route('admin.home')->with('msg', 'ログインに成功しました');
    }

    public function logout()
    {
        Auth::logout();

        return to_route('root')->with('msg', 'ログアウトしました');
    }
}
