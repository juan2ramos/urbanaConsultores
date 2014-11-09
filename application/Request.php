<?php

/*
 *
 * -------------------------------------
 * Description of Request
 * ejecuta y maneja las URL
 * @author juan2ramos
 * -------------------------------------
 *
 */



class Request {

    private $_modulo;
    private $_controlador;
    private $_metodo;
    private $_argumentos;
    private $_modules;

    public function __construct() {

        $config = Config::singleton();
        $this->_dbc = Db::getInstance();
        if (isset($_GET['url'])) {
            $url = filter_input(INPUT_GET, 'url', FILTER_SANITIZE_URL);
            $url = str_replace("-","",$url);
            $url = explode('/', $url);
            $url = array_filter($url);

            /* modulos de la app */
            $this->_modules = $this->modulosDB();
            $this->_modulo = strtolower(array_shift($url));

            if (!$this->_modulo) {
                $this->_modulo = false;
            } else {
                if (count($this->_modules)) {
                    
                    if (!in_array($this->_modulo, $this->_modules)) {
                                                
                        $this->_controlador = $this->_modulo;
                        $this->_modulo = false;
                    } else {
                        $this->_controlador = strtolower(array_shift($url));

                        if (!$this->_controlador) {
                            $this->_controlador = 'index';
                        }
                    }
                } else {
                    $this->_controlador = $this->_modulo;
                    $this->_modulo = false;
                }
            }

            $this->_metodo = strtolower(array_shift($url));
            $this->_argumentos = $url;
        }

        if (!$this->_controlador) {
            $this->_controlador = $config->get('DefaultControllers'); // controlador por defecto
        }

        if (!$this->_metodo) {
            $this->_metodo = 'index';
        }

        if (!isset($this->_argumentos)) {
            $this->_argumentos = array();
        }
    }
    
    private function modulosDB(){
        
        $tabla = "modulos";
        $datos = array("nombre");
        $where = " activo = '1'";
        $sql = new sql($tabla, $datos, $where);
        $sql1 = $sql->select();
        $ejecutar = $this->_dbc->ejecutar($sql1);
        $data = $this->_dbc->obtenerFila($ejecutar, 1);
        return $data;
    }

    public function getModulo() {
        return $this->_modulo;
    }

    public function getControlador() {
        return $this->_controlador;
    }

    public function getMetodo() {
        return $this->_metodo;
    }

    public function getArgs() {
        return $this->_argumentos;
    }

}

