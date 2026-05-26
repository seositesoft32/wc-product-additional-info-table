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
$table_style = isset($settings['table_style']) ? (string) $settings['table_style'] : 'clean_table';
$header_label_text = isset($settings['header_label_text']) ? (string) $settings['header_label_text'] : __('Label', 'wc-pait');
$header_value_text = isset($settings['header_value_text']) ? (string) $settings['header_value_text'] : __('Value', 'wc-pait');
$show_table_header = isset($settings['show_table_header']) ? (string) $settings['show_table_header'] : 'yes';
$preview_styles = sprintf(
    '--wcpait-table-bg:%s;--wcpait-header-bg:%s;--wcpait-text:%s;--wcpait-border:%s;--wcpait-alt-row:%s;--wcpait-row-spacing:%dpx;--wcpait-cell-padding:%dpx;--wcpait-font-size:%dpx;--wcpait-radius:%dpx;--wcpait-cards-accent:%s;--wcpait-cards-badge-text:%s;--wcpait-cards-shadow:%dpx;--wcpait-grid-badge-bg:%s;--wcpait-grid-badge-text:%s;--wcpait-grid-card-bg:%s;--wcpait-grid-card-border:%s;--wcpait-ribbon-bg:%s;--wcpait-ribbon-text:%s;--wcpait-ribbon-badge-bg:%s;--wcpait-ribbon-badge-text:%s;--wcpait-minimal-line:%s;--wcpait-glass-tint:%s;--wcpait-glass-border:%s;--wcpait-glass-glow:%dpx;--wcpait-steps-bar:%s;--wcpait-steps-badge-bg:%s;--wcpait-steps-badge-text:%s;--wcpait-chips-bg:%s;--wcpait-chips-text:%s;--wcpait-chips-border:%s;',
    esc_attr((string) $settings['table_background']),
    esc_attr((string) $settings['header_background']),
    esc_attr((string) $settings['text_color']),
    esc_attr((string) $settings['border_color']),
    esc_attr((string) $settings['alternate_row_color']),
    absint($settings['row_spacing']),
    absint($settings['cell_padding']),
    absint($settings['font_size']),
    absint($settings['border_radius']),
    esc_attr((string) $settings['cards_accent_color']),
    esc_attr((string) $settings['cards_badge_text_color']),
    absint($settings['cards_shadow_intensity']),
    esc_attr((string) $settings['grid_badge_bg']),
    esc_attr((string) $settings['grid_badge_text']),
    esc_attr((string) $settings['grid_card_bg']),
    esc_attr((string) $settings['grid_card_border']),
    esc_attr((string) $settings['ribbon_bg_color']),
    esc_attr((string) $settings['ribbon_text_color']),
    esc_attr((string) $settings['ribbon_badge_bg']),
    esc_attr((string) $settings['ribbon_badge_text']),
    esc_attr((string) $settings['minimal_line_color']),
    esc_attr((string) $settings['glass_tint_color']),
    esc_attr((string) $settings['glass_border_color']),
    absint($settings['glass_glow_intensity']),
    esc_attr((string) $settings['steps_bar_color']),
    esc_attr((string) $settings['steps_badge_bg']),
    esc_attr((string) $settings['steps_badge_text']),
    esc_attr((string) $settings['chips_bg_color']),
    esc_attr((string) $settings['chips_text_color']),
    esc_attr((string) $settings['chips_border_color'])
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

        <div class="wcpait-settings-tabs-wrap">
            <div class="wcpait-settings-tabs" role="tablist" aria-label="<?php esc_attr_e('Settings tabs', 'wc-pait'); ?>">
                <button type="button" id="wcpait-tab-fields" class="button-link wcpait-tab is-active" data-tab="fields" role="tab" aria-selected="true" aria-controls="wcpait-panel-fields" tabindex="0">
                    <span class="dashicons dashicons-feedback" aria-hidden="true"></span>
                    <span><?php esc_html_e('Fields', 'wc-pait'); ?></span>
                </button>
                <button type="button" id="wcpait-tab-display" class="button-link wcpait-tab" data-tab="display" role="tab" aria-selected="false" aria-controls="wcpait-panel-display" tabindex="-1">
                    <span class="dashicons dashicons-visibility" aria-hidden="true"></span>
                    <span><?php esc_html_e('Display', 'wc-pait'); ?></span>
                </button>
                <button type="button" id="wcpait-tab-style" class="button-link wcpait-tab" data-tab="style" role="tab" aria-selected="false" aria-controls="wcpait-panel-style" tabindex="-1">
                    <span class="dashicons dashicons-art" aria-hidden="true"></span>
                    <span><?php esc_html_e('Style', 'wc-pait'); ?></span>
                </button>
                <button type="button" id="wcpait-tab-advanced" class="button-link wcpait-tab" data-tab="advanced" role="tab" aria-selected="false" aria-controls="wcpait-panel-advanced" tabindex="-1">
                    <span class="dashicons dashicons-admin-generic" aria-hidden="true"></span>
                    <span><?php esc_html_e('Advanced', 'wc-pait'); ?></span>
                </button>
            </div>
            <p class="description wcpait-tabs-help"><?php esc_html_e('Move between setup areas without leaving the page. Your changes stay in place until saved.', 'wc-pait'); ?></p>
        </div>

        <div class="wcpait-tab-panel is-active" id="wcpait-panel-fields" data-panel="fields" role="tabpanel" aria-labelledby="wcpait-tab-fields">
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

        <div class="wcpait-tab-panel" id="wcpait-panel-display" data-panel="display" role="tabpanel" aria-labelledby="wcpait-tab-display" hidden>
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

        <div class="wcpait-tab-panel" id="wcpait-panel-style" data-panel="style" role="tabpanel" aria-labelledby="wcpait-tab-style" hidden>
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

                <div class="wcpait-style-grid wcpait-style-options" data-styles="clean_table,minimal_lines">
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

                <div class="wcpait-style-grid wcpait-style-options" data-styles="minimal_lines">
                    <label><?php esc_html_e('Line color', 'wc-pait'); ?><input type="color" name="wcpait_settings[minimal_line_color]" value="<?php echo esc_attr((string) $settings['minimal_line_color']); ?>" /></label>
                </div>

                <div class="wcpait-style-grid wcpait-style-options" data-styles="cards_timeline">
                    <label><?php esc_html_e('Card accent color', 'wc-pait'); ?><input type="color" name="wcpait_settings[cards_accent_color]" value="<?php echo esc_attr((string) $settings['cards_accent_color']); ?>" /></label>
                    <label><?php esc_html_e('Badge text color', 'wc-pait'); ?><input type="color" name="wcpait_settings[cards_badge_text_color]" value="<?php echo esc_attr((string) $settings['cards_badge_text_color']); ?>" /></label>
                    <label><?php esc_html_e('Shadow depth', 'wc-pait'); ?><input type="number" min="0" max="35" name="wcpait_settings[cards_shadow_intensity]" value="<?php echo esc_attr((string) $settings['cards_shadow_intensity']); ?>" /></label>
                </div>

                <div class="wcpait-style-grid wcpait-style-options" data-styles="badge_grid">
                    <label><?php esc_html_e('Card background', 'wc-pait'); ?><input type="color" name="wcpait_settings[grid_card_bg]" value="<?php echo esc_attr((string) $settings['grid_card_bg']); ?>" /></label>
                    <label><?php esc_html_e('Card border', 'wc-pait'); ?><input type="color" name="wcpait_settings[grid_card_border]" value="<?php echo esc_attr((string) $settings['grid_card_border']); ?>" /></label>
                    <label><?php esc_html_e('Badge background', 'wc-pait'); ?><input type="color" name="wcpait_settings[grid_badge_bg]" value="<?php echo esc_attr((string) $settings['grid_badge_bg']); ?>" /></label>
                    <label><?php esc_html_e('Badge text color', 'wc-pait'); ?><input type="color" name="wcpait_settings[grid_badge_text]" value="<?php echo esc_attr((string) $settings['grid_badge_text']); ?>" /></label>
                </div>

                <div class="wcpait-style-grid wcpait-style-options" data-styles="ribbon_list">
                    <label><?php esc_html_e('Ribbon background', 'wc-pait'); ?><input type="color" name="wcpait_settings[ribbon_bg_color]" value="<?php echo esc_attr((string) $settings['ribbon_bg_color']); ?>" /></label>
                    <label><?php esc_html_e('Ribbon text color', 'wc-pait'); ?><input type="color" name="wcpait_settings[ribbon_text_color]" value="<?php echo esc_attr((string) $settings['ribbon_text_color']); ?>" /></label>
                    <label><?php esc_html_e('Ribbon badge background', 'wc-pait'); ?><input type="color" name="wcpait_settings[ribbon_badge_bg]" value="<?php echo esc_attr((string) $settings['ribbon_badge_bg']); ?>" /></label>
                    <label><?php esc_html_e('Ribbon badge text', 'wc-pait'); ?><input type="color" name="wcpait_settings[ribbon_badge_text]" value="<?php echo esc_attr((string) $settings['ribbon_badge_text']); ?>" /></label>
                </div>

                <div class="wcpait-style-grid wcpait-style-options" data-styles="glass_panel">
                    <label><?php esc_html_e('Glass tint', 'wc-pait'); ?><input type="color" name="wcpait_settings[glass_tint_color]" value="<?php echo esc_attr((string) $settings['glass_tint_color']); ?>" /></label>
                    <label><?php esc_html_e('Glass border', 'wc-pait'); ?><input type="color" name="wcpait_settings[glass_border_color]" value="<?php echo esc_attr((string) $settings['glass_border_color']); ?>" /></label>
                    <label><?php esc_html_e('Glow intensity', 'wc-pait'); ?><input type="number" min="0" max="40" name="wcpait_settings[glass_glow_intensity]" value="<?php echo esc_attr((string) $settings['glass_glow_intensity']); ?>" /></label>
                </div>

                <div class="wcpait-style-grid wcpait-style-options" data-styles="progress_steps">
                    <label><?php esc_html_e('Step bar color', 'wc-pait'); ?><input type="color" name="wcpait_settings[steps_bar_color]" value="<?php echo esc_attr((string) $settings['steps_bar_color']); ?>" /></label>
                    <label><?php esc_html_e('Step badge background', 'wc-pait'); ?><input type="color" name="wcpait_settings[steps_badge_bg]" value="<?php echo esc_attr((string) $settings['steps_badge_bg']); ?>" /></label>
                    <label><?php esc_html_e('Step badge text', 'wc-pait'); ?><input type="color" name="wcpait_settings[steps_badge_text]" value="<?php echo esc_attr((string) $settings['steps_badge_text']); ?>" /></label>
                </div>

                <div class="wcpait-style-grid wcpait-style-options" data-styles="chip_tiles">
                    <label><?php esc_html_e('Chip background', 'wc-pait'); ?><input type="color" name="wcpait_settings[chips_bg_color]" value="<?php echo esc_attr((string) $settings['chips_bg_color']); ?>" /></label>
                    <label><?php esc_html_e('Chip text color', 'wc-pait'); ?><input type="color" name="wcpait_settings[chips_text_color]" value="<?php echo esc_attr((string) $settings['chips_text_color']); ?>" /></label>
                    <label><?php esc_html_e('Chip border', 'wc-pait'); ?><input type="color" name="wcpait_settings[chips_border_color]" value="<?php echo esc_attr((string) $settings['chips_border_color']); ?>" /></label>
                </div>
            </div>

            <div class="wcpait-admin-card">
                <h2><?php esc_html_e('Live Preview', 'wc-pait'); ?></h2>
                <p class="description"><?php esc_html_e('Preview updates in real time and uses the same style classes as frontend output.', 'wc-pait'); ?></p>
                <div id="wcpait-live-preview" class="wcpait-table-wrap wcpait-style-<?php echo esc_attr($table_style); ?>" style="<?php echo esc_attr($preview_styles); ?>">
                    <table class="wcpait-table wcpait-preview-template" data-preview="clean_table minimal_lines">
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

                    <ol class="wcpait-cards-list wcpait-preview-template" data-preview="cards_timeline" style="display:none;">
                        <li class="wcpait-card-item"><span class="wcpait-card-index">1</span><div class="wcpait-card-content"><h4><?php esc_html_e('Material', 'wc-pait'); ?></h4><p><?php esc_html_e('SPC', 'wc-pait'); ?></p></div></li>
                        <li class="wcpait-card-item"><span class="wcpait-card-index">2</span><div class="wcpait-card-content"><h4><?php esc_html_e('Thickness', 'wc-pait'); ?></h4><p><?php esc_html_e('5 mm', 'wc-pait'); ?></p></div></li>
                    </ol>

                    <div class="wcpait-grid-list wcpait-preview-template" data-preview="badge_grid" style="display:none;">
                        <article class="wcpait-grid-item"><span class="wcpait-grid-badge">1</span><h4><?php esc_html_e('Material', 'wc-pait'); ?></h4><p><?php esc_html_e('SPC', 'wc-pait'); ?></p></article>
                        <article class="wcpait-grid-item"><span class="wcpait-grid-badge">2</span><h4><?php esc_html_e('Thickness', 'wc-pait'); ?></h4><p><?php esc_html_e('5 mm', 'wc-pait'); ?></p></article>
                    </div>

                    <ul class="wcpait-ribbon-list wcpait-preview-template" data-preview="ribbon_list" style="display:none;">
                        <li class="wcpait-ribbon-item"><span class="wcpait-ribbon-badge">1</span><div><strong><?php esc_html_e('Material', 'wc-pait'); ?></strong><span><?php esc_html_e('SPC', 'wc-pait'); ?></span></div></li>
                        <li class="wcpait-ribbon-item"><span class="wcpait-ribbon-badge">2</span><div><strong><?php esc_html_e('Thickness', 'wc-pait'); ?></strong><span><?php esc_html_e('5 mm', 'wc-pait'); ?></span></div></li>
                    </ul>

                    <ul class="wcpait-glass-list wcpait-preview-template" data-preview="glass_panel" style="display:none;">
                        <li class="wcpait-glass-item"><strong><?php esc_html_e('Material', 'wc-pait'); ?></strong><span><?php esc_html_e('SPC', 'wc-pait'); ?></span></li>
                        <li class="wcpait-glass-item"><strong><?php esc_html_e('Thickness', 'wc-pait'); ?></strong><span><?php esc_html_e('5 mm', 'wc-pait'); ?></span></li>
                    </ul>

                    <ol class="wcpait-steps-list wcpait-preview-template" data-preview="progress_steps" style="display:none;">
                        <li class="wcpait-step-item"><span class="wcpait-step-badge">1</span><div class="wcpait-step-content"><strong><?php esc_html_e('Material', 'wc-pait'); ?></strong><span><?php esc_html_e('SPC', 'wc-pait'); ?></span></div></li>
                        <li class="wcpait-step-item"><span class="wcpait-step-badge">2</span><div class="wcpait-step-content"><strong><?php esc_html_e('Thickness', 'wc-pait'); ?></strong><span><?php esc_html_e('5 mm', 'wc-pait'); ?></span></div></li>
                    </ol>

                    <div class="wcpait-chips-grid wcpait-preview-template" data-preview="chip_tiles" style="display:none;">
                        <article class="wcpait-chip-item"><strong><?php esc_html_e('Material', 'wc-pait'); ?></strong><span><?php esc_html_e('SPC', 'wc-pait'); ?></span></article>
                        <article class="wcpait-chip-item"><strong><?php esc_html_e('Thickness', 'wc-pait'); ?></strong><span><?php esc_html_e('5 mm', 'wc-pait'); ?></span></article>
                    </div>
                </div>
            </div>
        </div>
        </div>

        <div class="wcpait-tab-panel" id="wcpait-panel-advanced" data-panel="advanced" role="tabpanel" aria-labelledby="wcpait-tab-advanced" hidden>
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
