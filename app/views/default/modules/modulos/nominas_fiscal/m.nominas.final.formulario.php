<?php
/*
 * Copyright 2021 - Felipe angel cerda contreras 
 * felipeangelcerdacontreras@gmail.com
 */
session_start();

$_SITE_PATH = $_SERVER["DOCUMENT_ROOT"] . "/" . explode("/", $_SERVER["PHP_SELF"])[1] . "/";
require_once($_SITE_PATH . "/app/model/nominas_fiscal.class.php");

$oNominas = new nominas_fiscal();
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
                    columns: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9]
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

                faltas = api
                    .column(2)
                    .data()
                    .reduce(function(a, b) {
                        return intVal(a) + intVal(b);
                    }, 0);
                total = api
                    .column(4)
                    .data()
                    .reduce(function(a, b) {
                        return intVal(a) + intVal(b);
                    }, 0);
                pagar = api
                    .column(5)
                    .data()
                    .reduce(function(a, b) {
                        return intVal(a) + intVal(b);
                    }, 0);

                // Update footer
               
                $(api.column(2).footer()).html(faltas);

                $(api.column(4).footer()).html(formatterDolar.format(total));

                $(api.column(5).footer()).html(formatterDolar.format(pagar));
                
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
                        <th style="text-align: left;">Sueldo Diario</th>
                        <th style="text-align: left;">Faltas</th>
                        <th style="text-align: left;">Dias Laborados</th>
                        <th style="text-align: left;">Total Percepciones</th>
                        <th style="text-align: left;">Total A Pagar</th>
                        <th style="text-align: left;">Recibo</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if (count($lstnominas) > 0) {
                        foreach ($lstnominas as $idx => $prenomina) {

                            $oNominas_edit = new nominas_fiscal();
                            $oNominas_edit->id_nomina = $prenomina->id_nomina;
                            $oNominas_edit->id_empleado = $prenomina->id_empleado;
                            $oNominas_edit->Nomina_edit();
                            if ($oNominas_edit->nombre != '' && $oNominas_edit->estatus_final_edit == "2") {

                                $id_nomina = $oNominas_edit->id_nomina;
                                $id_empleado = $oNominas_edit->id_empleado;
                                $nombre = $oNominas_edit->nombre;
                                $diario = $oNominas_edit->diario;
                                $faltas = $oNominas_edit->faltas;
                                $asistencias = $oNominas_edit->asistencias;
                                $total = $oNominas_edit->total;
                                $total_p = $oNominas_edit->total_p;
                            } else {
                                $id_nomina = $prenomina->id_nomina;
                                $id_empleado = $prenomina->id_empleado;
                                $nombre = $prenomina->nombre;
                                $diario = $prenomina->diario;
                                $faltas = $prenomina->faltas;
                                $asistencias = $prenomina->asistencias;
                                $total = $prenomina->total;
                                $total_p = $prenomina->total_p;
                            }
                    ?>
                            <tr>
                                <td style="text-align: center;"><?= $nombre ?></td>
                                
                                <td style="text-align: center;"><?= "$" . $diario ?></td>
                                <td style="text-align: center;"><?= $faltas ?></td>
                                <td style="text-align: center;"><?= $asistencias ?></td>
                                
                                <td style="text-align: center;"><?= "$" . $total ?></td>
                                
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
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>