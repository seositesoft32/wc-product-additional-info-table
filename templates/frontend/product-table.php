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
    <table class="wcpait-table" role="table" aria-label="<?php esc_attr_e('Product additional information', 'wc-pait'); ?>">
        <thead>
        <tr>
            <th><?php esc_html_e('Label', 'wc-pait'); ?></th>
            <th><?php esc_html_e('Value', 'wc-pait'); ?></th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($rows as $row) : ?>
            <tr>
                <td><?php echo esc_html($row['label']); ?></td>
                <td><?php echo esc_html($row['value']); ?></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>
