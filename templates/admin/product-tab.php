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
                <p class="form-field">
                    <label for="wcpait_values_<?php echo esc_attr($field_id); ?>"><?php echo esc_html($field['label']); ?></label>
                    <span class="wrap" style="display:flex;gap:8px;align-items:center;max-width:420px;">
                        <?php if ('' !== $prefix) : ?>
                            <span><?php echo esc_html($prefix); ?></span>
                        <?php endif; ?>
                        <input
                            type="<?php echo ('number' === $type) ? 'number' : 'text'; ?>"
                            id="wcpait_values_<?php echo esc_attr($field_id); ?>"
                            name="wcpait_values[<?php echo esc_attr($field_id); ?>]"
                            value="<?php echo esc_attr($value); ?>"
                            <?php echo ('number' === $type) ? 'step="any" min="0"' : ''; ?>
                        />
                        <?php if ('' !== $suffix) : ?>
                            <span><?php echo esc_html($suffix); ?></span>
                        <?php endif; ?>
                    </span>
                    <span class="description"><?php esc_html_e('Label, prefix, and suffix are controlled in plugin settings.', 'wc-pait'); ?></span>
                </p>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>
