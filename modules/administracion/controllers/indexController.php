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

class indexController extends Controller {

    private $_login;

    public function __construct() {
        
        $this->_item = 'administracion';
        parent::__construct(); #llamado al constructor padre el cual tiene como  atributo vista.        
        $this->_view->menu = $this->_menu;
        $this->_login = $this->loadModel('login'); #carga el modelo.        
        $this->_view->titulo = 'Iniciar sesion';
        $this->metas(array('description' => 'Inicio'));
        
    }

    public function index() {


        if ($this->getInt('enviar') == 1) {
            $this->_view->datos = $_POST;

            if (!$this->getAlphaNum('nombreusuario')) {
                $this->_view->_error = '* Debe introducir su nombre de usuario';
                $this->_view->renderizar('index', $this->_menu);
                exit;
            }

            if (!$this->getSql('contrasenia')) {
                $this->_view->_error = 'Debe introducir su password';
                $this->_view->renderizar('index', $this->_menu);
                exit;
            }
            $hashKey = $this->_config->get('HASH_KEY');
            $_POST['contrasenia'] = Hash::getHash('sha1', $_POST['contrasenia'], $hashKey);

            $row = $this->_login->getUsuario(
                    $this->getAlphaNum('nombreusuario'), $this->getSql('contrasenia')
            );

            if (!$row) {
                $this->_view->_error = 'Usuario y/o password incorrectos';
                $this->_view->renderizar('index', $this->_menu);
                exit;
            }
            //$this->_login->getTipoUsuario();
            Session::set('autenticado', true);
            Session::set('rol', $row['id_rol']);
            Session::set('usuario', $row['nombreusuario']);
            Session::set('id_usuario', $row['id_usuario']);
            Session::set('tiempo', time());

            $this->redireccionar('administrador/principal');
        }

        $this->_view->renderizar('index');
    }

}

?>