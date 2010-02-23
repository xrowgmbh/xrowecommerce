#!/bin/sh

SOURCE=$1
DESTINATION=$2
EXCLUDE_FILES="--exclude .svn --exclude .cache --exclude pack_and_publish.bat"

tar -cz --exclude .svn --exclude .cache --exclude pack_and_publish.bat --exclude createzpkg.sh -f $DESTINATION.ezpkg $SOURCE