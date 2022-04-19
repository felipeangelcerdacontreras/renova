<?php
/*
 * Copyright 2021 - Felipe angel cerda contreras 
 * felipeangelcerdacontreras@gmail.com
*/
session_start();

$_SITE_PATH = $_SERVER["DOCUMENT_ROOT"] . "/" . explode("/", $_SERVER["PHP_SELF"])[1] . "/";
require_once($_SITE_PATH . "/app/model/ahorros.class.php");

$accion = addslashes(filter_input(INPUT_POST, "accion"));


if ($accion == "GUARDAR") {
    $oAhorros = new ahorros(true, $_POST);
    $resultado = $oAhorros->Existe();
    $resultado1 = $oAhorros->AhorroActivo();
    
    if (date("m-d") >= '01-01' && date("m-d") <= '01-31') {
        if ($resultado) {
            echo "Sistema@El empleado ya tiene un ahorro activo este año. @warning";
        } else if ($resultado1) {
            echo "Sistema@El empleado ya tiene un ahorro detenido y no puede seguir ahorrando. @warning";
        } else {
            if ($oAhorros->Guardar() === true) {
                echo "Sistema@Se ha registrado exitosamente la información. @success";
            } else {
                echo "Sistema@Ha ocurrido un error al guardar la información , vuelva a intentarlo o consulte con el administrador del sistema.@warning";
            }
        }
    } else {
        echo "Sistema@Los ahorros solo se pueden generar del 1 Enero al 31.@warning";
    }
} else if ($accion == "Detener") {
    $oAhorros = new ahorros(true, $_POST);

    if ($oAhorros->Detener() === true) {
        echo "Sistema@Se ha registrado exitosamente la información. @success";
    } else {
        echo "Sistema@Ha ocurrido un error al guardar la información , vuelva a intentarlo o consulte con el administrador del sistema.@warning";
    }
} else if ($accion == "AhorroActivo") {
    $oAhorros = new ahorros(true, $_POST);

    $resultado = $oAhorros->Existe();
    if ($resultado) {
        echo "El empleado ya tiene un ahorro activo";
    }
}
