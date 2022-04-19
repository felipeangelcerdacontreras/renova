<?php
/*
 * Copyright 2021 - Felipe angel cerda contreras 
 * felipeangelcerdacontreras@gmail.com
 */
session_start();

$_SITE_PATH = $_SERVER["DOCUMENT_ROOT"] . "/" . explode("/", $_SERVER["PHP_SELF"])[1] . "/";
require_once($_SITE_PATH . "/app/model/prestamos.class.php");

$oPrestamos = new prestamos();
$oPrestamos->id = addslashes(filter_input(INPUT_POST, "id"));
$nombre = addslashes(filter_input(INPUT_POST, "nombre"));
$empleado = addslashes(filter_input(INPUT_POST, "empleado"));
$sesion = $_SESSION[$oPrestamos->NombreSesion];
$oPrestamos->Informacion();

$aPermisos = empty($oPrestamos->perfiles_id) ? array() : explode("@", $oPrestamos->perfiles_id);
?>
<script type="text/javascript">
    $(document).ready(function(e) {
        $("#nameModal").text("<?php echo $nombre ?> ");
        $("#frmFormulario_").ajaxForm({
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
                $("#myModal_nominas").modal("hide");
            }
        });
    });
</script>
<div>
    <h4><strong>Detalles del prestamo del empleado:</strong> </h4>
    <div class="row">
        <div class="col">
            <div class="form-group">
                <strong class="">Nombre:</strong>
                <div class="form-group">
                    <label><?= $empleado ?></label>
                </div>
            </div>
        </div>
        <div class="col">
            <div class="form-group">
                <strong class="">Fecha de inicio:</strong>
                <div class="form-group">
                    <label><?= $oPrestamos->fecha_registro ?></label>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col">
            <div class="form-group">
                <strong class="">Monto Solicitado:</strong>
                <div class="form-group">
                    <label>$<?= $oPrestamos->monto ?></label>
                </div>
            </div>
        </div>
        <div class="col">
            <div class="form-group">
                <strong class="">Monto a pagar:</strong>
                <div class="form-group">
                    <label>$<?= $oPrestamos->monto_pagar ?></label>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col">
            <div class="form-group">
                <strong class="">Pago semanal:</strong>
                <div class="form-group">
                    <label>$<?= $oPrestamos->monto_por_semana ?></label>
                </div>
            </div>
        </div>
        <div class="col">
            <div class="form-group">
                <strong class="">Semanas a pagar:</strong>
                <div class="form-group">
                    <label><?= $oPrestamos->numero_semanas ?></label>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col">
            <div class="form-group">
                <strong class="">Intereses:</strong>
                <div class="form-group">
                    <label>$<?= $oPrestamos->interes ?></label>
                </div>
            </div>
        </div>
        <div class="col">
            <div class="form-group">
                <strong class="">Restante a pagar:</strong>
                <div class="form-group">
                    <label>$<?= $oPrestamos->restante ?></label>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col">
            <div class="form-group">
                <strong class="">Semana en curso:</strong>
                <div class="form-group">
                    <label><?= $oPrestamos->semana_actual ?></label>
                </div>
            </div>
        </div>
    </div>
    <input type="hidden" id="id_" name="id_" value="<?= $oPrestamos->id ?>" />
    <input type="hidden" id="id_empleado_" name="id_empleado_" value="<?= $oPrestamos->id_empleado ?>">
    <input type="hidden" id="fecha_registro_" name="fecha_registro_" value="<?= $oPrestamos->fecha_registro ?>">
</div>