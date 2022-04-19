<?php

include_once './bd.php';
$con = new bd();
$ext = "jpg";
if (isset($_GET['documento']) && !empty($_GET['documento'])) {
    $documento = $_GET['documento'];
    $rs = $con->findAll("SELECT foto, ext from usuarios WHERE documento = '" . $documento . "'");
    if (count($rs) > 0) {
        $foto = $rs[0]['foto'];
        if ($rs[0]['ext'] != '') {
            $ext = $rs[0]['ext'];
        }
        header("Content-type: image/" . $ext);
        if ($foto != "") {
            echo $foto;
        }
    } else {
        $img = "../imagenes/default.png";
        $dat = file_get_contents($img);
        echo $dat;
    }
} else {
    header("Content-type: image/" . $ext);
    $img = "../imagenes/default.png";
    $dat = file_get_contents($img);
    echo $dat;
}
?>








