<?php

namespace App\View\Composers;

use Illuminate\Support\Facades\Request;
use Illuminate\View\View;

class UriTreeComposer
{
    public function compose(View $view)
    {
        $view->with('uri_tree', $this->getUriTree(Request::getRequestUri()));
    }

    private function getUriTree($request_uri)
    {
        return explode('/', substr($request_uri, 1));
    }
}