<?php

if (!defined('WP_UNINSTALL_PLUGIN')) {
    exit;
}

$settings = get_option('wcpait_settings', []);
$cleanup = isset($settings['cleanup_on_uninstall']) && 'yes' === $settings['cleanup_on_uninstall'];

if (!$cleanup) {
    return;
}

delete_option('wcpait_settings');

$products = get_posts(
    [
        'post_type' => ['product', 'product_variation'],
        'posts_per_page' => -1,
        'fields' => 'ids',
        'post_status' => 'any',
    ]
);

if (empty($products)) {
    return;
}

foreach ($products as $product_id) {
    delete_post_meta((int) $product_id, '_wcpait_values');
}
