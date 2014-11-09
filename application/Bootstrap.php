<?php

/*
 *
 * -------------------------------------
 * Descripción de Bootstrap
 * LLama a los controladores y métodos
 * @autor juan2ramos
 * -------------------------------------
 *
 */

class Bootstrap {

    public static function ejecutar(Request $peticion) {
        
        $modulo = $peticion->getModulo();//Obtiene el modulo
        $controller = $peticion->getControlador() . 'Controller'; //Obtiene el controlador
        $rutaControlador = ROOT . 'controllers' . DS . $controller . '.php'; //ruta física de controlador
        $metodo = $peticion->getMetodo(); //Obtiene el método
        $args = $peticion->getArgs(); //Obtiene los argumentos
        
            print_r($controller);
        
        if($modulo){
            $rutaModulo = ROOT . 'controllers' . DS . $modulo . 'Controller.php';
            
            if(is_readable($rutaModulo)){
                require_once $rutaModulo;
                $rutaControlador = ROOT . 'modules'. DS . $modulo . DS . 'controllers' . DS . $controller . '.php';
            }
            else{
                throw new Exception('Error de base de modulo');
            }
        }
        else{
            $rutaControlador = ROOT . 'controllers' . DS . $controller . '.php';
        }
        // busca si el archivo no existe, de ser asi envia al controlador encargado de los errores
        if (!is_readable($rutaControlador)) {
          
            $controller = 'error' . 'Controller';
            $rutaControlador = ROOT . 'controllers' . DS . $controller . '.php';            
            $args[0] = '404'; 
            
        }
        
        require_once $rutaControlador;
        $controller = new $controller(); //Se instancia la clase controladora 
        
        if (is_callable(array($controller, $metodo))) {// revisa si el método es valido
            $metodo = $peticion->getMetodo();
        } else {
            $metodo = 'index';
            
        }

        if (isset($args)) {//revisa si los argumentos existen
           
            call_user_func_array(array($controller, $metodo), $args); //llamamos al metodo de la clase y le pasamos los argumentos
        } else {            
            call_user_func(array($controller, $metodo)); //llama la clase y el metodo
        }
    }

}


       
       