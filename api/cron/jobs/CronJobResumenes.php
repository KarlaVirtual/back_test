<?php
/**
 * Resúmen cronométrico
 *
 *
 * @package ninguno
 * @author Daniel Tamayo <it@virtualsoft.tech>
 * @version ninguna
 * @access public
 * @see no
 * @date 18.10.17
 *
 */

use Backend\cron\ResumenesCron;
use Backend\dto\BonoInterno;
use Backend\dto\ConfigurationEnvironment;
use Backend\mysql\BonoDetalleMySqlDAO;
use Backend\mysql\BonoInternoMySqlDAO;


/**
 * Clase 'CronJobResumenes'
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
class CronJobResumenes
{


    public function __construct()
    {
    }

    public function execute()
    {

        $BonoInterno = new BonoInterno();
        $BonoDetalleMySqlDAO = new BonoDetalleMySqlDAO();
        $transaccion = $BonoDetalleMySqlDAO->getTransaction();

        if (true) {

            //SCRIPT QUE CREA TABLA DEL DIA ACTUAL Y USUARIO_SALDO A ANTIER Y RENOMBRA TABLA DEL DIA ANTERIOR A USUARIO_SALDO

            try {
                $newTableUsuarioSaldo = "usuario_saldo_" . date("Y_m_d") . "";


                $port = 3306;
                $driver = 'mysql';

                $url = "mysql:host=" . $_ENV['DB_HOST_ALTER'] . ";dbname=" . $_ENV['DB_NAME_ALTER'] . ";charset=utf8mb4";

                $conn2 = new PDO($url, $_ENV['DB_USER_ALTER'], $_ENV['DB_PASSWORD_ALTER'], [
                    PDO::ATTR_EMULATE_PREPARES => false,
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
                ]);


                $conn2->exec("set names utf8");


                $sql2 = "rename table usuario_saldo to usuario_saldo_" . date("Y_m_d", strtotime('-2 days')) . ";";

                $conn2->exec($sql2);

                $sql2 = "rename table usuario_saldo_" . date("Y_m_d", strtotime('-1 days')) . " to usuario_saldo;";

                $conn2->exec($sql2);

                $sql2 = "
create table " . $newTableUsuarioSaldo . "
(
	usuariosaldo_id bigint unsigned auto_increment comment 'Autonumerico',
	usuario_id bigint unsigned comment 'Id del usuario',
	mandante bigint unsigned default 0 not null comment 'Mandante',
	fecha varchar(10) default '' not null comment 'Periodo',
	saldo_recarga double default 0 not null comment 'Saldo de recargas',
	saldo_apuestas double default 0 not null comment 'Saldo apostado',
	saldo_premios double default 0 not null comment 'Saldo premios',
	saldo_notaret_pagadas double default 0 not null comment 'Saldo notas de retiro pagadas',
	saldo_notaret_pend double default 0 not null comment 'Saldo notas de retiro pendientes',
	saldo_ajustes_entrada double default 0 not null comment 'Saldo ajustes por entrada',
	saldo_ajustes_salida double default 0 not null comment 'Saldo ajustes por salida',
	saldo_inicial double default 0 not null comment 'Saldo inicial',
	saldo_final double default 0 not null comment 'Saldo final',
	saldo_bono double default 0 not null comment 'Saldo de bonos',
	saldo_creditos_inicial double default 0 null,
	saldo_creditos_base_inicial double default 0 null,
	saldo_creditos_final double default 0 null,
	saldo_creditos_base_final double default 0 null,
	saldo_notaret_creadas double default 0 null,
	saldo_apuestas_casino double default 0 null,
	saldo_premios_casino double default 0 null,
	saldo_notaret_eliminadas double default 0 null,
	saldo_bono_free_ganado double default 0 null,
	saldo_bono_casino_free_ganado double default 0 null,
	saldo_bono_casino_vivo double default 0 null,
	saldo_bono_casino_vivo_free_ganado double default 0 null,
	saldo_bono_virtual double default 0 null,
	saldo_bono_virtual_free_ganado double default 0 null,
	billetera_id int default 0 null,
	saldo_apuestas_casino_vivo double default 0 null,
	saldo_impuestos_apuestas_deportivas double default 0 null,
	saldo_impuestos_premios_deportivas double default 0 null,
	saldo_premios_jackpot_casino double default 0 null,
	saldo_premios_jackpot_deportivas double default 0 null,
	saldo_impuestos_depositos double default 0 null,
	saldo_ventas_loteria double default 0 null,
	saldo_rollbacks_loteria double default 0 null,
	saldo_impuestos_apuestas_casino double default 0 null,
	primary key (usuariosaldo_id)
);
		
		";
                $conn2->exec($sql2);

                $sql2 = "create index idx_" . $newTableUsuarioSaldo . "_mandante
	on " . $newTableUsuarioSaldo . " (mandante);
		
		";

                $conn2->exec($sql2);


                $sql2 = "

create index " . $newTableUsuarioSaldo . "_fecha_index
	on " . $newTableUsuarioSaldo . " (fecha);
		
		";

                $conn2->exec($sql2);

                $sql2 = "
create index " . $newTableUsuarioSaldo . "_usuario_id_index
	on " . $newTableUsuarioSaldo . " (usuario_id);
		";

                $conn2->exec($sql2);

                $sql2 = "
	alter table " . $newTableUsuarioSaldo . "
	add constraint " . $newTableUsuarioSaldo . "_pk
		unique (usuario_id, fecha,billetera_id);
		
		";

                $conn2->exec($sql2);

                $sql2 = "
create index " . $newTableUsuarioSaldo . "_billetera_id_index
	on " . $newTableUsuarioSaldo . " (billetera_id);
		";

                $conn2->exec($sql2);

                $sql2 = "
create index " . $newTableUsuarioSaldo . "_usuario_id_billetera_id_index
	on " . $newTableUsuarioSaldo . " (usuario_id, billetera_id);
		";

                $conn2->exec($sql2);

                $sql2 = "
create index " . $newTableUsuarioSaldo . "_usuario_id_fecha_billetera_id_index
	on " . $newTableUsuarioSaldo . " (usuario_id, fecha, billetera_id);
		";

                $conn2->exec($sql2);
            } catch (Exception $e) {

            }


        }
    }
}