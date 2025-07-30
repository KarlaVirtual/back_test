<?php

namespace Backend\cms;

use Backend\dto\BonoInterno;
use Backend\dto\CategoriaMandante;
use Backend\dto\ConfigurationEnvironment;
use Backend\dto\Proveedor;
use Backend\dto\Subproveedor;
use Backend\mysql\BonoDetalleMySqlDAO;
use Exception;

/**
 * Clase 'CMSCategoria'
 *
 * Esta clase provee datos de CMSCategoria
 *
 * Ejemplo de uso:
 * $CMSCategoria = new CMSCategoria();
 *
 *
 * @package ninguno
 * @author Daniel Tamayo <it@virtualsoft.tech>
 * @version ninguna
 * @access public
 * @see no
 * @date 20.09.17
 *
 */
class CMSProveedor
{

    /**
     * Representación de 'tipo'
     *
     * @var string
     */
    private $tipo;

    /**
     * Representación de 'proveedorId'
     *
     * @var string
     */
    private $proveedorId;

    /**
     * Representación de 'partnerId'
     *
     * @var string
     */
    private $partnerId;
    private $paisId;


    /**
     * Constructor de clase
     *
     * @param String $tipo tipo
     * @param String $proveedorId proveedorId
     * @param String $partnerId partnerId
     *
     * @return no
     * @throws no
     *
     * @access public
     * @see no
     * @since no
     * @deprecated no
     */
    public function __construct($tipo = "", $proveedorId = "", $partnerId = "", $paisId = "")
    {
        $this->tipo = $tipo;
        $this->proveedorId = $proveedorId;
        $this->partnerId = $partnerId;
        $this->paisId = $paisId;
    }

    /**
     * Obtener los productos
     *
     * @param String $category category
     * @param String $provider provider
     * @param String $offset offset
     * @param String $limit limit
     * @param String $search search
     * @param String $isMobile isMobile
     *
     * @return array categorias categorias
     *
     */
    public function getProductos($category = "", $provider = "", $offset = "", $limit = "", $search = "", $isMobile = "", $subprovider = "", $userId = null, &$getCount = null)
    {
        if($limit<0){
            $limit=0;
        }
        $Proveedor = new Proveedor();
        $Proveedor->setTipo($this->tipo);

        $Subproveedor = new Subproveedor();
        $Subproveedor->setTipo($this->tipo);

        $Productos = array();
        if ($subprovider == "") {
            //$Productos = $Proveedor->getProductosTipo($category, $provider, $offset, $limit, $search, $this->partnerId,$isMobile);
            // $total = $Proveedor->countProductosTipo($category,$this->partnerId,$isMobile,$provider);


            $Productos = $Subproveedor->getProductosTipoMandante($category, $subprovider, $offset, $limit, $search, $this->partnerId, $isMobile,$this->paisId, $userId, $getCount);
            $total = $Subproveedor->countProductosTipo($category, $this->partnerId, $isMobile, $subprovider,$this->paisId,$search);

        } else {

            $Subproveedor = new Subproveedor();
            $Subproveedor->setTipo($this->tipo);
            $Productos = $Subproveedor->getProductosTipoMandante($category, $subprovider, $offset, $limit, $search, $this->partnerId, $isMobile,$this->paisId);
            $total = $Subproveedor->countProductosTipo($category, $this->partnerId, $isMobile, $subprovider,$this->paisId,$search);

        }


        $data = array();

        foreach ($Productos as $producto) {

            $array = array(
                "id" => $producto['producto_mandante.prodmandante_id'],
                "externo_id" => $producto['producto.externo_id'],
                "producto_id" => $producto['producto.producto_id'],
                "categoria" => array(
                    "id" => $producto['categoria.categoria_id'],
                    "descripcion" => $producto['categoria.descripcion'],
                    "estado" => $producto['categoria.estado'],
                    "borde" =>  $producto['producto.borde'],
                    "imagenUrl"=>$producto['producto_mandante.imagenUrl'],
                    "ImagenUrl2"=>$producto['producto_mandante.ImageUrl2']
                ),
                "descripcion" => $producto['producto.descripcion'],
                "image" => $producto['producto.image_url'],
                "image2" => $producto['producto.image_url2'],
                "background" => $producto['producto_detalle.background'],
                "proveedor" => array(
                    "id" => $producto['proveedor.proveedor_id'],
                    "descripcion" => $producto['proveedor.descripcion'],
                    "abreviado" => $producto['proveedor.abreviado'],
                    "estado" => $producto['proveedor.estado'],
                    "borde" =>  $producto['producto.borde'],
                    "imagenUrl"=>$producto['producto_mandante.imagenUrl'],
                    "ImagenUrl2"=>$producto['producto_mandante.ImageUrl2']

                ),
                "estado" => $producto['producto.estado'],
                "fila" => $producto['producto_mandante.num_fila'],
                "columna" => $producto['producto_mandante.num_columna']
            );

            array_push($data, $array);


        }
        $result = array();

        $result["data"] = $data;
        $result["total"] = $total;


        return json_encode($result);
    }

    public function getProductos2($category = "", $provider = "", $offset = "", $limit = "", $search = "", $isMobile = "", $subprovider = "")
    {
        if($limit<0){
            $limit=0;
        }
        $Proveedor = new Proveedor();
        $Proveedor->setTipo($this->tipo);

        $Subproveedor = new Subproveedor();
        $Subproveedor->setTipo($this->tipo);

        $Productos = array();
        if ($subprovider == "") {
            //$Productos = $Proveedor->getProductosTipo($category, $provider, $offset, $limit, $search, $this->partnerId,$isMobile);
            // $total = $Proveedor->countProductosTipo($category,$this->partnerId,$isMobile,$provider);


            //$Productos = $Subproveedor->getProductosTipoMandante($category, $subprovider, $offset, $limit, $search, $this->partnerId,$isMobile);
            //$total = $Subproveedor->countProductosTipo($category,$this->partnerId,$isMobile,$subprovider);

        } else {

            $Subproveedor = new Subproveedor();
            $Subproveedor->setTipo($this->tipo);
            //$Productos = $Subproveedor->getProductosTipoMandante($category, $subprovider, $offset, $limit, $search, $this->partnerId,$isMobile);
            //$total = $Subproveedor->countProductosTipo($category,$this->partnerId,$isMobile,$subprovider);

        }

        $BonoInterno = new BonoInterno();
        $BonoDetalleMySqlDAO = new BonoDetalleMySqlDAO();
        $transaccion = $BonoDetalleMySqlDAO->getTransaction();


        if($isMobile != ""){
            $isMobile='S';
        }else{
            $isMobile='N';
        }

        $innPais ='';

        if($this->paisId != '' && $this->paisId != '0'){
            $innPais = ' AND pais_id="'.$this->paisId.'" ';
        }

        if(false) {


            $sql = "select respuesta from respuesta_fija where tipo = 1 and mandante = '" . $this->partnerId . "' and campo1 = '" . $category . "' and campo2 = '" . $isMobile . "'   and metodo = 1 " . $innPais . " ;";

            $resultRespuesta = $BonoInterno->execQuery($transaccion, $sql);


            function decontaminate_text(
                $text,
                $remove_tags = true,
                $remove_line_breaks = true,
                $remove_BOM = true,
                $ensure_utf8_encoding = true,
                $ensure_quotes_are_properly_displayed = true,
                $decode_html_entities = true
            )
            {

                if ('' != $text && is_string($text)) {
                    $text = preg_replace('@<(script|style)[^>]*?>.*?</\\1>@si', '', $text);
                    $text = str_replace(']]>', ']]&gt;', $text);

                    if ($remove_tags) {
                        // Which tags to allow (none!)
                        // $text = strip_tags($text, '<p>,<strong>,<span>,<a>');
                        $text = strip_tags($text, '');
                    }

                    if ($remove_line_breaks) {
                        $text = preg_replace('/[\r\n\t ]+/', ' ', $text);
                        $text = trim($text);
                    }

                    if ($remove_BOM) {
                        // Source: https://stackoverflow.com/a/31594983/1766219
                        if (0 === strpos(bin2hex($text), 'efbbbf')) {
                            $text = substr($text, 3);
                        }
                    }

                    if ($ensure_utf8_encoding) {

                        // Check if UTF8-encoding
                        if (utf8_encode(utf8_decode($text)) != $text) {
                            $text = mb_convert_encoding($text, 'utf-8', 'utf-8');
                        }
                    }

                    if ($ensure_quotes_are_properly_displayed) {
                        $text = str_replace('&quot;', '"', $text);
                    }

                    if ($decode_html_entities) {
                        $text = html_entity_decode($text);
                    }

                    /**
                     * Other things to try
                     * - the chr-function: https://stackoverflow.com/a/20845642/1766219
                     * - stripslashes (THIS ONE BROKE MY JSON DECODING, AFTER IT STARTED WORKING, THOUGH): https://stackoverflow.com/a/28540745/1766219
                     * - This (improved?) JSON-decoder didn't help me, but it sure looks fancy: https://stackoverflow.com/a/43694325/1766219
                     */

                }
                return $text;
            }

            $Productos = json_decode(decontaminate_text($resultRespuesta[0]->{'respuesta_fija.respuesta'}), true);

        }else{

            if($this->partnerId=='8'){
                $this->paisId='66';
            }
            if(in_array($this->partnerId,array(3,4,5,6,7,10,22,13))){
                $this->paisId='146';
            }
            if(in_array($this->partnerId,array(14,17))){
                $this->paisId='33';
            }
            if(in_array($this->partnerId,array(15))){
                $this->paisId='102';
            }
            if(in_array($this->partnerId,array(16))){
                $this->paisId='170';
            }
            if(in_array($this->partnerId,array(12))){
                $this->paisId='232';
            }
            if(in_array($this->partnerId,array(21))){
                $this->paisId='232';
            }
            if(in_array($this->partnerId,array(1))){
                $this->paisId='99';
            }
            if(in_array($this->partnerId,array(2))){
                $this->paisId='113';
            }
            if(in_array($this->partnerId,array(21))){
                $this->paisId='232';
            }

            if(in_array($this->partnerId,array(20))){
                $this->paisId='68';
            }


            if(in_array($this->partnerId,array(22))){
                $this->paisId='146';
            }

            if(in_array($this->partnerId,array(17))){
                $this->paisId='33';
            }

            if(in_array($this->partnerId,array(21))){
                $this->paisId='232';
            }


            function decontaminate_text(
                $text,
                $remove_tags = true,
                $remove_line_breaks = true,
                $remove_BOM = true,
                $ensure_utf8_encoding = true,
                $ensure_quotes_are_properly_displayed = true,
                $decode_html_entities = true
            )
            {

                if ('' != $text && is_string($text)) {
                    $text = preg_replace('@<(script|style)[^>]*?>.*?</\\1>@si', '', $text);
                    $text = str_replace(']]>', ']]&gt;', $text);

                    if ($remove_tags) {
                        // Which tags to allow (none!)
                        // $text = strip_tags($text, '<p>,<strong>,<span>,<a>');
                        $text = strip_tags($text, '');
                    }

                    if ($remove_line_breaks) {
                        $text = preg_replace('/[\r\n\t ]+/', ' ', $text);
                        $text = trim($text);
                    }

                    if ($remove_BOM) {
                        // Source: https://stackoverflow.com/a/31594983/1766219
                        if (0 === strpos(bin2hex($text), 'efbbbf')) {
                            $text = substr($text, 3);
                        }
                    }

                    if ($ensure_utf8_encoding) {

                        // Check if UTF8-encoding
                        if (utf8_encode(utf8_decode($text)) != $text) {
                            $text = mb_convert_encoding($text, 'utf-8', 'utf-8');
                        }
                    }

                    if ($ensure_quotes_are_properly_displayed) {
                        $text = str_replace('&quot;', '"', $text);
                    }

                    if ($decode_html_entities) {
                        $text = html_entity_decode($text);
                    }

                    /**
                     * Other things to try
                     * - the chr-function: https://stackoverflow.com/a/20845642/1766219
                     * - stripslashes (THIS ONE BROKE MY JSON DECODING, AFTER IT STARTED WORKING, THOUGH): https://stackoverflow.com/a/28540745/1766219
                     * - This (improved?) JSON-decoder didn't help me, but it sure looks fancy: https://stackoverflow.com/a/43694325/1766219
                     */

                }
                return $text;
            }


            $filename = $this->partnerId . '_' . $this->paisId . '_' .$category. '_' . $isMobile;

            if($_ENV['debug']){
                print_r($filename);
            }

            $Productos = file_get_contents('/home/home2/backend/api/configfiles/files/'.$filename);

            if($_ENV['debug']){
                print_r($Productos);
            }
            $Productos = json_decode(decontaminate_text($Productos), true);

        }
        if($_ENV['debug']){

            switch (json_last_error()) {
                case JSON_ERROR_NONE:
                    echo ' - No errors';
                    break;
                case JSON_ERROR_DEPTH:
                    echo ' - Maximum stack depth exceeded';
                    break;
                case JSON_ERROR_STATE_MISMATCH:
                    echo ' - Underflow or the modes mismatch';
                    break;
                case JSON_ERROR_CTRL_CHAR:
                    echo ' - Unexpected control character found';
                    break;
                case JSON_ERROR_SYNTAX:
                    echo ' - Syntax error, malformed JSON';
                    break;
                case JSON_ERROR_UTF8:
                    echo ' - Malformed UTF-8 characters, possibly incorrectly encoded';
                    break;
                default:
                    echo ' - Unknown error';
                    break;
            }

            echo PHP_EOL;

        }
        $data = array();



        $cont = 0;
        $conttotal = 0;

        foreach ($Productos as $producto) {


            if($cont>=$offset && $cont <= ($offset+$limit)) {

                $array = array(
                    "id" => $producto['producto_mandante.prodmandante_id'],
                    "externo_id" => $producto['producto.externo_id'],
                    "producto_id" => $producto['producto.producto_id'],
                    "categoria" => array(
                        "id" => $producto['categoria.categoria_id'],
                        "descripcion" => $producto['categoria.descripcion'],
                        "estado" => $producto['categoria.estado']
                    ),
                    "descripcion" => $producto['producto.descripcion'],
                    "image" => $producto['producto.image_url'],
                    "image2" => $producto['producto.image_url2'],
                    "background" => $producto['producto_detalle.background'],
                    "proveedor" => array(
                        "id" => $producto['proveedor.proveedor_id'],
                        "descripcion" => $producto['proveedor.descripcion'],
                        "abreviado" => $producto['proveedor.abreviado'],
                        "estado" => $producto['proveedor.abreviado']
                    ),
                    "estado" => $producto['producto.estado'],
                    "fila" => $producto['producto_mandante.num_fila'],
                    "columna" => $producto['producto_mandante.num_columna']
                );

                $seguir=true;
                if($subprovider != "" && $subprovider != $producto['subproveedor.abreviado']){
                    $seguir=false;
                }
                if($search != "" && strpos(strtolower($producto['producto.descripcion']),strtolower($search)) === false){
                    $seguir=false;
                }
                if($seguir){
                    array_push($data, $array);
                }

            }

            $seguir=true;
            if($subprovider != "" && $subprovider != $producto['subproveedor.abreviado']){
                $seguir=false;
            }
            if($search != "" && strpos(strtolower($producto['producto.descripcion']),strtolower($search)) === false){
                $seguir=false;
            }
            if($seguir){
                $cont++;
                $conttotal++;
            }

        }
        $result = array();

        $result["data"] = $data;
        $result["total"] = $conttotal;


        return json_encode($result);
    }

    public function updateDatabaseCasino()
    {
        if($this->partnerId != '') {


            $Proveedor = new Proveedor();
            $Proveedor->setTipo('CASINO');

            $Subproveedor = new Subproveedor();
            $Subproveedor->setTipo('CASINO');



            $Productos = $Subproveedor->getProductosTipoMandante('', "", '0', 1100, '', $this->partnerId,'S',$this->paisId);


            $BonoInterno = new BonoInterno();
            $BonoDetalleMySqlDAO = new BonoDetalleMySqlDAO();
            $transaccion = $BonoDetalleMySqlDAO->getTransaction();

            //$sql = "select respuestafija_id from respuesta_fija where tipo = 1 and mandante = '" . $this->partnerId . "' and campo1 = '' and campo2 = 'S'     and metodo = 1   and pais_id = '".$this->paisId."';";

           // $resultRespuesta = $BonoInterno->execQuery($transaccion, $sql);



            if(true) {
                $ConfigurationEnvironment = new ConfigurationEnvironment();

                $curl_params = [
                    'token' => 'D0radobet1234!',
                    'partner' => $this->partnerId,
                    'country' => $this->paisId,
                    'category' => '',
                    'mobile' => 'S',
                ];

                $curl_params['content'] = str_replace("'", "\'", json_encode($Productos));
                $payload = $ConfigurationEnvironment->encrypt(json_encode($curl_params));

                $curl = curl_init('http://admin3.local/configfiles/setconfig.php');
                curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($curl, CURLINFO_HEADER_OUT, true);
                curl_setopt($curl, CURLOPT_POST, true);
                curl_setopt($curl, CURLOPT_POSTFIELDS, $payload);
                curl_setopt($curl, CURLOPT_TIMEOUT, 30);
                curl_setopt($curl, CURLOPT_HTTPHEADER, [
                    'Content-Type' => 'application/json',
                    'Content-Length' => strlen($payload)
                ]);
                $result = '-1- ' . (curl_exec($curl));

            }else {


                if ($resultRespuesta[0]->{'respuesta_fija.respuestafija_id'} != '' && $resultRespuesta[0]->{'respuesta_fija.respuestafija_id'} != null) {
                    $sql = "UPDATE respuesta_fija SET respuesta = '" . str_replace("'", "\'", json_encode($Productos)) . "' 
                WHERE tipo = 1 and mandante = '" . $this->partnerId . "' and campo1 = '' and campo2 = 'S'     and metodo = 1  and pais_id = '" . $this->paisId . "'";
                } else {
                    $sql = "INSERT INTO respuesta_fija (tipo, descripcion, respuesta, estado, mandante, pais_id, campo1, campo2, campo3, campo4, metodo)
VALUES ('1','Juegos Casino','" . str_replace("'", "\'", json_encode($Productos)) . "','A','" . $this->partnerId . "','" . $this->paisId . "','" . '' . "','" . 'S' . "','','','1')";
                }
            }

            curl_exec($curl);
            curl_close($curl);

            $Productos = $Subproveedor->getProductosTipoMandante('', "", '0', 1100, '', $this->partnerId,'',$this->paisId);

            //$sql = "select respuestafija_id from respuesta_fija where tipo = 1 and mandante = '" . $this->partnerId . "' and campo1 = '' and campo2 = 'N'     and metodo = 1  and pais_id = '".$this->paisId."';";

            //$resultRespuesta = $BonoInterno->execQuery($transaccion, $sql);

            if(true) {
                $ConfigurationEnvironment = new ConfigurationEnvironment();

                $curl_params = [
                    'token' => 'D0radobet1234!',
                    'partner' => $this->partnerId,
                    'country' => $this->paisId,
                    'category' => '',
                    'mobile' => 'N',
                ];

                $curl_params['content'] = str_replace("'", "\'", json_encode($Productos));
                $payload = $ConfigurationEnvironment->encrypt(json_encode($curl_params));

                $curl = curl_init('http://admin3.local/configfiles/setconfig.php');
                curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($curl, CURLINFO_HEADER_OUT, true);
                curl_setopt($curl, CURLOPT_POST, true);
                curl_setopt($curl, CURLOPT_POSTFIELDS, $payload);
                curl_setopt($curl, CURLOPT_TIMEOUT, 30);
                curl_setopt($curl, CURLOPT_HTTPHEADER, [
                    'Content-Type' => 'application/json',
                    'Content-Length' => strlen($payload)
                ]);
                $result = '-1- ' . (curl_exec($curl));

            }else {


                if ($resultRespuesta[0]->{'respuesta_fija.respuestafija_id'} != '' && $resultRespuesta[0]->{'respuesta_fija.respuestafija_id'} != null) {
                    $sql = "UPDATE respuesta_fija SET respuesta = '" . str_replace("'", "\'", json_encode($Productos)) . "' 
                WHERE tipo = 1 and mandante = '" . $this->partnerId . "' and campo1 = '' and campo2 = 'N'     and metodo = 1  and pais_id = '" . $this->paisId . "'";
                } else {
                    $sql = "INSERT INTO respuesta_fija (tipo, descripcion, respuesta, estado, mandante, pais_id, campo1, campo2, campo3, campo4, metodo)
VALUES ('1','Juegos Casino','" . str_replace("'", "\'", json_encode($Productos)) . "','A','" . $this->partnerId . "','" . $this->paisId . "','" . '' . "','" . 'N' . "','','','1')";
                }
            }






            /*$proveedores = $Proveedor->getProveedores($this->partnerId, 'A');

            $finalProveedores = [];

            foreach ($proveedores as $key => $value) {


                $Productos = $Subproveedor->getProductosTipoMandante('', $value->getProveedorId(), '0', 1100, '', $this->partnerId, '');

                $sql = "select respuestafija_id from respuesta_fija where tipo = 1 and mandante = '" . $this->partnerId . "' and campo1 = '' and campo2 = 'N'     and metodo = 1;";

                $resultRespuesta = $BonoInterno->execQuery($transaccion, $sql);

                if ($resultRespuesta[0]->{'respuesta_fija.respuestafija_id'} != '' && $resultRespuesta[0]->{'respuesta_fija.respuestafija_id'} != null) {
                    $sql = "UPDATE respuesta_fija SET respuesta = '" . str_replace("'", "\'", json_encode($Productos)) . "' 
                WHERE tipo = 1 and mandante = '" . $this->partnerId . "' and campo1 = '' and campo2 = 'N'     and metodo = 1";
                } else {
                    $sql = "INSERT INTO respuesta_fija (tipo, descripcion, respuesta, estado, mandante, pais_id, campo1, campo2, campo3, campo4, metodo)
VALUES ('1','Juegos Casino','" . str_replace("'", "\'", json_encode($Productos)) . "','A','" . $this->partnerId . "','0','" . '' . "','" . 'N' . "','','','1')";
                }

                $resultRespuesta = $BonoInterno->execQuery($transaccion, $sql);
            }*/

            // $Categoria = new CMSCategoria("", "CASINO",$this->partnerId, $this->paisId);
            $CategoriaMandante = new CategoriaMandante();


            // $Categorias = $CMSCategoria->getCategorias();

            $data = $CategoriaMandante->getCategoriasTipo('CASINO', $this->partnerId, $this->paisId);

            // $data = $Categorias->data;
            // $categories = array();

            foreach ($data as $categoria) {
                if ($categoria->getEstado() == "A") {


                    $Productos = array();

                    $Productos = $Subproveedor->getProductosTipoMandante($categoria->getCatmandanteId(), "", 0, 1100, '', strtolower($this->partnerId),'S',$this->paisId);


                    $BonoInterno = new BonoInterno();
                    $BonoDetalleMySqlDAO = new BonoDetalleMySqlDAO();
                    $transaccion = $BonoDetalleMySqlDAO->getTransaction();

                   // $sql = "select respuestafija_id from respuesta_fija where tipo = 1 and mandante = '" . $this->partnerId . "' and campo1 = '" . $categoria->getCatmandanteId() . "' and campo2 = 'S'     and metodo = 1  and pais_id = '".$this->paisId."';";

                   // $resultRespuesta = $BonoInterno->execQuery($transaccion, $sql);

                    if(true) {
                        $ConfigurationEnvironment = new ConfigurationEnvironment();

                        $curl_params = [
                            'token' => 'D0radobet1234!',
                            'partner' => $this->partnerId,
                            'country' => $this->paisId,
                            'category' => $categoria->getCatmandanteId(),
                            'mobile' => 'S',
                        ];

                        $curl_params['content'] = str_replace("'", "\'", json_encode($Productos));
                        $payload = $ConfigurationEnvironment->encrypt(json_encode($curl_params));

                        $curl = curl_init('http://admin3.local/configfiles/setconfig.php');
                        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                        curl_setopt($curl, CURLINFO_HEADER_OUT, true);
                        curl_setopt($curl, CURLOPT_POST, true);
                        curl_setopt($curl, CURLOPT_POSTFIELDS, $payload);
                        curl_setopt($curl, CURLOPT_TIMEOUT, 30);
                        curl_setopt($curl, CURLOPT_HTTPHEADER, [
                            'Content-Type' => 'application/json',
                            'Content-Length' => strlen($payload)
                        ]);
                        $result = '-1- ' . (curl_exec($curl));

                    }else{
                        if($resultRespuesta[0]->{'respuesta_fija.respuestafija_id'} != '' && $resultRespuesta[0]->{'respuesta_fija.respuestafija_id'} != null){
                            $sql = "UPDATE respuesta_fija SET respuesta = '".str_replace("'","\'",json_encode($Productos))."' 
                WHERE tipo = 1 and mandante = '" . $this->partnerId . "' and campo1 = '" . $categoria->getCatmandanteId() . "' and campo2 = 'S'     and metodo = 1  and pais_id = '".$this->paisId."'";
                        }else {

                            $sql = "INSERT INTO respuesta_fija (tipo, descripcion, respuesta, estado, mandante, pais_id, campo1, campo2, campo3, campo4, metodo)
VALUES ('1','Juegos Casino','" . str_replace("'", "\'", json_encode($Productos)) . "','A','" . $this->partnerId . "','".$this->paisId."','" . $categoria->getCatmandanteId() . "','" . 'S' . "','','','1')";
                        }

                    }




                    $Productos = $Subproveedor->getProductosTipoMandante($categoria->getCatmandanteId(), "", '0', '1100', '', strtolower($this->partnerId),'',$this->paisId);





                    if(true) {
                        $ConfigurationEnvironment = new ConfigurationEnvironment();

                        $curl_params = [
                            'token' => 'D0radobet1234!',
                            'partner' => $this->partnerId,
                            'country' => $this->paisId,
                            'category' => $categoria->getCatmandanteId(),
                            'mobile' => 'N',
                        ];

                        $curl_params['content'] = str_replace("'", "\'", json_encode($Productos));
                        $payload = $ConfigurationEnvironment->encrypt(json_encode($curl_params));

                        $curl = curl_init('http://admin3.local/configfiles/setconfig.php');
                        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                        curl_setopt($curl, CURLINFO_HEADER_OUT, true);
                        curl_setopt($curl, CURLOPT_POST, true);
                        curl_setopt($curl, CURLOPT_POSTFIELDS, $payload);
                        curl_setopt($curl, CURLOPT_TIMEOUT, 30);
                        curl_setopt($curl, CURLOPT_HTTPHEADER, [
                            'Content-Type' => 'application/json',
                            'Content-Length' => strlen($payload)
                        ]);
                        $result = '-1- ' . (curl_exec($curl));

                    }else {


                        if ($resultRespuesta[0]->{'respuesta_fija.respuestafija_id'} != '' && $resultRespuesta[0]->{'respuesta_fija.respuestafija_id'} != null) {
                            $sql = "UPDATE respuesta_fija SET respuesta = '" . str_replace("'", "\'", json_encode($Productos)) . "' 
                WHERE tipo = 1 and mandante = '" . $this->partnerId . "' and campo1 = '" . $categoria->getCatmandanteId() . "' and campo2 = 'N'     and metodo = 1  and pais_id = '" . $this->paisId . "'";
                        } else {

                            $sql = "INSERT INTO respuesta_fija (tipo, descripcion, respuesta, estado, mandante, pais_id, campo1, campo2, campo3, campo4, metodo)
VALUES ('1','Juegos Casino','" . str_replace("'", "\'", json_encode($Productos)) . "','A','" . $this->partnerId . "','" . $this->paisId . "','" . $categoria->getCatmandanteId() . "','" . 'N' . "','','','1')";
                        }

                    }







                }


            }




            return true;
        }
        return false;

    }

    /**
     * Obtener los proveedores
     *
     * @return array proveedores proveedores
     *
     */
    public function getProveedores($estadoProveedor = '')
    {
        $Proveedor = new Proveedor();
        $Proveedor->setTipo($this->tipo);

        $Proveedores = $Proveedor->getProveedores($this->partnerId, 'A', $estadoProveedor);

        $data = array();

        foreach ($Proveedores as $proveedor) {
            if ($proveedor->estado == "A") {
                $array = array(
                    "id" => $proveedor->proveedorId,
                    "descripcion" => $proveedor->descripcion,
                    "abreviado" => $proveedor->abreviado,
                    "estado" => $proveedor->estado,
                    "imagen" => $proveedor->imagen
                );

                array_push($data, $array);
            }

        }
        $result = array();

        $result["data"] = $data;
        $result["total"] = oldCount($Proveedores);


        return json_encode($result);
    }


    /**
     * Obtener los proveedores
     *
     * @return array proveedores proveedores
     *
     */
    public function getSubProveedores($estadoProveedor = '')
    {
        $SubProveedor = new SubProveedor();
        $SubProveedor->setTipo($this->tipo);

        $Proveedores = $SubProveedor->getSubProveedores($this->partnerId, 'A', $estadoProveedor,$this->paisId);

        $data = array();

        foreach ($Proveedores as $proveedor) {
            if ($proveedor->estado == "A") {
                $array = array(
                    "id" => $proveedor->subproveedorId,
                    "descripcion" => $proveedor->descripcion,
                    "abreviado" => $proveedor->abreviado,
                    "estado" => $proveedor->estado,
                    "imagen" => $proveedor->imagen
                );

                array_push($data, $array);
            }

        }
        $result = array();

        $result["data"] = $data;
        $result["total"] = oldCount($Proveedores);


        return json_encode($result);
    }

    public function getSubProveedoresPais($estadoProveedor = '')
    {
        $SubProveedor = new SubProveedor();
        $SubProveedor->setTipo($this->tipo);

        $Proveedores = $SubProveedor->getSubproveedoresPais($this->partnerId, 'A', $estadoProveedor,$this->paisId);

        $data = array();

        foreach ($Proveedores as $proveedor) {
            if ($proveedor->estado == "A") {
                $array = array(
                    "id" => $proveedor->subproveedorId,
                    "descripcion" => $proveedor->descripcion,
                    "abreviado" => $proveedor->abreviado,
                    "estado" => $proveedor->estado,
                    "imagen" => $proveedor->imagen
                );

                array_push($data, $array);
            }

        }
        $result = array();

        $result["data"] = $data;
        $result["total"] = oldCount($Proveedores);


        return json_encode($result);
    }

}
