<?php
/*
 * Copyright 2021 - Felipe angel cerda contreras 
 * felipeangelcerdacontreras@gmail.com
 */
session_start();

$_SITE_PATH = $_SERVER["DOCUMENT_ROOT"] . "/" . explode("/", $_SERVER["PHP_SELF"])[1] . "/";
require_once($_SITE_PATH . "/app/model/vacaciones.class.php");
require_once($_SITE_PATH . "vendor/autoload.php");

use Carbon\Carbon;

$oVacaciones = new vacaciones(true, $_POST);
$oVacaciones->fecha_genera = addslashes(filter_input(INPUT_POST, "fecha_genera"));
$lstvacaciones = $oVacaciones->Listado_vacaciones();

$lstvacacionesR = $oVacaciones->Listado_vacaciones_registradas();

?>
<script type="text/javascript">
    $(document).ready(function(e) {
        $("#frmFormulario_v").ajaxForm({
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
                $("#myModal_vnomina").modal("hide");
            }
        });
    });

    function checkIs(id) {
        if ($('#fecha_n-' + id).prop('checked')) {
            $('#fecha_v-' + id).prop('checked', false);
            $('#fecha_v-' + id).removeAttr("name");
            $('#fecha_v-' + id).removeAttr("class");
            $('#fecha_n-' + id).attr("name", "fecha_pago-" + id);
            $('#fecha_n-' + id).attr("class", "obligado");
        }
    }

    function checkIs2(id) {
        if ($('#fecha_v-' + id).prop('checked')) {
            $('#fecha_n-' + id).prop('checked', false);
            $('#fecha_n-' + id).removeAttr("name");
            $('#fecha_n-' + id).removeAttr("class");
            $('#fecha_v-' + id).attr("name", "fecha_pago-" + id);
            $('#fecha_v-' + id).attr("class", "obligado");
        }
    }

    function organizar() {
        var indice = x;
        var trNot = 0;
        var inicio = 0;
        var final = $("#contador").val();

        $("#frmFormulario_v").find('tr').each(function() {
            var elemnto = this;
            if ($(elemnto).hasClass("notremove")) {
                trNot++;
            }
        });
        inicio = trNot;

        $("#frmFormulario_v").find('tr').each(function() {
            var elemnto = this;
            if ($(elemnto).hasClass("remove")) {
                if (trNot >= inicio && trNot < final) {
                    trNot++;

                    if ($(elemnto).find('td').eq(0).find('input[type="hidden"]').eq(0).attr("id") != undefined) {
                        idV = $(elemnto).find('td').eq(0).find('input[type="hidden"]').eq(0).attr("id");
                        id1 = idV.split("-")[0];
                        $("#" + idV).attr("name", id1 + "-" + trNot);
                        $("#" + idV).attr("id", id1 + "-" + trNot);
                    }

                    if ($(elemnto).find('td').eq(0).find('input[type="hidden"]').eq(1).attr("id") != undefined) {
                        idV = $(elemnto).find('td').eq(0).find('input[type="hidden"]').eq(1).attr("id");
                        id1 = idV.split("-")[0];
                        $("#" + idV).attr("name", id1 + "-" + trNot);
                        $("#" + idV).attr("id", id1 + "-" + trNot);
                    }

                    if ($(elemnto).find('td').eq(1).find('input[type="hidden"]').attr("id") != undefined) {
                        idV = $(elemnto).find('td').eq(1).find('input[type="hidden"]').attr("id");
                        id1 = idV.split("-")[0];
                        $("#" + idV).attr("name", id1 + "-" + trNot);
                        $("#" + idV).attr("id", id1 + "-" + trNot);
                    }

                    if ($(elemnto).find('td').eq(2).find('input[type="hidden"]').attr("id") != undefined) {
                        idV = $(elemnto).find('td').eq(2).find('input[type="hidden"]').attr("id");
                        id1 = idV.split("-")[0];
                        $("#" + idV).attr("name", id1 + "-" + trNot);
                        $("#" + idV).attr("id", id1 + "-" + trNot);
                    }

                    if ($(elemnto).find('td').eq(3).find('input[type="hidden"]').attr("id") != undefined) {
                        idV = $(elemnto).find('td').eq(3).find('input[type="hidden"]').attr("id");
                        id1 = idV.split("-")[0];
                        $("#" + idV).attr("name", id1 + "-" + trNot);
                        $("#" + idV).attr("id", id1 + "-" + trNot);
                    }

                    if ($(elemnto).find('td').eq(4).find('input[type="checkbox"]').eq(0).attr("id") != undefined) {
                        idV = $(elemnto).find('td').eq(4).find('input[type="checkbox"]').eq(0).attr("id");
                        id1 = idV.split("-")[0];
                        $("#" + idV).attr("onclick", "checkIs("+trNot+")");
                        if ($(elemnto).find('td').eq(4).find('input[type="checkbox"]').eq(0).attr('name')) {
                            $("#" + idV).attr("name", "fecha_pago-" + trNot);
                        }
                        $("#" + idV).attr("id", id1 + "-" + trNot);
                    }

                    if ($(elemnto).find('td').eq(4).find('input[type="checkbox"]').eq(1).attr("id") != undefined) {
                        idV = $(elemnto).find('td').eq(4).find('input[type="checkbox"]').eq(1).attr("id");
                        id1 = idV.split("-")[0];
                        $("#" + idV).attr("onclick", "checkIs2("+trNot+")");
                        if ($(elemnto).find('td').eq(4).find('input[type="checkbox"]').eq(1).attr('name')) {
                            $("#" + idV).attr("name", "fecha_pago-" + trNot);
                        }
                        $("#" + idV).attr("id", id1 + "-" + trNot);
                    }

                    if ($(elemnto).find('td').eq(5).find('input[type="hidden"]').eq(0).attr("id") != undefined) {
                        idV = $(elemnto).find('td').eq(5).find('input[type="hidden"]').eq(0).attr("id");
                        id1 = idV.split("-")[0];
                        $("#" + idV).attr("name", id1 + "-" + trNot);
                        $("#" + idV).attr("id", id1 + "-" + trNot);
                    }
                    if ($(elemnto).find('td').eq(5).find('input[type="hidden"]').eq(1).attr("id") != undefined) {
                        idV = $(elemnto).find('td').eq(5).find('input[type="hidden"]').eq(1).attr("id");
                        id1 = idV.split("-")[0];
                        $("#" + idV).attr("name", id1 + "-" + trNot);
                        $("#" + idV).attr("id", id1 + "-" + trNot);
                    }
                    if ($(elemnto).find('td').eq(5).find('input[type="hidden"]').eq(2).attr("id") != undefined) {
                        idV = $(elemnto).find('td').eq(5).find('input[type="hidden"]').eq(2).attr("id");
                        id1 = idV.split("-")[0];
                        $("#" + idV).attr("name", id1 + "-" + trNot);
                        $("#" + idV).attr("id", id1 + "-" + trNot);
                    }
                    if ($(elemnto).find('td').eq(5).find('input[type="hidden"]').eq(3).attr("id") != undefined) {
                        idV = $(elemnto).find('td').eq(5).find('input[type="hidden"]').eq(3).attr("id");
                        id1 = idV.split("-")[0];
                        $("#" + idV).attr("name", id1 + "-" + trNot);
                        $("#" + idV).attr("id", id1 + "-" + trNot);
                    }
                }
            }
        });
    }

    function RemoveTr(id) {
        swal({
            title: "¿DESEA REMOVER LA PRIMA ?",
            text: "AL REMOVER LA PRIMA SE PODRA GENERAR LA PROXIMA NOMINA CON LA NUEVA FECHA HASTA QUE SE PAGUE",
            icon: "warning",
            buttons: [
                'No',
                'Si'
            ],
            dangerMode: true,
        }).then(function(isConfirm) {
            if (isConfirm) {
                swal({
                    title: 'Removido!',
                    text: 'La prima seleccionada a sido removida',
                    icon: 'success'
                }).then(function() {
                    x = id.split("-")[1];
                    $('#' + id).remove();
                    num = $("#contador").val();
                    $("#contador").val(num - 1);
                    organizar(x);
                });
            } else {
                swal("Cancelado", "Prima no removida", "error");
            }
        });
    }
</script>
<!-- DataTales Example -->
<div class="table-responsive">
    <form id="frmFormulario_v" name="frmFormulario_v" action="app/views/default/modules/modulos/vacaciones/m.vacaciones.procesa.php" enctype="multipart/form-data" method="post" target="_self" class="form-horizontal">
        <table class="table table-bordered" id="dataTable_v" width="100%" cellspacing="0">
            <thead>
                <tr>
                    <th>Fecha Ingreso</th>
                    <th>Empleado</th>
                    <th>Años</th>
                    <th>Dias</th>
                    <th>Fecha de pago prima</th>
                    <th>Pago de primas</th>
                    <th>Acciones </th>
                </tr>
            </thead>
            <tbody>
                <td colspan="7" style="text-align: center;color: white;background-color: #e70808;">PRIMAS VACACIONALES CON PAGO GENERADO</td>
                </tr>       
                <?php
                $contador = 1;
                if (count($lstvacacionesR) > 0 ) {
                    foreach ($lstvacacionesR as $idx => $campo) {
                        $name = $campo->nombres . " " . $campo->ape_paterno . " " . $campo->ape_materno;
                ?>
                        <tr <?php echo "id='Tr-{$contador}'"; ?> class="notremove">
                            <td style="text-align: center;">
                                <?php
                                print $campo->fecha_ingreso;
                                print "<input type='hidden' id='id_vac-{$contador}' name='id_vac-$contador' value='{$campo->id}'>";
                                print "<input type='hidden' id='id_vacaciones-{$contador}' name='id_vacaciones-$contador' value='{$campo->id_vac}'>";
                                ?>

                            </td>
                            <td style="text-align: center;">
                                <?php
                                print "<input type='hidden' id='id_empleado-{$contador}' name='id_empleado-$contador' value='{$campo->id_empleado}'>";
                                print $name ?></td>
                            <td style="text-align: center;">
                                <?php
                                print "<input type='hidden' id='ano-{$contador}' name='ano-$contador' value='{$campo->ano}'>";
                                print $campo->ano;
                                ?>
                            </td>
                            <td style="text-align: center;"><?php
                                                            print "<input type='hidden' id='dias-{$contador}' name='dias-$contador' value='{$campo->dias}'>";
                                                            print $campo->dias; ?>
                            </td>
                            <td style="text-align: center;"><?php
                                                            $campo->fecha_pago;
                                                            $nuevafecha = strtotime('+7 day', strtotime($campo->fecha_pago));
                                                            $nuevafecha = date('Y-m-d', $nuevafecha);
                                                            print $campo->fecha_pago .
                                                                "<input type='checkbox' id='fecha_n-{$contador}' name='fecha_pago-{$contador}'  onclick='checkIs($contador)' class='obligado' 
                                                            description='Seleccione una fecha de pago para " . $name . "' value='{$campo->fecha_pago}' > " .
                                                                $nuevafecha . " <input type='checkbox' id='fecha_v-{$contador}' name='fecha_pago-{$contador}' onclick='checkIs2($contador)'
                                                                description='Seleccione una fecha de pago para " . $name . "' value='{$nuevafecha}' >"; ?>
                            </td>
                            <td style="text-align: center;"><?php
                                                            $pago_prima = ($campo->dias * $campo->salario_diario * 0.25);
                                                            print "<input type='hidden' id='pago_prima-{$contador}' name='pago_prima-{$contador}' value='{$pago_prima}'>";
                                                            print $pago_prima;
                                                            ?>
                            </td>
                            <td>
                            </td>
                            <?php
                            $periodo_inicio = (date("Y")) . "-" . date("m-d", strtotime($campo->fecha_ingreso . ""));
                            print "<input type='hidden' id='periodo_inicio-{$contador}' name='periodo_inicio-{$contador}' value='{$periodo_inicio}' >";
                            $periodo_fin = (date("Y") + 1) . "-" . date("m-d", strtotime($campo->fecha_ingreso . "+ 1 year"));
                            print "<input type='hidden' id='periodo_fin-{$contador}' name='periodo_fin-{$contador}' value='{$periodo_fin}'>";
                            $fecha_final = Carbon::parse($periodo_inicio)->addMonth(6)->format('Y-m-d');
                            print "<input type='hidden' id='fecha_final-{$contador}' name='fecha_final-{$contador}' value='{$fecha_final}'>";
                            ?>
                        </tr>
                <?php
                        $contador++;
                    }
                }
                ?>
                <td colspan="7" style="text-align: center;color: white;background-color: #e70808;">PRIMAS VACACIONALES PARA GENERAR PAGO</td>
                </tr>
                <?php
                        if (count($lstvacaciones) > 0) {
                            foreach ($lstvacaciones as $idx => $campo) {
                                $oVacaciones_prima = new vacaciones();
                                $oVacaciones_prima->id_empleado = $campo->id;
                                $oVacaciones_prima->ano = $campo->ano;
                                $periodo_inicio = (date("Y")) . "-" . date("m-d", strtotime($campo->fecha_ingreso . ""));
                                $oVacaciones_prima->periodo_inicio = $periodo_inicio;
                                $var = $oVacaciones_prima->VerificarPrima();
                                $id_empleado = "";
                                if (!empty($var[0]->id_empleado)){
                                    $id_empleado = $var[0]->id_empleado;
                                } 

                                if ($id_empleado == $campo->id && $var[0]->ano == $campo->ano && $var[0]->periodo_inicio == $periodo_inicio ) {

                                } else {
                                
                                $name = $campo->nombres . " " . $campo->ape_paterno . " " . $campo->ape_materno;
                ?>
                                <tr <?php echo "id='Tr-{$contador}'"; ?> class="remove">
                                    <td style="text-align: center;">
                                        <?php print $campo->fecha_ingreso;
                                        print "<input type='hidden' id='id_vac-{$contador}' name='id_vac-$contador' value=''>";
                                        print "<input type='hidden' id='id_vacaciones-{$contador}' name='id_vacaciones-$contador' value=''>";
                                        ?>
                                    </td>
                                    <td style="text-align: center;">
                                        <?php
                                        print "<input type='hidden' id='id_empleado-{$contador}' name='id_empleado-$contador' value='{$campo->id}'>";
                                        print "<label id='nombre{$contador}'>$name</label>"  ?></td>
                                    <td style="text-align: center;">
                                        <?php
                                        print "<input type='hidden' id='ano-{$contador}' name='ano-$contador' value='{$campo->ano}'>";
                                        print $campo->ano;
                                        ?>
                                    </td>
                                    <td style="text-align: center;">
                                        <?php
                                        print "<input type='hidden' id='dias-{$contador}' name='dias-$contador' value='{$campo->dias}'>";
                                        print $campo->dias; ?>
                                    </td>
                                    <td style="text-align: center;">
                                        <?php
                                        $campo->fecha_nomina;
                                        $nuevafecha = strtotime('+7 day', strtotime($campo->fecha_nomina));
                                        $nuevafecha = date('Y-m-d', $nuevafecha);
                                        print $campo->fecha_nomina .
                                            "<input type='checkbox' id='fecha_n-{$contador}' name='fecha_pago-{$contador}'  onclick='checkIs($contador)' class='obligado' 
                                                            description='Seleccione una fecha de pago para " . $name . "' value='{$campo->fecha_nomina}' > " .
                                            $nuevafecha . " <input type='checkbox' id='fecha_v-{$contador}' name='fecha_pago-{$contador}' onclick='checkIs2($contador)'
                                                                description='Seleccione una fecha de pago para " . $name . "' value='{$nuevafecha}' >"; ?>
                                    </td>
                                    <td style="text-align: center;">
                                        <?php
                                        $pago_prima = ($campo->dias * $campo->salario_diario * 0.25);
                                        print "<input type='hidden' id='pago_prima-{$contador}' name='pago_prima-$contador' value='{$pago_prima}'>";
                                        print $pago_prima;
                                        print "<input type='hidden' id='periodo_inicio-{$contador}' name='periodo_inicio-{$contador}' value='{$periodo_inicio}' >";
                                        $periodo_fin = (date("Y") + 1) . "-" . date("m-d", strtotime($campo->fecha_ingreso . "+ 1 year"));
                                        print "<input type='hidden' id='periodo_fin-{$contador}' name='periodo_fin-{$contador}' value='{$periodo_fin}'>";
                                        $fecha_final = Carbon::parse($periodo_inicio)->addMonth(6)->format('Y-m-d');
                                        print "<input type='hidden' id='fecha_final-{$contador}' name='fecha_final-{$contador}' value='{$fecha_final}'>";
                                        ?>
                                    </td>
                                    <td>
                                        <input type="button" id="" class="btn btn-outline-danger" <?php echo "onclick=RemoveTr('Tr-{$contador}')"; ?> name="" value="remover">
                                    </td>
                                </tr>
                <?php
                                $contador++;
                                }
                            }
                        }
                ?>
                <input type="hidden" id="contador" name="contador" value="<?= ($contador) ?>" />
                <input type="hidden" id="accion" name="accion" value="GUARDAR_VACACIONES" />
    </form>
    </table>
    </tbody>
</div>