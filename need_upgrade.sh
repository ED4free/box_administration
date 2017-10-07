#! /bin/bash

cd `dirname "$0"`
BRANCH_NAME=$(git branch | grep \* | cut -d ' ' -f 2)
git fetch origin "$BRANCH_NAME"
DIFF=$(git diff $BRANCH_NAME origin/$BRANCH_NAME)
echo "$DIFF"

if [ -z "$DIFF" ]; then
    exit 1
else
    exit 0
fi
exit 1
