<?php namespace Backend\dto;
use Backend\mysql\MandanteMySqlDAO;
use CurlWrapper;
use Exception;
/** 
* Clase 'Mandante'
* 
* Esta clase provee una manera de instanciar un objeto de transferencia de datos (dto)
* para la tabla 'Mandante'
* 
* Ejemplo de uso: 
* $Mandante = new Mandante();
*   
* 
* @package ninguno 
* @author Daniel Tamayo <it@virtualsoft.tech>
* @version ninguna
* @access public 
* @see no
* 
*/
class Mandante
{

    /**
    * Representación de la columna 'mandante' de la tabla 'Mandante'
    *
    * @var string
    */ 
    var $mandante;

    /**
    * Representación de la columna 'descripcion' de la tabla 'Mandante'
    *
    * @var string
    */ 
    var $descripcion;

    /**
    * Representación de la columna 'contacto' de la tabla 'Mandante'
    *
    * @var string
    */ 
    var $contacto;

    /**
    * Representación de la columna 'email' de la tabla 'Mandante'
    *
    * @var string
    */ 
    var $email;

    /**
    * Representación de la columna 'telefono' de la tabla 'Mandante'
    *
    * @var string
    */ 
    var $telefono;

    /**
    * Representación de la columna 'nit' de la tabla 'Mandante'
    *
    * @var string
    */ 
    var $nit;

    /**
    * Representación de la columna 'legal1' de la tabla 'Mandante'
    *
    * @var string
    */ 
    var $legal1;

    /**
    * Representación de la columna 'legal2' de la tabla 'Mandante'
    *
    * @var string
    */ 
    var $legal2;

    /**
    * Representación de la columna 'legal3' de la tabla 'Mandante'
    *
    * @var string
    */ 
    var $legal3;

    /**
    * Representación de la columna 'legal4' de la tabla 'Mandante'
    *
    * @var string
    */ 
    var $legal4;

    /**
    * Representación de la columna 'propio' de la tabla 'Mandante'
    *
    * @var string
    */ 
    var $propio;

    /**
    * Representación de la columna 'success' de la tabla 'Mandante'
    *
    * @var string
    */ 
    var $success;


    /**
     * Representación de la columna 'nombre' de la tabla 'Mandante'
     *
     * @var string
     */
    var $nombre;

    /**
     * Representación de la columna 'base_url' de la tabla 'Mandante'
     *
     * @var string
     */
    var $baseUrl;

    /**
     * Representación de la columna 'email_noreply' de la tabla 'Mandante'
     *
     * @var string
     */
    var $emailNoreply;

    /**
     * Representación de la columna 'logo' de la tabla 'Mandante'
     *
     * @var string
     */
    var $logo;

    /**
     * Representación de la columna 'color_principal' de la tabla 'Mandante'
     *
     * @var string
     */
    var $colorPrincipal;

    /**
     * Representación de la columna 'email_fondo' de la tabla 'Mandante'
     *
     * @var string
     */
    var $emailFondo;

    /**
     * Representación de la columna 'skin_itainment' de la tabla 'Mandante'
     *
     * @var string
     */
    var $skinItainment;

    /**
     * Representación de la columna 'walletcode_itainment' de la tabla 'Mandante'
     *
     * @var string
     */
    var $walletcodeItainment;

    /**
     * Representación de la columna 'path_itainment' de la tabla 'Mandante'
     *
     * @var string
     */
    var $pathItainment;

    /**
     * Representación de la columna 'favicon' de la tabla 'Mandante'
     *
     * @var string
     */
    var $favicon;

    /**
     * Representación de la columna 'logo_pdf' de la tabla 'Mandante'
     *
     * @var string
     */
    var $logoPdf;

    /**
     * Constructor de clase
     *
     *
     * @param String mandante mandante
     *
     *
    * @return no
    * @throws Exception si el mandante no existe
    *
    * @access public
    * @see no
    * @since no
    * @deprecated no
    */
    public function __construct($mandante="")
    {
        if ($mandante != "") 
        {

            $this->mandante = $mandante;

            $MandanteMySqlDAO = new MandanteMySqlDAO();

            $Mandante = $MandanteMySqlDAO->load($this->mandante);

            $this->success = false;

            if ($Mandante != null && $Mandante != "") 
            {

                $this->descripcion = $Mandante->descripcion;
                $this->contacto = $Mandante->contacto;
                $this->email = $Mandante->email;
                $this->telefono = $Mandante->telefono;
                $this->nit = $Mandante->nit;
                $this->legal1 = $Mandante->legal1;
                $this->legal2 = $Mandante->legal2;
                $this->legal3 = $Mandante->legal3;
                $this->legal4 = $Mandante->legal4;
                $this->propio = $Mandante->propio;
                $this->mandante = $Mandante->mandante;
                $this->nombre = $Mandante->nombre;
                $this->baseUrl = $Mandante->baseUrl;
                $this->favicon = $Mandante->favicon;

                $this->emailNoreply = $Mandante->emailNoreply;
                $this->logo = $Mandante->logo;
                $this->colorPrincipal = $Mandante->colorPrincipal;
                $this->emailFondo = $Mandante->emailFondo;
                $this->skinItainment = $Mandante->skinItainment;
                $this->walletcodeItainment = $Mandante->walletcodeItainment;
                $this->pathItainment = $Mandante->pathItainment;


                $this->logoPdf = $Mandante->logoPdf;

                $this->success = true;
            }
            else 
            {
                throw new Exception("No existe " . get_class($this), "23");

            }

        }


    }

    /**
    * Realizar una consulta en la tabla de mandantes 'Mandante'
    * de una manera personalizada
    *
    *
    * @param String $sidx columna para ordenar
    * @param String $sord orden los datos asc | desc
    * @param String $start inicio de la consulta
    * @param String $limit limite de la consulta
    * @param String $filters condiciones de la consulta 
    * @param boolean $searchOn utilizar los filtros o no
    *
    * @return Array resultado de la consulta
    * @throws Exception si los mandantes no existen
    *
    * @access public
    * @see no
    * @since no
    * @deprecated no
    */
    public function getMandantes($sidx,$sord,$start,$limit,$filters,$searchOn)
    {

        $MandanteMySqlDAO = new MandanteMySqlDAO();

        $mandantes = $MandanteMySqlDAO->queryMandantes($sidx,$sord,$start,$limit,$filters,$searchOn);

        if ($mandantes != null && $mandantes != "") 
        {
            return $mandantes;
        }
        else 
        {
            throw new Exception("No existe " . get_class($this), "01");
        }


    }

    /**
    * Realizar una consulta en la tabla de puntod de venta 'PuntoVenta'
    * de una manera personalizada
    *
    *
    * @param String $sidx columna para ordenar
    * @param String $sord orden los datos asc | desc
    * @param String $start inicio de la consulta
    * @param String $limit limite de la consulta
    * @param String $filters condiciones de la consulta 
    * @param boolean $searchOn utilizar los filtros o no
    *
    * @return Array resultado de la consulta
    * @throws Exception si los mandantes no existen
    *
    * @access public
    * @see no
    * @since no
    * @deprecated no
    */
    public function getPuntosVentaTree($sidx,$sord,$start,$limit,$filters,$searchOn)
    {

        $MandanteMySqlDAO = new MandanteMySqlDAO();

        $mandantes = $MandanteMySqlDAO->queryPuntosVentaTree($sidx,$sord,$start,$limit,$filters,$searchOn);

        if ($mandantes != null && $mandantes != "") 
        {
            return $mandantes;
        }
        else 
        {
            throw new Exception("No existe " . get_class($this), "01");
        }


    }

    /**
     * Mandar una petición HTTP mediante el uso de curl
     *
     *
     * @param String url url del curl
     * @param String method metodo del curl
     * @param String ticket id del ticket
     * @param Array array_tmp data del curl
     *
     *
     * @return Array resultado de la petición curl
     * @throws no
     *
     * @access public
     * @see no
     * @since no
     * @deprecated no
     */  

    function sendRequest($url,$method,$array_tmp)
    {
        $data = array(
        );

        $data = array_merge($data, $array_tmp);

        $data =json_encode($data);


// Inicializar la clase CurlWrapper
        $curl = new CurlWrapper($url);

// Configurar opciones
        $curl->setOptionsArray([
            CURLOPT_CUSTOMREQUEST => $method,
            CURLOPT_POSTFIELDS => $data,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 5,
            CURLOPT_USERAGENT => $_SERVER['HTTP_USER_AGENT']
        ]);
        if($_ENV['debug']){
            print_r($url);
        }
// Ejecutar la solicitud
        $response = $curl->execute();

        if($_ENV['debug']){
            print_r($response);
        }
        $result = json_decode($response);

        return ($result);

    }

}

?>
