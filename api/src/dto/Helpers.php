<?php

namespace Backend\dto;

use phpDocumentor\Reflection\Types\Null_;

/**
 * Clase Helpers utilizada en los procesos de encriptación
 * @category No
 * @package No
 * @author      Juan David Algarín Valencia / Juan Manuel Salazar
 * @version     1.0
 * @since       Desconocido
 */
class Helpers
{
    private $COST = 15;
    private $PASSWORD_HASH = PASSWORD_DEFAULT;
    private $METHOD = 'AES-128-CBC';
    private $SECRET_KEY = '';
    private $HELPER_KEY = '(^&$%(*)$!@!@$&ÛUIJG#$RE!@E!@G%^^&*(*&W';

    /**
     * Constructor de la clase Helpers.
     *
     * Inicializa la propiedad SECRET_KEY con el valor de la variable de entorno 'SECRET_KEY'.
     * Si la variable de entorno no está definida, la propiedad se establece en null.
     */
    public function __construct()
    {
        $this->SECRET_KEY = $_ENV['SECRET_KEY'] ?: null;
    }

    /**
     * set_custom_secret_key
     *
     * Establece una clave secreta personalizada, ya sea directamente o desde las variables de entorno.
     *
     * @param string $secret_key Clave secreta a establecer o nombre de la variable de entorno que la contiene.
     * @param bool $ENV Indica si la clave debe obtenerse desde las variables de entorno ($_ENV).
     *
     * @return self response Retorna la instancia actual del objeto para permitir encadenamiento de métodos.
     *
     *
     * @throws Exception no
     * @access public
     */

    public function set_custom_secret_key($secret_key, $ENV = false)
    {
        if ($ENV) {
            $this->SECRET_KEY = isset($_ENV[$secret_key]) ? $_ENV[$secret_key] : null;
        } else {
            $this->SECRET_KEY = $secret_key;
        }

        return $this;
    }


    /**
     * decode_data
     *
     * Decodifica un texto en base64 y lo desencripta usando AES-128-CBC.
     *
     * @param string $encodeText Texto cifrado en base64 que se desea descifrar.
     *
     * @return string response Devuelve el texto descifrado si el proceso es exitoso. Si el texto no está cifrado correctamente o no se puede desencriptar, retorna una cadena vacía.
     *
     * Consideraciones:
     * - Si el texto proporcionado es vacío, `null` o solo un espacio en blanco, retorna una cadena vacía.
     * - Si el texto no está correctamente cifrado o no se puede desencriptar, retorna el mismo texto o una cadena vacía.
     * - Se utiliza AES-128-CBC como método de desencriptación, con una clave secreta (`SECRET_KEY`) y un IV extraído de los primeros 16 bytes del texto cifrado.
     *
     * @throws Exception No lanza excepciones, pero retorna una cadena vacía en caso de error en la desencriptación.
     * @access public
     */
    public function decode_data($encodeText)
    {
        if (empty($encodeText) || $encodeText == Null || $encodeText == ' ') return '';
        $HASH = base64_decode($encodeText);
        //Validamos si el texto esta encriptado o no
        if ($HASH === false || strlen($encodeText) <= 32) {
            return $encodeText; // No se puede decodificar ya que no cumple y posiblemente no esté encriptado
        }
        // Extraer el IV (primeros 16 bytes)
        $iv = substr($HASH, 0, 16);
        // Extraer el texto cifrado (a partir del byte 17)
        $cipherText = substr($HASH, 16);
        // Desencriptar usando AES-128-CBC
        $decrypted = openssl_decrypt($cipherText, $this->METHOD, $this->SECRET_KEY, OPENSSL_RAW_DATA, $iv);
        // Si no se pudo desencriptar se retorna vacio
        if ($decrypted == false) {
            return '';
        }

        $decrypted = mb_convert_encoding($decrypted, 'UTF-8', 'UTF-8');
        return $decrypted;
    }

    /**
     * encode_data
     *
     * Cifra un texto utilizando el algoritmo AES-128-CBC y lo codifica en base64.
     *
     * @param string $text Texto en claro que se desea cifrar.
     *
     * @return string response Devuelve el texto cifrado en base64 si el proceso es exitoso. Si el texto ya estaba cifrado o hay un error en la encriptación, devuelve el mismo texto original.
     *
     *
     * Consideraciones:
     * - Si el texto proporcionado es vacío, `null` o solo un espacio en blanco, retorna una cadena vacía.
     * - Se genera un IV aleatorio de 16 bytes para la encriptación.
     * - Antes de cifrar, se intenta desencriptar el texto para verificar si ya estaba cifrado.
     * - Si el texto ya estaba cifrado correctamente, se retorna sin cambios.
     * - Si la encriptación falla, se retorna el texto sin modificar.
     * - Luego de cifrar, se concatena el IV con el texto cifrado y se codifica en base64.
     * - Se realiza una verificación posterior desencriptando el resultado para garantizar que se pueda recuperar el texto original.
     *
     * @throws Exception No lanza excepciones, pero retorna el texto original en caso de error en la encriptación.
     * @access public
     */

    public function encode_data($text)
    {
        if (empty($text) || $text == Null || $text == ' ') return '';
        $iv = hex2bin('c4d2e1f89a7b6503f2d4a9c8e6b1fd32'); // Generar IV aleatorio
        $Help = new Helpers();
        $verify = $Help->set_custom_secret_key($this->SECRET_KEY, FALSE)->decode_data($text); //verificamos si se puede desencriptar el dato
        if ($verify === false || $verify == $text || $verify === '') { // si no se puede desencriptar o si devuelve el text otra vez procedemos a encriptar
            $cipherText = openssl_encrypt($text, $this->METHOD, $this->SECRET_KEY, OPENSSL_RAW_DATA, $iv);
            if ($cipherText === false) {
                return $text; // Error en encriptación
            }
            $encriptFinal = base64_encode($iv . $cipherText);// Combinar IV + texto cifrado y codificar en Base64
            $verify_encript = $Help->set_custom_secret_key($this->SECRET_KEY, FALSE)->decode_data($encriptFinal);
            if ($verify_encript == $text) {
                return $encriptFinal;
            }
            return $text; // No se logró que la encriptación y la desencriptación tuvieran concordancia con el valor final
        } else {
            //Se logró desencriptar por tanto el texto ya esta encriptado
            return $text;
        }

    }
    /**
     * encode_data_with_key
     *
     * Cifra un texto utilizando el algoritmo AES-128-CBC y lo codifica en base64.
     *
     * @param string $text Texto en claro que se desea cifrar.
     * @param string $key Key para encriptar.
     *
     * @return string response Devuelve el texto cifrado en base64 si el proceso es exitoso. Si el texto ya estaba cifrado o hay un error en la encriptación, devuelve el mismo texto original.
     *
     *
     * Consideraciones:
     * - Si el texto proporcionado es vacío, `null` o solo un espacio en blanco, retorna una cadena vacía.
     * - Se genera un IV aleatorio de 16 bytes para la encriptación.
     * - Antes de cifrar, se intenta desencriptar el texto para verificar si ya estaba cifrado.
     * - Si el texto ya estaba cifrado correctamente, se retorna sin cambios.
     * - Si la encriptación falla, se retorna el texto sin modificar.
     * - Luego de cifrar, se concatena el IV con el texto cifrado y se codifica en base64.
     * - Se realiza una verificación posterior desencriptando el resultado para garantizar que se pueda recuperar el texto original.
     *
     * @throws Exception No lanza excepciones, pero retorna el texto original en caso de error en la encriptación.
     * @access public
     */

    public function encode_data_with_key($text,$key)
    {
        if (empty($text) || $text == Null || $text == ' ') return '';
        $iv = hex2bin('c4d2e1f89a7b6503f2d4a9c8e6b1fd32'); // Generar IV aleatorio
        $Help = new Helpers();
        $verify = $Help->set_custom_secret_key($key, FALSE)->decode_data($text); //verificamos si se puede desencriptar el dato
        if ($verify === false || $verify == $text || $verify === '') { // si no se puede desencriptar o si devuelve el text otra vez procedemos a encriptar
            $cipherText = openssl_encrypt($text, $this->METHOD, $key, OPENSSL_RAW_DATA, $iv);
            if ($cipherText === false) {
                return $text; // Error en encriptación
            }
            $encriptFinal = base64_encode($iv . $cipherText);// Combinar IV + texto cifrado y codificar en Base64
            $verify_encript = $Help->set_custom_secret_key($key, FALSE)->decode_data($encriptFinal);
            if ($verify_encript == $text) {
                return $encriptFinal;
            }
            return $text; // No se logró que la encriptación y la desencriptación tuvieran concordancia con el valor final
        } else {
            //Se logró desencriptar por tanto el texto ya esta encriptado
            return $text;
        }

    }

    /**
     * create_password_hash
     *
     * Genera un hash seguro de la contraseña utilizando el algoritmo especificado.
     *
     * @param string $password Contraseña en texto plano que se desea cifrar.
     *
     * @return string response Devuelve el hash de la contraseña si el proceso es exitoso. Si la contraseña está vacía, retorna una cadena vacía.
     *
     *
     * Consideraciones:
     * - Si la contraseña proporcionada es una cadena vacía, la función retorna una cadena vacía.
     * - Utiliza `password_hash()` con el algoritmo definido en `$this->PASSWORD_HASH`.
     * - Se establece el costo de procesamiento con `$this->COST`, lo que afecta la seguridad y el rendimiento.
     * - El hash generado es seguro para ser almacenado en bases de datos y comparado con `password_verify()`.
     *
     * @throws Exception No lanza excepciones, pero retorna una cadena vacía si el parámetro es incorrecto.
     * @access public
     */

    public function create_password_hash($password)
    {
        if (empty($password)) return '';
        $HASH = password_hash($password, $this->PASSWORD_HASH, ['cost' => $this->COST]);
        return $HASH;
    }

    /**
     * verify_password_hash
     *
     * Verifica si una contraseña en texto plano coincide con su hash almacenado.
     *
     * @param string $password Contraseña en texto plano ingresada por el usuario.
     * @param string $password_hash Hash de la contraseña almacenado previamente.
     *
     * @return bool response Devuelve `true` si la contraseña coincide con el hash, de lo contrario, retorna `false`.
     *
     *
     * Consideraciones:
     * - Si alguno de los parámetros es una cadena vacía, la función retorna `false`.
     * - Utiliza `password_verify()` para comparar la contraseña ingresada con el hash almacenado.
     * - Es útil para la autenticación de usuarios en sistemas de login.
     *
     * @throws Exception No lanza excepciones, pero retorna `false` si los parámetros no son válidos.
     * @access public
     */

    public function verify_password_hash($password, $password_hash)
    {
        if (empty($password) || empty($password_hash)) return false;
        return password_verify($password, $password_hash);
    }

    /**
     * is_valid_passowrd_hash
     *
     * Verifica si un hash de contraseña sigue siendo válido según la configuración actual de hashing.
     *
     * @param string $password Contraseña en texto plano que se desea validar.
     *
     * @return bool response Devuelve `true` si el hash sigue siendo válido y no necesita ser regenerado, de lo contrario, retorna `false`.
     *
     *
     * Consideraciones:
     * - Si el parámetro `$password` está vacío, la función retorna `false`.
     * - Usa `password_needs_rehash()` para determinar si el hash de la contraseña requiere una actualización debido a cambios en los parámetros de hashing.
     * - Es útil para actualizar contraseñas antiguas a un esquema de hash más seguro cuando sea necesario.
     *
     * @throws Exception No lanza excepciones, pero retorna `false` si el parámetro no es válido.
     * @access public
     */

    public function is_valid_passowrd_hash($password)
    {
        if (empty($password)) return false;
        return !password_needs_rehash($password, $this->PASSWORD_HASH, ['cost' => $this->COST]);
    }

    /**
     * set_custom_field
     *
     * Genera una consulta SQL para desencriptar un campo en una cláusula WHERE, dependiendo del tipo de dato.
     *
     * @param string $field Nombre del campo a desencriptar en la consulta SQL.
     *
     * @return string response Retorna una cadena SQL con la lógica de desencriptación si el campo corresponde a datos sensibles,
     *                         de lo contrario, devuelve el campo sin modificaciones.
     *
     * Consideraciones:
     * - Se aplica desencriptación solo a campos específicos como `.login`, `.email`, `.celular`, `.telefono`, `.nombre`, `.apellido`, `.cedula`, `.direccion`, `.sexo`.
     * - Se usa `AES_DECRYPT` junto con `BASE64_DECODE` para recuperar los datos originales.
     * - La clave secreta utilizada para desencriptar varía según el tipo de dato y se obtiene de variables de entorno (`$_ENV`).
     * - Si el campo no es de los especificados, la función lo devuelve sin modificaciones.
     *
     * @throws Exception No lanza excepciones, pero un uso incorrecto puede generar errores en la consulta SQL.
     * @access public
     */

    public function set_custom_field($field)
    {
        if (strpos($field, '.nombre')) {
            $SECRET = $_ENV['SECRET_PASSPHRASE_NAME'];
            $customField = "(CASE WHEN {$field} IS NOT NULL AND LENGTH({$field})>32 THEN CONVERT(AES_DECRYPT(SUBSTRING(FROM_BASE64({$field}), 17), '{$SECRET}',SUBSTRING(FROM_BASE64({$field}), 1, 16)) USING utf8mb4)ELSE {$field}  END)";
        } else if (strpos($field, '.apellido')) {
            $SECRET = $_ENV['SECRET_PASSPHRASE_LASTNAME'];
            $customField = "(CASE WHEN {$field} IS NOT NULL AND LENGTH({$field})>32 THEN CONVERT(AES_DECRYPT(SUBSTRING(FROM_BASE64({$field}), 17), '{$SECRET}',SUBSTRING(FROM_BASE64({$field}), 1, 16)) USING utf8mb4)ELSE {$field}  END)";
        } else if (strpos($field, '.direccion')) {
            $SECRET = $_ENV['SECRET_PASSPHRASE_ADDRESS'];
            $customField = "(CASE WHEN {$field} IS NOT NULL AND LENGTH({$field})>32 THEN CONVERT(AES_DECRYPT(SUBSTRING(FROM_BASE64({$field}), 17), '{$SECRET}',SUBSTRING(FROM_BASE64({$field}), 1, 16)) USING utf8mb4)ELSE {$field}  END)";
        } else if (strpos($field, '.sexo')) {
            $SECRET = $_ENV['SECRET_PASSPHRASE_GENDER'];
            $customField = "(CASE WHEN {$field} IS NOT NULL AND LENGTH({$field})>32 THEN CONVERT(AES_DECRYPT(SUBSTRING(FROM_BASE64({$field}), 17), '{$SECRET}',SUBSTRING(FROM_BASE64({$field}), 1, 16)) USING utf8mb4)ELSE {$field}  END)";
        } else {
            return $field;
        }
        return $customField;
    }

    /**
     * process_data
     *
     * Desencripta los valores de un array que provienen de una sentencia SQL, dependiendo de las claves que contengan información sensible.
     *
     * @param array $data Array de datos que pueden contener campos cifrados que requieren desencriptación.
     *
     * @return array response Un array con los datos procesados, donde los valores de las claves específicas han sido desencriptados.
     *
     * Descripción:
     * - La función recorre cada elemento del array y verifica si sus claves contienen términos asociados a información sensible como:
     *   - `.login`, `.email`, `.celular`, `.telefono`, `.nombre`, `.apellido`, `.cedula`, `.direccion`, `.sexo`.
     * - Para las claves que contienen estos términos, se llama al método `decode_data` para desencriptar el valor del campo.
     * - El campo es desencriptado utilizando la clave secreta correspondiente, obtenida de las variables de entorno.
     * - Si el valor del campo no corresponde a ninguno de los términos, se deja sin modificaciones.
     *
     *
     * Consideraciones:
     * - La función espera un array de datos como entrada y devuelve un array con los valores de las claves específicas desencriptados.
     * - Las claves que contienen términos específicos deben coincidir con los prefijos establecidos para cada tipo de dato (como `.login`, `.email`, etc.).
     *
     * @throws Exception Lanza una excepción si ocurre un error al intentar desencriptar los datos, como una clave secreta inválida.
     * @access public
     */
    public function process_data($data)
    {

        if (!is_array($data)) {
            return $data;
        }
        $newData = array_map(function ($item) {
            // Obtiene todas las claves del elemento actual
            $keys = array_keys($item);

            foreach ($keys as $key) {
                // Verifica si la clave contiene los términos esperados y aplica decodificación
                if (strpos($key, '.login') !== false || strpos($key, '.email') !== false || strpos($key, '.Login') !== false || strpos($key, '.Email') !== false || strpos($key, 'puntoventa') !== false || strpos($key, 'puntologin') !== false) {
                    $item[$key] = $this->set_custom_secret_key('SECRET_PASSPHRASE_LOGIN', true)->decode_data($item[$key]);
                } elseif (strpos($key, '.celular') !== false || strpos($key, '.telefono') !== false || strpos($key, '.Celular') !== false || strpos($key, '.Telefono') !== false) {
                    $item[$key] = $this->set_custom_secret_key('SECRET_PASSPHRASE_PHONE', true)->decode_data($item[$key]);
                } elseif (strpos($key, '.nombre') !== false || strpos($key, '.Nombre') !== false) {
                    $item[$key] = $this->set_custom_secret_key('SECRET_PASSPHRASE_NAME', true)->decode_data($item[$key]);
                } elseif (strpos($key, '.apellido') !== false || strpos($key, '.Apellido') !== false) {
                    $item[$key] = $this->set_custom_secret_key('SECRET_PASSPHRASE_LASTNAME', true)->decode_data($item[$key]);
                } elseif (strpos($key, '.cedula') !== false || strpos($key, '.Cedula') !== false) {
                    $item[$key] = $this->set_custom_secret_key('SECRET_PASSPHRASE_DOCUMENT', true)->decode_data($item[$key]);
                } elseif (strpos($key, '.direccion') !== false || strpos($key, '.Direccion') !== false) {
                    $item[$key] = $this->set_custom_secret_key('SECRET_PASSPHRASE_ADDRESS', true)->decode_data($item[$key]);
                } elseif (strpos($key, '.sexo') !== false || strpos($key, '.Sexo') !== false) {
                    $item[$key] = $this->set_custom_secret_key('SECRET_PASSPHRASE_GENDER', true)->decode_data($item[$key]);
                }
            }

            return $item;
        }, $data);


        return $newData;
    }

}

?>
