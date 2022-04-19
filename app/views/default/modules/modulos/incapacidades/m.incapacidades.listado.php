<?php
/*
 * Copyright 2021 - Felipe angel cerda contreras 
 * felipeangelcerdacontreras@gmail.com
 */
session_start();

$_SITE_PATH = $_SERVER["DOCUMENT_ROOT"] . "/" . explode("/", $_SERVER["PHP_SELF"])[1] . "/";
require_once($_SITE_PATH . "/app/model/incapacidades.class.php");

$oIncapacidades = new incapacidades(true, $_POST);
$lstincapacidades = $oIncapacidades->Listado();

?>
<script type="text/javascript">
    $(document).ready(function(e) {
        $("#dataTable").DataTable();

        $("#btnAgregar").button().click(function(e) {
            Editar("", "Agregar");
        });
        $("#btnMinimo").button().click(function(e) {
            Editar("", "Minimo");
        });
    });
</script>
<!-- DataTales Example -->
<div class="card shadow mb-4">
    <div class="card-header py-3" style="text-align:left">
        <h5 class="m-0 font-weight-bold text-danger">incapacidades</h5>
        <div class="form-group" style="text-align:right">
            <input type="button" id="btnMinimo" class="btn btn-outline-danger" name="btnMinimo" value="Sueldo minimo" />
            <input type="button" id="btnAgregar" class="btn btn-outline-danger" name="btnAgregar" value="Agregar incapacidades" />
        </div>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>Fecha inicio</th>
                        <th>Fecha Final</th>
                        <th>Empleado</th>
                        <th>Días totales</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tfoot>
                    <tr>
                        <th>Fecha inicio</th>
                        <th>Fecha Final</th>
                        <th>Empleado</th>
                        <th>Días totales</th>
                        <th>Acciones</th>
                    </tr>
                </tfoot>
                <tbody>
                    <?php
                    if (count($lstincapacidades) > 0) {
                        foreach ($lstincapacidades as $idx => $campo) {
                    ?>
                            <tr>
                                <td style="text-align: center;"><?= $campo->inicio_incapacida ?></td>
                                <td style="text-align: center;"><?= $campo->fin_incapacida ?></td>
                                <td style="text-align: center;"><?= $campo->nombres . " " . $campo->ape_paterno . " " . $campo->ape_materno ?></td>
                                <td style="text-align: center;"><?= $campo->dias_autorizados ?></td>
                                <td style="text-align: center;">
                                    <a class="btn btn-outline-sm btn-danger" href="javascript:Editar('<?= $campo->id ?>','Detalles', '<?= $campo->nombres . " " . $campo->ape_paterno . " " . $campo->ape_materno ?>')">Ver incapacidades</a>
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