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
$oNominas->estatus = '1';
$sesion = $_SESSION[$oNominas->NombreSesion];
$oNominas->Listado_nomina();

$oNominas1 = new nominas();
$oNominas1->tabla = "1";
$oNominas1->id_nomina = $oNominas->id_nomina;
$oNominas1->id_empleado = $oNominas->id_empleado;
$sesion = $_SESSION[$oNominas1->NombreSesion];
$oNominas1->Listado_nomina();

$aPermisos = empty($oNominas->perfiles_id) ? array() : explode("@", $oNominas->perfiles_id);
?>
<script type="text/javascript">
    $(document).ready(function(e) {

    });
</script>
<div class="table-responsive">
    <h1 class="text-success"><strong>Solicitud:</strong></h1>
            <table class="table table-bordered" id="dataTable4" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>Nombre Empleado</th>
                        <th>Premio de asistencia</th>
                        <th>Premio de puntualidad</th>
                        <th>Bono de productividad</th>
                        <th>Bono de 12hrs</th>
                        <th>Complemento de sueldo</th>
                        <th>Sueldo Diario</th>
                        <th>Faltas</th>
                        <th>Dias Laborados</th>
                        <th>Horas Extras</th>
                        <th>Total Percepciones</th>
                        <th>Comedor</th>
                        <th>Ahorro</th>
                        <th>Prestamos</th>
                        <th>Fonacot</th>
                        <th>Infonavit</th>
                        <th>Otros Cargos</th>
                        <th>Total Retenciones</th>
                        <th>Total A Pagar</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td style="text-align: center;"><?= $oNominas->nombre ?></td>
                        <td style="text-align: center;">
                            <?= $oNominas->asistencia ?><br>
                        </td>
                        <td style="text-align: center;">
                            <?= "$" . $oNominas->puntualidad;?><br>
                        </td>
                        <td style="text-align: center;"><?= "$" . $oNominas->productividad ?></td>
                        <td style="text-align: center;"><?= "$" . $oNominas->doce ?></td>
                        <td style="text-align: center;">
                            <?= "$" . $oNominas->complemento ?>
                        </td>
                        <td style="text-align: center;"><?= "$" . $oNominas->diario ?></td>
                        <td style="text-align: center;"><?= $oNominas->faltas ?></td>
                        <td style="text-align: center;">
                            <?= $oNominas->asistencias ?>
                        </td>
                        <td style="text-align: center;"><?= "$" . $oNominas->extras ?></td>
                        <td style="text-align: center;"><?= "$" . $oNominas->total ?></td>
                        <td style="text-align: center;"><?= $oNominas->comedor ?></td>
                        <td style="text-align: center;"><?= "-$" . $oNominas->ahorro ?></td>
                        <td style="text-align: center;"><?= "-$" . $oNominas->prestamos ?></td>
                        <td style="text-align: center;"><?= "-$" . $oNominas->fonacot ?></td>
                        <td style="text-align: center;"><?= "-$" . $oNominas->infonavit ?></td>
                        <td style="text-align: center;"><?= "-$" . $oNominas->otros ?></td>
                        <td style="text-align: center;"><?= "-$" . $oNominas->total_r ?></td>
                        <td style="text-align: center;"><?= "$" . $oNominas->total_p ?></td>
                        <td style="text-align: center;">
                            <a class="btn btn-outline-sm btn-success" href="javascript:AprovarDenegar('<?= $oNominas->id ?>','2')">Aprovar</a>
                            <a class="btn btn-outline-sm btn-danger" href="javascript:AprovarDenegar('<?= $oNominas->id ?>','3')">Denegar</a>
                        </td>
                    </tr>
                </tbody>
            </table>
            <h1 class="text-warning"><strong>Original:</strong></h1>
            <table class="table table-bordered" id="dataTable5" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>Nombre Empleado</th>
                        <th>Premio de asistencia</th>
                        <th>Premio de puntualidad</th>
                        <th>Bono de productividad</th>
                        <th>Bono de 12hrs</th>
                        <th>Complemento de sueldo</th>
                        <th>Sueldo Diario</th>
                        <th>Faltas</th>
                        <th>Dias Laborados</th>
                        <th>Horas Extras</th>
                        <th>Total Percepciones</th>
                        <th>Comedor</th>
                        <th>Ahorro</th>
                        <th>Prestamos</th>
                        <th>Fonacot</th>
                        <th>Infonavit</th>
                        <th>Otros Cargos</th>
                        <th>Total Retenciones</th>
                        <th>Total A Pagar</th>
                        <th>Nombre</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td style="text-align: center;"><?= $oNominas1->nombre ?></td>
                        <td style="text-align: center;">
                            <?= $oNominas1->asistencia ?><br>
                        </td>
                        <td style="text-align: center;">
                            <?= "$" . $oNominas1->puntualidad;?><br>
                        </td>
                        <td style="text-align: center;"><?= "$" . $oNominas1->productividad ?></td>
                        <td style="text-align: center;"><?= "$" . $oNominas1->doce ?></td>
                        <td style="text-align: center;">
                            <?= "$" . $oNominas1->complemento ?>
                        </td>
                        <td style="text-align: center;"><?= "$" . $oNominas1->diario ?></td>
                        <td style="text-align: center;"><?= $oNominas1->faltas ?></td>
                        <td style="text-align: center;">
                            <?= $oNominas1->asistencias ?>
                        </td>
                        <td style="text-align: center;"><?= "$" . $oNominas1->extras ?></td>
                        <td style="text-align: center;"><?= "$" . $oNominas1->total ?></td>
                        <td style="text-align: center;"><?= $oNominas1->comedor ?></td>
                        <td style="text-align: center;"><?= "-$" . $oNominas1->ahorro ?></td>
                        <td style="text-align: center;"><?= "-$" . $oNominas1->prestamos ?></td>
                        <td style="text-align: center;"><?= "-$" . $oNominas1->fonacot ?></td>
                        <td style="text-align: center;"><?= "-$" . $oNominas1->infonavit ?></td>
                        <td style="text-align: center;"><?= "-$" . $oNominas1->otros ?></td>
                        <td style="text-align: center;"><?= "-$" . $oNominas1->total_r ?></td>
                        <td style="text-align: center;"><?= "$" . $oNominas1->total_p ?></td>
                        <td style="text-align: center;">
                            <?= $oNominas1->nombre . "<br>" ?>
                            <strong>Original</strong>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>