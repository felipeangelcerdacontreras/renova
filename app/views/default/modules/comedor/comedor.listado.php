<?php
/*
 * Copyright 2021 - Felipe angel cerda contreras 
 * felipeangelcerdacontreras@gmail.com
 */
session_start();

$_SITE_PATH = $_SERVER["DOCUMENT_ROOT"] . "/" . explode("/", $_SERVER["PHP_SELF"])[1] . "/";
require_once($_SITE_PATH . "/app/model/comedor.class.php");

$oComedor = new comedor(true, $_POST);
$oComedor->fecha_inicial = date('Y-m-d');
$oComedor->fecha_final = date('Y-m-d');
$lstcomedor = $oComedor->Listado_comedor();

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
                <th>Platillo</th>
            </tr>
        </thead>
        <tfoot>
            <th>Empleado</th>
            <th>Platillo</th>
        </tfoot>
        <tbody>
            <?php
            if (count($lstcomedor) > 0) {
                foreach ($lstcomedor as $idx => $campo) {
            ?>
                    <tr>
                        <td style="text-align: center;"><?= $campo->nombres . " " . $campo->ape_paterno . " " . $campo->ape_materno ?></td>
                        <td style="text-align: center;"><?= $campo->precio_platillo ?></td>
                    </tr>
            <?php
                }
            }
            ?>
        </tbody>
    </table>
</div>