<?php
/*
 * Copyright 2021 - Felipe angel cerda contreras 
 * felipeangelcerdacontreras@gmail.com
 */
$_SITE_PATH = $_SERVER["DOCUMENT_ROOT"] . "/" . explode("/", $_SERVER["PHP_SELF"])[1] . "/";
require_once($_SITE_PATH . "app/model/vacaciones.class.php");

$oVacaciones = new vacaciones();
$sesion = $_SESSION[$oVacaciones->NombreSesion];
$oVacaciones->ValidaNivelUsuario("vacaciones");

?>
<?php require_once('app/views/default/script_h.html'); ?>
<script type="text/javascript">
    $(document).ready(function(e) {
        Listado();
        $('#fecha_inicial').change(Listado);
        $('#fecha_final').change(Listado);
        $("#btnImprimir").hide();

        $("#btnGuardar2").button().click(function(e) { 
            var frmTru = false;
            var contador = 0;

            $("#frmFormulario_v").find('input').each(function() {
                var elemento = this;
                if ($(elemento).hasClass("obligado")) {
                    if ($(elemento).prop('checked')) {
                        contador++;
                        frmTru = true;

                    } else {
                        Alert("", $(elemento).attr("description"), "warning", 900, false); 
                        frmTru = false;
                    }
                }
            });
            if (frmTru == true && contador == ($("#contador").val() - 1) ) {
                $("#frmFormulario_v").submit();
            }
        });
        $("#btnGuardar").button().click(function(e) {
            $(".form-control").css('border', '1px solid #d1d3e2');
            var frmTrue = true;
            var check = 0;
            var check1 = 0;
            $("#frmFormulario_").find('select, input').each(function() {
                var elemento = this;
                if ($(elemento).hasClass("obligado")) {
                    if (elemento.value == "" || elemento.value == 0) {
                        if ($(elemento).hasClass("select2")) {
                        }
                        Alert("", $(elemento).attr("description"), "warning", 900, false);
                        Empty(elemento.id);
                        frmTrue = false;
                    } else {
                        if (ValidarFechas($("#validarFehca").val())) {
                        frmTrue = true;
                        } else {
                            Alert("", "La vacaciones de este periodo ya no estan disponibles", "warning", 1800, false);
                            Empty(elemento.id);
                            frmTrue = false;
                        }
                    }
                }
            });
            if (frmTrue == true ) {
                $("#frmFormulario_").submit();
            }
        });

        $("#btnBuscar").button().click(function(e) {
            Listado();
        });

       
    });

    function Listado() {
        var jsonDatos = {
            "fecha_inicial": $("#fecha_inicial").val(),
            "fecha_final": $("#fecha_final").val(),
            "accion": "BUSCAR"
        };
        $.ajax({
            data: jsonDatos,
            type: "POST",
            url: "app/views/default/modules/modulos/vacaciones/m.vacaciones.listado.php",
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

    function Editar(id, nombre) {
        switch (nombre) {
            case 'Editar':
                $.ajax({
                    data: "id="+id+"&nombre=" + nombre,
                    type: "POST",
                    url: "app/views/default/modules/modulos/vacaciones/m.vacaciones.formulario.nominas.php",
                    beforeSend: function() {
                        $("#divFormulario_").html(
                            '<div class="container"><center><img src="app/views/default/img/loading.gif" border="0"/><br />Cargando formulario, espere un momento por favor...</center></div>'
                        );
                    },
                    success: function(datos) {
                        $("#divFormulario_").html(datos);
                    }
                });
                $("#btnGuardar").show();
                $("#btnImprimir").hide();
                $("#myModal_vacaciones").modal({
                    backdrop: "true"
                });
                break;
            case 'Generar vacaciones':
                $.ajax({
                    data: "id="+id+"&nombre=" + nombre,
                    type: "POST",
                    url: "app/views/default/modules/modulos/vacaciones/m.vacaciones.formulario.generado.php",
                    beforeSend: function() {
                        $("#divFormulario_vnomina").html(
                            '<div class="container"><center><img src="app/views/default/img/loading.gif" border="0"/><br />Cargando formulario, espere un momento por favor...</center></div>'
                        );
                    },
                    success: function(datos) {
                        $("#divFormulario_vnomina").html(datos);
                    }
                });
                $("#btnGuardar").show();
                $("#btnImprimir").hide();
                $("#myModal_vnomina").modal({
                    backdrop: "true"
                });
                break;
            case 'Detalles':
                $.ajax({
                    data: "id="+id+"&nombre=" + nombre+"&empleado=" + empleado,
                    type: "POST",
                    url: "app/views/default/modules/modulos/vacaciones/m.vacaciones.formulario.php",
                    beforeSend: function() {
                        $("#divFormulario_").html(
                            '<div class="container"><center><img src="app/views/default/img/loading.gif" border="0"/><br />Cargando formulario, espere un momento por favor...</center></div>'
                        );
                    },
                    success: function(datos) {
                        $("#divFormulario_").html(datos);
                    }
                });
                $("#btnGuardar").hide();
                $("#btnImprimir").show();
                $("#myModal_vacaciones").modal({
                    backdrop: "true"
                });
                break;
        }
    }

    function Listado2() {
    var jsonDatos = {
        "fecha_genera": $("#fecha_genera").val()
    };
    $.ajax({
        data: jsonDatos,
        type: "POST",
        url: "app/views/default/modules/modulos/vacaciones/m.vacaciones.listado.genera.php",
        beforeSend: function() {
        },
        success: function(datos) {
            $("#divListado_V").html(datos);
            Listado();
        }
    });
}
</script>

<?php require_once('app/views/default/link.html'); ?>

<head>
    <?php require_once('app/views/default/head.html'); ?>
    <title>vacaciones</title>
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
                <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
                <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
                <div class="container-fluid">
                    <!-- contenido de la pagina -->
                    <div class="card shadow mb-4">
                        <center>
                            <div class="card-header py-3" style="text-align:left">
                                <div class="row">
                                    <div class="col">
                                        <div class="form-group">
                                            <strong class="">Desde:</strong>
                                            <input type="date" aria-describedby="" id="fecha_inicial" value="<?php echo date('Y-')."01-01"; ?>" required name="fecha_inicial" class="form-control" />
                                        </div>
                                    </div>
                                    <div class="col">
                                        <strong class="">Hasta:</strong>
                                        <div class="form-group">
                                            <input type="date" aria-describedby="" id="fecha_final" value="<?php echo date('Y-')."12-31"; ?>" required name="fecha_final" class="form-control" />
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </center>
                    </div>
                    <!-- cerrar contenido pagina-->
                    <div id="divListado"></div>
                </div>
            </div>
            <!-- Logout Modal-->
            <div class="modal fade bd-example-modal-lg" id="myModal_1" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-xl" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel"><strong id="nameModal"></strong>
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
                            <button class="btn btn-outline-secondary" type="button" data-dismiss="modal">Cancel</button>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Modal -->

            <!-- Logout Modal-->
            <div class="modal fade bd-example-modal-lg" id="myModal_vacaciones" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel"><strong id="nameModal_"></strong>
                            </h5>
                            <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <!-- contenido del modal-->
                            <div style="width:100%;" class="modal-body" id="divFormulario_">
                            </div>
                        </div>
                        <div class="modal-footer">
                            <input type="button" id="btnGuardar" class="btn btn-outline-danger" name="btnGuardar" value="Generar Vacaciones">
                            <button class="btn btn-outline-secondary" type="button" data-dismiss="modal">Cancel</button>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Modal -->

            <!-- Logout Modal-->
            <div class="modal fade bd-example-modal-lg" id="myModal_vnomina" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-xl" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel"><strong id="nameModal_vnomina"></strong>
                            </h5>
                            <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <!-- contenido del modal-->
                            <div style="width:100%;" class="modal-body" id="divFormulario_vnomina">
                            </div>
                        </div>
                        <div class="modal-footer">
                            <input type="button" id="btnGuardar2" class="btn btn-outline-danger" name="btnGuardar2" value="Generar Vacaciones">
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