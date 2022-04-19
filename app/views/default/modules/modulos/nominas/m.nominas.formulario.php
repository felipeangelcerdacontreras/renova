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
                        columns: [0, 6, 7, 9, 10, 11, 12, 13, 14, 15, 16, 17]
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
                        <th>Premio de asistencia</th>
                        <th>Premio de puntualidad</th>
                        <th>Bono de productividad</th>
                        <th>Bono de 12hrs</th>
                        <th>Bono de viaje</th>
                        <th>Complemento de sueldo</th>
                        <th>Sueldo Diario</th>
                        <th>Faltas</th>
                        <th>Dias Laborados</th>
                        <th>Horas Extras</th>
                        <th>Vacaciones</th>
                        <th>Incapacidades</th>
                        <th>Total Percepciones</th>
                        <th>Comedor</th>
                        <th>Ahorro</th>
                        <th>Prestamos</th>
                        <th>Fonacot</th>
                        <th>Infonavit</th>
                        <th>Otros Cargos</th>
                        <th>Total Retenciones</th>
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
                                <td style="text-align: center;">
                                    <?= $campo->asistencia ?><br>
                                        <input class="" type="checkbox" id="asistencia_<?= $campo->id_empleado?>" name="asistencia_<?= $campo->id_empleado?>" value="1"><br>
                                        <label class="form-check-label" for="A">Agregar Premio</label>
                                </td>
                                <td style="text-align: center;">
                                    <?= "$" . $campo->puntualidad;?><br>
                                    <input class="" type="checkbox" id="puntualidad_<?= $campo->id_empleado?>" name="puntualidad_<?= $campo->id_empleado?>" value="1"><br>
                                    <label class="form-check-label" for="A">Agregar Premio</label>
                                </td>
                                <td style="text-align: center;"><?= "$" . $campo->productividad ?></td>
                                <td style="text-align: center;"><?= "$" . $campo->doce ?></td>
                                <td style="text-align: center;"><?= "$" . $campo->bono_viaje ?></td>
                                <td style="text-align: center;">
                                    <?= "$" . $campo->complemento ?>
                                    <input type="input" id="complemento_<?= $campo->id_empleado?>"  name="complemento_<?= $campo->id_empleado?>" value="<?= $campo->complemento ?>" style="text-align:center" class="form-control remove_<?= $campo->id_empleado?>" />
                                </td>
                                <td style="text-align: center;"><?= "$" . $campo->diario ?></td>
                                <td style="text-align: center;"><?= $campo->faltas ?></td>
                                <td style="text-align: center;">
                                    <?= $campo->asistencias ?>
                                    <input type="input" id="laborados_<?= $campo->id_empleado?>"  name="laborados_<?= $campo->id_empleado?>" value="<?= $campo->asistencias ?>" style="text-align:center" class="form-control remove_<?= $campo->id_empleado?>" />
                                </td>
                                <td style="text-align: center;"><?= "$" . $campo->extras ?></td>
                                <td style="text-align: center;"><?= "$" . $campo->vacaciones ?></td>
                                <td style="text-align: center;"><?= "$" . bcdiv(($campo->monto_incapacida * $campo->dias_incapacida), '1', 2) ?></td>
                                <td style="text-align: center;"><?= "$" . $campo->total ?></td>
                                <td style="text-align: center;">
                                <?= $campo->comedor ?>
                                    <input type="input" id="comedor_<?= $campo->id_empleado?>"  name="comedor_<?= $campo->id_empleado?>" value="<?= $campo->comedor ?>" style="text-align:center" class="form-control remove_<?= $campo->id_empleado?>" />
                                </td>
                                <td style="text-align: center;"><?= "-$" . $campo->ahorro ?></td>
                                <td style="text-align: center;"><?= "-$" . $campo->prestamos ?></td>
                                <td style="text-align: center;"><?= "-$" . $campo->fonacot ?></td>
                                <td style="text-align: center;"><?= "-$" . $campo->infonavit ?></td>
                                <td style="text-align: center;"><?= "-$" . $campo->otros ?></td>
                                <td style="text-align: center;"><?= "-$" . $campo->total_r ?></td>
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
            <a href="javascript:Editar(<?= $lstnominas[0]->id_nomina ?>, 'AddNomina','<?= $lstnominas[0]->fecha ?>')" class="scroll-to-top" style="display: inline;width: 15%;background-color: #4fe141;">
                <span class="icon text-white-50">
                    <i class="fas fa-check"></i>
                </span>
                <span class="text">Agregar nomina</span>
            </a>
        </div>
    </div>
</div>