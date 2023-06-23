#!/usr/bin/env sh

# abort on errors
set -e

# prepare place for build.
plugin_name="$(basename $PWD)"
rm -rf ./"$plugin_name" ./"$plugin_name".zip
mkdir ./"$plugin_name"

# start build process.
cd ./assets/admin && npm run build
cd -
cd ./assets/website && npm run build
cd -
cp -R ./composer.json ./*.php src assets/admin/dist assets/website/dist readme.txt ./"$plugin_name"/ --parents
cd ./"$plugin_name"
composer install --no-dev
rm composer.json
rm composer.lock
sed -i "s/MODE', 'dev' );/MODE', 'prod' );/g" "$plugin_name.php"
find . -type d -exec sh -c "echo '<?php // silence' > {}/index.php" \;


cd -
# change this path according to your os.
"C:\Program Files\7-Zip\7z.exe" a ./"$plugin_name".zip ./"$plugin_name"/*
rm -rf ./"$plugin_name"
exit
