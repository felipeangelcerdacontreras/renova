<?php
session_start();
$_SITE_PATH = $_SERVER["DOCUMENT_ROOT"] . "/" . explode("/", $_SERVER["PHP_SELF"])[1] . "/";
require_once($_SITE_PATH . "app/model/registro.class.php");

$oClientes = new registro(true, $_GET);

$accion = filter_input(INPUT_GET, "accion");
$reg_cliente = filter_input(INPUT_GET, "reg_cliente");

if ($accion === "LINEATAXI") {
    $resultado = $oClientes->like($reg_cliente);

    if (count($resultado) > 0){
        echo "{$resultado[0]->cli_lineataxi}";
    }
}

?>
