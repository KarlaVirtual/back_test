#!/bin/bash

# Obtener la ruta del directorio del script
SCRIPT_DIR=$(dirname "$(realpath "$0")")

# Construir la ruta a la carpeta api
API_DIR="$SCRIPT_DIR/../../api"

#!/usr/bin/bash
#/usr/bin/php $API_DIR/cron/revisarPagosLPG.php
/usr/bin/php $API_DIR/cron/resumenesAutoexclusiones.php
#/usr/bin/php $API_DIR/cron/verificarUsuarioHistorial.php
/usr/bin/php $API_DIR/cron/cronMensajes.php
/usr/bin/php $API_DIR/cron/cronInterbankHora.php
/usr/bin/php $API_DIR/cron/cronNotifyBono.php
#/usr/bin/php $API_DIR/cron/cronDataCompleta2.php