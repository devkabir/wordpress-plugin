#!/bin/bash

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
progress_message "Replacing placeholders..."
# Get the current directory name
base=${PWD##*/}
# Convert to Pascal case for namespace
namespace=$(sed -E 's/(^|-)([a-zA-Z0-9])/\U\2/g' <<< "$base")
# Convert to Title Case with spaces
title=$(sed 's/-/ /g' <<< "$base")
title=${title^}
object=$(sed 's/-/_/g' <<< "$base")

if [ -e "plugin.php" ]; then
    mv plugin.php "$base.php"
fi
# Search and replace "Your Plugin Name" with the current directory name in all PHP, JS, JSON, and HTML files except files in the vendor folder inside src directory
find ./ -type f \( -name "*.php" -o -name "*.js" -o -name "*.json" -o -name "*.html"  -o -name "*.txt" \) -not -path "./vendor/*" -not -path "./assets/node_modules/*" -execdir sed -i "s/Your Plugin Name/${title//\//\\/}/g" {} \;
# Search and replace "your-plugin-name" with the current directory name in all PHP, JS, JSON, and HTML files except files in the vendor folder inside src directory
find ./ -type f \( -name "*.php" -o -name "*.js" -o -name "*.json" -o -name "*.html" \) -not -path "./vendor/*" -not -path "./assets/node_modules/*" -execdir sed -i "s/your-plugin-name/${base//\//\\/}/g" {} \;
# Search and replace "YourPluginName" with the current directory name in all PHP, JS, JSON, and HTML files except files in the vendor folder inside src directory
find ./ -type f \( -name "*.php" -o -name "*.js" -o -name "*.json" -o -name "*.html" \) -not -path "./vendor/*" -not -path "./assets/node_modules/*" -execdir sed -i "s/PluginPackage/${namespace//\//\\/}/g" {} \;
# Search and replace "your_plugin_name" with the current directory name in all PHP, JS, JSON, and HTML files except files in the vendor folder inside src directory
find ./ -type f \( -name "*.php" -o -name "*.js" -o -name "*.json" -o -name "*.html" -o -name "*.ts" \) -not -path "./vendor/*" -not -path "./assets/node_modules/*" -execdir sed -i "s/your_plugin_name/${object//\//\\/}/g" {} \;
# run composer dump-autoload
progress_message "Running composer dump-autoload..."
composer dump-autoload
# cd into assets
progress_message "Building assets..."
cd ./assets
# run npm install on assets dir
npm install 
# run npm run build on assets dir
npm run build 
# delete this file
progress_message "Deleting install.sh..."
cd ..
rm install.sh

