#!/bin/bash
# Store ROOT Directory
ROOT=$(pwd)


# return 1 if local npm package is installed at ./node_modules, else 0
# example
# echo "gruntacular : $(npm_package_is_installed gruntacular)"
function npm_package_is_installed {
  # set to 1 initially
  local return_=1
  # set to 0 if not found
  if [ $2 != "g" ]; then
    #default to local modules
    npm list | grep $1 >/dev/null 2>&1 || { local return_=0; }
  else
    #global modules
    npm list -g --depth=0 | grep $1 >/dev/null 2>&1 || { local return_=0; }
  fi
  # return value
  echo "$return_"
}

clear #clean window

DIR="$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd )"


cat config/banner.txt

echo 'Installing DevDash into' $ROOT '... '

echo
echo '----'
echo 'Installing Grunt'
echo '----'

if [[ $(npm_package_is_installed grunt l) != 1 ]]; then
    echo "...grunt not found on system... installing grunt..."
    npm install -g grunt
else
    echo "...grunt already installed... skipping installation..."
fi

echo
echo '----'
echo 'Bower Intall & Update'
echo '----'

if [[ $(npm_package_is_installed bower g) ]]; then
    echo "...bower already installed... skipping installation..."
    echo "...running update..."
    bower update
else
    echo "...bower not found on system... installing bower..."
    npm install -g bower
    echo "...running update..."
    bower update
fi

echo
echo '----'
echo 'Installing Files'
echo '----'

echo "...running npm install..."
npm install

echo "...copying dashboard into default root..."
cp -v ./dashboard-custom.php ../dashboard-custom.php

echo
echo '----'
echo 'Creating Cache'
echo '----'

echo "...removing old cache dir..."
rm -rf .devdash-cache

echo "...creating cache dir..."
mkdir .devdash-cache

echo "...setting up cache perms..."
chmod 755 .devdash-cache




