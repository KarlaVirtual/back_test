<?php namespace Backend\dto;
use Backend\mysql\TemplateMySqlDAO;
use Backend\mysql\UsuarioMensajecampanaMySqlDAO;
use Backend\mysql\UsuarioMensajeMySqlDAO;
use Exception;
/** 
* Clase 'Template'
* 
* Esta clase provee una manera de instanciar un objeto de transferencia de datos (dto)
* para la tabla 'UsuarioMensaje'
* 
* Ejemplo de uso: 
* $Template = new Template();
*   
* 
* @package ninguno 
* @author Daniel Tamayo <it@virtualsoft.tech>
* @version ninguna
* @access public 
* @see no
* 
*/
class Template
{

    /**
    * Representación de la columna 'usumensajeId' de la tabla 'UsuarioMensajecampana'
    *
    * @var string
    */
    public $templateId;

    /**
    * Representación de la columna 'fechaCrea' de la tabla 'UsuarioMensajecampana'
    *
    * @var string
    */
    public $fechaCrea;

    /**
    * Representación de la columna 'fechaModif' de la tabla 'UsuarioMensajecampana'
    *
    * @var string
    */
    public $fechaModif;

    /**
    * Representación de la columna 'usucreaId' de la tabla 'UsuarioMensajecampana'
    *
    * @var string
    */
    public $usucreaId;

    /**
    * Representación de la columna 'usumodifId' de la tabla 'UsuarioMensajecampana'
    *
    * @var string
    */
    public $usumodifId;

    /**
    * Representación de la columna 'tipo' de la tabla 'UsuarioMensajecampana'
    *
    * @var string
    */
    public $tipo;

    /**
     * Representación de la columna 'noombre' de la tabla 'UsuarioMensajecampana'
     *
     * @var string
     */
    public $nombre;
    /**
     * Representación de la columna 'TemplateArray' de la tabla 'UsuarioMensajecampana'
     *
     * @var string
     */
    public $templateArray;

    /**
     * Representación de la columna 'mandante' de la tabla 'UsuarioMensajecampana'
     *
     * @var string
     */
    public $mandante;

    /**
     * Representación de la columna 'paisId' de la tabla 'UsuarioMensajecampana'
     *
     * @var string
     */
    public $paisId;

    /**
     * Representación de la columna 'lenguaje' de la tabla 'UsuarioMensajecampana'
     *
     * @var string
     */
    public $lenguaje;


    /**
     * Representación de la columna 'templateHtml' de la tabla 'UsuarioMensajecampana'
     *
     * @var string
     */
    public $templateHtml;

    /**
     * Representación de la columna 'templateHtml' de la tabla 'UsuarioMensajecampana'
     *
     * @var string
     */
    public $templateHtmlCSSPrint ='
                <style>
                 @page {
            margin: 0px 0px 0px 0px !important;
            padding: 0px 0px 0px 0px !important;
        }
        .bodytmp h1,.bodytmp h2,.bodytmp h3,.bodytmp h4,.bodytmp h5{
            margin: 0px;

        }
                .bodytmp p {
    margin: 2px;
}
 .bodytmp table td, .bodytmp table th{   padding: 0;
    vertical-align: middle;
    border-top: none;
    }
    
    
.bodytmp table{width: 100%;
    overflow-wrap: anywhere !important;
      word-break: break-all;
      overflow-wrap: break-word;
word-wrap: break-word;

-ms-word-break: break-all;
/* This is the dangerous one in WebKit, as it breaks things wherever */
word-break: break-all;
/* Instead use this non-standard one: */
word-break: break-word;

/* Adds a hyphen where the word breaks, if supported (No Blink) */
-ms-hyphens: auto;
-moz-hyphens: auto;
-webkit-hyphens: auto;
hyphens: auto;

}

    
.bodytmp{
            padding: 0px 20px 0px 20px !important;
}
                figure {
                    margin: 0px;
                }
                   
                    .text-small {
                        /* font-size: 10px; */
                    }
                    
                    .bodytmp {
                        font-size: 13px;
                        width: 270px;
                    }

/*
 * CKEditor 5 (v29.1.0) content styles.
 * Generated on Wed, 04 Aug 2021 06:50:03 GMT.
 * For more information, check out https://ckeditor.com/docs/ckeditor5/latest/builds/guides/integration/content-styles.html
 */

.bodytmp :root {
    --ck-color-image-caption-background: hsl(0, 0%, 97%);
    --ck-color-image-caption-text: hsl(0, 0%, 20%);
    --ck-color-mention-background: hsla(341, 100%, 30%, 0.1);
    --ck-color-mention-text: hsl(341, 100%, 30%);
    --ck-color-table-caption-background: hsl(0, 0%, 97%);
    --ck-color-table-caption-text: hsl(0, 0%, 20%);
    --ck-highlight-marker-blue: hsl(201, 97%, 72%);
    --ck-highlight-marker-green: hsl(120, 93%, 68%);
    --ck-highlight-marker-pink: hsl(345, 96%, 73%);
    --ck-highlight-marker-yellow: hsl(60, 97%, 73%);
    --ck-highlight-pen-green: hsl(112, 100%, 27%);
    --ck-highlight-pen-red: hsl(0, 85%, 49%);
    --ck-image-style-spacing: 1.5em;
    --ck-inline-image-style-spacing: calc(var(--ck-image-style-spacing) / 2);
    --ck-todo-list-checkmark-size: 16px;
}

/* ckeditor5-image/theme/imagestyle.css */
.bodytmp .image-style-block-align-left,
.bodytmp .image-style-block-align-right {
    max-width: calc(100% - var(--ck-image-style-spacing));
}
/* ckeditor5-image/theme/imagestyle.css */
.bodytmp .image-style-align-left,
.bodytmp .image-style-align-right {
    clear: none;
}
/* ckeditor5-image/theme/imagestyle.css */
.bodytmp .image-style-side {
    float: right;
    margin-left: var(--ck-image-style-spacing);
    max-width: 50%;
}
/* ckeditor5-image/theme/imagestyle.css */
.bodytmp .image-style-align-left {
    float: left;
    margin-right: var(--ck-image-style-spacing);
}
/* ckeditor5-image/theme/imagestyle.css */
.bodytmp .image-style-align-center {
    margin-left: auto;
    margin-right: auto;
}
/* ckeditor5-image/theme/imagestyle.css */
.bodytmp .image-style-align-right {
    float: right;
    margin-left: var(--ck-image-style-spacing);
}
/* ckeditor5-image/theme/imagestyle.css */
.bodytmp .image-style-block-align-right {
    margin-right: 0;
    margin-left: auto;
}
/* ckeditor5-image/theme/imagestyle.css */
.bodytmp .image-style-block-align-left {
    margin-left: 0;
    margin-right: auto;
}
/* ckeditor5-image/theme/imagestyle.css */
.bodytmp p + .image-style-align-left,
.bodytmp p + .image-style-align-right,
.bodytmp p + .image-style-side {
    margin-top: 0;
}
/* ckeditor5-image/theme/imagestyle.css */
.bodytmp .image-inline.image-style-align-left,
.bodytmp .image-inline.image-style-align-right {
    margin-top: var(--ck-inline-image-style-spacing);
    margin-bottom: var(--ck-inline-image-style-spacing);
}
/* ckeditor5-image/theme/imagestyle.css */
.bodytmp .image-inline.image-style-align-left {
    margin-right: var(--ck-inline-image-style-spacing);
}
/* ckeditor5-image/theme/imagestyle.css */
.bodytmp .image-inline.image-style-align-right {
    margin-left: var(--ck-inline-image-style-spacing);
}
/* ckeditor5-image/theme/imagecaption.css */
.bodytmp .image > figcaption {
    display: table-caption;
    caption-side: bottom;
    word-break: break-word;
    color: var(--ck-color-image-caption-text);
    background-color: var(--ck-color-image-caption-background);
    padding: .6em;
    font-size: .75em;
    outline-offset: -1px;
}
/* ckeditor5-font/theme/fontsize.css */
.bodytmp .text-tiny {
    font-size: .7em;
}
/* ckeditor5-font/theme/fontsize.css */
.bodytmp .text-small {
    font-size: .85em;
}
/* ckeditor5-font/theme/fontsize.css */
.bodytmp .text-big {
    font-size: 1.4em;
}
/* ckeditor5-font/theme/fontsize.css */
.bodytmp .text-huge {
    font-size: 1.8em;
}
/* ckeditor5-highlight/theme/highlight.css */
.bodytmp .marker-yellow {
    background-color: var(--ck-highlight-marker-yellow);
}
/* ckeditor5-highlight/theme/highlight.css */
.bodytmp .marker-green {
    background-color: var(--ck-highlight-marker-green);
}
/* ckeditor5-highlight/theme/highlight.css */
.bodytmp .marker-pink {
    background-color: var(--ck-highlight-marker-pink);
}
/* ckeditor5-highlight/theme/highlight.css */
.bodytmp .marker-blue {
    background-color: var(--ck-highlight-marker-blue);
}
/* ckeditor5-highlight/theme/highlight.css */
.bodytmp .pen-red {
    color: var(--ck-highlight-pen-red);
    background-color: transparent;
}
/* ckeditor5-highlight/theme/highlight.css */
.bodytmp .pen-green {
    color: var(--ck-highlight-pen-green);
    background-color: transparent;
}
/* ckeditor5-image/theme/imageresize.css */
.bodytmp .image.image_resized {
    max-width: 150px;
    display: block;
    box-sizing: border-box;
}
/* ckeditor5-image/theme/imageresize.css */
.bodytmp .image.image_resized img {
    width: 150px;
}
/* ckeditor5-image/theme/imageresize.css */
.bodytmp .image.image_resized > figcaption {
    display: block;
}
/* ckeditor5-image/theme/image.css */
.bodytmp .image {
    display: table;
    clear: both;
    text-align: center;
    margin: 0.9em auto;
    min-width: 50px;
}
/* ckeditor5-image/theme/image.css */
.bodytmp .image img {
    display: block;
    margin: 0 auto;
    max-width: 100%;
    min-width: 100%;
}
/* ckeditor5-image/theme/image.css */
.bodytmp .image-inline {
    /*
     * Normally, the .image-inline would have "display: inline-block" and "img { width: 100% }" (to follow the wrapper while resizing).;
     * Unfortunately, together with "srcset", it gets automatically stretched up to the width of the editing root.
     * This strange behavior does not happen with inline-flex.
     */
    display: inline-flex;
    max-width: 100%;
    align-items: flex-start;
}
/* ckeditor5-image/theme/image.css */
.bodytmp .image-inline picture {
    display: flex;
}
/* ckeditor5-image/theme/image.css */
.bodytmp .image-inline picture,
.bodytmp .image-inline img {
    flex-grow: 1;
    flex-shrink: 1;
    max-width: 100%;
}
/* ckeditor5-horizontal-line/theme/horizontalline.css */
.bodytmp hr {
    margin: 10px 0;
    height: 2px;
    background: #dedede;
    border: 0;
}
/* ckeditor5-block-quote/theme/blockquote.css */
.bodytmp blockquote {
    overflow: hidden;
    padding-right: 1.5em;
    padding-left: 1.5em;
    margin-left: 0;
    margin-right: 0;
    font-style: italic;
    border-left: solid 5px #cccccc;
}
/* ckeditor5-block-quote/theme/blockquote.css */
.bodytmp[dir="rtl"] blockquote {
    border-left: 0;
    border-right: solid 5px #cccccc;
}
/* ckeditor5-basic-styles/theme/code.css */
.bodytmp code {
    background-color: hsla(0, 0%, 78%, 0.3);
    padding: .15em;
    border-radius: 2px;
}

/* ckeditor5-table/theme/table.css */
.bodytmp[dir="rtl"] .table th {
    text-align: right;
}
/* ckeditor5-table/theme/table.css */
.bodytmp[dir="ltr"] .table th {
    text-align: left;
}

/* ckeditor5-page-break/theme/pagebreak.css */
.bodytmp .page-break {
    position: relative;
    clear: both;
    padding: 5px 0;
    display: flex;
    align-items: center;
    justify-content: center;
}
/* ckeditor5-page-break/theme/pagebreak.css */
.bodytmp .page-break::after {
    content: "";
    position: absolute;
    border-bottom: 2px dashed hsl(0, 0%, 77%);
    width: 100%;
}
/* ckeditor5-page-break/theme/pagebreak.css */
.bodytmp .page-break__label {
    position: relative;
    z-index: 1;
    padding: .3em .6em;
    display: block;
    text-transform: uppercase;
    border: 1px solid hsl(0, 0%, 77%);
    border-radius: 2px;
    font-family: Helvetica, Arial, Tahoma, Verdana, Sans-Serif;
    font-size: 0.75em;
    font-weight: bold;
    color: hsl(0, 0%, 20%);
    background: hsl(0, 0%, 100%);
    box-shadow: 2px 2px 1px hsla(0, 0%, 0%, 0.15);
    -webkit-user-select: none;
    -moz-user-select: none;
    -ms-user-select: none;
    user-select: none;
}
/* ckeditor5-media-embed/theme/mediaembed.css */
.bodytmp .media {
    clear: both;
    margin: 0.9em 0;
    display: block;
    min-width: 15em;
}
/* ckeditor5-list/theme/todolist.css */
.bodytmp .todo-list {
    list-style: none;
}
/* ckeditor5-list/theme/todolist.css */
.bodytmp .todo-list li {
    margin-bottom: 5px;
}
/* ckeditor5-list/theme/todolist.css */
.bodytmp .todo-list li .todo-list {
    margin-top: 5px;
}
/* ckeditor5-list/theme/todolist.css */
.bodytmp .todo-list .todo-list__label > input {
    -webkit-appearance: none;
    display: inline-block;
    position: relative;
    width: var(--ck-todo-list-checkmark-size);
    height: var(--ck-todo-list-checkmark-size);
    vertical-align: middle;
    border: 0;
    left: -25px;
    margin-right: -15px;
    right: 0;
    margin-left: 0;
}
/* ckeditor5-list/theme/todolist.css */
.bodytmp .todo-list .todo-list__label > input::before {
    display: block;
    position: absolute;
    box-sizing: border-box;
    content: "";
    width: 100%;
    height: 100%;
    border: 1px solid hsl(0, 0%, 20%);
    border-radius: 2px;
    transition: 250ms ease-in-out box-shadow, 250ms ease-in-out background, 250ms ease-in-out border;
}
/* ckeditor5-list/theme/todolist.css */
.bodytmp .todo-list .todo-list__label > input::after {
    display: block;
    position: absolute;
    box-sizing: content-box;
    pointer-events: none;
    content: "";
    left: calc( var(--ck-todo-list-checkmark-size) / 3 );
    top: calc( var(--ck-todo-list-checkmark-size) / 5.3 );
    width: calc( var(--ck-todo-list-checkmark-size) / 5.3 );
    height: calc( var(--ck-todo-list-checkmark-size) / 2.6 );
    border-style: solid;
    border-color: transparent;
    border-width: 0 calc( var(--ck-todo-list-checkmark-size) / 8 ) calc( var(--ck-todo-list-checkmark-size) / 8 ) 0;
    transform: rotate(45deg);
}
/* ckeditor5-list/theme/todolist.css */
.bodytmp .todo-list .todo-list__label > input[checked]::before {
    background: hsl(126, 64%, 41%);
    border-color: hsl(126, 64%, 41%);
}
/* ckeditor5-list/theme/todolist.css */
.bodytmp .todo-list .todo-list__label > input[checked]::after {
    border-color: hsl(0, 0%, 100%);
}
/* ckeditor5-list/theme/todolist.css */
.bodytmp .todo-list .todo-list__label .todo-list__label__description {
    vertical-align: middle;
}
/* ckeditor5-language/theme/language.css */
.bodytmp span[lang] {
    font-style: italic;
}
/* ckeditor5-code-block/theme/codeblock.css */
.bodytmp pre {
    padding: 1em;
    color: hsl(0, 0%, 20.8%);
    background: hsla(0, 0%, 78%, 0.3);
    border: 1px solid hsl(0, 0%, 77%);
    border-radius: 2px;
    text-align: left;
    direction: ltr;
    tab-size: 4;
    white-space: pre-wrap;
    font-style: normal;
    min-width: 200px;
}
/* ckeditor5-code-block/theme/codeblock.css */
.bodytmp pre code {
    background: unset;
    padding: 0;
    border-radius: 0;
}
/* ckeditor5-mention/theme/mention.css */
.bodytmp .mention {
    background: var(--ck-color-mention-background);
    color: var(--ck-color-mention-text);
}
@media print {
    /* ckeditor5-page-break/theme/pagebreak.css */
    .bodytmp .page-break {
        padding: 0;
    }
    /* ckeditor5-page-break/theme/pagebreak.css */
    .bodytmp .page-break::after {
        display: none;
    }
}
                </style>
                ';

    /**
    * Constructor de clase
    *
    *
    * @param String $templateId usumencampanaId
    *
    * @return no
    * @throws Exception si UsuarioMensajecampana no existe
    *
    * @access public
    * @see no
    * @since no
    * @deprecated no
    */
    public function __construct($templateId="",$mandante="",$tipo="",$paisId="",$lenguaje="", $wrapMessage = true)
    {

        if ($templateId != "")
        {

            $this->templateId = $templateId;

            $TemplateMySqlDAO = new TemplateMySqlDAO();

            $template = $TemplateMySqlDAO->load($this->templateId);

            $this->success = false;

            if ($template != null && $template != "")
            {
            
                $this->templateId = $template->templateId;
                $this->fechaCrea = $template->fechaCrea;
                $this->fechaModif = $template->fechaModif;
                $this->usucreaId = $template->usucreaId;
                $this->usumodifId = $template->usumodifId;
                $this->tipo = $template->tipo;
                $this->nombre = $template->nombre;
                $this->templateArray = $template->templateArray;
                $this->mandante = $template->mandante;
                $this->paisId = $template->paisId;
                $this->lenguaje = $template->lenguaje;
                $this->templateHtml = $wrapMessage ? '<div class="bodytmp">'.$template->templateHtml.'</div>' : $template->templateHtml;


            } 
            else 
            {
                throw new Exception("No existe " . get_class($this), "22");
            }
        }else if ($mandante !="" && $tipo!="" && $paisId !="" && $lenguaje!="")
        {
            $lenguaje=strtolower($lenguaje);
            $this->templateId = $templateId;

            $TemplateMySqlDAO = new TemplateMySqlDAO();

            $template = $TemplateMySqlDAO->loadByMandanteAndTipoAndPaisIdAndLenguaje($mandante,$tipo,$paisId,$lenguaje);

            $this->success = false;

            if ($template != null && $template != "")
            {

                $this->templateId = $template->templateId;
                $this->fechaCrea = $template->fechaCrea;
                $this->fechaModif = $template->fechaModif;
                $this->usucreaId = $template->usucreaId;
                $this->usumodifId = $template->usumodifId;
                $this->tipo = $template->tipo;
                $this->nombre = $template->nombre;
                $this->templateArray = $template->templateArray;
                $this->mandante = $template->mandante;
                $this->paisId = $template->paisId;
                $this->lenguaje = $template->lenguaje;
                $this->templateHtml = $wrapMessage ? '<div class="bodytmp">'.$template->templateHtml.'</div>' : $template->templateHtml;


            }
            else
            {
                throw new Exception("No existe " . get_class($this), "22");
            }
        }
    }

    /**
    * Obtener mensaje WS
    *
    *
    *
    * @return Array $data data
    * @throws no
    *
    * @access public
    * @see no
    * @since no
    * @deprecated no
    */
    public function getWSMessage()
    {
        $profile_id = array();
        $profile_id['id'] = 26678955;
        $profile_id['unique_id'] = 26678955;
        $profile_id['username'] = 26678955;
        $profile_id['name'] = 'TEST';
        $profile_id['first_name'] = 'TEST';
        $profile_id['last_name'] = 'TEST';
        $profile_id['gender'] = "";
        $profile_id['email'] = "";
        $profile_id['phone'] = "";
        $profile_id['reg_info_incomplete'] = false;
        $profile_id['address'] = "";

        $profile_id["reg_date"] = "";
        $profile_id["birth_date"] = "";
        $profile_id["doc_number"] = "";
        $profile_id["casino_promo"] = null;
        $profile_id["currency_name"] = 'USD';

        $profile_id["currency_id"] = 'USD';
        $profile_id["balance"] = '2200';
        $profile_id["casino_balance"] = '2200';
        $profile_id["exclude_date"] = null;
        $profile_id["bonus_id"] = -1;
        $profile_id["games"] = 0;
        $profile_id["super_bet"] = -1;
        $profile_id["country_code"] = '3';
        $profile_id["doc_issued_by"] = null;
        $profile_id["doc_issue_date"] = null;
        $profile_id["doc_issue_code"] = null;
        $profile_id["province"] = null;
        $profile_id["iban"] = null;
        $profile_id["active_step"] = null;
        $profile_id["active_step_state"] = null;
        $profile_id["subscribed_to_news"] = false;
        $profile_id["bonus_balance"] = 0.0;
        $profile_id["frozen_balance"] = 0.0;
        $profile_id["bonus_win_balance"] = 0.0;
        $profile_id["city"] = "Manizales";
        $profile_id["has_free_bets"] = false;
        $profile_id["loyalty_point"] = 0.0;
        $profile_id["loyalty_earned_points"] = 0.0;
        $profile_id["loyalty_exchanged_points"] = 0.0;
        $profile_id["loyalty_level_id"] = null;
        $profile_id["affiliate_id"] = null;
        $profile_id["is_verified"] = false;
        $profile_id["incorrect_fields"] = null;
        $profile_id["loyalty_point_usage_period"] = 0;
        $profile_id["loyalty_min_exchange_point"] = 0;
        $profile_id["loyalty_max_exchange_point"] = 0;
        $profile_id["active_time_in_casino"] = null;
        $profile_id["last_read_message"] = null;
        $profile_id["unread_count"] = 0;
        $profile_id["last_login_date"] = 1506281782;
        $profile_id["swift_code"] = null;
        $profile_id["bonus_money"] = 0.0;
        $profile_id["loyalty_last_earned_points"] = 0.0;

        $data = array(
            "7372873025621876707" => array(
                "profile" => array(
                    "26678955" => $profile_id,
                ),
            ),

        );

        return $data;
    }

    /**
    * Realizar una consulta en la tabla de UsuarioMensaje 'UsuarioMensaje'
    * de una manera personalizada
    *
    *
    * @param String $select campos de consulta
    * @param String $sidx columna para ordenar
    * @param String $sord orden los datos asc | desc
    * @param String $start inicio de la consulta
    * @param String $limit limite de la consulta
    * @param String $filters condiciones de la consulta 
    * @param boolean $searchOn utilizar los filtros o no
    *
    * @return Array resultado de la consulta
    * @throws Exception si el usuario no existe
    *
    * @access public
    * @see no
    * @since no
    * @deprecated no
    */
    public function getTemplateCustom($select, $sidx, $sord, $start, $limit, $filters,$searchOn,$userToSpecific='',$grouping="")
    {

        $TemplateMySqlDAO = new TemplateMySqlDAO();
        $Template = $TemplateMySqlDAO->queryTemplateCustom($select, $sidx, $sord, $start, $limit, $filters, $searchOn,$userToSpecific,$grouping);

        if ($Template != null && $Template != "")
        {
            return $Template;
        } 
        else 
        {
            throw new Exception("No existe " . get_class($this), "01");
        }

    }

    /**
     * Obtiene un conjunto de plantillas personalizada con los parámetros especificados.
     *
     * @param string $select Campos a seleccionar en la consulta.
     * @param string $sidx Índice de ordenación.
     * @param string $sord Orden de clasificación (ascendente o descendente).
     * @param int $start Índice de inicio para la paginación.
     * @param int $limit Límite de registros a obtener.
     * @param array $filters Filtros a aplicar en la consulta.
     * @param bool $searchOn Indica si la búsqueda está activada.
     * @param string $userToSpecific (Opcional) Usuario específico para la consulta.
     * @param string $grouping (Opcional) Agrupamiento para la consulta.
     * @return mixed Plantilla obtenida de la consulta.
     * @throws Exception Si no se encuentra la plantilla.
     */
    public function getTemplateCustom2($select, $sidx, $sord, $start, $limit, $filters,$searchOn,$userToSpecific='',$grouping="")
    {

        $TemplateMySqlDAO = new TemplateMySqlDAO();
        $Template = $TemplateMySqlDAO->queryTemplateCustom2($select, $sidx, $sord, $start, $limit, $filters, $searchOn,$userToSpecific,$grouping);

        if ($Template != null && $Template != "")
        {
            return $Template;
        }
        else
        {
            throw new Exception("No existe " . get_class($this), "01");
        }

    }



    /**
     * Obtener el campo usumensajeId de un objeto
     *
     * @return String usumensajeId usumensajeId
     * 
     */
    public function getTemplateId()
    {
        return $this->templateId;
    }


    /**
     * Obtener el campo fechaCrea de un objeto
     *
     * @return String fechaCrea fechaCrea
     * 
     */
    public function getFechaCrea()
    {
        return $this->fechaCrea;
    }

    /**
     * Modificar el campo 'fechaCrea' de un objeto
     *
     * @param String $fechaCrea fechaCrea
     *
     * @return no
     *
     */
    public function setFechaCrea($fechaCrea)
    {
        $this->fechaCrea = $fechaCrea;
    }

    /**
     * Obtener el campo fechaModif de un objeto
     *
     * @return String fechaModif fechaModif
     * 
     */
    public function getFechaModif()
    {
        return $this->fechaModif;
    }

    /**
     * Modificar el campo 'fechaModif' de un objeto
     *
     * @param String $fechaModif fechaModif
     *
     * @return no
     *
     */
    public function setFechaModif($fechaModif)
    {
        $this->fechaModif = $fechaModif;
    }

    /**
     * Obtener el campo usucreaId de un objeto
     *
     * @return String usucreaId usucreaId
     * 
     */
    public function getUsucreaId()
    {
        return $this->usucreaId;
    }

    /**
     * Modificar el campo 'usucreaId' de un objeto
     *
     * @param String $usucreaId usucreaId
     *
     * @return no
     *
     */
    public function setUsucreaId($usucreaId)
    {
        $this->usucreaId = $usucreaId;
    }

    /**
     * Obtener el campo usumodifId de un objeto
     *
     * @return String usumodifId usumodifId
     * 
     */
    public function getUsumodifId()
    {
        return $this->usumodifId;
    }

    /**
     * Modificar el campo 'usumodifId' de un objeto
     *
     * @param String $usumodifId usumodifId
     *
     * @return no
     *
     */
    public function setUsumodifId($usumodifId)
    {
        $this->usumodifId = $usumodifId;
    }

    /**
     * Obtener el campo tipo de un objeto
     *
     * @return String tipo tipo
     * 
     */
    public function getTipo()
    {
        return $this->tipo;
    }

    /**
     * Modificar el campo 'tipo' de un objeto
     *
     * @param String $tipo tipo
     *
     * @return no
     *
     */
    public function setTipo($tipo)
    {
        $this->tipo = $tipo;
    }


/**
     * Obtener el campo 'nombre' de un objeto
     *
     * @return string nombre
     */
    public function getNombre()
    {
        return $this->nombre;
    }

    /**
     * Modificar el campo 'nombre' de un objeto
     *
     * @param string $nombre
     */
    public function setNombre($nombre)
    {
        $this->nombre = $nombre;
    }

    /**
     * Obtener el campo 'templateArray' de un objeto
     *
     * @return string templateArray
     */
    public function getTempalateArray()
    {
        return $this->TemplateArray;
    }

    /**
     * Modificar el campo 'templateArray' de un objeto
     *
     * @param string $templateArray
     */
    public function setTemplateArray($templateArray)
    {
        $this->templateArray = $templateArray;
    }

    /**
     * Obtener el campo 'mandante' de un objeto
     *
     * @return string mandante
     */
    public function getMandante()
    {
        return $this->mandante;
    }

    /**
     * Modificar el campo 'mandante' de un objeto
     *
     * @param string $mandante
     */
    public function setMandante($mandante)
    {
        $this->mandante = $mandante;
    }

    /**
     * Obtener el campo 'paisId' de un objeto
     *
     * @return string paisId
     */
    public function getPaisId()
    {
        return $this->paisId;
    }

    /**
     * Modificar el campo 'paisId' de un objeto
     *
     * @param string $paisId
     */
    public function setPaisId($paisId)
    {
        $this->paisId = $paisId;
    }

    /**
     * Obtener el campo 'lenguaje' de un objeto
     *
     * @return string lenguaje
     */
    public function getLenguaje()
    {
        return $this->lenguaje;
    }

    /**
     * Modificar el campo 'lenguaje' de un objeto
     *
     * @param string $lenguaje
     */
    public function setLenguaje($lenguaje)
    {
        $this->lenguaje = $lenguaje;
    }

    /**
     * Obtener el campo 'templateHtml' de un objeto
     *
     * @return string templateHtml
     */
    public function getTemplateHtml()
    {
        return $this->templateHtml;
    }

    /**
     * Modificar el campo 'templateHtml' de un objeto
     *
     * @param string $templateHtml
     */
    public function setTemplateHtml($templateHtml)
    {
        $this->templateHtml = $templateHtml;
    }



}
