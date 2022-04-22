<?php
/*
 * Copyright 2021 - Felipe angel cerda contreras 
 * felipeangelcerdacontreras@gmail.com
*/
session_start();

$_SITE_PATH = $_SERVER["DOCUMENT_ROOT"] . "/" . explode("/", $_SERVER["PHP_SELF"])[1] . "/";
require_once($_SITE_PATH . "/app/model/vacaciones.class.php");
require_once($_SITE_PATH . "/app/model/empleados.class.php");
require_once($_SITE_PATH . "vendor/autoload.php"); 

use Carbon\Carbon;


$accion = addslashes(filter_input(INPUT_POST, "accion"));

if ($accion == "GUARDAR") {
    $oVacaciones = new vacaciones(true, $_POST);
    if ($oVacaciones->Guardar() === true) {
        echo "Sistema@Se ha registrado exitosamente la información. @success";
    } else {
        echo "Sistema@Ha ocurrido un error al guardar la información , vuelva a intentarlo o consulte con el administrador del sistema.@warning";
    }
} else if ($accion == "GUARDAR_VACACIONES") {
    $oVacaciones = new vacaciones(true, $_POST);

    $contador = addslashes(filter_input(INPUT_POST, "contador"));
    $result = 1;

    for ($i = 1; $i <= $contador; $i++) {
        $oVacaciones->id_vacaciones = addslashes(filter_input(INPUT_POST, "id_vacaciones-".$i));
        $oVacaciones->id_vac = addslashes(filter_input(INPUT_POST, "id_vac-".$i));
        $oVacaciones->id_empleado = addslashes(filter_input(INPUT_POST, "id_empleado-".$i));
        $oVacaciones->ano = addslashes(filter_input(INPUT_POST, "ano-".$i));
        $oVacaciones->dias = addslashes(filter_input(INPUT_POST, "dias-".$i));
        $oVacaciones->fecha_pago = addslashes(filter_input(INPUT_POST, "fecha_pago-".$i));
        $oVacaciones->pago_prima = addslashes(filter_input(INPUT_POST, "pago_prima-".$i));
        $oVacaciones->periodo_inicio = addslashes(filter_input(INPUT_POST, "periodo_inicio-".$i));
        $oVacaciones->periodo_fin = addslashes(filter_input(INPUT_POST, "periodo_fin-".$i));
        
        $oVacaciones->fecha_final = addslashes(filter_input(INPUT_POST, "fecha_final-".$i));

        if ($oVacaciones->VacacionesGeneradas() === true) {
            $result = $i+1;
        }
    }
    if ($contador == $result) {
    echo "Sistema@Se ha registrado exitosamente la información. @success";
    } else {
        echo "Sistema@Este usuario ya tiene vacaciones y/o la nomina de la fecha actual ya a sido cerrada@warning";
    }
} else if ($accion == "Empleado") {
    $oEmpleados = new empleados();
    $oEmpleados->id = addslashes(filter_input(INPUT_POST, "id"));
    $resultado = $oEmpleados->Informacion();
    
    if (count($resultado) > 0){
        echo "{$resultado[0]->fecha_ingreso}"."@";
        echo "{$resultado[0]->salario_diario}";
    }

} else if ($accion == "ANOS") {
    $oVacaciones = new vacaciones(true, $_POST);
    $resultado = $oVacaciones->ObtenerAños(addslashes(filter_input(INPUT_POST, "fecha_ingreso")));
     if ($resultado[0]->años_transcurridos <= 0) {
        $resultado[0]->años_transcurridos = 1;
     }
    echo $resultado[0]->años_transcurridos;
} else if ($accion == "DIAS") {
    $oVacaciones = new vacaciones(true, $_POST);
    $resultado = $oVacaciones->ObtenerDias(addslashes(filter_input(INPUT_POST, "anos")), addslashes(filter_input(INPUT_POST, "id_empleado"))); 
    
    echo $resultado[0]->dias."@";
    if (!empty($resultado[1]->dias)) {
        echo $resultado[1]->dias;
    } else {
        echo '0';
    }
} else if ($accion == "DiasTotales") {
    $oVacaciones = new vacaciones(true, $_POST);
    $dias = addslashes(filter_input(INPUT_POST, "dias"));
    $fecha_inicial = addslashes(filter_input(INPUT_POST, "fecha_inicial"));
    $fecha_final = addslashes(filter_input(INPUT_POST, "fecha_final"));

    $resultado = $oVacaciones->DiasVacacion($dias, $fecha_inicial, $fecha_final); 
    
    echo $resultado;
} else if ($accion == "VERIFICAR_REGISTRO") {
    $oVacaciones = new vacaciones(true, $_POST);
    $val = $oVacaciones->VerificarR();
    
    if ($val === true) {
        echo 1; 
    } else {
        echo 2;
    }
} else if ($accion == "VERIFICAR_NOMINA") {
    $oVacaciones = new vacaciones(true, $_POST);
    $resultado = $oVacaciones->VerificarNomina();
    echo $resultado[0]->id."@";
    echo $resultado[1]->id;
}
?>
