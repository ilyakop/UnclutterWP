<?php

/**
 * Plugin Name: UnclutterWP
 * Plugin URI: https://unclutterwp.com
 * Description: Improve Core Web Vitals by removing WordPress frontend bloat, optimizing assets, and controlling scripts that slow down your site.
 * Version: 1.0.1
 * Author: UnclutterWP
 * Author URI: https://unclutterwp.com
 * License: GPLv2 or later
 */

namespace UnclutterWP;

const UNCLT_PREFIX = 'unclt_';
const UNCLT_FILE = __FILE__;

if (!defined('ABSPATH')) {
    exit;
}

/** 
 * Main plugin class
 */
class UNCLT_Main
{
    /**
     * Constructor
     *
     * @since 1.0.0
     */
    public function __construct()
    {
        $this->includes();
        $this->register_hooks();
    }

    /**
     * Include necessary files
     *
     * @since 1.0.0
     */
    private function includes()
    {
        include_once plugin_dir_path(__FILE__) . 'includes/options.php';
        include_once plugin_dir_path(__FILE__) . 'includes/unclutter.frontend.php';
        if (is_admin()) {
            require_once plugin_dir_path(__FILE__) . 'includes/unclutter.admin.php';
        }
    }

    /**
     * Register plugin hooks
     *
     * @since 1.0.0
     */
    private function register_hooks()
    {
        // Add your custom hooks here, e.g. add_action( 'admin_init', array( $this, 'your_cleanup_function' ) );
        // Need to flush when rules are changed
        register_activation_hook(__FILE__, 'flush_rewrite_rules');
        register_deactivation_hook(__FILE__, 'flush_rewrite_rules');
    }
}

// Initialize the plugin
new UNCLT_Main();
