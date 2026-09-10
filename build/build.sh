#!/usr/bin/env bash
set -euo pipefail
ROOT="$(cd "$(dirname "$0")/.." && pwd)"
VERSION="$(tr -d '\r\n' < "$ROOT/VERSION")"
DIST="$ROOT/dist"
WORK="$ROOT/build/.work"
php "$ROOT/tests/smoke.php"
rm -rf "$DIST" "$WORK"
mkdir -p "$DIST" "$WORK/component" "$WORK/package"
cp -R "$ROOT/component/." "$WORK/component/"
find "$WORK/component" -type f -exec touch -t 198001010000 {} +
(cd "$WORK/component" && find . -type f -print0 | sort -z | xargs -0 zip -X -q "$DIST/com_xdecarofeedback_${VERSION}.zip")
cp "$ROOT/package/pkg_xdecarofeedback.xml" "$WORK/package/pkg_xdecarofeedback.xml"
cp "$ROOT/package/script.php" "$WORK/package/script.php"
cp "$DIST/com_xdecarofeedback_${VERSION}.zip" "$WORK/package/com_xdecarofeedback.zip"
find "$WORK/package" -type f -exec touch -t 198001010000 {} +
(cd "$WORK/package" && find . -type f -print0 | sort -z | xargs -0 zip -X -q "$DIST/pkg_xdecarofeedback_${VERSION}.zip")
(cd "$DIST" && sha256sum "com_xdecarofeedback_${VERSION}.zip" "pkg_xdecarofeedback_${VERSION}.zip" > SHA256SUMS.txt)
printf 'Built Feedback %s\n' "$VERSION"
