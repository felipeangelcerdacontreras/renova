<?php
$_SITE_PATH = $_SERVER["DOCUMENT_ROOT"] . "/" . explode("/", $_SERVER["PHP_SELF"])[1] . "/";
require_once($_SITE_PATH . "/Configuracion.class.php");
require_once($_SITE_PATH . "/app/model/usuarios.class.php");

$oConfig = new Configuracion();

$sesion = $_SESSION[$oConfig->NombreSesion];
//print_r($sesion);
$oUsuario = new usuarios();
$oUsuario->id = $sesion->id;
$oUsuario->Informacion();

$aPermisos = empty($oUsuario->perfiles_id) ? array() : explode("@", $oUsuario->perfiles_id);
?>
<script>
    $(document).ready(function(e) {
        $('#empleados').attr('href', "index.php?action=empleados&token=" + localStorage.getItem("srnPc"));
    });
</script>
<!-- Sidebar -->
<ul class="navbar-nav bg-gradient-danger sidebar sidebar-dark accordion" id="accordionSidebar">

    <!-- Sidebar - Brand -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="index.php?action=bienvenida">
        <div class="sidebar-brand-icon rotate-n-15">
            <img src="app/views/default/img/icon.png" style="width: 116%;">
        </div>
        <div class="sidebar-brand-text mx-3"><sup></sup></div>
    </a>

    <!-- Divider -->
    <hr class="sidebar-divider my-0">

    <hr class="sidebar-divider">
    <!-- Heading -->

    <!-- Nav Item - Pages Collapse Menu -->
    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="true" aria-controls="collapseTwo">
            <i class="fas fa-fw fa-cog"></i>
            <span>Catalogós</span>
        </a>

        <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <?php if ($oUsuario->ExistePermiso("usuarios", $aPermisos) === true) { ?>
                    <a class='collapse-item' href='index.php?action=usuarios'>Usuarios</a>
                <?php } ?>
                <?php if ($oUsuario->ExistePermiso("choferes", $aPermisos) === true) { ?>
                    <a class='collapse-item' href='index.php?action=choferes'>Choferes</a>
                <?php } ?>
                <?php if ($oUsuario->ExistePermiso("contenedores", $aPermisos) === true) { ?>
                    <a class='collapse-item' href='index.php?action=contenedores'>Contenedores</a>
                <?php } ?>
                <?php if ($oUsuario->ExistePermiso("vehiculos", $aPermisos) === true) { ?>
                    <a class='collapse-item' href='index.php?action=vehiculos'>Vehiculos</a>
                <?php } ?>
                <?php if ($oUsuario->ExistePermiso("departamentos", $aPermisos) === true) { ?>
                    <a class='collapse-item' href='index.php?action=departamentos'>Departamentos</a>
                <?php } ?>
                <?php if ($oUsuario->ExistePermiso("puestos", $aPermisos) === true) { ?>
                    <a class='collapse-item' href='index.php?action=puestos'>Puestos</a>
                <?php } ?>
                <?php if ($oUsuario->ExistePermiso("horarios", $aPermisos) === true) { ?>
                    <a class='collapse-item' href='index.php?action=horarios'>Horarios</a>
                <?php } ?>
                <?php if ($oUsuario->ExistePermiso("horas", $aPermisos) === true) { ?>
                    <a class='collapse-item' href='index.php?action=horas'>Horas extras</a>
                <?php } ?>
                <?php if ($oUsuario->ExistePermiso("empleados", $aPermisos) === true) { ?>
                    <a class='collapse-item' id="empleados">Empleados</a>
                <?php } ?>
                <?php if ($oUsuario->ExistePermiso("festivos", $aPermisos) === true) { ?>
                    <a class='collapse-item' href='index.php?action=festivos'>Festivos</a>
                <?php } ?>
                <?php if ($oUsuario->ExistePermiso("proveedores", $aPermisos) === true) { ?>
                    <a class='collapse-item' href='index.php?action=proveedores'>Proveedores</a>
                <?php } ?>
                <?php if ($oUsuario->ExistePermiso("materiales", $aPermisos) === true) { ?>
                    <a class='collapse-item' href='index.php?action=materiales'>Materiales</a>
                <?php } ?>
                <?php if ($oUsuario->ExistePermiso("ubicacion_checador", $aPermisos) === true) { ?>
                    <a class='collapse-item' href='index.php?action=ubicacion_checador'>Ubicación Checador</a>
                <?php } ?>
            </div>
        </div>
    </li>
    <!-- Divider -->
    <hr class="sidebar-divider">

    <!-- Nav Item - Pages Collapse Menu -->
    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapsePages" aria-expanded="true" aria-controls="collapsePages">
            <i class="fas fa-fw fa-folder"></i>
            <span>Modulos</span>
        </a>
        <div id="collapsePages" class="collapse" aria-labelledby="headingPages" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <?php if ($oUsuario->ExistePermiso("nominas", $aPermisos) === true) { ?>
                    <a class='collapse-item' href='index.php?action=nominas'>Nominas</a>
                <?php } ?>
                <?php if ($oUsuario->ExistePermiso("asistencia", $aPermisos) === true) { ?>
                    <a class='collapse-item' href='index.php?action=asistencia'>Asistencia</a>
                <?php } ?>
                <?php if ($oUsuario->ExistePermiso("permisos", $aPermisos) === true) { ?>
                    <a class='collapse-item' href='index.php?action=permisos'>Permisos</a>
                <?php } ?>
                <?php if ($oUsuario->ExistePermiso("ahorros", $aPermisos) === true) { ?>
                    <a class='collapse-item' href='index.php?action=ahorros'>Ahorros</a>
                <?php } ?>
                <?php if ($oUsuario->ExistePermiso("prestamos", $aPermisos) === true) { ?>
                    <a class='collapse-item' href='index.php?action=prestamos'>Prestamos</a>
                <?php } ?>
                <?php if ($oUsuario->ExistePermiso("otros", $aPermisos) === true) { ?>
                    <a class='collapse-item' href='index.php?action=otros'>Otros Cargos</a>
                <?php } ?>
                <?php if ($oUsuario->ExistePermiso("fonacot", $aPermisos) === true) { ?>
                    <a class='collapse-item' href='index.php?action=fonacot'>Fonacot</a>
                <?php } ?>
                <?php if ($oUsuario->ExistePermiso("infonavit", $aPermisos) === true) { ?>
                    <a class='collapse-item' href='index.php?action=infonavit'>Infonavit</a>
                <?php } ?>
                <?php if ($oUsuario->ExistePermiso("vacaciones", $aPermisos) === true) { ?>
                    <a class='collapse-item' href='index.php?action=vacaciones'>Vacaciones</a>
                <?php } ?>
                <?php if ($oUsuario->ExistePermiso("incapacidades", $aPermisos) === true) { ?>
                    <a class='collapse-item' href='index.php?action=incapacidades'>Incapacidades</a>
                <?php } ?>
                <?php if ($oUsuario->ExistePermiso("recoleccion", $aPermisos) === true) { ?>
                    <a class='collapse-item' href='index.php?action=recoleccion'>Recolección</a>
                <?php } ?>
                <?php if ($oUsuario->ExistePermiso("embarque", $aPermisos) === true) { ?>
                    <a class='collapse-item' href='index.php?action=embarque'>Embarque y venta</a>
                <?php } ?>
                <?php if ($oUsuario->ExistePermiso("servicio", $aPermisos) === true) { ?>
                    <a class='collapse-item' href='index.php?action=servicio'>Servicio</a>
                <?php } ?>
                <?php if ($oUsuario->ExistePermiso("nomina_comedor", $aPermisos) === true) { ?>
                    <a class='collapse-item' href='index.php?action=nomina_comedor'>Comedor</a>
                <?php } ?>
            </div>
        </div>
    </li>
    <!-- Divider -->
    <hr class="sidebar-divider d-none d-md-block">

    <!-- Sidebar Toggler (Sidebar) -->
    <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
    </div>

</ul>
<!-- End of Sidebar -->
<!-- ////////////////////////////////////////////////////////////////////////////////////////////menu lateral izquierdo-->