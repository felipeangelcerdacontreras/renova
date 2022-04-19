<?php
/*
 * Copyright 2021 - Felipe angel cerda contreras 
 * felipeangelcerdacontreras@gmail.com
*/
session_start();

$_SITE_PATH = $_SERVER["DOCUMENT_ROOT"] . "/" . explode("/", $_SERVER["PHP_SELF"])[1] . "/";
require_once($_SITE_PATH . "/app/model/incapacidades.class.php");
require_once($_SITE_PATH . "/app/model/sueldoMinimo.class.php");
require_once($_SITE_PATH . "/app/model/empleados.class.php");
require_once($_SITE_PATH . "vendor/autoload.php"); 

use Carbon\Carbon;


$accion = addslashes(filter_input(INPUT_POST, "accion"));

if ($accion == "GUARDAR") {
    $oIncapacidades = new incapacidades(true, $_POST);
    if ($oIncapacidades->Guardar() === true) {
        echo "Sistema@Se ha registrado exitosamente la información. @success";
    } else {
        echo "Sistema@Ha ocurrido un error al guardar la información , vuelva a intentarlo o consulte con el administrador del sistema.@warning";
    }
} else if ($accion == "GUARDAR_SUELDO") {
    $oSueldo = new sueldo(true, $_POST);
    if ($oSueldo->Guardar() === true) {
        echo "Sistema@Se ha registrado exitosamente la información. @success";
    } else {
        echo "Sistema@Ha ocurrido un error al guardar la información , vuelva a intentarlo o consulte con el administrador del sistema.@warning";
    }
}
?>
