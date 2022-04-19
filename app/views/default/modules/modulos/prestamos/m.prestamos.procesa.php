<?php
/*
 * Copyright 2021 - Felipe angel cerda contreras 
 * felipeangelcerdacontreras@gmail.com
*/
session_start();

$_SITE_PATH = $_SERVER["DOCUMENT_ROOT"] . "/" . explode("/", $_SERVER["PHP_SELF"])[1] . "/";
require_once($_SITE_PATH . "/app/model/prestamos.class.php");
require_once($_SITE_PATH . "/app/model/ahorros.class.php");

$accion = addslashes(filter_input(INPUT_POST, "accion"));


if ($accion == "GUARDAR") {
    $oAhorros = new ahorros(true, $_POST);
    $resultado2 = $oAhorros->Existe();

    $oPrestamos = new prestamos(true, $_POST);
    if ($resultado2) {
        $oPrestamos = new prestamos(true, $_POST);
        if (!empty($oPrestamos->id_prestamo) && !empty($oPrestamos->restante)) {
            if ($oPrestamos->Actualizar($oPrestamos->id_prestamo, $oPrestamos->Semanas) === true) {
                if ($oPrestamos->Guardar() === true) {
                    echo "Sistema@Se ha registrado exitosamente la información. @success";
                } else {
                    echo "Sistema@Ha ocurrido un error al guardar la información , vuelva a intentarlo o consulte con el administrador del sistema.@warning";
                }
            }
        } else {
            if ($oPrestamos->Guardar() === true) {
                echo "Sistema@Se ha registrado exitosamente la información. @success";
            } else {
                echo "Sistema@Ha ocurrido un error al guardar la información , vuelva a intentarlo o consulte con el administrador del sistema.@warning";
            }
        }
    } else {
        echo "Sistema@El empleado no cuenta con un ahorro.@warning";
    }
} else if ($accion == "ACTUALIZAR") {
    $oPrestamos = new prestamos(true, $_POST);

    if ($oPrestamos->Editar() === true) {
        echo "Sistema@Se actualizo el prestamo. @success";
    } else {
        echo "Sistema@Ha ocurrido un error al guardar la información , vuelva a intentarlo o consulte con el administrador del sistema.@warning";
    }
} else if ($accion == "Liquidado") {
    $oPrestamos = new prestamos(true, $_POST);

    if ($oPrestamos->Liquidar() === true) {
        echo "Sistema@Prestamo liquidado exitosamente. @success";
    } else {
        echo "Sistema@Ha ocurrido un error al guardar la información , vuelva a intentarlo o consulte con el administrador del sistema.@warning";
    }
} else if ($accion == "PrestamoActivo") {
    $oPrestamos = new prestamos(true, $_POST);
    $resultado = $oPrestamos->AhorroActivo();

    if ($resultado) {
        if (count($resultado) > 0) {
            echo $resultado[0]->restante . "@" . $resultado[0]->id . "@" . $resultado[0]->numero_semanas;
        }
    }
}
