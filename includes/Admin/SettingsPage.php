<?php

namespace WCPAIT\Admin;

use WCPAIT\Helpers\Defaults;
use WCPAIT\Helpers\Sanitizer;
use WCPAIT\Helpers\Settings;

class SettingsPage
{
    public function register_hooks(): void
    {
        add_action('admin_init', [$this, 'register_settings']);
        add_action('admin_menu', [$this, 'register_menu']);
        add_filter('plugin_action_links_' . WCPAIT_BASENAME, [$this, 'action_links']);
    }

    public function register_settings(): void
    {
        register_setting(
            'wcpait_settings_group',
            'wcpait_settings',
            [
                'type' => 'array',
                'sanitize_callback' => [Sanitizer::class, 'sanitize_settings'],
                'default' => Defaults::settings(),
            ]
        );
    }

    public function register_menu(): void
    {
        add_submenu_page(
            'woocommerce',
            __('Product Additional Info', 'wc-pait'),
            __('Additional Info Table', 'wc-pait'),
            'manage_woocommerce',
            'wcpait-settings',
            [$this, 'render_page']
        );
    }

    /**
     * @param array<int, string> $links
     * @return array<int, string>
     */
    public function action_links(array $links): array
    {
        array_unshift(
            $links,
            sprintf(
                '<a href="%s">%s</a>',
                esc_url(admin_url('admin.php?page=wcpait-settings')),
                esc_html__('Settings', 'wc-pait')
            )
        );

        return $links;
    }

    public function render_page(): void
    {
        if (!current_user_can('manage_woocommerce')) {
            return;
        }

        $settings = Settings::get();
        $positions = Defaults::position_options();
        $styles = Defaults::table_styles();

        include WCPAIT_PATH . 'templates/admin/settings-page.php';
    }
}
