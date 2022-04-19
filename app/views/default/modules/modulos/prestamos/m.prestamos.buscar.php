<?php
/*
 * Copyright 2021 - Felipe angel cerda contreras 
 * felipeangelcerdacontreras@gmail.com
 */
$_SITE_PATH = $_SERVER["DOCUMENT_ROOT"] . "/" . explode("/", $_SERVER["PHP_SELF"])[1] . "/";
require_once($_SITE_PATH . "app/model/prestamos.class.php");

$oPrestamos = new prestamos();
$sesion = $_SESSION[$oPrestamos->NombreSesion];
$oPrestamos->ValidaNivelUsuario("prestamos");

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

            $("#frmFormulario").find('select, input, textarea').each(function() {
                var elemento = this;
                if ($(elemento).hasClass("obligado")) {
                    if (elemento.value == "" || elemento.value == 0) {
                        Alert("", $(elemento).attr("description"), "warning", 900, false);
                        Empty(elemento.id);
                        frmTrue = false;
                    }
                }
            });
            if (frmTrue == true) {
                $("#frmFormulario").submit();
            }
        });
        $("#btnBuscar").button().click(function(e) {
            Listado();
        });
        $("#id_empleado").change(PrestamoActivo);

        $("#btnImprimir").button().click(function(e) {
            var opc = "fullscreen=no, menubar=no, resizable=no, scrollbars=yes, status=yes, titlebar=yes, toolbar=no, width=750, height=580";
            var pagina = "app/views/default/modules/modulos/prestamos/m.prestamos.recibo.pdf.php?";

            pagina += "id="+ $("#id_").val()+"&id_empleado="+ $("#id_empleado_").val()+"&fecha_registro="+$("#fecha_registro_").val();

            window.open(pagina, "reporte", opc);   
        });
    });

    function Listado() {
        var jsonDatos = {
            "fecha_inicial": $("#fecha_inicial").val(),
            "fecha_final": $("#fecha_final").val(),
            "estatus1": $("#estatus").val(),
            "accion": "BUSCAR"
        };
        $.ajax({
            data: jsonDatos,
            type: "POST",
            url: "app/views/default/modules/modulos/prestamos/m.prestamos.listado.php",
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
            case 'Liquidar':
                swal({
                    title: "¿Desea liquidar el prestamo?",
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
                            title: 'Liquidado!',
                            text: 'Prestamo Liquidado',
                            icon: 'success'
                        }).then(function() {
                            $.ajax({
                                data: "accion=Liquidado&id=" + id + "&estatus=0",
                                type: "POST",
                                url: "app/views/default/modules/modulos/prestamos/m.prestamos.procesa.php",
                                beforeSend: function() {

                                },
                                success: function(datos) {
                                    console.log(datos);
                                    Listado();
                                }
                            });
                        });
                    } else {
                        swal("Cancelado", "Prestamo no liquidado", "error");
                    }
                });
                break;
            case 'Agregar':
                $.ajax({
                    data: "nombre=" + nombre,
                    type: "POST",
                    url: "app/views/default/modules/modulos/prestamos/m.prestamos.formulario.php",
                    beforeSend: function() {
                        $("#divFormulario").html(
                            '<div class="container"><center><img src="app/views/default/img/loading.gif" border="0"/><br />Cargando formulario, espere un momento por favor...</center></div>'
                        );
                    },
                    success: function(datos) {
                        $("#btnGuardar").show();
                        $("#imprimir").hide();
                        $("#divFormulario").html(datos);
                    }
                });
                $("#myModal").modal({
                    backdrop: "true"
                });
                break;
            case 'Detalles':
                $.ajax({
                    data: "id=" + id + "&nombre=" + nombre + "&empleado=" + empleado,
                    type: "POST",
                    url: "app/views/default/modules/modulos/prestamos/m.prestamos.formulario.detalles.php",
                    beforeSend: function() {
                        $("#divFormulario").html(
                            '<div class="container"><center><img src="app/views/default/img/loading.gif" border="0"/><br />Cargando formulario, espere un momento por favor...</center></div>'
                        );
                    },
                    success: function(datos) {
                        $("#btnGuardar").hide();
                        $("#imprimir").show();
                        $("#divFormulario").html(datos);
                    }
                });
                $("#myModal").modal({
                    backdrop: "true"
                });
                break;
            case 'Editar':
                $.ajax({
                    data: "id="+id+"&nombre=" + nombre,
                    type: "POST",
                    url: "app/views/default/modules/modulos/prestamos/m.prestamos.editar.php",
                    beforeSend: function() {
                        $("#divFormulario").html(
                            '<div class="container"><center><img src="app/views/default/img/loading.gif" border="0"/><br />Cargando formulario, espere un momento por favor...</center></div>'
                        );
                    },
                    success: function(datos) {
                        $("#btnGuardar").show();
                        $("#imprimir").hide();
                        $("#divFormulario").html(datos);
                    }
                });
                $("#myModal").modal({
                    backdrop: "true"
                });
                break;
        }
    }

    function PrestamoActivo() {
        $.ajax({
            data: "accion=PrestamoActivo&id_empleado=" + $("#id_empleado").val() + "&prestamo=1",
            type: "POST",
            url: "app/views/default/modules/modulos/prestamos/m.prestamos.procesa.php",
            beforeSend: function() {},
            success: function(datos) {
                var str = datos;
                var datos0 = str.split("@")[0];
                var datos1 = str.split("@")[1];
                var datos2 = str.split("@")[2];

                if (datos0 != '' && datos1 != '') {
                    swal({
                        title: "¿El empleado tiene un prestamo activo, desea sumar el restante que es de $" + datos0 + " a el nuevo prestamo?",
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
                                title: 'Liquidado!',
                                text: 'Prestamo Liquidado y el restante sera sumado al sigueinte prestamo',
                                icon: 'success'
                            }).then(function() {
                                $("#restoVisible").show();
                                $("#restante").val(datos0);
                                $("#restoVisible").val(datos0);
                                $("#id_prestamo").val(datos1);
                                $("#Semanas").val(datos2);
                            });
                        } else {
                            swal("Cancelado", "Prestamo no liquidado", "error");
                        }
                    });
                }
            }
        });
    }

</script>

<?php require_once('app/views/default/link.html'); ?>

<head>
    <?php require_once('app/views/default/head.html'); ?>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <title>Prestamos</title>
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
                                            <input type="date" aria-describedby="" id="fecha_inicial" value="<?php echo date("Y-m-d",strtotime($fecha_actual."- 1 week")); ?>" required name="fecha_inicial" class="form-control" />
                                        </div>
                                    </div>
                                    <div class="col">
                                        <strong class="">Hasta:</strong>
                                        <div class="form-group">
                                            <input type="date" aria-describedby="" id="fecha_final" value="<?php echo date('Y-m-d'); ?>" required name="fecha_final" class="form-control" />
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <strong class="">Estado:</strong>
                                    <select id="estatus" class="form-control obligado" onchange="Listado()">
                                        <option value=''>--TODOS--</option>
                                        <option value='0'>Liquidado</option>
                                        <option value='1'>Pagando</option>
                                    </select>
                                </div>
                            </div>
                        </center>
                    </div>
                    <!-- cerrar contenido pagina-->
                    <div id="divListado"></div>
                </div>
            </div>

            <!-- Logout Modal-->
            <div class="modal fade " id="myModal" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
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
                            <input type="button" id="btnGuardar" class="btn btn-outline-danger" name="btnGuardar" value="Guardar">
                            <input type="button" id="btnImprimir" class="btn btn-outline-danger" name="btnImprimir" value="Imprimir Prestamo">
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