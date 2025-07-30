<?php

namespace Backend\dto;

use Backend\mysql\PuntoVentaMySqlDAO;
use Exception;
use Backend\mysql\PuntoventadimMySqlDAO;

/**
 * Clase 'PuntoVenta'
 *
 * Esta clase provee una manera de instanciar un objeto de transferencia de datos (dto)
 * para la tabla 'PuntoVenta'
 *
 * Ejemplo de uso:
 * $PuntoVenta = new PuntoVenta();
 *
 *
 * @package ninguno
 * @author Daniel Tamayo <it@virtualsoft.tech>
 * @version ninguna
 * @access public
 * @see no
 *
 */
class PuntoVenta
{

    /**
     * Representación de la columna 'puntoventaId' de la tabla 'PuntoVenta'
     *
     * @var string
     */
    var $puntoventaId;

    /**
     * Representación de la columna 'descripcion' de la tabla 'PuntoVenta'
     *
     * @var string
     */
    var $descripcion;

    /**
     * Representación de la columna 'ciudad' de la tabla 'PuntoVenta'
     *
     * @var string
     */
    var $ciudad;

    /**
     * Representación de la columna 'direccion' de la tabla 'PuntoVenta'
     *
     * @var string
     */
    var $direccion;

    /**
     * Representación de la columna 'telefono' de la tabla 'PuntoVenta'
     *
     * @var string
     */
    var $telefono;

    /**
     * Representación de la columna 'nombreContacto' de la tabla 'PuntoVenta'
     *
     * @var string
     */
    var $nombreContacto;

    /**
     * Representación de la columna 'estado' de la tabla 'PuntoVenta'
     *
     * @var string
     */
    var $estado;

    /**
     * Representación de la columna 'usuarioId' de la tabla 'PuntoVenta'
     *
     * @var string
     */
    var $usuarioId;

    /**
     * Representación de la columna 'ciudadId' de la tabla 'PuntoVenta'
     *
     * @var string
     */
    var $ciudadId;

    /**
     * Representación de la columna 'mandante' de la tabla 'PuntoVenta'
     *
     * @var string
     */
    var $mandante;

    /**
     * Representación de la columna 'email' de la tabla 'PuntoVenta'
     *
     * @var string
     */
    var $email;

    /**
     * Representación de la columna 'valorCupo' de la tabla 'PuntoVenta'
     *
     * @var string
     */
    var $valorCupo;

    /**
     * Representación de la columna 'porcenComision' de la tabla 'PuntoVenta'
     *
     * @var string
     */
    var $porcenComision;

    /**
     * Representación de la columna 'periodicidadId' de la tabla 'PuntoVenta'
     *
     * @var string
     */
    var $periodicidadId;

    /**
     * Representación de la columna 'valorRecarga' de la tabla 'PuntoVenta'
     *
     * @var string
     */
    var $valorRecarga;

    /**
     * Representación de la columna 'valorCupo2' de la tabla 'PuntoVenta'
     *
     * @var string
     */
    var $valorCupo2;

    /**
     * Representación de la columna 'porcenComision2' de la tabla 'PuntoVenta'
     *
     * @var string
     */
    var $porcenComision2;

    /**
     * Representación de la columna 'barrio' de la tabla 'PuntoVenta'
     *
     * @var string
     */
    var $barrio;

    /**
     * Representación de la columna 'clasificador1Id' de la tabla 'PuntoVenta'
     *
     * @var string
     */
    var $clasificador1Id;

    /**
     * Representación de la columna 'clasificador2Id' de la tabla 'PuntoVenta'
     *
     * @var string
     */
    var $clasificador2Id;

    /**
     * Representación de la columna 'clasificador3Id' de la tabla 'PuntoVenta'
     *
     * @var string
     */
    var $clasificador3Id;

    /**
     * Representación de la columna 'clasificador4Id' de la tabla 'PuntoVenta'
     *
     * @var string
     */
    var $clasificador4Id;

    /**
     * Representación de la columna 'creditos' de la tabla 'PuntoVenta'
     *
     * @var string
     */
    var $creditos;

    /**
     * Representación de la columna 'creditosBase' de la tabla 'PuntoVenta'
     *
     * @var string
     */
    var $creditosBase;

    /**
     * Representación de la columna 'creditosAnt' de la tabla 'PuntoVenta'
     *
     * @var string
     */
    var $creditosAnt;

    /**
     * Representación de la columna 'creditosBaseAnt' de la tabla 'PuntoVenta'
     *
     * @var string
     */
    var $creditosBaseAnt;

    /**
     * Representación de la columna 'cupoRecarga' de la tabla 'PuntoVenta'
     *
     * @var string
     */
    var $cupoRecarga;

    /**
     * Representación de la columna 'moneda' de la tabla 'PuntoVenta'
     *
     * @var string
     */
    var $moneda;

    /**
     * Representación de la columna 'idioma' de la tabla 'PuntoVenta'
     *
     * @var string
     */
    var $idioma;

    /**
     * Representación de la columna 'propio' de la tabla 'PuntoVenta'
     *
     * @var string
     */
    var $propio;

    /**
     * Representación de la columna 'cuentacontable_id' de la tabla 'PuntoVenta'
     *
     * @var string
     */
    var $cuentacontableId;


    /**
     * Representación de la columna 'cuentacontablecierre_id' de la tabla 'PuntoVenta'
     *
     * @var string
     */
    var $cuentacontablecierreId;


    /**
     * Representación de la columna 'codigo_personalizado' de la tabla 'PuntoVenta'
     *
     * @var string
     */
    var $codigoPersonalizado;

    /**
     * Representación de la columna 'header_recibopagopremio' de la tabla 'PuntoVenta'
     *
     * @var string
     */
    var $headerRecibopagopremio;

    /**
     * Representación de la columna 'footer_recibopagopremio' de la tabla 'PuntoVenta'
     *
     * @var string
     */
    var $footerRecibopagopremio;

    /**
     * Representación de la columna 'header_recibopagoretiro' de la tabla 'PuntoVenta'
     *
     * @var string
     */
    var $headerRecibopagoretiro;

    /**
     * Representación de la columna 'footer_recibopagoretiro' de la tabla 'PuntoVenta'
     *
     * @var string
     */
    var $footerRecibopagoretiro;

    /**
     * Representación de la columna 'tipo_tienda' de la tabla 'PuntoVenta'
     *
     * @var string
     */
    var $tipoTienda;

    /**
     * Representación de la columna 'cedula' de la tabla 'PuntoVenta'
     *
     * @var string
     */
    var $cedula;

    /**
     * Representación de la columna 'identificacion_ip' de la tabla 'PuntoVenta'
     *
     * @var string
     */
    var $identificacionIp;

    /**
     * Representación de la columna 'facebook' de la tabla 'PuntoVenta'
     *
     * @var string
     */
    var $facebook;

    /**
     * Representación de la columna 'facebook_verificacion' de la tabla 'PuntoVenta'
     *
     * @var string
     */
    var $facebookVerificacion;

    /**
     * Representación de la columna 'instagram' de la tabla 'PuntoVenta'
     *
     * @var string
     */
    var $instagram;

    /**
     * Representación de la columna 'instagram_verificacion' de la tabla 'PuntoVenta'
     *
     * @var string
     */
    var $instagramVerificacion;

    /** 
     * Representación de la columna 'whatsApp' de la tabla 'PuntoVenta'
     * 
     * @var string
     */
    var $whatsApp;

    /** * @var string Representación de la columna 'whatsApp_verificacion' de la tabla 'PuntoVenta' */
    var $whatsAppVerificacion;

    /** * @var string Representación de la columna 'otra_redes_social' de la tabla 'PuntoVenta' */
    var $otraRedesSocial;

    /** * @var string Representación de la columna 'otra_redes_social_name' de la tabla 'PuntoVenta' */
    var $otraRedesSocialName;

    /** * @var string Representación de la columna 'otra_redes_social_verificacion' de la tabla 'PuntoVenta' */
    var $otraRedesSocialVerificacion;

    /** * @var string Representación de la columna 'physical_prize' de la tabla 'PuntoVenta' */
    var $PhysicalPrize;

    /** * @var string Representación de la columna 'impuesto_pagopremio' de la tabla 'PuntoVenta' */
    var $impuestoPagopremio;












    /**
     * Constructor de clase
     *
     *
     * @param String $proveedorId id del proveedor
     * @param String $abreviado abreviado
     *
     * @return no
     * @throws Exception si el punto de venta no existe
     *
     * @access public
     * @see no
     * @since no
     * @deprecated no
     */
    public function __construct($puntoventaId = "", $usuarioId = "")
    {
        $this->puntoventaId = $puntoventaId;
        $this->usuarioId = $usuarioId;

        if ($puntoventaId != "") {

            $PuntoVentaMySqlDAO = new PuntoVentaMySqlDAO();

            $PuntoVenta = $PuntoVentaMySqlDAO->load($puntoventaId);

            if ($PuntoVenta != null && $PuntoVenta != "") {

                $this->puntoventaId = $PuntoVenta->puntoventaId;
                $this->descripcion = $PuntoVenta->descripcion;
                $this->ciudad = $PuntoVenta->ciudad;
                $this->direccion = $PuntoVenta->direccion;
                $this->telefono = $PuntoVenta->telefono;
                $this->nombreContacto = $PuntoVenta->nombreContacto;
                $this->estado = $PuntoVenta->estado;
                $this->usuarioId = $PuntoVenta->usuarioId;
                $this->ciudadId = $PuntoVenta->ciudadId;
                $this->mandante = $PuntoVenta->mandante;
                $this->email = $PuntoVenta->email;
                $this->valorCupo = $PuntoVenta->valorCupo;
                $this->porcenComision = $PuntoVenta->porcenComision;
                $this->porcenComision2 = $PuntoVenta->porcenComision2;
                $this->periodicidadId = $PuntoVenta->periodicidadId;
                $this->valorRecarga = $PuntoVenta->valorRecarga;
                $this->valorCupo2 = $PuntoVenta->valorCupo2;
                $this->barrio = $PuntoVenta->barrio;
                $this->clasificador1Id = $PuntoVenta->clasificador1Id;
                $this->clasificador2Id = $PuntoVenta->clasificador2Id;
                $this->clasificador3Id = $PuntoVenta->clasificador3Id;
                $this->clasificador4Id = $PuntoVenta->clasificador4Id;
                $this->creditos = $PuntoVenta->creditos;
                $this->creditosBase = $PuntoVenta->creditosBase;
                $this->creditosAnt = $PuntoVenta->creditosAnt;
                $this->creditosBaseAnt = $PuntoVenta->creditosBaseAnt;
                $this->cupoRecarga = $PuntoVenta->cupoRecarga;
                $this->moneda = $PuntoVenta->moneda;
                $this->idioma = $PuntoVenta->idioma;
                $this->propio = $PuntoVenta->propio;
                $this->cuentacontableId = $PuntoVenta->cuentacontableId;
                $this->cuentacontablecierreId = $PuntoVenta->cuentacontablecierreId;
                $this->codigoPersonalizado = $PuntoVenta->codigoPersonalizado;
                $this->headerRecibopagopremio = $PuntoVenta->headerRecibopagopremio;
                $this->footerRecibopagopremio = $PuntoVenta->footerRecibopagopremio;
                $this->headerRecibopagoretiro = $PuntoVenta->headerRecibopagoretiro;
                $this->footerRecibopagoretiro = $PuntoVenta->footerRecibopagoretiro;
                $this->tipoTienda = $PuntoVenta->tipoTienda;
                $this->cedula = $PuntoVenta->cedula;
                $this->identificacionIp = $PuntoVenta->identificacionIp;
                $this->facebook = $PuntoVenta->facebook;
                $this->facebookVerificacion = $PuntoVenta->facebookVerificacion;
                $this->instagram = $PuntoVenta->instagram;
                $this->instagramVerificacion = $PuntoVenta->instagramVerificacion;
                $this->whatsApp = $PuntoVenta->whatsApp;
                $this->whatsAppVerificacion = $PuntoVenta->whatsAppVerificacion;
                $this->otraRedesSocial = $PuntoVenta->otraRedesSocial;
                $this->otraRedesSocialName = $PuntoVenta->otraRedesSocialName;
                $this->otraRedesSocialVerificacion = $PuntoVenta->otraRedesSocialVerificacion;
                $this->PhysicalPrize = $PuntoVenta->PhysicalPrize;

                $this->impuestoPagopremio = $PuntoVenta->impuestoPagopremio;
            } else {
                throw new Exception("No existe " . get_class($this), "98");
            }
        } elseif ($usuarioId != "") {

            $PuntoVentaMySqlDAO = new PuntoVentaMySqlDAO();

            $PuntoVenta = $PuntoVentaMySqlDAO->queryByUsuarioId($usuarioId);
            $PuntoVenta = $PuntoVenta[0];

            if ($PuntoVenta != null && $PuntoVenta != "") {

                $this->puntoventaId = $PuntoVenta->puntoventaId;
                $this->descripcion = $PuntoVenta->descripcion;
                $this->ciudad = $PuntoVenta->ciudad;
                $this->direccion = $PuntoVenta->direccion;
                $this->telefono = $PuntoVenta->telefono;
                $this->nombreContacto = $PuntoVenta->nombreContacto;
                $this->estado = $PuntoVenta->estado;
                $this->usuarioId = $PuntoVenta->usuarioId;
                $this->ciudadId = $PuntoVenta->ciudadId;
                $this->mandante = $PuntoVenta->mandante;
                $this->email = $PuntoVenta->email;
                $this->valorCupo = $PuntoVenta->valorCupo;
                $this->porcenComision = $PuntoVenta->porcenComision;
                $this->porcenComision2 = $PuntoVenta->porcenComision2;
                $this->periodicidadId = $PuntoVenta->periodicidadId;
                $this->valorRecarga = $PuntoVenta->valorRecarga;
                $this->valorCupo2 = $PuntoVenta->valorCupo2;
                $this->barrio = $PuntoVenta->barrio;
                $this->clasificador1Id = $PuntoVenta->clasificador1Id;
                $this->clasificador2Id = $PuntoVenta->clasificador2Id;
                $this->clasificador3Id = $PuntoVenta->clasificador3Id;
                $this->clasificador4Id = $PuntoVenta->clasificador4Id;
                $this->creditos = $PuntoVenta->creditos;
                $this->creditosBase = $PuntoVenta->creditosBase;
                $this->creditosAnt = $PuntoVenta->creditosAnt;
                $this->creditosBaseAnt = $PuntoVenta->creditosBaseAnt;
                $this->cupoRecarga = $PuntoVenta->cupoRecarga;
                $this->moneda = $PuntoVenta->moneda;
                $this->idioma = $PuntoVenta->idioma;
                $this->propio = $PuntoVenta->propio;
                $this->cuentacontableId = $PuntoVenta->cuentacontableId;
                $this->cuentacontablecierreId = $PuntoVenta->cuentacontablecierreId;
                $this->codigoPersonalizado = $PuntoVenta->codigoPersonalizado;
                $this->headerRecibopagopremio = $PuntoVenta->headerRecibopagopremio;
                $this->footerRecibopagopremio = $PuntoVenta->footerRecibopagopremio;
                $this->headerRecibopagoretiro = $PuntoVenta->headerRecibopagoretiro;
                $this->footerRecibopagoretiro = $PuntoVenta->footerRecibopagoretiro;
                $this->tipoTienda = $PuntoVenta->tipoTienda;
                $this->cedula = $PuntoVenta->cedula;
                $this->identificacionIp = $PuntoVenta->identificacionIp;
                $this->facebook = $PuntoVenta->facebook;
                $this->facebookVerificacion = $PuntoVenta->facebookVerificacion;
                $this->instagram = $PuntoVenta->instagram;
                $this->instagramVerificacion = $PuntoVenta->instagramVerificacion;
                $this->whatsApp = $PuntoVenta->whatsApp;
                $this->whatsAppVerificacion = $PuntoVenta->whatsAppVerificacion;
                $this->otraRedesSocial = $PuntoVenta->otraRedesSocial;
                $this->otraRedesSocialName = $PuntoVenta->otraRedesSocialName;
                $this->otraRedesSocialVerificacion = $PuntoVenta->otraRedesSocialVerificacion;
                $this->PhysicalPrize = $PuntoVenta->PhysicalPrize;

                $this->impuestoPagopremio = $PuntoVenta->impuestoPagopremio;
            } else {
                throw new Exception("No existe " . get_class($this), "98");
            }
        }
    }


    /**
     * Obtener el campo puntoventaId de un objeto
     *
     * @return String puntoventaId puntoventaId
     *
     */
    public function getPuntoventaId()
    {
        return $this->puntoventaId;
    }

    /**
     * Obtener el campo descripcion de un objeto
     *
     * @return String descripcion descripcion
     *
     */
    public function getDescripcion()
    {
        return $this->descripcion;
    }

    /**
     * Define el campo descripción del objeto PuntoVenta
     * @param mixed $telefono
     */
    public function setDescripcion($descripcion)
    {
        $this->descripcion = $descripcion;
    }

    /**
     * Obtener el campo ciudad de un objeto
     *
     * @return String ciudad ciudad
     *
     */
    public function getCiudad()
    {
        return $this->ciudad;
    }

    /**
     * Obtener el campo direccion de un objeto
     *
     * @return String direccion direccion
     *
     */
    public function getDireccion()
    {
        return $this->direccion;
    }

    /**
     * Define el valor direccion del objeto PuntoVenta
     * @param mixed $telefono
     */
    public function setDireccion($direccion)
    {
        $this->direccion = $direccion;
    }

    /**
     * Obtener el campo telefono de un objeto
     *
     * @return String telefono telefono
     *
     */
    public function getTelefono()
    {
        return $this->telefono;
    }


    /**
     * Define el valor telefono del objeto PuntoVenta
     * @param mixed $telefono
     */
    public function setTelefono($telefono)
    {
        $this->telefono = $telefono;
    }

    /**s
     * Obtener el campo nombreContacto de un objeto
     *
     * @return String nombreContacto nombreContacto
     *
     */
    public function getNombreContacto()
    {
        return $this->nombreContacto;
    }

    /**
     * Obtener el campo estado de un objeto
     *
     * @return String estado estado
     *
     */
    public function getEstado()
    {
        return $this->estado;
    }

    /**
     * Obtener el campo usuarioId de un objeto
     *
     * @return String usuarioId usuarioId
     *
     */
    public function getUsuarioId()
    {
        return $this->usuarioId;
    }

    /**
     * Obtener el campo ciudadId de un objeto
     *
     * @return String ciudadId ciudadId
     *
     */
    public function getCiudadId()
    {
        return $this->ciudadId;
    }

    /**
     * Obtener el campo mandante de un objeto
     *
     * @return String mandante mandante
     *
     */
    public function getMandante()
    {
        return $this->mandante;
    }

    /**
     * Obtener el campo email de un objeto
     *
     * @return String email email
     *
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Obtener el campo valorCupo de un objeto
     *
     * @return String valorCupo valorCupo
     *
     */
    public function getValorCupo()
    {
        return $this->valorCupo;
    }

    /**
     * Obtener el campo porcenComision de un objeto
     *
     * @return String porcenComision porcenComision
     *
     */
    public function getPorcenComision()
    {
        return $this->porcenComision;
    }

    /**
     * Obtener el campo periodicidadId de un objeto
     *
     * @return String periodicidadId periodicidadId
     *
     */
    public function getPeriodicidadId()
    {
        return $this->periodicidadId;
    }

    /**
     * Obtener el campo valorRecarga de un objeto
     *
     * @return String valorRecarga valorRecarga
     *
     */
    public function getValorRecarga()
    {
        return $this->valorRecarga;
    }

    /**
     * Obtener el campo valorCupo2 de un objeto
     *
     * @return String valorCupo2 valorCupo2
     *
     */
    public function getValorCupo2()
    {
        return $this->valorCupo2;
    }

    /**
     * Obtener el campo porcenComision2 de un objeto
     *
     * @return String porcenComision2 porcenComision2
     *
     */
    public function getPorcenComision2()
    {
        return $this->porcenComision2;
    }

    /**
     * Obtener el campo barrio de un objeto
     *
     * @return String barrio barrio
     *
     */
    public function getBarrio()
    {
        return $this->barrio;
    }

    /**
     * Obtener el campo clasificador1Id de un objeto
     *
     * @return String clasificador1Id clasificador1Id
     *
     */
    public function getClasificador1Id()
    {
        return $this->clasificador1Id;
    }

    /**
     * Obtener el campo clasificador2Id de un objeto
     *
     * @return String clasificador2Id clasificador2Id
     *
     */
    public function getClasificador2Id()
    {
        return $this->clasificador2Id;
    }

    /**
     * Obtener el campo clasificador3Id de un objeto
     *
     * @return String clasificador3Id clasificador3Id
     *
     */
    public function getClasificador3Id()
    {
        return $this->clasificador3Id;
    }
    /**
     * Obtener el campo clasificador4Id de un objeto
     *
     * @return String clasificador4Id clasificador4Id
     *
     */
    public function getClasificador4Id()
    {
        return $this->clasificador4Id;
    }

    /**
     * Obtener el campo creditos de un objeto
     *
     * @return String creditos creditos
     *
     */
    public function getCreditos()
    {
        return $this->creditos;
    }

    /**
     * Obtener el campo creditosBase de un objeto
     *
     * @return String creditosBase creditosBase
     *
     */
    public function getCreditosBase()
    {
        return $this->creditosBase;
    }

    /**
     * Obtener el campo creditosAnt de un objeto
     *
     * @return String creditosAnt creditosAnt
     *
     */
    public function getCreditosAnt()
    {
        return $this->creditosAnt;
    }

    /**
     * Obtener el campo creditosBaseAnt de un objeto
     *
     * @return String creditosBaseAnt creditosBaseAnt
     *
     */
    public function getCreditosBaseAnt()
    {
        return $this->creditosBaseAnt;
    }

    /**
     * Obtener el campo cupoRecarga de un objeto
     *
     * @return String cupoRecarga cupoRecarga
     *
     */
    public function getCupoRecarga()
    {
        return $this->cupoRecarga;
    }

    /**
     * Obtener el campo moneda del objeto PuntoVenta
     * @return string
     */
    public function getMoneda()
    {
        return $this->moneda;
    }

    /**
     * Definir el valor moneda del objeto PuntoVenta
     * @param string $moneda
     */
    public function setMoneda($moneda)
    {
        $this->moneda = $moneda;
    }

    /**
     * @return string
     */
    public function getIdioma()
    {
        return $this->idioma;
    }

    /**
     * @param string $idioma
     */
    public function setIdioma($idioma)
    {
        $this->idioma = $idioma;
    }

    /**
     * @return string
     */
    public function getPropio()
    {
        return $this->propio;
    }

    /**
     * @param string $propio
     */
    public function setPropio($propio)
    {
        $this->propio = $propio;
    }

    /**
     * @return string
     */
    public function getCuentacontableId()
    {
        return $this->cuentacontableId;
    }

    /**
     * @param string $cuentacontableId
     */
    public function setCuentacontableId($cuentacontableId)
    {
        $this->cuentacontableId = $cuentacontableId;
    }

    /**
     * @return string
     */
    public function getCuentacontablecierreId()
    {
        return $this->cuentacontablecierreId;
    }

    /**
     * @param string $cuentacontablecierreId
     */
    public function setCuentacontablecierreId($cuentacontablecierreId)
    {
        $this->cuentacontablecierreId = $cuentacontablecierreId;
    }

    /**
     * @return string
     */
    public function getCodigoPersonalizado()
    {
        return $this->codigoPersonalizado;
    }

    /**
     * @param string $codigoPersonalizado
     */
    public function setCodigoPersonalizado($codigoPersonalizado)
    {
        $this->codigoPersonalizado = $codigoPersonalizado;
    }

    /**
     * @return mixed
     */
    public function getHeaderRecibopagopremio()
    {
        return $this->headerRecibopagopremio;
    }

    /**
     * @param mixed $headerRecibopagopremio
     */
    public function setHeaderRecibopagopremio($headerRecibopagopremio)
    {
        $this->headerRecibopagopremio = $headerRecibopagopremio;
    }

    /**
     * @return mixed
     */
    public function getFooterRecibopagopremio()
    {
        return $this->footerRecibopagopremio;
    }

    /**
     * @param mixed $footerRecibopagopremio
     */
    public function setFooterRecibopagopremio($footerRecibopagopremio)
    {
        $this->footerRecibopagopremio = $footerRecibopagopremio;
    }

    /**
     * @return mixed
     */
    public function getHeaderRecibopagoretiro()
    {
        return $this->headerRecibopagoretiro;
    }

    /**
     * @param mixed $headerRecibopagoretiro
     */
    public function setHeaderRecibopagoretiro($headerRecibopagoretiro)
    {
        $this->headerRecibopagoretiro = $headerRecibopagoretiro;
    }

    /**
     * @return mixed
     */
    public function getFooterRecibopagoretiro()
    {
        return $this->footerRecibopagoretiro;
    }

    /**
     * @param mixed $footerRecibopagoretiro
     */
    public function setFooterRecibopagoretiro($footerRecibopagoretiro)
    {
        $this->footerRecibopagoretiro = $footerRecibopagoretiro;
    }

    /**
     * @return mixed
     */
    public function getTipoTienda()
    {
        return $this->tipoTienda;
    }

    /**
     * @param mixed $tipoTienda
     */
    public function setTipoTienda($tipoTienda)
    {
        $this->tipoTienda = $tipoTienda;
    }

    /**
     * @return mixed
     */
    public function getCedula()
    {
        return $this->cedula;
    }

    /**
     * @param mixed $cedula
     */
    public function setCedula($cedula)
    {
        $this->cedula = $cedula;
    }

    /**
     * @return mixed
     */
    public function getIdentificacionIp()
    {
        return $this->identificacionIp;
    }

    /**
     * @param mixed $identificacionIp
     */
    public function setIdentificacionIp($identificacionIp)
    {
        $this->identificacionIp = $identificacionIp;
    }

    /**
     * @return mixed
     */
    public function getFacebook()
    {
        return $this->facebook;
    }

    /**
     * @param mixed $facebook
     */
    public function setFacebook($facebook)
    {
        $this->facebook = $facebook;
    }

    /**
     * @return mixed
     */
    public function getFacebookVerificacion()
    {
        return $this->facebookVerificacion;
    }

    /**
     * @param mixed $facebookVerificacion
     */
    public function setFacebookVerificacion($facebookVerificacion)
    {
        $this->facebookVerificacion = $facebookVerificacion;
    }

    /**
     * @return mixed
     */
    public function getInstagram()
    {
        return $this->instagram;
    }

    /**
     * @param mixed $instagram
     */
    public function setInstagram($instagram)
    {
        $this->instagram = $instagram;
    }

    /**
     * @return mixed
     */
    public function getInstagramVerificacion()
    {
        return $this->instagramVerificacion;
    }

    /**
     * @param mixed $instagramVerificacion
     */
    public function setInstagramVerificacion($instagramVerificacion)
    {
        $this->instagramVerificacion = $instagramVerificacion;
    }

    /**
     * @return mixed
     */
    public function getWhatsApp()
    {
        return $this->whatsApp;
    }

    /**
     * @param mixed $whatsApp
     */
    public function setWhatsApp($whatsApp)
    {
        $this->whatsApp = $whatsApp;
    }

    /**
     * @return mixed
     */
    public function getWhatsAppVerificacion()
    {
        return $this->whatsAppVerificacion;
    }

    /**
     * @param mixed $whatsAppVerificacion
     */
    public function setWhatsAppVerificacion($whatsAppVerificacion)
    {
        $this->whatsAppVerificacion = $whatsAppVerificacion;
    }

    /**
     * @return mixed
     */
    public function getOtraRedesSocial()
    {
        return $this->otraRedesSocial;
    }

    /**
     * @param mixed $otraRedesSocial
     */
    public function setOtraRedesSocial($otraRedesSocial)
    {
        $this->otraRedesSocial = $otraRedesSocial;
    }



    /**
     * @return mixed
     */
    public function getOtraRedesSocialName()
    {
        return $this->otraRedesSocialName;
    }

    /**
     * @param mixed $otraRedesSocialName
     */
    public function setOtraRedesSocialName($otraRedesSocialName)
    {
        $this->otraRedesSocialName = $otraRedesSocialName;
    }

    /**
     * @return mixed
     */
    public function getOtraRedesSocialVerificacion()
    {
        return $this->otraRedesSocialVerificacion;
    }

    /**
     * @param mixed $otraRedesSocialVerificacion
     */
    public function setOtraRedesSocialVerificacion($otraRedesSocialVerificacion)
    {
        $this->otraRedesSocialVerificacion = $otraRedesSocialVerificacion;
    }

    /**
     * @return mixed
     */
    public function getphysicalPrize()
    {
        return $this->PhysicalPrize;
    }

    /**
     * @param mixed $physicalPrize
     */
    public function setPhysicalPrize($PhysicalPrize)
    {
        $this->PhysicalPrize = $PhysicalPrize;
    }







    /**
     * Realizar una consulta en la tabla de usuarios 'Usuario'
     * de una manera personalizada
     *
     *
     * @param String $select campos de consulta
     * @param String $sidx columna para ordenar
     * @param String $sord orden los datos asc | desc
     * @param String $start inicio de la consulta
     * @param String $limit limite de la consulta
     * @param String $filters condiciones de la consulta
     * @param boolean $searchOn utilizar los filtros o no
     *
     * @return Array resultado de la consulta
     * @throws Exception si los usuarios no existen
     *
     * @access public
     * @see no
     * @since no
     * @deprecated no
     */
    public function getUsuariosTree($sidx, $sord, $start, $limit, $filters, $searchOn)
    {

        $PuntoVentaMySqlDAO = new PuntoVentaMySqlDAO();

        $usuarios = $PuntoVentaMySqlDAO->queryUsuariosTree($sidx, $sord, $start, $limit, $filters, $searchOn);

        if ($usuarios != null && $usuarios != "") {
            return $usuarios;
        } else {
            throw new Exception("No existe " . get_class($this), "98");
        }
    }

    /**
     * Realizar una consulta en la tabla de puntos de venta 'PuntoVenta'
     * de una manera personalizada
     *
     *
     * @param String $select campos de consulta
     * @param String $sidx columna para ordenar
     * @param String $sord orden los datos asc | desc
     * @param String $start inicio de la consulta
     * @param String $limit limite de la consulta
     * @param String $filters condiciones de la consulta
     * @param boolean $searchOn utilizar los filtros o no
     *
     * @return Array resultado de la consulta
     * @throws Exception si los usuarios no existen
     *
     * @access public
     * @see no
     * @since no
     * @deprecated no
     */
    public function getPuntoVentasCustom($select, $sidx, $sord, $start, $limit, $filters, $searchOn)
    {

        $PuntoVentaMySqlDAO = new PuntoVentaMySqlDAO();

        $usuarios = $PuntoVentaMySqlDAO->queryPuntoVentasCustom($select, $sidx, $sord, $start, $limit, $filters, $searchOn);

        if ($usuarios != null && $usuarios != "") {
            return $usuarios;
        } else {
            throw new Exception("No existe " . get_class($this), "98");
        }
    }
    public function getPuntoVentasCustom3($select, $sidx, $sord, $start, $limit, $filters, $searchOn)
    {

        $PuntoVentaMySqlDAO = new PuntoVentaMySqlDAO();

        $usuarios = $PuntoVentaMySqlDAO->queryPuntoVentasCustom3($select, $sidx, $sord, $start, $limit, $filters, $searchOn);

        if ($usuarios != null && $usuarios != "") {
            return $usuarios;
        } else {
            throw new Exception("No existe " . get_class($this), "98");
        }
    }


    public function getPuntoVentasCustom2($select, $sidx, $sord, $start, $limit, $filters, $searchOn)
    {

        $PuntoVentaMySqlDAO = new PuntoVentaMySqlDAO();

        $usuarios = $PuntoVentaMySqlDAO->queryPuntoVentasCustom2($select, $sidx, $sord, $start, $limit, $filters, $searchOn);

        if ($usuarios != null && $usuarios != '') return $usuarios;

        throw new Exception('No existe ' . get_class($this), 98);
    }

    /**
     * Realizar una consulta en la tabla de flujos de caja 'FlujoCaja'
     * de una manera personalizada
     *
     *
     * @param String $select campos de consulta
     * @param String $sidx columna para ordenar
     * @param String $sord orden los datos asc | desc
     * @param String $start inicio de la consulta
     * @param String $limit limite de la consulta
     * @param String $filters condiciones de la consulta
     * @param boolean $searchOn utilizar los filtros o no
     * @param String $grouping columna para agrupar
     * @param String $FromDateLocal fecha inicial de la consulta
     * @param String $ToDateLocal fecha final de la consulta
     * @param String $ConcesionarioId ConcesionarioId
     * @param String $ConcesionarioId2 ConcesionarioId2
     * @param String $ConcesionarioId3 ConcesionarioId3
     * @param String $PaisId id del país
     * @param String $Mandante Mandante
     * @param String $PuntoVentaId id del punto de venta
     *
     * @return Array resultado de la consulta
     * @throws Exception si los usuarios no existen
     *
     * @access public
     * @see no
     * @since no
     * @deprecated no
     */
    public function getFlujoCaja($select, $sidx, $sord, $start, $limit, $filters, $searchOn, $grouping, $FromDateLocal, $ToDateLocal, $ConcesionarioId = "", $ConcesionarioId2 = "", $ConcesionarioId3 = "", $PaisId = "", $Mandante = "", $PuntoVentaId = "", $mandante = 0, $UsuarioId = "")
    {

        $PuntoVentaMySqlDAO = new PuntoVentaMySqlDAO();

        $usuarios = $PuntoVentaMySqlDAO->queryFlujoCaja($select, $sidx, $sord, $start, $limit, $filters, $searchOn, $grouping, $FromDateLocal, $ToDateLocal, $ConcesionarioId, $ConcesionarioId2, $ConcesionarioId3, $PaisId, $Mandante, $PuntoVentaId, $mandante, $UsuarioId);

        if ($usuarios != null && $usuarios != "") {
            return $usuarios;
        } else {
            throw new Exception("No existe " . get_class($this), "98");
        }
    }


    /**
     * Realizar una consulta en la tabla de flujos de caja 'FlujoCaja'
     * de una manera personalizada
     *
     *
     * @param String $select campos de consulta
     * @param String $sidx columna para ordenar
     * @param String $sord orden los datos asc | desc
     * @param String $start inicio de la consulta
     * @param String $limit limite de la consulta
     * @param String $filters condiciones de la consulta
     * @param boolean $searchOn utilizar los filtros o no
     * @param String $grouping columna para agrupar
     * @param String $FromDateLocal fecha inicial de la consulta
     * @param String $ToDateLocal fecha final de la consulta
     * @param String $PuntoVentaId id del punto de venta
     * @param String $CajeroId id del cajero
     *
     * @return Array resultado de la consulta
     * @throws Exception si los usuarios no existen
     *
     * @access public
     * @see no
     * @since no
     * @deprecated no
     */
    public function getFlujoCajaConCajero($select, $sidx, $sord, $start, $limit, $filters, $searchOn, $grouping, $FromDateLocal, $ToDateLocal, $PuntoVentaId = "", $CajeroId = "", $mandante = 0)
    {

        $PuntoVentaMySqlDAO = new PuntoVentaMySqlDAO();

        $usuarios = $PuntoVentaMySqlDAO->queryFlujoCajaConCajero($select, $sidx, $sord, $start, $limit, $filters, $searchOn, $grouping, $FromDateLocal, $ToDateLocal, $PuntoVentaId, $CajeroId, $mandante);

        if ($usuarios != null && $usuarios != "") {
            return $usuarios;
        } else {
            throw new Exception("No existe " . get_class($this), "98");
        }
    }

    /**
     * Obtiene el flujo de caja con cajero.
     *
     * @param string $select Campos a seleccionar en la consulta.
     * @param string $sidx Índice de ordenación.
     * @param string $sord Orden de clasificación (ascendente o descendente).
     * @param int $start Inicio de los registros a obtener.
     * @param int $limit Límite de registros a obtener.
     * @param string $filters Filtros aplicados a la consulta.
     * @param bool $searchOn Indica si la búsqueda está activada.
     * @param string $grouping Agrupación de los resultados.
     * @param string $FromDateLocal Fecha de inicio del rango de búsqueda.
     * @param string $ToDateLocal Fecha de fin del rango de búsqueda.
     * @param string $PuntoVentaId (Opcional) ID del punto de venta.
     * @param string $CajeroId (Opcional) ID del cajero.
     * @param int $mandante (Opcional) Mandante de la consulta.
     * 
     * @return array Resultados de la consulta.
     * @throws Exception Si no existen resultados.
     */
    public function getFlujoCajaConCajero2($select, $sidx, $sord, $start, $limit, $filters, $searchOn, $grouping, $FromDateLocal, $ToDateLocal, $PuntoVentaId = "", $CajeroId = "", $mandante = 0)
    {

        $PuntoVentaMySqlDAO = new PuntoVentaMySqlDAO();

        $usuarios = $PuntoVentaMySqlDAO->queryFlujoCajaConCajero2($select, $sidx, $sord, $start, $limit, $filters, $searchOn, $grouping, $FromDateLocal, $ToDateLocal, $PuntoVentaId, $CajeroId, $mandante);

        if ($usuarios != null && $usuarios != "") {
            return $usuarios;
        } else {
            throw new Exception("No existe " . get_class($this), "98");
        }
    }


    /**
     * Realizar una consulta en la tabla de flujos de caja 'FlujoCaja'
     * de una manera personalizada
     *
     *
     * @param String $select campos de consulta
     * @param String $sidx columna para ordenar
     * @param String $sord orden los datos asc | desc
     * @param String $start inicio de la consulta
     * @param String $limit limite de la consulta
     * @param String $filters condiciones de la consulta
     * @param boolean $searchOn utilizar los filtros o no
     * @param String $grouping columna para agrupar
     * @param String $FromDateLocal fecha inicial de la consulta
     * @param String $ToDateLocal fecha final de la consulta
     * @param String $ConcesionarioId ConcesionarioId
     * @param String $ConcesionarioId2 ConcesionarioId2
     * @param String $ConcesionarioId3 ConcesionarioId3
     * @param String $PaisId id del país
     * @param String $Mandante Mandante
     * @param String $PuntoVentaId id del punto de venta
     *
     * @return Array resultado de la consulta
     * @throws Exception si los usuarios no existen
     *
     * @access public
     * @see no
     * @since no
     * @deprecated no
     */
    public function getFlujoCajaResumido($select, $sidx, $sord, $start, $limit, $filters, $searchOn, $grouping, $FromDateLocal, $ToDateLocal, $ConcesionarioId = "", $ConcesionarioId2 = "", $ConcesionarioId3 = "", $PaisId = "", $Mandante = "", $PuntoVentaId = "", $mandante = '')
    {

        $PuntoVentaMySqlDAO = new PuntoVentaMySqlDAO();

        $usuarios = $PuntoVentaMySqlDAO->queryFlujoCajaResumido($select, $sidx, $sord, $start, $limit, $filters, $searchOn, $grouping, $FromDateLocal, $ToDateLocal, $ConcesionarioId, $ConcesionarioId2, $ConcesionarioId3, $PaisId, $Mandante, $PuntoVentaId, $mandante);

        if ($usuarios != null && $usuarios != "") {
            return $usuarios;
        } else {
            throw new Exception("No existe " . get_class($this), "98");
        }
    }


    /**
     * Obtiene un resumen del flujo de caja con horas.
     *
     * @param string $select Campos a seleccionar.
     * @param string $sidx Índice de ordenación.
     * @param string $sord Orden de clasificación (ascendente o descendente).
     * @param int $start Inicio de los resultados.
     * @param int $limit Límite de resultados.
     * @param string $filters Filtros aplicados.
     * @param bool $searchOn Indica si la búsqueda está activada.
     * @param string $grouping Agrupación de resultados.
     * @param string $FromDateLocal Fecha de inicio en formato local.
     * @param string $ToDateLocal Fecha de fin en formato local.
     * @param string $ConcesionarioId ID del concesionario (opcional).
     * @param string $ConcesionarioId2 ID del segundo concesionario (opcional).
     * @param string $ConcesionarioId3 ID del tercer concesionario (opcional).
     * @param string $PaisId ID del país (opcional).
     * @param string $Mandante Mandante (opcional).
     * @param string $PuntoVentaId ID del punto de venta (opcional).
     * @param string $mandante Mandante (opcional).
     * @return array|null Resumen del flujo de caja con horas.
     * @throws Exception Si no existen resultados.
     */
    public function getFlujoCajaResumidoConHoras($select, $sidx, $sord, $start, $limit, $filters, $searchOn, $grouping, $FromDateLocal, $ToDateLocal, $ConcesionarioId = "", $ConcesionarioId2 = "", $ConcesionarioId3 = "", $PaisId = "", $Mandante = "", $PuntoVentaId = "", $mandante = '')
    {

        $PuntoVentaMySqlDAO = new PuntoVentaMySqlDAO();

        $usuarios = $PuntoVentaMySqlDAO->queryFlujoCajaResumidoConHoras($select, $sidx, $sord, $start, $limit, $filters, $searchOn, $grouping, $FromDateLocal, $ToDateLocal, $ConcesionarioId, $ConcesionarioId2, $ConcesionarioId3, $PaisId, $Mandante, $PuntoVentaId, $mandante);

        if ($usuarios != null && $usuarios != "") {
            return $usuarios;
        } else {
            throw new Exception("No existe " . get_class($this), "98");
        }
    }

    /**
     * Realizar una consulta en la tabla de flujos de caja 'FlujoCaja'
     * de una manera personalizada
     *
     *
     * @param String $select campos de consulta
     * @param String $sidx columna para ordenar
     * @param String $sord orden los datos asc | desc
     * @param String $start inicio de la consulta
     * @param String $limit limite de la consulta
     * @param String $filters condiciones de la consulta
     * @param boolean $searchOn utilizar los filtros o no
     * @param String $grouping columna para agrupar
     * @param String $FromDateLocal fecha inicial de la consulta
     * @param String $ToDateLocal fecha final de la consulta
     * @param String $PuntoVentaId id del punto de venta
     * @param String $CajeroId id del cajero
     *
     * @return Array resultado de la consulta
     * @throws Exception si los usuarios no existen
     *
     * @access public
     * @see no
     * @since no
     * @deprecated no
     */
    public function getFlujoCajaResumidoConCajero($select, $sidx, $sord, $start, $limit, $filters, $searchOn, $grouping, $FromDateLocal, $ToDateLocal, $PuntoVentaId = "", $CajeroId = "", $mandante = '')
    {

        $PuntoVentaMySqlDAO = new PuntoVentaMySqlDAO();

        $usuarios = $PuntoVentaMySqlDAO->queryFlujoCajaResumidoConCajero($select, $sidx, $sord, $start, $limit, $filters, $searchOn, $grouping, $FromDateLocal, $ToDateLocal, $PuntoVentaId, $CajeroId, $mandante);

        if ($usuarios != null && $usuarios != "") {
            return $usuarios;
        } else {
            throw new Exception("No existe " . get_class($this), "98");
        }
    }


    /**
     * Realizar una consulta en la tabla de informes generales 'InformeGeneral'
     * de una manera personalizada
     *
     *
     * @param String $select campos de consulta
     * @param String $sidx columna para ordenar
     * @param String $sord orden los datos asc | desc
     * @param String $start inicio de la consulta
     * @param String $limit limite de la consulta
     * @param String $filters condiciones de la consulta
     * @param boolean $searchOn utilizar los filtros o no
     * @param String $grouping columna para agrupar
     * @param String $FromDateLocal fecha inicial de la consulta
     * @param String $ToDateLocal fecha final de la consulta
     * @param String $TypeBet tipo de apuesta
     * @param String $PaisId id del pais
     * @param String $Mandante Mandante
     *
     * @return Array resultado de la consulta
     * @throws Exception si los usuarios no existen
     *
     * @access public
     * @see no
     * @since no
     * @deprecated no
     */
    public function getInformeGerencial($select, $sidx, $sord, $start, $limit, $filters, $searchOn, $grouping, $FromDateLocal, $ToDateLocal, $TypeBet = "", $PaisId = "", $Mandante = "", $TypeUser = "", $WalletId = "")
    {

        $PuntoVentaMySqlDAO = new PuntoVentaMySqlDAO();

        $usuarios = $PuntoVentaMySqlDAO->queryInformeGerencial($select, $sidx, $sord, $start, $limit, $filters, $searchOn, $grouping, $FromDateLocal, $ToDateLocal, $TypeBet, $PaisId, $Mandante, $TypeUser, $WalletId);

        if ($usuarios != null && $usuarios != "") {
            return $usuarios;
        } else {
            throw new Exception("No existe " . get_class($this), "98");
        }
    }

    /**
     * Realizar una consulta en la tabla de resumenes de usuario 'UsuarioResumen'
     * de una manera personalizada
     *
     *
     * @param String $select campos de consulta
     * @param String $sidx columna para ordenar
     * @param String $sord orden los datos asc | desc
     * @param String $start inicio de la consulta
     * @param String $limit limite de la consulta
     * @param String $filters condiciones de la consulta
     * @param boolean $searchOn utilizar los filtros o no
     * @param String $grouping columna para agrupar
     * @param String $FromDateLocal fecha inicial de la consulta
     * @param String $ToDateLocal fecha final de la consulta
     * @param String $TypeBet tipo de apuesta
     * @param String $PaisId id del pais
     * @param String $Mandante Mandante
     *
     * @return Array resultado de la consulta
     * @throws Exception si los uaurios no existen
     *
     * @access public
     * @see no
     * @since no
     * @deprecated no
     */
    public function getUsuarioResumen($select, $sidx, $sord, $start, $limit, $filters, $searchOn, $grouping, $FromDateLocal, $ToDateLocal, $TypeBet = "", $PaisId = "", $Mandante = "")
    {

        $PuntoVentaMySqlDAO = new PuntoVentaMySqlDAO();

        $usuarios = $PuntoVentaMySqlDAO->queryUsuarioResumen($select, $sidx, $sord, $start, $limit, $filters, $searchOn, $grouping, $FromDateLocal, $ToDateLocal, $TypeBet, $PaisId, $Mandante);

        if ($usuarios != null && $usuarios != "") {
            return $usuarios;
        } else {
            throw new Exception("No existe " . get_class($this), "98");
        }
    }

    /**
     * Realizar una consulta en la tabla de resumenes de usuario 'UsuarioResumen'
     * de una manera personalizada
     *
     *
     * @param String $select campos de consulta
     * @param String $sidx columna para ordenar
     * @param String $sord orden los datos asc | desc
     * @param String $start inicio de la consulta
     * @param String $limit limite de la consulta
     * @param String $filters condiciones de la consulta
     * @param boolean $searchOn utilizar los filtros o no
     * @param String $grouping columna para agrupar
     * @param String $FromDateLocal fecha inicial de la consulta
     * @param String $ToDateLocal fecha final de la consulta
     * @param String $FromId FromId
     * @param String $UserId id del usuario
     * @param String $TypeBet tipo de apuesta
     * @param String $ConcesionarioId tipo del concensionario
     * @param String $PaisId id del pais
     * @param String $Mandante Mandante
     *
     * @return Array resultado de la consulta
     * @throws Exception si los usuarios no existen
     *
     * @access public
     * @see no
     * @since no
     * @deprecated no
     */
    public function getInformeGerencialByUser($select, $sidx, $sord, $start, $limit, $filters, $searchOn, $grouping, $FromDateLocal, $ToDateLocal, $FromId = "", $UserId = "", $TypeBet = "", $ConcesionarioId = "", $PaisId = "", $Mandante = "")
    {

        $PuntoVentaMySqlDAO = new PuntoVentaMySqlDAO();

        $usuarios = $PuntoVentaMySqlDAO->queryInformeGerencialByUser($select, $sidx, $sord, $start, $limit, $filters, $searchOn, $grouping, $FromDateLocal, $ToDateLocal, $FromId, $UserId, $TypeBet, $ConcesionarioId, $PaisId, $Mandante);

        if ($usuarios != null && $usuarios != "") {
            return $usuarios;
        } else {
            throw new Exception("No existe " . get_class($this), "98");
        }
    }


    /**
     * Modificar el campo 'creditos' de un objeto
     *
     * @param String $valor valor
     * @param String $transaction transaction
     *
     * @return no
     *
     */
    public function setBalanceCreditos($valor = "", $transaction = "")
    {
        $this->creditos = "creditos + " . $valor;


        if ($transaction != "") {
            $PuntoVentaMySqlDAO = new PuntoVentaMySqlDAO($transaction);
            $cant = $PuntoVentaMySqlDAO->update($this, ' AND  creditos + ' . $valor . ' >= 0 ');
        }

        return $cant;
    }

    /**
     * Modificar el campo 'creditosBase' de un objeto
     *
     * @param String $valor valor
     * @param String $transaction transaction
     *
     * @return no
     *
     */
    public function setBalanceCreditosBase($valor = "", $transaction = "")
    {

        $this->creditosBase = "creditos_base + " . $valor;

        $cant = 0;

        if ($transaction != "") {
            $PuntoVentaMySqlDAO = new PuntoVentaMySqlDAO($transaction);
            $cant = $PuntoVentaMySqlDAO->update($this, ' AND  creditos_base + ' . $valor . ' >= 0 ');
        }

        return $cant;
    }

    /**
     * Modificar el campo 'cupoRecarga' de un objeto
     *
     * @param String $valor valor
     *
     * @return no
     *
     */
    public function setBalanceCupoRecarga($valor, $transaction = "")
    {
        $this->cupoRecarga = "cupo_recarga + " . $valor;

        $cant = 0;

        if ($transaction != "") {
            $PuntoVentaMySqlDAO = new PuntoVentaMySqlDAO($transaction);
            $cant = $PuntoVentaMySqlDAO->update($this, ' AND  cupo_recarga + ' . $valor . ' >= 0 ');
        }

        return $cant;
    }
}
