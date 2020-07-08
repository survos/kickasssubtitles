#!/usr/bin/env bash

set -ex

################################################################################
#
# Runs `npm` inside `/tmp` folder and copies resulting `node_modules` into
# current folder. This solves some problems with `npm` and VirtualBox
# shared-folders.
#
# Usage: `vnpm install` or `vnpm update`
#
################################################################################

CURRENT_DIR=$PWD
TMP_DIR="$(mktemp -d)"

cd ${TMP_DIR}
cp ${CURRENT_DIR}/package.json ${TMP_DIR}/package.json 2>/dev/null
cp ${CURRENT_DIR}/package-lock.json ${TMP_DIR}/package-lock.json 2>/dev/null
npm "$@"
rm -rf ${CURRENT_DIR}/node_modules
mv ${TMP_DIR}/node_modules ${CURRENT_DIR}/node_modules
mv ${TMP_DIR}/package-lock.json ${CURRENT_DIR}/package-lock.json 2>/dev/null
rm -rf ${TMP_DIR}
cd $CURRENT_DIR
