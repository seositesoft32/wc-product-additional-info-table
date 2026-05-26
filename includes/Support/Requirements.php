<?php

namespace WCPAIT\Support;

class Requirements
{
    public static function is_woocommerce_active(): bool
    {
        return class_exists('WooCommerce');
    }

    public static function render_woocommerce_notice(): void
    {
        if (!current_user_can('activate_plugins')) {
            return;
        }

        echo '<div class="notice notice-error"><p>';
        echo esc_html__('WC Product Additional Info Table requires WooCommerce to be installed and active.', 'wc-pait');
        echo '</p></div>';
    }
}
