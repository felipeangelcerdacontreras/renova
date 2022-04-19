<?php
/*
 * Copyright 2021 - Felipe angel cerda contreras 
 * felipeangelcerdacontreras@gmail.com
 *  */
$_SITE_PATH = $_SERVER["DOCUMENT_ROOT"] . "/" . explode("/", $_SERVER["PHP_SELF"])[1] . "/";
require_once($_SITE_PATH . "/app/controllers/mvc.controller.php");

class mvc_controller_administrador extends mvc_controller
{
    public function __construct()
    {
        parent::__construct();
        /*
         * Constructor de la clase
         */
    }
    public function usuarios()
    {
          include_once("app/views/default/modules/catalogos/usuarios/m.usuarios.buscar.php");
    }
    
}

?>
