<?php 
namespace Backend\dto;
/** 
* Clase 'Configuracion'
* 
* Esta clase provee una manera de instanciar un objeto de transferencia de datos (dto)
* para la tabla 'Configuracion'
* 
* Ejemplo de uso: 
* $Configuracion = new Configuracion();
*   
* 
* @package ninguno 
* @author Daniel Tamayo <it@virtualsoft.tech>
* @version ninguna
* @access public 
* @see no
* 
*/
class Configuracion
{

    /**
    * Representación de la columna 'configId' de la tabla 'Configuracion'
    *
    * @var string
    */		
	var $configId;

    /**
    * Representación de la columna 'limiteLineas' de la tabla 'Configuracion'
    *
    * @var string
    */
	var $limiteLineas;

    /**
    * Representación de la columna 'ticketPie1' de la tabla 'Configuracion'
    *
    * @var string
    */
	var $ticketPie1;

    /**
    * Representación de la columna 'ticketPie2' de la tabla 'Configuracion'
    *
    * @var string
    */
	var $ticketPie2;

    /**
    * Representación de la columna 'ticketPie3' de la tabla 'Configuracion'
    *
    * @var string
    */
	var $ticketPie3;

    /**
    * Representación de la columna 'ticketPie4' de la tabla 'Configuracion'
    *
    * @var string
    */
	var $ticketPie4;

    /**
    * Representación de la columna 'ticketPie5' de la tabla 'Configuracion'
    *
    * @var string
    */
	var $ticketPie5;

    /**
    * Representación de la columna 'diasExpira' de la tabla 'Configuracion'
    *
    * @var string
    */
	var $diasExpira;

    /**
    * Representación de la columna 'email1' de la tabla 'Configuracion'
    *
    * @var string
    */
	var $email1;

    /**
    * Representación de la columna 'email2' de la tabla 'Configuracion'
    *
    * @var string
    */
	var $email2;

    /**
    * Representación de la columna 'email3' de la tabla 'Configuracion'
    *
    * @var string
    */
	var $email3;

    /**
    * Representación de la columna 'accesoPublico' de la tabla 'Configuracion'
    *
    * @var string
    */
	var $accesoPublico;

    /**
    * Representación de la columna 'tiempoRotaProg' de la tabla 'Configuracion'
    *
    * @var string
    */
	var $tiempoRotaProg;

    /**
    * Representación de la columna 'limiteLogro' de la tabla 'Configuracion'
    *
    * @var string
    */
	var $limiteLogro;

    /**
    * Representación de la columna 'premioMax' de la tabla 'Configuracion'
    *
    * @var string
    */
	var $premioMax;

    /**
    * Representación de la columna 'listaId' de la tabla 'Configuracion'
    *
    * @var string
    */
	var $listaId;

    /**
    * Representación de la columna 'regaloRegistro' de la tabla 'Configuracion'
    *
    * @var string
    */
	var $regaloRegistro;

    /**
    * Representación de la columna 'resultadoDias' de la tabla 'Configuracion'
    *
    * @var string
    */
	var $resultadoDias;

    /**
    * Representación de la columna 'tiempoRotaEtiqueta' de la tabla 'Configuracion'
    *
    * @var string
    */
	var $tiempoRotaEtiqueta;

    /**
    * Representación de la columna 'intercalaEtiqueta1' de la tabla 'Configuracion'
    *
    * @var string
    */
	var $intercalaEtiqueta1;

    /**
    * Representación de la columna 'intercalaEtiqueta2' de la tabla 'Configuracion'
    *
    * @var string
    */
	var $intercalaEtiqueta2;

    /**
    * Representación de la columna 'usulistabaseId' de la tabla 'Configuracion'
    *
    * @var string
    */
	var $usulistabaseId;

    /**
    * Representación de la columna 'recargaPie1' de la tabla 'Configuracion'
    *
    * @var string
    */
	var $recargaPie1;

    /**
    * Representación de la columna 'recargaPie2' de la tabla 'Configuracion'
    *
    * @var string
    */
	var $recargaPie2;

    /**
    * Representación de la columna 'recargaPie3' de la tabla 'Configuracion'
    *
    * @var string
    */
	var $recargaPie3;

    /**
    * Representación de la columna 'recargaPie4' de la tabla 'Configuracion'
    *
    * @var string
    */
	var $recargaPie4;

    /**
    * Representación de la columna 'premioMax1' de la tabla 'Configuracion'
    *
    * @var string
    */
	var $premioMax1;

    /**
    * Representación de la columna 'premioMax2' de la tabla 'Configuracion'
    *
    * @var string
    */
	var $premioMax2;

    /**
    * Representación de la columna 'premioMax3' de la tabla 'Configuracion'
    *
    * @var string
    */
	var $premioMax3;

    /**
    * Representación de la columna 'valorDirecto' de la tabla 'Configuracion'
    *
    * @var string
    */
	var $valorDirecto;

    /**
    * Representación de la columna 'minCaduca' de la tabla 'Configuracion'
    *
    * @var string
    */
	var $minCaduca;

    /**
    * Representación de la columna 'premioDirecto' de la tabla 'Configuracion'
    *
    * @var string
    */
	var $premioDirecto;

    /**
    * Representación de la columna 'porcenRegaloRecarga' de la tabla 'Configuracion'
    *
    * @var string
    */
	var $porcenRegaloRecarga;

    /**
    * Representación de la columna 'mandante' de la tabla 'Configuracion'
    *
    * @var string
    */
	var $mandante;

    /**
    * Representación de la columna 'valorEvento' de la tabla 'Configuracion'
    *
    * @var string
    */
	var $valorEvento;

    /**
    * Representación de la columna 'valorDiario' de la tabla 'Configuracion'
    *
    * @var string
    */
	var $valorDiario;

    /**
    * Representación de la columna 'premioMaxP' de la tabla 'Configuracion'
    *
    * @var string
    */
	var $premioMaxP;

    /**
    * Representación de la columna 'premioMax1P' de la tabla 'Configuracion'
    *
    * @var string
    */
	var $premioMax1P;

    /**
    * Representación de la columna 'premioMax2P' de la tabla 'Configuracion'
    *
    * @var string
    */
	var $premioMax2P;

    /**
    * Representación de la columna 'premioMax3P' de la tabla 'Configuracion'
    *
    * @var string
    */
	var $premioMax3P;

    /**
    * Representación de la columna 'limiteLineasP' de la tabla 'Configuracion'
    *
    * @var string
    */
	var $limiteLineasP;

    /**
    * Representación de la columna 'valorDirectoP' de la tabla 'Configuracion'
    *
    * @var string
    */
	var $valorDirectoP;

    /**
    * Representación de la columna 'valorEventoP' de la tabla 'Configuracion'
    *
    * @var string
    */
	var $valorEventoP;

    /**
    * Representación de la columna 'valorDiarioP' de la tabla 'Configuracion'
    *
    * @var string
    */
	var $valorDiarioP;

    /**
    * Representación de la columna 'porcenComision' de la tabla 'Configuracion'
    *
    * @var string
    */
	var $porcenComision;

    /**
    * Representación de la columna 'porcenComision2' de la tabla 'Configuracion'
    *
    * @var string
    */
	var $porcenComision2;

    /**
    * Representación de la columna 'valorCupo' de la tabla 'Configuracion'
    *
    * @var string
    */
	var $valorCupo;

    /**
    * Representación de la columna 'valorCupo2' de la tabla 'Configuracion'
    *
    * @var string
    */
	var $valorCupo2;

    /**
    * Representación de la columna 'periodoBodega' de la tabla 'Configuracion'
    *
    * @var string
    */
	var $periodoBodega;

    /**
    * Representación de la columna 'procesoRecarga' de la tabla 'Configuracion'
    *
    * @var string
    */
	var $procesoRecarga;

    /**
    * Representación de la columna 'facturaTexto1' de la tabla 'Configuracion'
    *
    * @var string
    */
	var $facturaTexto1;

    /**
    * Representación de la columna 'facturaTexto2' de la tabla 'Configuracion'
    *
    * @var string
    */
	var $facturaTexto2;

    /**
    * Representación de la columna 'facturaTexto3' de la tabla 'Configuracion'
    *
    * @var string
    */
	var $facturaTexto3;

    /**
    * Representación de la columna 'facturaTexto4' de la tabla 'Configuracion'
    *
    * @var string
    */
	var $facturaTexto4;

    /**
    * Representación de la columna 'facturaTexto5' de la tabla 'Configuracion'
    *
    * @var string
    */
	var $facturaTexto5;

    /**
    * Representación de la columna 'facturaTexto6' de la tabla 'Configuracion'
    *
    * @var string
    */
	var $facturaTexto6;

    /**
    * Representación de la columna 'facturaTexto7' de la tabla 'Configuracion'
    *
    * @var string
    */
	var $facturaTexto7;

    /**
    * Representación de la columna 'pedirAnexoDoc' de la tabla 'Configuracion'
    *
    * @var string
    */
	var $pedirAnexoDoc;

    /**
    * Representación de la columna 'contingenciaItainment' de la tabla 'Configuracion'
    *
    * @var string
    */
	var $contingenciaItainment;

    /**
    * Representación de la columna 'porcenIva' de la tabla 'Configuracion'
    *
    * @var string
    */
	var $porcenIva;

    /**
    * Representación de la columna 'valorIva' de la tabla 'Configuracion'
    *
    * @var string
    */
	var $valorIva;

}

?>