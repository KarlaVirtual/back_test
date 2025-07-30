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

/**
 *Define la proporción y actuar de interfaces respecto a diferentes casos de uso
 *@param string $json->params->recurso Recurso a consultar
 *@return array $response Respuesta de la consulta
 *  - string GRID_URL URL de la API
 *  - string GRID_DATATYPE Tipo de dato
 *  - array GRID_COLNAMES Nombres de las columnas
 *  - array GRID_COLMODEL Modelo de columnas
 *  - int GRID_ROWNUM Número de filas
 *  - array GRID_ROWLIST Lista de filas
 *  - string GRID_SORTNAME Campo de ordenamiento
 *  - string GRID_SORTORDER Orden de clasificación
 *  - string GRID_EDITURL URL de edición
 *  - string GRID_CAPTION Título de la cuadrícula
 *  - bool GRID_EDITAR Indica si se puede editar
 *  - bool GRID_ELIMINAR Indica si se puede eliminar
 *  - bool GRID_AGREGAR Indica si se puede agregar
 *  - bool GRID_GROUPING Indica si se puede agrupar
 *  - array GRID_GROUPINGVIEW Vista de agrupamiento
 *  - array GRID_SELECT Opciones de selección
 *  - bool PERMISO Indica si se tiene permiso
 *  - int data_source Fuente de datos
 */
$recurso = $json->params->recurso;

switch (strtolower($recurso)) {


    /**
     * get_filtre puntos_venta
     *
     * @param no
     *
     * @return no
     * @throws no
     *
     * @access public
     * @see no
     * @since no
     * @deprecated no
     */
    case "puntos_venta":
        /*Definición de comportamiento y presentación para interfaz de punto de venta */
        $GRID_URL = "https://admin.doradobet.com/restapi/api/usuario2_xml/";
        $GRID_EDITAR = false;
        $GRID_ELIMINAR = false;
        $GRID_AGREGAR = false;
        $GRID_GROUPING = true;

        $GRID_DATATYPE = "xml";
        $GRID_COLNAMES = ['Id', 'Login', 'Nombre', 'Agrupador'];
        $GRID_COLMODEL = [
            array(name => 'id', index => 'usuario_id', width => 40, hidden => true, search => false, editable => false),
            array(name => 'login', index => 'login', hidden => true, width => 90, search => false),
            array(name => 'nombre', index => 'nombre', width => 285, editable => false, search => true, stype => 'text', searchoptions => array(sopt => ['cn'])),
            array(name => 'perfil', index => 'perfil', align => 'center', width => 140, editable => false),

        ];

        $GRID_ROWNUM = 100;
        $GRID_ROWLIST = [100, 200, 300];

        $GRID_SORTNAME = "nombre";
        $GRID_SORTORDER = "asc";
        $GRID_EDITURL = "";

        $GRID_CAPTION = "Puntos de Venta / Usuarios Online";

        //Definición de color en la presentación
        $GRID_GROUPINGVIEW = array(

            groupField => ['perfil'],
            groupColumnShow => [false],
            groupText => ['<b><font color="#DF3A01">{0}</font></b>'],
            groupCollapse => true,
            groupOrder => ['desc'],
        );
        $GRID_SELECT = [];

        break;

    /**
     * get_filtre concesionario
     *
     * @param no
     *
     * @return no
     * @throws no
     *
     * @access public
     * @see no
     * @since no
     * @deprecated no
     */
    case "concesionario":
        /*Definición de comportamiento y presentación para interfaz de un consecionario */

        $GRID_URL = "https://admin.doradobet.com/restapi/api/usuario4_xml/";
        // Permitir edición en la cuadrícula
        $GRID_EDITAR = false;
        // Permitir eliminación de registros en la cuadrícula
        $GRID_ELIMINAR = false;
        // Permitir agregar registros a la cuadrícula
        $GRID_AGREGAR = false;
        // Activar agrupamiento en la cuadrícula
        $GRID_GROUPING = true;

        /*Configuración tamaño de la grilla*/
        $GRID_DATATYPE = "xml";
        $GRID_COLNAMES = ['Nro', 'Login', 'Nombre', 'Agrupador'];
        $GRID_COLMODEL = [
            array(name => 'id', index => 'a.usuario_id', width => 50, align => 'center', hidden => false, editable => false, search => true, stype => 'text', searchoptions => array(sopt => ['cn'])),
            array(name => 'login', index => 'login', hidden => true, width => 90, search => false),
            array(name => 'nombre', index => 'nombre', width => 285, editable => false, search => true, stype => 'text', searchoptions => array(sopt => ['cn'])),
            array(name => 'perfil', index => 'perfil', align => 'center', width => 140, editable => false),

        ];

        // Número de filas a mostrar en la cuadrícula
        $GRID_ROWNUM = 100;
        $GRID_ROWLIST = [100, 200, 300];

        $GRID_SORTNAME = "a.nombre";
        $GRID_SORTORDER = "asc";
        $GRID_EDITURL = "";

        $GRID_CAPTION = "Concesionarios";

        //Definición de color en la presentación
        $GRID_GROUPINGVIEW = array(

            groupField => ['perfil'],
            groupColumnShow => [false],
            groupText => ['<b><font color="#DF3A01">{0}</font></b>'],
            groupCollapse => true,
            groupOrder => ['desc'],
        );
        $GRID_SELECT = [];

        break;

    /**
     * get_filtre departamento
     *
     * @param no
     *
     * @return no
     * @throws no
     *
     * @access public
     * @see no
     * @since no
     * @deprecated no
     */
    case "departamento":
        /*Definición de comportamiento y presentación para interfaz de un departamento */
        $GRID_URL = "https://admin.doradobet.com/restapi/api/departamento_xml/";
        $GRID_EDITAR = false;
        $GRID_ELIMINAR = false;
        $GRID_AGREGAR = false;
        $GRID_GROUPING = false;

        /*Definición tamaño de la grilla que se presentará*/
        $GRID_DATATYPE = "xml";
        $GRID_COLNAMES = ['Id', 'Nombre Departamento'];
        $GRID_COLMODEL = [
            array(name => 'id', index => 'a.depto_id', width => 50, align => 'center', hidden => true, editable => false, search => false, stype => 'text', searchoptions => array(sopt => ['cn'])),
            array(name => 'nombre', index => 'a.depto_nom', width => 300, editable => false, search => true, stype => 'text', searchoptions => array(sopt => ['cn'])),
        ];

        $GRID_ROWNUM = 100;
        $GRID_ROWLIST = [100, 200, 300];

        $GRID_SORTNAME = "a.depto_nom";
        $GRID_SORTORDER = "asc";
        $GRID_EDITURL = "";

        $GRID_CAPTION = "Departamentos";
        $GRID_GROUPINGVIEW = "";
        $GRID_SELECT = [];

        break;

    /**
     * get_filtre ciudad
     *
     * @param no
     *
     * @return no
     * @throws no
     *
     * @access public
     * @see no
     * @since no
     * @deprecated no
     */
    case "ciudad":

        $GRID_URL = "https://admin.doradobet.com/restapi/api/ciudad_xml/?depto_id="; // URL de la API para obtener los datos de ciudades
        $GRID_EDITAR = false; // Indica si se pueden editar los registros
        $GRID_ELIMINAR = false; // Indica si se pueden eliminar los registros
        $GRID_AGREGAR = false; // Indica si se pueden agregar nuevos registros
        $GRID_GROUPING = true; // Habilita el agrupamiento en la cuadrícula

        $GRID_DATATYPE = "xml"; // Tipo de datos a recibir de la API
        $GRID_COLNAMES = ['Id', 'Departamento', 'Nombre Ciudad']; // Nombres de las columnas a mostrar
        $GRID_COLMODEL = [ // Configuración del modelo de columnas
            array(name => 'id', index => 'a.ciudad_id', width => 50, align => 'center', hidden => false, editable => false, search => false, stype => 'text', searchoptions => array(sopt => ['cn'])),
            array(name => 'depto_nom', index => 'b.depto_nom', width => 250, editable => false, search => false, stype => 'text', searchoptions => array(sopt => ['cn'])),
            array(name => 'ciudad_nom', index => 'a.ciudad_nom', width => 300, editable => false, search => true, stype => 'text', searchoptions => array(sopt => ['cn'])),
        ];

        $GRID_ROWNUM = 100;
        $GRID_ROWLIST = [100, 200, 300];

        $GRID_SORTNAME = "b.depto_nom,a.ciudad_nom";
        $GRID_SORTORDER = "asc";
        $GRID_EDITURL = "";

        $GRID_CAPTION = "Ciudades";
        $GRID_GROUPINGVIEW = array(
            groupField => ['depto_nom'],
            groupColumnShow => [false],
            groupText => ['<b><font color="#DF3A01">{0}</font></b>'],
            groupCollapse => true,
            groupOrder => ['asc'],
        );
        $GRID_SELECT = []; // Opciones de selección

        break;

    /**
     * get_filtre pais
     *
     * @param no
     *
     * @return no
     * @throws no
     *
     * @access public
     * @see no
     * @since no
     * @deprecated no
     */
    case "pais":

        $GRID_URL = "https://admin.doradobet.com/restapi/api/pais_xml/";  // URL del API para obtener los datos de países
        $GRID_EDITAR = false;  // Indica si la edición está permitida
        $GRID_ELIMINAR = false;  // Indica si la eliminación está permitida
        $GRID_AGREGAR = false;  // Indica si la adición de nuevos registros está permitida
        $GRID_GROUPING = false;  // Indica si la agrupación de datos está permitida

        $GRID_DATATYPE = "xml";  // Tipo de dato esperado (XML)
        $GRID_COLNAMES = ['Nro', 'Descripcion'];  // Nombres de las columnas a mostrar
        $GRID_COLMODEL = [  // Modelo de columnas que define propiedades de cada columna
            array(name => 'id', index => 'a.pais_id', width => 50, align => 'center', hidden => true, editable => false, search => true, stype => 'text', searchoptions => array(sopt => ['cn'])),
            array(name => 'pais_nom', index => 'pais_nom', width => 285, editable => false, search => true, stype => 'text', searchoptions => array(sopt => ['cn'])),
        ];

        $GRID_ROWNUM = 100;
        $GRID_ROWLIST = [100, 200, 300];

        $GRID_SORTNAME = "a.pais_nom";
        $GRID_SORTORDER = "asc";
        $GRID_EDITURL = "";

        $GRID_CAPTION = "Paises";
        $GRID_GROUPINGVIEW = "";
        $GRID_SELECT = [];

        break;

    /**
     * get_filtre producto
     *
     * @param no
     *
     * @return no
     * @throws no
     *
     * @access public
     * @see no
     * @since no
     * @deprecated no
     */
    case "producto":

    $GRID_URL = "https://admin.doradobet.com/restapi/api/producto_xml/"; // URL del API para obtener productos
    $GRID_EDITAR = false; // Indica si se puede editar
    $GRID_ELIMINAR = false; // Indica si se puede eliminar
    $GRID_AGREGAR = false; // Indica si se puede agregar
    $GRID_GROUPING = false; // Indica si se puede agrupar

        $GRID_DATATYPE = "xml";
        $GRID_COLNAMES = ['Nro', 'Descripcion'];
        $GRID_COLMODEL = [
            array(name => 'id', index => 'a.producto_id', width => 50, align => 'center', hidden => true, editable => false, search => true, stype => 'text', searchoptions => array(sopt => ['cn'])),
            array(name => 'descripcion', index => 'descripcion', width => 285, editable => false, search => true, stype => 'text', searchoptions => array(sopt => ['cn'])),
        ];

    $GRID_ROWNUM = 100; // Número de filas a mostrar por defecto
    $GRID_ROWLIST = [100, 200, 300]; // Opciones de filas a mostrar

    $GRID_SORTNAME = "a.descripcion"; // Campo por defecto para ordenar
    $GRID_SORTORDER = "asc"; // Orden de clasificación por defecto
    $GRID_EDITURL = ""; // URL para edición (no está configurada)

    $GRID_CAPTION = "Paises"; // Título de la cuadrícula
    $GRID_GROUPINGVIEW = ""; // Vista de agrupamiento (no está configurada)
    $GRID_SELECT = []; // Opciones de selección (no está configurada)

        break;

    /**
     * get_filtre proveedor
     *
     * @param no
     *
     * @return no
     * @throws no
     *
     * @access public
     * @see no
     * @since no
     * @deprecated no
     */
    case "proveedor":

        // URL para obtener datos del proveedor en formato XML
        $GRID_URL = "https://admin.doradobet.com/restapi/api/proveedor_xml/";
        $GRID_EDITAR = false;
        $GRID_ELIMINAR = false;
        $GRID_AGREGAR = false;
        $GRID_GROUPING = false;

        // Tipo de datos a utilizar
        $GRID_DATATYPE = "xml";
        $GRID_COLNAMES = ['Nro', 'Descripcion'];
        $GRID_COLMODEL = [
            array(name => 'id', index => 'a.proveedor_id', width => 50, align => 'center', hidden => true, editable => false, search => true, stype => 'text', searchoptions => array(sopt => ['cn'])),
            array(name => 'descripcion', index => 'descripcion', width => 285, editable => false, search => true, stype => 'text', searchoptions => array(sopt => ['cn'])),
        ];

        // Número de filas a mostrar
        $GRID_ROWNUM = 100;
        $GRID_ROWLIST = [100, 200, 300];

        $GRID_SORTNAME = "a.descripcion";
        $GRID_SORTORDER = "asc";
        $GRID_EDITURL = "";

        // Título del grid
        $GRID_CAPTION = "Paises";
        $GRID_GROUPINGVIEW = "";
        $GRID_SELECT = [];

        break;

}

//Almacenamiento de la respuesta
$response = array("code" => 0, "rid" => "$json->rid", "data" => array(
    "GRID_URL" => $GRID_URL,
    "GRID_DATATYPE" => $GRID_DATATYPE,
    "GRID_COLNAMES" => $GRID_COLNAMES,
    "GRID_COLMODEL" => $GRID_COLMODEL,
    "GRID_ROWNUM" => $GRID_ROWNUM,
    "GRID_ROWLIST" => $GRID_ROWLIST,
    "GRID_SORTNAME" => $GRID_SORTNAME,
    "GRID_SORTORDER" => $GRID_SORTORDER,
    "GRID_EDITURL" => $GRID_EDITURL,
    "GRID_CAPTION" => $GRID_CAPTION,
    "GRID_EDITAR" => $GRID_EDITAR,
    "GRID_ELIMINAR" => $GRID_ELIMINAR,
    "GRID_AGREGAR" => $GRID_AGREGAR,
    "GRID_GROUPING" => $GRID_GROUPING,
    "GRID_GROUPINGVIEW" => $GRID_GROUPINGVIEW,
    "GRID_SELECT" => $GRID_SELECT,
    "PERMISO" => true,
    "data_source" => 5,
));

