<?php
/*
 * Copyright 2021 - Felipe angel cerda contreras 
 * felipeangelcerdacontreras@gmail.com
 */
session_start();

$_SITE_PATH = $_SERVER["DOCUMENT_ROOT"] . "/" . explode("/", $_SERVER["PHP_SELF"])[1] . "/";
require_once($_SITE_PATH . "/app/model/nominas.class.php");

$oNominas = new nominas(true, $_POST);
$lstnominas = $oNominas->Listado();
?>
<script type="text/javascript">
    $(document).ready(function(e) {
        $("#dataTable").DataTable();

        $("#btnAgregar").button().click(function(e) {
            Editar("", "Agregar");
        });

    });
</script>
<!-- DataTales Example -->
<div class="card shadow mb-4">
    <div class="card-header py-3" style="text-align:left">
        <h5 class="m-0 font-weight-bold text-danger">nominas</h5>
        <div class="form-group" style="text-align:right">
            <input type="button" id="btnAgregar" class="btn btn-outline-danger" name="btnAgregar" value="Agregar Nomina" />
        </div>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>Fecha</th>
                        <th>Semana</th>
                        <th>Total Nomina</th>
                        <th>Estatus</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tfoot>
                    <th>Fecha</th>
                    <th>Semana</th>
                    <th>Total Nomina</th>
                    <th>Estatus</th>
                    <th>Acciones</th>
                </tfoot>
                <tbody>
                    <?php
                    if (count($lstnominas) > 0) {
                        foreach ($lstnominas as $idx => $campo) {
                    ?>
                            <tr>
                                <td style="text-align: center;"><?= $campo->fecha ?></td>
                                <td style="text-align: center;"><?= $campo->semana ?></td>
                                <td style="text-align: center;"><?= "$".number_format($campo->total_nomina, 2, '.', ',') ?></td>
                                <td style="text-align: center;"><?= $campo->estatus ?></td>
                                <td style="text-align: center;">
                                    <?php if ($campo->estatus == "NO PAGADA") { ?>
                                        <a class="btn btn-outline-sm btn-success" href="javascript:Editar('<?= $campo->id ?>','Pagar')">Pagar</a>
                                        <a class="btn btn-outline-sm btn-warning" href="javascript:Editar('<?= $campo->id ?>','Recalcular')">Recalcular</a>
                                        <a class="btn btn-outline-sm btn-danger" href="javascript:Editar('<?= $campo->id ?>','<?= $campo->semana ?>')">Editar</a>
                                    <?php } ?>
                                    <a class="btn btn-outline-sm btn-info" href="javascript:Editar('<?= $campo->id ?>','Final','<?= $campo->semana ?>')">Ver</a>
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