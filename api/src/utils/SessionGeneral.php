<?php
namespace Backend\utils;


use Exception;
use PDO;
use PDOException;

/**
 * Clase para manejar las sesiones de los usuarios
 *
 *@author Desconocido
 *@version 1.0
 *@package No aplica
 *@category No aplica
 *@since Desconocido
 */
class SessionGeneral {
    /**
     *Conexión con el método de persistencia
     * @var $connection
     */
    private $connection;

    /**
     *Método de cifrado
     * @var $cryptMethod
     */
    private $cryptMethod = 'aes-256-cbc';
    // private $salt = 'Er2teVS37HGvWC1pBMrTyxa5ipuGP3bENfETn31ER16mFRG6m5mbbd5MP8f5Friu';

    
    /**
     * Constructor de la clase SessionGeneral.
     * 
     * Este constructor configura los controladores personalizados para el manejo de sesiones
     * utilizando `session_set_save_handler`. Los métodos personalizados definidos en esta clase
     * se asignan para manejar las operaciones de apertura, cierre, lectura, escritura, destrucción
     * y recolección de basura de las sesiones.
     * 
     * Además, registra una función de cierre de sesión (`session_write_close`) que se ejecutará
     * automáticamente al finalizar el script, asegurando que los datos de la sesión se escriban
     * correctamente antes de que el script termine.
     */
    public function __construct() {
        session_set_save_handler([$this, 'open'], [$this, 'close'], [$this, 'read'], [$this, 'write'], [$this, 'destroy'], [$this, 'gc']);
        register_shutdown_function('session_write_close');
    }

    /**
     * Establece una conexión a la base de datos utilizando PDO.
     *
     * @return bool Devuelve true si la conexión se establece correctamente, 
     *              de lo contrario devuelve false.
     *
     * @throws PDOException Si ocurre un error durante la conexión a la base de datos.
     *
     * Variables de entorno requeridas:
     * - DB_NAME: Nombre de la base de datos.
     * - DB_USER: Usuario de la base de datos.
     * - DB_PASSWORD: Contraseña del usuario de la base de datos.
     * - DB_HOST: Host del servidor de la base de datos.
     */
    private function DBconnect() {
        try {

            $database = $_ENV['DB_NAME'];
            $user = $_ENV['DB_USER'];
            $password = $_ENV['DB_PASSWORD'];
            $host=$_ENV['DB_HOST'];

            $url = "mysql:host=".$host.";dbname={$database};charset=utf8mb4";
            // $mysqli = new PDO("mysql:unix_socket=/tmp/proxysql.sock;dbname=" . $name, $user, $pass);
            $conn = new PDO($url, $user, $password, [
                PDO::ATTR_EMULATE_PREPARES => false,
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
            ]);

            $this->connection = $conn;
            return true;
        } catch (PDOException $ex) {  }

        return false;
    }

    /**
     * Abre una conexión a la base de datos para el manejo de sesiones.
     *
     * @return bool Devuelve true si la conexión se establece correctamente, false en caso contrario.
     */
    public function open() {
        try{
            return $this->DBconnect();
        }catch (Exception $e){
            return false;
        }
    }

    /**
     * Inicia una nueva sesión o reanuda una sesión existente.
     */
    public function iniciar_sesion() {
        session_start();
    }

    /**
     * Inicia una nueva sesión o reanuda una sesión existente.
     */
    public function inicio_sesion() {
        session_start();
    }

    /**
     * Lee los datos de sesión asociados a un ID específico desde la base de datos.
     *
     * @param string $id El identificador único de la sesión.
     * @return string Los datos de la sesión desencriptados si existen, o una cadena vacía si no se encuentran o ocurre un error.
     *
     * Este método realiza los siguientes pasos:
     * - Limpia el ID proporcionado utilizando el método `clear`.
     * - Prepara y ejecuta una consulta SQL para obtener los datos de la sesión desde la tabla `sesiones`.
     * - Obtiene la clave única asociada al ID utilizando el método `getUnicKey`.
     * - Si no se encuentran datos o la clave es inválida, retorna una cadena vacía.
     * - Intenta desencriptar los datos de la sesión utilizando el método `decryptData`.
     * - Si ocurre una excepción durante la desencriptación, retorna una cadena vacía.
     * - Si todo es exitoso, retorna los datos de la sesión desencriptados.
     */
    public function read($id) {
        $id = $this->clear($id);
        $stm = $this->connection->prepare("SELECT data FROM sesiones WHERE id = '{$id}'");
        $stm->execute();
        $sesion_data = $stm->fetch(PDO::FETCH_ASSOC);
        $key = $this->getUnicKey($id);



        if($sesion_data == false || $key == false) return '';

        try{
            $sesion_data = $this->decryptData($sesion_data['data'], $key);
        }catch (Exception $e){
            return '';
        }

        return $sesion_data;
    }


    /**
     * Escribe datos de sesión en la base de datos.
     *
     * @param string $id El identificador único de la sesión.
     * @param string $data Los datos de la sesión que se deben almacenar.
     * @return bool Devuelve true si la operación fue exitosa, false en caso contrario.
     *
     * Este método realiza las siguientes acciones:
     * - Limpia el identificador de sesión.
     * - Genera una clave única para la sesión.
     * - Cifra los datos de la sesión utilizando la clave generada.
     * - Verifica que las variables de sesión 'usuario2' y 'mandante' estén definidas.
     * - Si el usuario tiene un ID específico (2434652), se puede enviar un mensaje de alerta (comentado en el código).
     * - Elimina sesiones anteriores del mismo usuario, excepto la actual.
     * - Inserta o actualiza la sesión en la base de datos con los datos proporcionados.
     *
     * Notas:
     * - Utiliza consultas SQL para interactuar con la base de datos.
     * - Se recomienda validar y sanitizar las entradas para evitar inyecciones SQL.
     * - El manejo de excepciones está presente, pero no realiza ninguna acción en caso de error.
     */
    public function write($id, $data) {
        $id = $this->clear($id);
        $key = $this->getUnicKey($id);
        $data = $this->encryptData($data, $key);
        $timer = time();
        $user_id = $_SESSION['usuario2'];
        $mandante = strtolower($_SESSION['mandante']);
        if($user_id == '' || $mandante == ''){
            return false;
        }
        try{
            if($user_id=='2434652'){
                $message='Write sesiones'.$id;
                //exec("php -f /home/home2/backend/api/src/imports/Slack/message.php '" . $message . "' '#alertas-integraciones' > /dev/null & ");

            }

        }catch (Exception $e){

        }

        if(true) {

            // Seleccionar la información relevante de la base de datos
            $stm = $this->connection->prepare("SELECT id FROM sesiones WHERE usuario_id = '{$user_id}'");
            $stm->execute();
            $results = $stm->fetchAll(PDO::FETCH_ASSOC);

// Eliminar los registros utilizando un bucle foreach
            foreach ($results as $row) {
                $sessionId = $row['id'];
                if($sessionId !=$id){

                    $stm = $this->connection->prepare("DELETE FROM sesiones WHERE id = '{$sessionId}'");
                    $stm->execute();
                }
            }
        }else{
            $stm = $this->connection->prepare("DELETE FROM sesiones WHERE usuario_id = '{$user_id}'  ");
            $stm->execute();

        }
        $stm = $this->connection->prepare("
  INSERT INTO sesiones (id, horario, data, clave_sesion, mandante, usuario_id)
  VALUES (:id, :horario, :data, :clave_sesion, :mandante, :usuario_id)
  ON DUPLICATE KEY UPDATE
    horario = VALUES(horario),
    data = VALUES(data),
    clave_sesion = VALUES(clave_sesion),
    mandante = VALUES(mandante),
    usuario_id = VALUES(usuario_id)
");
        $stm->execute([
            ':id' => $id,
            ':horario' => $timer,
            ':data' => $data,
            ':clave_sesion' => $key,
            ':mandante' => $mandante,
            ':usuario_id' => $user_id
        ]);

        $stm->execute();
        return true;
    }

    /**
     * Cierra la conexión a la base de datos.
     *
     * @return bool Devuelve true siempre.
     */
    public function close() {
        $this->connection = null;
        return true;
    }

    /**
     * Destruye una sesión específica en la base de datos.
     *
     * @param string $id El identificador único de la sesión.
     * @return bool Devuelve true siempre.
     */
    public function destroy($id) {
        $id = $this->clear($id);
        $stm = $this->connection->prepare("DELETE FROM sesiones WHERE id = '{$id}'");
        $stm->execute();
        return true;
    }

    /**
     * Cifra los datos de la sesión.
     *
     * @param string $data Los datos de la sesión a cifrar.
     * @param string $key La clave utilizada para el cifrado.
     * @return string Los datos cifrados en base64.
     */
    private function encryptData($data, $key) {
        $iv = base64_encode($key);
        $encrypted = openssl_encrypt($data, $this->cryptMethod, $key, false, $iv);
        return base64_encode($encrypted);
    }

    /**
     * Descifra los datos de la sesión.
     *
     * @param string $data Los datos cifrados de la sesión.
     * @param string $key La clave utilizada para el descifrado.
     * @return string Los datos descifrados.
     */
    private function decryptData($data, $key) {
        $iv = base64_encode($key);
        $decrypt = openssl_decrypt(base64_decode($data), $this->cryptMethod, $key, false, $iv);
        return $decrypt;
    }

    /**
     * Obtiene la clave única asociada a un ID de sesión.
     *
     * @param string $id El identificador único de la sesión.
     * @return string La clave única de la sesión.
     */
    private function getUnicKey($id) {
        $stm = $this->connection->prepare("SELECT clave_sesion from sesiones where id = '{$id}'");
        $stm->execute();
        $sesion_key = $stm->fetch(PDO::FETCH_ASSOC);
        return $sesion_key !== false ? $sesion_key['clave_sesion'] : $this->generateKey();
    }

    /**
     * Genera una nueva clave única.
     *
     * @return string La clave generada.
     */
    private function generateKey() {
        return hash('sha512', uniqid(mt_rand(1, mt_getrandmax()), true));
    }

    /**
     * Limpia una cadena de caracteres no deseados.
     *
     * @param string $string La cadena a limpiar.
     * @return string La cadena limpia.
     */
    public function clear($string) {
        return preg_replace('/[^\w\.@-]/', '', $string);
    }

    /**
     * Recolecta y elimina las sesiones expiradas de la base de datos.
     *
     * @param int $max_time El tiempo máximo de vida de una sesión en segundos.
     * @return bool Devuelve true siempre.
     */
    public function gc($max_time) {
        $expire_date = time() - $max_time;
        $stm = $this->connection->prepare("DELETE FROM sesiones WHERE horario < '{$expire_date}'");
        $stm->execute();
        return true;
    }
}
?>