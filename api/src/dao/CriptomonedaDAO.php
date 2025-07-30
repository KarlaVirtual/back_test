<?php
namespace Backend\dao;

use Backend\dto\Criptomoneda;

/**
 * Interfaz para el modelo o tabla 'criptomoneda'.
 *
 * @author David Torres Rendon david.torres@virtualsoft.tech
 * @category    No
 * @package     No
 * @version     1.0
 * @since       2025-06-04
 */
interface CriptomonedaDAO
{
    /**
     *Obtener el dto de una criptomoneda.
     * @param mixed $criptomonedaId Id de la criptomoneda.
     * @return Criptomoneda|null Retorna el dto de la criptomoneda o null si no existe.
     */
    public function load(mixed $criptomonedaId);

    /**
     * Insertar un registro en la base de datos
     *
     * @param Criptomoneda $Criptomoneda Dto de Criptomoneda a almacenar.
     * @return int Retorna el ID del registro insertado.
     */
    public function insert(Criptomoneda $Criptomoneda) : int;

    /**
     * Editar un registro en la base de datos
     *
     * @param Criptomoneda $Criptomoneda Dto de la criptomoneda a ser actualizada.
     * @return int Retorna el número de filas afectadas.
     */
    public function update(Criptomoneda $Criptomoneda): int;

    /**
     *Consultar una colección personalizada de criptomonedas.
     * @param string $select Campos a seleccionar, separados por comas.
     * @param string $sidx Campo por el cual se ordenará la consulta.
     * @param string $sord Orden de la consulta ('asc' o 'desc').
     * @param mixed $start Índice de inicio para la paginación.
     * @param mixed $limit Límite de registros a retornar.
     * @param string $filters Filtros en formato JSON para aplicar a la consulta.
     * @return object Retorna un objeto con los resultados de la consulta.
     */
    public function queryCriptomonedaCustom (string $select, string $sidx, string $sord, mixed $start, mixed $limit, string $filters) : string;
}