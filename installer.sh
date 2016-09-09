#!/usr/bin/env bash
set -euvo pipefail
IFS=$'\n\t'

VERSION="v1.1.1"

TARGET_DIR="/usr/local/bin"
TARGET="$TARGET_DIR/php-transpiler"

mkdir -p "$TARGET_DIR"

curl --location "https://github.com/Mikulas/transpiler/releases/download/$VERSION/transpiler.phar" --output "$TARGET"
chmod a+x "$TARGET"

export PATH="$PATH:$TARGET_DIR"
echo 'PATH="$PATH:'"$TARGET_DIR"'"' >> ~/.bashrc
