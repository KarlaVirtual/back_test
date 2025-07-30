<?php
use Backend\dto\UsuarioBono;
use Backend\mysql\UsuarioBonoMySqlDAO;
use Backend\dto\BonoInterno;
use Backend\dto\ConfigurationEnvironment;




/**
 * Clase 'CronJobNotifyBono'
 *
 *
 *
 *
 * Ejemplo de uso:
 *
 *
 *
 * @package ninguno
 * @author Daniel Tamayo <it@virtualsoft.tech>
 * @version ninguna
 * @access public
 * @see no
 *
 */
class CronJobNotifyBono
{


    public function __construct()
    {
    }

    public function execute()
    {


        $ConfigurationEnvironment = new ConfigurationEnvironment();
        $_ENV["enabledConnectionGlobal"] = 1;


        $sql = "SELECT
    ub.bono_id,
    COUNT(*) AS total_bonos,
    SUM(CASE WHEN ub.estado = 'R' THEN 1 ELSE 0 END) AS bonos_reclamados,
    (SUM(CASE WHEN ub.estado = 'R' THEN 1 ELSE 0 END) / COUNT(*)) * 100 AS porcentaje_reclamados,
    SUM(CASE WHEN ub.estado = 'A' THEN 1 ELSE 0 END) AS bonos_activos,
    (SUM(CASE WHEN ub.estado = 'A' THEN 1 ELSE 0 END) / COUNT(*)) * 100 AS porcentaje_activos
FROM
    usuario_bono ub
WHERE
    ub.bono_id IN (SELECT bi.bono_id FROM bono_interno bi WHERE bi.estado = 'A')
GROUP BY
    ub.bono_id";

        $execSQl = new BonoInterno();
        $datos = $execSQl->execQuery('', $sql);

        foreach ($datos as $value) {
            if (!$ConfigurationEnvironment->isDevelopment()) {

                if ($value->{'.porcentaje_activos'} >= 80) {
                    //print_r(number_format($value->{'.porcentaje_activos'},1));
                    $message = 'Bono id:' . $value->{'ub.bono_id'} . ' Porcentaje Activado:%' . number_format($value->{'.porcentaje_activos'}, 1);
                    exec("php -f " . __DIR__ . "../../src/imports/Slack/message.php '" . $message . "' '#alertas-bonos' > /dev/null & ");
                }
                if ($value->{'.porcentaje_reclamados'} >= 80) {
                    $message = 'Bono id:' . $value->{'ub.bono_id'} . 'Porcentaje Reclamado:%' . number_format($value->{'.porcentaje_reclamados'}, 1);
                    exec("php -f " . __DIR__ . "../../src/imports/Slack/message.php '" . $message . "' '#alertas-bonos' > /dev/null & ");
                }
            }

        }


        $sql = "SELECT usuario.mandante,usuario.pais_id,usuario.verifcedula_ant,usuario.verifcedula_post,t.*
FROM casino.sitio_tracking t
         inner join usuario on t.tabla_id = usuario.usuario_id
         left outer join usuario_bono on (usuario.usuario_id = usuario_bono.usuario_id
    and usuario_bono.bono_id = CASE
                                   WHEN usuario.mandante = 0 AND usuario.pais_id = 173 THEN 23763
                                   WHEN usuario.mandante = 0 AND usuario.pais_id = 66 THEN 29789
                                   WHEN usuario.mandante = 0 AND usuario.pais_id = 46 THEN 26177
                                   WHEN usuario.mandante = 0 AND usuario.pais_id = 94 THEN 34120
                                   WHEN usuario.mandante = 8 AND usuario.pais_id = 66 THEN 30955
                                   WHEN usuario.mandante = 14 THEN 32289
                                   ELSE null END
    )
WHERE (tvalue = 'apuesta_deportiva_gratis_de_25_soles' or tvalue='apuesta_deportiva_gratis_de_20_soles')
  and usuario.verifcedula_ant = 'S'   and usuario.verifcedula_post = 'S' and usuario_bono.usubono_id IS   NULL
ORDER BY sitiotracking_id DESC
LIMIT 2000";

        $execSQl = new BonoInterno();
        $datos = $execSQl->execQuery('', $sql);

        foreach ($datos as $value) {
            if (!$ConfigurationEnvironment->isDevelopment()) {

                if ($value->{'.porcentaje_activos'} >= 80) {
                    //print_r(number_format($value->{'.porcentaje_activos'},1));
                    $message = 'Bono id:' . $value->{'ub.bono_id'} . ' Porcentaje Activado:%' . number_format($value->{'.porcentaje_activos'}, 1);
                    exec("php -f " . __DIR__ . "../../src/imports/Slack/message.php '" . $message . "' '#alertas-bonos' > /dev/null & ");
                }
                if ($value->{'.porcentaje_reclamados'} >= 80) {
                    $message = 'Bono id:' . $value->{'ub.bono_id'} . 'Porcentaje Reclamado:%' . number_format($value->{'.porcentaje_reclamados'}, 1);
                    exec("php -f " . __DIR__ . "../../src/imports/Slack/message.php '" . $message . "' '#alertas-bonos' > /dev/null & ");
                }
            }
        }


    }
}