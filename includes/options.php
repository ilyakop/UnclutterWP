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
        add_settings_section('general_section',  __('General Settings', 'unclutterwp'), array($this, 'render_general_section'), $this->options_page_slug);

        // Add settings for each method
        $this->add_checkbox_setting(
            'clean_head',
            __('Clean Head', 'unclutterwp'),
            __('Removes unnecessary elements from the <head> section.', 'unclutterwp')
        );
        $this->add_checkbox_setting(
            'disable_json_api',
            __('Disable JSON API', 'unclutterwp'),
            __('Disables the JSON API, reducing potential security risks and server load.', 'unclutterwp')
        );
        $this->add_checkbox_setting(
            'disable_rest_api',
            __('Disable REST API', 'unclutterwp'),
            __('Completely disables the REST API, improving security and reducing unnecessary requests.', 'unclutterwp')
        );
        $this->add_checkbox_setting(
            'disable_trackbacks',
            __('Disable Trackbacks', 'unclutterwp'),
            __('Removes trackback functionality, which is often considered obsolete and can be a target for spam.', 'unclutterwp')
        );
        $this->add_checkbox_setting(
            'disable_pingback',
            __('Disable Pingback', 'unclutterwp'),
            __('Disables the XML-RPC pingback functionality, reducing the risk of DDoS attacks and improving security.', 'unclutterwp')
        );
        $this->add_checkbox_setting(
            'disable_emojis',
            __('Disable Emojis', 'unclutterwp'),
            __('Removes the emoji script and styles, reducing page load times and improving performance.', 'unclutterwp')
        );
        $this->add_checkbox_setting(
            'remove_translations',
            __('Remove Translations', 'unclutterwp'),
            __('Disables loading of translations, saving server resources if multilingual support is not needed.', 'unclutterwp')
        );
        $this->add_checkbox_setting(
            'remove_wptexturize',
            __('Remove wptexturize', 'unclutterwp'),
            __('Disables the wptexturize functionality, which automatically formats certain characters, improving performance.', 'unclutterwp')
        );
        $this->add_checkbox_setting(
            'disable_embeds',
            __('Disable Embeds', 'unclutterwp'),
            __('Disables oEmbed functionality, reducing potential security risks and improving page load times.', 'unclutterwp')
        );
        $this->add_checkbox_setting(
            'remove_gutenberg_styles',
            __('Remove Gutenberg Styles', 'unclutterwp'),
            __('Removes styles added by Gutenberg, reducing page load times if not using the block editor.', 'unclutterwp')
        );
        $this->add_checkbox_setting(
            'remove_xmlrpc',
            __('Remove XMLRPC', 'unclutterwp'),
            __('Blocks access to the XML-RPC file, preventing potential security vulnerabilities and DDoS attacks.', 'unclutterwp')
        );


        // Add more settings for other methods as needed
    }

    private function add_checkbox_setting($id, $label, $description)
    {
        add_settings_field(
            $id,
            $label,
            array($this, 'render_checkbox_field'),
            $this->options_page_slug,
            'general_section',
            array('id' => $id, 'label' => $label, 'description' => $description)
        );
    }

    public function render_options_page()
    {
?>
        <div class="wrap">
            <h1>UnclutterWP Settings</h1>
            <form method="post" action="options.php">
                <?php
                settings_fields($this->settings_group);
                do_settings_sections($this->options_page_slug);
                submit_button();
                ?>
            </form>
        </div>
    <?php
    }

    public function render_general_section()
    {
        echo '<p>';
        _e('Configure general settings to unclutter your WordPress site.', 'your-text-domain');
        echo '</p>';
    }

    public function render_checkbox_field($args)
    {
        $value = get_option($this->prefix . 'settings', array());
        $checked = isset($value[$args['id']]) ? checked(1, $value[$args['id']], false) : '';
    ?>
        <label for="<?php echo esc_attr($args['id']); ?>">
            <input type="checkbox" id="<?php echo esc_attr($args['id']); ?>" name="<?php echo esc_attr($this->prefix . 'settings'); ?>[<?php echo esc_attr($args['id']); ?>]" value="1" <?php echo $checked; ?>>
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
}

new UNCLT_Options();
