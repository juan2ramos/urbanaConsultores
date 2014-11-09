<?php

/*
 *
2 * -------------------------------------
 * Archivo con los datos de configuración
 * @autor juan2ramos
 * -------------------------------------
 *
 */

$config = Config::singleton();

$config->set('BaseUrl', 'http://napsdt.com/urbana');//Ruta del sitio
$config->set('DefaultControllers', 'index');// controlador por defecto
$config->set('DefaultLayout', 'default'); //vista inicial

$config->set('AppName', 'Urbana Consultores');
$config->set('AppSlogan', '');
$config->set('AppCompany', 'Urbana');
$config->set('SessionTime', 100);
$config->set('HASH_KEY', '4f6a6d832be79');

$config->set('DBHost', 'localhost');//Servidor
$config->set('DBUser', 'napsdtco_urbana');//Usuario base de datos  
$config->set('DBPass', 'Urbana2012');// Contraseña
$config->set('DBName', 'napsdtco_urbana');// Base de datos
$config->set('DBChar', 'utf8');// Codificación