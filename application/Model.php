<?php

/*
 *
 * -------------------------------------
 * Description of Model
 * Modelo Principal
 * @author juan2ramos
 * -------------------------------------
 * 
 */

abstract class Model {

    protected $_dbc;

    public function __construct() {
        $this->_dbc = Db::getInstance();
    }

    protected function selectDB($tabla, $data, $datos = FALSE, $where = FALSE, $otros = FALSE) {

        $sql = new sql($tabla, $datos, $where, $otros);
        $sql1 = $sql->select();
        //print_r($sql1);
        //exit;
        $ejecutar = $this->_dbc->ejecutar($sql1);
        $data = $this->_dbc->obtenerFila($ejecutar, $data);
        return $data;
    }

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

}

