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
    $('#add_asistencia').change(function() {
        if ($('#add_asistencia').is(':checked')) {
            $('#asistencia_add').attr('name','asistencia');
        } else {
            $('#asistencia_add').removeAttr('name');
        }
    });
    $('#add_puntualidad').change(function() {
        if ($('#add_puntualidad').is(':checked')) {
            $('#puntualidad_add').attr('name','puntualidad');
        } else {
            $('#puntualidad_add').removeAttr('name');
        }
    });
    $('#add_productividad').change(function() {
        if ($('#add_productividad').is(':checked')) {
            $('#productividad_add').attr('name','productividad');
        } else {
            $('#productividad_add').removeAttr('name');
        }
    });
    $('#add_bono').change(function() {
        if ($('#add_bono').is(':checked')) {
            $('#bono_add').attr('name','doce');
        } else {
            $('#bono_add').removeAttr('name');
        }
    });
    $('#add_complemento').change(function() {
        if ($('#add_complemento').is(':checked')) {
            $('#complemento_add').attr('name','complemento');
        } else {
            $('#complemento_add').removeAttr('name');
        }
    });
    $(".change").change(function() {
        let asistencia = 0;
        if ($('#add_asistencia').is(':checked')) {
            asistencia = $("#asistencia_add").val();
        }
        let puntualidad = 0;
        if ($('#add_puntualidad').is(':checked')) {
            puntualidad = $("#puntualidad_add").val();
        }
        let productividad = 0;
        if ($('#add_productividad').is(':checked')) {
            productividad = $("#productividad_add").val();
        }
        let doce = 0;
        if ($('#add_bono').is(':checked')) {
            doce = $("#bono_add").val();
        }
        let complemento = 0;
        if ($('#add_complemento').is(':checked')) {
            complemento = $("#complemento_add").val();
        }

        let diario = $("#diario_add").val();

        let asistencias = $("#asistencias_add").val();


        let horas_extras = 0;
        if ($("#extras").val() != "") {
            horas_extras = $("#extras").val();
        }

        let viaje = 0;
        if ($("#viaje").val() != "") {
            viaje = $("#viaje").val();
        }

        let extras = 0;
        if (horas_extras > 0){
            extras = ((diario / 8) * 2) * horas_extras;
        }
        $('input[name="extras"]').val(extras);
        totalPercepciones = ((diario * asistencias) + parseFloat(asistencia) + parseFloat(puntualidad) + parseFloat(productividad) + 
        parseFloat(doce) + parseFloat(complemento)  + parseFloat(extras) + parseFloat(viaje));
        //console.log(totalPercepciones);
        $("#total_add").val(totalPercepciones).trigger('change');
    });
    $("#total_add").change(function() {
        $.ajax({
            data: "accion=ExisteAhorro&id_empleado=" + $("#id_empleado").val(),
            type: "POST",
            url: "app/views/default/modules/modulos/nominas/m.nominas.procesa.php",
            beforeSend: function() {},
            success: function(datos) {
                ret = JSON.parse(datos);
                if (ret[0] != ""){
                    $("#ahorro_add").val(ret[0]).trigger('change');
                } else {
                    $("#ahorro_add").val("0.00").trigger('change');
                }
            }
        });
    });

    $("#comedor_add").change(function() {
        $.ajax({
            data: "accion=ExisteAhorro&id_empleado=" + $("#id_empleado").val(),
            type: "POST",
            url: "app/views/default/modules/modulos/nominas/m.nominas.procesa.php",
            beforeSend: function() {},
            success: function(datos) {
                ret = JSON.parse(datos);
                if (ret[0] != ""){
                    $("#ahorro_add").val(ret[0]).trigger('change');
                } else {
                    $("#ahorro_add").val("0.00").trigger('change');
                }
            }
        });
    });
    
    $("#ahorro_add").change(function() {
        $.ajax({
            data: "accion=ExistePrestamo&id_empleado=" + $("#id_empleado").val()+"&fecha="+$("#fecha").val(),
            type: "POST",
            url: "app/views/default/modules/modulos/nominas/m.nominas.procesa.php",
            beforeSend: function() {},
            success: function(datos) {
                ret = JSON.parse(datos);
                if (ret[0] != ""){
                    $("#prestamo_add").val(ret[0]).trigger('change');
                } else {
                    $("#prestamo_add").val("0.00").trigger('change');
                }
            }
        });
    });
    $("#prestamo_add").change(function() {
        $.ajax({
            data: "accion=ExisteFonacot&id_empleado=" + $("#id_empleado").val()+"&fecha="+$("#fecha").val(),
            type: "POST",
            url: "app/views/default/modules/modulos/nominas/m.nominas.procesa.php",
            beforeSend: function() {},
            success: function(datos) {
                ret = JSON.parse(datos);
                if (ret[0] != ""){
                $("#fonacot_add").val(ret[0]).trigger('change');
                } else {
                    $("#prestamo_add").val("0.00").trigger('change');
                }
            }
        });
    });
    $("#fonacot_add").change(function() {
        $.ajax({
            data: "accion=ExisteInfonavit&id_empleado=" + $("#id_empleado").val()+"&fecha="+$("#fecha").val(),
            type: "POST",
            url: "app/views/default/modules/modulos/nominas/m.nominas.procesa.php",
            beforeSend: function() {},
            success: function(datos) {
                ret = JSON.parse(datos);
                if (ret[0] != ""){
                    $("#infonavit_add").val(ret[0]).trigger('change');
                } else {
                    $("#infonavit_add").val("0.00").trigger('change');
                }
            }
        });
    });
    $("#infonavit_add").change(function() {
        $.ajax({
            data: "accion=ExisteOtros&id_empleado=" + $("#id_empleado").val()+"&fecha="+$("#fecha").val(),
            type: "POST",
            url: "app/views/default/modules/modulos/nominas/m.nominas.procesa.php",
            beforeSend: function() {},
            success: function(datos) {
                ret = JSON.parse(datos);
                if (ret[0] != ""){
                    $("#otros_add").val(ret[0]).trigger('change');
                } else {
                    $("#otros_add").val("0.00").trigger('change');
                }    
            }
        });
    });
    $("#otros_add").change(function() {
        let comedor = 0;
        if ($("#comedor_add").val() != "") {
            comedor = $("#comedor_add").val();
        }

        let ahorro = 0 
        if ($("#ahorro_add").val() != "") {
            ahorro = $("#ahorro_add").val();
        }

        let prestamo = 0;
        if ($("#prestamo_add").val() != "") {
            prestamo = $("#prestamo_add").val();
        }

        let infonavit = 0;
        if ($("#infonavit_add").val() != ""){
            infonavit = $("#infonavit_add").val();
        }

        let fonacot = 0;
        if ($("#fonacot_add").val() != "") {
            fonacot = $("#fonacot_add").val();
        }
        
        let otros = 0;
        if ($("#otros_add").val() != ""){
            otros = $("#otros_add").val();
        }

        totalRetenciones = parseFloat(comedor) + parseFloat(ahorro) + parseFloat(prestamo) + 
        parseFloat(fonacot) + parseFloat(otros) + parseFloat(infonavit);
        //console.log(totalPercepciones);
        $("#total_r").val(totalRetenciones).trigger('change');
    });

    $("#total_r").change(function() {
        let total_add = $("#total_add").val();

        let total_r = $("#total_r").val();

        total_p = parseFloat(total_add) - parseFloat(total_r);
        //console.log(totalPercepciones);
        $("#total_p").val(total_p).trigger('change');
    });

</script>
<form id="frmFormulario_add" name="frmFormulario_add" action="app/views/default/modules/modulos/nominas/m.nominas.procesa.php" enctype="multipart/form-data" method="post" target="_self" class="form-horizontal">
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
                    <strong class="">Premio de asistencia:</strong>
                    <input class="" type="checkbox" id="add_asistencia">
                    <strong>Agregar</strong>
                    <div class="form-group">
                        <input type="number" description="" readonly step="0.01" id="asistencia_add" class="form-control" />
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col">
                <div class="form-group">
                    <strong class="">Premio de puntualidad:</strong>
                    <input class="" type="checkbox" id="add_puntualidad" >
                    <strong>Agregar</strong>
                    <div class="form-group">
                        <input type="number" description="" readonly step="0.01" id="puntualidad_add" class="form-control" />
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col">
                <div class="form-group">
                    <strong class="">Bono de productividad:</strong>
                    <input class="" type="checkbox" id="add_productividad" >
                    <strong>Agregar</strong>
                    <div class="form-group">
                        <input type="number" description="" readonly aria-describedby="" step="0.01" id="productividad_add" class="form-control" />
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col">
                <div class="form-group">
                    <strong class="">Bono de 12 Hrs:</strong>
                    <input class="" type="checkbox" id="add_bono" >
                    <strong>Agregar</strong>
                    <div class="form-group">
                        <input type="number" description="" readonly aria-describedby="" step="0.01"  id="bono_add" class="form-control" />
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col">
                <div class="form-group">
                    <strong class="">Complemento de sueldo:</strong>
                    <input class="" type="checkbox" id="add_complemento" >
                    <strong>Agregar</strong>
                    <div class="form-group">
                        <input type="number" description="" readonly aria-describedby="" step="0.01"  id="complemento_add" class="form-control" />
                    </div>
                </div>
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
        <div class="row">
            <div class="col">
                <div class="form-group">
                    <strong class="">Bono viaje:</strong>
                    <div class="form-group">
                        <input type="number" description="" readonly placeholder="1.5" step="0.01" aria-describedby="" id="viaje" name="bono_viaje" class="form-control change " />
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col">
                <div class="form-group">
                    <strong class="">Horas extras(como el ejemplo):</strong>
                    <div class="form-group">
                        <input type="number" description="" readonly placeholder="1.5" step="0.1" aria-describedby="" id="extras" class="form-control change" />
                        <input type="number" description="" readonly placeholder="1.5" step="0.1" aria-describedby="" name="extras" class="form-control change" />
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
        <div class="row">
            <div class="col">
                <div class="form-group">
                    <strong class="">Comedor:</strong>
                    <div class="form-group">
                        <input type="number" id="comedor_add" step="0.01"  name="comedor" class="form-control" />
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col">
                <div class="form-group">
                    <strong class="">Ahorro:</strong>
                    <div class="form-group">
                        <input type="number" readonly="true" id="ahorro_add" step="0.01"  name="ahorro" vale="0.00" class="form-control" />
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col">
                <div class="form-group">
                    <strong class="">Prestamo:</strong>
                    <div class="form-group">
                        <input type="number" readonly="true" id="prestamo_add" step="0.01"  name="prestamo" vale="0.00" class="form-control" />
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col">
                <div class="form-group">
                    <strong class="">Fonacot:</strong>
                    <div class="form-group">
                        <input type="number" readonly="true" id="fonacot_add" step="0.01"  name="fonacot" vale="0.00" class="form-control" />
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col">
                <div class="form-group">
                    <strong class="">Infonavit:</strong>
                    <div class="form-group">
                        <input type="number" readonly="true" id="infonavit_add" step="0.01"  name="infonavit" vale="0.00" class="form-control" />
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col">
                <div class="form-group">
                    <strong class="">Otros cargos:</strong>
                    <div class="form-group">
                        <input type="number" readonly="true" id="otros_add" step="0.01"  name="otros" vale="0.00" class="form-control" />
                    </div>
                </div>
            </div>
        </div>
        <div class="row" hidden>
            <div class="col">
                <div class="form-group">
                    <strong class="">Total retenciones:</strong>
                    <div class="form-group">
                        <input type="number" readonly="true" id="total_r" step="0.01"  name="total_r" vale="0.00" class="form-control" />
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