<?php
/**
 * @var array<int, array<string, string>> $fields
 * @var array<string, string> $saved
 */

if (!defined('ABSPATH')) {
    exit;
}
?>
<div id="wcpait_additional_info_data" class="panel woocommerce_options_panel hidden">
    <div class="options_group">
        <?php wp_nonce_field('wcpait_product_nonce_action', 'wcpait_product_nonce'); ?>

        <?php if (empty($fields)) : ?>
            <p style="padding: 0 12px;"><?php esc_html_e('No fields configured yet. Configure fields from WooCommerce > Additional Info Table.', 'wc-pait'); ?></p>
        <?php else : ?>
            <div class="wcpait-product-table-wrap">
                <table class="widefat striped wcpait-product-values-table">
                    <thead>
                    <tr>
                        <th><?php esc_html_e('Label', 'wc-pait'); ?></th>
                        <th><?php esc_html_e('Prefix', 'wc-pait'); ?></th>
                        <th><?php esc_html_e('Value', 'wc-pait'); ?></th>
                        <th><?php esc_html_e('Suffix', 'wc-pait'); ?></th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($fields as $field) : ?>
                        <?php
                        if (empty($field['id']) || empty($field['label'])) {
                            continue;
                        }

                        $field_id = (string) $field['id'];
                        $type = isset($field['type']) ? (string) $field['type'] : 'text';
                        $value = isset($saved[$field_id]) ? (string) $saved[$field_id] : '';
                        $prefix = isset($field['prefix']) ? (string) $field['prefix'] : '';
                        $suffix = isset($field['suffix']) ? (string) $field['suffix'] : '';
                        ?>
                        <tr>
                            <td><strong><?php echo esc_html($field['label']); ?></strong></td>
                            <td><?php echo esc_html($prefix); ?></td>
                            <td>
                                <input
                                    class="wcpait-value-input"
                                    type="<?php echo ('number' === $type) ? 'number' : 'text'; ?>"
                                    id="wcpait_values_<?php echo esc_attr($field_id); ?>"
                                    name="wcpait_values[<?php echo esc_attr($field_id); ?>]"
                                    value="<?php echo esc_attr($value); ?>"
                                    <?php echo ('number' === $type) ? 'step="any" min="0"' : ''; ?>
                                />
                            </td>
                            <td><?php echo esc_html($suffix); ?></td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</div>
