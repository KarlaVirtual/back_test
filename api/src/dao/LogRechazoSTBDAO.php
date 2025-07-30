<?php
namespace Backend\dao;

use Backend\dto\LogRechazoSTB;

interface LogRechazoSTBDAO {
    /** Función retorna el log de la tabla con el logId correspondiente
     *@param mixed $logId Autoincremental de LogRechazoSTB
     */
    public function load($logId);


    /** Función recibe un objeto LogRechazoSTB, realiza el registro del mismo en la base de datos
     *y retorna el autoincremental correspondiente al registro
     *
     *@param LogRechazoSTB $LogRechazoSTB
     *
     *@return int LogId
     */
    public function insert(LogRechazoSTB $LogRechazoSTB);


    /** Función recibe un objeto LogRechazoSTB existente en base de datos y actualiza sus parámetros
     *@param LogRechazoSTB $LogRechazoSTB
     */
    public function update(LogRechazoSTB $LogRechazoSTB);

}
?>
