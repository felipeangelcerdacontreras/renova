<?php
/*
 * Copyright 2021 - Felipe angel cerda contreras 
 * felipeangelcerdacontreras@gmail.com
*/
session_start();

$_SITE_PATH = $_SERVER["DOCUMENT_ROOT"] . "/" . explode("/", $_SERVER["PHP_SELF"])[1] . "/";
require_once($_SITE_PATH . "/app/model/asistencia.class.php");

$accion = addslashes(filter_input(INPUT_POST, "accion"));

if ($accion == "Sincronizar") {
    $oAsistencia = new asistencia(true, $_POST);

    if ($oAsistencia->Existe_Sincronizar() == 1) {
        echo "Sistema@ @success";
    } else {
        echo "Sistema@Se han sincronizado todas las asistencias.@warning";
    }
}  else if ($accion == "Agregar") {
    $oAsistencia = new asistencia(true, $_POST);
    $res = $oAsistencia->AgregarAsis();
    if ($res == 1) {
        echo "Sistema@ El empleado registro asistencia, si desea corregir asigne un permiso @success";
    } else if ($res == 2 || $res == 3) {
        echo "Sistema@ Asistencia agregada correctamente @success";
    } else {
        echo "Sistema@Ocurrio algun error.@warning";
    }
}    else if ($accion == "txt") {
    $oAsistencia = new asistencia(true, $_POST);

    if ($oAsistencia->GeneraTxt() === true) {
    echo "Se ha creado la lista de inasistencias correctamente.";
    } else {
        echo "No se a generado la lista.";
    }
}
