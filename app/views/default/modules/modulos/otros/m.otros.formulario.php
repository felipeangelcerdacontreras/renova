<?php
/*
 * Copyright 2021 - Felipe angel cerda contreras 
 * felipeangelcerdacontreras@gmail.com
 */
session_start();

$_SITE_PATH = $_SERVER["DOCUMENT_ROOT"] . "/" . explode("/", $_SERVER["PHP_SELF"])[1] . "/";
require_once($_SITE_PATH . "/app/model/otros.class.php");
require_once($_SITE_PATH . "/app/model/empleados.class.php");

$oOtros = new otros();
$oOtros->id = addslashes(filter_input(INPUT_POST, "id"));
$nombre = addslashes(filter_input(INPUT_POST, "nombre"));
$sesion = $_SESSION[$oOtros->NombreSesion];
$oOtros->Informacion();

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
<form id="frmFormulario" name="frmFormulario" action="app/views/default/modules/modulos/otros/m.otros.procesa.php" enctype="multipart/form-data" method="post" target="_self" class="form-horizontal">
    <div>
        <div class="form-group">
            <strong class="">Empleado:</strong>
            <div class="form-group">
                <select id="id_empleado" description="Seleccione el empleado" class="form-control obligado" name="id_empleado">
                    <?php
                    if (count($lstEmpleados) > 0) {
                        echo "<option value='0' >-- SELECCIONE --</option>\n";
                        foreach ($lstEmpleados as $idx => $campo) {
                            echo "<option value='{$campo->id}' >" . $campo->nombres . " " . $campo->ape_paterno . " " . $campo->ape_materno . "</option>\n";
                        }
                    }
                    ?>
                </select>
            </div>
        </div>
        <div class="form-group">
            <strong class="">Monto a Pagar:</strong>
            <div class="form-group">
                <input type="number" description="Ingrese el monto a prestar" aria-describedby="" id="monto" required name="monto" class="form-control obligado" />
            </div>
        </div>
        <div class="form-group">
            <strong class="">Fecha pago:</strong>
            <div class="form-group">
                <input type="date" description="Seleccione la fecha del pago" aria-describedby="" id="fecha_pago" required name="fecha_pago" class="form-control obligado" />
            </div>
        </div>
        <div class="form-group">
            <strong class="">Numero de Semanas:</strong>
            <div class="form-group">
                <input type="number" description="Ingrese el numero de semanas" aria-describedby="" id="numero_semanas" required name="numero_semanas" class="form-control obligado" />
            </div>
        </div>
        <div class="form-group">
            <strong class="">Causa del cargo:</strong>
            <div class="form-group">
                <select id="motivo" description="Seleccione el cargo" class="form-control obligado" name="motivo">
                    <option value='0'>--SELECCIONE--</option>
                    <option value='Daños a unidad'>Daños a unidad</option>
                    <option value='Daños/cambio a uniforme'>Daños/cambio a uniforme</option>
                    <option value='Descuento adelanto de nomina'>Descuento adelanto de nómina</option>
                    <option value='Daño/Extravio de celular'>Daño/Extravío de celular</option>
                    <option value='Perdida de carga'>Perdida de carga</option>
                    <option value='Perdida de herramienta almacen'>Perdida de herramienta almacén</option>
                </select>
            </div>
        </div>
        <div class="form-group">
            <strong class="">Detalles:</strong>
            <div class="form-group">
                <input type="text" description="" aria-describedby="" id="detalles" required name="detalles" class="form-control" />
            </div>
        </div>
    </div>
    <input type="hidden" id="id" name="id" value="<?= $oOtros->id ?>" />
    <input type="hidden" id="user_id" name="user_id" value="<?= $sesion->id ?>">
    <input type="hidden" id="accion" name="accion" value="GUARDAR" />
    </div>
</form>