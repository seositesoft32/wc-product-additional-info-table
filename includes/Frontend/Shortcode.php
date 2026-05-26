<?php

namespace WCPAIT\Frontend;

class Shortcode
{
    public function register_hooks(): void
    {
        add_shortcode('product_additional_info', [$this, 'render_shortcode']);
    }

    /**
     * @param array<string, string> $atts
     */
    public function render_shortcode(array $atts = []): string
    {
        $atts = shortcode_atts(
            [
                'product_id' => '0',
                'style' => '',
            ],
            $atts,
            'product_additional_info'
        );

        $product_id = absint($atts['product_id']);
        if (0 === $product_id) {
            $product_id = (int) get_the_ID();
        }

        if ($product_id <= 0) {
            return '';
        }

        return Renderer::render($product_id, sanitize_key((string) $atts['style']));
    }
}
