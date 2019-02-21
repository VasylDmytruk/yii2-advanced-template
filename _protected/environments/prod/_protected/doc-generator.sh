#!/usr/bin/env bash

vendor/bin/apidoc guide guide backend/views/doc/pages --interactive=0
php yii doc/move-assets @backend/views/doc/pages @approot/admin/doc/