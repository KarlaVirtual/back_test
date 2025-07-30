<?php

namespace Backend\dto;

use Backend\dto\Usuario;
use Backend\dto\Registro;
use Backend\dto\Template;
use Backend\dto\Clasificador;
use Backend\dto\PaisMandante;
use Backend\mysql\ReferidoInvitacionMySqlDAO;
use Backend\sql\Transaction;

class ReferidoInvitacion
{
    /** @var int
     *Representación de la columna 'refinvitacion_id' de la tabla 'referido_invitacion' */
    var $refinvitacionId;

    /** @var int
     * Representación de la columna 'usuid_referente' de la tabla 'referido_invitacion' */
    var $usuidReferente;

    /** @var string
     *Representación de la columna 'referido_email' de la tabla 'referido_invitacion' */
    var $referidoEmail;

    /** @var boolean
     *Representación de la columna 'referido_exitoso' de la tabla 'referido_invitacion' */
    var $referidoExitoso;

    /** @var string
     *Representación de la columna 'asunto' de la tabla 'referido_invitacion' */
    var $asunto;

    /** @var string
     *Representación de la columna 'mensaje' de la tabla 'referido_invitacion'*/
    var $mensaje;

    /** @var boolean
     * Representación de la columna 'leido' de la tabla 'referido_invitacion'*/
    var $leido;

    /** @var int
     * Representación de la columna 'usucrea_id' de la tabla 'referido_invitacion'*/
    var $usucreaId;

    /** @var int
     * Representación de la columna 'usumodif_id' de la tabla 'referido_invitacion'*/
    var $usumodifId;

    /** @var string
     * Representación de la columna 'fecha_crea' de la tabla 'referido_invitacion'*/
    var $fechaCrea;

    /** @var string
     * Representación de la columna 'fecha_modif' de la tabla 'referido_invitacion'*/
    var $fechaModif;

    /** @var string
     * Representación de la columna 'estado' de la tabla 'referido_invitacion'*/
    var $estado;

    /**
     * Constructor de la clase ReferidoInvitacion.
     *
     * @param int|string $refinvitacionId ID de la invitación referida.
     * @throws Exception Si la invitación referida no existe.
     */
    public function __construct($refinvitacionId = "")
    {
        if ($refinvitacionId != null || $refinvitacionId != "") {
            $ReferidoInvitacionMySqlDAO = new ReferidoInvitacionMySqlDAO();
            $ReferidoInvitacion = $ReferidoInvitacionMySqlDAO->load($refinvitacionId);

            if ($ReferidoInvitacion == null || $ReferidoInvitacion == "") throw new Exception("No existe " . get_class($this), 4002);

            /** Toda propiedad que se agregue a la función readRow de MySqlDAO y se defina en el dto, es incializada por el foreach */
            foreach ($ReferidoInvitacion as $propiedad => $valor) {
                $this->$propiedad = $valor;
            }
        }
    }


    /**
     * Obtiene el ID de la invitación referida.
     *
     * @return int
     */
    public function getRefinvitacionId()
    {
        return $this->refinvitacionId;
    }

    /**
     * Establece el ID de la invitación referida.
     *
     * @param int $refinvitacionId
     */
    public function setRefinvitacionId(int $refinvitacionId): void
    {
        $this->refinvitacionId = $refinvitacionId;
    }

    /**
     * Obtiene el ID del referente.
     *
     * @return int
     */
    public function getUsuidReferente()
    {
        return $this->usuidReferente;
    }

    /**
     * Establece el ID del referente.
     *
     * @param int $usuidReferente
     */
    public function setUsuidReferente(int $usuidReferente): void
    {
        $this->usuidReferente = $usuidReferente;
    }

    /**
     * Obtiene el email del referido.
     *
     * @return string
     */
    public function getReferidoEmail()
    {
        return $this->referidoEmail;
    }

    /**
     * Establece el email del referido.
     *
     * @param string $referidoEmail
     */
    public function setReferidoEmail(string $referidoEmail): void
    {
        $this->referidoEmail = $referidoEmail;
    }

    /**
     * Obtiene si el referido fue exitoso.
     *
     * @return boolean
     */
    public function getReferidoExitoso()
    {
        return $this->referidoExitoso;
    }

    /**
     * Establece si el referido fue exitoso.
     *
     * @param boolean $referidoExitoso
     */
    public function setReferidoExitoso($referidoExitoso): void
    {
        $this->referidoExitoso = $referidoExitoso;
    }

    /**
     * Obtiene el asunto de la invitación.
     *
     * @return string
     */
    public function getAsunto()
    {
        return $this->asunto;
    }

    /**
     * Establece el asunto de la invitación.
     *
     * @param string $asunto
     */
    public function setAsunto(string $asunto): void
    {
        $this->asunto = $asunto;
    }

    /**
     * Obtiene el mensaje de la invitación.
     *
     * @return string
     */
    public function getMensaje()
    {
        return $this->mensaje;
    }

    /**
     * Establece el mensaje de la invitación.
     *
     * @param string $mensaje
     */
    public function setMensaje(string $mensaje): void
    {
        $this->mensaje = $mensaje;
    }

    /**
     * Obtiene si la invitación fue leída.
     *
     * @return boolean
     */
    public function getLeido()
    {
        return $this->leido;
    }

    /**
     * Establece si la invitación fue leída.
     *
     * @param boolean $leido
     */
    public function setLeido(int $leido): void
    {
        $this->leido = $leido;
    }

    /**
     * Obtiene el ID del usuario que creó la invitación.
     *
     * @return int
     */
    public function getUsucreaId()
    {
        return $this->usucreaId;
    }

    /**
     * Establece el ID del usuario que creó la invitación.
     *
     * @param int $usucreaId
     */
    public function setUsucreaId(int $usucreaId): void
    {
        $this->usucreaId = $usucreaId;
    }

    /**
     * Obtiene el ID del usuario que modificó la invitación.
     *
     * @return int
     */
    public function getUsumodifId()
    {
        return $this->usumodifId;
    }

    /**
     * Establece el ID del usuario que modificó la invitación.
     *
     * @param int $usumodifId
     */
    public function setUsumodifId(int $usumodifId): void
    {
        $this->usumodifId = $usumodifId;
    }

    /**
     * Obtiene la fecha de creación de la invitación.
     *
     * @return string
     */
    public function getFechaCrea()
    {
        return $this->fechaCrea;
    }

    /**
     * Establece la fecha de creación de la invitación.
     *
     * @param string $fechaCrea
     */
    public function setFechaCrea(string $fechaCrea): void
    {
        $this->fechaCrea = $fechaCrea;
    }

    /**
     * Obtiene la fecha de modificación de la invitación.
     *
     * @return string
     */
    public function getFechaModif()
    {
        return $this->fechaModif;
    }

    /**
     * Establece la fecha de modificación de la invitación.
     *
     * @param string $fechaModif
     */
    public function setFechaModif(string $fechaModif): void
    {
        $this->fechaModif = $fechaModif;
    }

    /**
     * Obtiene el estado de la invitación.
     *
     * @return string
     */
    public function getEstado()
    {
        return $this->estado;
    }

    /**
     * Establece el estado de la invitación.
     *
     * @param string $estado
     */
    public function setEstado(string $estado): void
    {
        $this->estado = $estado;
    }

    /** Retorna la plantilla por defecto para el asunto utlizado en el envío de correos a referidos.
     *
     * @param PaisMandante PaisMandante del programa de referidos con base al cual se consultará la plantilla
     * @param string Idioma de la plantilla
     * @return string Retornar el string correspondiente a la plantilla del asunto
     */
    public function getAsuntoTemplate(PaisMandante $PaisMandante, $idioma)
    {
        $Clasificador = new Clasificador('', 'REFERREDINVITATIONSUBJECT');
        $Template = new Template('', $PaisMandante->getMandante(), $Clasificador->getClasificadorId(), $PaisMandante->getPaisId(), $idioma);
        return $Template->getTemplateHtml();
    }

    /** Retorna la plantilla por defecto para el asunto utilizado en el envío de correos a referidos con las
     *etiquetas personalizables reemplazadas.
     *
     * @param PaisMandante PaisMandante del programa de referidos con base al cual se consultará la plantilla
     * @param string Idioma de la plantilla
     * @return string Retornar el string correspondiente a la plantilla del asunto
     */
    public function getAsuntoTemplatePersonalizado(UsuarioOtrainfo $UsuarioOtrainfo, PaisMandante $PaisMandante, $idioma)
    {
        $subject = $this->getAsuntoTemplate($PaisMandante, $idioma);
        switch ($PaisMandante->getPaismandanteId()) {
            //DTR Reemplazar el case con el paisMandanteId lotosport brasil en producción
            case 36:
                $Registro = new Registro("", $UsuarioOtrainfo->getUsuarioId());
                $finalParameters["namereferent"] = $Registro->getNombre1();
                $finalParameters["lastnamereferent"] = $Registro->getApellido1();
                $patterns = [];
                foreach ($finalParameters as $searchedPattern => $finalValue) {
                    $patterns[$searchedPattern] = '/\#' . $searchedPattern . '\#/';
                }
                $subject = preg_replace($patterns, $finalParameters, $subject);
                $markUpPattern = '#<(/|div){1}[^<]*>#';
                $subject = preg_replace($markUpPattern, "", $subject);
                break;
            default:
                $Registro = new Registro("", $UsuarioOtrainfo->getUsuarioId());
                $finalParameters["namereferent"] = $Registro->getNombre1();
                $finalParameters["lastnamereferent"] = $Registro->getApellido1();
                $patterns = [];
                foreach ($finalParameters as $searchedPattern => $finalValue) {
                    $patterns[$searchedPattern] = '/\#' . $searchedPattern . '\#/';
                }
                $subject = preg_replace($patterns, $finalParameters, $subject);
                $markUpPattern = '#<(/|div){1}[^<]*>#';
                $subject = preg_replace($markUpPattern, "", $subject);
                break;
        }
        return $subject;
    }


    /** Retorna la plantilla por defecto para el mensaje utlizado en el envío de correos a referidos.
     *
     * @param PaisMandante PaisMandante del programa de referidos con base al cual se consultará la plantilla
     * @param string Idioma de la plantilla
     * @return string Retornar el string correspondiente a la plantilla del asunto
     */
    public function getMensajeTemplate(PaisMandante $PaisMandante, $idioma)
    {
        $Clasificador = new Clasificador("", 'REFERREDINVITATION');
        $Template = new Template("", $PaisMandante->getMandante(), $Clasificador->getClasificadorId(), $PaisMandante->getPaisId(), $idioma);
        return $Template->getTemplateHtml();
    }


    /** Retorna la plantilla por defecto para el mensaje utilizado en el envío de correos a referidos con las
     *etiquetas personalizables reemplazadas.
     *
     * @param UsuarioOtrainfo UsuarioOtrainfo con base en el cual se personaliza la plantilla
     * @param PaisMandante PaisMandante del programa de referidos con base al cual se consultará la plantilla
     * @param string Idioma de la plantilla
     * @return string Retornar el string correspondiente a la plantilla del asunto
     */
    public function getMensajeTemplatePersonalizado(UsuarioOtrainfo $UsuarioOtrainfo, PaisMandante $PaisMandante, $idioma)
    {
        $htmlMessage = $this->getMensajeTemplate($PaisMandante, $idioma);
        switch ($PaisMandante->getPaismandanteId()) {
            //DTR Reemplazar el case con el paisMandanteId lotosport brasil en producción
            case 36:
                $Registro = new Registro("", $UsuarioOtrainfo->getUsuarioId());
                $finalParameters["namereferent"] = $Registro->getNombre1();
                $finalParameters["lastnamereferent"] = $Registro->getApellido1();
                $patterns = [];
                foreach ($finalParameters as $searchedPattern => $finalValue) {
                    $patterns[$searchedPattern] = '/\#' . $searchedPattern . '\#/';
                }
                $htmlMessage = preg_replace($patterns, $finalParameters, $htmlMessage);
                break;
            default:
                $Registro = new Registro("", $UsuarioOtrainfo->getUsuarioId());
                $finalParameters["namereferent"] = $Registro->getNombre1();
                $finalParameters["lastnamereferent"] = $Registro->getApellido1();
                $patterns = [];
                foreach ($finalParameters as $searchedPattern => $finalValue) {
                    $patterns[$searchedPattern] = '/\#' . $searchedPattern . '\#/';
                }
                $htmlMessage = preg_replace($patterns, $finalParameters, $htmlMessage);
                break;
        }
        return $htmlMessage;
    }


    /**
     * Obtiene las invitaciones referidas personalizadas según los parámetros dados.
     *
     * @param string $select Columnas a seleccionar.
     * @param string $sidx Índice de ordenación.
     * @param string $sord Orden de clasificación (ascendente o descendente).
     * @param int $start Inicio de los resultados.
     * @param int $limit Límite de resultados.
     * @param array $filters Filtros a aplicar.
     * @param bool $searchOn Indica si la búsqueda está activada.
     * @param bool $onlyCount Indica si solo se debe contar los resultados.
     * @return array|int Lista de invitaciones referidas o el conteo de las mismas.
     * @throws Exception Si no existen invitaciones referidas.
     */
    public function getReferidoInvitacionCustom($select, $sidx, $sord, $start, $limit, $filters, $searchOn, $onlyCount = false)
    {
        $ReferidoInvitancionMySqlDAO = new ReferidoInvitacionMySqlDAO();
        $invitaciones = $ReferidoInvitancionMySqlDAO->queryReferidoInvitacionCustom($select, $sidx, $sord, $start, $limit, $filters, $searchOn, $onlyCount);

        if ($invitaciones != null && $invitaciones != "") {
            return $invitaciones;
        } else {
            throw new Exception("No existe " . get_class($this), "01");
        }
    }
}