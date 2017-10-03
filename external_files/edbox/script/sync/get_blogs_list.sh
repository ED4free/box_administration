#! /bin/bash

gsutil ls -l $1 | sed -e 's/gs:\/\/[^\/]\+\///;s/TOTAL.\+$//;s/ \+/ /g;s/^ //'
