<?php
/*
 * Copyright 2021 - Felipe angel cerda contreras 
 * felipeangelcerdacontreras@gmail.com
 */
session_start();

$_SITE_PATH = $_SERVER["DOCUMENT_ROOT"] . "/" . explode("/", $_SERVER["PHP_SELF"])[1] . "/";
require_once($_SITE_PATH . "/app/model/ubicacion.class.php");

$oUbicacion = new ubicacion();
$lstubicacion = $oUbicacion->Listado();
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
        <h5 class="m-0 font-weight-bold text-danger">ubicacion checador</h5>
        <div class="form-group" style="text-align:right">
            <input type="button" id="btnAgregar" class="btn btn-outline-success" name="btnAgregar" value="Agregar ubicacion Extra" />
        </div>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>Ubicacion</th>
                        <th>Latitude</th>
                        <th>Longitude</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tfoot>
                    <th>Ubicacion</th>
                    <th>Latitude</th>
                    <th>Longitude</th>
                    <th>Acciones</th>
                </tfoot>
                <tbody>
                    <?php
                    if (count($lstubicacion) > 0) {
                        foreach ($lstubicacion as $idx => $campo) {
                    ?>
                            <tr>
                                <td style="text-align: center;"><?= $campo->nombre ?></td>
                                <td style="text-align: center;"><?= $campo->lat ?></td>
                                <td style="text-align: center;"><?= $campo->lon ?></td>
                                <td style="text-align: center;">
                                        <a class="btn btn-outline-sm btn-danger" href="javascript:Editar('<?= $campo->id ?>','Editar')">Editar</a>
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