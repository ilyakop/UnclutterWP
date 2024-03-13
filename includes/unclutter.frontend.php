<?php

class UnclutterWP_Frontend
{
    private $prefix = 'unclutterwp';

    public function __construct()
    {

        $options = get_option($this->prefix . '_settings', array());

        if (!empty($options[$this->prefix . '_clean_head'])) {
            $this->clean_head();
        }

        if (!empty($options[$this->prefix . '_disable_json_api'])) {
            $this->remove_json_api();
        }

        if (!empty($options[$this->prefix . '_disable_rest_api'])) {
            $this->disable_rest_api();
        }

        if (!empty($options[$this->prefix . '_disable_emojis'])) {
            $this->disable_emojis();
        }

        if (!empty($options[$this->prefix . '_disable_embeds'])) {
            $this->disable_embeds();
        }

        if (!empty($options[$this->prefix . '_remove_translations'])) {
            $this->remove_translations();
        }

        if (!empty($options[$this->prefix . '_disable_trackbacks'])) {
            $this->disable_trackbacks();
        }

        if (!empty($options[$this->prefix . '_disable_pingback'])) {
            $this->disable_pingback();
        }

        if (!empty($options[$this->prefix . '_remove_wptexturize'])) {
            $this->remove_wptexturize();
        }

        if (!empty($options[$this->prefix . '_remove_xmlrpc'])) {
            $this->remove_xmlrpc();
        }

        //$this->remove_css_styles(); // Uncomment if needed
    }

    /**
     * Cleans unnecessary elements from the `<head>` section.
     *
     * @since 1.0.0
     */
    public function clean_head()
    {
        remove_action('wp_head', 'rsd_link'); // remove really simple discovery link
        remove_action('wp_head', 'wp_generator'); // remove wordpress version
        remove_action('wp_head', 'feed_links', 2); // remove rss feed links (make sure you add them in yourself if youre using feedblitz or an rss service)
        remove_action('wp_head', 'feed_links_extra', 3); // removes all extra rss feed links
        remove_action('wp_head', 'index_rel_link'); // remove link to index page
        remove_action('wp_head', 'wlwmanifest_link'); // remove wlwmanifest.xml (needed to support windows live writer)
        remove_action('wp_head', 'start_post_rel_link', 10, 0); // remove random post link
        remove_action('wp_head', 'parent_post_rel_link', 10, 0); // remove parent post link
        remove_action('wp_head', 'adjacent_posts_rel_link', 10, 0); // remove the next and previous post links
        remove_action('wp_head', 'adjacent_posts_rel_link_wp_head', 10, 0);
        remove_action('wp_head', 'wp_shortlink_wp_head', 10, 0); // Remove shortlink
        remove_action('template_redirect', 'wp_shortlink_header', 11);
    }

    /**
     * Removes JSON API related links and functionalities.
     *
     * @since 1.0.0
     */
    public function remove_json_api()
    {
        // Remove the REST API lines from the HTML Header.
        remove_action('wp_head', 'rest_output_link_wp_head');
        remove_action('template_redirect', 'rest_output_link_header');
        // Remove the REST API endpoint.    
        remove_action('rest_api_init', 'wp_oembed_register_route');
        // Don't filter oEmbed results.    
        remove_filter('oembed_dataparse', 'wp_filter_oembed_result');
        // Remove oEmbed discovery links.    
        remove_action('wp_head', 'wp_oembed_add_discovery_links');
        // Remove oEmbed-specific JavaScript from the front-end and back-end.    
        remove_action('wp_head', 'wp_oembed_add_host_js');
        // Turn off oEmbed auto discovery.    
        add_filter('embed_oembed_discover', '__return_false');
        // Remove all embeds rewrite rules.
        add_filter(
            'rewrite_rules_array',
            function ($rules) {
                foreach ($rules as $rule => $rewrite) {
                    if (false !== strpos($rewrite, 'embed=true')) {
                        unset($rules[$rule]);
                    }
                }
                return $rules;
            }
        );
    }

    /**
     * Disables the REST API entirely.
     *
     * @since 1.0.0
     */
    public function disable_rest_api()
    {
        // Filters for WP-API version 1.x  
        add_filter('json_enabled', '__return_false');
        add_filter('json_jsonp_enabled', '__return_false');
        // Filters for WP-API version 2.x  
        add_filter('rest_enabled', '__return_false');
        add_filter('rest_jsonp_enabled', '__return_false');
    }

    /**
     * Disables trackbacks functionality.
     *
     * @since 1.0.0
     */
    public function disable_trackbacks()
    {
        add_filter(
            'rewrite_rules_array',
            function ($rules) {
                foreach ($rules as $rule => $rewrite) {
                    if (preg_match('/trackback\/\?\$$/i', $rule)) {
                        unset($rules[$rule]);
                    }
                }
                return $rules;
            }
        );
    }

    /**
     * Disables trackbacks functionality.
     *
     * @since 1.0.0
     */
    public function disable_pingback()
    {
        add_filter(
            'wp_headers',
            function ($headers) {
                if (isset($headers['X-Pingback'])) {
                    unset($headers['X-Pingback']);
                }
                return $headers;
            }
        );

        add_filter(
            'bloginfo_url',
            function ($output, $show) {
                if ($show == 'pingback_url') {
                    $output = '';
                }

                return $output;
            },
            10,
            2
        );

        // pingback hooks
        add_filter('pre_update_default_ping_status', '__return_false');
        add_filter('pre_option_default_ping_status', '__return_zero');
        add_filter('pre_update_default_pingback_flag', '__return_false');
        add_filter('pre_option_default_pingback_flag', '__return_zero');
    }

    /**
     * Disables emojis functionalities.
     *
     * @since 1.0.0
     */
    public function disable_emojis()
    {
        remove_action('init', 'smilies_init');
        remove_filter('wp_mail', 'wp_staticize_emoji_for_email');
        remove_action('wp_head', 'print_emoji_detection_script', 7);
        remove_action('wp_print_styles', 'print_emoji_styles');
        remove_filter('the_content_feed', 'wp_staticize_emoji');
        remove_filter('comment_text_rss', 'wp_staticize_emoji');
        remove_action('admin_print_styles', 'print_emoji_styles');
        remove_action('admin_print_scripts', 'print_emoji_detection_script');
        add_filter('emoji_svg_url', '__return_false');
        add_filter('option_use_smilies', '__return_false', 99, 1);
        add_filter(
            'tiny_mce_plugins',
            function ($plugins) {
                if (is_array($plugins)) {
                    return array_diff($plugins, ['wpemoji']);
                } else {
                    return [];
                }
            }
        );
    }

    /**
     * Disables translation functionalities. They use a LOT of resources.
     *
     * @since 1.0.0
     */
    public function remove_translations()
    {
        // Remove translations - we don't need them. 
        add_filter('override_load_textdomain', '__return_false');
        remove_action('wp_enqueue_scripts', 'wp_localize_jquery_ui_datepicker');
        remove_action('admin_enqueue_scripts', 'wp_localize_jquery_ui_datepicker');
    }

    /**
     * Disables the `wptexturize` functionality.
     *
     * @since 1.0.0
     */
    public function remove_wptexturize()
    {
        add_filter('run_wptexturize', '__return_false');
    }

    /**
     * Disables embeds functionality. They use a LOT of resources.
     *
     * @since 1.0.0
     */
    public function disable_embeds()
    {
        function deregister_embed_scripts()
        {
            wp_dequeue_script('wp-embed');
            wp_deregister_script('wp-embed');
        }
        add_action('wp_print_scripts', 'deregister_embed_scripts');
        // Remove some oEmbed features - they're slow!
        add_filter(
            'tiny_mce_plugins',
            function ($plugins) {
                return array_diff($plugins, ['wpembed']);
            }
        );
    }

    /**
     * Removes Gutenberg styles.
     *
     * @since 1.0.0
     */
    public function remove_gutenberg_styles()
    {
        global $wp_styles;
        foreach ($wp_styles->queue as $key => $handle) {
            if (strpos($handle, 'wp-block-') === 0) {
                wp_dequeue_style($handle);
            }
        }
        wp_dequeue_style('global-styles');
        wp_dequeue_style('core-block-supports');
        wp_dequeue_style('wp-block-library');
        wp_dequeue_style('wp-block-library-theme');
        wp_deregister_style('wp-block-library');
    }


    /**
     * Removes XMLRPC call.
     *
     * @since 1.0.0
     */
    public function remove_xmlrpc()
    {
        add_action(
            'xmlrpc_call',
            function ($action) {
                if ('pingback.ping' == $action) {
                    wp_die(__('403 Permission Denied'), __('Permission Denied'), ['response' => 403]);
                }
            }
        );
    }


    /**
     * Remove CSS styles.
     *
     * @since 1.0.0
     */
    public function remove_css_styles()
    {
        add_filter('show_recent_comments_widget_style', '__return_false');
    }
}

new UnclutterWP_Frontend();
