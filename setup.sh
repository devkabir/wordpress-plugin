#!/bin/bash

# Colors for output
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
RED='\033[0;31m'
NC='\033[0m' # No Color

echo -e "${BLUE}========================================${NC}"
echo -e "${BLUE}WordPress Plugin Template Setup${NC}"
echo -e "${BLUE}========================================${NC}"
echo ""

# Get the directory name
DIR_NAME=$(basename "$(pwd)")

# Generate plugin information from directory name
echo -e "${YELLOW}Generating plugin information from directory: ${DIR_NAME}${NC}"
echo ""

# Convert directory name to plugin slug (lowercase with hyphens)
PLUGIN_SLUG=$(echo "$DIR_NAME" | tr '[:upper:]' '[:lower:]' | sed 's/_/-/g')

# Convert slug to plugin name (capitalize each word)
PLUGIN_NAME=$(echo "$PLUGIN_SLUG" | sed 's/-/ /g' | sed 's/\b\(.\)/\u\1/g')

# Convert slug to namespace (PascalCase with backslashes)
NAMESPACE=$(echo "$PLUGIN_SLUG" | sed 's/-/ /g' | sed 's/\b\(.\)/\u\1/g' | sed 's/ /\\/g')

# Generate default values
PLUGIN_DESC="A WordPress plugin"
PLUGIN_URI="https://example.com/plugins/${PLUGIN_SLUG}"
AUTHOR_NAME="Your Name"
AUTHOR_EMAIL="your.email@example.com"
AUTHOR_URI="https://example.com"

# Derive additional values
CONSTANT_PREFIX=$(echo "$NAMESPACE" | sed 's/\([A-Z]\)/_\1/g' | sed 's/^_//' | tr '[:lower:]' '[:upper:]')
YEAR=$(date +%Y)

# Display generated values
echo -e "${GREEN}Generated plugin information:${NC}"
echo "  Plugin Name: $PLUGIN_NAME"
echo "  Plugin Slug: $PLUGIN_SLUG"
echo "  Namespace: $NAMESPACE"
echo "  Constant Prefix: $CONSTANT_PREFIX"
echo ""

echo ""
echo -e "${BLUE}Updating files...${NC}"

# Update plugin.php
if [ -f "plugin.php" ]; then
    sed -i "s/Your Plugin Name/$PLUGIN_NAME/g" plugin.php
    sed -i "s|https://example.com/plugins/your-plugin|$PLUGIN_URI|g" plugin.php
    sed -i "s/A brief description of what your plugin does\./$PLUGIN_DESC/g" plugin.php
    sed -i "s/Your Name/$AUTHOR_NAME/g" plugin.php
    sed -i "s|https://example.com|$AUTHOR_URI|g" plugin.php
    sed -i "s/your-plugin-slug/$PLUGIN_SLUG/g" plugin.php
    sed -i "s/Your_Plugin_Name/$NAMESPACE/g" plugin.php
    sed -i "s/YOUR_PLUGIN/${CONSTANT_PREFIX}/g" plugin.php
    echo -e "${GREEN}✓${NC} Updated plugin.php"
fi

# Update uninstall.php
if [ -f "uninstall.php" ]; then
    sed -i "s/Your_Plugin_Name/$NAMESPACE/g" uninstall.php
    sed -i "s/YOUR_PLUGIN/${CONSTANT_PREFIX}/g" uninstall.php
    sed -i "s/your-plugin-slug/$PLUGIN_SLUG/g" uninstall.php
    echo -e "${GREEN}✓${NC} Updated uninstall.php"
fi

# Update composer.json
if [ -f "composer.json" ]; then
    sed -i "s/\"name\": \"devkabir\/wordpress-plugin\"/\"name\": \"$(echo $AUTHOR_EMAIL | cut -d'@' -f1)\/$PLUGIN_SLUG\"/g" composer.json
    sed -i "s/A WordPress plugin boilerplate\/template with modular architecture/$PLUGIN_DESC/g" composer.json
    sed -i "s/Your Name/$AUTHOR_NAME/g" composer.json
    sed -i "s/your\.email@example\.com/$AUTHOR_EMAIL/g" composer.json
    sed -i "s/YourPlugin/$NAMESPACE/g" composer.json
    echo -e "${GREEN}✓${NC} Updated composer.json"
fi

# Update README.md
if [ -f "README.md" ]; then
    sed -i "s/# Your Plugin Name/# $PLUGIN_NAME/g" README.md
    sed -i "s/A brief description of what your plugin does\./$PLUGIN_DESC/g" README.md
    sed -i "s/\[Year\]/$YEAR/g" README.md
    sed -i "s/\[Your Name\]/$AUTHOR_NAME/g" README.md
    echo -e "${GREEN}✓${NC} Updated README.md"
fi

# Update autoload.php
if [ -f "includes/autoload.php" ]; then
    sed -i "s/Your_Plugin_Name/$NAMESPACE/g" includes/autoload.php
    sed -i "s/YOUR_PLUGIN/${CONSTANT_PREFIX}/g" includes/autoload.php
    echo -e "${GREEN}✓${NC} Updated includes/autoload.php"
fi

# Update constants.php
if [ -f "includes/constants.php" ]; then
    sed -i "s/Your_Plugin_Name/$NAMESPACE/g" includes/constants.php
    sed -i "s/YOUR_PLUGIN/${CONSTANT_PREFIX}/g" includes/constants.php
    sed -i "s/your-plugin-slug/$PLUGIN_SLUG/g" includes/constants.php
    echo -e "${GREEN}✓${NC} Updated includes/constants.php"
fi

# Update hooks.php
if [ -f "includes/hooks.php" ]; then
    sed -i "s/Your_Plugin_Name/$NAMESPACE/g" includes/hooks.php
    sed -i "s/YOUR_PLUGIN/${CONSTANT_PREFIX}/g" includes/hooks.php
    echo -e "${GREEN}✓${NC} Updated includes/hooks.php"
fi

# Update filters.php
if [ -f "includes/filters.php" ]; then
    sed -i "s/Your_Plugin_Name/$NAMESPACE/g" includes/filters.php
    sed -i "s/YOUR_PLUGIN/${CONSTANT_PREFIX}/g" includes/filters.php
    echo -e "${GREEN}✓${NC} Updated includes/filters.php"
fi

# Update all PHP files in subdirectories
find includes/Admin includes/Common includes/Frontend -type f -name "*.php" 2>/dev/null | while read file; do
    sed -i "s/Your_Plugin_Name/$NAMESPACE/g" "$file"
    sed -i "s/YOUR_PLUGIN/${CONSTANT_PREFIX}/g" "$file"
    sed -i "s/your-plugin-slug/$PLUGIN_SLUG/g" "$file"
done
echo -e "${GREEN}✓${NC} Updated all include files"

# Remove setup script
echo ""
echo -e "${BLUE}Cleaning up...${NC}"
echo -e "${GREEN}✓${NC} Setup complete!"

echo ""
echo -e "${GREEN}========================================${NC}"
echo -e "${GREEN}Setup Complete!${NC}"
echo -e "${GREEN}========================================${NC}"
echo ""
echo -e "${YELLOW}Next steps:${NC}"
echo "1. Review the generated files"
echo "2. Run: composer install"
echo "3. Activate the plugin in WordPress"
echo "4. Start developing!"
echo ""
echo -e "${BLUE}Happy coding!${NC}"
echo ""

# Self-delete
rm -- "$0"
