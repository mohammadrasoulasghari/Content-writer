<?php

use App\Models\Prompt;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

if (! function_exists('isSuperAdmin')) {
    function isSuperAdmin()
    {
        return Auth::check() && Auth::user()->isSuperAdmin();
    }
}
if (!function_exists('get_prompt')) {
    function get_prompt(string $title): ?string
    {
        $prompt = Prompt::query()
             ->where('title', $title)->first();

        return $prompt ? $prompt->prompt : throw new \Nette\InvalidArgumentException("Prompt not found");
    }
}

if (!function_exists('replaceDelimitedPlaceholder')) {
    function replace_delimited_placeholder(string $template, string $replacement, string $placeholderKey, string $delimiter = '"'): string
    {
        $placeholder = $delimiter . $placeholderKey . $delimiter;
        return Str::replace($placeholder, $replacement, $template);
    }
}
