<?php
/*
 * Copyright 2021 - Felipe angel cerda contreras 
 * felipeangelcerdacontreras@gmail.com
 */
session_start();

$_SITE_PATH = $_SERVER["DOCUMENT_ROOT"] . "/" . explode("/", $_SERVER["PHP_SELF"])[1] . "/";
require_once($_SITE_PATH . "/app/model/comedor.nomina.class.php");

$oComedor = new comedor_nominas(true, $_POST);
$lstcomedor = $oComedor->Listado1();

$lstcomedor1 = $oComedor->Listado();

?>
<script type="text/javascript">
    $(document).ready(function(e) {
        $("#dataTable").DataTable({
            dom: 'Bfrtip',
            buttons: [{
                extend: 'excel',
                footer: true,
                title: 'Reporte comedor al dia <?= date('d-m-Y') ?>',
                text: 'Exportar a Excel',
                exportOptions: {
                    columns: [0, 1, 2]
                }
            },{
                extend: 'pdfHtml5',
                footer: true,
                title: 'Reporte comedor al dia <?= date('d-m-Y') ?>',
                text: 'Exportar a pdf',
                exportOptions: {
                    columns: [0, 1, 2]
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
                monto = api
                    .column(2)
                    .data()
                    .reduce(function(a, b) {
                        return intVal(a) + intVal(b);
                    }, 0);

                // Update footer
                $(api.column(2).footer()).html(formatterDolar.format(monto));
            }
        });
        $(".buttons-html5 ").addClass("btn btn-outline-danger");

        $("#btnAgregar").button().click(function(e) {
            Editar("", "Agregar");
        });

    });
</script>
<!-- DataTales Example -->
<div class="card shadow mb-4">
    <div class="card-header py-3" style="text-align:left">
        <h5 class="m-0 font-weight-bold text-danger">Comedor</h5>
        <div class="form-group" style="text-align:right">
            <input type="button" id="btnAgregar" class="btn btn-outline-danger" name="btnAgregar" value="Agregar" />
        </div>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>Empleado</th>
                        <th>Fecha de registro</th>
                        <th>Cantidad</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tfoot>
                    <th>Empleado</th>
                    <th>Fecha de registro</th>
                    <th></th>
                    <th>Acciones</th>
                </tfoot>
                <tbody>
                    <?php
                    if (count($lstcomedor) > 0) {
                        foreach ($lstcomedor as $idx => $campo) {
                    ?>
                            <tr>
                                <td style="text-align: center;"><?= $campo->nombres . " " . $campo->ape_paterno . " " . $campo->ape_materno ?></td>
                                <td style="text-align: center;"><?= $campo->fecha ?></td>
                                <td style="text-align: center;"><?= $campo->precio_platillo ?></td>
                                <td style="text-align: center;">
                                        <a class="btn btn-outline-sm btn-danger" href="javascript:Editar('<?= $campo->id ?>','Editar')"><span class="glyphicon glyphicon-ok">Editar</a>
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