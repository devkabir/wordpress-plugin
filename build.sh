#!/bin/bash

# Build script for WordPress Plugin
# Creates a production-ready zip file excluding dev files

set -e

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

# Get plugin directory name (folder name)
PLUGIN_DIR=$(basename "$PWD")

# Get plugin version from main plugin file
PLUGIN_VERSION=$(grep -i "Version:" plugin.php | head -1 | awk -F: '{print $2}' | sed 's/[[:space:]]//g')

# Build directory and file names
BUILD_DIR="build"
PLUGIN_BUILD_DIR="$BUILD_DIR/$PLUGIN_DIR"
ZIP_NAME="${PLUGIN_DIR}-${PLUGIN_VERSION}.zip"

echo -e "${YELLOW}Building WordPress Plugin...${NC}"
echo "Plugin: $PLUGIN_DIR"
echo "Version: $PLUGIN_VERSION"
echo ""

# Clean up previous build
if [ -d "$BUILD_DIR" ]; then
    echo -e "${YELLOW}Cleaning up previous build...${NC}"
    rm -rf "$BUILD_DIR"
fi

# Create build directory
echo -e "${YELLOW}Creating build directory...${NC}"
mkdir -p "$PLUGIN_BUILD_DIR"

# Copy files to build directory, excluding dev files
echo -e "${YELLOW}Copying plugin files...${NC}"
rsync -av \
    --exclude='.git' \
    --exclude='.gitignore' \
    --exclude='.gitattributes' \
    --exclude='node_modules' \
    --exclude='vendor' \
    --exclude='composer.json' \
    --exclude='composer.lock' \
    --exclude='package.json' \
    --exclude='package-lock.json' \
    --exclude='yarn.lock' \
    --exclude='phpunit.xml' \
    --exclude='phpcs.xml' \
    --exclude='.phpcs.xml.dist' \
    --exclude='tests' \
    --exclude='Test' \
    --exclude='.editorconfig' \
    --exclude='.eslintrc' \
    --exclude='.eslintignore' \
    --exclude='webpack.config.js' \
    --exclude='gulpfile.js' \
    --exclude='Gruntfile.js' \
    --exclude='setup.sh' \
    --exclude='build.sh' \
    --exclude='build' \
    --exclude='dist' \
    --exclude='.DS_Store' \
    --exclude='*.log' \
    --exclude='.vscode' \
    --exclude='.idea' \
    --exclude='*.zip' \
    ./ "$PLUGIN_BUILD_DIR/"

# Create zip file
echo -e "${YELLOW}Creating zip file...${NC}"
cd "$BUILD_DIR"
zip -r "../$ZIP_NAME" "$PLUGIN_DIR" -q

# Return to original directory
cd ..

# Clean up build directory
echo -e "${YELLOW}Cleaning up build directory...${NC}"
rm -rf "$BUILD_DIR"

# Display success message
echo ""
echo -e "${GREEN}✓ Build completed successfully!${NC}"
echo -e "${GREEN}✓ Created: $ZIP_NAME${NC}"
echo ""

# Display file size
FILE_SIZE=$(du -h "$ZIP_NAME" | cut -f1)
echo "File size: $FILE_SIZE"
echo ""
echo "The zip file is ready for distribution."
