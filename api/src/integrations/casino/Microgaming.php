<?php

/**
 * Clase para integrar y gestionar operaciones con el proveedor de casino Microgaming.
 *
 * @category   Red
 * @package    API
 * @subpackage Integrations
 * @author     Desconocido
 * @version    1.0.0
 * @since      2025-04-27
 */

namespace Backend\Integrations\payment;

use Backend\dto\Ciudad;
use Backend\dto\Pais;
use Backend\dto\PaisMandante;
use Backend\dto\Proveedor;
use Backend\dto\Usuario;
use Backend\dto\Producto;
use Backend\dto\Registro;
use Backend\dto\Subproveedor;
use Backend\dto\TransprodLog;
use Backend\dto\TransaccionProducto;
use Backend\dto\TransproductoDetalle;
use Backend\dto\SubproveedorMandante;
use Backend\dto\UsuarioTarjetacredito;
use Backend\mysql\TransprodLogMySqlDAO;
use Backend\dto\ConfigurationEnvironment;
use Backend\mysql\TransaccionProductoMySqlDAO;
use Backend\mysql\TransproductoDetalleMySqlDAO;
use Backend\dto\UsuarioToken;
use Backend\dto\Departamento;
use Backend\mysql\UsuarioTarjetacreditoMySqlDAO;

use Exception;

/**
 * Clase que proporciona servicios para la integración con el proveedor INSWITCH.
 * Contiene métodos para manejar transacciones, generación de tokens y conexiones HTTP.
 */
class INSWITCHSERVICES
{
    /**
     * Clave API para Perú.
     *
     * @var string
     */
    private $ApiKey = "";

    /**
     * Contraseña para Perú.
     *
     * @var string
     */
    private $Password = "";

    /**
     * Nombre de usuario para Perú.
     *
     * @var string
     */
    private $Username = "";

    /**
     * Clave API para Guatemala.
     *
     * @var string
     */
    private $ApiKeyGT = "";

    /**
     * Contraseña para Guatemala.
     *
     * @var string
     */
    private $PasswordGT = "";

    /**
     * Nombre de usuario para Guatemala.
     *
     * @var string
     */
    private $UsernameGT = "";

    /**
     * Clave API para El Salvador.
     *
     * @var string
     */
    private $ApiKeySV = "";

    /**
     * Contraseña para El Salvador.
     *
     * @var string
     */
    private $PasswordSV = "";

    /**
     * Nombre de usuario para El Salvador.
     *
     * @var string
     */
    private $UsernameSV = "";

    /**
     * Clave API para Ecuador.
     *
     * @var string
     */
    private $ApiKeyEC = "";

    /**
     * Contraseña para Ecuador.
     *
     * @var string
     */
    private $PasswordEC = "";

    /**
     * Nombre de usuario para Ecuador.
     *
     * @var string
     */
    private $UsernameEC = "";

    /**
     * Clave API para Costa Rica.
     *
     * @var string
     */
    private $ApiKeyCR = "";

    /**
     * Contraseña para Costa Rica.
     *
     * @var string
     */
    private $PasswordCR = "";

    /**
     * Nombre de usuario para Costa Rica.
     *
     * @var string
     */
    private $UsernameCR = "";

    /**
     * Clave API para Ecuabet.
     *
     * @var string
     */
    private $ApiKeyECB = "";

    /**
     * Contraseña para Ecuabet.
     *
     * @var string
     */
    private $PasswordECB = "";

    /**
     * Nombre de usuario para Ecuabet.
     *
     * @var string
     */
    private $UsernameECB = "";

    /**
     * Clave API para Brasil.
     *
     * @var string
     */
    private $ApiKeyDBR = "";

    /**
     * Contraseña para Brasil.
     *
     * @var string
     */
    private $PasswordDBR = "";

    /**
     * Nombre de usuario para Brasil.
     *
     * @var string
     */
    private $UsernameDBR = "";

    /**
     * Clave API para Honduras.
     *
     * @var string
     */
    private $ApiKeyHND = "";

    /**
     * Contraseña para Honduras.
     *
     * @var string
     */
    private $PasswordHND = "";

    /**
     * Nombre de usuario para Honduras.
     *
     * @var string
     */
    private $UsernameHND = "";

    /**
     * Clave API para Nicaragua.
     *
     * @var string
     */
    private $ApiKeyNI = "";

    /**
     * Contraseña para Nicaragua.
     *
     * @var string
     */
    private $PasswordNI = "";

    /**
     * Nombre de usuario para Nicaragua.
     *
     * @var string
     */
    private $UsernameNI = "";

    /**
     * Clave API para el entorno de desarrollo.
     *
     * @var string
     */
    private $ApiKeyDEV = "eyJ4NXQiOiJNV1ExTWpBMlpESm1PV1U1WXpjNFpUazFZelk1T1dVeU56SmtaV1l5TWpZNE5qa3pZVFkyWXpjNE9EY3lZMlprWmpGaVpHUmhNMkkyTUdFeU5qRmpaZyIsImtpZCI6Im9rZGF3cyIsInR5cCI6IkpXVCIsImFsZyI6IlJTMjU2In0=.eyJzdWIiOiJkb3JhZG9iZXRkZXZwZUBjYXJib24uc3VwZXIiLCJhcHBsaWNhdGlvbiI6eyJvd25lciI6ImRvcmFkb2JldGRldnBlIiwidGllclF1b3RhVHlwZSI6bnVsbCwidGllciI6IlVubGltaXRlZCIsIm5hbWUiOiJEZWZhdWx0QXBwbGljYXRpb24iLCJpZCI6MTI3LCJ1dWlkIjoiOTBkZGNlODItZDEyYS00Yzk5LTg4ZTctYzgzN2JlMDAzM2VlIn0sImlzcyI6Imh0dHBzOlwvXC9hcGltLW1hbmFnZW1lbnQuYXBwcy5pbnMuaW5zd2h1Yi5jb206NDQzXC9vYXV0aDJcL3Rva2VuIiwidGllckluZm8iOnsiQnJvbnplIjp7InRpZXJRdW90YVR5cGUiOiJyZXF1ZXN0Q291bnQiLCJncmFwaFFMTWF4Q29tcGxleGl0eSI6MCwiZ3JhcGhRTE1heERlcHRoIjowLCJzdG9wT25RdW90YVJlYWNoIjp0cnVlLCJzcGlrZUFycmVzdExpbWl0IjowLCJzcGlrZUFycmVzdFVuaXQiOm51bGx9LCJHb2xkIjp7InRpZXJRdW90YVR5cGUiOiJyZXF1ZXN0Q291bnQiLCJncmFwaFFMTWF4Q29tcGxleGl0eSI6MCwiZ3JhcGhRTE1heERlcHRoIjowLCJzdG9wT25RdW90YVJlYWNoIjp0cnVlLCJzcGlrZUFycmVzdExpbWl0IjowLCJzcGlrZUFycmVzdFVuaXQiOm51bGx9LCJVbmxpbWl0ZWQiOnsidGllclF1b3RhVHlwZSI6InJlcXVlc3RDb3VudCIsImdyYXBoUUxNYXhDb21wbGV4aXR5IjowLCJncmFwaFFMTWF4RGVwdGgiOjAsInN0b3BPblF1b3RhUmVhY2giOnRydWUsInNwaWtlQXJyZXN0TGltaXQiOjAsInNwaWtlQXJyZXN0VW5pdCI6bnVsbH19LCJrZXl0eXBlIjoiUFJPRFVDVElPTiIsInBlcm1pdHRlZFJlZmVyZXIiOiIiLCJzdWJzY3JpYmVkQVBJcyI6W3sic3Vic2NyaWJlclRlbmFudERvbWFpbiI6ImNhcmJvbi5zdXBlciIsIm5hbWUiOiJBdXRoLVNlcnZpY2UiLCJjb250ZXh0IjoiXC9hdXRoLXNlcnZpY2VcLzEuMCIsInB1Ymxpc2hlciI6ImFkbWluIiwidmVyc2lvbiI6IjEuMCIsInN1YnNjcmlwdGlvblRpZXIiOiJVbmxpbWl0ZWQifSx7InN1YnNjcmliZXJUZW5hbnREb21haW4iOiJjYXJib24uc3VwZXIiLCJuYW1lIjoiVHJhbnNhY3Rpb25zIiwiY29udGV4dCI6IlwvdHJhbnNhY3Rpb25zXC8xLjAiLCJwdWJsaXNoZXIiOiJhZG1pbiIsInZlcnNpb24iOiIxLjAiLCJzdWJzY3JpcHRpb25UaWVyIjoiVW5saW1pdGVkIn0seyJzdWJzY3JpYmVyVGVuYW50RG9tYWluIjoiY2FyYm9uLnN1cGVyIiwibmFtZSI6Ikhvc3RlZENoZWNrb3V0IiwiY29udGV4dCI6IlwvaG9zdGVkY2hlY2tvdXRcLzEuMCIsInB1Ymxpc2hlciI6ImFkbWluIiwidmVyc2lvbiI6IjEuMCIsInN1YnNjcmlwdGlvblRpZXIiOiJVbmxpbWl0ZWQifSx7InN1YnNjcmliZXJUZW5hbnREb21haW4iOiJjYXJib24uc3VwZXIiLCJuYW1lIjoiV2FsbGV0cyIsImNvbnRleHQiOiJcL3dhbGxldHNcLzEuMCIsInB1Ymxpc2hlciI6InB1Ymxpc2hlci51c2VyIiwidmVyc2lvbiI6IjEuMCIsInN1YnNjcmlwdGlvblRpZXIiOiJVbmxpbWl0ZWQifSx7InN1YnNjcmliZXJUZW5hbnREb21haW4iOiJjYXJib24uc3VwZXIiLCJuYW1lIjoiRlgiLCJjb250ZXh0IjoiXC9meFwvMS4wIiwicHVibGlzaGVyIjoicHVibGlzaGVyLnVzZXIiLCJ2ZXJzaW9uIjoiMS4wIiwic3Vic2NyaXB0aW9uVGllciI6IlVubGltaXRlZCJ9LHsic3Vic2NyaWJlclRlbmFudERvbWFpbiI6ImNhcmJib24uc3VwZXIiLCJuYW1lIjoiU2VydmljZVByb3ZpZGVycyIsImNvbnRleHQiOiJcL3NlcnZpY2Vwcm92aWRlcnNcLzMuMCIsInB1Ymxpc2hlciI6InB1Ymxpc2hlci51c2VyIiwidmVyc2lvbiI6IjMuMCIsInN1YnNjcmlwdGlvblRpZXIiOiJVbmxpbWl0ZWQifSx7InN1YnNjcmliZXJUZW5hbnREb21haW4iOiJjYXJib24uc3VwZXIiLCJuYW1lIjoiT1RQLU1hbmFnZXIiLCJjb250ZXh0IjoiXC9vdHAtbWFuYWdlclwvMS4wIiwicHVibGlzaGVyIjoiYWRtaW4iLCJ2ZXJzaW9uIjoiMS4wIiwic3Vic2NyaXB0aW9uVGllciI6IkJyb256ZSJ9LHsic3Vic2NyaWJlclRlbmFudERvbWFpbiI6ImNhcmJib24uc3VwZXIiLCJuYW1lIjoiRW50aXRpZXMiLCJjb250ZXh0IjoiXC9lbnRpdGllc1wvMS4yIiwicHVibGlzaGVyIjoiYWRtaW4iLCJ2ZXJzaW9uIjoiMS4yIiwic3Vic2NyaXB0aW9uVGllciI6IkJyb256ZSJ9LHsic3Vic2NyaWJlclRlbmFudERvbWFpbiI6ImNhcmJvbi5zdXBlciIsIm5hbWUiOiJLWUMiLCJjb250ZXh0IjoiXC9reWNcLzEuMCIsInB1Ymxpc2hlciI6InB1Ymxpc2hlci51c2VyIiwidmVyc2lvbiI6IjEuMCIsInN1YnNjcmlwdGlvblRpZXIiOiJVbmxpbWl0ZWQifSx7InN1YnNjcmliZXJUZW5hbnREb21haW4iOiJjYXJib24uc3VwZXIiLCJuYW1lIjoiTm90aWZpY2F0aW9uRW5naW5lIiwiY29udGV4dCI6Ilwvbm90aWZpY2F0aW9uZW5naW5lXC8xLjAiLCJwdWJsaXNoZXIiOiJhZG1pbiIsInZlcnNpb24iOiIxLjAiLCJzdWJzY3JpcHRpb25UaWVyIjoiR29sZCJ9LHsic3Vic2NyaWJlclRlbmFudERvbWFpbiI6ImNhcmJvbi5zdXBlciIsIm5hbWUiOiJQYXltZW50LU1ldGhvZHMiLCJjb250ZXh0IjoiXC9wYXltZW50LW1ldGhvZHNcLzEuMCIsInB1Ymxpc2hlciI6ImFkbWluIiwidmVyc2lvbiI6IjEuMCIsInN1YnNjcmlwdGlvblRpZXIiOiJCcm9uemUifV0sInBlcm1pdHRlZElQIjoiIiwiaWF0IjoxNjUwMzIxNDM3LCJqdGkiOiI3MjNmMjM2MC02MDg5LTQ5MDEtOGIzMC0yY2QxNzA3MmNhMTgifQ==";

    /**
     * Contraseña para el entorno de desarrollo.
     *
     * @var string
     */
    private $PasswordDEV = "Inswitch@2022";

    /**
     * Nombre de usuario para el entorno de desarrollo.
     *
     * @var string
     */
    private $UsernameDEV = "DoradoBetdevPE";

    /**
     * Clave API para el entorno de producción en Ecuador.
     *
     * @var string
     */
    private $ApiKeyPROEC = "eyJ4NXQiOiJNV1ExTWpBMlpESm1PV1U1WXpjNFpUazFZelk1T1dVeU56SmtaV1l5TWpZNE5qa3pZVFkyWXpjNE9EY3lZMlprWmpGaVpHUmhNMkkyTUdFeU5qRmpaZyIsImtpZCI6Im9rZGF3cyIsInR5cCI6IkpXVCIsImFsZyI6IlJTMjU2In0=.eyJzdWIiOiJkb3JhZG9iZXRkZXZlY0BjYXJib24uc3VwZXIiLCJhcHBsaWNhdGlvbiI6eyJvd25lciI6ImRvcmFkb2JldGRldmVjIiwidGllclF1b3RhVHlwZSI6bnVsbCwidGllciI6IlVubGltaXRlZCIsIm5hbWUiOiJEZWZhdWx0QXBwbGljYXRpb24iLCJpZCI6MTQwLCJ1dWlkIjoiMjAwOGQ2NmQtODY2YS00ZWY3LTgxYTEtODA1NDVkMjc4YTlhIn0sImlzcyI6Imh0dHBzOlwvXC9hcGltLW1hbmFnZW1lbnQuYXBwcy5pbnMuaW5zd2h1Yi5jb206NDQzXC9vYXV0aDJcL3Rva2VuIiwidGllckluZm8iOnsiQnJvbnplIjp7InRpZXJRdW90YVR5cGUiOiJyZXF1ZXN0Q291bnQiLCJncmFwaFFMTWF4Q29tcGxleGl0eSI6MCwiZ3JhcGhRTE1heERlcHRoIjowLCJzdG9wT25RdW90YVJlYWNoIjp0cnVlLCJzcGlrZUFycmVzdExpbWl0IjowLCJzcGlrZUFycmVzdFVuaXQiOm51bGx9LCJVbmxpbWl0ZWQiOnsidGllclF1b3RhVHlwZSI6InJlcXVlc3RDb3VudCIsImdyYXBoUUxNYXhDb21wbGV4aXR5IjowLCJncmFwaFFMTWF4RGVwdGgiOjAsInN0b3BPblF1b3RhUmVhY2giOnRydWUsInNwaWtlQXJyZXN0TGltaXQiOjAsInNwaWtlQXJyZXN0VW5pdCI6bnVsbH19LCJrZXl0eXBlIjoiUFJPRFVDVElPTiIsInBlcm1pdHRlZFJlZmVyZXIiOiIiLCJzdWJzY3JpYmVkQVBJcyI6W3sic3Vic2NyaWJlclRlbmFudERvbWFpbiI6ImNhcmJvbi5zdXBlciIsIm5hbWUiOiJBdXRoLVNlcnZpY2UiLCJjb250ZXh0IjoiXC9hdXRoLXNlcnZpY2VcLzEuMCIsInB1Ymxpc2hlciI6ImFkbWluIiwidmVyc2lvbiI6IjEuMCIsInN1YnNjcmlwdGlvblRpZXIiOiJCcm9uemUifSx7InN1YnNjcmliZXJUZW5hbnREb21haW4iOiJjYXJib24uc3VwZXIiLCJuYW1lIjoiRW50aXRpZXMiLCJjb250ZXh0IjoiXC9lbnRpdGllc1wvMS4yIiwicHVibGlzaGVyIjoiYWRtaW4iLCJ2ZXJzaW9uIjoiMS4yIiwic3Vic2NyaXB0aW9uVGllciI6IkJyb256ZSJ9LHsic3Vic2NyaWJlclRlbmFudERvbWFpbiI6ImNhcmJvbi5zdXBlciIsIm5hbWUiOiJIb3N0ZWRDaGVja291dCIsImNvbnRleHQiOiJcL2hvc3RlZGNoZWNrb3V0XC8xLjAiLCJwdWJsaXNoZXIiOiJhZG1pbiIsInZlcnNpb24iOiIxLjAiLCJzdWJzY3JpcHRpb25UaWVyIjoiVW5saW1pdGVkIn0seyJzdWJzY3JpYmVyVGVuYW50RG9tYWluIjoiY2FyYm9uLnN1cGVyIiwibmFtZSI6Ik5vdGlmaWNhdGlvbkVuZ2luZSIsImNvbnRleHQiOiJcL25vdGlmaWNhdGlvbmVuZ2luZVwvMS4wIiwicHVibGlzaGVyIjoiYWRtaW4iLCJ2ZXJzaW9uIjoiMS4wIiwic3Vic2NyaXB0aW9uVGllciI6IkJyb256ZSJ9LHsic3Vic2NyaWJlclRlbmFudERvbWFpbiI6ImNhcmJvbi5zdXBlciIsIm5hbWUiOiJPVFAtTWFuYWdlciIsImNvbnRleHQiOiJcL290cC1tYW5hZ2VyXC8xLjAiLCJwdWJsaXNoZXIiOiJhZG1pbiIsInZlcnNpb24iOiIxLjAiLCJzdWJzY3JpcHRpb25UaWVyIjoiQnJvbnplIn0seyJzdWJzY3JpYmVyVGVuYW50RG9tYWluIjoiY2FyYm9uLnN1cGVyIiwibmFtZSI6IlBheW1lbnQtTWV0aG9kcyIsImNvbnRleHQiOiJcL3BheW1lbnQtbWV0aG9kc1wvMS4wIiwicHVibGlzaGVyIjoiYWRtaW4iLCJ2ZXJzaW9uIjoiMS4wIiwic3Vic2NyaXB0aW9uVGllciI6IkJyb256ZSJ9LHsic3Vic2NyaWJlclRlbmFudERvbWFpbiI6ImNhcmJvbi5zdXBlciIsIm5hbWUiOiJUcmFuc2FjdGlvbnMiLCJjb250ZXh0IjoiXC90cmFuc2FjdGlvbnNcLzEuMCIsInB1Ymxpc2hlciI6ImFkbWluIiwidmVyc2lvbiI6IjEuMCIsInN1YnNjcmlwdGlvblRpZXIiOiJCcm9uemUifSx7InN1YnNjcmliZXJUZW5hbnREb21haW4iOiJjYXJib24uc3VwZXIiLCJuYW1lIjoiV2FsbGV0cyIsImNvbnRleHQiOiJcL3dhbGxldHNcLzEuMCIsInB1Ymxpc2hlciI6InB1Ymxpc2hlci51c2VyIiwidmVyc2lvbiI6IjEuMCIsInN1YnNjcmlwdGlvblRpZXIiOiJCcm9uemUifV0sInBlcm1pdHRlZElQIjoiIiwiaWF0IjoxNjU0NjM4OTYyLCJqdGkiOiIxNzU5ZTJmNC1jMTQ1LTRmMTQtYWFmZi1mMDMxZDk3ZDFkNGYifQ==.gHEF5njrxG6THc_BHcvSPKSc03VpoLPSR-OM56cXf9ZNbqRTO4Y3sKaBYd47-cgabEnKJK6wJ9p873wAOxPOV-OfGOaMbNqLzku2riLXXt80HLBKYyeNOJ-BTuv3YZ3xtRtX4i1vIYV4tNHboQXTOTlX_CPdDcM4vKsNqhUzBXl1wEwD7rSVI5k2F3BzF8GjQwvHYPoIEPQ19vEkwKR5-ZXVue3laqJYAt5sHKShCpmzHVdxHTNaSdOKs4Fy3nErvxlzEaDqiFvGH8ocsTKsVF3EIgrxm5rnhAcpzrTmEbGVawtDSmumbStBbj4a0SUbDuicTeEZvArqMY0UybXrRw==";

    /**
     * Contraseña para el entorno de producción en Ecuador.
     *
     * @var string
     */
    private $PasswordPROEC = "Inswitch@2022";

    /**
     * Nombre de usuario para el entorno de producción en Ecuador.
     *
     * @var string
     */
    private $UsernamePROEC = "DoradoBetdevEC";

    /**
     * Clave API para el entorno de producción en Costa Rica.
     *
     * @var string
     */
    private $ApiKeyPROCR = "eyJ4NXQiOiJNV1ExTWpBMlpESm1PV1U1WXpjNFpUazFZelk1T1dVeU56SmtaV1l5TWpZNE5qa3pZVFkyWXpjNE9EY3lZMlprWmpGaVpHUmhNMkkyTUdFeU5qRmpaZyIsImtpZCI6Im9rZGF3cyIsInR5cCI6IkpXVCIsImFsZyI6IlJTMjU2In0=.eyJzdWIiOiJkb3JhZG9iZXRkZXZjckBjYXJib24uc3VwZXIiLCJhcHBsaWNhdGlvbiI6eyJvd25lciI6ImRvcmFkb2JldGRldmNyIiwidGllclF1b3RhVHlwZSI6bnVsbCwidGllciI6IlVubGltaXRlZCIsIm5hbWUiOiJEZWZhdWx0QXBwbGljYXRpb24iLCJpZCI6MzQ3LCJ1dWlkIjoiYzU2ZTEzYTAtMzNjZS00M2Q4LWEyNjUtNDE4NmMxODhmNWI5In0sImlzcyI6Imh0dHBzOlwvXC9hcGltLW1hbmFnZW1lbnQuYXBwcy5pbnMuaW5zd2h1Yi5jb206NDQzXC9vYXV0aDJcL3Rva2VuIiwidGllckluZm8iOnsiQnJvbnplIjp7InRpZXJRdW90YVR5cGUiOiJyZXF1ZXN0Q291bnQiLCJncmFwaFFMTWF4Q29tcGxleGl0eSI6MCwiZ3JhcGhRTE1heERlcHRoIjowLCJzdG9wT25RdW90YVJlYWNoIjp0cnVlLCJzcGlrZUFycmVzdExpbWl0IjowLCJzcGlrZUFycmVzdFVuaXQiOm51bGx9LCJVbmxpbWl0ZWQiOnsidGllclF1b3RhVHlwZSI6InJlcXVlc3RDb3VudCIsImdyYXBoUUxNYXhDb21wbGV4aXR5IjowLCJncmFwaFFMTWF4RGVwdGgiOjAsInN0b3BPblF1b3RhUmVhY2giOnRydWUsInNwaWtlQXJyZXN0TGltaXQiOjAsInNwaWtlQXJyZXN0VW5pdCI6bnVsbH19LCJrZXl0eXBlIjoiUFJPRFVDVElPTiIsInBlcm1pdHRlZFJlZmVyZXIiOiIiLCJzdWJzY3JpYmVkQVBJcyI6W3sic3Vic2NyaWJlclRlbmFudERvbWFpbiI6ImNhcmJvbi5zdXBlciIsIm5hbWUiOiJBdXRoLVNlcnZpY2UiLCJjb250ZXh0IjoiXC9hdXRoLXNlcnZpY2VcLzEuMCIsInB1Ymxpc2hlciI6ImFkbWluIiwidmVyc2lvbiI6IjEuMCIsInN1YnNjcmlwdGlvblRpZXIiOiJCcm9uemUifSx7InN1YnNjcmliZXJUZW5hbnREb21haW4iOiJjYXJib24uc3VwZXIiLCJuYW1lIjoiRW50aXRpZXMiLCJjb250ZXh0IjoiXC9lbnRpdGllc1wvMS4yIiwicHVibGlzaGVyIjoiYWRtaW4iLCJ2ZXJzaW9uIjoiMS4yIiwic3Vic2NyaXB0aW9uVGllciI6IkJyb256ZSJ9LHsic3Vic2NyaWJlclRlbmFudERvbWFpbiI6ImNhcmJvbi5zdXBlciIsIm5hbWUiOiJGWCIsImNvbnRleHQiOiJcL2Z4XC8xLjAiLCJwdWJsaXNoZXIiOiJwdWJsaXNoZXIudXNlciIsInZlcnNpb24iOiIxLjAiLCJzdWJzY3JpcHRpb25UaWVyIjoiVW5saW1pdGVkIn0seyJzdWJzY3JpYmVyVGVuYW50RG9tYWluIjoiY2FyYm9uLnN1cGVyIiwibmFtZSI6Ikhvc3RlZENoZWNrb3V0IiwiY29udGV4dCI6IlwvaG9zdGVkY2hlY2tvdXRcLzEuMCIsInB1Ymxpc2hlciI6ImFkbWluIiwidmVyc2lvbiI6IjEuMCIsInN1YnNjcmlwdGlvblRpZXIiOiJVbmxpbWl0ZWQifSx7InN1YnNjcmliZXJUZW5hbnREb21haW4iOiJjYXJib24uc3VwZXIiLCJuYW1lIjoiTm90aWZpY2F0aW9uRW5naW5lIiwiY29udGV4dCI6Ilwvbm90aWZpY2F0aW9uZW5naW5lXC8xLjAiLCJwdWJsaXNoZXIiOiJhZG1pbiIsInZlcnNpb24iOiIxLjAiLCJzdWJzY3JpcHRpb25UaWVyIjoiQnJvbnplIn0seyJzdWJzY3JpYmVyVGVuYW50RG9tYWluIjoiY2FyYm9uLnN1cGVyIiwibmFtZSI6IlBheW1lbnQtTWV0aG9kcyIsImNvbnRleHQiOiJcL3BheW1lbnQtbWV0aG9kc1wvMS4wIiwicHVibGlzaGVyIjoicG9ydGFsIiwidmVyc2lvbiI6IjEuMCIsInN1YnNjcmlwdGlvblRpZXIiOiJCcm9uemUifSx7InN1YnNjcmliZXJUZW5hbnREb21haW4iOiJjYXJib24uc3VwZXIiLCJuYW1lIjoic3Vic2NyaXB0aW9ucyIsImNvbnRleHQiOiJcL3N1YnNjcmlwdGlvbnNcLzEuMCIsInB1Ymxpc2hlciI6InB1Ymxpc2hlci51c2VyIiwidmVyc2lvbiI6IjEuMCIsInN1YnNjcmlwdGlvblRpZXIiOiJVbmxpbWl0ZWQifSx7InN1YnNjcmliZXJUZW5hbnREb21haW4iOiJjYXJib24uc3VwZXIiLCJuYW1lIjoiVHJhbnNhY3Rpb25zIiwiY29udGV4dCI6IlwvdHJhbnNhY3Rpb25zXC8xLjAiLCJwdWJsaXNoZXIiOiJhZG1pbiIsInZlcnNpb24iOiIxLjAiLCJzdWJzY3JpcHRpb25UaWVyIjoiQnJvbnplIn0seyJzdWJzY3JpYmVyVGVuYW50RG9tYWluIjoiY2FyYm9uLnN1cGVyIiwibmFtZSI6IldhbGxldHMiLCJjb250ZXh0IjoiXC93YWxsZXRzXC8xLjAiLCJwdWJsaXNoZXIiOiJwdWJsaXNoZXIudXNlciIsInZlcnNpb24iOiIxLjAiLCJzdWJzY3JpcHRpb25UaWVyIjoiQnJvbnplIn0seyJzdWJzY3JpYmVyVGVuYW50RG9tYWluIjoiY2FyYm9uLnN1cGVyIiwibmFtZSI6IlZpcnR1YWwtQWNjb3VudHMiLCJjb250ZXh0IjoiXC92aXJ0dWFsYWNjb3VudHNcLzEuMCIsInB1Ymxpc2hlciI6InBvcnRhbCIsInZlcnNpb24iOiIxLjAiLCJzdWJzY3JpcHRpb25UaWVyIjoiQnJvbnplIn1dLCJwZXJtaXR0ZWRJUCI6IiIsImlhdCI6MTY5MzMyOTMwNiwianRpIjoiYWIwYjA2MzUtZGUwNC00NDU2LTg0YjktMDE1ODQ1M2VmMjU0In0=.V0f4S0VZwi_peqDMOqrOCJBnPAyF4NNgBb7MAjdzOxie1ZDaA6CyoGq7Ro4LX9EDA9okKZIvwW28JGvcNAY7gtsjwoi9zGCql9Kvoo8pp3UmgOyZi0Ycudx8UcNgYYCFdgeyAPFhNxLzjqgnYrwSpUILiluMvKnIVYzdZqifynEUSeuJQhcwEQ83NxWzLrO2vcq1RI8E0NaY3etrlw5qQOMAVzBc6Kz7HRBVXTfnS9p2v43wgd21J2dvdeqs46lvK06C_vzaBGEFiE0stwmrtcC58e7RAlftKcMsWhJS-0OCsjt-SwLWx6gYCmhrk9Tm-Y-YjroBjjkvEFMPfCqFpA==";
    /**
     * Contraseña para el entorno de producción en Costa Rica.
     *
     * @var string
     */
    private $PasswordPROCR = "Qunc4Vv07u7V";

    /**
     * Nombre de usuario para el entorno de producción en Costa Rica.
     *
     * @var string
     */
    private $UsernamePROCR = "doradobetdevcr";

    /**
     * Clave API para el entorno de producción en Ecuabet.
     *
     * @var string
     */
    private $ApiKeyPROECB = "eyJ4NXQiOiJNV1ExTWpBMlpESm1PV1U1WXpjNFpUazFZelk1T1dVeU56SmtaV1l5TWpZNE5qa3pZVFkyWXpjNE9EY3lZMlprWmpGaVpHUmhNMkkyTUdFeU5qRmpaZyIsImtpZCI6Im9rZGF3cyIsInR5cCI6IkpXVCIsImFsZyI6IlJTMjU2In0=.eyJzdWIiOiJlY3VhYmV0ZWNkZXZwcm9kQGNhcmJvbi5zdXBlciIsImFwcGxpY2F0aW9uIjp7Im93bmVyIjoiZWN1YWJldGVjZGV2cHJvZCIsInRpZXJRdW90YVR5cGUiOm51bGwsInRpZXIiOiJVbmxpbWl0ZWQiLCJuYW1lIjoiRGVmYXVsdEFwcGxpY2F0aW9uIiwiaWQiOjIwNSwidXVpZCI6IjFiNTcxOWRhLTc0ODEtNDEwNy05MmZhLTdjZmEzMGNkMGQ1ZSJ9LCJpc3MiOiJodHRwczpcL1wvYXBpbS1tYW5hZ2VtZW50LmFwcHMuaW5zLmluc3dodWIuY29tOjQ0M1wvb2F1dGgyXC90b2tlbiIsInRpZXJJbmZvIjp7IkJyb256ZSI6eyJ0aWVyUXVvdGFUeXBlIjoicmVxdWVzdENvdW50IiwiZ3JhcGhRTE1heENvbXBsZXhpdHkiOjAsImdyYXBoUUxNYXhEZXB0aCI6MCwic3RvcE9uUXVvdGFSZWFjaCI6dHJ1ZSwic3Bpa2VBcnJlc3RMaW1pdCI6MCwic3Bpa2VBcnJlc3RVbml0IjpudWxsfSwiVW5saW1pdGVkIjp7InRpZXJRdW90YVR5cGUiOiJyZXF1ZXN0Q291bnQiLCJncmFwaFFMTWF4Q29tcGxleGl0eSI6MCwiZ3JhcGhRTE1heERlcHRoIjowLCJzdG9wT25RdW90YVJlYWNoIjp0cnVlLCJzcGlrZUFycmVzdExpbWl0IjowLCJzcGlrZUFycmVzdFVuaXQiOm51bGx9fSwia2V5dHlwZSI6IlBST0RVQ1RJT04iLCJwZXJtaXR0ZWRSZWZlcmVyIjoiIiwic3Vic2NyaWJlZEFQSXMiOlt7InN1YnNjcmliZXJUZW5hbnREb21haW4iOiJjYXJib24uc3VwZXIiLCJuYW1lIjoiQXV0aC1TZXJ2aWNlIiwiY29udGV4dCI6IlwvYXV0aC1zZXJ2aWNlXC8xLjAiLCJwdWJsaXNoZXIiOiJhZG1pbiIsInZlcnNpb24iOiIxLjAiLCJzdWJzY3JpcHRpb25UaWVyIjoiQnJvbnplIn0seyJzdWJzY3JpYmVyVGVuYW50RG9tYWluIjoiY2FyYm9uLnN1cGVyIiwibmFtZSI6IkVudGl0aWVzIiwiY29udGV4dCI6IlwvZW50aXRpZXNcLzEuMiIsInB1Ymxpc2hlciI6ImFkbWluIiwidmVyc2lvbiI6IjEuMiIsInN1YnNjcmlwdGlvblRpZXIiOiJCcm9uemUifSx7InN1YnNjcmliZXJUZW5hbnREb21haW4iOiJjYXJib24uc3VwZXIiLCJuYW1lIjoiRlgiLCJjb250ZXh0IjoiXC9meFwvMS4wIiwicHVibGlzaGVyIjoicHVibGlzaGVyLnVzZXIiLCJ2ZXJzaW9uIjoiMS4wIiwic3Vic2NyaXB0aW9uVGllciI6IlVubGltaXRlZCJ9LHsic3Vic2NyaWJlclRlbmFudERvbWFpbiI6ImNhcmJvbi5zdXBlciIsIm5hbWUiOiJIb3N0ZWRDaGVja291dCIsImNvbnRleHQiOiJcL2hvc3RlZGNoZWNrb3V0XC8xLjAiLCJwdWJsaXNoZXIiOiJhZG1pbiIsInZlcnNpb24iOiIxLjAiLCJzdWJzY3JpcHRpb25UaWVyIjoiVW5saW1pdGVkIn0seyJzdWJzY3JpYmVyVGVuYW50RG9tYWluIjoiY2FyYm9uLnN1cGVyIiwibmFtZSI6IlRyYW5zYWN0aW9ucyIsImNvbnRleHQiOiJcL3RyYW5zYWN0aW9uc1wvMS4wIiwicHVibGlzaGVyIjoiYWRtaW4iLCJ2ZXJzaW9uIjoiMS4wIiwic3Vic2NyaXB0aW9uVGllciI6IkJyb256ZSJ9LHsic3Vic2NyaWJlclRlbmFudERvbWFpbiI6ImNhcmJvbi5zdXBlciIsIm5hbWUiOiJHaWZ0Q2FyZCIsImNvbnRleHQiOiJcL2dpZnRjYXJkXC8xLjAiLCJwdWJsaXNoZXIiOiJwdWJsaXNoZXIudXNlciIsInZlcnNpb24iOiIxLjAiLCJzdWJzY3JpcHRpb25UaWVyIjoiVW5saW1pdGVkIn0seyJzdWJzY3JpYmVyVGVuYW50RG9tYWluIjoiY2FyYm9uLnN1cGVyIiwibmFtZSI6Iklzc3VpbmciLCJjb250ZXh0IjoiXC9pc3N1aW5nXC8xLjAiLCJwdWJsaXNoZXIiOiJwdWJsaXNoZXIudXNlciIsInZlcnNpb24iOiIxLjAiLCJzdWJzY3JpcHRpb25UaWVyIjoiVW5saW1pdGVkIn0seyJzdWJzY3JpYmVyVGVuYW50RG9tYWluIjoiY2FyYm9uLnN1cGVyIiwibmFtZSI6IktZQyIsImNvbnRleHQiOiJcL2t5Y1wvMS4wIiwicHVibGlzaGVyIjoicHVibGlzaGVyLnVzZXIiLCJ2ZXJzaW9uIjoiMS4wIiwic3Vic2NyaXB0aW9uVGllciI6IlVubGltaXRlZCJ9LHsic3Vic2NyaWJlclRlbmFudERvbWFpbiI6ImNhcmJvbi5zdXBlciIsIm5hbWUiOiJOb3RpZmljYXRpb25FbmdpbmUiLCJjb250ZXh0IjoiXC9ub3RpZmljYXRpb25lbmdpbmVcLzEuMCIsInB1Ymxpc2hlciI6ImFkbWluIiwidmVyc2lvbiI6IjEuMCIsInN1YnNjcmlwdGlvblRpZXIiOiJCcm9uemUifSx7InN1YnNjcmliZXJUZW5hbnREb21haW4iOiJjYXJib24uc3VwZXIiLCJuYW1lIjoiUGF5bWVudC1NZXRob2RzIiwiY29udGV4dCI6IlwvcGF5bWVudC1tZXRob2RzXC8xLjAiLCJwdWJsaXNoZXIiOiJhZG1pbiIsInZlcnNpb24iOiIxLjAiLCJzdWJzY3JpcHRpb25UaWVyIjoiQnJvbnplIn0seyJzdWJzY3JpYmVyVGVuYW50RG9tYWluIjoiY2FyYm9uLnN1cGVyIiwibmFtZSI6InN1YnNjcmlwdGlvbnMiLCJjb250ZXh0IjoiXC9zdWJzY3JpcHRpb25zXC8xLjAiLCJwdWJsaXNoZXIiOiJwdWJsaXNoZXIudXNlciIsInZlcnNpb24iOiIxLjAiLCJzdWJzY3JpcHRpb25UaWVyIjoiVW5saW1pdGVkIn0seyJzdWJzY3JpYmVyVGVuYW50RG9tYWluIjoiY2FyYm9uLnN1cGVyIiwibmFtZSI6IktZQ01hbmFnZXIiLCJjb250ZXh0IjoiXC9LWUNNYW5hZ2VyXC8xLjAiLCJwdWJsaXNoZXIiOiJwb3J0YWwiLCJ2ZXJzaW9uIjoiMS4wIiwic3Vic2NyaXB0aW9uVGllciI6IlVubGltaXRlZCJ9LHsic3Vic2NyaWJlclRlbmFudERvbWFpbiI6ImNhcmJvbi5zdXBlciIsIm5hbWUiOiJXYWxsZXRzIiwiY29udGV4dCI6Ilwvd2FsbGV0c1wvMS4wIiwicHVibGlzaGVyIjoicHVibGlzaGVyLnVzZXIiLCJ2ZXJzaW9uIjoiMS4wIiwic3Vic2NyaXB0aW9uVGllciI6IkJyb256ZSJ9LHsic3Vic2NyaWJlclRlbmFudERvbWFpbiI6ImNhcmJvbi5zdXBlciIsIm5hbWUiOiJTZXJ2aWNlUHJvdmlkZXJzIiwiY29udGV4dCI6Ilwvc2VydmljZXByb3ZpZGVyc1wvMy4wIiwicHVibGlzaGVyIjoicHVibGlzaGVyLnVzZXIiLCJ2ZXJzaW9uIjoiMy4wIiwic3Vic2NyaXB0aW9uVGllciI6IlVubGltaXRlZCJ9XSwicGVybWl0dGVkSVAiOiIiLCJpYXQiOjE2NzEyMjAwMzksImp0aSI6IjEzOWZlZWRmLTVjNzktNDgwMC05ZWYyLTdjMDIxZDg3OTMxZiJ9.mocbgp5fv_hd326sVWSrlHkuBs87bVvZiNQGKrS9hZqayirYAYuyojbWlBdbZQvxsKjVuDTkZigR4GNrSNKWwFbf4b6jXBAQRx74iYygCELLI3inNDP1iMC3USAgAoQKdvXyWh6kVJSWbt-lpEBCDPuiRAlcu6x5SKoH-VVaOpDmh9WIcuuSXCltz0bbAtFD8bTDb6VbmlbJ0q0ccgMQBJSjJCgyxoiW8kljIPmgCCj9LndtsHoH9w5FM8ln0EeNDgVHI82wv6oh3DoYBgoKrPqY4ex_2JpnP836w2maoVJ9pQegvdnItgkBn67razXomnGevzgUhnOvsWmktCgjkw==";
    /**
     * Contraseña para el entorno de producción en Ecuabet.
     *
     * @var string
     */
    private $PasswordPROECB = "6kP3r7e87k9U";

    /**
     * Nombre de usuario para el entorno de producción en Ecuabet.
     *
     * @var string
     */
    private $UsernamePROECB = "ecuabetecdevprod";

    /**
     * Clave API para el entorno de producción en Brasil.
     *
     * @var string
     */
    private $ApiKeyPRODBR = "eyJ4NXQiOiJNV1ExTWpBMlpESm1PV1U1WXpjNFpUazFZelk1T1dVeU56SmtaV1l5TWpZNE5qa3pZVFkyWXpjNE9EY3lZMlprWmpGaVpHUmhNMkkyTUdFeU5qRmpaZyIsImtpZCI6Im9rZGF3cyIsInR5cCI6IkpXVCIsImFsZyI6IlJTMjU2In0=.eyJzdWIiOiJkb3JhZG9iZXRkZXZickBjYXJib24uc3VwZXIiLCJhcHBsaWNhdGlvbiI6eyJvd25lciI6ImRvcmFkb2JldGRldmJyIiwidGllclF1b3RhVHlwZSI6bnVsbCwidGllciI6IlVubGltaXRlZCIsIm5hbWUiOiJEZWZhdWx0QXBwbGljYXRpb24iLCJpZCI6Mjk5LCJ1dWlkIjoiODBiNDkyYzEtOWRlZS00NzAxLTgzOTAtZDRjYmZmNTczNjYzIn0sImlzcyI6Imh0dHBzOlwvXC9hcGltLW1hbmFnZW1lbnQuYXBwcy5pbnMuaW5zd2h1Yi5jb206NDQzXC9vYXV0aDJcL3Rva2VuIiwidGllckluZm8iOnsiQnJvbnplIjp7InRpZXJRdW90YVR5cGUiOiJyZXF1ZXN0Q291bnQiLCJncmFwaFFMTWF4Q29tcGxleGl0eSI6MCwiZ3JhcGhRTE1heERlcHRoIjowLCJzdG9wT25RdW90YVJlYWNoIjp0cnVlLCJzcGlrZUFycmVzdExpbWl0IjowLCJzcGlrZUFycmVzdFVuaXQiOm51bGx9LCJVbmxpbWl0ZWQiOnsidGllclF1b3RhVHlwZSI6InJlcXVlc3RDb3VudCIsImdyYXBoUUxNYXhDb21wbGV4aXR5IjowLCJncmFwaFFMTWF4RGVwdGgiOjAsInN0b3BPblF1b3RhUmVhY2giOnRydWUsInNwaWtlQXJyZXN0TGltaXQiOjAsInNwaWtlQXJyZXN0VW5pdCI6bnVsbH19LCJrZXl0eXBlIjoiUFJPRFVDVElPTiIsInBlcm1pdHRlZFJlZmVyZXIiOiIiLCJzdWJzY3JpYmVkQVBJcyI6W3sic3Vic2NyaWJlclRlbmFudERvbWFpbiI6ImNhcmJvbi5zdXBlciIsIm5hbWUiOiJBdXRoLVNlcnZpY2UiLCJjb250ZXh0IjoiXC9hdXRoLXNlcnZpY2VcLzEuMCIsInB1Ymxpc2hlciI6ImFkbWluIiwidmVyc2lvbiI6IjEuMCIsInN1YnNjcmlwdGlvblRpZXIiOiJCcm9uemUifSx7InN1YnNjcmliZXJUZW5hbnREb21haW4iOiJjYXJib24uc3VwZXIiLCJuYW1lIjoiRW50aXRpZXMiLCJjb250ZXh0IjoiXC9lbnRpdGllc1wvMS4yIiwicHVibGlzaGVyIjoiYWRtaW4iLCJ2ZXJzaW9uIjoiMS4yIiwic3Vic2NyaXB0aW9uVGllciI6IkJyb256ZSJ9LHsic3Vic2NyaWJlclRlbmFudERvbWFpbiI6ImNhcmJvbi5zdXBlciIsIm5hbWUiOiJGWCIsImNvbnRleHQiOiJcL2Z4XC8xLjAiLCJwdWJsaXNoZXIiOiJwdWJsaXNoZXIudXNlciIsInZlcnNpb24iOiIxLjAiLCJzdWJzY3JpcHRpb25UaWVyIjoiVW5saW1pdGVkIn0seyJzdWJzY3JpYmVyVGVuYW50RG9tYWluIjoiY2FyYm9uLnN1cGVyIiwibmFtZSI6Ikhvc3RlZENoZWNrb3V0IiwiY29udGV4dCI6IlwvaG9zdGVkY2hlY2tvdXRcLzEuMCIsInB1Ymxpc2hlciI6ImFkbWluIiwidmVyc2lvbiI6IjEuMCIsInN1YnNjcmlwdGlvblRpZXIiOiJVbmxpbWl0ZWQifSx7InN1YnNjcmliZXJUZW5hbnREb21haW4iOiJjYXJib24uc3VwZXIiLCJuYW1lIjoiS1lDIiwiY29udGV4dCI6Ilwva3ljXC8xLjAiLCJwdWJsaXNoZXIiOiJwdWJsaXNoZXIudXNlciIsInZlcnNpb24iOiIxLjAiLCJzdWJzY3JpcHRpb25UaWVyIjoiVW5saW1pdGVkIn0seyJzdWJzY3JpYmVyVGVuYW50RG9tYWluIjoiY2FyYm9uLnN1cGVyIiwibmFtZSI6Ik5vdGlmaWNhdGlvbkVuZ2luZSIsImNvbnRleHQiOiJcL25vdGlmaWNhdGlvbmVuZ2luZVwvMS4wIiwicHVibGlzaGVyIjoiYWRtaW4iLCJ2ZXJzaW9uIjoiMS4wIiwic3Vic2NyaXB0aW9uVGllciI6IkJyb256ZSJ9LHsic3Vic2NyaWJlclRlbmFudERvbWFpbiI6ImNhcmJvbi5zdXBlciIsIm5hbWUiOiJQYXltZW50LU1ldGhvZHMiLCJjb250ZXh0IjoiXC9wYXltZW50LW1ldGhvZHNcLzEuMCIsInB1Ymxpc2hlciI6InBvcnRhbCIsInZlcnNpb24iOiIxLjAiLCJzdWJzY3JpcHRpb25UaWVyIjoiQnJvbnplIn0seyJzdWJzY3JpYmVyVGVuYW50RG9tYWluIjoiY2FyYm9uLnN1cGVyIiwibmFtZSI6IlRyYW5zYWN0aW9ucyIsImNvbnRleHQiOiJcL3RyYW5zYWN0aW9uc1wvMS4wIiwicHVibGlzaGVyIjoiYWRtaW4iLCJ2ZXJzaW9uIjoiMS4wIiwic3Vic2NyaXB0aW9uVGllciI6IkJyb256ZSJ9LHsic3Vic2NyaWJlclRlbmFudERvbWFpbiI6ImNhcmJvbi5zdXBlciIsIm5hbWUiOiJXYWxsZXRzIiwiY29udGV4dCI6Ilwvd2FsbGV0c1wvMS4wIiwicHVibGlzaGVyIjoicHVibGlzaGVyLnVzZXIiLCJ2ZXJzaW9uIjoiMS4wIiwic3Vic2NyaXB0aW9uVGllciI6IkJyb256ZSJ9XSwicGVybWl0dGVkSVAiOiIiLCJpYXQiOjE2ODY5NDAxNTUsImp0aSI6ImM3OGI3YWJhLWIzMGQtNDYyYy1iZjdjLTA5YTM2NDVmYzhjYSJ9.RMWFrbG4h7pfh_BQEitwHRjZO-stmAln-hOGEn2MyusjJgyqiUmsfEVzLudV5XysPOk34z2JIDigm_2H0VCFzFOUfAS5F9iBfRUwDV98l4A7mXvLPjMaBuHJ_PTESvOPsNxnRSEP3peao5UU2P-lHPmDp-uPhKo7gaEYEsD_Dor6ZvzeRnbrHKzxs56RHpc2d2xNBH3aZ3TV-HaG7QzD8bJdvZPZl4QrStxnBASnaDAh3UfeRpsqMpA1zZYCOjfF4CoTt55ZTXoP54-55HXsV2_GPacv6CBKGR0bn43NQy6vGo0Sv_arKal4Y2qOm1v0WHtANMvMJIoDXXh2cA5GYw==";
    /**
     * Contraseña para el entorno de producción en Brasil.
     *
     * @var string
     */
    private $PasswordPRODBR = "Inswitch@2022";

    /**
     * Nombre de usuario para el entorno de producción en Brasil.
     *
     * @var string
     */
    private $UsernamePRODBR = "DoradoBetdevBR";

    /**
     * Clave API para el entorno de producción en Honduras.
     *
     * @var string
     */
    private $ApiKeyPROHND = "eyJ4NXQiOiJNV1ExTWpBMlpESm1PV1U1WXpjNFpUazFZelk1T1dVeU56SmtaV1l5TWpZNE5qa3pZVFkyWXpjNE9EY3lZMlprWmpGaVpHUmhNMkkyTUdFeU5qRmpaZyIsImtpZCI6Im9rZGF3cyIsInR5cCI6IkpXVCIsImFsZyI6IlJTMjU2In0=.eyJzdWIiOiJkb3JhZG9iZXRkZXZobmRAY2FyYm9uLnN1cGVyIiwiYXBwbGljYXRpb24iOnsib3duZXIiOiJkb3JhZG9iZXRkZXZobmQiLCJ0aWVyUXVvdGFUeXBlIjpudWxsLCJ0aWVyIjoiVW5saW1pdGVkIiwibmFtZSI6IkRlZmF1bHRBcHBsaWNhdGlvbiIsImlkIjozNDksInV1aWQiOiJmY2E1ZDg1Ni05YmZjLTQ2YzItOTM2Yi1jODZiYWMyZmVlM2QifSwiaXNzIjoiaHR0cHM6XC9cL2FwaW0tbWFuYWdlbWVudC5hcHBzLmlucy5pbnN3aHViLmNvbTo0NDNcL29hdXRoMlwvdG9rZW4iLCJ0aWVySW5mbyI6eyJCcm9uemUiOnsidGllclF1b3RhVHlwZSI6InJlcXVlc3RDb3VudCIsImdyYXBoUUxNYXhDb21wbGV4aXR5IjowLCJncmFwaFFMTWF4RGVwdGgiOjAsInN0b3BPblF1b3RhUmVhY2giOnRydWUsInNwaWtlQXJyZXN0TGltaXQiOjAsInNwaWtlQXJyZXN0VW5pdCI6bnVsbH0sIlVubGltaXRlZCI6eyJ0aWVyUXVvdGFUeXBlIjoicmVxdWVzdENvdW50IiwiZ3JhcGhRTE1heENvbXBsZXhpdHkiOjAsImdyYXBoUUxNYXhEZXB0aCI6MCwic3RvcE9uUXVvdGFSZWFjaCI6dHJ1ZSwic3Bpa2VBcnJlc3RMaW1pdCI6MCwic3Bpa2VBcnJlc3RVbml0IjpudWxsfX0sImtleXR5cGUiOiJQUk9EVUNUSU9OIiwicGVybWl0dGVkUmVmZXJlciI6IiIsInN1YnNjcmliZWRBUElzIjpbeyJzdWJzY3JpYmVyVGVuYW50RG9tYWluIjoiY2FyYm9uLnN1cGVyIiwibmFtZSI6IkF1dGgtU2VydmljZSIsImNvbnRleHQiOiJcL2F1dGgtc2VydmljZVwvMS4wIiwicHVibGlzaGVyIjoiYWRtaW4iLCJ2ZXJzaW9uIjoiMS4wIiwic3Vic2NyaXB0aW9uVGllciI6IkJyb256ZSJ9LHsic3Vic2NyaWJlclRlbmFudERvbWFpbiI6ImNhcmJvbi5zdXBlciIsIm5hbWUiOiJFbnRpdGllcyIsImNvbnRleHQiOiJcL2VudGl0aWVzXC8xLjIiLCJwdWJsaXNoZXIiOiJhZG1pbiIsInZlcnNpb24iOiIxLjIiLCJzdWJzY3JpcHRpb25UaWVyIjoiQnJvbnplIn0seyJzdWJzY3JpYmVyVGVuYW50RG9tYWluIjoiY2FyYm9uLnN1cGVyIiwibmFtZSI6IkZYIiwiY29udGV4dCI6IlwvZnhcLzEuMCIsInB1Ymxpc2hlciI6InB1Ymxpc2hlci51c2VyIiwidmVyc2lvbiI6IjEuMCIsInN1YnNjcmlwdGlvblRpZXIiOiJVbmxpbWl0ZWQifSx7InN1YnNjcmliZXJUZW5hbnREb21haW4iOiJjYXJib24uc3VwZXIiLCJuYW1lIjoiSG9zdGVkQ2hlY2tvdXQiLCJjb250ZXh0IjoiXC9ob3N0ZWRjaGVja291dFwvMS4wIiwicHVibGlzaGVyIjoiYWRtaW4iLCJ2ZXJzaW9uIjoiMS4wIiwic3Vic2NyaXB0aW9uVGllciI6IlVubGltaXRlZCJ9LHsic3Vic2NyaWJlclRlbmFudERvbWFpbiI6ImNhcmJvbi5zdXBlciIsIm5hbWUiOiJQYXltZW50LU1ldGhvZHMiLCJjb250ZXh0IjoiXC9wYXltZW50LW1ldGhvZHNcLzEuMCIsInB1Ymxpc2hlciI6InBvcnRhbCIsInZlcnNpb24iOiIxLjAiLCJzdWJzY3JpcHRpb25UaWVyIjoiQnJvbnplIn0seyJzdWJzY3JpYmVyVGVuYW50RG9tYWluIjoiY2FyYm9uLnN1cGVyIiwibmFtZSI6InN1YnNjcmlwdGlvbnMiLCJjb250ZXh0IjoiXC9zdWJzY3JpcHRpb25zXC8xLjAiLCJwdWJsaXNoZXIiOiJwdWJsaXNoZXIudXNlciIsInZlcnNpb24iOiIxLjAiLCJzdWJzY3JpcHRpb25UaWVyIjoiVW5saW1pdGVkIn0seyJzdWJzY3JpYmVyVGVuYW50RG9tYWluIjoiY2FyYm9uLnN1cGVyIiwibmFtZSI6IlRyYW5zYWN0aW9ucyIsImNvbnRleHQiOiJcL3RyYW5zYWN0aW9uc1wvMS4wIiwicHVibGlzaGVyIjoiYWRtaW4iLCJ2ZXJzaW9uIjoiMS4wIiwic3Vic2NyaXB0aW9uVGllciI6IkJyb256ZSJ9LHsic3Vic2NyaWJlclRlbmFudERvbWFpbiI6ImNhcmJvbi5zdXBlciIsIm5hbWUiOiJXYWxsZXRzIiwiY29udGV4dCI6Ilwvd2FsbGV0c1wvMS4wIiwicHVibGlzaGVyIjoicHVibGlzaGVyLnVzZXIiLCJ2ZXJzaW9uIjoiMS4wIiwic3Vic2NyaXB0aW9uVGllciI6IkJyb256ZSJ9XSwicGVybWl0dGVkSVAiOiIiLCJpYXQiOjE2OTE3OTYzMjAsImp0aSI6ImQ2YjM3NDhjLTNiNmQtNDVhYy1iYmFkLTVhYzEzMjMzNjZkNiJ9.NgQc3KJx9yl0cJfF_yBzMrhfN-NlvHJ2iZyR_m58BaEPGO-5CU0szORp5u4DNR3SG5_LIyC2SmXxew4AXCCL_w25FGvypJAgjC4d9g6bahTG7uVSHYNt_HhC-09VsKHxv7TCH3e6tuBLSFy1dg-z2NmonDh83JgoClXsPawGqU06olv7TAnYWCV4gnIEY2Tr2fc1cpMbKRPbXDrJUTYsnmENs_fgQGeNckT0Tkfw9-4uTSCV5AUl1jthmCy5d2vgN-iptnJGmJtG7_aQ6cauvG3-KfvFRw_lJcBw8cUbsj_1l4z4jh1UxUJxGS_6ZSMx5gp8ozVGkONqifT4hRqptg==";
    /**
     * Contraseña para el entorno de producción en Honduras.
     *
     * @var string
     */
    private $PasswordPROHND = "af1J2g9RXmMc";

    /**
     * Nombre de usuario para el entorno de producción en Honduras.
     *
     * @var string
     */
    private $UsernamePROHND = "doradobetdevhnd";

    /**
     * Clave API para el entorno de producción en Nicaragua.
     *
     * @var string
     */
    private $ApiKeyPRONI = "eyJ4NXQiOiJNV1ExTWpBMlpESm1PV1U1WXpjNFpUazFZelk1T1dVeU56SmtaV1l5TWpZNE5qa3pZVFkyWXpjNE9EY3lZMlprWmpGaVpHUmhNMkkyTUdFeU5qRmpaZyIsImtpZCI6Im9rZGF3cyIsInR5cCI6IkpXVCIsImFsZyI6IlJTMjU2In0=.eyJzdWIiOiJkb3JhZG9iZXRkZXZuaUBjYXJib24uc3VwZXIiLCJhcHBsaWNhdGlvbiI6eyJvd25lciI6ImRvcmFkb2JldGRldm5pIiwidGllclF1b3RhVHlwZSI6bnVsbCwidGllciI6IlVubGltaXRlZCIsIm5hbWUiOiJEZWZhdWx0QXBwbGljYXRpb24iLCJpZCI6NDIzLCJ1dWlkIjoiNjY2OTUxYjUtZDRjNC00ZDIxLTk2YjUtMjQyMDE0ZjZjMGRmIn0sImlzcyI6Imh0dHBzOlwvXC9hcGltLW1hbmFnZW1lbnQuYXBwcy5pbnMuaW5zd2h1Yi5jb206NDQzXC9vYXV0aDJcL3Rva2VuIiwidGllckluZm8iOnsiQnJvbnplIjp7InRpZXJRdW90YVR5cGUiOiJyZXF1ZXN0Q291bnQiLCJncmFwaFFMTWF4Q29tcGxleGl0eSI6MCwiZ3JhcGhRTE1heERlcHRoIjowLCJzdG9wT25RdW90YVJlYWNoIjp0cnVlLCJzcGlrZUFycmVzdExpbWl0IjowLCJzcGlrZUFycmVzdFVuaXQiOm51bGx9LCJVbmxpbWl0ZWQiOnsidGllclF1b3RhVHlwZSI6InJlcXVlc3RDb3VudCIsImdyYXBoUUxNYXhDb21wbGV4aXR5IjowLCJncmFwaFFMTWF4RGVwdGgiOjAsInN0b3BPblF1b3RhUmVhY2giOnRydWUsInNwaWtlQXJyZXN0TGltaXQiOjAsInNwaWtlQXJyZXN0VW5pdCI6bnVsbH19LCJrZXl0eXBlIjoiUFJPRFVDVElPTiIsInBlcm1pdHRlZFJlZmVyZXIiOiIiLCJzdWJzY3JpYmVkQVBJcyI6W3sic3Vic2NyaWJlclRlbmFudERvbWFpbiI6ImNhcmJvbi5zdXBlciIsIm5hbWUiOiJBdXRoLVNlcnZpY2UiLCJjb250ZXh0IjoiXC9hdXRoLXNlcnZpY2VcLzEuMCIsInB1Ymxpc2hlciI6ImFkbWluIiwidmVyc2lvbiI6IjEuMCIsInN1YnNjcmlwdGlvblRpZXIiOiJCcm9uemUifSx7InN1YnNjcmliZXJUZW5hbnREb21haW4iOiJjYXJib24uc3VwZXIiLCJuYW1lIjoiRW50aXRpZXMiLCJjb250ZXh0IjoiXC9lbnRpdGllc1wvMS4yIiwicHVibGlzaGVyIjoiYWRtaW4iLCJ2ZXJzaW9uIjoiMS4yIiwic3Vic2NyaXB0aW9uVGllciI6IkJyb256ZSJ9LHsic3Vic2NyaWJlclRlbmFudERvbWFpbiI6ImNhcmJvbi5zdXBlciIsIm5hbWUiOiJGcmF1ZENoZWNrIiwiY29udGV4dCI6IlwvZnJhdWRjaGVja1wvMS4wIiwicHVibGlzaGVyIjoiYWRtaW4iLCJ2ZXJzaW9uIjoiMS4wIiwic3Vic2NyaXB0aW9uVGllciI6IkJyb256ZSJ9LHsic3Vic2NyaWJlclRlbmFudERvbWFpbiI6ImNhcmJvbi5zdXBlciIsIm5hbWUiOiJIb3N0ZWRDaGVja291dCIsImNvbnRleHQiOiJcL2hvc3RlZGNoZWNrb3V0XC8xLjAiLCJwdWJsaXNoZXIiOiJhZG1pbiIsInZlcnNpb24iOiIxLjAiLCJzdWJzY3JpcHRpb25UaWVyIjoiVW5saW1pdGVkIn0seyJzdWJzY3JpYmVyVGVuYW50RG9tYWluIjoiY2FyYm9uLnN1cGVyIiwibmFtZSI6Ik5vdGlmaWNhdGlvbkVuZ2luZSIsImNvbnRleHQiOiJcL25vdGlmaWNhdGlvbmVuZ2luZVwvMS4wIiwicHVibGlzaGVyIjoiYWRtaW4iLCJ2ZXJzaW9uIjoiMS4wIiwic3Vic2NyaXB0aW9uVGllciI6IkJyb256ZSJ9LHsic3Vic2NyaWJlclRlbmFudERvbWFpbiI6ImNhcmJvbi5zdXBlciIsIm5hbWUiOiJQYXltZW50LU1ldGhvZHMiLCJjb250ZXh0IjoiXC9wYXltZW50LW1ldGhvZHNcLzEuMCIsInB1Ymxpc2hlciI6InBvcnRhbCIsInZlcnNpb24iOiIxLjAiLCJzdWJzY3JpcHRpb25UaWVyIjoiQnJvbnplIn0seyJzdWJzY3JpYmVyVGVuYW50RG9tYWluIjoiY2FyYm9uLnN1cGVyIiwibmFtZSI6IlRyYW5zYWN0aW9ucyIsImNvbnRleHQiOiJcL3RyYW5zYWN0aW9uc1wvMS4wIiwicHVibGlzaGVyIjoiYWRtaW4iLCJ2ZXJzaW9uIjoiMS4wIiwic3Vic2NyaXB0aW9uVGllciI6IkJyb256ZSJ9LHsic3Vic2NyaWJlclRlbmFudERvbWFpbiI6ImNhcmJvbi5zdXBlciIsIm5hbWUiOiJzdWJzY3JpcHRpb25zIiwiY29udGV4dCI6Ilwvc3Vic2NyaXB0aW9uc1wvMS4wIiwicHVibGlzaGVyIjoicHVibGlzaGVyLnVzZXIiLCJ2ZXJzaW9uIjoiMS4wIiwic3Vic2NyaXB0aW9uVGllciI6IlVubGltaXRlZCJ9LHsic3Vic2NyaWJlclRlbmFudERvbWFpbiI6ImNhcmJvbi5zdXBlciIsIm5hbWUiOiJWaXJ0dWFsLUFjY291bnRzIiwiY29udGV4dCI6IlwvdmlydHVhbGFjY291bnRzXC8xLjAiLCJwdWJsaXNoZXIiOiJwb3J0YWwiLCJ2ZXJzaW9uIjoiMS4wIiwic3Vic2NyaXB0aW9uVGllciI6IkJyb256ZSJ9LHsic3Vic2NyaWJlclRlbmFudERvbWFpbiI6ImNhcmJvbi5zdXBlciIsIm5hbWUiOiJXYWxsZXRzIiwiY29udGV4dCI6Ilwvd2FsbGV0c1wvMS4wIiwicHVibGlzaGVyIjoicHVibGlzaGVyLnVzZXIiLCJ2ZXJzaW9uIjoiMS4wIiwic3Vic2NyaXB0aW9uVGllciI6IkJyb256ZSJ9XSwicGVybWl0dGVkSVAiOiIiLCJpYXQiOjE3MDAwNjg2ODMsImp0aSI6ImE5MzFmMWQxLTcxY2UtNDY4Zi05MDNkLTBiMTc4NjQ1NjJmNiJ9.n5KKbfi_i5uZouUE5oDUjOR_Lkvjef4NCIU-_WRKExJ6KMgK7US2JdOQpNdh0YLaqb73zYWXwoqoRT1FNFjZ3vQWQLCgXHy1rnqpSiZ0Xm-Cjv8Hx87v66CPEtFV2GcU2pO6DMlc5Codb7Q50nkKv7eeSbIHL3bQpUuqCenv1LkmFGPW35dUmlecWkJeI5VIfw_BN9l1vkpEPEazB73uiFqG5LZkpypZzkKtU3f-paEEfq2aVAGBCW4Umw2ie9upuq_dg6xgSTU0KtGOzfR_Glerdi5Dp6TbfpkC5Z-avtSVfYtcvykRhIQEZt4oaRWOh6JeyOpXF8sGD-d2EMh_cQ==";
    /**
     * Contraseña para el entorno de producción en Nicaragua.
     *
     * @var string
     */
    private $PasswordPRONI = "uTHT0010FyZg!";

    /**
     * Nombre de usuario para el entorno de producción en Nicaragua.
     *
     * @var string
     */
    private $UsernamePRONI = "doradobetdevni";

    /**
     * URL base utilizada en el entorno actual.
     *
     * @var string
     */
    private $URL = "";

    /**
     * URL base para el entorno de desarrollo.
     *
     * @var string
     */
    private $URLDEV = 'https://gateway-am.apps.ins.inswhub.com';

    /**
     * URL base para el entorno de producción.
     *
     * @var string
     */
    private $URLPROD = 'https://gateway-am.apps.ins.inswhub.com';

    /**
     * URL de callback utilizada en el entorno actual.
     *
     * @var string
     */
    private $callback_url = "";

    /**
     * Nombre de usuario utilizado en el entorno actual.
     *
     * @var string
     */
    private $username = "";

    /**
     * URL de callback para el entorno de desarrollo.
     *
     * @var string
     */
    private $callback_urlDEV = "https://apidev.virtualsoft.tech/integrations/payment/inswitch/confirm/";

    /**
     * URL de callback para el entorno de producción.
     *
     * @var string
     */
    private $callback_urlPROD = "https://integrations.virtualsoft.tech/payment/inswitch/confirm/";

    /**
     * URL para depósitos utilizada en el entorno actual.
     *
     * @var string
     */
    private $URLDEPOSIT = "";

    /**
     * URL para depósitos en el entorno de desarrollo.
     *
     * @var string
     */
    private $URLDEPOSITDEV = "https://devfrontend.virtualsoft.tech/doradobetv3/gestion/deposito";

    /**
     * URL para depósitos en el entorno de producción.
     *
     * @var string
     */
    private $URLDEPOSITPROD = "https://doradobet.com/gestion/deposito";

    /**
     * URL para depósitos en el entorno actual de Ecuador.
     *
     * @var string
     */
    private $URLDEPOSITECB = "";

    /**
     * URL para depósitos en el entorno de desarrollo de Ecuador.
     *
     * @var string
     */
    private $URLDEPOSITDEVECB = "https://devfrontend.virtualsoft.tech/ecuabetv4/gestion/deposito";

    /**
     * URL para depósitos en el entorno de producción de Ecuador.
     *
     * @var string
     */
    private $URLDEPOSITPRODECB = "https://ecuabet.com/gestion/deposito";

    /**
     * Constructor de la clase.
     *
     * Inicializa las configuraciones del entorno (desarrollo o producción) y asigna
     * los valores correspondientes a las propiedades de la clase, como claves API,
     * credenciales de usuario, URLs y otros parámetros necesarios.
     */
    public function __construct()
    {
        $ConfigurationEnvironment = new ConfigurationEnvironment();

        if ($ConfigurationEnvironment->isDevelopment()) {
            $this->ApiKey = $this->ApiKeyDEV;
            $this->ClaveConsu = $this->ClaveConsuDEV;
            $this->SecretConsu = $this->SecretConsuDEV;
            $this->Username = $this->UsernameDEV;
            $this->Password = $this->PasswordDEV;
            $this->ApiKeyGT = $this->ApiKeyPROGT;
            $this->UsernameGT = $this->UsernamePROGT;
            $this->PasswordGT = $this->PasswordPROGT;
            $this->ApiKeySV = $this->ApiKeyPROSV;
            $this->UsernameSV = $this->UsernamePROSV;
            $this->PasswordSV = $this->PasswordPROSV;
            $this->ApiKeyEC = $this->ApiKeyPROEC;
            $this->UsernameEC = $this->UsernamePROEC;
            $this->PasswordEC = $this->PasswordPROEC;
            $this->ApiKeyCR = $this->ApiKeyPROCR;
            $this->UsernameCR = $this->UsernamePROCR;
            $this->PasswordCR = $this->PasswordPROCR;
            $this->ApiKeyECB = $this->ApiKeyPROECB;
            $this->UsernameECB = $this->UsernamePROECB;
            $this->PasswordECB = $this->PasswordPROECB;
            $this->ApiKeyDBR = $this->ApiKeyPRODBR;
            $this->UsernameDBR = $this->UsernamePRODBR;
            $this->PasswordDBR = $this->PasswordPRODBR;
            $this->ApiKeyHND = $this->ApiKeyPROHND;
            $this->UsernameHND = $this->UsernamePROHND;
            $this->PasswordHND = $this->PasswordPROHND;
            $this->ApiKeyNI = $this->ApiKeyPRONI;
            $this->UsernameNI = $this->UsernamePRONI;
            $this->PasswordNI = $this->PasswordPRONI;
            $this->callback_url = $this->callback_urlDEV;
            $this->URLDEPOSIT = $this->URLDEPOSITDEV;
            $this->URLDEPOSITECB = $this->URLDEPOSITDEVECB;
            $this->URL = $this->URLDEV;
        } else {
            $this->ApiKey = $this->ApiKeyPRO;
            $this->ClaveConsu = $this->ClaveConsuPRO;
            $this->SecretConsu = $this->SecretConsuPRO;
            $this->Username = $this->UsernamePRO;
            $this->Password = $this->PasswordPRO;
            $this->ApiKeyGT = $this->ApiKeyPROGT;
            $this->UsernameGT = $this->UsernamePROGT;
            $this->PasswordGT = $this->PasswordPROGT;
            $this->ApiKeySV = $this->ApiKeyPROSV;
            $this->UsernameSV = $this->UsernamePROSV;
            $this->PasswordSV = $this->PasswordPROSV;
            $this->ApiKeyEC = $this->ApiKeyPROEC;
            $this->UsernameEC = $this->UsernamePROEC;
            $this->PasswordEC = $this->PasswordPROEC;
            $this->ApiKeyCR = $this->ApiKeyPROCR;
            $this->UsernameCR = $this->UsernamePROCR;
            $this->PasswordCR = $this->PasswordPROCR;
            $this->ApiKeyECB = $this->ApiKeyPROECB;
            $this->UsernameECB = $this->UsernamePROECB;
            $this->PasswordECB = $this->PasswordPROECB;
            $this->ApiKeyDBR = $this->ApiKeyPRODBR;
            $this->UsernameDBR = $this->UsernamePRODBR;
            $this->PasswordDBR = $this->PasswordPRODBR;
            $this->ApiKeyHND = $this->ApiKeyPROHND;
            $this->UsernameHND = $this->UsernamePROHND;
            $this->PasswordHND = $this->PasswordPROHND;
            $this->ApiKeyNI = $this->ApiKeyPRONI;
            $this->UsernameNI = $this->UsernamePRONI;
            $this->PasswordNI = $this->PasswordPRONI;
            $this->callback_url = $this->callback_urlPROD;
            $this->URLDEPOSIT = $this->URLDEPOSITPROD;
            $this->URLDEPOSITECB = $this->URLDEPOSITPRODECB;
            $this->URL = $this->URLPROD;
        }
    }

    /**
     * Crea una solicitud de pago para un usuario y producto específicos.
     *
     * @param Usuario  $Usuario    Objeto que contiene la información del usuario.
     * @param Producto $Producto   Objeto que contiene la información del producto.
     * @param float    $valor      Monto del pago a procesar.
     * @param string   $urlSuccess URL a la que se redirige en caso de éxito.
     * @param string   $urlFailed  URL a la que se redirige en caso de fallo.
     *
     * @return object Objeto JSON con el resultado de la operación, incluyendo éxito y URL de redirección.
     */
    public function createRequestPayment(Usuario $Usuario, Producto $Producto, $valor, $urlSuccess, $urlFailed)
    {
        $Subproveedor = new Subproveedor("", "INSWITCH");

        $SubproveedorMandante = new SubproveedorMandante($Subproveedor->subproveedorId, $Usuario->mandante);

        $detalle = json_decode($SubproveedorMandante->detalle);
        $this->username = $detalle->username;

        $Registro = new Registro("", $Usuario->usuarioId);

        $data = array();
        $data["success"] = false;
        $data["error"] = 1;

        $estado = 'A';
        $estado_producto = 'E';
        $tipo = 'T';

        $pais = $Usuario->paisId;
        $usuario_id = $Usuario->usuarioId;
        $cedula = $Registro->cedula;
        $nombre = $Usuario->nombre;
        $email = $Usuario->login;
        $apellido = $Registro->apellido1;
        $valor = $valor;
        $producto_id = $Producto->productoId;
        $extID = $Producto->externoId;
        $moneda = $Usuario->moneda;
        $mandante = $Usuario->mandante;
        $descripcion = "Deposito";
        $iso = "";
        $celular = $Usuario->celular;
        $tipoDocumento = $Registro->tipoDoc;
        $genero = $Registro->sexo;

        $producto = new Producto($producto_id);
        $Pais = new Pais($Usuario->paisId);


        $TransaccionProductoMySqlDAO = new TransaccionProductoMySqlDAO();
        $Transaction = $TransaccionProductoMySqlDAO->getTransaction();
        $Transaction->getConnection()->beginTransaction();

        $TransaccionProducto = new TransaccionProducto();
        $TransaccionProducto->setProductoId($producto_id);
        $TransaccionProducto->setUsuarioId($usuario_id);
        $TransaccionProducto->setValor($valor);
        $TransaccionProducto->setEstado($estado);
        $TransaccionProducto->setTipo($tipo);
        $TransaccionProducto->setEstadoProducto($estado_producto);
        $TransaccionProducto->setMandante($mandante);
        $TransaccionProducto->setFinalId(0);
        $TransaccionProducto->setExternoId(0);
        $transproductoId = $TransaccionProductoMySqlDAO->insert($TransaccionProducto);


        //convertir el tipo de documento a los requeridos por el proveedor
        switch ($Pais->iso) {
            case "GT":
                $this->ApiKey = $this->ApiKeyGT;
                $this->Username = $this->UsernameGT;
                $this->Password = $this->PasswordGT;
                $moneda = 'USD';
                $valor = ($valor * 0.13);
                break;
            case "SV":
                $this->ApiKey = $this->ApiKeySV;
                $this->Username = $this->UsernameSV;
                $this->Password = $this->PasswordSV;
                $moneda = 'USD';
                break;
            case "EC":
                if ($mandante == 8) {
                    $this->ApiKey = $this->ApiKeyECB;
                    $this->Username = $this->UsernameECB;
                    $this->Password = $this->PasswordECB;
                    $this->URLDEPOSIT = $this->URLDEPOSITECB;
                    $moneda = 'USD';
                } else {
                    $this->ApiKey = $this->ApiKeyEC;
                    $this->Username = $this->UsernameEC;
                    $this->Password = $this->PasswordEC;
                    $moneda = 'USD';
                }
                break;
            case "CR":
                $this->ApiKey = $this->ApiKeyCR;
                $this->Username = $this->UsernameCR;
                $this->Password = $this->PasswordCR;
                $moneda = 'CRC';
                break;
            case "BR":
                $this->ApiKey = $this->ApiKeyDBR;
                $this->Username = $this->UsernameDBR;
                $this->Password = $this->PasswordDBR;
                $moneda = 'BRL';
                break;
            case "HN":
                $this->ApiKey = $this->ApiKeyHND;
                $this->Username = $this->UsernameHND;
                $this->Password = $this->PasswordHND;
                $moneda = 'USD';
                break;
            case "NI":
                $this->ApiKey = $this->ApiKeyNI;
                $this->Username = $this->UsernameNI;
                $this->Password = $this->PasswordNI;
                $moneda = 'USD';
                break;
            default:
                $this->ApiKey = $this->ApiKey;
                $this->Username = $this->Username;
                $this->Password = $this->Password;
                $moneda = $moneda;
                break;
        }

        syslog(LOG_WARNING, " INSWITCH ApiKey :" . $this->ApiKey . "  Username :" . $this->Username . "  Password :" . $this->Password);

        //Generar token
        $respuesta = $this->generateToken($this->URL, '/auth-service/1.0/protocol/openid-connect/token');

        $tokenT = $respuesta->access_token;
        $ciudad = '';
        try {
            $ciudadNo = new Ciudad($Registro->ciudadId);
            $ciudad = $ciudadNo->ciudadNom;
            date_default_timezone_set('America/"' . $ciudad . '"');
        } catch (Exception  $e) {
        }
        $date = date("Y-m-d H:i:s");
        $mod_date = strtotime($date . "+ 30 days");
        $expiration = date("Y-m-d H:i:s", $mod_date);


        date_default_timezone_set('America/Bogota');

        $lang = 'es';
        $idType = 'nationalId';
        $paymentM = 'bankkin-pe';

        if ($Pais->iso == 'BR') {
            $lang = 'pt';
            $idType = 'CPF';
        }
        if ($extID == 'DBR_PIX') {
            $paymentM = 'pixin-br';
        }

        if ($extID == 'BIN_PAY') {
            $paymentM = 'binancepayin-' . strtolower($Pais->iso);
        }

        //Cuerpo de la solicitud de deposito
        $data = array();
        $data['language'] = $lang;
        $data['paymentExpiration'] = 1700200;
        $data['pageExpiration'] = 600;
        $data['successUrl'] = $this->URLDEPOSIT;
        $data['errorUrl'] = $this->URLDEPOSIT;
        $data['currency'] = $moneda;
        $data['countryCode'] = $Pais->iso;
        $data['amount'] = $valor;
        $data['descriptionText'] = $transproductoId;
        $data['requestingOrganisationTransactionReference'] = $transproductoId;

        $data["purchaseItems"][] = array(
            'item_name' => 'Deposit',
            'item_quantity' => 1,
            'item_amount' => $valor
        );
        $data['senderKycInformation'] = [
            'name' => [
                "firstName" => $nombre,
                "lastName" => $apellido,
                "fullName" => $nombre . ' ' . $apellido,
            ],
            'contact' => [
                "email" => $email
            ],
            'idDocuments' => [[
                "idType" => $idType,
                "idNumber" => $cedula,
                "issuerCountry" => $Pais->iso
            ]],
            "entityType" => "naturalPerson"
        ];
        $data["metadata"][] = array(
            'key' => $transproductoId,
            'value' => $usuario_id
        );
        if ($mandante != 8) {
            $data["paymentMethods"] = array(
                $paymentM,
            );
        } elseif ($extID == 'BIN_PAY' && $mandante == 8) {
            $data["paymentMethods"] = array(
                $paymentM,
            );
        }


        $uuid = vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex(openssl_random_pseudo_bytes(16)), 4));

        $TransproductoDetalle = new TransproductoDetalle();
        $TransproductoDetalle->transproductoId = $transproductoId;
        $TransproductoDetalle->tValue = json_encode($data);
        $TransproductoDetalleMySqlDAO = new TransproductoDetalleMySqlDAO($Transaction);
        $TransproductoDetalleMySqlDAO->insert($TransproductoDetalle);

        $Result = $this->connection($data, $tokenT, $uuid, $this->URL, '/hostedcheckout/1.0/checkout');

        if ($Result != '' && $Result->errorCode == null) {
            $TransprodLog = new TransprodLog();
            $TransprodLog->setTransproductoId($transproductoId);
            $TransprodLog->setEstado('E');
            $TransprodLog->setTipoGenera('A');
            $TransprodLog->setComentario('Envio Solicitud de deposito');
            $TransprodLog->setTValue(json_encode($Result));
            $TransprodLog->setUsucreaId(0);
            $TransprodLog->setUsumodifId(0);

            $TransprodLogMySqlDAO = new TransprodLogMySqlDAO($Transaction);
            $TransprodLogMySqlDAO->insert($TransprodLog);

            $TransaccionProducto->setExternoId($Result->id);
            $TransaccionProductoMySqlDAO->update($TransaccionProducto);
            $Transaction->commit();

            $response = $Result;
            $data = array();
            $data["success"] = true;
            $data["url"] = $response->redirect;
        }

        return json_decode(json_encode($data));
    }

    /**
     * Procesa un pago mediante tarjeta para un usuario y producto específicos.
     *
     * @param Usuario $Usuario     Objeto que contiene la información del usuario.
     * @param mixed   $Producto    Objeto o identificador que contiene la información del producto.
     * @param float   $valor       Monto del pago a procesar.
     * @param string  $success_url URL a la que se redirige en caso de éxito.
     * @param string  $fail_url    URL a la que se redirige en caso de fallo.
     *
     * @return object Objeto JSON con el resultado de la operación, incluyendo éxito y URL de redirección.
     */
    public function PaymentMethodsCard(Usuario $Usuario, $Producto, $valor, $success_url, $fail_url)
    {
        $time = time();
        syslog(LOG_WARNING, " INSWITCH2 :" . $time);


        $this->success_url = $success_url;
        $this->fail_url = $fail_url;
        $Subproveedor = new Subproveedor("", "INSWITCH");

        $SubproveedorMandante = new SubproveedorMandante($Subproveedor->subproveedorId, $Usuario->mandante);

        $detalle = json_decode($SubproveedorMandante->detalle);
        $this->username = $detalle->username;

        $Registro = new Registro("", $Usuario->usuarioId);

        $data = array();
        $data["success"] = false;
        $data["error"] = 1;

        $estado = 'A';
        $estado_producto = 'E';
        $tipo = 'T';

        $usuario_id = $Usuario->usuarioId;
        $cedula = $Registro->cedula;
        $nombre = $Usuario->nombre;
        $email = $Usuario->login;
        $apellido = $Registro->apellido1;
        $valor = $valor;
        $producto_id = $Producto->productoId;
        $moneda = $Usuario->moneda;
        $mandante = $Usuario->mandante;
        $descripcion = "Deposito";
        $celular = $Registro->celular;
        $direccion = $Registro->getDireccion();
        $tipoDocumento = $Registro->tipoDoc;
        $genero = $Registro->sexo;
        $Pais = new Pais($Usuario->paisId);
        $ValorTMR = $valor;

        switch ($Pais->iso) {
            case "GT":
                $this->ApiKey = $this->ApiKeyGT;
                $this->Username = $this->UsernameGT;
                $this->Password = $this->PasswordGT;
                $moneda = 'USD';
                $metodo = "";
                $PaisMandante = new PaisMandante("", $Usuario->mandante, $Pais->paisId);
                $ValorTMR = ($valor * $PaisMandante->trmUsd);

                break;
            case "SV":
                $this->ApiKey = $this->ApiKeySV;
                $this->Username = $this->UsernameSV;
                $this->Password = $this->PasswordSV;
                $moneda = 'USD';
                $metodo = "";
                break;
            case "CR":
                $this->ApiKey = $this->ApiKeyCR;
                $this->Username = $this->UsernameCR;
                $this->Password = $this->PasswordCR;
                $moneda = 'CRC';
                $metodo = "";
                break;
            case "EC":
                if ($mandante == 8) {
                    $this->ApiKey = $this->ApiKeyECB;
                    $this->Username = $this->UsernameECB;
                    $this->Password = $this->PasswordECB;
                    $this->URLDEPOSIT = $this->URLDEPOSITECB;
                    $moneda = 'USD';
                    $metodo = "cardurlin-ec";
                } else {
                    $this->ApiKey = $this->ApiKeyEC;
                    $this->Username = $this->UsernameEC;
                    $this->Password = $this->PasswordEC;
                    $this->URLDEPOSIT = $this->URLDEPOSITECB;
                    $moneda = 'USD';
                    $metodo = "cardurlin-ec";
                }
                break;
            case "CR":
                $this->ApiKey = $this->ApiKeyCR;
                $this->Username = $this->UsernameCR;
                $this->Password = $this->PasswordCR;
                $moneda = 'CRC';
                break;
            case "BR":
                $this->ApiKey = $this->ApiKeyDBR;
                $this->Username = $this->UsernameDBR;
                $this->Password = $this->PasswordDBR;
                $moneda = 'BRL';
                break;
            case "HN":
                $this->ApiKey = $this->ApiKeyHND;
                $this->Username = $this->UsernameHND;
                $this->Password = $this->PasswordHND;
                $moneda = 'USD';
                break;
            case "NI":
                $this->ApiKey = $this->ApiKeyNI;
                $this->Username = $this->UsernameNI;
                $this->Password = $this->PasswordNI;
                $moneda = 'USD';
                break;
            default:
                $this->ApiKey = $this->ApiKey;
                $this->Username = $this->Username;
                $this->Password = $this->Password;
                $moneda = $moneda;
                $metodo = "cardurlin-pe";
                break;
        }


        $ciudad = '';
        $nombreCiudad = '';
        try {
            $ciudad = new Ciudad($Registro->ciudadId);
            $nombreCiudad = $ciudad->ciudadNom;
        } catch (Exception  $e) {
            if ($Pais->iso == 'EC') {
                $nombreCiudad = 'Pichincha';
            }
        }


        $TransaccionProductoMySqlDAO = new TransaccionProductoMySqlDAO();
        $Transaction = $TransaccionProductoMySqlDAO->getTransaction();
        $Transaction->getConnection()->beginTransaction();

        $TransaccionProducto = new TransaccionProducto();
        $TransaccionProducto->setProductoId($producto_id);
        $TransaccionProducto->setUsuarioId($usuario_id);
        $TransaccionProducto->setValor($valor);
        $TransaccionProducto->setEstado($estado);
        $TransaccionProducto->setTipo($tipo);
        $TransaccionProducto->setEstadoProducto($estado_producto);
        $TransaccionProducto->setMandante($mandante);
        $TransaccionProducto->setFinalId(0);
        $TransaccionProducto->setExternoId(0);
        $transproductoId = $TransaccionProductoMySqlDAO->insert($TransaccionProducto);


        $mandante = $Usuario->mandante;


        $Tipometodo = "card";


        $respuesta = $this->generateToken($this->URL, '/auth-service/1.0/protocol/openid-connect/token');

        $tokenT = $respuesta->access_token;

        syslog(LOG_WARNING, "INSWITCH TOKENCARD: " . $tokenT);

        $ConfigurationEnvironment = new ConfigurationEnvironment();

        $data = "direction=in&country=" . $Pais->iso . "&paymentMethodTypeClass=" . $Tipometodo . "&paymentMethodTypeStatus=available";


        $uuid = vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex(openssl_random_pseudo_bytes(16)), 4));


        $Result = $this->connectionGET($data, $tokenT, $uuid, $this->URL, '/payment-methods/1.0/paymentmethodtypes');

        $metodo = $Result[0]->paymentMethodType;

        if ($Usuario->mandante == "8" && $Producto->externoId == "INSWITCHTARJETAS") {
            $metodo = "cardurlin-ec";
        }
        $uuid2 = vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex(openssl_random_pseudo_bytes(16)), 4));

        if ($Pais->iso == "PE" && $Producto->externoId == "INSWITCHTARJETAS") {
            //$RefencePayment = "3239333032362D313030302D31313031";
            $RefencePayment = "3531373430342D313030302D31303430";
        }

        if ($Pais->iso == "GT" && $Producto->externoId == "INSWITCHTARJETAS" && $mandante == "0") {
            $RefencePayment = "3837303630392D313030302D31303030";
        }

        if ($Pais->iso == "SV" && $Producto->externoId == "INSWITCHTARJETAS" && $mandante == "0") {
            $RefencePayment = "3531353731382D313030302D31303030";
        }

        if ($Pais->iso == "EC" && $Producto->externoId == "INSWITCHTARJETAS" && $mandante == "0") {
            $RefencePayment = "3730343637302D313030302D31303030";
        }

        if ($Pais->iso == "EC" && $Producto->externoId == "INSWITCHTARJETAS" && $mandante == "8") {
            $RefencePayment = "3233303631382D313030302D31303030";
        }
        if ($Pais->iso == "CR" && $Producto->externoId == "INSWITCHTARJETAS" && $mandante == "0") {
            $RefencePayment = "32373335342D313030302D31303230";
        }

        if ($Pais->iso == "NI" && $Producto->externoId == "INSWITCHTARJETAS") {
            $RefencePayment = "3933383432342D313030302D31303030";
        }

        if ($Pais->iso == "HN" && $Producto->externoId == "INSWITCHTARJETAS") {
            $RefencePayment = "3236373630382D313030302D31303030";
        }

        if ($Pais->iso == "BR" && $Producto->externoId == "INSWITCHTARJETAS") {
            $RefencePayment = "3631333536332D313030302D31303030";
        }

        $PaisMandante = new PaisMandante("", $Usuario->mandante, $Pais->paisId);
        $ValorTMR = ($valor * $PaisMandante->trmUsd);


        $data2 = array(
            "amount" => strval($ValorTMR),
            "currency" => $moneda,
            "debitParty" => array(
                "type" => $metodo,
                "data" => array(
                    "document_number" => $cedula,
                    "full_name" => $nombre,
                    "first_name" => $Registro->nombre1,
                    "last_name" => $apellido,
                    "email" => $email,
                    "city" => $nombreCiudad,
                    "state" => $nombreCiudad,
                    "address" => $direccion,
                    "phone" => $celular

                )
            ),
            "creditParty" => array(
                "paymentMethodReference" => $RefencePayment,
            ),
            "descriptionText" => $descripcion,
            "requestingOrganisationTransactionReference" => $transproductoId,
            "country" => $Pais->iso
        );

        syslog(LOG_WARNING, "INSWITCH DATACARD: " . json_encode($data2));

        $data2 = json_encode($data2);


        $TransproductoDetalle = new TransproductoDetalle();
        $TransproductoDetalle->transproductoId = $transproductoId;
        $TransproductoDetalle->tValue = $data2;
        $TransproductoDetalleMySqlDAO = new TransproductoDetalleMySqlDAO($Transaction);
        $TransproductoDetalleMySqlDAO->insert($TransproductoDetalle);

        $Result2 = $this->connectionPOST($data2, $tokenT, $uuid2, $this->URL, '/transactions/1.0/transactions/type/deposit');
        syslog(LOG_WARNING, "INSWITCH RESPONSECARD: " . json_encode($Result2));

        if ($Usuario->test == 'S') {
        }
        if ($Result2 != '' && $Result2->transactionStatus == "waiting") {
            $TransprodLog = new TransprodLog();
            $TransprodLog->setTransproductoId($transproductoId);
            $TransprodLog->setEstado('E');
            $TransprodLog->setTipoGenera('A');
            $TransprodLog->setComentario('Envio Solicitud de deposito');
            $TransprodLog->setTValue(json_encode($Result));
            $TransprodLog->setUsucreaId(0);
            $TransprodLog->setUsumodifId(0);

            $TransprodLogMySqlDAO = new TransprodLogMySqlDAO($Transaction);
            $TransprodLogMySqlDAO->insert($TransprodLog);

            $TransaccionProducto->setExternoId($Result2->transactionReference);
            $TransaccionProductoMySqlDAO->update($TransaccionProducto);
            $Transaction->commit();

            $response = $Result2;
            $data = array();
            $data["success"] = true;
            $data["url"] = $response->requiredAction->data->redirectURL;
        }

        return json_decode(json_encode($data));
    }

    /**
     * Procesa un pago en efectivo para un usuario y producto específicos.
     *
     * @param Usuario $Usuario     Objeto que contiene la información del usuario.
     * @param mixed   $Producto    Objeto o identificador que contiene la información del producto.
     * @param float   $valor       Monto del pago a procesar.
     * @param string  $success_url URL a la que se redirige en caso de éxito.
     * @param string  $fail_url    URL a la que se redirige en caso de fallo.
     *
     * @return object Objeto JSON con el resultado de la operación, incluyendo éxito, URL de redirección y código.
     */
    public function PaymentMethodsCash(Usuario $Usuario, $Producto, $valor, $success_url, $fail_url)
    {
        $this->success_url = $success_url;
        $this->fail_url = $fail_url;
        $Subproveedor = new Subproveedor("", "INSWITCH");

        $SubproveedorMandante = new SubproveedorMandante($Subproveedor->subproveedorId, $Usuario->mandante);

        $detalle = json_decode($SubproveedorMandante->detalle);
        $this->username = $detalle->username;

        $Registro = new Registro("", $Usuario->usuarioId);

        $data = array();
        $data["success"] = false;
        $data["error"] = 1;

        $estado = 'A';
        $estado_producto = 'E';
        $tipo = 'T';

        $usuario_id = $Usuario->usuarioId;
        $cedula = $Registro->cedula;
        $nombre = $Usuario->nombre;
        $email = $Usuario->login;
        $apellido = $Registro->apellido1;
        $valor = $valor;
        $producto_id = $Producto->productoId;
        $moneda = $Usuario->moneda;
        $mandante = $Usuario->mandante;
        $descripcion = "Deposito";
        $celular = $Registro->celular;
        $direccion = $Registro->getDireccion();
        $tipoDocumento = $Registro->tipoDoc;
        $genero = $Registro->sexo;

        $Pais = new Pais($Usuario->paisId);

        switch ($Pais->iso) {
            case "GT":
                $this->ApiKey = $this->ApiKeyGT;
                $this->Username = $this->UsernameGT;
                $this->Password = $this->PasswordGT;
                $moneda = 'USD';
                break;
            case "SV":
                $this->ApiKey = $this->ApiKeySV;
                $this->Username = $this->UsernameSV;
                $this->Password = $this->PasswordSV;
                $moneda = 'USD';
                break;
            case "EC":
                if ($mandante == 8) {
                    $this->ApiKey = $this->ApiKeyECB;
                    $this->Username = $this->UsernameECB;
                    $this->Password = $this->PasswordECB;
                    $this->URLDEPOSIT = $this->URLDEPOSITECB;
                    $moneda = 'USD';
                } else {
                    $this->ApiKey = $this->ApiKeyEC;
                    $this->Username = $this->UsernameEC;
                    $this->Password = $this->PasswordEC;
                    $this->URLDEPOSIT = $this->URLDEPOSITECB;
                    $moneda = 'USD';
                }

                break;
            case "CR":
                $this->ApiKey = $this->ApiKeyCR;
                $this->Username = $this->UsernameCR;
                $this->Password = $this->PasswordCR;
                $moneda = 'CRC';
                break;
            case "BR":
                $this->ApiKey = $this->ApiKeyDBR;
                $this->Username = $this->UsernameDBR;
                $this->Password = $this->PasswordDBR;
                $moneda = 'BRL';
                break;
            case "HN":
                $this->ApiKey = $this->ApiKeyHND;
                $this->Username = $this->UsernameHND;
                $this->Password = $this->PasswordHND;
                $moneda = 'USD';
                break;
            case "NI":
                $this->ApiKey = $this->ApiKeyNI;
                $this->Username = $this->UsernameNI;
                $this->Password = $this->PasswordNI;
                $moneda = 'USD';
                break;
            default:
                $this->ApiKey = $this->ApiKey;
                $this->Username = $this->Username;
                $this->Password = $this->Password;
                $moneda = $moneda;
                break;
        }


        $TransaccionProductoMySqlDAO = new TransaccionProductoMySqlDAO();
        $Transaction = $TransaccionProductoMySqlDAO->getTransaction();
        $Transaction->getConnection()->beginTransaction();

        $TransaccionProducto = new TransaccionProducto();
        $TransaccionProducto->setProductoId($producto_id);
        $TransaccionProducto->setUsuarioId($usuario_id);
        $TransaccionProducto->setValor($valor);
        $TransaccionProducto->setEstado($estado);
        $TransaccionProducto->setTipo($tipo);
        $TransaccionProducto->setEstadoProducto($estado_producto);
        $TransaccionProducto->setMandante($mandante);
        $TransaccionProducto->setFinalId(0);
        $TransaccionProducto->setExternoId(0);
        $transproductoId = $TransaccionProductoMySqlDAO->insert($TransaccionProducto);


        $mandante = $Usuario->mandante;
        $Tipometodo = "cash";


        $respuesta = $this->generateToken($this->URL, '/auth-service/1.0/protocol/openid-connect/token');

        $tokenT = $respuesta->access_token;

        syslog(LOG_WARNING, "INSWITCH TOKENCASH: " . json_encode($respuesta));

        $ConfigurationEnvironment = new ConfigurationEnvironment();

        $data = "direction=in&country=" . $Pais->iso . "&paymentMethodTypeClass=" . $Tipometodo . "&paymentMethodTypeStatus=available";


        $uuid = vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex(openssl_random_pseudo_bytes(16)), 4));


        $Result = $this->connectionGET($data, $tokenT, $uuid, $this->URL, '/payment-methods/1.0/paymentmethodtypes');

        if ($Usuario->test == 'S') {
            //print_r($Result);
        }
        $metodo = $Result[0]->paymentMethodType;

        $uuid2 = vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex(openssl_random_pseudo_bytes(16)), 4));
        $Result = json_decode($Result);


        if ($Pais->iso == "PE" && $Producto->externoId == "INSWITCHPAGOSFISICOS") {
            $RefencePayment = "3431393534362D313030302D31303030";

            $tipoDocumento = 'nationalId';
        }


        if ($Pais->iso == "GT" && $Producto->externoId == "INSWITCHPAGOSFISICOS") {
            $RefencePayment = "3837303630392D313030302D31303030";
        }

        if ($Pais->iso == "SV" && $Producto->externoId == "INSWITCHPAGOSFISICOS") {
            $RefencePayment = "3531353731382D313030302D31303030";
        }

        if ($Pais->iso == "EC" && $Producto->externoId == "INSWITCHPAGOSFISICOS") {
            $RefencePayment = "3730343637302D313030302D31303030";
        }

        if ($Pais->iso == "CR" && $Producto->externoId == "INSWITCHPAGOSFISICOS") {
            $RefencePayment = "32373335342D313030302D31303230";
        }

        if ($Pais->iso == "NI" && $Producto->externoId == "INSWITCHPAGOSFISICOS") {
            $RefencePayment = "3933383432342D313030302D31303030";
        }

        if ($Pais->iso == "HN" && $Producto->externoId == "INSWITCHPAGOSFISICOS") {
            $RefencePayment = "3236373630382D313030302D31303030";
        }

        if ($Pais->iso == "BR" && $Producto->externoId == "INSWITCHPAGOSFISICOS") {
            $RefencePayment = "3631333536332D313030302D31303030";
        }

        if ($Pais->iso == "EC" && $Producto->externoId == "INSWITCHPAGOSFISICOS" && $mandante == "8") {
            $RefencePayment = "3233303631382D313030302D31303030";
        }


        $data2 = array(
            "amount" => strval($valor),
            "currency" => $moneda,
            "debitParty" => array(
                "type" => $metodo,
                "data" => array(
                    "first_name" => $Registro->nombre1,
                    "last_name" => $apellido,
                    "document_number" => $cedula,
                    "document_type" => $tipoDocumento,
                    "email" => $email

                )
            ),
            "creditParty" => array(
                "paymentMethodReference" => $RefencePayment,
            ),
            "descriptionText" => $descripcion,
            "requestingOrganisationTransactionReference" => $transproductoId,
            "country" => $Pais->iso
        );

        syslog(LOG_WARNING, "INSWITCH DATACASH: " . json_encode($Result2));

        $TransproductoDetalle = new TransproductoDetalle();
        $TransproductoDetalle->transproductoId = $transproductoId;
        $TransproductoDetalle->tValue = json_encode($data);
        $TransproductoDetalleMySqlDAO = new TransproductoDetalleMySqlDAO($Transaction);
        $TransproductoDetalleMySqlDAO->insert($TransproductoDetalle);

        $Result2 = $this->connectionPOST($data2, $tokenT, $uuid2, $this->URL, '/transactions/1.0/transactions/type/deposit');
        syslog(LOG_WARNING, "INSWITCH RESPONSECASH: " . json_encode($Result2));

        if ($Usuario->test == 'S') {
        }

        if ($Result2 != '' && $Result2->transactionStatus == "waiting") {
            $TransprodLog = new TransprodLog();
            $TransprodLog->setTransproductoId($transproductoId);
            $TransprodLog->setEstado('E');
            $TransprodLog->setTipoGenera('A');
            $TransprodLog->setComentario('Envio Solicitud de deposito');
            $TransprodLog->setTValue(json_encode($Result2));
            $TransprodLog->setUsucreaId(0);
            $TransprodLog->setUsumodifId(0);

            $TransprodLogMySqlDAO = new TransprodLogMySqlDAO($Transaction);
            $TransprodLogMySqlDAO->insert($TransprodLog);

            $TransaccionProducto->setExternoId($Result2->transactionReference);
            $TransaccionProductoMySqlDAO->update($TransaccionProducto);
            $Transaction->commit();

            $response = $Result2;

            $data = array();
            $data["success"] = true;
            $data["url"] = $response->requiredAction->data->additionalInformation[0]->value;
            $data["code"] = $response->requiredAction->data->code;
        }
        return json_decode(json_encode($data));
    }

    /**
     * Procesa un pago utilizando criptomonedas para un usuario y producto específicos.
     *
     * @param Usuario $Usuario     Objeto que contiene la información del usuario.
     * @param mixed   $Producto    Objeto o identificador que contiene la información del producto.
     * @param float   $valor       Monto del pago a procesar.
     * @param string  $success_url URL a la que se redirige en caso de éxito.
     * @param string  $fail_url    URL a la que se redirige en caso de fallo.
     *
     * @return object Objeto JSON con el resultado de la operación, incluyendo éxito, URL de redirección y código.
     */
    public function PaymentMethodsCrypto(Usuario $Usuario, $Producto, $valor, $success_url, $fail_url)
    {
        $this->success_url = $success_url;
        $this->fail_url = $fail_url;
        $Subproveedor = new Subproveedor("", "INSWITCH");

        $SubproveedorMandante = new SubproveedorMandante($Subproveedor->subproveedorId, $Usuario->mandante);

        $detalle = json_decode($SubproveedorMandante->detalle);
        $this->username = $detalle->username;

        $Registro = new Registro("", $Usuario->usuarioId);

        $data = array();
        $data["success"] = false;
        $data["error"] = 1;

        $estado = 'A';
        $estado_producto = 'E';
        $tipo = 'T';

        $usuario_id = $Usuario->usuarioId;
        $cedula = $Registro->cedula;
        $nombre = $Usuario->nombre;
        $email = $Usuario->login;
        $apellido = $Registro->apellido1;
        $valor = $valor;
        $producto_id = $Producto->productoId;
        $moneda = $Usuario->moneda;
        $mandante = $Usuario->mandante;
        $descripcion = "Deposito";
        $celular = $Registro->celular;
        $direccion = $Registro->getDireccion();
        $tipoDocumento = $Registro->tipoDoc;
        $genero = $Registro->sexo;
        $Pais = new Pais($Usuario->paisId);

        switch ($Pais->iso) {
            case "GT":
                $this->ApiKey = $this->ApiKeyGT;
                $this->Username = $this->UsernameGT;
                $this->Password = $this->PasswordGT;
                $moneda = 'USD';

                break;
            case "SV":
                $this->ApiKey = $this->ApiKeySV;
                $this->Username = $this->UsernameSV;
                $this->Password = $this->PasswordSV;
                $moneda = 'USD';

                break;
            case "EC":
                if ($mandante == 8) {
                    $this->ApiKey = $this->ApiKeyECB;
                    $this->Username = $this->UsernameECB;
                    $this->Password = $this->PasswordECB;
                    $this->URLDEPOSIT = $this->URLDEPOSITECB;
                    $moneda = 'USD';
                } else {
                    $this->ApiKey = $this->ApiKeyEC;
                    $this->Username = $this->UsernameEC;
                    $this->Password = $this->PasswordEC;
                    $this->URLDEPOSIT = $this->URLDEPOSITECB;
                    $moneda = 'USD';
                }

                break;
            case "CR":
                $this->ApiKey = $this->ApiKeyCR;
                $this->Username = $this->UsernameCR;
                $this->Password = $this->PasswordCR;
                $moneda = 'CRC';
                break;
            case "BR":
                $this->ApiKey = $this->ApiKeyDBR;
                $this->Username = $this->UsernameDBR;
                $this->Password = $this->PasswordDBR;
                $moneda = 'BRL';
                break;
            case "HN":
                $this->ApiKey = $this->ApiKeyHND;
                $this->Username = $this->UsernameHND;
                $this->Password = $this->PasswordHND;
                $moneda = 'USD';
                break;
            case "NI":
                $this->ApiKey = $this->ApiKeyNI;
                $this->Username = $this->UsernameNI;
                $this->Password = $this->PasswordNI;
                $moneda = 'USD';
                break;
            default:
                $this->ApiKey = $this->ApiKey;
                $this->Username = $this->Username;
                $this->Password = $this->Password;
                $moneda = $moneda;
                break;
        }


        $TransaccionProductoMySqlDAO = new TransaccionProductoMySqlDAO();
        $Transaction = $TransaccionProductoMySqlDAO->getTransaction();
        $Transaction->getConnection()->beginTransaction();

        $TransaccionProducto = new TransaccionProducto();
        $TransaccionProducto->setProductoId($producto_id);
        $TransaccionProducto->setUsuarioId($usuario_id);
        $TransaccionProducto->setValor($valor);
        $TransaccionProducto->setEstado($estado);
        $TransaccionProducto->setTipo($tipo);
        $TransaccionProducto->setEstadoProducto($estado_producto);
        $TransaccionProducto->setMandante($mandante);
        $TransaccionProducto->setFinalId(0);
        $TransaccionProducto->setExternoId(0);
        $transproductoId = $TransaccionProductoMySqlDAO->insert($TransaccionProducto);

        $mandante = $Usuario->mandante;
        $Tipometodo = "crypto";

        $respuesta = $this->generateToken($this->URL, '/auth-service/1.0/protocol/openid-connect/token');

        $tokenT = $respuesta->access_token;

        syslog(LOG_WARNING, "INSWITCH TOKENCRYPTO: " . json_encode($respuesta));

        $ConfigurationEnvironment = new ConfigurationEnvironment();

        $data = "direction=in&country=" . $Pais->iso . "&paymentMethodTypeClass=" . $Tipometodo . "&paymentMethodTypeStatus=available";

        $uuid = vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex(openssl_random_pseudo_bytes(16)), 4));

        $Result = $this->connectionGET($data, $tokenT, $uuid, $this->URL, '/payment-methods/1.0/paymentmethodtypes');
        syslog(LOG_WARNING, "INSWITCH PAYMENTCRYPTO: " . json_encode($Result));
        $metodo = $Result[0]->paymentMethodType;

        $uuid2 = vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex(openssl_random_pseudo_bytes(16)), 4));
        //$Result = json_decode($Result);


        if ($Pais->iso == "PE" && $Producto->externoId == "BIN_PAY") {
            $RefencePayment = "313837312D313030302D31313030";
        }

        if ($Pais->iso == "GT" && $Producto->externoId == "BIN_PAY") {
            $RefencePayment = "3837303630392D313030302D31303030";
        }

        if ($Pais->iso == "SV" && $Producto->externoId == "BIN_PAY") {
            $RefencePayment = "3531353731382D313030302D31303030";
        }

        if ($Pais->iso == "EC" && $Producto->externoId == "BIN_PAY") {
            $RefencePayment = "3730343637302D313030302D31303030";
        }

        if ($Pais->iso == "CR" && $Producto->externoId == "BIN_PAY") {
            $RefencePayment = "32373335342D313030302D31303230";
        }

        if ($Pais->iso == "NI" && $Producto->externoId == "BIN_PAY") {
            $RefencePayment = "3933383432342D313030302D31303030";
        }

        if ($Pais->iso == "HN" && $Producto->externoId == "BIN_PAY") {
            $RefencePayment = "3236373630382D313030302D31303030";
        }

        if ($Pais->iso == "BR" && $Producto->externoId == "BIN_PAY") {
            $RefencePayment = "3631333536332D313030302D31303030";
        }

        if ($Pais->iso == "EC" && $Producto->externoId == "BIN_PAY" && $mandante == "8") {
            $RefencePayment = "3233303631382D313030302D31303030";
        }

        $data2 = array(
            "amount" => strval($valor),
            "currency" => $moneda,
            "debitParty" => array(
                "type" => $metodo,
                "data" => array(
                    "first_name" => $Registro->nombre1,
                    "last_name" => $apellido
                )
            ),
            "creditParty" => array(
                "paymentMethodReference" => $RefencePayment,
            ),
            "descriptionText" => $descripcion,
            "requestingOrganisationTransactionReference" => $transproductoId,
            "country" => $Pais->iso
        );

        syslog(LOG_WARNING, "INSWITCH DATACRYPTO: " . json_encode($data2));

        $TransproductoDetalle = new TransproductoDetalle();
        $TransproductoDetalle->transproductoId = $transproductoId;
        $TransproductoDetalle->tValue = json_encode($data);
        $TransproductoDetalleMySqlDAO = new TransproductoDetalleMySqlDAO($Transaction);
        $TransproductoDetalleMySqlDAO->insert($TransproductoDetalle);

        $Result2 = $this->connectionPOST($data2, $tokenT, $uuid2, $this->URL, '/transactions/1.0/transactions/type/deposit');
        syslog(LOG_WARNING, "INSWITCH RESPONSECRYPTO: " . json_encode($Result2));
        if ($Result2 != '' && $Result2->transactionStatus == "waiting") {
            $TransprodLog = new TransprodLog();
            $TransprodLog->setTransproductoId($transproductoId);
            $TransprodLog->setEstado('E');
            $TransprodLog->setTipoGenera('A');
            $TransprodLog->setComentario('Envio Solicitud de deposito');
            $TransprodLog->setTValue(json_encode($Result2));
            $TransprodLog->setUsucreaId(0);
            $TransprodLog->setUsumodifId(0);

            $TransprodLogMySqlDAO = new TransprodLogMySqlDAO($Transaction);
            $TransprodLogMySqlDAO->insert($TransprodLog);

            $TransaccionProducto->setExternoId($Result2->transactionReference);
            $TransaccionProductoMySqlDAO->update($TransaccionProducto);
            $Transaction->commit();
            $useragent = $_SERVER['HTTP_USER_AGENT'];
            $jsonServer = json_encode($_SERVER);
            $serverCodif = base64_encode($jsonServer);


            $ismobile = '';

            if (preg_match('/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino/i', $useragent) || preg_match('/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i', substr($useragent, 0, 4))) {
                $ismobile = '1';
            }
            $iPod = stripos($_SERVER['HTTP_USER_AGENT'], "iPod");
            $iPhone = stripos($_SERVER['HTTP_USER_AGENT'], "iPhone");
            $iPad = stripos($_SERVER['HTTP_USER_AGENT'], "iPad");
            $Android = stripos($_SERVER['HTTP_USER_AGENT'], "Android");
            $webOS = stripos($_SERVER['HTTP_USER_AGENT'], "webOS");

            if ($iPod || $iPhone) {
                $ismobile = '1';
            } elseif ($iPad) {
                $ismobile = '1';
            } elseif ($Android) {
                $ismobile = '1';
            }
            $response = $Result2;

            $data = array();
            $data["success"] = true;
            $data["url"] = $response->requiredAction->data->redirectURL;
            $data["code"] = $response->transactionReference;
        }
        return json_decode(json_encode($data));
    }

    /**
     * Método para gestionar pagos mediante cuentas virtuales.
     *
     * @param Usuario $Usuario     Objeto que representa al usuario que realiza la transacción.
     * @param mixed   $Producto    Objeto que representa el producto asociado a la transacción.
     * @param float   $valor       Monto de la transacción.
     * @param string  $success_url URL a la que se redirige en caso de éxito.
     * @param string  $fail_url    URL a la que se redirige en caso de fallo.
     *
     * @return object Objeto JSON con el resultado de la operación, incluyendo éxito, URL e información adicional.
     *
     * Este método realiza las siguientes acciones:
     * - Configura las credenciales y parámetros necesarios.
     * - Inicia una transacción en la base de datos.
     * - Genera un token de autenticación para realizar solicitudes a servicios externos.
     * - Valida si ya existe una cuenta virtual para evitar duplicados.
     * - Realiza depósitos o crea cuentas virtuales según sea necesario.
     * - Registra los detalles de la transacción y los logs correspondientes.
     * - Detecta si el usuario está utilizando un dispositivo móvil.
     */
    public function PaymentMethodsVirtualAccounts(Usuario $Usuario, $Producto, $valor, $success_url, $fail_url)
    {
        if ($_ENV['debug']) {
            error_reporting(E_ALL);
            ini_set("display_errors", "ON");
        }
        $this->success_url = $success_url;
        $this->fail_url = $fail_url;
        $Subproveedor = new Subproveedor("", "INSWITCH");

        $SubproveedorMandante = new SubproveedorMandante($Subproveedor->subproveedorId, $Usuario->mandante);

        $detalle = json_decode($SubproveedorMandante->detalle);
        $this->username = $detalle->username;

        $Registro = new Registro("", $Usuario->usuarioId);

        $data = array();
        $data["success"] = false;
        $data["error"] = 1;

        $estado = 'A';
        $estado_producto = 'E';
        $tipo = 'T';

        $usuario_id = $Usuario->usuarioId;
        $cedula = $Registro->cedula;
        $nombre = $Usuario->nombre;
        $email = $Usuario->login;
        $apellido = $Registro->apellido1;
        $valor = $valor;
        $producto_id = $Producto->productoId;
        $moneda = $Usuario->moneda;
        $mandante = $Usuario->mandante;
        $descripcion = "Deposito";
        $celular = $Registro->celular;
        $direccion = $Registro->getDireccion();
        $tipoDocumento = $Registro->tipoDoc;
        $genero = $Registro->sexo;
        $Pais = new Pais($Usuario->paisId);

        switch ($tipoDocumento) {
            case "C":
                $tipoDoc = "nationalId";
                break;

            case "E":
                $tipoDoc = "residentId";
                break;
        }
        $this->ApiKey = "eyJ4NXQiOiJNV1ExTWpBMlpESm1PV1U1WXpjNFpUazFZelk1T1dVeU56SmtaV1l5TWpZNE5qa3pZVFkyWXpjNE9EY3lZMlprWmpGaVpHUmhNMkkyTUdFeU5qRmpaZyIsImtpZCI6Im9rZGF3cyIsInR5cCI6IkpXVCIsImFsZyI6IlJTMjU2In0=.eyJzdWIiOiJkb3JhZG9iZXRkZXZAY2FyYm9uLnN1cGVyIiwiYXBwbGljYXRpb24iOnsib3duZXIiOiJkb3JhZG9iZXRkZXYiLCJ0aWVyUXVvdGFUeXBlIjpudWxsLCJ0aWVyIjoiVW5saW1pdGVkIiwibmFtZSI6IkRlZmF1bHRBcHBsaWNhdGlvbiIsImlkIjo5OSwidXVpZCI6ImYyNDEwYzcwLTE1NzctNDA0NS1iYjg5LTk1NDMyNTEyOWE0MCJ9LCJpc3MiOiJodHRwczpcL1wvYXBpbS1tYW5hZ2VtZW50LmFwcHMuaW5zLmluc3dodWIuY29tOjQ0M1wvb2F1dGgyXC90b2tlbiIsInRpZXJJbmZvIjp7IkJyb256ZSI6eyJ0aWVyUXVvdGFUeXBlIjoicmVxdWVzdENvdW50IiwiZ3JhcGhRTE1heENvbXBsZXhpdHkiOjAsImdyYXBoUUxNYXhEZXB0aCI6MCwic3RvcE9uUXVvdGFSZWFjaCI6dHJ1ZSwic3Bpa2VBcnJlc3RMaW1pdCI6MCwic3Bpa2VBcnJlc3RVbml0IjpudWxsfSwiVW5saW1pdGVkIjp7InRpZXJRdW90YVR5cGUiOiJyZXF1ZXN0Q291bnQiLCJncmFwaFFMTWF4Q29tcGxleGl0eSI6MCwiZ3JhcGhRTE1heERlcHRoIjowLCJzdG9wT25RdW90YVJlYWNoIjp0cnVlLCJzcGlrZUFycmVzdExpbWl0IjowLCJzcGlrZUFycmVzdFVuaXQiOm51bGx9fSwia2V5dHlwZSI6IlNBTkRCT1giLCJwZXJtaXR0ZWRSZWZlcmVyIjoiIiwic3Vic2NyaWJlZEFQSXMiOlt7InN1YnNjcmliZXJUZW5hbnREb21haW4iOiJjYXJib24uc3VwZXIiLCJuYW1lIjoiQXV0aC1TZXJ2aWNlIiwiY29udGV4dCI6IlwvYXV0aC1zZXJ2aWNlXC8xLjAiLCJwdWJsaXNoZXIiOiJhZG1pbiIsInZlcnNpb24iOiIxLjAiLCJzdWJzY3JpcHRpb25UaWVyIjoiVW5saW1pdGVkIn0seyJzdWJzY3JpYmVyVGVuYW50RG9tYWluIjoiY2FyYm9uLnN1cGVyIiwibmFtZSI6IkVudGl0aWVzIiwiY29udGV4dCI6IlwvZW50aXRpZXNcLzEuMiIsInB1Ymxpc2hlciI6ImFkbWluIiwidmVyc2lvbiI6IjEuMiIsInN1YnNjcmlwdGlvblRpZXIiOiJVbmxpbWl0ZWQifSx7InN1YnNjcmliZXJUZW5hbnREb21haW4iOiJjYXJib24uc3VwZXIiLCJuYW1lIjoiRlgiLCJjb250ZXh0IjoiXC9meFwvMS4wIiwicHVibGlzaGVyIjoicHVibGlzaGVyLnVzZXIiLCJ2ZXJzaW9uIjoiMS4wIiwic3Vic2NyaXB0aW9uVGllciI6IlVubGltaXRlZCJ9LHsic3Vic2NyaWJlclRlbmFudERvbWFpbiI6ImNhcmJvbi5zdXBlciIsIm5hbWUiOiJIb3N0ZWRDaGVja291dCIsImNvbnRleHQiOiJcL2hvc3RlZGNoZWNrb3V0XC8xLjAiLCJwdWJsaXNoZXIiOiJhZG1pbiIsInZlcnNpb24iOiIxLjAiLCJzdWJzY3JpcHRpb25UaWVyIjoiVW5saW1pdGVkIn0seyJzdWJzY3JpYmVyVGVuYW50RG9tYWluIjoiY2FyYm9uLnN1cGVyIiwibmFtZSI6Ik5vdGlmaWNhdGlvbkVuZ2luZSIsImNvbnRleHQiOiJcL25vdGlmaWNhdGlvbmVuZ2luZVwvMS4wIiwicHVibGlzaGVyIjoiYWRtaW4iLCJ2ZXJzaW9uIjoiMS4wIiwic3Vic2NyaXB0aW9uVGllciI6IkJyb256ZSJ9LHsic3Vic2NyaWJlclRlbmFudERvbWFpbiI6ImNhcmJvbi5zdXBlciIsIm5hbWUiOiJPVFAtTWFuYWdlciIsImNvbnRleHQiOiJcL290cC1tYW5hZ2VyXC8xLjAiLCJwdWJsaXNoZXIiOiJhZG1pbiIsInZlcnNpb24iOiIxLjAiLCJzdWJzY3JpcHRpb25UaWVyIjoiVW5saW1pdGVkIn0seyJzdWJzY3JpYmVyVGVuYW50RG9tYWluIjoiY2FyYm9uLnN1cGVyIiwibmFtZSI6IldhbGxldHMiLCJjb250ZXh0IjoiXC93YWxsZXRzXC8xLjAiLCJwdWJsaXNoZXIiOiJwdWJsaXNoZXIudXNlciIsInZlcnNpb24iOiIxLjAiLCJzdWJzY3JpcHRpb25UaWVyIjoiVW5saW1pdGVkIn0seyJzdWJzY3JpYmVyVGVuYW50RG9tYWluIjoiY2FyYm9uLnN1cGVyIiwibmFtZSI6IkdpZnRDYXJkIiwiY29udGV4dCI6IlwvZ2lmdGNhcmRcLzEuMCIsInB1Ymxpc2hlciI6InB1Ymxpc2hlci51c2VyIiwidmVyc2lvbiI6IjEuMCIsInN1YnNjcmlwdGlvblRpZXIiOiJVbmxpbWl0ZWQifSx7InN1YnNjcmliZXJUZW5hbnREb21haW4iOiJjYXJib24uc3VwZXIiLCJuYW1lIjoiS1lDIiwiY29udGV4dCI6Ilwva3ljXC8xLjAiLCJwdWJsaXNoZXIiOiJwdWJsaXNoZXIudXNlciIsInZlcnNpb24iOiIxLjAiLCJzdWJzY3JpcHRpb25UaWVyIjoiVW5saW1pdGVkIn0seyJzdWJzY3JpYmVyVGVuYW50RG9tYWluIjoiY2FyYm9uLnN1cGVyIiwibmFtZSI6IlNlcnZpY2VQcm92aWRlcnMiLCJjb250ZXh0IjoiXC9zZXJ2aWNlcHJvdmlkZXJzXC8zLjAiLCJwdWJsaXNoZXIiOiJwdWJsaXNoZXIudXNlciIsInZlcnNpb24iOiIzLjAiLCJzdWJzY3JpcHRpb25UaWVyIjoiVW5saW1pdGVkIn0seyJzdWJzY3JpYmVyVGVuYW50RG9tYWluIjoiY2FyYm9uLnN1cGVyIiwibmFtZSI6IlNtYXJ0TGVuZGluZyIsImNvbnRleHQiOiJcL3NtYXJ0TGVuZGluZ1wvMS4wIiwicHVibGlzaGVyIjoiYWRtaW4iLCJ2ZXJzaW9uIjoiMS4wIiwic3Vic2NyaXB0aW9uVGllciI6IlVubGltaXRlZCJ9LHsic3Vic2NyaWJlclRlbmFudERvbWFpbiI6ImNhcmJvbi5zdXBlciIsIm5hbWUiOiJUcmFuc2FjdGlvbnMiLCJjb250ZXh0IjoiXC90cmFuc2FjdGlvbnNcLzEuMCIsInB1Ymxpc2hlciI6ImFkbWluIiwidmVyc2lvbiI6IjEuMCIsInN1YnNjcmlwdGlvblRpZXIiOiJCcm9uemUifSx7InN1YnNjcmliZXJUZW5hbnREb21haW4iOiJjYXJib24uc3VwZXIiLCJuYW1lIjoiVmlydHVhbC1BY2NvdW50cyIsImNvbnRleHQiOiJcL3ZpcnR1YWxhY2NvdW50c1wvMS4wIiwicHVibGlzaGVyIjoicG9ydGFsIiwidmVyc2lvbiI6IjEuMCIsInN1YnNjcmlwdGlvblRpZXIiOiJCcm9uemUifV0sInBlcm1pdHRlZElQIjoiIiwiaWF0IjoxNjk0MTE5ODA5LCJqdGkiOiI0ZDBlZTExZC00ZDI1LTRhNGItYjZlMS1lZTc4ZjI0M2UyOWYifQ==.n5zqtoibf_Van1pevU017esYUiepOrQpFDY3FnySBow6QMVRSv4YShFk2f4XGjtMsW1NVLN3_Qk-1DyKR9njQthRwh049nSM6znKkBVkV72Lb_aOwspMXbdy6s0PSpoQeiHZA3DC432RKjpE-zxZYZUFvUz-2uXT6XTDD0gSAQ0JztIiu99qyB8WX9foaWAzrrtD5cxjrlUl-FOOt4NIRIcPucNsP87d1Aez10oxr8tHYg9HfzzBx-GvuOdhvRK8NcGoJb1X14oB4CUM3d9wql7SlhNV6pfjb4-RdVj-NskUJQ2T5n-excWWxD98EVf1VwQsN0ciHptyxnLkzDgRVA==";

        $this->Username = $this->UsernameDEV;
        $this->Password = $this->PasswordDEV;
        $moneda = 'CRC';

        $TransaccionProductoMySqlDAO = new TransaccionProductoMySqlDAO();
        $Transaction = $TransaccionProductoMySqlDAO->getTransaction();
        $Transaction->getConnection()->beginTransaction();

        $TransaccionProducto = new TransaccionProducto();
        $TransaccionProducto->setProductoId($producto_id);
        $TransaccionProducto->setUsuarioId($usuario_id);
        $TransaccionProducto->setValor($valor);
        $TransaccionProducto->setEstado($estado);
        $TransaccionProducto->setTipo($tipo);
        $TransaccionProducto->setEstadoProducto($estado_producto);
        $TransaccionProducto->setMandante($mandante);
        $TransaccionProducto->setFinalId(0);
        $TransaccionProducto->setExternoId(0);
        $transproductoId = $TransaccionProductoMySqlDAO->insert($TransaccionProducto);


        $mandante = $Usuario->mandante;
        $Tipometodo = "sinpein-cr";


        $respuesta = $this->generateToken($this->URL, '/auth-service/1.0/protocol/openid-connect/token');


        $tokenT = $respuesta->access_token;

        $ConfigurationEnvironment = new ConfigurationEnvironment();

        $data = "paymentMethodTypeId%40" . $Tipometodo . "%24virtualAccountKey%40" . $tipoDoc . "%40" . $Registro->cedula;


        $uuid = vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex(openssl_random_pseudo_bytes(16)), 4));


        $Result = $this->connectionGETAccount($data, $tokenT, $uuid, $this->URL, '/virtualaccounts/1.0/virtualaccounts/');

        if ($ConfigurationEnvironment->isDevelopment()) {
            if ($Pais->iso == "CR" && $Producto->externoId == "VIRTUALACCOUNT") {
                $RefencePayment = "353839352D313030302D31303031";
            }
        } else {
            if ($Pais->iso == "CR" && $Producto->externoId == "VIRTUALACCOUNT") {
                $RefencePayment = "32373335342D313030302D31303230";
            }
        }

        //Validar si ya existe para evitar duplicados
        if ($Result->errorCode != "NOT_FOUND" || $Result->errorCode == null) {
//hacer el deposito
            $data2 = array(
                "amount" => $valor,
                "currency" => $moneda,
                "mode" => "perform",
                "debitParty" => array(
                    "type" => $Tipometodo,
                    "virtualAccountKey" => $tipoDoc . "@" . $cedula,
                    "data" => array(),
                ),
                "creditParty" => array(

                    "paymentMethodReference" => $RefencePayment
                ),
                "descriptionText" => "Payment using Order",
                "country" => $Pais->iso

            );
            $uuid2 = vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex(openssl_random_pseudo_bytes(16)), 4));

            //$data2 = json_encode($data2);
            //print_r($data2);

            $TransproductoDetalle = new TransproductoDetalle();
            $TransproductoDetalle->transproductoId = $transproductoId;
            $TransproductoDetalle->tValue = json_encode($data);
            $TransproductoDetalleMySqlDAO = new TransproductoDetalleMySqlDAO($Transaction);
            $TransproductoDetalleMySqlDAO->insert($TransproductoDetalle);

            $Result2 = $this->connectionPOST($data2, $tokenT, $uuid2, $this->URL, '/transactions/1.0/transactions/type/deposit');
            //print_r($Result2);

            if ($Result2 != '' && $Result2->transactionStatus == "waiting") {
                $TransprodLog = new TransprodLog();
                $TransprodLog->setTransproductoId($transproductoId);
                $TransprodLog->setEstado('E');
                $TransprodLog->setTipoGenera('A');
                $TransprodLog->setComentario('Envio Solicitud de deposito');
                $TransprodLog->setTValue(json_encode($Result2));
                $TransprodLog->setUsucreaId(0);
                $TransprodLog->setUsumodifId(0);

                $TransprodLogMySqlDAO = new TransprodLogMySqlDAO($Transaction);
                $TransprodLogMySqlDAO->insert($TransprodLog);

                $TransaccionProducto->setExternoId($Result2->transactionReference);
                $TransaccionProductoMySqlDAO->update($TransaccionProducto);
                $Transaction->commit();
                $useragent = $_SERVER['HTTP_USER_AGENT'];
                $jsonServer = json_encode($_SERVER);
                $serverCodif = base64_encode($jsonServer);


                $ismobile = '';

                if (preg_match('/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino/i', $useragent) || preg_match('/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i', substr($useragent, 0, 4))) {
                    $ismobile = '1';
                }
//Detect special conditions devices
                $iPod = stripos($_SERVER['HTTP_USER_AGENT'], "iPod");
                $iPhone = stripos($_SERVER['HTTP_USER_AGENT'], "iPhone");
                $iPad = stripos($_SERVER['HTTP_USER_AGENT'], "iPad");
                $Android = stripos($_SERVER['HTTP_USER_AGENT'], "Android");
                $webOS = stripos($_SERVER['HTTP_USER_AGENT'], "webOS");

//do something with this information
                if ($iPod || $iPhone) {
                    $ismobile = '1';
                } elseif ($iPad) {
                    $ismobile = '1';
                } elseif ($Android) {
                    $ismobile = '1';
                }
                //exec("php -f ". __DIR__ ."/../crm/AgregarCrm.php " . $Usuario->usuarioId . " " . "SOLICITUDDEPOSITOCRM" . " " . $transproductoId . " " . $serverCodif . " " . $ismobile . " > /dev/null &");


                // $data["url"] = $response->requiredAction->data->additionalInformation->value;

                $dataFinal = array();
                $dataFinal["success"] = true;
                $dataFinal["url"] = $Result2->instructions;
                $dataFinal["code"] = $Result2->requiredAction->data->code;
                //return json_decode(json_encode($dataFinal));
                //print_r($dataFinal);

            } else {
                $dataFinal = array();
                $dataFinal["success"] = false;
                $dataFinal["url"] = "";
                $dataFinal["code"] = $Result2->errorCode;
            }
        } else {
            $data2 = array(
                "paymentMethodTypeId" => $Tipometodo,
                "paymentMethodReference" => $RefencePayment,
                "virtualAccountKey" => $tipoDoc . "@" . $cedula,
                "data" => array(
                    "document_number" => $cedula,
                    "document_type" => "nationalId",
                    "first_name" => $Registro->nombre1,
                    "last_name" => $apellido,
                ),

            );
            $uuid2 = vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex(openssl_random_pseudo_bytes(16)), 4));

            $Result2 = $this->connectionPOST($data2, $tokenT, $uuid2, $this->URL, '/virtualaccounts/1.0/virtualaccounts');


            if ($Result2->status == "complete") {
                $data3 = array(
                    "amount" => $valor,
                    "currency" => $moneda,
                    "mode" => "perform",
                    "debitParty" => array(
                        "type" => $Tipometodo,
                        "virtualAccountKey" => $tipoDoc . "@" . $cedula,
                        "data" => array(),
                    ),
                    "creditParty" => array(
                        "paymentMethodReference" => $RefencePayment
                    ),
                    "descriptionText" => "Payment using Order",
                    "country" => $Pais->iso
                );
                $uuid2 = vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex(openssl_random_pseudo_bytes(16)), 4));

                $TransproductoDetalle = new TransproductoDetalle();
                $TransproductoDetalle->transproductoId = $transproductoId;
                $TransproductoDetalle->tValue = json_encode($data);
                $TransproductoDetalleMySqlDAO = new TransproductoDetalleMySqlDAO($Transaction);
                $TransproductoDetalleMySqlDAO->insert($TransproductoDetalle);

                $Result3 = $this->connectionPOST($data3, $tokenT, $uuid2, $this->URL, '/transactions/1.0/transactions/type/deposit');

                if ($Result3 != '' && $Result3->transactionStatus == "waiting") {
                    $TransprodLog = new TransprodLog();
                    $TransprodLog->setTransproductoId($transproductoId);
                    $TransprodLog->setEstado('E');
                    $TransprodLog->setTipoGenera('A');
                    $TransprodLog->setComentario('Envio Solicitud de deposito');
                    $TransprodLog->setTValue(json_encode($Result3));
                    $TransprodLog->setUsucreaId(0);
                    $TransprodLog->setUsumodifId(0);

                    $TransprodLogMySqlDAO = new TransprodLogMySqlDAO($Transaction);
                    $TransprodLogMySqlDAO->insert($TransprodLog);

                    $TransaccionProducto->setExternoId($Result3->transactionReference);
                    $TransaccionProductoMySqlDAO->update($TransaccionProducto);
                    $Transaction->commit();
                    $useragent = $_SERVER['HTTP_USER_AGENT'];
                    $jsonServer = json_encode($_SERVER);
                    $serverCodif = base64_encode($jsonServer);


                    $ismobile = '';

                    if (preg_match('/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino/i', $useragent) || preg_match('/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i', substr($useragent, 0, 4))) {
                        $ismobile = '1';
                    }
//Detect special conditions devices
                    $iPod = stripos($_SERVER['HTTP_USER_AGENT'], "iPod");
                    $iPhone = stripos($_SERVER['HTTP_USER_AGENT'], "iPhone");
                    $iPad = stripos($_SERVER['HTTP_USER_AGENT'], "iPad");
                    $Android = stripos($_SERVER['HTTP_USER_AGENT'], "Android");
                    $webOS = stripos($_SERVER['HTTP_USER_AGENT'], "webOS");


//do something with this information
                    if ($iPod || $iPhone) {
                        $ismobile = '1';
                    } elseif ($iPad) {
                        $ismobile = '1';
                    } elseif ($Android) {
                        $ismobile = '1';
                    }
                    //exec("php -f ". __DIR__ ."/../crm/AgregarCrm.php " . $Usuario->usuarioId . " " . "SOLICITUDDEPOSITOCRM" . " " . $transproductoId . " " . $serverCodif . " " . $ismobile . " > /dev/null &");


                    // $data["url"] = $response->requiredAction->data->additionalInformation->value;

                    $dataFinal = array();
                    $dataFinal["success"] = true;
                    $dataFinal["url"] = $Result3->instructions;
                    $dataFinal["code"] = $Result3->requiredAction->data->code;
                    //return json_decode(json_encode($dataFinal));
//print_r($dataFinal);

                }
            } else {
                $dataFinal = array();
                $dataFinal["success"] = false;
                $dataFinal["url"] = "";
                $dataFinal["code"] = $Result2->errorCode;
            }
        }
        return json_decode(json_encode($dataFinal));
    }

    /**
     * Genera un token de autenticación realizando una solicitud POST al servicio especificado.
     *
     * @param string $url  La URL base del servicio de autenticación.
     * @param string $path El endpoint para la generación del token.
     *
     * @return object|null El objeto JSON decodificado que contiene el token, o null si la solicitud falla.
     */
    public function generateToken($url, $path)
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $url . $path,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => 'grant_type=password&username=' . $this->Username . '&password=' . $this->Password,
            CURLOPT_HTTPHEADER => array(
                'apikey: ' . $this->ApiKey,
                'Content-Type: application/x-www-form-urlencoded'
            ),
        ));

        $response = curl_exec($curl);
        syslog(LOG_WARNING, "INSWITCH TOKEN DATA " . ($this->ApiKey) . ' ' . ($this->Username) . ' ' . ($this->Password));
        syslog(LOG_WARNING, "INSWITCH TOKEN RESPONSE " . ($response));

        curl_close($curl);
        return json_decode($response);
    }

    /**
     * Realiza una conexión HTTP POST utilizando cURL.
     *
     * @param array  $data  Datos que se enviarán en el cuerpo de la solicitud.
     * @param string $token Token de autenticación para la solicitud.
     * @param string $uuid  Identificador único para correlación de la solicitud.
     * @param string $url   URL base del servicio al que se realizará la conexión.
     * @param string $path  Endpoint específico del servicio.
     *
     * @return object|null   Respuesta decodificada en formato JSON o null si la solicitud falla.
     */
    public function connection($data, $token, $uuid, $url, $path)
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $url . $path,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => json_encode($data),
            CURLOPT_HTTPHEADER => array(
                'X-CorrelationID: ' . $uuid,
                'apikey: ' . $this->ApiKey,
                'X-User-Bearer: Bearer ' . $token,
                'Content-Type: application/json',
                'X-Callback-URL: ' . $this->callback_url,
                'X-Redirect-OK: ' . $this->success_url,
                'X-Redirect-Error: ' . $this->fail_url
            ),
        ));
        syslog(LOG_WARNING, "INSWITCH DATA " . json_encode($data));

        $response = curl_exec($curl);
        syslog(LOG_WARNING, "INSWITCH RESPONSE " . ($response));

        curl_close($curl);
        return json_decode($response);
    }

    /**
     * Realiza una conexión HTTP POST utilizando cURL.
     *
     * @param array  $data  Datos que se enviarán en el cuerpo de la solicitud.
     * @param string $token Token de autenticación para la solicitud.
     * @param string $uuid  Identificador único para correlación de la solicitud.
     * @param string $url   URL base del servicio al que se realizará la conexión.
     * @param string $path  Endpoint específico del servicio.
     *
     * @return object|null Respuesta decodificada en formato JSON o null si la solicitud falla.
     */
    public function connectionPOST($data, $token, $uuid, $url, $path)
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $url . $path,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => json_encode($data),
            CURLOPT_HTTPHEADER => array(
                'X-CorrelationID: ' . $uuid,
                'apikey: ' . $this->ApiKey,
                'X-User-Bearer: Bearer' . $token,
                'Content-Type: application/json',
                'X-Callback-URL: ' . $this->callback_url,
                'X-Channel: WS',
                'X-Redirect-OK: ' . $this->success_url,
                'X-Redirect-Error: ' . $this->fail_url
            ),
        ));
        $time = time();
        syslog(LOG_WARNING, " INSWITCH DATA " . $time . ' ' . json_encode($data));


        $response = curl_exec($curl);

        syslog(LOG_WARNING, " INSWITCH RESPONSE " . $time . ' ' . $response);

        $httpcode = curl_getinfo($curl, CURLINFO_HTTP_CODE);

        curl_close($curl);
        return json_decode($response);
    }

    /**
     * Realiza una conexión HTTP GET utilizando cURL.
     *
     * @param string $data  Parámetros de consulta que se enviarán en la URL.
     * @param string $token Token de autenticación para la solicitud.
     * @param string $uuid  Identificador único para correlación de la solicitud.
     * @param string $url   URL base del servicio al que se realizará la conexión.
     * @param string $path  Endpoint específico del servicio.
     *
     * @return object|null Respuesta decodificada en formato JSON o null si la solicitud falla.
     */
    public function connectionGET($data, $token, $uuid, $url, $path)
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $url . $path . "?" . $data,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 300,

            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            //CURLOPT_POSTFIELDS =>json_encode($data),
            CURLOPT_HTTPHEADER => array(
                'X-CorrelationID: ' . $uuid,
                'apikey: ' . $this->ApiKey,
                'X-User-Bearer: ' . $token,
                'Content-Type: application/json'
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        return json_decode($response);
    }

    /**
     * Realiza una conexión HTTP GET utilizando cURL para obtener información de cuentas virtuales.
     *
     * @param string $data  Parámetros de consulta que se enviarán en la URL.
     * @param string $token Token de autenticación para la solicitud.
     * @param string $uuid  Identificador único para correlación de la solicitud.
     * @param string $url   URL base del servicio al que se realizará la conexión.
     * @param string $path  Endpoint específico del servicio.
     *
     * @return object|null Respuesta decodificada en formato JSON o null si la solicitud falla.
     */
    public function connectionGETAccount($data, $token, $uuid, $url, $path)
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $url . $path . $data,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            //CURLOPT_POSTFIELDS =>json_encode($data),
            CURLOPT_HTTPHEADER => array(
                'X-CorrelationID: ' . $uuid,
                'apikey: ' . $this->ApiKey,
                'X-User-Bearer: Bearer ' . $token,
                'Content-Type: application/json'
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        return json_decode($response);
    }

    /**
     * Realiza una conexión HTTP PUT utilizando cURL.
     *
     * @param array  $data  Datos que se enviarán en el cuerpo de la solicitud.
     * @param string $token Token de autenticación para la solicitud.
     * @param string $uuid  Identificador único para correlación de la solicitud.
     * @param string $url   URL base del servicio al que se realizará la conexión.
     * @param string $path  Endpoint específico del servicio.
     *
     * @return object|null Respuesta decodificada en formato JSON o null si la solicitud falla.
     */
    public function connectionPUT($data, $token, $uuid, $url, $path)
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $url . $path,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'PUT',
            //CURLOPT_POSTFIELDS =>json_encode($data),
            CURLOPT_HTTPHEADER => array(
                'X-CorrelationID: ' . $uuid,
                'apikey: ' . $this->ApiKey,
                'X-User-Bearer: ' . $token,
                'Content-Type: application/json'
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        return json_decode($response);
    }


}