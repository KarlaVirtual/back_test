<?php

    use Backend\dto\BonoInterno;
    use Backend\dto\Clasificador;
    use Backend\dto\CuentaCobro;
    use Backend\dto\GeneralLog;
    use Backend\dto\MandanteDetalle;
    use Backend\dto\Usuario;
use Backend\dto\UsuarioHistorial;
use Backend\mysql\CuentaCobroMySqlDAO;
    use Backend\mysql\GeneralLogMySqlDAO;
use Backend\mysql\UsuarioHistorialMySqlDAO;

    for ($i=0; $i <= 10; $i++) { 
        $BonoInterno = new BonoInterno();
        $CuentaCobroMySqlDAO = new CuentaCobroMySqlDAO();
        $Transaction = $CuentaCobroMySqlDAO->getTransaction();

        $sqlInternalProcess = 'SELECT fecha_ultima from proceso_interno2 WHERE tipo = "CODEOPTWITHDRAW" ORDER BY procesointerno2_id ASC LIMIT 1';
        $queryInteralProcess = json_encode($BonoInterno->execQuery('', $sqlInternalProcess));
        $queryInteralProcess = json_decode($queryInteralProcess, true);
        $endDate = '';

        if(oldCount($queryInteralProcess) === 0) {
            $currentDate = date('Y-m-d H:i:s');
            $sqlInsert = "INSERT INTO proceso_interno2 (tipo, fecha_ultima) VALUES ('CODEOPTWITHDRAW', '{$currentDate}')";
            $BonoInterno->execQuery($Transaction, $sqlInsert);
            $Transaction->commit();
            $endDate = $currentDate;
        } else if(
            date('Y-m-d H:i:s', strtotime($queryInteralProcess[0]['proceso_interno2.fecha_ultima'] . '+10 seconds')) >
            date('Y-m-d H:i:s')
        ) die;
        else {
            $currentDate = date('Y-m-d H:i:s',strtotime($queryInteralProcess[0]['proceso_interno2.fecha_ultima'] . '+10 seconds'));
            $sqlUpdateInternalProcess = "UPDATE proceso_interno2 SET fecha_ultima = '{$currentDate}' WHERE tipo = 'CODEOPTWITHDRAW'";
            $BonoInterno->execUpdate($Transaction, $sqlUpdateInternalProcess);
            $Transaction->commit();

            $endDate = $queryInteralProcess[0]['proceso_interno2.fecha_ultima'];
        }

        $Clasificador = new Clasificador('', 'MAXTIMEOTPCODE');

        $rules = [];
        array_push($rules, ['field' => 'mandante_detalle.estado', 'data' => 'A', 'op' => 'eq']);
        array_push($rules, ['field' => 'mandante_detalle.tipo', 'data' => $Clasificador->getClasificadorId(), 'op' => 'eq']);

        $filters = json_encode(['rules' => $rules, 'groupOp' => 'AND']);

        $UsuarioMandante = new MandanteDetalle();
        $queryPartnerUser = (string)$UsuarioMandante->getMandanteDetallesCustom('mandante_detalle.valor, mandante_detalle.mandante, mandante_detalle.pais_id', 'mandante_detalle.manddetalle_id', 'ASC', 0, 100000, $filters, true);
        $queryPartnerUser = json_decode($queryPartnerUser, true);

        foreach ($queryPartnerUser['data'] as $key => $value) {
            $expireTime = $value['mandante_detalle.valor'];
            $expireTime = intval($expireTime/1000);

            $rules = [];
            array_push($rules, ['field' => 'cuenta_cobro.estado', 'data' => 'O', 'op' => 'eq']);
            array_push($rules, ['field' => 'usuario.mandante', 'data' => $value['mandante_detalle.mandante'], 'op' => 'eq']);
            array_push($rules, ['field' => 'usuario.pais_id', 'data' => $value['mandante_detalle.pais_id'], 'op' => 'eq']);
            array_push($rules, ['field' => 'cuenta_cobro.fecha_crea', 'data' => date('Y-m-d H:i:s', strtotime($endDate . ' -' . $expireTime . ' seconds')), 'op' => 'lt']);
            array_push($rules, ['field' => 'cuenta_cobro.fecha_crea', 'data' => date('Y-m-d H:i:s', strtotime($endDate . ' - ' . ($expireTime + 10) . ' seconds')), 'op' => 'ge']);

            $filters = json_encode(['rules' => $rules, 'groupOp' => 'AND']);

            $CuentaCobro = new CuentaCobro();
            $queryWithdraw = (string)$CuentaCobro->getCuentasCobroCustom('cuenta_cobro.cuenta_id, cuenta_cobro.usuario_id, cuenta_cobro.valor, cuenta_cobro.fecha_crea', 'cuenta_cobro.cuenta_id', 'ASC', 0, 1000000, $filters, true);
            $queryWithdraw = json_decode($queryWithdraw, true);

            foreach ($queryWithdraw['data'] as $key => $value) {
                $CuentaCobro = new CuentaCobro($value['cuenta_cobro.cuenta_id']);

                $CuentaCobroMySqlDAO = new CuentaCobroMySqlDAO();
                $Transaction = $CuentaCobroMySqlDAO->getTransaction();

                if($CuentaCobro->estado === 'O') {
                    $CuentaCobro->estado = 'Z';

                    $CuentaCobro->setFechaAccion(date('Y-m-d H:i:s'));

                    $CuentaCobroMySqlDAO = new CuentaCobroMySqlDAO($Transaction);
                    $rowAffected = $CuentaCobroMySqlDAO->update($CuentaCobro, ' AND estado = "O"');

                    if($rowAffected == 0) {
                        $Transaction->rollback();
                        continue;
                    }

                    $Usuario = new Usuario($value['cuenta_cobro.usuario_id']);
                    $Usuario->creditWin2($value['cuenta_cobro.valor'], $Transaction, true);

                    $GeneralLog = new GeneralLog();
                    $GeneralLog->setUsuarioId($Usuario->usuarioId);
                    $GeneralLog->setUsuarioIp(0);
                    $GeneralLog->setUsuariosolicitaId(0);
                    $GeneralLog->setUsuariosolicitaIp(0);
                    $GeneralLog->setTipo('CHANGESTATE');
                    $GeneralLog->setEstado('A');
                    $GeneralLog->setValorAntes('O');
                    $GeneralLog->setValorDespues('Z');
                    $GeneralLog->setUsucreaId(0);
                    $GeneralLog->setUsumodifId(0);
                    $GeneralLog->setDispositivo('');
                    $GeneralLog->setSoperativo('');
                    $GeneralLog->setSversion('');
                    $GeneralLog->setTabla('cuenta_cobro');
                    $GeneralLog->setCampo('estado');
                    $GeneralLog->setExternoId(0);
                    $GeneralLog->setMandante($Usuario->mandante);
                    $GeneralLog->setExplicacion('Nota de retiro cancelada por expiracion de codigo OPT');
        
                    $GeneralLogMySqlDAO = new GeneralLogMySqlDAO($Transaction);
                    $GeneralLogMySqlDAO->insert($GeneralLog);

                    $UsuarioHistorial = new UsuarioHistorial();
                    $UsuarioHistorial->setUsuarioId($value['cuenta_cobro.usuario_id']);
                    $UsuarioHistorial->setDescripcion('Nota de retiro expirada por codigo OTP');
                    $UsuarioHistorial->setMovimiento('E');
                    $UsuarioHistorial->setUsucreaId(0);
                    $UsuarioHistorial->setUsumodifId(0);
                    $UsuarioHistorial->setTipo(40);
                    $UsuarioHistorial->setValor($value['cuenta_cobro.valor']);
                    $UsuarioHistorial->setExternoId($value['cuenta_cobro.cuenta_id']);

                    $UsuarioHistorialMySqlDAO = new UsuarioHistorialMySqlDAO($Transaction);
                    $UsuarioHistorialMySqlDAO->insert($UsuarioHistorial);

                    $Transaction->commit();
                }
            }
        }
        
    }
?>