<?php namespace Backend\mysql;


use Backend\dao\TemplateDAO;
use Backend\dao\UsuarioMensajecampanaDAO;
use Backend\dao\UsuarioMensajeDAO;
use Backend\dto\Template;
use Backend\dto\UsuarioMensaje;
use Backend\dto\UsuarioMensajecampana;
use Backend\sql\QueryExecutor;
use Backend\sql\SqlQuery;
use Backend\sql\Transaction;

/**
 * Clase 'UsuarioMensajecampanaMySqlDAO'
 *
 * Esta clase provee las consultas del modelo o tabla 'UsuarioMensajeCampana'
 *
 * Ejemplo de uso:
 * $UsuarioMensajecampanaMySqlDAO = new UsuarioMensajecampanaMySqlDAO();
 *
 *
 * @package ninguno
 * @author Daniel Tamayo <it@virtualsoft.tech>
 * @version ninguna
 * @access public
 * @see no
 *
 */
class TemplateMySqlDAO implements TemplateDAO
{


    /**
     * Atributo Transaction transacción
     *
     * @var object
     */
    private $transaction;

    /**
     * Obtener la transacción de un objeto
     *
     * @return Objeto Transaction transacción
     *
     */
    public function getTransaction()
    {
        return $this->transaction;
    }

    /**
     * Modificar el atributo transacción del objeto
     *
     * @param Objeto $Transaction transacción
     *
     * @return no
     *
     */
    public function setTransaction($transaction)
    {
        $this->transaction = $transaction;
    }

    /**
     * Constructor de clase
     *
     *
     * @param Objeto $transaction transaccion
     *
     * @return no
     * @throws no
     *
     * @access public
     * @see no
     * @since no
     * @deprecated no
     */
    public function __construct($transaction = "")
    {
        if ($transaction == "") {
            $transaction = new Transaction();
            $this->transaction = $transaction;
        } else {
            $this->transaction = $transaction;
        }
    }



    /**
     * Obtener todos los registros condicionados por la
     * llave primaria que se pasa como parámetro
     *
     * @param String $id llave primaria
     *
     * @return Array $ resultado de la consulta
     *
     */
    public function load($id)
    {
        $sql = 'SELECT * FROM template WHERE template_id = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->setNumber($id);
        return $this->getRow($sqlQuery);
    }

    /**
     * Carga una plantilla basada en el mandante, tipo, ID del país y lenguaje.
     *
     * @param string $mandante El mandante de la plantilla.
     * @param string $tipo El tipo de la plantilla.
     * @param int $paisId El ID del país de la plantilla.
     * @param string $lenguaje El lenguaje de la plantilla.
     * @return array La fila de la plantilla que coincide con los parámetros dados.
     */
    public function loadByMandanteAndTipoAndPaisIdAndLenguaje($mandante,$tipo,$paisId,$lenguaje)
    {
        $sql = 'SELECT * FROM template WHERE mandante = ? AND tipo = ? AND pais_id = ? AND lenguaje= ? ';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->set($mandante);
        $sqlQuery->set($tipo);
        $sqlQuery->set($paisId);
        $sqlQuery->set($lenguaje);
        return $this->getRow($sqlQuery);
    }

    /**
     * Obtener todos los registros condicionados por la
     * llave primaria que se pasa como parámetro
     *
     * @param String $id llave primaria
     *
     * @return Array $ resultado de la consulta
     *
     */
    public function queryAll()
    {
        $sql = 'SELECT * FROM template';
        $sqlQuery = new SqlQuery($sql);
        return $this->getList($sqlQuery);
    }

    /**
     * Obtener todos los registros
     * ordenadas por el nombre de la columna
     * que se pasa como parámetro
     *
     * @param String $orderColumn nombre de la columna
     *
     * @return Array $ resultado de la consulta
     *
     */
    public function queryAllOrderBy($orderColumn)
    {
        $sql = 'SELECT * FROM template ORDER BY ' . $orderColumn;
        $sqlQuery = new SqlQuery($sql);
        return $this->getList($sqlQuery);
    }

    /**
     * Eliminar todos los registros condicionados
     * por la llave primaria
     *
     * @param String $usumandante_id llave primaria
     *
     * @return boolean $ resultado de la consulta
     *
     */
    public function delete($templateId)
    {
        $sql = 'DELETE FROM template WHERE template_id = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->setNumber($templateId);
        return $this->executeUpdate($sqlQuery);
    }

    /**
     * Insertar un registro en la base de datos
     *
     * @param Objeto usuarioMandante usuarioMandante
     *
     * @return String $id resultado de la consulta
     *
     */
    public function insert($template)
    {
        $sql = 'INSERT INTO template ( usucrea_id, usumodif_id,tipo,nombre,template_array,mandante,pais_id,lenguaje,template_html) VALUES (?, ?, ?, ?, ?,?,?,?,?)';
        $sqlQuery = new SqlQuery($sql);

        $sqlQuery->setNumber($template->usucreaId);
        $sqlQuery->setNumber($template->usumodifId);
        $sqlQuery->set($template->tipo);
        $sqlQuery->set($template->nombre);
        $sqlQuery->set($template->templateArray);
        $sqlQuery->set($template->mandante);
        $sqlQuery->set($template->paisId);
        $sqlQuery->set($template->lenguaje);

        $sqlQuery->set($template->templateHtml);

        $id = $this->executeInsert($sqlQuery);
        $template->templateId = $id;
        return $id;
    }

    /**
     * Editar un registro en la base de datos
     *
     * @param Objeto usuarioMandante usuarioMandante
     *
     * @return boolean $ resultado de la consulta
     *
     */
    public function update($template)
    {
        $sql = 'UPDATE template SET  tipo=? nombre = ?, template_array = ?, mandante = ?, pais_id = ?, lenguaje = ?, template_html = ?,usumodif_id=? WHERE template_id = ?';
        $sqlQuery = new SqlQuery($sql);


        $sqlQuery->set($template->tipo);
        $sqlQuery->set($template->nombre);
        $sqlQuery->set($template->templateArray);
        $sqlQuery->set($template->mandante);
        $sqlQuery->set($template->paisId);
        $sqlQuery->set($template->lenguaje);

        $sqlQuery->set($template->templateHtml);

        $sqlQuery->set($template->usumodifId);

        $sqlQuery->setNumber($template->templateId);
        return $this->executeUpdate($sqlQuery);
    }


    /**
     * Actualiza un registro en la tabla 'template'.
     *
     * @param object $template Objeto que contiene los datos del template a actualizar.
     *  - templateArray: Array con los datos del template.
     *  - templateHtml: HTML del template.
     *  - usumodifId: ID del usuario que modifica.
     *  - templateId: ID del template a actualizar.
     * @return int Número de filas afectadas por la actualización.
     */
    public function updateTemplate($template)
    {
        $sql = 'UPDATE template SET   template_array = ?,template_html = ?,usumodif_id=? WHERE template_id = ?';
        $sqlQuery = new SqlQuery($sql);


        $sqlQuery->set($template->templateArray);
        $sqlQuery->set($template->templateHtml);
        $sqlQuery->set($template->usumodifId);


        $sqlQuery->setNumber($template->templateId);
        return $this->executeUpdate($sqlQuery);
    }


    /**
     * Eliminar todas los registros de la base de datos
     *
     * @param no
     *
     * @return boolean $ resultado de la consulta
     *
     */
    public function clean()
    {
        $sql = 'DELETE FROM template';
        $sqlQuery = new SqlQuery($sql);
        return $this->executeUpdate($sqlQuery);
    }




    /**
     * Obtener todos los registros donde se encuentre que
     * la columna fecha_crea sea igual al valor pasado como parámetro
     *
     * @param String $value fecha_crea requerido
     *
     * @return Array $ resultado de la consulta
     *
     */
    public function queryByFechaCrea($value)
    {
        $sql = 'SELECT * FROM template WHERE fecha_crea = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->set($value);
        return $this->getList($sqlQuery);
    }

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna usucrea_id sea igual al valor pasado como parámetro
     *
     * @param String $value usucrea_id requerido
     *
     * @return Array $ resultado de la consulta
     *
     */
    public function queryByUsucreaId($value)
    {
        $sql = 'SELECT * FROM template WHERE usucrea_id = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->setNumber($value);
        return $this->getList($sqlQuery);
    }

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna fecha_modif sea igual al valor pasado como parámetro
     *
     * @param String $value fecha_modif requerido
     *
     * @return Array $ resultado de la consulta
     *
     */
    public function queryByFechaModif($value)
    {
        $sql = 'SELECT * FROM template WHERE fecha_modif = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->set($value);
        return $this->getList($sqlQuery);
    }

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna usumodif_id sea igual al valor pasado como parámetro
     *
     * @param String $value usumodif_id requerido
     *
     * @return Array $ resultado de la consulta
     *
     */
    public function queryByUsumodifId($value)
    {
        $sql = 'SELECT * FROM template WHERE usumodif_id = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->setNumber($value);
        return $this->getList($sqlQuery);
    }
    /**
     * Obtener todos los registros donde se encuentre que
     * la columna nombre sea igual al valor pasado como parámetro
     *
     * @param String $value nombre requerido
     *
     * @return Array $ resultado de la consulta
     *
     */
    public function queryByNombre($value)
    {
        $sql = 'SELECT * FROM template WHERE nombre = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->set($value);
        return $this->getList($sqlQuery);
    }
    /**
     * Obtener todos los registros donde se encuentre que
     * la columna descripcion sea igual al valor pasado como parámetro
     *
     * @param String $value descripcion requerido
     *
     * @return Array $ resultado de la consulta
     *
     */
    public function queryByTemplateArray($value)
    {
        $sql = 'SELECT * FROM template WHERE template_array = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->set($value);
        return $this->getList($sqlQuery);
    }

    /**
     * Realizar una consulta en la tabla de UsuarioMensaje 'UsuarioMensaje'
     * de una manera personalizada
     *
     * @param String $select campos de consulta
     * @param String $sidx columna para ordenar
     * @param String $sord orden los datos asc | desc
     * @param String $start inicio de la consulta
     * @param String $limit limite de la consulta
     * @param String $filters condiciones de la consulta
     * @param boolean $searchOn utilizar los filtros o no
     *
     * @return Array $json resultado de la consulta
     *
     */
    public function queryTemplateCustom($select, $sidx, $sord, $start, $limit, $filters, $searchOn, $userToSpecific = '', $grouping = '')
    {

        $where = " where 1=1 ";

        if ($searchOn) {
            // Construye el where
            $filters = json_decode($filters);
            $whereArray = array();
            $rules = $filters->rules;
            $groupOperation = $filters->groupOp;
            $cont = 0;

            foreach ($rules as $rule) {
                $fieldName = $rule->field;
                $fieldData = $rule->data;
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
                        $fieldOperation = " LIKE '%" . $fieldData . "'";
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
                    case "isnull":
                        $fieldOperation = " IS NULL ";
                        break;
                    default:
                        $fieldOperation = "";
                        break;
                }
                if ($fieldOperation != "") {
                    $whereArray[] = $fieldName . $fieldOperation;
                }

                if (oldCount($whereArray) > 0) {
                    $where = $where . " " . $groupOperation . " ((" . $fieldName . " )) " . strtoupper($fieldOperation);
                } else {
                    $where = "";
                }
            }

        }
        /*  $leftUserSpecific = '';
          if ($userToSpecific != '') {
              $leftUserSpecific = " LEFT OUTER JOIN usuario_mensajecampana usuario_mensaje2 on (usuario_mensaje2.parent_id = usuario_mensajecampana.usumensajecampana_id and usuario_mensaje2.usuto_id='" . $userToSpecific . "') ";
          }
          */
        $sql = "SELECT count(*) count FROM template"  . $where;
        if ($grouping != "") {

            $where = $where . " GROUP BY " . $grouping;
        }


        $sqlQuery = new SqlQuery($sql);

        $count = $this->execute2($sqlQuery);
        $sql = "SELECT " . $select . " FROM template " . $where . " " . " order by " . $sidx . " " . $sord . " LIMIT " . $start . " , " . $limit;

        $sqlQuery = new SqlQuery($sql);

        $result = $this->execute2($sqlQuery);

        $json = '{ "count" : ' . json_encode($count) . ', "data" : ' . json_encode($result) . ', "sql" : "' . $sql . '"}';

        return $json;
    }

    /**
     * Realiza una consulta personalizada en la base de datos utilizando varios parámetros de búsqueda y filtros.
     *
     * @param string $select Campos a seleccionar en la consulta.
     * @param string $sidx Campo por el cual se ordenará la consulta.
     * @param string $sord Orden de la consulta (ASC o DESC).
     * @param int $start Índice de inicio para la paginación.
     * @param int $limit Límite de registros a devolver.
     * @param string $filters Filtros en formato JSON para aplicar en la consulta.
     * @param bool $searchOn Indica si se deben aplicar los filtros de búsqueda.
     * @param string $userToSpecific (Opcional) Usuario específico para filtrar.
     * @param string $grouping (Opcional) Campo por el cual agrupar los resultados.
     *
     * @return string JSON con el conteo de registros, los datos resultantes y la consulta SQL generada.
     */
    public function queryTemplateCustom2($select, $sidx, $sord, $start, $limit, $filters, $searchOn, $userToSpecific = '', $grouping = '')
    {

        $where = " where 1=1 ";

        if ($searchOn) {
            // Construye el where
            $filters = json_decode($filters);
            $whereArray = array();
            $rules = $filters->rules;
            $groupOperation = $filters->groupOp;
            $cont = 0;

            foreach ($rules as $rule) {
                $fieldName = $rule->field;
                $fieldData = $rule->data;
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
                        $fieldOperation = " LIKE '%" . $fieldData . "'";
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
                    case "isnull":
                        $fieldOperation = " IS NULL ";
                        break;
                    default:
                        $fieldOperation = "";
                        break;
                }
                if ($fieldOperation != "") {
                    $whereArray[] = $fieldName . $fieldOperation;
                }

                if (oldCount($whereArray) > 0) {
                    $where = $where . " " . $groupOperation . " ((" . $fieldName . " )) " . strtoupper($fieldOperation);
                } else {
                    $where = "";
                }
            }

        }
        /*  $leftUserSpecific = '';
          if ($userToSpecific != '') {
              $leftUserSpecific = " LEFT OUTER JOIN usuario_mensajecampana usuario_mensaje2 on (usuario_mensaje2.parent_id = usuario_mensajecampana.usumensajecampana_id and usuario_mensaje2.usuto_id='" . $userToSpecific . "') ";
          }
          */

        $sql = "SELECT count(*) count FROM template LEFT OUTER JOIN clasificador ON (clasificador.clasificador_id = template.tipo) LEFT OUTER JOIN pais ON (pais.pais_id = template.pais_id) LEFT OUTER JOIN mandante ON (mandante.mandante = template.mandante)"  . $where;

        if ($grouping != "") {

            $where = $where . " GROUP BY " . $grouping;
        }


        $sqlQuery = new SqlQuery($sql);

        $count = $this->execute2($sqlQuery);
        $sql = "SELECT " . $select . " FROM template LEFT OUTER JOIN clasificador ON (clasificador.clasificador_id = template.tipo) LEFT OUTER JOIN pais ON (pais.pais_id = template.pais_id) LEFT OUTER JOIN mandante ON (mandante.mandante = template.mandante)" . $where . " " . " order by " . $sidx . " " . $sord . " LIMIT " . $start . " , " . $limit;

        $sqlQuery = new SqlQuery($sql);

        $result = $this->execute2($sqlQuery);

        $json = '{ "count" : ' . json_encode($count) . ', "data" : ' . json_encode($result) . ', "sql" : "' . $sql . '"}';

        return $json;
    }


    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna fecha_crea sea igual al valor pasado como parámetro
     *
     * @param String $value fecha_crea requerido
     *
     * @return boolean $ resultado de la ejecución
     *
     */
    public function deleteByFechaCrea($value)
    {
        $sql = 'DELETE FROM template WHERE fecha_crea = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->set($value);
        return $this->executeUpdate($sqlQuery);
    }

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna usucrea_id sea igual al valor pasado como parámetro
     *
     * @param String $value usucrea_id requerido
     *
     * @return boolean $ resultado de la ejecución
     *
     */
    public function deleteByUsucreaId($value)
    {
        $sql = 'DELETE FROM template WHERE usucrea_id = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->setNumber($value);
        return $this->executeUpdate($sqlQuery);
    }

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna fecha_modif sea igual al valor pasado como parámetro
     *
     * @param String $value fecha_modif requerido
     *
     * @return boolean $ resultado de la ejecución
     *
     */
    public function deleteByFechaModif($value)
    {
        $sql = 'DELETE FROM template WHERE fecha_modif = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->set($value);
        return $this->executeUpdate($sqlQuery);
    }

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna usumodif_id sea igual al valor pasado como parámetro
     *
     * @param String $value usumodif_id requerido
     *
     * @return boolean $ resultado de la ejecución
     *
     */
    public function deleteByUsumodifId($value)
    {
        $sql = 'DELETE FROM template WHERE usumodif_id = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->setNumber($value);
        return $this->executeUpdate($sqlQuery);
    }

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna nombre sea igual al valor pasado como parámetro
     *
     * @param String $value nombre requerido
     *
     * @return boolean $ resultado de la ejecución
     *
     */
    public function deleteByNombre($value)
    {
        $sql = 'DELETE FROM template WHERE nombre = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->setNumber($value);
        return $this->executeUpdate($sqlQuery);
    }
    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna templateArray sea igual al valor pasado como parámetro
     *
     * @param String $value descripcion requerido
     *
     * @return boolean $ resultado de la ejecución
     *
     */
    public function deleteByTemplateArray($value)
    {
        $sql = 'DELETE FROM template WHERE template_array = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->setNumber($value);
        return $this->executeUpdate($sqlQuery);
    }


    /**
     * Crear y devolver un objeto del tipo UsuarioMensajeCampana
     * con los valores de una consulta sql
     *
     *
     * @param Arreglo $row arreglo asociativo
     *
     * @return Objeto $usuarioMensajeCampana UsuarioMensajeCampana
     *
     * @access protected
     *
     */
    protected function readRow($row)
    {
        $template = new Template();

        $template->templateId = $row['template_id'];
        $template->fechaCrea = $row['fecha_crea'];
        $template->usucreaId = $row['usucrea_id'];
        $template->fechaModif = $row['fecha_modif'];
        $template->usumodifId = $row['usumodif_id'];
        $template->tipo = $row['tipo'];
        $template->nombre = $row['nombre'];
        $template->templateArray = $row['template_array'];
        $template->mandante = $row['mandante'];
        $template->paisId = $row['pais_id'];
        $template->lenguaje = $row['lenguaje'];

        $template->templateHtml = $row['template_html'];
        return $template;
    }

    /**
     * Ejecutar una consulta sql y devolver los datos
     * como un arreglo asociativo
     *
     *
     * @param String $sqlQuery consulta sql
     *
     * @return Array $ret arreglo indexado
     *
     * @access protected
     *
     */
    protected function getList($sqlQuery)
    {
        $tab = QueryExecutor::execute($this->transaction, $sqlQuery);
        $ret = array();
        for ($i = 0; $i < oldCount($tab); $i++) {
            $ret[$i] = $this->readRow($tab[$i]);
        }
        return $ret;
    }


    /**
     * Ejecutar una consulta sql y devolver el resultado como un arreglo
     *
     *
     * @param String $sqlQuery consulta sql
     *
     * @return Array $ resultado de la ejecución
     *
     * @access protected
     *
     */
    protected function getRow($sqlQuery)
    {
        $tab = QueryExecutor::execute($this->transaction, $sqlQuery);
        if (oldCount($tab) == 0) {
            return null;
        }
        return $this->readRow($tab[0]);
    }

    /**
     * Ejecutar una consulta sql
     *
     *
     * @param String $sqlQuery consulta sql
     *
     * @return Array $ resultado de la ejecución
     *
     * @access protected
     *
     */
    protected function execute($sqlQuery)
    {
        return QueryExecutor::execute($this->transaction, $sqlQuery);
    }


    /**
     * Ejecutar una consulta sql
     *
     *
     * @param String $sqlQuery consulta sql
     *
     * @return Array $ resultado de la ejecución
     *
     * @access protected
     *
     */
    protected function execute2($sqlQuery)
    {
        return QueryExecutor::execute2($this->transaction, $sqlQuery);
    }

    /**
     * Ejecutar una consulta sql como update
     *
     *
     * @param String $sqlQuery consulta sql
     *
     * @return Array $ resultado de la ejecución
     *
     * @access protected
     *
     */
    protected function executeUpdate($sqlQuery)
    {
        return QueryExecutor::executeUpdate($this->transaction, $sqlQuery);
    }


    /**
     * Ejecutar una consulta sql como select
     *
     *
     * @param String $sqlQuery consulta sql
     *
     * @return Array $ resultado de la ejecución
     *
     * @access protected
     *
     */
    protected function querySingleResult($sqlQuery)
    {
        return QueryExecutor::queryForString($this->transaction, $sqlQuery);
    }

    /**
     * Ejecutar una consulta sql como insert
     *
     *
     * @param String $sqlQuery consulta sql
     *
     * @return Array $ resultado de la ejecución
     *
     * @access protected
     *
     */
    protected function executeInsert($sqlQuery)
    {
        return QueryExecutor::executeInsert($this->transaction, $sqlQuery);
    }
}
