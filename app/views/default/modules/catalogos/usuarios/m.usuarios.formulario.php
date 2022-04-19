<?php
/*
 * Copyright 2021 - Felipe angel cerda contreras 
 * felipeangelcerdacontreras@gmail.com
 */
session_start();

$_SITE_PATH = $_SERVER["DOCUMENT_ROOT"] . "/" . explode("/", $_SERVER["PHP_SELF"])[1] . "/";
require_once($_SITE_PATH . "/app/model/usuarios.class.php");

$oUsuarios = new Usuarios();
$oUsuarios->id = addslashes(filter_input(INPUT_POST, "id"));
$nombre = addslashes(filter_input(INPUT_POST, "nombre"));
$sesion = $_SESSION[$oUsuarios->NombreSesion];
$oUsuarios->Informacion();

$aPermisos = empty($oUsuarios->perfiles_id) ? array() : explode("@", $oUsuarios->perfiles_id);
?>
<script type="text/javascript">
    $(document).ready(function(e) {
        $("#nameModal").text("<?php echo $nombre ?> Usuario");
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
<form id="frmFormulario" name="frmFormulario" action="app/views/default/modules/catalogos/usuarios/m.usuarios.procesa.php" enctype="multipart/form-data" method="post" target="_self" class="form-horizontal">
    <div>
        <div class="row">
            <div class="col">
                <div class="form-group">
                    <strong class="">Nombre:</strong>
                    <div class="form-group">
                        <input type="text" description="Ingrese el nombre" aria-describedby="" id="nombre_usuario" required name="nombre_usuario" value="<?= $oUsuarios->nombre_usuario ?>" class="form-control obligado" />
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="form-group">
                    <strong class="">Usuario:</strong>
                    <div class="form-group">
                        <input type="text" description="Ingrese el usuario" aria-describedby="" id="usuario" required name="usuario" value="<?= $oUsuarios->usuario ?>" class="form-control obligado" />
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col">
                <div class="form-group">
                    <strong class="">Correo:</strong>
                    <div class="form-group">
                        <input type="text" description="Ingrese el correo" aria-describedby="" id="correo" required name="correo" value="<?= $oUsuarios->correo ?>" class="form-control obligado" />
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="form-group">
                    <strong class="">N° Económico:</strong>
                    <div class="form-group">
                        <input type="text" description="" aria-describedby="" id="numero_economico" required name="numero_economico" value="<?= $oUsuarios->numero_economico ?>" class="form-control" />
                    </div>
                </div>
            </div>
        </div>
        <div class="form-group">
            <strong class="">Permisos </strong>
            <div class="row">
                <div class="col">
                    <strong class="">Administrador: </strong><br>
                    <strong class="">Modulos: </strong><br>
                    <input type="checkbox" name="perfiles_id[]" value="recoleccion" <?php if ($oUsuarios->ExistePermiso("recoleccion", $aPermisos) === true) echo "checked" ?>><strong> Recolección</strong><br>
                    <input type="checkbox" name="perfiles_id[]" value="embarque" <?php if ($oUsuarios->ExistePermiso("embarque", $aPermisos) === true) echo "checked" ?>><strong> Embarque y venta(En construccion)</strong><br>
                    <input type="checkbox" name="perfiles_id[]" value="servicio" <?php if ($oUsuarios->ExistePermiso("servicio", $aPermisos) === true) echo "checked" ?>><strong> Servicio(En construccion)</strong><br>
                    <strong class="">Catalogos: </strong><br>
                    <input type="checkbox" name="perfiles_id[]" value="usuarios" <?php if ($oUsuarios->ExistePermiso("usuarios", $aPermisos) === true) echo "checked" ?>><strong> Usuarios</strong><br>
                    <input type="checkbox" name="perfiles_id[]" value="materiales" <?php if ($oUsuarios->ExistePermiso("materiales", $aPermisos) === true) echo "checked" ?>><strong> Materiales</strong><br>
                    <input type="checkbox" name="perfiles_id[]" value="choferes" <?php if ($oUsuarios->ExistePermiso("choferes", $aPermisos) === true) echo "checked" ?>><strong> Choferes</strong><br />
                    <input type="checkbox" name="perfiles_id[]" value="contenedores" <?php if ($oUsuarios->ExistePermiso("contenedores", $aPermisos) === true) echo "checked" ?>><strong> Contenedores</strong><br />
                    <input type="checkbox" name="perfiles_id[]" value="vehiculos" <?php if ($oUsuarios->ExistePermiso("vehiculos", $aPermisos) === true) echo "checked" ?>><strong> Vehiculos</strong><br />
                    <input type="checkbox" name="perfiles_id[]" value="proveedores" <?php if ($oUsuarios->ExistePermiso("proveedores", $aPermisos) === true) echo "checked" ?>><strong> Proveedores</strong><br />
                </div>
                <div class="col">
                    <strong class="">Recursos Humanos: </strong><br>
                    <strong class="">Modulos: </strong><br>
                    <input type="checkbox" name="perfiles_id[]" value="ahorros" <?php if ($oUsuarios->ExistePermiso("ahorros", $aPermisos) === true) echo "checked" ?>><strong> Ahorros</strong><br />
                    <input type="checkbox" name="perfiles_id[]" value="prestamos" <?php if ($oUsuarios->ExistePermiso("prestamos", $aPermisos) === true) echo "checked" ?>><strong> Prestamos</strong><br />
                    <input type="checkbox" name="perfiles_id[]" value="otros" <?php if ($oUsuarios->ExistePermiso("otros", $aPermisos) === true) echo "checked" ?>><strong> Otros Cargos</strong><br />
                    <input type="checkbox" name="perfiles_id[]" value="nomina_comedor" <?php if ($oUsuarios->ExistePermiso("nomina_comedor", $aPermisos) === true) echo "checked" ?>><strong> Comedor</strong><br />
                    <input type="checkbox" name="perfiles_id[]" value="fonacot" <?php if ($oUsuarios->ExistePermiso("fonacot", $aPermisos) === true) echo "checked" ?>><strong> Fonacot</strong><br />
                    <input type="checkbox" name="perfiles_id[]" value="infonavit" <?php if ($oUsuarios->ExistePermiso("infonavit", $aPermisos) === true) echo "checked" ?>><strong> Infonavit</strong><br />
                    <input type="checkbox" name="perfiles_id[]" value="vacaciones" <?php if ($oUsuarios->ExistePermiso("vacaciones", $aPermisos) === true) echo "checked" ?>><strong> Vacaciones</strong><br />
                    <input type="checkbox" name="perfiles_id[]" value="incapacidades" <?php if ($oUsuarios->ExistePermiso("incapacidades", $aPermisos) === true) echo "checked" ?>><strong> Incapacidades</strong><br />
                    <input type="checkbox" name="perfiles_id[]" value="ubicacion_checador" <?php if ($oUsuarios->ExistePermiso("ubicacion_checador", $aPermisos) === true) echo "checked" ?>><strong> Ubicación Checador</strong><br />
                    <strong class="">Catalogos: </strong><br>
                    <input type="checkbox" name="perfiles_id[]" value="departamentos" <?php if ($oUsuarios->ExistePermiso("departamentos", $aPermisos) === true) echo "checked" ?>><strong> Departamentos</strong><br />
                    <input type="checkbox" name="perfiles_id[]" value="puestos" <?php if ($oUsuarios->ExistePermiso("puestos", $aPermisos) === true) echo "checked" ?>><strong> Puestos</strong><br />
                    <input type="checkbox" name="perfiles_id[]" value="empleados" <?php if ($oUsuarios->ExistePermiso("empleados", $aPermisos) === true) echo "checked" ?>><strong> Empleados</strong><br />
                    <input type="checkbox" name="perfiles_id[]" value="horas" <?php if ($oUsuarios->ExistePermiso("horas", $aPermisos) === true) echo "checked" ?>><strong> Horas extras</strong><br />
                    <input type="checkbox" name="perfiles_id[]" value="horarios" <?php if ($oUsuarios->ExistePermiso("horarios", $aPermisos) === true) echo "checked" ?>><strong> Horarios</strong><br />
                    <input type="checkbox" name="perfiles_id[]" value="nominas" <?php if ($oUsuarios->ExistePermiso("nominas", $aPermisos) === true) echo "checked" ?>><strong> Nominas</strong><br />
                    <input type="checkbox" name="perfiles_id[]" value="asistencia" <?php if ($oUsuarios->ExistePermiso("asistencia", $aPermisos) === true) echo "checked" ?>><strong> Asistencia</strong><br />
                    <input type="checkbox" name="perfiles_id[]" value="permisos" <?php if ($oUsuarios->ExistePermiso("permisos", $aPermisos) === true) echo "checked" ?>><strong> Permisos</strong><br />
                    <input type="checkbox" name="perfiles_id[]" value="festivos" <?php if ($oUsuarios->ExistePermiso("festivos", $aPermisos) === true) echo "checked" ?>><strong> Festivos</strong><br />
                </div>
            </div>
        </div>
        <div class="form-group">
            <strong>Nivel del usuario</strong>
            <div class="form-group">
                <select id="nvl_usuario" description="Seleccione el nivel del usuario" class="form-control obligado" name="nvl_usuario" >
                    <option value="">--SELECCIONE--</option>
                    <option value="1" 
                        <?php if ($oUsuarios->nvl_usuario == "1") echo "selected";?>>Administrador</option>
                    <option value="2" 
                       <?php if ($oUsuarios->nvl_usuario == "2") echo "selected";?>>Usuario</option>
                </select>
            </div>
        </div>
        <div class="form-group">
            <strong class="">Contraseña:</strong>
            <div class="form-group">
                <input type="text" description="Ingrese la contraseña" aria-describedby="" id="clave_usuario" required name="clave_usuario" value="" class="form-control" />
            </div>
        </div>
        <input type="hidden" id="id" name="id" value="<?= $oUsuarios->id ?>" />
        <input type="hidden" id="user_id" name="user_id" value="<?= $sesion->id ?>">
        <input type="hidden" id="accion" name="accion" value="GUARDAR" />
    </div>
</form>