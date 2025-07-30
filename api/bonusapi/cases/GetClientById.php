<?php
/* error_reporting(E_ALL);
 ini_set('display_errors', 'ON');*/

use Backend\dto\Usuario;

/**
 * Obtiene la información detallada de un cliente por su ID
 * 
 * Este recurso permite obtener la información completa de un usuario/cliente
 * incluyendo sus datos personales, de contacto, ubicación y configuración.
 * 
 * @method GET
 * @param string $id ID del cliente a consultar
 * @param object $params Parámetros de paginación y ordenamiento
 * @param int $params->MaxRows Número máximo de registros a retornar (default: 10)
 * @param int $params->OrderedItem Campo por el cual ordenar (default: 1)
 * @param int $params->SkeepRows Número de registros a saltar para paginación (default: 0)
 * 
 * @return object {
 *   "HasError": bool,           // Indica si hubo error en la operación
 *   "AlertType": string,        // Tipo de alerta (success/error)
 *   "AlertMessage": string,     // Mensaje de alerta
 *   "ModelErrors": array,       // Lista de errores del modelo
 *   "Data": {
 *     "Id": string,            // ID del usuario
 *     "Ip": string,            // Dirección IP
 *     "Login": string,         // Nombre de usuario
 *     "Estado": array,         // Estado del usuario
 *     "EstadoEspecial": string,// Estado especial
 *     "Idioma": string,        // Idioma preferido
 *     "Nombre": string,        // Nombre completo
 *     "FirstName": string,     // Primer nombre
 *     "MiddleName": string,    // Segundo nombre
 *     "LastName": string,      // Apellido
 *     "Email": string,         // Correo electrónico
 *     "Address": string,       // Dirección
 *     "TipoUsuario": string,   // Tipo de usuario
 *     "Intentos": int,         // Intentos de login
 *     "Observaciones": string, // Observaciones
 *     "Moneda": string,        // Moneda preferida
 *     "Pais": string,          // País
 *     "City": string,          // Ciudad
 *     "CreatedLocalDate": string, // Fecha de creación
 *     "IsLocked": bool,        // Estado de bloqueo
 *     "BirthCity": string,     // Ciudad de nacimiento
 *     "BirthDate": string,     // Fecha de nacimiento
 *     "BirthDepartment": string, // Departamento de nacimiento
 *     "BirthRegionCode2": string, // Código de región de nacimiento
 *     "BirthRegionId": string,    // ID de región de nacimiento
 *     "CurrencyId": string,       // ID de moneda
 *     "DocNumber": string,        // Número de documento
 *     "Gender": string,           // Género
 *     "Language": string,         // Idioma
 *     "Phone": string,            // Teléfono
 *     "MobilePhone": string,      // Teléfono móvil
 *     "LastLoginLocalDate": string, // Último login
 *     "Province": string,         // Provincia
 *     "RegionId": string,         // ID de región
 *     "CountryName": string,      // Nombre del país
 *     "ZipCode": string,          // Código postal
 *     "IsVerified": bool          // Estado de verificación
 *   }
 * }
 */
// Inicializa el objeto Usuario para acceder a los datos de usuarios
$Usuario = new Usuario();

// Obtiene y decodifica los parámetros de entrada desde la solicitud
$params = file_get_contents('php://input');
$params = json_decode($params);
$id = $_GET["id"];

// Extrae los parámetros de paginación y ordenamiento
$MaxRows = $params->MaxRows;
$OrderedItem = $params->OrderedItem;
$SkeepRows = $params->SkeepRows;

// Establece valores predeterminados para los parámetros de paginación
// si no se proporcionan en la solicitud
if ($SkeepRows == "") {
    $SkeepRows = 0;
}

if ($OrderedItem == "") {
    $OrderedItem = 1;
}

if ($MaxRows == "") {
    $MaxRows = 10;
}

// Construye el filtro JSON para la consulta de usuarios
// Filtra por el ID de usuario específico proporcionado en la URL
$json = '{"rules" : [{"field" : "usuario.usuario_id", "data": "' . $id . '","op":"eq"}] ,"groupOp" : "AND"}';

// Ejecuta la consulta para obtener los datos del usuario
// Selecciona campos específicos de las tablas usuario, registro, y otras relacionadas
$usuarios = $Usuario->getUsuariosCustom("  DISTINCT (usuario.usuario_id),usuario.fecha_ult,usuario.moneda,usuario.idioma,usuario.dir_ip,usuario.login,usuario.estado,usuario.estado_esp,usuario.pais_id,usuario.fecha_crea,registro.cedula,registro.sexo,registro.ciudad_id,registro.nombre1,registro.nombre2,registro.apellido1,registro.email,registro.direccion,registro.telefono,registro.celular,registro.codigo_postal,registro.ciudnacim_id,registro.paisnacim_id,c.*,g.* ", "usuario.usuario_id", "asc", $SkeepRows, $MaxRows, $json, true);

// Decodifica los resultados obtenidos para su procesamiento
$usuarios = json_decode($usuarios);

// Inicializa un array para almacenar los datos procesados del usuario
$usuariosFinal = [];

// Recorre cada registro de usuario encontrado y extrae la información relevante
// Mapea los campos de la base de datos a la estructura de respuesta esperada
foreach ($usuarios->data as $key => $value) {

    $array = [];

    // Asigna los valores de cada campo del usuario a la estructura de respuesta
    $array["Id"] = $value->{"usuario.usuario_id"};
    $array["Ip"] = $value->{"ausuario.dir_ip"};
    $array["Login"] = $value->{"usuario.login"};
    $array["Estado"] = array($value->{"usuario.estado"});
    $array["EstadoEspecial"] = $value->{"usuario.estado_esp"};
    $array["Idioma"] = $value->{"a.idioma"};
    $array["Nombre"] = $value->{"a.nombre"};
    $array["FirstName"] = $value->{"registro.nombre1"};
    
    // Continúa asignando los datos personales del usuario
    $array["MiddleName"] = $value->{"registro.nombre2"};
    $array["LastName"] = $value->{"registro.apellido1"};
    $array["Email"] = $value->{"registro.email"};
    $array["Address"] = $value->{"registro.direccion"};
    $array["TipoUsuario"] = $value->{"usuario_perfil.perfil_id"};
    $array["Intentos"] = $value->{"usuario.intentos"};
    $array["Observaciones"] = $value->{"usuario.observ"};
    $array["Moneda"] = $value->{"usuario.moneda"};

    // Asigna información geográfica y fechas importantes
    $array["Pais"] = $value->{"usuario.pais_id"};
    $array["City"] = $value->{"g.ciudad_nom"};
    $array["CreatedLocalDate"] = $value->{"usuario.fecha_crea"};
    $array["IsLocked"] = false;
    $array["BirthCity"] = $value->{"registro.ciudnacim_id"};
    $array["BirthDate"] = $value->{"c.fecha_nacim"};

    // Asigna información adicional de ubicación y datos personales
    $array["BirthDepartment"] = $value->{"g.depto_id"};
    $array["BirthRegionCode2"] = $value->{"registro.paisnacim_id"};
    $array["BirthRegionId"] = $value->{"registro.paisnacim_id"};
    $array["CurrencyId"] = $value->{"usuario.moneda"};
    $array["DocNumber"] = $value->{"registro.cedula"};
    $array["Gender"] = $value->{"registro.sexo"};
    $array["Language"] = $value->{"usuario.idioma"};
    
    // Completa la asignación con información de contacto y estado
    $array["Phone"] = $value->{"registro.telefono"};
    $array["MobilePhone"] = $value->{"registro.celular"};
    $array["LastLoginLocalDate"] = $value->{"usuario.fecha_ult"};
    $array["Province"] = $value->{"registro.ciudad_id"};
    $array["RegionId"] = $value->{"usuario.pais_id"};
    $array["CountryName"] = $value->{"usuario.pais_id"};
    $array["ZipCode"] = $value->{"registro.codigo_postal"};
    $array["IsVerified"] = true;
    
    // Asigna el array completo a la variable de respuesta
    $usuariosFinal = $array;
}

// Prepara la respuesta con indicadores de éxito y los datos del usuario
$response["HasError"] = false;
$response["AlertType"] = "success";
$response["AlertMessage"] = "";
$response["ModelErrors"] = [];
$response["Data"] = $usuariosFinal;
