#! /bin/bash

git fetch
DIFF=$(git diff master origin/master)

if [ -z "$DIFF" ]; then
    exit 1
else
    exit 0
fi
exit 1
