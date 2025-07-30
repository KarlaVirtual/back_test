<?php namespace Backend\dto;
use Backend\mysql\ApiTransactionsMySqlDAO;
use Exception;
/** 
* Clase 'ApiTransaction'
* 
* Esta clase provee una manera de instanciar un objeto de transferencia de datos (dto)
* para la tabla 'ApiTransaction'
* 
* Ejemplo de uso: 
* $ApiTransaction = new ApiTransaction();
*	
* 
* @package ninguno 
* @author Daniel Tamayo <it@virtualsoft.tech>
* @version ninguna
* @access public 
* @see no
* 
*/
class ApiTransaction
{

    /**
   	* Representación de la columna 'trnID' de la tabla 'ApiTransactions'
   	*
   	* @var string
   	*/
    var $trnID;

    /**
   	* Representación de la columna 'cliID' de la tabla 'ApiTransactions'
   	*
   	* @var string
   	*/
	  var $cliID;

    /**
   	* Representación de la columna 'mocID' de la tabla 'ApiTransactions'
   	*
   	* @var string
   	*/
	  var $mocID;

    /**
   	* Representación de la columna 'trnMonto' de la tabla 'ApiTransactions'
  	*
   	* @var float
   	*/
	  var $trnMonto;

    /**
   	* Representación de la columna 'transactionID' de la tabla 'ApiTransactions'
   	*
   	* @var String
   	*/
	  var $transactionID;

    /**
   	* Representación de la columna 'trnType' de la tabla 'ApiTransactions'
   	*
   	* @var String
   	*/
	  var $trnType;

    /**
   	* Representación de la columna 'trnDescription' de la tabla 'ApiTransactions'
   	*
   	* @var String
   	*/
	  var $trnDescription;

    /**
   	* Representación de la columna 'roundID' de la tabla 'ApiTransactions'
   	*
   	* @var String
   	*/
	  var $roundID;

    /**
   	* Representación de la columna 'history' de la tabla 'ApiTransactions'
   	*
   	* @var String
   	*/
	  var $history;

    /**
   	* Representación de la columna 'isRoundFinished' de la tabla 'ApiTransactions'
   	*
   	* @var boolean
   	*/
	  var $isRoundFinished;

    /**
   	* Representación de la columna 'gameID' de la tabla 'ApiTransactions'
   	*
   	* @var String
   	*/
	  var $gameID;

    /**
   	* Representación de la columna 'trnSaldo' de la tabla 'ApiTransactions'
   	*
   	* @var float
   	*/
	  var $trnSaldo;

    /**
   	* Representación de la columna 'trnSaldoEX' de la tabla 'ApiTransactions'
   	*
   	* @var float
   	*/
	  var $trnSaldoEX;

    /**
   	* Representación de la columna 'trnEstado' de la tabla 'ApiTransactions'
   	*
   	* @var String
   	*/
	  var $trnEstado;

    /**
   	* Representación de la columna 'trnFecReg' de la tabla 'ApiTransactions'
   	*
   	* @var String
   	*/
	  var $trnFecReg;

    /**
   	* Representación de la columna 'trnFecMod' de la tabla 'ApiTransactions'
   	*
   	* @var String
   	*/
	  var $trnFecMod;






   	/**
   	* Realizar una consulta en la tabla de transacciones 'ApiTransactions'
   	* de una manera personalizada
   	*
   	* Ejemplo de uso:
   	*
   	* $apt = $ApiTransaction->getTransaccionesCustom("usuario.moneda,'VivoGaming' ProviderName, COUNT(	ApiTransactions.trnID) count,SUM(CASE WHEN ApiTransactions.trnType = 'BET' THEN ApiTransactions.trnMonto ELSE 0 END) apuestas,SUM(CASE WHEN ApiTransactions.trnType = 'WIN' THEN ApiTransactions.trnMonto ELSE 0 END) premios", "ApiTransactions.trnID", "asc", $SkeepRows, $MaxRows, $json, true, "usuario.moneda");
    *
   	* @param String $select campos de consulta
   	* @param String $sidx columna para ordenar
   	* @param String $sord orden los datos asc | desc
   	* @param String $start inicio de la consulta
   	* @param String $limit limite de la consulta
   	* @param String $filters condiciones de la consulta 
  	* @param boolean $searchOn utilizar los filtros o no
   	* @param String $grouping columna para agrupar
   	*
   	* @return Array resultado de la consulta
   	* @throws Exception si la transacción no existe
   	*
   	* @access public
    * @see no
    * @since no
    * @deprecated no
   	*/
    public function getTransaccionesCustom($select,$sidx,$sord,$start,$limit,$filters,$searchOn,$grouping)
    {

        $ApiTransactionsMySqlDAO = new ApiTransactionsMySqlDAO();

        $transacciones = $ApiTransactionsMySqlDAO->queryApiTransactionsCustom($select,$sidx,$sord,$start,$limit,$filters,$searchOn,$grouping);

        if ($transacciones != null && $transacciones != "") 
        {
          return $transacciones;
        }
        else 
        {
          throw new Exception("No existe " . get_class($this), "01");
        }

    }
	
}
?>