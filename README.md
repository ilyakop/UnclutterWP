# UnclutterWP - Core Web Vitals & Performance Optimizer

UnclutterWP is a WordPress plugin focused on improving Core Web Vitals and PageSpeed by optionally removing unnecessary frontend resources and legacy WordPress overhead.

## Core Web Vitals Focus

UnclutterWP helps optimize:
- **LCP** (Largest Contentful Paint)
- **CLS** (Cumulative Layout Shift)
- **INP** (Interaction to Next Paint)

## Features

### Core Web Vitals Optimization
- Improve Core Web Vitals with optional frontend controls
- Reduce render-blocking scripts and styles
- Remove unnecessary WordPress assets
- Reduce avoidable frontend JavaScript overhead
- Optimize script loading behavior

### Optimization Controls
- **Clean Head Metadata**
- **Disable JSON API Links**
- **Disable REST API**
- **Disable Trackbacks**
- **Disable Pingback**
- **Disable Emojis**
- **Remove Translations**
- **Remove wptexturize**
- **Disable Embeds**
- **Remove Gutenberg Styles**
- **Remove XMLRPC Pingback Calls**

### Tools
- Optional dashboard widget cleanup
- Optional admin menu cleanup
- Optional update notification cleanup

## Admin Pages

- **Optimization Settings**: Controls grouped into LCP, CLS, INP, and Advanced Optimization
- **Tools**: Optional wp-admin cleanup controls

## Installation

1. Download the plugin zip file.
2. Log in to your WordPress admin panel.
3. Go to `Plugins > Add New`.
4. Click `Upload Plugin`, select the zip file, and click `Install Now`.
5. Activate the plugin.

## Usage

After activation:
1. Open `UnclutterWP Performance` in the WordPress admin menu.
2. Visit `Optimization Settings`.
3. Enable the relevant options for your site.
4. Test your pages and Core Web Vitals metrics after each change.

## License

This project is licensed under GPLv2 or later.
