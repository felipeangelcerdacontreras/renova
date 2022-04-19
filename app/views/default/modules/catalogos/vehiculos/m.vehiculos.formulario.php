<?php
/*
 * Copyright 2021 - Felipe angel cerda contreras 
 * felipeangelcerdacontreras@gmail.com
 */
session_start();

$_SITE_PATH = $_SERVER["DOCUMENT_ROOT"] . "/" . explode("/", $_SERVER["PHP_SELF"])[1] . "/";
require_once($_SITE_PATH . "/app/model/vehiculos.class.php");

$oVehiculos = new vehiculos();
$oVehiculos->id = addslashes(filter_input(INPUT_POST, "id"));
$nombre = addslashes(filter_input(INPUT_POST, "nombre"));
$sesion = $_SESSION[$oVehiculos->NombreSesion];
$oVehiculos->Informacion();

?>
<script type="text/javascript">
    $(document).ready(function(e) {
        $("#nameModal").text("<?php echo $nombre ?> Vehiculo");
        $("#frmFormulario").ajaxForm({
            beforeSubmit: function(formData, jqForm, options) {},
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
                Alert(datos0, datos1 + "" + datos3, datos2);
                Listado();
                $("#myModal_1").modal("hide");
            }
        });
    });
</script>
<form id="frmFormulario" name="frmFormulario" action="app/views/default/modules/catalogos/vehiculos/m.vehiculos.procesa.php" enctype="multipart/form-data" method="post" target="_self" class="form-horizontal">
    <div>
        <div class="form-group">
            <div class="row">
                <div class="col">
                    <div class="form-group">
                        <strong class="">Nombre:</strong>
                        <div class="form-group">
                            <input type="text" description="Ingrese el nombre" aria-describedby="" id="nombre" required name="nombre" value="<?= $oVehiculos->nombre ?>" class="form-control obligado" />
                        </div>
                    </div>
                </div>
                <div class="col">
                    <div class="form-group">
                        <strong class="">Placa:</strong>
                        <div class="form-group">
                            <input type="text" description="Ingrese la placa" aria-describedby="" id="placa" required name="placa" value="<?= $oVehiculos->placa ?>" class="form-control obligado" />
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="form-group">
            <div class="row">
                <div class="col">
                    <div class="form-group">
                        <strong class="">Año:</strong>
                        <div class="form-group">
                            <input type="text" description="Ingrese el año" aria-describedby="" id="ano" required name="ano" value="<?= $oVehiculos->ano ?>" class="form-control obligado" />
                        </div>
                    </div>
                </div>
                <div class="col">
                    <div class="form-group">
                        <strong class="">Marca:</strong>
                        <div class="form-group">
                            <input type="text" description="Ingrese la marca" aria-describedby="" id="marca" required name="marca" value="<?= $oVehiculos->marca ?>" class="form-control obligado" />
                        </div>
                    </div>
                </div>
            </div>
        </div>

        
        <input type="hidden" id="id" name="id" value="<?= $oVehiculos->id ?>" />
        <input type="hidden" id="user_id" name="user_id" value="<?= $sesion->id ?>">
        <input type="hidden" id="accion" name="accion" value="GUARDAR" />
    </div>
</form>