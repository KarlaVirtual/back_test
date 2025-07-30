<?php

/**
 * CronJobAsignacionRuleta
 *
 * @author David Alvarez <juan.alvarez@virtualsoft.tech>
 * @since 25/02/2025
 */

use Backend\dto\BonoInterno;
use Backend\dto\RuletaDetalle;
use Backend\dto\Usuario;
use Backend\dto\UsuarioMandante;
use Backend\dto\UsuarioRuleta;
use Backend\dto\WebsocketNotificacion;
use Backend\mysql\BonoDetalleMySqlDAO;
use Backend\mysql\UsuarioMandanteMySqlDAO;
use Backend\mysql\UsuarioRuletaMySqlDAO;
use Backend\mysql\WebsocketNotificacionMySqlDAO;

class CronJobAsignacionRuleta
{

    private $BonoInterno;
    private $transaccion;

    public function __construct()
    {
        $this->BonoInterno = new BonoInterno();
        $BonoDetalleMySqlDAO = new BonoDetalleMySqlDAO();
        $this->transaccion = $BonoDetalleMySqlDAO->getTransaction();
    }

    public function execute()
    {
        try {

            $filename = __DIR__ . '/lastrunCronJobAsignacionRuleta';

            $sqlProcesoInterno2 = "SELECT * FROM proceso_interno2 WHERE tipo='ASIGNACIONRULETA'";

            $data = $this->BonoInterno->execQuery('', $sqlProcesoInterno2);
            $data = $data[0];
            $line = $data->{'proceso_interno2.fecha_ultima'};

            if ($line == '') return;

            $fechaL1 = date('Y-m-d H:i:00', strtotime($line . '+1 minute'));
            $fechaL2 = date('Y-m-d H:i:59', strtotime($line . '+1 minute'));

            $filename .= str_replace(' ', '-', str_replace(':', '-', $fechaL1));

            if ($fechaL1 >= date('Y-m-d H:i:00', strtotime('-1 minute'))) {
                unlink($filename);
                return;
            }

            if (file_exists($filename)) {
                $datefilename = date("Y-m-d H:i:s", filemtime($filename));
                if ($datefilename <= date("Y-m-d H:i:s", strtotime('-10 minute'))) unlink($filename);
                return;
            }

            file_put_contents($filename, 'RUN');

            $sqlProcesoInterno2 = "UPDATE proceso_interno2 SET fecha_ultima='" . $fechaL1 . "' WHERE  tipo='ASIGNACIONRULETA';";

            $data = $this->BonoInterno->execQuery($this->transaccion, $sqlProcesoInterno2);
            $this->transaccion->commit();

            #RULETA PARA REGISTRO DE USUARIO NUEVO
            $ruletasParaRegistro = $this->getRuletasParaRegistro($fechaL1, $fechaL2);
            foreach ($ruletasParaRegistro as $datanum) {
                $this->asignarRuleta($datanum->{'sq.ruleta_id'}, $datanum->{'r.usuario_id'}, $datanum->{'um.usumandante_id'}, $datanum->{'sq.fecha_expiracion'});
            }

            #RULETA PARA LOGIN - PRIMER INICIO DE SESION DEL DIA
            $ruletasParaLogin = $this->getRuletasParaLogin($fechaL1, $fechaL2);
            foreach ($ruletasParaLogin as $datanum) {
                $this->asignarRuleta($datanum->{'sq.ruleta_id'}, $datanum->{'ul.usuario_id'}, $datanum->{'um.usumandante_id'}, $datanum->{'sq.fecha_expiracion'}, $datanum->{'ur.usuruletaId'});
            }

            unlink($filename);
        } catch (\Throwable $th) {
            if ($_ENV['debug']) {
                print_r($th->getMessage());
            }
            throw $th;
        }
    }

    /**
     * Obtiene el listado de usuarios registrados en el sistema dentro del rango de fecha y hora especificado,
     * y devuelve un listado de usuarios con una ruleta de tipo 'ESPARAREGISTRO', seleccionada por orden.
     * @param string $fechaL1 Fecha y hora de inicio del rango de búsqueda
     * @param string $fechaL2 Fecha y hora de fin del rango de búsqueda
     * @return array
     */
    private function getRuletasParaRegistro(string $fechaL1, string $fechaL2)
    {
        $sql = "SELECT r.usuario_id, um.usumandante_id, sq.ruleta_id AS ruleta_id, sq.fecha_expiracion
            FROM registro r
            INNER JOIN usuario u ON r.usuario_id = u.usuario_id
            INNER JOIN usuario_mandante um ON um.usuario_mandante = u.usuario_id
            INNER JOIN (
                SELECT ri.ruleta_id, ri.mandante,rd2.valor AS pais,
                 CASE WHEN  rd_exp.tipo = 'EXPIRACIONFECHA' THEN rd_exp.valor
                     WHEN  rd_exp.tipo = 'EXPIRACIONDIA' THEN DATE_ADD(now(), INTERVAL rd_exp.valor DAY)
                     ELSE '' END fecha_expiracion
                FROM ruleta_interno ri
                INNER JOIN ruleta_detalle rd1 
                    ON rd1.ruleta_id = ri.ruleta_id 
                    AND rd1.tipo = 'ESPARAREGISTRO' 
                    AND rd1.valor = 1
                INNER JOIN ruleta_detalle rd2 
                    ON rd2.ruleta_id = ri.ruleta_id 
                    AND rd2.tipo = 'CONDPAISUSER'
                LEFT JOIN ruleta_detalle rd_exp 
                    ON rd_exp.ruleta_id = ri.ruleta_id 
                    AND rd_exp.tipo IN ('EXPIRACIONFECHA', 'EXPIRACIONDIA')
                LEFT JOIN ruleta_detalle rd_csv 
                    ON rd_csv.ruleta_id = ri.ruleta_id 
                    AND rd_csv.tipo = 'CSVREGISTRADO'
                WHERE ri.estado = 'A' 
                AND rd_csv.ruleta_id IS NULL
                AND ri.fecha_inicio <= NOW() AND ri.fecha_fin >= NOW()
                AND CASE
                    WHEN rd_exp.tipo = 'EXPIRACIONFECHA' THEN rd_exp.valor >= NOW()
                    else true
                    END
                ORDER BY ri.orden ASC
                LIMIT 1
            ) AS sq ON (sq.ruleta_id IS NOT NULL AND sq.mandante = u.mandante  AND sq.pais = u.pais_id)
            LEFT OUTER JOIN usuario_ruleta ur ON (ur.usuario_id = u.usuario_id AND ur.ruleta_id = sq.ruleta_id  AND ur.estado != 'PP')
            LEFT OUTER JOIN usuario_ruleta ur2 ON (ur2.usuario_id = um.usumandante_id AND ur2.ruleta_id = sq.ruleta_id  AND ur2.estado != 'PP')
            WHERE 
            um.usumandante_id NOT IN (
                SELECT ur.usuario_id
                FROM usuario_ruleta ur
                INNER JOIN ruleta_interno ri1 ON ri1.ruleta_id = ur.ruleta_id
                INNER JOIN ruleta_detalle rd ON rd.ruleta_id = ri1.ruleta_id 
                WHERE rd.tipo = 'ESPARAREGISTRO' AND ri1.estado = 'A'
            )
            AND u.fecha_crea >= '{$fechaL1}'
            AND u.fecha_crea < '{$fechaL2}'
            ";
        return $this->BonoInterno->execQuery('', $sql);
    }

    /**
     * Obtiene el listado de usuarios que inician sesión por primera vez en el día dentro del rango de fecha y hora especificado,
     * y devuelve un listado de usuarios con una ruleta de tipo 'PARAPRIMERLOGINDIA', seleccionada por orden.
     * @param string $fechaL1 Fecha y hora de inicio del rango de búsqueda
     * @param string $fechaL2 Fecha y hora de fin del rango de búsqueda
     * @return array
     */
    private function getRuletasParaLogin(string $fechaL1, string $fechaL2)
    {

        $sql = "SELECT ul.usuario_id, um.usumandante_id, sq.ruleta_id AS ruleta_id, MIN(ul.fecha_crea) AS fecha_primer_inicio_sesion,sq.fecha_expiracion, sq.ruleta_csv, ur.usuruleta_id usuruletaId
            FROM usuario_log ul
            INNER JOIN usuario u ON u.usuario_id = ul.usuario_id
            INNER JOIN usuario_mandante um ON um.usuario_mandante = u.usuario_id
            INNER JOIN (
                SELECT ri.ruleta_id, ri.mandante, rd2.valor AS pais,
                 CASE WHEN  rd_exp.tipo = 'EXPIRACIONFECHA' THEN rd_exp.valor
                     WHEN  rd_exp.tipo = 'EXPIRACIONDIA' THEN DATE_ADD(now(), INTERVAL rd_exp.valor DAY)
                     ELSE '' END fecha_expiracion, rd_rep.valor ruleta_repite, rd_csv.valor ruleta_csv
                FROM ruleta_interno ri
                INNER JOIN ruleta_detalle rd1 
                    ON rd1.ruleta_id = ri.ruleta_id 
                    AND rd1.tipo = 'PARAPRIMERLOGINDIA' 
                    AND rd1.valor = 1
                INNER JOIN ruleta_detalle rd2 
                    ON rd2.ruleta_id = ri.ruleta_id 
                    AND rd2.tipo = 'CONDPAISUSER'
                LEFT JOIN ruleta_detalle rd_exp 
                    ON rd_exp.ruleta_id = ri.ruleta_id 
                    AND rd_exp.tipo IN ('EXPIRACIONFECHA', 'EXPIRACIONDIA')
                LEFT JOIN ruleta_detalle rd_rep 
                    ON rd_rep.ruleta_id = ri.ruleta_id 
                    AND rd_rep.tipo = 'USUARIOREPITE'
                LEFT JOIN ruleta_detalle rd_csv 
                    ON rd_csv.ruleta_id = ri.ruleta_id 
                    AND rd_csv.tipo = 'CSVREGISTRADO'
                WHERE ri.estado = 'A'
                AND ri.fecha_inicio <= NOW() AND ri.fecha_fin >= NOW()
                    AND CASE
                    WHEN rd_exp.tipo = 'EXPIRACIONFECHA' THEN rd_exp.valor >= NOW()
                    else true
                    END
                GROUP BY ri.ruleta_id
                
            ) AS sq ON (sq.ruleta_id IS NOT NULL AND sq.mandante = u.mandante AND sq.pais = u.pais_id)
            -- Se valida que el usuario no tenga contingencia de abusador de bonos actíva
            LEFT JOIN (
                SELECT uc.usuario_id FROM usuario_configuracion uc 
                WHERE tipo = (SELECT clasificador_id FROM clasificador WHERE abreviado = 'BONDABUSER') AND estado='A' 
            ) as contingencia ON (contingencia.usuario_id = um.usuario_mandante)
            LEFT OUTER JOIN usuario_ruleta ur ON (ur.usuario_id = um.usumandante_id AND ur.ruleta_id = sq.ruleta_id AND ur.estado='PP')
            LEFT OUTER JOIN usuario_ruleta ur2 ON (ur2.usuario_id = um.usumandante_id AND ur2.ruleta_id = sq.ruleta_id  AND ur2.estado != 'PP')
            WHERE contingencia.usuario_id IS NULL
                AND ul.fecha_crea >= CURRENT_DATE()
            AND ul.tipo IN ('LOGIN', 'LOGINAPP')
            AND ( 
                sq.ruleta_csv IS NULL
                OR ( sq.ruleta_csv IS NOT NULL AND ur.usuruleta_id IS NOT NULL )
            )
            AND ( 
                -- Si USUARIOREPITE es 1, la ruleta puede aparecer cada día
                sq.ruleta_repite = 1
                OR 
                -- Si USUARIOREPITE es 0, solo debe aparecer si no existe en usuario_ruleta
                ((sq.ruleta_repite is null or sq.ruleta_repite = 0) AND ur2.usuruleta_id IS NULL)
            )
            GROUP BY ul.usuario_id
            HAVING fecha_primer_inicio_sesion >= '{$fechaL1}' AND fecha_primer_inicio_sesion < '{$fechaL2}';
            ";
        return $this->BonoInterno->execQuery('', $sql);
    }

    /**
     * Asigna una ruleta a un usuario
     * @param int $ruletaId Identificador de la ruleta
     * @param int $usuarioId Identificador del usuario
     * @return void
     */
    private function asignarRuleta($ruletaId, $usuarioId, $usumandanteId, $fechaExpiracion, $usuruletaId = '')
    {
        $Usuario = new Usuario($usuarioId);
        $UsuarioRuletaMySqlDAO = new UsuarioRuletaMySqlDAO($this->transaccion);

        try {
            $ruletaDetalle = new RuletaDetalle("", $ruletaId, "MINBETPRICE", $Usuario->moneda);
        } catch (\Throwable $th) {
            if ($th->getCode() == 21 && $th->getMessage() == "No existe Backend\dto\RuletaDetalle") {
                $ruletaDetalle = new stdClass();
                $ruletaDetalle->valor = 0;
            }else {
                throw $th;
            }
        }

        if($usuruletaId > 0 ){
            $UsuarioRuleta = new UsuarioRuleta($usuruletaId);
            $UsuarioRuleta->valorBase = $ruletaDetalle->valor > 0 ? $ruletaDetalle->valor : 0;
            $UsuarioRuleta->estado = $ruletaDetalle->valor > 0 ? 'PR' : 'A';
            $UsuarioRuleta->fechaExpiracion = $fechaExpiracion;
            $UsuarioRuletaMySqlDAO->update($UsuarioRuleta);
        }else{
            $UsuarioRuleta = new UsuarioRuleta();
            $UsuarioRuleta->ruletaId = $ruletaId;
            $UsuarioRuleta->usuarioId = $usumandanteId;
            $UsuarioRuleta->posicion = 0;
            $UsuarioRuleta->valor = 0;
            $UsuarioRuleta->fechaCrea = 0;
            $UsuarioRuleta->usucreaId = 0;
            $UsuarioRuleta->fechaModif = 0;
            $UsuarioRuleta->usumodifId = 0;
            $UsuarioRuleta->errorId = 0;
            $UsuarioRuleta->idExterno = 0;
            $UsuarioRuleta->mandante = 0;
            $UsuarioRuleta->version = 0;
            $UsuarioRuleta->apostado = 0;
            $UsuarioRuleta->codigo = 0;
            $UsuarioRuleta->externoId = 0;
            $UsuarioRuleta->valorPremio = 0;
            $UsuarioRuleta->premio = "";
            $UsuarioRuleta->valorBase = $ruletaDetalle->valor > 0 ? $ruletaDetalle->valor : 0;
            $UsuarioRuleta->estado = $ruletaDetalle->valor > 0 ? 'PR' : 'A';
            $UsuarioRuleta->fechaExpiracion = $fechaExpiracion;
            $UsuarioRuletaMySqlDAO->insert($UsuarioRuleta);
        }

        $this->transaccion->commit();

        if ($UsuarioRuleta->estado == "A") {
            $websocketNotification = new WebsocketNotificacion();
            $websocketNotificacionMySqlDAO = new WebsocketNotificacionMySqlDAO($this->transaccion);

            $websocketNotification->setTipo('ruleta');
            $websocketNotification->setCanal('');
            $websocketNotification->setEstado('P');
            $websocketNotification->setUsuarioId($usuarioId);
            $websocketNotification->setValor($UsuarioRuleta->usuruletaId);
            $websocketNotification->setFecha_exp(date('Y-m-d H:i:s', strtotime('+6 hour')));

            $UsuarioMandanteMySqlDAO = new UsuarioMandanteMySqlDAO();
            $idUsuMandante = $UsuarioMandanteMySqlDAO->queryByUsuarioMandante($usuarioId);
            $UsuarioMandante = new UsuarioMandante($idUsuMandante[0]->usumandanteId);

            $ruleta = $UsuarioRuleta->getRuletasUsuarioMandante($UsuarioMandante);
            $ruleta = !empty($ruleta) ? json_encode(['roulette' => $ruleta]) : '';

            $websocketNotification->setMensaje($ruleta);
            $websocketNotificacionMySqlDAO->insert($websocketNotification);
            $this->transaccion->commit();
        }
    }
}
