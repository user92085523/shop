<?php

namespace App\View\Composers;

use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class CurrentUserComposer
{
    public function compose(View $view)
    {
        $view->with('cur_user', Auth::user());
    }
}