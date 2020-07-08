#!/usr/bin/env bash

set -ex

CURRENT_BRANCH="master"

function split()
{
    SHA1=`splitsh-lite --prefix=$1`
    git push $2 "$SHA1:refs/heads/$CURRENT_BRANCH" -f
}

function remote()
{
    git remote add $1 $2 || true
}

git pull origin $CURRENT_BRANCH

remote open-subtitles git@github.com-grzesw:kickasssubtitles/open-subtitles.git

split 'src/OpenSubtitles' open-subtitles
