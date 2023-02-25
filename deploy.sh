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
cd ./assets/admin && npm install && npm run build
cd -
cd ./assets/website && npm install && npm run build
cd -
cp -R ./composer.json ./*.php plugin assets/admin/dist assets/website/dist ./"$GITHUB_REPOSITORY"/ --parents
cd ./"${GITHUB_REPOSITORY}"
composer install --no-dev
rm composer.json
rm composer.lock

git add .
git commit -m "${COMMIT_MESSAGE}"
git push origin "${GITHUB_REPOSITORY_BRANCH}"

cd -
rm -rf ./assets/admin/dist ./assets/website/dist
rm -rf "${GITHUB_REPOSITORY}"
echo "done"
