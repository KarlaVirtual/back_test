#!/bin/bash

# Obtener la ruta del directorio del script
SCRIPT_DIR=$(dirname "$(realpath "$0")")

# Construir la ruta a la carpeta api
API_DIR="$SCRIPT_DIR/../../api"

#!/usr/bin/bash
/usr/bin/php $API_DIR/cron/resumenesPaso2.php
#/usr/bin/php $API_DIR/cron/resumenesBodega.php
/usr/bin/php $API_DIR/cron/resumenesBodegaFlujoCaja.php
/usr/bin/php $API_DIR/cron/sportbookSEO.php
#/usr/bin/php $API_DIR/cron/cierresdecaja.php
/usr/bin/php $API_DIR/cron/resumenesPaso3.php
/usr/bin/php $API_DIR/cron/resumenesPaso4.php
/usr/bin/php $API_DIR/cron/resumenesComisiones.php
/usr/bin/php $API_DIR/cron/resumenes-2AM.php
/usr/bin/php $API_DIR/cron/resumenesPaso2-2AM.php
/usr/bin/php $API_DIR/cron/resumenesBodega-2AM.php
/usr/bin/php $API_DIR/cron/resumenesBodegaFlujoCaja-2AM.php
/usr/bin/php $API_DIR/cron/resumenesPaso3-2AM.php
/usr/bin/php $API_DIR/cron/resumenesPaso4-2AM.php
/usr/bin/php $API_DIR/cron/resumenesComisiones-2AM.php
/usr/bin/php $API_DIR/cron/cronResumenesReferidos.php
/usr/bin/php $API_DIR/cron/cronExpiracionReferidos.php