<?php
/**
* Lenguaje Dorado Antiguo
*   
* 
* @package ninguno 
* @author Daniel Tamayo <it@virtualsoft.tech>
* @version ninguna
* @access public 
* @see no
* @date 23.05.17
* 
*/
use Backend\dto\LenguajeMandante;
use Backend\dto\Usuario;

ini_set('memory_limit', '-1');
error_reporting(E_ALL);

ini_set('display_errors', 'ON');

require_once __DIR__ . '../../vendor/autoload.php';

date_default_timezone_set('America/Bogota');


$jsonLenguaje = '{"VERSION": "1.0",
                "BUTTON_LOGIN": "Iniciar sesión ",
                "BUTTON_JOIN": "Registrate",
                "BUTTON_ACCOUNT": "Cuenta",
                "LOGIN_LEGEND": "Inicia sesión con tu cuenta",
                "LOGIN_USERNAME": "Usuario",
                "LOGIN_PASSWORD": "Contraseña",
                "LOGIN_ERROR_EMPTY": "Por favor ingrese su usuario y contraseña.",
                "LOGIN_FORGET_PASSWORD": "Olvido su contraseña?",
                "LOGIN_BUTTON_SUBMIT": "Acceder",
                "LOGIN_JOIN": "Regístrate",
                "LOGIN_BUTTON_SHOW": "Mostrar",
                "LOGIN_BUTTON_HIDE": "Ocultar",
                "HEAD_KEYWORDS": {
                    "DEFAULT": "Apuestas, apuestas online, apuestas peru, apuestas peru, apuestas deportivas peru, pronósticos, apuestas vivo, apuestas en línea, casino online, mejor casa de apuestas, doradobet",
                    "HOME": "Apuestas, apuestas online, apuestas peru, apuestas peru, apuestas deportivas peru, pronósticos, apuestas vivo, apuestas en línea, casino online, mejor casa de apuestas, doradobet",
                    "CONTACTO": "Apuestas deportivas Latam,apuestas,latinoamerica,Doradobet,Liga BBVA,Premier League,Copa Libertadores,Copa Suramericana, Apostar | Doradobet",
                    "REGISTRO": "Apuestas deportivas Latam,apuestas,latinoamerica,Doradobet,Liga BBVA,Premier League,Copa Libertadores,Copa Suramericana, registrate, registro, Apostar, peru, doradobet",
                    "TERMINOSYCONDICIONES": "Apuestas deportivas Latam,apuestas,termino y condiciones,Doradobet, Apostar, reglamento| Doradobet",
                    "POLITICADEPRIVACIDAD": "Apuestas deportivas Latam,apuestas,politica de privacidad,Doradobet, Apostar| Doradobet",
                    "JUEGORESPONSABLE": "Apuestas deportivas, Peru, Apuesta perú, apuestas ,juego responsable, Casa de apuestas, Doradobet, Apostar",
                    "TRABAJACONNOSTROS": "Apuestas deportivas Latam,apuestas,trabaja con nosotros,Doradobet, Apostar| Doradobet",
                    "PROMOCIONES": "Apuestas deportivas Latam,apuestas,promociones,bonos,Doradobet, Apostar| Doradobet",
                    "DEPORTES": "Apuestas deportivas Latam,apuestas,deportes,apuestas deportivas,Doradobet, Apostar,ligas,sports, E sports, Esports, mejores cuotas, mejores ligas, ligas del mundo| Doradobet",
                    "DEPORTESENVIVO": "Apuestas deportivas Latam,apuestas,deportes,deportes en vivo,apuestas deportivas,Doradobet, Apostar, ligas, sports, E sports, Esports, mejores cuotas, mejores ligas, ligas del mundo| Doradobet",
                    "VIRTUAL": "Apuestas deportivas Latam,apuestas,virtuales,apuestas virtuales,Doradobet, Apostar| Doradobet",
                    "CASINO": "Apuestas deportivas Latam,apuestas,casino,apuestas casino,Doradobet, Apostar| Doradobet",
                    "CASINOENVIVO": "Apuestas deportivas Latam,apuestas,casino,casino en vivo,apuestas casino,Doradobet, Apostar| Doradobet"
                },
                "HEAD_DESCRIPTION": {
                    "DEFAULT": "Mejor casa de apuestas deportivas Peru 2018  Mejor sitio de apuestas online en Peru  Apuestas en vivo  Pronosticos apuestas online  Doradobet",
                    "HOME": "Mejor casa de apuestas deportivas Peru 2018  Mejor sitio de apuestas online en Peru  Apuestas en vivo  Pronosticos apuestas online  Doradobet",
                    "CONTACTO": "Apuestas deportivas Doradobet. Apuestas en Latinoamerica con todas las ligas del mundo | Doradobet",
                    "REGISTRO": "Registrate aquí en Doradobet, Apuestas Deportivas en Perú y reclama tu bono de deposito",
                    "TERMINOSYCONDICIONES": "Terminos y condiciones Doradobet. Encuentra aqui todo el reglamento en Doradobet | ",
                    "POLITICADEPRIVACIDAD": "Politica de privacidad Doradobet. Encuentra aqui toda la politica de privacidad en Doradobet",
                    "JUEGORESPONSABLE": "Juego responsable Doradobet. Casas de apuestas deportivas en Perú y Latinoamerica",
                    "TRABAJACONNOSTROS": "Trabaja con nosotros Doradobet. Encuentra aqui todo sobre trabaja con nosotros en Doradobet | Doradobet",
                    "PROMOCIONES": "Promociones Doradobet. Encuentra aquí bonos para apuestas deportivas en Perú y pronósticos deportivos ",
                    "DEPORTES": "Deportes Doradobet. Encuentra aqui todo sobre deportes en Doradobet | Doradobet",
                    "DEPORTESENVIVO": "Deportes en vivo Doradobet. Encuentra aqui todo sobre deportes en vivo en Doradobet | Doradobet",
                    "VIRTUAL": "Apostar en Apuestas Virtuales de doradobet. Encuentra aquí Futbol, Caballos, Galgos, Basket, Tenis",
                    "CASINO": "Disfruta de losmejores juegos de Casino en Doradobet. Microgaming, Betixon, Wordmatch, Inbet, Joingames",
                    "CASINOENVIVO": "Casino en vivo Doradobet. Disfruta los mejores juegos de casino en Vivo"
                },
                "HEAD_TITLE": {
                    "DEFAULT": "Apuestas Deportivas Peru 2018 | Apuestas Online | Casino Online",
                    "HOME": "Apuestas Deportivas Peru 2018 | Apuestas Online | Casino Online",
                    "CONTACTO": "Contacto con Doradobet | El Sitio de Apuestas Deportivas en Peru",
                    "REGISTRO": "Regístrate en doradobet, plataforma de apuestas deportivas en Peru y Latinoamerica | Doradobet",
                    "TERMINOSYCONDICIONES": "Terminos y condiciones de Doradobet | Casa de Apuestas Deportivas | Doradobet",
                    "POLITICADEPRIVACIDAD": "Politicas de privacidad para el sitio de Apuestas Deportivas | Doradobet",
                    "JUEGORESPONSABLE": "Juego Responsable para sitio de Apuestas Deportivas | Doradobet",
                    "TRABAJACONNOSTROS": "Politicas de privacidad para el sitio de Apuestas Deportivas | Doradobet",
                    "PROMOCIONES": "Promociones para apuestas deportivas  | Doradobet",
                    "DEPORTES": "Apuestas Deportivas en fútbol, tenis, baloncesto y mucho mas | Doradobet",
                    "DEPORTESENVIVO": "Apuestas deportivas en vivo con las mejores cuotas del mercado | Doradobet",
                    "VIRTUAL": "Apuestas virtuales | Doradobet",
                    "CASINO": " Apuestas en Casino con los mejores juegos del mercado | Doradobet",
                    "CASINOENVIVO": "Casino en vivo | Doradobet"
                },
                "PAGE_REGISTRO_TITLE": "Registro",
                "PAGE_REGISTRORAPIDO_TITLE": "Registro Rápido",
                "PAGE_TRABAJACONNOSOTROS_TITLE": "Trabaja con nosotros",
                "PAGE_CONTACTO_TITLE": "Contactenos",
                "PAGE_RECUPERARCLAVE_TITLE": "Recuperar clave",
                "PAGE_TERMINOSYCONDICIONES_TITLE": "Terminos y condiciones",
                "PAGE_PROMOCIONES_TITLE": "Promociones",
                "PAGE_POLITICADEPRIVACIDAD_TITLE": "Politica de Privacidad",
                "PAGE_JUEGORESPONSABLE_TITLE": "Juego responsable",
                "PAGE_MICUENTA_TITLE": "Mi Cuenta",
                "PAGE_GESTION_TITLE": "Gestion",
                "PAGE_CAJA_TITLE": "Caja",
                "PAGE_CONSULTAS_TITLE": "Consultas",
                "PAGE_CONSULTATICKETS_TITLE": "Consulta Tickets",
                "PAGE_RESULTADOS_TITLE": "Resultados",
                "HEADER_MENU_CONTACTO": "Contacto",
                "HEADER_MENU_REGISTRO": "Registro",
                "HEADER_MENU_SERVICIOS": "Servicios",
                "HEADER_MENU_LOGIN": "Iniciar Sesion",
                "HEADER_SALDO_PRINCIPAL": "Saldo Disponible:",
                "HEADER_SALDOPV_PRINCIPAL": "Cupo de Recargas:",
                "HEADER_SALDO_RECARGA": "Saldo Recargas:",
                "HEADER_SALDOPV_RECARGA": "Cupo de Recargas:",
                "HEADER_SALDO_RETIRO": "Saldo Retiro:",
                "HEADER_SALDOPV_JUEGO": "Cupo de Juego:",
                "HEADER_SALDO_BONO": "Saldo Bonos:",
                "ALERT_BUTTON_OK": "Aceptar",
                "ALERT_BUTTON_CANCEL": "Cancelar",
                "PAGE_RETIRO": {
                    "PAGE_TITLE": "Crear nota de retiro",
                    "TIPO_MONEDA_TITLE": "Tipo de Moneda",
                    "CANTIDAD_TITLE": "Cantidad",
                    "CANTIDAD_PLACEHOLDER": "Ingrese la cantidad aquí",
                    "BUTTON_CONFIRM": "Retirar"
                },
                "PAGE_ANULAR_NOTA": {
                    "PAGE_TITLE": "Anular nota de retiro",
                    "TIPO_MONEDA_TITLE": "Tipo de Moneda",
                    "NUMERO_CUENTA_TITLE": "Numero de Nota de retiro",
                    "NUMERO_CUENTA_PLACEHOLDER": "Ingrese la nota de retiro",
                    "FECHA_NOTA_TITLE": "Fecha de creación:",
                    "NOMBRE_USUARIO_TITLE": "Nombre del usuario:",
                    "VALOR_NOTA_TITLE": "Valor de la nota:",
                    "BUTTON_SEARCH": "Consultar",
                    "BUTTON_CONFIRM": "Anular Nota de retiro"
                },
                "PAGE_PREMIOPAGO": {
                    "PAGE_TITLE": "",
                    "TIPO_MONEDA_TITLE": "",
                    "NUMERO_TICKET_TITLE": "Numero de Ticket",
                    "NUMERO_TICKET_PLACEHOLDER": "Ingrese el numero de ticket",
                    "CLAVE_TICKET_TITLE": "Clave de el Ticket",
                    "CLAVE_TICKET_PLACEHOLDER": "Ingrese la clave de el ticket",
                    "MSG_TICKET_PAGADO": "¡ ATENCION ! - ESTE TICKET YA FUE PAGADO",
                    "VALOR_NOTA_TITLE": "Valor de la nota:",
                    "BUTTON_SEARCH": "Consultar",
                    "BUTTON_CONFIRM": "Pagar "
                },
                "PAGE_PAGONOTA": {
                    "PAGE_TITLE": "",
                    "TIPO_MONEDA_TITLE": "",
                    "NUMERO_NOTA_TITLE": "Numero de Nota",
                    "NUMERO_NOTA_PLACEHOLDER": "Ingrese el numero de nota",
                    "CLAVE_NOTA_TITLE": "Clave de la nota",
                    "CLAVE_NOTA_PLACEHOLDER": "Ingrese la clave de la nota",
                    "BUTTON_SEARCH": "Consultar",
                    "BUTTON_CONFIRM": "Pagar "
                },
                "PAGE_RECARGARCREDITO": {
                    "PAGE_TITLE": "",
                    "TIPO_MONEDA_TITLE": "",
                    "NUMERO_USUARIO_TITLE": "Numero del cliente: ",
                    "NUMERO_USUARIO_PLACEHOLDER": "Ingrese el numero del cliente",
                    "EMAIL_USUARIO_TITLE": "Email del cliente:",
                    "EMAIL_USUARIO_PLACEHOLDER": "Ingrese el email de cliente",
                    "CEDULA_USUARIO_TITLE": "Cedula del cliente: ",
                    "CEDULA_USUARIO_PLACEHOLDER": "Ingrese la cedula de cliente",
                    "NOMBRE_USUARIO_TITLE": "Nombre de cliente",
                    "PAIS_USUARIO_TITLE": "Pais de cliente",
                    "MONEDA_USUARIO_TITLE": "Moneda de cliente",
                    "VARLOR_RECARGA_TITLE": "Valor a recargar",
                    "BUTTON_SEARCH": "Consultar",
                    "BUTTON_CONFIRM": "Recargar ",
                    "BUTTON_CANCEL": "Cancelar "
                },
                "PAGE_CAMBIAR_MICUENTA": {
                    "RESULT_CORRECT": "Contraseña cambiada satisfactoriamente.",
                    "PAGE_TITLE": "Cambiar Contraseña",
                    "TIPO_MONEDA_TITLE": "",
                    "CAMBIAR_CONTRASENA_ANTERIOR_TITLE": "Ingrese la contraseña actual: ",
                    "CAMBIAR_CONTRASENA_ANTERIOR_PLACEHOLDER": "Ingrese la contraseña actual",
                    "CAMBIAR_CONTRASENA_NUEVA_TITLE": "Ingrese la contraseña nueva: ",
                    "CAMBIAR_CONTRASENA_NUEVA_PLACEHOLDER": "Ingrese la contraseña nueva",
                    "CAMBIAR_CONTRASENA_NUEVA_CONFIRMAR_TITLE": "Confirma la contraseña nueva: ",
                    "CAMBIAR_CONTRASENA_NUEVA_CONFIRMAR_PLACEHOLDER": "Confirma la contraseña nueva",
                    "BUTTON_SEARCH": "Consultar",
                    "BUTTON_CONFIRM": "Guardar ",
                    "BUTTON_CANCEL": "Cancelar "
                },
                "ERROR_GENERAL": "Ocurrio un error inesperado en el sistema. Por favor intente mas tarde",
                "ERRORCODE": {
                    "ERROR01": "El usuario ingresado no se encuentra registrado en nuestro sistema. Por favor verifique sus datos e intente nuevamente.",
                    "ERROR02": "La clave ingresada es errónea. Recuerde que al tercer (3) intento con una clave equivocada, el sistema lo bloqueará automáticamente. Recuerde que puede usar la opción de recuperación de clave que está disponible en la parte superior derecha del sitio y así evitar bloquear su cuenta de Doradobet.",
                    "ERROR03": "El usuario ingresado se encuentra inactivo. Si tiene alguna inquietud, favor escríbanos por el chat en línea o por la página de contacto.",
                    "ERROR04": "En el momento nos encontramos en proceso de mantenimiento del sitio. Por favor intente nuevamente en unos minutos. Agradecemos su comprensión.",
                    "ERROR05": "El usuario ha sido bloqueado por el sistema debido a que excedió el número de intentos permitidos con clave errónea. Por favor use la opción de recuperación de clave que encontrará en la parte superior derecha del sitio << ¿Olvidó su contraseña? >>, o escríbanos por el chat en línea o por la página de contacto, para ayudarle a solucionar su inconveniente.",
                    "ERROR06": "El usuario ingresado se encuentra inactivo. Si tiene alguna inquietud, favor escríbanos por el chat en línea o por la página de contacto.",
                    "ERROR07": "Se ha limitado su acceso a la plataforma. Por favor verifique la URL a la que esta accediendo o contacte a su administrador.",
                    "ERROR08": "Ingresa todos los campos requeridos.",
                    "ERROR09": "No tiene el saldo suficiente para realizar este retiro.",
                    "ERROR10": "Hemos detectado que nunca ha realizado una recarga por lo tanto su operacion no puede ser procesada.",
                    "ERROR11": "El valor minimo para un retiro es de #VAL.",
                    "ERROR12": "Se ha encontrado inconsistencias en los parametrs suministrados por lo cual su solicitud no pudo ser procesada",
                    "ERROR13": "La nota de retiro no se puede eliminar porque ya fue cobrada.",
                    "ERROR14": "No fue posible ubicar la nota de retiro ingresada.",
                    "ERROR15": "El numero de cedula suministrada ya se encuentra registrada en la base de datos.",
                    "ERROR16": "Ha ocurrido un error inesperado en nuestro sistema intentalo nuevamente.",
                    "ERROR17": "Por favor ingresa correctamente el CAPTCHA. ",
                    "ERROR18": "Solo se pueden registrar dos cuentas bancarias.",
                    "ERROR19": "",
                    "ERROR20": "",
                    "ERROR21": ""
                },
                "HOME_SECOND": {
                    "HOME_SECOND_URL": "/registro",
                    "HOME_SECOND_IMAGE": "assets/images/sliders/home-second/registrateahora-min.png"
                },
                "HOME_SLIDER": [{
                    "SLIDE_TITLE": "Apuesta a la Premier League",
                    "SLIDE_TITLE_SMALL": "Vive la premier!",
                    "SLIDE_IMAGE": "assets/images/sliders/home/premierleague-banner.jpg",
                    "SLIDE_TEXT": "",
                    "BUTTON_URL": "/deportes/liga/28",
                    "BUTTON_TITLE": "Apostar!",
                    "BUTTON_TEXT": "Apostar!"
                }, {
                    "SLIDE_TITLE": "Disfruta la copa sudamericana",
                    "SLIDE_TITLE_SMALL": "Gana con la copa!",
                    "SLIDE_IMAGE": "assets/images/sliders/home/copasudamericana-banner.jpg",
                    "SLIDE_TEXT": "",
                    "BUTTON_URL": "/deportes/liga/79",
                    "BUTTON_TITLE": "Apostar!",
                    "BUTTON_TEXT": "Apostar!"
                }, {
                    "SLIDE_TITLE": "Apuesta en el Tour de Francia",
                    "SLIDE_TITLE_SMALL": "¡Siente el tour!",
                    "SLIDE_IMAGE": "assets/images/sliders/home/banner-tour.jpg",
                    "SLIDE_TEXT": "",
                    "BUTTON_URL": "/deportes/liga/819",
                    "BUTTON_TITLE": "Apostar!",
                    "BUTTON_TEXT": "Apostar!"
                }, {
                    "SLIDE_TITLE": "¡ Trabaja con la mejor casa de apuestas !",
                    "SLIDE_TITLE_SMALL": "Una tienda de Doradobet en tu negocio ",
                    "SLIDE_IMAGE": "assets/images/sliders/home/slider3.jpg",
                    "SLIDE_TEXT": "",
                    "BUTTON_URL": "/trabaja-con-nosotros",
                    "BUTTON_TITLE": "Aliarme",
                    "BUTTON_TEXT": "Aliarme"
                }],
                "HOME_SECOND_SLIDER": [{
                    "SLIDE_TITLE": "",
                    "SLIDE_TITLE_SMALL": "",
                    "SLIDE_IMAGE": "assets/images/sliders/home-second/blog.png",
                    "SLIDE_IMAGE_SHADOW": "assets/images/sliders/home-second/img-bono2-shadow2-min21.png",
                    "SLIDE_TEXT": "",
                    "BUTTON_URL": "",
                    "BUTTON_TITLE": "",
                    "BUTTON_TEXT": "",
                    "SLIDE_URL": "http://blog.doradobet.com/"
                }, {
                    "SLIDE_TITLE": "",
                    "SLIDE_TITLE_SMALL": "",
                    "SLIDE_IMAGE": "assets/images/sliders/home-second/aprendeaapostar.png",
                    "SLIDE_IMAGE_SHADOW": "assets/images/sliders/home-second/img-bono2-shadow2-min.png",
                    "SLIDE_TEXT": "",
                    "BUTTON_URL": "",
                    "BUTTON_TITLE": "",
                    "BUTTON_TEXT": "",
                    "SLIDE_URL": "/terminosycondiciones/tutoriales"
                }],
                "DEPORTES_SLIDER": [{
                    "SLIDE_TITLE": "Disfruta de más de 100 modalidades de apuestas por evento.",
                    "SLIDE_TITLE_SMALL": "Las mejores cuotas",
                    "SLIDE_IMAGE": "assets/images/sliders/deportes/sliderinterno.jpg",
                    "SLIDE_TEXT": "",
                    "BUTTON_URL": "/deportes",
                    "BUTTON_TITLE": "Apostar ahora",
                    "BUTTON_TEXT": "Apostar ahora"
                }, {
                    "SLIDE_TITLE": "Juega hoy mismo.",
                    "SLIDE_TITLE_SMALL": "Nuevo Casino Online",
                    "SLIDE_IMAGE": "assets/images/sliders/deportes/slider5-2.jpg",
                    "SLIDE_TEXT": "",
                    "BUTTON_URL": "/live-casino",
                    "BUTTON_TITLE": "Jugar",
                    "BUTTON_TEXT": "Jugar"
                }, {
                    "SLIDE_TITLE": "¡Las emociones de la MLB vivelas en en Doradobet y se un ganador! ",
                    "SLIDE_TITLE_SMALL": "",
                    "SLIDE_IMAGE": "assets/images/sliders/deportes/slider2-2.jpg",
                    "SLIDE_TEXT": "",
                    "BUTTON_URL": "/deportes",
                    "BUTTON_TITLE": "Apostar ahora",
                    "BUTTON_TEXT": "Apostar ahora"
                }, {
                    "SLIDE_TITLE": "¡Más de 20 deportes Más oportunidades de ganar!",
                    "SLIDE_TITLE_SMALL": "",
                    "SLIDE_IMAGE": "assets/images/sliders/deportes/banner-futbol-americano.jpg",
                    "SLIDE_TEXT": "",
                    "BUTTON_URL": "/deportes-en-vivo",
                    "BUTTON_TITLE": "Ir ahora",
                    "BUTTON_TEXT": "Ir ahora"
                }, {
                    "SLIDE_TITLE": "Vive una nueva experiencia, apuesta y gana en nuestro casino online.",
                    "SLIDE_TITLE_SMALL": "Ruleta, slots, blackjack ... ",
                    "SLIDE_IMAGE": "assets/images/sliders/deportes/casino-superior.jpg",
                    "SLIDE_TEXT": "",
                    "BUTTON_URL": "/casino",
                    "BUTTON_TITLE": "Ir ahora",
                    "BUTTON_TEXT": "Ir ahora"
                }],
                "BANNER_LEFT_SLIDER": [{
                    "SLIDE_TITLE": "",
                    "SLIDE_TITLE_SMALL": "",
                    "SLIDE_IMAGE": "assets/images/sliders/banner/left/premierleague-lateral.jpg",
                    "SLIDE_TEXT": "",
                    "BUTTON_URL": "",
                    "BUTTON_TITLE": "",
                    "BUTTON_TEXT": "",
                    "TYPE": "LIGA",
                    "ID": "91"
                }, {
                    "SLIDE_TITLE": "",
                    "SLIDE_TITLE_SMALL": "",
                    "SLIDE_IMAGE": "assets/images/sliders/banner/left/casino-int.jpg",
                    "SLIDE_TEXT": "",
                    "BUTTON_URL": "/new-casino",
                    "BUTTON_TITLE": "",
                    "BUTTON_TEXT": "",
                    "TYPE": "CUSTOM",
                    "ID": ""
                }, {
                    "SLIDE_TITLE": "",
                    "SLIDE_TITLE_SMALL": "",
                    "SLIDE_IMAGE": "assets/images/sliders/banner/left/banner-premier-int.jpg",
                    "SLIDE_TEXT": "",
                    "BUTTON_URL": "",
                    "BUTTON_TITLE": "",
                    "BUTTON_TEXT": "",
                    "TYPE": "LIGA",
                    "ID": "91"
                }, {
                    "SLIDE_TITLE": "",
                    "SLIDE_TITLE_SMALL": "",
                    "SLIDE_IMAGE": "assets/images/sliders/banner/left/lol-world-chip.jpg",
                    "SLIDE_TEXT": "",
                    "BUTTON_URL": "",
                    "BUTTON_TITLE": "",
                    "BUTTON_TEXT": "",
                    "TYPE": "LIGA",
                    "ID": "1467675"
                }],
                "BANNER_RIGHT_SLIDER": [{
                    "SLIDE_TITLE": "",
                    "SLIDE_TITLE_SMALL": "",
                    "SLIDE_IMAGE": "assets/images/sliders/banner/left/copasudamericana-lateral.jpg",
                    "SLIDE_TEXT": "",
                    "BUTTON_URL": "",
                    "BUTTON_TITLE": "",
                    "BUTTON_TEXT": "",
                    "TYPE": "LIGA",
                    "ID": "79"
                }, {
                    "SLIDE_TITLE": "",
                    "SLIDE_TITLE_SMALL": "",
                    "SLIDE_IMAGE": "assets/images/sliders/banner/right/registrate-interno2.jpg",
                    "SLIDE_TEXT": "",
                    "BUTTON_URL": "/registro",
                    "BUTTON_TITLE": "",
                    "BUTTON_TEXT": "",
                    "TYPE": "CUSTOM",
                    "ID": ""
                }, {
                    "SLIDE_TITLE": "",
                    "SLIDE_TITLE_SMALL": "",
                    "SLIDE_IMAGE": "assets/images/sliders/banner/left/casino-int.jpg",
                    "SLIDE_TEXT": "",
                    "BUTTON_URL": "/new-casino",
                    "BUTTON_TITLE": "",
                    "BUTTON_TEXT": "",
                    "TYPE": "CUSTOM",
                    "ID": ""
                }, {
                    "SLIDE_TITLE": "",
                    "SLIDE_TITLE_SMALL": "",
                    "SLIDE_IMAGE": "assets/images/sliders/banner/right/uefa-champions.jpg",
                    "SLIDE_TEXT": "",
                    "BUTTON_URL": "",
                    "BUTTON_TITLE": "",
                    "BUTTON_TEXT": "",
                    "TYPE": "LIGA",
                    "ID": "735764"
                }, {
                    "SLIDE_TITLE": "",
                    "SLIDE_TITLE_SMALL": "",
                    "SLIDE_IMAGE": "assets/images/sliders/banner/right/uefa-europa.jpg",
                    "SLIDE_TEXT": "",
                    "BUTTON_URL": "",
                    "BUTTON_TITLE": "",
                    "BUTTON_TEXT": "",
                    "TYPE": "LIGA",
                    "ID": "735748"
                }],
                "MENU_PRINCIPAL": [{
                    "MENU_TITLE": "HOME",
                    "MENU_URL": "/home",
                    "MENU_ICON": "assets/images/icons/home.svg",
                    "MENU_SECCION": "SectionItem"
                }, {
                    "MENU_TITLE": "APUESTAS DEPORTIVAS",
                    "MENU_URL": "/apuestas",
                    "MENU_ICON": "assets/images/icons/sports.svg",
                    "MENU_SECCION": "SectionItem",
                    "MENU_COLOR": "#000000"
                }, {
                    "MENU_TITLE": "APUESTAS EN VIVO",
                    "MENU_URL": "/deportes-en-vivo",
                    "MENU_ICON": "assets/images/icons/en-vivo.svg",
                    "MENU_SECCION": "SectionItem menu-vivo",
                    "MENU_COLOR": "#000000"
                }],
                "MENU_PRINCIPAL_SEGUNDO": [{
                    "MENU_TITLE": "REGISTRO",
                    "MENU_URL": "/registro",
                    "MENU_ICON": "",
                    "MENU_SECCION": "",
                    "MENU_COLOR": "#000000"
                }],
                "MENU_USUARIO_SEGUNDO": [],

                "MENU_USUARIO_SEGUNDOMOBILE": [{
                    "MENU_TITLE": "APUESTAS VIRTUALES",
                    "MENU_URL": "/virtualnew",
                    "MENU_ICON": "assets/images/icons/race-horse.svg",
                    "MENU_SECCION": "SectionItem",
                    "MENU_COLOR": "#000000"
                }],
                "MENU_USUARIO_PRINCIPALNEW": [{
                    "MENU_TITLE": "HOME",
                    "MENU_URL": "/home",
                    "MENU_ICON": "assets/images/icons/home.svg",
                    "MENU_SECCION": "SectionItem"
                }, {
                    "MENU_TITLE": "APUESTAS DEPORTIVAS",
                    "MENU_URL": "/apuestas",
                    "MENU_ICON": "assets/images/icons/home.svg",
                    "MENU_SECCION": "SectionItem",
                    "MENU_COLOR": "#000000"
                }, {
                    "MENU_TITLE": "APUESTAS EN VIVO",
                    "MENU_URL": "/deportes-en-vivo",
                    "MENU_ICON": "assets/images/icons/casino.svg",
                    "MENU_SECCION": "SectionItem menu-vivo",
                    "MENU_COLOR": "#000000"
                }, {
                    "MENU_TITLE": "APUESTAS VIRTUALES",
                    "MENU_URL": "/virtualnew",
                    "MENU_ICON": "assets/images/icons/livecasino.svg",
                    "MENU_SECCION": "SectionItem",
                    "MENU_COLOR": "#000000"
                }],
                "MENU_USUARIO_PRINCIPAL": [{
                    "MENU_TITLE": "HOME",
                    "MENU_URL": "/home",
                    "MENU_ICON": "assets/images/icons/home.svg",
                    "MENU_SECCION": "SectionItem"
                }, {
                    "MENU_TITLE": "APUESTAS DEPORTIVAS",
                    "MENU_URL": "/apuestas",
                    "MENU_ICON": "assets/images/icons/home.svg",
                    "MENU_SECCION": "SectionItem",
                    "MENU_COLOR": "#000000"
                }, {
                    "MENU_TITLE": "APUESTAS EN VIVO",
                    "MENU_URL": "/deportes-en-vivo",
                    "MENU_ICON": "assets/images/icons/casino.svg",
                    "MENU_SECCION": "SectionItem menu-vivo",
                    "MENU_COLOR": "#000000"
                }, {
                    "MENU_TITLE": "APUESTAS VIRTUALES",
                    "MENU_URL": "/virtual",
                    "MENU_ICON": "assets/images/icons/livecasino.svg",
                    "MENU_SECCION": "SectionItem",
                    "MENU_COLOR": "#000000"
                }],
                "MENU_SERVICIOS": [{
                    "MENU_ID": "3",
                    "MENU_TITLE": "Gestion"
                }, {
                    "MENU_ID": "5",
                    "MENU_TITLE": "Consultas"
                }],
                "SUBMENU_SERVICIOS": [{
                    "MENU_ID": "3",
                    "MENU_TITLE": "Gestion",
                    "MENU_SLUG": "Gestion"
                }, {
                    "MENU_ID": "5",
                    "MENU_TITLE": "Consultas",
                    "MENU_SLUG": "Consultas"
                }],
                "MENU_FOOTER": [{
                    "MENU_TITLE": "Terminos y condiciones",
                    "MENU_URL": "/terminosycondiciones",
                    "MENU_TARGET": "_self",
                    "MENU_ICON": "",
                    "MENU_SECCION": ""
                }, {
                    "MENU_TITLE": "Politica de Privacidad",
                    "MENU_URL": "/politica-de-privacidad",
                    "MENU_TARGET": "_self",
                    "MENU_ICON": "",
                    "MENU_SECCION": "",
                    "MENU_COLOR": "#000000"
                }, {
                    "MENU_TITLE": "Contactenos",
                    "MENU_URL": "/contactenos",
                    "MENU_TARGET": "_self",
                    "MENU_ICON": "",
                    "MENU_SECCION": "",
                    "MENU_COLOR": "#000000"
                }, {
                    "MENU_TITLE": "Trabaja con nosotros",
                    "MENU_URL": "/trabaja-con-nosotros",
                    "MENU_TARGET": "_self",
                    "MENU_ICON": "",
                    "MENU_COLOR": "#000000"
                }, {
                    "MENU_TITLE": "Juego Responsable",
                    "MENU_URL": "/juego-responsable",
                    "MENU_TARGET": "_self",
                    "MENU_ICON": "",
                    "MENU_COLOR": "#000000"
                }, {
                    "MENU_TITLE": "Afiliados",
                    "MENU_URL": "https://afiliados.doradobet.com",
                    "MENU_TARGET": "_blank",
                    "MENU_ICON": "",
                    "MENU_COLOR": "#000000"
                }, {
                    "MENU_TITLE": "Nuestras tiendas",
                    "MENU_URL": "/geolocalizacion",
                    "MENU_TARGET": "_self",
                    "MENU_ICON": "",
                    "MENU_COLOR": "#000000"
                }, {
                    "MENU_TITLE": "Resultados",
                    "MENU_URL": "/resultados",
                    "MENU_TARGET": "_self",
                    "MENU_ICON": "",
                    "MENU_COLOR": "#000000"
                }, {
                    "MENU_TITLE": "Blog",
                    "MENU_URL": "https://blog.doradobet.com",
                    "MENU_TARGET": "_blank",
                    "MENU_ICON": "",
                    "MENU_COLOR": "#000000"
                }],
                "MENU_VIRTUAL": [{
                    "MENU_TITLE": "Copa del Mundo 2018",
                    "MENU_URL": "/virtual/copa-mundo",
                    "MENU_ICON": "assets/images/icons/sports.svg",
                    "MENU_SECCION": "SectionItem"
                }, {
                    "MENU_TITLE": "Eurocopa",
                    "MENU_URL": "/virtual/eurocopa",
                    "MENU_ICON": "assets/images/icons/sports.svg",
                    "MENU_SECCION": "SectionItem"
                }, {
                    "MENU_TITLE": "Futbol",
                    "MENU_URL": "/virtual/futbol",
                    "MENU_ICON": "assets/images/icons/sports.svg",
                    "MENU_SECCION": "SectionItem"
                }, {
                    "MENU_TITLE": "Carrera de caballos",
                    "MENU_URL": "/virtual/carrera-de-caballos",
                    "MENU_ICON": "assets/images/icons/sports.svg",
                    "MENU_SECCION": "SectionItem",
                    "MENU_COLOR": "#000000"
                }, {
                    "MENU_TITLE": "Carrera de perros",
                    "MENU_URL": "/virtual/carrera-de-perros",
                    "MENU_ICON": "assets/images/icons/sports.svg",
                    "MENU_SECCION": "SectionItem",
                    "MENU_COLOR": "#000000"
                }, {
                    "MENU_TITLE": "Baloncesto",
                    "MENU_URL": "/virtual/baloncesto",
                    "MENU_ICON": "assets/images/icons/sports.svg",
                    "MENU_SECCION": "SectionItem",
                    "MENU_COLOR": "#000000"
                }, {
                    "MENU_TITLE": "Tennis",
                    "MENU_URL": "/virtual/tennis",
                    "MENU_SECCION": "SectionItem",
                    "MENU_COLOR": "#000000"
                }],
                "FORM_REGISTRO": {
                    "RESULT_CORRECT": "El usuario se ha registrado correctamente. Revise su bandeja de entrada y/o su bandeja de correo no deseado donde encontrara las instrucciones para activar su cuenta. Sugerimos adicionar el correo de notificacion de su registro como un email conocido y evitar asi que futuras notificaciones lleguen a su correo no deseado.",
                    "STEP1": "Informacion Personal",
                    "STEP2": "Informacion Geografica",
                    "STEP3": "Informacon de Seguridad",
                    "REQUIERE_TEXT": "Campos Requeridos",
                    "INPUT_NOMBRE1": "Primer Nombre",
                    "INPUT_NOMBRE2": "Segundo Nombre",
                    "INPUT_APELLIDO1": "Primer Apellido",
                    "INPUT_APELLIDO2": "Segundo Apellido",
                    "INPUT_SEXO": "Sexo",
                    "INPUT_NACIONALIDAD": "Nacionalidad",
                    "INPUT_TIPO_DOC": "Tipo de Documento",
                    "INPUT_NUM_DOC": "Numero de Identificacion",
                    "INPUT_PAIS_NACIM": "Pais de Nacimiento",
                    "INPUT_DEPTO_NACIM": "Provincia de Nacimiento",
                    "INPUT_CIUDAD_NACIM": "Ciudad de Nacimiento",
                    "INPUT_FECHA_NACIM": "Fecha de Nacimiento",
                    "INPUT_TELEFONO_CELULAR": "Telefono Celular",
                    "INPUT_TELEFONO_FIJO": "Telefono Fijo",
                    "INPUT_PAIS_RESIDENCIA": "Pais de Residencia",
                    "INPUT_DEPTO_RESIDENCIA": "Provincia de Residencia",
                    "INPUT_CIUDAD_RESIDENCIA": "Ciudad de Residencia",
                    "INPUT_DIRECCION_DOMICILIO": "Direccion del Domicilio",
                    "INPUT_OCUPACION": "Ocupacion",
                    "INPUT_ORIGEN_FONDOS": "Origen de fondos",
                    "INPUT_INGRESOS": "Ingresos mensuales equivalente a",
                    "INPUT_CORREO_ELECTRONICO": "Correo electronico",
                    "INPUT_CORREO_ELECTRONICO_CONFIRMAR": "Confirmar correo electronico",
                    "INPUT_IDIOMA": "Idioma",
                    "INPUT_CLAVE": "Clave:",
                    "INPUT_CONFIRMACION_CLAVE": "Confirmacion de Clave:",
                    "CONFIRMACION_EDAD_TEXT": "Soy mayor de 18 años y he leído y aceptado los Terminos y condiciones generales, la politica de privacidad y la politica de juego responsable de Doradobet",
                    "INFO_TEXT": "Le llegará una notificación a su correo electrónico y después de la confirmación desu parte, podra acercarse a cualquiera de nuestras sedes en los horarios disponibles para la activación. En caso de no ser correctos los datos, el registro será rechazado en cualquier momento y la cuenta será suspendida."
                },
                "FORM_REGISTRORAPIDO": {
                    "HEADER_TEXT": "",
                    "RESULT_CORRECT": "El usuario se ha registrado correctamente.",
                    "STEP1": "Informacion Personal",
                    "STEP2": "Informacion Geografica",
                    "STEP3": "Informacon de Seguridad",
                    "REQUIERE_TEXT": "Campos Requeridos",
                    "INPUT_NOMBRES": "Nombre",
                    "INPUT_APELLIDOS": "Apellido",
                    "INPUT_SEXO": "Sexo",
                    "INPUT_NACIONALIDAD": "Nacionalidad",
                    "INPUT_TIPO_DOC": "Tipo de Documento",
                    "INPUT_NUM_DOC": "Numero de Identificacion",
                    "INPUT_PAIS_NACIM": "Pais de Nacimiento",
                    "INPUT_DEPTO_NACIM": "Provincia de Nacimiento",
                    "INPUT_CIUDAD_NACIM": "Ciudad de Nacimiento",
                    "INPUT_FECHA_NACIM": "Fecha de Nacimiento",
                    "INPUT_TELEFONO_CELULAR": "Telefono Celular",
                    "INPUT_TELEFONO_FIJO": "Telefono Fijo",
                    "INPUT_PAIS_RESIDENCIA": "Pais de Residencia",
                    "INPUT_DEPTO_RESIDENCIA": "Provincia de Residencia",
                    "INPUT_CIUDAD_RESIDENCIA": "Ciudad de Residencia",
                    "INPUT_DIRECCION_DOMICILIO": "Direccion del Domicilio",
                    "INPUT_OCUPACION": "Ocupacion",
                    "INPUT_ORIGEN_FONDOS": "Origen de fondos",
                    "INPUT_INGRESOS": "Ingresos mensuales equivalente a",
                    "INPUT_CORREO_ELECTRONICO": "Correo electronico",
                    "INPUT_IDIOMA": "Idioma",
                    "INPUT_CLAVE": "Clave:",
                    "INPUT_CONFIRMACION_CLAVE": "Confirmacion de Clave:",
                    "INPUT_EMPRESA": "Empresa",
                    "INPUT_SKYPE": "Skype",
                    "INPUT_OBSERVACION": "Observacion",
                    "CONFIRMACION_EDAD_TEXT": "Soy mayor de 18 años y he leído y aceptado los Terminos y condiciones generales, la politica de privacidad y la politica de juego responsable de Doradobet",
                    "INFO_TEXT": "Le llegará una notificación a su correo electrónico y después de la confirmación desu parte, podra acercarse a cualquiera de nuestras sedes en los horarios disponibles para la activación. En caso de no ser correctos los datos, el registro será rechazado en cualquier momento y la cuenta será suspendida."
                },
                "FORM_TRABAJACONNOSOTROS": {
                    "HEADER_TEXT": "En Doradobet buscamos agentes y comercializadores que nos ayuden a seguir formando la marca con mayor proyección en latinoamerica.",
                    "RESULT_CORRECT": "El mensaje se ha enviado correctamente. Muchas gracias por su interes en nuestra plataforma, pronto nos comunicaremos con usted.",
                    "STEP1": "Informacion Personal",
                    "STEP2": "Informacion Geografica",
                    "STEP3": "Informacon de Seguridad",
                    "REQUIERE_TEXT": "Campos Requeridos",
                    "INPUT_NOMBRES": "Nombre",
                    "INPUT_APELLIDOS": "Apellido",
                    "INPUT_SEXO": "Sexo",
                    "INPUT_NACIONALIDAD": "Nacionalidad",
                    "INPUT_TIPO_DOC": "Tipo de Documento",
                    "INPUT_NUM_DOC": "Numero de Identificacion",
                    "INPUT_PAIS_NACIM": "Pais de Nacimiento",
                    "INPUT_DEPTO_NACIM": "Provincia de Nacimiento",
                    "INPUT_CIUDAD_NACIM": "Ciudad de Nacimiento",
                    "INPUT_FECHA_NACIM": "Fecha de Nacimiento",
                    "INPUT_TELEFONO_CELULAR": "Telefono Celular",
                    "INPUT_TELEFONO_FIJO": "Telefono Fijo",
                    "INPUT_PAIS_RESIDENCIA": "Pais de Residencia",
                    "INPUT_DEPTO_RESIDENCIA": "Provincia de Residencia",
                    "INPUT_CIUDAD_RESIDENCIA": "Ciudad de Residencia",
                    "INPUT_DIRECCION_DOMICILIO": "Direccion del Domicilio",
                    "INPUT_OCUPACION": "Ocupacion",
                    "INPUT_ORIGEN_FONDOS": "Origen de fondos",
                    "INPUT_INGRESOS": "Ingresos mensuales equivalente a",
                    "INPUT_CORREO_ELECTRONICO": "Correo electronico",
                    "INPUT_IDIOMA": "Idioma",
                    "INPUT_CLAVE": "Clave:",
                    "INPUT_CONFIRMACION_CLAVE": "Confirmacion de Clave:",
                    "INPUT_EMPRESA": "Empresa",
                    "INPUT_SKYPE": "Skype",
                    "INPUT_OBSERVACION": "Observacion",
                    "CONFIRMACION_EDAD_TEXT": "Soy mayor de 18 años y he leído y aceptado los Terminos y condiciones generales, la politica de privacidad y la politica de juego responsable de Doradobet",
                    "INFO_TEXT": "Le llegará una notificación a su correo electrónico y después de la confirmación desu parte, podra acercarse a cualquiera de nuestras sedes en los horarios disponibles para la activación. En caso de no ser correctos los datos, el registro será rechazado en cualquier momento y la cuenta será suspendida."
                },
                "FORM_CONTACTO": {
                    "HEADER_TEXT": "En Doradobet buscamos agentes y comercializadores que nos ayuden a seguir formando la marca con mayor proyección en latinoamerica.",
                    "RESULT_CORRECT": "Se ha enviado correctamente el mensaje, muchas gracias por querer contactarnos, lo antes posible te estaremos respondiendo.",
                    "STEP1": "Informacion Personal",
                    "STEP2": "Informacion Primordial",
                    "STEP3": "Informacion Importante",
                    "STEP4": "Configuracion Cuenta",
                    "REQUIERE_TEXT": "Campos Requeridos",
                    "INPUT_NOMBRES": "Nombre completo",
                    "INPUT_APELLIDOS": "Apellido",
                    "INPUT_SEXO": "Sexo",
                    "INPUT_NACIONALIDAD": "Nacionalidad",
                    "INPUT_TIPO_DOC": "Tipo de Documento",
                    "INPUT_NUM_DOC": "Numero de Identificacion",
                    "INPUT_PAIS_NACIM": "Pais de Nacimiento",
                    "INPUT_DEPTO_NACIM": "Provincia de Nacimiento",
                    "INPUT_CIUDAD_NACIM": "Ciudad de Nacimiento",
                    "INPUT_FECHA_NACIM": "Fecha de Nacimiento",
                    "INPUT_TELEFONO_CELULAR": "Telefono Celular",
                    "INPUT_TELEFONO_FIJO": "Telefono",
                    "INPUT_PAIS_RESIDENCIA": "Pais de Residencia",
                    "INPUT_DEPTO_RESIDENCIA": "Provincia de Residencia",
                    "INPUT_CIUDAD_RESIDENCIA": "Ciudad de Residencia",
                    "INPUT_DIRECCION_DOMICILIO": "Direccion del Domicilio",
                    "INPUT_OCUPACION": "Ocupacion",
                    "INPUT_ORIGEN_FONDOS": "Origen de fondos",
                    "INPUT_INGRESOS": "Ingresos mensuales equivalente a",
                    "INPUT_CORREO_ELECTRONICO": "Correo electronico",
                    "INPUT_IDIOMA": "Idioma",
                    "INPUT_CLAVE": "Clave:",
                    "INPUT_CONFIRMACION_CLAVE": "Confirmacion de Clave:",
                    "INPUT_EMPRESA": "Empresa",
                    "INPUT_SKYPE": "Skype",
                    "INPUT_OBSERVACION": "Mensaje",
                    "CONFIRMACION_EDAD_TEXT": "Soy mayor de 18 años y he leído y aceptado los Terminos y condiciones generales, la politica de privacidad y la politica de juego responsable de Doradobet",
                    "INFO_TEXT": "Le llegará una notificación a su correo electrónico y después de la confirmación desu parte, podra acercarse a cualquiera de nuestras sedes en los horarios disponibles para la activación. En caso de no ser correctos los datos, el registro será rechazado en cualquier momento y la cuenta será suspendida."
                },
                "FORM_RECUPERARCLAVE": {
                    "HEADER_TEXT": "  Nota importante: esta funcionalidad le permite tanto recuperar su clave en caso de haberla olvidado como también desbloquear su usuario cuando usted ha sobrepasado el máximo número de intentos fallidos de acceso. El correo electrónico que debe suministrar es el mismo que está asociado a su cuenta de Doradobet. Una vez haga clic en el botón ENVIAR INFORMACIÓN, por favor revise su correo donde se le darán más instrucciones: *",
                    "RESULT_CORRECT": "Se ha enviado correctamente el mensaje, te llegara un correo electronico con la información.",
                    "INPUT_CORREO_ELECTRONICO": "Correo electronico"
                },
                "PROVIDERS": [{
                    "URL": "",
                    "NAME": "BBVA",
                    "ICON": "https://images.doradobet.com/productos/payment/icon/color/3logoBcp.png"
                }, {
                    "URL": "",
                    "NAME": "Interbank",
                    "ICON": "https://images.doradobet.com/productos/payment/icon/color/5logoInterbank.png"
                }, {
                    "URL": "",
                    "NAME": "Scotiabank",
                    "ICON": "https://images.doradobet.com/productos/payment/icon/color/4logoScotiabank.png"
                }, {
                    "URL": "",
                    "NAME": "BBVA",
                    "ICON": "https://images.doradobet.com/productos/payment/icon/color/2logoBbva.png"
                }, {
                    "URL": "",
                    "NAME": "Safetypay",
                    "ICON": "https://images.doradobet.com/productos/payment/icon/color/1logoSafetypay.png"
                }, {
                    "URL": "",
                    "NAME": "Pagoefectivo",
                    "ICON": "https://images.doradobet.com/productos/payment/icon/color/6logoPagoefectivo.png"
                }, {
                    "URL": "",
                    "NAME": "PaySafecard",
                    "ICON": "https://images.doradobet.com/productos/payment/icon/logo-paysafecard.svg"
                }],
                "SOCIAL_NEWTWORKS": [{
                    "SOCIAL_URL": "https://www.facebook.com/DoradoBet-Perú-882370918617257/",
                    "SOCIAL_NAME": "Facebook",
                    "SOCIAL_ICON": "<i class=#####fa fa-facebook-square fa-2x##### aria-hidden=#####true#####></i>"
                }, {
                    "SOCIAL_URL": "https://twitter.com/doradobet/",
                    "SOCIAL_NAME": "Twitter",
                    "SOCIAL_ICON": "<i class=#####fa fa-twitter-square fa-2x##### aria-hidden=#####true#####></i>"
                }, {
                    "SOCIAL_URL": "https://www.instagram.com/doradobetlatam/",
                    "SOCIAL_NAME": "Instagram",
                    "SOCIAL_ICON": "<i class=#####fa fa-instagram fa-2x##### aria-hidden=#####true#####></i>"
                }, {
                    "SOCIAL_URL": "https://plus.google.com/u/1/109119436366679125879/",
                    "SOCIAL_NAME": "Google Plus",
                    "SOCIAL_ICON": "<i class=#####fa fa-google-plus-square fa-2x##### aria-hidden=#####true#####></i>"
                }, {
                    "SOCIAL_URL": "https://www.youtube.com/channel/UCuxJjrf89zWId29oOBq7Iqg",
                    "SOCIAL_NAME": "Youtube ",
                    "SOCIAL_ICON": "<i class=#####fa fa-youtube-square fa-3x##### aria-hidden=#####true#####></i>"
                }],
                "TERMINOSYCONDICIONES": [{
                    "TERMINOS_SLUG": "tutoriales",
                    "TERMINOS_TITLE": "Tutoriales",
                    "TERMINOS_CONTENT": [{
                        "TERMINOS_CONTENT_TITLE": "¿ Cómo Registrarme ?",
                        "TERMINOS_CONTENT_CONTENT": "",
                        "VideoURL": "https://www.youtube.com/embed/FajTquueQGo",
                        "isVideo": true,
                        "isExpanded": true
                    }, {
                        "TERMINOS_CONTENT_TITLE": "¿ Cómo Jugar ?",
                        "TERMINOS_CONTENT_CONTENT": "",
                        "VideoURL": "https://www.youtube.com/embed/XDuD9KP9xYI",
                        "isVideo": true
                    }, {
                        "TERMINOS_CONTENT_TITLE": "¿ Cómo Jugar en virtuales ?",
                        "TERMINOS_CONTENT_CONTENT": "",
                        "VideoURL": "https://www.youtube.com/embed/kDhUZb1tbLg",
                        "isVideo": true
                    }, {
                        "TERMINOS_CONTENT_TITLE": "¿ Cómo Jugar en eSports ?",
                        "TERMINOS_CONTENT_CONTENT": "",
                        "VideoURL": "https://www.youtube.com/embed/yLfO_-Qxe8Y",
                        "isVideo": true
                    }]
                }, {

                    "TERMINOS_SLUG": "promociones-bonos",
                    "TERMINOS_TITLE": "Promociones y Bonos",
                    "TERMINOS_CONTENT": []
                }, {
                    "TERMINOS_SLUG": "condiciones-generales",
                    "TERMINOS_TITLE": "1. Condiciones generales",
                    "TERMINOS_CONTENT": [{
                        "TERMINOS_CONTENT_TITLE": "Condiciones generales",
                        "TERMINOS_CONTENT_CONTENT": "1.1 Cada página web bajo esta licencia es un nombre de marca (White Label) operado por iTains N.V., una compañía registrada el 18 de Marzo del 2011 bajo las leyes de Curazao, con un registro de compañía No122623 y bajo la licencia No 1668/JAZ otorgado por el Gobierno de Curazao el 1ro de Julio del 2011. Cualquier referencia a ¨La Compañía¨significa la pagina web y iTains N.V. iTains N.V. es una compañía de I-Tainment Europe LTD, una compañía incorporada en Malta con el numero de registro C50470 y con dirección registrada en 20 Bisazza Str, SML1640, Sliema, Malta. I-Tainment Europe LTD es un procesador de pagos. <br/><br/> 1.2  La Empresa se reserva el derecho a suspender cualquier jugada donde se haya detectado error humano después de haberse comparado los resultados y en base a las demás casas de apuestas. <br/><br/>1.3 La Empresa se reserva el derecho de aceptar, limitar o rechazar cualquier apuesta antes de recibirla. <br/><br/>1.4 La empresa se reserva el derecho de agregar, quitar o cambiar sus reglas, términos y condiciones sin previo aviso. <br/><br/>1.5 La empresa guardará por noventa (90) días a partir de la fecha del evento, registros de todos los tickets, logros, marcadores finales para la protección de los clientes y de la empresa, o para verificar en caso de un error humano o por parte de la plataforma. <br/><br/>1.6 La empresa no reconoce juegos suspendidos, protestados o decisiones equivocadas, para efectos de apuesta. <br/><br/>1.7 Cuando un evento sea suspendido, aplazado o abandonado se considerará un evento como “no acción” a efectos de la apuesta a no ser que el partido se juegue dentro de la misma semana (la semana finalizaría el día domingo) y si es el caso en el que el evento sea jugado, la apuesta prevalecerá. Por otro lado, si un partido del domingo se aplaza hasta la noche del lunes siguiente debido a razones ajenas, todas las apuestas a este evento prevalecerán. <br/><br/>1.8 Si un evento, en el que ya exista un ticket de apuesta, se lleva a cabo antes de la fecha y hora prevista inicialmente se incluirá el partido (prevalecerá la apuesta), siempre y cuando la apuesta se realice antes de la nueva fecha y hora de inicio. Si por el contrario, la apuesta fue realizada posterior al inicio del partido, la casa se reserva el derecho de eliminar o no este ticket sea o no ganador si así lo considera. <br/><br/>Se anulará cualquier evento que se suspenda antes de que se completen los minutos denotados como oficiales de juego, excepto las apuestas cuyo resultado se determinan con anterioridad a la suspensión del partido. Por ejemplo, las apuestas a “primer goleador”, “anotará en cualquier momento” y “ultimo anotador” prevalecerán, siempre y cuando los goles sean anotados con anterioridad a la suspensión del partido.",
                        "isExpanded": true
                    }]
                }, {
                    "TERMINOS_SLUG": "control-de-riesgo",
                    "TERMINOS_TITLE": "2. Control de riesgo.",
                    "TERMINOS_CONTENT": [{
                        "TERMINOS_CONTENT_TITLE": "Control de riesgo",
                        "TERMINOS_CONTENT_CONTENT": "Nuestro control de riesgo se construye a nivel interno y en base a nuestros términos y condiciones. Cuando se infrinja alguna condición en la oferta de los eventos aprobados en el mercado de apuestas o exista evidencia de que un mismo individuo o grupo de individuos ha realizado una serie de apuestas (Ej. Cuando se identifican patrones de apuestas comunes en el mismo encuentro/ mercado entre cuentas etc.) Donde dichos individuos o grupos quieran aprovecharse de una ganancia potencial de ingreso, pagos aumentados y apuestas donde no se comprometa ningún tipo de riesgo o cualquier otra promoción que garantice las ganancias independientes del resultado, tanto de manera individual o como parte de un grupo. En dichos casos la empresa se reserva el derecho a determinar las apuestas con el precio correcto, anular las apuestas sin riesgo o anular cualquier tipo de apuesta que vaya en contra del control de riesgo que representa cada uno de los eventos ofertados y seleccionados en la parrilla de apuestas, en caso tal de que eventos seleccionados no hayan tenido lugar, la empresa reembolsará el valor apostado al disponible para jugar.<br/>La empresa se reserva el derecho de presentar una penalidad a dichos usuarios y dependiendo de la gravedad del caso, inactivar y ser retiradas respectivas cuentas de juego.<br/>La empresa se reserva el derecho de suspender un mercado y/o cancelar una apuesta en cualquier momento. Cuando se suspende una apuesta, las apuestas realizadas serán rechazadas y nos reservamos el derecho a cesar las apuestas sobre un evento o apartado concreto en cualquier momento y sin previo aviso.",
                        "isExpanded": true
                    }]
                }, {
                    "TERMINOS_SLUG": "registros-usuarios-online-y-cuenta-de-usuarios",
                    "TERMINOS_TITLE": "3. Registros Usuarios Online y Cuenta de Usuarios",
                    "TERMINOS_CONTENT": [{
                        "TERMINOS_CONTENT_TITLE": "Registros Usuarios Online y Cuenta de Usuarios",
                        "TERMINOS_CONTENT_CONTENT": "3.1 Solo pueden registrarse personas mayores de edad y una única vez. <br/><br/>3.2 La persona que se registra se compromete a suministrar toda la información verídica en cada uno de los campos solicitados en el proceso de registro La empresa se reserva el derecho a cancelar el registro de una persona si se encuentra falsedad o error en la información suministrada. El correo electrónico reportado en el proceso de registro por el usuario, es la base de su registro y no podrá ser modificado. <br/><br/>3.3 Previo a la aceptación de la solicitud de registro por parte de nuestra plataforma , la persona que desee registrarse debe leer y aceptar los términos y condiciones que están publicados en el sitio web, con lo cual adicionalmente acepta los cambios que la empresa realice a futuro en los mismos. <br/><br/>3.4 En el caso de que usted quiera retirar fondos recibidos a través de bonos promocionales, sin haber cumplido con los requisitos que se requieren, tenga restricciones de retiros, o problemas generales de elegibilidad, usted perderá el monto completo de dicho bono promocional y cualquier ganancia resultante del mismo y la Compañía lo deducirá de su Cuenta. Un \"cliente\" es un individuo, que tiene una cuenta, y por lo tanto una relación contractual con la empresa, una “cuenta” es una registro creado por un cliente, para transacciones de buena fe y con un único propósito de formar una relación comercial con la empresa y con el estricto propósito de realizar apuestas en diferentes modalidades ofrecidas por nuestro portal. <br/><br/>3.5  La persona que se registra recibe un usuario o login para ingresar a la plataforma , una clave de acceso y un número de cliente que lo identifica. El usuario comprende y acepta que es responsable por el manejo seguro de su usuario, clave de acceso al sistema y numero de cliente y se compromete a cambiar la clave periódicamente. Además exonera a la empresa de toda responsabilidad por el mal uso de las mismas. <br/><br/>3.6 Todas las marcas registradas, marcas de servicio y nombres comerciales así también como las imágenes, gráficos, textos, conceptos o metodologías (“Derechos de autorpatentes”) que se encuentran en nuestra página web, el programa para clientes, los materiales que contiene y todo el contenido presente son de exclusiva propiedad de la empresa y/o los proveedores o socios de la compañía. Los clientes no están en ningún derecho de sacar provecho o realizar uso indebido de los derechos de autor de lo antes mencionado.  <br/><br/>3.7  Toda persona debidamente registrada en nuestra plataforma, recibe una cuenta de usuario que consta de dos componentes: Saldo disponible para jugar y Saldo disponible para retirar. <br/><br/>Saldo disponible para jugar: Se abonará a este saldo el valor de las recargas realizadas por el usuario, las bonificaciones recibidas por promocionales y adicional reembolsos por apuestas “NO ACCIÓN”. Se retirará de este saldo el valor de las apuestas realizadas por el cliente. No se aceptarán solicitudes de retiro de este saldo. <br/><br/>Saldo disponible para retirar: Se abonará a este saldo el valor de los premios ganados por el usuario. Se retira de esta saldo lo correspondiente a las notas de retiro cobradas por el usuario. También el sistema retirará de este saldo el valor correspondiente a las apuestas realizadas por el usuario cuando este hubiese agotado su saldo disponible para apostar.",
                        "isExpanded": true
                    }]
                }, {
                    "TERMINOS_SLUG": "topes-y-valor-apuesta",
                    "TERMINOS_TITLE": "4. Topes y Valor Apuesta",
                    "TERMINOS_CONTENT": [{
                        "TERMINOS_CONTENT_TITLE": " Topes y Valor Apuesta",
                        "TERMINOS_CONTENT_CONTENT": "4.1 La apuesta mínima permitida en nuestro sitio web esta determinada por el tipo de moneda que se use equivalente a USD 1. <br/><br/>4.2 Nos reservamos el derecho de asignar topes máximos de premiación y de apuestas diarias para cada tipo de apuesta y usuario. La plataforma, por medio de una ventana emergente, indicará al usuario cuando la apuesta solicitada es rechazada por superar los topes máximos de premiación o de ventas diarias permitidas. <br/><br/>4.3 Un usuario puede solicitar el aumento de un único (Ventas diarias), para que esta solicitud pueda generarse de manera exitosa, nos reservamos el derecho de realizar un previo análisis para que el usuario pueda ser notificado por dicha solici-tud. <br/><br/>4.4 Para efectos de apuestas en mercado Par/Impar, Si el partido acaba en empate a 0-0 se determinará como un número par de goles.",
                        "isExpanded": true
                    }]
                }, {
                    "TERMINOS_SLUG": "modalidades-y-tipos",
                    "TERMINOS_TITLE": "5. Modalidades y Tipos",
                    "TERMINOS_CONTENT": [{
                        "TERMINOS_CONTENT_TITLE": "Modalidades y Tipos",
                        "TERMINOS_CONTENT_CONTENT": "5.1 Modalidades: Nuestra plataforma recibe apuestas deportivas en tres modalidades diferentes.<br/><br/>5.1.1  Apuestas prematch: Se reciben antes de iniciar un evento real. Todas las apuestas prematch deberán ser realizadas antes de que inicie el evento. Por errores o modificaciones de último momento en la hora de inicio de un evento, nos reservamos el derecho a cancelar cualquier apuesta prematch realizada después de iniciado el mismo y el valor de las apuestas correspondientes será reembolsado al saldo disponible para jugar.<br/><br/> 5.1.2 Apuestas Prelive live: Esta Modalidad de apuestas se encuentran en nuestro portal en la pestaña superior apuestas en vivo, se reciben antes de iniciar el evento y durante el desenvolvimiento real del evento, estas últimas con una variabl grandiosa y es el movimiento de cuotas y la posibilidad de acceder a diferentes estadísticas que optimizan una posible victoria. Apostar en vivo enriquece el factor entretenimiento al máximo, ofreciendo a la vez nuevas posibilidades de hacer dinero. Las apuestas en vivo se han ido convirtiendo en las favoritas en el mercado.<br/><br/>5.1.3 Para efectos de apuestas en mercado Par/Impar, Si el partido acaba en empate a 0-0 se determinará como un número par de goles. <br/><br/>5.2 Tipos: Directas, Parlay (Con opción de seleccionar apuestas secundarias) <br/><br/> 5.2.1 Apuestas directas: La apuesta se realiza en una sola línea al resultado de un solo evento. El valor del premio resulta de multiplicar el logro o cuota asignada al evento por el valor apostado por el usuario.<br/><br/>5.2.2 Apuestas Parlay: La apuesta se realiza en dos o más líneas ( máximo 15) al resultado de varios eventos diferentes de manera simultánea, es decir, para que un Parlay se considere ganador la determinación de cada uno de los eventos incluidos debe ser acertada. El valor del premio resulta de multiplicar entre si el logro o cuota asignada a cada uno de los eventos incluidos en la apuesta por el valor total apostado.",
                        "isExpanded": true
                    }]
                }, {
                    "TERMINOS_SLUG": "determinacion-de-las-jugadas",
                    "TERMINOS_TITLE": "6. Determinación de las Jugadas",
                    "TERMINOS_CONTENT": [{
                        "TERMINOS_CONTENT_TITLE": "6.1",
                        "TERMINOS_CONTENT_CONTENT": "6.1 Nos disponemos de un plazo de 24 horas hábiles contadas a partir de la terminación oficial de un evento para determinar el ganador o ganadores de los diferentes mercados ofertados a diferentes eventos. <br/><br/>6.2 En apuestas directas sobre eventos determinados como NO ACCION, la plataforma NO generará un premio pero si se realiza la devolución del dinero reembolsándose al saldo disponible para apostar. <br/><br/>6.3 En apuestas Parlay y combinadas sobre eventos determinados como NO ACCION , la plataforma No genera premios sobre dichos eventos , pero si redistribuye entre las demás líneas o apuestas el valor correspondiente a la apuesta sobre el evento determinado como No acción. En caso de denominarse un evento como no acción en un parlay de dos líneas o combinaciones, esta pasará a ser una apuesta directa. <br/><br/>6.4 Para la determinación de ganadores se considerará el resultado del evento hasta el tiempo reglamentario más el tiempo de reposición determinado por la autoridad en el evento. Los tiempos adicionales no cuentan en la determinación del resultado. A excepción de finales torneos o ligas donde sí se tendrán en cuenta los resultados obtenidos en tiempos complementarios.*Para más información ir al numeral condiciones específicas por deporte.",
                        "isExpanded": true
                    }]
                }, {
                    "TERMINOS_SLUG": "recargas-y-retiros",
                    "TERMINOS_TITLE": "7. Recargas y Retiros",
                    "TERMINOS_CONTENT": [{
                        "TERMINOS_CONTENT_TITLE": "7.1 Recargas",
                        "TERMINOS_CONTENT_CONTENT": "7.1 Recargas.<br/> <br/>Para hacer uso de la cuenta dentro de nuestra plataforma un usuario debe recargar acercándose a cualquiera de los puntos de venta habilitados para recargar, indicar su número de cliente, el valor a recargar y entregar el dinero correspondiente. Información sobre los puntos de recarga se consiguen a través de nuestro chat en vivo, ubicado en nuestra página web, parte inferior izquierda. La empresa NO realiza anulación o devolución de recargas.",
                        "isExpanded": true
                    }, {
                        "TERMINOS_CONTENT_TITLE": "7.2 Retiros Notas de retiro.",
                        "TERMINOS_CONTENT_CONTENT": "7.2 Retiros Notas de retiro. <br/><br/> • Para realizar un retiro, el usuario ONLINE debe en primera instancia generar la nota de retiro correspondiente.<br/><br/> • El valor máximo a retirar es igual al saldo disponible para retirar.<br/><br/>• El usuario ONLINE puede eliminar una nota de retiro ya generadaNsi posteriormente decide no realizar el retiro. <br/><br/> Una nota de retiro generada se paga una sola vez, es decir, copias de la misma no serán pagadas porque el sistema solo aceptará el cobro la primera vez. Para generar la nota de retiro el usuario debe ingresar a nuestra plataforma, ir al menú principal y elegir la opción Gestión>>Generar nota de retiro, en esta opción el usuario podrá verificar la cantidad o saldo disponible para retirar; teniendo en cuenta esto, se genera la nota de retiro por el valor deseado, imprimir o guarda imagen de la nota de retiro y para finalizar deberá dirigirse a cualquiera de nuestro puntos de venta y presentar la cédula para poder redimir la nota de retiro correspondiente."
                    }, {
                        "TERMINOS_CONTENT_TITLE": "7.3 Retiros Cobro premios de Tickets ganadores.",
                        "TERMINOS_CONTENT_CONTENT": "7.3 Si no se trata de un usuario ONLINE sino de un cliente que realizo un ticket en un punto de venta y dicha apuesta resulta ganadora, el cliente debe dirigirse al mismo punto de venta donde realizo la apuesta para que allí sea cancelado el valor del premio correspondiente. El cliente debe presentar el ticket original (o reimpreso) por el sistema, en buen estado y legible.<br/><br/>Un ticket ganador se paga una única vez, es decir, reimpresiones de un ticket no serán pagadas si el sistema ya registra el premio como pagado."
                    }]
                }, {
                    "TERMINOS_SLUG": "condiciones-especificas-por-deporte",
                    "TERMINOS_TITLE": "8. Condiciones Específicas por Deporte",
                    "TERMINOS_CONTENT": [{
                        "TERMINOS_CONTENT_TITLE": "8.1 Futbol.",
                        "TERMINOS_CONTENT_CONTENT": "8.1.1 Resultados válidos: Se toma como resultado el que se presente dentro del tiempo reglamentario, es decir 90 minutos de juego; más el tiempo añadido por el árbitro en razón del tiempo que se hubiere perdido a consecuencia de lesiones y sustituciones. No se tendrán en cuenta para determinar ganadores, el tiempo extra, gol de oro y la definición del partido por penaltis.<br/><br/>8.1.2 Partido suspendido: Se anulará cualquier partido de futbol que se suspenda antes de que se completen los 90 minutos de juego, excepto las apuestas cuyo resultado se determinan con anterioridad a la suspensión del partido. Por ejemplo, las apuestas a “primer goleador”, “anotará en cualquier momento” y “ultimo anotador”. Las anteriores prevalecerán, siempre y cuando los goles sean anotados con anterioridad a la suspensión del partido. <br/><br/>Como excepción a la regla de “partido suspendido”, los partidos de clubs sudamericanos donde las apuestas al “resultado final” y a la “doble oportunidad” (ambas directas y antes del partido) se determinarán según el resultado en el momento en que se suspenda el partido, siempre y cuando la liga en cuestión ofrezca dicho resultado como válido, para que las apuestas prevalezcan ambos participantes deberán competir.<br/>A continuación Mercado de apuestas:<br/>Apuesta a un evento (1X2): En este agrupador se pueden dar tres posibilidades:<br/>Que gane el equipo local (1), que el marcador final sea un empate (x) o que el ganador sea el equipo visitante (2). Importante: El equipo local siempre se presenta primero en la parrilla.*<br/>• 1 Victoria local<br/>• X Empate<br/>• 2 Victoria visitante",
                        "isExpanded": true
                    }, {
                        "TERMINOS_CONTENT_TITLE": "8.2 Básquetbol.",
                        "TERMINOS_CONTENT_CONTENT": "A la hora de apostar en Básquetbol tenga en cuenta que: Para efectos de apuesta, a menos que se indique de otra manera, un juego se considera oficialmente con 43 minutos de juego.<br/>En apuesta al total de puntos NO se toma en cuenta los tiempos extra, los tiempos extras SI cuentan para el marcador final. En apuesta de medio tiempo, los tiempos extras NO están incluidos como parte de la segunda mitad del juego.<br/>Cuando un evento sea suspendido, aplazado o abandonado se considerará un evento como “no acción”. *Para más información sobre eventos denominados como “no acción” referirse a condiciones generales de apuestas.<br/>En un partido de basquetbol si al momento de realizar la apuesta no está disponible la opción de empate en las apuestas primarias o secundarias y se dan estos resultados (empate) la selección se con-<br/>siderará «no acción» y si al momento de realizar la apuesta, el agrupador elegido incluye prórroga, este se determinará de acuerdo al marcador final después de haberse jugado la prórroga.<br/>Se anulará cualquier partido de basquetbol que se suspenda antes de que se complete el tiempo reglamentario, excepto las apuestas cuyo resultado se determinan con anterioridad a la suspensión del partido. Por ejemplo, las apuesta a ‘Ganador Primera Mitad’. Prevalecerán, siempre y cuando se den antes de la suspensión del partido."
                    }, {
                        "TERMINOS_CONTENT_TITLE": "8.3 Fútbol Americano.",
                        "TERMINOS_CONTENT_CONTENT": "Para efectos de apuesta, a menos que se indique de otra manera, un juego se considera oficial: Fútbol Americano NFL 55 minutos de juego.<br/>En apuesta al total de puntos y en apuesta de medio tiempo, los tiempos extras SI cuentan para el marcador final. En apuesta de medio tiempo, los tiempos extras NO están incluidos como parte de la segunda mitad del juego.<br/>Cuando un partido de fútbol Americano sea suspendido, aplazado o abandonado se considerará como «no acción» Para más información sobre eventos denominados como “no acción” referirse a condiciones generales de apuestas.<br/>En un partido de fútbol Americano si al momento de realizar la apuesta no está disponible la opción de empate en las apuestas primarias o secundarias y se dan estos resultados (empate) la selección se considerará «no acción».<br/>En apuestas a “Totales del equipo – Impar /par”, si un equipo no consigue ningún punto (Puntuación 0), dicho marcador contará como resultado “par” para la determinación de las apuestas.<br/>Si la sede del evento cambia, las apuestas colocadas se mantendrán siempre y cuando el equipo local siga siendo tal. Si el equipo local y visitante para un partido existente cambiaran, las apuestas basadas en el enfrentamiento existe inicialmente serán anuladas."
                    }, {
                        "TERMINOS_CONTENT_TITLE": "8.4 Boxeo (Nacional / Internacional).",
                        "TERMINOS_CONTENT_CONTENT": "Si un evento de boxeo es pospuesto y/o reprogramado, esto significa que el evento se considerará como «no acción» Para más información sobre eventos denominados como “no acción” referirse a condiciones generales de apuestas.<br/>En caso de empate, se consideran las apuestas como «no acción», incluyendo los casos de #####combate nulo por empate#####. Las apuestas se determinarán según el resultado anunciado en el cuadrilátero (ring). Cualquier posible apelación o rectificación posterior no afectará la determinación de las apuestas (a no ser que dicha rectificación se deba a un error humano a la hora de anunciar el resultado).<br/>El sonido de la campana es el signo para comienzo del primer round para propósitos de las apuestas. Cuando un luchador no pueda responder a la campana para el siguiente round, entonces el otro luchador será considerado como ganador en el round anterior.<br/>Cuando se declare un combate como “No contest” (Combate sin decisión) todas las apuestas serán anuladas, con la excepción de aquellas apuestas que ya hayan sido determinadas por la evolución del evento."
                    }, {
                        "TERMINOS_CONTENT_TITLE": "8.5 Béisbol.",
                        "TERMINOS_CONTENT_CONTENT": "El total de carreras (Alta o Baja) y jugadas hechas en RUNLINE, deberán jugarse las nueve entradas completas (81⁄2 si el equipo de casa lleva la ventaja) para tener acción. Si un juego es suspendido, se determinará el marcador (solo para el moneyline) después de la última entrada completa, a menos que el equipo de casa anote para empatar o tome ventaja en la parte baja de la entrada, entonces, se determinará en el momento en que el juego fue suspendido. (Si se suspende empatado en extra innings el RUNLINE y el total de carreras, ALTA o BAJA, tiene acción y el Money line queda sin efecto porque se empata. Es responsabilidad del cliente estar al tanto de cualquier cambio de lanzador. Es decir, que se mantendrá la apuesta equipo contra equipo, independientemente del pitcher que empiece el partido. A efectos de apuesta, se considerará como lanzador inicial al lanzador que realice el primer lanzamiento.<br/>Cuando un partido de béisbol sea suspendido, aplazado o abandonado se considerará como «no acción. Para más información sobre eventos denominados como “no acción” referirse a condiciones generales de apuestas.<br/>En un partido de béisbol al momento de realizar la apuesta no está disponible la opción de empate en las apuestas primarias o secundarias y se dan estos resultados (empate) la selección se considerará «no acción»."
                    }, {
                        "TERMINOS_CONTENT_TITLE": "8.6 Tenis.",
                        "TERMINOS_CONTENT_CONTENT": "A la hora de apostar en Tenis tenga en cuenta que: Los juegos deberán llevarse a cabo en la fecha indicada. Si un juego es pospuesto y/o reprogramado se establecerá como «no acción» a menos que se disponga de otra manera. «No acción» significa reembolso del dinero apostado (excepto apuesta de Parlay).<br/>Cuando un partido de Tenis sea suspendido, aplazado o abandonado se considerará como «no acción» a efectos de la apuesta, a no ser que el partido se juegue dentro de la misma semana (terminando la semana el domingo) en cuyo caso la apuesta prevalecerá. Por otro lado, si un partido del domingo se aplaza hasta la noche del lunes siguiente debido a la retransmisión televisiva en directo u otro motivo, todas las apuestas realizadas a este partido prevalecerán.<br/><br/>Se anulará cualquier partido de Tenis que se suspenda antes de que se complete los Sets reglamentarios,excepto las apuestas cuyo resultado se determinan con anterioridad a la suspensión del partido. Por ejemplo, las apuesta a ‘Ganador del 1er Set’, ‘Marcador del 1er Set’, etc. prevalecerán, siempre y cuando se den antes de que se suspenda el partido."
                    }, {
                        "TERMINOS_CONTENT_TITLE": "8.7 Voleibol.",
                        "TERMINOS_CONTENT_CONTENT": "Un partido de voleibol no tiene un tiempo determinado de duración, el partido depende de los sets ganados por cada equipo, una vez que un equipo gane 3 sets con un máximo de 5 sets, el partido se termina.<br/><br/>Si un partido no se completa todas las apuestas al final del partido serán anuladas, Los juegos deberán llevarse a cabo en la fecha indicada, Si la sede de un partido cambia, las apuestas ya realizadas se mantendrán, siempre y cuando el equipo local siga siendo designado como tal. Si los equipos local y visitante son invertidos, entonces las apuestas basadas en el evento original serán anuladas."
                    }, {
                        "TERMINOS_CONTENT_TITLE": "8.8 Rugby.",
                        "TERMINOS_CONTENT_CONTENT": "80 minutos de juego.<br/>A la hora de apostar en Rugby tenga en cuenta que: Todas las apuestas de Rugby se determinarán a los 80 minutos de juego. El término 80 minutos de juego incluye en tiempo de descuento a menos que se indique lo contrario.<br/>Cuando un partido de Rugby sea suspendido, aplazado o abandonado se considerará como «no acción» Para más información sobre eventos denominados como “no acción” referirse a condiciones generales de apuestas<br/>Se anulará cualquier apuesta a un partido de Rugby que se suspenda antes de que se complete el tiempo reglamentario, excepto las apuestas cuyo resultado se determinan con anterioridad a la suspensión.<br/>En mercados de 2 opciones, las apuestas serán anuladas en caso de empate."
                    }, {
                        "TERMINOS_CONTENT_TITLE": "8.9 Balonmano.",
                        "TERMINOS_CONTENT_CONTENT": "60 minutos de juego.<br/>Todos los partidos están basado en el resultado final de los 60 minutos programados de juego a menos que se indique lo contrario. Si el tiempo programado de 60 minutos no se jugará, entonces las apuestas serian anuladas, a menos que se indicara lo contrario. Deberá completarse el encuentro para que las apuestas prevalezcan (a menos que el mercado ya se haya determinado). Todos los mercados en directo no incluyen la prórroga, mercados como: 1x2, Handycap, Par/Impar, Quien marcara el x punto, Margen de victoria handball, Doble oportunidad, a excepción de casos como: Clasificará/Ganará la copa/Ganará después de la prórroga.<br/>Apuesta que se refieran a una mitad: En este tipo de apuestas deberá completarse la mitad de tiempo específica para que las apuestas prevalezcan por ejemplo: Total primer tiempo, Par/Impar primer tiempo.<br/>A la hora de apostar en Balonmano tenga en cuenta que: Todas las apuestas serán determinadas basadas en el marcador final del tiempo regular, excluyendo prorroga (si se jugara), a menos que se indique lo contrario y todos los eventos deberán comenzar en la fecha programada para que sean válidos (hora local) para que las apuestan sean válidas."
                    }, {
                        "TERMINOS_CONTENT_TITLE": "8.10 Futsal.",
                        "TERMINOS_CONTENT_CONTENT": "Para efectos de apuesta, a menos que se indique de otra manera, un juego se considera oficial: Fútbol de salón 40 minutos de juego.<br/>Todos los partidos serán determinados con el marcador final del tiempo regular, salvo que se indique lo contrario. El tiempo regular debe completar para que las apuestas se mantengan salvo que se indique lo contrario.<br/>Las apuestas estarán vigente dentro del tiempo reglamentario; más el tiempo añadido por el árbitro en razón del tiempo que se hubiere perdido a consecuencia de lesiones y sustituciones. Los tiempos extras, gol de oro y la definición del partido por penaltis no valen para determinar apuestas a este deporte.<br/>Cuando un partido de futbol de salón sea suspendido, aplazado o abandonado se considerará como «no acción» » Para más información sobre eventos denominados como “no acción” referirse a condiciones generales de apuestas."
                    }, {
                        "TERMINOS_CONTENT_TITLE": "8.11 Hockey Hielo.",
                        "TERMINOS_CONTENT_CONTENT": "55 minutos de juego en caso de “Hockey sobre hielo americano” (NHL, AHL UHL, WHL, OHL y la liga de hockey “Quebec Major Junior”).<br/>60 minutos de juego en caso de “Hockey sobre hielo no americano”.<br/>Las apuestas serán válidas si transcurren 55 minutos de juego, la prorroga no se tiene en cuenta a menos que se especifique lo contrario en los mercados, las prórrogas y lanzamientos de penalti no se tendrán en cuenta en las apuestas del 3er periodo. Para que las apuestas específicas sean válidas los periodos deben completarse y apuestas a jugadores, deben haber participado en el encuentro.<br/>Los mercados 1x2 para las a puestas a resultados del partido, totales y con hándicap, se resolverán de acuerdo al marcador únicamente cuando haya finalizado el tiempo reglamentario."
                    }, {
                        "TERMINOS_CONTENT_TITLE": "8.12 Snooker (Billar Inglés).",
                        "TERMINOS_CONTENT_CONTENT": "Si un partido comienza pero no se finaliza por alguna razón, todas las apuestas ofrecidas por el resultado final del partido quedan anuladas.<br/>A efectos de las apuestas, sólo contarán las bolas que hayan entrado “legalmente”, es decir cuando haya una “bola de falta” involucrada, las bolas introducidas no se tendrán en cuenta. Las apuestas se valorarán según corresponda.<br/>En el caso de un re-rack (reanudación del frame) en alguno de los frames, se aplicarán las siguientes normas:<br/><br/>Ganador del frame: todas las apuestas son válidas y se valorarán de acuerdo con el ganador oficial del frame.<br/><br/>Apuestas completadas: todas las apuestas cuyo resultado haya sido determinado antes del re-rack serán válidas. Cualquier suceso posterior al re-rack será irrelevante de cara a las apuestas."
                    }, {
                        "TERMINOS_CONTENT_TITLE": "8.13 Cricket.",
                        "TERMINOS_CONTENT_CONTENT": "Las apuestas se resolverán según el resultado oficial, siempre que al menos se haya golpeado una pelota. Si un partido se cancela por causas externas. Leer eventos determinado como “No acción” ."
                    }]
                }, {
                    "TERMINOS_SLUG": "condiciones-generales-apuestas-en-vivo",
                    "TERMINOS_TITLE": "9. Condiciones Generales APUESTAS JUEGO EN VIVO (Live – En vivo Durante el evento):",
                    "TERMINOS_CONTENT": [{
                        "TERMINOS_CONTENT_TITLE": "9.1 Disponibilidad.",
                        "TERMINOS_CONTENT_CONTENT": "Los eventos se ofrecen a discreción nuestra, No garantizamos tener disponible todos los tipos de apuestas a lo largo del evento, esta van cambiando a consideración de la plataforma. Se ofrecen ligas seleccionadas y deportes tales como: Futbol, Tenis, Rugby, Dardos, Baloncesto, Hockey en hielo, Balonmano, Voleibol, Cricket etc. Todas las Apuestas en Vivo deberán ser hechas exclusivamente en línea. Los eventos en Vivo son ofrecidos en deportes y ligas seleccionadas y tenga en cuenta que el nombre de los mismos también puede presentar cambios.",
                        "isExpanded": true
                    }, {
                        "TERMINOS_CONTENT_TITLE": "9.2 Errores en líneas.",
                        "TERMINOS_CONTENT_CONTENT": "En la eventualidad de que un error obvio haya sido identificado, todas las apuestas con ese error serán canceladas. En la eventualidad de que el formato de un encuentro difiera de nuestra información desplegada, nos reservamos el derecho de anular cualquier apuesta."
                    }, {
                        "TERMINOS_CONTENT_TITLE": "9.3 Transmisión.",
                        "TERMINOS_CONTENT_CONTENT": "Señor Usuario Por favor tenga en cuenta que las transmisiones descritas como “en vivo” pueden presentar un retraso en la transmisión satelital, por lo tanto El grado de retraso puede variar entre clientes dependiendo del sistema a través del cual están recibiendo la información, por lo tanto se le recomienda a los usuarios contar con una buena conexión a internet para que sea mucho más efectivo el uso y aprovechamiento de la plataforma."
                    }, {
                        "TERMINOS_CONTENT_TITLE": "9.4 Formato cuotas.",
                        "TERMINOS_CONTENT_CONTENT": "En base a que somos una Empresa internacional, con presencia en diferentes países, queremos brindarle a todos nuestros usuarios la posibilidad de cambiar el formato de cuotas para poder realizar las apuestas, el usuario puede seleccionar su formato preferido en el menú desplegable situado en parte superior derecha, todas las cuotas aparecerán automáticamente en el formato seleccionado, como se ve a continuación:<br/>Sin tener en cuenta el tipo de formato que selecciones las ganancias potenciales serán las mismas; esta opción le permite al usuario visualizar las ganancias en diferentes formatos para mayor entendimiento."
                    }, {
                        "TERMINOS_CONTENT_TITLE": "9.5 Fluctuación de cuotas",
                        "TERMINOS_CONTENT_CONTENT": "Todas las cuotas están sujetas a fluctuaciones, a menos que se especifique de otra forma, todas las cuotas están basadas en el desenvolvimiento real del evento Durante el ‘tiempo reglamentario’ (los términos ‘tiempo regular, ‘tiempo completo’, ‘90 minutos de juego’ “tiempo reglamentario y ‘tiempo normal’) son todos utilizados para denotar un período de tiempo que incluye su tiempo de reposición pero no el tiempo extra, tandas de penales, etc.). Estos cambios o fluctuación de cuotas se podrán evidenciar cuando el recuadro este totalmente en blanco.<br/>En la esquina superior del rectángulo del mercado de apuestas, el color rojo me indica que mi cuota disminuyo y el color verde que mi cuota aumento, este cambio se hace en cuestión de segundos, por lo tanto al “aceptar cambio de cuotas” el usuario está siendo consiente que el premio que puede obtener por dicho evento puede variar hasta así el sistema le<br/>haya permitido confirmar la apuesta."
                    }, {
                        "TERMINOS_CONTENT_TITLE": "9.6 Opción #####aceptar automáticamente cualquier cambio de cuota#####",
                        "TERMINOS_CONTENT_CONTENT": "Dependiendo del deporte, las cuotas pueden cambiar drásticamente de un momento a otro. Si la opción de #####Aceptar cualquier cambio de cuota##### se Activa durante la confirmación. Las apuestas serán aceptadas al precio de las cuotas del mercado actual sin ninguna alerta. Tenga en cuenta que para confirmar su apuesta en algunos evento es como requisito aceptar el cambio de cuotas por lo tanto Los jugadores son responsables de activar o desactivar esta opción a su discreción"
                    }, {
                        "TERMINOS_CONTENT_TITLE": "9.7 Proceso Aprobación de Apuestas.",
                        "TERMINOS_CONTENT_CONTENT": "Señor usuario es de vital importancia el conocimiento del proceso en el que incurre una apuesta para ser aprobada. Este proceso puede tardar hasta tres minutos más posibles retrasos en la transmisión como se explica en el numeral 8.3. En el proceso de aprobación se realiza una evaluación y control de la apuesta que está ingresando el usuario, en caso tal de que la apuesta no sea aprobada por el sistema esta será rechazada y la suma del valor apostado será reembolsado en el saldo disponible para jugar.UNA APUESTA NO SERÁ CONSIDERADA COMO VALIDA HASTA QUE SE MUESTRE EL EN HISTORIAL DEL CONSUMIDOR, EN CASO DE INCERTIDUMBRE SOBRE LA VALIDEZ DE UNA APUESTA, SE SOLICITA AL CLIENTE QUE COMPRUEBE LAS APUESTAS ABIERTAS (PENDIENTES) O PÓNGASE EN CONTACTO CON NUESTRO SERVICIO DE ATENCION AL CLIENTE (CHATONLINE).<br/>IMPORTANTE: SEÑOR USUARIO TENGA EN CUENTA, SI LA PLATAFORMA LLEGA A DETECTAR ALGUNA ANOMALIA O ALGUNA SITUACIÓN DE RIESGO, NOS RESERVAMOS EL DERECHO DE LA ELIMINACIÓN O CANCELACIÓN DE LAS APUESTAS."
                    }, {
                        "TERMINOS_CONTENT_TITLE": "9.8 Determinación",
                        "TERMINOS_CONTENT_CONTENT": "La valoración o determinación de las apuestas se realiza tan pronto como el resultado de una línea ofrecida se conozca, nuestro sistema necesita obtener el resultado oficial para poder valorar las apuestas pendientes; este proceso puede demorar varios minutos. Si el resultado de una línea ofrecida no puede ser verificado oficialmente, nos reservamos el derecho de retrasar la valoración hasta que se obtenga confirmación oficial. Para efectos de apuestas donde el resultado final se pueda dar durante el evento, el usuario podrá ingresar a la opción historial y mirar cual es el estado actual de su juego. En la eventualidad de una valoración incorrecta de alguna línea ofrecida, nos reservamos el derecho de corregirla en cualquier momento. Importante tener en cuenta: la Plataforma puede demorarse hasta 24 horas para hacer la respectiva valoración."
                    }, {
                        "TERMINOS_CONTENT_TITLE": "9.9 Acceso opción “Historial”",
                        "TERMINOS_CONTENT_CONTENT": "Cabe aclarar que la consulta de tickets de las apuestas normales que se realizan antes de iniciado el evento no se reflejan de la misma manera para las apuestas en vivo, por lo tanto señor usuario lo invitamos a que verifique sus eventos seleccionados desde el Historial, este lo podrá encontrar en la parte superior de la plataforma de apuestas en vivo o en Virtual.<br/>Es en esta opción donde el usuario podrá encontrar una maravillosa herramienta para verificar el estado de sus apuestas en vivo, el usuario puede filtrarlas por fechas y podrá visualizar todo lo relacionado a las jugadas con sus respectivas ganancias potenciales y ganancias netas. Para obtener más información sobre una apuesta específica, tan solo deberá desplazar el puntero del ratón sobre la apuesta respectiva."
                    }, {
                        "TERMINOS_CONTENT_TITLE": "9.10 Bloqueo o suspensión de Mercados de apuestas",
                        "TERMINOS_CONTENT_CONTENT": "El usuario no podrá realizar una apuesta siempre que ocurra algo importante como un gol, una expulsión etc., esta acción ocurre puesto que la plataforma como reacción bloquea las opciones de apuesta con el objetivo de actualizar de nuevo las cuotas"
                    }, {
                        "TERMINOS_CONTENT_TITLE": "9.11 Eventos “No acción”",
                        "TERMINOS_CONTENT_CONTENT": "Eventos que sean suspendidos, serán consideradas como “no acción” y las apuestas realizadas previamente a la suspensión quedan a evaluación de la plataforma. Para más información los invitamos a referirse a nuestras *Reglas generales para conocer cómo se valora una apuesta en este caso en particular."
                    }, {
                        "TERMINOS_CONTENT_TITLE": "9.12 Eventos Aplazados",
                        "TERMINOS_CONTENT_CONTENT": "Encuentro programados que por condiciones ajenas a la plataforma son reprogramados con el fin de jugarse en otra fecha. Ejemplo se aplaza un encuentro por condiciones meteorológicas."
                    }, {
                        "TERMINOS_CONTENT_TITLE": "9.13 Tipos de apuestas JUEGO EN VIVO",
                        "TERMINOS_CONTENT_CONTENT": "Simples: La apuesta simple es el tipo de apuesta más sencilla, el usuario debe seleccionar un pronóstico por el que desee apostar, A continuación introducir el valor que desea jugar y por ultimo confirmar su apuesta, si acierta el resultado ganará la apuesta. <br/><br/>Combinadas: Una apuesta combinada consiste en agrupar una serie de eventos los cuales formarán una única apuesta. Es decir, que si por ejemplo se apuesta a hacer una combinada con 4 eventos deportivos, se está apostando sobre los 4 eventos simultáneamente como si fuesen un único evento y por los tanto depende del acierto de todos para ganar. La cantidad apostada será indivisible entre los eventos (Opción de hacer un Parlay hasta de 14 eventos). <br/> <br/>Sistema: Es un tipo de apuesta combinada mucho más dinámica, aquí ya no existe la uniformidad de la apuesta combinada, ni la dependencia de acertar todos los eventos para ganar, cada evento escogido de un “sistema” tiene su propia apuesta, esto quiere decir que si se escogen 4 eventos estos tendrán una validez individual, es decir en vez de pagarse por una puesta como en la combinada se paga como si fueran 4 apuestas, como si se apostara por 4 eventos tipo apuestas.<br/>IMPORTANTE: A diferencia del Parlay normal donde todas las líneas seleccionadas por el usuario debían ser acertadas para dar lugar a una apuesta ganadora, en la modalidad de Parlay combinado el usuario no necesita acertar en todas las líneas. Su ganancia será proporcional a las líneas ganadoras."
                    }]
                }, {
                    "TERMINOS_SLUG": "apuestas-virtuales",
                    "TERMINOS_TITLE": "10. Apuestas virtuales.",
                    "TERMINOS_CONTENT": [{
                        "TERMINOS_CONTENT_TITLE": "Apuestas virtuales",
                        "TERMINOS_CONTENT_CONTENT": "Una nueva tendencia que coge fuerza en el sector del juego online! En esta modalidad ofrecemos encuentros deportivos simulados virtualmente. Lo primero que debes hacer en ingresar a nuestra plataforma y seleccionar la pestaña de “Virtuales” y una vez allí se desplegarán una serie de competiciones a escoger.<br/>En esta sección de apuestas deportivas, tenemos seis disciplinas en las que puedes jugar: Fútbol, Carrera de caballos, Carrera de perros, Baloncesto y Tenis con diferentes mercados que hacen aún más interesante y entretenido el juego.<br/>Carrera de Caballos – Carrera de Perros. Apuestas directas:<br/><br/>Ganador: Si se apuesta a “Ganador”, se apuesta sobre al ganador que deberá llegar primero en la carrera y se ganará en caso de que el caballo elegido sea el vencedor.<br/>Apuestas combinadas:<br/><br/>Exacta: Realizar una apuesta exacta implica que se apueste por el primer y segundo puesto en una sola carrera y estos deberán cruzar la meta en el orden exacto elegido.<br/><br/>Trifecta: En una apuesta Trifecta se apuesta por el primer, segundo, tercer puesto en una carrera y estos deberán quedar en el orden exacto en que se haya apostado.",
                        "isExpanded": true
                    }]
                }, {
                    "TERMINOS_SLUG": "preguntas-frecuentes-juego-en-vivo",
                    "TERMINOS_TITLE": "11. Preguntas frecuentes juevo en vivo.",
                    "TERMINOS_CONTENT": [{
                        "TERMINOS_CONTENT_TITLE": "11.1 ¿Cómo funcionan las apuestas en juego en vivo?",
                        "TERMINOS_CONTENT_CONTENT": "Si usted selecciona la opción apuesta juego en vivo se abrirá la plataforma que le permitirá realizar dicha acción, En el lado Izquierdo encontrará una visita detallada de todos los demás eventos que se están ofreciendo en ese momento<br/>En el centro aparecerán los pronósticos y las opciones disponibles para el evento seleccionado, haga clic sobre una opción de apuesta de su elección para que este se agregue a su parrilla de apuestas, esta parrilla de apuestas la podrá visualizar en la parte derecha de la página, a continuación introduzca el<br/>monto o valor que desee apostar y luego proceda a confirmar su apuesta con o sin “aceptar cambio de cuotas”.<br/>A continuación, sus pronósticos serán verificados. Si su apuesta es aceptada, el usuario recibirá una notificación de la confirmación de su apuesta, una vez esta haya sido realizada con éxitos y el evento haya sido finalizado podrá verificar en la opción historial toda la información referente a esta apuesta.",
                        "isExpanded": true
                    }, {
                        "TERMINOS_CONTENT_TITLE": "11.2 ¿Porque cambian constantemente las cuotas?",
                        "TERMINOS_CONTENT_CONTENT": "Las apuestas en vivo se ofrecen simultáneamente a un evento, por ellos las cuotas son modificadas continuamente en función del desarrollo real del juego."
                    }, {
                        "TERMINOS_CONTENT_TITLE": "11.3 ¿Cómo elimino de la parrilla de apuestas los pronósticos seleccionados para evitar generar un doble apuesta?",
                        "TERMINOS_CONTENT_CONTENT": "En la parte superior de la parrilla de apuestas existe la posibilidad de eliminar de la parrilla de apuestas las opciones que acaba de elegir o que ya fueron utilizados para una apuesta previa Usted puede borrar una opción de forma individual dando clic en esta opción , pero si por el contrario desea eliminar todas las opciones debe dar clic en el mismo logo en la parte superior situado al lado de la palabra “cuotas”."
                    }, {
                        "TERMINOS_CONTENT_TITLE": "11.4 ¿Cuál es el valor mínimo para realizar una apuesta?",
                        "TERMINOS_CONTENT_CONTENT": "El valor mínimo para realizar una apuesta esta determinada por el tipo de moneda que se use equivalente a USD1."
                    }, {
                        "TERMINOS_CONTENT_TITLE": "11.5 ¿Cuál es mi ganancia Máxima por apuesta y por día?",
                        "TERMINOS_CONTENT_CONTENT": "Nos reservamos el derecho de asignar topes máximos de premiación y de apuestas diarias para cada tipo de apuesta y usuario."
                    }, {
                        "TERMINOS_CONTENT_TITLE": "11.6 ¿Porque si gané mi apuesta no conseguí las ganancias?",
                        "TERMINOS_CONTENT_CONTENT": "Señor usuario(a) si su apuesta resulto ganadora, pero aún no se ve reflejada en su historial, recuerde que debe esperar como mínimo 24 horas para que el resultado se vea reflejado en su apuesta, de lo contrario lo invitamos a comunicarse con nuestro chatonline"
                    }, {
                        "TERMINOS_CONTENT_TITLE": "11.7 ¿Dónde puedo ver mis apuestas?",
                        "TERMINOS_CONTENT_CONTENT": "Una vez que se hizo una apuesta, los detalles de la misma aparecerán en el lado derecho de la página parte superior, A continuación ingresar al historial y filtrar la fecha deseada. Para obtener información detallada, situarse en la apuesta con el cursos del mouse y darle clic. Este automáticamente desplegara toda la información."
                    }, {
                        "TERMINOS_CONTENT_TITLE": "11.8 ¿Para hacer una apuesta que tengo que pagar?",
                        "TERMINOS_CONTENT_CONTENT": "Para que un usuario online pueda hacer uso de la plataforma debe tener saldo disponible en su cuenta. Si el saldo de la cuenta es cero, puede realizar una recarga en todos nuestros puntos autorizados. El valor recargado aparecerá automáticamente en la cuenta recargada. Para obtener más información acerca de nuestros puntos de venta autorizados, lo invitamos a que se comunique con nuestro chatonline, nuestros asesores estarán dispuestos a brindarle toda la información necesaria."
                    }, {
                        "TERMINOS_CONTENT_TITLE": "11.9 ¿Hasta qué minuto el sistema me permite realizar apuestas?",
                        "TERMINOS_CONTENT_CONTENT": "El sistema lo permitirá dependiendo de las condiciones y apuestas al encuentro."
                    }, {
                        "TERMINOS_CONTENT_TITLE": "11.10 ¿En un evento aplazado que sucede con mi apuesta?",
                        "TERMINOS_CONTENT_CONTENT": "En nuestros términos y condiciones encontrara reglas específicas para este tipo de situaciones, lo invitamos a echarle un vistazo al numeral eventos denominado como “no acción”."
                    }, {
                        "TERMINOS_CONTENT_TITLE": "11.11 ¿Cuánto tardan las apuestas en determinarse en juego en vivo?",
                        "TERMINOS_CONTENT_CONTENT": "Intentamos determinar las apuestas lo antes posible. En el caso de algunos eventos, necesitamos la confirmación oficial antes de poder determinar las apuestas y por ello es posible que se produzcan pequeños retrasos. Nuestra intención es determinar las apuestas en el plazo de una hora tras el final del evento y, en el caso de las apuestas en directo, hacerlo durante el evento cuando los resultados estén disponibles o con un máximo de 24 horas."
                    }, {
                        "TERMINOS_CONTENT_TITLE": "11.12 ¿Cómo Funciona el Handycap asiático?",
                        "TERMINOS_CONTENT_CONTENT": "Todas las apuestas de #####Hándicap asiático##### se determinan de una forma muy parecida a las apuestas con hándicap normal. La diferencia es que las apuestas se devuelven si el resultado del evento es un empate después de aplicar el #####Hándicap asiático#####.<br/>Otra diferencia clave es que en el caso de apuestas a #####Hándicap asiático##### en directo, los goles que se hayan anotado antes de hacer la apuesta serán descontados a la hora de determinar las apuestas. El marcador se considera como 0-0 en el momento en que se realiza la apuesta. Las cuotas de su selección y las opciones disponibles mostrarán este hecho."
                    }]
                }
                ],
                "POLITICADEPRIVACIDAD"
                    :
                    [{
                        "POLITICA_SLUG": "politica-de-privacidad",
                        "POLITICA_TITLE": "Politica de Privacidad",
                        "POLITICA_CONTENT": [{
                            "POLITICA_CONTENT_TITLE": "Politica de privacidad",
                            "POLITICA_CONTENT_CONTENT": "Doradobet apunta a mantener su información personal protegida y segura. La privacidad de nuestros clientes es fundamental para nosotros.<br/><br/>Nosotros recolectamos y usamos su información personal para darte un servicio al cliente superior, para proveerte acceso consistente a nuestros juegos, para mantenerte informado de los últimos premios otorgados, anuncio de ganadores, jackpots, ofertas especiales, nuevos juegos y otra información que pensemos que querrás recibir. Su información personal también nos ayuda a incrementar la satisfacción de nuestros clientes y a crear nuevos juegos. El tipo de informacion que usted desee recibir de nosotros depende de usted.<br/><br/>Nosotros guradamos su información en una base de datos segura luego de su registro.<br/>Doradobet.com usa la tecnología de ¨Cookies¨ la cual nos permite reconocer su computadora en sus visitas siguientes. Esto también nos ayuda a organizar sus preferencias e intereses previos para brindarle una experiencia única en sus visitas posteriores.<br/>Usted puede cambiar los ajustes de los ¨Cookies¨ en su explorador en cualquier momento que lo desee.  "
                        }]
                    }],
                "JUEGORESPONSABLE"
                    :
                    [{
                        "JUEGORESPONSABLE_SLUG": "juego-responsable",
                        "JUEGORESPONSABLE_TITLE": "Juego responsable",
                        "JUEGORESPONSABLE_CONTENT": [{
                            "JUEGORESPONSABLE_CONTENT_TITLE": "Juego responsable",
                            "JUEGORESPONSABLE_CONTENT_CONTENT": "El propósito de la compañía es asegurar entretenimiento de calidad, ofreciendo la oportunidad en una manera absolutamente segura y regulada, con diversión en un ambiente seguro. Queremos que nuestros jugadores se diviertan en nuestro website por lo tanto nos urge que todos jueguen responsablemente. Nuestra política de prevención apunta a reducir los efectos negativos del mundo de las apuestas y promover a su vez un juego responsable. Creemos que es nuestra responsabilidad asegurar que la experiencia de jugar con nosotros es emocionante y de disfrute, pero al mismo tiempo le recordamos a todos nuestros usuarios de los posibles efectos negativos tanto sociales como financieros que resultan si se adquiere la dependencia patológica a las apuestas. Pedir dinero prestado, gastar dinero que exceda su presupuesto y usar dinero destinado a otros propósitos para apostar, no es solo imprudente sino que también en el tiempo pueden crear mayores problemas para el cliente y para aquellos que lo rodean. "
                        }, {
                            "JUEGORESPONSABLE_CONTENT_TITLE": "Reglas",
                            "JUEGORESPONSABLE_CONTENT_CONTENT": "Asegúrate que solo apuesta por diversión, no pierdas el control y ten presente estas simples reglas: <br/><br/>-&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Créate un limite de juego y JAMAS lo excedas. <br/>-&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Juega solo la cantidad de dinero establecido inicialmente. <br/>-&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Para de jugar cuando hallas excedido el limite de tiempo de juego que te habias puesto inicialmente. <br/>-&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; NO JUEGUES mas dinero del que puedes arriesgarte a perder. <br/><span>-&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; No gastes dinero destinado a otras cosas en apuestas.<br/></span> <span>-&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Si decides parar de apostar o apostar menos, mantente firme en esta decisión. <br/></span> <span>-&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Evita apostar muy seguido el cambio o el resto del juego.<br/></span> <span>-&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; No apuestes para recuperar tus perdidas.<br/></span> -&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Evita considerar las apuestas como una solución a tus problemas o inconvenientes. <br/>-&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; NUNCA pidas dinero prestado para apostar. <br/>-&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Considera que el dinero perdido en apuestas es el precio que pagas por tu entretenimiento. <br/>-&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; No le mientas a tus amigos o familiares sobre la cantidad de dinero que has perdido en las apuestas o el tiempo que le has dedicado a las <br/>&nbsp; &nbsp; &nbsp; &nbsp;&nbsp;apuestas. <br/>-&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Pide ayuda si crees que estas gastando mucho o apostando muy seguido. <br/>-&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; No faltes a trabajar por apostar. <br/>-&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; No apuestes cuando te sienta deprimido, solo, aburrido, tenso o ansioso."
                        }, {
                            "JUEGORESPONSABLE_CONTENT_TITLE": "Acerca de nuestra compañía",
                            "JUEGORESPONSABLE_CONTENT_CONTENT": "Ten la libertad de enviarnos un correo electrónico para informarnos de cualquier problema que puedas tener con la adicción a las apuestas y te ayudaremos a crear un limite de depósito o incluso a cerrar tu cuenta de apuestas por un periodo de tiempo. &nbsp; Los Menores y las Apuestas <span>Está prohibido a personas menores de 18 años de edad abrir una cuenta o apostar con nosotros. <br/><br/>La compañía esto un problema serio y toma todas las medidas de prevención posibles, tales como verificaciones electrónicas . LAS GANANCIAS DE CUALQUIER QUE PROVEA SOLO UN APROXIMADO O INFORMACIÓN INCORRECTA CON RESPECTO A SU IDENTIDAD O EDAD, SERAN CANCELADAS Y LA PERSONA SERA LLEVADA A CASO JUDICIAL DE ACUERDO CON LAS LEYES DE SU PAIS DE RESIDENCIA.</span>"
                        }]
                    }],
                "MICUENTA"
                    :
                    [{
                        "URL": "/micuenta/saldos",
                        "SLUG": "saldos",
                        "TITLE": "Saldos"
                    }, {
                        "URL": "/micuenta/retiros",
                        "SLUG": "retiros",
                        "TITLE": "Retiros"
                    }, {
                        "URL": "/micuenta/retiros/anular",
                        "SLUG": "anularnota",
                        "TITLE": "Anular Nota de retiro"
                    }],
                "FOOTER"
                    :
                    {
                        "FOOTER_SOCIAL_TEXT"
                            :
                            "Siguenos en:",
                        "FOOTER_TOP_TEXT"
                            :
                            "<img src=#####assets/images/icon-18.png##### width=#####40##### alt=#####+18 Años#####>",
                        "FOOTER_COPYRIGHT_TEXT"
                            :
                            " © 2017 Doradobet. Todos los derechos reservados.",
                        "FOOTER_DESCRIPTION_TEXT"
                            :
                            "Esta página web es operada por iTains N.V., una compañía registrada el 18 de Marzo del 2011 bajo las leyes de Curazao, con el registro No122623 y bajo la licencia No 1668/JAZ otorgada por el Gobierno de Curazao el 1ro de Julio del 2011."
                    }
                ,
                                "DESARROLLADO_POR":"Desarrollado Por",
                "CASINO_EN_VIVO":"CASINO EN VIVO",
                "CASINO_SLOTS":"Casino Slots",
                "TORNEOS":"Torneos",
                "DEPOSITAR":"Depositar",
                "BONOS_POR_LIBERAR":"Bonos por liberar",
                "ACTUALIZAR_SALDO":"Actualizar Saldo",


                "CAMBIAR_CLAVE_DESC": "La nueva clave tiene que tener 6 caracteres y debe de contener minimo una minuscula, una mayuscula y un número",
                "CUENTA_BANCARIA_DESC": "Verifica tu cuenta si es la primera vez que haces un retiro por este medio, enviando un correo electronico desde tu cuenta con la copia de tu documento de identidad original al correo comercial@doradobet.com, demostrando que eres el titular de la cuenta bancaria y de este usuario como jugador.",
                "SELECCIONAR_BANCO": "Seleccionar Banco",
                "CONFIRMAR_BANCO": "Confirmar Banco",
                "ELEGIR_BANCO": "Elegir Banco",
                "CUENTA_BANCARIA": "Cuenta Bancaria",
                "CONFIRMAR_CUENTA_BANCARIA": "Confirmar Cuenta Bancaria",
                "CODIGO_INTERBANCARIO": "Codigo interbancario",
                "TIPOCUENTA": "Tipo de cuenta",
                "ESCOJATIPOCUENTA": "Escoja tipo de cuenta",
                "AHORROS": "Ahorros",
                "CORRIENTE": "Corriente",
                "TIPOCLIENTE": "Tipo de cliente",
                "GUARDAR": "Guardar",
                "LISTACUENTABANCARIAS": "Listado de Cuentas Bancarias",
                "METODO": "Método",
                "DESCRIPCION": "Descripción",
                "AMOUNT": "Valor",
                "ACTION": "Acción",
                "PASARELA": "Pasarela",
                "DESCMETODOSNODISPONIBLES": "Por el momento no estan disponibles los metodos de deposito.",
                "MISBONOS": "Mis Bonos",
                "CODIGODELBONO": "Codigo del bono",
                "OBTENER": "Obtener",
                "INGRESARCODIGODELBONO": "Ingresar Codigo de Bono",
                "VERIFICARCUENTA": "Verificar Cuenta",
                "DNILADOANTERIOR": "DNI lado anterior",
                "DNIENVALIDACION": "El DNI anterior esta en proceso da validación",
                "DNILADOPOSTERIOR": "DNI lado posterior",
                "ENVIAR": "Enviar",
                "FECHAINICIO": "Fecha Inicio",
                "FECHAFIN": "Fecha Fin",
                "CONSULTAR": "Consultar",
                "MISMENSAJES": "Mis mensajes",
                "FECHAESPECIFICA": "Fecha Especifica",
                "HOY": "HOY",
                "AYER": "AYER",
                
                "BONUS"
                    :
                    [{
                        "BONUS_TITLE": "Bono Bienvenida",
                        "BONUS_URL": "/promociones/bono-de-bienvenida",
                        "BONUS_SLUG": "bono-de-bienvenida",
                        "BONUS_IMAGE": "assets/images/sliders/promociones/slider1.png",
                        "BONUS_IMAGE_ICON": "assets/images/sliders/promociones/slider1.png",
                        "BONUS_CONTENT_SHORT": "Abra una cuenta, ingrese un minimo de $10* y recibira una bonificacion del 15% de esa cantidad, hasta un maximo de $100*",
                        "BONUS_CONTENT": "Doradobet es una casa de apuestas que ofrece un bono del 15% hasta $100. Eso quiere decir que para obtener el máximo bono posible de $100, deberías ingresar $200.<br/><br/>El depósito mínimo que hagas ha de ser de $10. Con ellos, percibirías $1.5 más en forma de bono. Te recomendamos hacer el ingreso de $200 para tener los $100 de bono, ya que si no el bono resultaría ser de una cuantía demasiado pequeña.<br/><br/>Para liberar el bono de Doradobet, tienes que apostar tu ingreso y el bono, cinco veces en apuestas con cuotas iguales o superiores a 1.80, siempre durante los 90 días posteriores a la fecha de tu primer depósito. Pulsa aquí para leer el análisis de Doradobet."
                    }, {
                        "BONUS_TITLE": "Combinadas Fútbol",
                        "BONUS_URL": "/promociones/combinadas-futbol",
                        "BONUS_SLUG": "combinadas-futbol",
                        "BONUS_IMAGE": "assets/images/sliders/promociones/slider2.png",
                        "BONUS_IMAGE_ICON": "assets/images/sliders/promociones/slider2.png",
                        "BONUS_CONTENT_SHORT": "Nuestra fantastica oferta de Combinadas de futbol le permite obtener una bonificacion de hasta el 100% en combinadas realizadas a las mejores ligas nacionales de Europa, además de la fase de grupos y fase eliminatoria de la UEFA Champions League",
                        "BONUS_CONTENT": ""
                    }],
                "GRID_SALDOS"
                    :
                    {
                        "CAPTION"
                            :
                            "Saldos Actuales por Tipo de Cuenta",
                        "COLUMNAS"
                            :
                            [{
                                "TITLE": "#"
                            }, {
                                "TITLE": "Agrupador"
                            }, {
                                "TITLE": "Tipo de Cuenta"
                            }, {
                                "TITLE": "Disponible para Retirar"
                            }, {
                                "TITLE": "Disponible para Jugar"
                            }]
                    }
                ,
                "GRID_SALDOS_TRANSFERENCIA"
                    :
                    {
                        "CAPTION"
                            :
                            "Historico de Transferencias de Saldo entre Tipos de Cuenta",
                        "COLUMNAS"
                            :
                            [{
                                "TITLE": "#"
                            }, {
                                "TITLE": "Tipo Cuenta Origen"
                            }, {
                                "TITLE": "Tipo Cuenta Destino"
                            }, {
                                "TITLE": "Fecha y Hora"
                            }, {
                                "TITLE": "Valor Transferido"
                            }]
                    }
                ,
                "GRID_RETIROS"
                    :
                    {
                        "CAPTION"
                            :
                            "Notas de Retiro Pendientes por Canjear",
                        "CONFIRM_TEXT"
                            :
                            "¿ Esta seguro que los datos son correctos y desea generar la nota de retiro ?",
                        "COLUMNAS"
                            :
                            [{
                                "TITLE": "No. Nota de Retiro"
                            }, {
                                "TITLE": "Fecha Generacion"
                            }, {
                                "TITLE": "Valor"
                            }, {
                                "TITLE": "Moneda"
                            }]
                    }
                ,
                "GRID_ANULAR"
                    :
                    {
                        "CAPTION"
                            :
                            "Notas de Retiro Pendientes por Canjear",
                        "CONFIRM_TEXT"
                            :
                            "¿ Esta seguro que desea eliminar la nota de retiro ingresada ?",
                        "SUCCESS_TEXT"
                            :
                            "La nota de retiro ha sido anulada satisfactoriamente.",
                        "COLUMNAS"
                            :
                            [{
                                "TITLE": "No. Nota de Retiro"
                            }, {
                                "TITLE": "Fecha Generacion"
                            }, {
                                "TITLE": "Valor"
                            }, {
                                "TITLE": "Moneda"
                            }]
                    }
            }
';

$primero = json_decode(str_replace("'", '"', $jsonLenguaje), true);
$FromLangId = 'en';


if ($FromLangId != "") {

    $MaxRows = $params->MaxRows;
    $OrderedItem = $params->OrderedItem;
    $SkeepRows = $params->SkeepRows;


    $MaxRows = $_REQUEST["count"];
    $SkeepRows = ($_REQUEST["start"] == "") ? $_REQUEST["?start"] : $_REQUEST["start"];


    if ($SkeepRows == "") {
        $SkeepRows = 0;
    }

    if ($OrderedItem == "") {
        $OrderedItem = 1;
    }

    if ($MaxRows == "") {
        $MaxRows = 1000000;
    }


    $rules = [];

//                array_push($rules, array("field" => "lenguaje_mandante.lenguaje", "data" => "$FromLangId", "op" => "eq"));

    $filtro = array("rules" => $rules, "groupOp" => "AND");

    $json = json_encode($filtro);


    $LenguajeMandante = new LenguajeMandante();
    $data = $LenguajeMandante->getLenguajeMandantesFromPalabraCustom(" lenguaje_palabra.*,lenguaje_mandante.* ", "lenguaje_palabra.lengpalabra_id", "asc", $SkeepRows, $MaxRows, $json, true, $FromLangId);

    $data = json_decode($data);

    $final = array();
    foreach ($data->data as $value) {
        foreach ($primero as $key => $item) {

            if (is_array($item)) {

                foreach ($item as $key2 => $item2) {
                    if (is_array($item2)) {
                        foreach ($item2 as $key3 => $item3) {
                            if (is_array($item3)) {
                                foreach ($item3 as $key4 => $item4) {
                                    if (is_array($item4)) {
                                        foreach ($item4 as $key5 => $item5) {
                                            if (trim($item5) == trim($value->{'lenguaje_palabra.valor'})) {
                                                $primero[$key][$key2][$key3][$key4][$key5] = $value->{'lenguaje_mandante.traducido'};
                                            }else{
                                                if (strpos(trim($item5), ':') !== false) {
                                                    if (trim(str_replace(":", "", $item5)) == trim($value->{'lenguaje_palabra.valor'})) {
                                                        $primero[$key][$key2][$key3][$key4][$key5] = $value->{'lenguaje_mandante.traducido'};
                                                    }
                                                }
                                            }
                                        }
                                    } else {
                                        if (trim($item4) == trim($value->{'lenguaje_palabra.valor'})) {
                                            $primero[$key][$key2][$key3][$key4] = $value->{'lenguaje_mandante.traducido'};
                                        }else{
                                            if (strpos(trim($item4), ':') !== false) {
                                                if (trim(str_replace(":", "", $item4)) == trim($value->{'lenguaje_palabra.valor'})) {
                                                    $primero[$key][$key2][$key3][$key4] = $value->{'lenguaje_mandante.traducido'};
                                                }
                                            }
                                        }
                                    }

                                }
                            } else {
                                if (trim($item3) == trim($value->{'lenguaje_palabra.valor'})) {
                                    $primero[$key][$key2][$key3] = $value->{'lenguaje_mandante.traducido'};
                                }else{
                                    if (strpos(trim($item3), ':') !== false) {
                                        if (trim(str_replace(":", "", $item3)) == trim($value->{'lenguaje_palabra.valor'})) {
                                            $primero[$key][$key2][$key3] = $value->{'lenguaje_mandante.traducido'};
                                        }
                                    }
                                }
                            }

                        }
                    } else {

                        if (trim($item2) == trim($value->{'lenguaje_palabra.valor'})) {
                            $primero[$key][$key2] = $value->{'lenguaje_mandante.traducido'};
                        }else{
                            if (strpos(trim($item2), ':') !== false) {
                                if (trim(str_replace(":", "", $item2)) == trim($value->{'lenguaje_palabra.valor'})) {
                                    $primero[$key][$key2] = $value->{'lenguaje_mandante.traducido'};
                                }
                            }
                        }
                    }

                }
            } else {
                if (trim($item) == trim($value->{'lenguaje_palabra.valor'})) {
                    $primero[$key] = $value->{'lenguaje_mandante.traducido'};
                }else{
                    if (strpos(trim($item), ':') !== false) {
                        if (trim(str_replace(":", "", $item)) == trim($value->{'lenguaje_palabra.valor'})) {
                            $primero[$key] = $value->{'lenguaje_mandante.traducido'};
                        }
                    }
                }
            }

        }


        $array = array();

        $array["Id"] = $value->{'lenguaje_palabra.lengpalabra_id'};
        $array["LangTo"] = $FromLangId;
        $array["Text"] = $value->{'lenguaje_palabra.valor'};
        $array["ToText"] = $value->{'lenguaje_mandante.traducido'};
        $array["ModifiedLocal"] = $value->{'lenguaje_mandante.fecha_modif'};

        array_push($final, $array);

    }

    print_r(json_encode($primero));

    $response["HasError"] = false;
    $response["AlertType"] = "success";
    $response["AlertMessage"] = '';
    $response["ModelErrors"] = [];
    $response["Data"] = $final;
    $response["pos"] = $SkeepRows;
    $response["total_count"] = $data->count[0]->{".count"};
    $response["data"] = $final;


}
