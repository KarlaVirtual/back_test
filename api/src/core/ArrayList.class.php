<?php
/** 
* Clase 'ArrayList'
* 
* Esta clase recrea el comportamiento de una lista mediante arreglos nativos
* 
* Ejemplo de uso: 
* $ArrayList = new ArrayList();
*	
* 
* @package ninguno 
* @author Tomasz Jazwinski
* @version ninguna
* @access public 
* @see no
* @date: 2007-11-28
* 
*/
class ArrayList{

    /**
   	* Representación de la lista
   	*
   	* @var array
    * @access private
   	*/
	private $tab;


    /**
   	* Tamaño de la lista
   	*
   	* @var int
    * @access private
    */
	private $size;



    /**
    * Constructor de clase
    *
    *
    * @param no
    *
    * @return no
    * @throws no
    *
    * @access public
    * @see no
    * @since no
    * @deprecated no
    */
	public function ArrayList(){
		$this->tab = array();
		$this->size=0;
	}

    /**
    * Agregar un valor a la lista
    *
    *
    * @param no
    *
    * @return no
    * @throws no
    *
    * @access public
    */
	public function add($value){
		$this->tab[$this->size] = $value;
		$this->size = ($this->size) +1;
	}

    /**
    * Obtener un elemento con un número 
    * dado como un parámetro de método
    *
    *
    * @param int $idx id del elemento requerido 
    *
    * @return mixed $ elemento 
    * @throws no
    *
    * @access public
    */
	public function get($idx){
		return $this->tab[$idx];
	}

    /**
    * Obtener el último elemento de la lista
    *
    *
    * @param no
    *
    * @return mixed $ elemento 
    * @throws no
    *
    * @access public
    */
	public function getLast(){
		if($this->size==0){
			return null;
		}
		return $this->tab[($this->size)-1];
	}

    /**
    * Obtener el tamaño de la lista
    *
    *
    * @param no
    *
    * @return int $ tamaño de la lista 
    * @throws no
    *
    * @access public
    */
	public function size(){
		return $this->size;
	}

    /**
    * ¿La lista está vacía?
    *
    *
    * @param no
    *
    * @return boolean $ verdadero si está vacía, falso si no lo está
    * @throws no
    *
    * @access public
    */
	public function isEmpty(){
		return ($this->size)==0;
	}

    /**
    * Eliminar el último elemento de la lista
    *
    *
    * @param no
    *
    * @return int $ nuevo tamaño de la lista 
    * @throws no
    *
    * @access public
    */
	public function removeLast(){
		return $this->size = ($this->size) -1;
	}
}
?>