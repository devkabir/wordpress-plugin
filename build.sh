#!/usr/bin/env sh

# Function to display progress messages
progress_message() {
  local message="$1"

  # Define color codes
  local color_reset="\033[0m"
  local color_green="\033[32m"

  # Print the colorized message
  echo -e "[$(date +'%Y-%m-%d %H:%M:%S')] ${color_green}${message}${color_reset}"
}

# abort on errors
set -e

# prepare place for build.
plugin_name="$(basename $PWD)"
progress_message "Preparing build directory..."
rm -rf ./"$plugin_name" ./"$plugin_name".zip
mkdir ./"$plugin_name"


# build assets
progress_message "Building admin template..."
npm --prefix ./assets/admin run build
npm --prefix ./assets/website run build

# copy all files for production
progress_message "Copying files for production..."
cp -R ./composer.json ./*.php src assets/admin/dist assets/website/dist readme.txt ./"$plugin_name"/ --parents

# Install PHP dependencies
progress_message "Installing PHP dependencies..."
composer install --working-dir=./"$plugin_name" --no-dev
rm ./"$plugin_name"/composer.json
rm ./"$plugin_name"/composer.lock

# Remove dev data
progress_message "Removing dev data..."
sed -i '13d;67,70d;73d' ./"$plugin_name"/src/Admin/Menu.php
sed -i '15d;34,38d;42d;57d' ./"$plugin_name"/src/Web/Shortcodes.php
sed -i '27d' ./"$plugin_name"/src/Traits/Api.php
# sed -i '48d' ./"$plugin_name"/"$plugin_name.php"
sed -i '1,2d' ./"$plugin_name"/assets/admin/dist/index.html
sed -i '1,2d' ./"$plugin_name"/assets/website/dist/form/index.html

# Add index.php to every directory
progress_message "Adding index.php to every directory..."
find ./"$plugin_name" -type d -exec sh -c "echo '<?php // silence' > {}/index.php" \;

# Create zip archive
progress_message "Creating zip archive..."
"C:\Program Files\7-Zip\7z.exe" a ./"$plugin_name".zip ./"$plugin_name"/*

# Revert changes for production
progress_message "Reverting changes..."
rm -rf ./assets/admin/dist
rm -rf ./assets/website/dist
rm -rf ./"$plugin_name"

# Completion message
progress_message "Build process completed successfully."
exit