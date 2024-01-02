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
npm --prefix ./assets run build

# copy all files for production
progress_message "Copying files for production..."
cp -R ./composer.json ./*.php src assets/dist  readme.txt ./"$plugin_name"/ --parents

# Install PHP dependencies
progress_message "Installing PHP dependencies..."
composer install --working-dir=./"$plugin_name" --no-dev
rm ./"$plugin_name"/composer.json

# Remove dev data
progress_message "Removing dev data..."
sed -i '67,70d;' ./"$plugin_name"/src/Controllers/AdminController.php
rm ./"$plugin_name"/assets/dist/index.html

# Add index.php to every directory
progress_message "Adding index.php to every directory..."
find ./"$plugin_name" -type d -exec sh -c "echo '<?php // silence' > {}/index.php" \;
./vendor/bin/phpcbf ./"$plugin_name"/src --standard=WordPress-Extra -s --report=source
# Create zip archive
progress_message "Creating zip archive..."
"C:\Program Files\7-Zip\7z.exe" a ./"$plugin_name".zip ./"$plugin_name"/*

# Revert changes for production
progress_message "Reverting changes..."
rm -rf ./assets/dist
rm -rf ./"$plugin_name"

# Completion message
progress_message "Build process completed successfully."
exit
