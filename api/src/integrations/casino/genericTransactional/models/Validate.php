<?php

namespace Backend\integrations\casino\genericTransactional\models;

use Exception;
use Throwable;

/**
 * Contains the necessary validations to allow or restrict the normal flow of transactional methods.
 * 
 * @category API
 * @package casino\genericTransactional\models\
 * @author Esteban Arévalo
 * @version 1.0
 * @since 3/3/2025
 */
class Validate {

    /**
     * Validates that userId is not empty.
     * @param $userId Represents the id of an user.
     * @return void
     * @throws Exception If the userId is empty.
     */
    public static function userId($userId) :void {
        try {
            if (empty($userId)){
                throw new Exception('UsuarioId vacio', 300135);
            }
        } catch (Throwable $th) {
            throw new Exception($th->getMessage(), $th->getCode() == 0 ? 300105 : $th->getCode());
        }
    }

    /**
     * Validates that the token is not empty.
     * @param $token Represents the token of an user.
     * @return bool True if the token is not empty, false otherwise.
     * @throws Exception If the token is empty.
     */
    public static function token($token) :bool {
        try {
            if (!empty($token)){
                return true;
            }
            return false;
        } catch (Throwable $th) {
            throw new Exception("An error ocurred while validating the token: {$th->getMessage()}", 300136);
        } 
    }

    
   /**
    * Validates that the externalId is not empty.
    * @param $externalId Represents the external id of an user.
    * @return void
    * @throws Exception If the externalId is empty.
    */
    public static function externalId($externalId) :void {
        try {
            if (empty($externalId)) {
                throw new Exception('Incorrect credencials', 300137);
            }
        } catch (Throwable $th) {
            throw new Exception($th->getMessage(), $th->getCode() == 0 ? 300105 : $th->getCode());
        }
    }

    /**
     * Validates that the username and password from the request match those in the database.
     * @param $userAndPasswordFromDataDabase Represents the username and password from the database.
     * @param $userAndPasswordFromRequest Represents the username and password from the request.
     * @return void
     * @throws Exception If the user's credentials do not match.
     */
    public static function userNameAndPassword(array $userAndPasswordFromDataDabase, array $userAndPasswordFromRequest) :void {
        try {
            if ($userAndPasswordFromDataDabase != $userAndPasswordFromRequest){
                throw new Exception("The user's credentials do not match.", 300140);
            }
        } catch (Throwable $th) {
            throw new Exception($th->getMessage(), $th->getCode() == 0 ? 300105 : $th->getCode());
        }
    }

    /**
     * Validates that the currency from the request matches the currency from the database.
     * @param $currencyFromRequest Represents the currency from the request.
     * @param $currencyFromDatabase Represents the currency from the database.
     * @return void
     * @throws Exception If the currencies do not match.
     */
    public static function someCurrency($currencyFromRequest, $currencyFromDatabase) :void {
        try {
            if ($currencyFromRequest != $currencyFromDatabase){
                throw new Exception("The currencies does not match.", 300142);
            }
        } catch (Throwable $th) {
            throw new Exception ($th->getMessage(), $th->getCode() == 0 ? 300105 : $th->getCode());
        }
    }

    /**
     * Checks if an array is bidimensional or monodimensional (Is it a matrix or not?).
     * @param array $matrix Represents the array to be checked.
     * @return bool True if the array is a matrix, false otherwise.
     * @throws Exception If the array to check the matrix is null.
     */
    public static function isMatrix($matrix) :bool {
        try {
            if (!is_array($matrix)) return false;

            foreach ($matrix as $value) {
                return is_array($value);
            }

            return false;
            
        } catch (Throwable $th) {
            throw new Exception("The array to check the matrix cannot be null.", 300148);
        }
    }
}