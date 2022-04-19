<?php
/*
 * Copyright 2021 - Felipe angel cerda contreras 
 * felipeangelcerdacontreras@gmail.com
*/
session_start();

$_SITE_PATH = $_SERVER["DOCUMENT_ROOT"] . "/" . explode("/", $_SERVER["PHP_SELF"])[1] . "/";
require_once($_SITE_PATH . "/app/model/nominas.class.php");
require_once($_SITE_PATH . "/app/model/empleados.class.php");
require_once($_SITE_PATH . "/app/model/ahorros.class.php");
require_once($_SITE_PATH . "/app/model/prestamos.class.php");
require_once($_SITE_PATH . "/app/model/fonacot.class.php");
require_once($_SITE_PATH . "/app/model/infonavit.class.php");
require_once($_SITE_PATH . "/app/model/otros.class.php");

$accion = addslashes(filter_input(INPUT_POST, "accion"));


if ($accion == "GUARDAR") {
    $oNominas = new nominas(true, $_POST);
    if ($oNominas->Guardar() === true) {
        echo "Sistema@Se ha registrado exitosamente la información. @success";
    } else {
        echo "Sistema@Ha ocurrido un error al guardar la información , vuelva a intentarlo o consulte con el administrador del sistema.@warning";
    }
} else if ($accion == "Recalcular") {
    $oNominas = new nominas(true, $_POST);
    if ($oNominas->Agregar() === true) {
        echo "Sistema@Se ha recalculado exitosamente la nomina. @success";
    } else {
        echo "Sistema@Ha ocurrido un error al recalcular la nomina, vuelva a intentarlo o consulte con el administrador del sistema.@warning";
    }
} else if ($accion == "ADD_NOMINA") {
    $oNominas = new nominas(true, $_POST);
    if ($oNominas->AddNomina() === true) {
        echo "Sistema@Se ha registrado exitosamente la información. @success";
    } else {
        echo "Sistema@Ha ocurrido un error al guardar la información , vuelva a intentarlo o consulte con el administrador del sistema.@warning";
    }
} else if ($accion == "Pagar") {
    $oNominas = new nominas(true, $_POST);

    if ($oNominas->Pagar() === true) {
        echo "Sistema@Se ha registrado exitosamente la información. @success";
    } else {
        echo "Sistema@Ha ocurrido un error al guardar la información , vuelva a intentarlo o consulte con el administrador del sistema.@warning";
    }
} else if ($accion == "Solicitar") {
    $oNominas = new nominas(true, $_POST);

    if ($oNominas->Solicitud() === true) {
        echo "Sistema@Se realizo la solicitud correctamente. @success";
    } else {
        echo "Sistema@Ha ocurrido un error al guardar la información , vuelva a intentarlo o consulte con el administrador del sistema.@warning";
    }
} else if ($accion == "AprovarDenegar") {
    $oNominas = new nominas(true, $_POST);

    if ($oNominas->AprovarDenegar() === true) {
        echo "Sistema@Se realizo la solicitud correctamente. @success";
    } else {
        echo "Sistema@Ha ocurrido un error al guardar la información , vuelva a intentarlo o consulte con el administrador del sistema.@warning";
    }
} else if ($accion == "DatosEmpleado") {
    $oEmpleados = new empleados(true, $_POST);
    $oEmpleados->id = addslashes(filter_input(INPUT_POST, "id_empleado"));
    $res = $oEmpleados->Informacion();
    $arr = array($res[0]->salario_diario,$res[0]->salario_asistencia,$res[0]->salario_puntualidad,
    $res[0]->salario_productividad,$res[0]->complemento_sueldo,$res[0]->bono_doce);
    echo json_encode($arr);
} else if ($accion == "ExisteAhorro") {
    $oAhorros = new ahorros(true, $_POST);
    $oAhorros->estatus = '1';
    $oAhorros->id_empleado = addslashes(filter_input(INPUT_POST, "id_empleado"));
    $oAhorros->Informacion();
    $arr = array($oAhorros->monto);
    echo json_encode($arr);
} else if ($accion == "ExistePrestamo") {
    $oPrestamos = new prestamos(true, $_POST);
    $oPrestamos->fecha = addslashes(filter_input(INPUT_POST, "fecha"));
    $oPrestamos->id_empleado = addslashes(filter_input(INPUT_POST, "id_empleado"));
    $oPrestamos->Informacion();
    $arr = array($oPrestamos->monto_por_semana);
    echo json_encode($arr);
} else if ($accion == "ExisteFonacot") {
    $oFonacot = new fonacot(true, $_POST);
    $oFonacot->fecha = addslashes(filter_input(INPUT_POST, "fecha"));
    $oFonacot->id_empleado = addslashes(filter_input(INPUT_POST, "id_empleado"));
    $oFonacot->Informacion();
    $arr = array($oFonacot->monto_por_semana);
    echo json_encode($arr);
} else if ($accion == "ExisteInfonavit") {
    $oInfonavit = new infonavit(true, $_POST);
    $oInfonavit->fecha = addslashes(filter_input(INPUT_POST, "fecha"));
    $oInfonavit->id_empleado = addslashes(filter_input(INPUT_POST, "id_empleado"));
    $oInfonavit->Informacion();
    $arr = array($oInfonavit->monto_por_semana);
    echo json_encode($arr);
} else if ($accion == "ExisteOtros") {
    $oOtros = new otros(true, $_POST);
    $oOtros->fecha_pago = addslashes(filter_input(INPUT_POST, "fecha"));
    $oOtros->id_empleado = addslashes(filter_input(INPUT_POST, "id_empleado"));
    $lstOtros = $oOtros->Listado();
    $sumOtros = 0;
    if (count($lstOtros) > 0) {
        foreach ($lstOtros as $idx => $prenomina) {
            $sumOtros = $sumOtros + $prenomina->monto_por_semana;
        }
    }
    $arr = array($sumOtros);
    echo json_encode($arr);
}
?>