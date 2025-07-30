<?php

    namespace Backend\mysql;

    use Backend\dto\Helpers;
    use Backend\sql\SqlQuery;
    use Backend\sql\Transaction;
    use Backend\sql\QueryExecutor;
    use Backend\dto\UsuarioSitebuilder;

    /**
     * Clase que maneja la conexión con la base de datos para la tabla de usuario_sitebuilder.
     *
     * @author Desconocido
     * @package No
     * @category No
     * @version    1.0
     * @since Desconocido
     */
    class UsuarioSitebuilderMySqlDAO {
        /**
         * Objeto transacción representa la conexión entre el objeto y la base de datos
         * @var Transaction
         */
        private $transaction;

        /**
         * Clave de encriptación
         * @var string
         */
        public $claveEncrypt;

        /**
         * Constructor de la clase.
         *
         * @param Transaction|null $transaction Objeto transacción opcional.
         */
        public function __construct($transaction = null) {
            $this->claveEncrypt = base64_decode($_ENV['APP_PASSKEY']);
            $this->transaction = $transaction ?: new Transaction();
        }

        /**
         * Establece la transacción.
         *
         * @param Transaction $transaction Objeto transacción.
         */
        public function setTransaction($transaction) {
            $this->transaction = $transaction;
        }

        /**
         * Obtiene la transacción actual.
         *
         * @return Transaction Objeto transacción.
         */
        public function getTransaction() {
            return $this->transaction;
        }

        /**
         * Carga un registro por ID.
         *
         * @param int $id ID del registro.
         * @return UsuarioSitebuilder|null Objeto UsuarioSitebuilder o null si no se encuentra.
         */
        public function load($id) {
            $sql = 'SELECT * FROM usuario_sitebuilder WHERE usuariositebuilder_id = ?';
            $sqlQuery = new SqlQuery($sql);
            $sqlQuery->setNumber($id);
            return $this->getRow($sqlQuery);
        }

        /**
         * Carga un registro por ID de usuario.
         *
         * @param int $usuarioId ID del usuario.
         * @return UsuarioSitebuilder|null Objeto UsuarioSitebuilder o null si no se encuentra.
         */
        public function loadByUsuarioId($usuarioId) {
            $sql = 'SELECT * FROM usuario_sitebuilder WHERE usuario_id = ?';
            $sqlQuery = new SqlQuery($sql);
            $sqlQuery->setNumber($usuarioId);
            return $this->getRow($sqlQuery);
        }

        /**
         * Consulta todos los registros.
         *
         * @return array Lista de objetos UsuarioSitebuilder.
         */
        public function queryAll(){
            $sql = 'SELECT * FROM usuario_sitebuilder';
            $sqlQuery = new SqlQuery($sql);
            return $this->getList($sqlQuery);
        }

        /**
         * Consulta registros por login.
         *
         * @param string $login Login del usuario.
         * @return array Lista de objetos UsuarioSitebuilder.
         */
        public function queryByLogin($login) {
            $Helpers = new Helpers();
            $login=strtolower($login);
            $login = $Helpers->encode_data_with_key($login, $_ENV['SECRET_PASSPHRASE_LOGIN']);

            if($_ENV['debug']){
                print_r($login);
            }

            $sql = "SELECT * FROM usuario_sitebuilder WHERE usuario_sitebuilder.login = ?";
            $sqlQuery = new SqlQuery($sql);
            $sqlQuery->set($login);

            return $this->getList($sqlQuery);
        }

        /**
         * Actualiza la clave de un usuario en la base de datos.
         *
         * @param UsuarioSitebuilder $usuarioSitebuilder Objeto que representa al usuario cuyo ID se utilizará para la actualización.
         * @param string $clave Nueva clave que se establecerá para el usuario.
         * @return int Número de filas afectadas por la actualización.
         */
        public function updateClave($usuarioSitebuilder, $clave) {
            $sql = 'UPDATE usuario_sitebuilder SET clave = ? WHERE usuario_id = ?';

            $sqlQuery = new SqlQuery($sql);

            $Helpers = new Helpers();
            $passwordHash = $Helpers->create_password_hash($clave);

            $sqlQuery->set($passwordHash);
            $sqlQuery->set($usuarioSitebuilder->getUsuarioId());

            return $this->executeUpdate($sqlQuery);
        }

        /**
         * Consulta para iniciar sesión de un usuario en el sistema.
         *
         * @param string $login El nombre de usuario para iniciar sesión.
         * @param string $clave La contraseña del usuario.
         * @return array|null Retorna los datos del usuario si las credenciales son correctas, de lo contrario, retorna null.
         */
        public function queryForLogin($login, $clave) {
            $Helpers = new Helpers();
            $login=strtolower($login);
            $login = $Helpers->encode_data_with_key($login, $_ENV['SECRET_PASSPHRASE_LOGIN']);

            if($_ENV['debug']){
                print_r($login);
            }

            $sql = "SELECT * FROM usuario_sitebuilder as us WHERE us.login = ? AND us.estado = 'A'";
            $sqlQuery = new SqlQuery($sql);
            $sqlQuery->set($login);

            $data = $this->execute($sqlQuery);

            if($Helpers->verify_password_hash($clave, $data[0]['clave'])) {
                if(!$Helpers->is_valid_passowrd_hash($data[0]['clave'])) {
                    $UsuarioSitebuilder = new UsuarioSitebuilder();
                    $UsuarioSitebuilder->setUsuarioId($data[0]['usuario_id']);
                    $UsuarioSitebuilder->setLogin(strtolower($data[0]['login']));
                    $this->update($UsuarioSitebuilder, $clave);
                    $this->transaction->commit();
                }

                unset($data[0]['clave']);
                unset($data[0][2]);
                return $data;
            }

            return null;
        }

        public function delete($usuverificacionId) {
            $sql = 'DELETE FROM usuario_sitebuilder WHERE usuverificacion_id = ?';
            $sqlQuery = new SqlQuery($sql);
            $sqlQuery->setNumber($usuverificacionId);
            return $this->executeUpdate($sqlQuery);
        }

        public function insert($usuarioSitebuilder) {
            $Helpers = new Helpers();
            $sql = "INSERT INTO usuario_sitebuilder (login, usuario_id, clave) VALUE (?, ?, '')";
            $sqlQuery = new SqlQuery($sql);
            $sqlQuery->set($Helpers->set_custom_secret_key('SECRET_PASSPHRASE_LOGIN', true)->encode_data(strtolower($usuarioSitebuilder->getLogin())));
            $sqlQuery->setNumber($usuarioSitebuilder->getUsuarioId());
             
            return $this->executeInsert($sqlQuery);
        }

        public function update($usuarioSitebuilder) {
            $Helpers = new Helpers();
            $sql = 'UPDATE usuario_sitebuilder SET login = ?, usuario_id = ?, estado = ?, intentos = ? WHERE usuariositebuilder_id = ?';
            $sqlQuery = new SqlQuery($sql);
            $sqlQuery->set($Helpers->set_custom_secret_key('SECRET_PASSPHRASE_LOGIN', true)->encode_data(strtolower($usuarioSitebuilder->getLogin())));
            $sqlQuery->setNumber($usuarioSitebuilder->getUsuarioId());
            $sqlQuery->set($usuarioSitebuilder->getEstado());
            $sqlQuery->setNumber($usuarioSitebuilder->getIntentos());
            $sqlQuery->setNumber($usuarioSitebuilder->getUsuarioSitebuilderId());
            return $this->executeUpdate($sqlQuery);
        }

        public function clean() {
            $sql = 'DELETE FROM usuario_sitebuilder';
            $sqlQuery = new SqlQuery($sql);
            return $this->executeUpdate($sqlQuery);
        }
    
        /**
         * Realiza una consulta personalizada en la tabla usuario_sitebuilder con filtros y paginación.
         *
         * @param string $select Columnas a seleccionar en la consulta.
         * @param string $sidx Columna por la cual ordenar los resultados.
         * @param string $sord Orden de los resultados (ASC o DESC).
         * @param int $start Índice de inicio para la paginación.
         * @param int $limit Número de registros a devolver.
         * @param string $filters Filtros en formato JSON para aplicar en la consulta.
         * @param bool $searchOn Indica si se deben aplicar los filtros.
         * @return string JSON con el conteo total de registros y los datos resultantes de la consulta.
         */
        public function queryUsuarioSitebuilderCustom($select, $sidx, $sord, $start, $limit, $filters, $searchOn) {
            $where = "where 1 = 1";

            $Helpers = new Helpers();

            if ($searchOn) {
                $filters = json_decode($filters);
                $whereArray = array();
                $rules = $filters->rules;
                $groupOperation = $filters->groupOp;
    
                foreach ($rules as $rule) {
                    $fieldName = $Helpers->set_custom_field($rule->field);
                    $fieldData = $rule->data;


                $Helpers = new Helpers();

                if($fieldName == 'registro.cedula'){
                    $fieldData = $Helpers->encode_data_with_key($fieldData, $_ENV['SECRET_PASSPHRASE_DOCUMENT']);
                }
                if($fieldName == 'registro.celular'){
                    $fieldData = $Helpers->encode_data_with_key($fieldData, $_ENV['SECRET_PASSPHRASE_PHONE']);
                }
                if($fieldName == 'usuario.cedula'){
                    $fieldData = $Helpers->encode_data_with_key($fieldData, $_ENV['SECRET_PASSPHRASE_DOCUMENT']);
                }
                if($fieldName == 'usuario.login'){
                    $fieldData = $Helpers->encode_data_with_key($fieldData, $_ENV['SECRET_PASSPHRASE_LOGIN']);
                }
                    if($fieldName == 'usuario.nombre'){
                        $fieldData = $Helpers->encode_data_with_key($fieldData, $_ENV['SECRET_PASSPHRASE_NAME']);
                    }
                    if($fieldName == 'usuario_mandante.nombres'){
                        $fieldData = $Helpers->encode_data_with_key($fieldData, $_ENV['SECRET_PASSPHRASE_NAME']);
                    }
                    if($fieldName == 'usuario_mandante.apellidos'){
                        $fieldData = $Helpers->encode_data_with_key($fieldData, $_ENV['SECRET_PASSPHRASE_LASTNAME']);
                    }
                if($fieldName == 'usuario_mandante.email'){
                    $fieldData = $Helpers->encode_data_with_key($fieldData, $_ENV['SECRET_PASSPHRASE_LOGIN']);
                }
                if($fieldName == 'punto_venta.email'){
                    $fieldData = $Helpers->encode_data_with_key($fieldData, $_ENV['SECRET_PASSPHRASE_LOGIN']);
                }
                if($fieldName == 'usuario_sitebuilder.login'){
                    $fieldData = $Helpers->encode_data_with_key($fieldData, $_ENV['SECRET_PASSPHRASE_LOGIN']);
                }
                switch ($rule->op) {
                        case "eq":
                            $fieldOperation = " = '" . $fieldData . "'";
                            break;
                        case "ne":
                            $fieldOperation = " != '" . $fieldData . "'";
                            break;
                        case "lt":
                            $fieldOperation = " < '" . $fieldData . "'";
                            break;
                        case "gt":
                            $fieldOperation = " > '" . $fieldData . "'";
                            break;
                        case "le":
                            $fieldOperation = " <= '" . $fieldData . "'";
                            break;
                        case "ge":
                            $fieldOperation = " >= '" . $fieldData . "'";
                            break;
                        case "nu":
                            $fieldOperation = " = ''";
                            break;
                        case "nn":
                            $fieldOperation = " != ''";
                            break;
                        case "in":
                            $fieldOperation = " IN (" . $fieldData . ")";
                            break;
                        case "ni":
                            $fieldOperation = " NOT IN '" . $fieldData . "'";
                            break;
                        case "bw":
                            $fieldOperation = " LIKE '" . $fieldData . "%'";
                            break;
                        case "bn":
                            $fieldOperation = " NOT LIKE '" . $fieldData . "%'";
                            break;
                        case "ew":
                            $fieldOperation = "  LIKE '%" . $fieldData . "'";
                            break;
                        case "en":
                            $fieldOperation = " NOT LIKE '%" . $fieldData . "'";
                            break;
                        case "cn":
                            $fieldOperation = " LIKE '%" . $fieldData . "%'";
                            break;
                        case "nc":
                            $fieldOperation = " NOT LIKE '%" . $fieldData . "%'";
                            break;
                        default:
                            $fieldOperation = "";
                            break;
                    }
                    if ($fieldOperation != "") {
                        $whereArray[] = $fieldName . $fieldOperation;
                    }
    
                    if (count($whereArray) > 0) {
                        $where = $where . " " . $groupOperation . " " . $fieldName . " " . strtoupper($fieldOperation);
                    } else {
                        $where = "";
                    }
                }
            }
    
            $sql = "SELECT count(*) count FROM usuario_sitebuilder INNER JOIN usuario ON (usuario.usuario_id = usuario_sitebuilder.usuario_id) INNER JOIN usuario_mandante ON (usuario_mandante.usuario_mandante = usuario.usuario_id) INNER JOIN pais ON (pais.pais_id = usuario.pais_id) " . $where;

            $sqlQuery = new SqlQuery($sql);
    
            $count = $this->execute2($sqlQuery);
            $sql = "SELECT " . $select . " FROM usuario_sitebuilder INNER JOIN usuario ON (usuario.usuario_id = usuario_sitebuilder.usuario_id) INNER JOIN usuario_mandante ON (usuario_mandante.usuario_mandante = usuario.usuario_id) INNER JOIN pais ON (pais.pais_id = usuario.pais_id)" . $where . " " . " order by " . $sidx . " " . $sord . " LIMIT " . $start . " , " . $limit;

            $sqlQuery = new SqlQuery($sql);

            $result = $Helpers->process_data($this->execute2($sqlQuery));
    
            $json = '{ "count" : ' . json_encode($count) . ', "data" : ' . json_encode($result) . '}';
    
            return $json;
        }

        /**
         * Lee una fila de la base de datos y la convierte en un objeto UsuarioSitebuilder.
         *
         * @param array $row Fila de la base de datos.
         * @return UsuarioSitebuilder Objeto UsuarioSitebuilder.
         */
        private function readRow($row) {
            $Helpers = new Helpers();
            $UsuarioSitebuilder = new UsuarioSitebuilder();
            $UsuarioSitebuilder->setUsuarioSitebuilderId($row['usuariositebuilder_id']);
            $UsuarioSitebuilder->setLogin($Helpers->set_custom_secret_key('SECRET_PASSPHRASE_LOGIN', true)->decode_data(($row['login'])));
            $UsuarioSitebuilder->setEstado($row['estado']);
            $UsuarioSitebuilder->setIntentos($row['intentos']);
            $UsuarioSitebuilder->setUsuarioId($row['usuario_id']);
            $UsuarioSitebuilder->setFechaCrea($row['fecha_crea']);
            $UsuarioSitebuilder->setFechaModif($row['fecha_modif']);

            return $UsuarioSitebuilder;
        }

        /**
         * Obtiene una lista de objetos UsuarioSitebuilder a partir de una consulta SQL.
         *
         * @param SqlQuery $sqlQuery Consulta SQL.
         * @return array Lista de objetos UsuarioSitebuilder.
         */
        private function getList($sqlQuery)
        {
            $tab = QueryExecutor::execute($this->transaction, $sqlQuery);
            $ret = array();
            for ($i = 0; $i < count($tab); $i++) {
                $ret[$i] = $this->readRow($tab[$i]);
            }
            return $ret;
        }

        /**
         * Obtiene una fila de la base de datos a partir de una consulta SQL.
         *
         * @param SqlQuery $sqlQuery Consulta SQL.
         * @return UsuarioSitebuilder|null Objeto UsuarioSitebuilder o null si no se encuentra.
         */
        private function getRow($sqlQuery)
        {
            $tab = QueryExecutor::execute($this->transaction, $sqlQuery);
            if (count($tab) == 0) {
                return null;
            }
            return $this->readRow($tab[0]);
        }

        /**
         * Ejecuta una consulta SQL.
         *
         * @param SqlQuery $sqlQuery Consulta SQL.
         * @return array Resultado de la consulta.
         */
        private function execute($sqlQuery)
        {
            return QueryExecutor::execute($this->transaction, $sqlQuery);
        }

        /**
         * Ejecuta una consulta SQL específica.
         *
         * @param SqlQuery $sqlQuery Consulta SQL.
         * @return array Resultado de la consulta.
         */
        private function execute2($sqlQuery)
        {
            return QueryExecutor::execute2($this->transaction, $sqlQuery);
        }

        /**
         * Ejecuta una actualización en la base de datos.
         *
         * @param SqlQuery $sqlQuery Consulta SQL.
         * @return int Número de filas afectadas por la actualización.
         */
        private function executeUpdate($sqlQuery)
        {
            return QueryExecutor::executeUpdate($this->transaction, $sqlQuery);
        }

        /**
         * Ejecuta una consulta SQL y obtiene un único resultado.
         *
         * @param SqlQuery $sqlQuery Consulta SQL.
         * @return string Resultado de la consulta.
         */
        private function querySingleResult($sqlQuery)
        {
            return QueryExecutor::queryForString($this->transaction, $sqlQuery);
        }

        /**
         * Ejecuta una inserción en la base de datos.
         *
         * @param SqlQuery $sqlQuery Consulta SQL.
         * @return int ID del registro insertado.
         */
        private function executeInsert($sqlQuery)
        {
            return QueryExecutor::executeInsert($this->transaction, $sqlQuery);
        }
    }
?>