<?php
/*
 * Copyright 2021 - Felipe angel cerda contreras 
 * felipeangelcerdacontreras@gmail.com
 */
session_start();

$_SITE_PATH = $_SERVER["DOCUMENT_ROOT"] . "/" . explode("/", $_SERVER["PHP_SELF"])[1] . "/";
require_once($_SITE_PATH . "/app/model/horas.class.php");
require_once($_SITE_PATH . "/app/model/empleados.class.php");

$oHoras = new horas();
$oHoras->id = addslashes(filter_input(INPUT_POST, "id"));
$nombre = addslashes(filter_input(INPUT_POST, "nombre"));
$sesion = $_SESSION[$oHoras->NombreSesion];
$oHoras->Informacion();

$oEmpleados = new empleados();
$lstEmpleados = $oEmpleados->Listado();
?>
<script type="text/javascript">
    $(document).ready(function(e) {
        $("#nameModal").text("<?php echo $nombre ?> Horas Extras");
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
        $('#id_empleado').select2({
            width: '100%'
        });
    });
</script>
<form id="frmFormulario" name="frmFormulario" action="app/views/default/modules/catalogos/horas/m.horas.procesa.php" enctype="multipart/form-data" method="post" target="_self" class="form-horizontal">
    <div>
        <div class="form-group">
            <strong class="">Empleado:</strong>
            <div class="form-group">
                <select id="id_empleado" description="Seleccione el empleado" class="form-control obligado" name="id_empleado">
                    <?php
                    if (count($lstEmpleados) > 0) {
                        echo "<option value='0' >-- SELECCIONE --</option>\n";
                        foreach ($lstEmpleados as $idx => $campo) {
                            if ($campo->estatus == "ACTIVO") {
                                echo "<option value='{$campo->id}' >".$campo->nombres." "."$campo->ape_paterno"." ".$campo->ape_materno."</option>\n";
                            }
                        }
                    }
                    ?>
                </select>
            </div>
        </div>
        <div class="form-group">
            <strong class="">Fecha de pago:</strong>
            <div class="form-group">
                <input type="date" description="Ingrese las horas a registrar" class="form-control obligado" aria-describedby="" id="fecha_registro" required name="fecha_registro" value="<?= $oHoras->fecha_registro ?>" />
            </div>
        </div>
        <div class="form-group">
            <strong class="">Horas Extras a Registrar:</strong>
            <div class="form-group">
                <input type="number" description="Ingrese las horas a registrar" class="form-control obligado" aria-describedby="" id="horas_extras" required name="horas_extras" value="<?= $oHoras->horas_extras ?>" />
            </div>
        </div>
        <div class="form-group">
            <strong class="">Motivo:</strong>
            <div class="form-group">
                <textarea name="motivo" id="motivo" description="Ingrese el motivo" class="form-control obligado"><?= $oHoras->motivo ?></textarea>
            </div>
        </div>
        <input type="hidden" id="id" name="id" value="<?= $oHoras->id ?>" />
        <input type="hidden" id="user_id" name="user_id" value="<?= $sesion->id ?>">
        <input type="hidden" id="accion" name="accion" value="GUARDAR" />
    </div>
</form>