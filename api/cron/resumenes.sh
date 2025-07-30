#!/bin/bash

# Obtener la ruta del directorio del script
SCRIPT_DIR=$(dirname "$(realpath "$0")")

# Construir la ruta a la carpeta api
API_DIR="$SCRIPT_DIR/../../api"

#!/usr/bin/bash
/usr/bin/php $API_DIR/cron/resumenes.php
/usr/bin/php $API_DIR/cron/resumenesRF.php
/usr/bin/php $API_DIR/cron/TokenUserBingo.php
/usr/bin/php $API_DIR/cron/cronCampaignsEmailOptimove.php
