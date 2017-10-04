#! /bin/bash

cd `dirname "$0"`
git fetch
DIFF=$(git diff master origin/master)
echo "$DIFF"

if [ -z "$DIFF" ]; then
    exit 1
else
    exit 0
fi
exit 1
