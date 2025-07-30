<?php namespace Backend\dto;
use Backend\mysql\UsuarioPremiomaxMySqlDAO;
use Exception;
/** 
* Clase 'UsuarioPremiomax'
* 
* Esta clase provee una manera de instanciar un objeto de transferencia de datos (dto)
* para la tabla 'UsuarioPremiomax'
* 
* Ejemplo de uso: 
* $UsuarioPremiomax = new UsuarioPremiomax();
*   
* 
* @package ninguno 
* @author Daniel Tamayo <it@virtualsoft.tech>
* @version ninguna
* @access public 
* @see no
* 
*/
class UsuarioPremiomax
{

    /**
    * Representación de la columna 'premiomaxId' de la tabla 'UsuarioPremiomax'
    *
    * @var string
    */		
	var $premiomaxId;

    /**
    * Representación de la columna 'usuarioId' de la tabla 'UsuarioPremiomax'
    *
    * @var string
    */
	var $usuarioId;

    /**
    * Representación de la columna 'premioMax' de la tabla 'UsuarioPremiomax'
    *
    * @var string
    */
	var $premioMax;

    /**
    * Representación de la columna 'usumodifId' de la tabla 'UsuarioPremiomax'
    *
    * @var string
    */
	var $usumodifId;

    /**
    * Representación de la columna 'fechaModif' de la tabla 'UsuarioPremiomax'
    *
    * @var string
    */
	var $fechaModif;

    /**
    * Representación de la columna 'cantLineas' de la tabla 'UsuarioPremiomax'
    *
    * @var string
    */
	var $cantLineas;

    /**
    * Representación de la columna 'premioMax1' de la tabla 'UsuarioPremiomax'
    *
    * @var string
    */
	var $premioMax1;

    /**
    * Representación de la columna 'premioMax2' de la tabla 'UsuarioPremiomax'
    *
    * @var string
    */
	var $premioMax2;

    /**
    * Representación de la columna 'premioMax3' de la tabla 'UsuarioPremiomax'
    *
    * @var string
    */
	var $premioMax3;

    /**
    * Representación de la columna 'apuestaMin' de la tabla 'UsuarioPremiomax'
    *
    * @var string
    */
	var $apuestaMin;

    /**
    * Representación de la columna 'valorDirecto' de la tabla 'UsuarioPremiomax'
    *
    * @var string
    */
	var $valorDirecto;

    /**
    * Representación de la columna 'premioDirecto' de la tabla 'UsuarioPremiomax'
    *
    * @var string
    */
	var $premioDirecto;

    /**
    * Representación de la columna 'mandante' de la tabla 'UsuarioPremiomax'
    *
    * @var string
    */
	var $mandante;

    /**
    * Representación de la columna 'optimizarParrilla' de la tabla 'UsuarioPremiomax'
    *
    * @var string
    */
	var $optimizarParrilla;

    /**
    * Representación de la columna 'textoOp1' de la tabla 'UsuarioPremiomax'
    *
    * @var string
    */
	var $textoOp1;

    /**
    * Representación de la columna 'textoOp2' de la tabla 'UsuarioPremiomax'
    *
    * @var string
    */
	var $textoOp2;

    /**
    * Representación de la columna 'urlOp2' de la tabla 'UsuarioPremiomax'
    *
    * @var string
    */
	var $urlOp2;

    /**
    * Representación de la columna 'textoOp3' de la tabla 'UsuarioPremiomax'
    *
    * @var string
    */
	var $textoOp3;

    /**
    * Representación de la columna 'urlOp3' de la tabla 'UsuarioPremiomax'
    *
    * @var string
    */
	var $urlOp3;

    /**
    * Representación de la columna 'valorEvento' de la tabla 'UsuarioPremiomax'
    *
    * @var string
    */
	var $valorEvento;

    /**
    * Representación de la columna 'valorDiario' de la tabla 'UsuarioPremiomax'
    *
    * @var string
    */
	var $valorDiario;


    /**
    * Constructor de clase
    *
    *
    * @param String $usuarioId usuarioId
    *
    * @return no
    * @throws no
    *
    * @access public
    * @see no
    * @since no
    * @deprecated no
    */
    public function __construct($usuarioId="")
    {
        if($usuarioId != ""){
            $UsuarioPremiomaxMySqlDAO = new UsuarioPremiomaxMySqlDAO();

            $UsuarioPremiomax = $UsuarioPremiomaxMySqlDAO->loadbyUsuarioId($usuarioId);

            if ($UsuarioPremiomax != "" && $UsuarioPremiomax != null)
            {

                $this->premiomaxId = $UsuarioPremiomax->premiomaxId;
                $this->usuarioId = $UsuarioPremiomax->usuarioId;
                $this->premioMax = $UsuarioPremiomax->premioMax;
                $this->usumodifId = $UsuarioPremiomax->usumodifId;
                $this->fechaModif = $UsuarioPremiomax->fechaModif;
                $this->cantLineas = $UsuarioPremiomax->cantLineas;
                $this->premioMax1 = $UsuarioPremiomax->premioMax1;
                $this->premioMax2 = $UsuarioPremiomax->premioMax2;
                $this->premioMax3 = $UsuarioPremiomax->premioMax3;
                $this->apuestaMin = $UsuarioPremiomax->apuestaMin;
                $this->valorDirecto = $UsuarioPremiomax->valorDirecto;
                $this->premioDirecto = $UsuarioPremiomax->premioDirecto;
                $this->mandante = $UsuarioPremiomax->mandante;
                $this->optimizarParrilla = $UsuarioPremiomax->optimizarParrilla;
                $this->textoOp1 = $UsuarioPremiomax->textoOp1;
                $this->textoOp2 = $UsuarioPremiomax->textoOp2;
                $this->textoOp3 = $UsuarioPremiomax->textoOp3;
                $this->urlOp3 = $UsuarioPremiomax->urlOp3;
                $this->valorEvento = $UsuarioPremiomax->valorEvento;
                $this->valorDiario = $UsuarioPremiomax->valorDiario;

            }
            else
            {
                throw new Exception("No existe " . get_class($this), "96");

            }


        }
    }


   }
?>