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
        add_action('wp_ajax_wcpait_save_settings', [$this, 'ajax_save_settings']);
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

    public function ajax_save_settings(): void
    {
        if (!current_user_can('manage_woocommerce')) {
            wp_send_json_error(
                [
                    'message' => __('You are not allowed to save these settings.', 'wc-pait'),
                ],
                403
            );
        }

        check_ajax_referer('wcpait_save_settings_nonce', 'nonce');

        $form_data_raw = isset($_POST['form_data']) ? wp_unslash($_POST['form_data']) : '';
        if (empty($form_data_raw) || !is_string($form_data_raw)) {
            wp_send_json_error(
                [
                    'message' => __('No settings data received.', 'wc-pait'),
                ],
                400
            );
        }

        $parsed = [];
        parse_str($form_data_raw, $parsed);

        $settings = isset($parsed['wcpait_settings']) && is_array($parsed['wcpait_settings'])
            ? $parsed['wcpait_settings']
            : [];

        $sanitized = Sanitizer::sanitize_settings($settings);
        Settings::update($sanitized);

        wp_send_json_success(
            [
                'message' => __('Settings saved successfully.', 'wc-pait'),
                'settings' => $sanitized,
            ]
        );
    }
}
