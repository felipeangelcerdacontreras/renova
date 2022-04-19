<?php
$_SITE_PATH = $_SERVER["DOCUMENT_ROOT"] . "/" . explode("/", $_SERVER["PHP_SELF"])[1] . "/";
require_once($_SITE_PATH . "/app/model/principal.class.php");
require_once($_SITE_PATH . "/app/model/asistencia.class.php");

$usr = addslashes(filter_input(INPUT_POST, "usr"));
$pass = addslashes(filter_input(INPUT_POST, "pass"));
$accion = addslashes(filter_input(INPUT_POST, "accion"));

if ($accion === "CHECAR") {
    $oAsistencia = new asistencia(true, $_POST);
    $Guardar = $oAsistencia->Guardar();
    if ($Guardar == 1) {
        echo "Bienvenido@Se ha registrado la entrada. @success";
    } else if ($Guardar == 2) {
        echo "@Se ha registrado la salida.@success";
    } else if ($Guardar == 3) {
        echo "@El empleado esta dado de baja.@warning";
    } else {
        echo "@El empleado no existe.@error";
    }
}
