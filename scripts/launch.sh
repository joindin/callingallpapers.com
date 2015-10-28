#!/bin/bash

set -x
set -e

if [ "x$BUILD_NUMBER" == "x" ]
then
	echo "Please set the BUILD_NUMBER environment var"
	exit 1
fi

if [ "x$TARGET_BASE" == "x" ]
then
	echo "Please set the TARGET_BASE environment var"
	exit 1
fi

bower install
npm install
grunt

mkdir -p $TARGET_BASE/$BUILD_NUMBER

cp -R * $TARGET_BASE/$BUILD_NUMBER/

# Prep the new symlink
rm -f www.new &&
  ln -s $BUILD_NUMBER $TARGET_BASE/www.new &&
  mv -Tf $TARGET_BASE/www.new $TARGET_BASE/www

# Clean up old releases
for i in $(ls -1 $TARGET_BASE | grep "^[0-9]" | sort -n | head -n -6)
do
	rm -rf $TARGET_BASE/$i
done
