<?php
use Backend\dto\UsuarioMandante;
use Backend\dto\Usuario;
use Backend\dto\PaisMandante;
use Backend\dto\LogroReferido;
use Backend\sql\Transaction;
use Backend\dto\BonoInterno;
use Backend\dto\Clasificador;
use Backend\dto\MandanteDetalle;


/**
 * Obtiene los usuarios referidos premiados.
 *
 * @param object $json Objeto JSON que contiene los parámetros de la solicitud.
 * @param object $json->session Información de la sesión del usuario.
 * @param object $json->session->usuario Información del usuario en la sesión.
 * @param object $json->params Parámetros de la solicitud.
 * @param int $json->params->Start Número de filas a omitir.
 * @param int $json->params->Limit Número máximo de filas a obtener.
 * @param bool $json->params->GetCount Indica si se debe obtener el conteo total.
 *
 * @throws Exception Si ocurre un error durante la ejecución.
 *
 * @return array Respuesta con el código, conteo total y usuarios premiados.
 */

$params = $json->params;
$start = (int)$params->Start;
$limit = (int)$params->Limit;
$getCount = (bool)$params->GetCount;
$totalCount = null;

/** Recuperando premios redimibles y redimidos */
//El usuario instanciado es el referente
$UsuarioMandante = new UsuarioMandante($json->session->usuario);
$Usuario = new Usuario($UsuarioMandante->getUsuarioMandante());
$Transaction = new Transaction();
$BonoInterno = new BonoInterno();


//Consultando total_count
$sql = "select COUNT(usuid_referido) from logro_referido where usuid_referente = {$Usuario->usuarioId} and
        ((estado_grupal in ('R','PE','C')) /*Premios redimidos, vencidos por referente y cumplidos*/
        or
        (estado_grupal = 'P' and (fecha_expira is null or fecha_expira > now())) /*Premios pendientes (Y QUE NO HAN EXPIRADO LAS CONDICIONES)*/)
        group by usuid_referido";
$referredUsers = $BonoInterno->execQuery($Transaction, $sql);
$totalCount = count($referredUsers);


$sql = "select usuid_referido, sum(if(estado_grupal = 'C', 1, 0)) as nuevoPremio, sum(if(estado_grupal = 'P', 1, 0)) as premioPendiente, 
       sum(if(estado_grupal = 'R', 1, 0)) as premioRedimido, sum(if(estado_grupal = 'PE', 1, 0)) as premioExpirado, usuario.nombre
        from logro_referido
        inner join usuario on (logro_referido.usuid_referido = usuario.usuario_id)
        where usuid_referente = {$Usuario->usuarioId}
        and ((estado_grupal in ('R', 'PE', 'C')) /*Premios redimidos, vencidos por referente y cumplidos*/
        or
       (estado_grupal = 'P' and (fecha_expira is null or fecha_expira > now())) /*Premios pendientes (Y QUE NO HAN EXPIRADO LAS CONDICIONES)*/)
        group by usuid_referido
        order by nuevoPremio desc, premioPendiente desc, premioRedimido desc, premioExpirado desc, usuid_referido desc
        limit ". $start ."," . $limit;
$possibleAwardedUsers = $BonoInterno->execQuery($Transaction, $sql);

/** Recolectando usuarios con premios redimidos o redimibles */
$awardedUsers = [];
foreach($possibleAwardedUsers as $possibleAwardedUser) {
    $sql = "select distinct tipo_premio, usuid_referido
            from logro_referido
            inner join mandante_detalle on (logro_referido.tipo_premio = mandante_detalle.manddetalle_id)
            where mandante_detalle.mandante = {$Usuario->mandante}
            and mandante_detalle.pais_id = {$Usuario->paisId}
            and mandante_detalle.estado = 'A'
            and usuid_referido = {$possibleAwardedUser->{'logro_referido.usuid_referido'}}
            and ((estado_grupal in ('R', 'PE', 'C')) /*Premios redimidos, vencidos por referente y cumplidos*/
            or
            (estado_grupal = 'P' and (fecha_expira is null or fecha_expira > now())) /*Premios pendientes (Y QUE NO HAN EXPIRADO LAS CONDICIONES)*/)";
    $awardsIds = $BonoInterno->execQuery($Transaction, $sql);

    //Recoletando estado de los premios
    $LogroReferido = new LogroReferido();
    $isAwarded = false;
    $awards = [];
    foreach($awardsIds as $awardId) {
        $awardStatus = $LogroReferido->getEstadoPremio($Transaction, $awardId->{'logro_referido.tipo_premio'}, $possibleAwardedUser->{'logro_referido.usuid_referido'}, true);
        $achievements = array_keys($awardStatus['logros']);

        /** Construyendo las etiquetas de respuesta para frontend */
        //Id es el tipo_premio/clasificador, cada una de las llaves posteriores en el array $award corresponden a una etiqueta visible en front;
        $award = [];
        $award['dbStatus'] = $awardStatus['dbStatus'];
        $award['id'] = $awardStatus['tipoPremio'];

        //Condiciones
        if(in_array('CONDMINFIRSTDEPOSITREFERRED', $achievements)) {
            //Condición de depósito
            $award['deposito'] = $awardStatus['logros']['CONDMINFIRSTDEPOSITREFERRED'];
            $award['deposito_ValorObjetivo'] = $awardStatus['valoresObjetivo']['CONDMINFIRSTDEPOSITREFERRED'] . ' ' . $Usuario->moneda;
        }

        if(in_array('CONDMINBETREFERRED', $achievements)){
            //Condición de apuesta
            $award['apuesta'] = $awardStatus['logros']['CONDMINBETREFERRED'];
            $award['apuesta_ValorObjetivo'] = $awardStatus['valoresObjetivo']['CONDMINBETREFERRED'] . ' ' . $Usuario->moneda;
        }

        if(in_array('CONDVERIFIEDREFERRED', $achievements)) {
            //Condición de verificación
            $award['verificado'] = $awardStatus['logros']['CONDVERIFIEDREFERRED'];
            $award['verificado_ValorObjetivo'] = (int)$awardStatus['valoresObjetivo']['CONDVERIFIEDREFERRED'];
        }

        //Fecha de expiración de las condiciones y del premio
        $award['fechaExpiraCondicion'] = $awardStatus['fechaExpiraCondicion'];
        $award['fechaExpiraPremio'] = $awardStatus['fechaExpiraPremio'];
        $award['fechaRedimido'] = null; //La fecha de redimido es actualizada abajo

        //Estado global del premio
        $award['estado'] = $awardStatus['estado'];
        if (in_array($award['estado'], [2,4])) $award['estado'] = 6;

        //Verificando si premio puede redimirse
        if($awardStatus['redimible'] == 1){
            $isAwarded = true;
            $award['bonosInfo']['redimible'] = 1;
        }
        else {
            $award['bonosInfo']['redimible'] = 0;
        }

        //Verificando si premio ha expirado
        if ($awardStatus['premioExpirado']) {
            $award['bonosInfo']['redimible'] = 2;
        }


        //Almacenando premio y sus etiquetas
        array_push($awards, $award);
    }

    //Asignando información de bonos ofertados y reclamados
    $keys = array_keys($awards);
    foreach($keys as $key) {
        //Verificando estado
        $redeemedAward = true;
        $completedConditions = array_reduce($awards[$key]['dbStatus'], function($carry, $item) use(&$redeemedAward) {
            if($item == 'C') {
                $redeemedAward = false;
                $carry +=1;
            }
            elseif($item == 'P' || $item == 'F') {
                $redeemedAward = false;
            }
            return $carry;
        }, 0);


        /**Asignando información de bono elegido y oferta de bonos según corresponda*/
        $awards[$key]['bonosInfo']['bonoElegido'] = [];
        $awards[$key]['bonosInfo']['bonosOferta'] = [];
        $totalConditions = count($awards[$key]['dbStatus']);
        unset($awards[$key]['dbStatus']);

        //Si premio aún no puede ser redimido, se pasa a la siguiente iteración/premio
        if((!$redeemedAward) && ($totalConditions > $completedConditions)) continue;

        //Si premio ha expirado se pasa a la siguiente iteración/premio
        if ($awards[$key]['bonosInfo']['redimible'] == 2) continue;

        if($redeemedAward) {
            //Si premio ya fue redimido se entrega la información del bono y se pasa a la siguiente iteración/premio
            $sql = "select distinct logro_referido.valor_premio, bono_interno.bono_id, bono_interno.nombre, bono_interno.descripcion, bono_interno.tipo, bono_interno.imagen, logro_referido.fecha_modif from logro_referido inner join bono_interno on (logro_referido.valor_premio = bono_interno.bono_id) where logro_referido.usuid_referido = ". $possibleAwardedUser->{'logro_referido.usuid_referido'} ." and logro_referido.tipo_premio = " . $awards[$key]['id'];
            $choicedBonusInfo = $BonoInterno->execQuery($Transaction, $sql);
            $choicedBonus['bonoId'] = $choicedBonusInfo[0]->{'bono_interno.bono_id'};
            $choicedBonus['nombre'] = $choicedBonusInfo[0]->{'bono_interno.nombre'};
            $choicedBonus['descripcion'] = $choicedBonusInfo[0]->{'bono_interno.descripcion'};
            $choicedBonus['tipo'] = $choicedBonusInfo[0]->{'bono_interno.tipo'};
            $choicedBonus['imagen'] = $choicedBonusInfo[0]->{'bono_interno.imagen'};
            $awards[$key]['bonosInfo']['bonoElegido'] = $choicedBonus;

            //Almacenando fecha de entrega o redención
            $awards[$key]['fechaRedimido'] = $choicedBonusInfo[0]->{'logro_referido.fecha_modif'};
            continue;
        }

        if($totalConditions == $completedConditions) {
            //Obteniendo ids de bonos ofertados
            $sql = "select valor from mandante_detalle where mandante_detalle.mandante = ". $Usuario->mandante ." and mandante_detalle.pais_id = ". $Usuario->paisId ." and mandante_detalle.estado = 'A' and mandante_detalle.manddetalle_id = ". $awards[$key]['id'];
            $ofertedBonusIds = $BonoInterno->execQuery($Transaction, $sql);
            //Obteniendo información de bonos ofertados
            $sql = "select bono_interno.bono_id, bono_interno.nombre, bono_interno.descripcion, bono_interno.tipo, bono_interno.imagen from bono_interno where bono_interno.estado = 'A' and bono_interno.bono_id in (". $ofertedBonusIds[0]->{'mandante_detalle.valor'} .")";
            $ofertedBonusInfo = $BonoInterno->execQuery($Transaction, $sql);

            //Llenado de información bonos ofertados
            $ofertedBonus = [];
            $ofertedBonus = array_map(function($bonus) {
                $bonusData['bonoId'] = $bonus->{'bono_interno.bono_id'};
                $bonusData['nombre'] = $bonus->{'bono_interno.nombre'};
                $bonusData['descripcion'] = $bonus->{'bono_interno.descripcion'};
                $bonusData['tipo'] = $bonus->{'bono_interno.tipo'};
                $bonusData['imagen'] = $bonus->{'bono_interno.imagen'};
                return $bonusData;
            }, $ofertedBonusInfo);
            $awards[$key]['bonosInfo']['bonosOferta'] = $ofertedBonus;
        }
    }

    //Formateo de objeto para usuario premiado
    $awardedUser = [];
    $awardedUser['idUser'] = $possibleAwardedUser->{'logro_referido.usuid_referido'};
    $awardedUser['iconUser'] = 'https://images.virtualsoft.tech/m/msjT1696427955.png';
    $awardedUser['userName'] = $possibleAwardedUser->{'usuario.nombre'};;
    $awardedUser['premios'] = $awards;
    array_push($awardedUsers, $awardedUser);
}

//Formato de respuesta
$response["code"] = 0;
$response["data"]['TotalCount'] = $totalCount;
$response["data"]["AwardedUsers"] = $awardedUsers;
?>