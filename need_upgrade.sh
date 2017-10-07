#! /bin/bash

BRANCH_NAME=$(git branch | grep \* | cut -d ' ' -f 2)
echo "$BRANCH_NAME";
cd `dirname "$0"`
git fetch
DIFF=$(git diff $BRANCH_NAME origin/$BRANCH_NAME)
echo "$DIFF"

if [ -z "$DIFF" ]; then
    exit 1
else
    exit 0
fi
exit 1
