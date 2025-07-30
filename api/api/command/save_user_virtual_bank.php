<?php

/**
 * Endpoint para guardar información de bancos digitales asociados a un usuario
 * 
 * Este recurso permite registrar cuentas de bancos digitales para un usuario específico,
 * aplicando validaciones de formato y límites según el tipo de banco seleccionado.
 * 
 * @author David Alvarez <juan.alvarez@virtualsoft.tech>
 * @since 2025-06-09
 * @param object $json Objeto global JSON con los parámetros de entrada
 * @param string $json->params->account Cuenta equivalente a un email o un numero de celular
 * @param int $json->params->user_id ID del usuario propietario de la cuenta
 * @param object $json->params->bank_id ID del banco seleccionado
 * 
 * Validaciones aplicadas:
 * - Zelle: Máximo 2 cuentas por usuario, formato debe ser email válido o número de 10 dígitos
 * - Paypal: Máximo 1 cuenta por usuario, formato debe ser email válido
 * 
 * @return array Respuesta con código 0 y el ID del registro creado
 * @throws Exception Si se excede el límite de cuentas o el formato es inválido
 */

 use Backend\dto\Banco;
 use Backend\dto\Usuario;
 use Backend\dto\UsuarioBanco;
 use Backend\mysql\UsuarioBancoMySqlDAO;

try {

    /* Se asigna un usuario mandante y se inicializan variables de restricciones. */
    $UsuarioMandante = $UsuarioMandanteSite;
    $Usuario = new Usuario($UsuarioMandante->getUsuarioMandante());

    /* Extrae parámetros de un objeto JSON relacionado con una cuenta bancaria y usuario. */
    $account = $json->params->account;
    $bank = $json->params->bank_id;

    /* Se crean reglas para filtrar usuarios en una base de datos. */
    $rules = [];
    array_push($rules, array("field" => "usuario_banco.usuario_id", "data" => $UsuarioMandante->getUsuarioMandante(), "op" => "eq"));
    array_push($rules, array("field" => "usuario_banco.estado", "data" => "A", "op" => "eq"));
    array_push($rules, array("field" => "usuario_banco.banco_id", "data" => $bank, "op" => "eq"));

    $rules = array("rules" => $rules, "groupOp" => "AND");

    $rules = json_encode($rules);

    $UsuarioBanco = new UsuarioBanco();

    $accounts = $UsuarioBanco->getUsuarioBancosCustom(" usuario_banco.* ", "usuario_banco.usubanco_id", "asc", 0, 100, $rules, true);

    $accounts = json_decode((string) $accounts);
    $banco = new Banco($bank);

    $maxAccounts = 0;

    if ($banco->descripcion === "Zelle") $maxAccounts = 2;

    if ($banco->descripcion === "Paypal") $maxAccounts = 1;

    // Verificar límite de cuentas
    if (oldCount($accounts->{'data'}) >= $maxAccounts) {
        throw new Exception('El maximo de cuentas ya esta registrado, si desea ingresar otra elimine otra.');
    }

    if (oldCount($accounts->{'data'}) > 0) {
        foreach ($accounts->{'data'} as $accountData) {
            if ($accountData->{'usuario_banco.cuenta'} === $account) {
                throw new Exception("La cuenta $account ya se encuentra registrada");
            }
        }
    }

    if ($banco->descripcion === "Zelle") {
        // Para Zelle: debe ser email o número de celular de 10 dígitos
        $isEmail = filter_var($account, FILTER_VALIDATE_EMAIL);
        $isPhone = preg_match('/^\d{10}$/', $account);

        if (!$isEmail && !$isPhone) {
            throw new Exception("Para Zelle, la cuenta debe ser un correo electrónico válido o un número de celular de 10 dígitos");
        }
    }

    if ($banco->descripcion === "Paypal") {
        // Para Paypal: debe ser un email válido
        if (!filter_var($account, FILTER_VALIDATE_EMAIL)) {
            throw new Exception("Para Paypal, la cuenta debe ser un correo electrónico válido");
        }
    }

    /* Creación y configuración de un objeto UsuarioBanco con datos específicos del usuario y cuenta. */
    $UsuarioBanco = new UsuarioBanco();
    $UsuarioBanco->setUsuarioId($UsuarioMandante->getUsuarioMandante());
    $UsuarioBanco->setBancoId($bank);
    $UsuarioBanco->setCuenta($account);
    $UsuarioBanco->setTipoCuenta("Digital");
    $UsuarioBanco->setTipoCliente("PERSONA");
    $UsuarioBanco->setUsucreaId($UsuarioMandante->getUsuarioMandante());
    $UsuarioBanco->setUsumodifId($UsuarioMandante->getUsuarioMandante());
    $UsuarioBanco->setEstado('A');
    $UsuarioBanco->setCodigo("0");
    $UsuarioBanco->setToken('0');
    $UsuarioBanco->setProductoId('0');

    $UsuarioBancoMySqlDAO = new UsuarioBancoMySqlDAO();

    /* Insertar un usuario en la base de datos y obtener la transacción correspondiente. */
    $id = $UsuarioBancoMySqlDAO->insert($UsuarioBanco);
    $UsuarioBancoMySqlDAO->getTransaction()->commit();

    $response = array(
        "code" => 0,
        "data" => array("id" => $id)
    );
} catch (\Throwable $th) {
    throw $th;
}
