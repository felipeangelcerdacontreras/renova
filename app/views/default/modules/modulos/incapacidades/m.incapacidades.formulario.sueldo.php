<?php
/*
 * Copyright 2021 - Felipe angel cerda contreras 
 * felipeangelcerdacontreras@gmail.com
 */
session_start();

$_SITE_PATH = $_SERVER["DOCUMENT_ROOT"] . "/" . explode("/", $_SERVER["PHP_SELF"])[1] . "/";
require_once($_SITE_PATH . "/app/model/sueldoMinimo.class.php");

$oSueldo = new sueldo(true, $_POST);
$oSueldo->id = 1;

$sesion = $_SESSION[$oSueldo->NombreSesion];
$oSueldo->Informacion();

?>
<script type="text/javascript">
    $(document).ready(function(e) {

    });
</script>
<div>
    <form id="frmFormulario_salario" name="frmFormulario_salario" action="" enctype="multipart/form-data" method="post" target="_self" class="form-horizontal">
        <div class="row">
                <strong class="">Sueldo minimo:</strong>
                <input type="input" id="sueldo_minimo" name="sueldo_minimo" value="<?= $oSueldo->sueldo_minimo ?>" class="form-control" onkeypress="return solonumeros(event);">
            </div>
        </div>
        <input type="hidden" id="id_" name="id_" value="<?= $oSueldo->id ?>" />
        <input type="hidden" id="accion" name="accion" value="GUARDAR_SUELDO" />
    </form>
</div>