<?php
/**
 * @var array<int, array<string, string>> $rows
 * @var string $style
 * @var string $wrapper_styles
 */

if (!defined('ABSPATH')) {
    exit;
}
?>
<div class="wcpait-table-wrap wcpait-style-<?php echo esc_attr($style); ?>" style="<?php echo esc_attr($wrapper_styles); ?>">
    <ol class="wcpait-steps-list" aria-label="<?php esc_attr_e('Product additional information', 'wc-pait'); ?>">
        <?php foreach ($rows as $index => $row) : ?>
            <li class="wcpait-step-item">
                <span class="wcpait-step-badge"><?php echo esc_html((string) ($index + 1)); ?></span>
                <div class="wcpait-step-content">
                    <strong><?php echo esc_html($row['label']); ?></strong>
                    <span><?php echo esc_html($row['value']); ?></span>
                </div>
            </li>
        <?php endforeach; ?>
    </ol>
</div>
