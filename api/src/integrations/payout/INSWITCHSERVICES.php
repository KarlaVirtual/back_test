<?php

/**
 * Clase que proporciona servicios relacionados con el proveedor INSWITCH.
 *
 * @category Red
 * @package  API
 * @author   Desconocido
 * @version  1.0.0
 * @since    2025-04-19
 */

namespace Backend\integrations\payout;

use Backend\dto\Mandante;
use Exception;
use Backend\dto\Banco;
use Backend\dto\ConfigurationEnvironment;
use Backend\dto\CuentaCobro;
use Backend\dto\Pais;
use Backend\dto\Producto;
use Backend\dto\ProductoMandante;
use Backend\dto\Registro;
use Backend\dto\UsuarioOtrainfo;
use Backend\dto\Subproveedor;
use Backend\dto\SubproveedorMandante;
use Backend\dto\TransaccionProducto;
use Backend\dto\TransprodLog;
use Backend\dto\Usuario;
use Backend\dto\UsuarioBanco;
use Backend\mysql\TransaccionProductoMySqlDAO;
use Backend\mysql\TransprodLogMySqlDAO;

/**
 * Clase que proporciona servicios relacionados con el proveedor INSWITCH.
 *
 * Esta clase incluye métodos para realizar operaciones como retiros (cash out),
 * generación de tokens, y conexión con los servicios del proveedor.
 */
class INSWITCHSERVICES
{

    /**
     * La URL base del servicio.
     *
     * @var string
     */
    private $URL = "";

    /**
     * La URL de desarrollo del servicio.
     *
     * @var string
     */
    private $URLDEV = 'https://gateway-am.apps.ins.inswhub.com';

    /**
     * La URL de producción del servicio.
     *
     * @var string
     */
    private $URLPROD = 'https://gateway-am.apps.ins.inswhub.com';

    /**
     * La URL de confirmación del servicio.
     *
     * @var string
     */
    private $callback_url = "";

    /**
     * La URL de confirmación del servicio en desarrollo.
     *
     * @var string
     */
    private $callback_urlDEV = "https://apidev.virtualsoft.tech/integrations/payout/inswitch/confirm/";

    /**
     * La URL de confirmación del servicio en producción.
     *
     * @var string
     */
    private $callback_urlPROD = "https://integrations.virtualsoft.tech/payout/inswitch/confirm/";

    /**
     * La URL de depósito del servicio.
     *
     * @var string
     */
    private $URLDEPOSIT = '';

    /**
     * La URL de depósito del servicio en desarrollo.
     *
     * @var string
     */
    private $URLDEPOSITDEV = "https://devfrontend.virtualsoft.tech/lotosports/gestion/cuenta_cobro";

    /**
     * La URL de depósito del servicio en producción.
     *
     * @var string
     */
    private $URLDEPOSITPROD = "https://lotosports.bet/gestion/cuenta_cobro";


    /**
     * La URL de consulta de saldo del servicio.
     *
     * @var string
     */
    private $ApiKey = "";

    /**
     * La URL de consulta de saldo del servicio en desarrollo.
     *
     * @var string
     */
    private $Password = "";

    /**
     * La URL de consulta de saldo del servicio en desarrollo.
     *
     * @var string
     */
    private $Username = "";

    /**
     * La URL de consulta de saldo del servicio en producción D.Brasil.
     *
     * @var string
     */
    private $ApiKeyDBR = "";

    /**
     * La URL de consulta de saldo del servicio en producción D.Brasil.
     *
     * @var string
     */
    private $PasswordDBR = "";

    /**
     * La URL de consulta de saldo del servicio en producción D.Brasil.
     *
     * @var string
     */
    private $UsernameDBR = "";

    /**
     * La URL de consulta de saldo del servicio en desarrollo D.Salvador.
     *
     * @var string
     */
    private $ApiKeySV = "";

    /**
     * La URL de consulta de saldo del servicio en desarrollo D.Salvador.
     *
     * @var string
     */
    private $PasswordSV = "";

    /**
     * La URL de consulta de saldo del servicio en desarrollo D.Salvador.
     *
     * @var string
     */
    private $UsernameSV = "";

    /**
     * La URL de consulta de saldo del servicio en desarrollo D.Ecuador.
     *
     * @var string
     */
    private $ApiKeyEC = "";

    /**
     * La URL de consulta de saldo del servicio en desarrollo D.Ecuador.
     *
     * @var string
     */
    private $PasswordEC = "";

    /**
     * La URL de consulta de saldo del servicio en desarrollo D.Ecuador.
     *
     * @var string
     */
    private $UsernameEC = "";

    /**
     * La URL de consulta de saldo del servicio en desarrollo D.Costa Rica.
     *
     * @var string
     */
    private $ApiKeyCR = "";

    /**
     * La URL de consulta de saldo del servicio en desarrollo D.Costa Rica.
     *
     * @var string
     */
    private $PasswordCR = "";

    /**
     * La URL de consulta de saldo del servicio en desarrollo D.Costa Rica.
     *
     * @var string
     */
    private $UsernameCR = "";

    /**
     * La URL de consulta de saldo del servicio en desarrollo.
     *
     * @var string
     */
    private $ApiKeyDEV = "eyJ4NXQiOiJNV1ExTWpBMlpESm1PV1U1WXpjNFpUazFZelk1T1dVeU56SmtaV1l5TWpZNE5qa3pZVFkyWXpjNE9EY3lZMlprWmpGaVpHUmhNMkkyTUdFeU5qRmpaZyIsImtpZCI6Im9rZGF3cyIsInR5cCI6IkpXVCIsImFsZyI6IlJTMjU2In0=.eyJzdWIiOiJkb3JhZG9iZXRkZXZAY2FyYm9uLnN1cGVyIiwiYXBwbGljYXRpb24iOnsib3duZXIiOiJkb3JhZG9iZXRkZXYiLCJ0aWVyUXVvdGFUeXBlIjpudWxsLCJ0aWVyIjoiVW5saW1pdGVkIiwibmFtZSI6IkRlZmF1bHRBcHBsaWNhdGlvbiIsImlkIjo5OSwidXVpZCI6ImYyNDEwYzcwLTE1NzctNDA0NS1iYjg5LTk1NDMyNTEyOWE0MCJ9LCJpc3MiOiJodHRwczpcL1wvYXBpbS1tYW5hZ2VtZW50LmFwcHMuaW5zLmluc3dodWIuY29tOjQ0M1wvb2F1dGgyXC90b2tlbiIsInRpZXJJbmZvIjp7IkJyb256ZSI6eyJ0aWVyUXVvdGFUeXBlIjoicmVxdWVzdENvdW50IiwiZ3JhcGhRTE1heENvbXBsZXhpdHkiOjAsImdyYXBoUUxNYXhEZXB0aCI6MCwic3RvcE9uUXVvdGFSZWFjaCI6dHJ1ZSwic3Bpa2VBcnJlc3RMaW1pdCI6MCwic3Bpa2VBcnJlc3RVbml0IjpudWxsfSwiVW5saW1pdGVkIjp7InRpZXJRdW90YVR5cGUiOiJyZXF1ZXN0Q291bnQiLCJncmFwaFFMTWF4Q29tcGxleGl0eSI6MCwiZ3JhcGhRTE1heERlcHRoIjowLCJzdG9wT25RdW90YVJlYWNoIjp0cnVlLCJzcGlrZUFycmVzdExpbWl0IjowLCJzcGlrZUFycmVzdFVuaXQiOm51bGx9fSwia2V5dHlwZSI6IlNBTkRCT1giLCJwZXJtaXR0ZWRSZWZlcmVyIjoiIiwic3Vic2NyaWJlZEFQSXMiOlt7InN1YnNjcmliZXJUZW5hbnREb21haW4iOiJjYXJib24uc3VwZXIiLCJuYW1lIjoiTm90aWZpY2F0aW9uRW5naW5lIiwiY29udGV4dCI6Ilwvbm90aWZpY2F0aW9uZW5naW5lXC8xLjAiLCJwdWJsaXNoZXIiOiJhZG1pbiIsInZlcnNpb24iOiIxLjAiLCJzdWJzY3JpcHRpb25UaWVyIjoiQnJvbnplIn0seyJzdWJzY3JpYmVyVGVuYW50RG9tYWluIjoiY2FyYm9uLnN1cGVyIiwibmFtZSI6IkF1dGgtU2VydmljZSIsImNvbnRleHQiOiJcL2F1dGgtc2VydmljZVwvMS4wIiwicHVibGlzaGVyIjoiYWRtaW4iLCJ2ZXJzaW9uIjoiMS4wIiwic3Vic2NyaXB0aW9uVGllciI6IlVubGltaXRlZCJ9LHsic3Vic2NyaWJlclRlbmFudERvbWFpbiI6ImNhcmJvbi5zdXBlciIsIm5hbWUiOiJGWCIsImNvbnRleHQiOiJcL2Z4XC8xLjAiLCJwdWJsaXNoZXIiOiJwdWJsaXNoZXIudXNlciIsInZlcnNpb24iOiIxLjAiLCJzdWJzY3JpcHRpb25UaWVyIjoiVW5saW1pdGVkIn0seyJzdWJzY3JpYmVyVGVuYW50RG9tYWluIjoiY2FyYm9uLnN1cGVyIiwibmFtZSI6Ikhvc3RlZENoZWNrb3V0IiwiY29udGV4dCI6IlwvaG9zdGVkY2hlY2tvdXRcLzEuMCIsInB1Ymxpc2hlciI6ImFkbWluIiwidmVyc2lvbiI6IjEuMCIsInN1YnNjcmlwdGlvblRpZXIiOiJVbmxpbWl0ZWQifSx7InN1YnNjcmliZXJUZW5hbnREb21haW4iOiJjYXJib24uc3VwZXIiLCJuYW1lIjoiVHJhbnNhY3Rpb25zIiwiY29udGV4dCI6IlwvdHJhbnNhY3Rpb25zXC8xLjAiLCJwdWJsaXNoZXIiOiJhZG1pbiIsInZlcnNpb24iOiIxLjAiLCJzdWJzY3JpcHRpb25UaWVyIjoiVW5saW1pdGVkIn0seyJzdWJzY3JpYmVyVGVuYW50RG9tYWluIjoiY2FyYm9uLnN1cGVyIiwibmFtZSI6IldhbGxldHMiLCJjb250ZXh0IjoiXC93YWxsZXRzXC8xLjAiLCJwdWJsaXNoZXIiOiJwdWJsaXNoZXIudXNlciIsInZlcnNpb24iOiIxLjAiLCJzdWJzY3JpcHRpb25UaWVyIjoiVW5saW1pdGVkIn0seyJzdWJzY3JpYmVyVGVuYW50RG9tYWluIjoiY2FyYm9uLnN1cGVyIiwibmFtZSI6IlBheW1lbnQtTWV0aG9kcyIsImNvbnRleHQiOiJcL3BheW1lbnQtbWV0aG9kc1wvMS4wIiwicHVibGlzaGVyIjoiYWRtaW4iLCJ2ZXJzaW9uIjoiMS4wIiwic3Vic2NyaXB0aW9uVGllciI6IlVubGltaXRlZCJ9LHsic3Vic2NyaWJlclRlbmFudERvbWFpbiI6ImNhcmJvbi5zdXBlciIsIm5hbWUiOiJHaWZ0Q2FyZCIsImNvbnRleHQiOiJcL2dpZnRjYXJkXC8xLjAiLCJwdWJsaXNoZXIiOiJwdWJsaXNoZXIudXNlciIsInZlcnNpb24iOiIxLjAiLCJzdWJzY3JpcHRpb25UaWVyIjoiVW5saW1pdGVkIn0seyJzdWJzY3JpYmVyVGVuYW50RG9tYWluIjoiY2FyYm9uLnN1cGVyIiwibmFtZSI6IktZQyIsImNvbnRleHQiOiJcL2t5Y1wvMS4wIiwicHVibGlzaGVyIjoicHVibGlzaGVyLnVzZXIiLCJ2ZXJzaW9uIjoiMS4wIiwic3Vic2NyaXB0aW9uVGllciI6IlVubGltaXRlZCJ9LHsic3Vic2NyaWJlclRlbmFudERvbWFpbiI6ImNhcmJvbi5zdXBlciIsIm5hbWUiOiJTZXJ2aWNlUHJvdmlkZXJzIiwiY29udGV4dCI6Ilwvc2VydmljZXByb3ZpZGVyc1wvMy4wIiwicHVibGlzaGVyIjoicHVibGlzaGVyLnVzZXIiLCJ2ZXJzaW9uIjoiMy4wIiwic3Vic2NyaXB0aW9uVGllciI6IlVubGltaXRlZCJ9LHsic3Vic2NyaWJlclRlbmFudERvbWFpbiI6ImNhcmJvbi5zdXBlciIsIm5hbWUiOiJTbWFydExlbmRpbmciLCJjb250ZXh0IjoiXC9zbWFydExlbmRpbmdcLzEuMCIsInB1Ymxpc2hlciI6ImFkbWluIiwidmVyc2lvbiI6IjEuMCIsInN1YnNjcmlwdGlvblRpZXIiOiJVbmxpbWl0ZWQifSx7InN1YnNjcmliZXJUZW5hbnREb21haW4iOiJjYXJib24uc3VwZXIiLCJuYW1lIjoiT1RQLU1hbmFnZXIiLCJjb250ZXh0IjoiXC9vdHAtbWFuYWdlclwvMS4wIiwicHVibGlzaGVyIjoiYWRtaW4iLCJ2ZXJzaW9uIjoiMS4wIiwic3Vic2NyaXB0aW9uVGllciI6IlVubGltaXRlZCJ9LHsic3Vic2NyaWJlclRlbmFudERvbWFpbiI6ImNhcmJvbi5zdXBlciIsIm5hbWUiOiJFbnRpdGllcyIsImNvbnRleHQiOiJcL2VudGl0aWVzXC8xLjIiLCJwdWJsaXNoZXIiOiJhZG1pbiIsInZlcnNpb24iOiIxLjIiLCJzdWJzY3JpcHRpb25UaWVyIjoiVW5saW1pdGVkIn1dLCJwZXJtaXR0ZWRJUCI6IiIsImlhdCI6MTY0NTEzMTIyNiwianRpIjoiMTk3MDExZWMtMmY3My00NDc5LTg0OTAtZjk1NzE3ZDVhMDA5In0=.gCJa_x_dzm0_hmAolBw1J-SHGLzUsMrAw8A9C_X1G94fQb7rJliJzGpxNBTufAzAM6_4UwXDvKSyigF-KSzyghtPhoQh4VDI0kgxJYtf1Lbz0trNHf30VRd6rPyL3zMRzKlwktC7x4mwG00Oq9XE8k3bexgjBh0c93AxD53elKtgmlzXe7imHD7Wl0rBWC4Nh_0ZL1oMSqggFqeOD6LuUg9z33-sbmS1kWiiqbfBChTcaTZdKT78fylqfhlECeJ8tLTbIIlrrwfunk9-bYMoHwSC9BZKhEXHKMM8OhRKQ49ckG7pRMZIpmbQSX8vFDOQh-zvHXbIHPPaEgF4IWaEow==";

    /**
     * La URL de consulta de saldo del servicio en desarrollo.
     *
     * @var string
     */
    private $PasswordDEV = "Inswitch@2022";

    /**
     * La URL de consulta de saldo del servicio en desarrollo.
     *
     * @var string
     */
    private $UsernameDEV = "Doradobetdev";

    /**
     * La URL de consulta de saldo del servicio en producción D.Peru.
     *
     * @var string
     */
    private $ApiKeyPRO = "eyJ4NXQiOiJNV1ExTWpBMlpESm1PV1U1WXpjNFpUazFZelk1T1dVeU56SmtaV1l5TWpZNE5qa3pZVFkyWXpjNE9EY3lZMlprWmpGaVpHUmhNMkkyTUdFeU5qRmpaZyIsImtpZCI6Im9rZGF3cyIsInR5cCI6IkpXVCIsImFsZyI6IlJTMjU2In0=.eyJzdWIiOiJkb3JhZG9iZXRkZXZwZUBjYXJib24uc3VwZXIiLCJhcHBsaWNhdGlvbiI6eyJvd25lciI6ImRvcmFkb2JldGRldnBlIiwidGllclF1b3RhVHlwZSI6bnVsbCwidGllciI6IlVubGltaXRlZCIsIm5hbWUiOiJEZWZhdWx0QXBwbGljYXRpb24iLCJpZCI6MTI3LCJ1dWlkIjoiOTBkZGNlODItZDEyYS00Yzk5LTg4ZTctYzgzN2JlMDAzM2VlIn0sImlzcyI6Imh0dHBzOlwvXC9hcGltLW1hbmFnZW1lbnQuYXBwcy5pbnMuaW5zd2h1Yi5jb206NDQzXC9vYXV0aDJcL3Rva2VuIiwidGllckluZm8iOnsiQnJvbnplIjp7InRpZXJRdW90YVR5cGUiOiJyZXF1ZXN0Q291bnQiLCJncmFwaFFMTWF4Q29tcGxleGl0eSI6MCwiZ3JhcGhRTE1heERlcHRoIjowLCJzdG9wT25RdW90YVJlYWNoIjp0cnVlLCJzcGlrZUFycmVzdExpbWl0IjowLCJzcGlrZUFycmVzdFVuaXQiOm51bGx9LCJHb2xkIjp7InRpZXJRdW90YVR5cGUiOiJyZXF1ZXN0Q291bnQiLCJncmFwaFFMTWF4Q29tcGxleGl0eSI6MCwiZ3JhcGhRTE1heERlcHRoIjowLCJzdG9wT25RdW90YVJlYWNoIjp0cnVlLCJzcGlrZUFycmVzdExpbWl0IjowLCJzcGlrZUFycmVzdFVuaXQiOm51bGx9LCJVbmxpbWl0ZWQiOnsidGllclF1b3RhVHlwZSI6InJlcXVlc3RDb3VudCIsImdyYXBoUUxNYXhDb21wbGV4aXR5IjowLCJncmFwaFFMTWF4RGVwdGgiOjAsInN0b3BPblF1b3RhUmVhY2giOnRydWUsInNwaWtlQXJyZXN0TGltaXQiOjAsInNwaWtlQXJyZXN0VW5pdCI6bnVsbH19LCJrZXl0eXBlIjoiUFJPRFVDVElPTiIsInBlcm1pdHRlZFJlZmVyZXIiOiIiLCJzdWJzY3JpYmVkQVBJcyI6W3sic3Vic2NyaWJlclRlbmFudERvbWFpbiI6ImNhcmJvbi5zdXBlciIsIm5hbWUiOiJBdXRoLVNlcnZpY2UiLCJjb250ZXh0IjoiXC9hdXRoLXNlcnZpY2VcLzEuMCIsInB1Ymxpc2hlciI6ImFkbWluIiwidmVyc2lvbiI6IjEuMCIsInN1YnNjcmlwdGlvblRpZXIiOiJVbmxpbWl0ZWQifSx7InN1YnNjcmliZXJUZW5hbnREb21haW4iOiJjYXJib24uc3VwZXIiLCJuYW1lIjoiVHJhbnNhY3Rpb25zIiwiY29udGV4dCI6IlwvdHJhbnNhY3Rpb25zXC8xLjAiLCJwdWJsaXNoZXIiOiJhZG1pbiIsInZlcnNpb24iOiIxLjAiLCJzdWJzY3JpcHRpb25UaWVyIjoiVW5saW1pdGVkIn0seyJzdWJzY3JpYmVyVGVuYW50RG9tYWluIjoiY2FyYm9uLnN1cGVyIiwibmFtZSI6Ikhvc3RlZENoZWNrb3V0IiwiY29udGV4dCI6IlwvaG9zdGVkY2hlY2tvdXRcLzEuMCIsInB1Ymxpc2hlciI6ImFkbWluIiwidmVyc2lvbiI6IjEuMCIsInN1YnNjcmlwdGlvblRpZXIiOiJVbmxpbWl0ZWQifSx7InN1YnNjcmliZXJUZW5hbnREb21haW4iOiJjYXJib24uc3VwZXIiLCJuYW1lIjoiV2FsbGV0cyIsImNvbnRleHQiOiJcL3dhbGxldHNcLzEuMCIsInB1Ymxpc2hlciI6InB1Ymxpc2hlci51c2VyIiwidmVyc2lvbiI6IjEuMCIsInN1YnNjcmlwdGlvblRpZXIiOiJVbmxpbWl0ZWQifSx7InN1YnNjcmliZXJUZW5hbnREb21haW4iOiJjYXJib24uc3VwZXIiLCJuYW1lIjoiRlgiLCJjb250ZXh0IjoiXC9meFwvMS4wIiwicHVibGlzaGVyIjoicHVibGlzaGVyLnVzZXIiLCJ2ZXJzaW9uIjoiMS4wIiwic3Vic2NyaXB0aW9uVGllciI6IlVubGltaXRlZCJ9LHsic3Vic2NyaWJlclRlbmFudERvbWFpbiI6ImNhcmJvbi5zdXBlciIsIm5hbWUiOiJTZXJ2aWNlUHJvdmlkZXJzIiwiY29udGV4dCI6Ilwvc2VydmljZXByb3ZpZGVyc1wvMy4wIiwicHVibGlzaGVyIjoicHVibGlzaGVyLnVzZXIiLCJ2ZXJzaW9uIjoiMy4wIiwic3Vic2NyaXB0aW9uVGllciI6IlVubGltaXRlZCJ9LHsic3Vic2NyaWJlclRlbmFudERvbWFpbiI6ImNhcmJvbi5zdXBlciIsIm5hbWUiOiJPVFAtTWFuYWdlciIsImNvbnRleHQiOiJcL290cC1tYW5hZ2VyXC8xLjAiLCJwdWJsaXNoZXIiOiJhZG1pbiIsInZlcnNpb24iOiIxLjAiLCJzdWJzY3JpcHRpb25UaWVyIjoiQnJvbnplIn0seyJzdWJzY3JpYmVyVGVuYW50RG9tYWluIjoiY2FyYm9uLnN1cGVyIiwibmFtZSI6IkVudGl0aWVzIiwiY29udGV4dCI6IlwvZW50aXRpZXNcLzEuMiIsInB1Ymxpc2hlciI6ImFkbWluIiwidmVyc2lvbiI6IjEuMiIsInN1YnNjcmlwdGlvblRpZXIiOiJCcm9uemUifSx7InN1YnNjcmliZXJUZW5hbnREb21haW4iOiJjYXJib24uc3VwZXIiLCJuYW1lIjoiS1lDIiwiY29udGV4dCI6Ilwva3ljXC8xLjAiLCJwdWJsaXNoZXIiOiJwdWJsaXNoZXIudXNlciIsInZlcnNpb24iOiIxLjAiLCJzdWJzY3JpcHRpb25UaWVyIjoiVW5saW1pdGVkIn0seyJzdWJzY3JpYmVyVGVuYW50RG9tYWluIjoiY2FyYm9uLnN1cGVyIiwibmFtZSI6Ik5vdGlmaWNhdGlvbkVuZ2luZSIsImNvbnRleHQiOiJcL25vdGlmaWNhdGlvbmVuZ2luZVwvMS4wIiwicHVibGlzaGVyIjoiYWRtaW4iLCJ2ZXJzaW9uIjoiMS4wIiwic3Vic2NyaXB0aW9uVGllciI6IkdvbGQifSx7InN1YnNjcmliZXJUZW5hbnREb21haW4iOiJjYXJib24uc3VwZXIiLCJuYW1lIjoiUGF5bWVudC1NZXRob2RzIiwiY29udGV4dCI6IlwvcGF5bWVudC1tZXRob2RzXC8xLjAiLCJwdWJsaXNoZXIiOiJhZG1pbiIsInZlcnNpb24iOiIxLjAiLCJzdWJzY3JpcHRpb25UaWVyIjoiQnJvbnplIn1dLCJwZXJtaXR0ZWRJUCI6IiIsImlhdCI6MTY1MDMyMTQzNywianRpIjoiNzIzZjIzNjAtNjA4OS00OTAxLThiMzAtMmNkMTcwNzJjYTE4In0=.tnkUhEUoAq4oHa0jm6vsce1oPcIhSPAZEjOoCV17K6Y6c43MRkwL0ix6eyCfAlqAkqjdTqCgdU79nM7sp2ZrfujeJgLkZiEqztWmSk4f-owGAgoCfSzRlS7BPuTFWFO66J1lTYnZZ_xijsNX4h9hkM4mwZrlIRVD0GP6psQLEweXFeM-uuBgAtHqnz7yQZWAzDdtDdnUSCAeomDEuNrQgy4qRAkOvcSG3ytf21o_EjxHK7KA-gpVxCodUmQ-EuAQTPBFeGv-kLq7NjDlZfYfbxHMeBG9qhOqWkDDBki8qbtS-hXRyurRZ12AV-3TdCn-WU8X0QXfWwfttNn4uAjFRw==";

    /**
     * La URL de consulta de saldo del servicio en producción D.Peru.
     *
     * @var string
     */
    private $PasswordPRO = "44KUvw_&y*G0";

    /**
     * La URL de consulta de saldo del servicio en producción D.Peru.
     *
     * @var string
     */
    private $UsernamePRO = "doradobetdevperu";

    /**
     * La URL de consulta de saldo del servicio en producción D.Brasil.
     *
     * @var string
     */
    private $ApiKeyPRODBR = "eyJ4NXQiOiJNV1ExTWpBMlpESm1PV1U1WXpjNFpUazFZelk1T1dVeU56SmtaV1l5TWpZNE5qa3pZVFkyWXpjNE9EY3lZMlprWmpGaVpHUmhNMkkyTUdFeU5qRmpaZyIsImtpZCI6Im9rZGF3cyIsInR5cCI6IkpXVCIsImFsZyI6IlJTMjU2In0=.eyJzdWIiOiJkb3JhZG9iZXRkZXZickBjYXJib24uc3VwZXIiLCJhcHBsaWNhdGlvbiI6eyJvd25lciI6ImRvcmFkb2JldGRldmJyIiwidGllclF1b3RhVHlwZSI6bnVsbCwidGllciI6IlVubGltaXRlZCIsIm5hbWUiOiJEZWZhdWx0QXBwbGljYXRpb24iLCJpZCI6Mjk5LCJ1dWlkIjoiODBiNDkyYzEtOWRlZS00NzAxLTgzOTAtZDRjYmZmNTczNjYzIn0sImlzcyI6Imh0dHBzOlwvXC9hcGltLW1hbmFnZW1lbnQuYXBwcy5pbnMuaW5zd2h1Yi5jb206NDQzXC9vYXV0aDJcL3Rva2VuIiwidGllckluZm8iOnsiQnJvbnplIjp7InRpZXJRdW90YVR5cGUiOiJyZXF1ZXN0Q291bnQiLCJncmFwaFFMTWF4Q29tcGxleGl0eSI6MCwiZ3JhcGhRTE1heERlcHRoIjowLCJzdG9wT25RdW90YVJlYWNoIjp0cnVlLCJzcGlrZUFycmVzdExpbWl0IjowLCJzcGlrZUFycmVzdFVuaXQiOm51bGx9LCJVbmxpbWl0ZWQiOnsidGllclF1b3RhVHlwZSI6InJlcXVlc3RDb3VudCIsImdyYXBoUUxNYXhDb21wbGV4aXR5IjowLCJncmFwaFFMTWF4RGVwdGgiOjAsInN0b3BPblF1b3RhUmVhY2giOnRydWUsInNwaWtlQXJyZXN0TGltaXQiOjAsInNwaWtlQXJyZXN0VW5pdCI6bnVsbH19LCJrZXl0eXBlIjoiUFJPRFVDVElPTiIsInBlcm1pdHRlZFJlZmVyZXIiOiIiLCJzdWJzY3JpYmVkQVBJcyI6W3sic3Vic2NyaWJlclRlbmFudERvbWFpbiI6ImNhcmJvbi5zdXBlciIsIm5hbWUiOiJBdXRoLVNlcnZpY2UiLCJjb250ZXh0IjoiXC9hdXRoLXNlcnZpY2VcLzEuMCIsInB1Ymxpc2hlciI6ImFkbWluIiwidmVyc2lvbiI6IjEuMCIsInN1YnNjcmlwdGlvblRpZXIiOiJCcm9uemUifSx7InN1YnNjcmliZXJUZW5hbnREb21haW4iOiJjYXJib24uc3VwZXIiLCJuYW1lIjoiRW50aXRpZXMiLCJjb250ZXh0IjoiXC9lbnRpdGllc1wvMS4yIiwicHVibGlzaGVyIjoiYWRtaW4iLCJ2ZXJzaW9uIjoiMS4yIiwic3Vic2NyaXB0aW9uVGllciI6IkJyb256ZSJ9LHsic3Vic2NyaWJlclRlbmFudERvbWFpbiI6ImNhcmJvbi5zdXBlciIsIm5hbWUiOiJGWCIsImNvbnRleHQiOiJcL2Z4XC8xLjAiLCJwdWJsaXNoZXIiOiJwdWJsaXNoZXIudXNlciIsInZlcnNpb24iOiIxLjAiLCJzdWJzY3JpcHRpb25UaWVyIjoiVW5saW1pdGVkIn0seyJzdWJzY3JpYmVyVGVuYW50RG9tYWluIjoiY2FyYm9uLnN1cGVyIiwibmFtZSI6Ikhvc3RlZENoZWNrb3V0IiwiY29udGV4dCI6IlwvaG9zdGVkY2hlY2tvdXRcLzEuMCIsInB1Ymxpc2hlciI6ImFkbWluIiwidmVyc2lvbiI6IjEuMCIsInN1YnNjcmlwdGlvblRpZXIiOiJVbmxpbWl0ZWQifSx7InN1YnNjcmliZXJUZW5hbnREb21haW4iOiJjYXJib24uc3VwZXIiLCJuYW1lIjoiS1lDIiwiY29udGV4dCI6Ilwva3ljXC8xLjAiLCJwdWJsaXNoZXIiOiJwdWJsaXNoZXIudXNlciIsInZlcnNpb24iOiIxLjAiLCJzdWJzY3JpcHRpb25UaWVyIjoiVW5saW1pdGVkIn0seyJzdWJzY3JpYmVyVGVuYW50RG9tYWluIjoiY2FyYm9uLnN1cGVyIiwibmFtZSI6Ik5vdGlmaWNhdGlvbkVuZ2luZSIsImNvbnRleHQiOiJcL25vdGlmaWNhdGlvbmVuZ2luZVwvMS4wIiwicHVibGlzaGVyIjoiYWRtaW4iLCJ2ZXJzaW9uIjoiMS4wIiwic3Vic2NyaXB0aW9uVGllciI6IkJyb256ZSJ9LHsic3Vic2NyaWJlclRlbmFudERvbWFpbiI6ImNhcmJvbi5zdXBlciIsIm5hbWUiOiJQYXltZW50LU1ldGhvZHMiLCJjb250ZXh0IjoiXC9wYXltZW50LW1ldGhvZHNcLzEuMCIsInB1Ymxpc2hlciI6InBvcnRhbCIsInZlcnNpb24iOiIxLjAiLCJzdWJzY3JpcHRpb25UaWVyIjoiQnJvbnplIn0seyJzdWJzY3JpYmVyVGVuYW50RG9tYWluIjoiY2FyYm9uLnN1cGVyIiwibmFtZSI6IlRyYW5zYWN0aW9ucyIsImNvbnRleHQiOiJcL3RyYW5zYWN0aW9uc1wvMS4wIiwicHVibGlzaGVyIjoiYWRtaW4iLCJ2ZXJzaW9uIjoiMS4wIiwic3Vic2NyaXB0aW9uVGllciI6IkJyb256ZSJ9LHsic3Vic2NyaWJlclRlbmFudERvbWFpbiI6ImNhcmJvbi5zdXBlciIsIm5hbWUiOiJXYWxsZXRzIiwiY29udGV4dCI6Ilwvd2FsbGV0c1wvMS4wIiwicHVibGlzaGVyIjoicHVibGlzaGVyLnVzZXIiLCJ2ZXJzaW9uIjoiMS4wIiwic3Vic2NyaXB0aW9uVGllciI6IkJyb256ZSJ9XSwicGVybWl0dGVkSVAiOiIiLCJpYXQiOjE2ODY5NDAxNTUsImp0aSI6ImM3OGI3YWJhLWIzMGQtNDYyYy1iZjdjLTA5YTM2NDVmYzhjYSJ9.RMWFrbG4h7pfh_BQEitwHRjZO-stmAln-hOGEn2MyusjJgyqiUmsfEVzLudV5XysPOk34z2JIDigm_2H0VCFzFOUfAS5F9iBfRUwDV98l4A7mXvLPjMaBuHJ_PTESvOPsNxnRSEP3peao5UU2P-lHPmDp-uPhKo7gaEYEsD_Dor6ZvzeRnbrHKzxs56RHpc2d2xNBH3aZ3TV-HaG7QzD8bJdvZPZl4QrStxnBASnaDAh3UfeRpsqMpA1zZYCOjfF4CoTt55ZTXoP54-55HXsV2_GPacv6CBKGR0bn43NQy6vGo0Sv_arKal4Y2qOm1v0WHtANMvMJIoDXXh2cA5GYw==";

    /**
     * La URL de consulta de saldo del servicio en producción D.Brasil.
     *
     * @var string
     */
    private $PasswordPRODBR = "=0l:WTkl37A6";

    /**
     * La URL de consulta de saldo del servicio en producción D.Brasil.
     *
     * @var string
     */
    private $UsernamePRODBR = "doradobetdevbras";

    /**
     * La URL de consulta de saldo del servicio en producción D.Salvador.
     *
     * @var string
     */
    private $ApiKeyPROSV = "eyJ4NXQiOiJNV1ExTWpBMlpESm1PV1U1WXpjNFpUazFZelk1T1dVeU56SmtaV1l5TWpZNE5qa3pZVFkyWXpjNE9EY3lZMlprWmpGaVpHUmhNMkkyTUdFeU5qRmpaZyIsImtpZCI6Im9rZGF3cyIsInR5cCI6IkpXVCIsImFsZyI6IlJTMjU2In0=.eyJzdWIiOiJkb3JhZG9iZXRkZXZzdkBjYXJib24uc3VwZXIiLCJhcHBsaWNhdGlvbiI6eyJvd25lciI6ImRvcmFkb2JldGRldnN2IiwidGllclF1b3RhVHlwZSI6bnVsbCwidGllciI6IlVubGltaXRlZCIsIm5hbWUiOiJEZWZhdWx0QXBwbGljYXRpb24iLCJpZCI6MTM4LCJ1dWlkIjoiN2UxOGYwNTItOGZlMy00MmExLTgwNzUtZmZhOWY1OTRmZGMwIn0sImlzcyI6Imh0dHBzOlwvXC9hcGltLW1hbmFnZW1lbnQuYXBwcy5pbnMuaW5zd2h1Yi5jb206NDQzXC9vYXV0aDJcL3Rva2VuIiwidGllckluZm8iOnsiQnJvbnplIjp7InRpZXJRdW90YVR5cGUiOiJyZXF1ZXN0Q291bnQiLCJncmFwaFFMTWF4Q29tcGxleGl0eSI6MCwiZ3JhcGhRTE1heERlcHRoIjowLCJzdG9wT25RdW90YVJlYWNoIjp0cnVlLCJzcGlrZUFycmVzdExpbWl0IjowLCJzcGlrZUFycmVzdFVuaXQiOm51bGx9LCJVbmxpbWl0ZWQiOnsidGllclF1b3RhVHlwZSI6InJlcXVlc3RDb3VudCIsImdyYXBoUUxNYXhDb21wbGV4aXR5IjowLCJncmFwaFFMTWF4RGVwdGgiOjAsInN0b3BPblF1b3RhUmVhY2giOnRydWUsInNwaWtlQXJyZXN0TGltaXQiOjAsInNwaWtlQXJyZXN0VW5pdCI6bnVsbH19LCJrZXl0eXBlIjoiUFJPRFVDVElPTiIsInBlcm1pdHRlZFJlZmVyZXIiOiIiLCJzdWJzY3JpYmVkQVBJcyI6W3sic3Vic2NyaWJlclRlbmFudERvbWFpbiI6ImNhcmJvbi5zdXBlciIsIm5hbWUiOiJFbnRpdGllcyIsImNvbnRleHQiOiJcL2VudGl0aWVzXC8xLjIiLCJwdWJsaXNoZXIiOiJhZG1pbiIsInZlcnNpb24iOiIxLjIiLCJzdWJzY3JpcHRpb25UaWVyIjoiQnJvbnplIn0seyJzdWJzY3JpYmVyVGVuYW50RG9tYWluIjoiY2FyYm9uLnN1cGVyIiwibmFtZSI6Ik5vdGlmaWNhdGlvbkVuZ2luZSIsImNvbnRleHQiOiJcL25vdGlmaWNhdGlvbmVuZ2luZVwvMS4wIiwicHVibGlzaGVyIjoiYWRtaW4iLCJ2ZXJzaW9uIjoiMS4wIiwic3Vic2NyaXB0aW9uVGllciI6IkJyb256ZSJ9LHsic3Vic2NyaWJlclRlbmFudERvbWFpbiI6ImNhcmJvbi5zdXBlciIsIm5hbWUiOiJGWCIsImNvbnRleHQiOiJcL2Z4XC8xLjAiLCJwdWJsaXNoZXIiOiJwdWJsaXNoZXIudXNlciIsInZlcnNpb24iOiIxLjAiLCJzdWJzY3JpcHRpb25UaWVyIjoiVW5saW1pdGVkIn0seyJzdWJzY3JpYmVyVGVuYW50RG9tYWluIjoiY2FyYm9uLnN1cGVyIiwibmFtZSI6IlRyYW5zYWN0aW9ucyIsImNvbnRleHQiOiJcL3RyYW5zYWN0aW9uc1wvMS4wIiwicHVibGlzaGVyIjoiYWRtaW4iLCJ2ZXJzaW9uIjoiMS4wIiwic3Vic2NyaXB0aW9uVGllciI6IkJyb256ZSJ9LHsic3Vic2NyaWJlclRlbmFudERvbWFpbiI6ImNhcmJvbi5zdXBlciIsIm5hbWUiOiJXYWxsZXRzIiwiY29udGV4dCI6Ilwvd2FsbGV0c1wvMS4wIiwicHVibGlzaGVyIjoicHVibGlzaGVyLnVzZXIiLCJ2ZXJzaW9uIjoiMS4wIiwic3Vic2NyaXB0aW9uVGllciI6IkJyb256ZSJ9LHsic3Vic2NyaWJlclRlbmFudERvbWFpbiI6ImNhcmJvbi5zdXBlciIsIm5hbWUiOiJBdXRoLVNlcnZpY2UiLCJjb250ZXh0IjoiXC9hdXRoLXNlcnZpY2VcLzEuMCIsInB1Ymxpc2hlciI6ImFkbWluIiwidmVyc2lvbiI6IjEuMCIsInN1YnNjcmlwdGlvblRpZXIiOiJVbmxpbWl0ZWQifSx7InN1YnNjcmliZXJUZW5hbnREb21haW4iOiJjYXJib24uc3VwZXIiLCJuYW1lIjoiSG9zdGVkQ2hlY2tvdXQiLCJjb250ZXh0IjoiXC9ob3N0ZWRjaGVja291dFwvMS4wIiwicHVibGlzaGVyIjoiYWRtaW4iLCJ2ZXJzaW9uIjoiMS4wIiwic3Vic2NyaXB0aW9uVGllciI6IlVubGltaXRlZCJ9LHsic3Vic2NyaWJlclRlbmFudERvbWFpbiI6ImNhcmJvbi5zdXBlciIsIm5hbWUiOiJQYXltZW50LU1ldGhvZHMiLCJjb250ZXh0IjoiXC9wYXltZW50LW1ldGhvZHNcLzEuMCIsInB1Ymxpc2hlciI6ImFkbWluIiwidmVyc2lvbiI6IjEuMCIsInN1YnNjcmlwdGlvblRpZXIiOiJCcm9uemUifV0sInBlcm1pdHRlZElQIjoiIiwiaWF0IjoxNjUzMzE5MDgwLCJqdGkiOiI4MmMxMTI4OC04ZDk4LTRmZTMtOWY1MC1mZWI5NjJhMzVmZTIifQ==.GrXSch0Wylz-qU3JlnRPwvFNVXAPyBaAV4dinqzfzjBWVEABjcP5_XgRSHeDjjX9bXxwLnU-m5VsexsQ11x30M9gdsmo6NRtsm6Fdshm0KeRY-jltpS5uJsSWKEagI8zbTIMRNu1a9a7CGQ689hS9b233ak0hGUE7MPlKRY1c5q-T1oR7JP_EAGNSnVdZ2HySoYMDhPlUgizQ7X51NibZM9CMzada_Vgc2-r3Ww0amhnEHm17duQcGPlq59y95FBHUb_Fr-y6N-2nl8A2xIpzzFMNIVpyjZb9wuap1yIBHfZVjD5BX88pzf5IEkegTFJx_wzK5_vjYiAeE0ph5grnw==";

    /**
     * La URL de consulta de saldo del servicio en producción D.Salvador.
     *
     * @var string
     */
    private $PasswordPROSV = "z!6D9Ha7SY66";

    /**
     * La URL de consulta de saldo del servicio en producción D.Salvador.
     *
     * @var string
     */
    private $UsernamePROSV = "doradobetdevsalv";

    /**
     * La URL de consulta de saldo del servicio en producción D.Ecuador.
     *
     * @var string
     */
    private $ApiKeyPROEC = "eyJ4NXQiOiJNV1ExTWpBMlpESm1PV1U1WXpjNFpUazFZelk1T1dVeU56SmtaV1l5TWpZNE5qa3pZVFkyWXpjNE9EY3lZMlprWmpGaVpHUmhNMkkyTUdFeU5qRmpaZyIsImtpZCI6Im9rZGF3cyIsInR5cCI6IkpXVCIsImFsZyI6IlJTMjU2In0=.eyJzdWIiOiJkb3JhZG9iZXRkZXZlY0BjYXJib24uc3VwZXIiLCJhcHBsaWNhdGlvbiI6eyJvd25lciI6ImRvcmFkb2JldGRldmVjIiwidGllclF1b3RhVHlwZSI6bnVsbCwidGllciI6IlVubGltaXRlZCIsIm5hbWUiOiJEZWZhdWx0QXBwbGljYXRpb24iLCJpZCI6MTQwLCJ1dWlkIjoiMjAwOGQ2NmQtODY2YS00ZWY3LTgxYTEtODA1NDVkMjc4YTlhIn0sImlzcyI6Imh0dHBzOlwvXC9hcGltLW1hbmFnZW1lbnQuYXBwcy5pbnMuaW5zd2h1Yi5jb206NDQzXC9vYXV0aDJcL3Rva2VuIiwidGllckluZm8iOnsiQnJvbnplIjp7InRpZXJRdW90YVR5cGUiOiJyZXF1ZXN0Q291bnQiLCJncmFwaFFMTWF4Q29tcGxleGl0eSI6MCwiZ3JhcGhRTE1heERlcHRoIjowLCJzdG9wT25RdW90YVJlYWNoIjp0cnVlLCJzcGlrZUFycmVzdExpbWl0IjowLCJzcGlrZUFycmVzdFVuaXQiOm51bGx9LCJVbmxpbWl0ZWQiOnsidGllclF1b3RhVHlwZSI6InJlcXVlc3RDb3VudCIsImdyYXBoUUxNYXhDb21wbGV4aXR5IjowLCJncmFwaFFMTWF4RGVwdGgiOjAsInN0b3BPblF1b3RhUmVhY2giOnRydWUsInNwaWtlQXJyZXN0TGltaXQiOjAsInNwaWtlQXJyZXN0VW5pdCI6bnVsbH19LCJrZXl0eXBlIjoiUFJPRFVDVElPTiIsInBlcm1pdHRlZFJlZmVyZXIiOiIiLCJzdWJzY3JpYmVkQVBJcyI6W3sic3Vic2NyaWJlclRlbmFudERvbWFpbiI6ImNhcmJvbi5zdXBlciIsIm5hbWUiOiJBdXRoLVNlcnZpY2UiLCJjb250ZXh0IjoiXC9hdXRoLXNlcnZpY2VcLzEuMCIsInB1Ymxpc2hlciI6ImFkbWluIiwidmVyc2lvbiI6IjEuMCIsInN1YnNjcmlwdGlvblRpZXIiOiJCcm9uemUifSx7InN1YnNjcmliZXJUZW5hbnREb21haW4iOiJjYXJib24uc3VwZXIiLCJuYW1lIjoiRW50aXRpZXMiLCJjb250ZXh0IjoiXC9lbnRpdGllc1wvMS4yIiwicHVibGlzaGVyIjoiYWRtaW4iLCJ2ZXJzaW9uIjoiMS4yIiwic3Vic2NyaXB0aW9uVGllciI6IkJyb256ZSJ9LHsic3Vic2NyaWJlclRlbmFudERvbWFpbiI6ImNhcmJvbi5zdXBlciIsIm5hbWUiOiJIb3N0ZWRDaGVja291dCIsImNvbnRleHQiOiJcL2hvc3RlZGNoZWNrb3V0XC8xLjAiLCJwdWJsaXNoZXIiOiJhZG1pbiIsInZlcnNpb24iOiIxLjAiLCJzdWJzY3JpcHRpb25UaWVyIjoiVW5saW1pdGVkIn0seyJzdWJzY3JpYmVyVGVuYW50RG9tYWluIjoiY2FyYm9uLnN1cGVyIiwibmFtZSI6Ik5vdGlmaWNhdGlvbkVuZ2luZSIsImNvbnRleHQiOiJcL25vdGlmaWNhdGlvbmVuZ2luZVwvMS4wIiwicHVibGlzaGVyIjoiYWRtaW4iLCJ2ZXJzaW9uIjoiMS4wIiwic3Vic2NyaXB0aW9uVGllciI6IkJyb256ZSJ9LHsic3Vic2NyaWJlclRlbmFudERvbWFpbiI6ImNhcmJvbi5zdXBlciIsIm5hbWUiOiJPVFAtTWFuYWdlciIsImNvbnRleHQiOiJcL290cC1tYW5hZ2VyXC8xLjAiLCJwdWJsaXNoZXIiOiJhZG1pbiIsInZlcnNpb24iOiIxLjAiLCJzdWJzY3JpcHRpb25UaWVyIjoiQnJvbnplIn0seyJzdWJzY3JpYmVyVGVuYW50RG9tYWluIjoiY2FyYm9uLnN1cGVyIiwibmFtZSI6IlBheW1lbnQtTWV0aG9kcyIsImNvbnRleHQiOiJcL3BheW1lbnQtbWV0aG9kc1wvMS4wIiwicHVibGlzaGVyIjoiYWRtaW4iLCJ2ZXJzaW9uIjoiMS4wIiwic3Vic2NyaXB0aW9uVGllciI6IkJyb256ZSJ9LHsic3Vic2NyaWJlclRlbmFudERvbWFpbiI6ImNhcmJvbi5zdXBlciIsIm5hbWUiOiJUcmFuc2FjdGlvbnMiLCJjb250ZXh0IjoiXC90cmFuc2FjdGlvbnNcLzEuMCIsInB1Ymxpc2hlciI6ImFkbWluIiwidmVyc2lvbiI6IjEuMCIsInN1YnNjcmlwdGlvblRpZXIiOiJCcm9uemUifSx7InN1YnNjcmliZXJUZW5hbnREb21haW4iOiJjYXJib24uc3VwZXIiLCJuYW1lIjoiV2FsbGV0cyIsImNvbnRleHQiOiJcL3dhbGxldHNcLzEuMCIsInB1Ymxpc2hlciI6InB1Ymxpc2hlci51c2VyIiwidmVyc2lvbiI6IjEuMCIsInN1YnNjcmlwdGlvblRpZXIiOiJCcm9uemUifV0sInBlcm1pdHRlZElQIjoiIiwiaWF0IjoxNjU0NjM4OTYyLCJqdGkiOiIxNzU5ZTJmNC1jMTQ1LTRmMTQtYWFmZi1mMDMxZDk3ZDFkNGYifQ==.gHEF5njrxG6THc_BHcvSPKSc03VpoLPSR-OM56cXf9ZNbqRTO4Y3sKaBYd47-cgabEnKJK6wJ9p873wAOxPOV-OfGOaMbNqLzku2riLXXt80HLBKYyeNOJ-BTuv3YZ3xtRtX4i1vIYV4tNHboQXTOTlX_CPdDcM4vKsNqhUzBXl1wEwD7rSVI5k2F3BzF8GjQwvHYPoIEPQ19vEkwKR5-ZXVue3laqJYAt5sHKShCpmzHVdxHTNaSdOKs4Fy3nErvxlzEaDqiFvGH8ocsTKsVF3EIgrxm5rnhAcpzrTmEbGVawtDSmumbStBbj4a0SUbDuicTeEZvArqMY0UybXrRw==";

    /**
     * La URL de consulta de saldo del servicio en producción D.Ecuador.
     *
     * @var string
     */
    private $PasswordPROEC = "8hO5[Hf1)N1S";

    /**
     * La URL de consulta de saldo del servicio en producción D.Ecuador.
     *
     * @var string
     */
    private $UsernamePROEC = "doradobetdevecu";

    /**
     * La URL de consulta de saldo del servicio en producción D.Costa Rica.
     *
     * @var string
     */
    private $ApiKeyPROCR = "eyJ4NXQiOiJNV1ExTWpBMlpESm1PV1U1WXpjNFpUazFZelk1T1dVeU56SmtaV1l5TWpZNE5qa3pZVFkyWXpjNE9EY3lZMlprWmpGaVpHUmhNMkkyTUdFeU5qRmpaZyIsImtpZCI6Im9rZGF3cyIsInR5cCI6IkpXVCIsImFsZyI6IlJTMjU2In0=.eyJzdWIiOiJkb3JhZG9iZXRkZXZjckBjYXJib24uc3VwZXIiLCJhcHBsaWNhdGlvbiI6eyJvd25lciI6ImRvcmFkb2JldGRldmNyIiwidGllclF1b3RhVHlwZSI6bnVsbCwidGllciI6IlVubGltaXRlZCIsIm5hbWUiOiJEZWZhdWx0QXBwbGljYXRpb24iLCJpZCI6MzQ3LCJ1dWlkIjoiYzU2ZTEzYTAtMzNjZS00M2Q4LWEyNjUtNDE4NmMxODhmNWI5In0sImlzcyI6Imh0dHBzOlwvXC9hcGltLW1hbmFnZW1lbnQuYXBwcy5pbnMuaW5zd2h1Yi5jb206NDQzXC9vYXV0aDJcL3Rva2VuIiwidGllckluZm8iOnsiQnJvbnplIjp7InRpZXJRdW90YVR5cGUiOiJyZXF1ZXN0Q291bnQiLCJncmFwaFFMTWF4Q29tcGxleGl0eSI6MCwiZ3JhcGhRTE1heERlcHRoIjowLCJzdG9wT25RdW90YVJlYWNoIjp0cnVlLCJzcGlrZUFycmVzdExpbWl0IjowLCJzcGlrZUFycmVzdFVuaXQiOm51bGx9LCJVbmxpbWl0ZWQiOnsidGllclF1b3RhVHlwZSI6InJlcXVlc3RDb3VudCIsImdyYXBoUUxNYXhDb21wbGV4aXR5IjowLCJncmFwaFFMTWF4RGVwdGgiOjAsInN0b3BPblF1b3RhUmVhY2giOnRydWUsInNwaWtlQXJyZXN0TGltaXQiOjAsInNwaWtlQXJyZXN0VW5pdCI6bnVsbH19LCJrZXl0eXBlIjoiUFJPRFVDVElPTiIsInBlcm1pdHRlZFJlZmVyZXIiOiIiLCJzdWJzY3JpYmVkQVBJcyI6W3sic3Vic2NyaWJlclRlbmFudERvbWFpbiI6ImNhcmJvbi5zdXBlciIsIm5hbWUiOiJBdXRoLVNlcnZpY2UiLCJjb250ZXh0IjoiXC9hdXRoLXNlcnZpY2VcLzEuMCIsInB1Ymxpc2hlciI6ImFkbWluIiwidmVyc2lvbiI6IjEuMCIsInN1YnNjcmlwdGlvblRpZXIiOiJCcm9uemUifSx7InN1YnNjcmliZXJUZW5hbnREb21haW4iOiJjYXJib24uc3VwZXIiLCJuYW1lIjoiRW50aXRpZXMiLCJjb250ZXh0IjoiXC9lbnRpdGllc1wvMS4yIiwicHVibGlzaGVyIjoiYWRtaW4iLCJ2ZXJzaW9uIjoiMS4yIiwic3Vic2NyaXB0aW9uVGllciI6IkJyb256ZSJ9LHsic3Vic2NyaWJlclRlbmFudERvbWFpbiI6ImNhcmJvbi5zdXBlciIsIm5hbWUiOiJGWCIsImNvbnRleHQiOiJcL2Z4XC8xLjAiLCJwdWJsaXNoZXIiOiJwdWJsaXNoZXIudXNlciIsInZlcnNpb24iOiIxLjAiLCJzdWJzY3JpcHRpb25UaWVyIjoiVW5saW1pdGVkIn0seyJzdWJzY3JpYmVyVGVuYW50RG9tYWluIjoiY2FyYm9uLnN1cGVyIiwibmFtZSI6Ikhvc3RlZENoZWNrb3V0IiwiY29udGV4dCI6IlwvaG9zdGVkY2hlY2tvdXRcLzEuMCIsInB1Ymxpc2hlciI6ImFkbWluIiwidmVyc2lvbiI6IjEuMCIsInN1YnNjcmlwdGlvblRpZXIiOiJVbmxpbWl0ZWQifSx7InN1YnNjcmliZXJUZW5hbnREb21haW4iOiJjYXJib24uc3VwZXIiLCJuYW1lIjoiTm90aWZpY2F0aW9uRW5naW5lIiwiY29udGV4dCI6Ilwvbm90aWZpY2F0aW9uZW5naW5lXC8xLjAiLCJwdWJsaXNoZXIiOiJhZG1pbiIsInZlcnNpb24iOiIxLjAiLCJzdWJzY3JpcHRpb25UaWVyIjoiQnJvbnplIn0seyJzdWJzY3JpYmVyVGVuYW50RG9tYWluIjoiY2FyYm9uLnN1cGVyIiwibmFtZSI6IlBheW1lbnQtTWV0aG9kcyIsImNvbnRleHQiOiJcL3BheW1lbnQtbWV0aG9kc1wvMS4wIiwicHVibGlzaGVyIjoicG9ydGFsIiwidmVyc2lvbiI6IjEuMCIsInN1YnNjcmlwdGlvblRpZXIiOiJCcm9uemUifSx7InN1YnNjcmliZXJUZW5hbnREb21haW4iOiJjYXJib24uc3VwZXIiLCJuYW1lIjoic3Vic2NyaXB0aW9ucyIsImNvbnRleHQiOiJcL3N1YnNjcmlwdGlvbnNcLzEuMCIsInB1Ymxpc2hlciI6InB1Ymxpc2hlci51c2VyIiwidmVyc2lvbiI6IjEuMCIsInN1YnNjcmlwdGlvblRpZXIiOiJVbmxpbWl0ZWQifSx7InN1YnNjcmliZXJUZW5hbnREb21haW4iOiJjYXJib24uc3VwZXIiLCJuYW1lIjoiVHJhbnNhY3Rpb25zIiwiY29udGV4dCI6IlwvdHJhbnNhY3Rpb25zXC8xLjAiLCJwdWJsaXNoZXIiOiJhZG1pbiIsInZlcnNpb24iOiIxLjAiLCJzdWJzY3JpcHRpb25UaWVyIjoiQnJvbnplIn0seyJzdWJzY3JpYmVyVGVuYW50RG9tYWluIjoiY2FyYm9uLnN1cGVyIiwibmFtZSI6IldhbGxldHMiLCJjb250ZXh0IjoiXC93YWxsZXRzXC8xLjAiLCJwdWJsaXNoZXIiOiJwdWJsaXNoZXIudXNlciIsInZlcnNpb24iOiIxLjAiLCJzdWJzY3JpcHRpb25UaWVyIjoiQnJvbnplIn1dLCJwZXJtaXR0ZWRJUCI6IiIsImlhdCI6MTY5MTc5MjI3OSwianRpIjoiZDYzMTFlZmEtYmViMC00ZGIwLWEzYjctYzU1OGNkNTRmNTIzIn0=.lzFdw6Afp0MyF9YRVdRad8XYtiXlXmVhwMiCZqFl7TbWuOuj07ODg6dO1o6PrxuebzyKpgZnUDz2USzck34cb7A6H-yCjIaDY31SaHzLuw2ABNXuGAao7k8pe1Kd8UkHTbkFxiLhRiFfHJeoDG1jO3o8QJJUcA4H6OMW1saxxRiGJ_aIGV2HBds4bw4p0ro_TxFC9xvXLBGXwYdGqXNrshUvSlRb8IXaDIzHFTH08rJ3e29bXaguKoECXVa47w6a0clNTRNXvlQEPI8NFHxXhRWcuYjsIT-KF_VguGjJxb1ExMeyiaeHNYx4Y9P6feh48E-YeR1x9tm1p1s9xAZ0ww==";

    /**
     * La URL de consulta de saldo del servicio en producción D.Costa Rica.
     *
     * @var string
     */
    private $PasswordPROCR = "l_tTIo30H17q";

    /**
     * La URL de consulta de saldo del servicio en producción D.Costa Rica.
     *
     * @var string
     */
    private $UsernamePROCR = "doradobetdevcosri";

    /**
     * La URL de consulta de saldo del servicio en producción.
     *
     * @var string
     */
    private $Reference = '';

    /**
     * La URL de consulta de saldo del servicio en Desarrollo.
     *
     * @var string
     */
    private $ReferenceDEV = '3234303038372D313030302D31313230';

    /**
     * La URL de consulta de saldo del servicio en producción Brasil.
     *
     * @var string
     */
    private $ReferenceBR = '3437313235332D313030302D31303230';

    /**
     * La URL de consulta de saldo del servicio en producción El Salvador.
     *
     * @var string
     */
    private $ReferenceSV = '3138303635352D313030302D31303430';

    /**
     * La URL de consulta de saldo del servicio en producción Peru.
     *
     * @var string
     */
    private $ReferencePE = '38343437372D313030302D31303231';

    /**
     * La URL de consulta de saldo del servicio en producción Ecuador.
     *
     * @var string
     */
    private $ReferenceEC = '3138363831372D313030302D31303230';

    /**
     * La URL de consulta de saldo del servicio en producción Costa Rica.
     *
     * @var string
     */
    private $ReferenceCR = '3934383837372D313030302D31303030';


    /**
     * Constructor de la clase.
     *
     * Configura las propiedades de la clase dependiendo del entorno
     * (desarrollo o producción). Establece las URLs, credenciales y
     * referencias necesarias para las operaciones.
     */
    public function __construct()
    {
        $ConfigurationEnvironment = new ConfigurationEnvironment();

        if ($ConfigurationEnvironment->isDevelopment()) {
            $this->URL = $this->URLDEV;
            $this->callback_url = $this->callback_urlDEV;
            $this->URLDEPOSIT = $this->URLDEPOSITDEV;
            $this->ApiKey = $this->ApiKeyDEV;
            $this->Username = $this->UsernameDEV;
            $this->Password = $this->PasswordDEV;
            $this->ApiKeyDBR = $this->ApiKeyPRODBR;
            $this->UsernameDBR = $this->UsernamePRODBR;
            $this->PasswordDBR = $this->PasswordPRODBR;
            $this->ApiKeySV = $this->ApiKeyPROSV;
            $this->UsernameSV = $this->UsernamePROSV;
            $this->PasswordSV = $this->PasswordPROSV;
            $this->ApiKeyEC = $this->ApiKeyPROEC;
            $this->UsernameEC = $this->UsernamePROEC;
            $this->PasswordEC = $this->PasswordPROEC;
            $this->ApiKeyCR = $this->ApiKeyPROCR;
            $this->UsernameCR = $this->UsernamePROCR;
            $this->PasswordCR = $this->PasswordPROCR;
            $this->Reference = $this->ReferenceDEV;
        } else {
            $this->URL = $this->URLPROD;
            $this->callback_url = $this->callback_urlPROD;
            $this->URLDEPOSIT = $this->URLDEPOSITPROD;
            $this->ApiKey = $this->ApiKeyPRO;
            $this->Username = $this->UsernamePRO;
            $this->Password = $this->PasswordPRO;
            $this->ApiKeyDBR = $this->ApiKeyPRODBR;
            $this->UsernameDBR = $this->UsernamePRODBR;
            $this->PasswordDBR = $this->PasswordPRODBR;
            $this->ApiKeySV = $this->ApiKeyPROSV;
            $this->UsernameSV = $this->UsernamePROSV;
            $this->PasswordSV = $this->PasswordPROSV;
            $this->ApiKeyEC = $this->ApiKeyPROEC;
            $this->UsernameEC = $this->UsernamePROEC;
            $this->PasswordEC = $this->PasswordPROEC;
            $this->ApiKeyCR = $this->ApiKeyPROCR;
            $this->UsernameCR = $this->UsernamePROCR;
            $this->PasswordCR = $this->PasswordPROCR;
            $this->Reference = $this->ReferencePE;
        }
    }

    /**
     * Realiza el proceso de retiro (cash out) para un usuario.
     *
     * @param CuentaCobro $CuentaCobro Objeto que contiene la información de la cuenta de cobro.
     * @param mixed       $Producto    Información del producto asociado al retiro.
     *
     * @return void
     * @throws Exception Si la transferencia no fue procesada.
     * @throws Exception Si el estado de la transacción no es 'waiting'.
     *
     * @throws Exception Si el tipo de cuenta bancaria no es encontrado.
     */
    public function cashOut(CuentaCobro $CuentaCobro, $Producto = '')
    {
        error_reporting(E_ALL);
        ini_set('display_errors', 'off');

        $Usuario = new Usuario($CuentaCobro->usuarioId);
        $Registro = new Registro("", $CuentaCobro->usuarioId);

        $mandante = $Usuario->mandante;
        $Mandante = new Mandante($Usuario->mandante);
        $Pais = new Pais($Usuario->paisId);

        $UsuarioOtrainfo = new UsuarioOtrainfo($Usuario->usuarioId);

        $Subproveedor = new Subproveedor('', 'INSWITCHOUT');
        $Subproveedor = new SubproveedorMandante($Subproveedor->getSubproveedorId(), $mandante, '');
        $Detalle = $Subproveedor->detalle;
        $Detalle = json_decode($Detalle);
        $this->username = $Detalle->username;


        $UsuarioBanco = new UsuarioBanco($CuentaCobro->mediopagoId);
        $Banco = new Banco($UsuarioBanco->bancoId);

        $TransaccionProductoMySqlDAO = new TransaccionProductoMySqlDAO();
        $Transaction = $TransaccionProductoMySqlDAO->getTransaction();


        $TransaccionProducto = new TransaccionProducto();
        $TransaccionProducto->setProductoId($Producto->productoId);
        $TransaccionProducto->setUsuarioId($Usuario->usuarioId);
        $TransaccionProducto->setValor($CuentaCobro->getValor());
        $TransaccionProducto->setEstado('A');
        $TransaccionProducto->setTipo('T');
        $TransaccionProducto->setExternoId(0);
        $TransaccionProducto->setEstadoProducto('E');
        $TransaccionProducto->setMandante($Usuario->mandante);
        $TransaccionProducto->setFinalId($CuentaCobro->getCuentaId());
        $TransaccionProducto->setFinalId(0);

        $transproductoId = $TransaccionProductoMySqlDAO->insert($TransaccionProducto);

        $CuentaCobro->setTransproductoId($transproductoId);

        $order_id = $transproductoId;
        $usuario_id = $Usuario->usuarioId;
        $credit_note = $CuentaCobro->getCuentaId();
        $account_id = $UsuarioBanco->cuenta;
        $interbank = $UsuarioBanco->getCodigo();
        $bankId = $UsuarioBanco->getBancoId();
        $typeAccount = $UsuarioBanco->getTipoCuenta();
        $account_type = $UsuarioBanco->tipoCuenta;
        $cedula = $Registro->cedula;
        $amount = $CuentaCobro->getValor();
        $name = $Usuario->nombre;
        $LastName = $Registro->apellido1;
        $subject = 'Transferencia Cuenta ' . $CuentaCobro->getCuentaId();
        $bank_detail = $Banco->descripcion;
        $channel = 1;
        $user_email = $Usuario->login;
        $phone_number = $Registro->celular;
        $bank = $Producto->getExternoId();
        $bank = $Banco->productoPago;
        $currency = $Usuario->moneda;
        $tipoDocumento = $Registro->tipoDoc;
        $TDocumento = $Registro->tipoDoc;
        $email = $Registro->email;
        $naci = $UsuarioOtrainfo->fechaNacim;

        $ConfigurationEnvironment = new ConfigurationEnvironment();

        if ($ConfigurationEnvironment->isDevelopment()) {
            $this->Reference = $this->ReferenceDEV;
        } else {
            switch ($Pais->iso) {
                case "SV":
                    $this->ApiKey = $this->ApiKeySV;
                    $this->Username = $this->UsernameSV;
                    $this->Password = $this->PasswordSV;
                    $this->Reference = $this->ReferenceSV;
                    $currency = 'USD';
                    break;
                case "BR":
                    $this->ApiKey = $this->ApiKeyDBR;
                    $this->Username = $this->UsernameDBR;
                    $this->Password = $this->PasswordDBR;
                    $this->Reference = $this->ReferenceBR;
                    $currency = 'BRL';
                    break;
                case "EC":
                    $this->ApiKey = $this->ApiKeyEC;
                    $this->Username = $this->UsernameEC;
                    $this->Password = $this->PasswordEC;
                    $this->Reference = $this->ReferenceEC;
                    $currency = 'USD';
                    break;
                case "CR":
                    $this->ApiKey = $this->ApiKeyCR;
                    $this->Username = $this->UsernameCR;
                    $this->Password = $this->PasswordCR;
                    $this->Reference = $this->ReferenceCR;
                    $currency = 'CRC';
                    break;
                default:
                    break;
            }
        }

        //convertir el tipo de documento a los requeridos por el proveedor
        switch ($tipoDocumento) {
            case "E":
                if ($Pais->iso == "PE") {
                    $tipoDocumento = "CE";
                }
                break;
            case "P": //OK
                if ($Pais->iso == "EC") {
                    $tipoDocumento = "PAS";
                } elseif ($Pais->iso == "CL") {
                    $tipoDocumento = "PP";
                } elseif ($Pais->iso == "PE") {
                    $tipoDocumento = "PAS";
                }
                break;
            case "C": //OK
                if ($Pais->iso == "CL") {
                    $tipoDocumento = "RUT";
                } elseif ($Pais->iso == "PE") {
                    $tipoDocumento = "DNI";
                } elseif ($Pais->iso == "BR") {
                    $tipoDocumento = "CPF";
                }
                break;
            default:
                $tipoDocumento = "DNI";
                break;
        }

        $extID = $Producto->getExternoId();

        switch ($UsuarioBanco->getTipoCuenta()) {
            case "0":
                $typeAccount = "CA";
                break;
            case "1":
                $typeAccount = "CC";
                break;
            case "Ahorros":
                $typeAccount = "CA";
                break;
            case "Corriente":
                $typeAccount = "CC";
                break;
            case "CPF":
                $typeAccount = "CPF";
                break;
            case "EMAIL":
                $typeAccount = "Email";
                break;
            case "PHONE":
                $typeAccount = "Phone";
                break;
            default:
                throw new Exception("Tipo de cuenta bancaria no encontrada", "10000");
                break;
        }

        //Generar token
        $respuesta = $this->generateToken($this->URL, '/auth-service/1.0/protocol/openid-connect/token');
        $tokenT = $respuesta->access_token;
        syslog(LOG_WARNING, "INSWITCHOUT TOKEN: " . json_encode($respuesta));

        $lang = 'es';
        $idType = 'nationalId';
        $paymentM = 'interbankkout-' . strtolower($Pais->iso);

        if ($Pais->iso == 'BR') {
            $lang = 'pt';
            $idType = 'CPF';
        }
        if ($extID == 'DBR_PIX') {
            $paymentM = 'pixout-' . strtolower($Pais->iso);
        }
        if ($extID == 'DPE_INTERBANK') {
            $paymentM = 'interbankkout-' . strtolower($Pais->iso);
        }
        if ($extID == 'DSV_INTERBANK') {
            $paymentM = 'interbankkout-' . strtolower($Pais->iso);
        }
        if ($extID == 'BIN_PAY') {
            $paymentM = 'binancepayin-' . strtolower($Pais->iso);
        }

        $prefix = '';
        if ($account_type == 'PHONE') {
            $prefix = '+55';
        }

        //Cuerpo de la solicitud de retiro
        $data = array();
        $data['amount'] = $amount;
        $data['currency'] = $currency;
        $data['creditParty'] = [
            "type" => $paymentM,
            'data' => [
                "document_number" => $cedula,
                "key" => $prefix . $account_id
            ]
        ];
        $data['debitParty'] = [
            "paymentMethodReference" => $this->Reference
        ];
        $data['descriptionText'] = 'OUT_' . $transproductoId;
        $data['requestingOrganisationTransactionReference'] = $transproductoId;
        $data['country'] = $Pais->iso;
        $data['recipientKYC'] = [
            "entityReference" => 1
        ];

        syslog(LOG_WARNING, "INSWITCHOUT DATA: " . json_encode($data));

        $uuid = vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex(openssl_random_pseudo_bytes(16)), 4));

        $result = $this->connection($data, $tokenT, $uuid, $this->URL, '/transactions/1.0/transactions/type/withdrawal');

        syslog(LOG_WARNING, "INSWITCHOUT RESPONSE: " . json_encode($result));

        $result_ = json_encode($result);
        $result_ = json_decode($result_, true);
        $array_encode = json_encode(array_merge($data, $result_));

        if ($result != "" && $result != null && $result->transactionStatus == 'waiting') {
            $TransprodLog = new TransprodLog();
            $TransprodLog->setTransproductoId($transproductoId);
            $TransprodLog->setEstado('E');
            $TransprodLog->setTipoGenera('M');
            $TransprodLog->setComentario('Envio Solicitud de pago');
            $TransprodLog->setTValue($array_encode);
            $TransprodLog->setUsucreaId(0);
            $TransprodLog->setUsumodifId(0);

            $TransaccionProducto->setExternoId($result->transactionReference);
            $TransaccionProductoMySqlDAO->update($TransaccionProducto);


            $TransprodLogMySqlDAO = new TransprodLogMySqlDAO($Transaction);
            $TransprodLogMySqlDAO->insert($TransprodLog);

            $Transaction->commit();
        }else if($result->transactionStatus != 'waiting' || $result->transactionStatus == '') {
            throw new Exception($result->transactionStatus, "10000");
        }else {
            throw new Exception("La transferencia no fue procesada", "10000");
        }
    }

    /**
     * Realiza una conexión HTTP utilizando cURL para enviar datos a un endpoint específico.
     *
     * @param array  $data  Datos que se enviarán en el cuerpo de la solicitud.
     * @param string $token Token de autenticación para la solicitud.
     * @param string $uuid  Identificador único de correlación para la solicitud.
     * @param string $url   URL base del servicio al que se realizará la conexión.
     * @param string $path  Ruta específica del endpoint al que se enviará la solicitud.
     *
     * @return object|null Respuesta decodificada del servidor en formato JSON, o null si no hay respuesta.
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
                'X-Callback-URL: ' . $this->callback_url
            ),
        ));

        $response = curl_exec($curl);
        syslog(LOG_WARNING, "INSWITCHOUT RESPONSE: " . $response);
        curl_close($curl);
        return json_decode($response);
    }

    /**
     * Genera un token de autenticación utilizando las credenciales proporcionadas.
     *
     * @param string $url  URL base del servicio de autenticación.
     * @param string $path Ruta específica del endpoint de autenticación.
     *
     * @return object|null Respuesta decodificada del servidor en formato JSON, o null si no hay respuesta.
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
                'apikey:' . $this->ApiKey,
                'Content-Type: application/x-www-form-urlencoded'
            ),
        ));

        $response = curl_exec($curl);
        curl_close($curl);
        return json_decode($response);
    }

}
