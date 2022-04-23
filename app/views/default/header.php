<?php
$_SITE_PATH = $_SERVER["DOCUMENT_ROOT"] . "/" . explode("/", $_SERVER["PHP_SELF"])[1] . "/";
require_once($_SITE_PATH . "/app/model/usuarios.class.php");
require_once($_SITE_PATH . "/app/model/principal.class.php");
require_once($_SITE_PATH . "/app/model/nominas.class.php");
require_once($_SITE_PATH . "/app/model/nominas.fiscal.class.php");


$oConfig = new Configuracion();

$sesion = $_SESSION[$oConfig->NombreSesion];
//echo($_SERVER['HTTP_USER_AGENT']);
$oUsuario = new usuarios();
$oUsuario->usr_id = $sesion->id;
$oUsuario->Informacion();

$oNominas = new nominas(true, $_POST);
$lstNominas = $oNominas->Listado_peticiones();
$count = count($lstNominas);

$oNominasF = new nominas_fiscal(true, $_POST);
$lstNominasF = $oNominasF->Listado_peticiones();
$count = $count + count($lstNominasF);
?>
<script>
    $(document).ready(function(e) {

    });

    function Solictud(id) {
        $.ajax({
            data: "id=" + id,
            type: "POST",
            url: "app/views/default/notificaciones.formulario.php",
            beforeSend: function() {
                $("#oNominas").html(
                    '<div class="container"><center><img src="app/views/default/img/loading.gif" border="0"/><br />Cargando formulario, espere un momento por favor...</center></div>'
                );
            },
            success: function(datos) {
                console.log(datos);
                $("#modal-body-nomina").html(datos);
            }
        });
        $("#btnGuardar").show();
        $("#nominasModal").modal({
            backdrop: "true"
        });
    }

    function AprovarDenegar(id, estatus) {
        $.ajax({
            data: "accion=AprovarDenegar&id=" + id + "&estatus=" + estatus,
            type: "POST",
            url: "app/views/default/modules/modulos/nominas/m.nominas.procesa.php",
            beforeSend: function() {},
            success: function(data) {
                console.log(data);
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
                $("#nominasModal").modal("hide");
                setTimeout(function() {
                    window.location.reload(1);
                }, 1000);
            }
        });
    }
</script>
<style>
    .topbar .dropdown-list .dropdown-header {
        background-color: #e7002f !important;
        border: 1px solid #dd0d0d !important;
    }
</style>
<!-- Topbar -->
<nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">

    <!-- Sidebar Toggle (Topbar) -->
    <button id="sidebarToggleTop" class="btn btn-outline-link d-md-none rounded-circle mr-3">
        <i class="fa fa-bars"></i>
    </button>

    <!-- Topbar Navbar -->
    <ul class="navbar-nav ml-auto">
        <!-- Nav Item - Alerts -->
        <?php if($sesion->nvl_usuario == 1) {?>
        <li class="nav-item dropdown no-arrow mx-1">
            <a class="nav-link dropdown-toggle" href="#" id="alertsDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class="fas fa-bell fa-fw"></i>
                <!-- Counter - Alerts -->
                <span class="badge badge-danger badge-counter"><?= $count ?></span>
            </a>
            <!-- Dropdown - Alerts -->
            <div class="dropdown-list dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="alertsDropdown">
                <h6 class="dropdown-header">
                    Solicitudes de modificacion de nomina.
                </h6>
                <?php if (count($lstNominasF) > 0) {
                    foreach ($lstNominasF as $idx => $campo) {
                        echo "
                            <a class='dropdown-item d-flex align-items-center' href='javascript:Solictud($campo->id)'>
                                <div class='mr-3'>
                                    <div class='icon-circle bg-success'>
                                        <i class='fas fa-donate text-white'></i>
                                    </div>
                                </div>
                                <div>
                                    <div class='small text-gray-500'>Nomina a pagar el: $campo->fecha</div>
                                    Solicitud para modificar la nomina de: $campo->nombre
                                </div>
                            </a>
                            ";
                    }
                } else {
                    echo "<a class='dropdown-item text-center small text-gray-500'>Sin solicitudes de nomina fiscal</a>";
                }?>
                <?php if (count($lstNominas) > 0) {
                    foreach ($lstNominas as $idx => $campo) {
                        echo "
                            <a class='dropdown-item d-flex align-items-center' href='javascript:Solictud($campo->id)'>
                                <div class='mr-3'>
                                    <div class='icon-circle bg-success'>
                                        <i class='fas fa-donate text-white'></i>
                                    </div>
                                </div>
                                <div>
                                    <div class='small text-gray-500'>Nomina a pagar el: $campo->fecha</div>
                                    Solicitud para modificar la nomina de: $campo->nombre
                                </div>
                            </a>
                            ";
                    }
                } else {
                    echo "<a class='dropdown-item text-center small text-gray-500'>Sin solicitudes</a>";
                }?>
            </div>
        </li>
        <?php } ?>
        <div class="topbar-divider d-none d-sm-block"></div>

        <!-- Nav Item - User Information -->
        <li class="nav-item dropdown no-arrow">
            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <span class="mr-2 d-none d-lg-inline text-gray-600 small"> <?= $sesion->nombre_usuario ?></span>
                <img class="img-profile rounded-circle" src="app/views/default/img/profile.jpg">
            </a>
            <!-- Dropdown - User Information -->
            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="userDropdown">
                <div class="dropdown-divider"></div>
                <a class="dropdown-item" href="#" data-toggle="modal" data-target="#logoutModal">
                    <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                    Cerrar sesión
                </a>
            </div>
        </li>
    </ul>
</nav>
<!-- End of Topbar -->
<!-- Logout Modal-->
<div class="modal fade" id="myModal_" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">

                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <!-- contenido del modal-->

            </div>
            <div class="modal-footer">

                <input type="submit" class="btn btn-outline-success" id="btnGuardar_" value="Guardar">
                <button class="btn btn-outline-secondary" type="button" data-dismiss="modal">Cancel</button>
            </div>
        </div>
    </div>
</div>
<!-- Modal -->
<!--MODAL DE SALIDA-->
<!-- Logout Modal-->
<div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">¿Listo para salir?</h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">Seleccione "Cerrar sesión" a continuación si está listo para finalizar su sesión
                actual.</div>
            <div class="modal-footer">
                <button class="btn btn-outline-secondary" type="button" data-dismiss="modal">Cancel</button>
                <a class="btn btn-outline-primary" href="index.php?action=cerrar_sesion">Cerrar sesión</a>
            </div>
        </div>

    </div>
</div>

<!-- Logout Modal-->
<div class="modal fade bd-example-modal-lg" id="nominasModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Aprobar solicitud</h5>
            </div>
            <div class="modal-body" id="modal-body-nomina">

            </div>
            <div class="modal-footer">
                <button class="btn btn-outline-secondary" type="button" data-dismiss="modal">Cerrar</button>
            </div>
        </div>

    </div>
</div>