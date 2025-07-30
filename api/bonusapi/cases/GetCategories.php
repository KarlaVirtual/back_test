<?php

use Backend\dto\PaisMandante;
use Backend\dto\UsuarioMandante;
use Backend\dto\CategoriaMandante;

/**
 * Obtiene las categorías de juegos según el tipo especificado para un mandante y país determinado
 * 
 * Este recurso permite obtener la lista de categorías de juegos disponibles para un mandante específico,
 * filtradas por país y tipo de juego (CASINO, LIVECASINO, VIRTUAL).
 * 
 * @return array{
 *   HasError: bool,
 *   AlertType: string,
 *   AlertMessage: string,
 *   ModelErrors: array,
 *   Data: array<array{
 *     Id: int,
 *     Name: string
 *   }>
 * }
 */
    // Inicializa el objeto UsuarioMandante con el ID de usuario de la sesión
    $UsuarioMandante = new UsuarioMandante($_SESSION['usuario2']);

    // Obtiene el ID del mandante (partner) de la sesión y establece variables iniciales
    $Partner = $_SESSION['mandante'];
    $CountryId = '';
    $Type = $_REQUEST['Type'];
    $CountryId = '';

    // Determina el ID del país según las condiciones de la sesión
    if($_SESSION['PaisCondS'] != '') {
        $CountryId = ($_SESSION['PaisCondS']);
    } else {
        if ($_SESSION['PaisCond'] == "S") {
            $CountryId = $_SESSION['pais_id'];
        }
    }

    // Si existe un mandante, busca información adicional del país asociado
    if($Partner!='0'){
        $rules = [];

        array_push($rules, array("field" => "pais_mandante.mandante", "data" => $Partner, "op" => "eq"));
        array_push($rules, array("field" => "pais_mandante.pais_id", "data" => $CountryId, "op" => "eq"));
        array_push($rules, array("field" => "pais_mandante.estado", "data" => "A", "op" => "eq"));

        // Construye el filtro JSON para la consulta
        $filtro = array("rules" => $rules, "groupOp" => "AND");
        $json = json_encode($filtro);

        // Inicializa el objeto PaisMandante y obtiene los datos filtrados
        $PaisMandante = new PaisMandante();
        $paises = $PaisMandante->getPaisMandantesCustom(" pais_mandante.*,pais.* ", "pais_mandante.paismandante_id", "asc", 0, 1000, $json, true);
        $paises = json_decode($paises);

        // Actualiza el ID del país con el valor obtenido de la consulta
        foreach ($paises->data as $key2 => $value2) {
            $CountryId = $value2->{"pais_mandante.pais_id"};
        }
    }

    // Función auxiliar para convertir entre valores numéricos y tipos de categorías
    function getCategoriesType($value) {
        $Types = ['0' => 'CASINO','1' => 'CASINO','3' => 'LIVECASINO', '4' => 'VIRTUAL'];
        return is_numeric($value) ? $Types[$value] : array_search($value, $Types);
    }

    // Prepara las reglas para filtrar las categorías según los parámetros
    $rules = [];
    array_push($rules, ['field' => 'categoria_mandante.mandante', 'data' => $Partner, 'op' => 'eq']);
    array_push($rules, ['field' => 'categoria_mandante.pais_id', 'data' => $CountryId, 'op' => 'eq']);
    array_push($rules, ['field' => 'categoria_mandante.tipo', 'data' => getCategoriesType($Type), 'op' => 'eq']);
    array_push($rules, ['field' => 'categoria_mandante.estado', 'data' => 'A', 'op' => 'eq']);

    // Construye el filtro JSON para la consulta de categorías
    $filter = json_encode(['rules' => $rules, 'groupOp' => 'AND']);

    // Inicializa el objeto CategoriaMandante y obtiene las categorías filtradas
    $CategoriaMandante = new CategoriaMandante();
    $Categories = $CategoriaMandante->getCategoriaMandanteCustom('*', 'categoria_mandante.orden', 'asc', 0, 100000, $filter, true);
    $Categories = json_decode($Categories);

    // Prepara el array para almacenar las categorías procesadas
    $AllCategories = [];

    // Procesa cada categoría y extrae los datos relevantes (ID y nombre)
    foreach($Categories->data as $key => $value) {
        $data = [];
        $data['Id'] = $value->{'categoria_mandante.catmandante_id'};
        $data['Name'] = $value->{'categoria_mandante.descripcion'};

        array_push($AllCategories, $data);
    }

    // Prepara la respuesta con las categorías obtenidas y los indicadores de éxito
    $response['HasError'] = false;
    $response['AlertType'] = 'success';
    $response['AlertMessage'] = '';
    $response['ModelErrors'] = [];
    $response['Data'] = $AllCategories;
?>