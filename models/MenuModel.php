<?php
/*
 * -------------------------------------
 * Description of menu
 * crea el arbol para  un menu 
 * por defecto crea el menu desde la tabla menu con la clase css nav para el css
 * si se desea otro menu aparte del principal debe instanciar la clase 
 * y enviarle al contructor el nombre de la tabla onde se encuentra el menu
 * la clase del css debe llamarse igual
 * @author juan2ramos
 * -------------------------------------
 *
 */
class MenuModelo extends Model {

    private $_menuFinal;
    private $_item;
    private $_acl;
    protected $_config;

    public function __construct($menu,$item, $acl) {

        parent::__construct();
        $this->_acl = $acl;
        $this->_item = $item;
        $this->_config = Config::singleton();
        $this->menu($menu);
    }

    public function getMenu() {
        if ($this->_menuFinal) {
            return $this->_menuFinal;
        }
    }

    private function menu($menuM) {


        $menu = array(
            'items' => array(),
            'parents' => array()
        );
        $tabla = $menuM;
        $datos = array("id_menu", "nombre", "`key`", "url", "id_padre",);
        $otros = "ORDER BY id_padre, orden, nombre";

        
        $menui = $this->selectDB($tabla, 2, $datos, NULL,$otros);
        foreach ($menui as $items) {

            $menu['items'][$items['id_menu']] = $items;
            $menu['parents'][$items['id_padre']][] = $items['id_menu'];
        }
       
        $classUl = ($menuM=='menu') ? 'nav' : $menuM;
        $this->_menuFinal = $this->construirMenu(0, $menu, $classUl);
    }

    private function construirMenu($padre, $menu,$classUl ) {
        $html = "";
        if (isset($menu['parents'][$padre])) {
            $html .= "<ul";
            if ($padre == 0) {
                $html .= ' class="'.$classUl.' " ';
            }
            $html .= ">\n";
            foreach ($menu['parents'][$padre] as $itemId) {


                if (!isset($menu['parents'][$itemId])) {

                    if ($menu['items'][$itemId]['nombre'] == $this->_item) {
                        $_item_style = "class ='selected'";
                    } else {
                        $_item_style = ' ';
                    }
                    if ($menu['items'][$itemId]['key'] == 'sinpermiso') {
                        $html .= "<li>\n <a href='" . $this->_config->get('BaseUrl') . DS . $menu['items'][$itemId]['url'] . "' " . $_item_style . " >" . ucwords($menu['items'][$itemId]['nombre']) . "</a>\n</li> \n";
                    } elseif ($this->_acl->permiso($menu['items'][$itemId]['key'])) {
                        $html .= "<li>\n <a href='" . $this->_config->get('BaseUrl') . $menu['items'][$itemId]['url'] . "' " . $_item_style . " >" . ucwords($menu['items'][$itemId]['nombre']) . "</a>\n</li> \n";
                    }
                }
                if (isset($menu['parents'][$itemId])) {
                    if ($menu['items'][$itemId]['nombre'] == $this->_item) {
                        $_item_style = "class ='selected'";
                    } else {
                        $_item_style = ' ';
                    }

                    if ($menu['items'][$itemId]['key'] == 'sinpermiso') {

                        $html .= "<li>\n  <a href='" . $this->_config->get('BaseUrl') . DS . $menu['items'][$itemId]['url'] . "  " . $_item_style . "' >" . ucwords($menu['items'][$itemId]['nombre']) . "</a> \n";
                        $html .= $this->construirMenu($itemId, $menu,$classUl);
                        $html .= "</li> \n";
                    } elseif ($this->_acl->permiso($menu['items'][$itemId]['key'])) {
                        $html .= "<li>\n  <a href='#' " . $_item_style . " >" . ucwords($menu['items'][$itemId]['nombre']) . "</a> \n";
                        $html .= $this->construirMenu($itemId, $menu);
                        $html .= "</li> \n";
                    }
                }
            }
            $html .= "</ul> \n";
        }


        return $html;
    }

}
