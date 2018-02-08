#! /bin/bash

if [ $# != 2 ]; then
    exit 1
fi

cp $1/admin_files/* `pwd`/ || exit 1
if [ ! -d $2/edbox ];
then
    echo "dir not exist"
    mkdir $2/edbox
fi
if [ -f "$2/edbox/conf/PHP/bucket.conf.php" ]
then
    echo "file exist"
    rsync -av --exclude='conf/PHP/bucket.conf.php' $1/edbox/* $2/edbox || exit 1
else
    echo "file not exist"
    cp -r $1/edbox/* $2/edbox || exit 1
fi;
cp $1/sudoers /etc/sudoers.d/box_administration || exit 1
exit 0
