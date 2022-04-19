<?php
/*
 * Copyright 2021 - Felipe angel cerda contreras 
 * felipeangelcerdacontreras@gmail.com
 */
session_start();

$_SITE_PATH = $_SERVER["DOCUMENT_ROOT"] . "/" . explode("/", $_SERVER["PHP_SELF"])[1] . "/";
require_once($_SITE_PATH . "/app/model/marcas.class.php");

$oMarcas = new marcas ();
$oMarcas->mar_id = addslashes(filter_input(INPUT_POST, "mar_id"));
$sesion = $_SESSION[$oMarcas->NombreSesion];
$oMarcas->Informacion();

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
    action="app/views/default/modules/catalogos/marcas/m.marcas.procesa.php" enctype="multipart/form-data"
    method="post" target="_self" class="form-horizontal">
    <div>
        <div class="form-group">
            <strong class="">Nombre:</strong>
            <div class="form-group">
                <input type="text" class="form-control form-control-user" aria-describedby="emailHelp" id="mar_nombre"
                    required name="mar_nombre" value="<?= $oMarcas->mar_nombre ?>" class="form-control" />
            </div>
        </div>
        <input type="hidden" id="mar_id" name="mar_id" value="<?= $oMarcas->mar_id ?>" />
        <input type="hidden" id="accion" name="accion" value="GUARDAR" />
    </div>
</form>