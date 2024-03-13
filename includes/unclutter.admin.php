<?php

// Remove unnecessary dashboard widgets
add_action('wp_dashboard_setup', function () {
    //remove_meta_box('dashboard_quick_press', 'dashboard', 'side'); //Quick Press widget
    //remove_meta_box('dashboard_recent_drafts', 'dashboard', 'side'); //Recent Drafts
    //remove_meta_box('dashboard_activity', 'dashboard', 'normal'); //Activity
    remove_meta_box('dashboard_primary', 'dashboard', 'side'); //WordPress.com Blog
    remove_meta_box('dashboard_secondary', 'dashboard', 'side'); //Other WordPress News
    remove_meta_box('dashboard_right_now', 'dashboard', 'normal'); //Right Now
    remove_action('welcome_panel', 'wp_welcome_panel');
});
