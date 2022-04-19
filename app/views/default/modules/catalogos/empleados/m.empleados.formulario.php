<?php
/*
 * Copyright 2021 - Felipe angel cerda contreras 
 * felipeangelcerdacontreras@gmail.com
 */
session_start();

$_SITE_PATH = $_SERVER["DOCUMENT_ROOT"] . "/" . explode("/", $_SERVER["PHP_SELF"])[1] . "/";
require_once($_SITE_PATH . "/app/model/empleados.class.php");
require_once($_SITE_PATH . "/app/model/puestos.class.php");
require_once($_SITE_PATH . "/app/model/horarios.class.php");

$oEmpleados = new empleados(true, $_POST);
$oEmpleados->id = addslashes(filter_input(INPUT_POST, "id"));
$nombre = addslashes(filter_input(INPUT_POST, "nombre"));
$sesion = $_SESSION[$oEmpleados->NombreSesion];
$oEmpleados->Informacion();
$lstJefes = $oEmpleados->jefes();

if ($oEmpleados->id != "") {
    $oEmpleados1 = new empleados();
    $oEmpleados1->id = addslashes(filter_input(INPUT_POST, "id"));
    $lstHuellas = $oEmpleados1->huellas();
}


$oPuestos = new puestos();
$lstpuestos = $oPuestos->Listado();

$oHorarios = new horarios();
$lsthorarios = $oHorarios->Listado();

?>
<script type="text/javascript">
    $("#token").val(localStorage.getItem("srnPc"));
    $('.id_token').attr('id', $("#token").val());
    $('.id_token').attr('name', $("#token").val());
    $('._status').attr('id', $("#token").val() + "_status");
    $('._texto').attr('id', $("#token").val() + "_texto");

    function upSensor() {
        $("#nombre_dedo").addClass("obligado");
        activarSensor($("#token").val());
        cargar_push();
    }

    function cargar_push() {
        $.ajax({
            async: true,
            type: "POST",
            url: "app/sensor/httpush.php",
            data: "&&timestamp=" + timestamp + "&token=" + $("#token").val(),
            dataType: "json",
            success: function(data) {
                $("#usr").val('');
                //$("#id").val('');
                var json = "";
                json = JSON.parse(JSON.stringify(data));
                timestamp = json["timestamp"];
                imageHuella = json["imgHuella"];
                tipo = json["tipo"];
                id = json["id"];
                $("#" + id + "_status").text(json["statusPlantilla"]);
                $("#" + id + "_texto").text(json["texto"]);
                if (imageHuella !== null) {
                    $("#" + id).attr("src", "data:image/png;base64," + imageHuella);
                    if (tipo === "leer") {
                        if (json["statusPlantilla"] == "El usuario no existe") {
                            Alert("", json["statusPlantilla"], "warning", 900, false);
                        } else {
                            $("#usr").val(json["documento"]);
                            console.log("accion=CHECAR&usr=" + json["documento"] + "&fecha_inicial=" + $("#fecha_").val() +
                                "&fecha_final=" + $("#fecha_").val() + "&hora=" + $("#hora").val() + "&diaActual=" + $("#diaActual").val());
                            $.ajax({
                                type: "POST",
                                url: "app/views/default/modules/checador/m.checador_procesa.php",
                                data: "accion=CHECAR&usr=" + $("#usr").val() + "&fecha_inicial=" + $("#fecha_").val() +
                                    "&fecha_final=" + $("#fecha_").val() + "&hora=" + $("#hora").val() + "&diaActual=" + $("#diaActual").val(),
                                success: function(response) {
                                    var str = response;
                                    var datos0 = str.split("@")[0];
                                    var datos1 = str.split("@")[1];
                                    var datos2 = str.split("@")[2];
                                    if ((datos3 = str.split("@")[3]) === undefined) {
                                        datos3 = "";
                                    } else {
                                        datos3 = str.split("@")[3];
                                    }
                                    Alert(datos0, datos1 + "" + datos3, datos2, 1100, false);
                                    Listado();
                                }
                            });
                        }
                    }
                }
                setTimeout("cargar_push()", 1000);
            }
        });
    }

    $(document).ready(function(e) {
        $("#nameModal").text("<?php echo $nombre ?> Empleado");
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
                $("#" + $("#token").val()).attr("src", "imagenes/finger.png");
                $("#fingerPrint").css("display", "none");
            }
        });
        $('#estado_civil').select2({
            width: '100%'
        });
        $('#nivel_estudios').select2({
            width: '100%'
        });
        $('#id_puesto').select2({
            width: '100%'
        });
        $('#id_jefe').select2({
            width: '100%'
        });
        $('[data-toggle="tooltip"]').tooltip();

        $('#rowTab li').on('click', function() {
            console.log("hola");
            $('#rowTab li').removeClass('divTabs');
            $(this).addClass('divTabs');
        });
    });
</script>
<style>
    .divTabs {
        border-top-left-radius: 10px !important;
        border-top-right-radius: 10px !important;
        background-color: #e7001a !important;
    }
</style>
<form id="frmFormulario" name="frmFormulario" action="app/views/default/modules/catalogos/empleados/m.empleados.procesa.php" enctype="multipart/form-data" method="post" target="_self" class="form-horizontal">
    <div>
        <div class="form-component-container">
            <div class="panel panel-default form component main">
                <div class="panel-heading">
                    <ul id="rowTab" class="nav nav-tabs">
                        <li class="active divTabs">
                            <a data-toggle="tab" class="btn btn-outline-danger" href="#tab1">Datos del Empleado</a>
                        </li>
                        <li class=" ">
                            <a data-toggle="tab" class="btn btn-outline-danger " href="#tab2">Puesto</a>
                        </li>
                        <li>
                            <a data-toggle="tab" class="btn btn-outline-danger " href="#tab3">Salario</a>
                        </li>
                        <li>
                            <a data-toggle="tab" class="btn btn-outline-danger " href="#tab4">Datos de contacto</a>
                        </li>
                        <li>
                            <a data-toggle="tab" class="btn btn-outline-danger" href="#tab5">Datos particulares</a>
                        </li>
                        <li>
                            <a data-toggle="tab" class="btn btn-outline-danger" href="#tab6">Datos bancarios</a>
                        </li>
                    </ul>
                    <div class="tab-content">
                        <div id="tab1" class="tab-pane fade active show">
                            <div class="row">
                                <div class="col">
                                    <div class="form-group">
                                        <strong class="">Nombres:</strong>
                                        <div class="form-group">
                                            <input type="text" description="Ingrese el nombre" class="form-control obligado" aria-describedby="" id="nombres" required name="nombres" value="<?= ucwords(strtolower($oEmpleados->nombres)) ?>" />
                                        </div>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="form-group">
                                        <strong class="">Apellido Paterno:</strong>
                                        <div class="form-group">
                                            <input type="text" description="Ingrese el apellido paterno " aria-describedby="" id="ape_paterno" required name="ape_paterno" value="<?= ucwords(strtolower($oEmpleados->ape_paterno)) ?>" class="form-control obligado" />
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col">
                                    <div class="form-group">
                                        <strong class="">Apellido Materno:</strong>
                                        <div class="form-group">
                                            <input type="text" description="Ingrese el apellido materno" aria-describedby="" id="ape_materno" required name="ape_materno" value="<?= ucwords(strtolower($oEmpleados->ape_materno)) ?>" class="form-control obligado" />
                                        </div>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="form-group">
                                        <strong class="">Fecha Nacimiento:</strong>
                                        <div class="form-group">
                                            <input type="date" description="Seleccione la fecha de nacimiento" aria-describedby="" id="fecha_nacimiento" required name="fecha_nacimiento" value="<?= $oEmpleados->fecha_nacimiento ?>" class="form-control obligado" />
                                        </div>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="form-group">
                                        <strong class="">Fecha Ingreso:</strong>
                                        <div class="form-group">
                                            <input type="date" description="Seleccione la fecha de ingreso" aria-describedby="" id="fecha_ingreso" required name="fecha_ingreso" value="<?= $oEmpleados->fecha_ingreso ?>" class="form-control obligado" />
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col">
                                    <div class="form-group">
                                        <strong class="">RFC:</strong>
                                        <div class="form-group">
                                            <input type="text" description="Ingrese el RFC" aria-describedby="" id="rfc" required name="rfc" value="<?= $oEmpleados->rfc ?>" class="form-control obligado" />
                                        </div>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="form-group">
                                        <strong class="">CURP:</strong>
                                        <div class="form-group">
                                            <input type="text" description="Ingrese la CURP" aria-describedby="" id="curp" required name="curp" value="<?= $oEmpleados->curp ?>" class="form-control obligado" />
                                        </div>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="form-group">
                                        <strong class="">NSS:</strong>
                                        <div class="form-group">
                                            <input type="text" description="Ingrese el NSS" aria-describedby="" id="nss" required name="nss" value="<?= $oEmpleados->nss ?>" class="form-control obligado" />
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div id="tab2" class="tab-pane fade">
                            <div class="row">
                                <div class="col">
                                    <div class="form-group">
                                        <strong class="">Puestos:</strong>
                                        <div class="form-group">
                                            <select id="id_puesto" description="Seleccione el puesto" class="form-control obligado" name="id_puesto">
                                                <?php
                                                if (count($lstpuestos) > 0) {
                                                    echo "<option value='0' >-- SELECCIONE --</option>\n";
                                                    foreach ($lstpuestos as $idx => $campo) {
                                                        if ($campo->id == $oEmpleados->id_puesto) {
                                                            echo "<option value='{$campo->id}' selected>{$campo->nombre}</option>\n";
                                                        } else {
                                                            echo "<option value='{$campo->id}' >{$campo->nombre}</option>\n";
                                                        }
                                                    }
                                                }
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <strong class="">Jefe Directo:</strong>
                                <div class="form-group">
                                    <select id="id_jefe" description="Seleccione el jefe directo" class="form-control obligado" name="id_jefe">
                                        <?php
                                        if (count($lstJefes) > 0) {
                                            echo "<option value='0' >-- SELECCIONE --</option>\n";
                                            foreach ($lstJefes as $idx => $campo) {
                                                if ($campo->id == $oEmpleados->id_jefe) {
                                                    echo "<option value='{$campo->id}' selected>{$campo->empleado}</option>\n";
                                                } else {
                                                    echo "<option value='{$campo->id}' >{$campo->empleado}</option>\n";
                                                }
                                            }
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group">
                                <strong class="">Reloj checador:</strong>
                                <div class="form-group">
                                    <input type="text" description="Conecte el lector para leer el numero" aria-describedby="" id="checador" required name="checador" value="<?= $oEmpleados->checador ?>" class="form-control obligado" />
                                </div>
                            </div>
                            <div class="form-group">
                                <input id="activeSensorLocal" onclick="upSensor()" style="margin-top: 5px;" type="button" value="Asociar Huella" class="btn btn-outline-primary btn-block" />
                            </div>
                            <div class="form-group">
                                <div id="fingerPrint" class="form-group" style="display:none;">
                                    <div class="row">
                                        <div class="col">
                                            <?php
                                            if ($oEmpleados->id != "") {
                                                if (!($lstHuellas == NULL)) {
                                                    if (count($lstHuellas) > 0) {
                                                        $indiceHuella = "";
                                                        foreach ($lstHuellas as $idx => $campo) {
                                                            $indiceHuella .= $campo->nombre_dedo . " ";
                                                        }
                                                        echo "Huellas Registradas: <span> " . $indiceHuella . "</span> <br>";
                                                    }
                                                }
                                            }
                                            ?>
                                            <img class="img-responsive id_token" style="border: solid; width:30%;" src="app/views/default/img/finger.gif">
                                        </div>
                                        <div class="col">
                                            <div class="form-group">
                                                <select id="nombre_dedo" description="Seleccione el dedo a registrar" class="form-control" name="nombre_dedo">
                                                    <option value=''>--SELECCIONE--</option>
                                                    <option value='A'>A</option>
                                                    <option value='B'>B</option>
                                                    <option value='C'>C</option>
                                                    <option value='D'>D</option>
                                                    <option value='E'>E</option>
                                                    <option value='F'>F</option>
                                                    <option value='G'>G</option>
                                                    <option value='H'>H</option>
                                                    <option value='I'>I</option>
                                                    <option value='J'>J</option>
                                                </select>
                                            </div>
                                            <img class="img-responsive" style="border: solid; width:80%;" src="app/views/default/img/dedos.png">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="_status">
                                            Estado del sensor: Inactivo
                                        </label>
                                        <div class="form-group">
                                            <strong class="_texto">
                                                ---
                                            </strong>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <strong class="">Horario:</strong>
                                <div class="form-group">
                                    <select id="id_horario" description="Seleccione el horario" class="form-control obligado" name="id_horario">
                                        <?php
                                        if (count($lsthorarios) > 0) {
                                            echo "<option value='0' >-- SELECCIONE --</option>\n";
                                            foreach ($lsthorarios as $idx => $campo) {
                                                if ($campo->id == $oEmpleados->id_horario) {
                                                    echo "<option value='{$campo->id}' selected>{$campo->nombre}</option>\n";
                                                } else {
                                                    echo "<option value='{$campo->id}' >{$campo->nombre}</option>\n";
                                                }
                                            }
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <input type="checkbox" name="extras" value="1" <?php if ($oEmpleados->extras == 1) echo "checked" ?>><strong> Horas extras</strong>

                        </div>
                        <div id="tab3" class="tab-pane fade">
                            <div class="card-header py-3">
                                <center>
                                    <strong class="center">Desglose de sueldo</strong>
                                </center>
                                <div class="row">
                                    <div class="col">
                                        <strong class="">Sueldo Base Diario:</strong>
                                        <div class="input-group mb-3">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">$</span>
                                            </div>
                                            <input type="text" description="Ingrese sueldo base" aria-describedby="" id="salario_diario" required name="salario_diario" value="<?= $oEmpleados->salario_diario ?>" class="form-control obligado" />
                                        </div>
                                    </div>
                                    <div class="col">
                                        <strong class=""> Premio de asistencia:</strong>
                                        <div class="input-group mb-3">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">$</span>
                                            </div>
                                            <input type="text" description="Ingrese premio de asistencia" aria-describedby="" id="salario_asistencia" required name="salario_asistencia" value="<?= $oEmpleados->salario_asistencia ?>" class="form-control " />
                                        </div>
                                    </div>
                                    <div class="col">
                                        <strong class=""> Premio de puntualidad:</strong>
                                        <div class="input-group mb-3">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">$</span>
                                            </div>
                                            <input type="text" description="Ingrese premio de puntualidad" aria-describedby="" id="salario_puntualidad" required name="salario_puntualidad" value="<?= $oEmpleados->salario_puntualidad ?>" class="form-control " />
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col">
                                        <strong class=""> Premio de productividad:</strong>
                                        <div class="input-group mb-3">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">$</span>
                                            </div>
                                            <input type="text" description="Ingrese premio de productividad" aria-describedby="" id="salario_productividad" required name="salario_productividad" value="<?= $oEmpleados->salario_productividad ?>" class="form-control " />
                                        </div>
                                    </div>
                                    <div class="col">
                                        <strong class=""> Bono 12 horas:</strong>
                                        <div class="input-group mb-3">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">$</span>
                                            </div>
                                            <input type="text" description="" aria-describedby="" id="bono_doce" name="bono_doce" value="<?= $oEmpleados->bono_doce ?>" class="form-control" />
                                        </div>
                                    </div>
                                    <div class="col">
                                        <strong class=""> Complemento de sueldo:</strong>
                                        <div class="input-group mb-3">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">$</span>
                                            </div>
                                            <input type="text" description="" aria-describedby="" id="complemento_sueldo" name="complemento_sueldo" value="<?= $oEmpleados->complemento_sueldo ?>" class="form-control" />
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div id="tab4" class="tab-pane fade">
                        </div>
                        <div id="tab5" class="tab-pane fade">
                            <div class="form-group">
                                <strong class="">Dirección:</strong>
                                <div class="form-group">
                                    <input type="text" description="Ingrese la dirección" aria-describedby="" id="direccion" name="direccion" value="<?= $oEmpleados->direccion ?>" class="form-control obligado" data-toggle="tooltip" title="" data-original-title="Escribir la direccion completa" />
                                </div>
                            </div>
                            <div class="row">
                                <div class="col">
                                    <div class="form-group">
                                        <strong class="">Estado Civil:</strong>
                                        <div class="form-group">
                                            <select id="estado_civil" description="Seleccione el estado civil" class="form-control obligado" name="estado_civil">
                                                <option value='0'>--SELECCIONE--</option>
                                                <option value='1' <?php if ($oEmpleados->estado_civil == 1) echo "selected" ?>>Soltero/a</option>
                                                <option value='2' <?php if ($oEmpleados->estado_civil == 2) echo 'Selected'; ?>>Casado/a</option>
                                                <option value='3' <?php if ($oEmpleados->estado_civil == 3) echo 'Selected'; ?>>Unión libre</option>
                                                <option value='4' <?php if ($oEmpleados->estado_civil == 4) echo 'Selected'; ?>>Separado/a</option>
                                                <option value='5' <?php if ($oEmpleados->estado_civil == 5) echo 'Selected'; ?>>Divorciado/a</option>
                                                <option value='6' <?php if ($oEmpleados->estado_civil == 6) echo 'Selected'; ?>>Viudo/a.</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col">
                                    <div class="form-group">
                                        <strong class="">Nivel de Estudios:</strong>
                                        <div class="form-group">
                                            <select id="nivel_estudios" description="Seleccione el nivel de estudios" class="form-control obligado" name="nivel_estudios">
                                                <option value='0'>--SELECCIONE--</option>
                                                <option value='1' <?php if ($oEmpleados->nivel_estudios == 1) echo "selected" ?>>Primaria</option>
                                                <option value='2' <?php if ($oEmpleados->nivel_estudios == 2) echo "selected" ?>>Secundaria</option>
                                                <option value='3' <?php if ($oEmpleados->nivel_estudios == 3) echo "selected" ?>>Preparatoria</option>
                                                <option value='4' <?php if ($oEmpleados->nivel_estudios == 4) echo "selected" ?>>Ingenieria</option>
                                                <option value='5' <?php if ($oEmpleados->nivel_estudios == 5) echo "selected" ?>>Licenciatura/a</option>
                                                <option value='6' <?php if ($oEmpleados->nivel_estudios == 6) echo "selected" ?>>Maestria</option>
                                                <option value='7' <?php if ($oEmpleados->nivel_estudios == 7) echo "selected" ?>>Doctorado</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div id="tab6" class="tab-pane fade">
                        <div class="card-header py-3">
                                <center>
                                    <strong class="center">Datos bancarios del empleado: </strong>
                                </center>
                                <div class="row">
                                    <div class="col">
                                        <strong class="">Cuenta:</strong>
                                        <div class="input-group mb-3">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"></span>
                                            </div>
                                            <input type="text" description="Ingrese cuenta en Datos bancarios" id="empleado_cuenta" required name="empleado_cuenta" onkeypress="return solonumeros(event);" value="<?= $oEmpleados->empleado_cuenta ?>" class="form-control obligado" />
                                        </div>
                                    </div>
                                    <div class="col">
                                        <strong class="">Clabe interbancaria:</strong>
                                        <div class="input-group mb-3">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"></span>
                                            </div>
                                            <input type="text" description="Ingrese clabe interbancaria en Datos Bancarios" id="empleado_clabe" required name="empleado_clabe"  onkeypress="return solonumeros(event);" value="<?= $oEmpleados->empleado_clabe ?>"  class="form-control obligado" />
                                        </div>
                                    </div>
                                    <div class="col">
                                        <strong class=""> Numero de tarjeta:</strong>
                                        <div class="input-group mb-3">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"></span>
                                            </div>
                                            <input type="text" description="Ingrese numero tarjeta en Datos bancarios" id="empleado_tarjeta" required name="empleado_tarjeta" value="<?= $oEmpleados->empleado_tarjeta ?>" class="form-control obligado" />
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <input type="hidden" id="id" name="id" value="<?= $oEmpleados->id ?>" />
        <input type="hidden" id="user_id" name="user_id" value="<?= $sesion->id ?>">
        <input type="hidden" id="token" name="token" value="">
        <input type="hidden" id="accion" name="accion" value="GUARDAR" />
    </div>
</form>