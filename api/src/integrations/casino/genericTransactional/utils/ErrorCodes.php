<?php

namespace Backend\integrations\casino\genericTransactional\utils;

/**
 * List of errors used in the generic transactional model.
 * 
 * @category API
 * @package casino\genericTransactional\utils\
 * @author Esteban Arévalo
 * @version 1.0
 * @since 3/3/2025
 */
class ErrorCodes {

    /**
     * List of errors used in the generic transactional model. 
     * This list is used to verify the existence of an error and, 
     * if true, return a default response type to the provider. 
     * If the error is not found in this list, one of the errors mapped 
     * in the provider's configuration file, errorResponseConfig.json, will be returned.
     * 
     * @return array List of error codes.
     */
    public static function getErrorCodes() :array {
        return 
        [
            300074, 
            300075, 
            300076, 
            300077, 
            300078, 
            300079, 
            300080,
            300081,
            300082,
            300083,
            300086,
            300087,
            300088,
            300091,
            300105,
            300106,
            300107,
            300108,
            300109,
            300110,
            300111,
            300112,
            300113,
            300114,
            300115,
            300116,
            300117,
            300118,
            300119,
            300120,
            300121,
            300122,
            300123,
            300124,
            300125,
            300126,
            300127,
            300128,
            300129,
            300130,
            300131,
            300132,
            300133,
            300134,
            300135,
            300136,
            300137,
            300141,
            300144,
            300147,
            300148,
            300149,
            300150,
            300157,
            300158
        ];
    }
}