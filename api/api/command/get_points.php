<?php

use Backend\dto\Banco;
use Backend\dto\BonoDetalle;
use Backend\dto\Categoria;
use Backend\dto\Clasificador;
use Backend\dto\CompetenciaPuntos;
use Backend\dto\ConfigurationEnvironment;
use Backend\dto\Consecutivo;
use Backend\dto\CuentaCobro;
use Backend\dto\Departamento;
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
use Backend\mysql\UsuarioPerfilMySqlDAO;
use Backend\mysql\UsuarioPremiomaxMySqlDAO;
use Backend\mysql\UsuarioRecargaMySqlDAO;
use Backend\mysql\UsuarioTokenMySqlDAO;
use Backend\websocket\WebsocketUsuario;
$params = $json->params; // Parámetros obtenidos del objeto JSON
$country = $params->country; // País a partir de los parámetros
$site_id = $params->site_id; // ID del sitio a partir de los parámetros

$Pais = new Pais('', $country); // Creación de una nueva instancia de la clase Pais
$Mandante = new Mandante($site_id); // Creación de una nueva instancia de la clase Mandante
$lista = []; // Inicialización de un arreglo vacío para la lista de departamentos

// Comprobación de condiciones para la configuración del país y el mandante
if ($Mandante->mandante == 0 && $Pais->paisId == 60) {
    $rules = []; // Inicialización de un arreglo vacío para las reglas de filtrado

    // Agregar reglas de filtrado para el país
    array_push($rules, ['field' => 'departamento.pais_id', 'data' => $Pais->paisId, 'op' => 'eq']);

    $filter = json_encode(['rules' => $rules, 'groupOp' => 'AND']); // Codificación de reglas en formato JSON

    $Departamento = new Departamento(); // Creación de una nueva instancia de la clase Departamento
    $query = $Departamento->getDartementosCustom('departamento.depto_id', 'ASC', 0 , 100000, $filter, true, $Mandante->mandante); // Obtención de departamentos personalizados
    $query = json_decode($query, true); // Decodificación del resultado JSON a un arreglo asociativo

    // Iteración sobre cada uno de los departamentos obtenidos
    foreach($query['data'] as $value) {
        /*Declaración y almacenamiento de un punto de venta para un departamento/Provincia*/
        $data = [];
        $data['Id'] = $value['departamento.depto_id'];
        $data['Name'] = $value['departamento.depto_nom'];
        $data['lat'] = floatval($value['departamento.latitud']);
        $data['lng'] = floatval($value['departamento.longitud']);
        $data['Points'] = [];

        // Adición de información del punto de venta al arreglo de datos
        array_push($data['Points'], array(
            'PuntoDeVenta' => $data['Name'],
            'Direccion' => '',
            'Telefono' => '8407-9595',
            'Indicaciones' => 'Contacta nuestro servicio al cliente al <a style="color: blue;font-weight: bold;" href="https://wa.me/message/HH4P5RCAHNOUP1">8407-9595</a> para conocer tu punto más cercano.',
            'log',
            'lat' => floatval($data['lat']),
            'lng' => floatval($data['lng'])
        ));

        array_push($lista, $data); // Agregar la información del departamento a la lista
    }

} else {
    /*Filtrado dinàmico a puntos de venta con ubicación*/
    $rules = [];
    array_push($rules, array("field" => "usuario.ubicacion_latitud", "data" => "", "op" => "ne"));
    array_push($rules, array("field" => "usuario.ubicacion_longitud", "data" => "", "op" => "ne"));
    array_push($rules, array("field" => "usuario.estado", "data" => "A", "op" => "eq"));
    array_push($rules, array("field" => "usuario.ubicacion_longitud", "data" => "0", "op" => "ne"));
    array_push($rules, array("field" => "usuario.ubicacion_latitud", "data" => "0", "op" => "ne"));
    array_push($rules, array("field" => "usuario.pais_id", "data" => "1", "op" => "ne"));

    if ($Pais->paisId != '') {
        array_push($rules, array("field" => "usuario.pais_id", "data" => $Pais->paisId, "op" => "eq"));
    }


    array_push($rules, array("field" => "punto_venta.propio", "data" => "S", "op" => "eq"));

    if ($site_id != '') {
        array_push($rules, array("field" => "usuario.mandante", "data" => $site_id, "op" => "eq"));

    } else {
        array_push($rules, array("field" => "usuario.mandante", "data" => "0", "op" => "eq"));

    }


    array_push($rules, array("field" => "usuario.mandante", "data" => "1", "op" => "ne"));

    $filtro = array("rules" => $rules, "groupOp" => "AND");
    $json = json_encode($filtro);

    $SkeepRows = 0;
    $MaxRows = 1000;

    // Solicitud dinaámica de los puntos de venta
    $Usuario = new Usuario();
    $usuarios = $Usuario->getUsuariosSuperCustom(" punto_venta.descripcion,departamento.depto_id,departamento.depto_nom,departamento.longitud lngdepto ,departamento.latitud latdepto,usuario.nombre,punto_venta.direccion,punto_venta.telefono,usuario.ubicacion_longitud,usuario.ubicacion_latitud ", "departamento.depto_id", "asc", $SkeepRows, $MaxRows, $json, true);

    $usuarios = json_decode($usuarios);

    $icon = 'https://images.virtualsoft.tech/m/msjT1661995413.png';

    if ($site_id == '0') {
        $icon = 'https://images.virtualsoft.tech/m/msjT1661995385.png';
    }

    $lista = [];

    foreach ($usuarios->data as $key => $value) {
        /*Proceso definición y almacenado de los puntos de venta*/

        $enc = false;
        $cont = 0;
        foreach ($lista as $item) {

            if ($item["Id"] == $value->{'departamento.depto_id'}) {
                $enc = true;

                $lista_temp2 = [];
                $lista_temp2['PuntoDeVenta'] = ($value->{"punto_venta.descripcion"});
                $lista_temp2['Direccion'] = ($value->{"punto_venta.direccion"});
                $lista_temp2['Telefono'] = ($value->{"punto_venta.telefono"});
                $lista_temp2['Indicaciones'] = '';
                $lista_temp2['lat'] = floatval($value->{"usuario.ubicacion_latitud"});
                $lista_temp2['lng'] = floatval($value->{"usuario.ubicacion_longitud"});
                $lista_temp2['iconBase'] = $icon;

                array_push($lista[$cont]['Points'], ($lista_temp2)); // Agrega el punto de venta encontrado

            }
            $cont++;

        }

        if (!$enc) {
            $lista_temp = [];
            $lista_temp['Id'] = $value->{"departamento.depto_id"};
            $lista_temp['Name'] = ($value->{"departamento.depto_nom"});
            $lista_temp['lat'] = floatval($value->{"departamento.latdepto"});
            $lista_temp['lng'] = floatval($value->{"departamento.lngdepto"});
            $lista_temp['Points'] = array();


            $lista_temp2 = [];
            $lista_temp2['PuntoDeVenta'] = ($value->{"usuario.nombre"});
            $lista_temp2['Direccion'] = ($value->{"punto_venta.direccion"});
            $lista_temp2['Telefono'] = ($value->{"punto_venta.telefono"});
            $lista_temp2['Indicaciones'] = '';
            $lista_temp2['lat'] = floatval($value->{"usuario.ubicacion_latitud"});
            $lista_temp2['lng'] = floatval($value->{"usuario.ubicacion_longitud"});
            array_push($lista_temp['Points'], ($lista_temp2)); // Agrega el punto de venta asociado al nuevo departamento

            array_push($lista, ($lista_temp)); // Agrega el nuevo departamento a la lista

        }


    }

    /*Declaración de filtrado - Competencia cercana al punto de venta*/
    $rules = [];
    array_push($rules, array("field" => "competencia.publico_mapa", "data" => "S", "op" => "eq"));
    if ($site_id != '') {
        array_push($rules, array("field" => "competencia.mandante", "data" => $site_id, "op" => "eq"));

    } else {
        array_push($rules, array("field" => "competencia.mandante", "data" => "0", "op" => "eq"));

    }
    $filtro = array("rules" => $rules, "groupOp" => "AND");
    $json = json_encode($filtro);

    // Crea una nueva instancia de la clase CompetenciaPuntos
    $CompetenciaPuntos = new CompetenciaPuntos();

    // Llama al método getCompetenciaPuntosCustom en el objeto $CompetenciaPuntos para obtener los mandantes
    $mandantes = $CompetenciaPuntos->getCompetenciaPuntosCustom("competencia_puntos.*,departamento.*", "competencia_puntos.comppunto_id", "asc", $SkeepRows, $MaxRows, $json, true);

    // Decodifica el resultado JSON en un objeto PHP
    $mandantes = json_decode($mandantes);

    // Inicializa un array vacío para almacenar los resultados finales
    $final = [];

    foreach ($mandantes->data as $key => $value) {
        $enc = false;
        $cont = 0;
        foreach ($lista as $item) {

            if ($item["Id"] == $value->{'departamento.depto_id'}) {
                $enc = true;

                $lista_temp2 = [];
                $lista_temp2['PuntoDeVenta'] = ($value->{"competencia_puntos.nombre"});
                $lista_temp2['Direccion'] = ($value->{"competencia_puntos.direccion"});
                $lista_temp2['Telefono'] = ($value->{"competencia_puntos.telefono"});
                $lista_temp2['Indicaciones'] = '';
                $lista_temp2['lat'] = floatval($value->{"competencia_puntos.latitud"});
                $lista_temp2['lng'] = floatval($value->{"competencia_puntos.longitud"});
                $lista_temp2['iconBase'] = $icon;

                array_push($lista[$cont]['Points'], ($lista_temp2));

            }
            $cont++;

        }
        if (!$enc) {
            $lista_temp = [];
            $lista_temp['Id'] = $value->{"departamento.depto_id"};
            $lista_temp['Name'] = ($value->{"departamento.depto_nom"});
            $lista_temp['lat'] = floatval($value->{"departamento.latdepto"});
            $lista_temp['lng'] = floatval($value->{"departamento.lngdepto"});
            $lista_temp['Points'] = array();


            $lista_temp2 = [];
            $lista_temp2['PuntoDeVenta'] = ($value->{"competencia_puntos.nombre"});
            $lista_temp2['Direccion'] = ($value->{"competencia_puntos.direccion"});
            $lista_temp2['Telefono'] = ($value->{"competencia_puntos.telefono"});
            $lista_temp2['Indicaciones'] = '';
            $lista_temp2['lat'] = floatval($value->{"competencia_puntos.latitud"});
            $lista_temp2['lng'] = floatval($value->{"competencia_puntos.longitud"});
            array_push($lista_temp['Points'], ($lista_temp2));

            array_push($lista, ($lista_temp));

        }


    }
}
$response["data"] = $lista;
