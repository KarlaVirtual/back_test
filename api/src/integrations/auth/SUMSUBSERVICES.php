<?php

/**
 * Contiene métodos para establecer conexiones a una URL específica.
 *
 * @category Seguridad
 * @package  Auth
 * @author   Desconocido
 * @version  1.0.0
 * @since    2025-03-14
 */

namespace Backend\integrations\auth;

use Backend\dto\BonoInterno;
use Backend\dto\Categoria;
use Backend\dto\CategoriaProducto;
use Backend\dto\Clasificador;
use Backend\dto\ConfigurationEnvironment;
use Backend\dto\Mandante;
use Backend\dto\MandanteDetalle;
use Backend\dto\Pais;
use Backend\dto\Producto;
use Backend\dto\ProductoDetalle;
use Backend\dto\Proveedor;
use Backend\dto\PuntoVenta;
use Backend\dto\Registro;
use Backend\dto\SitioTracking;
use Backend\dto\Subproveedor;
use Backend\dto\SubproveedorMandantePais;
use Backend\dto\Template;
use Backend\dto\TransaccionJuego;
use Backend\dto\TransaccionProducto;
use Backend\dto\TransjuegoLog;
use Backend\dto\TransprodLog;
use Backend\dto\Usuario;
use Backend\dto\UsuarioConfiguracion;
use Backend\dto\UsuarioLog;
use Backend\dto\UsuarioLog2;
use Backend\dto\UsuarioMandante;
use Backend\dto\UsuarioMensaje;
use Backend\dto\UsuarioOtrainfo;
use Backend\dto\UsuarioToken;
use Backend\dto\UsuarioVerificacion;
use Backend\dto\VerificacionLog;
use Backend\integrations\poker\ESAGAMING;
use Backend\mysql\BonoInternoMySqlDAO;
use Backend\mysql\ProductoMySqlDAO;
use Backend\mysql\PuntoVentaMySqlDAO;
use Backend\mysql\RegistroMySqlDAO;
use Backend\mysql\TransaccionJuegoMySqlDAO;
use Backend\mysql\TransaccionProductoMySqlDAO;
use Backend\mysql\UsuarioConfiguracionMySqlDAO;
use Backend\mysql\UsuarioLog2MySqlDAO;
use Backend\mysql\UsuarioLogMySqlDAO;
use Backend\mysql\UsuarioMensajeMySqlDAO;
use Backend\mysql\UsuarioMySqlDAO;
use Backend\mysql\UsuarioOtrainfoMySqlDAO;
use Backend\mysql\UsuarioTokenMySqlDAO;
use Backend\mysql\UsuarioVerificacionMySqlDAO;
use Backend\mysql\VerificacionLogMySqlDAO;
use \CurlWrapper;
use DateTime;
use Exception;

/**
 * SUMSUBSERVICES
 *
 * La clase `SUMSUBSERVICES` en PHP contiene métodos para establecer conexiones a una URL específica,
 * procesar los resultados de la verificación de la API de Sumsub, y actualizar la información del usuario
 * basándose en el resultado de la verificación.
 */
class SUMSUBSERVICES
{

    /**
     * Función constructora.
     *
     * La función constructora establece diferentes valores dependiendo de si el es de desarrollo o de producción.
     *
     * No devuelven ningún valor, el constructor se encargan de inicializar un objeto, En lugar de @return.
     */
    public function __construct()
    {
        $ConfigurationEnvironment = new ConfigurationEnvironment();

        if ($ConfigurationEnvironment->isDevelopment()) {
        } else {
        }
    }

    /**
     * Genera la URL de redirección para la verificación Sumsub.
     *
     * Este método construye la URL necesaria para iniciar el proceso de verificación
     * con Sumsub, utilizando los datos del usuario mandante y el nivel de verificación
     * configurado. Si se proporciona un objeto de verificación de usuario, registra
     * un log con la respuesta obtenida de la API.
     *
     * @param UsuarioMandante $UsuarioMandante     Objeto con la información del usuario mandante.
     * @param mixed           $UsuarioVerificacion Objeto o stdClass con la verificación del usuario.
     *
     * @return array Retorna un arreglo con la clave `success` y la URL generada para la verificación.
     */
    public function connectionUrl($UsuarioMandante, $UsuarioVerificacion = null)
    {
        $Subproveedor = new Subproveedor('', 'SUMSUB');
        $Usuario = new Usuario($UsuarioMandante->usuarioMandante);
        $SubproveedorMandantePais = new SubproveedorMandantePais('', $Subproveedor->getSubproveedorId(), $UsuarioMandante->mandante, $UsuarioMandante->paisId);
        $credentials = json_decode($SubproveedorMandantePais->getCredentials());
        $Clasificador = new Clasificador('', 'LEVELNAME');
        $MandanteDetalle = new MandanteDetalle('', $UsuarioMandante->mandante, $Clasificador->getClasificadorId(), $UsuarioMandante->paisId, "A");

        $levelName = $MandanteDetalle->valor;
        $encodedLevel = rawurlencode($levelName);
        $id = $this->obtenerUsuverificacionId($UsuarioVerificacion);

        $path = "/resources/sdkIntegrations/levels/" . $encodedLevel . "/websdkLink";

        //Lamado a la Api Sumsub
        $response = $this->connectionSumsub($path, $Usuario, $credentials, $method = "POST");

        // Registra un log de verificación si el objeto UsuarioVerificacion no es nulo.
        if ($UsuarioVerificacion != null) {
            $VerificacionLog = new VerificacionLog();
            $VerificacionLog->setUsuverificacionId($id);
            $VerificacionLog->setTipo('URLREDIRECTION');
            $VerificacionLog->setJson((($response)));

            $VerificacionLogMySqlDAO = new VerificacionLogMySqlDAO();
            $VerificacionLogMySqlDAO->insert($VerificacionLog);
            $VerificacionLogMySqlDAO->getTransaction()->commit();
        }

        $response = json_decode($response);

        $data = array();
        $data["success"] = true;
        $data["url"] = $response->url;

        return $data;
    }

    /**
     * La función `process` procesa los resultados de la verificación de la API de Sumsub
     *
     * La función `process` procesa los resultados de la verificación de la API de Sumsub y actualiza
     * la información del usuario basándose en el resultado de la verificación.
     *
     * @param string $account      Identificador de la cuenta del usuario.
     * @param string $status       Estado de la verificación.
     * @param string $accountId    Identificador de la cuenta en el sistema de verificación externo.
     * @param object $reviewResult Objeto que contiene el resultado de la revisión.
     * @param string $inspectionId Identificador de la inspección en el sistema de verificación externo.
     *
     * @return string una variable de respuesta codificada en JSON.
     */
    public function process($account, $status, $accountId, $reviewResult, $inspectionId)
    {
        $Usuario = new Usuario($account);
        $UsuarioMandante = new UsuarioMandante("", $Usuario->usuarioId, $Usuario->mandante);
        $Subproveedor = new Subproveedor('', 'SUMSUB');
        $SubproveedorMandantePais = new SubproveedorMandantePais('', $Subproveedor->getSubproveedorId(), $UsuarioMandante->mandante, $UsuarioMandante->paisId);
        $credentials = json_decode($SubproveedorMandantePais->getCredentials());

        //Validación sobre decision de Sumsub
        $esRechazoTemporal = false;
        $esVerificado = false;
        $esRechazoFinal = false;
        $esRechazo = false;
        $esDocumentoIncorrecto = false;
        $esRechazoId = false;
        $esRechazoSelfie = false;
        $esRechazoMenorEdad = false;

        // Validación de existencia de propiedades antes de acceder
        $reviewAnswer = property_exists($reviewResult, 'reviewAnswer') ? $reviewResult->reviewAnswer : null;
        $reviewRejectType = property_exists($reviewResult, 'reviewRejectType') ? $reviewResult->reviewRejectType : null;

        if ($reviewAnswer !== "GREEN" && $reviewRejectType === "RETRY") {
            $esRechazoTemporal = true;
            $esVerificado = false;
        } elseif ($reviewAnswer !== "GREEN" && $reviewRejectType === "FINAL") {
            $esRechazoFinal = true;
        }

        //GET Details Applicant
        $path = "/resources/applicants/" . $accountId . "/one";

        $response = $this->connectionSumsub($path, $Usuario, $credentials, $method = "GET");
        $Response = json_decode($response);

        $Registro = new Registro("", $Usuario->usuarioId);
        $UsuarioOtraInfo = new UsuarioOtrainfo($Usuario->usuarioId);

        try {
            $Clasificador = new Clasificador('', 'RECHAZOTEMPVERIF');
            $ClasificadorId = $Clasificador->getClasificadorId();
            $UsuarioConfiguracion = new UsuarioConfiguracion($Usuario->usuarioId, 'A', $ClasificadorId);
        } catch (Exception $e) {
        }

        $VerificaFiltro = "A";

        //Validacion de Documento configurado desde Partner AJustes
        try {
            $ClasificadorFiltro = new Clasificador("", "VERIFICANUMDOC");
            $MandanteDetalle = new MandanteDetalle("", $UsuarioMandante->mandante, $ClasificadorFiltro->getClasificadorId(), $Usuario->paisId, $Usuario->estado);

            if ($MandanteDetalle->valor == 1) {
                $VerificaFiltro = "A";
            } else {
                $VerificaFiltro = "I";
            }
        } catch (Exception $e) {
        }

        //Validacion de Numero de Rechazos configurado desde Partner AJustes
        $NumRechazos = 0;
        try {
            $ClasificadorFiltro = new Clasificador("", "NUMRECHAZOS");
            $MandanteDetalle = new MandanteDetalle("", $UsuarioMandante->mandante, $ClasificadorFiltro->getClasificadorId(), $Usuario->paisId, "A");
            $NumRechazos = $MandanteDetalle->valor;
        } catch (Exception $e) {
        }

        try {
            //VERIFICA SI ESTA ACTIVA VALIDACION DE DATOS
            if ($VerificaFiltro == "A") {
                try {
                    $documentFields = ['number', 'additionalNumber', 'mrzLine2', 'mrzLine1'];
                    $DocumentSumsub = null;

                    foreach ($documentFields as $field) {
                        if (isset($Response->info->idDocs[0]->$field)) {
                            // Obtener el número de documento
                            $DocumentSumsubPersonal = preg_replace("/[ _\-*.]/", "", $Response->info->idDocs[0]->$field);
                            $Response->info->idDocs[0]->$field = $DocumentSumsubPersonal;

                            // Comparar con la cédula del registro
                            if ($DocumentSumsubPersonal == $Registro->getCedula()) {
                                $DocumentSumsub = $DocumentSumsubPersonal;
                                $esVerificado = false;
                                break;
                            }
                        }
                    }

                    // Si no coincide directamente, verificar con lógica de conteo
                    if ( ! $esVerificado && $DocumentSumsub === null) {
                        $DocumentSumsub = str_split($Response->info->idDocs[0]->number);
                        $DocumentSistema = str_split($Registro->getCedula());
                        $Conteo = 0;

                        foreach ($DocumentSumsub as $Key => $value) {
                            if ($value != $DocumentSistema[$Key]) {
                                $Conteo++;
                            }
                        }

                        if ($Conteo > 1 && isset($Response->info->idDocs[0]->mrzLine1)) {
                            $DocumentSumsub = str_split($Response->info->idDocs[0]->mrzLine1);
                            $DocumentSistema = str_split($Registro->getCedula());
                            $Conteo = 0;

                            foreach ($DocumentSumsub as $Key => $value) {
                                if ($value != $DocumentSistema[$Key]) {
                                    $Conteo++;
                                }
                            }

                            if ($Conteo > 1) {
                                $esRechazo = true;
                                $esDocumentoIncorrecto = true;
                            }
                        } elseif ($Conteo > 1) {
                            $esRechazo = true;
                            $esDocumentoIncorrecto = true;
                        }
                    }
                } catch (Exception $e) {
                    print_r($e);
                }
            }

            // Validación de edad
            $dob = $Response->info->idDocs[0]->dob ?? null;
            $Edad = 0;
            if ($dob) {
                $birthDate = new DateTime($dob);
                $currentDate = new DateTime();
                $Edad = $birthDate->diff($currentDate)->y;
            }

            if ($Edad && $Edad < 18) {
                $esRechazo = true;
                $esVerificado = false;
                $Observacion = "Menor de edad";
                $esRechazoMenorEdad = true;

                $UsuarioMySqlDAO = new UsuarioMySqlDAO();
                $Transaction = $UsuarioMySqlDAO->getTransaction();

                $UsuarioLogDAO = new UsuarioLogMySqlDAO($Transaction);
                $UsuarioDAO = new UsuarioMySqlDAO($Transaction);


                $UsuarioLog = new UsuarioLog();
                $UsuarioLog->setUsuarioId($Usuario->usuarioId);
                $UsuarioLog->setUsuarioIp('');
                $UsuarioLog->setUsuariosolicitaId(1);
                $UsuarioLog->setUsuariosolicitaIp('');
                $UsuarioLog->setTipo("ESTADOUSUARIO");
                $UsuarioLog->setEstado("A");
                $UsuarioLog->setValorAntes($Usuario->estado);
                $UsuarioLog->setValorDespues('I');
                $UsuarioLog->setUsucreaId(0);
                $UsuarioLog->setUsumodifId(0);

                $UsuarioLogDAO->insert($UsuarioLog);

                $Usuario->setEstado("I");
                $UsuarioDAO->update($Usuario);

                $Transaction->commit();
            }


            //GET status de SELFIE-ID
            $path = "/resources/applicants/" . $accountId . "/requiredIdDocsStatus";

            $responseStatus = $this->connectionSumsub($path, $Usuario, $credentials, $method = "GET");
            $ResponseStatus = json_decode($responseStatus);

            $reviewAnswerIdentity = $ResponseStatus->IDENTITY->reviewResult->reviewAnswer ?? null;
            $reviewRejectTypeIdentity = $ResponseStatus->IDENTITY->reviewResult->reviewRejectType ?? null;
            $reviewAnswerSelfie = $ResponseStatus->SELFIE->reviewResult->reviewAnswer ?? null;
            $reviewRejectTypeSelfie = $ResponseStatus->SELFIE->reviewResult->reviewRejectType ?? null;

            $idImage1 = $ResponseStatus->IDENTITY->imageIds[0] ?? null;
            $idImage2 = $ResponseStatus->IDENTITY->imageIds[1] ?? null;

            //ID DIFERENTE DE GREEN
            if ($reviewAnswerIdentity != "GREEN" && $reviewRejectTypeIdentity == "FINAL") {
                $esRechazo = true;
                $esRechazoId = true;
            }

            //SELFIE DIFERENTE DE GREEN
            if ($reviewAnswerSelfie != "GREEN" && $reviewRejectTypeSelfie == "FINAL") {
                $esRechazo = true;
                $esRechazoSelfie = true;
            }

            //Flujo de validacion para usuario aprobado.

            if ($Usuario->verificado != "S" && ! $esVerificado && ! $esRechazo) {
                // Procesa la aprobación de la verificación de un usuario, actualizando el estado de la verificación,
                // registrando logs y actualizando la información del usuario.

                $UsuarioVerificacion = new UsuarioVerificacion('', $Usuario->usuarioId, 'I', 'USUVERIFICACION');

                $UsuarioVerificacionMySqlDAO = new UsuarioVerificacionMySqlDAO();
                $Transaction = $UsuarioVerificacionMySqlDAO->getTransaction();

                $UsuarioVerificacionDAO = new UsuarioVerificacionMySqlDAO($Transaction);
                $VerificacionLogDAO = new VerificacionLogMySqlDAO($Transaction);
                $UsuarioDAO = new UsuarioMySqlDAO($Transaction);
                $UsuarioLog2DAO = new UsuarioLog2MySqlDAO($Transaction);
                $RegistroDAO = new RegistroMySqlDAO($Transaction);
                $UsuarioOtrainfoDAO = new UsuarioOtrainfoMySqlDAO($Transaction);

                $UsuarioVerificacion->setEstado('A');
                $UsuarioVerificacion->setObservacion('Aprobado por Sumsub');
                $UsuarioVerificacionDAO->update($UsuarioVerificacion);

                $VerificacionLog = new VerificacionLog();
                $VerificacionLog->setUsuverificacionId($UsuarioVerificacion->getUsuverificacionId());
                $VerificacionLog->setJson(json_encode($Response));
                $VerificacionLog->setTipo('FINALDECISION');
                $VerificacionLogDAO->insert($VerificacionLog);

                $Usuario = new Usuario();
                $Usuario->setAccountIdJumio($accountId);
                $Usuario->setVerificado("S");
                $Usuario->setVerifcedulaAnt("S");
                $Usuario->setVerifcedulaPost("S");
                $Usuario->setFechaVerificado(date("Y-m-d H:i:s"));
                $UsuarioDAO->update($Usuario);

                // Obtener imágenes de identificación
                foreach (['A' => $idImage1 ?? null, 'P' => $idImage2 ?? null] as $tipo => $idImage) {
                    if ($idImage) {
                        $path = "/resources/inspections/" . $inspectionId . "/resources/" . $idImage;
                        $responseImage = $this->connectionSumsub($path, $Usuario, $credentials, "GET");
                        $imageBase64 = base64_encode($responseImage);

                        $tipoLog = ($tipo === 'A') ? 'USUDNIANTERIOR' : 'USUDNIPOSTERIOR';
                        $estadoLog = 'A';

                        $this->guardarLogImagen($Usuario->usuarioId, $tipoLog, $imageBase64, $UsuarioVerificacion->getUsuverificacionId(), $UsuarioLog2DAO, $estadoLog);
                    }
                }

                // Guardar nombres y apellidos extraídos de la data de Sumsub
                $firstName = $Response->info->idDocs[0]->firstName ?? '';
                $lastName = $Response->info->idDocs[0]->lastName ?? '';
                $Nombre = explode(" ", $firstName);
                $Apellidos = explode(" ", $lastName);

                $nombre1 = $Nombre[0] ?? '';
                $nombre2 = $Nombre[1] ?? '';
                $apellido1 = $Apellidos[0] ?? '';
                $apellido2 = $Apellidos[1] ?? '';

                // Helper para logs de usuario
                $logFields = [
                    ['tipo' => 'USUNOMBRE1', 'antes' => $Registro->getNombre1(), 'despues' => $nombre1],
                    ['tipo' => 'USUNOMBRE2', 'antes' => $Registro->getNombre2(), 'despues' => $nombre2],
                    ['tipo' => 'USUAPELLIDO1', 'antes' => $Registro->getApellido1(), 'despues' => $apellido1],
                    ['tipo' => 'USUAPELLIDO2', 'antes' => $Registro->getApellido2(), 'despues' => $apellido2],
                ];
                foreach ($logFields as $field) {
                    if ($field['despues']) {
                        $UsuarioLog = new UsuarioLog2();
                        $UsuarioLog->setUsuarioId($Usuario->usuarioId);
                        $UsuarioLog->setUsuarioIp('');
                        $UsuarioLog->setUsuariosolicitaId($Usuario->usuarioId);
                        $UsuarioLog->setUsuariosolicitaIp("");
                        $UsuarioLog->setTipo($field['tipo']);
                        $UsuarioLog->setEstado("A");
                        $UsuarioLog->setValorAntes($field['antes']);
                        $UsuarioLog->setValorDespues($field['despues']);
                        $UsuarioLog->setUsucreaId(0);
                        $UsuarioLog->setUsumodifId(0);
                        $UsuarioLog->setSversion($UsuarioVerificacion->getUsuverificacionId());

                        $UsuarioLog2DAO->insert($UsuarioLog);
                    }
                }

                // Fecha nacimiento
                $dob = $Response->info->idDocs[0]->dob ?? null;
                if ($dob) {
                    $UsuarioLog = new UsuarioLog2();
                    $UsuarioLog->setUsuarioId($Usuario->usuarioId);
                    $UsuarioLog->setUsuarioIp('');
                    $UsuarioLog->setUsuariosolicitaId($Usuario->usuarioId);
                    $UsuarioLog->setUsuariosolicitaIp("");
                    $UsuarioLog->setTipo("USUFECHANACIM");
                    $UsuarioLog->setEstado("A");
                    $UsuarioLog->setValorAntes($UsuarioOtraInfo->getFechaNacim());
                    $UsuarioLog->setValorDespues($dob);
                    $UsuarioLog->setUsucreaId(0);
                    $UsuarioLog->setUsumodifId(0);
                    $UsuarioLog->setSversion($UsuarioVerificacion->getUsuverificacionId());

                    $UsuarioLog2DAO->insert($UsuarioLog);
                }

                // Id único Sumsub
                if (isset($Response->id)) {
                    $UsuarioLog = new UsuarioLog2();
                    $UsuarioLog->setUsuarioId($Usuario->usuarioId);
                    $UsuarioLog->setUsuarioIp('');
                    $UsuarioLog->setUsuariosolicitaId($Usuario->usuarioId);
                    $UsuarioLog->setUsuariosolicitaIp("");
                    $UsuarioLog->setTipo("ACCOUNTIDSUMSUB");
                    $UsuarioLog->setEstado("A");
                    $UsuarioLog->setValorAntes($Usuario->getAccountIdJumio());
                    $UsuarioLog->setValorDespues($Response->id);
                    $UsuarioLog->setUsucreaId(0);
                    $UsuarioLog->setUsumodifId(0);
                    $UsuarioLog->setSversion($UsuarioVerificacion->getUsuverificacionId());

                    $UsuarioLog2DAO->insert($UsuarioLog);
                }

                // Documento
                if (isset($DocumentSumsub) && is_array($DocumentSumsub)) {
                    $DocumentSumsub = implode($DocumentSumsub);
                } else {
                    $DocumentSumsub = $Response->info->idDocs[0]->number ?? '';
                }

                // Nacionalidad
                $nationalityId = null;
                $nationalityMap = [
                    "GTM" => 94,
                    "CRI" => 60,
                    "CHL" => 46,
                    "ECU" => 66,
                    "SLV" => 68,
                    "PER" => 173,
                    "PAN" => 170,
                    "HND" => 102
                ];

                try {
                    $nationality = $Response->info->idDocs[0]->nationality ?? null;
                    if ($nationality && isset($nationalityMap[$nationality])) {
                        $nationalityId = $nationalityMap[$nationality];
                    }
                } catch (Exception $e) {
                }

                // Género
                $gender = null;
                try {
                    $gender = $Response->info->idDocs[0]->gender ?? null;
                } catch (Exception $e) {
                }

                // Actualizar datos de registro y usuario
                $Registro = new Registro();
                $Registro->setNombre(trim("$nombre1 $nombre2 $apellido1 $apellido2"));
                $Registro->setNombre1($nombre1);
                $Registro->setNombre2($nombre2);
                $Registro->setApellido1($apellido1);
                $Registro->setApellido2($apellido2);
                $Registro->setSexo($gender);
                $Registro->setNacionalidadId($nationalityId);
                $Registro->setCedula($DocumentSumsub);

                $RegistroDAO->update($Registro);

                $Usuario->SetNombre(trim("$nombre1 $apellido1"));
                $UsuarioDAO->update($Usuario);

                $UsuarioOtraInfo = new UsuarioOtrainfo();
                $UsuarioOtraInfo->setFechaNacim($dob);
                $UsuarioOtrainfoDAO->update($UsuarioOtraInfo);

                $Transaction->commit();

                // Fecha de expiración del documento
                try {
                    if (isset($Response->info->idDocs[0]->validUntil)) {
                        $ClasificadorFiltro = new Clasificador("", "EXPIRYDATE");
                        $expiryDate = $Response->info->idDocs[0]->validUntil;
                        try {
                            $UsuarioConfiguracion = new UsuarioConfiguracion($Usuario->usuarioId, "A", $ClasificadorFiltro->getClasificadorId());
                        } catch (Exception $e) {
                            if ($e->getCode() == 46) {
                                $UsuarioVerificacionMySqlDAO = new UsuarioConfiguracionMySqlDAO();
                                $Transaction = $UsuarioVerificacionMySqlDAO->getTransaction();

                                $UsuarioConfiguracionDAO = new UsuarioConfiguracionMySqlDAO($Transaction);

                                $UsuarioConfiguracion->tipo = $ClasificadorFiltro->clasificadorId;
                                $UsuarioConfiguracion->valor = $expiryDate;
                                $UsuarioConfiguracion->usuarioId = $Usuario->usuarioId;
                                $UsuarioConfiguracion->estado = 'A';

                                $UsuarioConfiguracionDAO->insert($UsuarioConfiguracion);

                                $Transaction->commit();
                            }
                        }
                    }
                } catch (Exception $e) {
                }

                // Notificaciones (POPUP, SMS, EMAIL, INBOX)
                $notificaciones = [
                    ['clave' => 'ISACTIVEPOPUP', 'abreviado' => 'VERIFICACIONEXITOSA', 'tipo' => 'MESSAGEINV'],
                    ['clave' => 'ISACTIVESMS', 'abreviado' => 'APROSMSJUMIO', 'tipo' => 'SMS'],
                    ['clave' => 'ISACTIVEEMAIL', 'abreviado' => 'APROEMAILJUMIO', 'tipo' => 'MENSAJE'],
                    ['clave' => 'ISACTIVEINBOX', 'abreviado' => 'APROINBOXJUMIO', 'tipo' => 'MENSAJE'],
                ];
                foreach ($notificaciones as $notif) {
                    try {
                        $ClasificadorFiltro = new Clasificador("", $notif['clave']);
                        $MandanteDetalle = new MandanteDetalle("", $UsuarioMandante->mandante, $ClasificadorFiltro->getClasificadorId(), $Usuario->paisId, $Usuario->estado ?? "A");
                        $Mandante = new Mandante($Usuario->mandante);
                        $isActive = ($MandanteDetalle->valor == 1) ? "A" : "I";
                        if ($isActive == "A") {
                            $this->envioPopups($notif['abreviado'], 'Respuesta de Verificación Exitosa', $UsuarioMandante, $Usuario, $notif['tipo'], $Registro, $Mandante);
                        }
                    } catch (Exception $e) {
                    }
                }

                return json_encode($Response);
            }
            elseif ($Usuario->verificado != "S" && ! $esVerificado && $esRechazoTemporal && ! $esDocumentoIncorrecto) {
                // Procesa el rechazo temporal de la verificación de un usuario, actualizando el estado de la verificación,
                // registrando logs y actualizando la información del usuario.

                // Inicia la transacción principal
                $UsuarioVerificacionDAO = new UsuarioVerificacionMySqlDAO();
                $Transaction = $UsuarioVerificacionDAO->getTransaction();

                // DAOS compartiendo la misma transaccion
                $UsuarioDAO = new UsuarioMySqlDAO($Transaction);
                $VerificacionLogDAO = new VerificacionLogMySqlDAO($Transaction);
                $UsuarioLog2DAO = new UsuarioLog2MySqlDAO($Transaction);

                $UsuarioVerificacion = new UsuarioVerificacion('', $Usuario->usuarioId, 'I', 'USUVERIFICACION');
                $Usuario = new Usuario($UsuarioVerificacion->getUsuarioId());

                $UsuarioVerificacion->setEstado('RT');
                $UsuarioVerificacion->setObservacion('Rechazo Temporal Resubmission ' . $Usuario->usuarioId);
                $UsuarioVerificacionDAO->update($UsuarioVerificacion);

                // Actualiza usuario en la misma transacción
                $Usuario->setAccountIdJumio($accountId);
                $UsuarioDAO->update($Usuario);

                // Log de verificación
                $VerificacionLog = new VerificacionLog();
                $VerificacionLog->setUsuverificacionId($UsuarioVerificacion->getUsuverificacionId());
                $VerificacionLog->setTipo('FINALDECISION');
                $VerificacionLog->setJson(json_encode($Response));
                $VerificacionLogDAO->insert($VerificacionLog);

                $UsuarioVerificaId = $UsuarioVerificacion->getUsuverificacionId();

                // Obtener imágenes de identificación
                foreach (['A' => $idImage1 ?? null, 'P' => $idImage2 ?? null] as $tipo => $idImage) {
                    if ($idImage) {
                        $path = "/resources/inspections/" . $inspectionId . "/resources/" . $idImage;
                        $responseImage = $this->connectionSumsub($path, $Usuario, $credentials, "GET");
                        $imageBase64 = base64_encode($responseImage);

                        $tipoLog = ($tipo === 'A') ? 'USUDNIANTERIOR' : 'USUDNIPOSTERIOR';
                        $estadoLog = 'RT';

                        $this->guardarLogImagen($Usuario->usuarioId, $tipoLog, $imageBase64, $UsuarioVerificacion->getUsuverificacionId(), $UsuarioLog2DAO, $estadoLog);
                    }
                }

                // Guardar nombres y apellidos extraídos de la data de Sumsub
                $firstName = $Response->info->idDocs[0]->firstName ?? '';
                $lastName = $Response->info->idDocs[0]->lastName ?? '';
                $Nombre = explode(" ", $firstName);
                $Apellidos = explode(" ", $lastName);

                $nombre1 = $Nombre[0] ?? '';
                $nombre2 = $Nombre[1] ?? '';
                $apellido1 = $Apellidos[0] ?? '';
                $apellido2 = $Apellidos[1] ?? '';

                // Helper para logs de usuario
                $logFields = [
                    ['tipo' => 'USUNOMBRE1', 'antes' => $Registro->getNombre1(), 'despues' => $nombre1],
                    ['tipo' => 'USUNOMBRE2', 'antes' => $Registro->getNombre2(), 'despues' => $nombre2],
                    ['tipo' => 'USUAPELLIDO1', 'antes' => $Registro->getApellido1(), 'despues' => $apellido1],
                    ['tipo' => 'USUAPELLIDO2', 'antes' => $Registro->getApellido2(), 'despues' => $apellido2],
                ];
                foreach ($logFields as $field) {
                    if ($field['despues']) {
                        $UsuarioLog = new UsuarioLog2();
                        $UsuarioLog->setUsuarioId($Usuario->usuarioId);
                        $UsuarioLog->setUsuarioIp('');
                        $UsuarioLog->setUsuariosolicitaId($Usuario->usuarioId);
                        $UsuarioLog->setUsuariosolicitaIp("");
                        $UsuarioLog->setTipo($field['tipo']);
                        $UsuarioLog->setEstado("RT");
                        $UsuarioLog->setValorAntes($field['antes']);
                        $UsuarioLog->setValorDespues($field['despues']);
                        $UsuarioLog->setUsucreaId(0);
                        $UsuarioLog->setUsumodifId(0);
                        $UsuarioLog->setSversion($UsuarioVerificaId);

                        $UsuarioLog2DAO->insert($UsuarioLog);
                    }
                }

                // Fecha nacimiento
                $dob = $Response->info->idDocs[0]->dob ?? null;
                if ($dob) {
                    $UsuarioLog = new UsuarioLog2();
                    $UsuarioLog->setUsuarioId($Usuario->usuarioId);
                    $UsuarioLog->setUsuarioIp("");
                    $UsuarioLog->setUsuariosolicitaId($Usuario->usuarioId);
                    $UsuarioLog->setUsuariosolicitaIp("");
                    $UsuarioLog->setTipo("USUFECHANACIM");
                    $UsuarioLog->setEstado("RT");
                    $UsuarioLog->setValorAntes($UsuarioOtraInfo->getFechaNacim());
                    $UsuarioLog->setValorDespues($dob);
                    $UsuarioLog->setUsucreaId(0);
                    $UsuarioLog->setUsumodifId(0);
                    $UsuarioLog->setSversion($UsuarioVerificaId);

                    $UsuarioLog2DAO->insert($UsuarioLog);
                }

                // Id único Sumsub
                if (isset($Response->id)) {
                    $UsuarioLog = new UsuarioLog2();
                    $UsuarioLog->setUsuarioId($Usuario->usuarioId);
                    $UsuarioLog->setUsuarioIp('');
                    $UsuarioLog->setUsuariosolicitaId($Usuario->usuarioId);
                    $UsuarioLog->setUsuariosolicitaIp("");
                    $UsuarioLog->setTipo("ACCOUNTIDSUMSUB");
                    $UsuarioLog->setEstado("RT");
                    $UsuarioLog->setValorAntes($Usuario->getAccountIdJumio());
                    $UsuarioLog->setValorDespues($Response->id);
                    $UsuarioLog->setUsucreaId(0);
                    $UsuarioLog->setUsumodifId(0);
                    $UsuarioLog->setSversion($UsuarioVerificacion->getUsuverificacionId());

                    $UsuarioLog2DAO->insert($UsuarioLog);
                }

                // Commit de la transacción principal
                $Transaction->commit();

                // Notificaciones (POPUP, SMS, EMAIL, INBOX)
                $notificaciones = [
                    ['clave' => 'ISACTIVEPOPUP', 'abreviado' => 'PENDPOPUPJUMIO', 'tipo' => 'MESSAGEINV'],
                    ['clave' => 'ISACTIVESMS', 'abreviado' => 'PENDSMSJUMIO', 'tipo' => 'SMS'],
                    ['clave' => 'ISACTIVEEMAIL', 'abreviado' => 'PENDEMAILJUMIO', 'tipo' => 'MENSAJE'],
                    ['clave' => 'ISACTIVEINBOX', 'abreviado' => 'PENDINBOXJUMIO', 'tipo' => 'MENSAJE'],
                ];
                foreach ($notificaciones as $notif) {
                    try {
                        $ClasificadorFiltro = new Clasificador("", $notif['clave']);
                        $MandanteDetalle = new MandanteDetalle("", $UsuarioMandante->mandante, $ClasificadorFiltro->getClasificadorId(), $Usuario->paisId, $Usuario->estado ?? "A");
                        $Mandante = new Mandante($Usuario->mandante);
                        $isActive = ($MandanteDetalle->valor == 1) ? "A" : "I";
                        if ($isActive == "A") {
                            $this->envioPopups($notif['abreviado'], 'Respuesta de Verificación Pendiente', $UsuarioMandante, $Usuario, $notif['tipo'], $Registro, $Mandante);
                        }
                    } catch (Exception $e) {
                    }
                }

                return json_encode($Response);
                // Flujo de validación para usuario rechazo final.
            }
            elseif ($Usuario->verificado == "S" || $esRechazo || $esDocumentoIncorrecto || $esRechazoFinal || $esRechazoMenorEdad) {
                // Procesa el rechazo final de la verificación de un usuario, actualizando el estado de la verificación,
                $Temp = ($Usuario->verificado == "S") ? " Usuario ya esta verificado" : '';

                // Inicia la transacción
                $UsuarioVerificacionDAO = new UsuarioVerificacionMySqlDAO();
                $Transaction = $UsuarioVerificacionDAO->getTransaction();

                // DAOs compartiendo la misma transacción
                $UsuarioConfiguracionDAO = new UsuarioConfiguracionMySqlDAO($Transaction);
                $UsuarioLogDAO = new UsuarioLogMySqlDAO($Transaction);
                $UsuarioDAO = new UsuarioMySqlDAO($Transaction);
                $VerificacionLogDAO = new VerificacionLogMySqlDAO($Transaction);
                $UsuarioLog2DAO = new UsuarioLog2MySqlDAO($Transaction);

                // Crear verificación
                $UsuarioVerificacion = new UsuarioVerificacion('', $Usuario->usuarioId, 'I', 'USUVERIFICACION');
                $UsuarioVerificacion->setEstado('R');

                // Observaciones
                if ($esRechazoFinal) {
                    $UsuarioVerificacion->setObservacion("Rechazo Final por SUMSUB");
                } elseif ($esRechazoId) {
                    $UsuarioVerificacion->setObservacion("Rechazado por ID");
                } elseif ($esRechazoSelfie) {
                    $UsuarioVerificacion->setObservacion("Rechazado por SELFIE");
                } elseif ($esDocumentoIncorrecto) {
                    $UsuarioVerificacion->setObservacion("Rechazado por Documento Incorrecto");
                } elseif ($esRechazoMenorEdad) {
                    $UsuarioVerificacion->setObservacion("Rechazado por ser Menor de edad");
                }

                $UsuarioVerificacionDAO->insert($UsuarioVerificacion);

                // Manejo de rechazos
                if ($NumRechazos > 0) {
                    try {
                        $Clasificador = new Clasificador('', 'RECHAZADOVERIF');
                        $ClasificadorId = $Clasificador->getClasificadorId();
                        $UsuarioConfiguracion = new UsuarioConfiguracion($Usuario->usuarioId, 'A', $ClasificadorId);

                        if ($UsuarioConfiguracion->valor < $NumRechazos) {
                            $UsuarioConfiguracion->valor += 1;
                            $UsuarioConfiguracionDAO->update($UsuarioConfiguracion);
                        } else {
                            // Inactivar usuario
                            $Usuario->setEstado("I");
                            $Usuario->setAccountIdJumio($accountId);

                            $UsuarioLog = new UsuarioLog();
                            $UsuarioLog->setUsuarioId($Usuario->usuarioId);
                            $UsuarioLog->setUsuariosolicitaId(2);
                            $UsuarioLog->setTipo("ESTADOUSUARIO");
                            $UsuarioLog->setEstado("A");
                            $UsuarioLog->setValorAntes($Usuario->estado);
                            $UsuarioLog->setValorDespues("I");
                            $UsuarioLog->setUsucreaId(0);
                            $UsuarioLog->setUsumodifId(0);
                            $UsuarioLogDAO->insert($UsuarioLog);

                            $UsuarioDAO->update($Usuario);

                            // Notificación por popup
                            try {
                                $ClasificadorFiltro = new Clasificador("", "ISACTIVEPOPUP");
                                $MandanteDetalle = new MandanteDetalle("", $UsuarioMandante->mandante, $ClasificadorFiltro->getClasificadorId(), $Usuario->paisId, "A");
                                if ($MandanteDetalle->valor == 1) {
                                    $this->envioPopups("RECPOPUPJUMIOINTENTOS", "Respuesta de Verificación Rechazada por Intentos", $UsuarioMandante, $Usuario, "MESSAGEINV", $Registro, new Mandante($Usuario->mandante));
                                }
                            } catch (Exception $e) {
                            }
                        }
                    } catch (Exception $e) {
                        // Si no existe configuración, la crea
                        $Clasificador = new Clasificador('', 'RECHAZADOVERIF');
                        $ClasificadorId = $Clasificador->getClasificadorId();

                        $UsuarioConfiguracion = new UsuarioConfiguracion();
                        $UsuarioConfiguracion->setUsuarioId($Usuario->usuarioId);
                        $UsuarioConfiguracion->setEstado('A');
                        $UsuarioConfiguracion->setTipo($ClasificadorId);
                        $UsuarioConfiguracion->setValor(1);
                        $UsuarioConfiguracion->setUsucreaId(0);
                        $UsuarioConfiguracion->setUsumodifId(0);
                        $UsuarioConfiguracion->setNota("");
                        $UsuarioConfiguracion->setProductoId(0);

                        $UsuarioConfiguracionDAO->insert($UsuarioConfiguracion);
                    }
                }

                // Log de verificación
                $VerificacionLog = new VerificacionLog();
                $VerificacionLog->setUsuverificacionId($UsuarioVerificacion->getUsuverificacionId());
                $VerificacionLog->setTipo('FINALDECISION');
                $VerificacionLog->setJson(json_encode($Response));
                $VerificacionLogDAO->insert($VerificacionLog);

                foreach (['A' => $idImage1 ?? null, 'P' => $idImage2 ?? null] as $tipo => $idImage) {
                    if ($idImage) {
                        $path = "/resources/inspections/" . $inspectionId . "/resources/" . $idImage;
                        $responseImage = $this->connectionSumsub($path, $Usuario, $credentials, "GET");
                        $imageBase64 = base64_encode($responseImage);

                        $tipoLog = ($tipo === 'A') ? 'USUDNIANTERIOR' : 'USUDNIPOSTERIOR';
                        $estadoLog = 'R';

                        $this->guardarLogImagen($Usuario->usuarioId, $tipoLog, $imageBase64, $UsuarioVerificacion->getUsuverificacionId(), $UsuarioLog2DAO, $estadoLog);
                    }
                }

                // Logs de nombres y fecha de nacimiento
                $firstName = $Response->info->idDocs[0]->firstName ?? '';
                $lastName = $Response->info->idDocs[0]->lastName ?? '';
                $Nombre = explode(" ", $firstName);
                $Apellidos = explode(" ", $lastName);

                $logFields = [
                    ['tipo' => 'USUNOMBRE1', 'antes' => $Registro->getNombre1(), 'despues' => $Nombre[0] ?? ''],
                    ['tipo' => 'USUNOMBRE2', 'antes' => $Registro->getNombre2(), 'despues' => $Nombre[1] ?? ''],
                    ['tipo' => 'USUAPELLIDO1', 'antes' => $Registro->getApellido1(), 'despues' => $Apellidos[0] ?? ''],
                    ['tipo' => 'USUAPELLIDO2', 'antes' => $Registro->getApellido2(), 'despues' => $Apellidos[1] ?? ''],
                ];

                foreach ($logFields as $field) {
                    if ($field['despues']) {
                        $UsuarioLog = new UsuarioLog2();
                        $UsuarioLog->setUsuarioId($Usuario->usuarioId);
                        $UsuarioLog->setUsuariosolicitaId($Usuario->usuarioId);
                        $UsuarioLog->setTipo($field['tipo']);
                        $UsuarioLog->setEstado("R");
                        $UsuarioLog->setValorAntes($field['antes']);
                        $UsuarioLog->setValorDespues($field['despues']);
                        $UsuarioLog->setUsucreaId(0);
                        $UsuarioLog->setUsumodifId(0);
                        $UsuarioLog->setSversion($UsuarioVerificacion->getUsuverificacionId());
                        $UsuarioLog2DAO->insert($UsuarioLog);
                    }
                }

                if ($Response->info->idDocs[0]->dob ?? false) {
                    $UsuarioLog = new UsuarioLog2();
                    $UsuarioLog->setUsuarioId($Usuario->usuarioId);
                    $UsuarioLog->setUsuariosolicitaId($Usuario->usuarioId);
                    $UsuarioLog->setTipo("USUFECHANACIM");
                    $UsuarioLog->setEstado("R");
                    $UsuarioLog->setValorAntes($UsuarioOtraInfo->getFechaNacim());
                    $UsuarioLog->setValorDespues($Response->info->idDocs[0]->dob);
                    $UsuarioLog->setUsucreaId(0);
                    $UsuarioLog->setUsumodifId(0);
                    $UsuarioLog->setSversion($UsuarioVerificacion->getUsuverificacionId());
                    $UsuarioLog2DAO->insert($UsuarioLog);
                }

                if ($Response->id ?? false) {
                    $UsuarioLog = new UsuarioLog2();
                    $UsuarioLog->setUsuarioId($Usuario->usuarioId);
                    $UsuarioLog->setUsuariosolicitaId($Usuario->usuarioId);
                    $UsuarioLog->setTipo("ACCOUNTIDSUMSUB");
                    $UsuarioLog->setEstado("R");
                    $UsuarioLog->setValorAntes($Usuario->getAccountIdJumio());
                    $UsuarioLog->setValorDespues($Response->id);
                    $UsuarioLog->setUsucreaId(0);
                    $UsuarioLog->setUsumodifId(0);
                    $UsuarioLog->setSversion($UsuarioVerificacion->getUsuverificacionId());
                    $UsuarioLog2DAO->insert($UsuarioLog);
                }

                // Confirmar la transacción
                $Transaction->commit();

                // Notificaciones
                $notificaciones = [
                    ['clave' => 'ISACTIVESMS', 'abreviado' => 'RECSMSJUMIO', 'tipo' => 'SMS'],
                    ['clave' => 'ISACTIVEEMAIL', 'abreviado' => 'RECEMAILJUMIO', 'tipo' => 'MENSAJE'],
                    ['clave' => 'ISACTIVEINBOX', 'abreviado' => 'RECINBOXJUMIO', 'tipo' => 'MENSAJE'],
                ];

                foreach ($notificaciones as $notif) {
                    try {
                        $ClasificadorFiltro = new Clasificador("", $notif['clave']);
                        $MandanteDetalle = new MandanteDetalle("", $UsuarioMandante->mandante, $ClasificadorFiltro->getClasificadorId(), $Usuario->paisId, $Usuario->estado);
                        if ($MandanteDetalle->valor == 1) {
                            $this->envioPopups($notif['abreviado'], 'Respuesta de Verificación Rechazada por Intentos', $UsuarioMandante, $Usuario, $notif['tipo'], $Registro, new Mandante($Usuario->mandante));
                        }
                    } catch (Exception $e) {
                    }
                }

                return json_encode($Response);
            }
        } catch (Exception $e) {
        }
    }

    /**
     * Envía notificaciones a través de popups, SMS, email o inbox según el tipo de mensaje y abreviado.
     *
     * @param string          $abreviado       Abreviado del mensaje.
     * @param string          $msg             Mensaje a enviar.
     * @param UsuarioMandante $UsuarioMandante Información del usuario mandante.
     * @param Usuario         $Usuario         Información del usuario.
     * @param string          $tipo            Tipo de mensaje (SMS, EMAIL, etc.).
     * @param Registro        $Registro        Información del registro del usuario.
     * @param Mandante        $Mandante        Información del mandante.
     *
     * @return void
     */
    public function envioPopups($abreviado, $msg, $UsuarioMandante, $Usuario, $tipo, $Registro, $Mandante)
    {
        if ($abreviado == 'APROSMSJUMIO' || $abreviado == 'PENDSMSJUMIO' || $abreviado == 'RECSMSJUMIO' ||
            $abreviado == 'APROEMAILJUMIO' || $abreviado == 'PENDEMAILJUMIO' || $abreviado == 'RECEMAILJUMIO' ||
            $abreviado == 'APROINBOXJUMIO' || $abreviado == 'PENDINBOXJUMIO' || $abreviado == 'RECINBOXJUMIO') {
            $templateHtml = '';

            $clasificador = new Clasificador("", $abreviado);

            $template = new Template("", $UsuarioMandante->mandante, $clasificador->getClasificadorId(), $Usuario->paisId, strtolower($Usuario->idioma));

            $templateHtml .= $template->templateHtml;

            $templateHtml = str_replace("#userid#", $Usuario->usuarioId, $templateHtml);
            $templateHtml = str_replace("#name#", $Usuario->nombre, $templateHtml);
            $templateHtml = str_replace("#identification#", $Registro->cedula, $templateHtml);
            $templateHtml = str_replace("#lastname#", $Registro->apellido1 . ' ' . $Registro->apellido2, $templateHtml);
            $templateHtml = str_replace("#login#", $Usuario->login, $templateHtml);
            $templateHtml = str_replace("#mandante#", $Usuario->mandante, $templateHtml);
            $templateHtml = str_replace("#creationdate#", $Usuario->fechaCrea, $templateHtml);
            $templateHtml = str_replace("#email#", $Registro->email, $templateHtml);
            $templateHtml = str_replace("#pais#", $Usuario->paisId, $templateHtml);
            $templateHtml = str_replace("#link#", $Mandante->baseUrl, $templateHtml);
            $templateHtml = str_replace("#telefono#", $Registro->celular, $templateHtml);

            $UsuarioMySqlDAO = new UsuarioMySqlDAO();
            $Transaction = $UsuarioMySqlDAO->getTransaction();
            $UsuarioMandante = new UsuarioMandante("", $Usuario->usuarioId, $Usuario->mandante);
            $UsuarioMensaje = new UsuarioMensaje();
            $UsuarioMensaje->usufromId = 0;
            $UsuarioMensaje->usutoId = $UsuarioMandante->getUsumandanteId();
            $UsuarioMensaje->isRead = 0;
            $UsuarioMensaje->body = $templateHtml;
            $UsuarioMensaje->msubject = $msg;
            $UsuarioMensaje->parentId = 0;
            $UsuarioMensaje->proveedorId = $UsuarioMandante->mandante;
            $UsuarioMensaje->tipo = $tipo;
            $UsuarioMensaje->paisId = $UsuarioMandante->paisId;
            $UsuarioMensaje->fechaExpiracion = date("Y-m-d H:i:s", strtotime("+" . '1' . " days"));

            $UsuarioMensajeMySqlDAO = new UsuarioMensajeMySqlDAO($Transaction);
            $UsuarioMensajeMySqlDAO->insert($UsuarioMensaje);
            $Transaction->commit();

            $ConfigurationEnviroment = new ConfigurationEnvironment();

            $msubjetc = $msg;
            $mtitle = "Verificacion de cuenta";

            $envio = $ConfigurationEnviroment->EnviarCorreoVersion3($Usuario->login, "", "", $msubjetc, '', $mtitle, $templateHtml, "", "", '', $Usuario->mandante);
        } else {
            $popup = '';

            $clasificador = new Clasificador("", $abreviado);

            $template = new Template("", $UsuarioMandante->mandante, $clasificador->getClasificadorId(), $Usuario->paisId, strtolower($Usuario->idioma));

            $popup .= $template->templateHtml;
            $popup = str_replace("#userid#", $Usuario->usuarioId, $popup);

            $UsuarioMySqlDAO = new UsuarioMySqlDAO();
            $Transaction = $UsuarioMySqlDAO->getTransaction();
            $UsuarioMandante = new UsuarioMandante("", $Usuario->usuarioId, $Usuario->mandante);
            $UsuarioMensaje = new UsuarioMensaje();
            $UsuarioMensaje->usufromId = 0;
            $UsuarioMensaje->usutoId = $UsuarioMandante->getUsumandanteId();
            $UsuarioMensaje->isRead = 0;
            $UsuarioMensaje->body = $popup;
            $UsuarioMensaje->msubject = $msg;
            $UsuarioMensaje->parentId = 0;
            $UsuarioMensaje->proveedorId = $UsuarioMandante->mandante;
            $UsuarioMensaje->tipo = $tipo;
            $UsuarioMensaje->paisId = $UsuarioMandante->paisId;
            $UsuarioMensaje->fechaExpiracion = date("Y-m-d H:i:s", strtotime("+" . '1' . " days"));

            $UsuarioMensajeMySqlDAO = new UsuarioMensajeMySqlDAO($Transaction);
            $UsuarioMensajeMySqlDAO->insert($UsuarioMensaje);
            $Transaction->commit();

            $ConfigurationEnviroment = new ConfigurationEnvironment();

            $msubjetc = $msg;
            $mtitle = "Verificacion de cuenta";

            $envio = $ConfigurationEnviroment->EnviarCorreoVersion3($Usuario->login, "", "", $msubjetc, '', $mtitle, $popup, "", "", '', $Usuario->mandante);
        }
    }


    /**
     * Recupera la dirección IP del cliente.
     *
     * La función `get_client_ip` en PHP recupera la dirección IP del cliente verificando varias variables del
     * servidor.
     * * servidor
     *
     * @return string La función `get_client_ip()` devuelve la dirección IP del cliente. Si la dirección IP no puede
     * Si la dirección IP no puede ser determinada desde ninguna de las cabeceras HTTP o la dirección remota, devolverá
     * 'UNKNOWN'.
     */
    function get_client_ip()
    {
        $ipaddress = '';
        if (getenv('HTTP_CLIENT_IP')) {
            $ipaddress = getenv('HTTP_CLIENT_IP');
        } elseif (getenv('HTTP_X_FORWARDED_FOR')) {
            $ipaddress = getenv('HTTP_X_FORWARDED_FOR');
        } elseif (getenv('HTTP_X_FORWARDED')) {
            $ipaddress = getenv('HTTP_X_FORWARDED');
        } elseif (getenv('HTTP_FORWARDED_FOR')) {
            $ipaddress = getenv('HTTP_FORWARDED_FOR');
        } elseif (getenv('HTTP_FORWARDED')) {
            $ipaddress = getenv('HTTP_FORWARDED');
        } elseif (getenv('REMOTE_ADDR')) {
            $ipaddress = getenv('REMOTE_ADDR');
        } else {
            $ipaddress = 'UNKNOWN';
        }
        return $ipaddress;
    }

    /**
     * La función `apiConnection` realiza una petición API.
     *
     * La función `apiConnection` realiza una petición API con los parámetros especificados y devuelve la
     * respuesta.
     *
     * @param string $signature   El parámetro `signature` de la función `apiConnection` suele ser una
     *                            firma criptográfica generada a partir de los datos de la solicitud para garantizar la
     *                            autenticidad e integridad de la solicitud que se envía a la API. Se utiliza como
     *                            parte del mecanismo de autenticación cuando se realizan llamadas a la API.
     * @param string $timestamp   El parámetro `timestamp` de la función `apiConnection` se utiliza para proporcionar
     *                            el valor de la marca de tiempo para la solicitud de la API. Este timestamp se utiliza
     *                            típicamente para la autenticación y seguridad para asegurar que la petición no es
     *                            reproducida o manipulada.
     * @param string $body        El parámetro `body` en la función `apiConnection` representa los datos que serán
     *                            serán enviados en el cuerpo de la petición cuando se haga una llamada a la API.
     *                            Normalmente contiene la carga útil o información que necesita ser transmitida al
     *                            punto final de la API. Estos datos suelen estar en formato JSON y puede incluir
     *                            varios campos y valores.
     * @param string $url_api     El parámetro `url_api` en la función `apiConnection` representa el punto final o ruta
     *                            específica de la API. endpoint o ruta de la API a la que desea conectarse. Es la ruta
     *                            que viene después de la URL base proporcionada en el objeto `url_api`.
     * @param object $credentials La función `apiConnection` función PHP para
     *                            hacer peticiones a la API. Toma varios parámetros incluyendo ``, ``, ``,``, ``, y ``.
     * @param string $method      El parámetro `method` en la función `apiConnection` representa el metodo HTTP
     *                            que se utilizará para la solicitud de la API. Puede ser uno de los métodos HTTP
     *                            estándar como GET, POST, PUT, DELETE, etc. Este parámetro determina cómo la solicitud
     *                            interactuará con la URL especificada endpoint.
     *
     * @return string La función `apiConnection` devuelve la respuesta de la petición API realizada utilizando
     * los parámetros y configuraciones proporcionados. La respuesta se obtiene ejecutando la petición cURL
     * con las opciones y cabeceras especificadas. La respuesta se registra con fines de depuración
     * y devuelta por la función.
     */
    public function apiConnection($signature, $timestamp, $body, $url_api, $credentials, $method)
    {
        $curl = new CurlWrapper($credentials->URL . $url_api);

        $curl->setOptionsArray(array(
            CURLOPT_URL => $credentials->URL . $url_api . $body,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => $method,
            CURLOPT_HTTPHEADER => array(
                'X-App-Token: ' . $credentials->SUMSUB_APP_TOKEN,
                'X-App-Access-Sig: ' . $signature,
                'X-App-Access-Ts: ' . $timestamp,
                'Accept: application/json',
                'Content-Type: application/json; charset=utf-8'
            ),
        ));

        $response = $curl->execute();

        syslog(LOG_WARNING, "SUMSUB SERVICE DATA link" . " " . $credentials->URL . $url_api . $body . 'RESPONSE' . " " . json_encode($response));

        return ($response);
    }

    /**
     * La función `connectionSumsub` establece una conexión a una API.
     *
     * La función `connectionSumsub` establece una conexión a una API utilizando los parámetros proporcionados y
     * devuelve la respuesta.
     *
     * @param string $path        El parámetro `path` de la función `connectionSumsub` representa el endpoint o
     *                            ruta URL a la que desea conectarse para la API Sumsub. Es la ruta específica o
     *                            recurso en el servidor al que está intentando acceder o interactuar. Esto podría ser
     *                            algo como `/users`.
     * @param object $Usuario     El parámetro `Usuario` en la función `connectionSumsub` parece representar un
     *                            objeto con las propiedades.
     *                            -[int] usuarioId: ID del usuario.
     *                            -[int] idioma: Indica el idioma preferido del usuario.
     *                            Estas propiedades se utilizan para construir los parámetros para la petición API.
     * @param object $credentials El parámetro `credentials` en la función `connectionSumsub` probablemente contiene
     *                            información sensible como claves API, tokens, u otros detalles de autenticación
     *                            requeridos para establecer una conexión segura con la API de Sumsub.
     * @param string $method      El parámetro `method` de la función `connectionSumsub` hace referencia al metodo HTTP
     *                            que se utiliza para la solicitud de la API. Puede ser `GET`, `POST`, `PUT`, `DELETE`
     *                            o cualquier otro metodo HTTP válido. válido. Este parámetro se utiliza para
     *                            especificar cómo la solicitud de la API debe ser manejada.
     *
     * @return string La función `connectionSumsub` devuelve la respuesta del metodo `apiConnection
     * después de crear una firma y construir los parámetros necesarios para la petición API.
     */
    public function connectionSumsub($path, $Usuario, $credentials, $method)
    {
        $ttlInSecs = 1800;
        $externalUserId = $Usuario->usuarioId;
        $lang = $Usuario->idioma;

        $params = [
            "externalUserId" => $externalUserId,
            "ttlInSecs" => $ttlInSecs,
            "lang" => $lang
        ];

        $body = '?' . http_build_query($params);
        $timestamp = time();

        $signature = $this->createSignature($method, $path, $body, $timestamp, $credentials);

        $response = $this->apiConnection($signature, $timestamp, $body, $path, $credentials, $method);

        return $response;
    }

    /**
     * Genera una firma HMAC-SHA256 para autenticar solicitudes a la API.
     *
     * @param string  $method      El método HTTP utilizado en la solicitud (GET, POST, etc.).
     * @param string  $url         La URL del endpoint de la API.
     * @param string  $body        El cuerpo de la solicitud en formato de cadena.
     * @param integer $timestamp   La marca de tiempo en segundos para la solicitud.
     * @param object  $credentials Objeto que contiene las credenciales necesarias, incluyendo la clave secreta.
     *
     * @return string              La firma generada como una cadena HMAC-SHA256.
     */
    protected function createSignature(string $method, string $url, string $body, int $timestamp, $credentials)
    {
        $data = $timestamp . strtoupper($method) . $url . $body;

        return hash_hmac('sha256', $data, $credentials->SUMSUB_SECRET_KEY);
    }

    /**
     * Guarda una imagen de usuario en un directorio específico y la sube a Google Cloud Storage.
     *
     * @param integer $usuarioId   El ID del usuario al que pertenece la imagen.
     * @param string  $sufijo      Un sufijo para el nombre del archivo de la imagen.
     * @param string  $imageBase64 La imagen en formato Base64 que se va a guardar.
     *
     * @return void
     */
    public function guardarImagenUsuario($usuarioId, $sufijo, $imageBase64)
    {
        $filename = "c{$usuarioId}{$sufijo}.png";
        $dir = '/home/home2/backend/images/c/';
        if ( ! file_exists($dir)) {
            mkdir($dir, 0755, true);
        }
        $dirsave = $dir . $filename;
        file_put_contents($dirsave, $imageBase64);
        shell_exec('export BOTO_CONFIG=/home/backend/.config/gcloud/legacy_credentials/admin@bpoconsultores.com.co/.boto && export PATH=/sbin:/bin:/usr/sbin:/usr/bin:/home/backend/google-cloud-sdk/bin && gsutil -h "Cache-Control:public,max-age=604800" cp ' . $dirsave . ' gs://cedulas-1/c/');
    }

    /**
     * Guarda un log de usuario con una imagen en Base64.
     *
     * @param integer             $usuarioId             El ID del usuario al que pertenece el log.
     * @param string              $tipo                  El tipo de log (por ejemplo, 'USUDNIANTERIOR').
     * @param string              $imageBase64           La imagen en formato Base64 que se va a guardar en el log.
     * @param integer             $usuarioVerificacionId El ID de verificación del usuario.
     * @param UsuarioLog2MySqlDAO $dao                   El objeto DAO para interactuar con la base de datos.
     * @param string              $estado                El estado del log (por ejemplo, 'R' para Rechazado).
     *
     * @return boolean                     Devuelve true si el log se guardó correctamente, false en caso contrario.
     */
    public function guardarLogImagen($usuarioId, $tipo, $imageBase64, $usuarioVerificacionId, UsuarioLog2MySqlDAO $dao, $estado)
    {
        $usuarioLog = new UsuarioLog2();
        $usuarioLog->setUsuarioId($usuarioId);
        $usuarioLog->setUsuarioIp("");
        $usuarioLog->setUsuariosolicitaId($usuarioId);
        $usuarioLog->setUsuariosolicitaIp("");
        $usuarioLog->setUsuarioaprobarId(0);
        $usuarioLog->setTipo($tipo);
        $usuarioLog->setEstado($estado);
        $usuarioLog->setValorAntes('');
        $usuarioLog->setValorDespues('');
        $usuarioLog->setUsucreaId(0);
        $usuarioLog->setUsumodifId(0);
        $usuarioLog->setImagen($imageBase64);
        $usuarioLog->setSversion($usuarioVerificacionId);

        return $dao->insert($usuarioLog);
    }

    /**
     * Obtiene el ID de verificación del usuario desde un objeto o stdClass.
     *
     * La función `obtenerUsuverificacionId` toma un objeto o stdClass y devuelve el ID de verificación del usuario
     * si está presente. Si no se encuentra, devuelve null.
     *
     * @param mixed $UsuarioVerificacion El objeto o stdClass que contiene la información de verificación del usuario.
     *
     * @return integer|null El ID de verificación del usuario o null si no se encuentra.
     */
    public function obtenerUsuverificacionId($UsuarioVerificacion)
    {
        // Caso 1: es un objeto de una clase conocida (como UsuarioVerificacion)
        if (is_object($UsuarioVerificacion) && property_exists($UsuarioVerificacion, 'usuverificacionId')) {
            return $UsuarioVerificacion->usuverificacionId;
        }

        // Caso 2: es un stdClass con claves tipo 'usuario_verificacion.usuverificacion_id'
        if (is_object($UsuarioVerificacion)) {
            foreach ($UsuarioVerificacion as $clave => $valor) {
                if (strpos($clave, 'usuario_verificacion.usuverificacion_id') !== false) {
                    return $valor;
                }
            }
        }
        // No se encontró el ID
        return null;
    }
}

