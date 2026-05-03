<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Setting extends Model
{
    protected $fillable = ['key', 'value', 'group', 'type'];
    public $timestamps = false;

    /**
     * Get a setting value. Supports dot notation: get('mail.from_name')
     */
    public static function get(string $key, mixed $default = null): mixed
    {
        // Dot notation: group.key
        if (str_contains($key, '.')) {
            [$group, $subkey] = explode('.', $key, 2);
            $stored = static::group($group);
            return $stored[$subkey] ?? config("settings.{$key}", $default);
        }

        return Cache::rememberForever("settings.{$key}", function () use ($key, $default) {
            $setting = static::where('key', $key)->whereNull('group')->first()
                ?? static::where('key', $key)->first();
            return $setting ? static::cast($setting->value, $setting->type ?? 'string') : config("settings.{$key}", $default);
        });
    }

    /**
     * Get all settings in a group as ['key' => value] array.
     */
    public static function group(string $group): array
    {
        return Cache::rememberForever("settings.group.{$group}", function () use ($group) {
            return static::where('group', $group)
                ->get()
                ->mapWithKeys(fn ($s) => [$s->key => static::cast($s->value, $s->type ?? 'string')])
                ->toArray();
        });
    }

    /**
     * Set a setting value. Clears cache.
     */
    public static function set(string $key, mixed $value, ?string $group = null, string $type = 'string'): void
    {
        $data = ['value' => $value, 'type' => $type];
        if ($group) $data['group'] = $group;

        static::updateOrCreate(
            array_filter(['key' => $key, 'group' => $group]),
            $data
        );

        Cache::forget("settings.{$key}");
        if ($group) Cache::forget("settings.group.{$group}");
    }

    /**
     * Bulk set from an array. Useful in settings form.
     */
    public static function setMany(array $values, ?string $group = null): void
    {
        foreach ($values as $key => $value) {
            static::set($key, $value, $group);
        }
    }

    private static function cast(mixed $value, string $type): mixed
    {
        return match ($type) {
            'boolean', 'bool' => (bool) $value,
            'integer', 'int'  => (int) $value,
            'array', 'json'   => json_decode($value, true),
            default           => $value,
        };
    }
}
