<?php
$_SITE_PATH = $_SERVER["DOCUMENT_ROOT"] . "/" . explode("/", $_SERVER["PHP_SELF"])[1] . "/";
require_once($_SITE_PATH . "/app/model/principal.class.php");

$usr = addslashes(filter_input(INPUT_POST, "usr"));
$pass = addslashes(filter_input(INPUT_POST, "pass"));
$accion = addslashes(filter_input(INPUT_POST, "accion"));

if ($accion === "LOGIN") {
    $oAw = new AW(false);

    $aResultado = array();
    $aResultado["valido"] = false;
    $aResultado["msg"] = "La información de acceso no es válida, vuelva a intentarlo o consulte con el administrador del sistema.
     El sistema es sensible a mayúsculas y minúsculas.";

    if ($oAw->ValidaLogin($usr, $pass) === true){
        $aResultado["valido"] = true;
        $aResultado["msg"] = "index.php?action=bienvenida";
    }

    echo json_encode($aResultado);
}
