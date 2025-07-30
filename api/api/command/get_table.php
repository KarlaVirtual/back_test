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
 * Este script maneja la configuración de tablas dinámicas para diferentes recursos en la aplicación.
 * Según el recurso solicitado, se configuran las columnas, datos, y opciones de edición, eliminación y agrupamiento.
 *
 * @param object $json Objeto JSON que contiene los parámetros de entrada:
 * @param string $params->recurso Nombre del recurso solicitado.
 * @param array $params->deptos Lista de departamentos.
 * @param array $params->paises Lista de países.
 * @param array $params->puntos Lista de puntos de venta.
 * @param array $params->concesionarios Lista de concesionarios.
 * @param array $params->ciudades Lista de ciudades.
 * @param array $params->productos Lista de productos.
 * @param array $params->proveedores Lista de proveedores.
 * @param array $params->estado Lista de estados.
 * @param array $params->estadoProducto Lista de estados de productos.
 * @param string $params->where->from_date Fecha de inicio en formato 'Y-m-d'.
 * @param string $params->where->to_date Fecha de fin en formato 'Y-m-d'.
 * @param array $params->promocionId Lista de IDs de promociones (solo para el recurso "promocional_detalle").
 * @param string $session->usuario Usuario de la sesión.
 * @param string $session->sid ID de la sesión.
 * 
 * 
 *
 * @return array $response Respuesta con la configuración de la tabla:
 *  - code (int): Código de respuesta (0 para éxito).
 *  - rid (string): ID de la solicitud.
 *  - data (array): Configuración de la tabla, incluyendo:
 *    - GRID_URL (string): URL para obtener los datos de la tabla.
 *    - GRID_DATATYPE (string): Tipo de datos (e.g., "xml").
 *    - GRID_COLNAMES (array): Nombres de las columnas.
 *    - GRID_COLMODEL (array): Modelo de las columnas.
 *    - GRID_ROWNUM (int): Número de filas por página.
 *    - GRID_ROWLIST (array): Opciones de filas por página.
 *    - GRID_SORTNAME (string): Nombre de la columna para ordenar.
 *    - GRID_SORTORDER (string): Orden de clasificación ("asc" o "desc").
 *    - GRID_EDITURL (string): URL para editar registros.
 *    - GRID_CAPTION (string): Título de la tabla.
 *    - GRID_EDITAR (bool): Indica si se permite editar.
 *    - GRID_ELIMINAR (bool): Indica si se permite eliminar.
 *    - GRID_AGREGAR (bool): Indica si se permite agregar.
 *    - GRID_GROUPING (bool): Indica si se permite agrupar.
 *    - GRID_GROUPINGVIEW (array): Configuración de agrupamiento.
 *    - GRID_SELECT (array): Campos seleccionados.
 *    - data_source (int): Fuente de datos (valor fijo 5).
 */

$recurso = $json->params->recurso; // Recurso obtenido de los parámetros JSON
$deptos = implode(', ', $json->params->deptos); // Departamentos convertidos a cadena
$paises = implode(', ', $json->params->paises); // Países convertidos a cadena
$puntos = implode(', ', $json->params->puntos); // Puntos convertidos a cadena
$concesionarios = implode(', ', $json->params->concesionarios); // Concesionarios convertidos a cadena
$ciudades = implode(', ', $json->params->ciudades); // Ciudades convertidas a cadena
$productos = implode(', ', $json->params->productos); // Productos convertidos a cadena
$proveedores = implode(', ', $json->params->proveedores); // Proveedores convertidos a cadena
$estado = implode(', ', $json->params->estado); // Estado convertido a cadena
$estadoProducto = implode(', ', $json->params->estadoProducto); // Estado del producto convertido a cadena
$fecha_ini = $json->params->where->from_date; // Fecha de inicio obtenida de los parámetros JSON
$fecha_fin = $json->params->where->to_date; // Fecha de fin obtenida de los parámetros JSON

if ($fecha_ini == "" || $fecha_ini == null) {
    // Si la fecha de inicio está vacía o es nula, establecemos fechas por defecto
    $fecha_ini = time(); // Fecha de inicio por defecto
    $fecha_fin = time(); // Fecha de fin por defecto
}

// $time = strtotime(substr($fecha_ini, 0, 10)); // Comentado: conversión de fecha de inicio
$time = $fecha_ini; // Asignación de tiempo a la fecha de inicio
$fecha_ini = date('Y-m-d', $time); // Formato de fecha de inicio a 'Y-m-d'

//$time = strtotime(substr($fecha_fin, 0, 10)); // Comentado: conversión de fecha de fin
$time = $fecha_fin; // Asignación de tiempo a la fecha de fin
$fecha_fin = date('Y-m-d', $time); // Formato de fecha de fin a 'Y-m-d'

switch (strtolower($recurso)) {

    /**
     * get_table contingencia
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
    case "contingencia":

        // URL del API para obtener los datos en formato XML
        $GRID_URL = "https://admin.doradobet.com/restapi/api/perfil3_xml/";

        // Tipo de dato que se espera recibir
        $GRID_DATATYPE = "xml";
        $GRID_COLNAMES = ['Perfil', 'Descripción', 'Apuesta Minima', 'Contingencia'];

        // Configuración de las columnas de la tabla, incluyendo opciones de edición y búsqueda
        $GRID_COLMODEL = [
            array('name' => 'id', index => 'perfil_id', width => 130, search => false, editable => false, editoptions => array(maxlength => 15), editrules => array(required => true)),
            array('name' => 'descripcion', index => 'descripcion', width => 350, editable => true, editoptions => array(maxlength => 50), editrules => array(required => true), search => true, stype => 'text', searchoptions => array(sopt => ['cn'])),
            array('name' => 'apuesta_min', index => 'apuesta_min', hidden => true, width => 95, align => 'right', editable => true, formatter => 'number', editrules => array(required => false), formatoptions => array(decimalSeparator => ".", thousandsSeparator => ",", decimalPlaces => 0, defaultValue => '-'), search => false),
            array('name' => 'contingencia', index => 'contingencia', align => 'center', hidden => true, width => 80, editable => false, search => false),
        ];

        // Número de filas a mostrar en la tabla
        $GRID_ROWNUM = 100;

        // Opciones de cantidad de filas a mostrar
        $GRID_ROWLIST = [100, 200, 300];

        // Nombre de la columna por la cual se ordenarán los datos
        $GRID_SORTNAME = "descripcion";

        // Orden de la columna (ascendente o descendente)
        $GRID_SORTORDER = "asc";

        // Título o encabezado de la tabla
        $GRID_CAPTION = "Lista de perfiles";

        // Habilita la opción de editar registros
        $GRID_EDITAR = true;

        // Habilita la opción de eliminar registros
        $GRID_ELIMINAR = true;

        // Habilita la opción de agregar nuevos registros
        $GRID_AGREGAR = true;

        // URL para actualizar un registro
        $GRID_EDITURL = "https://admin.doradobet.com/restapi/api/perfil_actualizar/?editar=" . (($GRID_EDITAR) ? 'true' : 'false');

        break;

    /**
     * Manejo de la gestión de contacto
     *
     * Esta sección del código se encarga de establecer las configuraciones necesarias para la
     * interfaz de gestión de contacto. Se define la URL del servicio web, los nombres de las columnas,
     * el modelo de las columnas, y otras configuraciones para la visualización de los datos.
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
    case "gestion_contacto":
        /*El código seleccionado es un caso dentro de una estructura switch que maneja la configuración de una tabla para la gestión de contactos. Aquí está la descripción detallada:
         URL del API: Define la URL del API para obtener los datos en formato XML.
         Tipo de datos: Especifica que el tipo de datos esperado es XML.
         Nombres de columnas: Define los nombres de las columnas que se mostrarán en la tabla.
         Modelo de columnas: Configura cada columna de la tabla, incluyendo propiedades como el nombre, el índice, la anchura, si es editable, si es buscable, y las opciones de búsqueda.
         Número de filas: Establece el número de filas a mostrar por defecto.
         Lista de filas: Define las opciones de cantidad de filas a mostrar.
         Nombre de la columna de ordenación: Especifica la columna por la cual se ordenarán los datos.
         Orden de la columna: Define el orden de la columna (ascendente).
         Título de la tabla: Establece el título o encabezado de la tabla.
         Opciones de edición: Habilita la opción de editar, eliminar y agregar registros.
         URL de edición: Define la URL para actualizar un registro, incluyendo un parámetro que indica si la edición está habilitada.
         Este caso configura una tabla para la gestión de contactos con varias opciones de personalización y edición. */

        $GRID_URL = "https://admin.doradobet.com/restapi/api/gestion_contacto_xml/?estado=A";

        $GRID_DATATYPE = "xml";
        $GRID_COLNAMES = ['Id', 'Fecha/Hora Creación', 'Apellidos', 'Nombres', 'Empresa', 'Email', 'Teléfono', 'Pais', 'Provincia', 'Dirección', 'Skype', 'Observacion', 'Fecha/Hora Ultima Modif', 'Usuario Ultima Modif'];
        $GRID_COLMODEL = [
            array(name => 'id', index => 'contactocom_id', hidden => true, width => 40, search => false, editable => false),
            array(name => 'fecha_crea', index => 'a.fecha_crea', align => 'center', width => 130, editable => false, search => true, stype => 'text', searchoptions => array(sopt => ['cn'])),
            array(name => 'apellidos', index => 'a.apellidos', width => 120, editable => false, search => true, stype => 'text', searchoptions => array(sopt => ['cn'])),
            array(name => 'nombres', index => 'a.nombres', width => 120, editable => false, search => true, stype => 'text', searchoptions => array(sopt => ['cn'])),
            array(name => 'empresa', index => 'a.empresa', width => 100, editable => false, search => true, stype => 'text', searchoptions => array(sopt => ['cn'])),
            array(name => 'email', index => 'a.email', width => 180, editable => false, search => true, stype => 'text', searchoptions => array(sopt => ['cn'])),
            array(name => 'telefono', index => 'a.telefono', width => 80, align => 'center', editable => false, search => true, stype => 'text', searchoptions => array(sopt => ['cn'])),
            array(name => 'pais_nom', index => 'b.pais_nom', width => 90, align => 'center', editable => false, search => true, stype => 'text', searchoptions => array(sopt => ['cn'])),
            array(name => 'depto_nom', index => 'c.depto_nom', width => 90, align => 'center', editable => false, search => true, stype => 'text', searchoptions => array(sopt => ['cn'])),
            array(name => 'direccion', index => 'a.direccion', width => 110, editable => false, search => true, stype => 'text', searchoptions => array(sopt => ['cn'])),
            array(name => 'skype', index => 'a.skype', width => 80, align => 'center', editable => false, search => true, stype => 'text', searchoptions => array(sopt => ['cn'])),
            array(name => 'observacion', index => 'a.observacion', width => 250, editable => false, search => true, stype => 'text', searchoptions => array(sopt => ['cn'])),
            array(name => 'fecha_modif', index => 'a.fecha_modif', align => 'center', width => 130, editable => false, search => false),
            array(name => 'usumodif_id', index => 'a.usumodif_id', align => 'left', width => 150, editable => false, search => false),
        ];
        $GRID_ROWNUM = 100;
        $GRID_ROWLIST = [100, 200, 300];
        $GRID_SORTNAME = "a.fecha_crea,a.apellidos,a.nombres";
        $GRID_SORTORDER = "asc";
        $GRID_CAPTION = "Trabaja con nosotros";
        $GRID_EDITAR = true;
        $GRID_ELIMINAR = true;
        $GRID_AGREGAR = true;
        $GRID_EDITURL = "https://admin.doradobet.com/restapi/api/perfil_actualizar/?editar=" . (($GRID_EDITAR) ? 'true' : 'false');

        break;

    /**
     * get_table usuario_admin_lista
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
    case "usuario_admin_lista":

        /*El código configura una tabla para listar administradores de usuarios, especificando la URL de la API, el tipo de datos, los nombres y
         modelos de las columnas, y otras opciones de visualización y búsqueda.*/
        $GRID_URL = "https://admin.doradobet.com/restapi/api/usuario_admin_lista_xml/?estado=A&perfil_id=";

        $GRID_DATATYPE = "xml"; // Tipo de datos que se espera de la API
        $GRID_COLNAMES = ['Nro', 'Login', 'Nombre', 'Ciudad', 'Nombre Contacto', 'Telefono', 'Fecha Creacion', 'Fecha Ultima Entrada', 'Email', 'Concesionario', 'Subconcesionario']; // Nombres de las columnas a mostrar
        $GRID_COLMODEL = [
            array(name => 'id', index => 'a.usuario_id', align => 'center', width => 65, hidden => false, search => false, editable => false, search => true, stype => 'text', searchoptions => array(sopt => ['eq'])), // Modelo de la columna ID
            array(name => 'login', index => 'a.login', width => 115, editable => false, search => true, stype => 'text', searchoptions => array(sopt => ['cn'])), // Modelo de la columna Login
            array(name => 'nombre', index => 'a.nombre', width => 270, editable => false, search => true, stype => 'text', searchoptions => array(sopt => ['cn'])), // Modelo de la columna Nombre
            array(name => 'ciudad', index => 'g.ciudad_nom', width => 140, editable => false, search => true, stype => 'text', searchoptions => array(sopt => ['cn'])), // Modelo de la columna Ciudad
            array(name => 'nombre_contacto', index => 'f.nombre_contacto', width => 160, editable => false, search => true, stype => 'text', searchoptions => array(sopt => ['cn'])), // Modelo de la columna Nombre Contacto
            array(name => 'telefono', index => 'f.telefono', width => 110, editable => false, search => true, stype => 'text', searchoptions => array(sopt => ['cn'])), // Modelo de la columna Teléfono
            array(name => 'fecha_crea', index => 'a.fecha_crea', align => 'center', width => 125, editable => false, search => true, search => true, stype => 'text', searchoptions => array(sopt => ['cn'])), // Modelo de la columna Fecha de Creación
            array(name => 'fecha_ult', index => 'a.fecha_ult', align => 'center', width => 125, editable => false, search => true, search => true, stype => 'text', searchoptions => array(sopt => ['cn'])), // Modelo de la columna Fecha de Última Entrada
            array(name => 'email', index => 'f.email', width => 270, editable => false, search => true, stype => 'text', searchoptions => array(sopt => ['cn'])), // Modelo de la columna Email
            array(name => 'concesionario', index => 'j.nombre', width => 270, editable => false, search => true, stype => 'text', searchoptions => array(sopt => ['cn'])), // Modelo de la columna Concesionario
            array(name => 'subconcesionario', index => 'k.nombre', width => 270, editable => false, search => true, stype => 'text', searchoptions => array(sopt => ['cn'])), // Modelo de la columna Subconcesionario
        ];

        /*El código configura una tabla para listar administradores de usuarios, especificando la URL de la API, el tipo de datos,
         los nombres y modelos de las columnas, y otras opciones de visualización y búsqueda.*/
        $GRID_ROWNUM = 100; // Número de filas a mostrar por defecto
        $GRID_ROWLIST = [100, 500, 1000]; // Opciones de número de filas a mostrar
        $GRID_SORTNAME = "a.nombre"; // Nombre de la columna por la que se ordenará inicialmente
        $GRID_SORTORDER = "asc"; // Orden de clasificación
        $GRID_EDITURL = ""; // URL para editar registros
        $GRID_CAPTION = "Usuarios"; // Título de la tabla
        $GRID_EDITAR = true; // Permitir edición
        $GRID_ELIMINAR = true; // Permitir eliminación
        $GRID_AGREGAR = true; // Permitir adición de nuevos usuarios

        break;

    /**
     * get_table menu
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
    case "menu":

        $GRID_URL = "https://admin.doradobet.com/restapi/api/menu_xml/";
        $GRID_EDITAR = true; // Indica si se puede editar
        $GRID_ELIMINAR = true; // Indica si se puede eliminar
        $GRID_AGREGAR = true; // Indica si se puede agregar

        $GRID_DATATYPE = "xml"; // Tipo de datos que se espera
        $GRID_COLNAMES = ['Id', 'Descripcion', 'Pagina', 'Orden']; // Nombres de las columnas
        $GRID_COLMODEL = [
            array(name => 'id', index => 'menu_id', width => 40, search => false, editable => false), // Configuración de la columna 'id'
            array(name => 'descripcion', index => 'descripcion', width => 300, editable => $GRID_EDITAR, editoptions => array(maxlength => 100), editrules => array(required => true), search => true, stype => 'text', searchoptions => array(sopt => ['cn'])), // Configuración de la columna 'descripcion'
            array(name => 'pagina', index => 'pagina', width => 300, editable => $GRID_EDITAR, editoptions => array(maxlength => 100), editrules => array(required => true), search => true, stype => 'text', searchoptions => array(sopt => ['cn'])), // Configuración de la columna 'pagina'
            array(name => 'orden', index => 'orden', align => 'center', width => 80, editable => $GRID_EDITAR, editoptions => array(maxlength => 5), editrules => array(required => true, integer => true, minValue => 0), search => false), // Configuración de la columna 'orden'
        ];
        $GRID_ROWNUM = 100; // Número de filas a mostrar
        $GRID_ROWLIST = [100, 200, 300]; // Opciones para el número de filas a mostrar
        $GRID_SORTNAME = "orden"; // Nombre de la columna por la que se ordena
        $GRID_SORTORDER = "asc"; // Orden de la clasificación
        $GRID_EDITURL = "https://admin.doradobet.com/restapi/api/menu_actualizar/?editar=" . (($GRID_EDITAR) ? 'true' : 'false'); // URL para la edición
        $GRID_CAPTION = "Menus"; // Título de la tabla

        break;

    /**
     * get_table perfil
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
    case "perfil":

        $GRID_URL = "https://admin.doradobet.com/restapi/api/perfil_xml/"; // URL para obtener los datos de la tabla de perfiles
        $GRID_EDITAR = true; // Permite la edición de registros
        $GRID_ELIMINAR = true; // Permite la eliminación de registros
        $GRID_AGREGAR = true; // Permite agregar nuevos registros

    $GRID_DATATYPE = "xml"; // Tipo de dato de la fuente de datos
    $GRID_COLNAMES = ['Perfil', 'Descripción', 'Apuesta Minima', 'Tipo', 'Dias Caducidad Clave']; // Nombres de las columnas de la tabla
    $GRID_COLMODEL = [ // Modelo de las columnas de la tabla
        array(name => 'id', index => 'perfil_id', width => 130, search => false, editable => false, editoptions => array(maxlength => 15), editrules => array(required => true)), // Columna para el ID del perfil
        array(name => 'descripcion', index => 'descripcion', width => 350, editable => $GRID_EDITAR, editoptions => array(maxlength => 50), editrules => array(required => true), search => true, stype => 'text', searchoptions => array(sopt => ['cn'])), // Columna para la descripción del perfil
        array(name => 'apuesta_min', index => 'apuesta_min', width => 95, align => 'center', editable => $GRID_EDITAR, formatter => 'number', editrules => array(required => true, number => true, minValue => 1), formatoptions => array(decimalSeparator => ".", thousandsSeparator => ",", decimalPlaces => 0, defaultValue => '-'), search => false), // Columna para la apuesta mínima
        array(name => 'tipo', index => 'tipo', width => 110, align => 'center', editable => $GRID_EDITAR, sortable => false, search => false, stype => 'select', edittype => "select", editoptions => array(value => ":...;A:Administrativo;C:Comercial"), editrules => array(required => true)), // Columna para el tipo de perfil
        array(name => 'dias_clave', index => 'dias_clave', width => 95, align => 'center', editable => $GRID_EDITAR, formatter => 'number', editrules => array(required => true, integer => true, minValue => 0, maxValue => 1000), formatoptions => array(decimalSeparator => ".", thousandsSeparator => ",", decimalPlaces => 0, defaultValue => '-'), search => false), // Columna para los días de caducidad de la clave
    ];
    $GRID_ROWNUM = 100; // Número de filas a mostrar por defecto
    $GRID_ROWLIST = [100, 200, 300]; // Opciones para la cantidad de filas a mostrar
    $GRID_SORTNAME = "descripcion"; // Columna por la que se ordenan los datos
    $GRID_SORTORDER = "asc"; // Orden de la clasificación
    $GRID_EDITURL = "https://admin.doradobet.com/restapi/api/perfil_actualizar/?editar=" . (($GRID_EDITAR) ? 'true' : 'false'); // URL para editar un perfil
    $GRID_CAPTION = "Perfiles"; // Título de la tabla de perfiles

        break;

    /**
     * get_table perfil_opcion
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
    case "perfil_opcion":

        /*El código configura una tabla para gestionar opciones de perfil, especificando la URL de la API, el tipo de datos,
         los nombres y modelos de las columnas, y otras opciones de visualización y edición.*/
        $GRID_URL = "https://admin.doradobet.com/restapi/api/perfil_opcion_xml/";
        $GRID_EDITAR = true;
        $GRID_ELIMINAR = true;
        $GRID_AGREGAR = true;
        $GRID_GROUPING = true;

        $GRID_DATATYPE = "xml";
        $GRID_COLNAMES = ['Id', 'Perfil', 'Menu', 'Submenu', 'Adicionar', 'Editar', 'Eliminar'];
        $GRID_COLMODEL = [
            array(name => 'id', index => 'id', hidden => true, width => 200, search => false, editable => false),
            array(name => 'perfil_id', index => 'perfil_id', width => 200, sortable => false, search => false, stype => 'select', editable => false, edittype => "select", editoptions => array(dataUrl => "https://admin.doradobet.com/restapi/api/lista_perfil/?tipo=B"), editrules => array(required => true)),
            array(name => 'menu_id', index => 'b.menu_id', width => 250, sortable => false, search => true, stype => 'select', editable => false, edittype => "select", editoptions => array(dataUrl => "https://admin.doradobet.com/restapi/api/lista_menu/?tipo=B")),
            array(name => 'submenu_id', index => 'b.submenu_id', width => 250, sortable => false, search => true, stype => 'select', editable => false, edittype => "select", editoptions => array(dataUrl => "https://admin.doradobet.com/restapi/api/lista_submenu/?tipo=B"), editrules => array(required => true)),
            array(name => 'adicionar_opcion', index => 'adicionar_opcion', width => 80, align => 'center', editable => $GRID_EDITAR, sortable => false, search => false, stype => 'select', edittype => "select", editoptions => array(value => ":...;false:No;true:Si"), editrules => array(required => true)),
            array(name => 'editar_opcion', index => 'editar_opcion', width => 80, align => 'center', editable => $GRID_EDITAR, sortable => false, search => false, stype => 'select', edittype => "select", editoptions => array(value => ":...;false:No;true:Si"), editrules => array(required => true)),
            array(name => 'eliminar_opcion', index => 'eliminar_opcion', width => 80, align => 'center', editable => $GRID_EDITAR, sortable => false, search => false, stype => 'select', edittype => "select", editoptions => array(value => ":...;false:No;true:Si"), editrules => array(required => true)),
        ];

        /*El código configura una tabla para gestionar opciones de perfil, estableciendo el número de filas, opciones de filas,
        orden de columnas, URL de edición, título, vista de agrupamiento y campos seleccionados.*/
        $GRID_ROWNUM = 100;
        $GRID_ROWLIST = [100, 200, 300];
        $GRID_SORTNAME = "c.orden,b.descripcion";
        $GRID_SORTORDER = "asc";
        $GRID_EDITURL = "https://admin.doradobet.com/restapi/api/perfil_opcion_actualizar/?editar=" . (($GRID_EDITAR) ? 'true' : 'false');
        $GRID_CAPTION = "Opciones para perfil";
        $GRID_GROUPINGVIEW = array(
            groupField => ['perfil_id'],
            groupColumnShow => [false],
            groupText => ['<b>{0} - {1} Elemento(s)</b>'],
            groupCollapse => true,
            groupOrder => ['asc'],
        );

        $GRID_SELECT = ["perfil_id", "submenu_id"];

        break;

    /**
     * get_table submenu
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
    case "submenu":

        $GRID_URL = "https://admin.doradobet.com/restapi/api/submenu_xml/";
        $GRID_EDITAR = true; // Indica si se puede editar
        $GRID_ELIMINAR = true; // Indica si se puede eliminar
        $GRID_AGREGAR = true; // Indica si se puede agregar
        $GRID_GROUPING = false; // Indica si el agrupamiento está habilitado

        $GRID_DATATYPE = "xml"; // Tipo de datos para la tabla
        $GRID_COLNAMES = ['Id', 'Descripcion', 'Pagina', 'Menu']; // Nombres de las columnas
        $GRID_COLMODEL = [ // Modelo de columnas con propiedades
            array(name => 'id', index => 'submenu_id', width => 40, search => false, editable => false),
            array(name => 'descripcion', index => 'a.descripcion', width => 250, editable => $GRID_EDITAR, editoptions => array(maxlength => 100), editrules => array(required => true), search => true, stype => 'text', searchoptions => array(sopt => ['cn'])),
            array(name => 'pagina', index => 'a.pagina', width => 270, editable => $GRID_EDITAR, editoptions => array(maxlength => 100), editrules => array(required => true), search => true, stype => 'text', searchoptions => array(sopt => ['cn'])),
            array(name => 'menu_id', index => 'a.menu_id', width => 170, sortable => true, search => true, stype => 'select', searchoptions => array(dataUrl => "https://admin.doradobet.com/restapi/api/lista_menu/?tipo=B"), editable => $GRID_EDITAR, edittype => "select", editrules => array(required => true), editoptions => array(dataUrl => "https://admin.doradobet.com/restapi/api/lista_menu/?tipo=E")),
        ];
        $GRID_ROWNUM = 100; // Número de filas a mostrar por defecto
        $GRID_ROWLIST = [100, 200, 300]; // Opciones de selección de número de filas
        $GRID_SORTNAME = "b.orden,a.descripcion"; // Nombre de las columnas para ordenar
        $GRID_SORTORDER = "asc"; // Orden de clasificación
        $GRID_EDITURL = "https://admin.doradobet.com/restapi/api/submenu_actualizar/?editar=" . (($GRID_EDITAR) ? 'true' : 'false'); // URL para actualizar
        $GRID_CAPTION = "Submenus"; // Título de la tabla
        $GRID_GROUPINGVIEW = ""; // Vista de agrupamiento

        break;

    /**
     * get_table usuario_perfil
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
    case "usuario_perfil":

        /*El código configura una tabla para gestionar perfiles de usuario, especificando la URL de la API, el tipo de datos,
         los nombres y modelos de las columnas, y otras opciones de visualización y edición.*/
        $GRID_URL = "https://admin.doradobet.com/restapi/api/usuario_perfil_xml/";
        $GRID_EDITAR = true;
        $GRID_ELIMINAR = true;
        $GRID_AGREGAR = true;
        $GRID_GROUPING = false;

        $GRID_DATATYPE = "xml";
        $GRID_COLNAMES = ['Usuario', 'Perfil'];
        $GRID_COLMODEL = [
            array(name => 'id', index => 'a.usuario_id', width => 350, search => true, stype => 'select', searchoptions => array(dataUrl => "https://admin.doradobet.com/restapi/api/lista_usuario4/?tipo=B"), editable => false, edittype => "select", editoptions => array(dataUrl => "https://admin.doradobet.com/restapi/api/lista_usuario4/?tipo=B"), editrules => array(required => true)),
            array(name => 'perfil_id', index => 'a.perfil_id', width => 300, sortable => true, search => true, stype => 'select', searchoptions => array(dataUrl => "https://admin.doradobet.com/restapi/api/lista_perfil/?tipo=B"), editable => $GRID_EDITAR, editrules => array(required => true), edittype => "select", editoptions => array(dataUrl => "https://admin.doradobet.com/restapi/api/lista_perfil/?tipo=B")),
        ];

        /*El código configura una tabla para gestionar perfiles de usuario, especificando la URL de la API, el tipo de datos, los nombres
         y modelos de las columnas, y otras opciones de visualización y edición.*/
        $GRID_ROWNUM = 100;
        $GRID_ROWLIST = [100, 200, 300];
        $GRID_SORTNAME = "a.usuario_id";
        $GRID_SORTORDER = "asc";
        $GRID_EDITURL = "https://admin.doradobet.com/restapi/api/usuario_perfil_actualizar/?editar=" . (($GRID_EDITAR) ? 'true' : 'false');
        $GRID_CAPTION = "Perfiles para Usuarios";
        $GRID_GROUPINGVIEW = "";

        $GRID_SELECT = ["id"];

        break;

    /**
     * get_table clasificador
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
    case "clasificador":
        /*El código configura una tabla para gestionar clasificadores, especificando la URL de la API, el tipo de datos, los nombres
         y modelos de las columnas, y otras opciones de visualización y edición.*/
        $GRID_URL = "https://admin.doradobet.com/restapi/api/clasificador_xml/";
        $GRID_EDITAR = true;
        $GRID_ELIMINAR = true;
        $GRID_AGREGAR = true;
        $GRID_GROUPING = true;

        $GRID_DATATYPE = "xml";
        $GRID_COLNAMES = ['Id', 'Agrupador', 'Descripcion', 'Tipo', 'Estado'];
        $GRID_COLMODEL = [
            array(name => 'id', index => 'clasificador_id', width => 40, search => false, editable => false),
            array(name => 'agrupa', index => 'a.tipo', width => 40, search => false, editable => false),
            array(name => 'descripcion', index => 'a.descripcion', width => 250, editable => $GRID_EDITAR, editoptions => array(maxlength => 100), editrules => array(required => true), search => true, stype => 'text', searchoptions => array(sopt => ['cn'])),
            array(name => 'tipo', index => 'a.tipo', align => 'center', width => 180, editable => $GRID_EDITAR, sortable => true, search => true, stype => 'select', searchoptions => array(value => ":...;RG:Regional;TP:Tipo Punto;TE=>Tipo Establecimiento"), edittype => "select", editrules => array(required => true), editoptions => array(value => "RG:Regional;TP:Tipo Punto;TE:Tipo Establecimiento")),
            array(name => 'estado', index => 'estado', align => 'center', width => 100, editable => $GRID_EDITAR, sortable => true, search => true, stype => 'select', searchoptions => array(value => ":...;A:Activo;I:Inactivo"), edittype => "select", editrules => array(required => true), editoptions => array(value => "A:Activo;I:Inactivo")),
        ];
        $GRID_ROWNUM = 100;
        $GRID_ROWLIST = [100, 200, 300];
        $GRID_SORTNAME = "a.descripcion";
        $GRID_SORTORDER = "asc";
        $GRID_EDITURL = "https://admin.doradobet.com/restapi/api/clasificador_actualizar/?editar=" . (($GRID_EDITAR) ? 'true' : 'false');
        $GRID_CAPTION = "Clasificadores";
        $GRID_GROUPINGVIEW = array(
            groupField => ['agrupa'],
            groupColumnShow => [false],
            groupText => ['<b><font color="#DF3A01">{0} - ( {1} Clasificadores )</font></b>'],
            groupCollapse => false,
            groupOrder => ['asc'],
        );

        $GRID_SELECT = ["id"];

        break;

    /**
     * get_table concesionario
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

        /*El código configura una tabla para gestionar concesionarios, especificando la URL de la API, el tipo de datos, los nombres
         y modelos de las columnas, y otras opciones de visualización y edición.*/

        $GRID_URL = "https://admin.doradobet.com/restapi/api/concesionario_xml/";
        $GRID_EDITAR = true;
        $GRID_ELIMINAR = true;
        $GRID_AGREGAR = true;
        $GRID_GROUPING = true;

        $GRID_DATATYPE = "xml";
        $GRID_COLNAMES = ['Id', 'Concesionario', 'Subconcesionario', 'Usuario Asociado', 'Concesionario', 'Subconcesionario', 'Es Punto de Venta?'];
        $GRID_COLMODEL = [
            array(name => 'id', index => 'concesionario_id', width => 40, search => false, editable => false),
            array(name => 'usupadre_id', hidden => true, index => 'b.nombre', width => 300, sortable => true, search => true, stype => 'text', searchoptions => array(sopt => ['cn']), editable => $GRID_EDITAR, editrules => array(required => true), edittype => "select", editoptions => array(dataUrl => "https://admin.doradobet.com/restapi/api/lista_usuconcesionario/?tipo=B&clase=C")),
            array(name => 'usupadre2_id', hidden => true, index => 'e.nombre', width => 300, sortable => true, search => true, stype => 'text', searchoptions => array(sopt => ['cn']), editable => $GRID_EDITAR, editrules => array(required => false), edittype => "select", editoptions => array(dataUrl => "https://admin.doradobet.com/restapi/api/lista_ususubconcesionario/?tipo=B&clase=C")),
            array(name => 'usuhijo_id', index => 'c.nombre', width => 350, sortable => true, search => true, stype => 'text', searchoptions => array(sopt => ['cn']), editable => $GRID_EDITAR, editrules => array(required => true), edittype => "select", editoptions => array(dataUrl => "https://admin.doradobet.com/restapi/api/lista_usuconcesionario/?tipo=B&clase=U")),
            array(name => 'concesionario', index => 'b.nombre', width => 210, sortable => true, search => true, stype => 'text', searchoptions => array(sopt => ['cn']), editable => false),
            array(name => 'concesionario2', index => 'e.nombre', width => 210, sortable => true, search => true, stype => 'text', searchoptions => array(sopt => ['cn']), editable => false),
            array(name => 'punto_venta', index => 'punto_venta', align => 'center', width => 60, editable => false, sortable => true, search => false),
        ];
        $GRID_ROWNUM = 100;
        $GRID_ROWLIST = [100, 200, 300];
        $GRID_SORTNAME = "b.nombre,c.nombre";
        $GRID_SORTORDER = "asc";
        $GRID_EDITURL = "https://admin.doradobet.com/restapi/api/concesionario_actualizar/?editar=" . (($GRID_EDITAR) ? 'true' : 'false');
        $GRID_CAPTION = "Usuarios Asociados x Concesionario";
        $GRID_GROUPINGVIEW = array(
            groupField => ['concesionario'],
            groupColumnShow => [false],
            groupText => ['<b><font color="#DF3A01">{0} - ( {1} Usuarios )</font></b>'],
            groupCollapse => false,
            groupOrder => ['asc'],
        );

        $GRID_SELECT = ["usupadre_id", "usupadre2_id", "usuhijo_id"];

        break;

    /**
     * get_table puntoventa
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
    case "puntoventa":
        /*El código configura una tabla para gestionar puntos de venta, especificando la URL de la API, el tipo de datos, los nombres y modelos de las columnas,
         y otras opciones de visualización y edición.*/
        $GRID_URL = "https://admin.doradobet.com/restapi/api/puntoventa_xml/";
        $GRID_EDITAR = true;
        $GRID_ELIMINAR = true;
        $GRID_AGREGAR = true;
        $GRID_GROUPING = false;

        $GRID_DATATYPE = "xml";
        $GRID_COLNAMES = ['Id', 'Descripcion', 'Departamento', 'Ciudad', 'Direccion', 'Barrio', 'Tel&eacute;fono', 'Nombre del Contacto', 'Usuario Ascociado', 'Estado', 'Concesionario'];
        $GRID_COLMODEL = [
            array(name => 'id', index => 'puntoventa_id', align => 'center', width => 30, search => false, editable => false),
            array(name => 'descripcion', index => 'a.descripcion', width => 250, editable => $GRID_EDITAR, editoptions => array(maxlength => 150), editrules => array(required => true), search => true, stype => 'text', searchoptions => array(sopt => ['cn'])),
            array(name => 'depto_id', index => 'depto_id', width => 227, sortable => true, search => false, hidden => true, editable => false, edittype => "select", editrules => array(required => true)),
            array(name => 'ciudad_id', index => 'c.ciudad_nom', width => 120, sortable => false, search => true, stype => 'text', editable => $GRID_EDITAR, editoptions => array(dataUrl => "https://admin.doradobet.com/restapi/api/lista_ciudad/?tipo=B&depto_id=0"), edittype => "select", editrules => array(required => true), searchoptions => array(sopt => ['cn'])),
            array(name => 'direccion', index => 'a.direccion', width => 220, editable => $GRID_EDITAR, editoptions => array(maxlength => 100), editrules => array(required => true), search => true, stype => 'text', searchoptions => array(sopt => ['cn'])),
            array(name => 'barrio', index => 'a.barrio', width => 150, editable => $GRID_EDITAR, editoptions => array(maxlength => 100), editrules => array(required => true), search => true, stype => 'text', searchoptions => array(sopt => ['cn'])),
            array(name => 'telefono', index => 'a.telefono', align => 'center', width => 150, editable => $GRID_EDITAR, editoptions => array(maxlength => 100), editrules => array(required => true), search => true, stype => 'text', searchoptions => array(sopt => ['cn'])),
            array(name => 'nombre_contacto', index => 'nombre_contacto', width => 200, editable => $GRID_EDITAR, editoptions => array(maxlength => 100), editrules => array(required => true), search => true, stype => 'text', searchoptions => array(sopt => ['cn'])),
            array(name => 'usuario_id', index => 'b.nombre', hidden => true, width => 140, sortable => false, search => true, stype => 'text', editable => $GRID_EDITAR, edittype => "select", editoptions => array(dataUrl => "https://admin.doradobet.com/restapi/api/lista_usuario3/?tipo=B&clase=PUNTO"), editrules => array(required => true), searchoptions => array(sopt => ['cn'])),
            array(name => 'estado', index => 'estado', hidden => true, align => 'center', width => 90, editable => $GRID_EDITAR, sortable => true, search => true, stype => 'select', searchoptions => array(value => ":...;A:Activo;I:Inactivo"), edittype => "select", editoptions => array(value => "A:Activo;I:Inactivo")),
            array(name => 'concesionario', index => 'f.nombre', width => 250, editable => false, search => true, stype => 'text', searchoptions => array(sopt => ['cn'])),
        ];
        $GRID_ROWNUM = 100;
        $GRID_ROWLIST = [100, 200, 300];
        $GRID_SORTNAME = "descripcion";
        $GRID_SORTORDER = "asc";
        $GRID_EDITURL = "https://admin.doradobet.com/restapi/api/puntoventa_actualizar/?editar=" . (($GRID_EDITAR) ? 'true' : 'false');
        $GRID_CAPTION = "Puntos de venta";
        $GRID_GROUPINGVIEW = "";

        $GRID_SELECT = ["depto_id"];

        break;

    /**
     * get_table cupo
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
    case "cupo":

        /*El código configura una tabla para gestionar cupos de concesionarios y puntos de venta, especificando la URL de la API, el tipo de datos,
         los nombres y modelos de las columnas, y otras opciones de visualización y edición.*/

        $GRID_URL = "https://admin.doradobet.com/restapi/api/cupo_xml/";
        $GRID_EDITAR = true;
        $GRID_ELIMINAR = true;
        $GRID_AGREGAR = true;
        $GRID_GROUPING = true;

        $GRID_DATATYPE = "xml";
        $GRID_COLNAMES = ['Nro', 'Descripcion', 'Tipo', 'Concesionario', 'Cupo para Apuestas', 'Cupo para Recargas', 'Moneda'];
        $GRID_COLMODEL = [
            array(name => 'id', index => 'a.usuario_id', hidden => true, align => 'center', width => 70, search => false, editable => false),
            array(name => 'nombre', index => 'b.nombre', width => 180, editable => false, search => true, stype => 'text', searchoptions => array(sopt => ['cn'])),
            array(name => 'tipo', index => 'tipo', align => 'center', width => 70, search => false, editable => false),
            array(name => 'concesionario', index => 'd.nombre', hidden => true, width => 170, editable => false, search => true, stype => 'text', searchoptions => array(sopt => ['cn'])),
            array(name => 'creditos_base', index => 'a.creditos_base', width => 80, align => 'right', editable => false, formatter => 'number', formatoptions => array(decimalSeparator => ".", thousandsSeparator => ",", decimalPlaces => 2, defaultValue => '-'), search => false),
            array(name => 'cupo_recarga', index => 'a.cupo_recarga', width => 80, align => 'right', editable => false, formatter => 'number', formatoptions => array(decimalSeparator => ".", thousandsSeparator => ",", decimalPlaces => 2, defaultValue => '-'), search => false),
            array(name => 'moneda', index => 'b.moneda', align => 'center', width => 80, editable => false, search => false),
        ];
        $GRID_ROWNUM = 100;
        $GRID_ROWLIST = [100, 200, 300];
        $GRID_SORTNAME = "b.nombre";
        $GRID_SORTORDER = "asc";
        $GRID_EDITURL = "https://admin.doradobet.com/restapi/api/cupo_actualizar/?editar=" . (($GRID_EDITAR) ? 'true' : 'false');
        $GRID_CAPTION = "Listado de Concesionarios / Puntos de Venta";
        $GRID_GROUPINGVIEW = array(
            groupField => ['tipo'],
            groupColumnShow => [false],
            groupText => ['<b><font color="#DF3A01">{0}</font></b>'],
            groupCollapse => false,
            groupOrder => ['asc'],
        );

        $GRID_SELECT = [];

        break;

    /**
     * get_table bono_it
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
    case "bono_it":

        /*El código configura una tabla para gestionar bonos, especificando la URL de la API, el tipo de datos, los nombres y modelos de las columnas,
        y otras opciones de visualización y edición.*/

        $GRID_URL = "https://admin.doradobet.com/restapi/api/bono_it_xml/";
        $GRID_EDITAR = true;
        $GRID_ELIMINAR = true;
        $GRID_AGREGAR = true;
        $GRID_GROUPING = false;

        $GRID_DATATYPE = "xml";
        $GRID_COLNAMES = ['Id', 'Descripción', 'PlanID', 'Código', 'Tipo', 'Fecha Inicio', 'Fecha Fin', 'Dias Expiración', 'Owner', 'Usuario Ultima Modificación', 'Fecha Modificación'];
        $GRID_COLMODEL = [
            array(name => 'id', index => 'bono_id', hidden => true, width => 40, search => false, editable => false),
            array(name => 'descripcion', index => 'descripcion', width => 200, editable => $GRID_EDITAR, editoptions => array(maxlength => 150), editrules => array(required => true), search => true, stype => 'text', searchoptions => array(sopt => ['cn'])),
            array(name => 'bonusplanid', index => 'bonusplanid', align => 'center', width => 100, editable => $GRID_EDITAR, editoptions => array(maxlength => 100), editrules => array(required => true, integer => true), search => true, stype => 'text', searchoptions => array(sopt => ['cn'])),
            array(name => 'codigo', index => 'codigo', align => 'center', width => 100, editable => $GRID_EDITAR, editoptions => array(maxlength => 50), editrules => array(required => true), search => true, stype => 'text', searchoptions => array(sopt => ['cn'])),
            array(name => 'tipo', index => 'tipo', width => 100, editable => $GRID_EDITAR, edittype => "select", editoptions => array(value => "C:Codigo;D:Depósito;PD:Primer Depósito;F:Free Bet"), editrules => array(required => true), search => true, stype => 'select', searchoptions => array(value => ":...;C:Codigo;D:Deposito;PD:Primer Deposito;F:Free Bet")),
            array(name => 'fecha_ini', index => 'fecha_ini', align => 'center', width => 88, editable => $GRID_EDITAR, formoptions => array(elmsuffix => '  aaaa-mm-dd'), editoptions => array(maxlength => 10), editrules => array(required => true, date => true), search => true, stype => 'text', searchoptions => array(sopt => ['cn'])),
            array(name => 'fecha_fin', index => 'fecha_fin', align => 'center', width => 88, editable => $GRID_EDITAR, formoptions => array(elmsuffix => '  aaaa-mm-dd'), editoptions => array(maxlength => 10), editrules => array(required => true, date => true), search => true, stype => 'text', searchoptions => array(sopt => ['cn'])),
            array(name => 'dias_expira', index => 'a.dias_expira', width => 90, align => 'center', editable => $GRID_EDITAR, editrules => array(required => true, integer => true, minValue => 1), editoptions => array(defaultValue => '1'), formatter => 'number', formatoptions => array(decimalSeparator => ".", thousandsSeparator => ",", decimalPlaces => 0, defaultValue => '-'), search => false),
            array(name => 'owner', index => 'owner', align => 'center', width => 100, editable => $GRID_EDITAR, editoptions => array(maxlength => 50), editrules => array(required => true), search => true, stype => 'text', searchoptions => array(sopt => ['cn'])),
            array(name => 'nombre', index => 'nombre', width => 200, editable => false, search => false),
            array(name => 'fecha_modif', index => 'fecha_modif', align => 'center', width => 140, editable => false, search => false),
        ];
        $GRID_ROWNUM = 100;
        $GRID_ROWLIST = [100, 200, 300];
        $GRID_SORTNAME = "descripcion";
        $GRID_SORTORDER = "asc";
        $GRID_EDITURL = "https://admin.doradobet.com/restapi/api/bono_it_actualizar/?editar=" . (($GRID_EDITAR) ? 'true' : 'false');
        $GRID_CAPTION = "Bonos";
        $GRID_GROUPINGVIEW = "";

        $GRID_SELECT = [];

        break;

    /**
     * get_table cuenta_cobro_eliminar
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
    case "cuenta_cobro_eliminar":

        /*El código configura una tabla para listar notas de retiro eliminadas, especificando la URL de la API, el tipo de datos,
         los nombres y modelos de las columnas, y otras opciones de visualización.*/

        $GRID_URL = "https://admin.doradobet.com/restapi/api/cuenta_cobro_eliminar_xml/";
        $GRID_EDITAR = false;
        $GRID_ELIMINAR = false;
        $GRID_AGREGAR = false;
        $GRID_GROUPING = false;

        $GRID_DATATYPE = "xml";
        $GRID_COLNAMES = ['Nro Nota Retiro', 'Fecha Eliminaci&oacute;n', 'Usuario que la Elimin&oacute;', 'Valor Nota', 'Moneda', 'Fecha Nota', 'Nro Usuario', 'Nombre del Usuario', 'Observaciones'];
        $GRID_COLMODEL = [
            array(name => 'id', index => 'a.cuenta_id', align => 'center', width => 72, editable => false, search => true, stype => 'text', searchoptions => array(sopt => ['cn'])),
            array(name => 'fecha_crea', index => 'a.fecha_crea', align => 'center', width => 135, editable => false, search => true, stype => 'text', searchoptions => array(sopt => ['cn'])),
            array(name => 'usucrea_id', index => 'b.nombre', width => 130, editable => false, search => true, stype => 'text', searchoptions => array(sopt => ['cn'])),
            array(name => 'valor', index => 'a.valor', align => 'right', width => 72, editable => false, editoptions => array(maxlength => 8), editrules => array(required => true, integer => true, minValue => 0), formatter => 'number', formatoptions => array(decimalSeparator => ".", thousandsSeparator => ",", decimalPlaces => 2, defaultValue => '-'), search => false),
            array(name => 'moneda', index => 'b.moneda', align => 'center', width => 80, editable => false, search => false),
            array(name => 'fecha_nota', index => 'a.fecha_nota', align => 'center', width => 135, editable => false, search => true, stype => 'text', searchoptions => array(sopt => ['cn'])),
            array(name => 'usuario_id', index => 'a.usuario_id', align => 'center', width => 75, editable => false, search => true, stype => 'text', searchoptions => array(sopt => ['cn'])),
            array(name => 'nombre', index => 'c.nombre', width => 185, editable => false, search => true, stype => 'text', searchoptions => array(sopt => ['cn'])),
            array(name => 'observ', index => 'a.observ', width => 210, editable => false, search => true, stype => 'text', searchoptions => array(sopt => ['cn'])),
        ];
        $GRID_ROWNUM = 100;
        $GRID_ROWLIST = [100, 200, 300];
        $GRID_SORTNAME = "a.fecha_crea";
        $GRID_SORTORDER = "desc";
        $GRID_EDITURL = "";
        $GRID_CAPTION = "Listado de Notas de Retiro Eliminadas";
        $GRID_GROUPINGVIEW = "";

        $GRID_SELECT = [];

        break;

    /**
     * get_table gestion_red
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
    case "gestion_red":
        /*configura una tabla para la gestión de red, especificando la URL de la API, el tipo de datos, los nombres y modelos de las columnas,
         y otras opciones de visualización y agrupamiento.*/

        $GRID_URL = "https://admin.doradobet.com/restapi/api/gestion_red1_xml/"; // URL del API para la gestión de red
        $GRID_EDITAR = false; // Permitir edición
        $GRID_ELIMINAR = false; // Permitir eliminación
        $GRID_AGREGAR = false; // Permitir agregar
        $GRID_GROUPING = true; // Permitir agrupamiento

        $GRID_DATATYPE = "xml"; // Tipo de datos para la cuadrícula
        $GRID_COLNAMES = ['Nro', 'Perfil', 'Nombre', 'Estado', 'Bloqueo Ventas', 'Observaciones']; // Nombres de las columnas
        $GRID_COLMODEL = [ // Modelo de las columnas
            array(name => 'id', index => 'usuario_id', hidden => true, width => 40, search => false, editable => false), // Columna de ID
            array(name => 'perfil', index => 'perfil', hidden => true, width => 40, search => false, editable => false), // Columna de perfil
            array(name => 'nombre', index => 'a.nombre', width => 285, editable => false, search => true, stype => 'text', searchoptions => array(sopt => ['cn'])), // Columna de nombre
            array(name => 'estado', index => 'a.estado_esp', align => 'center', width => 70, editable => false, sortable => true, search => true, stype => 'select', searchoptions => array(value => ":...;A:Activo;I:Inactivo")), // Columna de estado
            array(name => 'bloqueo_ventas', index => 'a.bloqueo_ventas', align => 'center', width => 60, editable => false, search => true, stype => 'select', searchoptions => array(value => ":...;S:Si;N:No")), // Columna de bloqueo de ventas
            array(name => 'observ', index => 'a.observ', width => 210, editable => false, search => false, stype => 'text', searchoptions => array(sopt => ['cn'])), // Columna de observaciones
        ];
        $GRID_ROWNUM = 100; // Número de filas por página
        $GRID_ROWLIST = [100, 200, 300]; // Opciones de filas por página
        $GRID_SORTNAME = "a.nombre"; // Nombre de la columna para ordenar
        $GRID_SORTORDER = "asc"; // Orden de la clasificación
        $GRID_EDITURL = ""; // URL para editar
        $GRID_CAPTION = "Listado de Notas de Retiro Eliminadas"; // Título de la cuadrícula
        $GRID_GROUPINGVIEW = array( // Configuración de vista de agrupamiento
            groupField => ['perfil'], // Campo para agrupar
            groupColumnShow => [false], // Mostrar o no la columna agrupada
            groupText => ['<b><font color="#DF3A01">{0} - ( {1} Personas )</font></b>'], // Texto de agrupamiento
            groupCollapse => false, // Colapsar grupos por defecto
            groupOrder => ['asc'], // Orden de grupos
        );

        $GRID_SELECT = []; // Selección de cuadrícula

        break;

    /**
     * get_table registro_rapido
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
    case "registro_rapido":

        /*El código configura una tabla para gestionar registros rápidos de usuarios, especificando la URL de la API,
         el tipo de datos, los nombres y modelos de las columnas, y otras opciones de visualización y edición.*/

        $GRID_URL = "https://admin.doradobet.com/restapi/api/registro_rapido_xml/"; // URL para obtener los datos del grid
        $GRID_EDITAR = true; // Permite la edición de registros
        $GRID_ELIMINAR = true; // Permite la eliminación de registros
        $GRID_AGREGAR = true; // Permite agregar nuevos registros
        $GRID_GROUPING = false; // Desactiva la agrupación de registros

        $GRID_DATATYPE = "xml"; // Tipo de dato a recibir
        $GRID_COLNAMES = ['Id', 'Tipo Documento', 'Nro Documento', 'Pais', 'Moneda', 'Primer Apellido', 'Segundo Apellido', 'Primer Nombre', 'Segundo Nombre']; // Nombres de las columnas del grid
        $GRID_COLMODEL = [ // Modelo de columnas que define las propiedades de cada columna
            array(name => 'id', index => 'registro_id', hidden => true, width => 40, search => false, editable => false),
            array(name => 'tipo_doc', index => 'a.tipo_doc', width => 150, editable => $GRID_EDITAR, sortable => true, search => true, stype => 'select', searchoptions => array(value => ":...;C:Cedula de Ciudadania;E:Cedula de Extranjeria;P:Pasaporte"), edittype => "select", editoptions => array(value => "C:Cedula de Ciudadania;E:Cedula de Extranjeria;P:Pasaporte")),
            array(name => 'cedula', index => 'a.cedula', width => 100, editable => $GRID_EDITAR, editoptions => array(maxlength => 100), editrules => array(required => true), search => true, stype => 'text', searchoptions => array(sopt => ['cn'])),
            array(name => 'pais', index => 'pais', width => 160, sortable => true, search => true, stype => 'select', searchoptions => array(dataUrl => "lista_pais2.php?tipo=B"), editable => false),
            array(name => 'moneda', index => 'pais', align => 'center', width => 80, sortable => true, search => false, editable => false),
            array(name => 'apellido1', index => 'a.apellido1', width => 120, editable => $GRID_EDITAR, editoptions => array(maxlength => 20), editrules => array(required => true), search => true, stype => 'text', searchoptions => array(sopt => ['cn'])),
            array(name => 'apellido2', index => 'a.apellido2', width => 120, editable => $GRID_EDITAR, editoptions => array(maxlength => 20), editrules => array(required => false), search => true, stype => 'text', searchoptions => array(sopt => ['cn'])),
            array(name => 'nombre1', index => 'a.nombre1', width => 120, editable => $GRID_EDITAR, editoptions => array(maxlength => 20), editrules => array(required => true), search => true, stype => 'text', searchoptions => array(sopt => ['cn'])),
            array(name => 'nombre2', index => 'a.nombre2', width => 120, editable => $GRID_EDITAR, editoptions => array(maxlength => 20), editrules => array(required => false), search => true, stype => 'text', searchoptions => array(sopt => ['cn'])),
        ];
        $GRID_ROWNUM = 100; // Número de filas a mostrar por defecto
        $GRID_ROWLIST = [100, 200, 300]; // Opciones de selección de filas a mostrar
        $GRID_SORTNAME = "a.apellido1,a.apellido2,a.nombre1,a.nombre2"; // Campos por los cuales se pueden ordenar los datos
        $GRID_SORTORDER = "asc"; // Orden de clasificación (ascendente)
        $GRID_EDITURL = "https://admin.doradobet.com/restapi/api/registro_rapido_actualizar/?editar=" . (($GRID_EDITAR) ? 'true' : 'false'); // URL para editar registros

        $GRID_CAPTION = "Listado de Usuarios con Registro Rapido"; // Título del grid
        $GRID_GROUPINGVIEW = ""; // Vista de agrupación

        $GRID_SELECT = []; // Selección de registros

        break;

    /**
     * get_table cheque_reimpresion
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
    case "cheque_reimpresion":
    // URL para acceder a la API de reimpresión de cheques
    $GRID_URL = "https://admin.doradobet.com/restapi/api/cheque_reimpresion_xml/";

    // Configuración del grid (no se permite editar, eliminar ni agregar)
    $GRID_EDITAR = false;
    $GRID_ELIMINAR = false;
    $GRID_AGREGAR = false;
    $GRID_GROUPING = true;

        $GRID_DATATYPE = "xml";
        $GRID_COLNAMES = ['Nro. Recarga', 'Punto de Venta', 'Nro. Cliente', 'Nombre Cliente', 'Fecha Creacion', 'Valor', 'Moneda'];
        $GRID_COLMODEL = [

            array(name => 'id', index => 'a.recarga_id', hidden => false, align => 'center', width => 80, search => true, editable => false, stype => 'text', searchoptions => array(sopt => ['cn'])),
            array(name => 'puntoventa_id', index => 'a.puntoventa_id', align => 'center', width => 80, search => false),
            array(name => 'usuario_id', index => 'a.usuario_id', align => 'center', width => 80, editable => false, search => true, stype => 'text', searchoptions => array(sopt => ['cn'])),
            array(name => 'nombre', index => 'b.nombre', width => 290, editable => false, search => true, stype => 'text', searchoptions => array(sopt => ['cn'])),
            array(name => 'fecha_crea', index => 'a.fecha_crea', align => 'center', width => 120, editable => false, search => false, stype => 'text', searchoptions => array(sopt => ['cn'])),
            array(name => 'valor', index => 'a.valor', width => 100, align => 'right', editable => false, formatter => 'number', formatoptions => array(decimalSeparator => ".", thousandsSeparator => ",", decimalPlaces => 2, defaultValue => '-'), search => false),
            array(name => 'moneda', index => 'b.moneda', align => 'center', width => 80, editable => false, search => false),

        ];
        /*El código configura una tabla para la reimpresión de cheques, especificando la URL de la API, el tipo de datos, los nombres y
         modelos de las columnas, y otras opciones de visualización y agrupamiento.*/
        $GRID_ROWNUM = 100;
        $GRID_ROWLIST = [100, 200, 300];
        $GRID_SORTNAME = "a.fecha_crea";
        $GRID_SORTORDER = "desc";
        $GRID_EDITURL = "";

        $GRID_CAPTION = "Reimpresión de cheques";
        $GRID_GROUPINGVIEW = array(
            groupField => ['puntoventa_id'],
            groupColumnShow => [false],
            groupText => ['<b><font color="#DF3A01">{0} - ( {1} Juegos )</font></b>'],
            groupCollapse => false,
            groupOrder => ['asc'],
        );

        $GRID_SELECT = [];

        break;

    /**
     * get_table flujo_caja
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
    case "flujo_caja":

        /*El código configura una tabla para gestionar el flujo de caja, especificando la URL de la API, el tipo de datos,
         los nombres y modelos de las columnas, y otras opciones de visualización y agrupamiento.*/

        $GRID_URL = "https://admin.doradobet.com/restapi/api/flujo_caja_xml/?fecha_ini=" . $fecha_ini . "&fecha_fin=" . $fecha_fin . "&tipo=I" . "&usuario_id=" . $puntos . "&concesionario_id=" . $concesionarios . "&depto_id=" . $deptos . "&ciudad_id=" . $ciudades . "&pais_id=" . $paises;
        $GRID_EDITAR = false;
        $GRID_ELIMINAR = false;
        $GRID_AGREGAR = false;
        $GRID_GROUPING = true;

        $GRID_DATATYPE = "xml";
        $GRID_COLNAMES = ['Id', 'Caja Agrupa', 'Fecha', 'Hora', 'No. Ticket', 'Forma Pago 1', 'Valor 1', 'Pago Bono / T.C.', 'Valor 2', 'Valor Entradas Efectivo', 'Valor Entradas Bono / T.C.', 'Valor Entradas Traslados', 'Valor Entradas Recargas', 'Valor Salidas Efectivo', 'Valor Salidas Traslados', 'Valor Salidas Notas de Retiro', 'Saldo', 'Moneda'];
        $GRID_COLMODEL = [
            array(name => 'id', index => 'ticket_id', hidden => true, width => 40, search => false, editable => false),
            array(name => 'punto_venta', index => 'd.nombre', align => 'center', width => 70, editable => false, search => true, stype => 'text', searchoptions => array(sopt => ['cn'])),
            array(name => 'fecha_crea', index => 'x.fecha_crea', align => 'center', width => 86, editable => false, search => true, stype => 'text', searchoptions => array(sopt => ['cn'])),
            array(name => 'hora_crea', index => 'x.hora_crea', align => 'center', width => 50, editable => false, search => true, stype => 'text', searchoptions => array(sopt => ['cn'])),
            array(name => 'ticket_id', index => 'x.ticket_id', align => 'center', width => 86, editable => false, search => true, stype => 'text', searchoptions => array(sopt => ['cn'])),
            array(name => 'forma_pago1', index => "case when x.tipomov_id='S' then '-' else b.descripcion end", width => 80, editable => false, search => true, stype => 'text', searchoptions => array(sopt => ['cn'])),
            array(name => 'valor_form1', index => 'a.valor_forma1', hidden => true, align => 'right', width => 75, editable => false, summaryType => 'sum', summaryTpl => '<b><font color="#DF3A01">array(0)</font></b>', formatter => 'number', formatoptions => array(decimalSeparator => ".", thousandsSeparator => ",", decimalPlaces => 2, defaultValue => '-'), search => false),
            array(name => 'forma_pago2', index => "case when x.tipomov_id='S' then '' else c.descripcion end", width => 70, editable => false, search => false, stype => 'text', searchoptions => array(sopt => ['cn'])),
            array(name => 'valor_form2', index => 'a.valor_forma2', hidden => true, align => 'right', width => 75, editable => false, summaryType => 'sum', summaryTpl => '<b><font color="#DF3A01">array(0)</font></b>', formatter => 'number', formatoptions => array(decimalSeparator => ".", thousandsSeparator => ",", decimalPlaces => 2, defaultValue => '-'), search => false),
            array(name => 'valor_entrada_efectivo', index => 'valor_entrada_efectivo', align => 'right', width => 74, editable => false, summaryType => 'sum', summaryTpl => '<b><font color="#DF3A01">array(0)</font></b>', formatter => 'number', formatoptions => array(decimalSeparator => ".", thousandsSeparator => ",", decimalPlaces => 2, defaultValue => '-'), search => false),
            array(name => 'valor_entrada_bono', index => 'valor_entrada_bono', align => 'right', width => 74, editable => false, summaryType => 'sum', summaryTpl => '<b><font color="#DF3A01">array(0)</font></b>', formatter => 'number', formatoptions => array(decimalSeparator => ".", thousandsSeparator => ",", decimalPlaces => 2, defaultValue => '-'), search => false),
            array(name => 'valor_entrada_traslado', index => 'valor_entrada_traslado', align => 'right', width => 74, editable => false, summaryType => 'sum', summaryTpl => '<b><font color="#DF3A01">array(0)</font></b>', formatter => 'number', formatoptions => array(decimalSeparator => ".", thousandsSeparator => ",", decimalPlaces => 2, defaultValue => '-'), search => false),
            array(name => 'valor_entrada_recarga', index => 'valor_entrada_recarga', align => 'right', width => 74, editable => false, summaryType => 'sum', summaryTpl => '<b><font color="#DF3A01">array(0)</font></b>', formatter => 'number', formatoptions => array(decimalSeparator => ".", thousandsSeparator => ",", decimalPlaces => 2, defaultValue => '-'), search => false),
            array(name => 'valor_salida_efectivo', index => 'valor_salida_efectivo', align => 'right', width => 74, editable => false, summaryType => 'sum', summaryTpl => '<b><font color="#DF3A01">array(0)</font></b>', formatter => 'number', formatoptions => array(decimalSeparator => ".", thousandsSeparator => ",", decimalPlaces => 2, defaultValue => '-'), search => false),
            array(name => 'valor_salida_traslado', index => 'valor_salida_traslado', align => 'right', width => 74, editable => false, summaryType => 'sum', summaryTpl => '<b><font color="#DF3A01">array(0)</font></b>', formatter => 'number', formatoptions => array(decimalSeparator => ".", thousandsSeparator => ",", decimalPlaces => 2, defaultValue => '-'), search => false),
            array(name => 'valor_salida_recarga', index => 'valor_salida_recarga', align => 'right', width => 74, editable => false, summaryType => 'sum', summaryTpl => '<b><font color="#DF3A01">array(0)</font></b>', formatter => 'number', formatoptions => array(decimalSeparator => ".", thousandsSeparator => ",", decimalPlaces => 2, defaultValue => '-'), search => false),
            array(name => 'saldo', index => 'saldo', align => 'right', width => 75, editable => false, summaryType => 'sum', summaryTpl => '<b><font color="#DF3A01">array(0)</font></b>', formatter => 'number', formatoptions => array(decimalSeparator => ".", thousandsSeparator => ",", decimalPlaces => 2, defaultValue => '-'), search => false),
            array(name => 'moneda', index => 'b.moneda', align => 'center', width => 80, editable => false, search => false),
        ];

        /*El código configura una tabla para gestionar el flujo de caja, especificando la URL de la API, el tipo de datos,
         los nombres y modelos de las columnas, y otras opciones de visualización y agrupamiento.*/
        if (stristr($_SESSION["win_perfil"], 'PUNTO')) {
            $GRID_ROWNUM = 250;
            $GRID_ROWLIST = [250, 500, 1000];
        } else {
            $GRID_ROWNUM = 1000;
            $GRID_ROWLIST = [1000, 5000, 10000];
        }

        $GRID_SORTNAME = "d.nombre,x.fecha_crea desc,x.hora_crea";
        $GRID_SORTORDER = "desc";
        $GRID_EDITURL = "";

        $GRID_CAPTION = "Flujo de Caja";
        $GRID_GROUPINGVIEW = array(
            groupField => ['punto_venta'],
            groupColumnShow => [false],
            groupText => ['<b><font color="#DF3A01">{0} - ( {1} Movimientos )</font></b>'],
            groupCollapse => true,
            groupOrder => ['desc'],
        );

        $GRID_SELECT = [];

        break;

    /**
     * get_table pago_nota_cobro
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
    case "pago_nota_cobro":

        /*El código configura una tabla para gestionar el detalle de notas de cobro, especificando la URL de la API, el tipo de datos,
         los nombres y modelos de las columnas, y otras opciones de visualización.*/

        $GRID_URL = "https://admin.doradobet.com/restapi/api/cuenta_cobro_detalle_xml/?cuenta_id=&clave=";
        $GRID_EDITAR = false;
        $GRID_ELIMINAR = false;
        $GRID_AGREGAR = false;
        $GRID_GROUPING = false;

        $GRID_DATATYPE = "xml";
        $GRID_COLNAMES = ['Id', 'Usuario', 'Valor', 'Moneda', 'Pagada?'];
        $GRID_COLMODEL = [
            array(name => 'id', index => 'id', hidden => true, align => 'center', width => 30, search => false, editable => false),
            array(name => 'nombre', index => 'b.nombre', width => 320, search => false, editable => false),
            array(name => 'valor', index => 'valor', align => 'center', width => 130, editable => false, formatter => 'number', formatoptions => array(decimalSeparator => ".", thousandsSeparator => ",", decimalPlaces => 2, defaultValue => '-'), search => false),
            array(name => 'moneda', index => 'b.moneda', align => 'center', width => 80, editable => false, search => false),
            array(name => 'pagada', index => 'pagada', align => 'center', width => 80, search => false, editable => false),
        ];

        /*El código configura una tabla para gestionar el detalle de notas de cobro, especificando la URL de la API, el tipo de datos,
         los nombres y modelos de las columnas, y otras opciones de visualización.*/
        $GRID_ROWNUM = 100;
        $GRID_ROWLIST = [100, 200, 300];

        $GRID_SORTNAME = "a.cuenta_id";
        $GRID_SORTORDER = "asc";
        $GRID_EDITURL = "";

        $GRID_CAPTION = "Detalle de la Nota de Retiro";
        $GRID_GROUPINGVIEW = "";

        $GRID_SELECT = [];

        break;

    /**
     * get_table consulta_flujo_historico
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
    case "consulta_flujo_historico":

        /*El código configura una tabla para gestionar el flujo de caja histórico, especificando la URL de la API,
         el tipo de datos, los nombres y modelos de las columnas, y otras opciones de visualización y agrupamiento.*/

        $GRID_URL = "https://admin.doradobet.com/restapi/api/consulta_flujo_historico_xml/?fecha_ini=" . $fecha_ini . "&fecha_fin=" . $fecha_fin . "&tipo=I" . "&usuario_id=" . $puntos . "&concesionario_id=" . $concesionarios . "&depto_id=" . $deptos . "&ciudad_id=" . $ciudades . "&pais_id=" . $paises;
        $GRID_EDITAR = false;
        $GRID_ELIMINAR = false;
        $GRID_AGREGAR = false;
        $GRID_GROUPING = true;

        $GRID_DATATYPE = "xml";

        $GRID_COLNAMES = ['Id', 'Caja Agrupa', 'Fecha', 'Moneda', 'Cantidad Tickets', 'Valor Entradas Efectivo', 'Valor Entradas Bono / T.C.', 'Valor Entradas Traslados', 'Valor Entradas Recargas', 'Valor Salidas Efectivo', 'Valor Salidas Traslados', 'Valor Salidas Notas de Retiro', 'Saldo'];

        $GRID_COLMODEL = [
            array(name => 'id', index => 'ticket_id', hidden => true, width => 40, search => false, editable => false),
            array(name => 'punto_venta', index => 'd.nombre', align => 'center', width => 70, editable => false, search => true, stype => 'text', searchoptions => array(sopt => ['cn'])),
            array(name => 'fecha_crea', index => 'x.fecha_crea', align => 'center', width => 86, editable => false, search => true, stype => 'text', searchoptions => array(sopt => ['cn'])),
            array(name => 'moneda', index => 'b.moneda', align => 'center', width => 70, editable => false, search => false),
            array(name => 'cant_tickets', index => 'cant_tickets', align => 'right', width => 74, editable => false, summaryType => 'sum', summaryTpl => '<b><font color="#DF3A01">array(0)</font></b>', formatter => 'number', formatoptions => array(decimalSeparator => ".", thousandsSeparator => ",", decimalPlaces => 0, defaultValue => '-'), search => false),
            array(name => 'valor_entrada_efectivo', index => 'valor_entrada_efectivo', align => 'right', width => 84, editable => false, summaryType => 'sum', summaryTpl => '<b><font color="#DF3A01">array(0)</font></b>', formatter => 'number', formatoptions => array(decimalSeparator => ".", thousandsSeparator => ",", decimalPlaces => 2, defaultValue => '-'), search => false),
            array(name => 'valor_entrada_bono', index => 'valor_entrada_bono', align => 'right', width => 84, editable => false, summaryType => 'sum', summaryTpl => '<b><font color="#DF3A01">array(0)</font></b>', formatter => 'number', formatoptions => array(decimalSeparator => ".", thousandsSeparator => ",", decimalPlaces => 2, defaultValue => '-'), search => false),
            array(name => 'valor_entrada_traslado', index => 'valor_entrada_traslado', align => 'right', width => 84, editable => false, summaryType => 'sum', summaryTpl => '<b><font color="#DF3A01">array(0)</font></b>', formatter => 'number', formatoptions => array(decimalSeparator => ".", thousandsSeparator => ",", decimalPlaces => 2, defaultValue => '-'), search => false),
            array(name => 'valor_entrada_recarga', index => 'valor_entrada_recarga', align => 'right', width => 84, editable => false, summaryType => 'sum', summaryTpl => '<b><font color="#DF3A01">array(0)</font></b>', formatter => 'number', formatoptions => array(decimalSeparator => ".", thousandsSeparator => ",", decimalPlaces => 2, defaultValue => '-'), search => false),
            array(name => 'valor_salida_efectivo', index => 'valor_salida_efectivo', align => 'right', width => 84, editable => false, summaryType => 'sum', summaryTpl => '<b><font color="#DF3A01">array(0)</font></b>', formatter => 'number', formatoptions => array(decimalSeparator => ".", thousandsSeparator => ",", decimalPlaces => 2, defaultValue => '-'), search => false),
            array(name => 'valor_salida_traslado', index => 'valor_salida_traslado', align => 'right', width => 84, editable => false, summaryType => 'sum', summaryTpl => '<b><font color="#DF3A01">array(0)</font></b>', formatter => 'number', formatoptions => array(decimalSeparator => ".", thousandsSeparator => ",", decimalPlaces => 2, defaultValue => '-'), search => false),
            array(name => 'valor_salida_recarga', index => 'valor_salida_recarga', align => 'right', width => 84, editable => false, summaryType => 'sum', summaryTpl => '<b><font color="#DF3A01">array(0)</font></b>', formatter => 'number', formatoptions => array(decimalSeparator => ".", thousandsSeparator => ",", decimalPlaces => 2, defaultValue => '-'), search => false),
            array(name => 'saldo', index => 'saldo', align => 'right', width => 84, editable => false, summaryType => 'sum', summaryTpl => '<b><font color="#DF3A01">array(0)</font></b>', formatter => 'number', formatoptions => array(decimalSeparator => ".", thousandsSeparator => ",", decimalPlaces => 2, defaultValue => '-'), search => false),
        ];

        /*El código configura una tabla para gestionar el flujo de caja histórico, especificando la URL de la API, el tipo de datos,
         los nombres y modelos de las columnas, y otras opciones de visualización y agrupamiento.*/
        if ($_SESSION["win_perfil"] == "SA" or $_SESSION["win_perfil"] == "ADMIN" or $_SESSION["win_perfil"] == "ADMIN2" or $_SESSION["win_perfil"] == "ADMIN3" or $_SESSION["win_perfil"] == "OPERCOM") {
            array_push($GRID_COLNAMES, 'Valor Premios Pendientes');
            array_push($GRID_COLMODEL, array(name => 'premios_pend', index => 'premios_pend', align => 'right', width => 84, editable => false, summaryType => 'sum', summaryTpl => '<b><font color="#DF3A01">array(0)</font></b>', formatter => 'number', formatoptions => array(decimalSeparator => ".", thousandsSeparator => ",", decimalPlaces => 2, defaultValue => '-'), search => false));
        }

        $GRID_ROWNUM = 50000;
        $GRID_ROWLIST = [50000, 100000, 200000];

        $GRID_SORTNAME = "z.fecha_crea";
        $GRID_SORTORDER = "desc";
        $GRID_EDITURL = "";

        $GRID_CAPTION = "Flujo de Caja Historico";
        $GRID_GROUPINGVIEW = array(

            groupField => ['punto_venta'],
            groupColumnShow => [false],
            groupText => ['<b><font color="#DF3A01">{0}</font></b>'],
            groupCollapse => true,
            groupOrder => ['desc'],
        );

        $GRID_SELECT = [];

        break;

    /**
     * get_table consulta_flujo_caja
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
    case "consulta_flujo_caja":

        /*El código configura una tabla para gestionar el flujo de caja resumido, especificando la URL de la API, el tipo de datos,
         los nombres y modelos de las columnas, y otras opciones de visualización y agrupamiento.*/

        $GRID_URL = "https://admin.doradobet.com/restapi/api/consulta_flujo_caja_xml/?tipo=I&fecha_ini=" . $fecha_ini . "&fecha_fin=" . $fecha_fin . "&usuario_id=" . $puntos . "&concesionario_id=" . $concesionarios . "&depto_id=" . $deptos . "&ciudad_id=" . $ciudades . "&pais_id=" . $paises;
        $GRID_EDITAR = false;
        $GRID_ELIMINAR = false;
        $GRID_AGREGAR = false;
        $GRID_GROUPING = true;

        $GRID_DATATYPE = "xml";
        $GRID_COLNAMES = ['Id', 'Caja Agrupa', 'Fecha', 'Moneda', 'Cantidad Tickets', 'Valor Entradas Efectivo', 'Valor Entradas Bono / T.C.', 'Valor Entradas Traslados', 'Valor Entradas Recargas', 'Valor Salidas Efectivo', 'Valor Salidas Traslados', 'Valor Salidas Notas de Retiro', 'Saldo', 'Moneda'];
        $GRID_COLMODEL = [
            array(name => 'id', index => 'ticket_id', hidden => true, width => 40, search => false, editable => false),
            array(name => 'punto_venta', index => 'd.nombre', align => 'center', width => 70, editable => false, search => true, stype => 'text', searchoptions => array(sopt => ['cn'])),
            array(name => 'fecha_crea', index => 'x.fecha_crea', align => 'center', width => 86, editable => false, search => true, stype => 'text', searchoptions => array(sopt => ['cn'])),
            array(name => 'moneda', index => 'b.moneda', align => 'center', width => 70, editable => false, search => false),
            array(name => 'cant_tickets', index => 'cant_tickets', align => 'right', width => 74, editable => false, summaryType => 'sum', summaryTpl => '<b><font color="#DF3A01">array(0)</font></b>', formatter => 'number', formatoptions => array(decimalSeparator => ".", thousandsSeparator => ",", decimalPlaces => 0, defaultValue => '-'), search => false),
            array(name => 'valor_entrada_efectivo', index => 'valor_entrada_efectivo', align => 'right', width => 74, editable => false, summaryType => 'sum', summaryTpl => '<b><font color="#DF3A01">array(0)</font></b>', formatter => 'number', formatoptions => array(decimalSeparator => ".", thousandsSeparator => ",", decimalPlaces => 2, defaultValue => '-'), search => false),
            array(name => 'valor_entrada_bono', index => 'valor_entrada_bono', align => 'right', width => 74, editable => false, summaryType => 'sum', summaryTpl => '<b><font color="#DF3A01">array(0)</font></b>', formatter => 'number', formatoptions => array(decimalSeparator => ".", thousandsSeparator => ",", decimalPlaces => 2, defaultValue => '-'), search => false),
            array(name => 'valor_entrada_traslado', index => 'valor_entrada_traslado', align => 'right', width => 74, editable => false, summaryType => 'sum', summaryTpl => '<b><font color="#DF3A01">array(0)</font></b>', formatter => 'number', formatoptions => array(decimalSeparator => ".", thousandsSeparator => ",", decimalPlaces => 2, defaultValue => '-'), search => false),
            array(name => 'valor_entrada_recarga', index => 'valor_entrada_recarga', align => 'right', width => 74, editable => false, summaryType => 'sum', summaryTpl => '<b><font color="#DF3A01">array(0)</font></b>', formatter => 'number', formatoptions => array(decimalSeparator => ".", thousandsSeparator => ",", decimalPlaces => 2, defaultValue => '-'), search => false),
            array(name => 'valor_salida_efectivo', index => 'valor_salida_efectivo', align => 'right', width => 74, editable => false, summaryType => 'sum', summaryTpl => '<b><font color="#DF3A01">array(0)</font></b>', formatter => 'number', formatoptions => array(decimalSeparator => ".", thousandsSeparator => ",", decimalPlaces => 2, defaultValue => '-'), search => false),
            array(name => 'valor_salida_traslado', index => 'valor_salida_traslado', align => 'right', width => 74, editable => false, summaryType => 'sum', summaryTpl => '<b><font color="#DF3A01">array(0)</font></b>', formatter => 'number', formatoptions => array(decimalSeparator => ".", thousandsSeparator => ",", decimalPlaces => 2, defaultValue => '-'), search => false),
            array(name => 'valor_salida_recarga', index => 'valor_salida_recarga', align => 'right', width => 74, editable => false, summaryType => 'sum', summaryTpl => '<b><font color="#DF3A01">array(0)</font></b>', formatter => 'number', formatoptions => array(decimalSeparator => ".", thousandsSeparator => ",", decimalPlaces => 2, defaultValue => '-'), search => false),
            array(name => 'saldo', index => 'saldo', align => 'right', width => 75, editable => false, summaryType => 'sum', summaryTpl => '<b><font color="#DF3A01">array(0)</font></b>', formatter => 'number', formatoptions => array(decimalSeparator => ".", thousandsSeparator => ",", decimalPlaces => 2, defaultValue => '-'), search => false),
            array(name => 'moneda', index => 'moneda', align => 'center', width => 80, editable => false, search => false),
        ];

        /*El código configura una tabla para gestionar el flujo de caja resumido, especificando la URL de la API, el tipo de datos, los nombres y
         modelos de las columnas, y otras opciones de visualización y agrupamiento.*/
        if ($_SESSION["win_perfil"] == "SA" or $_SESSION["win_perfil"] == "ADMIN" or $_SESSION["win_perfil"] == "ADMIN2" or $_SESSION["win_perfil"] == "ADMIN3" or $_SESSION["win_perfil"] == "OPERCOM") {
            array_push($GRID_COLNAMES, 'Valor Premios Pendientes');
            array_push($GRID_COLMODEL, array(name => 'premios_pend', index => 'premios_pend', align => 'right', width => 84, editable => false, summaryType => 'sum', summaryTpl => '<b><font color="#DF3A01">array(0)</font></b>', formatter => 'number', formatoptions => array(decimalSeparator => ".", thousandsSeparator => ",", decimalPlaces => 2, defaultValue => '-'), search => false));
        }

        $GRID_ROWNUM = 50000;
        $GRID_ROWLIST = [50000, 100000, 200000];

        $GRID_SORTNAME = "z.fecha_crea";
        $GRID_SORTORDER = "desc";
        $GRID_EDITURL = "";

        $GRID_CAPTION = "Flujo de Caja Resumido";
        $GRID_GROUPINGVIEW = array(

            groupField => ['punto_venta'],
            groupColumnShow => [false],
            groupText => ['<b><font color="#DF3A01">{0}</font></b>'],
            groupCollapse => true,
            groupOrder => ['desc'],
        );
        $GRID_SELECT = [];

        break;

    /**
     * get_table informe_casino
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
    case "informe_casino":
        /*El código configura una tabla para mostrar un informe de casino, especificando la URL de la API, el tipo de datos,
         los nombres y modelos de las columnas, y otras opciones de visualización y agrupamiento.*/

        $GRID_URL = "https://admin.doradobet.com/restapi/api/informe_casino_xml/?fecha_inicio=" . $fecha_ini . "&fecha_fin=" . $fecha_fin . "&usuario_id=" . $puntos . "&concesionario_id=" . $concesionarios . "&depto_id=" . $deptos . "&ciudad_id=" . $ciudades . "&pais_id=" . $paises;
        $GRID_EDITAR = false;
        $GRID_ELIMINAR = false;
        $GRID_AGREGAR = false;
        $GRID_GROUPING = true;

        $GRID_DATATYPE = "xml";
        $GRID_COLNAMES = ['Id', 'Pais', 'Fecha', 'Moneda', 'Cantidad de Jugadas', 'Valor Apostado', 'Valor Jugada Promedio', 'Proyecci&oacute;n Premios', 'Valor Premios'];
        $GRID_COLMODEL = [

            array(name => 'id', index => 'id', width => 40, hidden => true, search => false, editable => false),
            array(name => 'pais_nom', index => 'pais_nom', width => 120, align => 'center', search => false, editable => false),
            array(name => 'fecha_crea', index => 'fecha_crea', width => 120, align => 'center', search => false, editable => false),
            array(name => 'moneda', index => 'moneda', align => 'center', width => 70, editable => false, search => false),
            array(name => 'cant_tickets', index => 'cant_tickets', width => 80, align => 'center', summaryType => 'sum', summaryTpl => '<b><font color="#DF3A01">array(0)</font></b>', editable => false, formatter => 'number', formatoptions => array(decimalSeparator => ".", thousandsSeparator => ",", decimalPlaces => 0, defaultValue => '-'), search => false),
            array(name => 'valor_apostado', index => 'valor_apostado', width => 100, align => 'right', summaryType => 'sum', summaryTpl => '<b><font color="#DF3A01">array(0)</font></b>', editable => false, formatter => 'number', formatoptions => array(decimalSeparator => ".", thousandsSeparator => ",", decimalPlaces => 0, defaultValue => '-'), search => false),
            array(name => 'valor_ticket_prom', hidden => true, index => 'valor_ticket_prom', width => 100, align => 'right', summaryType => 'avg', summaryTpl => '<b><font color="#DF3A01">array(0)</font></b>', editable => false, formatter => 'number', formatoptions => array(decimalSeparator => ".", thousandsSeparator => ",", decimalPlaces => 0, defaultValue => '-'), search => false),
            array(name => 'proyeccion_premios', hidden => true, index => 'proyeccion_premios', width => 100, align => 'right', summaryType => 'sum', summaryTpl => '<b><font color="#DF3A01">array(0)</font></b>', editable => false, formatter => 'number', formatoptions => array(decimalSeparator => ".", thousandsSeparator => ",", decimalPlaces => 0, defaultValue => '-'), search => false),
            array(name => 'valor_premios', index => 'valor_premios', width => 100, align => 'right', summaryType => 'sum', summaryTpl => '<b><font color="#DF3A01">array(0)</font></b>', editable => false, formatter => 'number', formatoptions => array(decimalSeparator => ".", thousandsSeparator => ",", decimalPlaces => 0, defaultValue => '-'), search => false),

        ];

        /*El código configura una tabla para mostrar un informe de casino, especificando la URL de la API, el tipo de datos, los nombres y modelos de las columnas, y otras opciones de visualización y agrupamiento.*/
        $GRID_ROWNUM = 100;
        $GRID_ROWLIST = [100, 200, 300];

        $GRID_SORTNAME = "x.trnFecReg";
        $GRID_SORTORDER = "asc";
        $GRID_EDITURL = "";

        $GRID_CAPTION = "Informe de Casino";
        $GRID_GROUPINGVIEW = array(

            groupField => ['pais_nom'],
            groupColumnShow => [false],
            groupText => ['<b><font color="#DF3A01">{0}</font></b>'],
            groupCollapse => true,
            groupOrder => ['asc'],
        );
        $GRID_SELECT = [];

        break;

    /**
     * get_table transacciones
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
    case "transacciones":

        /*El código configura una tabla para mostrar transacciones, especificando la URL de la API, el tipo de datos, los nombres y
         modelos de las columnas, y otras opciones de visualización y agrupamiento.*/

        $GRID_URL = "https://admin.doradobet.com/restapi/api/transacciones_xml/?fecha_inicio=" . $fecha_ini . "&fecha_fin=" . $fecha_fin . "&usuario_id=" . $puntos . "&concesionario_id=" . $concesionarios . "&depto_id=" . $deptos . "&ciudad_id=" . $ciudades . "&pais_id=" . $paises . "&productos=" . $productos . "&proveedores=" . $proveedores;
        $GRID_EDITAR = false;
        $GRID_ELIMINAR = false;
        $GRID_AGREGAR = false;
        $GRID_GROUPING = true;

        $GRID_DATATYPE = "xml";
        $GRID_COLNAMES = ['Id', 'Proveedor', 'Producto', 'Usuario', 'Pais', 'Fecha', 'Moneda', 'Valor', 'Estado', 'Estado Producto'];
        $GRID_COLMODEL = [

            array(name => 'id', index => 'id', width => 40, hidden => true, search => false, editable => false),
            array(name => 'proveedor_nom', index => 'proveedor_nom', width => 120, align => 'center', search => false, editable => false),
            array(name => 'producto_nom', index => 'producto_nom', width => 120, align => 'center', search => false, editable => false),
            array(name => 'usuario', index => 'usuario', width => 120, align => 'center', search => false, editable => false),
            array(name => 'pais_nom', index => 'pais_nom', width => 120, align => 'center', search => false, editable => false),
            array(name => 'fecha_crea', index => 'fecha_crea', width => 120, align => 'center', search => false, editable => false),
            array(name => 'moneda', index => 'moneda', align => 'center', width => 70, editable => false, search => false),
            array(name => 'valor', index => 'valor', width => 80, align => 'center', summaryType => 'sum', summaryTpl => '<b><font color="#DF3A01">array(0)</font></b>', editable => false, formatter => 'number', formatoptions => array(decimalSeparator => ".", thousandsSeparator => ",", decimalPlaces => 0, defaultValue => '-'), search => false),
            array(name => 'estado', index => 'estado', width => 120, align => 'center', search => false, editable => false),
            array(name => 'estado_producto', index => 'pais_nom', width => 120, align => 'center', search => false, editable => false),

        ];

        /*El código configura una tabla para mostrar transacciones, especificando la URL de la API, el tipo de datos, los nombres y modelos
         de las columnas, y otras opciones de visualización y agrupamiento.*/
        $GRID_ROWNUM = 100;
        $GRID_ROWLIST = [100, 200, 300];

        $GRID_SORTNAME = "a.fecha_crea";
        $GRID_SORTORDER = "asc";
        $GRID_EDITURL = "";

        $GRID_CAPTION = "Transacciones";
        $GRID_GROUPINGVIEW = array(

            groupField => ['pais_nom'],
            groupColumnShow => [false],
            groupText => ['<b><font color="#DF3A01">{0}</font></b>'],
            groupCollapse => true,
            groupOrder => ['asc'],
        );
        $GRID_SELECT = [];

        break;

    /**
     * get_table consulta_listado_recargas
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
    case "consulta_listado_recargas":

        /*El código configura una tabla para mostrar un listado de recargas, especificando la URL de la API, el tipo de datos,
        los nombres y modelos de las columnas, y otras opciones de visualización y agrupamiento.*/

        $GRID_URL = "https://admin.doradobet.com/restapi/api/consulta_listado_recargas_xml/?fecha_ini=" . $fecha_ini . "&fecha_fin=" . $fecha_fin . "&tipo=C" . "&usuario_id=" . $puntos . "&concesionario_id=" . $concesionarios . "&depto_id=" . $deptos . "&ciudad_id=" . $ciudades . "&pais_id=" . $paises;
        $GRID_EDITAR = false;
        $GRID_ELIMINAR = false;
        $GRID_AGREGAR = false;
        $GRID_GROUPING = true;

        $GRID_DATATYPE = "xml";
        $GRID_COLNAMES = ['Id', 'Pais', 'Punto de Venta', 'Nro. Usuario', 'Nombre Usuario', 'Nro. Recarga', 'Fecha Recarga', 'Nro. Pedido', 'Valor Recargado', 'Moneda', 'Direccion IP'];
        $GRID_COLMODEL = [
            array(name => 'id', index => 'recarga_id', hidden => true, width => 40, search => false, editable => false),
            array(name => 'pais_nom', index => 'pais_nom', width => 80, editable => false, search => true, stype => 'text', searchoptions => array(sopt => ['cn'])),
            array(name => 'punto_venta', index => 'b.nombre', width => 170, editable => false, search => true, stype => 'text', searchoptions => array(sopt => ['cn'])),
            array(name => 'usuario_id', index => 'a.usuario_id', align => 'center', width => 80, editable => false, search => true, stype => 'text', searchoptions => array(sopt => ['cn'])),
            array(name => 'usuario', index => 'c.nombre', width => 170, editable => false, search => true, stype => 'text', searchoptions => array(sopt => ['cn'])),
            array(name => 'recarga_id', index => 'a.recarga_id', align => 'center', width => 80, editable => false, search => true, stype => 'text', searchoptions => array(sopt => ['eq'])),
            array(name => 'fecha_crea', index => 'a.fecha_crea', align => 'center', width => 140, editable => false, search => true, stype => 'text', searchoptions => array(sopt => ['cn'])),
            array(name => 'pedido', index => 'a.pedido', width => 100, align => 'center', editable => false, search => true, stype => 'text', searchoptions => array(sopt => ['cn'])),
            array(name => 'valor', index => 'a.valor', align => 'right', width => 90, editable => false, summaryType => 'sum', summaryTpl => '<b><font color="#DF3A01">array(0)</font></b>', formatter => 'number', formatoptions => array(decimalSeparator => ".", thousandsSeparator => ",", decimalPlaces => 2, defaultValue => '-'), search => false),
            array(name => 'moneda', index => 'moneda', align => 'center', width => 70, editable => false, search => false),
            array(name => 'dir_ip', index => 'a.dir_ip', width => 100, align => 'center', editable => false, search => true, stype => 'text', searchoptions => array(sopt => ['cn'])),
        ];

        /*El código configura una tabla para mostrar un listado de recargas, especificando la URL de la API, el tipo de datos, los nombres y modelos de las columnas,
         y otras opciones de visualización y agrupamiento.*/
        $GRID_ROWNUM = 100;
        $GRID_ROWLIST = [100, 500, 1000];

        $GRID_SORTNAME = "b.nombre,a.fecha_crea desc,c.nombre";
        $GRID_SORTORDER = "asc";
        $GRID_EDITURL = "";

        $GRID_CAPTION = "Listado de Recargas";
        $GRID_GROUPINGVIEW = array(

            groupField => ['pais_nom'],
            groupColumnShow => [false],
            groupText => ['<b><font color="#DF3A01">{0}</font></b>'],
            groupCollapse => true,
            groupOrder => ['desc'],
        );
        $GRID_SELECT = [];

        break;

    /**
     * get_table informe_gerencial
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
    case "informe_gerencial":

        /*El código configura una tabla para mostrar un informe gerencial, especificando la URL de la API, el tipo de datos, los nombres y
         modelos de las columnas, y otras opciones de visualización y agrupamiento.*/

        $GRID_URL = "https://admin.doradobet.com/restapi/api/informe_gerencial_xml/?fecha_inicio=" . $fecha_ini . "&fecha_fin=" . $fecha_fin . "&tipo=C" . "&usuario_id=" . $puntos . "&concesionario_id=" . $concesionarios . "&depto_id=" . $deptos . "&ciudad_id=" . $ciudades . "&pais_id=" . $paises;
        $GRID_EDITAR = false;
        $GRID_ELIMINAR = false;
        $GRID_AGREGAR = false;
        $GRID_GROUPING = true;

        $GRID_DATATYPE = "xml";
        $GRID_COLNAMES = ['Id', 'Pais', 'Fecha', 'Moneda', 'Cantidad de Tickets', 'Valor Apostado', 'Valor Ticket Promedio', 'Proyecci&oacute;n Premios', 'Valor Premios'];
        $GRID_COLMODEL = [
            array(name => 'id', index => 'id', width => 40, hidden => true, search => false, editable => false),
            array(name => 'pais_nom', index => 'pais_nom', width => 120, align => 'center', search => false, editable => false),
            array(name => 'fecha_crea', index => 'fecha_crea', width => 120, align => 'center', search => false, editable => false),
            array(name => 'moneda', index => 'moneda', align => 'center', width => 70, editable => false, search => false),
            array(name => 'cant_tickets', index => 'cant_tickets', width => 80, align => 'center', summaryType => 'sum', summaryTpl => '<b><font color="#DF3A01">array(0)</font></b>', editable => false, formatter => 'number', formatoptions => array(decimalSeparator => ".", thousandsSeparator => ",", decimalPlaces => 0, defaultValue => '-'), search => false),
            array(name => 'valor_apostado', index => 'valor_apostado', width => 100, align => 'right', summaryType => 'sum', summaryTpl => '<b><font color="#DF3A01">array(0)</font></b>', editable => false, formatter => 'number', formatoptions => array(decimalSeparator => ".", thousandsSeparator => ",", decimalPlaces => 0, defaultValue => '-'), search => false),
            array(name => 'valor_ticket_prom', index => 'valor_ticket_prom', width => 100, align => 'right', summaryType => 'avg', summaryTpl => '<b><font color="#DF3A01">array(0)</font></b>', editable => false, formatter => 'number', formatoptions => array(decimalSeparator => ".", thousandsSeparator => ",", decimalPlaces => 0, defaultValue => '-'), search => false),
            array(name => 'proyeccion_premios', index => 'proyeccion_premios', width => 100, align => 'right', summaryType => 'sum', summaryTpl => '<b><font color="#DF3A01">array(0)</font></b>', editable => false, formatter => 'number', formatoptions => array(decimalSeparator => ".", thousandsSeparator => ",", decimalPlaces => 0, defaultValue => '-'), search => false),
            array(name => 'valor_premios', index => 'valor_premios', width => 100, align => 'right', summaryType => 'sum', summaryTpl => '<b><font color="#DF3A01">array(0)</font></b>', editable => false, formatter => 'number', formatoptions => array(decimalSeparator => ".", thousandsSeparator => ",", decimalPlaces => 0, defaultValue => '-'), search => false),
        ];

        /*El código configura una tabla para mostrar un informe gerencial, especificando la URL de la API, el tipo de datos,
         los nombres y modelos de las columnas, y otras opciones de visualización y agrupamiento.*/
        $GRID_ROWNUM = 100;
        $GRID_ROWLIST = [100, 200, 300];

        $GRID_SORTNAME = "x.fecha_crea";
        $GRID_SORTORDER = "asc";
        $GRID_EDITURL = "";

        $GRID_CAPTION = "Informe Gerencial";
        $GRID_GROUPINGVIEW = array(

            groupField => ['pais_nom'],
            groupColumnShow => [false],
            groupText => ['<b><font color="#DF3A01">{0}</font></b>'],
            groupCollapse => true,
            groupOrder => ['desc'],
        );
        $GRID_SELECT = [];

        break;

    /**
     * get_table consulta_premio_pend
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
    case "consulta_premio_pend":

        /*El código configura una tabla para mostrar un listado de premios pendientes de pago, especificando la URL de la API, el tipo de datos, los nombres
         y modelos de las columnas, y otras opciones de visualización y agrupamiento.*/

        $GRID_URL = "https://admin.doradobet.com/restapi/api/ticket_premiado_xml/?pagados=N&punto&caduco=N";
        $GRID_EDITAR = false;
        $GRID_ELIMINAR = false;
        $GRID_AGREGAR = false;
        $GRID_GROUPING = true;

        $GRID_DATATYPE = "xml";
        $GRID_COLNAMES = ['No. Ticket', 'Agrupador', 'Fecha Crea', 'Hora Crea', 'Fecha Premio', 'Hora Premio', 'Valor Apostado', 'Valor Premio', 'Moneda', 'Punto de Venta', 'Concesionario', 'Fecha Caducidad Pago', 'Caduco'];
        $GRID_COLMODEL = [
            array(name => 'id', index => 'a.ticket_id', align => 'center', width => 82, editable => false, search => true, stype => 'text', searchoptions => array(sopt => ['cn'])),
            array(name => 'pais_nom', index => 'pais_nom', align => 'center', width => 95, editable => false, search => true, stype => 'text', searchoptions => array(sopt => ['cn'])),
            array(name => 'fecha_crea', index => 'a.fecha_crea', align => 'center', width => 82, editable => false, search => true, stype => 'text', searchoptions => array(sopt => ['cn'])),
            array(name => 'hora_crea', index => 'a.hora_crea', align => 'center', width => 45, editable => false, search => true, stype => 'text', searchoptions => array(sopt => ['cn'])),
            array(name => 'fecha_cierre', index => 'a.fecha_cierre', align => 'center', width => 82, editable => false, search => true, stype => 'text', searchoptions => array(sopt => ['cn'])),
            array(name => 'hora_cierre', index => 'a.hora_cierre', align => 'center', width => 45, editable => false, search => true, stype => 'text', searchoptions => array(sopt => ['cn'])),
            array(name => 'vlr_apuesta', index => 'a.vlr_apuesta', align => 'right', width => 60, editable => false, summaryType => 'sum', summaryTpl => '<b><font color="#DF3A01">array(0)</font></b>', formatter => 'number', formatoptions => array(decimalSeparator => ".", thousandsSeparator => ",", decimalPlaces => 2, defaultValue => '-'), search => false),
            array(name => 'vlr_premio', index => 'a.vlr_premio', align => 'right', width => 65, editable => false, summaryType => 'sum', summaryTpl => '<b><font color="#DF3A01">array(0)</font></b>', formatter => 'number', formatoptions => array(decimalSeparator => ".", thousandsSeparator => ",", decimalPlaces => 2, defaultValue => '-'), search => false),
            array(name => 'moneda', index => 'moneda', align => 'center', width => 70, editable => false, search => false),
            array(name => 'punto_venta', index => 'b.nombre', width => 270, editable => false, search => true, stype => 'text', searchoptions => array(sopt => ['cn'])),
            array(name => 'concesionario', index => 'y.nombre', hidden => true, width => 180, editable => false, search => true, stype => 'text', searchoptions => array(sopt => ['cn'])),
            array(name => 'fecha_maxpago', index => 'a.fecha_maxpago', align => 'center', width => 82, editable => false, formoptions => array(elmsuffix => '  aaaa-mm-dd'), editoptions => array(maxlength => 10), editrules => array(required => true, date => true), search => true, stype => 'text', searchoptions => array(sopt => ['cn'])),
            array(name => 'caduco', index => 'caduco', width => 50, align => 'center', editable => false, search => false, stype => 'text', searchoptions => array(sopt => ['cn'])),
        ];

        /*El código configura una tabla para mostrar un listado de premios pendientes de pago, especificando la URL de la API, el tipo de datos,
         los nombres y modelos de las columnas, y otras opciones de visualización y agrupamiento.*/
        $GRID_ROWNUM = 100;
        $GRID_ROWLIST = [100, 200, 300];

        $GRID_SORTNAME = "a.ticket_id";
        $GRID_SORTORDER = "asc";
        $GRID_EDITURL = "";

        $GRID_CAPTION = "Listado de Premios x Pagar";
        $GRID_GROUPINGVIEW = array(

            groupField => ['pais_nom'],
            groupColumnShow => [false],
            groupText => ['<b><font color="#DF3A01">{0} - ( {1} Tickets )</font></b>'],
            groupCollapse => true,
            groupOrder => ['desc'],
        );
        $GRID_SELECT = [];

        break;

    /**
     * get_table consulta_online_resumen
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
    case "consulta_online_resumen":

        /*El código configura una tabla para mostrar un resumen de usuarios en línea, especificando la URL de la API, el tipo de datos,
         los nombres y modelos de las columnas, y otras opciones de visualización y agrupamiento.*/

        $GRID_URL = "https://admin.doradobet.com/restapi/api/consulta_online_resumen_xml/?fecha_ini=$fecha_hoy&fecha_fin=$fecha_hoy";
        $GRID_EDITAR = false;
        $GRID_ELIMINAR = false;
        $GRID_AGREGAR = false;
        $GRID_GROUPING = true;

        $GRID_DATATYPE = "xml";
        $GRID_COLNAMES = ['...', 'Pais', 'Fecha', 'Moneda', 'Cant Tickets', 'Saldo Recargas', 'Saldo Disp Retiro', 'Valor Recargas', 'Valor Promocional', 'Valor Tickets Abiertos', 'Notas Retiro Pend', 'Valor Pagado', 'Valor Apostado', 'Valor Premios'];
        $GRID_COLMODEL = [
            array(name => 'id', index => 'id', width => 35, align => 'center', editable => false, search => false),
            array(name => 'pais_nom', index => 'pais_nom', align => 'center', width => 120, editable => false, search => true, stype => 'text', searchoptions => array(sopt => ['cn'])),
            array(name => 'fecha', index => 'x.fecha', align => 'center', width => 90, editable => false, search => true, stype => 'text', searchoptions => array(sopt => ['cn'])),
            array(name => 'moneda', index => 'b.moneda', align => 'center', width => 60, editable => false, search => false),
            array(name => 'cant_tickets', index => 'x.cant_tickets', width => 60, align => 'right', summaryType => 'sum', summaryTpl => '<b><font color="#DF3A01">array(0)</font></b>', editable => false, formatter => 'number', formatoptions => array(decimalSeparator => ".", thousandsSeparator => ",", decimalPlaces => 0, defaultValue => '-'), search => false),
            array(name => 'saldo_recarga', index => 'x.saldo_recarga', width => 85, align => 'right', summaryType => 'sum', summaryTpl => '<b><font color="#DF3A01">array(0)</font></b>', editable => false, formatter => 'number', formatoptions => array(decimalSeparator => ".", thousandsSeparator => ",", decimalPlaces => 2, defaultValue => '-'), search => false),
            array(name => 'disp_retiro', index => 'x.disp_retiro', width => 85, align => 'right', summaryType => 'sum', summaryTpl => '<b><font color="#DF3A01">array(0)</font></b>', editable => false, formatter => 'number', formatoptions => array(decimalSeparator => ".", thousandsSeparator => ",", decimalPlaces => 2, defaultValue => '-'), search => false),
            array(name => 'valor_recargas', index => 'x.valor_recargas', width => 85, align => 'right', summaryType => 'sum', summaryTpl => '<b><font color="#DF3A01">array(0)</font></b>', editable => false, formatter => 'number', formatoptions => array(decimalSeparator => ".", thousandsSeparator => ",", decimalPlaces => 2, defaultValue => '-'), search => false),
            array(name => 'valor_promocional', index => 'x.valor_promocional', width => 85, align => 'right', summaryType => 'sum', summaryTpl => '<b><font color="#DF3A01">array(0)</font></b>', editable => false, formatter => 'number', formatoptions => array(decimalSeparator => ".", thousandsSeparator => ",", decimalPlaces => 2, defaultValue => '-'), search => false),
            array(name => 'valor_tickets_abiertos', index => 'x.valor_tickets_abiertos', width => 85, align => 'right', summaryType => 'sum', summaryTpl => '<b><font color="#DF3A01">array(0)</font></b>', editable => false, formatter => 'number', formatoptions => array(decimalSeparator => ".", thousandsSeparator => ",", decimalPlaces => 2, defaultValue => '-'), search => false),
            array(name => 'nora_retiro_pend', index => 'x.nota_retiro_pend', width => 85, align => 'right', summaryType => 'sum', summaryTpl => '<b><font color="#DF3A01">array(0)</font></b>', editable => false, formatter => 'number', formatoptions => array(decimalSeparator => ".", thousandsSeparator => ",", decimalPlaces => 2, defaultValue => '-'), search => false),
            array(name => 'valor_pagado', index => 'x.valor_pagado', width => 85, align => 'right', summaryType => 'sum', summaryTpl => '<b><font color="#DF3A01">array(0)</font></b>', editable => false, formatter => 'number', formatoptions => array(decimalSeparator => ".", thousandsSeparator => ",", decimalPlaces => 2, defaultValue => '-'), search => false),
            array(name => 'valor_apostado', index => 'x.valor_apostado', width => 85, align => 'right', summaryType => 'sum', summaryTpl => '<b><font color="#DF3A01">array(0)</font></b>', editable => false, formatter => 'number', formatoptions => array(decimalSeparator => ".", thousandsSeparator => ",", decimalPlaces => 2, defaultValue => '-'), search => false),
            array(name => 'valor_premio', index => 'x.valor_premio', width => 85, align => 'right', summaryType => 'sum', summaryTpl => '<b><font color="#DF3A01">array(0)</font></b>', editable => false, formatter => 'number', formatoptions => array(decimalSeparator => ".", thousandsSeparator => ",", decimalPlaces => 2, defaultValue => '-'), search => false),
        ];

        /*El código configura una tabla para mostrar un resumen de usuarios en línea, especificando la URL de la API, el tipo de datos, los nombres y
         modelos de las columnas, y otras opciones de visualización y agrupamiento.*/
        $GRID_ROWNUM = 100;
        $GRID_ROWLIST = [100, 200, 300];

        $GRID_SORTNAME = "x.fecha";
        $GRID_SORTORDER = "asc";
        $GRID_EDITURL = "";

        $GRID_CAPTION = "Reporte Resumido de los Usuarios OnLine";
        $GRID_GROUPINGVIEW = array(

            groupField => ['pais_nom'],
            groupColumnShow => [false],
            groupText => ['<b><font color="#DF3A01">{0}</font></b>'],
            groupCollapse => true,
            groupOrder => ['desc'],
        );
        $GRID_SELECT = [];

        break;

    /**
     * get_table promocional
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
    case "promocional":

        /*configura una tabla para mostrar un listado de promociones, especificando la URL de la API, el tipo de datos, los nombres y
         modelos de las columnas, y otras opciones de visualización y agrupamiento.*/

        $GRID_URL = "https://admin.doradobet.com/restapi/api/promocional_xml/?fecha_ini=$fecha_hoy&fecha_fin=$fecha_hoy";
        $GRID_EDITAR = true; // Permitir edición
        $GRID_ELIMINAR = false; // No permitir eliminación
        $GRID_AGREGAR = false; // No permitir agregar
        $GRID_GROUPING = false; // No permitir agrupamiento

        $GRID_DATATYPE = "xml"; // Tipo de dato a manejar
        $GRID_COLNAMES = ['Id', 'Descripcion', 'Estado', 'Moneda', 'Tipo', 'Valor', 'Tope', 'Total', 'Acumulado', 'Tipo Promocional', 'Dias Expiracion', 'Fecha inicio', 'Fecha fin', 'Fecha Modificación', 'Usuario que Modifica'];
        $GRID_COLMODEL = [
            array(name => 'id', index => 'a.promocional_id', width => 35, align => 'center', editable => false, search => false),
            array(name => 'descripcion', index => 'a.descripcion', width => 300, editable => true, search => true, stype => 'text', searchoptions => array(sopt => ['cn'])),
            array(name => 'estado', index => 'a.estado', align => 'center', width => 120, editable => true, edittype => "select", editoptions => array(value => "A:Activo;I:Inactivo"), editrules => array(required => true), sortable => true, search => true, stype => 'select', searchoptions => array(value => ":...;A:Activo;I:Inactivo")),
            array(name => 'moneda', index => 'a.moneda', align => 'center', width => 140, editable => false, search => true, stype => 'text', searchoptions => array(sopt => ['cn'])),
            array(name => 'tipo', index => 'a.tipo', align => 'center', width => 120, editable => false, edittype => "select", editoptions => array(value => "Porcentaje:PORCENTAJE;Valor:VALOR"), editrules => array(required => true), sortable => true, search => true, stype => 'select', searchoptions => array(value => ":...;A:Activo;I:Inactivo")),
            array(name => 'valor', index => 'a.valor', align => 'center', width => 140, editable => true, search => true, stype => 'text', searchoptions => array(sopt => ['cn'])),
            array(name => 'tope', index => 'a.tope', align => 'center', width => 140, editable => true, search => true, stype => 'text', searchoptions => array(sopt => ['cn'])),
            array(name => 'total', index => 'a.total', align => 'center', width => 140, editable => true, search => true, stype => 'text', searchoptions => array(sopt => ['cn'])),
            array(name => 'acumulado', index => 'a.acumulado', align => 'center', width => 140, editable => false, search => true, stype => 'text', searchoptions => array(sopt => ['cn'])),
            array(name => 'tipopromocional', index => 'a.tipo_promocional', align => 'center', width => 120, editable => false, edittype => "select", editoptions => array(value => "Primer Deposito:PRIMERDEPOSITO"), editrules => array(required => true), sortable => true, search => true, stype => 'select', searchoptions => array(value => ":...;A:Activo;I:Inactivo")),
            array(name => 'dias_expiracion', index => 'a.dias_expiracion', align => 'center', width => 140, editable => true, search => true, stype => 'text', searchoptions => array(sopt => ['cn'])),
            array(name => 'fecha_ini', index => 'a.fecha_ini', align => 'center', width => 140, editable => false, search => true, stype => 'text', searchoptions => array(sopt => ['cn'])),
            array(name => 'fecha_fin', index => 'a.fecha_fin', align => 'center', width => 140, editable => false, search => true, stype => 'text', searchoptions => array(sopt => ['cn'])),
            array(name => 'fecha_modif', index => 'a.fecha_modif', align => 'center', width => 140, editable => false, search => true, stype => 'text', searchoptions => array(sopt => ['cn'])),
            array(name => 'usu_modif', index => 'usu_modif', width => 170, editable => false, search => false, stype => 'text', searchoptions => array(sopt => ['cn'])),
        ];


        /*El código configura una tabla para mostrar un listado de promociones, especificando la URL de la API, el tipo de datos,
        los nombres y modelos de las columnas, y otras opciones de visualización y agrupamiento.*/
        $GRID_ROWNUM = 100; // Número de filas a mostrar
        $GRID_ROWLIST = [100, 200, 300]; // Opciones de filas por página

        $GRID_SORTNAME = "a.descripcion"; // Columna para ordenar
        $GRID_SORTORDER = "asc"; // Orden de clasificación
        $GRID_EDITURL = "https://admin.doradobet.com/restapi/api/promocional_actualizar/?editar=" . (($GRID_EDITAR) ? 'true' : 'false');

        $GRID_CAPTION = "Listado de Promocionales"; // Título de la tabla
        $GRID_GROUPINGVIEW = ""; // Opciones de agrupamiento (vacío)
        $GRID_SELECT = []; // Selecciones adicionales

        break;

    /**
     * get_table promocional_detalle
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
    case "promocional_detalle":
        // Se obtienen los IDs de promoción como una cadena separada por comas
        $promocion_id = implode(', ', $json->params->promocionId);

        // Se define la URL para obtener los detalles promocionales en formato XML
        $GRID_URL = "https://admin.doradobet.com/restapi/api/promocional_detalle_xml/?fecha_ini=$fecha_hoy&fecha_fin=$fecha_hoy&promocion_id=" . $promocion_id;

        // Configuraciones para el Grid
        $GRID_EDITAR = true; // Habilita la edición
        $GRID_ELIMINAR = false; // Deshabilita la eliminación
        $GRID_AGREGAR = false; // Deshabilita la adición
        $GRID_GROUPING = false; // Deshabilita la agrupación

        // Tipo de datos para el Grid
        $GRID_DATATYPE = "xml";
        $GRID_COLNAMES = ['Id', 'Promocion', 'KEY', 'valor', 'estado'];
        $GRID_COLMODEL = [
            array(name => 'id', index => 'a.promocondicion_id', width => 35, align => 'center', editable => false, search => false),
            array(name => 'Promocion', index => 'b.descripcion', width => 35, align => 'center', editable => false, search => false),
            array(name => 'key', index => 'a.t_key', align => 'center', width => 120, editable => false, edittype => "select", editoptions => array(value => "PAIS:Pais"), editrules => array(required => true), sortable => true, search => true, stype => 'select', searchoptions => array(value => ":...;A:Activo;I:Inactivo")),
            array(name => 'valor', index => 'a.t_value', width => 300, editable => false, search => true, stype => 'text', searchoptions => array(sopt => ['cn'])),
            array(name => 'estado', index => 'a.estado', align => 'center', width => 120, editable => true, edittype => "select", editoptions => array(value => "A:Activo;I:Inactivo"), editrules => array(required => true), sortable => true, search => true, stype => 'select', searchoptions => array(value => ":...;A:Activo;I:Inactivo")),

        ];

        $GRID_ROWNUM = 100;
        $GRID_ROWLIST = [100, 200, 300];

        $GRID_SORTNAME = "a.promocondicion_id";
        $GRID_SORTORDER = "asc";
        $GRID_EDITURL = "https://admin.doradobet.com/restapi/api/promocional_detalle_actualizar/?editar=" . (($GRID_EDITAR) ? 'true' : 'false');

        // Título del Grid
        $GRID_CAPTION = "Detalle Promocional";
        $GRID_GROUPINGVIEW = ""; // Configuración de vista de agrupación
        $GRID_SELECT = []; // Selecciones adicionales

        break;

    /**
     * get_table informe_promocional
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
    case "informe_promocional":

        // URL para acceder a la API que proporciona los datos del informe promocional
        $GRID_URL = "https://admin.doradobet.com/restapi/api/promolog_xml/?fecha_ini=$fecha_hoy&fecha_fin=$fecha_hoy";
        $GRID_EDITAR = true;
        $GRID_ELIMINAR = false;
        $GRID_AGREGAR = false;
        $GRID_GROUPING = false;

        // Tipo de dato que se espera de la cuadrícula
        $GRID_DATATYPE = "xml";
        $GRID_COLNAMES = ['Id', 'Promocional', 'Usuario', 'Valor', 'Valor Promocional', 'Valor Base', 'Estado'];
        $GRID_COLMODEL = [
            array(name => 'id', index => 'a.promolog_id', width => 35, align => 'center', editable => false, search => false),
            array(name => 'promocional', index => 'b.descripcion', width => 300, editable => false, search => true, stype => 'text', searchoptions => array(sopt => ['cn'])),
            array(name => 'usuario', index => 'a.usuario_id', width => 300, editable => false, search => true, stype => 'text', searchoptions => array(sopt => ['cn'])),
            array(name => 'valor', index => 'a.valor', width => 300, editable => false, search => true, stype => 'text', searchoptions => array(sopt => ['cn'])),
            array(name => 'valorpromocional', index => 'c.valor_promocional', width => 300, editable => false, search => true, stype => 'text', searchoptions => array(sopt => ['cn'])),
            array(name => 'valorbase', index => 'c.valor_base', width => 300, editable => false, search => true, stype => 'text', searchoptions => array(sopt => ['cn'])),
            array(name => 'estado', index => 'a.estado', align => 'center', width => 120, editable => false, edittype => "select", editoptions => array(value => "A:Activo;I:Inactivo"), editrules => array(required => true), sortable => true, search => true, stype => 'select', searchoptions => array(value => ":...;A:Activo;I:Inactivo")),
        ];

        // Número de filas a mostrar en la cuadrícula por defecto
        $GRID_ROWNUM = 100;
        $GRID_ROWLIST = [100, 200, 300];

        // Nombre de la columna por la que se ordenarán los datos
        $GRID_SORTNAME = "a.promolog_id";
        $GRID_SORTORDER = "asc";
        $GRID_EDITURL = "";

        // Título de la cuadrícula
        $GRID_CAPTION = "Listado de Promocionales";
        $GRID_GROUPINGVIEW = "";
        $GRID_SELECT = [];

        break;

    /**
     * get_table producto
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

        $GRID_URL = "https://admin.doradobet.com/restapi/api/producto2_xml/"; // URL para obtener los datos de productos
        $GRID_EDITAR = true; // Indica si se permite la edición de productos
        $GRID_ELIMINAR = false; // Indica si se permite la eliminación de productos
        $GRID_AGREGAR = false; // Indica si se permite agregar nuevos productos
        $GRID_GROUPING = false; // Indica si se permite agrupar productos

        $GRID_DATATYPE = "xml"; // Tipo de dato que se espera recibir (XML)
        $GRID_COLNAMES = ['Id', 'Nombre', 'Proveedor', 'Imagen', 'Estado', 'Verificacion', 'ID Externo', 'Mostrar']; // Nombres de las columnas
        $GRID_COLMODEL = [ // Configuración de cada columna en la tabla
            array(name => 'id', index => 'a.producto_id', width => 35, align => 'center', editable => false, search => false),
            array(name => 'nombre', index => 'a.descripcion', width => 300, editable => true, search => true, stype => 'text', searchoptions => array(sopt => ['cn'])),
            array(name => 'proveedor', index => 'b.descripcion', width => 300, editable => false, search => true, stype => 'text', searchoptions => array(sopt => ['cn'])),
            array(name => 'imagen', index => 'a.image_url', width => 300, editable => true, search => true, stype => 'text', searchoptions => array(sopt => ['cn'])),
            array(name => 'estado', index => 'a.estado', align => 'center', width => 120, editable => true, edittype => "select", editoptions => array(value => "A:Activo;I:Inactivo"), editrules => array(required => true), sortable => true, search => true, stype => 'select', searchoptions => array(value => ":...;A:Activo;I:Inactivo")),
            array(name => 'verificacion', index => 'a.verifica', align => 'center', width => 120, editable => true, edittype => "select", editoptions => array(value => "A:Activo;I:Inactivo"), editrules => array(required => true), sortable => true, search => true, stype => 'select', searchoptions => array(value => ":...;A:Activo;I:Inactivo")),
            array(name => 'externo_id', index => 'a.externo_id', width => 300, editable => false, search => true, stype => 'text', searchoptions => array(sopt => ['cn'])),
            array(name => 'mostrar', index => 'a.mostrar', align => 'center', width => 120, editable => true, edittype => "select", editoptions => array(value => "S:Si;N:No"), editrules => array(required => true), sortable => true, search => true, stype => 'select', searchoptions => array(value => ":...;S:Si;N:No")),
        ];

        $GRID_ROWNUM = 100; // Número de filas a mostrar por página
        $GRID_ROWLIST = [100, 200, 300]; // Listas de opciones para seleccionar cuantas filas mostrar

        $GRID_SORTNAME = "a.producto_id"; // Columna por la cual se ordenarán los resultados
        $GRID_SORTORDER = "asc"; // Orden del sorteo (ascendente)
        $GRID_EDITURL = "https://admin.doradobet.com/restapi/api/producto_actualizar/?editar=" . (($GRID_EDITAR) ? 'true' : 'false'); // URL para actualizar un producto

        $GRID_CAPTION = "Productos"; // Título de la tabla
        $GRID_GROUPINGVIEW = ""; // Configuración de vista de agrupamiento
        $GRID_SELECT = []; // Selecciones adicionales

        break;

    /**
     * get_table producto_detalle
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
    case "producto_detalle":

        // URL del servicio para obtener los detalles del producto en formato XML
        $GRID_URL = "https://admin.doradobet.com/restapi/api/producto_detalle_xml/";
        $GRID_EDITAR = true;
        $GRID_ELIMINAR = false;
        $GRID_AGREGAR = false;
        $GRID_GROUPING = false;

        // Se define el tipo de dato de la grilla
        $GRID_DATATYPE = "xml";
        $GRID_COLNAMES = ['Id', 'Producto', 'Key', 'Valor'];
        $GRID_COLMODEL = [
            array(name => 'id', index => 'a.productodetalle_id', width => 35, align => 'center', editable => false, search => false),
            array(name => 'producto', index => 'b.descripcion', width => 300, editable => false, search => true, stype => 'text', searchoptions => array(sopt => ['cn'])),
            array(name => 'key', index => 'a.p_key', width => 300, editable => true, search => true, stype => 'text', searchoptions => array(sopt => ['cn'])),
            array(name => 'value', index => 'a.p_value', width => 300, editable => true, search => true, stype => 'text', searchoptions => array(sopt => ['cn'])),
        ];

        // Número de filas a mostrar en la grilla
        $GRID_ROWNUM = 100;
        $GRID_ROWLIST = [100, 200, 300];

        // Configuración del orden por el cual se ordenarán las filas
        $GRID_SORTNAME = "a.productodetalle_id";
        $GRID_SORTORDER = "asc";
        $GRID_EDITURL = "https://admin.doradobet.com/restapi/api/producto_detalle_actualizar/?editar=" . (($GRID_EDITAR) ? 'true' : 'false');

        // Título de la grilla
        $GRID_CAPTION = "Producto Detalle";
        $GRID_GROUPINGVIEW = "";
        $GRID_SELECT = [];

        break;


    /**
     * get_table proveedor
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

        $GRID_URL = "https://admin.doradobet.com/restapi/api/proveedor2_xml/"; // URL de la API para obtener datos de proveedores
        $GRID_EDITAR = true; // Permitir edición de registros
        $GRID_ELIMINAR = false; // No permitir eliminación de registros
        $GRID_AGREGAR = false; // No permitir adición de nuevos registros
        $GRID_GROUPING = false; // No permitir agrupamiento

        $GRID_DATATYPE = "xml";
        $GRID_COLNAMES = ['Id', 'Descripcion', 'Tipo', 'Estado', 'Verificacion', 'Abreviado'];
        $GRID_COLMODEL = [
            array(name => 'id', index => 'a.proveedor_id', width => 35, align => 'center', editable => false, search => false),
            array(name => 'descripcion', index => 'a.descripcion', width => 300, editable => true, search => true, stype => 'text', searchoptions => array(sopt => ['cn'])),
            array(name => 'tipo', index => 'a.tipo', align => 'center', width => 120, editable => true, edittype => "select", editoptions => array(value => "PAYMENT:Payment;CASINO:Casino"), editrules => array(required => true), sortable => true, search => true, stype => 'select', searchoptions => array(value => ":...;PAYMENT:Payment;CASINO:Casino")),
            array(name => 'estado', index => 'a.estado', align => 'center', width => 120, editable => true, edittype => "select", editoptions => array(value => "A:Activo;I:Inactivo"), editrules => array(required => true), sortable => true, search => true, stype => 'select', searchoptions => array(value => ":...;A:Activo;I:Inactivo")),
            array(name => 'verificacion', index => 'a.verifica', align => 'center', width => 120, editable => true, edittype => "select", editoptions => array(value => "A:Activo;I:Inactivo"), editrules => array(required => true), sortable => true, search => true, stype => 'select', searchoptions => array(value => ":...;A:Activo;I:Inactivo")),
            array(name => 'abreviado', index => 'a.abreviado', width => 300, editable => true, search => true, stype => 'text', searchoptions => array(sopt => ['cn'])),

        ];

        $GRID_ROWNUM = 100; // Número de filas a mostrar
        $GRID_ROWLIST = [100, 200, 300]; // Opciones de selección para la cantidad de filas mostradas

        $GRID_SORTNAME = "a.proveedor_id"; // Nombre de la columna para ordenamiento
        $GRID_SORTORDER = "asc"; // Orden de ordenamiento
        $GRID_EDITURL = "https://admin.doradobet.com/restapi/api/proveedor_actualizar/?editar=" . (($GRID_EDITAR) ? 'true' : 'false'); // URL para la edición de registros

        $GRID_CAPTION = "Proveedores"; // Título de la tabla
        $GRID_GROUPINGVIEW = ""; // Vista de agrupamiento deshabilitada
        $GRID_SELECT = []; // Selección inicial

        break;

}

// Inicializa la variable $str_GRIDURL como una cadena vacía
$str_GRIDURL = "";

// Verifica si la URL de la cuadrícula ($GRID_URL) termina con una barra diagonal
if (substr($GRID_URL, -1) === "/") {
    // Si termina con '/', se añade un token (tk) a la URL
    $str_GRIDURL = $GRID_URL . "?tk=" . str_replace("/", "#@@#", encrypt($json->session->usuario . "#" . $json->session->sid));

} else {
    // Si no termina con '/', se añade un token (tk) a la URL usando '&'
    $str_GRIDURL = $GRID_URL . "&tk=" . str_replace("/", "#@@#", encrypt($json->session->usuario . "#" . $json->session->sid));

}

// Inicializa la variable $str_GRIDEDITURL como una cadena vacía
$str_GRIDEDITURL = "";

// Verifica si la URL de edición de la cuadrícula ($GRID_EDITURL) termina con una barra diagonal
if (substr($GRID_EDITURL, -1) === "/") {
    // Si termina con '/', se añade un token (tk) a la URL de edición
    $str_GRIDEDITURL = $GRID_EDITURL . "?tk=" . str_replace("/", "#@@#", encrypt($json->session->usuario . "#" . $json->session->sid));

} else {
    // Si no termina con '/', se añade un token (tk) a la URL de edición usando '&'
    $str_GRIDEDITURL = $GRID_EDITURL . "&tk=" . str_replace("/", "#@@#", encrypt($json->session->usuario . "#" . $json->session->sid));

}

// Crea un arreglo de respuesta con diversos parámetros de configuración
$response = array(
    "code" => 0,
    "rid" => "$json->rid",
    "data" => array(
        "GRID_URL" => $str_GRIDURL,
        "GRID_DATATYPE" => $GRID_DATATYPE,
        "GRID_COLNAMES" => $GRID_COLNAMES,
        "GRID_COLMODEL" => $GRID_COLMODEL,
        "GRID_ROWNUM" => $GRID_ROWNUM,
        "GRID_ROWLIST" => $GRID_ROWLIST,
        "GRID_SORTNAME" => $GRID_SORTNAME,
        "GRID_SORTORDER" => $GRID_SORTORDER,
        "GRID_EDITURL" => $str_GRIDEDITURL,
        "GRID_CAPTION" => $GRID_CAPTION,
        "GRID_EDITAR" => $GRID_EDITAR,
        "GRID_ELIMINAR" => $GRID_ELIMINAR,
        "GRID_AGREGAR" => $GRID_AGREGAR,
        "GRID_GROUPING" => $GRID_GROUPING,
        "GRID_GROUPINGVIEW" => $GRID_GROUPINGVIEW,
        "GRID_SELECT" => $GRID_SELECT,
        "data_source" => 5,
    )
);
