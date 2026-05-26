<?php

namespace WCPAIT\Frontend;

class Assets
{
    public function register_hooks(): void
    {
        add_action('wp_enqueue_scripts', [$this, 'enqueue']);
    }

    public function enqueue(): void
    {
        if (!is_product() && !is_singular()) {
            return;
        }

        wp_enqueue_style(
            'wcpait-frontend',
            WCPAIT_URL . 'assets/css/frontend.css',
            [],
            WCPAIT_VERSION
        );

        wp_enqueue_script(
            'wcpait-frontend',
            WCPAIT_URL . 'assets/js/frontend.js',
            ['jquery'],
            WCPAIT_VERSION,
            true
        );
    }
}
