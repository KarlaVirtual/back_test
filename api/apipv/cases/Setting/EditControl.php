<?php

use Backend\dto\Clasificador;
use Backend\dto\Mandante;
use Backend\dto\ModeloFiscal;
use Backend\mysql\ModeloFiscalMySqlDAO;
use Backend\sql\Transaction;

try {
    /**
     * Este script maneja la actualización e inserción de valores porcentuales para
     * el depósito, retiro, apuestas deportivas y no deportivas, premios, bonos y tickets
     * de un socio específico, en función de los parámetros proporcionados.
     *
     * @param object $params Objeto que contiene los siguientes parámetros:
     * @param int $params->Country País para el cual se aplican los porcentajes.
     * @param string $params->Partner Socio que se está procesando.
     * @param string $params->Mounth Mes a considerar, por defecto es '01'.
     * @param string $params->Year Año a considerar, por defecto es el año actual.
     * @param float $params->PercentDepositValue Porcentaje de depósito.
     * @param float $params->PercentRetirementValue Porcentaje de retiro.
     * @param float $params->PercentValueSportsBets Porcentaje de apuestas deportivas.
     * @param float $params->PercentValueNonSportBets Porcentaje de apuestas no deportivas.
     * @param float $params->PercentValueSportsAwards Porcentaje de premios deportivos.
     * @param float $params->PercentValueNonSportsAwards Porcentaje de premios no deportivos.
     * @param float $params->PercentValueSportsBonds Porcentaje de bonos deportivos.
     * @param float $params->PercentValueNonSportsBounds Porcentaje de bonos no deportivos.
     * @param float $params->PercentValueTickets Porcentaje de tickets.
     * 
     *
     * @return array $response Respuesta estructurada con los siguientes valores:
     *  - bool $HasError Indica si ocurrió un error.
     *  - string $AlertType Tipo de alerta ('success', 'error', etc.).
     *  - string $AlertMessage Mensaje de alerta.
     *  - array $ModelErrors Lista de errores del modelo, si los hay.
     *
     * @throws Error Si el parámetro $Partner está vacío.
     * @throws Exception Si ocurre un error durante la transacción o en las operaciones de base de datos.
     */

    $Country = $params->Country; // País para el cual se aplican los porcentajes
    $Partner = $params->Partner; // Socio que se está procesando
    $Mounth = $params->Mounth ?: '01'; // Mes a considerar, por defecto es enero
    $Year = $params->Year ?: date('Y'); // Año a considerar, por defecto es el año actual

    if ($Partner === '') throw new Error('Inusual Detected', 11); // Lanza un error si el socio está vacío
    $PercentDepositValue = $params->PercentDepositValue; // Valor del porcentaje de depósito
    $PercentRetirementValue = $params->PercentRetirementValue; // Valor del porcentaje de retiro
    $PercentValueSportsBets = $params->PercentValueSportsBets; // Valor de apuestas deportivas
    $PercentValueNonSportBets = $params->PercentValueNonSportBets; // Valor de apuestas no deportivas
    $PercentValueSportsAwards = $params->PercentValueSportsAwards; // Valor de premios deportivos
    $PercentValueNonSportsAwards = $params->PercentValueNonSportsAwards; // Valor de premios no deportivos
    $PercentValueSportsBonds = $params->PercentValueSportsBonds; // Valor de bonos deportivos
    $PercentValueNonSportsBounds = $params->PercentValueNonSportsBounds; // Valor de bonos no deportivos
    $PercentValueTickets = $params->PercentValueTickets; // Valor de tickets

    $Mandante = new Mandante($Partner); // Crear una instancia de Mandante

    $Transaction = new Transaction(); // Crear una transacción para la base de datos

    // Actualizar o insertar el valor del porcentaje de depósito
    if ($PercentDepositValue !== '' && is_numeric($PercentDepositValue)) {
        $Clasificador = new Clasificador('', 'PORCENVADEPO');
        $tipoDetalle = $Clasificador->getClasificadorId();
        try {
            $ModeloFiscal = new ModeloFiscal('', $tipoDetalle, $Mandante->mandante, $Country, $Mounth, $Year);

            if ($ModeloFiscal->getValor() !== $PercentDepositValue) {

                $ModeloFiscal->setValor($PercentDepositValue); // Establecer nuevo valor
                $ModeloFiscal->setUsumodifIf($_SESSION['usuario']); // Usuario que modifica

                $ModeloFiscalMySqlDAO = new ModeloFiscalMySqlDAO($Transaction); // DAO para operaciones MySQL
                $ModeloFiscalMySqlDAO->update($ModeloFiscal); // Actualizar en la base de datos
            }
        } catch (Exception $ex) {
            if ($ex->getCode() == 34) {
                $ModeloFiscal = new ModeloFiscal(); // Crear nuevo objeto ModeloFiscal

                $ModeloFiscal->setTipo($tipoDetalle); // Establecer tipo
                $ModeloFiscal->setValor($PercentDepositValue); // Establecer valor
                $ModeloFiscal->setMandante($Mandante->mandante); // Establecer mandante
                $ModeloFiscal->setPaisId($Country); // Establecer país
                $ModeloFiscal->setMes($Mounth); // Establecer mes
                $ModeloFiscal->setAnio($Year); // Establecer año
                $ModeloFiscal->setEstado('A'); // Establecer estado
                $ModeloFiscal->setUsucreaId($_SESSION['usuario']); // Usuario que crea

                $ModeloFiscalMySqlDAO = new ModeloFiscalMySqlDAO($Transaction); // DAO para operaciones MySQL
                $ModeloFiscalMySqlDAO->insert($ModeloFiscal); // Insertar en la base de datos
            }
        }
    }

    // Actualizar o insertar el valor del porcentaje de retiro
    if ($PercentRetirementValue !== '' && is_numeric($PercentRetirementValue)) {
        $Clasificador = new Clasificador('', 'PORCENVARETR'); // Clasificador para porcentaje de retiro
        $tipoDetalle = $Clasificador->getClasificadorId(); // Obtener ID del clasificador

        try {
            $ModeloFiscal = new ModeloFiscal('', $tipoDetalle, $Mandante->mandante, $Country, $Mounth, $Year);

            if ($ModeloFiscal->getValor() != $PercentRetirementValue) {

                $ModeloFiscal->setValor($PercentRetirementValue); // Establecer nuevo valor
                $ModeloFiscal->setUsumodifIf($_SESSION['usuario']); // Usuario que modifica

                $ModeloFiscalMySqlDAO = new ModeloFiscalMySqlDAO($Transaction); // DAO para operaciones MySQL
                $ModeloFiscalMySqlDAO->update($ModeloFiscal); // Actualizar en la base de datos
            }
        } catch (Exception $ex) {
            if ($ex->getCode() == 34) {
                $ModeloFiscal = new ModeloFiscal(); // Crear nuevo objeto ModeloFiscal

                $ModeloFiscal->setTipo($tipoDetalle); // Establecer tipo
                $ModeloFiscal->setValor($PercentRetirementValue); // Establecer valor
                $ModeloFiscal->setMandante($Mandante->mandante); // Establecer mandante
                $ModeloFiscal->setPaisId($Country); // Establecer país
                $ModeloFiscal->setMes($Mounth); // Establecer mes
                $ModeloFiscal->setAnio($Year); // Establecer año
                $ModeloFiscal->setEstado('A'); // Establecer estado
                $ModeloFiscal->setUsucreaId($_SESSION['usuario']); // Usuario que crea

                $ModeloFiscalMySqlDAO = new ModeloFiscalMySqlDAO($Transaction); // DAO para operaciones MySQL
                $ModeloFiscalMySqlDAO->insert($ModeloFiscal); // Insertar en la base de datos
            }
        }
    }
    // Verifica si el valor de porcentaje para apuestas deportivas no está vacío y es numérico
    if ($PercentValueSportsBets !== '' && is_numeric($PercentValueSportsBets)) {
        // Crea una instancia de Clasificador con el tipo correspondiente
        $Clasificador = new Clasificador('', 'PORCENVAAPUESDEPOR');
        $tipoDetalle = $Clasificador->getClasificadorId();

        try {
            // Crea un nuevo objeto ModeloFiscal
            $ModeloFiscal = new ModeloFiscal('', $tipoDetalle, $Mandante->mandante, $Country, $Mounth, $Year);

            if ($ModeloFiscal->getValor() != $PercentValueSportsBets) {

                $ModeloFiscal->setValor($PercentValueSportsBets);
                $ModeloFiscal->setUsumodifIf($_SESSION['usuario']);

                $ModeloFiscalMySqlDAO = new ModeloFiscalMySqlDAO($Transaction);
                $ModeloFiscalMySqlDAO->update($ModeloFiscal);
            }
        } catch (Exception $ex) {
            // Captura la excepción y verifica el código de error
            if ($ex->getCode() == 34) {
                $ModeloFiscal = new ModeloFiscal();

                // Asigna valores al nuevo objeto ModeloFiscal
                $ModeloFiscal->setTipo($tipoDetalle);
                $ModeloFiscal->setValor($PercentValueSportsBets);
                $ModeloFiscal->setMandante($Mandante->mandante);
                $ModeloFiscal->setPaisId($Country);
                $ModeloFiscal->setMes($Mounth);
                $ModeloFiscal->setAnio($Year);
                $ModeloFiscal->setEstado('A');
                $ModeloFiscal->setUsucreaId($_SESSION['usuario']);

                // Crea DAO para la base de datos e inserta el nuevo registro
                $ModeloFiscalMySqlDAO = new ModeloFiscalMySqlDAO($Transaction);
                $ModeloFiscalMySqlDAO->insert($ModeloFiscal);
            }
        }
    }

    // Verifica si el valor de porcentaje para apuestas no deportivas no está vacío y es numérico
    if ($PercentValueNonSportBets !== '' && is_numeric($PercentValueNonSportBets)) {
        $Clasificador = new Clasificador('', 'PORCENVAAPUESNODEPOR');
        $tipoDetalle = $Clasificador->getClasificadorId();

        try {
            // Crea un nuevo objeto ModeloFiscal
            $ModeloFiscal = new ModeloFiscal('', $tipoDetalle, $Mandante->mandante, $Country, $Mounth, $Year);

            // Verifica si el valor actual en el modelo fiscal es diferente al nuevo valor
            if ($ModeloFiscal->getValor() != $PercentValueNonSportBets) {

                $ModeloFiscal->setValor($PercentValueNonSportBets);
                $ModeloFiscal->setUsumodifIf($_SESSION['usuario']);

                // Crea DAO para la base de datos y actualiza el registro
                $ModeloFiscalMySqlDAO = new ModeloFiscalMySqlDAO($Transaction);
                $ModeloFiscalMySqlDAO->update($ModeloFiscal);
            }
        } catch (Exception $ex) {
            // Captura la excepción y verifica el código de error
            if ($ex->getCode() == 34) {
                $ModeloFiscal = new ModeloFiscal();

                // Asigna valores al nuevo objeto ModeloFiscal
                $ModeloFiscal->setTipo($tipoDetalle);
                $ModeloFiscal->setValor($PercentValueNonSportBets);
                $ModeloFiscal->setMandante($Mandante->mandante);
                $ModeloFiscal->setPaisId($Country);
                $ModeloFiscal->setMes($Mounth);
                $ModeloFiscal->setAnio($Year);
                $ModeloFiscal->setEstado('A');
                $ModeloFiscal->setUsucreaId($_SESSION['usuario']);

                // Crea DAO para la base de datos e inserta el nuevo registro
                $ModeloFiscalMySqlDAO = new ModeloFiscalMySqlDAO($Transaction);
                $ModeloFiscalMySqlDAO->insert($ModeloFiscal);
            }
        }
    }

    if ($PercentValueSportsAwards !== '' && is_numeric($PercentValueSportsAwards)) {
        $Clasificador = new Clasificador('', 'PORCENVAPREMDEPOR');
        $tipoDetalle = $Clasificador->getClasificadorId();

        try {
            $ModeloFiscal = new ModeloFiscal('', $tipoDetalle, $Mandante->mandante, $Country, $Mounth, $Year);

            if ($ModeloFiscal->getValor() != $PercentValueSportsAwards) {

                $ModeloFiscal->setValor($PercentValueSportsAwards);
                $ModeloFiscal->setUsumodifIf($_SESSION['usuario']);

                $ModeloFiscalMySqlDAO = new ModeloFiscalMySqlDAO($Transaction);
                $ModeloFiscalMySqlDAO->update($ModeloFiscal);
            }
        } catch (Exception $ex) {
            if ($ex->getCode() == 34) {
                $ModeloFiscal = new ModeloFiscal();

                $ModeloFiscal->setTipo($tipoDetalle);
                $ModeloFiscal->setValor($PercentValueSportsAwards);
                $ModeloFiscal->setMandante($Mandante->mandante);
                $ModeloFiscal->setPaisId($Country);
                $ModeloFiscal->setMes($Mounth);
                $ModeloFiscal->setAnio($Year);
                $ModeloFiscal->setEstado('A');
                $ModeloFiscal->setUsucreaId($_SESSION['usuario']);

                $ModeloFiscalMySqlDAO = new ModeloFiscalMySqlDAO($Transaction);
                $ModeloFiscalMySqlDAO->insert($ModeloFiscal);
            }
        }
    }

    if ($PercentValueNonSportsAwards !== '' && is_numeric($PercentValueNonSportsAwards)) {
        $Clasificador = new Clasificador('', 'PORCENVAPREMNODEPOR');
        $tipoDetalle = $Clasificador->getClasificadorId();

        try {
            $ModeloFiscal = new ModeloFiscal('', $tipoDetalle, $Mandante->mandante, $Country, $Mounth, $Year);

            if ($ModeloFiscal->getValor() != $PercentValueNonSportsAwards) {

                $ModeloFiscal->setValor($PercentValueNonSportsAwards);
                $ModeloFiscal->setUsumodifIf($_SESSION['usuario']);

                $ModeloFiscalMySqlDAO = new ModeloFiscalMySqlDAO($Transaction);
                $ModeloFiscalMySqlDAO->update($ModeloFiscal);
            }
        } catch (Exception $ex) {
            if ($ex->getCode() == 34) {
                $ModeloFiscal = new ModeloFiscal();

                $ModeloFiscal->setTipo($tipoDetalle);
                $ModeloFiscal->setValor($PercentValueNonSportsAwards);
                $ModeloFiscal->setMandante($Mandante->mandante);
                $ModeloFiscal->setPaisId($Country);
                $ModeloFiscal->setMes($Mounth);
                $ModeloFiscal->setAnio($Year);
                $ModeloFiscal->setEstado('A');
                $ModeloFiscal->setUsucreaId($_SESSION['usuario']);

                $ModeloFiscalMySqlDAO = new ModeloFiscalMySqlDAO($Transaction);
                $ModeloFiscalMySqlDAO->insert($ModeloFiscal);
            }
        }
    }
    /**
     * Fin del proceso de actualización e inserción de modelos fiscales.
     */
    // Verifica si el valor de porcentaje de bonos deportivos no está vacío y es numérico
    if ($PercentValueSportsBonds !== '' && is_numeric($PercentValueSportsBonds)) {
        // Crea un nuevo clasificador para bonos deportivos
        $Clasificador = new Clasificador('', 'PORCENVABONDEPOR');
        $tipoDetalle = $Clasificador->getClasificadorId();

        try {
            // Crea un nuevo modelo fiscal con los parámetros necesarios
            $ModeloFiscal = new ModeloFiscal('', $tipoDetalle, $Mandante->mandante, $Country, $Mounth, $Year);

            // Comprueba si el valor del modelo fiscal es diferente al valor de porcentaje de bonos deportivos
            if ($ModeloFiscal->getValor() != $PercentValueSportsBonds) {

                $ModeloFiscal->setValor($PercentValueSportsBonds);
                $ModeloFiscal->setUsumodifIf($_SESSION['usuario']);

                // Crea un DAO para el modelo fiscal y actualiza
                $ModeloFiscalMySqlDAO = new ModeloFiscalMySqlDAO($Transaction);
                $ModeloFiscalMySqlDAO->update($ModeloFiscal);
            }
        } catch (Exception $ex) {
            // Manejo de excepción para el código específico
            if ($ex->getCode() == 34) {
                $ModeloFiscal = new ModeloFiscal();

                // Asigna los valores al nuevo modelo fiscal
                $ModeloFiscal->setTipo($tipoDetalle);
                $ModeloFiscal->setValor($PercentValueSportsBonds);
                $ModeloFiscal->setMandante($Mandante->mandante);
                $ModeloFiscal->setPaisId($Country);
                $ModeloFiscal->setMes($Mounth);
                $ModeloFiscal->setAnio($Year);
                $ModeloFiscal->setEstado('A');
                $ModeloFiscal->setUsucreaId($_SESSION['usuario']);

                // Inserta el nuevo modelo fiscal utilizando el DAO
                $ModeloFiscalMySqlDAO = new ModeloFiscalMySqlDAO($Transaction);
                $ModeloFiscalMySqlDAO->insert($ModeloFiscal);
            }
        }
    }

    // Verifica si el valor de porcentaje de bonos no deportivos no está vacío y es numérico
    if ($PercentValueNonSportsBounds !== '' && is_numeric($PercentValueNonSportsBounds)) {
        // Crea un nuevo clasificador para bonos no deportivos
        $Clasificador = new Clasificador('', 'PORCENVABONNODEPOR');
        $tipoDetalle = $Clasificador->getClasificadorId();

        try {
            $ModeloFiscal = new ModeloFiscal('', $tipoDetalle, $Mandante->mandante, $Country, $Mounth, $Year);

            if ($ModeloFiscal->getValor() != $PercentValueNonSportsBounds) {
                // Asigna el nuevo valor y el usuario que lo modifica
                $ModeloFiscal->setValor($PercentValueNonSportsBounds);
                $ModeloFiscal->setUsumodifIf($_SESSION['usuario']);

                // Crea un DAO para el modelo fiscal y actualiza
                $ModeloFiscalMySqlDAO = new ModeloFiscalMySqlDAO($Transaction);
                $ModeloFiscalMySqlDAO->update($ModeloFiscal);
            }
        } catch (Exception $ex) {
            // Manejo de excepción para el código específico
            if ($ex->getCode() == 34) {
                $ModeloFiscal = new ModeloFiscal();

                $ModeloFiscal->setTipo($tipoDetalle);
                $ModeloFiscal->setValor($PercentValueNonSportsBounds);
                $ModeloFiscal->setMandante($Mandante->mandante);
                $ModeloFiscal->setPaisId($Country);
                $ModeloFiscal->setMes($Mounth);
                $ModeloFiscal->setAnio($Year);
                $ModeloFiscal->setEstado('A');
                $ModeloFiscal->setUsucreaId($_SESSION['usuario']);

                // Inserta el nuevo modelo fiscal utilizando el DAO
                $ModeloFiscalMySqlDAO = new ModeloFiscalMySqlDAO($Transaction);
                $ModeloFiscalMySqlDAO->insert($ModeloFiscal);
            }
        }
    }

    if ($PercentValueTickets !== '' && is_numeric($PercentValueTickets)) {
        $Clasificador = new Clasificador('', 'PORCENVATICKET');
        $tipoDetalle = $Clasificador->getClasificadorId();

        try {
            $ModeloFiscal = new ModeloFiscal('', $tipoDetalle, $Mandante->mandante, $Country, $Mounth, $Year);

            if ($ModeloFiscal->getValor() != $PercentValueTickets) {

                $ModeloFiscal->setValor($PercentValueTickets);
                $ModeloFiscal->setUsumodifIf($_SESSION['usuario']);
                $ModeloFiscalMySqlDAO = new ModeloFiscalMySqlDAO($Transaction);
                $ModeloFiscalMySqlDAO->update($ModeloFiscal);
            }
        } catch (Exception $ex) {
            if ($ex->getCode() == 34) {
                $ModeloFiscal = new ModeloFiscal();

                $ModeloFiscal->setTipo($tipoDetalle);
                $ModeloFiscal->setValor($PercentValueTickets);
                $ModeloFiscal->setMandante($Mandante->mandante);
                $ModeloFiscal->setPaisId($Country);
                $ModeloFiscal->setMes($Mounth);
                $ModeloFiscal->setAnio($Year);
                $ModeloFiscal->setEstado('A');
                $ModeloFiscal->setUsucreaId($_SESSION['usuario']);

                $ModeloFiscalMySqlDAO = new ModeloFiscalMySqlDAO($Transaction);
                $ModeloFiscalMySqlDAO->insert($ModeloFiscal);
            }
        }
    }

    $Transaction->commit();

    /*Generación formato de respuesta*/
    $response['HasError'] = false;
    $response['AlertType'] = 'success';
    $response['AlertMessage'] = '';
    $response['ModelErrors'] = [];
} catch (Exception $ex) {
    echo $ex->getMessage();
}
?>