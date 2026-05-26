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
    <ol class="wcpait-cards-list" aria-label="<?php esc_attr_e('Product additional information', 'wc-pait'); ?>">
        <?php foreach ($rows as $index => $row) : ?>
            <li class="wcpait-card-item">
                <span class="wcpait-card-index"><?php echo esc_html((string) ($index + 1)); ?></span>
                <div class="wcpait-card-content">
                    <h4><?php echo esc_html($row['label']); ?></h4>
                    <p><?php echo esc_html($row['value']); ?></p>
                </div>
            </li>
        <?php endforeach; ?>
    </ol>
</div>
