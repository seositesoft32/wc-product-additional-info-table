<?php

namespace WCPAIT\Core;

use WCPAIT\Admin\Assets as AdminAssets;
use WCPAIT\Admin\ProductTab;
use WCPAIT\Admin\SettingsPage;
use WCPAIT\Frontend\Assets as FrontendAssets;
use WCPAIT\Frontend\Hooks;
use WCPAIT\Frontend\Shortcode;
use WCPAIT\Support\Requirements;

class Plugin
{
    /**
     * @var array<int, object>
     */
    private $services = [];

    public function run(): void
    {
        if (!Requirements::is_woocommerce_active()) {
            add_action('admin_notices', [Requirements::class, 'render_woocommerce_notice']);

            return;
        }

        $this->services = [
            new SettingsPage(),
            new ProductTab(),
            new AdminAssets(),
            new FrontendAssets(),
            new Hooks(),
            new Shortcode(),
        ];

        foreach ($this->services as $service) {
            if (method_exists($service, 'register_hooks')) {
                $service->register_hooks();
            }
        }
    }
}
