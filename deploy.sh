#!/usr/bin/env sh

# abort on errors
set -e

echo "Github User name"
read -r GITHUB_USER

echo "Github Repository name"
read -r GITHUB_REPOSITORY

echo "Commit message"
read -r COMMIT_MESSAGE

git clone https://github.com/"${GITHUB_USER}"/"${GITHUB_REPOSITORY}"
cd ./assets/admin && npm install && npm run build
cd -
cd ./assets/website && npm install && npm run build
cd -
mkdir -p ./"${GITHUB_REPOSITORY}"/assets/admin/ && cp -r -p ./assets/admin/dist ./"${GITHUB_REPOSITORY}"/assets/admin/
mkdir -p ./"${GITHUB_REPOSITORY}"/assets/website/ && cp -r -p ./assets/website/dist ./"${GITHUB_REPOSITORY}"/assets/website/
cp -r ./plugin ./"${GITHUB_REPOSITORY}"/
cp ./composer.json ./"${GITHUB_REPOSITORY}"/
cp ./*.php ./"${GITHUB_REPOSITORY}"/
cd ./"${GITHUB_REPOSITORY}"
composer install --no-dev
rm composer.json
rm composer.lock

git add .
git commit -m "${COMMIT_MESSAGE}"
git push origin main

cd -
rm -rf ./assets/admin/dist ./assets/website/dist
rm -rf "${GITHUB_REPOSITORY}"
echo "done"
