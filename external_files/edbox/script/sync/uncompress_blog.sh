#! /bin/bash

## This script uncompress downloaded blog repository
## then create it into wordpress bdd and
## move his media files into the wordpress repository

if [ $# != 1 ]
then
    exit 1
fi

CONFIGURATION_FILE_PATH="/var/edbox/conf/sync/sync.conf"
. $CONFIGURATION_FILE_PATH

DEST_REPO_PATH="$ZIP_FILE_REPO_NAME$1"

tar -zxvf "$DEST_REPO_PATH" -C / 1>/dev/null 2>/dev/null || exit 1
rm -rf "$DEST_REPO_PATH" 1>/dev/null 2>/dev/null
exit 0
