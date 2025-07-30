<?php

use Backend\dto\UsuarioRestriccion;
use Backend\mysql\UsuarioRestriccionMySqlDAO;

/**
 * Obtiene la acción correspondiente a un valor dado.
 *
 * @param mixed $value El valor para el cual se desea obtener la acción.
 * @return string La acción correspondiente al valor, o una cadena vacía si no se encuentra.
 */
function getAction($value)
{
    $actions = ['1', 'CREATE', '2' => 'UPDATE', '3' => 'DELETE'];
    return isset($actions[$value]) ? $actions[$value] : '';
}


/**
 * Convierte un valor numérico a su tipo correspondiente o busca su clave en un array.
 *
 * @param mixed $value El valor a convertir o buscar.
 * @return string|null El tipo correspondiente al valor o la clave encontrada, o null si no se encuentra.
 */
function getDocType($value)
{
    $types = ['1' => 'C', '2' => 'P', '3' => 'E'];
    return is_numeric($value) ? $types[$value] : array_search($value, $types);
}


$CSV = $params->CSV;
$ID = $params->Id;

/* asigna valores de parámetros a variables para su posterior uso. */
$Email = $params->Email;
$Document = $params->Document;
$DocType = $params->DocType;
$Name = $params->Name;
$Phone = $params->Phone;
$Note = $params->Note;
$Partner = $params->Partner;
$Type = $params->Type;
$Country = $params->Country;
$Action = $params->Action;

try {
    // Verifica si la acción está vacía y lanza una excepción si es inválida
    if (empty($Action)) throw new Exception('Accion invalida', '100000');

    switch (getAction($Action)) {
        case 'CREATE':
            // Verifica si la variable $CSV no está vacía
            if (!empty($CSV)) {
                // Separa el contenido de $CSV utilizando la coma como delimitador y toma el segundo elemento
                $CSV = explode(',', $CSV)[1];
                // Decodifica el contenido base64 y separa las restricciones por líneas
                $restrictions = explode("\n", base64_decode($CSV));

                    // Inicializa el objeto UsuarioRestriccionMySqlDAO para realizar operaciones en la base de datos
                    $UsuarioRestriccionMySqlDAO = new UsuarioRestriccionMySqlDAO();
                    // Obtiene la transacción actual
                    $Transaction = $UsuarioRestriccionMySqlDAO->getTransaction();

                    $changes = false; // Indica si hubo cambios al insertar registros

                // Recorre cada restricción en la lista obtenida
                foreach ($restrictions as $key => $value) {
                    // Verifica que el valor no esté vacío y cumpla con el patrón de validación
                    if (!empty($value) && preg_match('/[a-z, A-Z, 0-9]/', $value)) {
                        $data = explode(';', $value);
                        $UsuarioRestriccion = new UsuarioRestriccion();
                        $UsuarioRestriccion->setEmail($data[0]);
                        $UsuarioRestriccion->setTipoDoc(getDocType($data[1]) ?: 'C');
                        $UsuarioRestriccion->setDocumento($data[2]);
                        $UsuarioRestriccion->setNombre($data[3]);
                        $UsuarioRestriccion->setTelefono(str_replace(["\r", "\n"], '', $data[4]));
                        $UsuarioRestriccion->setMandante($Partner);
                        $UsuarioRestriccion->setPaisId($Country);
                        $UsuarioRestriccion->setEstado('A');
                        $UsuarioRestriccion->setClasificadorId($Type);
                        $UsuarioRestriccion->setUsucreaId($_SESSION['usuario']);

                        // Inicializa el objeto UsuarioRestriccionMySqlDAO con la transacción
                        $UsuarioRestriccionMySqlDAO = new UsuarioRestriccionMySqlDAO($Transaction);
                        $UsuarioRestriccionMySqlDAO->insert($UsuarioRestriccion);

                        $changes = true; // Se han realizado cambios
                    }
                }

                // Si se realizaron cambios, se confirma la transacción
                if ($changes === true) $Transaction->commit();
            } else {
                // Crear una nueva instancia de UsuarioRestriccion
                $UsuarioRestriccion = new UsuarioRestriccion();

                // Establecer el correo electrónico del usuario, eliminando espacios en blanco
                $UsuarioRestriccion->setEmail(str_replace(' ', '', $Email));
                $UsuarioRestriccion->setDocumento($Document);
                $UsuarioRestriccion->setTipoDoc(getDocType($DocType));
                $UsuarioRestriccion->setNombre($Name);
                $UsuarioRestriccion->setTelefono($Phone);
                $UsuarioRestriccion->setMandante($Partner);
                $UsuarioRestriccion->setPaisId($Country);
                $UsuarioRestriccion->setEstado('A');
                $UsuarioRestriccion->setClasificadorId($Type);
                $UsuarioRestriccion->setUsucreaId($_SESSION['usuario']);

                $UsuarioRestriccionMySqlDAO = new UsuarioRestriccionMySqlDAO();
                $UsuarioRestriccionMySqlDAO->insert($UsuarioRestriccion);
                $UsuarioRestriccionMySqlDAO->getTransaction()->commit();
            }
            break;

        /**
         * Maneja las operaciones 'UPDATE' y 'DELETE' para el objeto UsuarioRestriccion.
         *
         * En el caso 'UPDATE', se actualizan los campos del objeto UsuarioRestriccion si están disponibles,
         * y se realiza la actualización en la base de datos. Si el estado del usuario es 'A' (activo),
         * se permiten actualizaciones en otros campos.
         *
         * En el caso 'DELETE', se marca el estado del usuario como 'E' (eliminado) y se registra la nota.
         * Solo se permite la eliminación si el estado del usuario es 'A'.
         */
        case 'UPDATE':
            $UsuarioRestriccion = new UsuarioRestriccion($ID);

            if ($UsuarioRestriccion->getEstado() === 'A') {
                if (!empty($Email)) $UsuarioRestriccion->setEmail($Email);
                if (!empty($Document)) $UsuarioRestriccion->setDocumento($Document);
                if (!empty($DocType)) $UsuarioRestriccion->setTipoDoc(getDocType($DocType));
                if (!empty($Name)) $UsuarioRestriccion->setNombre($Name);
                if (!empty($Phone)) $UsuarioRestriccion->setTelefono($Phone);
                if (!empty($Type)) $UsuarioRestriccion->setClasificadorId($Type);
                if ($Partner != '') $UsuarioRestriccion->setMandante($Partner);
                if ($Country != '') $UsuarioRestriccion->setPaisId($Country);
                $UsuarioRestriccion->setUsumodifId($_SESSION['usuario']);

                $UsuarioRestriccionMySqlDAO = new UsuarioRestriccionMySqlDAO();
                $UsuarioRestriccionMySqlDAO->update($UsuarioRestriccion);
                $UsuarioRestriccionMySqlDAO->getTransaction()->commit();
            }
            break;
        case 'DELETE':
            /*Eliminación de una restricción*/
            $UsuarioRestriccion = new UsuarioRestriccion($ID);

            if ($UsuarioRestriccion->getEstado() === 'A') {
                $UsuarioRestriccion->setEstado('E');
                $UsuarioRestriccion->setNota($Note);
                $UsuarioRestriccion->getUsumodifId($_SESSION['usuario']);
                $UsuarioRestriccionMySqlDAO = new UsuarioRestriccionMySqlDAO();
                $UsuarioRestriccionMySqlDAO->update($UsuarioRestriccion);
                $UsuarioRestriccionMySqlDAO->getTransaction()->commit();
            }
            break;
    }

    //Formateo de respuesta exitosa
    $response = [];
    $response['HasError'] = false;
    $response['AlertType'] = 'success';
    $response['AlertMessage'] = '';
    $response['ModelErrors'] = [];
    $response['Data'] = [];
} catch (Exception $ex) {
    //Formateo respuesta fallida
    $response = [];
    $response['HasError'] = true;
    $response['AlertType'] = 'error';
    $response['AlertMessage'] = '';
    $response['ModelErrors'] = [];
    $response['Data'] = [];
}

?>