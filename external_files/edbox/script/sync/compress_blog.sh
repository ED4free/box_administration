#! /bin/bash

## This script create a new repository containing
## the blog contents on .html file and all of his media
## giving by sql reauest
## Then it compress it

if [ $# != 1 ]
then
    exit 1
fi

CONFIGURATION_FILE_PATH="/var/edbox/conf/sync/sync.conf"
. $CONFIGURATION_FILE_PATH
DEST_REPO_PATH="$ZIP_FILE_REPO_NAME$1"

tar -zcvf "$DEST_REPO_PATH$ZIP_FILE_EXTENSION" "$DEST_REPO_PATH" 2>/dev/null 1>/dev/null || exit 1
rm -rf "$DEST_REPO_PATH" || exit 1
exit 0
