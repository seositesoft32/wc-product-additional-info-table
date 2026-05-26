<?php
/**
 * @var array<int, array<string, string>> $rows
 * @var string $style
 * @var string $wrapper_styles
 * @var bool $show_table_header
 * @var string $header_label_text
 * @var string $header_value_text
 */

if (!defined('ABSPATH')) {
    exit;
}
?>
<div class="wcpait-table-wrap wcpait-style-<?php echo esc_attr($style); ?>" style="<?php echo esc_attr($wrapper_styles); ?>">
    <table class="wcpait-table" role="table" aria-label="<?php esc_attr_e('Product additional information', 'wc-pait'); ?>">
        <?php if ($show_table_header) : ?>
            <thead>
            <tr>
                <th><?php echo esc_html($header_label_text); ?></th>
                <th><?php echo esc_html($header_value_text); ?></th>
            </tr>
            </thead>
        <?php endif; ?>
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
