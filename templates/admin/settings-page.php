<?php
/**
 * @var array<string, mixed> $settings
 * @var array<string, string> $positions
 * @var array<string, string> $styles
 */

if (!defined('ABSPATH')) {
    exit;
}

$fields = isset($settings['fields']) && is_array($settings['fields']) ? $settings['fields'] : [];
$selected_position = isset($settings['display_position']) ? (string) $settings['display_position'] : 'shortcode';
?>
<div class="wrap wcpait-admin-wrap">
    <h1><?php esc_html_e('WC Product Additional Info Table', 'wc-pait'); ?></h1>
    <p class="description"><?php esc_html_e('Create reusable additional information fields and control frontend display.', 'wc-pait'); ?></p>

    <form action="options.php" method="post" id="wcpait-settings-form">
        <?php settings_fields('wcpait_settings_group'); ?>

        <div class="wcpait-admin-card">
            <h2><?php esc_html_e('Additional Information Fields', 'wc-pait'); ?></h2>
            <p><?php esc_html_e('Drag to reorder fields. Label is required.', 'wc-pait'); ?></p>

            <table class="widefat striped wcpait-fields-table">
                <thead>
                <tr>
                    <th class="wcpait-sort-col"></th>
                    <th><?php esc_html_e('Label', 'wc-pait'); ?></th>
                    <th><?php esc_html_e('Value Type', 'wc-pait'); ?></th>
                    <th><?php esc_html_e('Prefix', 'wc-pait'); ?></th>
                    <th><?php esc_html_e('Suffix', 'wc-pait'); ?></th>
                    <th><?php esc_html_e('Action', 'wc-pait'); ?></th>
                </tr>
                </thead>
                <tbody id="wcpait-field-rows">
                <?php foreach ($fields as $index => $field) : ?>
                    <tr class="wcpait-field-row">
                        <td class="wcpait-sort-col"><span class="dashicons dashicons-menu"></span></td>
                        <td>
                            <input type="hidden" name="wcpait_settings[fields][<?php echo esc_attr((string) $index); ?>][id]" value="<?php echo esc_attr(isset($field['id']) ? (string) $field['id'] : ''); ?>" />
                            <input type="text" name="wcpait_settings[fields][<?php echo esc_attr((string) $index); ?>][label]" value="<?php echo esc_attr(isset($field['label']) ? (string) $field['label'] : ''); ?>" required />
                        </td>
                        <td>
                            <select name="wcpait_settings[fields][<?php echo esc_attr((string) $index); ?>][type]">
                                <option value="text" <?php selected(isset($field['type']) ? (string) $field['type'] : 'text', 'text'); ?>><?php esc_html_e('Text', 'wc-pait'); ?></option>
                                <option value="number" <?php selected(isset($field['type']) ? (string) $field['type'] : 'text', 'number'); ?>><?php esc_html_e('Number', 'wc-pait'); ?></option>
                            </select>
                        </td>
                        <td><input type="text" name="wcpait_settings[fields][<?php echo esc_attr((string) $index); ?>][prefix]" value="<?php echo esc_attr(isset($field['prefix']) ? (string) $field['prefix'] : ''); ?>" /></td>
                        <td><input type="text" name="wcpait_settings[fields][<?php echo esc_attr((string) $index); ?>][suffix]" value="<?php echo esc_attr(isset($field['suffix']) ? (string) $field['suffix'] : ''); ?>" /></td>
                        <td><button type="button" class="button-link-delete wcpait-remove-row"><?php esc_html_e('Remove', 'wc-pait'); ?></button></td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>

            <p>
                <button type="button" class="button button-secondary" id="wcpait-add-row"><?php esc_html_e('Add New Row', 'wc-pait'); ?></button>
            </p>
        </div>

        <div class="wcpait-admin-grid">
            <div class="wcpait-admin-card">
                <h2><?php esc_html_e('Display Positions', 'wc-pait'); ?></h2>
                <p>
                    <select name="wcpait_settings[display_position]">
                        <?php foreach ($positions as $key => $label) : ?>
                            <option value="<?php echo esc_attr($key); ?>" <?php selected($selected_position, $key); ?>>
                                <?php echo esc_html($label); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </p>
                <p class="description"><?php esc_html_e('Use shortcode option to render manually with [product_additional_info].', 'wc-pait'); ?></p>
            </div>

            <div class="wcpait-admin-card">
                <h2><?php esc_html_e('Table Style Preset', 'wc-pait'); ?></h2>
                <p>
                    <select name="wcpait_settings[table_style]">
                        <?php foreach ($styles as $key => $label) : ?>
                            <option value="<?php echo esc_attr($key); ?>" <?php selected(isset($settings['table_style']) ? (string) $settings['table_style'] : 'minimal', $key); ?>>
                                <?php echo esc_html($label); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </p>

                <div class="wcpait-style-grid">
                    <label><?php esc_html_e('Table background', 'wc-pait'); ?><input type="color" name="wcpait_settings[table_background]" value="<?php echo esc_attr((string) $settings['table_background']); ?>" /></label>
                    <label><?php esc_html_e('Header background', 'wc-pait'); ?><input type="color" name="wcpait_settings[header_background]" value="<?php echo esc_attr((string) $settings['header_background']); ?>" /></label>
                    <label><?php esc_html_e('Text color', 'wc-pait'); ?><input type="color" name="wcpait_settings[text_color]" value="<?php echo esc_attr((string) $settings['text_color']); ?>" /></label>
                    <label><?php esc_html_e('Border color', 'wc-pait'); ?><input type="color" name="wcpait_settings[border_color]" value="<?php echo esc_attr((string) $settings['border_color']); ?>" /></label>
                    <label><?php esc_html_e('Alternate row color', 'wc-pait'); ?><input type="color" name="wcpait_settings[alternate_row_color]" value="<?php echo esc_attr((string) $settings['alternate_row_color']); ?>" /></label>
                    <label><?php esc_html_e('Row spacing (px)', 'wc-pait'); ?><input type="number" min="0" max="40" name="wcpait_settings[row_spacing]" value="<?php echo esc_attr((string) $settings['row_spacing']); ?>" /></label>
                    <label><?php esc_html_e('Cell padding (px)', 'wc-pait'); ?><input type="number" min="4" max="40" name="wcpait_settings[cell_padding]" value="<?php echo esc_attr((string) $settings['cell_padding']); ?>" /></label>
                    <label><?php esc_html_e('Typography size (px)', 'wc-pait'); ?><input type="number" min="10" max="24" name="wcpait_settings[font_size]" value="<?php echo esc_attr((string) $settings['font_size']); ?>" /></label>
                    <label><?php esc_html_e('Border radius (px)', 'wc-pait'); ?><input type="number" min="0" max="30" name="wcpait_settings[border_radius]" value="<?php echo esc_attr((string) $settings['border_radius']); ?>" /></label>
                </div>
            </div>
        </div>

        <div class="wcpait-admin-card">
            <h2><?php esc_html_e('Advanced', 'wc-pait'); ?></h2>
            <p>
                <label>
                    <input type="checkbox" name="wcpait_settings[show_table_header]" value="yes" <?php checked(isset($settings['show_table_header']) ? (string) $settings['show_table_header'] : 'yes', 'yes'); ?> />
                    <?php esc_html_e('Show table header (Label/Value) on frontend', 'wc-pait'); ?>
                </label>
            </p>
            <label>
                <input type="checkbox" name="wcpait_settings[cleanup_on_uninstall]" value="yes" <?php checked(isset($settings['cleanup_on_uninstall']) ? (string) $settings['cleanup_on_uninstall'] : 'no', 'yes'); ?> />
                <?php esc_html_e('Delete plugin options and product values on uninstall', 'wc-pait'); ?>
            </label>
        </div>

        <?php submit_button(__('Save Settings', 'wc-pait')); ?>
    </form>
</div>
