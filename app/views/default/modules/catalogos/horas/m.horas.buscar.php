<?php
/*
 * Copyright 2021 - Felipe angel cerda contreras 
 * felipeangelcerdacontreras@gmail.com
 */
$_SITE_PATH = $_SERVER["DOCUMENT_ROOT"] . "/" . explode("/", $_SERVER["PHP_SELF"])[1] . "/";
require_once($_SITE_PATH . "app/model/horas.class.php");

$oHoras = new horas();
$sesion = $_SESSION[$oHoras->NombreSesion];
$oHoras->ValidaNivelUsuario("horas");

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
    });

    function Listado() {
        var jsonDatos = {
            "fecha_inicial": $("#fecha_inicial").val(),
            "fecha_final": $("#fecha_final").val(),
            "estatus": $("#estatus").val(),
            "accion": "BUSCAR"
        };
        $.ajax({
            data: jsonDatos,
            type: "POST",
            url: "app/views/default/modules/catalogos/horas/m.horas.listado.php",
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
            case "Autorizar":
                swal({
                    title: "¿DESEA AUTORIZAR LAS HORAS EXTRAS?",
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
                            title: 'AUTORIZADAS!',
                            text: 'Las horas extras an sido autorizadas',
                            icon: 'success'
                        }).then(function() {
                            $.ajax({
                                data: "accion=Autorizar&id=" + id + "&estatus=2&id_usuario_autorizador="+<?=$sesion->id?>,
                                type: "POST",
                                url: "app/views/default/modules/catalogos/horas/m.horas.procesa.php",
                                beforeSend: function() {

                                },
                                success: function(datos) {
                                    console.log(datos);
                                    Listado();
                                }
                            });
                        });
                    } else {
                        swal("Cancelado", "Horas extra no autorizadas", "error");
                    }
                });
                break;
            case "Calcular":
                $.ajax({
                    data: "id=" + id + "&nombre=" + nombre,
                    type: "POST",
                    url: "app/views/default/modules/catalogos/horas/m.horas.formulario.genera.php",
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
                    url: "app/views/default/modules/catalogos/horas/m.horas.formulario.php",
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
</script>

<?php require_once('app/views/default/link.html'); ?>

<head>
    <?php require_once('app/views/default/head.html'); ?>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <title>Horas Extras</title>
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
                        <div class="card-header py-3" style="text-align:left">
                            <div class="row">
                                <div class="col">
                                    <div class="form-group">
                                        <strong class="">Desde:</strong>
                                        <input type="date" aria-describedby="" id="fecha_inicial" value="<?php  echo date("Y-m-d", strtotime($fecha_actual . "- 1 week"));  ?>" required name="fecha_inicial" class="form-control" />
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
                                        <option value='2'>AUTORIZADAS</option>
                                        <option value='1'>EN ESPERA A AUTORIZACIÓN</option>
                                    </select>
                                </div>
                        </div>
                    </div>
                    <!-- cerrar contenido pagina-->
                    <div id="divListado"></div>
                </div>
            </div>
            <!-- Logout Modal-->
            <div class="modal fade" id="myModal_1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
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

                            <input type="submit" class="btn btn-outline-danger" id="btnGuardar" value="Guardar">
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