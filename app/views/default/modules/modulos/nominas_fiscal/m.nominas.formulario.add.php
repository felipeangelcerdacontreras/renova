<?php
/*
 * Copyright 2021 - Felipe angel cerda contreras 
 * felipeangelcerdacontreras@gmail.com
 */
session_start();

$_SITE_PATH = $_SERVER["DOCUMENT_ROOT"] . "/" . explode("/", $_SERVER["PHP_SELF"])[1] . "/";
require_once($_SITE_PATH . "/app/model/empleados.class.php");

$id_nomina = addslashes(filter_input(INPUT_POST, "id_nomina"));
$fecha = (filter_input(INPUT_POST, "fecha")); 

$oEmpleados = new empleados();
$sesion = $_SESSION[$oEmpleados->NombreSesion];
$oEmpleados->estatus = "1";
$lstEmpleados = $oEmpleados->Listado();
?>
<script type="text/javascript">
    $(document).ready(function(e) {
        $("#frmFormulario_add").ajaxForm({
            beforeSubmit: function(formData, jqForm, options) {},
            success: function(data) {
                var str = data;
                var datos0 = str.split("@")[0];
                var datos1 = str.split("@")[1];
                var datos2 = str.split("@")[2];
                if ((datos3 = str.split("@")[3]) === undefined) {
                    datos3 = "";
                    name="ahorro"} else {
                    datos3 = str.split("@")[3];
                }
                Alert(datos0, datos1 + "" + datos3, datos2);
                Listado();
                $("#myModal_nominasAdd").modal("hide");
            }
        });
        $('#id_empleado').select2({
            width: '100%',
            dropdownParent: $('#myModal_nominasAdd')
        });
    });
    $('#id_empleado').change(function() {
        $("#nombre").val($( "#id_empleado option:selected" ).text());
    });

    $(".change").change(function() {

        let diario = $("#diario_add").val();

        let asistencias = $("#asistencias_add").val();

        totalPercepciones = ((diario * asistencias));
        //console.log(totalPercepciones);
        $("#total_add").val(totalPercepciones).trigger('change');
    });

</script>
<form id="frmFormulario_add" name="frmFormulario_add" action="app/views/default/modules/modulos/nominas_fiscal/m.nominas.procesa.php" enctype="multipart/form-data" method="post" target="_self" class="form-horizontal">
    <div>
        <div class="form-group">
            <strong class="">Empleado:</strong>
            <div class="form-group">
                <select id="id_empleado" description="Seleccione a el empleado" class="form-control obligado" name="id_empleado" onchange="DatosEmpleado()">
                    <?php
                    if (count($lstEmpleados) > 0) {
                        echo "<option value='0' >-- SELECCIONE --</option>\n";
                        foreach ($lstEmpleados as $idx => $campo) {
                            echo "<option value='{$campo->id}' >" .$campo->ape_paterno . " " . $campo->ape_materno . " ". $campo->nombres ."</option>\n";
                        }
                    }
                    ?>
                </select>
                <input type="hidden" id="nombre" name="nombre" value="" />
            </div>
        </div>

        <div class="row">
            <div class="col">
                <div class="form-group">
                    <strong class="">Salario diario:</strong>
                    <div class="form-group">
                        <input type="number" description="" readonly aria-describedby="" step="0.01"  id="diario_add" name="diario" class="form-control" />
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col">
                <div class="form-group">
                    <strong class="">Dias laborados:</strong>
                    <div class="form-group">
                        <input type="number" min="1" max="7" description="Ingrese los dias laborados" readonly id="asistencias_add" name="asistencias" class="form-control obligado" />
                    </div>
                </div>
            </div>
        </div>
        
        <div class="row" hidden>
            <div class="col">
                <div class="form-group">
                    <strong class="">Total Percepciones:</strong>
                    <div class="form-group">
                        <input type="number" readonly="true"  id="total_add" step="0.01"  name="total" class="form-control" />
                    </div>
                </div>
            </div>
        </div>

        <div class="row" hidden>
            <div class="col">
                <div class="form-group">
                    <strong class="">Total a pagar:</strong>
                    <div class="form-group">
                        <input type="number" readonly="true" id="total_p" step="0.01"  name="total_p" vale="0.00" class="form-control" />
                    </div>
                </div>
            </div>
        </div>
        <input type="hidden" id="id_nomina" name="id_nomina" value="<?= $id_nomina ?>" />
        <input type="hidden" id="fecha" name="fecha" value="<?= $fecha ?>" />
        <input type="hidden" id="user_id" name="user_id" value="<?= $sesion->id ?>">
        <input type="hidden" id="accion" name="accion" value="ADD_NOMINA" />
    </div>
</form>