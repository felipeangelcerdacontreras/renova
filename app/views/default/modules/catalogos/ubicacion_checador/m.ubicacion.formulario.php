<?php
/*
 * Copyright 2021 - Felipe angel cerda contreras 
 * felipeangelcerdacontreras@gmail.com
 */
session_start();

$_SITE_PATH = $_SERVER["DOCUMENT_ROOT"] . "/" . explode("/", $_SERVER["PHP_SELF"])[1] . "/";
require_once($_SITE_PATH . "/app/model/ubicacion.class.php");
require_once($_SITE_PATH . "/app/model/empleados.class.php");

$oUbicacion = new ubicacion();
$oUbicacion->id = addslashes(filter_input(INPUT_POST, "id"));
$nombre = addslashes(filter_input(INPUT_POST, "nombre"));
$sesion = $_SESSION[$oUbicacion->NombreSesion];
$oUbicacion->Informacion();

$oEmpleados = new empleados();
$lstEmpleados = $oEmpleados->Listado();
?>
<script type="text/javascript">
    $(document).ready(function(e) {
        $("#nameModal").text("<?php echo $nombre ?> ubicacion checador");
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
        $('#id_empleado').select2({
            width: '100%'
        });
    });
     function obtenerCoordenadas() {
        if (navigator.geolocation) {
            var success = function(position) {
                $("#lat").val(position.coords.latitude);
                $("#lon").val(position.coords.longitude);                
            }
            navigator.geolocation.getCurrentPosition(success, function(msg) {
                console.error(msg);
            });
        }
     }
   
</script>
<script type="text/javascript" src="http://maps.google.com/maps/api/js?key=AIzaSyDNuQjcMaL880tNTT_rY6X3G6DhiMqSDFw&libraries=places"></script>
<form id="frmFormulario" name="frmFormulario" action="app/views/default/modules/catalogos/ubicacion_checador/m.ubicacion.procesa.php" enctype="multipart/form-data" method="post" target="_self" class="form-horizontal">
    <div>
        <div class="form-group">
            <strong class="">Lugar:</strong>
            <div class="form-group">
                <input type="text" description="Ingrese las ubicacion a registrar" class="form-control obligado" autocomplete="off" aria-describedby="" id="nombre" required name="nombre" value="<?= $oUbicacion->nombre ?>" />
            </div>
        </div>
        <div class="form-group">
            <input id="activeSensorLocal" onclick="obtenerCoordenadas()" style="margin-top: 5px;" type="button" value="Obtener coordenadas" class="btn btn-outline-primary btn-block">
        </div>
        <div class="form-group">
            <strong class="">Latitude:</strong>
            <div class="form-group">
                <input type="text" readonly description="Falta la latitude" class="form-control obligado" aria-describedby="" id="lat" required name="lat" value="<?= $oUbicacion->lat ?>" />
            </div>
        </div>
        <div class="form-group">
            <strong class="">Longitude:</strong>
            <div class="form-group">
            <input type="text" readonly description="Falta la longitude" class="form-control obligado" aria-describedby="" id="lon" required name="lon" value="<?= $oUbicacion->lon ?>" />
            </div>
        </div>
        <input type="hidden" id="id" name="id" value="<?= $oUbicacion->id ?>" />
        <input type="hidden" id="user_id" name="user_id" value="<?= $sesion->id ?>">
        <input type="hidden" id="accion" name="accion" value="GUARDAR" />
    </div>
</form>