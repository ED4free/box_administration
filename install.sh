#! /bin/bash

if [ $# != 2 ]; then
    exit 1
fi

echo $1 && echo $2 && exit 0
exit 1
