<?php

use Backend\dto\Banco;
use Backend\dto\BonoDetalle;
use Backend\dto\Categoria;
use Backend\dto\CategoriaProducto;
use Backend\dto\Clasificador;
use Backend\dto\ConfigurationEnvironment;
use Backend\dto\Consecutivo;
use Backend\dto\CuentaCobro;
use Backend\dto\Descarga;
use Backend\dto\DocumentoUsuario;
use Backend\dto\IntApuestaDetalle;
use Backend\dto\IntCompetencia;
use Backend\dto\IntDeporte;
use Backend\dto\IntEventoApuesta;
use Backend\dto\IntEventoApuestaDetalle;
use Backend\dto\IntEventoDetalle;
use Backend\dto\IntRegion;
use Backend\dto\ItTicketEnc;
use Backend\dto\Mandante;
use Backend\dto\MandanteDetalle;
use Backend\dto\PaisMoneda;
use Backend\dto\PromocionalLog;
use Backend\dto\PuntoVenta;
use Backend\dto\Registro;
use Backend\dto\TransaccionJuego;
use Backend\dto\TransjuegoLog;
use Backend\dto\Usuario;
use Backend\dto\UsuarioBanco;
use Backend\dto\UsuarioConfig;
use Backend\dto\UsuarioConfiguracion;
use Backend\dto\UsuarioLog;
use Backend\dto\UsuarioMandante;
use Backend\dto\UsuarioOtrainfo;
use Backend\dto\UsuarioPerfil;
use Backend\dto\UsuarioPremiomax;
use Backend\dto\UsuarioPublicidad;
use Backend\dto\UsuarioRecarga;
use Backend\dto\UsuarioToken;
use Backend\dto\UsuarioMensaje;
use Backend\dto\ProductoMandante;
use Backend\dto\ProdMandanteTipo;
use Backend\dto\Proveedor;
use Backend\dto\Producto;
use Backend\dto\Pais;
use Backend\integrations\casino\IES;
use Backend\integrations\casino\JOINSERVICES;
use Backend\mysql\ConsecutivoMySqlDAO;
use Backend\mysql\CuentaCobroMySqlDAO;
use Backend\mysql\DocumentoUsuarioMySqlDAO;
use Backend\mysql\PromocionalLogMySqlDAO;
use Backend\mysql\PuntoVentaMySqlDAO;
use Backend\mysql\RegistroMySqlDAO;
use Backend\mysql\TransaccionJuegoMySqlDAO;
use Backend\mysql\UsuarioBancoMySqlDAO;
use Backend\mysql\UsuarioBannerMySqlDAO;
use Backend\mysql\UsuarioConfiguracionMySqlDAO;
use Backend\mysql\UsuarioLogMySqlDAO;
use Backend\mysql\UsuarioMandanteMySqlDAO;
use Backend\mysql\UsuarioMensajeMySqlDAO;
use Backend\mysql\UsuarioMySqlDAO;
use Backend\mysql\UsuarioOtrainfoMySqlDAO;
use Backend\mysql\UsuarioPerfilMySqlDAO;
use Backend\mysql\UsuarioPremiomaxMySqlDAO;
use Backend\mysql\UsuarioRecargaMySqlDAO;
use Backend\mysql\UsuarioTokenMySqlDAO;
use Backend\websocket\WebsocketUsuario;

/**
 * Obtiene las categorías de productos de un casino y las organiza en una estructura jerárquica con subcategorías y juegos.
 *
 * @param object $params Objeto que contiene los parámetros necesarios para la consulta.
 * @param int $params->MaxRows Número máximo de filas a obtener.
 * @param int $params->OrderedItem Elemento ordenado.
 * @param int $params->SkeepRows Número de filas a omitir.
 * @param object $json Objeto JSON que contiene la sesión del usuario.
 * @param object $json->session Objeto que contiene la sesión del usuario.
 * @param object $json->session->usuario Usuario de la sesión.
 * @param int $json->rid Identificador de la solicitud.
 *
 * @throws Exception Si ocurre un error al obtener las categorías.
 *
 * @return array
 *  -code:int Código de respuesta.
 *  -rid:int Identificador de la solicitud.
 *  -data:array Arreglo de datos.
 *      -Id:int Id de la categoría.
 *      -Name:string Nombre de la categoría.
 *      -Subcategories:array Arreglo de subcategorías.
 */

// Se obtienen los parámetros necesarios para la consulta
$MaxRows = $params->MaxRows;
$OrderedItem = $params->OrderedItem;
$SkeepRows = $params->SkeepRows;
$UsuarioMandante = new UsuarioMandante($json->session->usuario);
$UsuarioPerfil = new UsuarioPerfil($UsuarioMandante->getUsuarioMandante());

// Se verifica si SkeepRows está vacío y se asigna un valor por defecto
if ($SkeepRows == "") {
    $SkeepRows = 0; // Valor por defecto para SkeepRows
}

// Se verifica si OrderedItem está vacío y se asigna un valor por defecto
if ($OrderedItem == "") {
    $OrderedItem = 1;
}

// Se verifica si MaxRows está vacío y se asigna un valor por defecto
if ($MaxRows == "") {
    $MaxRows = 1000;
}

// Se instancia el objeto Categoria
$Categoria = new Categoria();


//$Categoria->setTipo("LEGCASINO");
//$Categoria->setTipo("CASINO");

//$Categorias = $Categoria->getCategoriasTipo();

$rules = [];

// Se agregan reglas de filtrado
array_push($rules, array("field" => "categoria.tipo", "data" => "CASINO", "op" => "eq"));
array_push($rules, array("field" => "categoria_producto.mandante", "data" => $UsuarioMandante->getMandante(), "op" => "eq"));

// Se crea el filtro basado en las reglas definidas
$filtro = array("rules" => $rules, "groupOp" => "AND");
$jsonfiltro = json_encode($filtro);

// Se define la consulta para seleccionar los campos deseados
$select = "categoria.*,categoria_producto.*,producto.*";
$CategoriaProducto = new CategoriaProducto();
$data = $CategoriaProducto->getCategoriaProductosCustom($select,"categoria.categoria_id",'asc','0','10000',$jsonfiltro,true);
$Categorias = json_decode($data);
$categoriasData = array();
foreach ($Categorias->data as $key => $value) {
    $Id = $value->{"categoria_producto.categoria_id"};
    if(isset($categoriasData[$Id])){
        //Id y Name de Subcategorias pendiente
        /*El código obtiene categorías de productos de un casino, las organiza en una estructura jerárquica con subcategorías
         y juegos, y devuelve esta información en una respuesta JSON.*/
        $idSubCategoria = $Id;
        $nameSubCategoria = "General";
        // Crear categoria si no existe
        if(!isset($categoriasData[$Id]->Subcategories[$idSubCategoria])){
            $categoriasData[$Id]->Subcategories[$idSubCategoria] = new stdClass();
            $categoriasData[$Id]->Subcategories[$idSubCategoria]->Id = $idSubCategoria;
            $categoriasData[$Id]->Subcategories[$idSubCategoria]->Name = $nameSubCategoria;
            $categoria = new stdClass(); // Se crea un nuevo objeto para la categoría

        $categoria->Id = $idSubCategoria; // Se asigna el Id de la categoría
        $categoria->Name = $nameSubCategoria; // Se asigna el nombre de la categoría

            array_push($categoriasData[$Id]->Subcategories,$categoria);
        }
        // Crear Juego
        $IdGame = $value->{"categoria_producto.producto_id"};
        if(!isset($categoriasData[$Id]->Subcategories[$idSubCategoria]->Games[$IdGame])){

            $game = new stdClass();

            $game->Id = $IdGame;
            $game->Name = $value->{"producto.descripcion"};

            array_push($categoriasData[$Id]->Subcategories[$idSubCategoria]->Games,$game);
        }

    }else{
        // Si la categoría no existe, se crea.
        $categoriasData[$Id] = new stdClass();
        $categoriasData[$Id]->Id = $Id;
        $categoriasData[$Id]->Name = $value->{"categoria.descripcion"};
        $categoriasData[$Id]->Subcategories = array();
        //Id y Name de Subcategorias pendiente
        $idSubCategoria = $Id;
        $nameSubCategoria = "General";
        $categoriasData[$Id]->Subcategories[$idSubCategoria] = new stdClass();
        $categoriasData[$Id]->Subcategories[$idSubCategoria]->Id = $idSubCategoria;
        $categoriasData[$Id]->Subcategories[$idSubCategoria]->Name = $nameSubCategoria;
        $categoriasData[$Id]->Subcategories[$idSubCategoria]->Games = array();
        $IdGame = $value->{"categoria_producto.producto_id"};
        /* $categoriasData[$Id]->Subcategories[$idSubCategoria]->Games[$IdGame] = new stdClass();
         $categoriasData[$Id]->Subcategories[$idSubCategoria]->Games[$IdGame]->Id = $IdGame;
         $categoriasData[$Id]->Subcategories[$idSubCategoria]->Games[$IdGame]->Name = $value->{"producto.descripcion"};
        */
        $game = new stdClass();

        $game->Id = $IdGame;
        $game->Name = $value->{"producto.descripcion"};

        array_push($categoriasData[$Id]->Subcategories[$idSubCategoria]->Games,$game);

    }
    /*  $array = array();
      $array["Id"] = $value->{"categoria_producto.categoria_id"};
      $array["Name"] = $value->{"categoria.descripcion"};
      $array["Subcategories"] = array(
          "Id" =>  "Id Subcategoria",
          "Name" => "Sub Prueba",
          "Games" => array(
              "Id" => $value->{"categoria_producto.producto_id"},
              "Name" => $value->{"producto.descripcion"}

          )
      );


      array_push($categoriasData, $array);

  */
}

/*El código obtiene categorías de productos de un casino, las organiza en una estructura jerárquica con subcategorías y juegos,
 y devuelve esta información en una respuesta JSON.*/
$categoriasDatanew = array();
foreach ($categoriasData as $c){
    $cnew = array();
    $cnew['Id'] = $c->Id;
    $cnew['Name'] = $c->Name;
    $cnew['Subcategories'] = array();
    foreach ($c->Subcategories as $subc){
        array_push($cnew['Subcategories'],$subc);
    }
    array_push($categoriasDatanew,$cnew);

}

//Formateo de respuesta
$response = array();
$response["code"] = 0;
$response["rid"] = $json->rid;
$response["data"] = $categoriasDatanew;

