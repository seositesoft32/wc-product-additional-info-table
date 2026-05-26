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
            'table_style' => 'minimal',
            'table_background' => '#ffffff',
            'header_background' => '#f3f4f6',
            'text_color' => '#1f2937',
            'border_color' => '#d1d5db',
            'alternate_row_color' => '#f9fafb',
            'row_spacing' => 0,
            'cell_padding' => 12,
            'font_size' => 14,
            'border_radius' => 8,
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
            'minimal' => __('Minimal', 'wc-pait'),
            'modern' => __('Modern', 'wc-pait'),
            'classic' => __('Classic', 'wc-pait'),
            'dark' => __('Dark', 'wc-pait'),
            'compact' => __('Compact', 'wc-pait'),
        ];
    }
}
