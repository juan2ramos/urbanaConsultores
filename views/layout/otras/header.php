<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="utf-8">
        <title><?php if (isset($this->titulo)) echo $this->titulo; ?></title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale= 1">
        <meta name="description" content="">
        <meta name="author" content="juan2ramos">

        <!-- Estilos -->
        <link rel= "stylesheet" href="<?php echo $_layoutParams['ruta_css']; ?>style.css" >
        <link rel= "stylesheet" href="<?php echo $_layoutParams['ruta_css']; ?>responsive.css" >
        <?
        $countCss = count($css);
        if (isset($css) && $countCss):
            for ($i = 0; $i < $countCss; $i++):
                ?><link rel= "stylesheet" href="<?php echo $css[$i] ?>" ><?
                                echo '
                                    ';
            endfor;
        endif;
        ?>
                
        <!-- JavaScript -->
        <script src="<?php echo $this->_config->get('BaseUrl'); ?>/public/js/jquery.js" type="text/javascript"></script>
        <script src="<?php echo $this->_config->get('BaseUrl'); ?>/public/js/jquery.validate.js" type="text/javascript"></script>
        

    </head>

    <body>
    <body>

        <header>
            <div id = "logo">
                <a href="<?php echo $this->_config->get('BaseUrl'); ?>" >
                    <img src="<?php echo $_layoutParams['ruta_img']; ?>logoUC.png">
                </a>
            </div>
            <h1><?php echo $this->_config->get('AppName'); ?></h1>
        </header>
        <nav>                
<?php echo $menu; ?>  
        </nav>  


