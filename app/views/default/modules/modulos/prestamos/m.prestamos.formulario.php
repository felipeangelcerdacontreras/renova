<?php
/*
 * Copyright 2021 - Felipe angel cerda contreras 
 * felipeangelcerdacontreras@gmail.com
 */
session_start();

$_SITE_PATH = $_SERVER["DOCUMENT_ROOT"] . "/" . explode("/", $_SERVER["PHP_SELF"])[1] . "/";
require_once($_SITE_PATH . "/app/model/prestamos.class.php");
require_once($_SITE_PATH . "/app/model/empleados.class.php");

$oPrestamos = new prestamos();
$oPrestamos->id = addslashes(filter_input(INPUT_POST, "id"));
$nombre = addslashes(filter_input(INPUT_POST, "nombre"));
$sesion = $_SESSION[$oPrestamos->NombreSesion];
$oPrestamos->Informacion();

$oEmpleados = new empleados();
$lstEmpleados = $oEmpleados->Listado();
?>
<script type="text/javascript">
    $(document).ready(function(e) {
        $("#nameModal").text("<?php echo $nombre ?> Prestamo");
        $("#restoVisible").hide();
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
        $('[data-toggle="tooltip"]').tooltip();
    });
</script>
<!-- DataTales Example -->
<form id="frmFormulario" name="frmFormulario" action="app/views/default/modules/modulos/prestamos/m.prestamos.procesa.php" enctype="multipart/form-data" method="post" target="_self" class="form-horizontal">
    <div>
        <div class="form-group">
            <strong class="">Empleado:</strong>
            <div class="form-group">
                <select id="id_empleado" description="Seleccione el empleado" class="form-control obligado" name="id_empleado" onchange="PrestamoActivo()">
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
            <strong class="">Seleccionar fecha inicial de pago:</strong>
            <div class="form-group">
                <input type="date" description="Seleccione la fecha inicial del pago" aria-describedby="" id="fecha_pago" required name="fecha_pago" class="form-control obligado" />
            </div>
        </div>
        <div class="form-group">
            <strong class="">Monto a Prestar:</strong>
            <div class="form-group">
                <input type="number" readonly="true" aria-describedby="" id="restoVisible" class="form-control" data-toggle="tooltip" title="" data-original-title="La cantidad aqui sera sumada al prestamo actual"/>
                <input type="number" description="Ingrese el monto a prestar" aria-describedby="" id="monto" required name="monto" class="form-control obligado" />
            </div>
        </div>
        <div class="form-group">
            <strong class="">Numero de Semanas:</strong>
            <div class="form-group">
                <input type="number" description="Ingrese el numero de semanas" aria-describedby="" id="numero_semanas" required name="numero_semanas" class="form-control obligado" />
            </div>
        </div>
    </div>
    <input type="hidden" id="id" name="id" value="<?= $oPrestamos->id ?>" />
    <input type="hidden" id="user_id" name="user_id" value="<?= $sesion->id ?>">
    <input type="hidden" id="id_prestamo" name="id_prestamo" value="" />
    <input type="hidden" id="restante" name="restante" value="" />
    <input type="hidden" id="Semanas" name="Semanas" value="" />
    <input type="hidden" id="accion" name="accion" value="GUARDAR" />
    </div>
</form>