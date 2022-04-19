<?php

header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

include_once './bd.php';
set_time_limit(0);
date_default_timezone_set("America/Mexico_City");
$token = $_GET['token'];
$fecha_actual = 0;
$fecha_bd = 0;

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $fecha_actual = (isset($_POST['timestamp']) && $_POST['timestamp'] != 'null') ? $_POST['timestamp'] : 0;
} else {
    if (isset($_GET['timestamp']) && $_GET['timestamp'] != 'null') {
        $fecha_actual = $_GET['timestamp'];
    }
}

$con = new bd();
$elapsedTime = 0;
while ($fecha_bd <= $fecha_actual) {
    $query = "Select fecha_creacion from huellas_temp where pc_serial = '" . $token . "' ORDER BY id DESC LIMIT 1";
    $rs = $con->findAll($query);
    usleep(100000);
    clearstatcache();
    if (count($rs) > 0) {
        $fecha_bd = strtotime($rs[0]['fecha_creacion']);
    }
    $elapsedTime = $elapsedTime + 1;
    if ($elapsedTime == 1500) {//modificar aqui si se requiere reiniciar em menos tiempo
        break;
    }
}

$query = "Select fecha_creacion, opc from huellas_temp where pc_serial = '" . $token . "' ORDER BY id DESC LIMIT 1";
$datos_query = $con->findAll($query);

$array = array('fecha_creacion' => 0, 'opc' => 'reintentar');
for ($i = 0; $i < count($datos_query); $i++) {
    $array['fecha_creacion'] = strtotime($datos_query[$i]['fecha_creacion']);
    $array['opc'] = $datos_query[$i]['opc'];
}
$con->desconectar();
$response = json_encode($array);
//echo "hola Mundo";
echo $response;


