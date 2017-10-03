#! /bin/bash

if [ $# != 2 ]
then
    exit 1
fi

gsutil cp "$1" "$2" || exit 1
exit 0
