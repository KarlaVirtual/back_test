<?php

use Backend\dto\ApiTransaction;
use Backend\dto\Area;
use Backend\dto\Bono;
use Backend\dto\BonoDetalle;
use Backend\dto\BonoInterno;
use Backend\dto\BonoLog;
use Backend\dto\Cargo;
use Backend\dto\Categoria;
use Backend\dto\CategoriaProducto;
use Backend\dto\CentroCosto;
use Backend\dto\Clasificador;
use Backend\dto\CodigoPromocional;
use Backend\dto\Competencia;
use Backend\dto\CompetenciaPuntos;
use Backend\dto\Concepto;
use Backend\dto\Concesionario;
use Backend\dto\Consecutivo;
use Backend\dto\ConfigurationEnvironment;
use Backend\dto\ContactoComercial;
use Backend\dto\ContactoComercialLog;
use Backend\dto\CuentaCobro;
use Backend\dto\CuentaContable;
use Backend\dto\CupoLog;
use Backend\dto\Descarga;
use Backend\dto\DocumentoUsuario;
use Backend\dto\Egreso;
use Backend\dto\Empleado;
use Backend\dto\FlujoCaja;
use Backend\dto\Flujocajafact;
use Backend\dto\Ingreso;
use Backend\dto\IntApuesta;
use Backend\dto\IntApuestaDetalle;
use Backend\dto\IntCompetencia;
use Backend\dto\IntDeporte;
use Backend\dto\IntEquipo;
use Backend\dto\IntEvento;
use Backend\dto\IntEventoApuesta;
use Backend\dto\IntEventoApuestaDetalle;
use Backend\dto\IntEventoDetalle;
use Backend\dto\IntRegion;
use Backend\dto\ItTicketEnc;
use Backend\dto\LenguajeMandante;
use Backend\dto\Mandante;
use Backend\dto\MandanteDetalle;
use Backend\dto\Pais;
use Backend\dto\PaisMandante;
use Backend\dto\Perfil;
use Backend\dto\PerfilSubmenu;
use Backend\dto\ProdMandanteTipo;
use Backend\dto\Producto;
use Backend\dto\ProductoDetalle;
use Backend\dto\ProductoComision;
use Backend\dto\ProductoInterno;
use Backend\dto\ProductoTercero;
use Backend\dto\ProductoterceroUsuario;
use Backend\dto\PromocionalLog;
use Backend\dto\Proveedor;
use Backend\dto\ProveedorMandante;
use Backend\dto\ProveedorTercero;
use Backend\dto\PuntoVenta;
use Backend\dto\ProductoMandante;
use Backend\dto\Registro;
use Backend\dto\SaldoUsuonlineAjuste;
use Backend\dto\Submenu;
use Backend\dto\Subproveedor;
use Backend\dto\TransaccionApi;
use Backend\dto\TransaccionApiMandante;
use Backend\dto\TransaccionJuego;
use Backend\dto\TransaccionProducto;
use Backend\dto\TransaccionSportsbook;
use Backend\dto\TransjuegoLog;
use Backend\dto\TransprodLog;
use Backend\dto\Usuario;
use Backend\dto\UsuarioAlerta;
use Backend\dto\UsuarioBanco;
use Backend\dto\UsuarioBloqueado;
use Backend\dto\UsuarioBono;
use Backend\dto\UsuarioCierrecaja;
use Backend\dto\UsuarioComision;
use Backend\dto\UsuarioConfig;
use Backend\dto\UsuarioConfiguracion;
use Backend\dto\UsuarioHistorial;
use Backend\dto\UsuarioLog;
use Backend\dto\UsuarioMandante;
use Backend\dto\UsuarioMensaje;
use Backend\dto\UsuarioNotas;
use Backend\dto\UsuarioPerfil;
use Backend\dto\UsuarioPremiomax;
use Backend\dto\UsuarioPublicidad;
use Backend\dto\UsuarioRecarga;
use Backend\dto\UsuarioRecargaResumen;
use Backend\dto\UsuarioRetiroResumen;
use Backend\dto\UsuarioSaldo;
use Backend\dto\UsuarioToken;
use Backend\dto\UsuarioTokenInterno;
use Backend\dto\UsucomisionResumen;
use Backend\dto\UsumarketingResumen;
use Backend\imports\Google\GoogleAuthenticator;
use Backend\integrations\mensajeria\Okroute;
use Backend\integrations\payout\LPGSERVICES;
use Backend\mysql\AreaMySqlDAO;
use Backend\mysql\BonoDetalleMySqlDAO;
use Backend\mysql\BonoInternoMySqlDAO;
use Backend\mysql\BonoLogMySqlDAO;
use Backend\mysql\CargoMySqlDAO;
use Backend\mysql\CategoriaMySqlDAO;
use Backend\mysql\CategoriaProductoMySqlDAO;
use Backend\mysql\CentroCostoMySqlDAO;
use Backend\mysql\ClasificadorMySqlDAO;
use Backend\mysql\CodigoPromocionalMySqlDAO;
use Backend\mysql\CompetenciaPuntosMySqlDAO;
use Backend\mysql\ConceptoMySqlDAO;
use Backend\mysql\ConcesionarioMySqlDAO;
use Backend\mysql\ConsecutivoMySqlDAO;
use Backend\mysql\ContactoComercialLogMySqlDAO;
use Backend\mysql\ContactoComercialMySqlDAO;
use Backend\mysql\CuentaCobroMySqlDAO;
use Backend\mysql\CuentaContableMySqlDAO;
use Backend\mysql\CupoLogMySqlDAO;
use Backend\mysql\DocumentoUsuarioMySqlDAO;
use Backend\mysql\EgresoMySqlDAO;
use Backend\mysql\EmpleadoMySqlDAO;
use Backend\mysql\FlujoCajaMySqlDAO;
use Backend\mysql\IngresoMySqlDAO;
use Backend\mysql\IntApuestaDetalleMySqlDAO;
use Backend\mysql\IntApuestaMySqlDAO;
use Backend\mysql\IntCompetenciaMySqlDAO;
use Backend\mysql\IntDeporteMySqlDAO;
use Backend\mysql\IntEventoApuestaDetalleMySqlDAO;
use Backend\mysql\IntEventoApuestaMySqlDAO;
use Backend\mysql\IntEventoDetalleMySqlDAO;
use Backend\mysql\IntEventoMySqlDAO;
use Backend\mysql\IntRegionMySqlDAO;
use Backend\mysql\LenguajeMandanteMySqlDAO;
use Backend\mysql\MandanteDetalleMySqlDAO;
use Backend\mysql\MandanteMySqlDAO;
use Backend\mysql\PerfilSubmenuMySqlDAO;
use Backend\mysql\ProdMandanteTipoMySqlDAO;
use Backend\mysql\ProductoComisionMySqlDAO;
use Backend\mysql\ProductoDetalleMySqlDAO;
use Backend\mysql\ProductoTerceroMySqlDAO;
use Backend\mysql\ProductoterceroUsuarioMySqlDAO;
use Backend\mysql\ProveedorMandanteMySqlDAO;
use Backend\mysql\ProveedorMySqlDAO;
use Backend\mysql\ProveedorTerceroMySqlDAO;
use Backend\mysql\PuntoVentaMySqlDAO;
use Backend\mysql\RegistroMySqlDAO;
use Backend\mysql\SaldoUsuonlineAjusteMySqlDAO;
use Backend\mysql\TransaccionJuegoMySqlDAO;
use Backend\mysql\TransaccionProductoMySqlDAO;
use Backend\mysql\TransprodLogMySqlDAO;
use Backend\mysql\UsuarioAlertaMySqlDAO;
use Backend\mysql\UsuarioBancoMySqlDAO;
use Backend\mysql\UsuarioBloqueadoMySqlDAO;
use Backend\mysql\UsuarioBonoMySqlDAO;
use Backend\mysql\UsuarioCierrecajaMySqlDAO;
use Backend\mysql\UsuarioConfigMySqlDAO;
use Backend\mysql\UsuarioConfiguracionMySqlDAO;
use Backend\mysql\UsuarioHistorialMySqlDAO;
use Backend\mysql\UsuarioLogMySqlDAO;
use Backend\mysql\UsuarioMandanteMySqlDAO;
use Backend\mysql\UsuarioMensajeMySqlDAO;
use Backend\mysql\ProductoMySqlDAO;
use Backend\mysql\ProductoMandanteMySqlDAO;
use Backend\mysql\UsuarioMySqlDAO;
use Backend\mysql\UsuarioNotasMySqlDAO;
use Backend\mysql\UsuarioPerfilMySqlDAO;
use Backend\mysql\UsuarioPremiomaxMySqlDAO;
use Backend\mysql\UsuarioPublicidadMySqlDAO;
use Backend\mysql\UsuarioRecargaMySqlDAO;
use Backend\mysql\UsuarioTokenInternoMySqlDAO;
use Backend\mysql\UsuarioTokenMySqlDAO;
use Backend\mysql\UsucomisionResumenMySqlDAO;
use Backend\websocket\WebsocketUsuario;

try {

    /**
     * Product/UpdateProductDetails2
     *
     * Actualiza o inserta un producto y su relación con proveedores y categorías
     *
     * Este método permite actualizar los datos de un producto existente o insertar uno nuevo. Además, maneja la carga de imágenes, la asociación de proveedores y categorías, y la actualización de los detalles del producto como ID externo, estado y visibilidad.
     *
     * @param object $params : Objeto que contiene los parámetros necesarios para la actualización o inserción del producto.
     *
     * El objeto $params contiene los siguientes atributos:
     *  - *Id* (int): Identificador del producto a actualizar, o vacío para insertar uno nuevo.
     *  - *IsActivate* (string): Estado de activación del producto, valores posibles 'A' (activo) o 'I' (inactivo).
     *  - *IsVerified* (string): Estado de verificación del producto, valores posibles 'A' (verificado) o 'I' (no verificado).
     *  - *Name* (string): Descripción o nombre del producto.
     *  - *ExternalId* (string): ID externo del producto.
     *  - *ExternalId2* (string): Segundo ID externo del producto.
     *  - *Mobile* (string): Indica si el producto es visible en dispositivos móviles ('A' para sí, 'N' para no).
     *  - *Desktop* (string): Indica si el producto es visible en dispositivos de escritorio ('A' para sí, 'N' para no).
     *  - *Visible* (string): Visibilidad del producto, valores posibles 'A' (visible) o 'N' (no visible).
     *  - *Provider* (object): Objeto proveedor del producto.
     *  - *Image* (string): URL de la imagen del producto.
     *  - *Image2* (string): URL adicional de la imagen del producto.
     *  - *Order* (int): Orden de presentación del producto.
     *  - *Partners* (string): Lista de proveedores asociados al producto.
     *  - *CategoriesAdd* (string): Lista de categorías que se añadirán al producto.
     *
     * @returns object El objeto $response es un array con los siguientes atributos:
     *  - *code* (int): Código de error desde el proveedor.
     *  - *result* (string): Contiene el mensaje de error.
     *  - *data* (array): Contiene el resultado de la consulta.
     *
     * Objeto en caso de error:
     *
     * "code" => [Código de error],
     * "result" => "[Mensaje de error]",
     * "data" => array(),
     *
     * @throws Exception Si ocurre un error al procesar la información o insertar los datos.
     *
     * @access public
     * @see no
     * @since no
     * @deprecated no
     */


    /* Asigna el valor de Id desde el objeto $params a la variable $Id. */
    $Id = $params->Id;

    if ($_POST["upload_fullpath"] != "") {

        /* procesa datos de un formulario y valida ciertos valores recibidos por POST. */
        $Id = $_POST["Id"];

        $IsActivate = ($_POST["IsActivate"] != "A" && $_POST["IsActivate"] != "I") ? "" : $_POST["IsActivate"];
        $IsVerified = ($_POST["IsVerified"] != "A" && $_POST["IsVerified"] != "I") ? "" : $_POST["IsVerified"];
        $Name = str_replace("'", " ", $_POST["Name"]);
        $ExternalId = $_POST["ExternalId"];

        /* asigna valores basados en entradas recibidas por POST en PHP. */
        $ExternalId2 = $_POST["ExternalId2"];
        $Mobile = ($_POST["Mobile"] == "A") ? "S" : "N";
        $Desktop = ($_POST["Desktop"] == "A") ? "S" : "N";
        $Visible = ($_POST["Visible"] == "A") ? "S" : "N";
        $Provider = $_POST["Provider"];
        $Image = $_POST["Image"];

        /* Código que obtiene datos de un formulario mediante el método POST. */
        $Image2 = $_POST["Image2"];
        $Order = $_POST["Order"];
        $ProviderId = $_POST["ProviderId"];

        $Partners = $_POST["Partners"];
        $CategoriesAdd = $_POST["CategoriesAdd"];


        /* asigna arrays a variables si no están vacías; inicializa un array vacío. */
        $Partners = ($Partners != "") ? explode(",", $Partners) : array();
        $CategoriesAdd = ($CategoriesAdd != "") ? explode(",", $CategoriesAdd) : array();

        $arrayProducto = array();

        $Order = 0;


        /* Se crean instancias de Producto, Subproveedor y Proveedor utilizando sus identificadores. */
        $Producto = new Producto($Id);

        $SubProveedor = new Subproveedor($Provider->Id);
        $Proveedor = new Proveedor($SubProveedor->getProveedorId());

        $ProviderId = $SubProveedor->getProveedorId();

        /* asigna un ID y modifica estado y verificación de un producto. */
        $SubProviderId = $SubProveedor->getSubproveedorId();

        if ($IsActivate != "") {
            $Producto->setEstado($IsActivate);
        }
        if ($IsVerified != "") {
            $Producto->setVerifica($IsVerified);
        }

        /* Verifica variables y establece descripciones y IDs en el objeto Producto. */
        if ($Name != "") {
            $Producto->setDescripcion($Name);

        }
        if ($ExternalId != "") {
            $Producto->setExternoId($ExternalId);
        }


        /* Asigna valores a un objeto Producto si las variables Mobile y Desktop no están vacías. */
        if ($Mobile != "") {
            $Producto->setMobile($Mobile);
        }
        if ($Desktop != "") {
            $Producto->setDesktop($Desktop);

        }


        /* verifica el tipo de archivo subido y asigna "png" si no es GIF. */
        $filename = $_FILES['upload']['name'];
        $filetype = $_FILES['upload']['type'];
        $fileTypeName = "";

        if ($filetype != "image/gif") {
            $fileTypeName = "png";
        } else {
            /* Asigna "gif" a $fileTypeName si no se cumplen condiciones previas. */

            $fileTypeName = "gif";
        }

        /* Genera un nombre de archivo único reemplazando caracteres en la descripción del producto. */
        $filename = time() . '.' . $fileTypeName;
        $name = str_replace(' ', '-', $Producto->getDescripcion());
        $name = str_replace('(', '-', $name);
        $name = str_replace(')', '-', $name);
        $name = str_replace('&', '-', $name);
        $name = str_replace("'", '', $name);

        /* limpia un nombre y genera un nombre de archivo único. */
        $name = str_replace("'", '', $name);
        $name = str_replace(":", '', $name);
        $name = str_replace("/", '-', $name);
        $name = str_replace("?", '-', $name);
        $name = normalize($name);

        $filename = $name . "T" . time() . '.' . $fileTypeName;


        /* Código que guarda imágenes subidas y las envía a Google Cloud Storage. */
        $dirsave = '/tmp/' . $filename;

        if ($filetype == 'image/jpeg' or $filetype == 'image/png' or $filetype == 'image/gif') {
            if (move_uploaded_file($_FILES['upload']['tmp_name'], $dirsave)) {
                shell_exec('export BOTO_CONFIG=/home/backend/.config/gcloud/legacy_credentials/admin@bpoconsultores.com.co/.boto && export PATH=/sbin:/bin:/usr/sbin:/usr/bin:/home/backend/google-cloud-sdk/bin && gsutil -h "Cache-Control:public,max-age=604800" cp /tmp/' . $filename . ' gs://virtualcdnrealbucket/productos/');
                $Producto->setImageUrl2('https://images.virtualsoft.tech/productos/' . $filename);
            } else {

            }
        }


        /* reemplaza comillas en la descripción y establece el orden si no está vacío. */
        $Producto->setDescripcion(str_replace("'", " ", $Producto->getDescripcion()));


        if ($Order != "") {
            $Producto->setOrden($Order);
        }
        //$Producto->setProveedorId($ProviderId);
        //$Producto->setSubproveedorId($SubProviderId);


        /*if ($Provider->Id != "") {
            $Producto->setProveedorId($Provider->Id);
        }*/


        /* Actualiza un producto con el ID de usuario en sesión si existe. */
        $Producto->setUsumodifId($_SESSION['usuario2']);

        $ProductoMySqlDAO = new ProductoMySqlDAO();
        if ($Id != "") {
            $ProductoMySqlDAO->update($Producto);
        } else {
            /* Insertar un producto en la base de datos si la condición anterior no se cumple. */

            $Producto_id = $ProductoMySqlDAO->insert($Producto);

        }

        /* Código que confirma una transacción en una base de datos MySQL utilizando un DAO. */
        $ProductoMySqlDAO->getTransaction()->commit();


        if ($Id == "") {


            /* Se crea una instancia de ProductoMandanteMySqlDAO y se obtiene la transacción. */
            $ProductoMandanteMySqlDAO = new ProductoMandanteMySqlDAO();
            $Transaction = $ProductoMandanteMySqlDAO->getTransaction();

            foreach ($Partners as $key => $value) {

                /* Código para actualizar el estado de un producto en base de datos. */
                try {
                    $ProductoMandante = new ProductoMandante($Producto_id, $value);

                    $ProductoMandante->estado = 'A';
                    $ProductoMandanteMySqlDAO = new ProductoMandanteMySqlDAO($Transaction);
                    $ProductoMandanteMySqlDAO->update($ProductoMandante);

                } catch (Exception $e) {
                    if ($e->getCode() == "27") {


                        /* Se crea un objeto de ProductoMandante y se inicializan sus propiedades. */
                        $ProductoMandante = new ProductoMandante();

                        $ProductoMandante->mandante = $value;
                        $ProductoMandante->productoId = $Producto_id;
                        $ProductoMandante->estado = 'A';
                        $ProductoMandante->verifica = 'I';

                        /* Se configuran propiedades de un objeto ProductoMandante con valores específicos. */
                        $ProductoMandante->filtroPais = 'I';
                        $ProductoMandante->filtroPais = 'I';
                        $ProductoMandante->max = 0;
                        $ProductoMandante->min = 0;
                        $ProductoMandante->detalle = '';
                        $ProductoMandante->orden = 100000;

                        /* Código para inicializar propiedades de un objeto y crear un DAO para base de datos. */
                        $ProductoMandante->numFila = 1;
                        $ProductoMandante->numColumna = 1;
                        $ProductoMandante->ordenDestacado = 0;
                        $ProductoMandante->usucreaId = $_SESSION["usuario"];
                        $ProductoMandante->usumodifId = $_SESSION["usuario"];


                        $ProductoMandanteMySqlDAO = new ProductoMandanteMySqlDAO($Transaction);

                        /* Inserta un producto y retorna un arreglo con el producto y su ID. */
                        $prodmandid = $ProductoMandanteMySqlDAO->insert($ProductoMandante);

                        array($arrayProducto, $prodmandid);


                    }

                }

            }


            /* realiza una transacción y obtiene un DAO para manejar categorías de productos. */
            $Transaction->commit();

            $CategoriaProductoMySqlDAO = new CategoriaProductoMySqlDAO();
            $Transaction = $CategoriaProductoMySqlDAO->getTransaction();


            /* inserta categorías de productos en la base de datos utilizando un bucle. */
            foreach ($CategoriesAdd as $item2) {
                $CategoriaProducto = new CategoriaProducto();


                $CategoriaProducto->setCategoriaId($item2);
                $CategoriaProducto->setProductoId($Producto_id);

                $CategoriaProducto->setUsucreaId($_SESSION['usuario2']);
                $CategoriaProducto->setUsumodifId($_SESSION['usuario2']);


                $CategoriaProducto->setEstado('A');
                $CategoriaProducto->setOrden(100000);
                $CategoriaProducto->setMandante($Partner);

                $CategoriaProductoMySqlDAO = new CategoriaProductoMySqlDAO();
                $CategoriaProductoMySqlDAO->insert($CategoriaProducto);
                $CategoriaProductoMySqlDAO->getTransaction()->commit();

            }

            if ($ExternalId2 != "") {


                /* Condicional para insertar detalles del producto, excluyendo ciertos proveedores y verificando IDs. */
                if ($ExternalId2 != '' && $ExternalId2 != '0' && ($Proveedor->getAbreviado() != 'SMARTSOFT' && $Proveedor->getAbreviado() != 'SWINTT')) {
                    $ProductoDetalle = new ProductoDetalle();
                    $ProductoDetalle->setProductoId($Producto->productoId);
                    $ProductoDetalle->setPKey("GAMEID");
                    $ProductoDetalle->setPValue($ExternalId2);
                    $ProductoDetalle->setUsucreaId(0);
                    $ProductoDetalle->setUsumodifId(0);

                    $ProductoDetalle->insert($Transaction);
                }

                /* Valida condiciones y crea un objeto ProductoDetalle para insertarlo en la base de datos. */
                if ($ExternalId2 != '' && $ExternalId2 != '0' && ($Proveedor->getAbreviado() == 'SMARTSOFT' || $Proveedor->getAbreviado() == 'SWINTT')) {
                    $ProductoDetalle = new ProductoDetalle();
                    $ProductoDetalle->setProductoId($Producto->productoId);
                    $ProductoDetalle->setPKey("CATEGORY");
                    $ProductoDetalle->setPValue($ExternalId2);
                    $ProductoDetalle->setUsucreaId(0);
                    $ProductoDetalle->setUsumodifId(0);

                    $ProductoDetalle->insert($Transaction);
                }
                //$Producto->setExternoId($ExternalId);
            }


            /* confirma una transacción, guardando todos los cambios realizados. */
            $Transaction->commit();

        }

    } else {

        if ($Id != "") {


            /* Asignación de valores según condiciones de activación y verificación de parámetros. */
            $IsActivate = ($params->IsActivate != "A" && $params->IsActivate != "I") ? "" : $params->IsActivate;
            $IsVerified = ($params->IsVerified != "A" && $params->IsVerified != "I") ? "" : $params->IsVerified;
            $Name = $params->Name;
            $ExternalId = $params->ExternalId;
            $ExternalId2 = $params->ExternalId2;
            $Mobile = ($params->Mobile == "A") ? "S" : "N";

            /* Asigna valores a variables basadas en condiciones y parámetros de entrada. */
            $Desktop = ($params->Desktop == "A") ? "S" : "N";
            $Visible = ($params->Visible == "A") ? "S" : "N";
            $Provider = $params->Provider;
            $Image = $params->Image;
            $Image2 = $params->Image2;
            $Order = $params->Order;


            /* Se crea un objeto Producto y se activa si el estado no está vacío. */
            $Producto = new Producto($Id);


            if ($IsActivate != "") {
                $Producto->setEstado($IsActivate);
            }

            /* verifica y establece atributos en el objeto Producto según condiciones. */
            if ($IsVerified != "") {
                $Producto->setVerifica($IsVerified);
            }
            if ($Name != "") {
                $Producto->setDescripcion($Name);

            }

            /* asigna valores a un objeto Producto si las variables no están vacías. */
            if ($ExternalId != "") {
                $Producto->setExternoId($ExternalId);
            }

            if ($Mobile != "") {
                $Producto->setMobile($Mobile);
            }

            /* Establece el valor de $Desktop en el objeto $Producto si no está vacío. */
            if ($Desktop != "") {
                $Producto->setDesktop($Desktop);

            }

            if ($params->upload != "" && $params->upload_fullpath != "") {


                /* maneja la subida de archivos, generando nombres únicos basados en tiempo. */
                $filename = $_FILES['file']['name'];
                $filetype = $_FILES['file']['type'];

                $filename = time() . '.' . $_POST["fileType"];

                $filename = $Producto->getDescripcion() . "T" . time() . '.' . $_POST["fileType"];


                /* maneja la carga y almacenamiento de imágenes en un directorio temporal. */
                $filename = str_replace('&', '-', $filename);

                $dirsave = '/tmp/' . $filename;
                if ($filetype == 'image/jpeg' or $filetype == 'image/png' or $filetype == 'image/gif') {
                    if (move_uploaded_file($_FILES['file']['tmp_name'], $dirsave)) {
                        $Producto->setImageUrl2('https://images.virtualsoft.tech/productos/' . $filename);
                    } else {

                    }
                }
            } else {
                /* Condicional que asigna una segunda imagen a un producto si existe. */

                if ($Image2 != "") {
                    $Producto->setImageUrl2($Image2);
                }
            }

            /* reemplaza comillas simples en la descripción de un producto y establece un orden. */
            $Producto->setDescripcion(str_replace("'", " ", $Producto->getDescripcion()));


            if ($Order != "") {
                $Producto->setOrden($Order);
            }


            /* asigna un ID de proveedor a un producto y establece un usuario modificado. */
            if ($Provider->Id != "") {
                $Producto->setProveedorId($Provider->Id);
            }

            $Producto->setUsumodifId($_SESSION['usuario2']);


            $ProductoMySqlDAO = new ProductoMySqlDAO();

            /* Actualiza la información del producto en la base de datos MySQL. */
            $ProductoMySqlDAO->update($Producto);


            if ($ExternalId2 != "") {


                /* inserta un nuevo detalle de producto si se cumplen ciertas condiciones. */
                if ($ExternalId2 != '' && $ExternalId2 != '0' && ($Proveedor->getAbreviado() != 'SMARTSOFT' && $Proveedor->getAbreviado() != 'SWINTT')) {
                    $ProductoDetalle = new ProductoDetalle();
                    $ProductoDetalle->setProductoId($Producto->productoId);
                    $ProductoDetalle->setPKey("GAMEID");
                    $ProductoDetalle->setPValue($ExternalId2);
                    $ProductoDetalle->setUsucreaId(0);
                    $ProductoDetalle->setUsumodifId(0);

                    $ProductoDetalle->insert($ProductoMySqlDAO->getTransaction());
                }


                /* Condición para insertar un detalle de producto basado en identificador externo y proveedor. */
                if ($ExternalId2 != '' && $ExternalId2 != '0' && ($Proveedor->getAbreviado() == 'SMARTSOFT' || $Proveedor->getAbreviado() == 'SWINTT')) {
                    $ProductoDetalle = new ProductoDetalle();
                    $ProductoDetalle->setProductoId($Producto->productoId);
                    $ProductoDetalle->setPKey("CATEGORY");
                    $ProductoDetalle->setPValue($ExternalId2);
                    $ProductoDetalle->setUsucreaId(0);
                    $ProductoDetalle->setUsumodifId(0);

                    $ProductoDetalle->insert($ProductoMySqlDAO->getTransaction());
                }
                //$Producto->setExternoId($ExternalId);
            }


            /* confirma una transacción en una base de datos MySQL utilizando PHP. */
            $ProductoMySqlDAO->getTransaction()->commit();
        } else {

            /* asigna valores de parámetros y transforma el campo "Mobile". */
            $IsActivate = $params->IsActivate;
            $IsVerified = $params->IsVerified;
            $Name = $params->Name;
            $ExternalId = $params->ExternalId;
            $ExternalId2 = $params->ExternalId2;
            $Mobile = ($params->Mobile == "A") ? "S" : "N";

            /* asigna valores a variables basadas en condiciones específicas y redefine una variable. */
            $Desktop = ($params->Desktop == "A") ? "S" : "N";
            $Visible = ($params->Visible == "A") ? "S" : "N";
            $Provider = $params->Provider;
            $ProviderId = $params->ProviderId;
            $Order = $params->Order;
            $Order = 0;


            /* procesa cadenas de socios y categorías en arrays, usando coma como delimitador. */
            $Partners = $params->Partners;
            $CategoriesAdd = $params->CategoriesAdd;

            $Partners = ($Partners != "") ? explode(",", $Partners) : array();
            $CategoriesAdd = ($CategoriesAdd != "") ? explode(",", $CategoriesAdd) : array();

            $arrayProducto = array();


            /* Se crean instancias de Subproveedor, Proveedor y Producto con identificadores correspondientes. */
            $SubProveedor = new Subproveedor($ProviderId);
            $Proveedor = new Proveedor($SubProveedor->getProveedorId());

            $ProviderId = $SubProveedor->getProveedorId();
            $SubProviderId = $SubProveedor->getSubproveedorId();

            $Producto = new Producto();


            /* configura propiedades de un objeto "Producto" utilizando datos de sesión y parámetros. */
            $Producto->setEstado($IsActivate);
            $Producto->setVerifica($IsVerified);
            $Producto->setDescripcion($Name);
            $Producto->setUsumodifId($_SESSION['usuario2']);
            $Producto->setExternoId($ExternalId);
            $Producto->setMobile($Mobile);

            /* establece propiedades de un objeto "Producto" utilizando datos de sesión. */
            $Producto->setDesktop($Desktop);

            $Producto->setUsumodifId($_SESSION['usuario2']);
            $Producto->setUsucreaId($_SESSION['usuario2']);
            $Producto->setMostrar($Visible);
            $Producto->setOrden($Order);


            /* inserta un producto en la base de datos utilizando DAO y gestiona transacciones. */
            $Producto->setProveedorId($ProviderId);
            $Producto->setSubproveedorId($SubProviderId);

            $ProductoMySqlDAO = new ProductoMySqlDAO();
            $Producto_id = $ProductoMySqlDAO->insert($Producto);
            $ProductoMySqlDAO->getTransaction()->commit();


            /* Se crea un objeto DAO y se obtiene una transacción asociada a él. */
            $ProductoMandanteMySqlDAO = new ProductoMandanteMySqlDAO();
            $Transaction = $ProductoMandanteMySqlDAO->getTransaction();


            if ($ExternalId2 != "") {


                /* Condicional que inserta detalles del producto si se cumplen ciertos criterios. */
                if ($ExternalId2 != '' && $ExternalId2 != '0' && ($Proveedor->getAbreviado() != 'SMARTSOFT' && $Proveedor->getAbreviado() != 'SWINTT')) {
                    $ProductoDetalle = new ProductoDetalle();
                    $ProductoDetalle->setProductoId($Producto->productoId);
                    $ProductoDetalle->setPKey("GAMEID");
                    $ProductoDetalle->setPValue($ExternalId2);
                    $ProductoDetalle->setUsucreaId(0);
                    $ProductoDetalle->setUsumodifId(0);

                    $ProductoDetalle->insert($Transaction);
                }


                /* Condición que inserta un detalle de producto si cumplen criterios específicos y proveedor. */
                if ($ExternalId2 != '' && $ExternalId2 != '0' && ($Proveedor->getAbreviado() == 'SMARTSOFT' || $Proveedor->getAbreviado() == 'SWINTT')) {
                    $ProductoDetalle = new ProductoDetalle();
                    $ProductoDetalle->setProductoId($Producto->productoId);
                    $ProductoDetalle->setPKey("CATEGORY");
                    $ProductoDetalle->setPValue($ExternalId2);
                    $ProductoDetalle->setUsucreaId(0);
                    $ProductoDetalle->setUsumodifId(0);

                    $ProductoDetalleMySqlDAO = new ProductoDetalleMySqlDAO($Transaction);

                    $ProductoDetalleMySqlDAO->insert($ProductoDetalle);

                }
                //$Producto->setExternoId($ExternalId);
            }

            foreach ($Partners as $key => $value) {

                /* Actualiza el estado de un producto en la base de datos utilizando DAO. */
                try {
                    $ProductoMandante = new ProductoMandante($Producto_id, $value);

                    $ProductoMandante->estado = 'A';
                    $ProductoMandanteMySqlDAO = new ProductoMandanteMySqlDAO($Transaction);
                    $ProductoMandanteMySqlDAO->update($ProductoMandante);

                } catch (Exception $e) {
                    if ($e->getCode() == "27") {


                        /* Se crea un objeto y se asignan propiedades relacionadas a un producto y mandante. */
                        $ProductoMandante = new ProductoMandante();

                        $ProductoMandante->mandante = $value;
                        $ProductoMandante->productoId = $Producto_id;
                        $ProductoMandante->estado = 'A';
                        $ProductoMandante->verifica = 'I';

                        /* Configura propiedades de un objeto ProductoMandante con valores específicos. */
                        $ProductoMandante->filtroPais = 'I';
                        $ProductoMandante->filtroPais = 'I';
                        $ProductoMandante->max = 0;
                        $ProductoMandante->min = 0;
                        $ProductoMandante->detalle = '';
                        $ProductoMandante->orden = 100000;

                        /* Se inicializan propiedades del objeto ProductoMandante y se asigna un DAO. */
                        $ProductoMandante->numFila = 1;
                        $ProductoMandante->numColumna = 1;
                        $ProductoMandante->ordenDestacado = 0;
                        $ProductoMandante->usucreaId = $_SESSION["usuario"];
                        $ProductoMandante->usumodifId = $_SESSION["usuario"];


                        $ProductoMandanteMySqlDAO = new ProductoMandanteMySqlDAO($Transaction);

                        /* Inserta un producto y almacena su ID en un arreglo. */
                        $prodmandid = $ProductoMandanteMySqlDAO->insert($ProductoMandante);

                        array($arrayProducto, $prodmandid);


                    }

                }


                /* inserta categorías de productos en una base de datos. */
                foreach ($CategoriesAdd as $item2) {
                    $CategoriaProducto = new CategoriaProducto();


                    $CategoriaProducto->setCategoriaId($item2);
                    $CategoriaProducto->setProductoId($Producto_id);

                    $CategoriaProducto->setUsucreaId($_SESSION['usuario2']);
                    $CategoriaProducto->setUsumodifId($_SESSION['usuario2']);


                    $CategoriaProducto->setEstado('A');
                    $CategoriaProducto->setOrden(100000);
                    $CategoriaProducto->setMandante($value);

                    $CategoriaProductoMySqlDAO = new CategoriaProductoMySqlDAO($Transaction);
                    $CategoriaProductoMySqlDAO->insert($CategoriaProducto);

                }

            }


            /* confirma todas las acciones realizadas en una transacción de base de datos. */
            $Transaction->commit();


        }
    }
} catch (Exception $e) {
    /* Maneja excepciones en PHP sin realizar ninguna acción específica. */

}
/**
 * Normaliza una cadena de texto reemplazando caracteres especiales
 * por sus equivalentes en ASCII.
 *
 * @param string $string La cadena a normalizar.
 * @return string La cadena normalizada.
 */
function normalize($string)
{
    $table = array(
        'Š' => 'S', 'š' => 's', 'Đ' => 'Dj', 'đ' => 'dj', 'Ž' => 'Z', 'ž' => 'z', 'Č' => 'C', 'č' => 'c', 'Ć' => 'C', 'ć' => 'c',
        'À' => 'A', 'Á' => 'A', 'Â' => 'A', 'Ã' => 'A', 'Ä' => 'A', 'Å' => 'A', 'Æ' => 'A', 'Ç' => 'C', 'È' => 'E', 'É' => 'E',
        'Ê' => 'E', 'Ë' => 'E', 'Ì' => 'I', 'Í' => 'I', 'Î' => 'I', 'Ï' => 'I', 'Ñ' => 'N', 'Ò' => 'O', 'Ó' => 'O', 'Ô' => 'O',
        'Õ' => 'O', 'Ö' => 'O', 'Ø' => 'O', 'Ù' => 'U', 'Ú' => 'U', 'Û' => 'U', 'Ü' => 'U', 'Ý' => 'Y', 'Þ' => 'B', 'ß' => 'Ss',
        'à' => 'a', 'á' => 'a', 'â' => 'a', 'ã' => 'a', 'ä' => 'a', 'å' => 'a', 'æ' => 'a', 'ç' => 'c', 'è' => 'e', 'é' => 'e',
        'ê' => 'e', 'ë' => 'e', 'ì' => 'i', 'í' => 'i', 'î' => 'i', 'ï' => 'i', 'ð' => 'o', 'ñ' => 'n', 'ò' => 'o', 'ó' => 'o',
        'ô' => 'o', 'õ' => 'o', 'ö' => 'o', 'ø' => 'o', 'ù' => 'u', 'ú' => 'u', 'û' => 'u', 'ý' => 'y', 'ý' => 'y', 'þ' => 'b',
        'ÿ' => 'y', 'Ŕ' => 'R', 'ŕ' => 'r',
    );

    return strtr($string, $table);
}
