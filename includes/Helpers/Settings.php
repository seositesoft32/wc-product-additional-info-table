<?php

namespace WCPAIT\Helpers;

class Settings
{
    /**
     * @return array<string, mixed>
     */
    public static function get(): array
    {
        $saved = get_option('wcpait_settings', []);
        if (!is_array($saved)) {
            $saved = [];
        }

        return wp_parse_args($saved, Defaults::settings());
    }

    /**
     * @param array<string, mixed> $value
     */
    public static function update(array $value): void
    {
        update_option('wcpait_settings', $value);
    }
}
