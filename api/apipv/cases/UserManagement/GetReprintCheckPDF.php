<?php

/**
 * UserManagement/GetReprintCheckPDF
 *
 * Este script genera un PDF con los detalles de un cheque basado en su número.
 *
 * @param object $params Objeto que contiene los siguientes parámetros:
 * @param string $params->NroCheck Número del cheque a consultar.
 *
 * @return array Respuesta en formato JSON con los siguientes valores:
 *  - HasError (boolean): Indica si ocurrió un error.
 *  - AlertType (string): Tipo de alerta (e.g., "success", "error").
 *  - AlertMessage (string): Mensaje asociado a la alerta.
 *  - Pdf (string): Contenido del PDF codificado en base64.
 */

use Backend\dto\Cheque;
use Backend\dto\CuentaCobro;
use Backend\dto\ItTicketEnc;
use Backend\dto\RegistroRapido;
use Backend\dto\Usuario;
use Backend\dto\Mandante;


/* Se asignan valores a variables desde parámetros y sesión, y se obtiene la fecha actual. */
$NroCheck = $params->NroCheck;

$Usuario = new Usuario($_SESSION["usuario"]);


$FromDateLocal = date("Y-m-d");

/* define una fecha local y un arreglo para reglas de comparación. */
$ToDateLocal = date("Y-m-d");

$rules = [];

//array_push($rules, array("field" => "usuario_recarga.fecha_crea", "data" => "$ToDateLocal", "op" => "le"));


$grouping = "";

/* Código inicializa variables para selección y gestión de filas en una consulta. */
$select = "";

$SkeepRows = 0;

$OrderedItem = 1;

$MaxRows = 1;


/* Construye y codifica un filtro con reglas para una consulta SQL sobre cheques. */
array_push($rules, array("field" => "cheque.nro_cheque", "data" => "$NroCheck ", "op" => "eq"));

$filtro = array("rules" => $rules, "groupOp" => "AND");
$jsonfiltro = json_encode($filtro);

$select = "  cheque.mandante,cheque.origen,cheque.id,cheque.nro_cheque,case when cheque.origen='NR' then 'Nota Retiro' else 'Ticket' end origen,cheque.documento_id,case when cheque.origen='NR' then cuenta_cobro.valor else it_ticket_enc.vlr_premio end valor,case when cheque.origen='NR' then usuario.moneda when it_ticket_enc.tipo_beneficiario='RN' then usuariobeneficiario.moneda else registro_rapido.moneda end moneda,case when cheque.origen='NR' then usuario.nombre when usuariobeneficiario.nombre is null then concat(registro_rapido.nombre1,' ',registro_rapido.nombre2,' ',registro_rapido.apellido1,' ',registro_rapido.apellido2) else usuariobeneficiario.nombre end cliente,case when cheque.origen='NR' then registro.cedula when usuariobeneficiario.nombre is null then registro_rapido.cedula else registro.cedula end cedula,case when cheque.origen='NR' then cuenta_cobro.fecha_crea else concat(it_ticket_enc.fecha_crea,' ',it_ticket_enc.hora_crea) end fecha ";


/* obtiene cheques personalizados para un usuario específico en un país particular. */
$paisId = $_SESSION["pais_id"];
$usuarioId = $_SESSION["usuario"];


$Cheque = new Cheque();

$transacciones = $Cheque->getChequesCustom($usuarioId, $select, "cheque.id", "desc", $SkeepRows, $MaxRows, $jsonfiltro, true, $paisId);


/* decodifica JSON y crea un objeto "Mandante" a partir de datos transaccionales. */
$transacciones = json_decode($transacciones);
$transaccion = $transacciones->data[0];

$Mandante = new Mandante($transaccion->{'cheque.mandante'});

if ($transaccion->{"cheque.origen"} == "NR") {


    /* Se crea un objeto "CuentaCobro" y se inicializan variables para la paginación. */
    $CuentaCobro = new CuentaCobro();

    $SkeepRows = 0;
    $MaxRows = 1;


    $rules = array();


    /* Código que filtra cuentas de cobro usando criterios JSON y los obtiene en una consulta. */
    array_push($rules, array("field" => "cuenta_cobro.cuenta_id", "data" => $transaccion->{"cheque.documento_id"}, "op" => "eq"));


    $filtro = array("rules" => $rules, "groupOp" => "AND");
    $json = json_encode($filtro);


    $cuentas = $CuentaCobro->getCuentasCobroCustom('cuenta_cobro.cuenta_id,cuenta_cobro.valor,usuario.nombre,
								ciudad.ciudad_nom,
								cuenta_cobro.fecha_crea', "cuenta_cobro.cuenta_id", "desc", $SkeepRows, $MaxRows, $json, true, $grouping);


    /* Decodifica datos JSON y extrae información de una transacción y usuario. */
    $cuentas = json_decode($cuentas);
    $cuenta = $cuentas->data[0];


    $nro_cheque = $transaccion->{"cheque.nro_cheque"};
    $nombre = $cuenta->{"usuario.nombre"};

    /* Extrae y formatea información de transacciones y cuentas en variables específicas. */
    $ciudad = $cuenta->{"ciudad.ciudad_nom"};
    $tipo = $transaccion->{"cheque.origen"};
    $fecha_crea2 = $cuenta->{"cuenta_cobro.fecha_crea"};
    $fecha_crea = strftime("%B %d de %Y", strtotime($fecha_crea2));

    $tipo_txt = "Pago nota de retiro";
    if ($tipo != "NR")

        /* formatea un valor monetario para un pago de premio. */
        $tipo_txt = "Pago de premio";
    $valor_sin_formato = $cuenta->{"cuenta_cobro.valor"};
    $valor = number_format($valor_sin_formato, 2);

} else {


    /* Creación de un objeto y definición de variables para gestión de tickets. */
    $ItTicketEnc = new ItTicketEnc();

    $SkeepRows = 0;
    $MaxRows = 1;


    $rules = array();


    /* Se construye una consulta JSON para filtrar tickets con condiciones específicas. */
    array_push($rules, array("field" => "it_ticket_enc.ticket_id", "data" => $transaccion->{"cheque.documento_id"}, "op" => "eq"));


    $filtro = array("rules" => $rules, "groupOp" => "AND");
    $json = json_encode($filtro);

    $cuentas = $ItTicketEnc->getTicketsCustom("it_ticket_enc.ticket_id,
                                    it_ticket_enc.tipo_beneficiario,    
    								it_ticket_enc.vlr_premio valor,
    								it_ticket_enc.beneficiario_id,
    								usuario.nombre,
								CONCAT(it_ticket_enc.fecha_crea,' ',it_ticket_enc.hora_crea) fecha_crea", "it_ticket_enc.ticket_id", "desc", $SkeepRows, $MaxRows, $json, true, $grouping);


    /* decodifica un JSON y asigna valores de usuario y cliente. */
    $cuentas = json_decode($cuentas);
    $cuenta = $cuentas->data[0];

    $nombre = $cuenta->{"usuario.nombre"};
    $nombre = $transaccion->{".cliente"};


    /*    if($cuenta->{"it_ticket_enc.tipo_beneficiario"} != 'RN'){
            $rules = [];

            $grouping = "";
            $select = "";
            $SkeepRows = 0;
            $OrderedItem = 1;
            $MaxRows = 1;

            array_push($rules, array("field" => "registro_rapido.registro_id", "data" => $cuenta->{'it_ticket_enc.beneficiario_id'}, "op" => "eq"));

            $select = " registro_rapido.apellido1,registro_rapido.apellido2,registro_rapido.nombre1,registro_rapido.nombre2 ";

            $filtro = array("rules" => $rules, "groupOp" => "AND");
            $jsonfiltro = json_encode($filtro);

            $RegistroRapido = new RegistroRapido();

            $registros = $RegistroRapido->getRegistrosRapidosCustom($select, "registro_rapido.registro_id", "desc", $SkeepRows, $MaxRows, $jsonfiltro, '');


            $registros = json_decode($registros);
            $registro = $registros->data[0];

            $nombre = $registro->{"registro_rapido.nombre1"} . ' '.$registro->{"registro_rapido.nombre2"} . ' '.$registro->{"registro_rapido.apellido1"} . ' '.$registro->{"registro_rapido.apellido2"};


        }*/


    /* Asigna y formatea información de transacciones y cuentas en variables PHP. */
    $nro_cheque = $transaccion->{"cheque.nro_cheque"};
    $ciudad = $cuenta->{"ciudad.ciudad_nom"};
    $tipo = $transaccion->{"cheque.origen"};
    $fecha_crea2 = $cuenta->{".fecha_crea"};
    $fecha_crea = strftime("%B %d de %Y", strtotime($fecha_crea2));

    $tipo_txt = "Pago nota de retiro";
    if ($tipo != "NR")

        /* Formatea el valor de un ticket a dos decimales para un pago de premio. */
        $tipo_txt = "Pago de premio";
    $valor_sin_formato = $cuenta->{"it_ticket_enc.valor"};
    $valor = number_format($valor_sin_formato, 2);

}


/* obtiene la fecha actual formateada en un string específico. */
$fecha = strftime("%B %d de %Y");


$pdf = '
		<html>
  <head>
    <style type="text/css">
     .div-inline>div{
  display:inline-block !important;
}
.div-100{
  width:100%;
}
.div-50{
  width:45%;
}
.component-patern{

}
.text-center{
  text-align:center;
}
.text-right{
  text-align:right;
}
.border-line{
  border-bottom:1px solid #000;
}

.border-square{
  border: 1px solid #000;
  height:10px;
  width:30px;
  padding-top:10px;
  text-align:center;
  margin-left:2px;
}
.div-space-margin{
  height:20px;
}

.float-right {
    float: right !important;
}

.div-60 {
    width: 55%;
}

.float-left {
    float: left !important;
}

.div-40 {
    width: 44%;
}

.component {
    margin-bottom: 10px;
}

.component-patern-title {
    margin-bottom: 5px;
}
.div-center {
    text-align: center;
}
#ingreso_mensual .title {
    min-width: 160px;
}

.ingreso_mensual_child {
    margin: 5px 5px 5px 5px;
    width:240px !important;
}

body {
    padding: 15px 30px;

}
.div-pie-pagina{
  text-align:center;
  text-transform:uppercase;
}

    </style>
  </head>
  <body>
  <div class="div-cheque" style="border: 1px solid #000; padding:20px;">
    <div class="div-header">
    <div class="div-header-left float-left" style="width:300px;">

    <div class="div-inline" style="margin-bottom: 10px; width:100px; height:auto;"><img src="' . $Mandante->logoPdf . '"></div>
    <div class="div-inline " >' . $legal_nombre . '</div>
    <div class="div-inline " >' . $legal_numero . '</div>
      </div>
         <div class="div-header-right float-right" style="width:400px;" >
             <div class="div-space-margin"></div>

    <div class="div-inline cheque-numero div-100 text-right" >
      <div class="div-content float-right" style="width:100px; color:red;font-weight: bold;font-size:20px;margin-left:10px;text-align:left;top:100px;width:100px;">No ' . $nro_cheque . '</div>
      <div class="div-title float-right" style="width:120px;margin-top:5px;margin-right:10px;">Cheque Serie "A"          </div>

           </div>

    <div class="div-space-margin"></div>

 <div class="div-inline ciudad" >
      <div class="div-content  float-left" style="width:150px;">
        <div class="div-title float-left" style="width:50px;">Ciudad: </div>
        <div class="div-content float-left" style="width:100px; text-transform:capitalize;">' . $ciudad . ' </div>
      </div>
      </div>
       <div class="div-inline ciudad" >

      <div class="div-content  float-left" style="width:350px;">
            <div class="div-title float-left" style="width:150px;">Fecha de realización: </div>

            <div class="div-content  float-left" style="width:150px; text-transform:capitalize;">' . $fecha_crea . '</div>

</div>
           </div>
           </div>
      </div>
    </div>

    <div class="div-space-margin"></div>

    <div class="component-patern div-inline ">
      <div class="div-inline component div-100">
        <div class="title float-left" style="width:70px;">Paguese a :</div>
        <div class="content float-left border-line" style="width:88%;text-align:left;padding-left:10px;">' . $nombre . '</div>
      </div>
      <div class="div-inline component div-100">
        <div class="title float-left" style="width:30px;">' . $Usuario->moneda . '</div>
        <div class="content float-left border-line" style="width:100px;text-align:left;padding-left:10px;">' . $valor . '</div>
        <div class="float-left " style="width:300px;margin-left:10px;color:#000;">' . $_SESSION['moneda_nom'] . '</div>
      </div>

    </div>
        <div class="div-space-margin"></div>

    <div class="component-patern div-inline">
      <div class="div-inline component div-100">
        <div class="float-right border-line" style="width:400px; text-align:center;margin-right:20px;color:white;">XXXX</div>
      </div>


    </div>

    <div class="div-space-margin"></div>

<div class="div-100 div-center">

    <div class="div-pie-pagina div-100" >Cheque de caja ' . $compania . ' - no negociable - no transferible</div>
      </div>
    </div>


    <div class="div-egreso" style="border: 1px solid #000; padding:20px;margin-top:20px;">
    <div class="div-header">
    <div class="div-header-left float-left" style="width:300px;">

    <div class="div-inline" style="margin-bottom: 10px; width:100px; height:auto;"><img src="' . $Mandante->logoPdf . '"></div>
    <div class="div-inline " >' . $legal_nombre . '</div>
    <div class="div-inline " >' . $legal_numero . '</div>
      </div>
         <div class="div-header-right float-right" style="width:400px;" >
             <div class="div-space-margin"></div>

    <div class="div-inline div-100 text-right" >
      <div class="div-title float-left div-100 text-center" style="background:rgb(212, 170, 74); border:1px solid #000;border-radius:5px 5px 0px 0px ;font-size:25px;padding:10px 0px 10px 0px;">COMPROBANTE DE CHEQUE</div>

      <div class="div-title float-left div-100 text-center" style="border:1px solid #000;border-radius:0px 0px 5px 5px;font-size:20px;padding:10px 0px 10px 0px;">No ' . $nro_cheque . '         </div>

           </div>
      </div>
    </div>
    <div class="div-space-margin"></div>

    <div class="component-patern div-inline ">
      <div class="float-left text-center" style="background:rgb(212, 170, 74); border:1px solid #000;border-radius:5px 0px 0px 0px ;font-size:20px;padding:10px 0px 10px 0px;width:70%">Concepto</div>
      <div class="float-left text-center" style="background:rgb(212, 170, 74); border:1px solid #000;border-radius:0px 5px 0px 0px ;font-size:20px;padding:10px 0px 10px 0px;width:29%;">Valor</div>

    </div>

     <div class="component-patern div-inline ">
      <div class="float-left text-center" style=" border:1px solid #000;font-size:15px;padding:10px 0px 10px 0px;width:70%">' . $tipo_txt . '</div>
      <div class="float-left text-center" style=" border:1px solid #000;font-size:15px;padding:10px 0px 10px 0px;width:29%">' . $valor . '</div>

    </div>

    <div class="component-patern div-inline ">
      <div class="float-left text-center" style=" border:1px solid #000;font-size:15px;padding:10px 0px 10px 0px;width:70%;color:white;">Concepto</div>
      <div class="float-left text-center" style=" border:1px solid #000;font-size:15px;padding:10px 0px 10px 0px;width:29%;color:white;">Valor</div>

    </div>

    <div class="component-patern div-inline ">
      <div class="float-left text-center" style=" border:1px solid #000;font-size:15px;padding:10px 0px 10px 0px;width:70%;color:white;">Concepto</div>
      <div class="float-left text-center" style=" border:1px solid #000;font-size:15px;padding:10px 0px 10px 0px;width:29%;color:white;">Valor</div>

    </div>

    <div class="component-patern div-inline ">
      <div class="float-left text-center" style=" border:1px solid #000;font-size:15px;padding:10px 0px 10px 0px;width:70%;color:white;">Concepto</div>
      <div class="float-left text-center" style=" border:1px solid #000;font-size:15px;padding:10px 0px 10px 0px;width:29%;color:white;">Valor</div>

    </div>

    <div class="component-patern div-inline ">
      <div class="float-left text-center" style=" border:1px solid #000;font-size:15px;padding:10px 0px 0px 0px;width:60%;height:100px">
       <div style="height:100px;border-bottom:1px solid #000;text-transform: uppercase;text-align:center;line-height:90px;">' . $nombre . '</div>

        <div class="float-right text-center" style="font-size:15px;padding:10px 0px 10px 0px;width:49%;"><div class="float-left" style="margin-left:10px" style="width:70px;">Aprobado</div>
          <div class="float-right" style="margin-right:20px;width:70px; color:white;"> XXXXX</div></div>
        <div class="float-right text-center" style="border-right:1px solid #000;font-size:15px;padding:10px 0px 10px 0px;width:50%;height:40px">
          <div class="float-left" style="width:70px;">Elaborado</div>
          <div class="float-right" style="margin-right:20px;width:70px; color:white;"> XXXXX</div>
        </div>
      </div>
      <div class="float-left" style=" border:1px solid #000;font-size:15px;padding:10px 0px 0px 0px;width:39%">
          <div class="float-left" style=" font-size:15px;padding:5px 10px 10px 10px;width:200px">Firma del Beneficiario</div>

               <div style="height:100px;border-bottom:1px solid #000;"></div>
<div class="float-left" style=" font-size:15px;padding:5px 10px 10px 10px;border-bottom:1px solid #000;width:100%">
    <div class="float-left" style="width:135px">
        <div class="float-left" style="width:130px;">
     <div class="float-left" style="padding-top:0px;width:130px;">No. Documento:</div>

  </div>

  </div>


  <div class="float-left" style="width:70px;">
     <div class="float-left" style="width:70px;"></div>
  </div>

        </div>
<div class="float-left div-100" style=" font-size:15px;">
        <div class="float-left" style="width:45%;">
     <div class="float-left" style="padding:5px 0px 0px 5px;width:100%;">Fecha de Recibido</div>

  </div>
  <div class="float-left"  style="width:15%;border-left:1px solid #000;padding-left:5px;">
     <div class="float-left" style="padding-top:5px;width:15px;">D</div>

  </div>
<div class="float-left" style="width:15%;border-left:1px solid #000;padding-left:5px;">
     <div class="float-left" style="padding-top:5px;width:15px;">M</div>

  </div>
  <div class="float-left" style="width:15%;border-left:1px solid #000;padding-left:5px;">
     <div class="float-left" style="padding-top:5px;width:15px;">A</div>

  </div>

        </div>


      </div>

    </div>





    </div>


  </body>

</html>


		';


/* Crea un objeto Mpdf para generar PDF con formato A4 en paisaje y márgenes espejados. */
$mpdf = new \Mpdf\Mpdf(['format' => 'A4-L', 'tempDir' => '/tmp']);

//$mpdf = new mPDF('c', 'A4', '', '', 5, 5, 5, 5, 0, 0);

$mpdf->mirrorMargins = 1; // Use different Odd/Even headers and footers and mirror margins (1 or 0)

$mpdf->SetDisplayMode('fullpage', 'two');

// LOAD a stylesheet
//$stylesheet = file_get_contents('mdpdf/stylemdpdf.css');
//$mpdf->WriteHTML($stylesheet, 1); // The parameter 1 tells that this is css/style only and no body/html/text


/* genera un PDF y lo guarda en la ruta especificada. */
$mpdf->WriteHTML($pdf);

$mpdf->Output('/tmp' . "mpdf.pdf", "F");

$path = '/tmp' . 'mpdf.pdf';

$type = pathinfo($path, PATHINFO_EXTENSION);

/* lee un archivo, lo codifica en base64 y lo almacena en una respuesta. */
$data = file_get_contents($path);
$base64 = 'data:application/' . $type . ';base64,' . base64_encode($data);

$encoded_html = base64_encode($pdf);

$response["Pdf"] = base64_encode($data);