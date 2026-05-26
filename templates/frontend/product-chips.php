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
    <div class="wcpait-chips-grid" aria-label="<?php esc_attr_e('Product additional information', 'wc-pait'); ?>">
        <?php foreach ($rows as $row) : ?>
            <article class="wcpait-chip-item">
                <strong><?php echo esc_html($row['label']); ?></strong>
                <span><?php echo esc_html($row['value']); ?></span>
            </article>
        <?php endforeach; ?>
    </div>
</div>
