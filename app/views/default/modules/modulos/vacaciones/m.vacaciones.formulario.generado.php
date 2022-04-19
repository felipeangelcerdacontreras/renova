<?php
/*
 * Copyright 2021 - Felipe angel cerda contreras 
 * felipeangelcerdacontreras@gmail.com
 */
session_start();

$_SITE_PATH = $_SERVER["DOCUMENT_ROOT"] . "/" . explode("/", $_SERVER["PHP_SELF"])[1] . "/";
require_once($_SITE_PATH . "/app/model/vacaciones.class.php");

$oVacaciones = new vacaciones(true, $_POST);

?>
<script type="text/javascript">
$(document).ready(function(e) {
    $("#btnGenerar").click(Listado2);
});


</script>
<!-- DataTales Example -->
<div class="card shadow mb-4">
    <div class="card shadow mb-4">
        <center>
            <div class="card-header py-3" style="text-align:left">
                <div class="row">
                    <div class="col-9">
                        <div class="form-group">
                            <strong class="">Fecha de nomina:</strong>
                            <input type="date" aria-describedby="" id="fecha_genera"
                                value="<?php echo date('Y-m-d'); ?>" required name="fecha_genera"
                                class="form-control" />
                        </div>
                    </div>
                    <div class="col"><br>
                    <input type="button" id="btnGenerar" class="btn btn-outline-danger" name="btnGenerar" value="Generar Primas">
                    </div>
                </div>
            </div>
        </center>
    </div>
    <div id="divListado_V"></div>
</div>