<?php namespace backend\dto;

use Backend\mysql\ItTicketEncInfo1;
use Backend\mysql\PreUsuarioSorteoMySqlDAO;
use Backend\mysql\SorteoDetalle2MySqlDAO;
use Backend\mysql\SorteoInterno2MySqlDAO;
use Backend\mysql\TransjuegoInfoMySqlDAO;
use Backend\mysql\UsuarioMensajeMySqlDAO;
use Backend\mysql\UsuarioSorteo2MySqlDAO;
use Exception;


/**
 * Clase 'SorteoInterno'
 *
 * Esta clase provee una manera de instanciar un objeto de transferencia de datos (dto)
 * para la tabla 'SorteoInterno'
 *
 * Ejemplo de uso:
 * $SorteoInterno = new SorteoInterno();
 *
 *
 * @package ninguno
 * @author Daniel Tamayo <it@virtualsoft.tech>
 * @version ninguna
 * @access public
 * @see no
 *
 */

 class SorteoInterno2{
    /**
     * Representación de la columna 'sorteo2Id' de la tabla 'SorteoInterno'
     *
     * @var string
     */
    var $sorteoId;

     /**
     * Representación de la columna 'fecha_crea' de la tabla 'SorteoInterno'
     *
     * @var string
     */

    var $fechaInicio;


     /**
     * Representación de la columna 'fecha_fin' de la tabla 'SorteoInterno'
     *
     * @var string
     */

     var $fechaFin;

      /**
     * Representación de la columna 'descripcion' de la tabla 'SorteoInterno'
     *
     * @var string
     */

     var $descripcion;


      /**
     * Representación de la columna 'tipo' de la tabla 'SorteoInterno'
     *
     * @var string
     */


     var $tipo;


     /**
     * Representación de la columna 'nombre' de la tabla 'SorteoInterno'
     *
     * @var string
     */


      var $nombre;

      /**
     * Representación de la columna 'estado' de la tabla 'SorteoInterno'
     *
     * @var string
     */


      var $estado;


     /**
     * Representación de la columna 'mandante' de la tabla 'SorteoInterno'
     *
     * @var string
     */


      var $mandante;

      /**
     * Representación de la columna 'fecha_crea' de la tabla 'SorteoInterno'
     *
     * @var string
     */


      var $fechaCrea;


     /**
     * Representación de la columna 'usumodif_id' de la tabla 'SorteoInterno'
     *
     * @var string
     */

       var $usumodifId;


    /**
     * Representación de la columna 'fecha_modif' de la tabla 'SorteoInterno'
     *
     * @var string
     */

     var $fechaModif;



     /**
     * Representación de la columna 'condicional' de la tabla 'SorteoInterno2'
     *
     * @var string
     */

     var $condicional;

     /**
     * Representación de la columna 'orden' de la tabla 'SorteoInterno2'
     *
     * @var string
     */


     var $orden;
/**
     * Representación de la columna 'cupo_actual' de la tabla 'SorteoInterno2'
     *
     * @var string
     */


     var $cupoActual;
/**
     * Representación de la columna 'cupo_maximo' de la tabla 'SorteoInterno2'
     *
     * @var string
     */

     var $cupoMaximo;

    /**
     * Representación de la columna 'cantidad_sorteos' de la tabla 'SorteoInterno2'
     *
     * @var string
     */

     var $cantidadSorteos;



    /**
     * Representación de la columna 'maximo_sorteos' de la tabla 'SorteoInterno2'
     *
     * @var string
     */

     var $maximoSorteos;


     /**
     * Representación de la columna 'codigo' de la tabla 'SorteoInterno2'
     *
     * @var string
     */

     var $codigo;

     /**
     * Representación de la columna 'reglas' de la tabla 'SorteoInterno2'
     *
     * @var string
     */

     var $reglas;


     /**
     * Representación de la columna 'usucrea_id' de la tabla 'SorteoInterno2'
     *
     * @var string
     */

     var $usucreaId;

     /**
     * Representación de la columna 'json_temp' de la tabla 'SorteoInterno2'
     *
     * @var string
     */

     var $jsonTemp;


     public function execQuery($transaccion, $sql)
    {

        $BonoInternoMySqlDAO = new SorteoInterno2MySqlDAO($transaccion);
        $return = $BonoInternoMySqlDAO->querySQL($sql);
        $return = json_decode(json_encode($return), FALSE);

        return $return;

    }


     public function __construct($sorteoId = ""){
        if($sorteoId != ""){
            $this->sorteoId = $sorteoId;

            $sorteoInterno2MySqlDAO = new SorteoInterno2MySqlDAO();

            $sorteoInterno2 = $sorteoInterno2MySqlDAO->load($this->sorteoId);

            if($sorteoInterno2 != "" && $sorteoInterno2 != null and $sorteoInterno2 != "NULL"){
                $this->sorteoId = $sorteoInterno2->sorteoId;
                $this->fechaInicio = $sorteoInterno2->fechaInicio;
                $this->fechaFin = $sorteoInterno2->fechaFin;
                $this->descripcion = $sorteoInterno2->descripcion;
                $this->tipo = $sorteoInterno2->tipo;
                $this->nombre = $sorteoInterno2->nombre;
                $this->estado = $sorteoInterno2->estado;
                $this->mandante = $sorteoInterno2->mandante;
                $this->fechaCrea = $sorteoInterno2->fechaCrea;
                $this->usucreaId = $sorteoInterno2->usucreaId;
                $this->usumodifId = $sorteoInterno2->usumodifId;
                $this->fechaModif = $sorteoInterno2->fechaModif;
                $this->condicional = $sorteoInterno2->condicional;
                $this->orden = $sorteoInterno2->orden;
                $this->cupoActual = $sorteoInterno2->cupoActual;
                $this->cupoMaximo = $sorteoInterno2->cupoMaximo;
                $this->cantidadSorteos = $sorteoInterno2->cantidadSorteos;
                $this->maximoSorteos = $sorteoInterno2->maximoSorteos;
                $this->codigo = $sorteoInterno2->codigo;
                $this->reglas = $sorteoInterno2->reglas;
                $this->jsonTemp = $sorteoInterno2->jsonTemp;
            }else{
                throw new Exception("no existe".get_class($this), "100097");
            }

        }
     }

        /**
         * Realizar una consulta en la tabla de sorteos 'SorteoInterno'
         * de una manera personalizada
         *
         *
         * @param String $select campos de consulta
         * @param String $sidx columna para ordenar
         * @param String $sord orden los datos asc | desc
         * @param String $start inicio de la consulta
         * @param String $limit limite de la consulta
         * @param String $filters condiciones de la consulta
         * @param boolean $searchOn utilizar los filtros o no
         *
         * @return Array resultado de la consulta
         * @throws Exception si los sorteos no existen
         *
         * @access public
         * @see no
         * @since no
         * @deprecated no
         */
        public function getSorteos2Custom($select, $sidx, $sord, $start, $limit, $filters, $searchOn){

            $SorteoInterno2MySqlDAO = new SorteoInterno2MySqlDAO();

            $sorteosPv = $SorteoInterno2MySqlDAO->querySorteosCustom($select,$sidx,$sord,$start,$limit,$filters,$searchOn);

            if($sorteosPv != "" and $sorteosPv != "null" and $sorteosPv != null){
                return $sorteosPv;
            }else{
                throw new Exception("No existe". get_class($this),"202300");
            }

        }


        /**
         * Inserta el objeto actual en la base de datos utilizando una transacción.
         *
         * @param mixed $transaction La transacción a utilizar para la inserción.
         * @return mixed El resultado de la operación de inserción.
         */
        public function insert($transaction)
        {
            $SorteoInternoMySqlDAO = new SorteoInterno2MySqlDAO($transaction);
            return $SorteoInternoMySqlDAO->insert($this);

        }

        /**
             * Agregar un sorteo en la base de datos
             *
             *
             * @param String tipoSorteo tipoSorteo
             * @param String usuarioId id del usuario
             * @param String mandante mandante
             * @param String detalles detalles
             * @param Objeto Transaction transacción
             *
             * @return boolean $ resultado de la transacción
             * @throws no
             *
             * @access public
             * @see no
             * @since no
             * @deprecated no
             */

    //  public function agregarSorteo($tipoSorteo, $usuarioId, $mandante, $detalles, $transaccion){

    //     $detalleDepositos = $detalles->Depositos;
    //     $detalleDepositoEfectivo = $detalles->DepositoEfectivo;
    //     $detalleDepositoMetodoPago = $detalles->MetodoPago;
    //     $detalleValorDeposito = $detalles->ValorDeposito;
    //     $detallePaisPV = $detalles->PaisPV;
    //     $detalleDepartamentoPV = $detalles->DepartamentoPV;
    //     $detalleCiudadPV = $detalles->CiudadPV;

    //     $CodePromo = $detalles->CodePromo;

    //     $detallePaisUSER = $detalles->PaisUSER;
    //     $detalleDepartamentoUSER = $detalles->DepartamentoUSER;
    //     $detalleCiudadUSER = $detalles->CiudadUSER;
    //     $detalleMonedaUSER = $detalles->MonedaUSER;

    //     $detallePuntoVenta = $detalles->PuntoVenta;

    //     $cumpleCondiciones = false;
    //     $sorteoElegido = 0;
    //     $sorteoTieneRollower = false;
    //     $rollowerSorteo = 0;
    //     $rollowerDeposito = 0;

    //     $sqlSorteos = "select a.sorteo2_id,a.tipo,a.fecha_inicio,a.fecha_fin,now() test from sorteo_interno2 a where a.mandante=" . $mandante . " and now()  between (a.fecha_inicio) and (a.fecha_fin) and a.estado='A' ORDER BY a.orden DESC,a.fecha_crea ASC ";


    //     if ($CodePromo != "") {
    //         $sqlSorteos = "select a.sorteo2_id,a.tipo,a.fecha_inicio,a.fecha_fin from sorteo_interno2 a INNER JOIN sorteo_detalle2 b ON (a.sorteo2_id=b.sorteo2_id AND b.tipo='CODEPROMO' AND b.valor='" . $CodePromo . "') where a.mandante=" . $mandante . " and now()  between (a.fecha_inicio) and (a.fecha_fin) and a.estado='A' ORDER BY a.orden DESC,a.fecha_crea ASC ";
    //     }

    //     $sorteosDisponibles = $this->execQuery($transaccion, $sqlSorteos);


    //     foreach ($sorteosDisponibles as $sorteo) {


    //         if (!$cumpleCondiciones) {

    //             //Obtenemos todos los detalles del sorteo
    //             $sqlDetalleSorteo = "select * from sorteo_detalle2 a where a.sorteo2_id='" . $sorteo->{"a.sorteo2_id"} . "' AND (moneda='' OR moneda='PEN') ";
    //             $sorteoDetalles = $this->execQuery($transaccion, $sqlDetalleSorteo);

    //             //Inicializamos variables
    //             $cumpleCondiciones = true;
    //             $condicionmetodoPago = false;
    //             $condicionmetodoPagocount = 0;

    //             $condicionPaisPV = false;
    //             $condicionPaisPVcount = 0;
    //             $condicionDepartamentoPV = false;
    //             $condicionDepartamentoPVcount = 0;
    //             $condicionCiudadPV = false;
    //             $condicionCiudadPVcount = 0;
    //             $condicionPuntoVenta = false;
    //             $condicionPuntoVentacount = 0;

    //             $condicionPaisUSER = false;
    //             $condicionPaisUSERcount = 0;
    //             $condicionDepartamentoUSER = false;
    //             $condicionDepartamentoUSERcount = 0;
    //             $condicionCiudadUSER = false;
    //             $condicionCiudadUSERcount = 0;

    //             $condicionTrigger = true;

    //             $puederepetirSorteo = false;
    //             $ganaSorteoId = 0;


    //             $maximopago = 0;
    //             $maximodeposito = 0;
    //             $minimodeposito = 0;
    //             $valorsorteo = 0;
    //             $tipoproducto = 0;
    //             $tiposorteo = "";
    //             $sorteoTieneRollower = false;
    //             $tiposaldo = -1;

    //             if ($tipoSorteo != $sorteo->{"a.tipo"}) {
    //                 $cumpleCondiciones = false;

    //             }


    //             foreach ($sorteoDetalles as $sorteoDetalle) {


    //                 switch ($sorteoDetalle->{"a.tipo"}) {


    //                     case "TIPOPRODUCTO":
    //                         $tipoproducto = $sorteoDetalle->{"a.valor"};


    //                         break;

    //                     case "CANTDEPOSITOS":
    //                         if ($detalleDepositos != ($sorteoDetalle->{"a.valor"} - 1) && $sorteo->{"a.tipo"} == 2) {

    //                             $cumpleCondiciones = false;

    //                         }

    //                         break;

    //                     case "CONDEFECTIVO":
    //                         if ($detalleDepositoEfectivo) {
    //                             if (($sorteoDetalle->{"a.valor"} == "true")) {
    //                                 $condicionmetodoPago = true;
    //                             }
    //                         } else {
    //                             if (($sorteoDetalle->{"a.valor"} != "true")) {
    //                                 $condicionmetodoPago = true;
    //                             }
    //                         }
    //                         $condicionmetodoPagocount++;


    //                         break;


    //                     case "PORCENTAJE":
    //                         $tiposorteo = "PORCENTAJE";
    //                         $valorsorteo = $sorteoDetalle->{"a.valor"};

    //                         break;


    //                     case "NUMERODEPOSITO":

    //                         break;

    //                     case "MAXJUGADORES":

    //                         break;


    //                     case "MAXPAGO":

    //                         if ($sorteoDetalle->{"a.moneda"} == $detalleMonedaUSER) {

    //                             $maximopago = $sorteoDetalle->{"a.valor"};

    //                         }
    //                         break;

    //                     case "MAXDEPOSITO":

    //                         $maximodeposito = $sorteoDetalle->{"a.valor"};
    //                         if ($sorteoDetalle->{"a.moneda"} == $detalleMonedaUSER) {

    //                             if ($detalleValorDeposito > $maximodeposito) {
    //                                 $cumpleCondiciones = false;
    //                             }
    //                         }

    //                         break;

    //                     case "MINDEPOSITO":

    //                         $minimodeposito = $sorteoDetalle->{"a.valor"};

    //                         if ($sorteoDetalle->{"a.moneda"} == $detalleMonedaUSER) {

    //                             if ($detalleValorDeposito < $minimodeposito) {
    //                                 $cumpleCondiciones = false;
    //                             }
    //                         }

    //                         break;

    //                     case "VALORBONO":
    //                         if ($sorteoDetalle->{"a.moneda"} == $detalleMonedaUSER) {

    //                             $valorsorteo = $sorteoDetalle->{"a.valor"};
    //                             $tiposorteo = "VALOR";
    //                         }
    //                         break;

    //                     case "CONDPAYMENT":



    //                         if ($detalleDepositoMetodoPago == $sorteoDetalle->{"a.valor"}) {
    //                             $condicionmetodoPago = true;
    //                         }
    //                         $condicionmetodoPagocount++;

    //                         break;

    //                     case "CONDPAISPV":

    //                         $condicionPaisPVcount = $condicionPaisPVcount + 1;
    //                         if ($sorteoDetalle->{"a.valor"} == $detallePaisPV) {
    //                             $condicionPaisPV = true;
    //                         }

    //                         break;

    //                     case "CONDDEPARTAMENTOPV":

    //                         $condicionDepartamentoPVcount = $condicionDepartamentoPVcount + 1;
    //                         if ($sorteoDetalle->{"a.valor"} == $detalleDepartamentoPV) {
    //                             $condicionDepartamentoPV = true;
    //                         }

    //                         break;

    //                     case "CONDCIUDADPV":

    //                         $condicionCiudadPVcount = $condicionCiudadPVcount + 1;

    //                         if ($sorteoDetalle->{"a.valor"} == $detalleCiudadPV) {
    //                             $condicionCiudadPV = true;
    //                         }

    //                         break;

    //                     case "CONDPAISUSER":

    //                         $condicionPaisUSERcount = $condicionPaisUSERcount + 1;
    //                         if ($sorteoDetalle->{"a.valor"} == $detallePaisUSER) {
    //                             $condicionPaisUSER = true;
    //                         }

    //                         break;

    //                     case "CONDDEPARTAMENTOUSER":

    //                         $condicionDepartamentoUSERcount = $condicionDepartamentoUSERcount + 1;
    //                         if ($sorteoDetalle->{"a.valor"} == $detalleDepartamentoUSER) {
    //                             $condicionDepartamentoUSER = true;
    //                         }

    //                         break;

    //                     case "CONDCIUDADUSER":

    //                         $condicionCiudadUSERcount = $condicionCiudadUSERcount + 1;

    //                         if ($sorteoDetalle->{"a.valor"} == $detalleCiudadUSER) {
    //                             $condicionCiudadUSER = true;
    //                         }

    //                         break;

    //                     case "CONDPUNTOVENTA":

    //                         $condicionPuntoVentacount = $condicionPuntoVentacount + 1;

    //                         if ($sorteoDetalle->{"a.valor"} == $detallePuntoVenta) {
    //                             $condicionPuntoVenta = true;
    //                         }

    //                         break;

    //                     case "EXPDIA":

    //                         break;

    //                     case "EXPFECHA":

    //                         break;

    //                     case "WFACTORBONO":
    //                         $sorteoTieneRollower = true;

    //                         $rollowerSorteo = $sorteoDetalle->{"a.valor"};

    //                         break;

    //                     case "WFACTORDEPOSITO":
    //                         $sorteoTieneRollower = true;
    //                         $rollowerDeposito = $sorteoDetalle->{"a.valor"};

    //                         break;

    //                     case "VALORROLLOWER":
    //                         if ($sorteoDetalle->{"a.moneda"} == $detalleMonedaUSER) {

    //                             $sorteoTieneRollower = true;
    //                             $rollowerValor = $sorteoDetalle->{"a.valor"};
    //                         }
    //                         break;
    //                     case "REPETIRBONO":

    //                         if ($sorteoDetalle->{"a.valor"} == '1') {
    //                             $puederepetirSorteo = true;
    //                         }

    //                         break;

    //                     case "WINBONOID":
    //                         $ganaSorteoId = $sorteoDetalle->{"a.valor"};
    //                         $tiposorteo = "WINBONOID";
    //                         $valor_sorteo = 0;

    //                         break;

    //                     case "TIPOSALDO":
    //                         $tiposaldo = $sorteoDetalle->{"a.valor"};

    //                         break;

    //                     case "LIVEORPREMATCH":

    //                         break;

    //                     case "MINSELCOUNT":

    //                         break;

    //                     case "LIVEORPREMATCH":

    //                         break;

    //                     case "MINSELPRICE":

    //                         break;

    //                     case "MINBETPRICE":

    //                         break;

    //                     case "FROZEWALLET":

    //                         break;

    //                     case "SUPPRESSWITHDRAWAL":

    //                         break;

    //                     case "SCHEDULECOUNT":

    //                         break;

    //                     case "SCHEDULENAME":

    //                         break;

    //                     case "SCHEDULEPERIOD":

    //                         break;


    //                     case "SCHEDULEPERIODTYPE":

    //                         break;

    //                     case "CODEPROMO":

    //                         if ($CodePromo != "") {
    //                             if ($CodePromo != $sorteoDetalle->{"a.valor"}) {
    //                                 $condicionTrigger = false;

    //                             }
    //                         } else {

    //                             if ($tipoSorteo == 2) {
    //                                 $sqlDetalleSorteoPendiente = "SELECT a.ususorteo2_id FROM usuario_sorteo2 a WHERE a.sorteo2_id='" . $sorteo->{"a.sorteo2_id"} . "' AND a.registro2_id='" . $usuarioId . "' AND a.estado='P'";
    //                                 $sorteoDetallesPendiente = $this->execQuery($transaccion, $sqlDetalleSorteoPendiente);

    //                                 if (count($sorteoDetallesPendiente) > 0) {
    //                                     $condicionTriggerPosterior = $sorteoDetallesPendiente[0]->ususorteo_id;

    //                                 } else {
    //                                     $condicionTrigger = false;

    //                                 }

    //                             } else {
    //                                 $condicionTrigger = false;

    //                             }

    //                         }

    //                         break;

    //                     default:

    //                         //   if (stristr($sorteodetalle->{'sorteo_detalle.tipo'}, 'CONDGAME')) {
    //                         //
    //                         // }
    //                         //
    //                         //   if (stristr($sorteodetalle->{'sorteo_detalle.tipo'}, 'ITAINMENT')) {
    //                         //
    //                         //
    //                         //
    //                         //   }
    //                         break;
    //                 }
    //             }


    //             if (!$condicionTrigger) {
    //                 $cumpleCondiciones = false;
    //             }

    //             if ($CodePromo == "") {

    //                 if ($condicionPaisPVcount > 0) {
    //                     if (!$condicionPaisPV) {
    //                         $cumpleCondiciones = false;
    //                     }

    //                 }

    //                 if ($condicionDepartamentoPVcount > 0) {
    //                     if (!$condicionDepartamentoPV) {
    //                         $cumpleCondiciones = false;
    //                     }

    //                 }

    //                 if ($condicionCiudadPVcount > 0) {
    //                     if (!$condicionCiudadPV) {
    //                         $cumpleCondiciones = false;
    //                     }

    //                 }
    //             }

    //             if ($condicionPaisUSERcount > 0) {
    //                 if (!$condicionPaisUSER) {
    //                     $cumpleCondiciones = false;
    //                 }

    //             }

    //             if ($condicionDepartamentoUSERcount > 0) {
    //                 if (!$condicionDepartamentoUSER) {
    //                     $cumpleCondiciones = false;
    //                 }

    //             }

    //             if ($condicionCiudadUSERcount > 0) {
    //                 if (!$condicionCiudadUSER) {
    //                     $cumpleCondiciones = false;
    //                 }

    //             }
    //             if ($CodePromo == "") {

    //                 if ($condicionPuntoVentacount > 0) {
    //                     if (!$condicionPuntoVenta) {
    //                         $cumpleCondiciones = false;
    //                     }
    //                 }

    //                 if ($condicionmetodoPagocount > 0) {
    //                     if (!$condicionmetodoPago) {
    //                         $cumpleCondiciones = false;
    //                     }

    //                 }
    //             }

    //             if ($cumpleCondiciones) {


    //                 if ($puederepetirSorteo) {
    //                     $sorteoElegido = $sorteo->{"a.sorteo2_id"};

    //                 } else {
    //                     $sqlRepiteSorteo = "select * from usuario_sorteo2 a where a.sorteo2_id='" . $sorteo->{"a.sorteo2_id"} . "' AND a.registro2_id = '" . $usuarioId . "'";
    //                     $repiteSorteo = $this->execQuery($transaccion, $sqlRepiteSorteo);

    //                     if ((!$puederepetirSorteo && count($repiteSorteo) == 0)) {
    //                         $sorteoElegido = $sorteo->{"a.sorteo2_id"};
    //                     } else {
    //                         $cumpleCondiciones = false;
    //                     }

    //                 }


    //             }

    //             if ($cumpleCondiciones) {
    //                 if ($transaccion != '') {
    //                     if ($tiposorteo == "PORCENTAJE") {

    //                         $valor_sorteo = ($detalleValorDeposito) * ($valorsorteo) / 100;

    //                         if ($valor_sorteo > $maximopago) {
    //                             $valor_sorteo = $maximopago;
    //                         }

    //                     } elseif ($tiposorteo == "VALOR") {

    //                         $valor_sorteo = $valorsorteo;

    //                     }

    //                     if ($condicionTriggerPosterior > 0) {
    //                         $strsql = "UPDATE sorteo_interno2 SET sorteo_interno2.cupo_actual =sorteo_interno2.cupo_actual + " . $valor_sorteo . " WHERE sorteo_interno2.cupo_maximo >= (sorteo_interno2.cupo_actual + " . $valor_sorteo . ") AND sorteo_interno2.sorteo2_id ='" . $sorteoElegido . "'";

    //                     } else {
    //                         $strsql = "UPDATE sorteo_interno2 SET sorteo_interno2.cupo_actual =sorteo_interno2.cupo_actual + " . $valor_sorteo . ",sorteo_interno2.cantidad_sorteos=sorteo_interno2.cantidad_sorteos+1 WHERE (sorteo_interno2.cupo_maximo >= (sorteo_interno2.cupo_actual + " . $valor_sorteo . ") OR sorteo_interno2.cupo_maximo = 0) AND ((sorteo_interno2.maximo_sorteos >= (sorteo_interno2.cantidad_sorteos+1)) OR sorteo_interno2.maximo_sorteos=0) AND sorteo_interno2.sorteo_id ='" . $sorteoElegido . "'";

    //                     }
    //                     if ($usuarioId == 886) {
    //                         //print_r("TEST" . $cumpleCondiciones);
    //                         //print_r($strsql);
    //                     }

    //                     $resp = $this->execUpdate($transaccion, $strsql);
    //                     if ($usuarioId == 886) {
    //                         //print_r($resp);
    //                     }
    //                     if ($resp > 0) {
    //                         $cumpleCondiciones = true;
    //                     } else {

    //                         $cumpleCondiciones = false;
    //                         $sorteoElegido = 0;

    //                         if ($condicionTriggerPosterior > 0) {
    //                             $strsql = "UPDATE usuario_sorteo2 SET usuario_sorteo2.estado = 'E',usuario_sorteo2.error_id='1' WHERE usuario_sorteo2.ususorteo_id ='" . $condicionTriggerPosterior . "'";
    //                             $resp = $this->execUpdate($transaccion, $strsql);

    //                         }

    //                     }

    //                 }

    //             }

    //         }

    //     }



/**
 * Establece la fecha de inicio del sorteo.
 *
 * @param string $value La fecha de inicio.
 */
public function setFechaInicio($value){
    $this->fechaInicio = $value;
}

/**
 * Obtiene la fecha de inicio del sorteo.
 *
 * @return string La fecha de inicio.
 */
public function getFechaInicio(){
    return $this->fechaInicio;
}

/**
 * Establece la fecha de fin del sorteo.
 *
 * @param string $date La fecha de fin.
 */
public function setFechaFin($date){
    $this->fechaFin = $date;
}

/**
 * Obtiene la fecha de fin del sorteo.
 *
 * @return string La fecha de fin.
 */
public function getFechaFin(){
    return $this->fechaFin;
}

/**
 * Establece la descripción del sorteo.
 *
 * @param string $value La descripción.
 */
public function setdescription($value){
    $this->descripcion = $value;
}

/**
 * Obtiene la descripción del sorteo.
 *
 * @return string La descripción.
 */
public function getdescription(){
    return $this->descripcion;
}

/**
 * Establece el tipo de sorteo.
 *
 * @param string $type El tipo de sorteo.
 */
public function setTipo($type){
    $this->tipo = $type;
}

/**
 * Obtiene el tipo de sorteo.
 *
 * @return string El tipo de sorteo.
 */
public function getTipo(){
    return $this->tipo;
}

/**
 * Establece el nombre del sorteo.
 *
 * @param string $name El nombre del sorteo.
 */
public function setName($name){
    $this->nombre = $name;
}

/**
 * Obtiene el nombre del sorteo.
 *
 * @return string El nombre del sorteo.
 */
public function getName(){
    return $this->nombre;
}

/**
 * Establece el estado del sorteo.
 *
 * @param string $estado El estado del sorteo.
 */
public function setState($estado){
    $this->estado = $estado;
}

/**
 * Obtiene el estado del sorteo.
 *
 * @return string El estado del sorteo.
 */
public function getState(){
    return $this->estado;
}

/**
 * Establece el mandante del sorteo.
 *
 * @param string $partner El mandante del sorteo.
 */
public function setMandante($partner){
    $this->mandante = $partner;
}

/**
 * Obtiene la fecha de creación del sorteo.
 *
 * @return string La fecha de creación.
 */
public function getFechaCrea(){
    return $this->fechaCrea;
}

/**
 * Establece el ID del usuario creador del sorteo.
 *
 * @param string $user El ID del usuario creador.
 */
public function setUsuCreaId($user){
    $this->usucreaId = $user;
}

/**
 * Obtiene el ID del usuario creador del sorteo.
 *
 * @return string El ID del usuario creador.
 */
public function getUsuCreaId(){
    return $this->usucreaId;
}

/**
 * Establece el ID del usuario que modificó el sorteo.
 *
 * @param string $user1 El ID del usuario que modificó.
 */
public function setUsuModif($user1){
    $this->usumodifId = $user1;
}

/**
 * Obtiene la fecha de modificación del sorteo.
 *
 * @return string La fecha de modificación.
 */
public function getFechaModif(){
    return $this->fechaModif;
}

/**
 * Establece la condición del sorteo.
 *
 * @param string $condicion La condición del sorteo.
 */
public function setCondicional($condicion){
    $this->condicional = $condicion;
}

/**
 * Obtiene la condición del sorteo.
 *
 * @return string La condición del sorteo.
 */
public function getCondicional(){
    return $this->condicional;
}

/**
 * Establece el orden del sorteo.
 *
 * @param string $orden El orden del sorteo.
 */
public function setOrden($orden){
    $this->orden = $orden;
}

/**
 * Obtiene el orden del sorteo.
 *
 * @return string El orden del sorteo.
 */
public function getOrden(){
    return $this->orden;
}

/**
 * Establece las reglas del sorteo.
 *
 * @param string $rules Las reglas del sorteo.
 */
public function setRules($rules){
    $this->reglas = $rules;
}

/**
 * Obtiene las reglas del sorteo.
 *
 * @return string Las reglas del sorteo.
 */
public function getRules(){
    return $this->reglas;
}

/**
 * Establece el JSON temporal del sorteo.
 *
 * @param string $valor El JSON temporal.
 */
public function setJsonTemp($valor){
    $this->jsonTemp = $valor;
}

/**
 * Obtiene el JSON temporal del sorteo.
 *
 * @return string El JSON temporal.
 */
public function getJsonTemp(){
    return $this->jsonTemp;
}



        // $respuesta = array();
        // $respuesta["Sorteo"] = 0;
        // $respuesta["WinBonus"] = false;








    // }

 }

?>