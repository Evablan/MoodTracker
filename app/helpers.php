<?php
if (!function_exists('setting')) {
    function setting(string $key, $default = null)
    {
        return \App\Models\Setting::where('key', $key)->value('value') ?? $default;
    }
}
