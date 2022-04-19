<?php
/*
 * Copyright 2021 - Felipe angel cerda contreras 
 * felipeangelcerdacontreras@gmail.com
 */
session_start();

$_SITE_PATH = $_SERVER["DOCUMENT_ROOT"] . "/" . explode("/", $_SERVER["PHP_SELF"])[1] . "/";
require_once($_SITE_PATH . "/app/model/vacaciones.class.php");

$oVacaciones = new vacaciones(true, $_POST);
$lstvacaciones = $oVacaciones->Listado();

?>
<script type="text/javascript">
    $(document).ready(function(e) {
        $("#dataTable").DataTable();

        $("#btnAgregar").button().click(function(e) {
            Editar("", "Editar");
        });

        $("#btnAgregarV").button().click(function(e) {
            Editar("", "Generar vacaciones");
        });

    });
</script>
<!-- DataTales Example -->
<div class="card shadow mb-4">
    <div class="card-header py-3" style="text-align:left">
        <h5 class="m-0 font-weight-bold text-danger">vacaciones</h5>
        <div class="form-group" style="text-align:right">
        <input type="button" id="btnAgregarV" class="btn btn-outline-danger" name="btnAgregarV" value="Generar vaciones del periodo nominal" />
            <input type="button" id="btnAgregar" class="btn btn-outline-danger" name="btnAgregar" value="Agregar vacaciones" />
        </div>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>Fecha</th>
                        <th>Empleado</th>
                        <th>Dias restantes</th>
                        <th>Pago</th>
                        <th>Fecha de pago prima</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tfoot>
                    <tr>
                        <th>Fecha</th>
                        <th>Empleado</th>
                        <th>Dias restantes</th>
                        <th>Pago</th>
                        <th>Fecha de pago prima</th>
                        <th>Acciones</th>
                    </tr>
                </tfoot>
                <tbody>
                    <?php
                    if (count($lstvacaciones) > 0) {
                        foreach ($lstvacaciones as $idx => $campo) {

                    ?>
                            <tr>
                                <td style="text-align: center;"><?= $campo->fecha ?></td>
                                <td style="text-align: center;"><?= $campo->nombres . " " . $campo->ape_paterno . " " . $campo->ape_materno ?></td>
                                <td style="text-align: center;"><?php
                                                                if ($campo->inicio_vacaci == "" || $campo->fin_vacaci == "") {
                                                                    echo "Dias disponibles " . $campo->dias_restantes;
                                                                } else if ($campo->inicio_vacaci != "" || $campo->fin_vacaci != "") {
                                                                    echo "Dias de vacaciones asignados";
                                                                } else if ($campo->dia_completo == 1) {
                                                                    echo "Dia completo";
                                                                }
                                                                ?></td>
                                <td style="text-align: center;"><?= $campo->pago_prima ?></td>
                                <td style="text-align: center;"><?= $campo->fecha_pago ?></td>
                                <td style="text-align: center;">
                                    <?php
                                    $fechaActual = date('Y-m-d');
                                    if ($fechaActual <= $campo->fecha_final) { ?>
                                        <a class="btn btn-outline-sm btn-warning" href="javascript:Editar('<?= $campo->id ?>','Editar')">Ver</a>
                                    <?php } else { ?>
                                        <a class="btn btn-outline-sm btn-danger" href="javascript:Editar('<?= $campo->id ?>','')">Vacaciones no disponibles</a>
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