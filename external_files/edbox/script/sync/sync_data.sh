#! /bin/bash

## This script use values contain in /var/edbox/sync.conf
## to call the web service script
##
## Usage : sync_data.sh function values ...
##
## function: the function to call (upload | download | remove | removeTwinning)
## values: the name of blog to refer

SCRIPT_REPOSITORY_ROOT="/var/edbox/script/sync/"
SYNC_DATA_PATH="/var/edbox/conf/sync.conf"

UPLOAD_SCRIPT_NAME="upload_data.sh"
DOWNLOAD_SCRIPT_NAME="download_data.sh"
REMOVE_SCRIPT_NAME="remove_data.sh"
REMOVE_TWINNIG_SCRIPT_NAME="remove_twinnig_data.sh"
COMPRESS_BLOG_SCRIPT_NAME="compress_blog.sh"
UNCOMPRESS_BLOG_SCRIPT_NAME="uncompress_blog.sh"

InitializeSyncData() {
    . $SYNC_DATA_PATH
}

UploadBlogs() {
    for i in "$@"
    do
	./$COMPRESS_BLOG_SCRIPT_NAME "$i"
    done
    $SCRIPT_REPOSITORY_ROOT$SCRIPT_REPO_NAME/$UPLOAD_SCRIPT_NAME "$@" &&
    exit 0 || exit 1
}

DownloadBlogs() {
    $SCRIPT_REPOSITORY_ROOT$SCRIPT_REPO_NAME/$DOWNLOAD_SCRIPT_NAME "$@" || exit 1
    for i in "$@"
    do
	./$UNCOMPRESS_BLOG_SCRIPT_NAME "$i"
    done
    exit 0
}

RemoveBlogs() {
    $SCRIPT_REPOSITORY_ROOT$SCRIPT_REPO_NAME/$REMOVE_SCRIPT_NAME "$@" &&
    exit 0 || exit 1
}

RemoveTwinningBlogs() {
    $SCRIPT_REPOSITORY_ROOT$SCRIPT_REPO_NAME/$REMOVE_TWINNIG_SCRIPT_NAME "$@" &&
    exit 0 || exit 1
}

if [ $# -lt 2 ]
then
    >&2 echo "Usage : sync_data.sh function values ...

function: the function to call (upload | download | remove | removeTwinning)
values: the name of blog to refer"
    exit 1
elif [ $1 != "upload" ] && [ $1 != "download" ] && [ $1 != "remove" ] && [ $1 != "removeTwinning" ]
then
    >&2 echo "$1 is not a valid function"
    exit 1
fi

# Initialise les configurations de synchronisations
InitializeSyncData

# Verifie la fonction a appeler
if [ $1 == "upload" ]
then
    UploadBlogs "${@:2}"
elif [ $1 == "download" ]
then
    DownloadBlogs "${@:2}"
elif [ $1 == "remove" ]
then
    RemoveBlogs "${@:2}"
elif [ $1 == "removeTwinning" ]
then
    RemoveTwinningBlogs "${@:2}"
fi
exit 1
