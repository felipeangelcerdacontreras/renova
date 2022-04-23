<?php
/*
 * Copyright 2021 - Felipe angel cerda contreras 
 * felipeangelcerdacontreras@gmail.com
 */
$_SITE_PATH = $_SERVER["DOCUMENT_ROOT"] . "/" . explode("/", $_SERVER["PHP_SELF"])[1] . "/";
require_once($_SITE_PATH . "app/model/nominas.fiscal.class.php");

$oNominas = new nominas_fiscal();
$sesion = $_SESSION[$oNominas->NombreSesion];
$oNominas->ValidaNivelUsuario("nominas_fiscal");

$fecha_actual = date("d-m-Y");
?>
<?php require_once('app/views/default/script_h.html'); ?>
<script type="text/javascript">
    $(document).ready(function(e) {
        Listado();
        $('#fecha_inicial').change(Listado);
        $('#fecha_final').change(Listado);

        $("#btnGuardar").button().click(function(e) {

            $(".form-control").css('border', '1px solid #d1d3e2');
            var frmTrue = true;

            $("#frmFormulario_").find('select, input').each(function() {
                var elemento = this;
                if ($(elemento).hasClass("obligado")) {
                    if (elemento.value == "" || elemento.value == 0) {
                        Alert("", $(elemento).attr("description"), "warning", 900, false);
                        Empty(elemento.id);
                        frmTrue = false;
                    } else {
                        /*if (ValidarFechas($("#fecha").val())) {
                        frmTrue = true;
                        } else {
                            Alert("", "La fecha seleccionada debe ser mayor o igual a la actual", "warning", 900, false);
                            Empty(elemento.id);
                            frmTrue = false;
                        }*/
                    }
                }
            });

            if (frmTrue == true) {
                $.ajax({
                    data: $('#frmFormulario_').submit(),
                    type: "POST",
                    url: "app/views/default/modules/modulos/nominas_fiscal/m.nominas.procesa.php",
                    beforeSend: function() {
                        $("#btnGuardar").hide();
                        $("#divFormulario_").html(
                            '<div class="container"><center><img src="app/views/default/img/loading.gif" border="0"/><br />Insertando informacion en la BD, espere un momento por favor...</center></div>'
                        );
                    },
                    success: function(datos) {
                        $("#btnGuardar").show();
                        Listado();
                    }
                });
            }
        });
        $("#btnAdd").button().click(function(e) {
            var frmTrue = true;

            $("#frmFormulario_add").find('select, input').each(function() {
                var elemento = this;
                if ($(elemento).hasClass("obligado")) {
                    if (elemento.value == "" || elemento.value == 0) {
                        Alert("", $(elemento).attr("description"), "warning", 500, false);
                        Empty(elemento.id);
                        frmTrue = false;
                    }
                }
            });

            if (frmTrue == true) {
                $('#frmFormulario_add').submit();
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
            url: "app/views/default/modules/modulos/nominas_fiscal/m.nominas.listado.php",
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

    function Reporte(id_, id_empleado) {

        var opc = "fullscreen=no, menubar=no, resizable=no, scrollbars=yes, status=yes, titlebar=yes, toolbar=no, width=750, height=580";
        var pagina = "app/views/default/modules/modulos/nominas_fiscal/m.nominas.recibo.pdf.php?";

        pagina += "id=" + id_ + "&id_empleado=" + id_empleado;

        window.open(pagina, "reporte", opc);
    }

    function Editar(id, nombre, id_empleado, empleado) {
        switch (nombre) {
            case 'Pagar':
                swal({
                    title: "¿DESEA PAGAR LA NOMINA SELECCIONADA?",
                    text: "",
                    icon: "warning",
                    buttons: [
                        'No',
                        'Si'
                    ],
                    dangerMode: true,
                }).then(function(isConfirm) {
                    if (isConfirm) {
                        swal({
                            title: 'Pagada!',
                            text: 'La nomina seleccionada a sido pagada',
                            icon: 'success'
                        }).then(function() {
                            $.ajax({
                                data: "accion=Pagar&id=" + id,
                                type: "POST",
                                url: "app/views/default/modules/modulos/nominas_fiscal/m.nominas.procesa.php",
                                beforeSend: function() {
                                    //Alert("", "Solicitando edicion", "warning", 900, false);
                                },
                                success: function(datos) {
                                    Listado();
                                }
                            });
                        });
                    } else {
                        swal("Cancelado", "Nomina no pagada", "error");
                    }
                });
                break;
            case 'Recalcular':
                swal({
                    title: "¿DESEA RECALCULAR LA NOMINA SELECCIONADA?",
                    text: "",
                    icon: "warning",
                    buttons: [
                        'No',
                        'Si'
                    ],
                    dangerMode: true,
                }).then(function(isConfirm) {
                    if (isConfirm) {
                        swal({
                            title: 'Recalculada!',
                            text: 'La nomina seleccionada a sido recalculada',
                            icon: 'success'
                        }).then(function() {
                            $.ajax({
                                data: "accion=Recalcular&id=" + id+"&recalcular=1",
                                type: "POST",
                                url: "app/views/default/modules/modulos/nominas_fiscal/m.nominas.procesa.php",
                                beforeSend: function() {
                                    //Alert("", "Solicitando edicion", "warning", 900, false);
                                },
                                success: function(datos) {
                                    Listado();
                                }
                            });
                        });
                    } else {
                        swal("Cancelado", "Nomina no Recalculada", "error");
                    }
                });
                break;
            case 'Agregar':
                $.ajax({
                    data: "nombre=" + nombre,
                    type: "POST",
                    url: "app/views/default/modules/modulos/nominas_fiscal/m.nominas.formulario.nominas.php",
                    beforeSend: function() {
                        $("#divFormulario_").html(
                            '<div class="container"><center><img src="app/views/default/img/loading.gif" border="0"/><br />Cargando formulario, espere un momento por favor...</center></div>'
                        );
                    },
                    success: function(datos) {
                        $("#divFormulario_").html(datos);
                    }
                });
                $("#myModal_nominas_fiscal").modal({
                    backdrop: "true"
                });
                break;
            case 'AddNomina':
                $.ajax({
                    data: "id_nomina=" + id + "&fecha=" + id_empleado,
                    type: "POST",
                    url: "app/views/default/modules/modulos/nominas_fiscal/m.nominas.formulario.add.php",
                    beforeSend: function() {
                        $("#divFormulario_add").html(
                            '<div class="container"><center><img src="app/views/default/img/loading.gif" border="0"/><br />Cargando formulario, espere un momento por favor...</center></div>'
                        );
                    },
                    success: function(datos) {
                        $("#divFormulario_add").html(datos);
                    }
                });
                $("#myModal_nominas_fiscalAdd").modal({
                    backdrop: "true"
                });
                break;
            case 'Solicitud':
                swal({
                    title: "¿Esta solicitando editar la nomina de: " + empleado + "?",
                    text: "",
                    icon: "warning",
                    buttons: [
                        'No',
                        'Si'
                    ],
                    dangerMode: true,
                }).then(function(isConfirm) {
                    if (isConfirm) {
                        swal({
                            title: 'Solicitado!',
                            text: 'Notificar al encargado de las solicitudes',
                            icon: 'success'
                        }).then(function() {


                            var jsonDatos = {
                                "accion": "Solicitar",
                                "id_": id,
                                "id_empleado_": id_empleado,
                                "laborados_": $("#laborados_" + id_empleado).val()
                            };

                            $.ajax({
                                data: jsonDatos,
                                type: "POST",
                                url: "app/views/default/modules/modulos/nominas_fiscal/m.nominas.procesa.php",
                                beforeSend: function() {

                                },
                                success: function(datos) {
                                    Listado();
                                }
                            });
                        });
                    } else {
                        swal("Cancelado", "", "error");
                    }
                });
                break;
            case 'Final':
                $.ajax({
                    data: "id=" + id + "&nombre=" + id_empleado,
                    type: "POST",
                    url: "app/views/default/modules/modulos/nominas_fiscal/m.nominas.final.formulario.php",
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
                break;
            default:
                $.ajax({
                    data: "id=" + id + "&nombre=" + nombre,
                    type: "POST",
                    url: "app/views/default/modules/modulos/nominas_fiscal/m.nominas.formulario.php",
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
    }

    function DatosEmpleado() {
        $.ajax({
            data: "accion=DatosEmpleado&id_empleado=" + $("#id_empleado").val(),
            type: "POST",
            url: "app/views/default/modules/modulos/nominas_fiscal/m.nominas.procesa.php",
            beforeSend: function() {},
            success: function(datos) {
                ret = JSON.parse(datos);
                salario_diario = ret[0];

                $("#diario_add").val(salario_diario);
            }
        });
    }
</script>

<?php require_once('app/views/default/link.html'); ?>
<script src="app/views/default/js/jsPDF/jspdf.js"></script>

<head>
    <?php require_once('app/views/default/head.html'); ?>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <title>nominas_fiscal</title>
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
                    <div class="card shadow mb-4">
                        <center>
                            <div class="card-header py-3" style="text-align:left">
                                <div class="row">
                                    <div class="col">
                                        <div class="form-group">

                                            <strong class="">Desde:</strong>
                                            <input type="date" aria-describedby="" id="fecha_inicial" value="<?php echo date("Y-m-d", strtotime($fecha_actual . "- 1 week"));  ?>" required name="fecha_inicial" class="form-control" />
                                        </div>
                                    </div>
                                    <div class="col">
                                        <strong class="">Hasta:</strong>
                                        <div class="form-group">
                                            <input type="date" aria-describedby="" id="fecha_final" value="<?php echo date('Y-m-d'); ?>" required name="fecha_final" class="form-control" />
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
            <div class="modal fade " id="myModal_nominas_fiscal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
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
                            <input type="button" id="btnGuardar" class="btn btn-outline-danger" name="btnGuardar" value="Crear Pre Nomina">
                            <button class="btn btn-outline-secondary" type="button" data-dismiss="modal">Cancel</button>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Modal -->

            <!-- Logout Modal-->
            <div class="modal fade " id="myModal_nominas_fiscalAdd" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel"><strong id="nameModal_">Agregar nomina</strong>
                            </h5>
                            <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <!-- contenido del modal-->
                            <div style="width:100%;" class="modal-body" id="divFormulario_add">
                            </div>
                        </div>
                        <div class="modal-footer">
                            <input type="button" id="btnAdd" class="btn btn-outline-danger" name="btnAdd" value="Agregar Nomina">
                            <button class="btn btn-outline-secondary" type="button" data-dismiss="modal">Cancel</button>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Modal -->

            <!-- Logout Modal-->
            <div class="modal fade bd-example-modal-lg" id="myModal_item" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
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
                            <div style="width:100%;" class="modal-body" id="divFormulario_item">
                            </div>
                        </div>
                        <div class="modal-footer">
                            <input type="button" id="btnPrint" class="btn btn-outline-danger" name="btnPrint" onclick="printDiv()" value="Imprimir">
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