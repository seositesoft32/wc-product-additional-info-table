<?php

namespace WCPAIT\Frontend;

use WCPAIT\Helpers\Settings;

class Renderer
{
    /**
     * @param int $product_id
     * @param string $style
     */
    public static function render(int $product_id, string $style = ''): string
    {
        $product = wc_get_product($product_id);
        if (!$product instanceof \WC_Product) {
            return '';
        }

        $settings = Settings::get();
        $fields = isset($settings['fields']) && is_array($settings['fields']) ? $settings['fields'] : [];
        if (empty($fields)) {
            return '';
        }

        $values = self::resolve_values($product);
        if (empty($values)) {
            return '';
        }

        $rows = [];

        foreach ($fields as $field) {
            if (empty($field['id']) || empty($field['label'])) {
                continue;
            }

            $field_id = (string) $field['id'];
            if (!isset($values[$field_id]) || '' === (string) $values[$field_id]) {
                continue;
            }

            $prefix = isset($field['prefix']) ? (string) $field['prefix'] : '';
            $suffix = isset($field['suffix']) ? (string) $field['suffix'] : '';
            $value = (string) $values[$field_id];

            $rows[] = [
                'label' => (string) $field['label'],
                'value' => trim($prefix . ' ' . $value . ' ' . $suffix),
            ];
        }

        if (empty($rows)) {
            return '';
        }

        $style = '' !== $style ? sanitize_key($style) : (string) $settings['table_style'];
        $allowed_styles = ['minimal', 'modern', 'classic', 'dark', 'compact'];
        if (!in_array($style, $allowed_styles, true)) {
            $style = 'minimal';
        }

        $wrapper_styles = sprintf(
            '--wcpait-table-bg:%s;--wcpait-header-bg:%s;--wcpait-text:%s;--wcpait-border:%s;--wcpait-alt-row:%s;--wcpait-row-spacing:%dpx;--wcpait-cell-padding:%dpx;--wcpait-font-size:%dpx;--wcpait-radius:%dpx;',
            esc_attr((string) $settings['table_background']),
            esc_attr((string) $settings['header_background']),
            esc_attr((string) $settings['text_color']),
            esc_attr((string) $settings['border_color']),
            esc_attr((string) $settings['alternate_row_color']),
            absint($settings['row_spacing']),
            absint($settings['cell_padding']),
            absint($settings['font_size']),
            absint($settings['border_radius'])
        );

        ob_start();
        include WCPAIT_PATH . 'templates/frontend/product-table.php';

        return (string) ob_get_clean();
    }

    /**
        * @param \WC_Product $product
     * @return array<string, string>
     */
    private static function resolve_values($product): array
    {
        if ($product->is_type('variation')) {
            $value = get_post_meta($product->get_id(), '_wcpait_values', true);

            return is_array($value) ? $value : [];
        }

        if ($product->is_type('variable')) {
            $selected_variation = isset($_REQUEST['variation_id']) ? absint(wp_unslash($_REQUEST['variation_id'])) : 0;
            if ($selected_variation > 0) {
                $variation_values = get_post_meta($selected_variation, '_wcpait_values', true);
                if (is_array($variation_values) && !empty($variation_values)) {
                    return $variation_values;
                }
            }
        }

        $value = get_post_meta($product->get_id(), '_wcpait_values', true);

        return is_array($value) ? $value : [];
    }
}
