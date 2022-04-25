<?php
header("Acces-Control-Allow-Origin: *");
/*
 * Copyright 2021 - Felipe angel cerda contreras 
 * felipeangelcerdacontreras@gmail.com
 */
$_SITE_PATH = $_SERVER["DOCUMENT_ROOT"] . "/" . explode("/", $_SERVER["PHP_SELF"])[1] . "/";
require_once($_SITE_PATH . "app/model/asistencia.class.php");
require_once($_SITE_PATH . "app/model/departamentos.class.php");

$oAsistencia = new asistencia();
$sesion = $_SESSION[$oAsistencia->NombreSesion];
$oAsistencia->ValidaNivelUsuario("asistencia");

$oDepartamentos = new departamentos();
$oDepartamentos->estatus = 1;
$lstDepartamentos = $oDepartamentos->Listado();

?>
<?php require_once('app/views/default/script_h.html'); ?>
<script type="text/javascript">
    $(document).ready(function(e) {
        Listado();
        $('#fecha_inicial').change(Listado);
        $('#fecha_final').change(Listado);
        $('#id_departamento').change(Listado);

        $("#btnSincronizar").button().click(function(e) {
            if ($("#btnSincronizar").val() == 'Agregar') {
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
            }
        });
        $("#sincronizar").button().click(function(e) {
            Editar("", "Sincronizar");
        });
        $("#agregar").button().click(function(e) {
            Editar("", "Agregar");
        });
        $("#txt").button().click(function(e) {
            Editar("", "txt");
        });
        $("#btnSincronizar").button().click(function(e) {
            if ($("#btnSincronizar").val() == 'Sincronizar') {
                if ($("#desde").val() != "" && $("#hasta").val() != "") {
                    $.ajax({
                        async: true,
                        data: "desde=" + $("#desde").val() + "&hasta=" + $("#hasta").val(),
                        type: "POST",
                        url: "http://192.168.1.161/renova/app/sensor/AsistenciaRestApi.php",
                        dataType: "json",
                        success: function(datos) {
                            json = JSON.parse(JSON.stringify(datos));
                            rest = 0;

                            $("#btnSincronizar").hide();
                            $("#divFormulario_").html(
                                '<div class="container"><center><img src="app/views/default/img/loading.gif" border="0"/><br />Insertando informacion en la BD, espere un momento por favor...</center></div>'
                            );

                            for (x of json) {
                                if (x.insert != '' && x.insert != null) {
                                    var jsonDatos = {
                                        "accion": "Sincronizar",
                                        "insert_": x.insert,
                                        "update_": x.update,
                                        "fecha_": x.fecha,
                                        "id_empleado_": x.id_empleado
                                    };

                                    $.ajax({
                                        async: true,
                                        data: jsonDatos,
                                        type: "POST",
                                        url: "app/views/default/modules/modulos/asistencia/m.asistencia.procesa.php",
                                        dataType: "json",
                                        success: function(datos) {

                                        }
                                    });
                                }
                                rest++;
                                if (json[0]['total'] == rest) {
                                    $("#myModal").modal("hide");
                                    Listado();
                                    $("#btnSincronizar").show();
                                }
                            }
                        }
                    });
                } else {
                    Alert("", "Selecciona las fechas para sincronizar asistencia", "warning", 900, false);
                }
            }
        });
    });

    function Listado() {
        var jsonDatos = {
            "fecha_inicial": $("#fecha_inicial").val(),
            "fecha_final": $("#fecha_final").val(),
            "id_departamento": $("#id_departamento").val()
        };
        $.ajax({
            data: jsonDatos,
            type: "POST",
            url: "app/views/default/modules/modulos/asistencia/m.asistencia.listado.php",
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
            case 'Sincronizar':
                $.ajax({
                    data: "nombre=" + nombre,
                    type: "POST",
                    url: "app/views/default/modules/modulos/asistencia/m.asistencia.formulario.php",
                    beforeSend: function() {
                        $("#divFormulario").html(
                            '<div class="container"><center><img src="app/views/default/img/loading.gif" border="0"/><br />Cargando formulario, espere un momento por favor...</center></div>'
                        );
                    },
                    success: function(datos) {
                        $("#divFormulario").html(datos);
                    }
                });
                $("#myModal").modal({
                    backdrop: "true"
                });
                break;
            case 'Agregar':
                $.ajax({
                    data: "nombre=" + nombre,
                    type: "POST",
                    url: "app/views/default/modules/modulos/asistencia/m.asistencia.justificar.php",
                    beforeSend: function() {
                        $("#divFormulario").html(
                            '<div class="container"><center><img src="app/views/default/img/loading.gif" border="0"/><br />Cargando formulario, espere un momento por favor...</center></div>'
                        );
                    },
                    success: function(datos) {
                        $("#divFormulario").html(datos);

                    }
                });
                $("#myModal").modal({
                    backdrop: "true"
                });
                break;
            case 'txt':
                var jsonDatos = {
                    "fecha_inicial": $("#fecha_inicial").val(),
                    "fecha_final": $("#fecha_final").val(),
                    "accion": "txt"
                };
                $.ajax({
                    data: jsonDatos,
                    type: "POST",
                    url: "app/views/default/modules/modulos/asistencia/m.asistencia.procesa.php",
                    beforeSend: function() {
                        Alert("", "Generando archivo", "success", 900, false);
                    },
                    success: function(datos) {
                        if (datos == "Se ha creado la lista de inasistencias correctamente.") {
                            Alert("Sistema", datos, "success", 900, false);
                            $("#download").attr("href", "rh/asistencia/"+$("#fecha_final").val()+".txt");
                            $("#download").attr("download",$("#fecha_final").val()+".txt");
                            $("#download").get(0).click();
                        } else {
                            Alert("Sistema", datos, "success", 900, false);
                        }
                    }
                });
                break;
        }
    }
   
</script>

<?php require_once('app/views/default/link.html'); ?>

<head>
    <?php require_once('app/views/default/head.html'); ?>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <title>Asistencia</title>
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
                                        <input type="date" aria-describedby="" id="fecha_inicial" value="<?php echo date('Y-m-d'); ?>" required name="fecha_inicial" class="form-control" />
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
                                <strong class="">Departamento:</strong>
                                <div class="form-group">
                                    <select id="id_departamento" class="form-control" name="id_departamento">
                                        <?php
                                        if (count($lstDepartamentos) > 0) {
                                            echo "<option value='0' >-- SELECCIONE --</option>\n";
                                            foreach ($lstDepartamentos as $idx => $campo) {
                                                echo "<option value='{$campo->id}' >" . $campo->nombre . "</option>\n";
                                            }
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="row" style="float: right">
                            <a id='download'  ></a>
                                <button class="btn btn-outline-secondary" tabindex="0" id="txt" type="button"><span>Extraer asistencia Microsip</span></button>&nbsp;
                                <button class="btn btn-outline-success" tabindex="0" id="agregar" type="button"><span>Agregar asistencia</span></button>&nbsp;
                                <button class="btn btn-outline-danger" tabindex="0" id="sincronizar" type="button"><span>Sincronizar Asistencia</span></button>
                            </div>
                        </div>
                    </div>
                    <!-- cerrar contenido pagina-->
                    <div id="divListado"></div>
                </div>
            </div>

            <!-- Logout Modal-->
            <div class="modal fade bd-example-modal-lg" id="myModal" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg" role="document">
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
                            <input type="button" id="btnSincronizar" class="btn btn-outline-danger" name="btnSincronizar" value="Sincronizar">
                            <button class="btn btn-outline-secondary" type="button" data-dismiss="modal">Cerrar</button>
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
    <script src="https://cdn.datatables.net/buttons/2.0.0/js/buttons.print.min.js"></script>
</body>

</html>