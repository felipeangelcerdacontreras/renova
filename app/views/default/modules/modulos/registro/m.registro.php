<?php
/*
 * Copyright 2021 - Felipe angel cerda contreras 
 * felipeangelcerdacontreras@gmail.com
 */
$_SITE_PATH = $_SERVER["DOCUMENT_ROOT"] . "/" . explode("/", $_SERVER["PHP_SELF"])[1] . "/";
require_once($_SITE_PATH . "/app/model/registro.class.php");

$oRegistro = new registro();
$sesion = $_SESSION[$oRegistro->NombreSesion];

$lstRegistro = $oRegistro->Listado();


?>
<?php require_once('app/views/default/script_h.html'); ?>
<script type="text/javascript">
$(document).ready(function(e) {
    Listado();
    $("#btnGuardar").button().click(function(e) {
        if ($("#reg_cliente").val() === "" || $("#reg_lieneataxi").val() === "" || $("#reg_marca").val() === "0") {
            Alert("", "Llene todos los campos porfavor", "warning");
        } else {
            $("#frmFormulario").submit();
        }
    });
    $("#btnBuscar").button().click(function(e) {
        Listado();
    });

});

function Listado() {
    var jsonDatos = {
        "accion": "BUSCAR"
    };
    $.ajax({
        data: jsonDatos,
        type: "POST",
        url: "app/views/default/modules/modulos/registro/m.registro.listado.php",
        beforeSend: function() {
            $("#divListado").html(
                '<div class="container"><center><img src="app/views/default/img/loading.gif" border="0"/><br />Leyendo información de la Base de Datos, espere un momento por favor...</center></div>'
            );
        },
        success: function(datos) {
            $("#divListado").html(datos);
        }
    });
}

function Editar(reg_id) {
    $.ajax({
        data: "reg_id=" + reg_id,
        type: "POST",
        url: "app/views/default/modules/modulos/registro/m.registro.formulario.php",
        beforeSend: function() {
            $("#divFormulario").html(
                '<div class="container"><center><img src="app/views/default/img/loading.gif" border="0"/><br />Cargando formulario, espere un momento por favor...</center></div>'
            );
        },
        success: function(datos) {
            $("#divFormulario").html(datos);
        }
    });
    $("#myModal_1").modal({
        backdrop: "true"
    });
}
</script>

<?php require_once('app/views/default/link.html'); ?>

<head>
    <?php require_once('app/views/default/head.html'); ?>
    <title>Registro</title>
</head>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">
        <!-- archivo menu-->
        <?php require_once('app/views/default/menu.php'); ?>
        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">
                <!--archivo header-->
                <?php require_once('app/views/default/header.php'); ?>
                <div class="container-fluid">
                    <!-- contenido de la pagina -->
                    
                    <!-- cerrar contenido pagina-->
                    <div id="divListado"></div>
                </div>
            </div>
            <!-- Logout Modal-->
            <div class="modal fade" id="myModal_1" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
                aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel"><strong>Registro</strong>
                            </h5>
                            <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <!-- contenido del modal-->
                            <div style="width:100%;" class="modal-body" id="divFormulario">
                            </div>
                        </div>
                        <div class="modal-footer">

                            <input type="submit" class="btn btn-outline-success" id="btnGuardar" value="Guardar">
                            <button class="btn btn-outline-secondary" type="button" data-dismiss="modal">Cancel</button>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Modal -->

            <!-- archivo Footer -->
            <?php require_once('app/views/default/footer.php'); ?>
            <!-- End of Footer -->
        </div>
    </div>
    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>
    <?php require_once('app/views/default/script_f.html'); ?>
</body>

</html>