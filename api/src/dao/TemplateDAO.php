<?php namespace Backend\dao;
/**
 * Interfaz para el modelo o tabla 'template'.
 *
 * @author Daniel Tamayo <it@virtualsoft.tech>
 * @category    No
 * @package     No
 * @version     1.0
 * @since       No definida
 */
interface TemplateDAO
{

    /**
     * Obtener el registro condicionado por la 
     * llave primaria que se pasa como parámetro
     *
     * @param String $id llave primaria
     */
    public function load($id);
    
    /**
     * Obtener todos los registros de la base datos
     */
    public function queryAll();

    /**
     * Obtener todos los registros
     * ordenadas por el nombre de la columna 
     * que se pasa como parámetro
     *
     * @param String $orderColumn nombre de la columna
     */
    public function queryAllOrderBy($orderColumn);
    
    /**
     * Eliminar todos los registros condicionados
     * por la llave primaria
     *
     * beggin
     * @param String $templateId llave primaria
     */
    public function delete($templateId);
    
    /**
     * Insertar un registro en la base de datos
     *
     * @param String UsuarioMensajeCampana UsuarioMensajeCampana
     */
    public function insert($template);

    /**
     * Editar un registro en la base de datos
     *
     * @param Object UsuarioMensajeCampana UsuarioMensajeCampana
     */
    public function update($template);

    /**
     * Eliminar todos los registros de la base de datos
     */
    public function clean();






    /**
     * Obtener todos los registros donde se encuentre que
     * la columna FechaCrea sea igual al valor pasado como parámetro
     *
     * @param String $value FechaCrea requerido
     */ 
    public function queryByFechaCrea($value);

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna UsucreaId sea igual al valor pasado como parámetro
     *
     * @param String $value UsucreaId requerido
     */ 
    public function queryByUsucreaId($value);

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna FechaModif sea igual al valor pasado como parámetro
     *
     * @param String $value FechaModif requerido
     */ 
    public function queryByFechaModif($value);

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna UsumodifId sea igual al valor pasado como parámetro
     *
     * @param String $value UsumodifId requerido
     */ 
    public function queryByUsumodifId($value);

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna nombre sea igual al valor pasado como parámetro
     *
     * @param String $value nombre requerido
     */
    public function queryByNombre($value);

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna descrpcion sea igual al valor pasado como parámetro
     *
     * @param String $value descripcion requerido
     */
    public function queryByTemplateArray($value);





    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna FechaCrea sea igual al valor pasado como parámetro
     *
     * @param String $value FechaCrea requerido
     */ 
    public function deleteByFechaCrea($value);

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna UsucreaId sea igual al valor pasado como parámetro
     *
     * @param String $value UsucreaId requerido
     */ 
    public function deleteByUsucreaId($value);

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna FechaModif sea igual al valor pasado como parámetro
     *
     * @param String $value FechaModif requerido
     */ 
    public function deleteByFechaModif($value);

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna UsumodifId sea igual al valor pasado como parámetro
     *
     * @param String $value UsumodifId requerido
     */ 
    public function deleteByUsumodifId($value);

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna nombre sea igual al valor pasado como parámetro
     *
     * @param String $value nombre requerido
     */
    public function deleteByNombre($value);

         /**
          * Eliminar todos los registros donde se encuentre que
          * la columna descripcion sea igual al valor pasado como parámetro
          *
          * @param String $value descripcion requerido
          */
    public function deleteByTemplateArray($value);


}
