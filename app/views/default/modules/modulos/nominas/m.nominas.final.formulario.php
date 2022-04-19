<?php
/*
 * Copyright 2021 - Felipe angel cerda contreras 
 * felipeangelcerdacontreras@gmail.com
 */
session_start();

$_SITE_PATH = $_SERVER["DOCUMENT_ROOT"] . "/" . explode("/", $_SERVER["PHP_SELF"])[1] . "/";
require_once($_SITE_PATH . "/app/model/nominas.class.php");

$oNominas = new nominas();
$oNominas->id = addslashes(filter_input(INPUT_POST, "id"));
$nombre = addslashes(filter_input(INPUT_POST, "nombre"));
$lstnominas = $oNominas->Listado_prenomina();

?>
<script type="text/javascript">
    const formatterDolar = new Intl.NumberFormat('en-US', {
        style: 'currency',
        currency: 'USD'
    })
    $(document).ready(function() {
        $('#dataTable3').DataTable({
            "paging": false,
            dom: 'Bfrtip',
            buttons: [{
                extend: 'pdfHtml5',
                footer: true,
                title: 'Reporte Nomina Semana <?= $lstnominas[0]->fecha ?>',
                text: 'Exportar a pdf',
                orientation: 'landscape',
                exportOptions: {
                    columns: [0, 12, 13, 14, 15, 16, 17, 18, 19, 20]
                }
            }],
            "footerCallback": function(row, data, start, end, display) {
                var api = this.api(),
                    data;

                // Remove the formatting to get integer data for summation
                var intVal = function(i) {
                    return typeof i === 'string' ?
                        i.replace(/[\$,]/g, '') * 1 :
                        typeof i === 'number' ?
                        i : 0;
                };

                // Total over all pages
                asistencia = api
                    .column(1)
                    .data()
                    .reduce(function(a, b) {
                        return intVal(a) + intVal(b);
                    }, 0);
                
                puntualidad = api
                    .column(2)
                    .data()
                    .reduce(function(a, b) {
                        return intVal(a) + intVal(b);
                    }, 0);

                productividad = api
                    .column(3)
                    .data()
                    .reduce(function(a, b) {
                        return intVal(a) + intVal(b);
                    }, 0);

                bono12 = api
                    .column(4)
                    .data()
                    .reduce(function(a, b) {
                        return intVal(a) + intVal(b);
                    }, 0);

                complemento = api
                    .column(5)
                    .data()
                    .reduce(function(a, b) {
                        return intVal(a) + intVal(b);
                    }, 0);

                faltas = api
                    .column(7)
                    .data()
                    .reduce(function(a, b) {
                        return intVal(a) + intVal(b);
                    }, 0);
                extras = api
                    .column(9)
                    .data()
                    .reduce(function(a, b) {
                        return intVal(a) + intVal(b);
                    }, 0);
                vacaciones = api
                    .column(10)
                    .data()
                    .reduce(function(a, b) {
                        return intVal(a) + intVal(b);
                    }, 0);
                incapacidades = api
                    .column(11)
                    .data()
                    .reduce(function(a, b) {
                        return intVal(a) + intVal(b);
                    }, 0);
                total = api
                    .column(12)
                    .data()
                    .reduce(function(a, b) {
                        return intVal(a) + intVal(b);
                    }, 0);
                comedor = api
                    .column(13)
                    .data()
                    .reduce(function(a, b) {
                        return intVal(a) + intVal(b);
                    }, 0);
                ahorro = api
                    .column(14)
                    .data()
                    .reduce(function(a, b) {
                        return intVal(a) + intVal(b);
                    }, 0);
                prestamo = api
                    .column(15)
                    .data()
                    .reduce(function(a, b) {
                        return intVal(a) + intVal(b);
                    }, 0);
                fonacot = api
                    .column(16)
                    .data()
                    .reduce(function(a, b) {
                        return intVal(a) + intVal(b);
                    }, 0);
                infonavit = api
                    .column(17)
                    .data()
                    .reduce(function(a, b) {
                        return intVal(a) + intVal(b);
                    }, 0);
                otros = api
                    .column(18)
                    .data()
                    .reduce(function(a, b) {
                        return intVal(a) + intVal(b);
                    }, 0);
                retencionesT = api
                    .column(19)
                    .data()
                    .reduce(function(a, b) {
                        return intVal(a) + intVal(b);
                    }, 0);
                pagar = api
                    .column(20)
                    .data()
                    .reduce(function(a, b) {
                        return intVal(a) + intVal(b);
                    }, 0);

                // Update footer
                $(api.column(1).footer()).html(formatterDolar.format(asistencia));

                $(api.column(2).footer()).html(formatterDolar.format(puntualidad));

                $(api.column(3).footer()).html(formatterDolar.format(productividad));

                $(api.column(4).footer()).html(formatterDolar.format(bono12));

                $(api.column(5).footer()).html(formatterDolar.format(complemento));

                $(api.column(7).footer()).html(faltas);

                $(api.column(9).footer()).html(formatterDolar.format(extras));
                $(api.column(10).footer()).html(formatterDolar.format(vacaciones));
                $(api.column(11).footer()).html(formatterDolar.format(incapacidades));

                $(api.column(12).footer()).html(formatterDolar.format(total));

                $(api.column(13).footer()).html(formatterDolar.format(comedor));

                $(api.column(14).footer()).html(formatterDolar.format(ahorro));

                $(api.column(15).footer()).html(formatterDolar.format(prestamo));

                $(api.column(16).footer()).html(formatterDolar.format(fonacot));

                $(api.column(17).footer()).html(formatterDolar.format(infonavit));

                $(api.column(18).footer()).html(formatterDolar.format(otros));

                $(api.column(19).footer()).html(formatterDolar.format(retencionesT));

                $(api.column(20).footer()).html(formatterDolar.format(pagar));
                
            }
        });
        $(".buttons-html5 ").addClass("btn btn-outline-danger");
        var total = 0;
            $('#dataTable2').DataTable().rows().data().each(function(el, index) {
                //Asumiendo que es la columna 5 de cada fila la que quieres agregar a la sumatoria
                total += el[8];
            });
    });
</script>
<!-- DataTales Example -->
<div class="card shadow mb-4">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered" id="dataTable3" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th style="text-align: left;">Nombre Empleado</th>
                        <th style="text-align: left;">Premio de asistencia</th>
                        <th style="text-align: left;">Premio de puntualidad</th>
                        <th style="text-align: left;">Bono de productividad</th>
                        <th style="text-align: left;">Bono de 12hrs</th>
                        <th style="text-align: left;">Complemento de sueldo</th>
                        <th style="text-align: left;">Sueldo Diario</th>
                        <th style="text-align: left;">Faltas</th>
                        <th style="text-align: left;">Dias Laborados</th>
                        <th style="text-align: left;">Horas Extras</th>
                        <th style="text-align: left;">Vacaciones</th>
                        <th style="text-align: left;">Incapacidades</th>
                        <th style="text-align: left;">Total Percepciones</th>
                        <th style="text-align: left;">Comedor</th>
                        <th style="text-align: left;">Ahorro</th>
                        <th style="text-align: left;">Prestamos</th>
                        <th style="text-align: left;">Fonacot</th>
                        <th style="text-align: left;">Infonavit</th>
                        <th style="text-align: left;">Otros Cargos</th>
                        <th style="text-align: left;">Total Retenciones</th>
                        <th style="text-align: left;">Total A Pagar</th>
                        <th style="text-align: left;">Recibo</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if (count($lstnominas) > 0) {
                        foreach ($lstnominas as $idx => $prenomina) {

                            $oNominas_edit = new nominas();
                            $oNominas_edit->id_nomina = $prenomina->id_nomina;
                            $oNominas_edit->id_empleado = $prenomina->id_empleado;
                            $oNominas_edit->Nomina_edit();
                            if ($oNominas_edit->nombre != '' && $oNominas_edit->estatus_final_edit == "2") {

                                $id_nomina = $oNominas_edit->id_nomina;
                                $id_empleado = $oNominas_edit->id_empleado;
                                $nombre = $oNominas_edit->nombre;
                                $asistencia = $oNominas_edit->asistencia;
                                $puntualidad = $oNominas_edit->puntualidad;
                                $productividad = $oNominas_edit->productividad;
                                $doce = $oNominas_edit->doce;
                                $complemento = $oNominas_edit->complemento;
                                $diario = $oNominas_edit->diario;
                                $faltas = $oNominas_edit->faltas;
                                $asistencias = $oNominas_edit->asistencias;
                                $extras = $oNominas_edit->extras;
                                $vacaciones = $oNominas_edit->vacaciones;
                                $total = $oNominas_edit->total;
                                $comedor = $oNominas_edit->comedor;
                                $ahorro = $oNominas_edit->ahorro;
                                $prestamos = $oNominas_edit->prestamos;
                                $fonacot = $oNominas_edit->fonacot;
                                $infonavit = $oNominas_edit->infonavit;
                                $otros = $oNominas_edit->otros;
                                $total_r = $oNominas_edit->total_r;
                                $total_p = $oNominas_edit->total_p;
                            } else {
                                $id_nomina = $prenomina->id_nomina;
                                $id_empleado = $prenomina->id_empleado;
                                $nombre = $prenomina->nombre;
                                $asistencia = $prenomina->asistencia;
                                $puntualidad = $prenomina->puntualidad;
                                $productividad = $prenomina->productividad;
                                $doce = $prenomina->doce;
                                $complemento = $prenomina->complemento;
                                $diario = $prenomina->diario;
                                $faltas = $prenomina->faltas;
                                $asistencias = $prenomina->asistencias;
                                $extras = $prenomina->extras;
                                $vacaciones = $prenomina->vacaciones;
                                $total = $prenomina->total;
                                $comedor = $prenomina->comedor;
                                $ahorro = $prenomina->ahorro;
                                $prestamos = $prenomina->prestamos;
                                $fonacot = $prenomina->fonacot;
                                $infonavit = $prenomina->infonavit;
                                $otros = $prenomina->otros;
                                $total_r = $prenomina->total_r;
                                $total_p = $prenomina->total_p;
                            }
                    ?>
                            <tr>
                                <td style="text-align: center;"><?= $nombre ?></td>
                                <td style="text-align: center;"><?= "$" . $asistencia ?></td>
                                <td style="text-align: center;"><?= "$" . $puntualidad; ?></td>
                                <td style="text-align: center;"><?= "$" . $productividad ?></td>
                                <td style="text-align: center;"><?= "$" . $doce ?></td>
                                <td style="text-align: center;"><?= "$" . $complemento ?></td>
                                <td style="text-align: center;"><?= "$" . $diario ?></td>
                                <td style="text-align: center;"><?= $faltas ?></td>
                                <td style="text-align: center;"><?= $asistencias ?></td>
                                <td style="text-align: center;"><?= "$" . $extras ?></td>
                                <td style="text-align: center;"><?= "$" . $vacaciones ?></td>
                                <td style="text-align: center;"><?= "$" . bcdiv(($prenomina->monto_incapacida * $prenomina->dias_incapacida), '1', 2) ?></td>
                                <td style="text-align: center;"><?= "$" . $total ?></td>
                                <td style="text-align: center;"><?= "-$" . $comedor ?></td>
                                <td style="text-align: center;"><?= "-$" . $ahorro ?></td>
                                <td style="text-align: center;"><?= "-$" . $prestamos ?></td>
                                <td style="text-align: center;"><?= "-$" . $fonacot ?></td>
                                <td style="text-align: center;"><?= "-$" . $infonavit ?></td>
                                <td style="text-align: center;"><?= "-$" . $otros ?></td>
                                <td style="text-align: center;"><?= "-$" . $total_r ?></td>
                                <td style="text-align: center;"><?= "$" . $total_p ?></td>
                                <td style="text-align: center;">
                                    <?= $nombre . "<br>" ?>
                                    <?php if ($prenomina->estatus == 1) { ?>
                                        <a class="btn btn-outline-sm btn-warning" href="javascript:Reporte('<?= $id_nomina ?>','<?= $id_empleado ?>')">Ver</a>
                                    <?php } ?>
                                </td>
                            </tr>
                    <?php
                        }
                    }
                    ?>
                </tbody>
                <tfoot>
                    <tr>
                        <th style="text-align:right">Totales:</th>
                        <th style="text-align:right"></th>
                        <th style="text-align:right"></th>
                        <th style="text-align:right"></th>
                        <th style="text-align:right"></th>
                        <th style="text-align:right"></th>
                        <th style="text-align:right"></th>
                        <th style="text-align:right"></th>
                        <th style="text-align:right"></th>
                        <th style="text-align:right"></th>
                        <th style="text-align:right"></th>
                        <th style="text-align:right"></th>
                        <th style="text-align:right"></th>
                        <th style="text-align:right"></th>
                        <th style="text-align:right"></th>
                        <th style="text-align:right"></th>
                        <th style="text-align:right"></th>
                        <th style="text-align:right"></th>
                        <th style="text-align:right"></th>
                        <th style="text-align:right"></th>
                        <th style="text-align:right"></th>
                        <th style="text-align:right"></th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>