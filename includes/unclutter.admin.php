<?php

namespace UnclutterWP\Admin;

if (!defined('ABSPATH')) {
    exit;
}

use UnclutterWP\Options\UNCLT_Options;

class UNCLT_Admin
{
    private $settings;

    public function __construct()
    {
        global $UNCLT_Options;
        $this->settings = $UNCLT_Options->get_all_settings();

        // Hook into WordPress admin init action
        add_action('admin_menu', array($this, 'remove_admin_menu_items'), 999);
        add_action('wp_dashboard_setup', array($this, 'remove_dashboard_widgets'), 999);
        add_action('admin_bar_menu', array($this, 'remove_update_notifications'), 999);
    }

    public function remove_dashboard_widgets()
    {
        // Remove dashboard widgets based on settings
        if (isset($this->settings['remove_dashboard_widget_welcome']) && $this->settings['remove_dashboard_widget_welcome']) {
            remove_action('welcome_panel', 'wp_welcome_panel');
        }
        if (isset($this->settings['remove_right_now_widget']) && $this->settings['remove_right_now_widget']) {
            remove_meta_box('dashboard_right_now', 'dashboard', 'normal');
        }
        if (isset($this->settings['remove_dashboard_widget_quick_draft']) && $this->settings['remove_dashboard_widget_quick_draft']) {
            remove_meta_box('dashboard_quick_press', 'dashboard', 'side');
        }
        if (isset($this->settings['remove_dashboard_widget_activity']) && $this->settings['remove_dashboard_widget_activity']) {
            remove_meta_box('dashboard_activity', 'dashboard', 'normal');
        }
        if (isset($this->settings['remove_wpcom_blog_widget']) && $this->settings['remove_wpcom_blog_widget']) {
            remove_meta_box('dashboard_primary', 'dashboard', 'side');
        }
    }

    public function remove_admin_menu_items()
    {
        // Remove admin menu items based on settings
        if (isset($this->settings['remove_menu_dashboard']) && $this->settings['remove_menu_dashboard']) {
            remove_menu_page('index.php'); // Dashboard
        }
        if (isset($this->settings['remove_menu_posts']) && $this->settings['remove_menu_posts']) {
            remove_menu_page('edit.php'); // Posts
        }
        if (isset($this->settings['remove_menu_media']) && $this->settings['remove_menu_media']) {
            remove_menu_page('upload.php'); // Media
        }
        if (isset($this->settings['remove_menu_pages']) && $this->settings['remove_menu_pages']) {
            remove_menu_page('edit.php?post_type=page'); // Pages
        }
        if (isset($this->settings['remove_menu_comments']) && $this->settings['remove_menu_comments']) {
            remove_menu_page('edit-comments.php'); // Comments
        }
        if (isset($this->settings['remove_menu_appearance']) && $this->settings['remove_menu_appearance']) {
            remove_menu_page('themes.php'); // Appearance
        }
        if (isset($this->settings['remove_menu_plugins']) && $this->settings['remove_menu_plugins']) {
            remove_menu_page('plugins.php'); // Plugins
        }
        if (isset($this->settings['remove_menu_users']) && $this->settings['remove_menu_users']) {
            remove_menu_page('users.php'); // Users
        }
        if (isset($this->settings['remove_menu_tools']) && $this->settings['remove_menu_tools']) {
            remove_menu_page('tools.php'); // Tools
        }
        if (isset($this->settings['remove_menu_settings']) && $this->settings['remove_menu_settings']) {
            remove_menu_page('options-general.php'); // Settings
        }
    }

    public function remove_update_notifications()
    {
        // Remove update notifications based on settings
        if (isset($this->settings['remove_update_notifications']) && $this->settings['remove_update_notifications']) {
            remove_action('admin_notices', 'update_nag', 3);
        }
    }
}

new UNCLT_Admin();
