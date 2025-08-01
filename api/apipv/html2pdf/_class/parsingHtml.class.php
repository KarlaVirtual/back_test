<?php
/**
 * HTML2PDF Librairy - parsingHtml class
 *
 * HTML => PDF convertor
 * distributed under the LGPL License
 *
 * @author      Laurent MINGUET <webmaster@html2pdf.fr>
 * @version     4.03
 */

class HTML2PDF_parsingHtml
{
    protected    $_html     = '';        // HTML code to parse
    protected    $_num      = 0;         // table number
    protected    $_level    = 0;         // table level
    protected    $_encoding = '';        // encoding
    public       $code      = array();   // parsed HTML codfe

    const HTML_TAB = '        ';

    /**
     * main constructor
     *
     * @param   string encoding
     * @access  public
     */
    public function __construct($encoding = 'UTF-8')
    {
        $this->_num   = 0;
        $this->_level = array($this->_num);
        $this->_html  = '';
        $this->code  = array();
        $this->setEncoding($encoding);
    }

    /**
     * change the encoding
     *
     * @param   string encoding
     * @access  public
     */
    public function setEncoding($encoding)
    {
        $this->_encoding = $encoding;
    }

    /**
     * Define the HTML code to parse
     *
     * @param   string HTML code
     * @access  public
     */
    public function setHTML($html)
    {
        // remove the HTML in comment
        $html = preg_replace('/<!--(.*)-->/isU', '', $html);

        // save the HTML code
        $this->_html = $html;
    }

    /**
     * parse the HTML code
     *
     * @access public
     */
    public function parse()
    {
        $parents = array();

        // flag : are we in a <pre> Tag ?
        $tagPreIn = false;

        // action to use for each line of the content of a <pre> Tag
        $tagPreBr = array(
                    'name' => 'br',
                    'close' => false,
                    'param' => array(
                        'style' => array(),
                        'num'    => 0
                    )
                );

        // tag that can be not closed
        $tagsNotClosed = array(
            'br', 'hr', 'img', 'col',
            'input', 'link', 'option',
            'circle', 'ellipse', 'path', 'rect', 'line', 'polygon', 'polyline'
        );

        // search the HTML tags
        $tmp = array();
        $this->_searchCode($tmp);

        // all the actions to do
        $actions = array();

        // foreach part of the HTML code
        foreach ($tmp as $part) {
            // if it is a tag code
            if ($part[0]=='code') {
                // analise the HTML code
                $res = $this->_analiseCode($part[1]);

                // if it is a real HTML tag
                if ($res) {
                    // save the current posistion in the HTML code
                    $res['html_pos'] = $part[2];

                    // if the tag must be closed
                    if (!in_array($res['name'], $tagsNotClosed)) {
                        // if it is a closure tag
                        if ($res['close']) {
                            // HTML validation
                            if (oldCount($parents)<1)
                                throw new HTML2PDF_exception(3, $res['name'], $this->getHtmlErrorCode($res['html_pos']));
                            else if ($parents[oldCount($parents)-1]!=$res['name'])
                                throw new HTML2PDF_exception(4, $parents, $this->getHtmlErrorCode($res['html_pos']));
                            else
                                unset($parents[oldCount($parents)-1]);
                        } else {
                            // if it is a autoclosed tag
                            if ($res['autoclose']) {
                                // save the opened tag
                                $actions[] = $res;

                                // prepare the closed tag
                                $res['params'] = array();
                                $res['close'] = true;
                            }
                            // else :add a child for validation
                            else
                                $parents[oldCount($parents)] = $res['name'];
                        }

                        // if it is a <pre> tag (or <code> tag) not auclosed => update the flag
                        if (($res['name']=='pre' || $res['name']=='code') && !$res['autoclose']) {
                            $tagPreIn = !$res['close'];
                        }
                    }

                    // save the actions to convert
                    $actions[] = $res;
                } else { // else (it is not a real HTML tag => we transform it in Texte
                    $part[0]='txt';
                }
            }
            // if it is text
            if ($part[0]=='txt') {
                // if we are not in a <pre> tag
                if (!$tagPreIn) {
                    // save the action
                    $actions[] = array(
                        'name'    => 'write',
                        'close'    => false,
                        'param' => array('txt' => $this->_prepareTxt($part[1])),
                    );
                } else { // else (if we are in a <pre> tag)
                    // prepare the text
                    $part[1] = str_replace("\r", '', $part[1]);
                    $part[1] = explode("\n", $part[1]);

                    // foreach line of the text
                    foreach ($part[1] as $k => $txt) {
                        // transform the line
                        $txt = str_replace("\t", self::HTML_TAB, $txt);
                        $txt = str_replace(' ', '&nbsp;', $txt);

                        // add a break line
                        if ($k>0) $actions[] = $tagPreBr;

                        // save the action
                        $actions[] = array(
                            'name'    => 'write',
                            'close'    => false,
                            'param' => array('txt' => $this->_prepareTxt($txt, false)),
                        );
                    }
                }
            }
        }

        // for each indentified action, we have to clean up the begin and the end of the texte
        // based on tags that surround it

        // list of the tags to clean
        $tagsToClean = array(
            'page', 'page_header', 'page_footer', 'form',
            'table', 'thead', 'tfoot', 'tr', 'td', 'th', 'br',
            'div', 'hr', 'p', 'ul', 'ol', 'li',
            'h1', 'h2', 'h3', 'h4', 'h5', 'h6',
            'bookmark', 'fieldset', 'legend',
            'draw', 'circle', 'ellipse', 'path', 'rect', 'line', 'g', 'polygon', 'polyline',
            'option'
        );

        // foreach action
        $nb = oldCount($actions);
        for ($k=0; $k<$nb; $k++) {
            // if it is a Text
            if ($actions[$k]['name']=='write') {
                // if the tag before the text is a tag to clean => ltrim on the text
                if ($k>0 && in_array($actions[$k-1]['name'], $tagsToClean))
                    $actions[$k]['param']['txt'] = ltrim($actions[$k]['param']['txt']);

                // if the tag after the text is a tag to clean => rtrim on the text
                if ($k<$nb-1 && in_array($actions[$k+1]['name'], $tagsToClean))
                    $actions[$k]['param']['txt'] = rtrim($actions[$k]['param']['txt']);

                // if the text is empty => remove the action
                if (!strlen($actions[$k]['param']['txt']))
                    unset($actions[$k]);
            }
        }

        // if we are not on the level 0 => HTML validator ERROR
        if (oldCount($parents)) throw new HTML2PDF_exception(5, $parents);

        // save the actions to do
        $this->code = array_values($actions);
    }

    /**
     * prepare the text
     *
     * @param   string texte
     * @param   boolean true => replace multiple space+\t+\r+\n by a single space
     * @return  string texte
     * @access  protected
     */
    protected function _prepareTxt($txt, $spaces = true)
    {
        if ($spaces) $txt = preg_replace('/\s+/is', ' ', $txt);
        $txt = str_replace('&euro;', '€', $txt);
        $txt = html_entity_decode($txt, ENT_QUOTES, $this->_encoding);
        return $txt;
    }

    /**
     * parse the HTML code
     *
     * @param    &array    array's result
     * @return   null
     */
    protected function _searchCode(&$tmp)
    {
        // initialise the array
        $tmp = array();

        // regexp to separate the tags from the texts
        $reg = '/(<[^>]+>)|([^<]+)+/isU';

        // last match found
        $str = '';
        $offset = 0;

        // As it finds a match
        while (preg_match($reg, $this->_html, $parse, PREG_OFFSET_CAPTURE, $offset)) {
            // if it is a tag
            if ($parse[1][0]) {
                // save the previous text if it exists
                if ($str!=='')    $tmp[] = array('txt', $str);

                // save the tag, with the offset
                $tmp[] = array('code', trim($parse[1][0]), $offset);

                // init the current text
                $str = '';
            } else { // else (if it is a text)
                // add the new text to the current text
                $str.= $parse[2][0];
            }

            // Update offset to the end of the match
            $offset = $parse[0][1] + strlen($parse[0][0]);
            unset($parse);
        }
        // if a text is present in the end, we save it
        if ($str!='') $tmp[] = array('txt', $str);
        unset($str);
    }

    /**
     * analise a HTML tag
     *
     * @param   string   HTML code to analise
     * @return  array    corresponding action
     */
    protected function _analiseCode($code)
    {
        // name of the tag, opening, closure, autoclosure
        $tag = '<([\/]{0,1})([_a-z0-9]+)([\/>\s]+)';
        if (!preg_match('/'.$tag.'/isU', $code, $match)) return null;
        $close     = ($match[1]=='/' ? true : false);
        $autoclose = preg_match('/\/>$/isU', $code);
        $name      = strtolower($match[2]);

        // required parameters (depends on the tag name)
        $param    = array();
        $param['style'] = '';
        if ($name=='img') {
            $param['alt'] = '';
            $param['src'] = '';
        }
        if ($name=='a') {
            $param['href'] = '';
        }

        // read the parameters : nom=valeur
        $prop = '([a-zA-Z0-9_]+)=([^"\'\s>]+)';
        preg_match_all('/'.$prop.'/is', $code, $match);
        for($k=0; $k<oldCount($match[0]); $k++)
            $param[trim(strtolower($match[1][$k]))] = trim($match[2][$k]);

        // read the parameters : nom="valeur"
        $prop = '([a-zA-Z0-9_]+)=["]([^"]*)["]';
        preg_match_all('/'.$prop.'/is', $code, $match);
        for($k=0; $k<oldCount($match[0]); $k++)
            $param[trim(strtolower($match[1][$k]))] = trim($match[2][$k]);

        // read the parameters : nom='valeur'
        $prop = "([a-zA-Z0-9_]+)=[']([^']*)[']";
        preg_match_all('/'.$prop.'/is', $code, $match);
        for($k=0; $k<oldCount($match[0]); $k++)
            $param[trim(strtolower($match[1][$k]))] = trim($match[2][$k]);

        // compliance of each parameter
        $color  = "#000000";
        $border = null;
        foreach ($param as $key => $val) {
            $key = strtolower($key);
            switch($key)
            {
                case 'width':
                    unset($param[$key]);
                    $param['style'] .= 'width: '.$val.'px; ';
                    break;

                case 'align':
                    if ($name==='img') {
                        unset($param[$key]);
                        $param['style'] .= 'float: '.$val.'; ';
                    } elseif ($name!=='table') {
                        unset($param[$key]);
                        $param['style'] .= 'text-align: '.$val.'; ';
                    }
                    break;

                case 'valign':
                    unset($param[$key]);
                    $param['style'] .= 'vertical-align: '.$val.'; ';
                    break;

                case 'height':
                    unset($param[$key]);
                    $param['style'] .= 'height: '.$val.'px; ';
                    break;

                case 'bgcolor':
                    unset($param[$key]);
                    $param['style'] .= 'background: '.$val.'; ';
                    break;

                case 'bordercolor':
                    unset($param[$key]);
                    $color = $val;
                    break;

                case 'border':
                    unset($param[$key]);
                    if (preg_match('/^[0-9]+$/isU', $val)) $val = $val.'px';
                    $border = $val;
                    break;

                case 'cellpadding':
                case 'cellspacing':
                    if (preg_match('/^([0-9]+)$/isU', $val)) $param[$key] = $val.'px';
                    break;

                case 'colspan':
                case 'rowspan':
                    $val = preg_replace('/[^0-9]/isU', '', $val);
                    if (!$val) $val = 1;
                    $param[$key] = $val;
                    break;
            }
        }

        // compliance of the border
        if ($border!==null) {
            if ($border)    $border = 'border: solid '.$border.' '.$color;
            else            $border = 'border: none';

            $param['style'] .= $border.'; ';
            $param['border'] = $border;
        }

        // reading styles: decomposition and standardization
        $styles = explode(';', $param['style']);
        $param['style'] = array();
        foreach ($styles as $style) {
            $tmp = explode(':', $style);
            if (oldCount($tmp)>1) {
                $cod = $tmp[0];
                unset($tmp[0]);
                $tmp = implode(':', $tmp);
                $param['style'][trim(strtolower($cod))] = preg_replace('/[\s]+/isU', ' ', trim($tmp));
            }
        }

        // determining the level of table opening, with an added level
        if (in_array($name, array('ul', 'ol', 'table')) && !$close) {
            $this->_num++;
            $this->_level[oldCount($this->_level)] = $this->_num;
        }

        // get the level of the table containing the element
        if (!isset($param['num'])) {
            $param['num'] = $this->_level[oldCount($this->_level)-1];
        }

        // for closures table: remove a level
        if (in_array($name, array('ul', 'ol', 'table')) && $close) {
            unset($this->_level[oldCount($this->_level)-1]);
        }

        // prepare the parameters
        if (isset($param['value']))  $param['value']  = $this->_prepareTxt($param['value']);
        if (isset($param['alt']))    $param['alt']    = $this->_prepareTxt($param['alt']);
        if (isset($param['title']))  $param['title']  = $this->_prepareTxt($param['title']);
        if (isset($param['class']))  $param['class']  = $this->_prepareTxt($param['class']);

        // return the new action to do
        return array('name' => $name, 'close' => $close ? 1 : 0, 'autoclose' => $autoclose, 'param' => $param);
    }

    /**
     * get a full level of HTML, between an opening and closing corresponding
     *
     * @param   integer key
     * @return  array   actions
     */
    public function getLevel($k)
    {
        // if the code does not exist => return empty
        if (!isset($this->code[$k])) return array();

        // the tag to detect
        $detect = $this->code[$k]['name'];

        // if it is a text => return
        if ($detect=='write') {
            return array($this->code[$k]);
        }

        //
        $level = 0;      // depth level
        $end = false;    // end of the search
        $code = array(); // extract code

        // while it's not ended
        while (!$end) {
            // current action
            $row = $this->code[$k];

            // if 'write' => we add the text
            if ($row['name']=='write') {
                $code[] = $row;
            } else { // else, it is a html tag
                $not = false; // flag for not taking into account the current tag

                // if it is the searched tag
                if ($row['name']==$detect) {
                    // if we are just at the root level => dont take it
                    if ($level==0) {
                        $not = true;
                    }

                    // update the level
                    $level+= ($row['close'] ? -1 : 1);

                    // if we are now at the root level => it is the end, and dont take it
                    if ($level==0) {
                        $not = true;
                        $end = true;
                    }
                }

                // if we can takin into account the current tag => save it
                if (!$not) {
                    if (isset($row['style']['text-align'])) unset($row['style']['text-align']);
                    $code[] = $row;
                }
            }

            // it continues as long as there has code to analise
            if (isset($this->code[$k+1]))
                $k++;
            else
                $end = true;
        }

        // return the extract
        return $code;
    }

    /**
     * return a part of the HTML code, for error message
     *
     * @param   integer position
     * @param   integer take before
     * @param   integer take after
     * @return  string  part of the html code
     */
    public function getHtmlErrorCode($pos, $before=30, $after=40)
    {
        return substr($this->_html, $pos-$before, $before+$after);
    }
}