=== UnclutterWP - Core Web Vitals & Performance Optimizer ===

Contributors: unclutterwp
Tags: core web vitals, pagespeed, performance optimization, faster wordpress, reduce cls, improve lcp
Requires at least: 6.0
Tested up to: 6.9
Stable tag: 1.0.1
License: GPLv2 or later

Improve Core Web Vitals by removing unnecessary WordPress frontend resources, reducing render-blocking overhead, and streamlining script behavior.

== Description ==

UnclutterWP is a Core Web Vitals and PageSpeed optimization plugin for WordPress.

Its primary goal is to help site owners improve:
- LCP (Largest Contentful Paint)
- CLS (Cumulative Layout Shift)
- INP (Interaction to Next Paint)

It does this by giving you optional controls to remove unnecessary frontend bloat, disable legacy WordPress features, and reduce unneeded requests.

== Features ==

- Improve Core Web Vitals through optional frontend optimization controls
- Reduce render-blocking scripts and styles
- Remove unused WordPress assets and discovery links
- Disable unnecessary frontend features
- Reduce layout shift risk from non-essential scripts
- Optimize script loading behavior by removing unneeded runtime features

Existing feature controls include:
- **Clean Head Metadata**: Removes unnecessary elements from the `<head>` output.
- **Disable JSON API Links**: Removes JSON and oEmbed discovery output when not needed.
- **Disable REST API**: Disables REST API functionality for sites that do not require it.
- **Disable Trackbacks**: Disables legacy trackback behavior.
- **Disable Pingback**: Disables pingback headers and XML-RPC pingback behavior.
- **Disable Emojis**: Removes WordPress emoji scripts and styles.
- **Remove Translations**: Disables translation loading where multilingual support is not required.
- **Remove wptexturize**: Disables wptexturize processing.
- **Disable Embeds**: Disables embed functionality and `wp-embed` scripts.
- **Remove Gutenberg Styles**: Removes front-end block library styles.
- **Remove XMLRPC Pingback Calls**: Blocks XML-RPC pingback calls.
- **Admin Cleanup Tools**: Optional dashboard widget and admin menu cleanup controls.

== Installation ==

1. Download the plugin zip file.
2. Log in to your WordPress admin panel.
3. Go to Plugins > Add New.
4. Click "Upload Plugin", select the zip file, and click "Install Now".
5. Activate the plugin.
6. Open `UnclutterWP Performance` in the admin menu.

== Screenshots ==

1. Optimization Settings grouped by LCP, CLS, INP, and Advanced Optimization
2. Tools page for optional admin cleanup controls

== FAQ ==

= How does this plugin improve Core Web Vitals? =
It removes or disables optional WordPress frontend resources and legacy features that can add extra requests, CSS, or JavaScript overhead.

= Will this break my site? =
All controls are optional. Enable settings incrementally and test pages after each change, especially if your site depends on REST API, embeds, or XML-RPC integrations.

= Can I still use the plugin for admin cleanup? =
Yes. Existing dashboard widget and admin menu cleanup features are still available under the Tools page.

== Changelog ==

= 1.0 =
- Initial release
