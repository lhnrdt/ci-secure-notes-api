<?php

namespace App\Controllers;

class Home extends BaseController
{
    public function index(): string
    {
        // always return the svelte-entrypoint view
        return view('svelte');
    }
}
