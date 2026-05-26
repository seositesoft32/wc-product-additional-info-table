<?php

namespace WCPAIT\Admin;

use WCPAIT\Helpers\Sanitizer;
use WCPAIT\Helpers\Settings;

class ProductTab
{
    public function register_hooks(): void
    {
        add_filter('woocommerce_product_data_tabs', [$this, 'add_product_tab']);
        add_action('woocommerce_product_data_panels', [$this, 'render_panel']);
        add_action('woocommerce_process_product_meta', [$this, 'save_simple_product']);

        add_action('woocommerce_product_after_variable_attributes', [$this, 'render_variation_fields'], 10, 3);
        add_action('woocommerce_save_product_variation', [$this, 'save_variation_fields'], 10, 2);
    }

    /**
     * @param array<string, array<string, mixed>> $tabs
     * @return array<string, array<string, mixed>>
     */
    public function add_product_tab(array $tabs): array
    {
        $tabs['wcpait_additional_info'] = [
            'label' => __('Additional Information', 'wc-pait'),
            'target' => 'wcpait_additional_info_data',
            'class' => [],
            'priority' => 85,
        ];

        return $tabs;
    }

    public function render_panel(): void
    {
        global $post;

        if (!$post || 'product' !== $post->post_type) {
            return;
        }

        $settings = Settings::get();
        $fields = isset($settings['fields']) && is_array($settings['fields']) ? $settings['fields'] : [];
        $saved = get_post_meta((int) $post->ID, '_wcpait_values', true);
        $saved = is_array($saved) ? $saved : [];

        include WCPAIT_PATH . 'templates/admin/product-tab.php';
    }

    public function save_simple_product(int $product_id): void
    {
        if (!current_user_can('edit_post', $product_id)) {
            return;
        }

        $nonce = isset($_POST['wcpait_product_nonce']) ? sanitize_text_field(wp_unslash($_POST['wcpait_product_nonce'])) : '';
        if (!wp_verify_nonce($nonce, 'wcpait_product_nonce_action')) {
            return;
        }

        $values_raw = isset($_POST['wcpait_values']) ? wp_unslash($_POST['wcpait_values']) : [];
        $settings = Settings::get();
        $fields = isset($settings['fields']) && is_array($settings['fields']) ? $settings['fields'] : [];

        $payload = $this->sanitize_values($fields, $values_raw);

        if (empty($payload)) {
            delete_post_meta($product_id, '_wcpait_values');

            return;
        }

        update_post_meta($product_id, '_wcpait_values', $payload);
    }

    /**
     * @param int $loop
     * @param array<string, mixed> $variation_data
     * @param WP_Post $variation
     */
    public function render_variation_fields(int $loop, array $variation_data, $variation): void
    {
        $settings = Settings::get();
        $fields = isset($settings['fields']) && is_array($settings['fields']) ? $settings['fields'] : [];

        if (empty($fields)) {
            return;
        }

        $saved = get_post_meta((int) $variation->ID, '_wcpait_values', true);
        $saved = is_array($saved) ? $saved : [];

        echo '<div class="wcpait-variation-group">';
        echo '<h4>' . esc_html__('Additional Information Table', 'wc-pait') . '</h4>';

        foreach ($fields as $field) {
            if (empty($field['id']) || empty($field['label'])) {
                continue;
            }

            $field_id = (string) $field['id'];
            $type = isset($field['type']) ? (string) $field['type'] : 'text';
            $value = isset($saved[$field_id]) ? (string) $saved[$field_id] : '';

            woocommerce_wp_text_input(
                [
                    'id' => 'wcpait_values_' . $field_id . '[' . $variation->ID . ']',
                    'name' => 'wcpait_variation_values[' . $variation->ID . '][' . $field_id . ']',
                    'value' => $value,
                    'label' => esc_html($field['label']),
                    'type' => ('number' === $type) ? 'number' : 'text',
                    'custom_attributes' => ('number' === $type)
                        ? ['step' => 'any', 'min' => '0']
                        : [],
                ]
            );
        }

        echo '</div>';
    }

    public function save_variation_fields(int $variation_id, int $i): void
    {
        if (!current_user_can('edit_post', $variation_id)) {
            return;
        }

        if (!isset($_POST['wcpait_variation_values']) || !is_array($_POST['wcpait_variation_values'])) {
            return;
        }

        $all_values = wp_unslash($_POST['wcpait_variation_values']);
        $variation_values = isset($all_values[$variation_id]) ? $all_values[$variation_id] : [];

        $settings = Settings::get();
        $fields = isset($settings['fields']) && is_array($settings['fields']) ? $settings['fields'] : [];
        $payload = $this->sanitize_values($fields, $variation_values);

        if (empty($payload)) {
            delete_post_meta($variation_id, '_wcpait_values');

            return;
        }

        update_post_meta($variation_id, '_wcpait_values', $payload);
    }

    /**
     * @param array<int, array<string, string>> $fields
     * @param mixed $values
     * @return array<string, string>
     */
    private function sanitize_values(array $fields, $values): array
    {
        if (!is_array($values)) {
            return [];
        }

        $output = [];

        foreach ($fields as $field) {
            if (empty($field['id'])) {
                continue;
            }

            $key = (string) $field['id'];
            $raw = isset($values[$key]) ? $values[$key] : '';
            $type = isset($field['type']) ? (string) $field['type'] : 'text';
            $value = Sanitizer::sanitize_product_value($raw, $type);

            if ('' === $value) {
                continue;
            }

            $output[$key] = $value;
        }

        return $output;
    }
}
