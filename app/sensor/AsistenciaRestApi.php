<?php

//Api Rest
/*
 * Copyright 2022 - Felipe angel cerda contreras 
 * felipeangelcerdacontreras@gmail.com
 */
header("Acces-Control-Allow-Origin: *");
header("Content-Type: application/json");

include_once './bd.php';
$con = new bd();

$method = $_SERVER['REQUEST_METHOD'];


// Metodo para peticiones tipo GET
if ($method == "POST") {
//    eliminar el token
    $desde = $_POST['desde'];
    $hasta = $_POST['hasta'];

    $sql = "SELECT `insert`, `update`,`fecha`,`id_empleado`  FROM asistencia_backup where fecha between '{$desde}' and '{$hasta}'";
    $rs = $con->findAll($sql);    
    
    

    $sql_ = "SELECT count(id) total from asistencia_backup where fecha between '{$desde}' and '{$hasta}'";
    $rs_c = $con->findAll($sql_);

    $arrayResponse = array();
    for ($index = 0; $index < count($rs); $index++) {

        $arrayObject = array();
        $arrayObject["total"] = $rs_c[0]['total'];
        $arrayObject["insert"] = $rs[$index]['insert'];
        $arrayObject["update"] = $rs[$index]["update"];
        $arrayObject["fecha"] = $rs[$index]["fecha"];
        $arrayObject["id_empleado"] = $rs[$index]["id_empleado"];
        $arrayResponse[] = $arrayObject;
    }
    echo json_encode($arrayResponse);
}