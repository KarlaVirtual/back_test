<?php 
namespace Backend\integrations\casino\genericTransactional\controller;

use Backend\dto\Producto;
use Backend\dto\ProductoMandante;
use Backend\dto\Proveedor;
use Backend\dto\SubproveedorMandantePais;
use Backend\dto\TransaccionApi;
use Backend\dto\TransaccionJuego;
use Backend\dto\TransjuegoLog;
use Backend\dto\Usuario;
use Backend\dto\UsuarioMandante;
use Backend\dto\UsuarioToken;
use Backend\integrations\casino\Game;
use Backend\integrations\casino\genericTransactional\models\DataConvert;
use Backend\integrations\casino\genericTransactional\models\Validate;
use Backend\integrations\casino\genericTransactional\utils\TransactionalUtilities;
use Backend\mysql\TransaccionApiMySqlDAO;
use Backend\mysql\UsuarioTokenMySqlDAO;
use Exception;
use Throwable;

/**
 * This class is responsible for managing the transactional flow, 
 * including the execution of transactional methods, creation, and storage of API transactions. 
 * It also defines arrays of additional resources for error responses and validates whether the userId, 
 * token, and externalId are valid to determine if the transactional flow should proceed.
 * 
 * @category API
 * @package casino\genericTransactional\controller\
 * @author Esteban Arévalo
 * @version 1.0
 * @since 4/3/2025
 */
class TransactionalProccess extends TransactionalUtilities {

    /**
     * The token used for user authentication.
     * @var string
     */
    private $token;

    /**
     * The user Id used for user authentication.
     * @var string
     */
    private $userId; 

    /**
     * A universally unique identifier (UUID).
     * @var string
     */
    private $requestUUID;

    /**
     * The session ID for the current user.
     * @var string
     */
    private $sessionId;

    /**
     * The name of the provider involved in the transaction.
     * @var string
     */
    private $provider;

    /**
     * It's an unique identifier for each partner
     * @var mixed
     */
    private $operatorId; 

    /**
     * The bonus balance associated with the transaction.
     * @var float
     */
    private $bonus;

    /**
     * Error code generated for an exception for searching an error array
     * @var int
     */
    private $errorCode;

    /**
     * The signature generated for the transaction.
     * @var string
     */
    private $signature;

    /**
     * The status of the transaction.
     * @var string
     */
    private $status;

    /**
     * Message about the transaction result, whether it was successful or not
     * @var string
     */
    private $message;

    /**
     * The version of the API used for the transaction.
     * @var string
     */
    private $apiVersion;

    /**
     * The request data for the transaction.
     * @var mixed
     */
    private $request;

    /**
     * The external ID used for user authentication.
     * @var string
     */
    private $externalId;

    /**
     * Indicates the methods that should be rolled back consecutively.
     * @var mixed The name methods to be rolled back.
     */
    private $rollbackTo;

    /**
     * The current transaction API object.
     * @var TransaccionApi
     */
    private TransaccionApi $transaccionApi;

    /**
     * Query object to access the data of UsuarioToken
     * @var UsuarioToken
     */
    private UsuarioToken $usuarioToken;

    /**
     * Query object to access the data of UsuarioMandante
     * @var UsuarioMandante
     */
    private UsuarioMandante $usuarioMandante;

    /**
     * Query object to access the data of Proveedor
     * @var Proveedor
     */
    private Proveedor $proveedor;

    /**
     * The name of the method used for debit transactions.
     * @var string
     */
    private static $debitMethod = 'DEBIT';

    /**
     * The name of the method used for credit transactions.
     * @var string
     */
    private static $creditMethod = 'CREDIT';

    /**
     * The name of the method used for balance transactions.
     * @var string
     */
    private static $balanceMethod = 'BALANCE';

    /**
     * The name of the method used for authentication transactions.
     * @var string
     */
    private static $authenticateMethod = 'AUTHENTICATE';

    /**
     * The name of the method used for rollback transactions.
     * @var string
     */
    private static $rollbackMethod = 'ROLLBACK';

    /**
     * The name to extract the necessary keys for the resource array content.
     * @var string
     */
    private static $sourceError = 'SOURCE';

    /**
     * Saves the user name
     * @var string
     */
    private $userName;

    /**
     * Saves the user password
     * @var string
     */
    private $password;

    /**
     * Indicates whether the token needs to be updated.
     * If true, the token is automatically updated (random token).
     * @var bool
     */
    private bool $updateToken;

    /**
     * Indicates whether the token should be replaced. 
     * If the string is not empty, its value will be used to create a new token.
     * @var string
     */
    private string $newToken;

    /**
     * Variable where the date will be stored according to the format indicated by the supplier
     * @var string
     */
    private static $date = "extract the DATE in the supplier's response and replace this string";

    /**
    * Initializes an object by assigning values from an input array to its properties.
    * This constructor dynamically maps an array of data to the class properties based on predefined keys.
    * If a key in the `$data` array does not exist, the corresponding property is set to `null`.
    *
    * @param array &$data An associative array containing the data to initialize the object's properties. 
    *        Keys in this array should match the names in the predefined property list (`$keysName`).
    *
    * @throws Exception If an error occurs while initializing the object properties.
    */
    public function initializeData(array &$data){
        try {
            $keysName = [
                'externalId',
                'token',
                'sessionId',
                'userId',
                'bonus',
                'errorCode',
                'message',
                'signature',
                'status',
                'requestUUID',
                'operatorId',
                'request',
                'provider',
                'userName',
                'password'
            ];
    
            $validate = function($propertyName) use ($data){
                $this->$propertyName = $data[$propertyName] ?? null;
            };
    
            foreach($keysName as $property){
                $validate($property);
            }
            
        } catch (Throwable $th) {
            throw new Exception(
                "Failed to initialize data properties. An unexpected error occurred while mapping values. Details: {$th->getMessage()}", 
                300107
            );
        } 
    }

    /**
     * Processes a debit transaction for a specific supplier, handling user, game, 
     * and transaction details, and generates a formatted response.
     *
     * @param string $supplierName The name of the supplier involved in the transaction.
     * @param array &$acronyms Reference to an array of acronyms used for validation.
     * @param mixed &$data Reference to the data used for the transaction.
     * @param string $gameId The unique identifier of the game.
     * @param string $transactionId The unique identifier for the transaction.
     * @param float $amount The amount to be debited.
     * @param string $roundId The unique identifier for the game round.
     * @param string|null $currency The currency used for the transaction. Defaults to the user's currency if not provided.
     * @param bool $isFreeSpin Indicates if the transaction is a free spin. Default is false.
     * @param array $bets An array of bets associated with the transaction. Default is an empty array.
     * @param bool $existTicketAllowed Determines if existing tickets are allowed. Default is true.
     * @param bool $allowChangIfIsEnd Determines if changes are allowed when the game round ends. Default is true.
     *
     * @return array The formatted response containing transaction details.
     *
     * @throws Exception If any error occurs during the transaction process.
     */
    public function debit(
        $supplierName, 
        $acronyms, 
        &$data, 
        $gameId, 
        $transactionId, 
        $amount, 
        $roundId, 
        $currency, 
        $isFreeSpin = false, 
        $bets = [], 
        $existTicketAllowed = true, 
        $allowChangIfIsEnd = true) :array {
        try {
            $this->proveedor = new Proveedor("", $supplierName);
            
            $this->validateFor($acronyms);
            $this->createTransactionApi($transactionId, $data, $amount, self::$debitMethod);

            $this->transaccionApi->setIdentificador($roundId . $this->usuarioMandante->getUsumandanteId() . $supplierName);
            $producto = new Producto("", $gameId, $this->proveedor->getProveedorId());
            $game = new Game();
            $responseGame = $game->debit(
                $this->usuarioMandante, 
                $producto, 
                $this->transaccionApi, 
                $isFreeSpin, 
                $bets, 
                $existTicketAllowed, 
                $allowChangIfIsEnd
            );

            $this->transaccionApi = $responseGame->transaccionApi;

            $Usuario = new Usuario($this->usuarioMandante->getUsuarioMandante());
            $genericResponse = $this->createGenericResponse(
                self::$debitMethod,
                isset($currency) ? $currency : (isset($this->usuarioMandante) ? $this->usuarioMandante->moneda : null),
                $responseGame->transaccionId,
                $transactionId,
                $this->bonus,
                $responseGame->saldo,
                $this->errorCode,
                $this->message,
                self::$date,
                $this->token,
                $this->signature,
                $this->status,
                isset($this->usuarioMandante) ? $this->usuarioMandante->getUsumandanteId() : null,
                isset($this->requestUUID) ? $this->requestUUID : $this->generateUUID(),
                $this->sessionId,
                $this->apiVersion,
                $this->operatorId,
                $roundId,
                $amount,
                $Usuario->getNombre(),
                $Usuario->idioma,
                isset($this->proveedor) ? $this->proveedor->getProveedorId() : null,
                $this->request,
                $this->provider,
                ($this->responseGame->saldo + $amount)
            );

            return $genericResponse;

        } catch (Throwable $th){
            throw new Exception($th->getMessage(), $th->getCode() == 0 ? 300105 : $th->getCode());
        }
    }

    /**
     * Processes a credit transaction for a specific supplier, handling user, game, 
     * and transaction details, and generates a formatted response.
     *
     * @param string $supplierName The name of the supplier involved in the transaction.
     * @param array &$acronyms Reference to an array of acronyms used for validation.
     * @param mixed &$data Reference to the data used for the transaction.
     * @param string $gameId The unique identifier of the game.
     * @param string $transactionId The unique identifier for the transaction.
     * @param float $amount The amount to be credited.
     * @param string $roundId The unique identifier for the game round.
     * @param string $currency The currency used for the transaction. Defaults to the user's currency if not provided.
     * @param bool $isEndRound Indicates whether the transaction marks the end of the game round.
     * @param bool $isFreeSpin Indicates if the transaction is for a free spin. Default is false.
     * @param bool $isOnlyOneWin Indicates if only one win is allowed for this transaction. Default is false.
     * @param bool $allowChangIfIsEnd Determines if changes are allowed when the game round ends. Default is true.
     *
     * @return array The formatted response containing transaction details.
     *
     * @throws Exception If any error occurs during the transaction process.
     */
    public function credit(
        $supplierName, 
        $acronyms, 
        &$data, 
        $gameId, 
        $transactionId, 
        $amount, 
        $roundId, 
        $currency,
        $isEndRound, 
        $isFreeSpin = false, 
        $isOnlyOneWin = false, 
        $allowChangIfIsEnd = true) :array {
        try {
            $this->proveedor = new Proveedor('', $supplierName);
            
            $this->validateFor($acronyms);
            $this->createTransactionApi($transactionId, $data, $amount, self::$creditMethod);

            try {
                $transaccionJuego = new TransaccionJuego("", $roundId . $this->usuarioMandante->getUsumandanteId() . $supplierName);
                $this->usuarioMandante = new UsuarioMandante($transaccionJuego->getUsuarioId());
            } catch (Throwable $th) {
                throw new Exception(
                    'Transaction does not exist',
                    10005
                );
            }
            
            $this->transaccionApi->setIdentificador($roundId . $this->usuarioMandante->getUsumandanteId() . $supplierName);
            $producto = new Producto('', $gameId, $this->proveedor->getProveedorId());
            $productoMandante = new ProductoMandante($producto->getProductoId(), $this->usuarioMandante->getMandante());

            $this->transaccionApi->setProductoId($productoMandante->getProdmandanteId());
            $this->transaccionApi->setUsuarioId($this->usuarioMandante->getUsumandanteId());

            $game = new Game();
            $responseGame = $game->credit(
                $this->usuarioMandante, 
                $producto, 
                $this->transaccionApi, 
                $isEndRound, 
                $isOnlyOneWin, 
                $isFreeSpin, 
                $allowChangIfIsEnd
            );

            $this->transaccionApi = $responseGame->transaccionApi;

            $genericResponse = $this->createGenericResponse(
                self::$creditMethod,
                $this->status,
                isset($currency) ? $currency : (isset($this->usuarioMandante) ? $this->usuarioMandante->moneda : null),
                $responseGame->saldo,
                $transactionId,
                $responseGame->transaccionId,
                $this->bonus,
                $this->errorCode,
                $this->message,
                self::$date,
                $this->token,
                isset($this->usuarioMandante) ? $this->usuarioMandante->getUsumandanteId() : null,
                $this->requestUUID,
                $this->request,
                $this->sessionId,
                $this->operatorId,
                $roundId,
                $this->signature,
                isset($this->proveedor) ? $this->proveedor->getProveedorId() : null,
                $amount,
                $this->provider,
                ($this->responseGame->saldo - $amount)
            );

            return $genericResponse;
    
        } catch (Throwable $th) {
           throw new Exception($th->getMessage(), $th->getCode() == 0 ? 300105 : $th->getCode());
        }
    } 

    /**
     * Retrieves the balance for a specific supplier and user, formats the response,
     * and returns it in a structured format.
     *
     * @param string $supplierName The name of the supplier to retrieve the balance from.
     * @param array &$acronym Reference to an array of acronyms used for validation.
     * @param string|null $currency The currency for the balance. Defaults to the user's currency if not provided.
     *
     * @return array The formatted response containing the user's balance and related details.
     *
     * @throws Exception If any error occurs during the balance retrieval process.
     */
    public function balance(string $supplierName, array $acronym, $currency) :array {
        try {
            $this->proveedor = new Proveedor("", $supplierName);
            $this->validateFor($acronym);
            $this->createTransactionApi('0', '', 0, self::$balanceMethod);

            $game = new Game();
            $responseGame = $game->getBalance($this->usuarioMandante);

            $genericResponse = $this->createGenericResponse(
                self::$balanceMethod,
                $responseGame->saldo,
                $this->bonus,
                isset($currency) ? $currency : (isset($this->usuarioMandante) ? $this->usuarioMandante->moneda : null),
                $responseGame->usuarioId,
                $this->token,
                $this->message,
                $this->errorCode,
                $this->status,
                self::$date,
            );

            return $genericResponse;

        } catch (Throwable $th) {
            throw new Exception($th->getMessage(), $th->getCode() == 0 ? 300105 : $th->getCode());
        }
    }

    /**
     * Authenticates a user for a specific supplier, retrieves user details, and returns
     * a formatted response with authentication and balance information.
     *
     * @param string $supplierName The name of the supplier for which the user will be authenticated.
     * @param array &$acronym Reference to an array of acronyms used for validation.
     * @param bool $updatetoken Indicates whether to update the token with a randomly generated value.
     * @param string|null $gameId The unique identifier of the game.
     * @param mixed $extractedValue The extracted value for validation.
     * @param $newToken Indicates whether to replaces the token in the database with the value defined in $newToken
     *
     * @return array A structured response containing the authentication result, user details, 
     *               and balance information.
     *
     * @throws Exception If any error occurs during the authentication process.
     */
    public function authenticate(
        string $supplierName, 
        array $acronym, 
        $updateToken = false, 
        $gameId = null, 
        $extractedValue = null,
        $newToken = "") :array {
        try {
            $this->proveedor = new Proveedor("", $supplierName);

            $this->updateToken = $updateToken;
            $this->newToken = $newToken;

            $this->validateFor($acronym);
            $this->createTransactionApi('0', '', 0, self::$authenticateMethod);

            // Validates if the user's credentials match the credentials in the database, only if necessary.
            if (!empty($this->userName) && !empty($this->password)){
                $credentialsToCompare = array($this->userName, $this->password);
                $userCredentials = $this->getsTheUserCredentials($gameId);
                Validate::userNameAndPassword($userCredentials, $credentialsToCompare);
            }

            // Validates if the currency from the request matches the currency from the database, only if necessary.
            if (is_array($extractedValue)){
                ['extractedValue' => $extractedValue, 'validationType' => $validationType] = $extractedValue;
                if ($validationType == 'someCurrency') Validate::someCurrency($this->usuarioMandante->moneda, $extractedValue);
            }

            $game = new Game();
            $responseGame = $game->autenticate($this->usuarioMandante);

            $genericResponse = $this->createGenericResponse(
                self::$authenticateMethod,
                $responseGame->usuarioId . "_" . $responseGame->moneda,
                $responseGame->saldo,
                $this->bonus,
                $responseGame->moneda,
                $this->errorCode,
                $this->message,
                $responseGame->usuarioId,
                $responseGame->usuario,
                $this->sessionId,
                $this->token,
                $this->status,
                self::$date,
            );

            return $genericResponse;

        } catch (Throwable $th) {
            throw new Exception($th->getMessage(), $th->getCode() == 0 ? 300105 : $th->getCode());
        }
    }

    /**
     * Gets the user's credentials from the database and adds them to an array.
     * @param string $gameId The unique identifier of the game.
     * 
     */
    private function getsTheUserCredentials($gameId){
        try {
            $producto = new Producto("", $gameId, $this->proveedor->getProveedorId());
            $subProveedorMandantePais = new SubproveedorMandantePais(
                '', 
                $producto->subproveedorId, 
                $this->usuarioMandante->mandante, 
                $this->usuarioMandante->paisId
            );

            $userCredentials = DataConvert::fromJsonToObject($subProveedorMandantePais->getCredentials());
            return array($userCredentials->USERNAME, $userCredentials->PASSWORD);

        } catch (Throwable $th) {
            throw new Exception("It was not possible to retrieve the user's credentials.", 300143);
        }
    }

    /**
     * Reverses a previously executed debit or credit transactions, restoring the balance to its previous state,
     * and returns a formatted response with rollback details.
     *
     * @param string $supplierName The name of the supplier for which the rollback is being performed.
     * @param array &$acronym Reference to an array of acronyms used for validation.
     * @param array &$data Reference to additional data required for processing the rollback.
     * @param float $amount The amount associated with the rollback transaction.
     * @param string $transactionId The ID of the transaction to be rolled back.
     *
     * @return array A structured response containing rollback details, including the transaction ID, 
     *               updated balance, and currency information.
     *
     * @throws Exception If the transaction does not exist, is not a debit type, or any other error occurs during processing.
     */
    public function rollback(
        string $supplierName, 
        array $acronym, 
        &$data, 
        $amount, 
        $transactionId, 
        $roundId, 
        $gameId, 
        $validationTicketValue = false, 
        $specificTransaction = '', 
        $allowChangIfIsEnd = true, 
        $validateValueOfTransaction = false, 
        $allowCreditTransaction = false, 
        $checkDeleteRound = false, 
        $status = 'I') :array {
        try {
            $this->proveedor = new Proveedor("", $supplierName);
            $this->validateFor($acronym);

            try {
                $producto = new Producto("", $gameId, $this->proveedor->getProveedorId());
                $this->rollbackTo = strtoupper($this->rollbackTo);

                $transaccionJuego = new TransaccionJuego("", $roundId . $this->usuarioMandante->getUsumandanteId() . $supplierName);
                $newTransactionId = $transaccionJuego->getTransaccionId();
                
                if ($this->rollbackTo === self::$creditMethod){
                    $allowCreditTransaction = true;
                    $newTransactionId = $transaccionJuego->getTransaccionId() . '_CREDIT';
                }
                
                $transJuegoLog = new TransjuegoLog("", $transaccionJuego->transjuegoId, "", $newTransactionId . '_' . $producto->getSubproveedorId());
                $transaccionJuego = new TransaccionJuego($transJuegoLog->transjuegoId);
                $this->usuarioMandante = new UsuarioMandante($transaccionJuego->getUsuarioId());

                if (strpos($transJuegoLog->getTipo(), $this->rollbackTo) === false){
                    throw new Exception("Transaction is not '{$this->rollbackTo}'", 10006);
                } 
                
                $this->createTransactionApi(self::$rollbackMethod . $newTransactionId, $data, $amount, self::$rollbackMethod);
                $this->transaccionApi->setIdentificador($transaccionJuego->getTicketId());

            } catch (Throwable $th) {
                throw new Exception('Transaction does not exist', 10005);
            }

            $game = new Game();
            $responseGame = $game->rollback(
                $this->usuarioMandante, 
                $this->proveedor, 
                $this->transaccionApi, 
                $validationTicketValue, 
                $specificTransaction, 
                $allowChangIfIsEnd, 
                $validateValueOfTransaction, 
                $allowCreditTransaction, 
                $checkDeleteRound, 
                $status
            );
            $this->transaccionApi = $responseGame->transaccionApi;

            $genericResponse = $this->createGenericResponse(
                self::$rollbackMethod,
                $responseGame->transaccionId,
                $transactionId,
                $responseGame->saldo,
                $responseGame->moneda,
                $this->bonus,
                $this->errorCode,
                $this->message,
                $responseGame->usuarioId,
                $this->provider,
                $roundId,
                ($responseGame->saldo - $amount),
                $gameId,
                $this->status,
                self::$date,
                $this->token
            );
            
            return $genericResponse;

        } catch (Throwable $th) {
            throw new Exception($th->getMessage(), $th->getCode() == 0 ? 300105 : $th->getCode());
        }
    }

    /**
     * Initializes and configures a new instance of the `TransaccionApi` object, 
     * setting its properties based on the provided transaction details.
     *
     * @param string $transactionId Is the transaction ID to be set in the `TransaccionApi` object.
     * @param array $data Is the data associated with the transaction.
     * @param float $amount Is the monetary amount of the transaction. Defaults to 0 if not provided.
     * @param string &$nameMethod Reference to the name of the method/type for the transaction (e.g., debit, credit, rollback).
     *
     * @return void
     *
     * @throws Exception If an error occurs while setting up the `TransaccionApi` object.
     */
    private function createTransactionApi($transactionId, $data, $amount, &$nameMethod) :void {
        try {
            $amount = $amount ?? 0;
            $this->transaccionApi = new TransaccionApi();
            $this->transaccionApi->setTransaccionId($transactionId);
            $this->transaccionApi->setTipo($nameMethod);
            $this->transaccionApi->setProveedorId($this->proveedor->getProveedorId());
            $this->transaccionApi->setTValue($data);
            $this->transaccionApi->setUsucreaId(0);
            $this->transaccionApi->setUsumodifId(0);
            $this->transaccionApi->setValor($amount);
        } catch (Throwable $th) {
            throw new Exception("Transaction API creation failed. An error occurred while initializing transaction properties. Details: {$th->getMessage()}", 300108);
        }
    }

    /**
     * Validates the provided acronyms and initializes associated user objects as needed.
     * @param array $validateAcronyms An array of validation acronyms. 
     *              Each acronym determines the validation to be performed.
     *              The supported acronyms are: 'token', 'externalId', 'userId'.
     * @throws Exception If the array is empty or an unsupported acronym is provided.
     * @return void This function modifies internal properties like `usuarioToken` and `usuarioMandante`.
     */
    private function validateFor(array $validateAcronyms) :void {
        try {
            if (empty($validateAcronyms)){
                throw new Exception("Validation failed: The array of acronyms cannot be empty.", 300087);
            }

            foreach ($validateAcronyms as $acronym) {
                switch ($acronym) {
                    case 'token':
                        $validation = Validate::token($this->token);
                        if ($validation === true){
                            try {
                                $this->usuarioToken = new UsuarioToken($this->token, $this->proveedor->getProveedorId());
                                $this->usuarioMandante = new UsuarioMandante($this->usuarioToken->usuarioId);
        
                                // Updates the token with a randomly generated value in the database.
                                if (isset($this->updateToken)){
                                    $this->updateToken($this->token);
                                } 

                                // Replaces the token in the database with the value defined in $newToken
                                if (isset($this->newToken)){
                                    $this->updateToken($this->newToken);
                                }
                                
                            } catch (Throwable $th) {
                                Validate::userId($this->userId);
                                $this->usuarioMandante = new UsuarioMandante($this->userId);
                            }
                        } else {
                            Validate::userId($this->userId);
                            $this->usuarioMandante = new UsuarioMandante($this->userId);
                        }
                        break;
    
                    case 'externalId':
                        Validate::externalId($this->externalId);
                        $this->usuarioMandante = new UsuarioMandante($this->externalId);
                        break;
    
                    case 'userId':
                        Validate::userId($this->userId);
                        $this->usuarioMandante = new UsuarioMandante($this->userId);
                        break;
    
                    default:
                        throw new Exception(
                            "Validation failed: The validation for '{$acronym}' does not exist. " .
                            "Supported acronyms are: token, externalId, userId, userNameAndPassword", 300088
                        );
                }
            }
        } catch (Throwable $th) {
            throw new Exception($th->getMessage(), $th->getCode() == 0 ? 300105 : $th->getCode());
        }
    }

    /**
     * Sets the response data for the current `TransaccionApi` object, updates it in the database, 
     * and commits the transaction.
     * @return void
     * @param mixed &$response Reference to the response data to be stored in the `TransaccionApi` object.
     * @throws Exception If an error occurs while updating the `TransaccionApi` object or committing the transaction.
     */
    public function setResponseInTransaccionApi(&$response) :void {
        try {
            $this->transaccionApi->setRespuesta($response);
            $transaccionMySqlDAO = new TransaccionApiMySqlDAO();
            $transaccionMySqlDAO->update($this->transaccionApi);
            $transaccionMySqlDAO->getTransaction()->commit();
        } catch (Throwable $th) {
            throw new Exception("An error ocurred while updating the transaction response. Details: {$th->getMessage()}", 300109);
        }
    }

    /**
     * Builds and returns an associative array with game transaction details.
     * This function retrieves data from the `Game` object, constructs a transaction log, 
     * and collects balance and currency details to form the array.
     * @return array An associative array containing:
     *               - 'gameTransactionLogId': string, the transaction log ID.
     *               - 'balance': float, the balance retrieved from the game response.
     *               - 'currency': string, the currency type from the game response.
     * @throws Exception If any of the dependent objects fail to initialize or retrieve data.
     */
    public function buildSourceArray() :array {
        try {
            $game = new Game();
            $responseGame = $game->getBalance($this->usuarioMandante);

            $productoMandante = new ProductoMandante('', '', $this->transaccionApi->getProductoId());
            $producto = new Producto($productoMandante->productoId);
            $transjuegoLog = new TransjuegoLog(
                '', '', '', $this->transaccionApi->getTransaccionId() . '_' . $producto->getSubproveedorId(), 
                $producto->getSubproveedorId()
            );

            $genericResponse = $this->createGenericResponse(
                self::$sourceError,
                $transjuegoLog->transjuegologId,
                $responseGame->saldo,
                $responseGame->moneda
            );

            return $genericResponse;

        } catch (Throwable $th) {
            throw new Exception("An error occurred while building the source array. Details: {$th->getMessage()}", 300110);
        }
    }

    /**
     * Updates the token for the current user in the database.
     * @param string $token The new token to be updated in the database.
     * @return void
     * @throws Exception If an error occurs while updating the user token.
     */
    private function updateToken($token) :void {
        try {
            $this->usuarioToken->setToken($token);
            $usuarioTokenMySqlDAO = new UsuarioTokenMySqlDAO();
            $usuarioTokenMySqlDAO->update($this->usuarioToken);
            $usuarioTokenMySqlDAO->getTransaction()->commit();
        } catch (Throwable $th) {
            throw new Exception("An error ocurred while updating the user token. Details: {$th->getMessage()}", 300111);
        }
    }

    /**
     * Attempts to retrieve the user's balance. If the user is not found, 
     * the balance is set to 0. Otherwise, the user's balance is returned as an associative array.
     * @return array An associative array containing the user's balance.
     * @throws Exception If an error occurs while retrieving the user's balance.
     */
    public function retrieveBalance() :array {
        try {
            $balance = isset($this->usuarioMandante) ? $this->usuarioMandante->getSaldo() : 0;
            return ['balance' => $balance];
        } catch (Throwable $th) {
            throw new Exception("An error occurred while retrieving the user's balance. Details: {$th->getMessage()}", 300112);
        }
    }

    /**
     * Sets the rollback method for the current transaction.
     * @param string $rollbackTo The rollback method to be set.
     * @return void
     */
    public function setRollbackTo($rollbackTo) :void {
        $this->rollbackTo = $rollbackTo;
    }
}