<?php
/*
 * Copyright 2021 - Felipe angel cerda contreras 
 * felipeangelcerdacontreras@gmail.com
 */
session_start();

$_SITE_PATH = $_SERVER["DOCUMENT_ROOT"] . "/" . explode("/", $_SERVER["PHP_SELF"])[1] . "/";
require_once($_SITE_PATH . "/app/model/prestamos.class.php");

$oPrestamos = new prestamos(true, $_POST);
$lstprestamos = $oPrestamos->Listado();

?>
<script type="text/javascript">
    $(document).ready(function(e) {
        const formatterDolar = new Intl.NumberFormat('en-US', {
            style: 'currency',
            currency: 'USD'
        })
        $("#dataTable").DataTable({
            dom: 'Bfrtip',
            buttons: [{
                extend: 'pdfHtml5',
                footer: true,
                title: 'Reporte Prestamos al dia <?= date('d-m-Y') ?>',
                text: 'Exportar a pdf',
                exportOptions: {
                    columns: [0, 1, 2, 3, 4]
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
                solicitado = api
                    .column(3)
                    .data()
                    .reduce(function(a, b) {
                        return intVal(a) + intVal(b);
                    }, 0);
                
                pagar = api
                    .column(4)
                    .data()
                    .reduce(function(a, b) {
                        return intVal(a) + intVal(b);
                    }, 0);

                pagarSemana = api
                    .column(5)
                    .data()
                    .reduce(function(a, b) {
                        return intVal(a) + intVal(b);
                    }, 0);

                // Update footer
                $(api.column(3).footer()).html(formatterDolar.format(solicitado));

                $(api.column(4).footer()).html(formatterDolar.format(pagar));

                $(api.column(5).footer()).html(formatterDolar.format(pagarSemana));
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
        <h5 class="m-0 font-weight-bold text-danger">Prestamos</h5>
        <div class="form-group" style="text-align:right">
            <input type="button" id="btnAgregar" class="btn btn-outline-danger" name="btnAgregar" value="Agregar Prestamo" />
        </div>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>Empleado</th>
                        <th>Fecha de registro</th>
                        <th>Fecha pago</th>
                        <th>Monto Solicitado</th>
                        <th>Monto A Pagar</th>
                        <th>Cantidad A Pagar Por Semana</th>
                        <th>Semanas</th>
                        <th>Semana actual</th>
                        <th>Estatus</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tfoot>
                    <th>Empleado</th>
                    <th>Fecha de registro</th>
                    <th>Fecha pago</th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th>Semanas</th>
                    <th>Semana actual</th>
                    <th>Estatus</th>
                    <th>Acciones</th>
                </tfoot>
                <tbody>
                    <?php
                    if (count($lstprestamos) > 0) {
                        foreach ($lstprestamos as $idx => $campo) {
                    ?>
                            <tr>
                                <td ><?= $campo->nombres . " " . $campo->ape_paterno . " " . $campo->ape_materno ?></td>
                                <td ><?= $campo->fecha_registro ?></td>
                                <td ><?= $campo->fecha_pago ?></td>
                                <td >$<?= $campo->monto ?></td>
                                <td >$<?= $campo->monto_pagar ?></td>
                                <td >$<?= $campo->monto_por_semana ?></td>
                                <td ><?= $campo->numero_semanas ?></td>
                                <td ><?= $campo->semana_actual ?></td>
                                <td ><?= $campo->est ?></td>
                                <td style="width: 20%;">
                                    <div class="row">
                                        <a class="btn btn-outline-sm btn-danger" href="javascript:Editar('<?= $campo->id ?>','Detalles','<?= $campo->nombres . " " . $campo->ape_paterno . " " . $campo->ape_materno ?>')">
                                            <span class="glyphicon glyphicon-ok">Ver Detalles</a><br>
                                    </div>
                                    <?php if (empty($campo->semana_actual) && $campo->estatus == "1") { ?>
                                        <div class="row" style="margin-top: 1%;">
                                            <a class="btn btn-outline-sm btn-success" href="javascript:Editar('<?= $campo->id ?>','Editar')"><span class="glyphicon glyphicon-ok">Editar</a>
                                        </div>
                                    <?php } ?>
                                    <?php if ($campo->estatus == "1") { ?>
                                        <div class="row" style="margin-top: 1%;">
                                            <a class="btn btn-outline-sm btn-warning" href="javascript:Editar('<?= $campo->id ?>','Liquidar')"><span class="glyphicon glyphicon-ok">Liquidar</a>
                                        </div>
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