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
}   else if ($accion == "Agregar") {
    $oAsistencia = new asistencia(true, $_POST);
    print_r($oAsistencia);
    /*$res = $oAsistencia->AgregarAsis();
    if ($res == 1) {
        echo "Sistema@ El empleado registro asistencia, si desea corregir asigne un permiso @success";
    } else if ($res == 2) {
        echo "Sistema@ Asistencia justificada correctamente @success";
    } else {
        echo "Sistema@Ocurrio algun error.@warning";
    }*/
}
