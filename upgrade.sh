#! /bin/bash

cd `dirname "$0"`
git pull origin master && exit 0
exit 1
