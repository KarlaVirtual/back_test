<?php
use Backend\dto\Usuario;

/**
 * Obtiene redes aliadas disponibiles para el proceso de retiros
 *
 * @param object $json Objeto JSON que contiene la solicitud.
 *  - rid: int ID de la solicitud.
 * @return array Respuesta con el código de estado, ID de la solicitud y datos de los métodos de pago.
 *  - code: int Código de estado de la respuesta.
 *  - rid: int ID de la solicitud de la que proviene la respuesta.
 *  - data: array Datos de los métodos de pago.
 * @throws Exception Si ocurre un error al procesar la solicitud.
 */
$UsuarioMandante = $UsuarioMandanteSite;

/*Agrega un método de pago específico a la lista si el usuario es del mandante 1.*/
$final=array();
if($UsuarioMandante->mandante ==1) {
    $array = array(
        'value' => '5996264',
        'name' => 'MMG',
        'min' => '2000',
        'max' => '20000'

    );
   array_push($final,$array);

}

if($UsuarioMandante->mandante ==8){
    /*Agrega métodos de pago específicos a la lista según el mandante del usuario.*/
    $array=array(
        'value'=>'693978',
        'name'=>'Facilito',
        'min'=>'1',
        'max'=>'500'

    );

    // Se comentan las líneas que agregan servicios al arreglo final

    $array=array(
        'value'=>'853460',
        'name'=>'Red Activa Western Union',
        'min'=>'5',
        'max'=>'500'

    );
    $day = date('N');

       array_push($final,$array);

    $array=array(
        'value'=>'1211624',
        'name'=>'Bemovil',
        'min'=>'5',
        'max'=>'500'

    );
    //array_push($final,$array);

    /*Define un arreglo con información de métodos de pago y lo agrega a la lista final.*/
    $array=array(
        'value'=>'1784692',
        'name'=>'Bakan',
        'min'=>'5',
        'max'=>'500'

    );
    //array_push($final,$array);

    $array=array(
        'value'=>'2894342',
        'name'=>'Full Carga',
        'min'=>'5',
        'max'=>'500'

    );
    array_push($final,$array);

    /* Se comenta por solicitud del acount
    $array=array(
        'value'=>'5446026',
        'name'=>'Mi Negocio Efectivo',
        'min'=>'5',
        'max'=>'500'

    );
    array_push($final,$array);*/

    /*Agrega métodos de pago específicos a la lista según el mandante del usuario.*/
    $array=array(
        'value'=>'6283508',
        'name'=>'Farmacias Medicity',
        'min'=>'5',
        'max'=>'500'

    );
    array_push($final,$array);

    $array=array(
        'value'=>'6283508',
        'name'=>'Farmacias Economicas',
        'min'=>'5',
        'max'=>'500'

    );
    array_push($final,$array);

    $array=array(
        'value'=>'8773898',
        'name'=>'Ponle más',
        'min'=>'5',
        'max'=>'1500'

    );
    array_push($final,$array);
}

/*Verifica el mandante y país del usuario para agregar información de la tienda.*/
if($UsuarioMandante->mandante == 0 && $UsuarioMandante->paisId == 173) {
    // Se crea una nueva instancia de la clase Usuario utilizando el usuario mandante
    $Usuario = new Usuario($UsuarioMandante->getUsuarioMandante());

    if(true){

        // Se define un arreglo con información de la tienda
        $array = array(
            'value' => '5758546', // Valor asociado a la tienda
            'name' => 'Tiendas Tambo', // Nombre de la tienda
            'min' => '50', // Valor mínimo
            'max' => '100' // Valor máximo
        );
        //array_push($final,$array);


    }
}

// Se prepara la respuesta para ser devuelta
$response = array();
$response["code"] = 0; // Código de respuesta
$response["rid"] = $json->rid; // ID de la solicitud
$response["data"] = $final; // Datos a ser devueltos en la respuesta