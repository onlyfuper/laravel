<?php

if (! function_exists('setting')) {
    /**
     * Get or set a setting value.
     *
     * Supports dot notation: setting('mail.from_name')
     * Groups: setting('mail') returns all mail.* settings as array
     */
    function setting(string $key, mixed $default = null): mixed
    {
        return \App\Models\Setting::get($key, $default);
    }
}

if (! function_exists('settings')) {
    /**
     * Get all settings in a group as key => value array.
     * settings('mail') → ['from_name' => '...', 'from_address' => '...']
     */
    function settings(string $group): array
    {
        return \App\Models\Setting::group($group);
    }
}
