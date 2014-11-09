<?php

/*
 *
 * -------------------------------------
 * Descripción de Acl
 * Sistema de lista de acceso
 * 
 * @autor juan2ramos
 * -------------------------------------
 *
 */

class ACL {

    private $_id;
    private $_role;
    private $_permisos;
    private $_dbc;
    protected $_config;

    public function __construct($id = FALSE) {

        //id de usuario, se carga desde la sesion o se envia,
        if ($id) {
            $this->_id = (int) $id;
        } else {
            if (Session::get('id_usuario')) {
                $this->_id = Session::get('id_usuario');
            }
        }
        if (Session::get('autenticado')) {
            $this->_dbc = Db::getInstance();
            $this->_role = $this->getRole();
            $this->_permisos = $this->getPermisosRole();
            $this->compilarAcl();
        }
        $this->_config = Config::singleton();
    }
  // Datos de configuracion para crear el sql
    private function selectDB($tabla, $data, $datos = false, $where = false) {

        $sql = new sql($tabla, $datos, $where);
        $sql1 = $sql->select();
        //print_r($sql1);
        //exit;
        $ejecutar = $this->_dbc->ejecutar($sql1);
        $data = $this->_dbc->obtenerFila($ejecutar, $data);
        return $data;
    }
    //genera los permisos a partir de la mezcla de la matriz de permisos rol y matriz permisos usuario
    public function compilarAcl() {
        $this->_permisos = array_merge(
                $this->_permisos, $this->getPermisosUsuario()
        );
        //echo '<pre>';
        // print_r($this->_permisos);
        //exit;
    }
  //Obtener el rol del usuario
    public function getRole() {
        $tabla = "usuarios";
        $datos = array("id_rol");
        $where = "id_usuario = '$this->_id'";
        $role = $this->selectDB($tabla, 1, $datos, $where);
        return $role['id_rol'];
    }

    //Obtener los datos de los permisos por rol
    
    public function getPermisosRole() {
        $tabla = "permisos_role";
        $datos = array('permiso,valor');
        $where = "rol = '$this->_role'";
        $permisos = $this->selectDB($tabla, 2, $datos, $where);
        $data = array();
        $numPermisos = count($permisos);

        for ($i = 0; $i < $numPermisos; $i++) {

            $key = $this->getPermisoKey($permisos[$i]['permiso']);
            if ($key == '') {
                continue;
            }

            if ($permisos[$i]['valor'] == 1) {
                $v = true;
            } else {
                $v = false;
            }

            $data[$key] = array(
                'key' => $key,
                'permiso' => $this->getPermisoNombre($permisos[$i]['permiso']),
                'valor' => $v,
                'heredado' => true,
                'id' => $permisos[$i]['permiso']
            );
        }

        return $data;
    }
    
    //Obtener el rol del usuario para usuarios
    public function getPermisosRoleId() {
        $tabla = "permisos_role";
        $datos = array("permiso");
        $where = "rol = '$this->_role'";

        $ids = $this->selectDB($tabla, 2, $datos, $where);

        $id = array();

        for ($i = 0; $i < count($ids); $i++) {
            $id[] = $ids[$i]['permiso'];
        }

        return $id;
    }
    //obtiene el la llave del permiso
    public function getPermisoKey($permisoID) {

        $permisoID = (int) $permisoID;
        $tabla = "permisos";
        $datos = array("`key`");
        $where = "id_permiso = '$permisoID'";
        $key = $this->selectDB($tabla, 1, $datos, $where);
        return $key['key'];
    }
    //obtiene el nombre del permiso
    public function getPermisoNombre($permisoID) {

        $permisoID = (int) $permisoID;
        $tabla = "permisos";
        $datos = array("`permiso`");
        $where = "id_permiso = '$permisoID'";
        $key = $this->selectDB($tabla, 1, $datos, $where);
        return $key['permiso'];
    }
    //Permisos por usuario
    public function getPermisosUsuario() {
        $ids = $this->getPermisosRoleId();


        if (count($ids)) {

            $tabla = "permisos_usuario";
            $where = "usuario = '$this->_id' and permiso in (" . implode(",", $ids) . ")";
            $permisos = $this->selectDB($tabla, 2, NULL, $where);
            if (!$permisos) {
                $permisos = array();
            }
        } else {
            $permisos = array();
        }
        $data = array();

        for ($i = 0; $i < count($permisos); $i++) {
            $key = $this->getPermisoKey($permisos[$i]['permiso']);
            if ($key == '') {
                continue;
            }

            if ($permisos[$i]['valor'] == 1) {
                $v = true;
            } else {
                $v = false;
            }

            $data[$key] = array(
                'key' => $key,
                'permiso' => $this->getPermisoNombre($permisos[$i]['permiso']),
                'valor' => $v,
                'heredado' => false,
                'id' => $permisos[$i]['permiso']
            );
        }

        return $data;
    }
    
    public function getPermisos() {

        if (isset($this->_permisos) && count($this->_permisos))
            return $this->_permisos;
    }
    //permiso por llave
    public function permiso($key) {
        if (Session::get('autenticado') || $this->_id = 0) {
            if (array_key_exists($key, $this->_permisos) ) {
                if ($this->_permisos[$key]['valor'] == true || $this->_permisos[$key]['valor'] == 1) {
                    return true;
                }
            }
        }


        return false;
    }

    public function acceso($key) {
        if ($this->permiso($key)) {
            return;
        }
        header('location:' . $this->_config->get('BaseUrl'));
        exit();
    }

}

?>
