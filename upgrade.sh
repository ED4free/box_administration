#! /bin/bash

cd `dirname "$0"`
BRANCH_NAME=$(git branch | grep \* | cut -d ' ' -f 2)
git pull origin "$BRANCH_NAME" || exit 1
exit 0
