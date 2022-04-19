<?php
/*
 * Copyright 2021 - Felipe angel cerda contreras 
 * felipeangelcerdacontreras@gmail.com
 */
session_start();

$_SITE_PATH = $_SERVER["DOCUMENT_ROOT"] . "/" . explode("/", $_SERVER["PHP_SELF"])[1] . "/";
require_once($_SITE_PATH . "/app/model/marcas.class.php");

$oMarcas = new marcas();
$lstMarcas = $oMarcas->Listado();
?>
<script type="text/javascript">
$(document).ready(function(e) {
    $("#dataTable").DataTable();

    $("#btnAgregar").button().click(function(e) {
        Editar("");
    });

});
</script>
<!-- DataTales Example -->
<div class="card shadow mb-4">
    <div class="card-header py-3" style="text-align:left">
        <h5 class="m-0 font-weight-bold text-primary">Marcas Vehiculos</h5>
        <div class="form-group" style="text-align:right">
            <input type="button" id="btnAgregar" class="btn btn-outline-success" name="btnAgregar" value="Agregar nuevo" />
        </div>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>Editar</th>
                        <th>Nombre</th>
                    </tr>
                </thead>
                <tfoot>
                    <th>Editar</th>
                    <th>Nombre</th>
                </tfoot>
                <tbody>
                    <?php
                        if (count($lstMarcas) > 0) {
                            foreach ($lstMarcas as $idx => $campo) {
                                ?>
                                    <tr>
                                        <td style="text-align: center;"><a href="javascript:Editar('<?= $campo->mar_id ?>')">Editar</a></td> 
                                        <td style="text-align: center;"><?= $campo->mar_nombre ?></td>
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