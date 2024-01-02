#!/bin/bash

# Get the current directory name
base=${PWD##*/}
# Convert to Pascal case for namespace
namespace=$(sed -E 's/(^|-)([a-zA-Z0-9])/\U\2/g' <<< "$base")
# Convert to Title Case with spaces
title=$(sed 's/-/ /g' <<< "$base")
title=${title^}

if [ -e "plugin.php" ]; then
    mv plugin.php "$base.php"
fi
# Search and replace "Your Plugin Name" with the current directory name in all PHP, JS, JSON, and HTML files except files in the vendor folder inside src directory
find ./ -type f \( -name "*.php" -o -name "*.js" -o -name "*.json" -o -name "*.html" \) -not -path "./vendor/*" -not -path "./assets/node_modules/*" -execdir sed -i "s/Your Plugin Name/${title//\//\\/}/g" {} \;
# Search and replace "your-plugin-name" with the current directory name in all PHP, JS, JSON, and HTML files except files in the vendor folder inside src directory
find ./ -type f \( -name "*.php" -o -name "*.js" -o -name "*.json" -o -name "*.html" \) -not -path "./vendor/*" -not -path "./assets/node_modules/*" -execdir sed -i "s/your-plugin-name/${base//\//\\/}/g" {} \;
# Search and replace "YourPluginName" with the current directory name in all PHP, JS, JSON, and HTML files except files in the vendor folder inside src directory
find ./ -type f \( -name "*.php" -o -name "*.js" -o -name "*.json" -o -name "*.html" \) -not -path "./vendor/*" -not -path "./assets/node_modules/*" -execdir sed -i "s/PluginPackage/${namespace//\//\\/}/g" {} \;
# Search and replace "your_plugin_name" with the current directory name in all PHP, JS, JSON, and HTML files except files in the vendor folder inside src directory
find ./ -type f \( -name "*.php" -o -name "*.js" -o -name "*.json" -o -name "*.html" \) -not -path "./vendor/*" -not -path "./assets/node_modules/*" -execdir sed -i "s/your_plugin_name/${base//\//\\/}/g" {} \;
# run composer dump-autoload
composer dump-autoload
# run npm install on assets dir
npm install --prefix ./assets
# run npm run build on assets dir
npm run build --prefix ./assets
# delete this file
rm install.sh

