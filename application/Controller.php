<?php

/*
 *
 * -------------------------------------
 * Descripción de Controller
 * Controlador Principal, Padre de todas los demás controladores
 * Desde aca instanciamoslos modelos como las vistas
 * @autor juan2ramos
 * -------------------------------------
 *
 */

abstract class Controller {

    protected $_view; //
    protected $_menu;
    protected $_config;
    protected $_acl;
    protected $_item;
    protected $_request;

    public function __construct() {

        $this->_request = new Request();
        $this->_view = new View($this->_request); //instancia el objeto request lo envía a nueva instancia de la clase vista
        $this->_acl = new ACL(); //Instancia la clase  acl para el permiso de usuarios
        $this->_config = Config::singleton();
        $this->metas();
        $this->menu();
        
    }

    protected function metas($metas = FALSE) {

        $metasDefault = array(
            'author' => 'juan2ramos',
            'description' => 'Portafolio en linea Juan2ramos inicio'
        );
        if ($metas) {
            $this->_view->meta = array_merge(
                    $metasDefault, $metas
            );
        }  else {
            $this->_view->meta = $metasDefault ;
        }
    }

    abstract public function index(); //obliga  a que todas las clase que hereden de esta implementen un método index(método por defecto cuando no se envié ningún método o un error)

    protected function menu($menu = 'menu') {// Creo el menu dependiendo de los permisos
        $rutaModelo = ROOT . 'models' . DS . 'MenuModel' . '.php';
        require_once $rutaModelo;
        $menu = new MenuModelo($menu, $this->_item, $this->_acl);
        $listasmenu = $menu->getMenu();
        $this->_menu = $listasmenu;
    }

    protected function redireccionar($ruta = false) {
        if ($ruta) {
            header('location:' . $this->_config->get('BaseUrl') . '/' . $ruta);
            exit;
        } else {
            header('location:' . $this->_config->get('BaseUrl'));
            exit;
        }
    }

    protected function autentificar($key) {


        if (Session::get('autenticado')) {
            Session::tiempo();
            $this->_acl->acceso($key);
        } else {
            $this->redireccionar();
        }
    }

    # Instancia y llama al modelo

    protected function loadModel($modelo, $modulo = FALSE) {//
        $modelo = $modelo . 'Model';
        $rutaModelo = ROOT . 'models' . DS . $modelo . '.php';
        if (!$modulo) {
            $modulo = $this->_request->getModulo();
        }
        if ($modulo) {
            if ($modulo != 'default') {
                $rutaModelo = ROOT . 'modules' . DS . $modulo . DS . 'models' . DS . $modelo . '.php';
            }
        }

        if (is_readable($rutaModelo)) {
            require_once $rutaModelo;
            $modelo = new $modelo;
            return $modelo;
        } else {
            //print_r($rutaModelo);
            throw new Exception('Error de modelo');
        }
    }

    protected function loadClass($class) {//
        $rutaClass = ROOT . 'models' . DS . $class . '.php';

        if (is_readable($rutaClass)) {
            require_once $rutaClass;
            $class = new $class;
            return $class;
        } else {
            throw new Exception('Error de modelo');
        }
    }

    protected function getLibrary($libreria) {
        $rutaLibreria = ROOT . 'libs' . DS . $libreria . '.php';

        if (is_readable($rutaLibreria)) {
            require_once $rutaLibreria;
        } else {
            throw new Exception('Error de libreria');
        }
    }

    protected function getTexto($clave) {
        if (isset($_POST[$clave]) && !empty($_POST[$clave])) {
            $_POST[$clave] = htmlspecialchars($_POST[$clave], ENT_QUOTES);
            return $_POST[$clave];
        }

        return '';
    }

    protected function getInt($clave) {
        if (isset($_POST[$clave]) && !empty($_POST[$clave])) {
            $_POST[$clave] = filter_input(INPUT_POST, $clave, FILTER_VALIDATE_INT);
            return $_POST[$clave];
        }

        return 0;
    }

    protected function filtrarInt($int) {
        $int = (int) $int;

        if (is_int($int)) {
            return $int;
        } else {
            return 0;
        }
    }

    protected function getPostParam($clave) {
        if (isset($_POST[$clave])) {
            return $_POST[$clave];
        }
    }

    protected function getSql($clave) {
        if (isset($_POST[$clave]) && !empty($_POST[$clave])) {
            $_POST[$clave] = strip_tags($_POST[$clave]);

            if (!get_magic_quotes_gpc()) {
                $_POST[$clave] = mysql_escape_string($_POST[$clave]);
            }

            return trim($_POST[$clave]);
        }
    }

    protected function getAlphaNum($clave) {
        if (isset($_POST[$clave]) && !empty($_POST[$clave])) {
            $_POST[$clave] = (string) preg_replace('/[^A-Z0-9_]/i', '', $_POST[$clave]);
            return trim($_POST[$clave]);
        }
    }

    public function validarEmail($email) {
        $email = $_POST[$email];
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return false;
        }

        return true;
    }

    public function getSelect($clave) {
        if (isset($_POST[$clave]) && $_POST[$clave] > 0) {
            $_POST[$clave] = filter_input(INPUT_POST, $clave, FILTER_VALIDATE_INT);
            return $_POST[$clave];
        }
    }

    public function validarFecha($clave) {
        if (isset($_POST[$clave]) && $this->datecheck($_POST[$clave]) == false) {
            return false;
        }
        return true;
    }

    private function datecheck($input) {
        $separator_type = array(
            "/",
            "-",
            "."
        );
        foreach ($separator_type as $separator) {
            $find = stripos($input, $separator);
            if ($find <> false) {
                $separator_used = $separator;
            }
        }
        $input_array = explode($separator_used, $input);
        return checkdate($input_array[1], $input_array[2], $input_array[0]);
    }

}

