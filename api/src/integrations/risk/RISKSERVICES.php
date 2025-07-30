<?php

/**
 * Script para analizar código PHP y generar documentación PHPDoc automáticamente.
 *
 * @category Red
 * @package  API
 * @author   Desconocido
 * @version  1.0.0
 * @since    2025-04-21
 */

namespace Backend\integrations\risk;

use Backend\dto\BonoInterno;
use Backend\mysql\BonoInternoMySqlDAO;
use Backend\dto\Exception;
use Backend\dto\ConfigurationEnvironment;
use CurlWrapper;

/**
 * Clase que proporciona servicios relacionados con el análisis de riesgos.
 */
class RISKSERVICES
{
    /**
     * Token para autenticación.
     *
     * @var string
     */
    private $token = "";

    /**
     * URL del endpoint.
     *
     * @var string
     */
    private $url = "";

    /**
     * Token de desarrollo.
     *
     * @var string
     */
    private $tokenDEV = "FB27A72078888EF6ECBVV76F5CFC45C789338C4A63781EF681965YHGGTFR53F4";

    /**
     * Token de producción.
     *
     * @var string
     */
    private $tokenPROD = "FB27A72078888EF6ECBVV76F5CFC45C789338C4A63781EF681965YHGGTFR53F4";

    /**
     * URL del endpoint en entorno de desarrollo.
     *
     * @var string
     */
    private $urlDEV = 'https://apirisk.bpo.lat/api.php';

    /**
     * URL del endpoint en entorno de producción.
     *
     * @var string
     */
    private $urlPROD = 'https://apirisk.bpo.lat/api.php';

    /**
     * Constructor que configura los valores de token y URL según el entorno.
     */
    public function __construct()
    {
        $ConfigurationEnvironment = new ConfigurationEnvironment();
        if ($ConfigurationEnvironment->isDevelopment()) {
            // Asignar valores para el entorno de desarrollo
            $this->token = $this->tokenDEV;
            $this->url = $this->urlDEV;
        } else {
            $this->token = $this->tokenPROD;
            $this->url = $this->urlPROD;
        }
    }

    /**
     * Envía datos al endpoint configurado.
     *
     * @param array $params Parámetros a enviar en la solicitud.
     *
     * @return array Respuesta del endpoint como un array asociativo.
     */
    public function sendData($params)
    {
        // Inicializar la clase CurlWrapper
        $curl = new CurlWrapper($this->url);

        // Configurar opciones
        $curl->setOptionsArray(
            [
                CURLOPT_POST => true, // Solicitud POST
                CURLOPT_POSTFIELDS => http_build_query($params), // Enviar el array de parámetros completo
                CURLOPT_HTTPHEADER => [
                    'Authorization: Bearer ' . $this->token,
                    'Accept: application/json'
                ],
                CURLOPT_TIMEOUT => 10,
                CURLOPT_CONNECTTIMEOUT => 10,
                CURLOPT_RETURNTRANSFER => true,
            ]
        );

        $response = $curl->execute();

        return json_decode($response, true); // Retornar como array asociativo
    }

    /**
     * Obtiene datos de riesgo para un usuario específico.
     *
     * @param int $usuarioId ID del usuario para el cual se obtendrán los datos.
     *
     * @return array Datos de riesgo del usuario.
     */
    public function riskData($usuarioId)
    {
        $BonoInternoMySqlDao = new BonoInternoMySqlDAO();
        $transaction = $BonoInternoMySqlDao->getTransaction();

        $sql = "SELECT
            m.nombre AS PARTNER,
            p.pais_nom AS PAIS,
            u.usuario_id AS userid,
            ROUND((usr.saldo_recarga), 2) AS deposits,
            ROUND((usr.saldo_notaret_pagadas), 2) AS withdrawals,
            ROUND((usr.saldo_apuestas + usr.saldo_apuestas_casino + usr.saldo_apuestas_casino_vivo - usr.saldo_premios -
                     usr.saldo_premios_casino - usr.saldo_bono - usr.saldo_bono_free_ganado -
                     usr.saldo_bono_casino_free_ganado - usr.saldo_bono_virtual - usr.saldo_bono_virtual_free_ganado) /
                    (usr.saldo_recarga),
                    2) AS profit,
            ROUND((usr.saldo_apuestas + usr.saldo_apuestas_casino + usr.saldo_apuestas_casino_vivo) / (usr.saldo_recarga),
                    2) AS wager,
            ROUND((usr.saldo_bono + usr.saldo_bono_free_ganado + usr.saldo_bono_casino_free_ganado + usr.saldo_bono_virtual +
                     usr.saldo_bono_virtual_free_ganado) /
                    (usr.saldo_apuestas + usr.saldo_apuestas_casino + usr.saldo_apuestas_casino_vivo), 2) AS bonus,
            ROUND((usr.saldo_notaret_pagadas) / (usr.saldo_recarga), 2) AS withdraw_similarity,
            ROUND((usr.saldo_apuestas - usr.saldo_premios - usr.saldo_bono) / (usr.saldo_apuestas), 2) AS sports_margin,
            ROUND((usr.saldo_apuestas_casino + usr.saldo_apuestas_casino_vivo -
                     usr.saldo_premios_casino - usr.saldo_bono_free_ganado - usr.saldo_bono_casino_free_ganado -
                     usr.saldo_bono_virtual - usr.saldo_bono_virtual_free_ganado) / (usr.saldo_recarga), 2) AS casino_margin,
            DATEDIFF(NOW(), u.fecha_crea) AS days_registration
        FROM
            usuario_saldoresumen usr
        JOIN usuario u ON
            usr.usuario_id = u.usuario_id
        JOIN mandante m ON
            u.mandante = m.mandante
        JOIN pais p ON
            u.pais_id = p.pais_id
            WHERE u.usuario_id = $usuarioId";

        $BonoInterno = new BonoInterno();
        $datos = $BonoInterno->execQuery($transaction, $sql);

        $result = [];

        foreach ($datos as $item) {
            $result[] = [
                'partner' => $item->{'m.PARTNER'},
                'pais' => $item->{'p.PAIS'},
                'user_id' => $item->{'u.userid'},
                'deposits' => $item->{'.deposits'},
                'withdrawals' => $item->{'.withdrawals'},
                'profit' => $item->{'.profit'},
                'wager' => $item->{'.wager'},
                'bonus' => $item->{'.bonus'},
                'withdraw_similarity' => $item->{'.withdraw_similarity'},
                'sports_margin' => $item->{'.sports_margin'},
                'casino_margin' => $item->{'.casino_margin'},
                'days_registration' => $item->{'.days_registration'},
            ];
        }

        return $result;
    }
}
