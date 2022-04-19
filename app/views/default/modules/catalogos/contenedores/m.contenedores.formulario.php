<?php
/*
 * Copyright 2021 - Felipe angel cerda contreras 
 * felipeangelcerdacontreras@gmail.com
 */
session_start();

$_SITE_PATH = $_SERVER["DOCUMENT_ROOT"] . "/" . explode("/", $_SERVER["PHP_SELF"])[1] . "/";
require_once($_SITE_PATH . "/app/model/contenedores.class.php");

$oContenedores = new contenedores();
$oContenedores->id = addslashes(filter_input(INPUT_POST, "id"));
$nombre = addslashes(filter_input(INPUT_POST, "nombre"));
$sesion = $_SESSION[$oContenedores->NombreSesion];
$oContenedores->Informacion();

?>
<script type="text/javascript">
    $(document).ready(function(e) {
        $("#nameModal").text("<?php echo $nombre ?> Contenedor");
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
<form id="frmFormulario" name="frmFormulario" action="app/views/default/modules/catalogos/contenedores/m.contenedores.procesa.php" enctype="multipart/form-data" method="post" target="_self" class="form-horizontal">
    <div>
        <div class="form-group">
            <strong class="">Nombre/Numero:</strong>
            <div class="form-group">
                <input type="text" description="Ingrese el nombre/numero" aria-describedby="" id="nombre" required name="nombre" value="<?= $oContenedores->nombre ?>" class="form-control obligado" />
            </div>
        </div>
        <div class="form-group">
            <div class="row">
                <div class="col">
                    <div class="form-group">
                        <strong class="">Tipo:</strong>
                        <div class="form-group">
                            <input name="tipo" type="radio" id="tipo" value="1" required="" <?php if ($oContenedores->tipo == 1) echo "checked" ?>><label>&nbsp;Abierto</label>&nbsp;
                            <input name="tipo" type="radio" id="tipo" value="2" required="" <?php if ($oContenedores->tipo == 2) echo "checked" ?>><label>&nbsp;Cerrado</label>
                        </div>
                    </div>
                </div>
                <div class="col">
                    <div class="form-group">
                        <strong class="">Tara:</strong>
                        <div class="form-group">
                            <input type="text" description="Ingrese la tara" aria-describedby="" id="tara" required name="tara" value="<?= $oContenedores->tara ?>" class="form-control obligado" />
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="form-group">
            <strong class="">Capacidad:</strong>
            <div class="form-group">
                <input type="text" description="Ingrese la capacidad" aria-describedby="" id="capacidad" required name="capacidad" value="<?= $oContenedores->capacidad ?>" class="form-control obligado" />
            </div>
        </div>
            <input type="hidden" id="id" name="id" value="<?= $oContenedores->id ?>" />
            <input type="hidden" id="user_id" name="user_id" value="<?= $sesion->id ?>">
            <input type="hidden" id="accion" name="accion" value="GUARDAR" />
        </div>
</form>