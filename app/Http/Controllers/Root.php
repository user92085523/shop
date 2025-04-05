<?php

namespace App\Http\Controllers;

use App\Enums\User\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class Root extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        return view('root');
    }
}
