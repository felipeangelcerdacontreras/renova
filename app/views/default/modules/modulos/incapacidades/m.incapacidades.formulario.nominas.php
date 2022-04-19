<?php
/*
 * Copyright 2021 - Felipe angel cerda contreras 
 * felipeangelcerdacontreras@gmail.com
 */
session_start();

$_SITE_PATH = $_SERVER["DOCUMENT_ROOT"] . "/" . explode("/", $_SERVER["PHP_SELF"])[1] . "/";
require_once($_SITE_PATH . "/app/model/usuarios.class.php");
require_once($_SITE_PATH . "/app/model/empleados.class.php");
require_once($_SITE_PATH . "/app/model/incapacidades.class.php");
require_once($_SITE_PATH . "vendor/autoload.php");

use Carbon\Carbon;

date_default_timezone_set('America/Mexico_City');

$oIncapacidades = new incapacidades();
$oIncapacidades->id = addslashes(filter_input(INPUT_POST, "id"));
$nombre = addslashes(filter_input(INPUT_POST, "nombre"));
$sesion = $_SESSION[$oIncapacidades->NombreSesion];
$oIncapacidades->Informacion();

$oEmpleados = new empleados();
$lstEmpleados = $oEmpleados->Listado();

?>
<script type="text/javascript">
    $(document).ready(function(e) {
        var btnGuardar = <?php echo empty($oIncapacidades->inicio_incapacida); ?> + "";
        if (btnGuardar <= 0) {
            $("#btnGuardar").hide();
        }
        $("#cantidad").hide();

        $("#nameModal_").text("<?php echo $nombre ?> Incapacidades");

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
                $("#myModal_incapacidades").modal("hide");
            }
        });

        $("#btnImprimir").button().click(function(e) {
            var opc = "fullscreen=no, menubar=no, resizable=no, scrollbars=yes, status=yes, titlebar=yes, toolbar=no, width=750, height=580";
            var pagina = "app/views/default/modules/modulos/incapacidades/m.incapacidades.recibo.pdf.php?";

            pagina += "id=" + $("#id").val();

            window.open(pagina, "reporte", opc);
        });

        $('#id_empleado').select2({
            width: '100%'
        });
        $('[data-toggle="tooltip"]').tooltip();
    });

    function contador_dias() {
        var timeStart = new Date(document.getElementById("inicio_incapacida").value);
        var timeEnd = new Date(document.getElementById("fin_incapacida").value);
        var actualDate = new Date();

        if (timeEnd >= timeStart) {
            var diff = timeEnd.getTime() - timeStart.getTime();
            dias_totales = Math.round(diff / (1000 * 60 * 60 * 24) + 1);

            $("#dias_autorizados").val(dias_totales).trigger('change');
            if (dias_totales > 3) {
                $("#cantidad").show();
                $("#monto_incapacidad").addClass('obligado');
            }

        } else if (timeEnd != null && timeEnd < timeStart) {
            Alert("", 'La fecha final de la promoción debe ser mayor a la fecha inicial', "warning", 900, false);
        }
    }

    function input_reingreso() {
        var timeStart = new Date(document.getElementById("fin_incapacida").value);
        var Reingreso = new Date(document.getElementById("reingreso").value);
        var actualDate = new Date();

        if (timeStart >= Reingreso) {
            $("#btnGuardar").hide();
             Alert("", 'La fecha de reingreso debe ser mayor a la fecha final', "warning", 900, false);
        } else {
            $("#btnGuardar").show();
        }
    }
    
    function Porcentaje() {
        let real = $("#monto_real").val();
        let seguro = $("#monto_incapacidad").val();
        let dias = $("#dias_autorizados").val();

        if (real > 0 && seguro > 0 && dias > 0) {
            if ($("#ramo_seguro").val() == 1 || $("#ramo_seguro").val() == 3) {
                cantidad_dia = (parseFloat(real) - parseFloat(seguro));    
            } else {
                cantidad_dia = ((parseFloat(real) - parseFloat(seguro)) * 0.60);    
            }
            $("#monto_dia").val(cantidad_dia);
        }
    }
</script>

<form id="frmFormulario_" name="frmFormulario_" action="app/views/default/modules/modulos/incapacidades/m.incapacidades.procesa.php" enctype="multipart/form-data" method="post" target="_self" class="form-horizontal">
    <div>
        <div class="row">
            <div class="col">
                <div class="form-group">
                    <strong class="">Empleado:</strong>
                    <div class="form-group">
                        <?php if ($oIncapacidades->id_empleado != "") {
                            echo "<input type='hidden' name='id_empleado' value='$oIncapacidades->id_empleado' >";
                        } ?>
                        <select id="id_empleado" description="Seleccione el empleado" <?php if ($oIncapacidades->id_empleado != "") {
                                                                                            echo "disabled";
                                                                                        } ?> class="form-control obligado" onchange="DatosEmpleado()" name="id_empleado">
                            <?php
                            if (count($lstEmpleados) > 0) {
                                echo "<option value='0' >-- SELECCIONE --</option>\n";
                                foreach ($lstEmpleados as $idx => $campo) {
                                    if ($campo->id == $oIncapacidades->id_empleado) {
                                        echo "<option value='{$campo->id}' selected>" . $campo->nombres . " " . $campo->ape_paterno . " " . $campo->ape_materno . "</option>\n";
                                    } else {
                                        echo "<option value='{$campo->id}'>" . $campo->nombres . " " . $campo->ape_paterno . " " . $campo->ape_materno . "</option>\n";
                                    }
                                }
                            }
                            ?>
                        </select>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col">
                <div class="form-group">
                    <strong class="">Tipo de incapacidad:</strong>
                        <div class="form-group">
                            <select id="tipo_incapacidad" name="tipo_incapacidad" description="Seleccione los dias a disfrutar" <?php if ($oIncapacidades->tipo_incapacidad != "") {
                                                                                            echo "disabled";
                                                                                        } ?> class="form-control obligado">
                            <option value="">--SELECCIONE--</option>
                            <option value="1" 
                                <?php if ($oIncapacidades->tipo_incapacidad == "1") echo "selected";?>>Unica</option>
                            <option value="2" 
                                <?php if ($oIncapacidades->tipo_incapacidad == "2") echo "selected";?>>Inicial</option>
                            <option value="3" 
                                <?php if ($oIncapacidades->tipo_incapacidad == "3") echo "selected";?>>Subsecuente</option>
                            <option value="4" 
                                <?php if ($oIncapacidades->tipo_incapacidad == "4") echo "selected";?>>Alta Medica-ST2</option>
                            </select>
                        </div>
                </div>
            </div>
            <div class="col">
                <strong class="">Serie y Folio:</strong>
                <div class="form-group">
                    <input type="input" id="folio" name="folio" <?php if (!empty($oIncapacidades->folio)) {
                                                                                echo "readonly='true'";
                                                                            } ?> value="<?= $oIncapacidades->folio; ?>" class="form-control" onkeyup="javascript:this.value=this.value.toUpperCase();">
                </div>
            </div>
            <div class="col">
                <strong class="">Dias autorizados:</strong>
                <div class="form-group">
                    <input type="input" id="dias_autorizados" name="dias_autorizados" readonly='true' value="<?= $oIncapacidades->dias_autorizados ?>" class="form-control">
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col">
                <strong>A partir del día:</strong>
                <div class="form-group ">
                    <input type="date" id="inicio_incapacida" name="inicio_incapacida" <?php if ($oIncapacidades->inicio_incapacida != "") {
                                                                                    echo "readonly='true'";
                                                                                } ?> value="<?= $oIncapacidades->inicio_incapacida; ?>" class="form-control">
                </div>
            </div>
            <div class="col">
                <strong>Hasta el día:</strong>
                <div class="form-group">
                    <input type="date" id="fin_incapacida" name="fin_incapacida" <?php if (!empty($oIncapacidades->fin_incapacida)) {
                                                                                echo "readonly='true'";
                                                                            } ?> value="<?= $oIncapacidades->fin_incapacida; ?>" onchange="contador_dias()" class="form-control">
                </div>
            </div>
            <div class="col" hidden>
                <strong>Dia que regresa a trabajar: </strong>
                <br />
                <div class="form-group">
                    <input type="date" id="reingreso" name="reingreso" <?php if (!empty($oIncapacidades->reingreso)) {
                                                                            echo "readonly='true'";
                                                                        } ?> value="<?= $oIncapacidades->reingreso; ?>" onchange="input_reingreso()" class="form-control">
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col">
            <div class="form-group">
                <strong class="">Ramo del seguro:</strong>
                <div class="form-group">
                    <select id="ramo_seguro" name="ramo_seguro" description="Seleccione los dias a disfrutar" class="form-control obligado" onchange="Porcentaje()" >
                    <option value="">--SELECCIONE--</option>
                    <option value="1" 
                        <?php if ($oIncapacidades->ramo_seguro == "1") echo "selected";?>>Riesgo de trabajo</option>
                    <option value="2" 
                        <?php if ($oIncapacidades->ramo_seguro == "2") echo "selected";?>>Enfermedad general</option>
                    <option value="3" 
                        <?php if ($oIncapacidades->ramo_seguro == "3") echo "selected";?>>Maternidad</option>
                    </select>
                </div>
            </div>
        </div>
        <div class="col">
            <strong>Expedido del día: </strong>
            <div class="form-group">
                <input type="date" id="expedido" name="expedido" <?php if (!empty($oIncapacidades->expedido)) {
                                                                        echo "readonly='true'";
                                                                    } ?> value="<?= $oIncapacidades->expedido; ?>" class="form-control">
            </div>
        </div>
        <div class="col">
            <div class="form-group">
                <strong class="">Probable riesgo trabajo:</strong>
                <div class="form-group">
                    <select id="riesgo" name="riesgo" description="Seleccione los dias a disfrutar" class="form-control obligado">
                    <option value="">--SELECCIONE--</option>
                    <option value="1" 
                        <?php if ($oIncapacidades->riesgo == "1") echo "selected";?>>Si</option>
                    <option value="2" 
                        <?php if ($oIncapacidades->riesgo == "2") echo "selected";?>>No</option>
                    </select>
                </div>
            </div>
        </div>
    </div>
    <div class="row" id="cantidad">
        <div class="col">
            <div class="form-group">
                <strong class="">Monto incapacidad(Seguro):</strong>
                <div class="form-group">
                <input type="text" onkeypress="return solonumeros(event);" onchange="Porcentaje()" onchange="" id="monto_incapacidad" name="monto_incapacidad" value="<?= $oIncapacidades->monto_incapacidad; ?>" class="form-control">
                </div>
            </div>
        </div>
        <div class="col">
            <div class="form-group">
                <strong class="">Monto Real(Renova):</strong>
                <div class="form-group">
                    <input type="text"readonly="true" id="monto_real" name="" value="" class="form-control">
                </div>
            </div>
        </div>
        <div class="col">
            <div class="form-group">
                <strong class="">Cantidad por día:</strong>
                <div class="form-group">
                    <input type="text" readonly="true" id="monto_dia" name="monto_dia" value="<?= $oIncapacidades->monto_dia; ?>" class="form-control">
                </div>
            </div>
        </div>
    </div>
    <div class="form-group">
        <div class="form-group">
            <strong>Observaciones:</strong>
        </div>
            <div class="form-group" style="width: 100%;">
                <textarea id="observaciones" name="observaciones" class="form-group" rows="3" style="width: 100%;"><?= $oIncapacidades->observaciones ?></textarea>
            </div>
    </div>
    </div>
    </div>
    <input type="hidden" id="id" name="id" value="<?= $oUsuarios->id ?>" />
    <input type="hidden" id="user_id" name="user_id" value="<?= $sesion->id ?>">
    <input type="hidden" id="accion" name="accion" value="GUARDAR" />
</form>