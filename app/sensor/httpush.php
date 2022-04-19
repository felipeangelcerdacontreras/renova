<?php

require_once './bd.php';
set_time_limit(0);
date_default_timezone_set("America/Mexico_City");
clearstatcache();

$datosJson = "";
$fecha_actual = 0;
$fecha_bd = 0;
if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $fecha_actual = (isset($_POST['timestamp']) && $_POST['timestamp'] != 'null') ? $_POST['timestamp'] : 0;
} else {
    if (isset($_GET['timestamp']) && $_GET['timestamp'] != 'null') {
        $fecha_actual = $_GET['timestamp'];
    }
}

$conn = new bd();

while ($fecha_bd <= $fecha_actual) {
    $sql = "SELECT update_time FROM huellas_temp where pc_serial = '" . $_POST['token'] . "'  ORDER BY update_time DESC LIMIT 1";
    $rows = $conn->findAll($sql);
    usleep(100000);
    clearstatcache();

    if (count($rows) > 0) {
        $fecha_bd = strtotime($rows[0]['update_time']);
    }
}

$sql = "SELECT pc_serial,imgHuella,update_time,texto,statusPlantilla,documento,nombre, opc"
        . " FROM huellas_temp ORDER BY update_time DESC LIMIT 1";
$rows = $conn->findAll($sql);

$reponse = array();
$reponse["id"] = $rows[0]['pc_serial'];
$reponse["timestamp"] = strtotime($rows[0]['update_time']);
$reponse["texto"] = $rows[0]['texto'];
$reponse["statusPlantilla"] = $rows[0]['statusPlantilla'];
$reponse["nombre"] = $rows[0]['nombre'];
$reponse["documento"] = $rows[0]['documento'];
$reponse["imgHuella"] = $rows[0]['imgHuella'];
$reponse["tipo"] = $rows[0]['opc'];

$datosJson = json_encode($reponse);
$conn->desconectar();
echo $datosJson;




