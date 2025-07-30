<?php

/**
 * Este archivo contiene un script para procesar y generar un informe de cuotas totales
 * basado en datos de usuarios, transacciones y actividades relacionadas.
 *
 * @category   Red
 * @package    API
 * @subpackage Integrations
 * @author     Desconocido
 * @version    1.0.0
 * @since      2025-05-09
 */

/**
 * Variables globales utilizadas en el script:
 *
 * @var mixed $_REQUEST            Variable superglobal que contiene datos enviados a través del método REQUEST.
 * @var mixed $Mandante            Esta variable se utiliza para almacenar y manipular el mandante asociado.
 * @var mixed $Country             Esta variable indica el país asociado al mandante.
 * @var mixed $AffiliateId         Variable que almacena el identificador de un afiliado.
 * @var mixed $BonoInternoMySqlDAO Objeto que maneja operaciones de base de datos para bonos internos en MySQL.
 * @var mixed $Transaction         Esta variable contiene información de una transacción, utilizada para el seguimiento y procesamiento de operaciones.
 * @var mixed $sql                 Variable que almacena una consulta SQL a ejecutar en la base de datos.
 * @var mixed $BonoInterno         Variable que representa un bono interno en el sistema.
 * @var mixed $Resultado           Variable que almacena el resultado de una operación o consulta.
 * @var mixed $array               Variable que almacena una lista o conjunto de datos.
 * @var mixed $index               Variable que representa un índice en una estructura de datos.
 * @var mixed $value               Esta variable se utiliza para almacenar y manipular valores asociados a claves.
 * @var mixed $item                Variable que almacena un elemento genérico en una lista o estructura de datos.
 * @var mixed $response            Esta variable almacena la respuesta generada por una operación o petición.
 */

use Backend\dto\BonoInterno;
use Backend\mysql\BonoInternoMySqlDAO;

if ($_REQUEST["Sign"] == "8eWGT3838M8ihOfiX4pA5vd8acrWJMYu") {
    $Mandante = $_REQUEST["Partner"];
    $Country = $_REQUEST["Country"];
    $AffiliateId = $_REQUEST["AffiliateId"];

    if ($Mandante == '') {
        throw new Exception("Error en los parametros enviados", "100001");
    }

    if ($Country == '') {
        throw new Exception("Error en los parametros enviados", "100001");
    }


    $BonoInternoMySqlDAO = new BonoInternoMySqlDAO();
    $Transaction = $BonoInternoMySqlDAO->getTransaction();

    $sql = "select UPPER(mandante.descripcion)                        Partner
     , UPPER(pais.pais_nom)                               Pais
     , activos_casino.cantidad                    ActivosCasino
     , activos_deportiva.cantidad                  ActivosDeportivas
     , bodega_informe_gerencial.usuarios_registrados usuarios_registrados
     , bodega_informe_gerencial.primeros_depositos PrimerosDepositos
     , depositos.cantidad                          ActivosDepositos
from pais_mandante
         inner join mandante on mandante.mandante = pais_mandante.mandante
         inner join pais on pais.pais_id = pais_mandante.pais_id
         left outer join bodega_informe_gerencial
                         on (bodega_informe_gerencial.pais_id = pais_mandante.pais_id and
                             bodega_informe_gerencial.mandante = pais_mandante.mandante
                             AND bodega_informe_gerencial.fecha = DATE_SUB(CURDATE(), INTERVAL 1 DAY)
                             AND bodega_informe_gerencial.tipo_fecha = 1 and bodega_informe_gerencial.tipo_usuario = 1
                             )

         left outer join (SELECT count(distinct usucasino_detalle_resumen.usuario_id) cantidad,
                                 usuario_mandante.mandante,
                                 usuario_mandante.pais_id
                          FROM usucasino_detalle_resumen
                                   INNER JOIN usuario_mandante
                                              on usucasino_detalle_resumen.usuario_id = usuario_mandante.usumandante_id
                          WHERE usucasino_detalle_resumen.fecha_crea >= DATE_SUB(CURDATE(), INTERVAL 1 DAY)
                            AND usucasino_detalle_resumen.fecha_crea < CURDATE()
                            AND usucasino_detalle_resumen.tipo LIKE 'DEBIT%'
                          group by usuario_mandante.mandante, usuario_mandante.pais_id) activos_casino
                         ON (activos_casino.mandante = pais_mandante.mandante and
                             activos_casino.pais_id = pais_mandante.pais_id)
         left outer join (SELECT count(distinct usuario.usuario_id) cantidad,
                                 usuario.mandante,
                                 usuario.pais_id
                          FROM usuario_deporte_resumen
                                   INNER JOIN usuario
                                              on usuario_deporte_resumen.usuario_id = usuario.usuario_id
                          WHERE usuario_deporte_resumen.fecha_crea >= DATE_SUB(CURDATE(), INTERVAL 1 DAY)
                            AND usuario_deporte_resumen.fecha_crea < CURDATE()
                            AND usuario_deporte_resumen.tipo LIKE 'BET%'
                          group by usuario.mandante, usuario.pais_id) activos_deportiva
                         ON (activos_deportiva.mandante = pais_mandante.mandante and
                             activos_deportiva.pais_id = pais_mandante.pais_id)
         left outer join (SELECT count(distinct usuario_recarga_resumen.usuario_id) cantidad,
                                 usuario.mandante,
                                 usuario.pais_id
                          FROM usuario_recarga_resumen
                                   INNER JOIN usuario
                                              on usuario_recarga_resumen.usuario_id = usuario.usuario_id
                          WHERE usuario_recarga_resumen.fecha_crea >= DATE_SUB(CURDATE(), INTERVAL 1 DAY)
                            AND usuario_recarga_resumen.fecha_crea < CURDATE() and usuario_recarga_resumen.estado='A'
                          group by usuario.mandante, usuario.pais_id) depositos
                         ON (depositos.mandante = pais_mandante.mandante and
                             depositos.pais_id = pais_mandante.pais_id)

WHERE pais_mandante.estado = 'A'
        AND pais_mandante.mandante IN ($Mandante)
        AND pais_mandante.pais_id in ($Country)

group by pais_mandante.mandante,pais_mandante.pais_id


";
    if ($_ENV['debug']) {
        print_r($sql);
    }
    $BonoInterno = new BonoInterno();
    $Resultado = $BonoInterno->execQuery($Transaction, $sql);

    $array = [];

    foreach ($Resultado as $index => $value) {
        $item = new stdClass();

        $item->Mandante = $value->{".Partner"};
        $item->Country = $value->{".Pais"};
        $item->Registers = $value->{"bodega_informe_gerencial.usuarios_registrados"};
        $item->UserDeposits = $value->{"depositos.ActivosDepositos"};
        $item->FirstDeposits = $value->{"bodega_informe_gerencial.PrimerosDepositos"};


        $item->UserActiveCasino = $value->{"activos_casino.ActivosCasino"};
        $item->UserActiveSport = $value->{"activos_deportiva.ActivosDeportivas"};

        array_push($array, $item);
    }


    $response["code"] = 0;
    $response["msg"] = "";
    $response["count"] = count($array);
    $response["data"] = $array;
} else {
    $response["code"] = 0;
    $response["msg"] = '';
    $response["count"] = 0;
    $response["data"] = [];
}


