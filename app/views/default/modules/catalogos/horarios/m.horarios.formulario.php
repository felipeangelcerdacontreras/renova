<?php
/*
 * Copyright 2021 - Felipe angel cerda contreras 
 * felipeangelcerdacontreras@gmail.com
 */
session_start();

$_SITE_PATH = $_SERVER["DOCUMENT_ROOT"] . "/" . explode("/", $_SERVER["PHP_SELF"])[1] . "/";
require_once($_SITE_PATH . "/app/model/horarios.class.php");

$oHorarios = new horarios();
$oHorarios->id = addslashes(filter_input(INPUT_POST, "id"));
$nombre = addslashes(filter_input(INPUT_POST, "nombre"));
$sesion = $_SESSION[$oHorarios->NombreSesion];
$oHorarios->Informacion();

?>
<script type="text/javascript">
    $(document).ready(function(e) {
        $("#nameModal").text("<?php echo $nombre ?> Horario");
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
            }
        });

        $('#entrada').change(calculardiferencia);
        $('#salida').change(calculardiferencia);
        calculardiferencia();

    });

    function newDate(partes) {
        var date = new Date(0);
        date.setHours(partes[0]);
        date.setMinutes(partes[1]);
        return date;
    }

    function prefijo(num) {
        return num < 10 ? ("0" + num) : num;
    }

    function calculardiferencia() {
        var dateDesde = newDate($('#entrada').val().split(":"));
        var dateHasta = newDate($('#salida').val().split(":"));

        var minutos = (dateHasta - dateDesde) / 1000 / 60;
        var horas = Math.floor(minutos / 60);
        minutos = minutos % 60;
        console.log(prefijo(horas)*6);

        $('#resultado').val(prefijo(horas) + ':' + prefijo(minutos));
    }
</script>
<form id="frmFormulario" name="frmFormulario" action="app/views/default/modules/catalogos/horarios/m.horarios.procesa.php" enctype="multipart/form-data" method="post" target="_self" class="form-horizontal">
    <div>
        <div class="form-group">
            <strong class="">Nombre:</strong>
            <div class="form-group">
                <input type="text" description="Ingrese el nombre" aria-describedby="" id="nombre" name="nombre" value="<?= $oHorarios->nombre ?>" class="form-control obligado" />
            </div>
        </div>
        <strong class="">Asignacion de horario: </strong>
        <div class="row" style="margin-left: 1%;">
            &nbsp;
        </div>
        <div class="row" style="margin-left: 1%;">
            <div class="col">
                <input class="form-check-input" type="checkbox" id="A" name="A" <?php if ($oHorarios->A == 1) echo "checked" ?> value="1">
                <label class="form-check-label" for="A">Lunes</label>
            </div>
            <div class="col">
                <input class="form-check-input" type="checkbox" id="B" name="B" <?php if ($oHorarios->B == 1) echo "checked" ?> value="1">
                <label class="form-check-label" for="B">Martes</label>
            </div>
            <div class="col">
                <input class="form-check-input" type="checkbox" id="C" name="C" <?php if ($oHorarios->C == 1) echo "checked" ?> value="1">
                <label class="form-check-label" for="C">Miercoles</label>
            </div>
            <div class="col">
                <input class="form-check-input" type="checkbox" id="D" name="D" <?php if ($oHorarios->D == 1) echo "checked" ?> value="1">
                <label class="form-check-label" for="D">Jueves</label>
            </div>
            <div class="col">
                <input class="form-check-input" type="checkbox" id="E" name="E" <?php if ($oHorarios->E == 1) echo "checked" ?> value="1">
                <label class="form-check-label" for="E">Viernes</label>
            </div>
            <div class="col">
                <input class="form-check-input" type="checkbox" id="F" name="F" <?php if ($oHorarios->F == 1) echo "checked" ?> value="1">
                <label class="form-check-label" for="F">Sabado</label>
            </div>
            <div class="col">
                <input class="form-check-input" type="checkbox" id="G" name="G" <?php if ($oHorarios->G == 1) echo "checked" ?> value="1">
                <label class="form-check-label" for="G">Domingo</label>
            </div>
        </div>
        <div class="row" style="margin-left: 1%;">
            &nbsp;
        </div>
        <div class="row">
            <div class="col">
                <div class="form-group">
                    <strong class="text-success">Entrada:</strong>
                    <div class="form-group">
                        <input type="time" description="Seleccione una hora de entrada" aria-describedby="" id="entrada" name="entrada" value="<?= $oHorarios->entrada ?>" class="form-control obligado" />
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="form-group">
                    <strong class="text-warning">Inicio Comida:</strong>
                    <div class="form-group">
                        <input type="time" description="Seleccione una hora de inicio de comida" aria-describedby="" id="comida_1" name="comida_1" value="<?= $oHorarios->comida_1 ?>" class="form-control obligado" />
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="form-group">
                    <strong class="text-warning">Termino Comida:</strong>
                    <div class="form-group">
                        <input type="time" description="Seleccione una hora de termino de comida" aria-describedby="" id="comida_2" name="comida_2" value="<?= $oHorarios->comida_2 ?>" class="form-control obligado" />
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="form-group">
                    <strong class="text-danger">Salida:</strong>
                    <div class="form-group">
                        <input type="time" description="Seleccione una hora de salida" aria-describedby="" id="salida" name="salida" value="<?= $oHorarios->salida ?>" class="form-control obligado" />
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col">
                <div class="form-group">
                    <strong class="">Horas por dia:</strong><br>
                    <strong class="">&nbsp;</strong>
                    <div class="form-group">
                        <input type="text" description="" readonly aria-describedby="" id="resultado" class="form-control" />
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="form-group">
                    <strong class="">Minimo de horas a cumplir:</strong>
                    <div class="form-group">
                        <input type="int" description="Ingrese las horas a cumplir" maxlength="2" onkeypress="return solonumeros(event);" aria-describedby="" id="horas_cumplir" name="horas_cumplir" value="<?= $oHorarios->horas_cumplir ?>" class="form-control obligado" />
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="form-group">
                    <strong class="">Tiempo tolerancia Entrada:</strong>
                    <div class="form-group">
                        <input type="time" description="" aria-describedby="" id="tiempo_tolerancia" name="tiempo_tolerancia" value="<?= $oHorarios->tiempo_tolerancia ?>" class="form-control" />
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="form-group">
                    <strong class="">Horas extras apartir de:</strong>
                    <div class="form-group">
                        <input type="time" description="" aria-describedby="" id="horas_extra" name="horas_extra" value="<?= $oHorarios->horas_extra ?>" class="form-control" />
                    </div>
                </div>
            </div>
        </div>
        <input type="hidden" id="id" name="id" value="<?= $oHorarios->id ?>" />
        <input type="hidden" id="user_id" name="user_id" value="<?= $sesion->id ?>">
        <input type="hidden" id="accion" name="accion" value="GUARDAR" />
    </div>
</form>