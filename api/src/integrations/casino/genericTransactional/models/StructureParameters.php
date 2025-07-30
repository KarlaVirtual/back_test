<?php

namespace Backend\integrations\casino\genericTransactional\models;

/**
 * This class defines the required order of parameters to be sent to the transactional method.
 * 
 * @category API
 * @package casino\genericTransactional\models\
 * @author Esteban Arévalo
 * @version 1.0
 * @since 3/3/2025
 */
class StructureParameters {

    // Defines the required order of parameters for the debit method.
    public static function debit(){
        return [
            'gameId',
            'transactionId',
            'amount',
            'roundId',
            'currency',
            'isFreeSpin',           // optional
            'bets',                 // optional
            'existTicketAllowed',   // optional
            'allowChanIfIsEnd'      // optional
        ];
    }

    // Defines the required order of parameters for the credit method.
    public static function credit(){
        return [
            'gameId',
            'transactionId',
            'amount',
            'roundId',
            'currency',
            'isEndRound',
            'isFreeSpin',
            'isOnlyOneWin',         // optional
            'allowChangIfIsEnd'     // optional
        ];
    }

    // Defines the required order of parameters for the balance method.
    public static function balance(){
        return ['currency'];
    }

    // Defines the required order of parameters for the authenticate method.
    public static function authenticate(){
        return [
            'updateToken',
            'gameId',           // optional
            'extractedValue',   // optional
            'newToken'          // optional
        ];
    }

    // Defines the required order of parameters for the rollback method.
    public static function rollback(){
        return [
            'amount',
            "transactionId",
            'roundId',
            'gameId',
            'validationTicketValue',        // optional
            "specificTransaction",          // optional 
            'allowChangIfIsEnd',            // optional
            'validateValueOfTransaction',   // optional
            'allowCreditTransaction',       // optional
            'checkDeleteRound',             // optional
            'status'                        // optional
        ];
    }
}