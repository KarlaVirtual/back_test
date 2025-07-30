<?php

namespace Backend\dto;

use Backend\mysql\CriptomonedaMySqlDAO;
use Google_Client;
use Google_Service_Storage_StorageObject;
use Google_Service_Storage;
use Backend\dto\Exception;


/**
 * Objeto de transferencia de datos / Modelo de la entidad Criptomoneda vinculada a la tabla 'criptomoneda'.
 *
 * @author David Torres Rendon david.torres@virtualsoft.tech
 * @category    No
 * @package     No
 * @version     1.0
 * @since       2025-06-04
 */
class Criptomoneda
{
    /**
     * Representación de la columna 'criptomoneda_id' en la tabla Criptomoneda.
     */
    public $criptomonedaId;

    /**
     * Representación de la columna 'codigo_iso' en la tabla Criptomoneda.
     */
    private $codigoIso;

    /**
     * Representación de la columna 'nombre' en la tabla Criptomoneda.
     */
    private $nombre;

    /**
     * Representación de la columna 'icono' en la tabla Criptomoneda.
     */
    private $icono;

    /**
     * Representación de la columna 'tipo_activo' en la tabla Criptomoneda.
     */
    private $nivelEstabilidad;

    /**
     * Representación de la columna 'estado' en la tabla Criptomoneda.
     */
    private $estado;

    /**
     * Representación de la columna 'fecha_crea' en la tabla Criptomoneda.
     */
    private $fechaCrea;

    /**
     * Representación de la columna 'fecha_modif' en la tabla Criptomoneda.
     */
    private $fechaModif;

    /**
     * Representación de la columna 'usucrea_id' en la tabla Criptomoneda.
     */
    private $usucreaId;

    /**
     * Representación de la columna 'usumodif_id' en la tabla Criptomoneda.
     */
    private $usumodifId;

    /**
     * Constructor de la clase Criptomoneda.
     *
     * Permite inicializar una instancia de Criptomoneda a partir de un ID o un código ISO.
     * Si se proporciona un ID o un código ISO, intenta cargar la información correspondiente desde la base de datos.
     * Lanza una excepción si no se encuentra la criptomoneda solicitada.
     *
     * @param mixed $criptomonedaId ID de la criptomoneda (opcional).
     * @param string|null $codigoIso Código ISO de la criptomoneda (opcional).
     * @throws Exception Si no existe la criptomoneda con el ID o código ISO proporcionado.
     */
    public function __construct(mixed $criptomonedaId = null, ?string $codigoIso = null)
    {
        $Criptomoneda = null;
        if (!empty($criptomonedaId)) {
            $CriptomonedaMySqlDAO = new CriptomonedaMySqlDAO();
            $Criptomoneda = $CriptomonedaMySqlDAO->load($criptomonedaId);
        } elseif (!empty($codigoIso)) {
            $CriptomonedaMySqlDAO = new CriptomonedaMySqlDAO();
            $Criptomoneda = $CriptomonedaMySqlDAO->loadByCodigoIso($codigoIso);
        }

        if (empty($Criptomoneda) && (!empty($criptomonedaId) || !empty($codigoIso))) {
            throw new Exception("No existe " . $this::class, 300174);
        } elseif (!empty($Criptomoneda)) {
            $this->forceAttributesSetting($Criptomoneda);
        }
    }

    /**
     * Función encargada de obtener la columna 'criptomoneda_id' en la tabla Criptomoneda.
     * @return mixed
     */
    public function getCriptomonedaId()
    {
        return $this->criptomonedaId;
    }

    /**
     * Función encargada de configurar la columna 'criptomoneda_id' en la tabla Criptomoneda.
     * @param mixed $criptomonedaId Id de la criptomoneda.
     * @return void
     */
    public function setCriptomonedaId($criptomonedaId): void
    {
        $this->criptomonedaId = $criptomonedaId;
    }

    /**
     * Función encargada de obtener la columna 'codigo_iso' en la tabla Criptomoneda.
     * @return mixed
     */
    public function getCodigoIso()
    {
        return $this->codigoIso;
    }

    /**
     * Función encargada de configurar la columna 'codigo_iso' en la tabla Criptomoneda.
     * @param mixed $codigoIso Codigo ISO de la criptomoneda.
     * @return void
     */
    public function setCodigoIso($codigoIso): void
    {
        $this->codigoIso = $codigoIso;
    }

    /**
     * Función encargada de obtener la columna 'nombre' en la tabla Criptomoneda.
     * @return mixed
     */
    public function getNombre()
    {
        return $this->nombre;
    }

    /**
     * Función encargada de configurar la columna 'nombre' en la tabla Criptomoneda.
     * @param mixed $nombre Nombre de la criptomoneda.
     * @return void
     */
    public function setNombre($nombre): void
    {
        $this->nombre = $nombre;
    }

    /**
     * Función encargada de obtener la columna 'icono' en la tabla Criptomoneda.
     * @return mixed
     */
    public function getIcono()
    {
        return $this->icono;
    }

    /**
     * Función encargada de configurar la columna 'icono' en la tabla Criptomoneda.
     * @param mixed $icono Url del icono de la criptomoneda.
     * @return void
     */
    public function setIcono($icono): void
    {
        $this->icono = $icono;
    }

    /**
     * Función encargada de obtener la columna 'tipo_activo' en la tabla Criptomoneda.
     * @return mixed
     */
    public function getNivelEstabilidad()
    {
        return $this->nivelEstabilidad;
    }

    /**
     * Función encargada de configurar la columna 'tipo_activo' en la tabla Criptomoneda.
     * @param mixed $nivelEstabilidad Nivel de estabilidad de la criptomoneda.
     * @throws Exception Si el valor no es soportado.
     * @return void
     */
    public function setNivelEstabilidad($nivelEstabilidad): void
    {
        /*Agregue aquí nuevos valores que requiera en el campo tipo_activo de criptomoneda*/
        $nivelEstabilidad = strtolower($nivelEstabilidad);
        $validValues = [
            'inestable', // Token
            'estable', // Stablecoin
        ];

        if (!in_array($nivelEstabilidad, $validValues)) {
            throw new Exception("Valor no soportado", 300175);
        }

        $this->nivelEstabilidad = $nivelEstabilidad;
    }

    /**
     * Función encargada de obtener la columna 'estado' en la tabla Criptomoneda.
     * @return mixed
     */
    public function getEstado()
    {
        return $this->estado;
    }

    /**
     * Función encargada de configurar la columna 'estado' en la tabla Criptomoneda.
     * @param mixed $estado Estado actual de la criptomoneda
     * @throws Exception Si el valor no es soportado.
     * @return void
     */
    public function setEstado($estado): void
    {
        /*Agregue aquí nuevos estados que requiera en la tabla criptomoneda*/
        $estado = strtoupper($estado);
        $validValues = [
            'A', //ACTIVO
            'I', //INACTIVO
        ];

        if (!in_array($estado, $validValues)) {
            throw new Exception("Valor no soportado", 300175);
        }

        $this->estado = $estado;
    }

    /**
     * Función encargada de obtener la columna 'fecha_crea' en la tabla Criptomoneda.
     * @return mixed
     */
    public function getFechaCrea()
    {
        return $this->fechaCrea;
    }

    /**
     * Función encargada de configurar la columna 'fecha_crea' en la tabla Criptomoneda.
     * @param mixed $fechaCrea Fecha de creacion de la criptomoneda.
     * @return void
     */
    public function setFechaCrea($fechaCrea): void
    {
        $this->fechaCrea = $fechaCrea;
    }

    /**
     * Función encargada de obtener la columna 'fecha_modif' en la tabla Criptomoneda.
     * @return mixed
     */
    public function getFechaModif()
    {
        return $this->fechaModif;
    }

    /**
     * Función encargada de configurar la columna 'fecha_modif' en la tabla Criptomoneda.
     * @param mixed $fechaModif Ultima fecha de modificacion de la criptomoneda.
     * @return void
     */
    public function setFechaModif($fechaModif): void
    {
        $this->fechaModif = $fechaModif;
    }

    /**
     * Función encargada de obtener la columna 'usucrea_id' en la tabla Criptomoneda.
     * @return mixed
     */
    public function getUsucreaId()
    {
        return $this->usucreaId;
    }

    /**
     * Función encargada de configurar la columna 'usucrea_id' en la tabla Criptomoneda.
     * @param mixed $usucreaId Usuario creador de la criptomoneda.
     * @return void
     */
    public function setUsucreaId($usucreaId): void
    {
        $this->usucreaId = $usucreaId;
    }

    /**
     * Función encargada de obtener la columna 'usumodif_id' en la tabla Criptomoneda.
     * @return mixed
     */
    public function getUsumodifId()
    {
        return $this->usumodifId;
    }

    /**
     * Función encargada de configurar la columna 'usumodif_id' en la tabla Criptomoneda.
     * @param mixed $usumodifId Ultimo usuario modificador de la criptomoneda.
     * @return void
     */
    public function setUsumodifId($usumodifId): void
    {
        $this->usumodifId = $usumodifId;
    }

    /**
     * Asigna los valores de un objeto recibido a los atributos de la instancia actual.
     *
     * @param object $row Objeto con propiedades que corresponden a los atributos de la clase.
     * @return void
     */
    public function forceAttributesSetting(object $row)
    {
        foreach ($row as $param => $value) {
            $this->$param = $value;
        }
    }

    /**
     * Solicita una consulta personalizada de criptomonedas al DAO instanciado.
     * @param string $select Campos a seleccionar, separados por comas.
     * @param string $sidx Campo por el cual se ordenará la consulta.
     * @param string $sord Orden de la consulta ('asc' o 'desc').
     * @param mixed $start Índice de inicio para la paginación.
     * @param mixed $limit Límite de registros a retornar.
     * @param string $filters Filtros en formato JSON para aplicar a la consulta.
     * @param array|null $joins Arreglo de objetos que definen los joins a realizar.
     * @return object Retorna un objeto con los resultados de la consulta.
     */
    public function getCriptomonedaCustom (string $select, string $sidx, string $sord, mixed $start, mixed $limit, string $filters, ?array $joins = null) : string
    {
        $CriptomonedaMySqlDAO = new CriptomonedaMySqlDAO();

        $respuestaCustom = $CriptomonedaMySqlDAO->queryCriptomonedaCustom($select, $sidx, $sord, $start, $limit, $filters, $joins);

        return $respuestaCustom;
    }

    /**
     * Retorna un arreglo con los formatos MIME válidos para los iconos de criptomonedas.
     *
     * @return array Lista de formatos MIME permitidos para los iconos.
     */
    public static function getValidIconFormats(): array
    {
        return [
            'image/png',
            'image/jpeg',
            'image/jpg'
        ];
    }

    /**
     * Valida si el binario proporcionado corresponde a un icono válido.
     *
     * Realiza las siguientes validaciones:
     * - Verifica que el binario pueda ser interpretado como una imagen.
     * - Obtiene la información de la imagen y valida que el formato sea PNG o JPEG.
     * - Verifica que el tamaño de la imagen no exceda los 500x500 píxeles.
     *
     * @param string $binarioIcono Binario de la imagen a validar.
     * @return bool Retorna true si el icono es válido, false en caso contrario.
     */
    public static function isValidIconResource(string $binarioIcono): bool
    {
        $validResource = true;
        /*Sanitización del binario*/
        $imageSource = imagecreatefromstring($binarioIcono);
        if ($imageSource === false) {
            throw new Exception("Imagen invalida", 300176);
        }
        imagedestroy($imageSource);

        /*Obtención información del recurso*/
        $imageData = getimagesizefromstring($binarioIcono);
        if ($imageData === false) {
            throw new Exception("Imagen invalida", 300176);
        }

        /*Verificación del formato*/
        if (!in_array($imageData['mime'], self::getValidIconFormats())) {
            throw new Exception("Imagen invalida", 300176);
        }

        /*Verificación del tamaño*/
        if ($imageData[0] > 500 || $imageData[1] > 500) {
            throw new Exception("Dimensiones de imagen invalida", 300178);
        }

        return $validResource;
    }

    /**
     * Guarda el icono proporcionado en Google Cloud Storage y retorna la URL de acceso.
     *
     * Este metodo realiza las siguientes acciones:
     * - Verifica que el binario de la imagen sea válido utilizando isValidIconResource.
     * - Obtiene la extensión y el tipo MIME de la imagen.
     * - Genera un nombre de archivo único basado en el mandante y la marca de tiempo.
     * - Configura el cliente de Google Cloud Storage con credenciales de servicio.
     * - Sube la imagen al bucket especificado con los metadatos adecuados.
     * - Retorna la URL pública para acceder al icono almacenado.
     *
     * @param string $binarioIcono Binario de la imagen a guardar.
     * @param mixed $mandante Mandante del usuario que solicita el almacenamiento del icono.
     * @return string URL pública de la imagen almacenada.
     * @throws Exception Si el icono no es válido.
     */
    public static function saveIconResource(string $binarioIcono, mixed $mandante): string
    {
        /*Verifica validez de la imagen*/
        self::isValidIconResource($binarioIcono);

        /*Obtención extensión de la imagen*/
        $imageData = getimagesizefromstring($binarioIcono);
        $mime = $imageData['mime'];
        $ext = (explode('/', $mime))[1];

        /*Definición nombre del recurso*/
        $name = "msj" . (intval($mandante)) . '212';
        $filename = $name . "T" . time() . '.' . $ext;

        /* Configuración de cliente para acceder a Google Cloud Storage con permisos completos. */
        $bucketName = 'virtualcdnrealbucket';
        $client = new Google_Client();
        $client->setAuthConfig('/etc/private/virtual.json');
        $client->useApplicationDefaultCredentials();
        $client->addScope(Google_Service_Storage::DEVSTORAGE_FULL_CONTROL);
        $storage = new Google_Service_Storage($client);

        /* Configuración objeto de subida al bucket*/
        $file_name = 'm/' . $filename;
        $obj = new Google_Service_Storage_StorageObject();
        $obj->setName($file_name);
        $obj->setCacheControl('public, max-age=604800');
        $obj->setMetadata(['contentType' => $mime]);

        /*Adición del objeto al bucket*/
        $storage->objects->insert(
            $bucketName,
            $obj,
            ['mimeType' => $mime, 'name' => $file_name, 'data' => $binarioIcono, 'uploadType' => 'media']
        );

        /* Construye una URL basada en un nombre de archivo para acceder a una imagen. */
        $url = 'https://images.virtualsoft.tech/m/' . $filename;
        return $url;
    }
}