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

# abort on errors
set -e

# prepare place for build.
plugin_name="${GITHUB_REPOSITORY}"
progress_message "Preparing build directory..."
rm -rf ./"$plugin_name" ./"$plugin_name".zip
mkdir ./"$plugin_name"

# start build process.
progress_message "Updating asset file..."
sed -i '9s#//# #' ./assets/admin/src/main.js
sed -i '10s|^|// |' ./assets/admin/src/main.js

# build assets
progress_message "Building admin template..."
npm --prefix ./assets/admin run build
npm --prefix ./assets/website run build

# copy all files for production
progress_message "Copying files for production..."
cp -R ./composer.json ./*.php src assets/admin/dist assets/website/dist readme.txt ./"$plugin_name"/ --parents

# Install PHP dependencies
progress_message "Installing PHP dependencies..."
composer install --working-dir=./"$plugin_name" --no-dev
rm ./"$plugin_name"/composer.json
rm ./"$plugin_name"/composer.lock

# Remove dev data
progress_message "Removing dev data..."
sed -i '13d;67,70d;73d' ./"$plugin_name"/src/Admin/Menu.php
sed -i '15d;34,38d;42d;57-59d' ./"$plugin_name"/src/Web/Shortcodes.php
sed -i '11d;28,30d' ./"$plugin_name"/src/Traits/Api.php
sed -i '48d' ./"$plugin_name"/"$plugin_name.php"
sed -i '1d' ./"$plugin_name"/assets/admin/dist/index.html

# Add index.php to every directory
progress_message "Adding index.php to every directory..."
find ./"$plugin_name" -type d -exec sh -c "echo '<?php // silence' > {}/index.php" \;

# pusing new update to github. you can push svn too.
progress_message "Pushing new update to github..."
cd ./"$plugin_name"
git add .
git commit -m "${COMMIT_MESSAGE}"
git push origin "${GITHUB_REPOSITORY_BRANCH}"
cd -

# Revert changes for production
progress_message "Reverting changes..."
rm -rf ./assets/dist
rm -rf ./"$plugin_name"
sed -i '10s#//# #' ./assets/admin/src/main.js
sed -i '9s|^|// |' ./assets/admin/src/main.js

# Completion message
progress_message "Build process completed successfully."
exit
