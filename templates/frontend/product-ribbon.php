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
    <ul class="wcpait-ribbon-list" aria-label="<?php esc_attr_e('Product additional information', 'wc-pait'); ?>">
        <?php foreach ($rows as $index => $row) : ?>
            <li class="wcpait-ribbon-item">
                <span class="wcpait-ribbon-badge"><?php echo esc_html((string) ($index + 1)); ?></span>
                <strong><?php echo esc_html($row['label']); ?></strong>
                <span><?php echo esc_html($row['value']); ?></span>
            </li>
        <?php endforeach; ?>
    </ul>
</div>
