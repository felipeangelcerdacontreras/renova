<?php
/*
 * Copyright 2021 - Felipe angel cerda contreras 
 * felipeangelcerdacontreras@gmail.com
 */
session_start();

$_SITE_PATH = $_SERVER["DOCUMENT_ROOT"] . "/" . explode("/", $_SERVER["PHP_SELF"])[1] . "/";
require_once($_SITE_PATH . "/app/model/asistencia.class.php");
require_once($_SITE_PATH . "/app/model/empleados.class.php");

$oAsistencia = new asistencia();
$oAsistencia->id = addslashes(filter_input(INPUT_POST, "id"));
$nombre = addslashes(filter_input(INPUT_POST, "nombre"));
$sesion = $_SESSION[$oAsistencia->NombreSesion];
$oAsistencia->Informacion();

$oEmpleados = new empleados();
$oEmpleados->estatus = 1;
$lstEmpleados = $oEmpleados->Listado();
?>
<script type="text/javascript">
    $(document).ready(function(e) {
        $("#btnSincronizar").val("<?php echo $nombre ?>");
        $('#fecha').change(ObtenerNumDia);
        $("#nameModal").text("<?php echo $nombre ?> Asistencia");
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
        if ($('#add_salida').is(':checked')) { 
        $("#add_salida").addClass('obligado');
        } else {
            $("#add_salida").removeClass('obligado');
        }
    });

    function ObtenerNumDia() {
        var Xmas95 = new Date($("#fecha").val());
        var weekday = Xmas95.getDay();

        $("#dia").val(weekday + 1);
        $("#dia_").val(weekday + 1);
    }
</script>
<!-- DataTales Example -->
<form id="frmFormulario" name="frmFormulario" action="app/views/default/modules/modulos/asistencia/m.asistencia.procesa.php" enctype="multipart/form-data" method="post" target="_self" class="form-horizontal">
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
            <strong class="">Fecha:</strong>
            <div class="form-group">
                <input type="date" description="Seleccione la fecha" aria-describedby="" id="fecha" required name="fecha" class="form-control obligado" />
            </div>
        </div>
        <div class="form-group">
            <strong class="">Dia:</strong>
            <div class="form-group">
                <select id="dia_" description="Seleccione el empleado" class="form-control obligado" disabled name="dia_">
                    <option value='0'>Domingo</option>
                    <option value='1'>Lunes</option>
                    <option value='2'>Martes</option>
                    <option value='3'>Miercoles</option>
                    <option value='4'>Jueves</option>
                    <option value='5'>Viernes</option>
                    <option value='6'>Sabado</option>
                </select>
                <input type="hidden" id="dia" name="dia" value="">
            </div>
        </div>
        <div id="tab3" class="tab-pane">
            <strong class="">Entrada:</strong>
            <div class="card-header py-3">
                <div class="form-group">
                    <strong class="">Hora entrada:</strong>
                    <div class="form-group">
                        <input type="time" description="Seleccione la hora" aria-describedby="" id="hora_entrada" required name="hora_entrada" class="form-control obligado" />
                    </div>
                </div>
                <div class="form-group">
                    <strong class="">Tipo de entrada:</strong>
                    <div class="form-group">
                        <select id="estatus_entrada" description="Seleccione el tipo de entrada" class="form-control obligado" name="estatus_entrada">
                            <option value=''>--Seleccione--</option>
                            <option value='1'>A tiempo</option>
                            <option value='2'>Tarde</option>
                        </select>
                        <input type="hidden" id="dia" name="dia" value="">
                    </div>
                </div>
                <div class="form-group">
                    <strong class="">Quitar bonos:</strong>
                    <div class="form-group">
                        <div>
                            <input type="radio" id="quitar_bonos" name="quitar_bonos" value="0" checked>
                            <label for="quitar_bonos">No</label>
                        </div>
                        <div>
                            <input type="radio" id="quitar_bonos" name="quitar_bonos" value="1">
                            <label for="quitar_bonos">Si</label>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="form-group">
            <strong class="">Agregar salida:</strong>
            <input type="checkbox" name="add_salida" value="1">
        </div>
        <div id="tab3" class="tab-pane">
            <strong class="">Salida:</strong>
            <div class="card-header py-3">
                <div class="form-group">
                    <strong class="">Hora entrada:</strong>
                    <div class="form-group">
                        <input type="time" description="Seleccione la hora" aria-describedby="" id="hora_salida" required name="hora_salida" class="form-control obligado" />
                    </div>
                </div>
            </div>
        </div>
    </div>
    <input type="hidden" id="id" name="id" value="<?= $oAsistencia->id ?>" />
    <input type="hidden" id="user_id" name="user_id" value="<?= $sesion->id ?>">
    <input type="hidden" id="accion" name="accion" value="<?php echo $nombre ?>" />
    </div>
</form>