<?php

use Backend\dto\ConfigurationEnvironment;
use Backend\dto\FormulariosGenericos;
use Backend\dto\UsuarioMandante;
use Backend\dto\Pais;
use Backend\dto\Template;
use Backend\dto\Clasificador;
use Backend\dto\Usuario;
use Backend\mysql\FormulariosGenericosMySqlDAO;

/**
 * command/send_form
 *
 * Envia los formularios diligenciados.
 *
 * @param string $data : Contenido del formulario a enviar
 *
 * El objeto $response es un array con los siguientes atributos:
 *  - *code* (int): Codigo de error desde el proveedor
 *  - *rid* (string): Contiene el mensaje de error.
 *  - *data* (array): vacio
 *  - *error_code* (int): Codigo de error
 *  - *msg* (string): Contiene el mensaje de error o de aprobación.
 *
 * Objeto en caso de error:
 *
 * $response['error_code'] = $ex->getCode();
 * $response['msg'] = $ex->getCode() == '1000' ? $ex->getMessage() : 'General error';
 *
 *
 * @throws Exception No existen datos
 *
 * @access public
 * @see no
 * @since no
 * @deprecated no
 */


/* inicializa una respuesta con datos y año actual en PHP. */
$response = [];
$response['code'] = 0;
$response['msg'] = 'Success';
$response['rid'] = $json->rid;
$response['data'] = [];

$year = date('Y');


/* Crea instancias de UsuarioMandante y FormulariosGenericos, obteniendo un ID genérico. */
$UsuarioMandante = new UsuarioMandante($json->session->usuario);
$Usuario = new Usuario($UsuarioMandante->usuarioMandante);

$genericoId = '';

try {
    $FormulariosGenericos = new FormulariosGenericos('', $UsuarioMandante->usuarioMandante, $UsuarioMandante->mandante, $year, $UsuarioMandante->paisId, 'SPLAFT');
    $genericoId = $FormulariosGenericos->getFormGenericoId();
} catch (Exception $ex) {
    /* Manejo de excepciones en PHP; captura errores sin ejecutar ninguna acción. */

}

if (empty($genericoId)) {
    try {

        /* Se asigna el valor de "params" de un objeto JSON a la variable $data. */
        $data = $json->params;
        if (empty($data)) throw new Exception('No existen datos', 01);

        // function sendError() {
        //     throw new Exception('Error en la validacion de los datos', 1000);
        // }

        // $dataValidation = [
        //     'paternal_last_name' => 'string',
        //     'mother_last_name' => 'string',
        //     'first_name' => 'string',
        //     'nationality_id' => 'string',
        //     'doc_type' => 'string',
        //     'doc_nationality' => 'string',
        //     'identification' => 'string',
        //     'email' => 'string',
        //     'phone' => 'string',
        //     'equity_capital_declaration' => 'string',
        //     'origin_money_declaration' => 'string',
        //     'illegal_activities_declaration' => 'string',
        //     'is_a_pep' => 'string',
        //     'family_members_pep' => 'string',
        //     'know_activities_family_member' => 'string',
        //     'situations_to_be_reported' => 'string',
        //     'terms_and_conditions' => 'boolean'
        // ];

        /*foreach($dataValidation as $key => $value) {
            $valueParts = explode('|', $value);
            $type = $valueParts[0];
            $isNull = $valueParts[1] ?: '';
            if(!empty($isNull)) {
                if (!isset($data->{$key})) {
                    sendError();
                };
            }
            if(!empty($isNull)) {
                if(empty($data->{$key})) continue;
                elseif(gettype($data->{$key}) !== $type) sendError();
            } elseif(gettype($data->{$key}) !== $type) sendError();
        }*/

        $data->{'terms_and_conditions'} = $data->{'terms_and_conditions'} === 'on' ? true : false;

        /* Asigna la fecha y crea un objeto con apellidos del usuario. */
        $data->{'date_completed'} = date('Y-m-d H:i:s');

        $newData = new stdClass();

        $newData->{'Apellido paterno'} = $data->paternal_last_name;
        $newData->{'Apellido materno'} = $data->mother_last_name;

        /* Asignación de datos y conversión de tipos de documentos en un objeto. */
        $newData->{'Nombre'} = $data->first_name;
        $Pais = new Pais($data->nationality_id);
        $newData->{'Nacionalidad'} = $Pais->paisNom;

        switch ($data->doc_type) {
            case 'P':
                $data->doc_type = 'Pasaporte';
                break;
            case 'C':
                $data->doc_type = 'DNI';
                break;
            case 'E':
                $data->doc_type = 'Cedula extranjeria';
                break;
        }


        /* Asigna valores a un nuevo objeto a partir de datos existentes. */
        $newData->{'Tipo Documento'} = $data->doc_type;

        $newData->{'Pais Nacionalidad'} = $Pais->paisNom;
        $newData->{'Identificacion'} = $data->identification;
        $newData->{'Email'} = $data->email;
        $newData->{'Celular'} = $data->phone;

        /* Asigna datos de ocupación y declaraciones a un nuevo objeto basado en condiciones específicas. */
        $newData->{'Ocupacion'} = $data->occupation;
        $newData->{'1. Declaro que no soy un intermediario en esta operación y que los fondos para efectuar la apuesta materia de este premio son propios'} = $data->equity_capital_declaration === 'S' ? 'Si' : 'No';
        $newData->{'2. Declaro que el origen del dinero con el que realicé esta apuesta ganadora proviene de: (ejemplos: mi trabajo, un préstamo, una herencia, un premio, un seguro, etc.)'} = $data->origin_money_declaration;
        $newData->{'3. Declaro que el dinero con el que realicé esta apuesta ganadora en ningún caso involucra contagio con actividades ilícitas propias o de terceras personas'} = $data->illegal_activities_declaration === 'S' ? 'Si' : 'No';

        $newData->{'SOY UNA PEP'} = $data->is_a_pep == 'S' ? 'Si' : 'No';

        /* asigna datos sobre personas expuestas políticamente a una nueva estructura. */
        $newData->{'En caso de SI, detallar el cargo'} = $data->is_a_pep_detail;
        $newData->{'TENGO FAMILIARES DIRECTOS QUE SON PEP'} = $data->family_members_pep === 'S' ? 'Si' : 'No';
        $newData->{'Nombre y apellidos del familiar'} = $data->family_member_fullname;
        $newData->{'CONOZCO LAS ACTIVIDADES DE CIERTOS FAMILIARES DIRECTOS'} = $data->know_activities_family_member === 'S' ? 'Si' : 'No';
        $newData->{'TENGO NADA QUE DECLARAR'} = $data->situations_to_be_reported === 'S' ? 'Si' : 'No';
        $newData->{'DETALLE DE DECLARACION'} = $data->situations_to_be_reported_detail;


        /* Se crea un objeto con datos de formulario y visualización, usando una clase específica. */
        $dataDetail = new stdClass();

        $dataDetail->form = $data;
        $dataDetail->visual = $newData;

        $FormulariosGenericos = new FormulariosGenericos();

        /* Configuración de un objeto 'FormulariosGenericos' con datos y atributos específicos. */
        $FormulariosGenericos->setFormData(json_encode($dataDetail));
        $FormulariosGenericos->setUsuarioId($UsuarioMandante->usuarioMandante);
        $FormulariosGenericos->setDiligenciado('S');
        $FormulariosGenericos->setAnio($year);
        $FormulariosGenericos->setMandante($UsuarioMandante->mandante);
        $FormulariosGenericos->setPaisId($UsuarioMandante->paisId);

        /* inserta un formulario genérico en la base de datos usando MySQL. */
        $FormulariosGenericos->setTipo('SPLAFT');
        $FormulariosGenericos->setUsucreaId($UsuarioMandante->usuarioMandante);

        $FormulariosGenericosMySqlDAO = new FormulariosGenericosMySqlDAO();
        $FormulariosGenericosMySqlDAO->insert($FormulariosGenericos);
        $FormulariosGenericosMySqlDAO->getTransaction()->commit();

        try {
            // $CLasificador = new Clasificador('', 'TEMFORMGENERIC');
            // $Template = new Template('', $UsuarioMandante->mandante, $CLasificador->clasificadorId, $UsuarioMandante->paisId, strtolower($Usuario->idioma));

            // $html = $Template->templateHtml;

            $html = '<!DOCTYPE html>
                <html lang="en">
                <head>
                    <meta charset="UTF-8">
                    <meta http-equiv="X-UA-Compatible" content="IE=edge">
                    <meta name="viewport" content="width=device-width, initial-scale=1.0">
                    <title>Document</title>
                    <style>
                        * {
                            padding: 0;
                            margin: 0;
                            box-sizing: border-box;
                        }
                
                        .container {
                            width: 80%;
                            margin: auto;
                        }
                
                        .pdf-header-title {
                            text-align: center;
                            font-size: 1em;
                            background: #BFBFBF;
                            padding: 6px;
                            border: 1px solid #000000;
                        }
                
                        .pdf-header-text {
                            padding: 10px;
                            text-align: center;
                        }
                        
                        .personal-information {
                            width: 100%;
                        }

                        .personal-information th {
                            text-align: left;
                        }
                
                        .pdf-body ul {
                            list-style: upper-roman;
                            padding-left: 25px;
                        }
                
                        .pdf-body ul li {
                            padding-left: 10px;
                            margin-bottom: 50px;
                        }
                
                        .personal-information {
                            border-collapse: collapse;
                        }
                
                        .table-header {
                            background: #9A3365;
                            color: #ffffff;
                            padding: 4px;
                            font-size: 0.9em;
                        }
                
                        .personal-information th, .personal-information td {
                            padding: 5px;
                            border: 1px solid #000000;
                        }
                
                    </style>
                </head>
                <body>
                    <div class="container">
                        <div class="pdf-header">
                            <h1 class="pdf-header-title">FORMATO DE DECLARACIÓN - CLIENTE GANADOR DE PREMIOS MAYORES A S/. 8 MIL</h1>
                            <p class="pdf-header-text">
                                <u>Declaro bajo juramento</u> que la información consignada es verdadera y actual, y que la consigno de <b>manera voluntaria</b> a solicitud de Interplay Word SAC, en el marco de las
                                normas para la prevención del lavado de activos y financiamiento del terrorismo.
                                <b>Autorizo a Interplay Word SAC a remitirla a la UIF-Perú</b> en caso dicha entidad, o quien haga sus veces, lo requiera.
                            </p>
                        </div>
                        <div class="pdf-body">
                            <ul>
                                <li>
                                    <table class="personal-information">
                                        <thead>
                                            <tr>
                                                <th class="table-header" colspan="2">DATOS PERSONALES</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <th>Apellido Paterno:</th>
                                                <td>#paternName</td>
                                                <th>Apellidos Maternos:</th>
                                                <td>#motherName</td>
                                            </tr>
                                            <tr>
                                                <th>Nombres:</th>
                                                <td>#fullName</td>
                                                <th>Nacionalidad:</th>
                                                <td>#nationality</td>
                                            </tr>
                                            <tr>
                                                <th>Documento de Identidad:</th>
                                                <td>
                                                    <div>
                                                        <div>
                                                            <label for="dni">D.N.I</label>
                                                            <input type="checkbox" value="D.N.I" id="dni" #docTypeD disabled>
                                                        </div>
                                                        <div>
                                                            <label for="carnet">Carne Extranjeria</label>
                                                            <input type="checkbox" value="Carné Extranjería" id="carnet" #doctTypeC disabled>
                                                        </div>
                                                    </div>
                                                    </td>
                                                <th>Pais: #country</th>
                                                <td>N°: #document</td>
                                            </tr>
                                            <tr>
                                                <th>Correo electronico:</th>
                                                <td>#email</td>
                                                <th>Telefono:</th>
                                                <td>#phone</td>
                                            </tr>
                                            <tr>
                                                <th>Ocupación:</th>
                                                <td colspan="3">#occupation</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </li>
                                <li>
                                    <table class="personal-information">
                                        <thead>
                                            <tr>
                                                <th class="table-header">DE LA TRANSACCIÓN Y EL ORIGEN DE LOS FONDOS</th>
                                                <th style="text-align: center;" colspan="4">MARCAR/COMPLETAR</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>1. Declaro que no soy un intermediario en esta operación y que los fondos para efectuar la apuesta
                                                    materia de este premio son propios.
                                                </td>
                                                <th>SI SOY:</th>
                                                <td>#isMe</td>
                                                <th>NO SOY:</th>
                                                <td>#isNotMe</td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    2. Declaro que el origen del dinero con el que realicé esta apuesta ganadora proviene de: (ejemplos: mi trabajo, un préstamo, una herencia, un premio, un seguro, etc).
                                                </td>
                                                <td colspan="4">#equitDetail</td>
                                            </tr>
                                            <tr>
                                               <td>
                                                3. Declaro que el dinero con el que realicé esta apuesta ganadora en ningún caso involucra contagio con actividades ilícitas propias o de terceras personas.
                                               </td>
                                               <th>SI INVOLUCRA:</th>
                                               <td>#involveS</td>
                                               <th>NO INVOLUCRA:</th>
                                               <td>#involveN</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </li>
                                <li>
                                    <table class="personal-information">
                                        <thead>
                                            <tr>
                                                <th class="table-header" colspan="1">DE LA TRANSACCIÓN Y EL ORIGEN DE LOS FONDOS</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td colspan="3">
                                                    Las personas expuestas políticamente (PEP) son personas naturales, nacionales o extranjeras, que cumplen o que en los últimos 5 años han cumplido funciones
                                                    públicas destacadas o funciones prominentes en una organización internacional, en Perú o el extranjero (Por ejemplo: presidentes, ministros, gobernadores
                                                    regionales, alcaldes, congresistas, jueces, fiscales, gerentes, cargos de primer nivel en entidades del Estado, organismos constitucionalmente autónomos, partidos
                                                    políticos, cargos de primer nivel en las fuerzas armadas (Ejercito, Marina de Guerra y Áerea) y fuerzas de la seguridad pública (policia), embajadores y en general
                                                    todos aquellos colaboradores directos y máxima autoridad de las instituciones a las que pertencen).<br>
                                                    <b>Utilizando esta definición, declaro bajo juramento que:</b>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <div>
                                                        <div>
                                                            <label for="pep-s">SÍ SOY UNA PEP</label>
                                                            <input type="checkbox" value="isPEP" id="pep-s" #isPepS disabled>
                                                        </div>
                                                        <div>
                                                            <label for="pep-n">NO SOY UNA PEP</label>
                                                            <input type="checkbox" value="notPEP" id="pep-n" #isPepN disabled>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>En caso de SI, detallar el cargo:</td>
                                                <td>#isPepDetail</td>
                                            </tr>
                                            <tr>
                                                <td style="padding-right: 20px;">
                                                    <div>
                                                        <div>
                                                            <label for="family-pep-s">SÍ TENGO FAMILIARES DIRECTOS QUE SON PEP</label>
                                                            <input type="checkbox" value="have pep fam" id="family-pep-s" #havePepFa disabled>
                                                        </div>
                                                        <div>
                                                            <label for="family-pep-n">NO TENGO FAMILIARES DIRECTOS QUE SON PEP</label>
                                                            <input type="checkbox" value="not have pep fam" id="family-pep-n" #haveNotPepFa disabled>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>Nombre/ apellidos del familiar:</td>
                                                <td style="width: 40%;">#pepFamilyName</td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    NO CONOZCO LAS ACTIVIDADES DE CIERTOS FAMILIARES DIRECTOS
                                                </td>
                                                <td>
                                                    <label for="if-know-s">SI CONOZCO</label>
                                                    <input type="checkbox" value="know" id="if-know-s" #ifKnow disabled>
                                                </td>
                                                <td>
                                                    <label for="if-know-n">NO CONOZCO</label>
                                                    <input type="checkbox" value="yes know" id="if-know-n" #ifNotKnow disabled>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </li>
                                <li>
                                    <table class="personal-information">
                                        <thead>
                                            <tr>
                                                <th class="table-header">RELACIÓN CON EL SECTOR PÚBLICO (PEP)</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td colspan="3">
                                                    <b>Situaciones que deben declararse:</b><br>
                                                    1. Tengo algún familiar directo, novio/a o roommate que labora en Interplay/ Doradobet.<br>
                                                    2. Soy (o algún familiar directo es) ludópata.<br>
                                                    3. Soy (o algún familiar directo es) deportista profesional, árbitro, juez deportivo, entrenador profesional, dirigente deportivo o alguna ocupación afín.
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <div>
                                                        <div>
                                                            <label for="declare-s">SÍ TENGO ALGO QUE DECLARAR</label>
                                                            <input type="checkbox" value="declaratio yes" id="declare-s" #declareS disabled>
                                                        </div>
                                                        <div>
                                                            <label for="declare-n">NO TENGO NADA QUE DECLARAR</label>
                                                            <input type="checkbox" value="declaration not" id="declare-n" #declareN disabled>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>En caso de Sí, detallar:</td>
                                                <td style="width: 35%;">#declarationDetail</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </li>
                            </ul>
                        </div>
                    </div>
                </body>
                </html>';


            /* combina nombres y reemplaza marcadores en una cadena HTML. */
            $fullName = $data->first_name . ' ' . $data->paternal_last_name . ' ' . $data->mother_last_name;

            $html = str_replace('#name', $fullName, $html);
            $html = str_replace('#paternName', $data->paternal_last_name, $html);
            $html = str_replace('#motherName', $data->mother_last_name, $html);
            $html = str_replace('#fullName', $data->first_name, $html);

            /* Reemplaza marcadores en HTML con datos específicos de un objeto en PHP. */
            $html = str_replace('#document', $data->identification, $html);
            $html = str_replace('#nationality', $Pais->paisNom, $html);
            $html = str_replace('#docTypeD', $data->doc_type === 'DNI' ? 'checked' : '', $html);
            $html = str_replace('#docTypeC', $data->doc_type !== 'DNI' ? 'checked' : '', $html);
            $html = str_replace('#email', $data->email, $html);
            $html = str_replace('#country', $Pais->paisNom, $html);

            /* reemplaza marcadores en HTML con datos específicos de un objeto. */
            $html = str_replace('#occupation', $data->occupation, $html);
            $html = str_replace('#phone', $data->phone, $html);

            $html = str_replace('#isMe', $data->equity_capital_declaration === 'S' ? 'SI' : '', $html);
            $html = str_replace('#isNotMe', $data->equity_capital_declaration === 'N' ? 'NO' : '', $html);
            $html = str_replace('#involveS', $data->illegal_activities_declaration === 'S' ? 'SI' : '', $html);

            /* Sustituye marcadores en HTML con datos del objeto $data para generar contenido dinámico. */
            $html = str_replace('#involveN', $data->illegal_activities_declaration === 'N' ? 'NO' : '', $html);
            $html = str_replace('#equitDetail', $data->origin_money_declaration, $html);

            $html = str_replace('#isPepS', $data->is_a_pep === 'S' ? 'checked' : '', $html);
            $html = str_replace('#isPepN', $data->is_a_pep === 'N' ? 'checked' : '', $html);
            $html = str_replace('#isPepDetail', empty($data->is_a_pep_detail) ? '' : $data->is_a_pep_detail, $html);

            /* Reemplaza marcadores en HTML con valores basados en condiciones del objeto $data. */
            $html = str_replace('#havePepFa', $data->family_members_pep === 'S' ? 'checked' : '', $html);
            $html = str_replace('#haveNotPepFa', $data->family_members_pep === 'N' ? 'checked' : '', $html);
            $html = str_replace('#pepFamilyName', $data->family_member_fullname ?: ' ', $html);
            $html = str_replace('#ifKnow', $data->know_activities_family_member === 'S' ? 'checked' : '', $html);
            $html = str_replace('#ifNotKnow', $data->know_activities_family_member === 'N' ? 'checked' : '', $html);

            $html = str_replace('#declareS', $data->situations_to_be_reported === 'S' ? 'checked' : '', $html);

            /* Sustituye marcadores en HTML con valores basados en condiciones de datos. */
            $html = str_replace('#declareN', $data->situations_to_be_reported === 'N' ? 'checked' : '', $html);
            $html = str_replace('#declarationDetail', empty($data->situations_to_be_reported_detail) ? '' : $data->situations_to_be_reported_detail, $html);

            $ConfigurationEnvironment = new ConfigurationEnvironment();

        } catch (Exception $ex) {
            /* Captura excepciones en PHP para manejar errores sin interrumpir la ejecución del código. */

        }


        /* Envía un correo con detalles del formulario SPLAFT a usuarios específicos. */
        $subjet = 'Formulario SPLAFT ' . $fullName . ' - ' . $year;

        $ConfigurationEnvironment->EnviarCorreoVersion2($Usuario->login, 'noreply@doradobet.com', 'Doradobet', $subjet, 'mail_registro.php', 'Formulario diligenciado', $html, '', '', '', $UsuarioMandante->mandante, true);
        $ConfigurationEnvironment->EnviarCorreoVersion2('Oficialdecumplimiento@doradobet.com', 'noreply@doradobet.com', 'Doradobet', $subjet, 'mail_registro.php', 'Formulario diligenciado', $html, '', '', '', $UsuarioMandante->mandante, true, true);

    } catch (Exception $ex) {
        /* Maneja excepciones, captura errores y genera mensajes personalizados en función del código. */

        $response = [];
        $response['error_code'] = $ex->getCode();
        $response['msg'] = $ex->getCode() == '1000' ? $ex->getMessage() : 'General error';
    }
}

if (!empty($genericoId)) {
    try {

        /* Extrae los parámetros de un objeto JSON y los asigna a la variable $data. */
        $data = $json->params;
        if (empty($data)) throw new Exception('No existen datos', 01);

        // function sendError() {
        //     throw new Exception('Error en la validacion de los datos', 1000);
        // }

        // $dataValidation = [
        //     'paternal_last_name' => 'string',
        //     'mother_last_name' => 'string',
        //     'first_name' => 'string',
        //     'nationality_id' => 'string',
        //     'doc_type' => 'string',
        //     'doc_nationality' => 'string',
        //     'identification' => 'string',
        //     'email' => 'string',
        //     'phone' => 'string',
        //     'equity_capital_declaration' => 'string',
        //     'origin_money_declaration' => 'string',
        //     'illegal_activities_declaration' => 'string',
        //     'is_a_pep' => 'string',
        //     'family_members_pep' => 'string',
        //     'know_activities_family_member' => 'string',
        //     'situations_to_be_reported' => 'string',
        //     'terms_and_conditions' => 'boolean'
        // ];

        /*foreach($dataValidation as $key => $value) {
            $valueParts = explode('|', $value);
            $type = $valueParts[0];
            $isNull = $valueParts[1] ?: '';
            if(!empty($isNull)) {
                if (!isset($data->{$key})) {
                    sendError();
                };
            }
            if(!empty($isNull)) {
                if(empty($data->{$key})) continue;
                elseif(gettype($data->{$key}) !== $type) sendError();
            } elseif(gettype($data->{$key}) !== $type) sendError();
        }*/

        $data->{'terms_and_conditions'} = $data->{'terms_and_conditions'} === 'on' ? true : false;

        /* Código para asignar fecha actual y crear objeto con apellidos. */
        $data->{'date_completed'} = date('Y-m-d H:i:s');

        $newData = new stdClass();

        $newData->{'Apellido paterno'} = $data->paternal_last_name;
        $newData->{'Apellido materno'} = $data->mother_last_name;

        /* asigna valores a un objeto basado en datos de entrada. */
        $newData->{'Nombre'} = $data->first_name;
        $Pais = new Pais($data->nationality_id);
        $newData->{'Nacionalidad'} = $Pais->paisNom;

        switch ($data->doc_type) {
            case 'P':
                $data->doc_type = 'Pasaporte';
                break;
            case 'C':
                $data->doc_type = 'DNI';
                break;
            case 'E':
                $data->doc_type = 'Cedula extranjeria';
                break;
        }


        /* Asigna valores de $data y $Pais a las propiedades de $newData. */
        $newData->{'Tipo Documento'} = $data->doc_type;

        $newData->{'Pais Nacionalidad'} = $Pais->paisNom;
        $newData->{'Identificacion'} = $data->identification;
        $newData->{'Email'} = $data->email;
        $newData->{'Celular'} = $data->phone;

        /* Asigna valores a nuevas propiedades en un objeto según datos de entrada. */
        $newData->{'Ocupacion'} = $data->occupation;
        $newData->{'1. Declaro que no soy un intermediario en esta operación y que los fondos para efectuar la apuesta materia de este premio son propios'} = $data->equity_capital_declaration === 'S' ? 'Si' : 'No';
        $newData->{'2. Declaro que el origen del dinero con el que realicé esta apuesta ganadora proviene de: (ejemplos: mi trabajo, un préstamo, una herencia, un premio, un seguro, etc.)'} = $data->origin_money_declaration;
        $newData->{'3. Declaro que el dinero con el que realicé esta apuesta ganadora en ningún caso involucra contagio con actividades ilícitas propias o de terceras personas'} = $data->illegal_activities_declaration === 'S' ? 'Si' : 'No';

        $newData->{'SOY UNA PEP'} = $data->is_a_pep == 'S' ? 'Si' : 'No';

        /* Asigna valores a un nuevo objeto basado en datos de otro objeto. */
        $newData->{'En caso de SI, detallar el cargo'} = $data->is_a_pep_detail;
        $newData->{'TENGO FAMILIARES DIRECTOS QUE SON PEP'} = $data->family_members_pep === 'S' ? 'Si' : 'No';
        $newData->{'Nombre y apellidos del familiar'} = $data->family_member_fullname;
        $newData->{'CONOZCO LAS ACTIVIDADES DE CIERTOS FAMILIARES DIRECTOS'} = $data->know_activities_family_member === 'S' ? 'Si' : 'No';
        $newData->{'TENGO NADA QUE DECLARAR'} = $data->situations_to_be_reported === 'S' ? 'Si' : 'No';
        $newData->{'DETALLE DE DECLARACION'} = $data->situations_to_be_reported_detail;


        /* Crea un objeto con datos y lo codifica en JSON para procesarlo. */
        $dataDetail = new stdClass();

        $dataDetail->form = $data;
        $dataDetail->visual = $newData;

        //$FormulariosGenericos = new FormulariosGenericos();
        $FormulariosGenericos->setFormData(json_encode($dataDetail));

        /* establece datos específicos en un objeto de formularios genéricos. */
        $FormulariosGenericos->setUsuarioId($UsuarioMandante->usuarioMandante);
        $FormulariosGenericos->setDiligenciado('S');
        $FormulariosGenericos->setAnio($year);
        $FormulariosGenericos->setMandante($UsuarioMandante->mandante);
        $FormulariosGenericos->setPaisId($UsuarioMandante->paisId);
        $FormulariosGenericos->setTipo('SPLAFT');

        /* Actualiza datos de un formulario genérico en la base de datos. */
        $FormulariosGenericos->setUsucreaId($UsuarioMandante->usuarioMandante);

        $FormulariosGenericosMySqlDAO = new FormulariosGenericosMySqlDAO();
        $FormulariosGenericosMySqlDAO->update($FormulariosGenericos);
        $FormulariosGenericosMySqlDAO->getTransaction()->commit();

        try {
            // $CLasificador = new Clasificador('', 'TEMFORMGENERIC');
            // $Template = new Template('', $UsuarioMandante->mandante, $CLasificador->clasificadorId, $UsuarioMandante->paisId, strtolower($Usuario->idioma));

            // $html = $Template->templateHtml;

            $html = '<!DOCTYPE html>
                <html lang="en">
                <head>
                    <meta charset="UTF-8">
                    <meta http-equiv="X-UA-Compatible" content="IE=edge">
                    <meta name="viewport" content="width=device-width, initial-scale=1.0">
                    <title>Document</title>
                    <style>
                        * {
                            padding: 0;
                            margin: 0;
                            box-sizing: border-box;
                        }
                
                        .container {
                            width: 80%;
                            margin: auto;
                        }
                
                        .pdf-header-title {
                            text-align: center;
                            font-size: 1em;
                            background: #BFBFBF;
                            padding: 6px;
                            border: 1px solid #000000;
                        }
                
                        .pdf-header-text {
                            padding: 10px;
                            text-align: center;
                        }
                        
                        .personal-information {
                            width: 100%;
                        }

                        .personal-information th {
                            text-align: left;
                        }
                
                        .pdf-body ul {
                            list-style: upper-roman;
                            padding-left: 25px;
                        }
                
                        .pdf-body ul li {
                            padding-left: 10px;
                            margin-bottom: 50px;
                        }
                
                        .personal-information {
                            border-collapse: collapse;
                        }
                
                        .table-header {
                            background: #9A3365;
                            color: #ffffff;
                            padding: 4px;
                            font-size: 0.9em;
                        }
                
                        .personal-information th, .personal-information td {
                            padding: 5px;
                            border: 1px solid #000000;
                        }
                
                    </style>
                </head>
                <body>
                    <div class="container">
                        <div class="pdf-header">
                            <h1 class="pdf-header-title">FORMATO DE DECLARACIÓN - CLIENTE GANADOR DE PREMIOS MAYORES A S/. 8 MIL</h1>
                            <p class="pdf-header-text">
                                <u>Declaro bajo juramento</u> que la información consignada es verdadera y actual, y que la consigno de <b>manera voluntaria</b> a solicitud de Interplay Word SAC, en el marco de las
                                normas para la prevención del lavado de activos y financiamiento del terrorismo.
                                <b>Autorizo a Interplay Word SAC a remitirla a la UIF-Perú</b> en caso dicha entidad, o quien haga sus veces, lo requiera.
                            </p>
                        </div>
                        <div class="pdf-body">
                            <ul>
                                <li>
                                    <table class="personal-information">
                                        <thead>
                                            <tr>
                                                <th class="table-header" colspan="2">DATOS PERSONALES</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <th>Apellido Paterno:</th>
                                                <td>#paternName</td>
                                                <th>Apellidos Maternos:</th>
                                                <td>#motherName</td>
                                            </tr>
                                            <tr>
                                                <th>Nombres:</th>
                                                <td>#fullName</td>
                                                <th>Nacionalidad:</th>
                                                <td>#nationality</td>
                                            </tr>
                                            <tr>
                                                <th>Documento de Identidad:</th>
                                                <td>
                                                    <div>
                                                        <div>
                                                            <label for="dni">D.N.I</label>
                                                            <input type="checkbox" value="D.N.I" id="dni" #docTypeD disabled>
                                                        </div>
                                                        <div>
                                                            <label for="carnet">Carne Extranjeria</label>
                                                            <input type="checkbox" value="Carné Extranjería" id="carnet" #doctTypeC disabled>
                                                        </div>
                                                    </div>
                                                    </td>
                                                <th>Pais: #country</th>
                                                <td>N°: #document</td>
                                            </tr>
                                            <tr>
                                                <th>Correo electronico:</th>
                                                <td>#email</td>
                                                <th>Telefono:</th>
                                                <td>#phone</td>
                                            </tr>
                                            <tr>
                                                <th>Ocupación:</th>
                                                <td colspan="3">#occupation</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </li>
                                <li>
                                    <table class="personal-information">
                                        <thead>
                                            <tr>
                                                <th class="table-header">DE LA TRANSACCIÓN Y EL ORIGEN DE LOS FONDOS</th>
                                                <th style="text-align: center;" colspan="4">MARCAR/COMPLETAR</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>1. Declaro que no soy un intermediario en esta operación y que los fondos para efectuar la apuesta
                                                    materia de este premio son propios.
                                                </td>
                                                <th>SI SOY:</th>
                                                <td>#isMe</td>
                                                <th>NO SOY:</th>
                                                <td>#isNotMe</td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    2. Declaro que el origen del dinero con el que realicé esta apuesta ganadora proviene de: (ejemplos: mi trabajo, un préstamo, una herencia, un premio, un seguro, etc).
                                                </td>
                                                <td colspan="4">#equitDetail</td>
                                            </tr>
                                            <tr>
                                               <td>
                                                3. Declaro que el dinero con el que realicé esta apuesta ganadora en ningún caso involucra contagio con actividades ilícitas propias o de terceras personas.
                                               </td>
                                               <th>SI INVOLUCRA:</th>
                                               <td>#involveS</td>
                                               <th>NO INVOLUCRA:</th>
                                               <td>#involveN</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </li>
                                <li>
                                    <table class="personal-information">
                                        <thead>
                                            <tr>
                                                <th class="table-header" colspan="1">DE LA TRANSACCIÓN Y EL ORIGEN DE LOS FONDOS</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td colspan="3">
                                                    Las personas expuestas políticamente (PEP) son personas naturales, nacionales o extranjeras, que cumplen o que en los últimos 5 años han cumplido funciones
                                                    públicas destacadas o funciones prominentes en una organización internacional, en Perú o el extranjero (Por ejemplo: presidentes, ministros, gobernadores
                                                    regionales, alcaldes, congresistas, jueces, fiscales, gerentes, cargos de primer nivel en entidades del Estado, organismos constitucionalmente autónomos, partidos
                                                    políticos, cargos de primer nivel en las fuerzas armadas (Ejercito, Marina de Guerra y Áerea) y fuerzas de la seguridad pública (policia), embajadores y en general
                                                    todos aquellos colaboradores directos y máxima autoridad de las instituciones a las que pertencen).<br>
                                                    <b>Utilizando esta definición, declaro bajo juramento que:</b>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <div>
                                                        <div>
                                                            <label for="pep-s">SÍ SOY UNA PEP</label>
                                                            <input type="checkbox" value="isPEP" id="pep-s" #isPepS disabled>
                                                        </div>
                                                        <div>
                                                            <label for="pep-n">NO SOY UNA PEP</label>
                                                            <input type="checkbox" value="notPEP" id="pep-n" #isPepN disabled>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>En caso de SI, detallar el cargo:</td>
                                                <td>#isPepDetail</td>
                                            </tr>
                                            <tr>
                                                <td style="padding-right: 20px;">
                                                    <div>
                                                        <div>
                                                            <label for="family-pep-s">SÍ TENGO FAMILIARES DIRECTOS QUE SON PEP</label>
                                                            <input type="checkbox" value="have pep fam" id="family-pep-s" #havePepFa disabled>
                                                        </div>
                                                        <div>
                                                            <label for="family-pep-n">NO TENGO FAMILIARES DIRECTOS QUE SON PEP</label>
                                                            <input type="checkbox" value="not have pep fam" id="family-pep-n" #haveNotPepFa disabled>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>Nombre/ apellidos del familiar:</td>
                                                <td style="width: 40%;">#pepFamilyName</td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    NO CONOZCO LAS ACTIVIDADES DE CIERTOS FAMILIARES DIRECTOS
                                                </td>
                                                <td>
                                                    <label for="if-know-s">SI CONOZCO</label>
                                                    <input type="checkbox" value="know" id="if-know-s" #ifKnow disabled>
                                                </td>
                                                <td>
                                                    <label for="if-know-n">NO CONOZCO</label>
                                                    <input type="checkbox" value="yes know" id="if-know-n" #ifNotKnow disabled>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </li>
                                <li>
                                    <table class="personal-information">
                                        <thead>
                                            <tr>
                                                <th class="table-header">RELACIÓN CON EL SECTOR PÚBLICO (PEP)</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td colspan="3">
                                                    <b>Situaciones que deben declararse:</b><br>
                                                    1. Tengo algún familiar directo, novio/a o roommate que labora en Interplay/ Doradobet.<br>
                                                    2. Soy (o algún familiar directo es) ludópata.<br>
                                                    3. Soy (o algún familiar directo es) deportista profesional, árbitro, juez deportivo, entrenador profesional, dirigente deportivo o alguna ocupación afín.
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <div>
                                                        <div>
                                                            <label for="declare-s">SÍ TENGO ALGO QUE DECLARAR</label>
                                                            <input type="checkbox" value="declaratio yes" id="declare-s" #declareS disabled>
                                                        </div>
                                                        <div>
                                                            <label for="declare-n">NO TENGO NADA QUE DECLARAR</label>
                                                            <input type="checkbox" value="declaration not" id="declare-n" #declareN disabled>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>En caso de Sí, detallar:</td>
                                                <td style="width: 35%;">#declarationDetail</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </li>
                            </ul>
                        </div>
                    </div>
                </body>
                </html>';


            /* reemplaza marcadores en HTML con nombres del objeto $data. */
            $fullName = $data->first_name . ' ' . $data->paternal_last_name . ' ' . $data->mother_last_name;

            $html = str_replace('#name', $fullName, $html);
            $html = str_replace('#paternName', $data->paternal_last_name, $html);
            $html = str_replace('#motherName', $data->mother_last_name, $html);
            $html = str_replace('#fullName', $data->first_name, $html);

            /* Reemplaza marcadores de posición en HTML con datos dinámicos de un objeto. */
            $html = str_replace('#document', $data->identification, $html);
            $html = str_replace('#nationality', $Pais->paisNom, $html);
            $html = str_replace('#docTypeD', $data->doc_type === 'DNI' ? 'checked' : '', $html);
            $html = str_replace('#docTypeC', $data->doc_type !== 'DNI' ? 'checked' : '', $html);
            $html = str_replace('#email', $data->email, $html);
            $html = str_replace('#country', $Pais->paisNom, $html);

            /* Reemplaza marcadores en HTML con valores obtenidos de un objeto de datos. */
            $html = str_replace('#occupation', $data->occupation, $html);
            $html = str_replace('#phone', $data->phone, $html);

            $html = str_replace('#isMe', $data->equity_capital_declaration === 'S' ? 'SI' : '', $html);
            $html = str_replace('#isNotMe', $data->equity_capital_declaration === 'N' ? 'NO' : '', $html);
            $html = str_replace('#involveS', $data->illegal_activities_declaration === 'S' ? 'SI' : '', $html);

            /* reemplaza marcadores en HTML según datos de una declaración. */
            $html = str_replace('#involveN', $data->illegal_activities_declaration === 'N' ? 'NO' : '', $html);
            $html = str_replace('#equitDetail', $data->origin_money_declaration, $html);

            $html = str_replace('#isPepS', $data->is_a_pep === 'S' ? 'checked' : '', $html);
            $html = str_replace('#isPepN', $data->is_a_pep === 'N' ? 'checked' : '', $html);
            $html = str_replace('#isPepDetail', empty($data->is_a_pep_detail) ? '' : $data->is_a_pep_detail, $html);

            /* Se reemplazan marcadores en HTML según condiciones de los datos proporcionados. */
            $html = str_replace('#havePepFa', $data->family_members_pep === 'S' ? 'checked' : '', $html);
            $html = str_replace('#haveNotPepFa', $data->family_members_pep === 'N' ? 'checked' : '', $html);
            $html = str_replace('#pepFamilyName', $data->family_member_fullname ?: ' ', $html);
            $html = str_replace('#ifKnow', $data->know_activities_family_member === 'S' ? 'checked' : '', $html);
            $html = str_replace('#ifNotKnow', $data->know_activities_family_member === 'N' ? 'checked' : '', $html);

            $html = str_replace('#declareS', $data->situations_to_be_reported === 'S' ? 'checked' : '', $html);

            /* Se reemplazan marcadores en HTML según condiciones de datos y se crea un objeto. */
            $html = str_replace('#declareN', $data->situations_to_be_reported === 'N' ? 'checked' : '', $html);
            $html = str_replace('#declarationDetail', empty($data->situations_to_be_reported_detail) ? '' : $data->situations_to_be_reported_detail, $html);

            $ConfigurationEnvironment = new ConfigurationEnvironment();

        } catch (Exception $ex) {
            /* Código PHP que captura excepciones, pero no realiza ninguna acción en caso de error. */

        }


        /* Se envían correos con el formulario SPLAFT a usuarios específicos. */
        $subjet = 'Formulario SPLAFT ' . $fullName . ' - ' . $year;

        $ConfigurationEnvironment->EnviarCorreoVersion2($Usuario->login, 'noreply@doradobet.com', 'Doradobet', $subjet, 'mail_registro.php', 'Formulario diligenciado', $html, '', '', '', $UsuarioMandante->mandante, true);
        $ConfigurationEnvironment->EnviarCorreoVersion2('Oficialdecumplimiento@doradobet.com', 'noreply@doradobet.com', 'Doradobet', $subjet, 'mail_registro.php', 'Formulario diligenciado', $html, '', '', '', $UsuarioMandante->mandante, true, true);

    } catch (Exception $ex) {
        /* Manejo de excepciones: captura el error y genera un mensaje con código específico. */

        $response = [];
        $response['error_code'] = $ex->getCode();
        $response['msg'] = $ex->getCode() == '1000' ? $ex->getMessage() : 'General error';
    }
}

?>