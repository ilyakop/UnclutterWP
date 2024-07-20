<?php

namespace UnclutterWP\Options;

if (!defined('ABSPATH')) {
    exit;
}

use UnclutterWP;

class UNCLT_Options
{
    private $prefix = UnclutterWP\UNCLT_PREFIX;
    private $options_page_slug = 'unclt-settings-page';
    private $settings_group = 'unclt_settings_group';

    public function __construct()
    {
        add_action('admin_menu', array($this, 'add_options_page'));
        add_action('admin_init', array($this, 'register_settings'));
        add_action('admin_enqueue_scripts', array($this, 'enqueue_scripts'));
    }

    public function enqueue_scripts($hook) {
        if ($hook != 'settings_page_'.$this->options_page_slug) {
            return;
        }
        wp_enqueue_script('unclutterwp-admin', plugin_dir_url(UnclutterWP\UNCLT_FILE) . 'includes/js/admin.js', array(), '1.0', true);
    }

    public function add_options_page()
    {
        add_options_page(
            __('UnclutterWP Settings', 'unclutterwp'),
            'UnclutterWP',
            'manage_options',
            $this->options_page_slug,
            array($this, 'render_options_page')
        );
    }

    public function register_settings()
    {
        register_setting($this->settings_group, $this->prefix . 'settings', array($this, 'sanitize_settings'));

        // Add settings sections
        add_settings_section('general', __('Frontend Settings', 'unclutterwp'), array($this, 'render_frontend_section'), $this->options_page_slug . '_frontend');
        add_settings_section('general', __('General Admin Settings', 'unclutterwp'), array($this, 'render_admin_general_section'), $this->options_page_slug . '_admin');
        add_settings_section('widget', __('Remove Dashboard Widgets', 'unclutterwp'), array($this, 'render_admin_widget_section'), $this->options_page_slug . '_admin');
        add_settings_section('menu', __('Remove Menu Items', 'unclutterwp'), array($this, 'render_admin_menu_section'), $this->options_page_slug . '_admin');

        // Add settings for Frontend methods
        $this->add_checkbox_setting(
            'clean_head',
            __('Clean Head', 'unclutterwp'),
            __('Removes unnecessary elements from the <head> section.', 'unclutterwp'),
            'frontend'
        );
        $this->add_checkbox_setting(
            'disable_json_api',
            __('Disable JSON API', 'unclutterwp'),
            __('Disables the JSON API, reducing potential security risks and server load.', 'unclutterwp'),
            'frontend'
        );
        $this->add_checkbox_setting(
            'disable_rest_api',
            __('Disable REST API', 'unclutterwp'),
            __('Completely disables the REST API, improving security and reducing unnecessary requests.', 'unclutterwp'),
            'frontend'
        );
        $this->add_checkbox_setting(
            'disable_trackbacks',
            __('Disable Trackbacks', 'unclutterwp'),
            __('Removes trackback functionality, which is often considered obsolete and can be a target for spam.', 'unclutterwp'),
            'frontend'
        );
        $this->add_checkbox_setting(
            'disable_pingback',
            __('Disable Pingback', 'unclutterwp'),
            __('Disables the XML-RPC pingback functionality, reducing the risk of DDoS attacks and improving security.', 'unclutterwp'),
            'frontend'
        );
        $this->add_checkbox_setting(
            'disable_emojis',
            __('Disable Emojis', 'unclutterwp'),
            __('Removes the emoji script and styles, reducing page load times and improving performance.', 'unclutterwp'),
            'frontend'
        );
        $this->add_checkbox_setting(
            'remove_translations',
            __('Remove Translations', 'unclutterwp'),
            __('Disables loading of translations, saving server resources if multilingual support is not needed.', 'unclutterwp'),
            'frontend'
        );
        $this->add_checkbox_setting(
            'remove_wptexturize',
            __('Remove wptexturize', 'unclutterwp'),
            __('Disables the wptexturize functionality, which automatically formats certain characters, improving performance.', 'unclutterwp'),
            'frontend'
        );
        $this->add_checkbox_setting(
            'disable_embeds',
            __('Disable Embeds', 'unclutterwp'),
            __('Disables oEmbed functionality, reducing potential security risks and improving page load times.', 'unclutterwp'),
            'frontend'
        );
        $this->add_checkbox_setting(
            'remove_gutenberg_styles',
            __('Remove Gutenberg Styles', 'unclutterwp'),
            __('Removes styles added by Gutenberg, reducing page load times if not using the block editor.', 'unclutterwp'),
            'frontend'
        );
        $this->add_checkbox_setting(
            'remove_xmlrpc',
            __('Remove XMLRPC', 'unclutterwp'),
            __('Blocks access to the XML-RPC file, preventing potential security vulnerabilities and DDoS attacks.', 'unclutterwp'),
            'frontend'
        );

        // Add settings for Admin methods
        $this->add_checkbox_setting(
            'remove_wpcom_blog_widget',
            __('WordPress Events & News', 'unclutterwp'),
            __('Removes the "WordPress Events & News" widget from the dashboard.', 'unclutterwp'),
            'admin',
            'widget'
        );

        $this->add_checkbox_setting(
            'remove_right_now_widget',
            __('At a Galnce', 'unclutterwp'),
            __('Removes the "At a Galnce" widget from the dashboard.', 'unclutterwp'),
            'admin',
            'widget'
        );

        $this->add_checkbox_setting(
            'remove_dashboard_widget_welcome',
            __('Welcome', 'unclutterwp'),
            __('Removes the "Welcome" widget from the dashboard.', 'unclutterwp'),
            'admin',
            'widget'
        );

        $this->add_checkbox_setting(
            'remove_dashboard_widget_quick_draft',
            __('Quick Draft', 'unclutterwp'),
            __('Removes the "Quick Draft" widget from the dashboard.', 'unclutterwp'),
            'admin',
            'widget'
        );

        $this->add_checkbox_setting(
            'remove_dashboard_widget_activity',
            __('Activity', 'unclutterwp'),
            __('Removes the "Activity" widget from the dashboard.', 'unclutterwp'),
            'admin',
            'widget'
        );

        /** Menus  */

        $this->add_checkbox_setting(
            'remove_menu_dashboard',
            __('Dashboard Menu', 'unclutterwp'),
            __('Removes the "Dashboard" menu item from the admin sidebar.', 'unclutterwp'),
            'admin',
            'menu'
        );

        $this->add_checkbox_setting(
            'remove_menu_posts',
            __('Posts Menu', 'unclutterwp'),
            __('Removes the "Posts" menu item from the admin sidebar.', 'unclutterwp'),
            'admin',
            'menu'
        );

        $this->add_checkbox_setting(
            'remove_menu_media',
            __('Media Menu', 'unclutterwp'),
            __('Removes the "Media" menu item from the admin sidebar.', 'unclutterwp'),
            'admin',
            'menu'
        );

        $this->add_checkbox_setting(
            'remove_menu_pages',
            __('Pages Menu', 'unclutterwp'),
            __('Removes the "Pages" menu item from the admin sidebar.', 'unclutterwp'),
            'admin',
            'menu'
        );

        $this->add_checkbox_setting(
            'remove_menu_comments',
            __('Comments Menu', 'unclutterwp'),
            __('Removes the "Comments" menu item from the admin sidebar.', 'unclutterwp'),
            'admin',
            'menu'
        );

        $this->add_checkbox_setting(
            'remove_menu_appearance',
            __('Appearance Menu', 'unclutterwp'),
            __('Removes the "Appearance" menu item from the admin sidebar.', 'unclutterwp'),
            'admin',
            'menu'
        );

        $this->add_checkbox_setting(
            'remove_menu_plugins',
            __('Plugins Menu', 'unclutterwp'),
            __('Removes the "Plugins" menu item from the admin sidebar.', 'unclutterwp'),
            'admin',
            'menu'
        );

        $this->add_checkbox_setting(
            'remove_menu_users',
            __('Users Menu', 'unclutterwp'),
            __('Removes the "Users" menu item from the admin sidebar.', 'unclutterwp'),
            'admin',
            'menu'
        );

        $this->add_checkbox_setting(
            'remove_menu_tools',
            __('Tools Menu', 'unclutterwp'),
            __('Removes the "Tools" menu item from the admin sidebar.', 'unclutterwp'),
            'admin',
            'menu'
        );

        $this->add_checkbox_setting(
            'remove_update_notifications',
            __('Update Notifications', 'unclutterwp'),
            __('Hides notifications for WordPress core, theme, and plugin updates.', 'unclutterwp'),
            'admin'
        );

    }

    private function add_checkbox_setting($id, $label, $description, $tab, $section = 'general')
    {   
        add_settings_field(
            $id,
            $label,
            array($this, 'render_checkbox_field'),
            $this->options_page_slug . '_' . $tab,
            $section,
            array('id' => $id, 'label' => $label, 'description' => $description)
        );
    }

    public function render_options_page()
    {
?>
        <div class="wrap">
            <h1><?php esc_html_e('UnclutterWP Settings', 'unclutterwp'); ?></h1>
            <h2 class="nav-tab-wrapper">
                <a href="#frontend" class="nav-tab" id="frontend-tab"><?php esc_html_e('Frontend', 'unclutterwp'); ?></a>
                <a href="#admin" class="nav-tab" id="admin-tab"><?php esc_html_e('Admin', 'unclutterwp'); ?></a>
            </h2>
            <form method="post" action="options.php">
                <?php
                settings_fields($this->settings_group);
                ?>
                <div id="frontend" class="tab-content">
                    <?php
                    do_settings_sections($this->options_page_slug . '_frontend');
                    ?>
                </div>
                <div id="admin" class="tab-content">
                    <?php
                    do_settings_sections($this->options_page_slug . '_admin');
                    ?>
                </div>
                <?php
                submit_button();
                ?>
            </form>
        </div>
    <?php
    }

    public function render_frontend_section()
    {
        echo '<p>';
        esc_html_e('Configure general settings to unclutter your WordPress site.', 'unclutterwp');
        echo '</p>';
    }

    public function render_admin_general_section()
    {
        echo '<p>';
        esc_html_e('Configure admin settings to unclutter your WordPress dashboard.', 'unclutterwp');
        echo '</p>';
    }
    
    public function render_admin_widget_section()
    {
        echo '<p>';
        esc_html_e('Remove Dashboard Widgets you don\'t use.', 'unclutterwp');
        echo '</p>';
    }

    public function render_admin_menu_section()
    {
        echo '<p>';
        esc_html_e('Remove Dashboard Menu Items you don\'t use.', 'unclutterwp');
        echo '</p>';
    }
    
    public function render_checkbox_field($args)
    {
        $value = get_option($this->prefix . 'settings', array());
        $checked = isset($value[$args['id']]) ? checked(1, $value[$args['id']], false) : '';
    ?>
        <label for="<?php echo esc_attr($args['id']); ?>">
            <input type="checkbox" id="<?php echo esc_attr($args['id']); ?>" name="<?php echo esc_attr($this->prefix . 'settings'); ?>[<?php echo esc_attr($args['id']); ?>]" value="1" <?php echo esc_attr($checked); ?>>
            <span class="description"><?php echo esc_html($args['description']); ?></span>
        </label>
    <?php
    }

    public function sanitize_settings($input)
    {
        $sanitized_input = array();
        foreach ($input as $key => $value) {
            $sanitized_input[$key] = isset($value) ? 1 : 0;
        }
        return $sanitized_input;
    }

    public function get_all_settings()
    {
        return get_option($this->prefix . 'settings', array());
    }
}

global $UNCLT_Options;
$UNCLT_Options = new UNCLT_Options();
