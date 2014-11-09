<?php

class loginModel extends Model
{
    

    public function __construct()
    { 
        parent::__construct();
        
    }

    public function getUsuario($user,$pass)
    {
        
        $tabla = "usuarios";
        $where = "nombreusuario = '$user' and contrasenia = '$pass'";        
        $sql = new sql($tabla,NULL, $where);
        $sql1= $sql->select(); 
        $ejecutar = $this->_dbc->ejecutar($sql1);
        $usuario = $this->_dbc->obtenerFila($ejecutar,1);
        return $usuario;

    }

}


