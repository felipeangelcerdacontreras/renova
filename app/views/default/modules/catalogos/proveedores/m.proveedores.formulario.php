<?php
/*
 * Copyright 2021 - Felipe angel cerda contreras 
 * felipeangelcerdacontreras@gmail.com
 */
session_start();

$_SITE_PATH = $_SERVER["DOCUMENT_ROOT"] . "/" . explode("/", $_SERVER["PHP_SELF"])[1] . "/";
require_once($_SITE_PATH . "/app/model/proveedores.class.php");
require_once($_SITE_PATH . "/app/model/puestos.class.php");

$oProveedores = new proveedores();
$oProveedores->id = addslashes(filter_input(INPUT_POST, "id"));
$nombre = addslashes(filter_input(INPUT_POST, "nombre"));
$sesion = $_SESSION[$oProveedores->NombreSesion];
$oProveedores->Informacion();

$oPuestos = new puestos();
$lstpuestos = $oPuestos->Listado();

?>
<script type="text/javascript">
    $(document).ready(function(e) {
        $("#nameModal").text("<?php echo $nombre ?> Proveedor");
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
        $('[data-toggle="tooltip"]').tooltip();
    });
</script>
<form id="frmFormulario" name="frmFormulario" action="app/views/default/modules/catalogos/proveedores/m.proveedores.procesa.php" enctype="multipart/form-data" method="post" target="_self" class="form-horizontal">
    <div>
        <div class="row">
            <div class="col">
                <div class="form-group">
                    <strong class="">Alias:</strong>
                    <div class="form-group">
                        <input type="text" description="Ingrese el alias" aria-describedby="" id="alias" required name="alias" value="<?= $oProveedores->alias ?>" class="form-control obligado" />
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="form-group">
                    <strong class="">Nombre:</strong>
                    <div class="form-group">
                        <input type="text" description="Ingrese el nombre" aria-describedby="" id="nombre" required name="nombre" value="<?= $oProveedores->nombre ?>" class="form-control obligado" />
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col">
                <div class="form-group">
                    <strong class="">Calle:</strong>
                    <div class="form-group">
                        <input type="text" description="Ingrese la calle" aria-describedby="" id="Calle" required name="Calle" value="<?= $oProveedores->Calle ?>" class="form-control obligado" />
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="form-group">
                    <strong class="">Numero:</strong>
                    <div class="form-group">
                        <input type="text" description="Ingrese el numero" aria-describedby="" id="Numero" required name="Numero" value="<?= $oProveedores->Numero ?>" class="form-control obligado" />
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col">
                <div class="form-group">
                    <strong class="">Colonia:</strong>
                    <div class="form-group">
                        <input type="text" description="Ingrese la colonia" aria-describedby="" id="Colonia" required name="Colonia" value="<?= $oProveedores->Colonia ?>" class="form-control obligado" />
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="form-group">
                    <strong class="">Municipio:</strong>
                    <div class="form-group">
                        <input type="text" description="Ingrese el municipio" aria-describedby="" id="Municipio" required name="Municipio" value="<?= $oProveedores->Municipio ?>" class="form-control obligado" />
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col">
                <div class="form-group">
                    <strong class="">Estado:</strong>
                    <div class="form-group">
                        <input type="text" description="Ingrese el estado" aria-describedby="" id="Estado" required name="Estado" value="<?= $oProveedores->Estado ?>" class="form-control obligado" />
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="form-group">
                    <strong class="">CP:</strong>
                    <div class="form-group">
                        <input type="text" description="Ingrese el CP" aria-describedby="" id="CP" required name="CP" value="<?= $oProveedores->CP ?>" class="form-control obligado" />
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col">
                <div class="form-group">
                    <strong class="">RFC:</strong>
                    <div class="form-group">
                        <input type="text" description="Ingrese el RFC" aria-describedby="" id="RFC" required name="RFC" value="<?= $oProveedores->RFC ?>" class="form-control obligado" />
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="form-group">
                    <strong class="">Telefono:</strong>
                    <div class="form-group">
                        <input type="text" description="Ingrese el telefono" aria-describedby="" id="Telefono" required name="Telefono" value="<?= $oProveedores->Telefono ?>" class="form-control obligado" />
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col">
                <div class="form-group">
                    <strong class="">Fecha Nacimiento:</strong>
                    <div class="form-group">
                        <input type="date" description="Seleccione la fecha de nacimiento" aria-describedby="" id="Fecha_Nac" required name="Fecha_Nac" value="<?= $oProveedores->Fecha_Nac ?>" class="form-control obligado" />
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="form-group">
                    <strong class="">Observaciones:</strong>
                    <div class="form-group">
                       <textarea class="form-control" id="observaciones" required name="observaciones" ><?= $oProveedores->observaciones ?></textarea>
                    </div>
                </div>
            </div>
        </div>
        <input type="hidden" id="id" name="id" value="<?= $oProveedores->id ?>" />
        <input type="hidden" id="user_id" name="user_id" value="<?= $sesion->id ?>">
        <input type="hidden" id="accion" name="accion" value="GUARDAR" />
    </div>
</form>