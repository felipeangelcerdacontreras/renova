<?php
/*
 * Copyright 2021 - Felipe angel cerda contreras 
 * felipeangelcerdacontreras@gmail.com
 */
session_start();

$_SITE_PATH = $_SERVER["DOCUMENT_ROOT"] . "/" . explode("/", $_SERVER["PHP_SELF"])[1] . "/";
require_once($_SITE_PATH . "/app/model/asistencia.class.php");

$oAsistencia = new asistencia(true, $_POST);
$oAsistencia->fecha_inicial = date('Y-m-d');
$oAsistencia->fecha_final = date('Y-m-d');
$lstasistencia = $oAsistencia->Listado_asistencia();

?>
<script type="text/javascript">
    $(document).ready(function(e) {
        //$('#dataTable').DataTable();
        $('#dataTable').DataTable({
            searching: false,
            paging: false,
            info: false,
            ordering: false
        });

        $("#btnAgregar").button().click(function(e) {
            Editar("", "Agregar");
        });
        $(".buttons-html5 ").addClass("btn btn-outline-danger");

        $("#frmFormulario").ajaxForm({
            success: function(data) {
                var str = data;
                var datos0 = str.split("@")[0];
                var datos1 = str.split("@")[1];
                var datos2 = str.split("@")[2];
                if ((datos3 = str.split("@")[3]) === undefined) {
                    datos3 = "";
                } else {
                    datos3 = str.split("@")[3];
                }
                Alert(datos0, datos1 + "" + datos3, datos2, 900, false);
                Listado();
                $("#usr").val("");
                $("#usr").focus();
            }
        });
    });
</script>
<!-- DataTales Example -->
<div class="table-responsive">
    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
        <thead>
            <tr>
                <th>Empleado</th>
                <th>Entrada</th>
                <th>Salida</th>
            </tr>
        </thead>
        <tfoot>
            <th>Empleado</th>
            <th>Entrada</th>
            <th>Salida</th>
        </tfoot>
        <tbody>
            <?php
            if (count($lstasistencia) > 0) {
                foreach ($lstasistencia as $idx => $campo) {
            ?>
                    <tr>
                        <td style="text-align: center;"><?= $campo->nombres . " " . $campo->ape_paterno . " " . $campo->ape_materno ?></td>
                        <td style="text-align: center;"><?php if (!empty($campo->hora_entrada)) echo date("h:i:s A", strtotime($campo->hora_entrada)); ?></td>
                        <td style="text-align: center;"><?php if (!empty($campo->hora_salida) && $campo->estatus_salida > 0) echo (date("h:i:s A", strtotime($campo->hora_salida))); ?></td>
                    </tr>
            <?php
                }
            }
            ?>
        </tbody>
    </table>
</div>