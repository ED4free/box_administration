#! /bin/bash

if [ $# != 2 ]; then
    exit 1
fi

cp $1/admin_files/* `pwd`/ || exit 1
cp -r $1/edbox $2 || exit 1
cp $1/sudoers /etc/ || exit 1
exit 0
