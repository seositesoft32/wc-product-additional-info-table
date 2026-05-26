<?php

namespace WCPAIT\Frontend;

use WCPAIT\Helpers\Settings;

class Hooks
{
    public function register_hooks(): void
    {
        add_filter('the_content', [$this, 'inject_into_description_tab']);

        $settings = Settings::get();
        $positions = isset($settings['display_positions']) && is_array($settings['display_positions'])
            ? $settings['display_positions']
            : [];

        $map = [
            'after_summary' => ['woocommerce_single_product_summary', 40],
            'after_add_to_cart' => ['woocommerce_after_add_to_cart_form', 20],
            'before_tabs' => ['woocommerce_before_single_product_summary', 35],
            'after_tabs' => ['woocommerce_after_single_product_summary', 15],
            'after_additional_info_tab' => ['woocommerce_product_additional_information', 30],
            'after_related_products' => ['woocommerce_after_single_product_summary', 25],
        ];

        foreach ($positions as $position) {
            if (!isset($map[$position])) {
                continue;
            }

            add_action($map[$position][0], [$this, 'output_table'], (int) $map[$position][1]);
        }
    }

    public function output_table(): void
    {
        if (!is_product()) {
            return;
        }

        $product_id = get_the_ID();
        if (!$product_id) {
            return;
        }

        echo wp_kses_post(Renderer::render((int) $product_id));
    }

    public function inject_into_description_tab(string $content): string
    {
        if (!is_product()) {
            return $content;
        }

        if (!is_main_query() || !in_the_loop()) {
            return $content;
        }

        $settings = Settings::get();
        $positions = isset($settings['display_positions']) && is_array($settings['display_positions'])
            ? $settings['display_positions']
            : [];

        if (!in_array('inside_description_tab', $positions, true)) {
            return $content;
        }

        $table = Renderer::render((int) get_the_ID());
        if ('' === $table) {
            return $content;
        }

        return $content . $table;
    }
}
