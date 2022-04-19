<?php
/*
 * Copyright 2021 - Felipe angel cerda contreras 
 * felipeangelcerdacontreras@gmail.com
 */
session_start();

$_SITE_PATH = $_SERVER["DOCUMENT_ROOT"] . "/" . explode("/", $_SERVER["PHP_SELF"])[1] . "/";
require_once($_SITE_PATH . "/app/model/puestos.class.php");
require_once($_SITE_PATH . "/app/model/departamentos.class.php");

$oPuestos = new puestos();
$oPuestos->id = addslashes(filter_input(INPUT_POST, "id"));
$nombre = addslashes(filter_input(INPUT_POST, "nombre"));
$sesion = $_SESSION[$oPuestos->NombreSesion];
$oPuestos->Informacion();

$oDepartamentos = new departamentos();
$oDepartamentos->form = '1';
$lstdepartamentos = $oDepartamentos->Listado();

?>
<script type="text/javascript">
    $(document).ready(function(e) {
        $("#nameModal").text("<?php echo $nombre ?> Puesto");
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
        $('#id_departamento').select2({ width: '100%' }); 
    });
</script>
<form id="frmFormulario" name="frmFormulario" action="app/views/default/modules/catalogos/puestos/m.puestos.procesa.php" enctype="multipart/form-data" method="post" target="_self" class="form-horizontal">
    <div>
        <div class="form-group">
            <strong class="">Nombre:</strong>
            <div class="form-group">
                <input type="text" description="Ingrese el nombre" aria-describedby="" id="nombre" required name="nombre" value="<?= $oPuestos->nombre ?>" class="form-control obligado" />
            </div>
        </div>
        <div class="form-group">
            <strong class="">Departamento:</strong>
            <div class="form-group">
                <select id="id_departamento" description="Seleccione el departamneto" class="form-control obligado" name="id_departamento">
                    <?php
                    if (count($lstdepartamentos) > 0) {
                        echo "<option value='0' >-- SELECCIONE --</option>\n";
                        foreach ($lstdepartamentos as $idx => $campo) {
                            if ($campo->id == $oPuestos->id_departamento) {
                                echo "<option value='{$campo->id}' selected>{$campo->nombre}</option>\n";
                            } else {
                                echo "<option value='{$campo->id}' >{$campo->nombre}</option>\n";
                            }
                        }
                    }
                    ?>
                </select>
            </div>
        </div>
        <input type="hidden" id="id" name="id" value="<?= $oPuestos->id ?>" />
        <input type="hidden" id="user_id" name="user_id" value="<?= $sesion->id ?>">
        <input type="hidden" id="accion" name="accion" value="GUARDAR" />
    </div>
</form>