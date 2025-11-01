# Your Plugin Name

A brief description of what your plugin does.

## Description

This plugin provides [describe main functionality here]. It is built with a modular architecture following WordPress best practices.

## Creating a New Plugin from this Template

You can create a new plugin from this template using Composer:

```bash
composer create-project devkabir/wordpress-plugin my-new-plugin
```

The setup script will automatically run and prompt you for:
- Plugin Name
- Plugin Slug
- Plugin Description
- Plugin URI
- Author Name
- Author Email
- Author URI
- Package Namespace

All placeholder values in the template will be automatically replaced with your inputs.

### Manual Setup

If you prefer to set up manually or need to run the setup again:

```bash
composer run setup
```

Or run the setup script directly:

```bash
php setup.php
```

## Features

- Modular architecture with separate Admin, Frontend, and Common components
- PSR-4 autoloading
- WordPress coding standards compliant
- Easy customization through hooks and filters
- Clean uninstall process

## Requirements

- WordPress 5.8 or higher
- PHP 7.4 or higher
- Composer (for installation and development)

## Installation

### Via Composer (Recommended)

```bash
composer create-project devkabir/wordpress-plugin my-plugin-name
cd my-plugin-name
composer install
```

### Manual Installation

1. Download the plugin files
2. Upload the plugin folder to `/wp-content/plugins/` directory
3. Activate the plugin through the 'Plugins' menu in WordPress
4. Configure the plugin settings if needed

## File Structure

```
wordpress-plugin/
├── includes/
│   ├── Admin/          # Admin-specific functionality
│   ├── Common/         # Shared functionality
│   ├── Frontend/       # Frontend-specific functionality
│   ├── autoload.php    # Autoloader for plugin classes
│   ├── constants.php   # Plugin constants
│   ├── filters.php     # Filter hooks
│   └── hooks.php       # Action hooks
├── plugin.php          # Main plugin file
├── uninstall.php       # Cleanup on uninstall
├── composer.json       # Composer configuration
├── setup.php           # Interactive setup script (auto-removed after setup)
└── README.md           # This file
```

## Development

### Architecture

This plugin follows a modular architecture with separate components for:
- **Admin**: Backend/admin panel functionality
- **Frontend**: Public-facing features
- **Common**: Shared utilities and helpers

### Hooks and Filters

The plugin provides various hooks and filters for extensibility. See `includes/hooks.php` and `includes/filters.php` for available hooks.

### Autoloading

The plugin uses PSR-4 autoloading. All classes in the `includes/` directory are automatically loaded based on their namespace.

### Development Dependencies

Install development dependencies:

```bash
composer install --dev
```

This includes:
- PHPUnit for testing
- WordPress Coding Standards (WPCS)

## Changelog

### 1.0.0
- Initial release

## License

This plugin is licensed under the GPL v2 or later.

```
Copyright (C) [Year] [Your Name]

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.
```

## Support

For support, please [contact method or issue tracker link].

## Contributing

Contributions are welcome! Please feel free to submit a Pull Request.

## Credits

Developed by [Your Name]
