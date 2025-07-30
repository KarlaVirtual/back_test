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
use Backend\dto\EtiquetaProducto;
use Backend\dto\FlujoCaja;
use Backend\dto\Flujocajafact;
use Backend\dto\GeneralLog;
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
use Backend\mysql\EtiquetaProductoMySqlDAO;
use Backend\mysql\FlujoCajaMySqlDAO;
use Backend\mysql\GeneralLogMySqlDAO;
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
     * Product/UpdateProductDetails
     *
     * Actualizar detalles de productos
     *
     * @param int $Id id del producto
     * @param string $IsActivate si esta activo o no (A:I)
     * @param string $IsVerified si esta verificado o no (A:I)
     * @param string $Name nombre del producto
     * @param int $ExternalId Id externo del producto
     * @param int $ExternalId2 Id externo del producto 2
     * @param string $Mobile si esta activo o no (A:I)
     * @param string $Desktop si esta activo o no (A:I)
     * @param string $Visible si esta visible o no (A:I)
     * @param string $Provider proveedor vinculado al producto
     * @param string $Image Imagen vinculado al producto
     * @param string $Order Orden de visualizacion del producto dentro de la plataforma
     * @param string $TheoreticalRTP RTP teorico para el usuario al usar el producto
     * @param string $Category Categoria vinculada al producto
     * @param int $ProviderId id del proveedor del producto
     * @param string $Partners partners vinculados al producto
     * @param string $CategoriesAdd Categorias a ñadir al producto
     * @param string $TagsAdd Tags a añadir al producto
     *
     *
     * @returns object El objeto $response es un array con los siguientes atributos:
     *  - *HasError* (bool): Indica si hubo un error en la operación.
     *  - *AlertType* (string): Especifica el tipo de alerta que se mostrará en la vista.
     *  - *AlertMessage* (string): Contiene el mensaje que se mostrará en la vista.
     *  - *ModelErrors*  (array): Lista de errores generados, vacío si no hay errores.
     *
     * @throws no
     *
     * @access public
     * @see no
     * @since no
     * @deprecated no
     */


    /* Se asigna el valor de 'Id' desde el objeto 'params' a la variable '$Id'. */
    $Id = $params->Id;

    if ($_POST["upload_fullpath"] != "") {

        /* procesa datos del formulario, validando y sanitizando entradas específicas. */
        $Id = $_POST["Id"];

        $IsActivate = ($_POST["IsActivate"] != "A" && $_POST["IsActivate"] != "I") ? "" : $_POST["IsActivate"];
        $IsVerified = ($_POST["IsVerified"] != "A" && $_POST["IsVerified"] != "I") ? "" : $_POST["IsVerified"];
        $Name = str_replace("'", " ", $_POST["Name"]);
        $ExternalId = $_POST["ExternalId"];

        /* Asigna valores condicionales a variables a partir de entradas del formulario POST. */
        $ExternalId2 = $_POST["ExternalId2"];
        $Mobile = ($_POST["Mobile"] == "A") ? "S" : "N";
        $Desktop = ($_POST["Desktop"] == "A") ? "S" : "N";
        $Visible = ($_POST["Visible"] == "A") ? "S" : "N";
        $Provider = $_POST["Provider"];
        $Image = $_POST["Image"];

        /* procesa datos de un formulario y formatea una lista de socios. */
        $Order = $_POST["Order"];
        $ProviderId = $_POST["ProviderId"];

        $Partners = $_POST["Partners"];
        $CategoriesAdd = $_POST["CategoriesAdd"];

        $Partners = ($Partners != "") ? explode(",", $Partners) : array();

        /* Separa cadenas en arrays, asignando valores solo si no están vacíos. */
        $CategoriesAdd = ($CategoriesAdd != "") ? explode(",", $CategoriesAdd) : array();


        $TagsAdd = $params->TagsAdd;

        $TagsAdd = ($TagsAdd != "") ? explode(",", $TagsAdd) : array();


        /* Se inicializan variables y objetos relacionados con productos y proveedores. */
        $arrayProducto = array();

        $Order = 0;

        $Producto = new Producto($Id);

        $SubProveedor = new Subproveedor($Provider->Id);

        /* Código que inicializa un proveedor y establece el estado de un producto. */
        $Proveedor = new Proveedor($SubProveedor->getProveedorId());

        $ProviderId = $SubProveedor->getProveedorId();
        $SubProviderId = $SubProveedor->getSubproveedorId();

        if ($IsActivate != "") {
            $Producto->setEstado($IsActivate);
        }

        /* Asigna un estado de verificación al producto según la variable $IsVerified. */
        if ($IsVerified != "") {
            $Producto->setVerifica($IsVerified);
        } else {
            $Producto->setVerifica('I');

        }

        /* establece descripciones y IDs externos si las variables no están vacías. */
        if ($Name != "") {
            $Producto->setDescripcion($Name);

        }
        if ($ExternalId != "") {
            $Producto->setExternoId($ExternalId);
        }


        /* asigna valores a un objeto según si son diferentes de vacío. */
        if ($Mobile != "") {
            $Producto->setMobile($Mobile);
        }
        if ($Desktop != "") {
            $Producto->setDesktop($Desktop);

        }

        /* Código que configura la visibilidad de un producto y valida tipo de imagen subida. */
        $Producto->setMostrar($Visible);

        $filename = $_FILES['upload']['name'];
        $filetype = $_FILES['upload']['type'];
        $fileTypeName = "";

        if ($filetype != "image/gif") {
            $fileTypeName = "png";
        } else {
            /* asigna "gif" a $fileTypeName si la condición previa no se cumple. */

            $fileTypeName = "gif";
        }

        /* Genera un nombre de archivo formateado a partir de la descripción del producto. */
        $filename = time() . '.' . $fileTypeName;
        $name = str_replace(' ', '-', $Producto->getDescripcion());
        $name = str_replace('(', '-', $name);
        $name = str_replace(')', '-', $name);
        $name = str_replace('&', '-', $name);
        $name = str_replace("'", '', $name);

        /* sanitiza un nombre y crea un nombre de archivo único. */
        $name = str_replace("'", '', $name);
        $name = str_replace(":", '', $name);
        $name = str_replace("/", '-', $name);
        $name = str_replace("?", '-', $name);
        $name = normalize($name);

        $filename = $name . "T" . time() . '.' . $fileTypeName;


        /* maneja la carga de imágenes a Google Cloud Storage. */
        $dirsave = '/tmp/' . $filename;

        if ($filetype == 'image/jpeg' or $filetype == 'image/png' or $filetype == 'image/gif') {
            if (move_uploaded_file($_FILES['upload']['tmp_name'], $dirsave)) {
                shell_exec('export BOTO_CONFIG=/home/backend/.config/gcloud/legacy_credentials/admin@bpoconsultores.com.co/.boto && export PATH=/sbin:/bin:/usr/sbin:/usr/bin:/home/backend/google-cloud-sdk/bin && gsutil -h "Cache-Control:public,max-age=604800" cp /tmp/' . $filename . ' gs://virtualcdnrealbucket/productos/');
                $Producto->setImageUrl('https://images.virtualsoft.tech/productos/' . $filename);
            } else {
            }
        }


        /* Reemplaza apóstrofes en descripción del producto y asigna orden si está presente. */
        $Producto->setDescripcion(str_replace("'", " ", $Producto->getDescripcion()));


        if ($Order != "") {
            $Producto->setOrden($Order);
        }
        //$Producto->setProveedorId($ProviderId);
        //$Producto->setSubproveedorId($SubProviderId);


        /*if ($Provider->Id != "") {
            $Producto->setProveedorId($Provider->Id);
        }*/


        /* Código que actualiza un producto usando datos de sesión del usuario. */
        $Producto->setUsumodifId($_SESSION['usuario2']);

        $ProductoMySqlDAO = new ProductoMySqlDAO();
        if ($Id != "") {
            $ProductoMySqlDAO->update($Producto);
        } else {
            /* inserta un producto en la base de datos si no hay errores previos. */

            $Producto_id = $ProductoMySqlDAO->insert($Producto);

        }

        /* confirma una transacción en una base de datos MySQL. */
        $ProductoMySqlDAO->getTransaction()->commit();


        if ($Id == "") {


            /* Se instancia un DAO y se obtiene una transacción relacionada con productos. */
            $ProductoMandanteMySqlDAO = new ProductoMandanteMySqlDAO();
            $Transaction = $ProductoMandanteMySqlDAO->getTransaction();

            foreach ($Partners as $key => $value) {

                /* actualiza el estado de un producto en la base de datos. */
                try {
                    $ProductoMandante = new ProductoMandante($Producto_id, $value);

                    $ProductoMandante->estado = 'A';
                    $ProductoMandanteMySqlDAO = new ProductoMandanteMySqlDAO($Transaction);
                    $ProductoMandanteMySqlDAO->update($ProductoMandante);

                } catch (Exception $e) {
                    if ($e->getCode() == "27") {


                        /* Se crea un objeto ProductoMandante con valores específicos y estado 'A'. */
                        $ProductoMandante = new ProductoMandante();

                        $ProductoMandante->mandante = $value;
                        $ProductoMandante->productoId = $Producto_id;
                        $ProductoMandante->estado = 'A';
                        $ProductoMandante->verifica = 'I';

                        /* Se definen propiedades de un objeto ProductoMandante con valores específicos. */
                        $ProductoMandante->filtroPais = 'I';
                        $ProductoMandante->filtroPais = 'I';
                        $ProductoMandante->max = 0;
                        $ProductoMandante->min = 0;
                        $ProductoMandante->detalle = '';
                        $ProductoMandante->orden = 100000;

                        /* Se establece atributos de un producto y se crea un DAO para manejo de datos. */
                        $ProductoMandante->numFila = 1;
                        $ProductoMandante->numColumna = 1;
                        $ProductoMandante->ordenDestacado = 0;
                        $ProductoMandante->usucreaId = $_SESSION["usuario"];
                        $ProductoMandante->usumodifId = $_SESSION["usuario"];


                        $ProductoMandanteMySqlDAO = new ProductoMandanteMySqlDAO($Transaction);

                        /* Inserta un producto y devuelve un array con el producto y su ID generado. */
                        $prodmandid = $ProductoMandanteMySqlDAO->insert($ProductoMandante);

                        array($arrayProducto, $prodmandid);


                    }

                }

            }


            /* realiza un compromiso de transacción en base de datos usando DAO. */
            $Transaction->commit();

            $CategoriaProductoMySqlDAO = new CategoriaProductoMySqlDAO();
            $Transaction = $CategoriaProductoMySqlDAO->getTransaction();


            /* Inserta múltiples categorías de productos en la base de datos usando un bucle. */
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


            /* Inserta un detalle de producto si $ExternalId2 no está vacío o `0`. */
            if ($ExternalId2 != "") {

                if ($ExternalId2 != '' && $ExternalId2 != '0') {
                    $ProductoDetalle = new ProductoDetalle();
                    $ProductoDetalle->setProductoId($Producto->productoId);
                    $ProductoDetalle->setPKey("GAMEID");
                    $ProductoDetalle->setPValue($ExternalId2);
                    $ProductoDetalle->setUsucreaId(0);
                    $ProductoDetalle->setUsumodifId(0);

                    $ProductoDetalle->insert($Transaction);
                }
                //$Producto->setExternoId($ExternalId);
            }


            /* Inserta etiquetas de producto en la base de datos para cada etiqueta en $TagsAdd. */
            foreach ($TagsAdd as $item3) {

                $EtiquetaProducto = new EtiquetaProducto();

                $EtiquetaProducto->setEtiquetaId($item2);
                $EtiquetaProducto->setProductoId($Producto_id);
                $EtiquetaProducto->setUsucreaId($_SESSION['usuario2']);
                $EtiquetaProducto->setUsumodifId($_SESSION['usuario2']);
                $EtiquetaProducto->setEstado('A');
                $EtiquetaProductoMySqlDAO = new EtiquetaProductoMySqlDAO($Transaction);
                $EtiquetaProductoMySqlDAO->insert($EtiquetaProducto);

            }

            /* Confirma la transacción en la base de datos, guardando los cambios realizados. */
            $Transaction->commit();

        }

    } else {

        if ($Id != "") {


            /* Condicionales establecen valores según parámetros para activar, verificar y otros atributos. */
            $IsActivate = ($params->IsActivate != "A" && $params->IsActivate != "I") ? "" : $params->IsActivate;
            $IsVerified = ($params->IsVerified != "A" && $params->IsVerified != "I") ? "" : $params->IsVerified;
            $Name = $params->Name;
            $ExternalId = $params->ExternalId;
            $ExternalId2 = $params->ExternalId2;
            $Mobile = ($params->Mobile == "A") ? "S" : "N";

            /* asigna valores basados en condiciones de parámetros recibidos. */
            $Desktop = ($params->Desktop == "A") ? "S" : "N";
            $Visible = ($params->Visible == "A") ? "S" : "N";
            $Provider = $params->Provider;
            $Image = $params->Image;
            $Order = $params->Order;
            $TheoreticalRTP = $params->TheoreticalRTP;

            /* La categoría se establece; si no hay, se asigna 0. Se crea un objeto Producto. */
            $Category = $params->Category ?: 0;
            $Producto = new Producto($Id);

            if ($IsActivate != "") {


                /* Se registra información del usuario en un registro general. */
                $GeneralLog = new GeneralLog();
                $GeneralLog->setUsuarioId($_SESSION['usuario2']);
                $GeneralLog->setUsuarioIp($_SESSION['dir_ip']);
                $GeneralLog->setUsuariosolicitaId($_SESSION['usuario2']);
                $GeneralLog->setUsuariosolicitaIp($_SESSION['dir_ip']);
                $GeneralLog->setUsuarioaprobarId(0);

                /* Configura un registro de log para un cambio de producto en la aplicación. */
                $GeneralLog->setUsuarioaprobarIp('');
                $GeneralLog->setTipo('CHANGEPRODUCT');
                $GeneralLog->setUsucreaId(0);
                $GeneralLog->setUsumodifId(0);
                $GeneralLog->setEstado('A');
                $GeneralLog->setSoperativo('');

                /* Configura un registro general con información de dispositivo y producto en la base de datos. */
                $GeneralLog->setDispositivo($Global_dispositivo);
                $GeneralLog->setSversion('');
                $GeneralLog->setImagen('');
                $GeneralLog->setExternoId($Producto->productoId);
                $GeneralLog->setCampo('estado');
                $GeneralLog->setTabla('categoria_mandante');

                /* Registro de cambios en producto: actualización de estado y descripción. */
                $GeneralLog->setExplicacion('Se actualizo el producto ' . $Producto->productoId . '-' . $Producto->descripcion);
                $GeneralLog->setMandante($_SESSION['mandante']);
                $GeneralLog->setValorAntes($Producto->getEstado());
                $GeneralLog->setValorDespues($IsActivate);

                $GeneralLogMySqlDAO = new GeneralLogMySqlDAO();

                /* Inserta un registro en el log general y actualiza el estado de un producto. */
                $GeneralLogMySqlDAO->insert($GeneralLog);
                $GeneralLogMySqlDAO->getTransaction()->commit();

                $Producto->setEstado($IsActivate);
            }

            /* Verifica y asigna valores a un producto si no están vacíos. */
            if ($IsVerified != "") {
                $Producto->setVerifica($IsVerified);
            }
            if ($Name != "") {
                $Producto->setDescripcion($Name);

            }

            /* asigna valores a un objeto solo si las variables no están vacías. */
            if ($ExternalId != "") {
                $Producto->setExternoId($ExternalId);
            }

            if ($Mobile != "") {
                $Producto->setMobile($Mobile);
            }

            /* asigna valores a un objeto Producto si se cumplen ciertas condiciones. */
            if ($Desktop != "") {
                $Producto->setDesktop($Desktop);

            }

            $Producto->setMostrar($Visible);

            /* maneja la carga y procesamiento de imágenes en un servidor específico. */
            if ($params->upload != "" && $params->upload_fullpath != "") {
                $filename = $_FILES['file']['name'];
                $filetype = $_FILES['file']['type'];

                $filename = time() . '.' . $_POST["fileType"];

                $filename = $Producto->getDescripcion() . "T" . time() . '.' . $_POST["fileType"];

                $filename = str_replace('&', '-', $filename);

                $dirsave = '/tmp/' . $filename;
                if ($filetype == 'image/jpeg' or $filetype == 'image/png' or $filetype == 'image/gif') {
                    if (move_uploaded_file($_FILES['file']['tmp_name'], $dirsave)) {
                        $Producto->setImageUrl('https://images.virtualsoft.tech/productos/' . $filename);
                    } else {

                    }
                }
            } else {
                /* Verifica si $Image no está vacío y asigna su valor a setImageUrl. */

                if ($Image != "") {
                    $Producto->setImageUrl($Image);
                }
            }

            /* Reemplaza apóstrofes en la descripción del producto y establece su orden si existe. */
            $Producto->setDescripcion(str_replace("'", " ", $Producto->getDescripcion()));


            if ($Order != "") {
                $Producto->setOrden($Order);
            }


            /* asigna un proveedor y usuario a un producto, estableciendo su RTP teórico. */
            if ($Provider->Id != "") {
                $Producto->setProveedorId($Provider->Id);
            }

            $Producto->setUsumodifId($_SESSION['usuario2']);
            $Producto->setRtpTeorico($TheoreticalRTP);

            /* Actualiza un producto asignándole una categoría usando un Data Access Object. */
            $Producto->setCategoriaId($Category);

            $ProductoMySqlDAO = new ProductoMySqlDAO();
            $ProductoMySqlDAO->update($Producto);
            $ProductoMySqlDAO->getTransaction()->commit();


            if ($ExternalId2 != "") {


                /* actualiza un detalle de producto en la base de datos usando transacciones. */
                $Proveedor = new Proveedor($Provider->Id);


                try {

                    $ProductoDetalle = new ProductoDetalle('', $Id, "GAMEID");
                    $Id_Producto_detalle = $ProductoDetalle->productodetalleId;

                    $ProductoDetalle = new ProductoDetalle($Id_Producto_detalle);
                    $ProductoDetalle->setProductoId($Producto->productoId);
                    $ProductoDetalle->setPKey("GAMEID");
                    $ProductoDetalle->setPValue($ExternalId2);
                    $ProductoDetalle->setUsucreaId(0);
                    $ProductoDetalle->setUsumodifId(0);

                    $ProductoDetalleMySqlDAO = new ProductoDetalleMySqlDAO();
                    $transaction = $ProductoDetalleMySqlDAO->getTransaction();
                    $ProductoDetalleMySqlDAO->update($ProductoDetalle);
                    $ProductoDetalleMySqlDAO->getTransaction()->commit();

                } catch (Exception $e) {
                    /* Manejo de excepciones que inserta un producto detallado en la base de datos. */

                    if ($e->getCode() == 01) {

                        $ProductoDetalle = new ProductoDetalle();
                        $ProductoDetalle->setProductoId($Producto->productoId);
                        $ProductoDetalle->setPKey("GAMEID");
                        $ProductoDetalle->setPValue($ExternalId2);
                        $ProductoDetalle->setUsucreaId(0);
                        $ProductoDetalle->setUsumodifId(0);

                        $ProductoDetalleMySqlDAO = new ProductoDetalleMySqlDAO();
                        $ProductoDetalleMySqlDAO->insert($ProductoDetalle);
                        $ProductoDetalleMySqlDAO->getTransaction()->commit();
                    }
                }
            } else {


                /* Modifica detalles de un producto en una base de datos utilizando transacciones. */
                $Proveedor = new Proveedor($Provider->Id);


                try {

                    $ProductoDetalle = new ProductoDetalle('', $Id, "GAMEID");
                    $Id_Producto_detalle = $ProductoDetalle->productodetalleId;

                    $ProductoDetalle = new ProductoDetalle($Id_Producto_detalle);
                    $ProductoDetalle->setProductoId($Producto->productoId);
                    $ProductoDetalle->setPKey("GAMEID");
                    $ProductoDetalle->setPValue($ExternalId2);
                    $ProductoDetalle->setUsucreaId(0);
                    $ProductoDetalle->setUsumodifId(0);

                    $ProductoDetalleMySqlDAO = new ProductoDetalleMySqlDAO();
                    $transaction = $ProductoDetalleMySqlDAO->getTransaction();
                    $ProductoDetalleMySqlDAO->update($ProductoDetalle);
                    $ProductoDetalleMySqlDAO->getTransaction()->commit();

                } catch (Exception $e) {
                    /* Bloque que captura excepciones en PHP sin realizar ninguna acción con ellas. */

                }

            }


        } else {


            /* Asigna valores de parámetros a variables y evalúa la condición del móvil. */
            $IsActivate = $params->IsActivate;
            $IsVerified = $params->IsVerified;
            $Name = $params->Name;
            $ExternalId = $params->ExternalId;
            $ExternalId2 = $params->ExternalId2;
            $Mobile = ($params->Mobile == "A") ? "S" : "N";

            /* Asigna valores condicionales y extrae parámetros de un objeto en PHP. */
            $Desktop = ($params->Desktop == "A") ? "S" : "N";
            $Visible = ($params->Visible == "A") ? "S" : "N";
            $Provider = $params->Provider;
            $ProviderId = $params->ProviderId;
            $Order = $params->Order;
            $Order = 0;

            /* Se asignan y procesan parámetros para configurar retorno teórico y categorías de socios. */
            $TheoreticalRTP = $params->TheoreticalRTP;
            $Category = $params->Category ?: 0;

            $Partners = $params->Partners;
            $CategoriesAdd = $params->CategoriesAdd;

            $Partners = ($Partners != "") ? explode(",", $Partners) : array();

            /* Convierte cadenas de texto en arreglos utilizando comas como separador si no están vacías. */
            $CategoriesAdd = ($CategoriesAdd != "") ? explode(",", $CategoriesAdd) : array();


            $TagsAdd = $params->TagsAdd;


            $TagsAdd = ($TagsAdd != "") ? explode(",", $TagsAdd) : array();


            /* Se inicializan objetos de Subproveedor y Proveedor usando un ID específico. */
            $arrayProducto = array();

            $SubProveedor = new Subproveedor($ProviderId);
            $Proveedor = new Proveedor($SubProveedor->getProveedorId());

            $ProviderId = $SubProveedor->getProveedorId();

            /* obtiene un ID de subproveedor y asegura un estado de verificación. */
            $SubProviderId = $SubProveedor->getSubproveedorId();

            $Producto = new Producto();

            if ($IsVerified == '') {
                $IsVerified = 'I';
            }


            /* establece propiedades de un objeto Producto usando valores proporcionados. */
            $Producto->setEstado($IsActivate);
            $Producto->setVerifica($IsVerified);
            $Producto->setDescripcion($Name);
            $Producto->setUsumodifId($_SESSION['usuario2']);
            $Producto->setExternoId($ExternalId);
            $Producto->setMobile($Mobile);

            /* asigna valores a propiedades de un objeto "Producto" usando datos de sesión. */
            $Producto->setDesktop($Desktop);

            $Producto->setUsumodifId($_SESSION['usuario2']);
            $Producto->setUsucreaId($_SESSION['usuario2']);
            $Producto->setMostrar($Visible);
            $Producto->setOrden($Order);


            /* Se configuran propiedades de un objeto 'Producto' con valores específicos. */
            $Producto->setVerifica('I');
            $Producto->setPagoTerceros(0);
            $Producto->setProveedorId($ProviderId);
            $Producto->setSubproveedorId($SubProviderId);
            $Producto->setRtpTeorico($TheoreticalRTP);
            $Producto->setCategoriaId($Category);


            /* Se inserta un producto y se confirma la transacción en la base de datos. */
            $ProductoMySqlDAO = new ProductoMySqlDAO();
            $Producto_id = $ProductoMySqlDAO->insert($Producto);
            $ProductoMySqlDAO->getTransaction()->commit();


            $ProductoMandanteMySqlDAO = new ProductoMandanteMySqlDAO();

            /* inserta un detalle de producto si se proporciona un identificador externo válido. */
            $Transaction = $ProductoMandanteMySqlDAO->getTransaction();


            if ($ExternalId2 != "") {

                if ($ExternalId2 != '' && $ExternalId2 != '0') {
                    $ProductoDetalle = new ProductoDetalle();
                    $ProductoDetalle->setProductoId($Producto->productoId);
                    $ProductoDetalle->setPKey("GAMEID");
                    $ProductoDetalle->setPValue($ExternalId2);
                    $ProductoDetalle->setUsucreaId(0);
                    $ProductoDetalle->setUsumodifId(0);

                    $ProductoDetalle->insert($Transaction);
                }
                //$Producto->setExternoId($ExternalId);
            }


            foreach ($Partners as $key => $value) {

                /* Código para actualizar el estado de un producto en una base de datos MySQL. */
                try {
                    $ProductoMandante = new ProductoMandante($Producto_id, $value);

                    $ProductoMandante->estado = 'A';
                    $ProductoMandanteMySqlDAO = new ProductoMandanteMySqlDAO($Transaction);
                    $ProductoMandanteMySqlDAO->update($ProductoMandante);

                } catch (Exception $e) {
                    if ($e->getCode() == "27") {


                        /* Se crea un objeto ProductoMandante y se inicializan sus propiedades. */
                        $ProductoMandante = new ProductoMandante();

                        $ProductoMandante->mandante = $value;
                        $ProductoMandante->productoId = $Producto_id;
                        $ProductoMandante->estado = 'A';
                        $ProductoMandante->verifica = 'I';

                        /* Asignación de propiedades a un objeto ProductoMandante con valores específicos. */
                        $ProductoMandante->filtroPais = 'I';
                        $ProductoMandante->filtroPais = 'I';
                        $ProductoMandante->max = 0;
                        $ProductoMandante->min = 0;
                        $ProductoMandante->detalle = '';
                        $ProductoMandante->orden = 100000;

                        /* Se asignan valores a propiedades de un objeto y se crea un DAO. */
                        $ProductoMandante->numFila = 1;
                        $ProductoMandante->numColumna = 1;
                        $ProductoMandante->ordenDestacado = 0;
                        $ProductoMandante->usucreaId = $_SESSION["usuario"];
                        $ProductoMandante->usumodifId = $_SESSION["usuario"];


                        $ProductoMandanteMySqlDAO = new ProductoMandanteMySqlDAO($Transaction);

                        /* Inserta un producto mandante y guarda su ID en un array. */
                        $prodmandid = $ProductoMandanteMySqlDAO->insert($ProductoMandante);

                        array($arrayProducto, $prodmandid);


                    }

                }


                /* Se insertan categorías asociadas a un producto en una base de datos. */
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


            /* inserta etiquetas de productos en la base de datos usando un bucle. */
            try {

                foreach ($TagsAdd as $item3 => $value3) {
                    $EtiquetaProducto = new EtiquetaProducto();


                    $EtiquetaProducto->setEtiquetaId($value3);
                    $EtiquetaProducto->setProductoId($Producto_id);
                    $EtiquetaProducto->setUsucreaId($_SESSION['usuario2']);
                    $EtiquetaProducto->setUsumodifId($_SESSION['usuario2']);
                    $EtiquetaProducto->setEstado('A');
                    $EtiquetaProductoMySqlDAO = new EtiquetaProductoMySqlDAO($Transaction);
                    $EtiquetaProductoMySqlDAO->insert($EtiquetaProducto);

                }
            } catch (Exception $e) {
                /* Manejo de excepciones en PHP para capturar errores sin interrumpir la ejecución. */


            }

            /* Confirma y guarda todos los cambios realizados en la transacción actual. */
            $Transaction->commit();


        }


        /* inicializa un arreglo para gestionar respuestas con posibles errores. */
        $response = [];
        $response['HasError'] = false;
        $response['AlertType'] = 'success';
        $response['AlertMessage'] = '';
        $response['ModelErrors'] = [];
    }
} catch (Exception $e) {
    /* Manejo de excepciones en PHP, mostrando información en modo debug y creando respuesta de error. */


    if ($_ENV["debug"]) {
        print_r('eNTRO');
        print_r($e);
    }

    $response = [];
    $response['HasError'] = true;
    $response['AlertType'] = 'error';
    $response['AlertMessage'] = '';
    $response['ModelErrors'] = [];
}
/**
 * Normaliza una cadena de texto reemplazando caracteres especiales por sus equivalentes ASCII.
 *
 * Este método toma una cadena de texto como entrada y utiliza una tabla de traducción
 * para convertir caracteres acentuados y especiales en sus representaciones en
 * el alfabeto latino básico. Es especialmente útil para preparar textos
 * que serán utilizados en URLs o identificadores amigables.
 *
 * @param string $string La cadena de texto a normalizar.
 * @return string La cadena de texto normalizada.
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
