#!/usr/bin/env sh

# abort on errors
set -e

echo "Github User name"
read -r GITHUB_USER

echo "Github Repository name"
read -r GITHUB_REPOSITORY

echo "Github Repository branch name"
read -r GITHUB_REPOSITORY_BRANCH

echo "Commit message"
read -r COMMIT_MESSAGE

git clone https://github.com/"${GITHUB_USER}"/"${GITHUB_REPOSITORY}"
cd ./assets/admin && npm run build
cd -
cd ./assets/website && npm run build
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

git add .
git commit -m "${COMMIT_MESSAGE}"
git push origin "${GITHUB_REPOSITORY_BRANCH}"

cd -
rm -rf ./assets/admin/dist ./assets/website/dist
rm -rf "${GITHUB_REPOSITORY}"
echo "done"
exit
