<?php

namespace WCPAIT\Helpers;

class Sanitizer
{
    /**
     * @param mixed $input
     *
     * @return array<string, mixed>
     */
    public static function sanitize_settings($input): array
    {
        $defaults = Defaults::settings();
        $input = is_array($input) ? $input : [];

        $settings = [];
        $settings['fields'] = self::sanitize_fields(isset($input['fields']) ? $input['fields'] : []);
        $settings['display_positions'] = self::sanitize_display_positions(isset($input['display_positions']) ? $input['display_positions'] : []);

        $settings['table_style'] = self::allowed_string(
            isset($input['table_style']) ? $input['table_style'] : $defaults['table_style'],
            array_keys(Defaults::table_styles()),
            $defaults['table_style']
        );

        $settings['table_background'] = sanitize_hex_color(isset($input['table_background']) ? $input['table_background'] : '') ?: $defaults['table_background'];
        $settings['header_background'] = sanitize_hex_color(isset($input['header_background']) ? $input['header_background'] : '') ?: $defaults['header_background'];
        $settings['text_color'] = sanitize_hex_color(isset($input['text_color']) ? $input['text_color'] : '') ?: $defaults['text_color'];
        $settings['border_color'] = sanitize_hex_color(isset($input['border_color']) ? $input['border_color'] : '') ?: $defaults['border_color'];
        $settings['alternate_row_color'] = sanitize_hex_color(isset($input['alternate_row_color']) ? $input['alternate_row_color'] : '') ?: $defaults['alternate_row_color'];

        $settings['row_spacing'] = self::int_range(isset($input['row_spacing']) ? $input['row_spacing'] : $defaults['row_spacing'], 0, 40, (int) $defaults['row_spacing']);
        $settings['cell_padding'] = self::int_range(isset($input['cell_padding']) ? $input['cell_padding'] : $defaults['cell_padding'], 4, 40, (int) $defaults['cell_padding']);
        $settings['font_size'] = self::int_range(isset($input['font_size']) ? $input['font_size'] : $defaults['font_size'], 10, 24, (int) $defaults['font_size']);
        $settings['border_radius'] = self::int_range(isset($input['border_radius']) ? $input['border_radius'] : $defaults['border_radius'], 0, 30, (int) $defaults['border_radius']);

        $settings['cleanup_on_uninstall'] = (!empty($input['cleanup_on_uninstall']) && 'yes' === $input['cleanup_on_uninstall']) ? 'yes' : 'no';

        return $settings;
    }

    /**
     * @param mixed $fields
     * @return array<int, array<string, string>>
     */
    public static function sanitize_fields($fields): array
    {
        if (!is_array($fields)) {
            return [];
        }

        $sanitized = [];

        foreach ($fields as $field) {
            if (!is_array($field)) {
                continue;
            }

            $label = sanitize_text_field(isset($field['label']) ? $field['label'] : '');
            if ('' === $label) {
                continue;
            }

            $id_raw = isset($field['id']) ? sanitize_title((string) $field['id']) : '';
            $id = '' !== $id_raw ? $id_raw : sanitize_title($label);

            $type = self::allowed_string(isset($field['type']) ? $field['type'] : 'text', ['text', 'number'], 'text');

            $sanitized[] = [
                'id' => $id,
                'label' => $label,
                'type' => $type,
                'prefix' => sanitize_text_field(isset($field['prefix']) ? $field['prefix'] : ''),
                'suffix' => sanitize_text_field(isset($field['suffix']) ? $field['suffix'] : ''),
            ];
        }

        return array_values($sanitized);
    }

    /**
     * @param mixed $positions
     * @return array<int, string>
     */
    private static function sanitize_display_positions($positions): array
    {
        $allowed = array_keys(Defaults::position_options());
        $positions = is_array($positions) ? $positions : [];

        $sanitized = [];
        foreach ($positions as $position) {
            $value = sanitize_key((string) $position);
            if (in_array($value, $allowed, true)) {
                $sanitized[] = $value;
            }
        }

        if (empty($sanitized)) {
            $sanitized[] = 'shortcode';
        }

        return array_values(array_unique($sanitized));
    }

    /**
     * @param mixed $value
     * @param array<int, string> $allowed
     */
    private static function allowed_string($value, array $allowed, string $fallback): string
    {
        $value = sanitize_key((string) $value);

        return in_array($value, $allowed, true) ? $value : $fallback;
    }

    /**
     * @param mixed $value
     */
    private static function int_range($value, int $min, int $max, int $fallback): int
    {
        $value = absint($value);
        if ($value < $min || $value > $max) {
            return $fallback;
        }

        return $value;
    }

    /**
     * @param mixed $value
     * @param string $type
     */
    public static function sanitize_product_value($value, string $type): string
    {
        if ('number' === $type) {
            $raw = is_scalar($value) ? wc_format_decimal((string) $value, wc_get_price_decimals()) : '';

            return (string) $raw;
        }

        return sanitize_text_field((string) $value);
    }
}
