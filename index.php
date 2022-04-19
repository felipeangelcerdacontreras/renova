<?php
/*
 * Copyright 2021 - Felipe angel cerda contreras 
 * felipeangelcerdacontreras@gmail.com
*/
$_SITE_PATH = $_SERVER["DOCUMENT_ROOT"] . "/" . explode("/", $_SERVER["PHP_SELF"])[1] . "/";
require_once($_SITE_PATH . "/app/controllers/mvc.controller.php");
require_once($_SITE_PATH . "/app/controllers/mvc.controller_default.php");
require_once($_SITE_PATH . "/app/controllers/mvc.controller_administrador.php");


$mvc = new mvc_controller();
$action = addslashes(filter_input(INPUT_GET, "action"));
session_start();
if ($action === "login") {
    $mvc->login();
} else if (strpos($action, "checador") !== false && $action != "ubicacion_checador") {
    $mvc->checador();
} else if (strpos($action, "comedor") !== false && $action != "nomina_comedor") {
    $mvc->comedor();
}  else {
    $mvc->ExisteSesion();

    $mvc_default = new mvc_controller_default();

    if ($action === "bienvenida") {// muestra el modulo de bienvenida
        $mvc_default->bienvenida();
    }else if ($action === "usuarios") {
        $mvc_admin = new mvc_controller_administrador();
        $mvc_admin->usuarios();
    }else if ($action === "choferes") {
        $mvc_default->choferes();
    }else if ($action === "contenedores") {
        $mvc_default->contenedores();
    }else if ($action === "vehiculos") {
        $mvc_default->vehiculos();
    }else if ($action === "departamentos") {
        $mvc_default->departamentos();
    }else if ($action === "puestos") {
        $mvc_default->puestos();
    }else if ($action === "nominas") {
        $mvc_default->nominas();
    }else if ($action === "horarios") {
        $mvc_default->horarios();
    }else if ($action === "horas") {
        $mvc_default->horas();
    }else if (strpos($action, "empleados") !== false) {
        $mvc_default->empleados();
    }else if ($action === "proveedores") {
        $mvc_default->proveedores();
    }else if ($action === "materiales") {
        $mvc_default->materiales();
    }else if ($action === "ahorros") {
        $mvc_default->ahorros();
    }else if ($action === "prestamos") {
        $mvc_default->prestamos();
    }else if ($action === "otros") {
        $mvc_default->otros();
    }else if ($action === "recoleccion") {
        $mvc_default->recoleccion();
    }else if ($action === "embarque") {
        $mvc_default->embarque();
    }else if ($action === "servicio") {
        $mvc_default->servicio();
    }else if ($action === "asistencia") {
        $mvc_default->asistencia();
    }else if ($action === "permisos") {
        $mvc_default->permisos();
    }else if ($action === "nomina_comedor") {
        $mvc_default->nomina_comedor();
    }else if ($action === "fonacot") {
        $mvc_default->fonacot();
    }else if ($action === "infonavit") {
        $mvc_default->infonavit();
    }else if ($action === "vacaciones") {
        $mvc_default->vacaciones();
    }else if ($action === "incapacidades") {
        $mvc_default->incapacidades();
    }else if ($action === "ubicacion_checador") {
        $mvc_default->ubicacion_checador();
    }else if ($action === "festivos") {
        $mvc_default->festivos();
    }else if ($action === "cerrar_sesion") {
        $mvc->CerrarSesion();
    }else if ($action === "acceso_denegado") {
        $mvc->acceso_denegado();
    } else {
        $mvc->error_page();
    }
}
