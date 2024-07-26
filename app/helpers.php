<?php

use Illuminate\Support\Facades\Auth;

if (! function_exists('isSuperAdmin')) {
    function isSuperAdmin()
    {
        return Auth::check() && Auth::user()->isSuperAdmin();
    }
}
