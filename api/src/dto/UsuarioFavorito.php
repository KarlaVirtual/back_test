<?php
namespace Backend\dto;
use Backend\mysql\UsuarioFavoritoMySqlDAO;

class UsuarioFavorito {
/**
     * @var int Representación de la columna 'usufavorito_id' en la tabla 'usuario_favorito'
     */
    public $usufavoritoId;

    /**
     * @var int Representación de la columna 'usuario_id' en la tabla 'usuario_favorito'
     */
    public $usuarioId;

    /**
     * @var int Representación de la columna 'producto_id' en la tabla 'usuario_favorito'
     */
    public $productoId;

    /**
     * @var string Representación de la columna 'estado' en la tabla 'usuario_favorito'
     */
    public $estado;

    /**
     * @var string Representación de la columna 'fecha_crea' en la tabla 'usuario_favorito'
     */
    public $fechaCrea;

    /**
     * @var string Representación de la columna 'fecha_modif' en la tabla 'usuario_favorito'
     */
    public $fechaModif;

    /**
     * @var int Representación de la columna 'usucrea_id' en la tabla 'usuario_favorito'
     */
    public $usucreaId;

    /**
     * @var int Representación de la columna 'usumodif_id' en la tabla 'usuario_favorito'
     */
    public $usumodifId;

    /**
     * Constructor de la clase UsuarioFavorito.
     *
     * @param int|null $usufavoritoId ID del usuario favorito (opcional).
     * @param int|null $usuarioId ID del usuario (opcional).
     * @param int|null $productoId ID del producto (opcional).
     * @throws \Exception Si no existe el usuario favorito.
     */
    public function __construct($usufavoritoId = null, $usuarioId = null, $productoId = null)
    {
        $UsuarioFavorito = null;

        if (!empty($usufavoritoId)) {
            $UsuarioFavoritoMySqlDAO = new UsuarioFavoritoMySqlDAO();
            $UsuarioFavorito = $UsuarioFavoritoMySqlDAO->load($usufavoritoId);

            if (empty($UsuarioFavorito)) throw new \Exception('No existe ' . get_class($this), 300022);
        }
        elseif (!empty($usuarioId) && !empty($productoId)) {
            $UsuarioFavoritoMySqlDAO = new UsuarioFavoritoMySqlDAO();
            $UsuarioFavorito = $UsuarioFavoritoMySqlDAO->loadByUserProduct($usuarioId, $productoId);

            if (empty($UsuarioFavorito)) throw new \Exception('No existe ' . get_class($this), 300022);
        }


        if (!empty($UsuarioFavorito)) {
            /** Toda propiedad que se agregue a la función readRow de MySqlDAO y se defina en el dto, es incializada por el foreach*/
            foreach ($UsuarioFavorito as $propiedad => $valor) {
                $this->$propiedad = $valor;
            }
        }
    }


/**
     * Obtiene el ID del usuario favorito.
     *
     * @return int El ID del usuario favorito.
     */
    public function getUsufavoritoId()
    {
        return $this->usufavoritoId;
    }

    /**
     * Establece el ID del usuario favorito.
     *
     * @param int $usufavoritoId El ID del usuario favorito.
     */
    public function setUsufavoritoId($usufavoritoId): void
    {
        $this->usufavoritoId = $usufavoritoId;
    }

    /**
     * Obtiene el ID del usuario.
     *
     * @return int El ID del usuario.
     */
    public function getUsuarioId()
    {
        return $this->usuarioId;
    }

    /**
     * Establece el ID del usuario.
     *
     * @param int $usuarioId El ID del usuario.
     */
    public function setUsuarioId($usuarioId): void
    {
        $this->usuarioId = $usuarioId;
    }

    /**
     * Obtiene el ID del producto.
     *
     * @return int El ID del producto.
     */
    public function getProductoId()
    {
        return $this->productoId;
    }

    /**
     * Establece el ID del producto.
     *
     * @param int $productoId El ID del producto.
     */
    public function setProductoId($productoId): void
    {
        $this->productoId = $productoId;
    }

    /**
     * Obtiene el estado.
     *
     * @return string El estado.
     */
    public function getEstado()
    {
        return $this->estado;
    }

    /**
     * Establece el estado.
     *
     * @param string $estado El estado.
     */
    public function setEstado($estado): void
    {
        $this->estado = $estado;
    }

    /**
     * Obtiene la fecha de creación.
     *
     * @return string La fecha de creación.
     */
    public function getFechaCrea()
    {
        return $this->fechaCrea;
    }

    /**
     * Establece la fecha de creación.
     *
     * @param string $fechaCrea La fecha de creación.
     */
    public function setFechaCrea($fechaCrea): void
    {
        $this->fechaCrea = $fechaCrea;
    }

    /**
     * Obtiene la fecha de modificación.
     *
     * @return string La fecha de modificación.
     */
    public function getFechaModif()
    {
        return $this->fechaModif;
    }

    /**
     * Establece la fecha de modificación.
     *
     * @param string $fechaModif La fecha de modificación.
     */
    public function setFechaModif($fechaModif): void
    {
        $this->fechaModif = $fechaModif;
    }

    /**
     * Obtiene el ID del usuario que creó el registro.
     *
     * @return int El ID del usuario que creó el registro.
     */
    public function getUsucreaId()
    {
        return $this->usucreaId;
    }

    /**
     * Establece el ID del usuario que creó el registro.
     *
     * @param int $usucreaId El ID del usuario que creó el registro.
     */
    public function setUsucreaId($usucreaId): void
    {
        $this->usucreaId = $usucreaId;
    }

    /**
     * Obtiene el ID del usuario que modificó el registro.
     *
     * @return int El ID del usuario que modificó el registro.
     */
    public function getUsumodifId()
    {
        return $this->usumodifId;
    }

    /**
     * Establece el ID del usuario que modificó el registro.
     *
     * @param int $usumodifId El ID del usuario que modificó el registro.
     */
    public function setUsumodifId($usumodifId): void
    {
        $this->usumodifId = $usumodifId;
    }

    /**
     * Obtiene los productos favoritos de un usuario.
     *
     * @param int $usuarioId El ID del usuario.
     * @return array Un array de productos favoritos del usuario.
     */
    public function getUsuarioProductosFavoritos($usuarioId): array
    {
        $UsuarioFavoritoMySqlDAO = new UsuarioFavoritoMySqlDAO();

        $productosFavoritos = $UsuarioFavoritoMySqlDAO->queryUsuarioProductosFavoritos($usuarioId);
        return $productosFavoritos;
    }

    /**
     * Obtiene una lista personalizada de usuarios favoritos.
     *
     * @param string $select Campos a seleccionar en la consulta.
     * @param string $sidx Índice de ordenación.
     * @param string $sord Orden de clasificación (ASC o DESC).
     * @param mixed $start Posición inicial para la paginación.
     * @param mixed $limit Límite de registros a obtener.
     * @param string $filters Filtros a aplicar en la consulta.
     * @param bool $searchOn Indica si la búsqueda está activada.
     * @param bool $onlyCount Indica si solo se debe obtener el conteo de registros (opcional, por defecto false).
     * @return mixed Resultado de la consulta personalizada.
     */
    public function getUsuarioFavoritoCustom(string $select, string $sidx, string $sord,mixed $start,mixed $limit,string $filters,bool $searchOn,bool $onlyCount = false)
    {
        $UsuarioFavoritoMySqlDAO = new UsuarioFavoritoMySqlDAO();

        $respuestaCustom = $UsuarioFavoritoMySqlDAO->queryUsuarioProductosFavoritos($select, $sidx, $sord, $start, $limit, $filters, $searchOn, $onlyCount);
        return $respuestaCustom;
    }
}
?>