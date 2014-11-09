<?php

/*
 *
 * -------------------------------------
 * Descripción de indexController
 * Controlador inicial
 * @autor juan2ramos
 * -------------------------------------
 *
 */

class nuestrafirmaController extends Controller
{

    public function __construct() {
        
        parent::__construct(); 
        $this->_item = 'nosotros'; 
        $this->_view->menu = $this->_menu;        
        $this->_view->titulo = 'Juan2ramos';
        
        
    }

    public function index()
    {
       
        echo 'jua';
        $this->_view->renderizar('index');

    }
 

}

 