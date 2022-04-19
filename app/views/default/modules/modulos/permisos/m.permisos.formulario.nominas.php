<?php
/*
 * Copyright 2021 - Felipe angel cerda contreras 
 * felipeangelcerdacontreras@gmail.com
 */
session_start();

$_SITE_PATH = $_SERVER["DOCUMENT_ROOT"] . "/" . explode("/", $_SERVER["PHP_SELF"])[1] . "/";
require_once($_SITE_PATH . "/app/model/usuarios.class.php");
require_once($_SITE_PATH . "/app/model/empleados.class.php");

$oUsuarios = new Usuarios();
$oUsuarios->id = addslashes(filter_input(INPUT_POST, "id"));
$nombre = addslashes(filter_input(INPUT_POST, "nombre"));
$sesion = $_SESSION[$oUsuarios->NombreSesion];
$oUsuarios->Informacion();

$oEmpleados = new empleados();
$lstEmpleados = $oEmpleados->Listado();

$aPermisos = empty($oUsuarios->perfiles_id) ? array() : explode("@", $oUsuarios->perfiles_id);
?>
<script type="text/javascript">
    $(document).ready(function(e) {
        $("#nameModal_").text("<?php echo $nombre ?> Permiso");
        $("#entrada1").hide();
        $("#salida1").hide();
        $("#frmFormulario_").ajaxForm({
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
                $("#myModal_permisos").modal("hide");
            }
        });
        $('#id_empleado').select2({
            width: '100%'
        });
    });
    $('input[type="checkbox"]').on('change', function() {
        $("#entrada1").hide();
        $("#salida1").hide();
        $("#entrada").removeClass("obligado");
        $("#salida").removeClass("obligado");
        var elemento= this;
        var id = $(elemento).attr("description");
        var idImput = $(elemento).attr("description1");
        $("#"+idImput).addClass("obligado");
        $("#"+id).show();
        $('input[type="checkbox"]').not(this).prop('checked', false);
    });
    
</script>
<form id="frmFormulario_" name="frmFormulario_" action="app/views/default/modules/modulos/permisos/m.permisos.procesa.php" enctype="multipart/form-data" method="post" target="_self" class="form-horizontal">
    <div>
        <div class="row">
            <div class="col">
                <div class="form-group">
                    <strong class="">Empleado:</strong>
                    <div class="form-group">
                        <select id="id_empleado" description="Seleccione el empleado" class="form-control obligado" name="id_empleado">
                            <?php
                            if (count($lstEmpleados) > 0) {
                                echo "<option value='0' >-- SELECCIONE --</option>\n";
                                foreach ($lstEmpleados as $idx => $campo) {
                                    echo "<option value='{$campo->id}' >" . $campo->nombres . " " . $campo->ape_paterno . " " . $campo->ape_materno . "</option>\n";
                                }
                            }
                            ?>
                        </select>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col">
                <div class="form-group">
                    <strong class="">Fecha del permiso:</strong>
                    <div class="form-group">
                        <input type="date" description="Seleccione la fecha" aria-describedby="" id="fecha" required name="fecha" class="form-control obligado" />
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="form-group">
                <strong>&nbsp; Con goce de sueldo:</strong>
                <label> Si</label> <input type="radio" name="sin_sueldo" class="check1" value="1">
                <label> No</label> <input type="radio" name="sin_sueldo" class="check1" value="0">
            </div>
        </div>
        <div class="form-group">
                <strong class="">Tipo de permiso:</strong>
            </div>
        <div class="row">
            <div class="col">
                <input type="checkbox" name="llegada_tarde" description="entrada1" description1="entrada" value="1" class="check"><strong> Entrada Tarde</strong><br />
            </div>
            <div class="col">
                <input type="checkbox" name="salida_temprano" description="salida1" description1="salida" value="1" class="check"><strong> Salida Temprano</strong><br />
            </div>
            <div class="col">
                <input type="checkbox" name="dia_completo" value="1" class="check"><strong> Día completo</strong><br />
            </div>
        </div>
        <div class="row">
            <div class="col">
                <div class="form-group" id="entrada1">
                    <strong class="">Hora de entrada:</strong>
                    <div class="form-group">
                        <input type="time" description="Seleccione la hora de entrada" aria-describedby="" id="entrada" required name="entrada" class="form-control" />
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="form-group" id="salida1">
                    <strong class="">Hora de salida:</strong>
                    <div class="form-group">
                        <input type="time" description="Seleccione la hora de salida" aria-describedby="" id="salida" required name="salida" class="form-control" />
                    </div>
                </div>
            </div>
        </div>
    </div>
    <input type="hidden" id="id" name="id" value="<?= $oUsuarios->id ?>" />
    <input type="hidden" id="user_id" name="user_id" value="<?= $sesion->id ?>">
    <input type="hidden" id="accion" name="accion" value="GUARDAR" />
    </div>
</form>