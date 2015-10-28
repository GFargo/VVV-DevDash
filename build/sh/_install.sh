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



echo '----------------------------------------'
echo 'Installing DevDash'
echo '----------------------------------------'

echo $ROOT

echo '----------------------------------------'
echo 'Installing Grunt'
echo '----------------------------------------'

if [[ $(npm_package_is_installed grunt l) != 1 ]]; then
    echo "...grunt not found on system... installing grunt..."
    npm install -g grunt
fi

echo '----------------------------------------'
echo 'Bower Intall & Update'
echo '----------------------------------------'

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


echo '----------------------------------------'
echo 'Installing Files'
echo '----------------------------------------'

echo "...running npm install..."
npm install

echo "...copying dashboard into default root..."
cp [-v] dashboard-custom.php ../dashboard-custom.php