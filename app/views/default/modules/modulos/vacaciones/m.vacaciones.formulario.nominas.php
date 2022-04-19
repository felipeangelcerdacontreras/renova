<?php
/*
 * Copyright 2021 - Felipe angel cerda contreras 
 * felipeangelcerdacontreras@gmail.com
 */
session_start();

$_SITE_PATH = $_SERVER["DOCUMENT_ROOT"] . "/" . explode("/", $_SERVER["PHP_SELF"])[1] . "/";
require_once($_SITE_PATH . "/app/model/usuarios.class.php");
require_once($_SITE_PATH . "/app/model/empleados.class.php");
require_once($_SITE_PATH . "/app/model/vacaciones.class.php");
require_once($_SITE_PATH . "vendor/autoload.php");

use Carbon\Carbon;

date_default_timezone_set('America/Mexico_City');

$oVacaciones = new Vacaciones();
$oVacaciones->id = addslashes(filter_input(INPUT_POST, "id"));
$nombre = addslashes(filter_input(INPUT_POST, "nombre"));
$sesion = $_SESSION[$oVacaciones->NombreSesion];
$oVacaciones->Informacion();

$oEmpleados = new empleados();
$lstEmpleados = $oEmpleados->Listado();

$ley = "LFT: Artículo 81.- Las vacaciones deberán concederse a los trabajadores dentro de los seis meses siguientes al
cumplimiento del año de servicios. Los patrones entregarán anualmente a sus trabajadores una constancia que 
contenga su antigüedad y de acuerdo con ella el período de vacaciones que les corresponda y la fecha en que 
deberán disfrutarlo.";

$avacaciones = empty($oVacaciones->perfiles_id) ? array() : explode("@", $oVacaciones->perfiles_id);
?>
<script type="text/javascript">
    $(document).ready(function(e) {
        var btnGuardar = <?php echo empty($oVacaciones->inicio_vacaci); ?> + "";
        if (btnGuardar <= 0) {
            $("#btnGuardar").hide();
        }
        $('#id_empleado').change(Empleado);
        if ($("#id").val() != '') {
            Empleado();
        }
        $('#dias_disfrutar').change(Restante);
        fecha_inicio = <?php $oVacaciones->inicio_vacaci ?> + "";
        fecha_fin = <?php $oVacaciones->fin_vacaci . "" ?> + "";

        $("#nameModal_").text("<?php echo $nombre ?> Vacaciones");
        $("#entrada1").hide();
        $("#salida1").hide();
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
                $("#myModal_vacaciones").modal("hide");
            }
        });

        $("#btnImprimir").button().click(function(e) {
            var opc = "fullscreen=no, menubar=no, resizable=no, scrollbars=yes, status=yes, titlebar=yes, toolbar=no, width=750, height=580";
            var pagina = "app/views/default/modules/modulos/vacaciones/m.vacaciones.recibo.pdf.php?";

            pagina += "id=" + $("#id").val();

            window.open(pagina, "reporte", opc);
        });

        $('#id_empleado').select2({
            width: '100%'
        });
        $('[data-toggle="tooltip"]').tooltip();
    });

    $('#dias_pagados').on('change', function() {
        dias = $('#dias_pagados').val();
        salario = $('#salario_diario').val();
        prima = 0.25;

        pago_prima = (dias * salario * prima);
        $("#pago_prima").val(pago_prima);
    });

    $('input[type="checkbox"]').on('change', function() {
        if (this.checked) {
            $("#retur_work").hide();
            $("#reingreso").removeAttr("name");

            $("#pagar_dias").val($("#dias_disfrutar").val());
            $("#pagar_dias").attr("name", "pagar_dias");

            $("#salario_diario").attr("name", "salario_diario");
            $("#salario_diario").val(function(index, value) {
                return value.replace(/\D/g, "")
                    .replace(/([0-9])([0-9]{2})$/, '$1.$2')
                    .replace(/\B(?=(\d{3})+(?!\d)\.?)/g, ",");
            });

            $("#pagar_total").attr("name", "pagar_total");

            var total = $("#pagar_dias").val() * $("#salario_diario").val();

            $("#pagar_total").val(total);
            $("#pagar_total").val(function(index, value) {
                return value.replace(/\D/g, "")
                    .replace(/([0-9])([0-9]{2})$/, '$1.$2')
                    .replace(/\B(?=(\d{3})+(?!\d)\.?)/g, ",");
            });

            $("#pagar_concepto").removeAttr("readonly");
            $("#pagar_concepto").attr("name", "pagar_concepto");


        } else {
            $("#retur_work").show();
            $("#reingreso").attr("name", "reingreso");

            $("#pagar_dias").val('');
            $("#pagar_dias").removeAttr("name");

            $("#salario_diario").removeAttr("name");

            $("#pagar_total").removeAttr("name");
            $("#pagar_total").val('');

            $("#pagar_concepto").attr("readonly", "true");
            $("#pagar_concepto").removeAttr("name");
        }
    });

    function Empleado() {
        var jsonDatos = {
            "id": $("#id_empleado").val(),
            "accion": "Empleado"
        };

        $(".obligado").val("");

        $("#id_empleado").val(jsonDatos['id']);

        $.ajax({
            data: jsonDatos,
            type: "POST",
            url: "app/views/default/modules/modulos/vacaciones/m.vacaciones.procesa.php",
            success: function(datos) {
                if (datos != "") {
                    var str = datos;
                    var salario_diario = str.split("@")[1];
                    var datos = str.split("@")[0];
                    $("#fecha_ingreso").val(datos);
                    $("#salario_diario").val(salario_diario);

                    var jsonDatos = {
                        "fecha_ingreso": datos,
                        "accion": "ANOS"
                    };

                    $.ajax({
                        data: jsonDatos,
                        type: "POST",
                        url: "app/views/default/modules/modulos/vacaciones/m.vacaciones.procesa.php",
                        success: function(dato) {

                            $("#ano").val(dato);

                            var str = datos;
                            var año = str.split("-")[0];
                            var mes = str.split("-")[1];
                            var dia = str.split("-")[2];

                            var today = new Date();
                            var curYear = today.getFullYear();
                            var curYear1 = today.getFullYear() - 1;
                            $("#periodo_inicio").val(curYear1 + "-" + mes + "-" + dia);
                            $("#periodo_fin").val(curYear + "-" + mes + "-" + dia);

                            var d = new Date(curYear, mes, dia);
                            var r = new Date(d.setMonth(d.getMonth() + 5));

                            var months = ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];
                            var days = ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'];
                            var curWeekDay = days[r.getDay()];
                            var curDay = r.getDate();
                            var curMonth = months[r.getMonth()];
                            var curYear = r.getFullYear();
                            var date = curWeekDay + ", " + curDay + " " + curMonth + " " + curYear;
                            mont = '';

                            if (r.getMonth() + 1 <= 9) {
                                mont = "0" + (r.getMonth() + 1);
                            } else {
                                mont = (r.getMonth() + 1);
                            }

                            if (r.getDay() <= 9) {
                                curDay = "0" + (r.getDay());
                            } else {
                                curDay = (r.getDay());
                            }

                            $("#validarFehca").val(curYear + "-" + mont + "-" + curDay);
                            $("#vacacionesInput").val(date);
                            $("#vacionesDisponibles").html("  " + date + "  ");

                            
                            var jsonDatos1 = {
                                "ano": dato,
                                "id_empleado": $("#id_empleado").val(),
                                "periodo_inicio": $("#periodo_inicio").val(),
                                "accion": "VERIFICAR_REGISTRO"
                            };
                            
                            $.ajax({
                                data: jsonDatos1,
                                type: "POST",
                                url: "app/views/default/modules/modulos/vacaciones/m.vacaciones.procesa.php",
                                success: function(datosV) {
                                    var verificar = datosV;
                                    var result = verificar.split("@")[0];
                                    var id_vacaciones = verificar.split("@")[1];

                                    if (verificar == 2 && $("#id").val() == false) {
                                        
                                    } else {
                                        //$("#myModal_vacaciones").modal("hide");
                                        if ($("#id").val() == "") {
                                            Alert("", 'El empleado cuenta con un registro', "warning", 1900, false);
                                            $("#btnGuardar").hide();
                                        }
                                    }
                                    var jsonDatos = {
                                            "anos": dato,
                                            "id_empleado": $("#id_empleado").val(),
                                            "accion": "DIAS"
                                        };
                                        $.ajax({
                                            data: jsonDatos,
                                            type: "POST",
                                            url: "app/views/default/modules/modulos/vacaciones/m.vacaciones.procesa.php",
                                            success: function(dat) {
                                                var str = dat;
                                                var dias_correspondientes = str.split("@")[0];
                                                var dias_restantes = str.split("@")[1];
                                                var dias_pagados = <?= $oVacaciones->dias_pagados; ?> + 0;
                                                $("#dias_correspondientes").val(dias_correspondientes);

                                                if (dias_restantes <= 0) {
                                                    $("#dias_restantes").val(dias_correspondientes);
                                                    $("#dias_restantes_1").val(dias_correspondientes);
                                                    dias_restantes = dias_correspondientes;
                                                } else {
                                                    $("#dias_restantes").val(dias_restantes);
                                                    $("#dias_restantes_1").val(dias_restantes);
                                                }

                                                diasOption = (dias_restantes);
                                                if (diasOption > 1) {
                                                    var options = '';
                                                    options += "<option value=''>--SELECCIONE--</option>";
                                                    for (var i = 1; i <= diasOption; i++) {
                                                        options += '<option value="' + i + '">' + i + '</option>';
                                                    }
                                                    $("#dias_disfrutar_view").html(options);
                                                }

                                                if (dias_correspondientes > 1) {
                                                    var options2 = '';
                                                    options2 += "<option value=''>--SELECCIONE--</option>";
                                                    for (var i = 1; i <= dias_correspondientes; i++) {
                                                        if (dias_pagados == i) {
                                                            options2 += '<option value="' + i + '" selected>' + i + '</option>';
                                                        }
                                                        options2 += '<option value="' + i + '">' + i + '</option>';
                                                    }
                                                    $("#dias_pagados").html(options2);
                                                }
                                            }
                                        });
                                }
                            });
                        }
                    });
                } else {
                    Alert("", 'El empleado no tiene fecha de ingreso', "warning", 900, false);
                }
            }
        });
    }

    function Restante() {
        restante = $("#dias_restantes_1").val() - $("#dias_disfrutar").val();
        if (is_negative_number(restante)) {
            $("#btnGuardar").hide();
            Alert("", 'Nose pueden seleccionar mas dias de los disponibles', "warning", 1000, false);
        } else {
            $("#btnGuardar").show();
            $("#dias_restantes").val(restante);
        }
    }

    function contador_dias() {
        var timeStart = new Date(document.getElementById("inicio_vacaci").value);
        var timeEnd = new Date(document.getElementById("fin_vacaci").value);
        var actualDate = new Date();
        if (timeEnd > timeStart) {
            var diff = timeEnd.getTime() - timeStart.getTime();
            dias_totales = Math.round(diff / (1000 * 60 * 60 * 24) + 1);

            var jsonDatos = {
                "fecha_inicial": $("#inicio_vacaci").val(),
                "fecha_final": $("#fin_vacaci").val(),
                "dias": dias_totales,
                "accion": "DiasTotales"
            };

            $.ajax({
                data: jsonDatos,
                type: "POST",
                url: "app/views/default/modules/modulos/vacaciones/m.vacaciones.procesa.php",
                beforeSend: function() {},
                success: function(datos) {
                    $("#dias_disfrutar").val(datos).trigger('change');
                    $("#dias_disfrutar_view").val(datos).trigger('change');

                }
            });
        } else if (timeEnd != null && timeEnd < timeStart) {
            Alert("", 'La fecha final debe ser mayor a la fecha inicial', "warning", 1200, false);
        }
    }
    function contador_dias1() {
        var timeStart = new Date(document.getElementById("periodo_fin").value);
        var timeEnd = new Date(document.getElementById("fecha_pago").value);
        var actualDate = new Date();
        if (timeEnd > timeStart) {
           
        } else if (timeEnd != null && timeEnd < timeStart) {
            Alert("", 'La fecha de pago debe ser mayor a la fecha del cumplimiento del año ', "warning", 1200, false);
        }
    }
</script>

<form id="frmFormulario_" name="frmFormulario_" action="app/views/default/modules/modulos/vacaciones/m.vacaciones.procesa.php" enctype="multipart/form-data" method="post" target="_self" class="form-horizontal">
    <div>
        <div class="row">
            <div class="col">
                <div class="form-group">
                    <strong class="">Empleado:</strong>
                    <div class="form-group">
                        <?php if ($oVacaciones->id_empleado != "") {
                            echo "<input type='hidden' name='id_empleado' value='$oVacaciones->id_empleado' >";
                        } ?>
                        <select id="id_empleado" description="Seleccione el empleado" <?php if ($oVacaciones->id_empleado != "") {
                                                                                            echo "disabled";
                                                                                        } ?> class="form-control obligado" onchange="" name="id_empleado">
                            <?php
                            if (count($lstEmpleados) > 0) {
                                echo "<option value='0' >-- SELECCIONE --</option>\n";
                                foreach ($lstEmpleados as $idx => $campo) {
                                    if ($campo->id == $oVacaciones->id_empleado) {
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
                    <strong class="">Fecha de ingreso:</strong>
                    <div class="form-group">
                        <input type="date" description="Seleccione la fecha" aria-describedby="" id="fecha_ingreso" required name="fecha_ingreso" value="<?= $oVacaciones->fecha_ingreso; ?>" autocomplete="off" class="form-control obligado" readonly="readonly" />
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="form-group">
                    <strong class="">Años:</strong>
                    <div class="form-group">
                        <input type="int" description="Seleccione la fecha" aria-describedby="" id="ano" readonly="true" name="ano" value="<?= $oVacaciones->ano ?>" class="form-control obligado" />
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col">
                <div class="form-group">
                    <strong class="">Dias totales de vacaciones:</strong>
                    <div class="form-group">
                        <input type="int" description="" aria-describedby="" id="dias_correspondientes" required name="dias_correspondientes" value="<?= $oVacaciones->dias_correspondientes ?>" class="form-control obligado" readonly="readonly" />
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="form-group">
                    <strong class="">Dias a disfrutar:</strong>
                    <?php if ($oVacaciones->inicio_vacaci != "") {
                        echo "<br>" . $oVacaciones->dias_disfrutar;
                    } else { ?>
                        <div class="form-group">
                            <select id="dias_disfrutar_view" disabled description="Seleccione los dias a disfrutar" class="form-control obligado" onchange="" name="dias_disfrutar">

                            </select>
                            <input type="hidden" name="dias_disfrutar" id="dias_disfrutar">
                        </div>
                    <?php } ?>
                </div>
            </div>
            <div class="col">
                <div class="form-group">
                    <strong class="">Dias restantes:</strong>
                    <div class="form-group">
                        <input type="int" description="" aria-describedby="" id="dias_restantes" value="<?= $oVacaciones->dias_restantes ?>" readonly="true" name="dias_restantes" class="form-control " />
                        <input type="hidden" id="dias_restantes_1" class="" />
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col">
                <?php if ($oVacaciones->inicio_vacaci != "") {
                    echo "Dias Pagados";
                } else { ?>
                    <input type="checkbox" name="pagar" id="pagar" value="recoleccion"><strong> Pagar dias</strong><br>
                <?php } ?>
            </div>
        </div>
        <div class="row">
            <div class="col">
                <div class="form-group">
                    <strong class="">Dias a pagar:</strong>
                    <div class="form-group">
                        <input type="text" description="Ingrese el nombre" class="form-control" readonly id="pagar_dias" value="<?= $oVacaciones->pagar_dias ?>" />
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="form-group">
                    <strong class="">Salario diario:</strong>
                    <div class="form-group">
                        <input type="text" description="" id="salario_diario" value="" class="form-control" readonly />
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col">
                <div class="form-group">
                    <strong class="">Total a págar:</strong>
                    <div class="form-group">
                        <input type="text" id="pagar_total" value="<?= $oVacaciones->pagar_total ?>" class="form-control" readonly />
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="form-group">
                    <strong class="">Asignar un concepto:</strong>
                    <div class="form-group">
                        <input type="text" id="pagar_concepto" value="<?= $oVacaciones->pagar_concepto ?>" class="form-control " readonly />
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="form-group">
                    <strong class="">Fecha a pagar dias:</strong>
                    <div class="form-group">
                        <input type="text" id="pagar_concepto" value="<?= $oVacaciones->pagar_concepto ?>" class="form-control " readonly />
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-2">
                <div class="form">
                    <strong>Periodo a Disfrutar :</strong>
                </div>
            </div>
            <div class="col-sm-5">
                <div class="form">
                    <strong>del año :</strong>
                    <label id="periodo_inicio_label"></label>
                    <input type="date" id="periodo_inicio" readonly="true" value="<?= $oVacaciones->periodo_inicio ?>" name="periodo_inicio" class="form" />
                </div>
            </div>
            <div class="col-sm-5">
                <div class="form">
                    <strong>al año :</strong>
                    <input type="date" id="periodo_fin" readonly="true" name="periodo_fin" value="<?= $oVacaciones->periodo_fin; ?>" class="form" />
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-5">
                <div class="form">
                    <strong>Vacaciones disponibles hasta el dia:</strong>
                </div>
            </div>
            <div class="col-sm-6 float-left">
                <div class="form-group ">
                    <label id="vacionesDisponibles" class="btn-danger" data-toggle="tooltip" title="" data-original-title="<?= $ley ?>"></label>
                    <input type="hidden" id="vacacionesInput" name="vacacionesInput" value="<?= $oVacaciones->vacacionesInput ?>">
                    <input type="hidden" id="validarFehca">
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-4">
                <div class="form">
                    <strong>Dia que inicia sus vacaciones:</strong>
                </div>
            </div>
            <div class="col-sm-6 float-left">
                <div class="form-group ">
                    <input type="date" id="inicio_vacaci" name="inicio_vacaci" <?php if ($oVacaciones->inicio_vacaci != "") {
                                                                                    echo "readonly='true'";
                                                                                } ?> value="<?= $oVacaciones->inicio_vacaci; ?>" class="form-control">
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-4">
                <div class="form">
                    <strong>Dia que terminan sus vacaciones:</strong>
                </div>
            </div>
            <div class="col-sm-6 float-left">
                <div class="form-group ">
                    <input type="date" id="fin_vacaci" name="fin_vacaci" <?php if (!empty($oVacaciones->fin_vacaci)) {
                                                                                echo "readonly='true'";
                                                                            } ?> value="<?= $oVacaciones->fin_vacaci; ?>" onchange="contador_dias()" class="form-control">
                </div>
            </div>
        </div>
        
        <div class="row">
            <div class="col">
                <div class="form-group">
                    <strong class="">Pago de primas vacaionales:</strong>
                    <?php if ($oVacaciones->pago_prima != "") {
                        echo "$" . $oVacaciones->pago_prima;
                        echo "<input type='hidden' name='pago_prima' value='$oVacaciones->pago_prima'>";
                    } else { ?>
                        <div class="input-group mb-2">
                            <div class="input-group-prepend">
                                <div class="input-group-text">$</div>
                            </div>
                            <input type="number" min="1" step="any" description="Ingrese el pago de prima vacacional" id="pago_prima" value="<?= $oVacaciones->pago_prima ?>" readonly="readonly" required name="pago_prima" class="form-control obligado" />
                        </div>
                    <?php } ?>
                </div>
            </div>
            <div class="col">
                <div class="form-group">
                    <strong class="">Dias de prima que se pagaron:</strong>
                    <?php if ($oVacaciones->dias_pagados != "") {
                        echo $oVacaciones->dias_pagados;
                        echo "<input type='hidden' name='dias_pagados' value='$oVacaciones->dias_pagados'>";
                    } else { ?>
                        <div class="form-group">
                            <select id="dias_pagados" description="Seleccione los dias a disfrutar" class="form-control obligado" value="<?= $oVacaciones->dias_pagados; ?>" name="dias_pagados">
                            </select>
                        </div>
                    <?php } ?>
                </div>
            </div>
            <div class="col">
                <div class="form-group">
                    <strong class="">Fecha de pago de prima:</strong>
                    <?php if ($oVacaciones->fecha_pago != "") {
                        echo $oVacaciones->fecha_pago;
                        echo "<input type='hidden' name='fecha_pago' value='$oVacaciones->fecha_pago'>";
                    } else { ?>
                        <div class="form-group">
                            <input type="date" description="Seleccione la fecha" aria-describedby="" id="fecha_pago" name="fecha_pago" value="<?= $oVacaciones->fecha_pago; ?>" onchange="contador_dias1()" class="form-control obligado" />
                        </div>
                    <?php } ?>
                </div>
            </div>
        </div>
        
        <div class="form-group">
            <div class="form-group">
                <strong>Observaciones:</strong>
            </div>
            <?php if ($oVacaciones->inicio_vacaci != "") {
                echo "<br>" . $oVacaciones->observaciones;
            } else { ?>
                <div class="form-group" style="width: 100%;">
                    <textarea id="observaciones" name="observaciones" class="form-group" rows="3" style="width: 100%;"><?= $oVacaciones->observaciones ?></textarea>
                </div>
            <?php } ?>
        </div>
        <?php if ($oVacaciones->inicio_vacaci != "") { ?>
            <input type="button" id="btnImprimir" class="btn btn-outline-danger btn-block" name="btnImprimir" value="Imprimir Vacaciones">
        <?php } ?>
    </div>
    <input type="hidden" id="id" name="id" value="<?= $oVacaciones->id ?>" />
    <input type="hidden" id="user_id" name="user_id" value="<?= $sesion->id ?>">
    <input type="hidden" id="accion" name="accion" value="GUARDAR" />
    </div>
</form>