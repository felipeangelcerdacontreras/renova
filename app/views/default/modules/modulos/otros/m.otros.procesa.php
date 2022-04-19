<?php
/*
 * Copyright 2021 - Felipe angel cerda contreras 
 * felipeangelcerdacontreras@gmail.com
*/
session_start();

$_SITE_PATH = $_SERVER["DOCUMENT_ROOT"] . "/" . explode("/", $_SERVER["PHP_SELF"])[1] . "/";
require_once($_SITE_PATH . "/app/model/otros.class.php");

$accion = addslashes(filter_input(INPUT_POST, "accion"));


if ($accion == "GUARDAR") {
    $oOtros = new otros(true, $_POST);
        if ($oOtros->Guardar() === true) {
            echo "Sistema@Se ha registrado exitosamente la información. @success";
        } else {
            echo "Sistema@Ha ocurrido un error al guardar la información , vuelva a intentarlo o consulte con el administrador del sistema.@warning";
        }
} else if ($accion == "Liquidado") {
    $oOtros = new otros(true, $_POST);

    if ($oOtros->Liquidar() === true) {
        echo "Sistema@Prestamo liquidado exitosamente. @success";
    } else {
        echo "Sistema@Ha ocurrido un error al guardar la información , vuelva a intentarlo o consulte con el administrador del sistema.@warning";
    }
}
