<?php
/*
 * Copyright 2021 - Felipe angel cerda contreras 
 * felipeangelcerdacontreras@gmail.com
 */
$_SITE_PATH = $_SERVER["DOCUMENT_ROOT"] . "/" . explode("/", $_SERVER["PHP_SELF"])[1] . "/";
require_once($_SITE_PATH . "app/model/incapacidades.class.php");
require_once($_SITE_PATH . "/app/model/sueldoMinimo.class.php");

$oIncapacidades = new incapacidades();
$sesion = $_SESSION[$oIncapacidades->NombreSesion];
$oIncapacidades->ValidaNivelUsuario("incapacidades");

$oSueldo = new sueldo(true, $_POST);
$oSueldo->id = 1;
$oSueldo->Informacion();
?>
<?php require_once('app/views/default/script_h.html'); ?>
<script type="text/javascript">
    $(document).ready(function(e) {
        Listado();
        $('#fecha_inicial').change(Listado);
        $('#fecha_final').change(Listado);
        $("#btnImprimir").hide();

        $("#btnGuardar1").button().click(function(e) {
            var jsonDatos = {
                "id_": 1,
                "sueldo_minimo": $("#sueldo_minimo").val(),
                "accion": "GUARDAR_SUELDO"
            };
            $.ajax({
                data: jsonDatos,
                type: "POST",
                url: "app/views/default/modules/modulos/incapacidades/m.incapacidades.procesa.php",
                beforeSend: function() {
                    $("#divFormulario_").html(
                        '<div class="container"><center><img src="app/views/default/img/loading.gif" border="0"/><br />Insertando informacion en la BD, espere un momento por favor...</center></div>'
                    );
                },
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
                    $("#myModal_minimo").modal("hide");
                    document.location.reload();
                }
            });
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
                            Alert("", "La incapacidades de este periodo ya no estan disponibles", "warning", 1800, false);
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
            url: "app/views/default/modules/modulos/incapacidades/m.incapacidades.listado.php",
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

    function Editar(id, nombre, empleado) {
        switch (nombre) {
            case 'Agregar':
                $.ajax({
                    data: "id="+id+"&nombre=" + nombre,
                    type: "POST",
                    url: "app/views/default/modules/modulos/incapacidades/m.incapacidades.formulario.nominas.php",
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
                $("#myModal_incapacidades").modal({
                    backdrop: "true"
                });
                break;
            case 'Minimo':
                $.ajax({
                    data: "",
                    type: "POST",
                    url: "app/views/default/modules/modulos/incapacidades/m.incapacidades.formulario.sueldo.php",
                    beforeSend: function() {
                        $("#divFormulario_minimo").html(
                            '<div class="container"><center><img src="app/views/default/img/loading.gif" border="0"/><br />Cargando formulario, espere un momento por favor...</center></div>'
                        );
                    },
                    success: function(datos) {
                        $("#divFormulario_minimo").html(datos);
                    }
                });
                $("#myModal_minimo").modal({
                    backdrop: "true"
                });
                break;
            case 'Detalles':
                $.ajax({
                    data: "id="+id+"&nombre=" + nombre+"&empleado=" + empleado,
                    type: "POST",
                    url: "app/views/default/modules/modulos/incapacidades/m.incapacidades.formulario.php",
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
                $("#myModal_incapacidades").modal({
                    backdrop: "true"
                });
                break;
        }
    }
    function DatosEmpleado() {
        $.ajax({
            data: "accion=DatosEmpleado&id_empleado=" + $("#id_empleado").val(),
            type: "POST",
            url: "app/views/default/modules/modulos/nominas/m.nominas.procesa.php",
            beforeSend: function() {},
            success: function(datos) {
                ret = JSON.parse(datos);
                salario = ret[0];
                sueldo_minimo = <?= $oSueldo->sueldo_minimo ? $oSueldo->sueldo_minimo : '0.00'; ?> ;
                if (sueldo_minimo < 1) {
                    Alert("", "Primero guarda un sueldo minimo", "warning", 1800, false);
                }
                else if (salario <= sueldo_minimo) {
                    salario = sueldo_minimo;
                    $("#monto_incapacidad").val(salario);
                }
                $("#monto_real").val(salario);
            }
        });
    }
</script>

<?php require_once('app/views/default/link.html'); ?>

<head>
    <?php require_once('app/views/default/head.html'); ?>
    <title>incapacidades</title>
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
            <div class="modal fade bd-example-modal-lg" id="myModal_incapacidades" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
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
                            <input type="button" id="btnGuardar" class="btn btn-outline-danger" name="btnGuardar" value="Generar Incapacidades">
                            <button class="btn btn-outline-secondary" type="button" data-dismiss="modal">Cancel</button>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Modal -->

            <!-- Logout Modal-->
            <div class="modal fade bd-example-modal-sm" id="myModal_minimo" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-sm" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel"><strong id="nameModal_">Sueldo minimo</strong></h5>
                            </h5>
                            <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <!-- contenido del modal-->
                            <div style="width:100%;" class="modal-body" id="divFormulario_minimo">
                            </div>
                        </div>
                        <div class="modal-footer">
                            <input type="button" id="btnGuardar1" class="btn btn-outline-danger" name="" value="Guardar">
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