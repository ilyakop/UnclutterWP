<?php

/**
 * Plugin Name: UnclutterWP
 * Plugin URI: https://github.com/ilyakop/UnclutterWP
 * Description: UnclutterWP removes unnecessary clutter, optimizes performance, and keeps your site running smoothly.
 * Version: 0.1
 * Author: Illia Online
 * Author URI: https://illia.online
 * License: GPLv2 or later
 */

namespace UnclutterWP;

const UNCLT_PREFIX = 'unclt_';

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
