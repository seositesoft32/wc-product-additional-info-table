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
$table_style = isset($settings['table_style']) ? (string) $settings['table_style'] : 'minimal';
$header_label_text = isset($settings['header_label_text']) ? (string) $settings['header_label_text'] : __('Label', 'wc-pait');
$header_value_text = isset($settings['header_value_text']) ? (string) $settings['header_value_text'] : __('Value', 'wc-pait');
$show_table_header = isset($settings['show_table_header']) ? (string) $settings['show_table_header'] : 'yes';
$preview_styles = sprintf(
    '--wcpait-table-bg:%s;--wcpait-header-bg:%s;--wcpait-text:%s;--wcpait-border:%s;--wcpait-alt-row:%s;--wcpait-row-spacing:%dpx;--wcpait-cell-padding:%dpx;--wcpait-font-size:%dpx;--wcpait-radius:%dpx;',
    esc_attr((string) $settings['table_background']),
    esc_attr((string) $settings['header_background']),
    esc_attr((string) $settings['text_color']),
    esc_attr((string) $settings['border_color']),
    esc_attr((string) $settings['alternate_row_color']),
    absint($settings['row_spacing']),
    absint($settings['cell_padding']),
    absint($settings['font_size']),
    absint($settings['border_radius'])
);
?>
<div class="wrap wcpait-admin-wrap">
    <div class="wcpait-settings-header">
        <div>
            <h1><?php esc_html_e('WC Product Additional Info Table', 'wc-pait'); ?></h1>
            <p class="description"><?php esc_html_e('Create reusable additional information fields and control frontend display.', 'wc-pait'); ?></p>
        </div>
        <div>
            <button type="submit" form="wcpait-settings-form" class="button button-primary" id="wcpait-save-settings-btn"><?php esc_html_e('Save Settings', 'wc-pait'); ?></button>
        </div>
    </div>

    <div id="wcpait-admin-notice" class="wcpait-admin-notice" style="display:none;"></div>

    <form action="options.php" method="post" id="wcpait-settings-form">
        <?php settings_fields('wcpait_settings_group'); ?>
        <?php wp_nonce_field('wcpait_save_settings_nonce', 'wcpait_save_settings_nonce'); ?>

        <div class="wcpait-settings-tabs" role="tablist" aria-label="<?php esc_attr_e('Settings tabs', 'wc-pait'); ?>">
            <button type="button" class="button-link wcpait-tab is-active" data-tab="fields"><?php esc_html_e('Fields', 'wc-pait'); ?></button>
            <button type="button" class="button-link wcpait-tab" data-tab="display"><?php esc_html_e('Display', 'wc-pait'); ?></button>
            <button type="button" class="button-link wcpait-tab" data-tab="style"><?php esc_html_e('Style', 'wc-pait'); ?></button>
            <button type="button" class="button-link wcpait-tab" data-tab="advanced"><?php esc_html_e('Advanced', 'wc-pait'); ?></button>
        </div>

        <div class="wcpait-tab-panel is-active" data-panel="fields">
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
        </div>

        <div class="wcpait-tab-panel" data-panel="display">
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
                <h2><?php esc_html_e('Header Row', 'wc-pait'); ?></h2>
                <p>
                    <label>
                        <input type="checkbox" name="wcpait_settings[show_table_header]" value="yes" <?php checked($show_table_header, 'yes'); ?> />
                        <?php esc_html_e('Show table header on frontend', 'wc-pait'); ?>
                    </label>
                </p>
                <p>
                    <label for="wcpait_header_label_text"><?php esc_html_e('Header text: Label column', 'wc-pait'); ?></label><br />
                    <input type="text" id="wcpait_header_label_text" name="wcpait_settings[header_label_text]" value="<?php echo esc_attr($header_label_text); ?>" class="regular-text" />
                </p>
                <p>
                    <label for="wcpait_header_value_text"><?php esc_html_e('Header text: Value column', 'wc-pait'); ?></label><br />
                    <input type="text" id="wcpait_header_value_text" name="wcpait_settings[header_value_text]" value="<?php echo esc_attr($header_value_text); ?>" class="regular-text" />
                </p>
            </div>
        </div>
        </div>

        <div class="wcpait-tab-panel" data-panel="style">
        <div class="wcpait-admin-grid">
            <div class="wcpait-admin-card">
                <h2><?php esc_html_e('Table Style Preset', 'wc-pait'); ?></h2>
                <p>
                    <select name="wcpait_settings[table_style]" id="wcpait_table_style">
                        <?php foreach ($styles as $key => $label) : ?>
                            <option value="<?php echo esc_attr($key); ?>" <?php selected($table_style, $key); ?>>
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

            <div class="wcpait-admin-card">
                <h2><?php esc_html_e('Live Preview', 'wc-pait'); ?></h2>
                <p class="description"><?php esc_html_e('Preview updates in real time and uses the same style classes as frontend output.', 'wc-pait'); ?></p>
                <div id="wcpait-live-preview" class="wcpait-table-wrap wcpait-style-<?php echo esc_attr($table_style); ?>" style="<?php echo esc_attr($preview_styles); ?>">
                    <table class="wcpait-table">
                        <thead <?php echo ('yes' === $show_table_header) ? '' : 'style="display:none;"'; ?>>
                        <tr>
                            <th class="wcpait-preview-label"><?php echo esc_html($header_label_text); ?></th>
                            <th class="wcpait-preview-value"><?php echo esc_html($header_value_text); ?></th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td><?php esc_html_e('Material', 'wc-pait'); ?></td>
                            <td><?php esc_html_e('SPC', 'wc-pait'); ?></td>
                        </tr>
                        <tr>
                            <td><?php esc_html_e('Thickness', 'wc-pait'); ?></td>
                            <td><?php esc_html_e('5 mm', 'wc-pait'); ?></td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        </div>

        <div class="wcpait-tab-panel" data-panel="advanced">
        <div class="wcpait-admin-card">
            <h2><?php esc_html_e('Advanced', 'wc-pait'); ?></h2>
            <label>
                <input type="checkbox" name="wcpait_settings[cleanup_on_uninstall]" value="yes" <?php checked(isset($settings['cleanup_on_uninstall']) ? (string) $settings['cleanup_on_uninstall'] : 'no', 'yes'); ?> />
                <?php esc_html_e('Delete plugin options and product values on uninstall', 'wc-pait'); ?>
            </label>
        </div>
        </div>

        <p class="submit">
            <button type="submit" class="button button-primary" id="wcpait-save-settings-btn-bottom"><?php esc_html_e('Save Settings', 'wc-pait'); ?></button>
        </p>
    </form>
</div>
