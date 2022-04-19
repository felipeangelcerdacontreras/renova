<?php
/*
 * Copyright 2021 - Felipe angel cerda contreras 
 * felipeangelcerdacontreras@gmail.com
 */
session_start();

$_SITE_PATH = $_SERVER["DOCUMENT_ROOT"] . "/" . explode("/", $_SERVER["PHP_SELF"])[1] . "/";
require_once($_SITE_PATH . "/app/model/registro.class.php");
require_once($_SITE_PATH . "/app/model/marcas.class.php");


$oRegistro = new registro ();
$oRegistro->reg_id = addslashes(filter_input(INPUT_POST, "reg_id"));
$sesion = $_SESSION[$oRegistro->NombreSesion];
$oRegistro->Informacion();

$oMarcas = new marcas ();
$lstMarcas = $oMarcas->Listado();

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
function autocompletar(){

    var reg_cliente = $("#reg_cliente").val();
    $.ajax({
        data: "accion=LINEATAXI" + "&reg_cliente=" +reg_cliente,
        type: "GET",
        url: "app/views/default/modules/modulos/registro/m.registro.querys.php",
        success: function(datos) {
            $("#reg_lineataxi").removeAttr("readonly");
            $("#reg_lineataxi").val(datos);
        }
    });
}

</script>
<form id="frmFormulario" name="frmFormulario"
    action="app/views/default/modules/modulos/registro/m.registro.procesa.php" enctype="multipart/form-data"
    method="post" target="_self" class="form-horizontal">
    <div>
        <div class="form-group">
            <strong class="">Cliente:</strong>
            <div class="form-group">
                <input type="text" class="form-control form-control-user" aria-describedby="emailHelp" id="reg_cliente"
                    required name="reg_cliente" value="<?= $oRegistro->reg_cliente ?>" class="form-control" onchange="autocompletar()"/>
            </div>
        </div>
        <div class="form-group">
            <strong class="">Linea Taxi:</strong>
            <div class="form-group">
                <input type="text" class="form-control form-control-user" aria-describedby="emailHelp" id="reg_lineataxi"
                    required name="reg_lineataxi" value="<?= $oRegistro->reg_lineataxi ?>"  readonly class="form-control" />
            </div>
        </div>
        <div class="form-group">
            <strong class="">Marca Vehiculo:</strong>
            <div class="form-group">
                <select id="reg_marca" name="reg_marca" class="form-control form-control-user">
                    <?php
                    if (count($lstMarcas) > 0) {
                        echo "<option value='0' >--SELECCIONE--</option>\n";
                        foreach ($lstMarcas as $idx => $campo) {
                            print_r($campo);
                            if ($campo->mar_id == $oRegistro->reg_marca)
                                echo "<option value='{$campo->mar_id}' selected>{$campo->mar_nombre}</option>\n";
                            else
                                echo "<option value='{$campo->mar_id}'>{$campo->mar_nombre}</option>\n";
                        }
                    }
                    ?>
                </select>
            </div>
        </div>
        <input type="hidden" id="reg_id" name="reg_id" value="<?= $oRegistro->reg_id ?>" />
        <input type="hidden" id="accion" name="accion" value="GUARDAR" />
    </div>
</form>