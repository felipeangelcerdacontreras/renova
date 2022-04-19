<?php
/*
 * Copyright 2021 - Felipe angel cerda contreras 
 * felipeangelcerdacontreras@gmail.com
 */
session_start();

$_SITE_PATH = $_SERVER["DOCUMENT_ROOT"] . "/" . explode("/", $_SERVER["PHP_SELF"])[1] . "/";
require_once($_SITE_PATH . "/app/model/comedor.nomina.class.php");
require_once($_SITE_PATH . "/app/model/empleados.class.php");

$oComedor = new comedor_nominas();
$oComedor->id = addslashes(filter_input(INPUT_POST, "id"));
$nombre = addslashes(filter_input(INPUT_POST, "nombre"));
$sesion = $_SESSION[$oComedor->NombreSesion];
$oComedor->Informacion();

$oEmpleados = new empleados();
$lstEmpleados = $oEmpleados->Listado();
?>
<script type="text/javascript">
    $(document).ready(function(e) {
        $("#nameModal").text("<?php echo $nombre ?> Otro cargo");
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
                $("#myModal").modal("hide");
            }
        });
        $('#id_empleado').select2({
            width: '100%'
        });
        $('#motivo').select2({
            width: '100%'
        });
    });
</script>
<!-- DataTales Example -->
<form id="frmFormulario" name="frmFormulario" action="app/views/default/modules/modulos/comedor/m.comedor.procesa.php" enctype="multipart/form-data" method="post" target="_self" class="form-horizontal">
    <div>
        <div class="form-group">
            <strong class="">Empleado:</strong>
            <div class="form-group">
                <select id="id_empleado" description="Seleccione el empleado" class="form-control obligado" name="id_empleado">
                    <?php
                    if (count($lstEmpleados) > 0) {
                        echo "<option value='0' >-- SELECCIONE --</option>\n";
                        foreach ($lstEmpleados as $idx => $campo) {
                            if ($campo->id == $oComedor->id_empleado) {
                                echo "<option value='{$campo->id}' selected >" . $campo->nombres . " " . $campo->ape_paterno . " " . $campo->ape_materno . "</option>\n";
                            } else {
                                echo "<option value='{$campo->id}' >" . $campo->nombres . " " . $campo->ape_paterno . " " . $campo->ape_materno . "</option>\n";
                            }
                        }
                    }
                    ?>
                </select>
            </div>
        </div>
        <div class="form-group">
            <strong class="">Cantidad:</strong>
            <div class="form-group">
                <input type="number" description="" id="" disabled value="<?= $oComedor->precio_platillo?>" name="" class="form-control obligado" />
            </div>
        </div>
        <div class="form-group">
            <strong class="">Cantidad a sumar:</strong>
            <div class="form-group">
                <input type="number" description="Ingrese la cantidad a sumar" aria-describedby="" name="sumar" class="form-control obligado" />
            </div>
        </div>
    </div>
    <input type="hidden" id="id" name="id" value="<?= $oComedor->id ?>" />
    <input type="hidden" id="user_id" name="user_id" value="<?= $sesion->id ?>">
    <input type="hidden" id="accion" name="accion" value="GUARDAR" />
    </div>
</form>