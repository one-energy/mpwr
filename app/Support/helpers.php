<?php

use App\Models\User;
use App\Support\Alert;
use Illuminate\Support\Carbon;

if (!function_exists('alert')) {
    function alert() : Alert
    {
        return new Alert();
    }
}

if (!function_exists('user')) {
    function user(): ?User
    {
        if (!auth()->check()) {
            return null;
        }

        /** @var User $user */
        $user = auth()->user();

        return $user;
    }
}

if (!function_exists('carbon')) {
    function carbon(...$args): Carbon
    {
        return new Carbon(...$args);
    }
}

if (!function_exists('mask')) {
    function mask($str, $first, $last)
    {
        $len    = strlen($str);
        $toShow = $first + $last;

        return substr($str, 0, $len <= $toShow ? 0 : $first) . str_repeat("*", $len - ($len <= $toShow ? 0 : $toShow)) . substr($str, $len - $last, $len <= $toShow ? 0 : $last);
    }
}

if (!function_exists('mask_email')) {
    function mask_email($email)
    {
        $mailParts   = explode("@", $email);
        $domainParts = explode('.', $mailParts[1]);

        $mailParts[0]   = mask($mailParts[0], 2, 2);
        $domainParts[0] = mask($domainParts[0], 2, 1);
        $mailParts[1]   = implode('.', $domainParts);

        return implode("@", $mailParts);
    }
}

if (!function_exists('is_active')) {
    function is_active($pattern)
    {
        return request()->routeIs($pattern);
    }
}
