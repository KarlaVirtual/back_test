<?php

use Backend\sql\SqlQuery;
use Backend\sql\QueryExecutor;
use Backend\mysql\ProductoMandanteMySqlDAO;
use Backend\dto\ProductoMandante;

/**
 * Servicio para habilitar/deshabilitar productos asociados a socios comerciales
 * 
 * @param object $params Parámetros de entrada
 * @param string $params->Note Nota o comentario del cambio
 * @param string $params->Partner ID del socio comercial
 * @param string $params->CountrySelect País seleccionado
 * @param string $params->ExcludedProductsList Lista de productos a deshabilitar
 * @param string $params->IncludedProductsList Lista de productos a habilitar
 * @param string $params->Note Nota o comentario del cambio
 *
 * 
 * @return object Respuesta del servicio
 * @return boolean $response->HasError Indica si hubo error
 * @return string $response->AlertType Tipo de alerta (success/error)
 * @return string $response->AlertMessage Mensaje descriptivo
 * @return array $response->ModelErrors Errores del modelo
 * @return array $response->Data Datos adicionales
 * @throws Exception Si ocurre un error durante el proceso
 * @throw Exception Si la nota está vacía
 */



/* asigna valores de $params a variables y determina el estado de habilitación. */
$Note = $params->Note;
$Partner = $params->Partner;
$CountrySelect = $params->CountrySelect;
$ExcludedProductsList = $params->ExcludedProductsList;
$IncludedProductsList = $params->IncludedProductsList;
$Enable = (!empty($params->ExcludedProductsList)) ? 'I' : 'A' ;
$ProductsId =($IncludedProductsList != '') ? $IncludedProductsList : $ExcludedProductsList;
$ProductsId = explode(',',$ProductsId);

if($Note == '' || $Note == null) { /*Se verifica si la nota o comentario es diferente de vacio o diferente de null*/
    throw new exception("La observacion es obligatoria", 300161); /*En caso de que el comentario este vacio se lanza la excepcion*/
}

if($Partner != '' && !empty($CountrySelect) && !empty($ProductsId) && !empty($Enable) ) {

    foreach ($ProductsId as $ProductId){
        try {

            /* Crea una instancia de ProductoMandante con parámetros específicos: ID, socio y país. */
            $ProductoMandate = new ProductoMandante($ProductId,$Partner,'',$CountrySelect);
            if ($Enable != $ProductoMandate->habilitacion)
            {

                /* Asignación de variables para realizar un cambio de producto en una base de datos. */
                $userId = $_SESSION['usuario2'];
                $dirIp = $_SESSION['dir_ip'];
                $type = 'CHANGEPRODUCT';
                $device = $Global_dispositivo;
                $field = 'habilitacion';
                $table = 'producto_mandante';

                /* Inserta un registro de cambios en la tabla general_log para auditoría. */
                $beforeValue = $ProductoMandate->habilitacion;
                $afterValue = $Enable;

                $dirIp = explode(",", $dirIp)[0];
                $sqlGeneralLog = 'INSERT general_log (usuario_id, usuario_ip, usuariosolicita_id, usuariosolicita_ip, usuarioaprobar_id, usuarioaprobar_ip, tipo, valor_antes, valor_despues, usucrea_id, usumodif_id, estado, dispositivo, externo_id, campo, tabla, explicacion, mandante) VALUES';
                $sqlValuesGeneralLog = " ({$userId}, '{$dirIp}', {$userId}, '{$dirIp}', 0, '', '{$type}', '{$beforeValue}', '{$afterValue}', 0, 0, 'A', '{$device}',  '{$ProductoMandate->prodmandanteId}', '{$field}', '{$table}', '{$Note}', {$Partner}),";
                $sqlGeneralLog .= str_replace('$1', $ProductId, $sqlValuesGeneralLog);

                /* actualiza un producto en la base de datos y obtiene la transacción. */
                $sqlGeneralLog = rtrim($sqlGeneralLog, ',');

                $ProductoMandate->setHabilitacion($Enable);
                $ProductoMandanteMySqlDAO = new ProductoMandanteMySqlDAO();
                $ProductoMandanteMySqlDAO->update($ProductoMandate);
                $Transaction = $ProductoMandanteMySqlDAO->getTransaction();

                /* ejecuta una consulta SQL y confirma la transacción. */
                $slqQuery = new SqlQuery($sqlGeneralLog);
                QueryExecutor::executeInsert($Transaction, $slqQuery);

                $Transaction->commit();
            }
        }catch (Exception $e){
            if ($e->getCode() == 27)
            {

                /* Asigna variables de sesión y configuración para una inserción de producto en la base de datos. */
                $userId = $_SESSION['usuario2'];
                $dirIp = $_SESSION['dir_ip'];
                $type = 'INSERTPRODUCT';
                $device = $Global_dispositivo;
                $field = 'habilitacion';
                $table = 'producto_mandante';

                /* Se establece un nuevo objeto con atributos de habilitación y producto. */
                $beforeValue = $ProductoMandate->habilitacion;
                $afterValue = $Enable;

                $ProductoMandate = new ProductoMandante();

                $ProductoMandate->setProductoId($ProductId);
                
                /* Se configuran propiedades de un objeto ProductoMandate con valores específicos. */
                $ProductoMandate->setMandante($Partner);
                $ProductoMandate->setEstado('I');
                $ProductoMandate->setVerifica('I');
                $ProductoMandate->setFiltroPais('I');
                $ProductoMandate->setUsuCreaId($_SESSION['usuario2']);
                $ProductoMandate->setUsuModifId($_SESSION['usuario2']);
                
                /* Establece propiedades para un objeto ProductoMandate, configurando límites y orden. */
                $ProductoMandate->setMax(0);
                $ProductoMandate->setMin(0);
                $ProductoMandate->setTiempoProcesamiento('');
                $ProductoMandate->setOrden(100000);
                $ProductoMandate->setNumFila(1);
                $ProductoMandate->setNumColumn(1);
                
                /* Se establece un producto con propiedades y se inserta en la base de datos. */
                $ProductoMandate->setOrdenDestacado(0);
                $ProductoMandate->setPais($CountrySelect);
                $ProductoMandate->setHabilitacion($Enable);

                $ProductoMandanteMySqlDAO = new ProductoMandanteMySqlDAO();
                $ProductoMandanteMySqlDAO->insert($ProductoMandate);
                $dirIp = explode(",", $dirIp)[0];


                /* Inserta datos en el registro general de usuarios en una base de datos. */
                $sqlGeneralLog = 'INSERT general_log (usuario_id, usuario_ip, usuariosolicita_id, usuariosolicita_ip, usuarioaprobar_id, usuarioaprobar_ip, tipo, valor_antes, valor_despues, usucrea_id, usumodif_id, estado, dispositivo, externo_id, campo, tabla, explicacion, mandante) VALUES';
                $sqlValuesGeneralLog = " ({$userId}, '{$dirIp}', {$userId}, '{($dirIp)}', 0, '', '{$type}', '{$beforeValue}', '{$afterValue}', 0, 0, 'A', '{$device}', '{$ProductoMandate->prodmandanteId}', '{$field}', '{$table}', '{$Note}', {$Partner}),";
                $sqlGeneralLog .= str_replace('$1', $ProductId, $sqlValuesGeneralLog);
                $sqlGeneralLog = rtrim($sqlGeneralLog, ',');

                $Transaction = $ProductoMandanteMySqlDAO->getTransaction();

                /* Ejecuta una consulta SQL e inserta resultados en una transacción. */
                $slqQuery = new SqlQuery($sqlGeneralLog);
                QueryExecutor::executeInsert($Transaction, $slqQuery);
                $Transaction->commit();
            }

        }
    }

    /* Inicializa un array de respuesta sin errores y con mensaje de éxito. */
    $response['HasError'] = false;
    $response['AlertType'] = 'success';
    $response['AlertMessage'] = '';
    $response['ModelErrors'] = [];
    $response['Data'] = [];
}else {
    /* maneja errores, configurando la respuesta con detalles de la alerta. */

    $response['HasError'] = true;
    $response['AlertType'] = 'error';
    $response['AlertMessage'] = '';
    $response['ModelErrors'] = [];
    $response['Data'] = [];
}

?>