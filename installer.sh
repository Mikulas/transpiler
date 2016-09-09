#!/usr/bin/env bash
set -euvo pipefail
IFS=$'\n\t'

VERSION="v1.1.2"

TARGET_NAME="php-transpiler"
TARGET_DIR="/usr/local/bin"
TARGET="$TARGET_DIR/$TARGET_NAME"

mkdir -p "$TARGET_DIR"

curl --location "https://github.com/Mikulas/transpiler/releases/download/$VERSION/transpiler.phar" --output "$TARGET"
chmod a+x "$TARGET"

export PATH="$PATH:$TARGET_DIR"
echo 'PATH="$PATH:'"$TARGET_DIR"'"' >> ~/.bashrc

"$TARGET_NAME" --version
