#!/bin/sh
set -e
php ${TL_OPT_DIR}/install-db.php ${TL_ROOT} && exec /entrypoint supervisord