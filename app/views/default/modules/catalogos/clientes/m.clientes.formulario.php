<?php
/*
 * Copyright 2021 - Felipe angel cerda contreras 
 * felipeangelcerdacontreras@gmail.com
 */
session_start();

$_SITE_PATH = $_SERVER["DOCUMENT_ROOT"] . "/" . explode("/", $_SERVER["PHP_SELF"])[1] . "/";
require_once($_SITE_PATH . "/app/model/clientes.class.php");

$oClientes = new clientes ();
$oClientes->cli_id = addslashes(filter_input(INPUT_POST, "cli_id"));
$sesion = $_SESSION[$oClientes->NombreSesion];
$oClientes->Informacion();

?>
<script type="text/javascript">
$(document).ready(function(e) {
    $("#frmFormulario").ajaxForm({
        beforeSubmit: function(formData, jqForm, options) {},
        success: function(data) {
            var str = data;
            var datos0 = str.split("@")[0];
            var datos1 = str.split("@")[1];
            var datos2 = str.split("@")[2];
            if ((datos3 = str.split("@")[3]) === undefined) {
                datos3 = "";
            } else {
                datos3 = str.split("@")[3];
            }
            Alert(datos0, datos1 + "" + datos3, datos2);
            Listado();
            $("#myModal_1").modal("hide");
        }
    });
});
</script>
<form id="frmFormulario" name="frmFormulario"
    action="app/views/default/modules/catalogos/clientes/m.clientes.procesa.php" enctype="multipart/form-data"
    method="post" target="_self" class="form-horizontal">
    <div>
        <div class="form-group">
            <strong class="">Nombre:</strong>
            <div class="form-group">
                <input type="text" class="form-control form-control-user" aria-describedby="emailHelp" id="cli_nombre"
                    required name="cli_nombre" value="<?= $oClientes->cli_nombre ?>" class="form-control" />
            </div>
        </div>
        <div class="form-group">
            <strong class="">Direccion:</strong>
            <div class="form-group">
                <input type="text" class="form-control form-control-user" aria-describedby="emailHelp" id="cli_direccion"
                    required name="cli_direccion" value="<?= $oClientes->cli_direccion ?>"   class="form-control" />
            </div>
        </div>
        <div class="form-group">
            <strong class="">Telefono:</strong>
            <div class="form-group">
                <input type="text" class="form-control form-control-user" aria-describedby="emailHelp" id="cli_telefono"
                    required name="cli_telefono" value="<?= $oClientes->cli_telefono ?>"   class="form-control" />
            </div>
        </div>
        <div class="form-group">
            <strong class="">Linea Taxi:</strong>
            <div class="form-group">
                <input type="text" class="form-control form-control-user" aria-describedby="emailHelp" id="cli_lineataxi"
                    required name="cli_lineataxi" value="<?= $oClientes->cli_lineataxi ?>"   class="form-control" />
            </div>
        </div>
        <input type="hidden" id="cli_id" name="cli_id" value="<?= $oClientes->cli_id ?>" />
        <input type="hidden" id="accion" name="accion" value="GUARDAR" />
    </div>
</form>