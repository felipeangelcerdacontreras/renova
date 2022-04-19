<?php

/*
 * Copyright 2021 - Felipe angel cerda contreras 
 * cerda@redzpot.com
 *  */
$_SITE_PATH = $_SERVER["DOCUMENT_ROOT"] . "/" . explode("/", $_SERVER["PHP_SELF"])[1] . "/";
require_once($_SITE_PATH . "/app/controllers/mvc.controller.php");
require_once($_SITE_PATH . "/app/model/principal.class.php");

class mvc_controller_default extends mvc_controller {

    public function __construct() {
        parent::__construct();
        /*
         * Constructor de la clase
         */
    }
    public function bienvenida() {
        include_once("app/views/default/modules/m.bienvenida.php");
    }
    //catalogos
    public function choferes () {
        include_once("app/views/default/modules/catalogos/choferes/m.choferes.buscar.php");
    }
    public function contenedores () {
        include_once("app/views/default/modules/catalogos/contenedores/m.contenedores.buscar.php");
    }
    public function vehiculos () {
        include_once("app/views/default/modules/catalogos/vehiculos/m.vehiculos.buscar.php");
    }
    public function departamentos () {
        include_once("app/views/default/modules/catalogos/departamentos/m.departamentos.buscar.php");
    }
    public function puestos () {
        include_once("app/views/default/modules/catalogos/puestos/m.puestos.buscar.php");
    }
    public function horarios () {
        include_once("app/views/default/modules/catalogos/horarios/m.horarios.buscar.php");
    }
    public function horas () {
        include_once("app/views/default/modules/catalogos/horas/m.horas.buscar.php");
    }
    public function empleados () {
        include_once("app/views/default/modules/catalogos/empleados/m.empleados.buscar.php");
    }
    public function proveedores () {
        include_once("app/views/default/modules/catalogos/proveedores/m.proveedores.buscar.php");
    }
    public function materiales () {
        include_once("app/views/default/modules/catalogos/materiales/m.materiales.buscar.php");
    }
    //modulos 
    public function nominas () {
        include_once("app/views/default/modules/modulos/nominas/m.nominas.buscar.php");
    }
    public function ahorros () {
        include_once("app/views/default/modules/modulos/ahorros/m.ahorros.buscar.php");
    }
    public function prestamos () {
        include_once("app/views/default/modules/modulos/prestamos/m.prestamos.buscar.php");
    }
    public function otros () {
        include_once("app/views/default/modules/modulos/otros/m.otros.buscar.php");
    }
    public function recoleccion () {
        include_once("app/views/default/modules/modulos/recoleccion/m.recoleccion.buscar.php");
    }
    public function embarque () {
        include_once("app/views/default/modules/modulos/embarque/m.embarque.buscar.php");
    }
    public function servicio () {
        include_once("app/views/default/modules/modulos/servicio/m.servicio.buscar.php");
    }
    public function asistencia () {
        include_once("app/views/default/modules/modulos/asistencia/m.asistencia.buscar.php");
    }
    public function permisos () {
        include_once("app/views/default/modules/modulos/permisos/m.permisos.buscar.php");
    }
    public function nomina_comedor () {
        include_once("app/views/default/modules/modulos/comedor/m.comedor.buscar.php");
    }
    public function fonacot () {
        include_once("app/views/default/modules/modulos/fonacot/m.fonacot.buscar.php");
    }
    public function infonavit () {
        include_once("app/views/default/modules/modulos/infonavit/m.infonavit.buscar.php");
    }
    public function vacaciones () {
        include_once("app/views/default/modules/modulos/vacaciones/m.vacaciones.buscar.php");
    }
    public function incapacidades () {
        include_once("app/views/default/modules/modulos/incapacidades/m.incapacidades.buscar.php");
    }
    public function ubicacion_checador () {
        include_once("app/views/default/modules/catalogos/ubicacion_checador/m.ubicacion.buscar.php");
    }
    public function festivos () {
        include_once("app/views/default/modules/catalogos/festivos/m.festivos.buscar.php");
    }
}
?>
