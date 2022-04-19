<?php

include_once './bd.php';
$con = new bd();
$delete = "delete from huellas_temp where pc_serial = '" . $_POST['token'] . "'";
$con->exec($delete);
$insert = "insert into huellas_temp (pc_serial, texto, opc) values ('" . $_POST['token'] . "', 'El sensor de huella dactilar esta activado','leer')";
$con->exec($insert);
$con->desconectar();
//header("Location: ../verificar.php?token=" . $_POST['token']);
