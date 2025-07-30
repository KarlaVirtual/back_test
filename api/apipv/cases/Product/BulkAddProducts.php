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
use Backend\dto\ProductoDetalle;
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
use Backend\dto\proveedor as DtoProveedor;
use Backend\dto\Subproveedor;
use Backend\dto\Template;
use Backend\integrations\casino\IES;
use Backend\integrations\casino\JOINSERVICES;
use Backend\mysql\ConsecutivoMySqlDAO;
use Backend\mysql\CuentaCobroMySqlDAO;
use Backend\mysql\DocumentoUsuarioMySqlDAO;
use Backend\mysql\ProductoDetalleMySqlDAO;
use Backend\mysql\ProductoMySqlDAO;
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
use PgSql\Lob;

/**
 * Product/BulkAddProducts.php
 *
 * Carga masiva de productos desde archivo CSV desde plataforma Bulk
 *
 * Este recurso permite procesar un archivo CSV con información de productos y registrarlos en la base de datos.
 * Se verifica la existencia de los productos mediante su ID externo y se manejan condiciones específicas para ciertos
 * proveedores que requieren dos códigos de identificación.
 *
 * @param string $CSV : Archivo CSV en formato base64 con los datos de los productos a registrar.
 *
 * @returns object El objeto $response es un array con los siguientes atributos:
 *  - *HasError* (bool): Indica si hubo un error en la operación.
 *  - *AlertType* (string): Tipo de alerta generado.
 *  - *AlertMessage* (string): Mensaje de alerta generado.
 *  - *ModelErrors* (array): Lista de errores si la operación falla.
 *
 * Objeto en caso de error:
 *
 * $response["HasError"] = true;
 * $response["AlertType"] = "error";
 * $response["AlertMessage"] = "[Mensaje de error]";
 * $response["ModelErrors"] = [];
 *
 * @throws Exception Si el archivo CSV no contiene datos válidos o si hay problemas al insertar en la base de datos.
 * @access public
 * @see no
 * @since no
 * @deprecated no
 */

// echo "entro juanda";
// exit;

// function random_strings($length_of_string)
// {

//     // String of all alphanumeric character
//     $str_result = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';

//     // Shuffle the $str_result and returns substring
//     // of specified length
//     return substr(str_shuffle($str_result),
//         0, $length_of_string);
// }


// $ConfigurationEnvironment = new ConfigurationEnvironment();


/* obtiene un CSV codificado en Base64 desde una solicitud HTTP. */
$params = file_get_contents('php://input');
$params = json_decode($params);
$ProductsCsv = $params->CSV;

$ProductsCsv = explode("base64,", $ProductsCsv);
$ProductsCsv = $ProductsCsv[1];

/* Decodifica un CSV en base64 y lo convierte en un array de líneas. */
$ProductsCsv = base64_decode($ProductsCsv);

$lines = explode(PHP_EOL, $ProductsCsv);
$lines = preg_split('/\r\n|\r|\n/', $ProductsCsv);

if (isset($ProductsCsv) && $ProductsCsv != '') {


    /* Convierte un CSV en un arreglo bidimensional separando campos por punto y coma. */
    $line = array();
    $i = 0;

    $linee = str_getcsv($ProductsCsv, "\n");

    //CSV: one line is one record and the cells/fields are seperated by ";"
    //so $dsatz is an two dimensional array saving the records like this: $dsatz[number of record][number of cell]

    foreach ($linee as $line) {

        if ($i > 0) {
            $dsatz[$i] = array();
            $dsatz[$i] = explode(";", $line);
        }

        $i++;
    }


    /* Inicializa un arreglo de emails y crea instancias de Producto y ProductoDetalle. */
    $emails = [];

    $producto = new Producto();

    $ProductoDetalle = new ProductoDetalle();


    foreach ($dsatz as $fila) {
        // Reiniciar $k a 0 para cada fila

        /* accede a valores de columnas en una fila, asignando a variables. */
        $k = 0;

        // Acceder a los valores de cada columna en la fila actual
        $Nombre = $fila[$k];
        $k++;

        $Proveedor = $fila[$k];
        $k++;


        /* Asignación de valores de un array a variables incrementando el índice. */
        $subproveedor = $fila[$k];
        $k++;

        $Estado = $fila[$k];
        $k++;

        $verifica = $fila[$k];
        $k++;


        /* asigna valores de un array a variables incrementando el índice. */
        $IdExterno = $fila[$k];
        $k++;

        $IdExterno2 = $fila[$k];
        $k++;

        $Visible = $fila[$k];
        $k++;


        /* asigna valores de un array a variables específicas. */
        $cellPhone = $fila[$k];
        $k++;

        $desktop = $fila[$k];
        $k++;


        $category = $fila[$k];


        /* verifica si un ID externo de producto está registrado. */
        $producto = new Producto();

        $verificarIdExterno = $producto->verifyIdExterno($IdExterno);


        if ($verificarIdExterno) {
            $juegoRegistrado = true;
        } else {
            /* asigna false a $juegoRegistrado si no se cumple una condición previa. */

            $juegoRegistrado = false;
        }


        /* Establece si se necesitan dos códigos según el proveedor. */
        if ($Proveedor == 6 || $Proveedor == 81) {
            $necesita2Codigos = true;
        } else {
            $necesita2Codigos = false;
        }


        if ($necesita2Codigos == false) {


            /* Código para establecer propiedades de un objeto 'producto' usando datos de entrada. */
            $producto->setProveedorId($Proveedor);
            $producto->setDescripcion($Nombre);
            $producto->setImageUrl("");
            $producto->setEstado($Estado);
            $producto->setVerifica($verifica);
            $producto->setUsucreaId($_SESSION["usuario"]);

            /* asigna valores a propiedades de un objeto 'producto' desde variables y sesión. */
            $producto->setUsumodifId($_SESSION["usuario"]);
            $producto->setExternoId($IdExterno);
            $producto->setMostrar($Visible);
            $producto->setOrden(0);
            $producto->setMobile($cellPhone);
            $producto->setDesktop($desktop);

            /* Se asignan valores a un producto y se inserta en la base de datos. */
            $producto->setSubproveedorId($subproveedor);
            $producto->setPagoTerceros("I");
            $producto->setCategoriaId($category);

            $ProductoMySqlDAO = new ProductoMySqlDAO();
            $ProductoMySqlDAO->insert($producto);


            /* confirma la transacción actual en la base de datos MySQL. */
            $ProductoMySqlDAO->getTransaction()->commit();
        } else {


            /* asigna valores a propiedades de un objeto de producto. */
            $producto->setProveedorId($Proveedor);
            $producto->setDescripcion($Nombre);
            $producto->setImageUrl("");
            $producto->setEstado($Estado);
            $producto->setVerifica($verifica);
            $producto->setUsucreaId($_SESSION["usuario"]);

            /* Configura atributos del producto, incluyendo usuario, visibilidad y dispositivos. */
            $producto->setUsumodifId($_SESSION["usuario"]);
            $producto->setExternoId($IdExterno);
            $producto->setMostrar($Visible);
            $producto->setOrden(0);
            $producto->setMobile($cellPhone);
            $producto->setDesktop($desktop);

            /* Se establece información del producto y se inserta en la base de datos. */
            $producto->setSubproveedorId($subproveedor);
            $producto->setPagoTerceros("I");
            $producto->setCategoriaId(0);

            $ProductoMySqlDAO = new ProductoMySqlDAO();
            $Id = $ProductoMySqlDAO->insert($producto);

            /* guarda detalles de un producto en una base de datos MySQL. */
            $ProductoMySqlDAO->getTransaction()->commit();

            if ($IdExterno2 != "") {

                $ProductoDetalle = new ProductoDetalle();
                $ProductoDetalle->setProductoId($Id);
                $ProductoDetalle->setPKey("GAMEID");
                $ProductoDetalle->setPValue($IdExterno2);
                $ProductoDetalle->setUsucreaId(0);
                $ProductoDetalle->setUsumodifId(0);

                $productoDetalleMySqlDAO = new ProductoDetalleMySqlDAO();
                $productoDetalleMySqlDAO->insert($ProductoDetalle);
                $productoDetalleMySqlDAO->getTransaction()->commit();
            } else {
                /* Lanza una excepción con un mensaje y código específico al procesar una solicitud. */

                throw new Exception("Error Processing Request", 200054);
            }
        }


    }


    /* Inicia una respuesta estructurada para manejar errores y mensajes de éxito. */
    $response = [];
    $response['HasError'] = false;
    $response['AlertType'] = 'success';
    $response['AlertMessage'] = '';
    $response['ModelErrors'] = [];


} else {
    /* maneja un error, estableciendo un respuesta con detalles específicos. */

    $response = [];
    $response['HasError'] = true;
    $response['AlertType'] = 'error';
    $response['AlertMessage'] = '';
    $response['ModelErrors'] = [];
}


?>