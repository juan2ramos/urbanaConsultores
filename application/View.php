<?php

/*
 *
 * -------------------------------------
 * Descriptcon of View
 * Vista, manejador de las vistas
 * @author juan2ramos
 * -------------------------------------
 *
 */

class View {

    private $_request;
    private $_js;
    private $_config;
    private $_rutas;
    private $_modulo;
    private $_controlador;
    private $_meta;
    private $_rutaLayout;
    
    public function __construct(Request $peticion ) {

        
        $this->_request = $peticion;
        $this->_config = Config::singleton();
        $this->_rutas = array();

        $this->_modulo = $this->_request->getModulo();
        $this->_controlador = $this->_request->getControlador();

        $this->_rutas['layout'] = ROOT . 'views' . DS . 'layout' . DS;
        
        if ($this->_modulo) {
            $ruta = ROOT . 'modules' . DS . $this->_modulo . DS . 'views' . DS . $this->_controlador . DS;
            $this->_rutas['views'] = $ruta;
            $this->_rutas['js'] = $ruta . 'js' . DS;
            $this->_rutas['css'] = $ruta . 'css' . DS;
            
        } else {
            $ruta = ROOT . $this->_modulo . 'views' . DS . $this->_controlador . DS;
            $this->_rutas['views'] = $ruta;
            $this->_rutas['js'] = $ruta . 'js' . DS;
            $this->_rutas['css'] = $ruta . 'css' . DS;            
        }
    }

    public function renderizar($vista, $layout = FALSE) {

        $rutaLayout = $this->_rutas['layout'] . $this->_config->get('DefaultLayout');
        $layoutHtml = $this->_config->get('BaseUrl') . DS . 'views' . DS . 'layout' . DS;
        $layoutHtmlCont = $this->_config->get('DefaultLayout');

        if ($layout) {
            $rutaLayout = $this->_rutas['layout'] . $layout;
            if (file_exists($rutaLayout)) {
                $layoutHtmlCont = $layout;
            } else {
                $rutaLayout = $this->_rutas['layout'] . $this->_config->get('DefaultLayout');
            }
        }
        $layoutHtml .= $layoutHtmlCont;

        $_layoutParams = array(
            'ruta_css' => $layoutHtml . '/css/',
            'ruta_img' => $layoutHtml . '/img/',
            'ruta_js' => $layoutHtml . '/js/'
        );

        /*
         * archivos con la configuracion html para visualizar el contenido   de la pagina 
         */
        $jsPublicRoutes = ROOT . '/public/js/';

        $css = $this->incluirArchivos($this->_rutas['css'], 'css');
        $js = $this->incluirArchivos($this->_rutas['js'], 'js');
        $jsPublic = $this->incluirArchivos($jsPublicRoutes, 'js','public');
        $this->_rutaLayout = $rutaLayout;
        
        include_once $rutaLayout . DS . 'header.php';

        if (is_readable($this->_rutas['views'])) {
            include_once $this->_rutas['views'] . $vista . '.phtml';
        } else {
            include_once ROOT . 'views' . DS . 'error' . DS . 'index' . '.phtml';
        }
        include_once $rutaLayout . DS . 'footer.php';
    }

    public function incluirArchivos($dir, $tipo, $ruta = FALSE) {

        if (is_dir($dir)) {
            if ($gd = opendir($dir)) {
                
                if (!$ruta) {
                    $ruta = $this->_config->get('BaseUrl');
                    $finRuta = 'views' . DS . $this->_controlador . DS . $tipo . DS;
                    if ($this->_modulo) {
                        $ruta .= DS . 'modules' . DS . $this->_modulo;
                    }
                }  else {
                    $ruta = $this->_config->get('BaseUrl'). DS .$ruta;
                    $finRuta = $tipo . DS;
                }
                

                while ($archivo = readdir($gd)) {
                    if (($archivo != '.') and ($archivo != '..')) {
                        $archivos[] = $ruta . DS . $finRuta . $archivo;
                    }
                }
            }

            closedir($gd);
            return $archivos;
        }
    }

}

?>
