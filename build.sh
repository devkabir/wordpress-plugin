#!/usr/bin/env sh

# abort on errors
set -e

plugin_name="$(basename $PWD)"
rm -rf ./"$plugin_name"  ./"$plugin_name".zip
mkdir ./"$plugin_name"
cd ./assets/admin && npm run build
cd -
cd ./assets/website  && npm run build
cd -
cp -R ./composer.json ./*.php src assets/admin/dist assets/website/dist ./"$plugin_name"/ --parents
cd ./"$plugin_name"
composer install --no-dev
rm composer.json
rm composer.lock
search='define( '\''PluginPackage\\MODE'\'', '\''dev'\'' );'
replace='define( '\''PluginPackage\\MODE'\'', '\''prod'\'' );'
file='./plugin.php'

# Check if the file exists
if [ -f "$file" ]; then
  # Perform the search and replace using sed
  sed -i "s|$search|$replace|g" "$file"
fi
find . -type d -exec sh -c "echo '<?php // silence' > {}/index.php" \;
cd -
"C:\Program Files\7-Zip\7z.exe" a ./"$plugin_name".zip ./"$plugin_name"/*
rm -rf ./"$plugin_name"
exit
