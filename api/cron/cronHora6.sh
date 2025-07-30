#!/bin/bash

# Obtener la ruta del directorio del script
SCRIPT_DIR=$(dirname "$(realpath "$0")")

# Construir la ruta a la carpeta api
API_DIR="$SCRIPT_DIR/../../api"

#!/usr/bin/bash
/usr/bin/php $API_DIR/cron/resumenes-6AM.php
/usr/bin/php $API_DIR/cron/resumenesBodegaFlujoCaja6AM.php

/usr/bin/php $API_DIR/cron/resumenesPaso2-6AM.php

/usr/bin/php $API_DIR/cron/resumenesComisiones-6AM.php

#/usr/bin/php $API_DIR/cron/resumenesBodega-6AM.php

/usr/bin/php $API_DIR/cron/resumenesPaso3-6AM.php
/usr/bin/php $API_DIR/cron/resumenesPaso4-6AM.php
#/usr/bin/php $API_DIR/cron/cronACEPSA.php
