<?php

namespace Backend\dto;

/** 
 * Clase de excepción personalizada con precauciones en el ambito transaccional
 * @category No
 * @package No
 * @author     Desconocido
 * @version     1.0
 * @since       Desconocido
 */
class Exception extends \Exception
{
    /**
     * Constructor de la clase Exception.
     *
     * Este constructor extiende el constructor de la clase base Exception y 
     * adicionalmente verifica si existe una conexión global activa. Si la 
     * conexión global está en una transacción y el estado de la transacción 
     * es 2, se realiza un rollback.
     *
     * @param string $message El mensaje de la excepción.
     * @param int $code El código de la excepción.
     * @param Throwable|null $previous La excepción anterior usada para la 
     *                                 encadenación de excepciones.
     */
    public function __construct($message = "", $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
        if ($_ENV["connectionGlobal"] != null && $_ENV["connectionGlobal"]   != '') {
            if (($_ENV["connectionGlobal"])->isBeginTransaction == 2) {
                ($_ENV["connectionGlobal"])->rollBack();
            }
        }
    }
}
