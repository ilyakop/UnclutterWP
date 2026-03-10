<?php

namespace UnclutterWP\Options;

if (!defined('ABSPATH')) {
    exit;
}

use UnclutterWP;

class UNCLT_Options
{
    private $prefix = UnclutterWP\UNCLT_PREFIX;
    private $settings_group = 'unclt_settings_group';
    private $options_page_slug = 'unclt-settings-page';
    private $tools_page_slug = 'unclt-tools-page';
    private $registered_setting_keys = array();

    public function __construct()
    {
        add_action('admin_menu', array($this, 'add_admin_pages'));
        add_action('admin_init', array($this, 'register_settings'));
        add_action('admin_enqueue_scripts', array($this, 'enqueue_scripts'));
    }

    public function enqueue_scripts($hook)
    {
        $is_settings_page = false !== strpos($hook, $this->options_page_slug);
        $is_tools_page = false !== strpos($hook, $this->tools_page_slug);

        if (!$is_settings_page && !$is_tools_page) {
            return;
        }

        wp_enqueue_style(
            'unclutterwp-admin',
            plugin_dir_url(UnclutterWP\UNCLT_FILE) . 'includes/css/admin.css',
            array('dashicons'),
            '1.0'
        );

        if ($is_settings_page) {
            wp_enqueue_script(
                'unclutterwp-admin',
                plugin_dir_url(UnclutterWP\UNCLT_FILE) . 'includes/js/admin.js',
                array(),
                '1.0',
                true
            );
        }
    }

    public function add_admin_pages()
    {
        add_menu_page(
            __('UnclutterWP', 'unclutterwp'),
            __('UnclutterWP', 'unclutterwp'),
            'manage_options',
            $this->options_page_slug,
            array($this, 'render_options_page'),
            'dashicons-performance',
            80
        );

        add_submenu_page(
            $this->options_page_slug,
            __('Optimization', 'unclutterwp'),
            __('Optimization', 'unclutterwp'),
            'manage_options',
            $this->options_page_slug,
            array($this, 'render_options_page')
        );

        add_submenu_page(
            $this->options_page_slug,
            __('Tools', 'unclutterwp'),
            __('Tools', 'unclutterwp'),
            'manage_options',
            $this->tools_page_slug,
            array($this, 'render_tools_page')
        );
    }

    public function register_settings()
    {
        register_setting($this->settings_group, $this->prefix . 'settings', array($this, 'sanitize_settings'));

        // Core Web Vitals optimization sections.
        add_settings_section(
            'lcp',
            $this->build_section_title('dashicons-performance', __('LCP Optimization', 'unclutterwp')),
            array($this, 'render_lcp_section'),
            $this->options_page_slug . '_cwv'
        );
        add_settings_section(
            'cls',
            $this->build_section_title('dashicons-align-wide', __('CLS Optimization', 'unclutterwp')),
            array($this, 'render_cls_section'),
            $this->options_page_slug . '_cwv'
        );
        add_settings_section(
            'inp',
            $this->build_section_title('dashicons-controls-repeat', __('INP Optimization', 'unclutterwp')),
            array($this, 'render_inp_section'),
            $this->options_page_slug . '_cwv'
        );
        add_settings_section(
            'advanced',
            $this->build_section_title('dashicons-admin-tools', __('Advanced Optimization', 'unclutterwp')),
            array($this, 'render_advanced_section'),
            $this->options_page_slug . '_advanced'
        );

        // Tools sections.
        add_settings_section(
            'admin_general',
            $this->build_section_title('dashicons-admin-generic', __('Admin Cleanup Tools', 'unclutterwp')),
            array($this, 'render_admin_general_section'),
            $this->tools_page_slug
        );
        add_settings_section(
            'widget',
            $this->build_section_title('dashicons-screenoptions', __('Dashboard Widget Cleanup', 'unclutterwp')),
            array($this, 'render_admin_widget_section'),
            $this->tools_page_slug
        );
        add_settings_section(
            'menu',
            $this->build_section_title('dashicons-menu-alt', __('Admin Menu Cleanup', 'unclutterwp')),
            array($this, 'render_admin_menu_section'),
            $this->tools_page_slug
        );

        // LCP optimization settings.
        $this->add_checkbox_setting(
            'clean_head',
            __('Clean Head Metadata', 'unclutterwp'),
            __('Removes unnecessary <head> elements to reduce front-end overhead and support faster LCP.', 'unclutterwp'),
            'cwv',
            'lcp'
        );
        $this->add_checkbox_setting(
            'disable_emojis',
            __('Disable Emojis', 'unclutterwp'),
            __('Disable emojis (reduces unnecessary requests that affect Core Web Vitals and LCP).', 'unclutterwp'),
            'cwv',
            'lcp'
        );
        $this->add_checkbox_setting(
            'remove_gutenberg_styles',
            __('Remove Gutenberg Styles', 'unclutterwp'),
            __('Removes block editor styles on the front-end to lower CSS weight and improve LCP opportunities.', 'unclutterwp'),
            'cwv',
            'lcp'
        );

        // CLS optimization settings.
        $this->add_checkbox_setting(
            'disable_embeds',
            __('Disable Embeds', 'unclutterwp'),
            __('Disables embed scripts to reduce late-loading UI elements that can contribute to CLS.', 'unclutterwp'),
            'cwv',
            'cls'
        );
        $this->add_checkbox_setting(
            'remove_wptexturize',
            __('Remove wptexturize', 'unclutterwp'),
            __('Disables wptexturize to reduce text-processing overhead and keep output handling predictable for layout stability.', 'unclutterwp'),
            'cwv',
            'cls'
        );

        // INP optimization settings.
        $this->add_checkbox_setting(
            'disable_json_api',
            __('Disable JSON API Links', 'unclutterwp'),
            __('Removes JSON API discovery output from the front-end to reduce non-essential processing and support INP.', 'unclutterwp'),
            'cwv',
            'inp'
        );
        $this->add_checkbox_setting(
            'disable_rest_api',
            __('Disable REST API', 'unclutterwp'),
            __('Disables REST API endpoints when not needed, reducing background request handling and JS-driven overhead for INP.', 'unclutterwp'),
            'cwv',
            'inp'
        );

        // Advanced optimization settings.
        $this->add_checkbox_setting(
            'disable_trackbacks',
            __('Disable Trackbacks', 'unclutterwp'),
            __('Disables legacy trackbacks to reduce unnecessary requests and background processing.', 'unclutterwp'),
            'advanced',
            'advanced'
        );
        $this->add_checkbox_setting(
            'disable_pingback',
            __('Disable Pingback', 'unclutterwp'),
            __('Disables XML-RPC pingback functionality to reduce server load and avoid avoidable external requests.', 'unclutterwp'),
            'advanced',
            'advanced'
        );
        $this->add_checkbox_setting(
            'remove_translations',
            __('Remove Translations', 'unclutterwp'),
            __('Disables translation loading when multilingual output is not required, reducing runtime overhead.', 'unclutterwp'),
            'advanced',
            'advanced'
        );
        $this->add_checkbox_setting(
            'remove_xmlrpc',
            __('Remove XMLRPC Pingback Calls', 'unclutterwp'),
            __('Blocks XML-RPC pingback calls to limit avoidable server work and reduce attack surface.', 'unclutterwp'),
            'advanced',
            'advanced'
        );

        // Tools page settings (existing admin functionality).
        $this->add_checkbox_setting(
            'remove_wpcom_blog_widget',
            __('WordPress Events & News', 'unclutterwp'),
            __('Removes the "WordPress Events & News" widget from the dashboard.', 'unclutterwp'),
            'tools',
            'widget'
        );
        $this->add_checkbox_setting(
            'remove_right_now_widget',
            __('At a Glance', 'unclutterwp'),
            __('Removes the "At a Glance" widget from the dashboard.', 'unclutterwp'),
            'tools',
            'widget'
        );
        $this->add_checkbox_setting(
            'remove_dashboard_widget_welcome',
            __('Welcome', 'unclutterwp'),
            __('Removes the "Welcome" widget from the dashboard.', 'unclutterwp'),
            'tools',
            'widget'
        );
        $this->add_checkbox_setting(
            'remove_dashboard_widget_quick_draft',
            __('Quick Draft', 'unclutterwp'),
            __('Removes the "Quick Draft" widget from the dashboard.', 'unclutterwp'),
            'tools',
            'widget'
        );
        $this->add_checkbox_setting(
            'remove_dashboard_widget_activity',
            __('Activity', 'unclutterwp'),
            __('Removes the "Activity" widget from the dashboard.', 'unclutterwp'),
            'tools',
            'widget'
        );

        $this->add_checkbox_setting(
            'remove_menu_dashboard',
            __('Dashboard Menu', 'unclutterwp'),
            __('Removes the "Dashboard" menu item from the admin sidebar.', 'unclutterwp'),
            'tools',
            'menu'
        );
        $this->add_checkbox_setting(
            'remove_menu_posts',
            __('Posts Menu', 'unclutterwp'),
            __('Removes the "Posts" menu item from the admin sidebar.', 'unclutterwp'),
            'tools',
            'menu'
        );
        $this->add_checkbox_setting(
            'remove_menu_media',
            __('Media Menu', 'unclutterwp'),
            __('Removes the "Media" menu item from the admin sidebar.', 'unclutterwp'),
            'tools',
            'menu'
        );
        $this->add_checkbox_setting(
            'remove_menu_pages',
            __('Pages Menu', 'unclutterwp'),
            __('Removes the "Pages" menu item from the admin sidebar.', 'unclutterwp'),
            'tools',
            'menu'
        );
        $this->add_checkbox_setting(
            'remove_menu_comments',
            __('Comments Menu', 'unclutterwp'),
            __('Removes the "Comments" menu item from the admin sidebar.', 'unclutterwp'),
            'tools',
            'menu'
        );
        $this->add_checkbox_setting(
            'remove_menu_appearance',
            __('Appearance Menu', 'unclutterwp'),
            __('Removes the "Appearance" menu item from the admin sidebar.', 'unclutterwp'),
            'tools',
            'menu'
        );
        $this->add_checkbox_setting(
            'remove_menu_plugins',
            __('Plugins Menu', 'unclutterwp'),
            __('Removes the "Plugins" menu item from the admin sidebar.', 'unclutterwp'),
            'tools',
            'menu'
        );
        $this->add_checkbox_setting(
            'remove_menu_users',
            __('Users Menu', 'unclutterwp'),
            __('Removes the "Users" menu item from the admin sidebar.', 'unclutterwp'),
            'tools',
            'menu'
        );
        $this->add_checkbox_setting(
            'remove_menu_tools',
            __('Tools Menu', 'unclutterwp'),
            __('Removes the "Tools" menu item from the admin sidebar.', 'unclutterwp'),
            'tools',
            'menu'
        );
        $this->add_checkbox_setting(
            'remove_update_notifications',
            __('Update Notifications', 'unclutterwp'),
            __('Hides notifications for WordPress core, theme, and plugin updates.', 'unclutterwp'),
            'tools',
            'admin_general'
        );
    }

    private function add_checkbox_setting($id, $label, $description, $tab, $section = 'general')
    {
        // Track all known option keys so sanitization can safely merge page-specific saves.
        $this->registered_setting_keys[$id] = true;

        $page = '';
        if ('cwv' === $tab) {
            $page = $this->options_page_slug . '_cwv';
        } elseif ('advanced' === $tab) {
            $page = $this->options_page_slug . '_advanced';
        } elseif ('tools' === $tab) {
            $page = $this->tools_page_slug;
        }

        add_settings_field(
            $id,
            $label,
            array($this, 'render_checkbox_field'),
            $page,
            $section,
            array(
                'id' => $id,
                'label' => $label,
                'description' => $description,
            )
        );
    }

    public function render_options_page()
    {
        ?>
        <div class="wrap unclt-admin-page">
            <h1><?php esc_html_e('UnclutterWP Optimization Settings', 'unclutterwp'); ?></h1>
            <h2 class="nav-tab-wrapper">
                <a href="#core-web-vitals" class="nav-tab" id="core-web-vitals-tab"><?php esc_html_e('Core Web Vitals Optimization', 'unclutterwp'); ?></a>
                <a href="#advanced-optimization" class="nav-tab" id="advanced-optimization-tab"><?php esc_html_e('Advanced Optimization', 'unclutterwp'); ?></a>
            </h2>
            <form method="post" action="options.php">
                <?php settings_fields($this->settings_group); ?>
                <div id="core-web-vitals" class="tab-content">
                    <?php do_settings_sections($this->options_page_slug . '_cwv'); ?>
                </div>
                <div id="advanced-optimization" class="tab-content">
                    <?php do_settings_sections($this->options_page_slug . '_advanced'); ?>
                </div>
                <?php submit_button(); ?>
            </form>
        </div>
        <?php
    }

    public function render_tools_page()
    {
        ?>
        <div class="wrap unclt-admin-page">
            <h1><?php esc_html_e('UnclutterWP Tools', 'unclutterwp'); ?></h1>
            <p><?php esc_html_e('Use these optional tools to simplify wp-admin and reduce interface noise for your team.', 'unclutterwp'); ?></p>
            <form method="post" action="options.php">
                <?php
                settings_fields($this->settings_group);
                do_settings_sections($this->tools_page_slug);
                submit_button();
                ?>
            </form>
        </div>
        <?php
    }

    public function render_lcp_section()
    {
        echo '<p>';
        esc_html_e('Configure options that can reduce render-blocking front-end resources and support better Largest Contentful Paint (LCP).', 'unclutterwp');
        echo '</p>';
    }

    public function render_cls_section()
    {
        echo '<p>';
        esc_html_e('Configure options that help reduce front-end behavior that may contribute to layout shifts (CLS).', 'unclutterwp');
        echo '</p>';
    }

    public function render_inp_section()
    {
        echo '<p>';
        esc_html_e('Configure options that reduce non-essential script and request overhead to support better interaction responsiveness (INP).', 'unclutterwp');
        echo '</p>';
    }

    public function render_advanced_section()
    {
        echo '<p>';
        esc_html_e('Advanced WordPress optimization controls for legacy features and endpoint cleanup.', 'unclutterwp');
        echo '</p>';
    }

    public function render_admin_general_section()
    {
        echo '<p>';
        esc_html_e('Optional admin controls for reducing dashboard noise and update banner interruptions.', 'unclutterwp');
        echo '</p>';
    }

    public function render_admin_widget_section()
    {
        echo '<p>';
        esc_html_e('Hide dashboard widgets your team does not use.', 'unclutterwp');
        echo '</p>';
    }

    public function render_admin_menu_section()
    {
        echo '<p>';
        esc_html_e('Hide admin menu items that are not needed for your workflow.', 'unclutterwp');
        echo '</p>';
    }

    public function render_checkbox_field($args)
    {
        $value = get_option($this->prefix . 'settings', array());
        $checked = isset($value[$args['id']]) ? checked(1, $value[$args['id']], false) : '';
        ?>
        <label for="<?php echo esc_attr($args['id']); ?>">
            <input
                type="hidden"
                name="<?php echo esc_attr($this->prefix . 'settings'); ?>[<?php echo esc_attr($args['id']); ?>]"
                value="0"
            >
            <input
                type="checkbox"
                id="<?php echo esc_attr($args['id']); ?>"
                name="<?php echo esc_attr($this->prefix . 'settings'); ?>[<?php echo esc_attr($args['id']); ?>]"
                value="1"
                <?php echo esc_attr($checked); ?>
            >
            <span class="description"><?php echo esc_html($args['description']); ?></span>
        </label>
        <?php
    }

    public function sanitize_settings($input)
    {
        $sanitized_input = get_option($this->prefix . 'settings', array());

        if (!is_array($sanitized_input)) {
            $sanitized_input = array();
        }

        if (!is_array($input)) {
            return $sanitized_input;
        }

        $allowed_keys = !empty($this->registered_setting_keys)
            ? array_keys($this->registered_setting_keys)
            : array_keys($input);

        foreach ($allowed_keys as $key) {
            if (!array_key_exists($key, $input)) {
                continue;
            }

            $sanitized_input[$key] = ('1' === (string) $input[$key]) ? 1 : 0;
        }

        return $sanitized_input;
    }

    public function get_all_settings()
    {
        return get_option($this->prefix . 'settings', array());
    }

    private function build_section_title($icon_class, $title)
    {
        return sprintf(
            '<span class="dashicons %1$s" aria-hidden="true"></span><span class="unclt-section-title-text">%2$s</span>',
            esc_attr($icon_class),
            esc_html($title)
        );
    }
}

global $UNCLT_Options;
$UNCLT_Options = new UNCLT_Options();
