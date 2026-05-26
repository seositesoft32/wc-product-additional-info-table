<?php
/**
 * Plugin Name:       WC Product Additional Info Table
 * Plugin URI:        https://myspecialtyflooring.com/
 * Description:       Create customizable additional information tables for WooCommerce products.
 * Version:           1.0.0
 * Author:            SeoSiteSoft
 * Author URI:        https://myspecialtyflooring.com/
 * Requires Plugins:  woocommerce
 * Requires at least: 6.4
 * Tested up to:      6.8
 * Requires PHP:      7.4
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       wc-pait
 * Domain Path:       /languages/
 * WC requires at least: 7.8
 * WC tested up to:   10.0
 */

if (!defined('ABSPATH')) {
    exit;
}

define('WCPAIT_VERSION', '1.0.0');
define('WCPAIT_FILE', __FILE__);
define('WCPAIT_PATH', plugin_dir_path(__FILE__));
define('WCPAIT_URL', plugin_dir_url(__FILE__));
define('WCPAIT_BASENAME', plugin_basename(__FILE__));

spl_autoload_register(
    static function ($class): void {
        $prefix = 'WCPAIT\\';
        $base_dir = WCPAIT_PATH . 'includes/';

        if (0 !== strpos($class, $prefix)) {
            return;
        }

        $relative_class = substr($class, strlen($prefix));
        $file = $base_dir . str_replace('\\', '/', $relative_class) . '.php';

        if (file_exists($file)) {
            require_once $file;
        }
    }
);

register_activation_hook(
    WCPAIT_FILE,
    static function (): void {
        if (!get_option('wcpait_settings')) {
            update_option('wcpait_settings', WCPAIT\Helpers\Defaults::settings());
        }
    }
);

add_action(
    'before_woocommerce_init',
    static function (): void {
        if (class_exists('\\Automattic\\WooCommerce\\Utilities\\FeaturesUtil')) {
            \Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility('custom_order_tables', WCPAIT_FILE, true);
            \Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility('cart_checkout_blocks', WCPAIT_FILE, true);
        }
    }
);

add_action(
    'plugins_loaded',
    static function (): void {
        load_plugin_textdomain('wc-pait', false, dirname(WCPAIT_BASENAME) . '/languages');

        $plugin = new WCPAIT\Core\Plugin();
        $plugin->run();
    }
);
