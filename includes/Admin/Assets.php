<?php

namespace WCPAIT\Admin;

class Assets
{
    public function register_hooks(): void
    {
        add_action('admin_enqueue_scripts', [$this, 'enqueue']);
    }

    public function enqueue(string $hook): void
    {
        $allowed = ['woocommerce_page_wcpait-settings', 'post.php', 'post-new.php'];
        $is_settings_page = 'woocommerce_page_wcpait-settings' === $hook;

        if (!in_array($hook, $allowed, true)) {
            return;
        }

        wp_enqueue_style(
            'wcpait-admin',
            WCPAIT_URL . 'assets/css/admin.css',
            [],
            WCPAIT_VERSION
        );

        if ($is_settings_page) {
            wp_enqueue_style(
                'wcpait-frontend',
                WCPAIT_URL . 'assets/css/frontend.css',
                [],
                WCPAIT_VERSION
            );
        }

        wp_enqueue_script(
            'wcpait-admin',
            WCPAIT_URL . 'assets/js/admin.js',
            ['jquery', 'jquery-ui-sortable'],
            WCPAIT_VERSION,
            true
        );

        wp_localize_script(
            'wcpait-admin',
            'wcpaitAdmin',
            [
                'rowTemplate' => $this->field_row_template(),
                'removeMessage' => __('Remove this row?', 'wc-pait'),
                'requiredMessage' => __('Label is required.', 'wc-pait'),
                'isSettingsPage' => $is_settings_page,
                'ajaxUrl' => admin_url('admin-ajax.php'),
                'saveNonce' => wp_create_nonce('wcpait_save_settings_nonce'),
                'savingText' => __('Saving...', 'wc-pait'),
                'saveText' => __('Save Settings', 'wc-pait'),
                'savedText' => __('Settings saved successfully.', 'wc-pait'),
                'errorText' => __('Unable to save settings. Please review fields and try again.', 'wc-pait'),
            ]
        );
    }

    private function field_row_template(): string
    {
        ob_start();
        ?>
        <tr class="wcpait-field-row">
            <td class="wcpait-sort-col"><span class="dashicons dashicons-menu"></span></td>
            <td><input type="text" name="wcpait_settings[fields][__index__][label]" value="" required /></td>
            <td>
                <select name="wcpait_settings[fields][__index__][type]">
                    <option value="text"><?php esc_html_e('Text', 'wc-pait'); ?></option>
                    <option value="number"><?php esc_html_e('Number', 'wc-pait'); ?></option>
                </select>
            </td>
            <td><input type="text" name="wcpait_settings[fields][__index__][prefix]" value="" /></td>
            <td><input type="text" name="wcpait_settings[fields][__index__][suffix]" value="" /></td>
            <td><button type="button" class="button-link-delete wcpait-remove-row"><?php esc_html_e('Remove', 'wc-pait'); ?></button></td>
        </tr>
        <?php

        return (string) ob_get_clean();
    }
}
