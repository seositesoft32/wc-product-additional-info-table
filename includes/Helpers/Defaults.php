<?php

namespace WCPAIT\Helpers;

class Defaults
{
    /**
     * @return array<string, mixed>
     */
    public static function settings(): array
    {
        return [
            'fields' => [
                [
                    'id' => 'material',
                    'label' => __('Material', 'wc-pait'),
                    'type' => 'text',
                    'prefix' => '',
                    'suffix' => '',
                ],
                [
                    'id' => 'thickness',
                    'label' => __('Thickness', 'wc-pait'),
                    'type' => 'number',
                    'prefix' => '',
                    'suffix' => __('mm', 'wc-pait'),
                ],
            ],
            'display_position' => 'after_add_to_cart',
            'show_table_header' => 'yes',
            'header_label_text' => __('Label', 'wc-pait'),
            'header_value_text' => __('Value', 'wc-pait'),
            'table_style' => 'clean_table',
            'table_background' => '#ffffff',
            'header_background' => '#f3f4f6',
            'text_color' => '#1f2937',
            'border_color' => '#d1d5db',
            'alternate_row_color' => '#f9fafb',
            'row_spacing' => 0,
            'cell_padding' => 12,
            'font_size' => 14,
            'border_radius' => 8,
            'cards_accent_color' => '#2563eb',
            'cards_badge_text_color' => '#ffffff',
            'cards_shadow_intensity' => 12,
            'grid_badge_bg' => '#7c3aed',
            'grid_badge_text' => '#ffffff',
            'grid_card_bg' => '#ffffff',
            'grid_card_border' => '#e5e7eb',
            'ribbon_bg_color' => '#0ea5e9',
            'ribbon_text_color' => '#ffffff',
            'ribbon_badge_bg' => '#0369a1',
            'ribbon_badge_text' => '#ffffff',
            'minimal_line_color' => '#cbd5e1',
            'cleanup_on_uninstall' => 'no',
        ];
    }

    /**
     * @return array<string, string>
     */
    public static function position_options(): array
    {
        return [
            'shortcode' => __('Shortcode only', 'wc-pait'),
            'after_summary' => __('After product summary', 'wc-pait'),
            'after_add_to_cart' => __('After add to cart button', 'wc-pait'),
            'before_tabs' => __('Before tabs area', 'wc-pait'),
            'after_tabs' => __('After tabs area', 'wc-pait'),
            'inside_description_tab' => __('Inside description tab', 'wc-pait'),
            'after_additional_info_tab' => __('After additional information tab', 'wc-pait'),
            'after_related_products' => __('After related products', 'wc-pait'),
        ];
    }

    /**
     * @return array<string, string>
     */
    public static function table_styles(): array
    {
        return [
            'clean_table' => __('Clean Table', 'wc-pait'),
            'cards_timeline' => __('Timeline Cards', 'wc-pait'),
            'badge_grid' => __('Badge Grid', 'wc-pait'),
            'ribbon_list' => __('Ribbon List', 'wc-pait'),
            'minimal_lines' => __('Minimal Lines', 'wc-pait'),
        ];
    }
}
