<?php
/*
 * Copyright 2021 - Felipe angel cerda contreras 
 * felipeangelcerdacontreras@gmail.com
 */
session_start();

$_SITE_PATH = $_SERVER["DOCUMENT_ROOT"] . "/" . explode("/", $_SERVER["PHP_SELF"])[1] . "/";
require_once($_SITE_PATH . "/app/model/nominas.fiscal.class.php");

$oNominas = new nominas_fiscal();
$oNominas->id = addslashes(filter_input(INPUT_POST, "id"));
$nombre = addslashes(filter_input(INPUT_POST, "nombre"));
$lstnominas = $oNominas->Listado_prenomina();

?>
<script type="text/javascript">
    $(document).ready(function(e) {
        $(document).ready(function() {
            $('#dataTable2').DataTable({
                "paging": false,
                dom: 'Bfrtip',
                buttons: [{
                    extend: 'pdfHtml5',
                    title: 'Reporte Nomina Semana <?= $nombre ?>',
                    text: 'Exportar a pdf',
                    orientation: 'landscape',
                    exportOptions: {
                        columns: [0, 1, 2, 3, 4, 5, 6]
                    }
                }],
            });
            $(".buttons-html5 ").addClass("btn btn-outline-danger");
        });
    });
</script>
<!-- DataTales Example -->
<div class="card shadow mb-4">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered" id="dataTable2" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>Nombre Empleado</th>
                        <th>Sueldo Diario</th>
                        <th>Faltas</th>
                        <th>Dias Laborados</th>
                        <th>Total Percepciones</th>
                        <th>Total A Pagar</th>
                        <th>Recibo</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if (count($lstnominas) > 0) {
                        foreach ($lstnominas as $idx => $campo) {
                    ?>
                            <tr>
                                <td style="text-align: center;"><?= $campo->nombre ?></td>
                                <td style="text-align: center;"><?= "$" . $campo->diario ?></td>
                                <td style="text-align: center;"><?= $campo->faltas ?></td>
                                <td style="text-align: center;">
                                    <?= $campo->asistencias ?>
                                    <input type="input" id="laborados_<?= $campo->id_empleado?>"  name="laborados_<?= $campo->id_empleado?>" value="<?= $campo->asistencias ?>" style="text-align:center" class="form-control remove_<?= $campo->id_empleado?>" />
                                </td>
                                <td style="text-align: center;"><?= "$" . $campo->total ?></td>
                                <td style="text-align: center;"><?= "$" . $campo->total_p ?></td>
                                <td style="text-align: center;">
                                    <?= $campo->nombre . "<br>" ?>
                                    <?php if ($campo->estatus == '1') { ?>
                                        <a class="btn btn-outline-sm btn-warning" href="javascript:Reporte('<?= $campo->id_nomina ?>','<?= $campo->id_empleado ?>')">Ver</a>
                                    <?php } else { ?>
                                        <a class="btn btn-outline-sm btn-danger" href="javascript:Editar('<?= $campo->id_nomina ?>','Solicitud','<?= $campo->id_empleado ?>','<?= $campo->nombre ?>')">Editar</a>
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
            <a href="javascript:Editar(<?= $lstnominas[0]->id_nomina ?>, 'AddNomina','<?= $lstnominas[0]->fecha ?>')" class="scroll-to-top" style="display: inline;width: 15%;background-color: #4fe141;">
                <span class="icon text-white-50">
                    <i class="fas fa-check"></i>
                </span>
                <span class="text">Agregar nomina</span>
            </a>
        </div>
    </div>
</div>