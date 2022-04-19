<?php
/*
 * Copyright 2021 - Felipe angel cerda contreras 
 * felipeangelcerdacontreras@gmail.com
 */
session_start();

$_SITE_PATH = $_SERVER["DOCUMENT_ROOT"] . "/" . explode("/", $_SERVER["PHP_SELF"])[1] . "/";
require_once($_SITE_PATH . "/app/model/permisos.class.php");

$oPermisos = new permisos(true, $_POST);
$oPermisos->id = addslashes(filter_input(INPUT_POST, "id"));

$nombre = addslashes(filter_input(INPUT_POST, "nombre"));
$empleado = addslashes(filter_input(INPUT_POST, "empleado"));

$sesion = $_SESSION[$oPermisos->NombreSesion];
$oPermisos->Informacion();

?>
<script type="text/javascript">
    $(document).ready(function(e) {
        
        
    });
</script>
<div>
    <div class="row">
        <div class="col">
            <strong class="">Empleado:</strong>
            <label class=""><?= $empleado ?></label>
        </div>
        <div class="col">
            <strong class="">Tipo de permiso:</strong>
            <label class=""><?php if ($oPermisos->llegada_tarde == 1) {
                                echo "Llegada tarde";
                            } else if ($oPermisos->salida_temprano == 1) {
                                echo "Salida temprano";
                            } else if ($oPermisos->dia_completo) {
                                echo "Dia completo";
                            } ?></label>
        </div>
    </div>
    <div class="row">
        <div class="col">
            <strong class="">Fecha:</strong>
            <label class=""><?= $oPermisos->fecha ?></label>
        </div>
        <div class="col">
            <?php if ($oPermisos->llegada_tarde == 1) { ?>
                <strong class="">Hora:</strong>
                <label class=""> <?php echo $oPermisos->entrada; ?> </label>
            <?php } else if ($oPermisos->salida_temprano == 1) { ?>
                <strong class="">Hora:</strong>
                <label class=""> <?php echo $oPermisos->salida; ?> </label>
            <?php } else if ($oPermisos->dia_completo) { 
            } ?>
        </div>
    </div>
    <div class="row">
        <div class="col">
            <strong class="">Con goce de sueldo:</strong>
            <label class=""><?php if ($oPermisos->sin_sueldo == 0) {
                                echo "No";
                            }else if ($oPermisos->sin_sueldo == 1){
                                echo "Si";
                            } ?></label>
        </div>
    </div>
</div>
<input type="hidden" id="id_" name="id_" value="<?= $oPermisos->id ?>" />
<input type="hidden" id="id_empleado_" name="id_empleado_" value="<?= $oPermisos->id_empleado ?>">