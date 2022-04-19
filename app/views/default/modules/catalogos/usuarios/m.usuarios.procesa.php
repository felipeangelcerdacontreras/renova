<?php
/*
 * Copyright 2021 - Felipe angel cerda contreras 
 * felipeangelcerdacontreras@gmail.com
*/
session_start();

$_SITE_PATH = $_SERVER["DOCUMENT_ROOT"] . "/" . explode("/", $_SERVER["PHP_SELF"])[1] . "/";
require_once($_SITE_PATH . "/app/model/usuarios.class.php");

$accion = addslashes(filter_input(INPUT_POST, "accion"));


if ($accion == "GUARDAR") {
    $oUsuarios = new Usuarios(true, $_POST);
    if ($oUsuarios->Guardar() === true) {
        echo "Sistema@Se ha registrado exitosamente la información del usuario. @success";
    } else {
        echo "Sistema@Ha ocurrido un error al guardar la información del usuario, vuelva a intentarlo o consulte con el administrador del sistema.@warning";
    }
} else if ($accion == "Desactivar"){
    $oUsuarios = new Usuarios(true, $_POST);
    //print_r($oUsuarios);
    if ($oUsuarios->Desactivar() === true) {
        echo "Sistema@Se ha registrado exitosamente la información del usuario. @success";
    } else {
        echo "Sistema@Ha ocurrido un error al guardar la información del usuario, vuelva a intentarlo o consulte con el administrador del sistema.@warning";
    }
}
?>
