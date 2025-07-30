<?php
namespace Backend\sql;

use Exception;

/**
 * Clase 'Transaction'
 *
 * Esta clase provee un objeto de transacción a la base de datos
 *
 * Ejemplo de uso:
 * $Transaction = new Transaction();
 *
 *
 * @package ninguno
 * @author Daniel Tamayo
 * @version 1.0
 * @access public
 * @date: 27.11.2007
 *
 */
class Transaction
{


    /**
     * Representación del objeto transacción
     *
     * @var objeto
     * @access private
     * @static
     */
    private static $transactions;

    /**
     * Información para enviar a CRM
     * @var string[]
     * @access private
     */
    private $crmInfo = [
        'movementID' => '',
        'abbreviated' => ''
    ];

    /**
     * Representación del objeto conexión
     *
     * @var objeto
     * @access private
     */
    private $connection;

    /**
     * Valor booleano para verificar si la conexión a la
     * base de datos está abierta
     *
     * @var boolean
     * @access private
     */
    private $isconnected;

    /**
     * Mensaje de error
     *
     * @var String
     * @access private
     */
    private $error;

    /**
     * Constructor de clase
     *
     * @access public
     * @see no
     * @since no
     * @deprecated no
     */
    public function __construct()
    {
        try {


            $Connection = new Connection();

            $this->connection = $Connection;

            $this->isconnected = true;

        } catch (\PDOException $exception) {

            $this->isconnected = false;
            $this->error = $exception->getMessage();

        }
    }

    /**
     * Método para obtener el mensaje de error al hacer la conexión
     *
     * @return String $ mensaje de error
     */
    public function getError()
    {
        return $this->error;
    }

    /**
     * Método para verificar si la conexión está abierta
     *
     * @return boolean $ verdadero si la conexión existe
     */
    public function isIsconnected()
    {
        return $this->isconnected;
    }

    /**
     * Constructor de clase
     *
     * @access public
     * @see no
     * @since no
     * @deprecated no
     */
    public function Transaction()
    {

        $this->connection = new Connection();

        $this->connection->beginTransaction();

        /* if (!Transaction::$transactions) {
             Transaction::$transactions = new ArrayList();
         }
         Transaction::$transactions->add($this);
         $this->connection->executeQuery('BEGIN');*/
    }


    /**
     * Verifica si todos los elementos de un array cumplen con una función dada.
     *
     * @param array $array El array a verificar.
     * @param callable $fn La función a aplicar a cada elemento del array.
     * @return bool Verdadero si todos los elementos cumplen con la función, falso en caso contrario.
     */
    private function arrayEvery(array $array, callable $fn)
    {
        $result = true;
        foreach ($array as $value) {
            if (!$fn($value)) {
                $result = false;
                break;
            }
        }

        return $result;
    }


/**
     * Envía información al CRM si todos los elementos de crmInfo están completos.
     *
     * @access private
     * @return void
     */
    private function sendCRM()
    {
        if ($this->arrayEvery($this->crmInfo, function ($item) {
            return $item !== '';
        })) {
            $abbreviated = $this->crmInfo['abbreviated'];
            $movementID = $this->crmInfo['movementID'];

            $isMobile = '2';
            if (preg_match('/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino/i', $useragent) || preg_match('/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i', substr($useragent, 0, 4))) {
                $isMobile = '1';
            }

            exec('php -f ' . __DIR__ . "/../integrations/crm/AgregarCrm.php '' {$abbreviated} {$movementID} '' {$isMobile} > /dev/null &");
        }
    }

    /**
     * Método para extraer la información de CRM
     *
     * @param string $query Consulta SQL
     * @param string $abbreviated Abreviatura de la tabla
     * @param string $searchColumn Columna a buscar
     * @param string $movementID ID del movimiento
     * @access private
     * @return void
     */
    private function extratCRMInfo($query, $abbreviated, $searchColumn, $movementID = '')
    {
        $updateMatches = [];
        if (empty($movementID)) {
            preg_match("/($searchColumn)=([\"|\']?[0-9]+[\"|\']?)/", str_replace(' ', '', $query), $updateMatches);
            if (oldCount($updateMatches) !== 3) throw new Exception('Error al encontrar los datos', 300001);
        }

        $this->crmInfo['abbreviated'] = $abbreviated;
        $this->crmInfo['movementID'] = empty($movementID) ? str_replace(["'", '"'], '', end($updateMatches)) : $movementID;
    }

    /**
     * Método para verificar consultas vinculadas a CRM
     *
     * @param string $query Consulta SQL
     * @param string $movementID ID del movimiento
     * @access public
     * @return void
     */
    public function searchCRMQuery($query, $movementID = '')
    {
        try {
            if (preg_match("/(?i)(insert|update)(?:\s+\w+)*\s*(usuario_recarga)/", $query, $matches) > 0 && oldCount($matches) > 0) {
                $this->extratCRMInfo($query, 'DEPOSITOCRM', 'recarga_id', $movementID);

            }

            if (preg_match("/(?i)(insert|update)(?:\s+\w+)*\s*(transaccion_producto)/", $query, $matches) > 0 && oldCount($matches) > 0) {
                $this->extratCRMInfo($query, 'SOLICITUDDEPOSITOCRM', 'transproducto_id', $movementID);

            }

            if (preg_match("/(?i)(insert|update)(?:\s+\w+)*\s*(it_transaccion)/", $query, $matches) > 0 && oldCount($matches) > 0) {
                $this->extratCRMInfo($query, 'BETSPORTSBOOKCRM', 'it_cuentatrans_id', $movementID);

            }

            if (preg_match("/(?i)(insert|update)(?:\s+\w+)*\s*(transaccion_api)/", $query, $matches) > 0 && oldCount($matches) > 0) {
                $this->extratCRMInfo($query, 'BETCASINOCRM', 'transapi_id', $movementID);

            }

            if (preg_match("/(?i)(insert|update)(?:\s+\w+)*\s*(cuenta_cobro)/", $query, $matches) > 0 && oldCount($matches) > 0) {
                $this->extratCRMInfo($query, 'RETIROCREADOCRM', 'cuenta_id', $movementID);

            }

            if (preg_match("/(?i)(insert|update)(?:\s+\w+)*\s*(usuario_log)/", $query, $matches) > 0 && oldCount($matches) > 0) {
                $this->extratCRMInfo($query, 'LOGINCRM', 'usuariolog_id', $movementID);

            }

            if (preg_match("/(?i)(insert|update)(?:\s+\w+)*\s*(usuario_token)/", $query, $matches) > 0 && oldCount($matches) > 0) {
                $this->extratCRMInfo($query, 'LOGOUTCRM', 'usutoken_id', $movementID);

            }

            if (preg_match("/(?i)(insert|update)(?:\s+\w+)*\s*(usuario_bono)/", $query, $matches) > 0 && oldCount($matches) > 0) {
                $this->extratCRMInfo($query, 'BONOCRM', 'usubono_id', $movementID);

            }

            if (preg_match("/(?i)(insert|update)(?:\s+\w+)*\s*(bono_log)/", $query, $matches) > 0 && oldCount($matches) > 0) {
                $this->extratCRMInfo($query, 'REDEEMEDBONUSCRM', 'bonolog_id', $movementID);

            }

            if (preg_match("/(?i)(insert)(?:\s+\w+)*\s*(registro)/", $query, $matches) > 0 && oldCount($matches) > 0) {
                $this->extratCRMInfo($query, 'REGISTROCRM', 'registro_id', $movementID);

            }

            if (preg_match("/(?i)(insert|update)(?:\s+\w+)*\s*(usuario_lealtad)/", $query, $matches) > 0 && oldCount($matches) > 0) {
                $this->extratCRMInfo($query, 'REDEEMGIFTCRM', 'usulealtad_id', $movementID);

            }

            if (preg_match("/(?i)(insert)(?:\s+\w+)*\s*(saldo_usuonline_ajuste)/", $query, $matches) > 0 && oldCount($matches) > 0) {
                $this->extratCRMInfo($query, 'SALDOAJUSTECRM', 'ajuste_id', $movementID);

            }

            if (preg_match("/(?i)(insert|update)(?:\s+\w+)*\s*(usuario_token)/", $query, $matches) > 0 && oldCount($matches) > 0) {
                $this->extratCRMInfo($query, 'OPENINGGAMECRM', 'usutoken_id', $movementID);

            }

            if (preg_match("/(?i)(insert|update)(?:\s+\w+)*\s*(usuario_log)/", $query, $matches) > 0 && oldCount($matches) > 0) {
                $this->extratCRMInfo($query, 'CHANGEPASSWORDCRM', 'usuariolog_id', $movementID);

            }

            if (preg_match("/(?i)(insert|update)(?:\s+\w+)*\s*(usuario_log2)/", $query, $matches) > 0 && oldCount($matches) > 0) {
                $this->extratCRMInfo($query, 'UPDATEINFOCRM', 'usuariolog2_id', $movementID);

            }

            if (preg_match("/(?i)(insert)(?:\s+\w+)*\s*(usuario_torneo)/", $query, $matches) > 0 && oldCount($matches) > 0) {
                $this->extratCRMInfo($query, 'SUBTORURNAMENTCRM', 'usutorneo_id', $movementID);

            }

            if (preg_match("/(?i)(insert)(?:\s+\w+)*\s*(usuario_sorteo)/", $query, $matches) > 0 && oldCount($matches) > 0) {
                $this->extratCRMInfo($query, 'SUBRAFFLECRM', 'ususorteo_id', $movementID);

            }

            if (preg_match("/(?i)(insert)(?:\s+\w+)*\s*(preusuario_sorteo)/", $query, $matches) > 0 && oldCount($matches) > 0) {
                $this->extratCRMInfo($query, 'SUMSTICKERCRM', 'preususorteo_id', $movementID);

            }

            if (preg_match("/(?i)(insert)(?:\s+\w+)*\s*(preusuario_sorteo)/", $query, $matches) > 0 && oldCount($matches) > 0) {
                $this->extratCRMInfo($query, 'SUMJACKPOTCRM', 'preususorteo_id', $movementID);

            }
        } catch (Exception $ex) {
        }
    }

    /**
     * Método para finalizar la transacción y guardar los cambios
     */
    public function commit()
    {
        // $this->connection->executeQuery('COMMIT');
        // $this->connection->close();
        //Transaction::$transactions->removeLast();
        if (($this->connection->isBeginTransaction == null || $this->connection->isBeginTransaction == 2) || $this->connection->inTransaction()) {
            $this->connection->commit();
            if ($_ENV['enabledCRMQuery']) {
                $this->sendCRM();
            }
        }
    }

    /**
     * Método para finalizar la transacción y eliminar los cambios
     */
    public function rollback()
    {
        /*$this->connection->executeQuery('ROLLBACK');
        $this->connection->close();
        Transaction::$transactions->removeLast();*/

        $this->connection->rollBack();

    }

    /**
     * Método para obtener la conexión de la transacción actual
     *
     * @return Objeto $ conexión a la base de datos
     */
    public function getConnection()
    {
        return $this->connection;
    }


/**
     * Establece la conexión de la transacción actual.
     *
     * @param objeto $conn La nueva conexión a la base de datos.
     * @access public
     */
    public function setConnection($conn)
    {
        $this->connection = $conn;
    }
}

?>
