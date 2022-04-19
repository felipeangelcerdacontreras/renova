<?php
$_SITE_PATH = $_SERVER["DOCUMENT_ROOT"] . "/" . explode("/", $_SERVER["PHP_SELF"])[1] . "/";
require_once($_SITE_PATH . "/app/model/principal.class.php");
require_once($_SITE_PATH . "/app/model/comedor.class.php");

$usr = addslashes(filter_input(INPUT_POST, "usr"));
$pass = addslashes(filter_input(INPUT_POST, "pass"));
$accion = addslashes(filter_input(INPUT_POST, "accion"));

if ($accion === "CHECAR") {
    $oComedor = new comedor(true, $_POST);
    
    if ($oComedor->Guardar()) {
        echo "Provecho@Se ha registrado la comida. @success";
    } else {
        echo "@Intente de nuevo.@error";
    }
}
