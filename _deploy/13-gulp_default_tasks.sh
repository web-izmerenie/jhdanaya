#!/bin/bash
if [ -z "$PARENT_DEPLOY_SCRIPT" ]; then YOUR_SUBJECT="./_deploy/$(basename "$0")" WD="$(dirname "$0")/../" ../deploy.sh; exit "$?"; fi

info "Starting default gulp tasks"
if ./gulp --production; then
	info_inline "Default gulp tasks status:"; ok;
else err; fi
