<?php

class errorController extends Controller
{
    public function __construct() {
        parent::__construct(); 
       
    }
    
    public function index($codigo = false)
    {
        $this->_view->menu = $this->_menu;
        $this->_view->titulo = 'Error';
        $this->_view->mensaje = $this->_getError($codigo);        
        $this->_view->renderizar('index',$this->_menu);
    }
       
    
    private function _getError($codigo = false)
    {
        if($codigo){
            $codigo = $this->filtrarInt($codigo);
            if(is_int($codigo))
                $codigo = $codigo;
        }
        else{
            $codigo = 'default';
        }        
        
        $error['default'] = 'Ha ocurrido un error y la página no puede mostrarse';
        $error['404'] = 'La pagina solicitada no se encuentra en el servidor';
        $error['5050'] = 'Acceso restringido!';
        $error['8080'] = 'Tiempo de la sesion agotado';
        
        if(array_key_exists($codigo, $error)){
            return $error[$codigo];
        }
        else{
            return $error['default'];
        }
    }
}