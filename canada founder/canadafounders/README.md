# CanadaFounders Theme

Welcome to the CanadaFounders WordPress theme! This theme is designed to support the CanadaFounders community, providing a platform for founders, entrepreneurs, and business professionals across Canada.

## Features

- **Responsive Design**: The theme is fully responsive, ensuring a seamless experience on all devices.
- **Custom Post Types**: Supports custom post types for Members, Events, and Partners.
- **One-Click Demo Import**: Easily import demo content to get started quickly.
- **Editable Pages**: All demo pages are editable using the WordPress block editor.

## Installation

1. Upload the `canadafounders` theme folder to the `/wp-content/themes/` directory.
2. Activate the theme through the 'Themes' menu in WordPress.
3. Install and activate the CanadaFounders Demo Import plugin for demo content.

### Packaging for WordPress uploads

When creating a ZIP for the WordPress uploader, do not archive the project root or the `wp-content` folder. Instead, zip only the actual theme/plugin folder so the ZIP root contains the required files:

- Theme: `canadafounders/wp-content/themes/canadafounders` -> ZIP should contain `style.css` at the root of the archive.
- Plugin: `canadafounders/wp-content/plugins/canadafounders-demo-import` -> ZIP should contain `canadafounders-demo-import.php` at the root of the archive.

You can generate the installable ZIPs with:

```powershell
powershell -ExecutionPolicy Bypass -File .\scripts\build-wordpress-zips.ps1
```

This creates the files in `dist/` with the correct structure for WordPress installation.

## Usage

After activating the theme, navigate to the CanadaFounders Demo Import section in the WordPress admin to install demo content. This will set up the necessary pages, posts, and settings to get your site up and running.

## Customization

You can customize the theme using the WordPress Customizer. Adjust colors, typography, and layout settings to match your brand.

## Support

For support, please reach out through the support forum or contact the theme developer directly.

Thank you for choosing CanadaFounders! We hope you enjoy building your community with this theme.