<?php

    use Backend\dto\BonoDetalle;
    use Backend\dto\BonoInterno;
    use Backend\dto\UsuarioBono;
    use Backend\mysql\BonoDetalleMySqlDAO;
    use Backend\mysql\BonoInternoMySqlDAO;
    use Backend\mysql\UsuarioBonoMySqlDAO;

/**
 * DuplicateBonus
 * 
 * Duplica un bono existente con nuevas fechas programadas
 *
 * @param object $params {
 *   "Id": int,              // ID del bono a duplicar
 *   "DateProgram": array[{  // Array de fechas programadas
 *     "BeginDate": string,  // Fecha de inicio en formato ISO
 *     "EndDate": string     // Fecha de fin en formato ISO
 *   }]
 * }
 *
 * @return array {
 *   "HasError": boolean,     // Indica si hubo error
 *   "AlertType": string,     // Tipo de alerta (success/danger)
 *   "AlertMessage": string,  // Mensaje descriptivo
 *   "ModelErrors": array,    // Errores del modelo
 *   "Result": array         // Resultado de la operación
 * }
 *
 * @throws Exception        // Errores de procesamiento
 */

    
    // Obtiene el ID del bono a duplicar y las fechas programadas
    $Id = $params->Id;
    $DateProgram = $params->DateProgram;

    if(oldCount($DateProgram) > 0) {
        // Inicializa los objetos necesarios para la duplicación
        $BonoDetalle = new BonoDetalle();
        $UsuarioBono = new UsuarioBono();
        $BonoInterno = new BonoInterno($Id);
        
        $BonoInternoMySqlDAO = new BonoInternoMySqlDAO();
        $Transaction = $BonoInternoMySqlDAO->getTransaction();

        // Itera sobre cada fecha programada para crear duplicados
        foreach($DateProgram as $value) {
            $StartDate = date('Y-m-d H:i:s', strtotime(str_replace('T', ' ', $value->BeginDate)));
            $EndDate = date('Y-m-d H:i:s', strtotime(str_replace('T', ' ', $value->EndDate)));

            // Crea una nueva instancia del bono con los datos del original
            $BonoInternoDuplicate = new BonoInterno();
            $BonoInternoDuplicate->nombre = $BonoInterno->nombre;
            $BonoInternoDuplicate->descripcion = $BonoInterno->descripcion;
            $BonoInternoDuplicate->fechaInicio = $StartDate;
            $BonoInternoDuplicate->fechaFin = $EndDate;
            $BonoInternoDuplicate->tipo = $BonoInterno->tipo;
            $BonoInternoDuplicate->estado = $BonoInterno->estado;
            $BonoInternoDuplicate->usucreaId = 0;
            $BonoInternoDuplicate->usumodifId = 0;

            // Configura los atributos adicionales del bono duplicado
            $BonoInternoDuplicate->mandante = $BonoInterno->mandante;
            $BonoInternoDuplicate->condicional = $BonoInterno->condicional;
            $BonoInternoDuplicate->orden = $BonoInterno->orden;
            $BonoInternoDuplicate->cupoActual = $BonoInterno->cupoActual;
            $BonoInternoDuplicate->cupoMaximo = $BonoInterno->cupoMaximo;
            $BonoInternoDuplicate->codigo = $BonoInterno->codigo;
            $BonoInternoDuplicate->cantidadBonos = $BonoInterno->cantidadBonos;
            $BonoInternoDuplicate->maximoBonos = $BonoInterno->maximoBonos;
            $BonoInternoDuplicate->reglas = $BonoInterno->reglas;
            $BonoInternoDuplicate->publico = $BonoInterno->publico;

            // Inserta el nuevo bono en la base de datos
            $BonoInternoDuplicateMySqlDAO = new BonoInternoMySqlDAO($Transaction);
            $duplicateBonoId = $BonoInternoDuplicateMySqlDAO->insert($BonoInternoDuplicate);

            // Prepara la consulta para obtener los detalles del bono original
            $rules = [];
            array_push($rules, ['field' => 'bono_detalle.bono_id', 'data' => $Id, 'op' => 'eq']);
            $filter = json_encode(['rules' => $rules, 'groupOp' => 'AND']);
            $queryBonoDetail = (string)$BonoDetalle->getBonoDetallesCustom('bono_detalle.*', 'bono_detalle.bonodetalle_id', 'ASC', 0, 10000, $filter, true);
            $queryBonoDetail = json_decode($queryBonoDetail, true);
            
            // Duplica los detalles del bono original al nuevo bono
            foreach ($queryBonoDetail['data'] as $value) {
                $BonoDetalle = new BonoDetalle();
                $BonoDetalle->bonoId = $duplicateBonoId;
                $BonoDetalle->tipo = $value['bono_detalle.tipo'];
                $BonoDetalle->moneda = $value['bono_detalle.moneda'];
                $BonoDetalle->valor = $value['bono_detalle.valor'];
                $BonoDetalle->usucreaId = 0;
                $BonoDetalle->usumodifId = 0;
            
                $BonoDetalleMySlqDAO = new BonoDetalleMySqlDAO($Transaction);
                $BonoDetalleMySlqDAO->insert($BonoDetalle);
            }

            // Bloque condicional desactivado para duplicar usuarios del bono
            if(false) {
                $rules = [];
                array_push($rules, ['field' => 'usuario_bono.bono_id', 'data' => $Id, 'op' => 'eq']);
                $filter = json_encode(['rules' => $rules, 'groupOp' => 'AND']);
                $queryUserBono = (string)$UsuarioBono->getUsuarioBonosCustom('usuario_bono.*', 'usuario_bono.usubono_id', 'ASC', 0, 10000, $filter, true);
                $queryUserBono = json_decode($queryUserBono, true);

                // Duplica los usuarios asociados al bono original
                foreach ($queryUserBono['data'] as $value) {
                    $UsuarioBono = new UsuarioBono();
                    $UsuarioBono->usuarioId = $value['usuario_bono.usuario_id'];
                    $UsuarioBono->bonoId = $duplicateBonoId;
                    $UsuarioBono->valor = $value['usuario_bono.valor'];
                    $UsuarioBono->valorBono = $value['usuario_bono.valor_bono'];
                    $UsuarioBono->valorBase = $value['usuario_bono.valor_base'];
                    $UsuarioBono->estado = $value['usuario_bono.estado'];
                    $UsuarioBono->errorId = $value['usuario_bono.error_id'];
                    $UsuarioBono->idExterno = $value['usuario_bono.id_externo'];
                    $UsuarioBono->mandante = $value['usuario_bono.mandante'];
                    $UsuarioBono->usucreaId = 0;
                    $UsuarioBono->usumodifId = 0;
                    $UsuarioBono->apostado = $value['usuario_bono.apostado'];
                    $UsuarioBono->rollowerRequerido = $value['usuario_bono.rollower_requerido'];
                    $UsuarioBono->codigo = $value['usuario_bono.codigo'];

                    $UsuarioBonoMySqlDAO = new UsuarioBonoMySqlDAO($Transaction);
                    $UsuarioBonoMySqlDAO->insert($UsuarioBono);
                }
            }
        }

        // Confirma la transacción y prepara la respuesta exitosa
        $Transaction->commit();

        $response['HasError'] = false;
        $response['AlertType'] = 'success';
        $response['AlertMessage'] = '';
        $response['idBonus'] = $duplicateBonoId;
        $response['ModelErrors'] = [];
        $response['Result'] = [];
    } else {
        // Prepara la respuesta de error si no hay fechas programadas
        $response['HasError'] = true;
        $response['AlertType'] = 'danger';
        $response['AlertMessage'] = '';
        $response['idBonus'] = 0;
        $response['ModelErrors'] = [];
        $response['Result'] = [];
    }

?>