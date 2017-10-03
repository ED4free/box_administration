#! /bin/bash

if [ $# != 1 ]
then
    exit 1
fi

gsutil rm "$1" || exit 1
exit 0
