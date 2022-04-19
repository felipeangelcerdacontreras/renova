<?php
/*
 * Copyright 2021 - Felipe angel cerda contreras 
 * felipeangelcerdacontreras@gmail.com
 */
session_start();

$_SITE_PATH = $_SERVER["DOCUMENT_ROOT"] . "/" . explode("/", $_SERVER["PHP_SELF"])[1] . "/";
require_once($_SITE_PATH . "/app/model/incapacidades.class.php");
require_once($_SITE_PATH . "/app/model/empleados.class.php");

$oIncapacidades = new incapacidades(true, $_POST);
$oIncapacidades->id = addslashes(filter_input(INPUT_POST, "id"));

$nombre = addslashes(filter_input(INPUT_POST, "nombre"));
$empleado = addslashes(filter_input(INPUT_POST, "empleado"));

$sesion = $_SESSION[$oIncapacidades->NombreSesion];
$oIncapacidades->Informacion();

$oEmpleados = new empleados();
$lstEmpleados = $oEmpleados->Listado();


?>
<script type="text/javascript">
    $(document).ready(function(e) {
        
        
    });
</script>
<div>
        <div class="row">
            <div class="col">
                <div class="form-group">
                    <strong class="">Empleado:</strong>
                    <div class="form-group">
                        <?php if ($oIncapacidades->id_empleado != "") {
                            echo "<input type='hidden' name='id_empleado' value='$oIncapacidades->id_empleado' >";
                        } ?>
                        <select id="id_empleado" description="Seleccione el empleado" <?php if ($oIncapacidades->id_empleado != "") {
                                                                                            echo "disabled";
                                                                                        } ?> class="form-control obligado" onchange="DatosEmpleado()" name="id_empleado">
                            <?php
                            if (count($lstEmpleados) > 0) {
                                echo "<option value='0' >-- SELECCIONE --</option>\n";
                                foreach ($lstEmpleados as $idx => $campo) {
                                    if ($campo->id == $oIncapacidades->id_empleado) {
                                        echo "<option value='{$campo->id}' selected>" . $campo->nombres . " " . $campo->ape_paterno . " " . $campo->ape_materno . "</option>\n";
                                    } else {
                                        echo "<option value='{$campo->id}'>" . $campo->nombres . " " . $campo->ape_paterno . " " . $campo->ape_materno . "</option>\n";
                                    }
                                }
                            }
                            ?>
                        </select>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col">
                <div class="form-group">
                    <strong class="">Tipo de incapacidad:</strong>
                        <div class="form-group">
                            <select id="tipo_incapacidad" name="tipo_incapacidad" description="Seleccione los dias a disfrutar" class="form-control obligado" <?php if ($oIncapacidades->tipo_incapacidad != "") {
                                                                                            echo "disabled";
                                                                                        } ?>>
                            <option value="">--SELECCIONE--</option>
                            <option value="1" 
                                <?php if ($oIncapacidades->tipo_incapacidad == "1") echo "selected";?>>Unica</option>
                            <option value="2" 
                                <?php if ($oIncapacidades->tipo_incapacidad == "2") echo "selected";?>>Inicial</option>
                            <option value="3" 
                                <?php if ($oIncapacidades->tipo_incapacidad == "3") echo "selected";?>>Subsecuente</option>
                            <option value="4" 
                                <?php if ($oIncapacidades->tipo_incapacidad == "4") echo "selected";?>>Alta Medica-ST2</option>
                            </select>
                        </div>
                </div>
            </div>
            <div class="col">
                <strong class="">Serie y Folio:</strong>
                <div class="form-group">
                    <input type="input" id="folio" name="folio" <?php if (!empty($oIncapacidades->folio)) {
                                                                                echo "readonly='true'";
                                                                            } ?> value="<?= $oIncapacidades->folio; ?>" class="form-control" onkeyup="javascript:this.value=this.value.toUpperCase();">
                </div>
            </div>
            <div class="col">
                <strong class="">Dias autorizados:</strong>
                <div class="form-group">
                    <input type="input" id="dias_autorizados" name="dias_autorizados" readonly='true' value="<?= $oIncapacidades->dias_autorizados ?>" class="form-control">
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col">
                <strong>A partir del día:</strong>
                <div class="form-group ">
                    <input type="date" id="inicio_incapacida" name="inicio_incapacida" <?php if ($oIncapacidades->inicio_incapacida != "") {
                                                                                    echo "readonly='true'";
                                                                                } ?> value="<?= $oIncapacidades->inicio_incapacida; ?>" class="form-control">
                </div>
            </div>
            <div class="col">
                <strong>Hasta el día:</strong>
                <div class="form-group">
                    <input type="date" id="fin_incapacida" name="fin_incapacida" <?php if (!empty($oIncapacidades->fin_incapacida)) {
                                                                                echo "readonly='true'";
                                                                            } ?> value="<?= $oIncapacidades->fin_incapacida; ?>" onchange="contador_dias()" class="form-control">
                </div>
            </div>
            <div class="col" style="display: none;">
                <strong>Dia que regresa a trabajar: </strong>
                <br />
                <div class="form-group">
                    <input type="date" id="reingreso" name="reingreso" <?php if (!empty($oIncapacidades->reingreso)) {
                                                                            echo "readonly='true'";
                                                                        } ?> value="<?= $oIncapacidades->reingreso; ?>" onchange="input_reingreso()" class="form-control">
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col">
            <div class="form-group">
                <strong class="">Ramo del seguro:</strong>
                <div class="form-group">
                    <select id="ramo_seguro" name="ramo_seguro" description="Seleccione los dias a disfrutar" class="form-control obligado" onchange="Porcentaje()" <?php if ($oIncapacidades->tipo_incapacidad != "") {
                                                                                            echo "disabled";
                                                                                        } ?>>>
                    <option value="">--SELECCIONE--</option>
                    <option value="1" 
                        <?php if ($oIncapacidades->ramo_seguro == "1") echo "selected";?>>Riesgo de trabajo</option>
                    <option value="2" 
                        <?php if ($oIncapacidades->ramo_seguro == "2") echo "selected";?>>Enfermedad general</option>
                    <option value="3" 
                        <?php if ($oIncapacidades->ramo_seguro == "3") echo "selected";?>>Maternidad</option>
                    </select>
                </div>
            </div>
        </div>
        <div class="col">
            <strong>Expedido del día: </strong>
            <div class="form-group">
                <input type="date" id="expedido" name="expedido" <?php if (!empty($oIncapacidades->expedido)) {
                                                                        echo "readonly='true'";
                                                                    } ?> value="<?= $oIncapacidades->expedido; ?>" class="form-control">
            </div>
        </div>
        <div class="col">
            <div class="form-group">
                <strong class="">Probable riesgo trabajo:</strong>
                <div class="form-group">
                    <select id="riesgo" name="riesgo" description="Seleccione los dias a disfrutar" class="form-control obligado" <?php if ($oIncapacidades->tipo_incapacidad != "") {
                                                                                            echo "disabled";
                                                                                        } ?>>
                    <option value="">--SELECCIONE--</option>
                    <option value="1" 
                        <?php if ($oIncapacidades->riesgo == "1") echo "selected";?>>Si</option>
                    <option value="2" 
                        <?php if ($oIncapacidades->riesgo == "2") echo "selected";?>>No</option>
                    </select>
                </div>
            </div>
        </div>
    </div>
    <div class="row" id="cantidad">
        <div class="col">
            <div class="form-group">
                <strong class="">Monto incapacidad(Seguro):</strong>
                <div class="form-group">
                <input type="text" onkeypress="return solonumeros(event);" onchange="Porcentaje()" onchange="" id="monto_incapacidad" name="monto_incapacidad" value="<?= $oIncapacidades->monto_incapacidad; ?>" 
                <?php if ($oIncapacidades->id != "") { echo "disabled"; } ?> class="form-control">
                </div>
            </div>
        </div>
        <div class="col">
            <div class="form-group">
                <strong class="">Monto Real(Renova):</strong>
                <div class="form-group">
                    <input type="text"readonly="true" id="monto_real" name="" value="" class="form-control">
                </div>
            </div>
        </div>
        <div class="col">
            <div class="form-group">
                <strong class="">Cantidad por día:</strong>
                <div class="form-group">
                    <input type="text" readonly="true" id="monto_dia" name="monto_dia" value="<?= $oIncapacidades->monto_dia; ?>" class="form-control">
                </div>
            </div>
        </div>
    </div>
    <div class="form-group">
        <div class="form-group">
            <strong>Observaciones:</strong>
        </div>
            <div class="form-group" style="width: 100%;">
                <textarea id="observaciones" name="observaciones" class="form-group" rows="3" style="width: 100%;" <?php if ($oIncapacidades->id != "") { echo "disabled"; } ?>><?= $oIncapacidades->observaciones ?></textarea>
            </div>
    </div>
    </div>
    <input type="hidden" id="id" name="id" value="<?= $oIncapacidades->id ?>" />
    <input type="hidden" id="user_id" name="user_id" value="<?= $sesion->id ?>">
    <input type="hidden" id="accion" name="accion" value="GUARDAR" />
    </div>