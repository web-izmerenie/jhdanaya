#!/bin/bash
if [ -z "$PARENT_DEPLOY_SCRIPT" ]; then YOUR_SUBJECT="./_deploy/$(basename "$0")" WD="$(dirname "$0")/../" ../deploy.sh; exit "$?"; fi

info_inline "Creating symbolic link to gulp-cli"
rm ./gulp &>/dev/null
if ln -s ./node_modules/.bin/gulp ./gulp &>/dev/null; then ok; else err; fi
