<?php
/*
 * Copyright 2021 - Felipe angel cerda contreras 
 * felipeangelcerdacontreras@gmail.com
 */
session_start();

$_SITE_PATH = $_SERVER["DOCUMENT_ROOT"] . "/" . explode("/", $_SERVER["PHP_SELF"])[1] . "/";
require_once($_SITE_PATH . "/app/model/permisos.class.php");

$oPermisos = new permisos(true, $_POST);
$lstpermisos = $oPermisos->Listado();

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
        <h5 class="m-0 font-weight-bold text-danger">permisos</h5>
        <div class="form-group" style="text-align:right">
            <input type="button" id="btnAgregar" class="btn btn-outline-danger" name="btnAgregar" value="Agregar Permiso" />
        </div>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>Fecha</th>
                        <th>Empleado</th>
                        <th>Tipo permiso</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tfoot>
                    <th>Fecha</th>
                    <th>Empleado</th>
                    <th>Tipo permiso</th>
                    <th>Acciones</th>
                </tfoot>
                <tbody>
                    <?php
                    if (count($lstpermisos) > 0) {
                        foreach ($lstpermisos as $idx => $campo) {
                    ?>
                            <tr>
                                <td style="text-align: center;"><?= $campo->fecha ?></td>
                                <td style="text-align: center;"><?= $campo->nombres . " " . $campo->ape_paterno . " " . $campo->ape_materno ?></td>
                                <td style="text-align: center;"><?php
                                                                if ($campo->llegada_tarde == 1) {
                                                                    echo"Llegada tarde";
                                                                } else if ($campo->salida_temprano == 1){
                                                                    echo "Salida tamprano";
                                                                } else if ($campo->dia_completo == 1){
                                                                    echo "Dia completo";
                                                                }
                                                                ?></td>
                                <td style="text-align: center;">
                                    <a class="btn btn-outline-sm btn-warning" href="javascript:Editar('<?= $campo->id ?>','Detalles','<?= $campo->nombres . " " . $campo->ape_paterno . " " . $campo->ape_materno ?>')">Ver</a>
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