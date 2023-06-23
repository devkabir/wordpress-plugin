#!/usr/bin/env sh

# abort on errors
set -e
# Collecting github repo info.
echo "Github User name"
read -r GITHUB_USER

echo "Github Repository name"
read -r GITHUB_REPOSITORY

echo "Github Repository branch name"
read -r GITHUB_REPOSITORY_BRANCH

echo "Commit message"
read -r COMMIT_MESSAGE

git clone https://github.com/"${GITHUB_USER}"/"${GITHUB_REPOSITORY}"

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

# pusing new update.
git add .
git commit -m "${COMMIT_MESSAGE}"
git push origin "${GITHUB_REPOSITORY_BRANCH}"

# reset to dev.
cd -
rm -rf ./assets/admin/dist ./assets/website/dist
rm -rf "${GITHUB_REPOSITORY}"
echo "done"
exit
