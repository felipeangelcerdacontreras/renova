<?php
/*
 * Copyright 2021 - Felipe angel cerda contreras 
 * felipeangelcerdacontreras@gmail.com
*/
session_start();

$_SITE_PATH = $_SERVER["DOCUMENT_ROOT"] . "/" . explode("/", $_SERVER["PHP_SELF"])[1] . "/";
require_once($_SITE_PATH . "/app/model/clientes.class.php");

$accion = addslashes(filter_input(INPUT_POST, "accion"));


if ($accion == "GUARDAR") {
    $oClientes = new clientes(true, $_POST);
    if ($oClientes->Guardar() === true) {
        echo "Sistema@Se ha registrado exitosamente la información del cliente. @success";
    } else {
        echo "Sistema@Ha ocurrido un error al guardar la información del cliente, vuelva a intentarlo o consulte con el administrador del sistema.@warning";
    }
}
?>
