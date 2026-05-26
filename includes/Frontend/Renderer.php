<?php

namespace WCPAIT\Frontend;

use WCPAIT\Helpers\Defaults;
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
        $legacy_map = [
            'minimal' => 'clean_table',
            'modern' => 'cards_timeline',
            'classic' => 'badge_grid',
            'dark' => 'ribbon_list',
            'compact' => 'minimal_lines',
        ];
        if (isset($legacy_map[$style])) {
            $style = $legacy_map[$style];
        }
        $allowed_styles = array_keys(Defaults::table_styles());
        if (!in_array($style, $allowed_styles, true)) {
            $style = 'clean_table';
        }

        $wrapper_styles = sprintf(
            '--wcpait-table-bg:%s;--wcpait-header-bg:%s;--wcpait-text:%s;--wcpait-border:%s;--wcpait-alt-row:%s;--wcpait-row-spacing:%dpx;--wcpait-cell-padding:%dpx;--wcpait-font-size:%dpx;--wcpait-radius:%dpx;--wcpait-cards-accent:%s;--wcpait-cards-badge-text:%s;--wcpait-cards-shadow:%dpx;--wcpait-grid-badge-bg:%s;--wcpait-grid-badge-text:%s;--wcpait-grid-card-bg:%s;--wcpait-grid-card-border:%s;--wcpait-ribbon-bg:%s;--wcpait-ribbon-text:%s;--wcpait-ribbon-badge-bg:%s;--wcpait-ribbon-badge-text:%s;--wcpait-minimal-line:%s;',
            esc_attr((string) $settings['table_background']),
            esc_attr((string) $settings['header_background']),
            esc_attr((string) $settings['text_color']),
            esc_attr((string) $settings['border_color']),
            esc_attr((string) $settings['alternate_row_color']),
            absint($settings['row_spacing']),
            absint($settings['cell_padding']),
            absint($settings['font_size']),
            absint($settings['border_radius']),
            esc_attr((string) $settings['cards_accent_color']),
            esc_attr((string) $settings['cards_badge_text_color']),
            absint($settings['cards_shadow_intensity']),
            esc_attr((string) $settings['grid_badge_bg']),
            esc_attr((string) $settings['grid_badge_text']),
            esc_attr((string) $settings['grid_card_bg']),
            esc_attr((string) $settings['grid_card_border']),
            esc_attr((string) $settings['ribbon_bg_color']),
            esc_attr((string) $settings['ribbon_text_color']),
            esc_attr((string) $settings['ribbon_badge_bg']),
            esc_attr((string) $settings['ribbon_badge_text']),
            esc_attr((string) $settings['minimal_line_color'])
        );

        $show_table_header = isset($settings['show_table_header']) && 'yes' === (string) $settings['show_table_header'];
        $header_label_text = isset($settings['header_label_text']) ? (string) $settings['header_label_text'] : __('Label', 'wc-pait');
        $header_value_text = isset($settings['header_value_text']) ? (string) $settings['header_value_text'] : __('Value', 'wc-pait');

        $template_map = [
            'clean_table' => 'product-table.php',
            'minimal_lines' => 'product-table.php',
            'cards_timeline' => 'product-cards.php',
            'badge_grid' => 'product-grid.php',
            'ribbon_list' => 'product-ribbon.php',
        ];

        $template_file = isset($template_map[$style]) ? $template_map[$style] : 'product-table.php';

        ob_start();
        include WCPAIT_PATH . 'templates/frontend/' . $template_file;

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
