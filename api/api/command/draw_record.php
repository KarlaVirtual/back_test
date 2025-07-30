<?php



use Backend\dto\Banco;
use Backend\dto\BonoDetalle;
use Backend\dto\Categoria;
use Backend\dto\Clasificador;
use Backend\dto\ConfigurationEnvironment;
use Backend\dto\Consecutivo;
use Backend\dto\CuentaCobro;
use Backend\dto\Descarga;
use Backend\dto\DocumentoUsuario;
use Backend\dto\IntApuestaDetalle;
use Backend\dto\IntCompetencia;
use Backend\dto\IntDeporte;
use Backend\dto\IntEventoApuesta;
use Backend\dto\IntEventoApuestaDetalle;
use Backend\dto\IntEventoDetalle;
use Backend\dto\IntRegion;
use Backend\dto\ItTicketEnc;
use Backend\dto\Mandante;
use Backend\dto\MandanteDetalle;
use Backend\dto\PaisMoneda;
use Backend\dto\PromocionalLog;
use Backend\dto\PuntoVenta;
use Backend\dto\Registro;
use Backend\dto\TransaccionJuego;
use Backend\dto\TransjuegoLog;
use Backend\dto\Usuario;
use Backend\dto\UsuarioBanco;
use Backend\dto\UsuarioConfig;
use Backend\dto\UsuarioConfiguracion;
use Backend\dto\UsuarioLog;
use Backend\dto\UsuarioMandante;
use Backend\dto\UsuarioOtrainfo;
use Backend\dto\UsuarioPerfil;
use Backend\dto\UsuarioPremiomax;
use Backend\dto\UsuarioPublicidad;
use Backend\dto\UsuarioRecarga;
use Backend\dto\UsuarioToken;
use Backend\dto\UsuarioMensaje;
use Backend\dto\ProductoMandante;
use Backend\dto\ProdMandanteTipo;
use Backend\dto\Proveedor;
use Backend\dto\Producto;
use Backend\dto\Pais;
use Backend\dto\SorteoDetalle2;
use Backend\dto\SorteoInterno2;
use Backend\dto\TicketEnc;
use Backend\dto\UsuarioSorteo;
use Backend\dto\UsuarioSorteo2;
use Backend\integrations\casino\IES;
use Backend\integrations\casino\JOINSERVICES;
use Backend\mysql\ConsecutivoMySqlDAO;
use Backend\mysql\CuentaCobroMySqlDAO;
use Backend\mysql\DocumentoUsuarioMySqlDAO;
use Backend\mysql\PromocionalLogMySqlDAO;
use Backend\mysql\PuntoVentaMySqlDAO;
use Backend\mysql\RegistroMySqlDAO;
use Backend\mysql\TransaccionJuegoMySqlDAO;
use Backend\mysql\UsuarioBancoMySqlDAO;
use Backend\mysql\UsuarioBannerMySqlDAO;
use Backend\mysql\UsuarioConfiguracionMySqlDAO;
use Backend\mysql\UsuarioLogMySqlDAO;
use Backend\mysql\UsuarioMandanteMySqlDAO;
use Backend\mysql\UsuarioMensajeMySqlDAO;
use Backend\mysql\UsuarioMySqlDAO;
use Backend\mysql\UsuarioOtrainfoMySqlDAO;
use Backend\mysql\SorteoInterno2MySqlDAO;
use Backend\mysql\UsuarioPerfilMySqlDAO;
use Backend\mysql\UsuarioPremiomaxMySqlDAO;
use Backend\mysql\UsuarioRecargaMySqlDAO;
use Backend\mysql\UsuarioSorteo2MySqlDAO;
use Backend\mysql\UsuarioTokenMySqlDAO;
use Backend\websocket\WebsocketUsuario;

/**
 *Registra Tickets a sorteos con base en la información proporcionada
* @param string $data Información proporcionada
* @param string $ck Cookie de sesión
* @param string $site_id ID del sitio
* @param string $idUser ID del usuario
 *
 * @return array
 *  - code (int) Código de respuesta
 *  - rid (string) ID de la respuesta
 *  - data (array) Datos de respuesta
 */

/**
 * Ejecuta una consulta SQL y devuelve el resultado.
 *
 * @param string $sql La consulta SQL a ejecutar.
 * @return mixed El resultado de la consulta, convertido en un objeto.
 */
function execQuery($sql){

    $SorteoInternoMySqlDAO = new SorteoInterno2MySqlDAO();
    $return = $SorteoInternoMySqlDAO->querySQL($sql);
    $return=json_decode(json_encode($return), FALSE);

    return $return;
}

//Recepción de parámetros
$data = $json->params->data;

$cookie = $json->params->ck;
$site_id = $json->params->site_id;
$site_id = strtolower($site_id);

$usuarioId = $json->params->idUser;
$ticketId = $json->params->ticketId;
$lotteryId = $json->params->lotteryId;

$UsuarioSorteo2 = new usuarioSorteo2();
$CheckTicket = $UsuarioSorteo2->queryBycode($ticketId);

if($CheckTicket){
    throw new Exception("Ticket ya registrado", 110000);
}

try {
    $TicketEnc = new ItTicketEnc($ticketId);
} catch (\Exception $e) {
    if($e->getCode() == 24){
        throw new Exception("ENo existe ticket id", 110001);
    }
}

// Se obtiene el valor de la apuesta del objeto TicketEnc
$valorApuesta = $TicketEnc->vlrApuesta;

// Se obtiene el estado de la apuesta del objeto TicketEnc
$EstadoApuesta = $TicketEnc->betStatus;

// Se crea una instancia de la clase SorteoInterno2 con el id de la lotería
$sorteoInterno2 = new SorteoInterno2($lotteryId);

    $sorteoid=0; // Inicializa la variable para el ID del sorteo
    $ususorteo_id=0; // Inicializa la variable para el ID del usuario del sorteo
    $valorASumar=0; // Inicializa la variable para el valor a sumar
    
    // $sqlSorteos = "SELECT * FROM sorteo_interno2 INNER JOIN  where now() < fecha_fin and mandante = ".$site_id." and estado = 'A'";
    // $sqlSorteos = "select a.ususorteo2_id,a.sorteo2_id,a.fecha_crea,sorteo_interno2.condicional,sorteo_interno2.tipo from usuario_sorteo2 a INNER JOIN sorteo_interno2 ON (a.sorteo2_id = sorteo_interno2.sorteo2_id) where now() < sorteo_interno2.fecha_fin and sorteo_interno2.mandante=".$site_id." and sorteo_interno2.estado = 'A'";

    // Consulta SQL para seleccionar la información de los sorteos disponibles asociados al usuario
    $sqlSorteos = "select a.ususorteo2_id,a.sorteo2_id,a.apostado,a.fecha_crea,sorteo_interno2.condicional,sorteo_interno2.tipo from usuario_sorteo2 a INNER JOIN sorteo_interno2 ON (sorteo_interno2.sorteo2_id = a.sorteo2_id) where a.estado = 'A' AND (sorteo_interno2.tipo = 2 OR sorteo_interno2.tipo = 5) AND a.sorteo2_id='".$sorteoInterno2->sorteoId. " 'AND a.registro2_id='".$usuarioId. "'";

// Ejecuta la consulta y almacena el resultado en la variable $sorteoDisponible
$sorteoDisponible = execQuery($sqlSorteos);

    if(count($sorteoDisponible) == 0 || NULL){
        if($sorteoid == 0 || NUll){
            $sqlSorteoDetalle2 = "select * from sorteo_detalle2 a where a.sorteo2_id='".$sorteoInterno2->sorteoId."' AND (moneda='' OR moneda='".$UsuarioMandanteSite->moneda."')";

            $sorteodetalles2 = execQuery($sqlSorteoDetalle2);

            // Inicialización de variables para el procesado de resultados del sorteo
            $tipoProducto = "";

            $cumplecondicion = true;
            $cumplecondicionProducto = false;
            $condicionesProducto = 0;
            $sorteoId = 0;
            $valorApostado = 0;
            $valorAsumar = 0;

           
            $sePuedeSimples=0;
            $sePuedeCombinadas=0;
            $sePuedeAmbas = 0;
            $minselcount=0;
        
            $ganaSorteoId=0;
            $tiposorteo="";
            $ganaSorteoId=0;

            // Determina el tipo de comparación basado en la condición del sorteo interno
            if($sorteoInterno2->condicional = 'NA'|| $sorteoInterno2->condicional == ''){
                $tipocomparacion = "OR";
            }else{
                $tipocomparacion = $sorteoInterno2->condicional;
            }

            foreach ($sorteodetalles2 as $key => $value) {

                switch ($sorteodetalles2->{"a.tipo"}){
                    // Caso para manejar el tipo de producto
                    case "TIPOPRODUCTO":
                        $tipoProducto = $sorteodetalles2->{"a.valor"};
                        break;

                    // Caso para manejar el precio mínimo de apuesta
                    case "MINBETPRICE":
                        $MinbetPrice = $sorteodetalles2->{"a.valor"}; 
                        if($valorApuesta > $MinbetPrice){
                            $cumplecondicion = true;
                        }  
                        break;

                    // Caso para manejar el precio mínimo de venta (sin lógica implementada)
                    case "MINSELPRICE":
                        break;

                    // Caso para suscripciones de usuario (sin lógica implementada)
                    case "USERSUBSCRIBE":
                        break;   

                    // Caso para manejar el precio mínimo de venta total
                    case "MINSELPRICETOTAL":
                        $MINSELPRICETOTAL = $sorteodetalles2->{"a.valor"};
                        if($valorApuesta > $MINSELPRICETOTAL){
                            $cumplecondicion = true;
                        }

                    // Caso para manejar la relación con Expedia
                    case "EXPEDIA":
                        $fechaSorteo = date("Y-m-d H:i:s",strtotime($sorteoInterno2->fechaCrea.' + '.$sorteodetalles->{"a.valor"}. 'days'));
                        $fecha_actual = date("Y-m-d H:i:s",time());
    
                        if ($fechaSorteo < $fecha_actual) {
                            $cumplecondicion = false;
                        }    

                        break;
    
                    case "CONDPAISUSER":
                        break;
                
                        case "FROZEWALLET":
                            break;
                            
                        case "SUPPRESSWITHDRAWAL":
                            break;    
    
                        case "SCHEDULECOUNT":
                            break;
    
                        case "SCHEDULENAME":
                            break; 
                        
                        case "SCHEDULEPERIOD": 
                            break;
    
    
                        case "SCHEDULEPERIODTYPE":
                            break;
                            
                        case "ITAINMENT1":
                            break;
    
                        case "ITAINMENT3":
                            break;
                            
                        case "ITAINMENT4":
                            break;
                           
                        case "ITAINMENT5":
                            break;
                        case "ITAINMENT82":
                            if($sorteodetalles2->{"a.valor"} == 1){
                                $sePuedeSimples = 1;
                            }

                            if($sorteodetalles2->{"a.valor"} == 2){
                                $sePuedeCombinadas = 2;
                            }

                            if($sorteodetalles2->{"a.valor"} == 3){
                                $sePuedeAmbas = 3;
                            }

                    }    
                    //Determina cumplimiento de múltiples condiciones
                    if($cumplecondicion && $cumplecondicionProducto || $condicionesProducto ==0){
                        $sorteoId = $sorteoInterno2->sorteoId;
                    }
            }
           
        }
                    //Define UsuarioSorteo2
                    $UsuarioSorteo2 = new UsuarioSorteo2();
                    $UsuarioSorteo2->setSorteo($sorteoId);
                    $UsuarioSorteo2->setRegistro2($usuarioId);
                    $UsuarioSorteo2->setValor($valorApuesta);
                    $UsuarioSorteo2->setPosicion(0);
                    $UsuarioSorteo2->setValorBase(0);
                    $UsuarioSorteo2->setUsuCreaId(0);
                    $UsuarioSorteo2->setUsumodifId(0);
                    $UsuarioSorteo2->setEstado("A");
                    $UsuarioSorteo2->setErrorId(0);
                    $UsuarioSorteo2->setIdExterno(0);
                    $UsuarioSorteo2->SetMandante(0);
                    $UsuarioSorteo2->setCodigo($ticketId);

                    //Almacena UsuarioSorteo2
                    $UsuarioSorteo2MySqlDAO = new UsuarioSorteo2MySqlDAO();
                    $UsuarioSorteo2MySqlDAO->insert($UsuarioSorteo2);
                    $UsuarioSorteo2MySqlDAO->getTransaction()->commit();
    }



    $response = array();
    $response["code"] = 0;
    $response["rid"] = $json->rid;
    $response["data"] = array();

?>