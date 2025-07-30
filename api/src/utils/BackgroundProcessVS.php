<?php

namespace Backend\utils;

/** Clase utilizada en la manipulación y uso de procesos en segundo plano; comúnmente vinculados a cron.
 * @category No aplica
 * @package No aplica
 * @author Daniel Tamayo
 * @version 1.0
 * @since Desconocido
 */
class BackgroundProcessVS
{
    /**
     * Constructor de la clase BackgroundProcessVS.
     */
    public function __construct()
    {
    }

    /**
     * Ejecuta un archivo PHP en segundo plano.
     *
     * @param string $file Ruta del archivo PHP a ejecutar.
     * @param string $vars Variables adicionales para pasar al script PHP.
     */
    public function execute($file, $vars = '')
    {
        exec("php -f " . $file . " " . $vars . " > /dev/null & ");
    }

    /**
     * Obtiene el comando para ejecutar un archivo PHP en segundo plano.
     *
     * @param string $file Ruta del archivo PHP a ejecutar.
     * @param string $vars Variables adicionales para pasar al script PHP.
     * @return string Comando para ejecutar el archivo PHP en segundo plano.
     */
    public function getCommandExecute($file, $vars = '')
    {
        return ("php -f " . $file . " " . $vars . " > /dev/null & ");
    }
}