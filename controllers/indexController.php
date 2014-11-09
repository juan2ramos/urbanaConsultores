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

class indexController extends Controller
{

    public function __construct() {
        
        parent::__construct(); 
        $this->_item = 'nosotros'; 
        $this->_view->menu = $this->_menu;        
        $this->_view->titulo = 'Urbana';
        
        
    }

    public function index()
    {
       
        
        $this->_view->renderizar('index');

    }
     public function cerrar()
    {
        Session::destroy();
        $this->redireccionar();
    }

}

 