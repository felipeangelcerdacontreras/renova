<?php
/*
 * Copyright 2021 - Felipe angel cerda contreras 
 * felipeangelcerdacontreras@gmail.com
 */
session_start();

$_SITE_PATH = $_SERVER["DOCUMENT_ROOT"] . "/" . explode("/", $_SERVER["PHP_SELF"])[1] . "/";
require_once($_SITE_PATH . "/app/model/horas.class.php");

$oHoras = new horas(true, $_POST);
$lsthoras = $oHoras->Listado();
?>
<script type="text/javascript">
    $(document).ready(function(e) {
        $("#dataTable").DataTable({
            dom: 'Bfrtip',
            buttons: [{
                extend: 'excel',
                footer: true,
                title: 'Reporte horas extras al dia <?= date('d-m-Y') ?>',
                text: 'Exportar a excel',
                exportOptions: {
                    columns: [0, 1, 2, 3, 4, 5, 6, 7, 8]
                }
            },{
                extend: 'pdfHtml5',
                footer: true,
                title: 'Reporte horas extras al dia <?= date('d-m-Y') ?>',
                text: 'Exportar a pdf',
                exportOptions: {
                    columns: [0, 1, 2, 3, 4, 5, 6]
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
                 horas = api
                    .column(5)
                    .data()
                    .reduce(function(a, b) {
                        return intVal(a) + intVal(b);
                    }, 0);

                // Update footer
                $(api.column(5).footer()).html(horas);
            }
        });
        $(".buttons-html5 ").addClass("btn btn-outline-danger");
        $("#btnAgregar").button().click(function(e) {
            Editar("", "Agregar");
        });
        $("#btnCalcular").button().click(function(e) {
            Editar("", "Calcular");
        });

    });
</script>
<!-- DataTales Example -->
<div class="card shadow mb-4">
    <div class="card-header py-3" style="text-align:left">
        <h5 class="m-0 font-weight-bold text-danger">Horas Extras</h5>
        <div class="form-group" style="text-align:right">
            <input type="button" id="btnCalcular" class="btn btn-outline-success" name="btnCalcular" value="Generar Extras" />
            <input type="button" id="btnAgregar" class="btn btn-outline-success" name="btnAgregar" value="Agregar extras" />
        </div>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>Folio</th>
                        <th>Empleado</th>
                        <th>Estatus</th>
                        <th>Fecha De Registro</th>
                        <th>Motivo</th>
                        <th>Horas Extras</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tfoot>
                    <th>Folio</th>
                    <th>Empleado</th>
                    <th>Estatus</th>
                    <th>Fecha De Registro</th>
                    <th>Motivo</th>
                    <th>Horas Extras</th>
                    <th>Acciones</th>
                </tfoot>
                <tbody>
                    <?php
                    if (count($lsthoras) > 0) {
                        foreach ($lsthoras as $idx => $campo) {
                    ?>
                            <tr>
                                <td style="text-align: center;"><?= $campo->id ?></td>
                                <td style="text-align: center;"><?= $campo->empleado ?></td>
                                <td style="text-align: center;"<?php if ($campo->est == "AUTORIZADA"){ echo "class='btn-success'";}else{echo "class='btn-warning'"; } ?>><?= $campo->est ?></td>
                                <td style="text-align: center;"><?= $campo->fecha_registro ?></td>
                                <td style="text-align: center;"><?= $campo->motivo ?></td>
                                <td style="text-align: center;"><?php 
                                echo $campo->horas_extras;
                                /*$porciones = explode(".", $campo->horas_extras);
                                echo $porciones[0].":".$porciones[1];*/
                                 ?></td>
                                <td style="text-align: center;">
                                    <?php if ($campo->estatus == 1) { ?>
                                        <a class="btn btn-outline-sm btn-success" href="javascript:Editar('<?= $campo->id ?>','Autorizar')">✓Autorizar</a>
                                    <?php } ?>
                                </td>
                            </tr>
                    <?php
                        }
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>