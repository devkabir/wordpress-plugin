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
cp -R ./composer.json ./*.php plugin assets/admin/dist assets/website/dist ./"$plugin_name"/ --parents
cd ./"$plugin_name"
composer install --no-dev
rm composer.json
rm composer.lock
find . -type d -exec sh -c "echo '<?php // silence' > {}/index.php" \;
cd -
"C:\Program Files\7-Zip\7z.exe" a ./"$plugin_name".zip ./"$plugin_name"/*
rm -rf ./"$plugin_name"
cd ./assets/admin && npm run build:dev
cd -
cd ./assets/website  && npm run build
cd -
exit
