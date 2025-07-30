<?php
use Backend\dto\UsuarioMandante;
use Backend\dto\BonoInterno;
use Backend\sql\Transaction;
use Backend\dto\Usuario;
use Backend\dto\LogroReferido;

/**
 * Obtiene los usuarios referidos que no han generado premios.
 *
 * @param object $json Objeto JSON que contiene la solicitud.
 *  - params: object Parámetros de la solicitud.
 *    - Start: int Valor de inicio para la paginación.
 *    - Limit: int Límite de resultados para la paginación.
 *  - session: object Sesión del usuario.
 *    - usuario: int ID del usuario de la sesión.
 * @return array Respuesta con el código de estado y los usuarios no premiados.
 *  - code: int Código de estado de la respuesta.
 *  - data: array Datos de los usuarios no premiados.
 *    - UnawardedUsers: array Lista de usuarios no premiados.
 *      - idUser: int ID del usuario.
 *      - iconUser: string URL del icono del usuario.
 *      - userName: string Nombre del usuario.
 *      - premios: array Lista de premios del usuario.
 *        - id: int ID del premio.
 *        - deposito: string Condición de depósito.
 *        - deposito_ValorObjetivo: string Valor objetivo del depósito.
 *        - apuesta: string Condición de apuesta.
 *        - apuesta_ValorObjetivo: string Valor objetivo de la apuesta.
 *        - verificado: string Condición de verificación.
 *        - verificado_ValorObjetivo: int Valor objetivo de la verificación.
 *        - fechaExpiraCondicion: string Fecha de expiración de la condición.
 *        - estado: string Estado del premio.
 *        - bonosInfo: array Información de los bonos.
 *          - redimible: int Indica si el premio es redimible.
 * @throws Exception Si ocurre un error al procesar la solicitud.
 */

$params = $json->params;
$start = (int)$params->Start; // Se obtiene el valor de inicio para la paginación.
$limit = (int)$params->Limit; // Se obtiene el límite de resultados para la paginación.

$Transaction = new Transaction(); // Se crea una nueva transacción para la ejecución de consultas.
$UsuarioMandante = new UsuarioMandante($json->session->usuario); // Se inicializa el objeto UsuarioMandante con el usuario de sesión.
$Usuario = new Usuario($UsuarioMandante->getUsuarioMandante()); // Se obtiene el usuario asociado al mandante.

/** Se solicita el id de los referidos que cuentan con al menos un logro pendiente */
# Este recurso no tiene COUNT pues Front solicita dicho valor mediante api/api/command/count_unawarded_referred_user.php
#
$sql = "select usuid_referido, usuario.nombre
        from logro_referido
        inner join usuario on (logro_referido.usuid_referido = usuario.usuario_id)
        where usuid_referente = " . $Usuario->usuarioId . "
        and ((logro_referido.estado = 'CE' and logro_referido.estado_grupal = 'F') /*Condicion expirada*/
        or
       (logro_referido.fecha_expira is not null and logro_referido.fecha_expira < now() and
        logro_referido.estado_grupal = 'P') /*Condicion expirada*/
        or
       (logro_referido.estado_grupal = 'F') /*Premio fallido*/
        )
        group by usuid_referido
        order by usuid_referido desc
        limit " . $start . "," . $limit;
$BonoInterno = new BonoInterno();
$possibleUnawardedUsers = $BonoInterno->execQuery($Transaction, $sql);

$unawardedUsers = [];
foreach($possibleUnawardedUsers as $possibleUnawardedUser) {
    $sql = "select distinct tipo_premio, usuid_referido
            from logro_referido
            inner join mandante_detalle on (logro_referido.tipo_premio = mandante_detalle.manddetalle_id)
            where mandante_detalle.mandante = {$Usuario->mandante}
            and mandante_detalle.pais_id = {$Usuario->paisId}
            and mandante_detalle.estado = 'A'
            and ((estado_grupal = 'F') or (estado_grupal = 'P' and fecha_expira < now()))
            and usuid_referido = {$possibleUnawardedUser->{'logro_referido.usuid_referido'}}";
    $awardsIds = $BonoInterno->execQuery($Transaction, $sql);
    $Usuario = new Usuario($possibleUnawardedUser->{'logro_referido.usuid_referido'});

// Recolectando estado de los premios
$LogroReferido = new LogroReferido();
$isAwarded = false; // Variable para verificar si se ha otorgado un premio
$awards = []; // Inicializa un array para almacenar los premios
foreach($awardsIds as $awardId) {
        $awardStatus = [];
        // Se obtiene el estado del premio en base al ID de transacción y al ID del premio
        $awardStatus = $LogroReferido->getEstadoPremio($Transaction, $awardId->{'logro_referido.tipo_premio'}, $possibleUnawardedUser->{'logro_referido.usuid_referido'});
        if($awardStatus['redimible']) continue; //Si el premio está disponible o fue reclamado, no se envía por este recurso


        /** Construyendo las etiquetas de respuesta para frontend */
        //Id es el tipo_premio/Mandante_detalle, cada una de las llaves posteriores en el array $award corresponden a una etiqueta visible en front
        $achievements = array_keys($awardStatus['logros']);
        $award = [];
        $award['id'] = $awardStatus['tipoPremio'];

        //Condiciones
        if(in_array('CONDMINFIRSTDEPOSITREFERRED', $achievements)) {
            $award['deposito'] = $awardStatus['logros']['CONDMINFIRSTDEPOSITREFERRED'];
            $award['deposito_ValorObjetivo'] = $awardStatus['valoresObjetivo']['CONDMINFIRSTDEPOSITREFERRED'] . ' ' . $Usuario->moneda;
        }

        // Condición para apuestas referidas
        if(in_array('CONDMINBETREFERRED', $achievements)){
            $award['apuesta'] = $awardStatus['logros']['CONDMINBETREFERRED'];
            $award['apuesta_ValorObjetivo'] = $awardStatus['valoresObjetivo']['CONDMINBETREFERRED'] . ' ' . $Usuario->moneda;
        }

        // Condición para usuarios verificados referidos
        if(in_array('CONDVERIFIEDREFERRED', $achievements)) {
            $award['verificado'] = $awardStatus['logros']['CONDVERIFIEDREFERRED'];
            $award['verificado_ValorObjetivo'] = (int)$awardStatus['valoresObjetivo']['CONDVERIFIEDREFERRED'];
        }

        // Fecha de expiración de las condiciones
        $award['fechaExpiraCondicion'] = $awardStatus['fechaExpiraCondicion'];

        // Estado global del premio
        $award['estado'] = $awardStatus['estado'];

        // Almacenando premio y sus etiquetas
        if(!$awardStatus['redimible']) $award['bonosInfo']['redimible'] = 0;
        array_push($awards, $award);
        unset($award);
    }

    // Filtra premios vacíos y reindexa el array
    $awards = array_filter($awards);
    $awards = array_values($awards);

    //Almacenando usuario sin premios redimidos o sin premios por redimir
    $unawardedUser = [];
    // Almacena el ID del usuario
    $unawardedUser['idUser'] = $possibleUnawardedUser->{'logro_referido.usuid_referido'};
    // Icono del usuario
    $unawardedUser['iconUser'] = 'https://images.virtualsoft.tech/m/msjT1696427955.png';
    // Nombre del usuario
    $unawardedUser['userName'] = $possibleUnawardedUser->{'usuario.nombre'};
    // Almacena los premios del usuario
    $unawardedUser['premios'] = $awards;
    array_push($unawardedUsers, $unawardedUser);
    unset($awards);
}

/**
 * Código que configura la respuesta de la API.
 *
 * Asigna un código de respuesta y los usuarios no premiados a la variable de respuesta.
 */
$response["code"] = 0;
$response["data"]["UnawardedUsers"] = $unawardedUsers;
?>