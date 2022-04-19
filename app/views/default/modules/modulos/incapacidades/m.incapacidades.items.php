<?php
session_start();
$_SITE_PATH = $_SERVER["DOCUMENT_ROOT"] . "/" . explode("/", $_SERVER["PHP_SELF"])[1] . "/";
require_once($_SITE_PATH . "app/model/incapacidades.class.php");
require_once($_SITE_PATH . "app/model/empleados.class.php");
require_once($_SITE_PATH . "app/model/puestos.class.php");
require_once($_SITE_PATH . "app/model/departamentos.class.php");
require_once($_SITE_PATH . "vendor/autoload.php");

use Carbon\Carbon;

$oIncapacidades = new incapacidades(true, $_POST);
$oIncapacidades->id = empty($_GET['id']) ? "" : $_GET['id'];
$oIncapacidades->Informacion();

$oEmpleados = new empleados();
$oEmpleados->id = $oIncapacidades->id_empleado;
$oEmpleados->Informacion();

$años = $oIncapacidades->ObtenerAños($oEmpleados->fecha_ingreso);

$dias = $oIncapacidades->ObtenerDias($años[0]->años_transcurridos, $oIncapacidades->id_empleado);

$oPuestos = new puestos();
$oPuestos->id = $oEmpleados->id_puesto;
$oPuestos->Informacion(); //id_departamento

$oDepartamentos = new departamentos();
$oDepartamentos->id = $oPuestos->id_departamento;
$oDepartamentos->Informacion(); //id_departamento
?>
<style>
    #encabezado .fila #col_1 {
        width: 50.3%;

    }

    #encabezado .fila #col_2 {
        width: 50.3%;
    }

    #encabezado .fila #col_0 {
        width: 30%;
    }

    #encabezadosup {
        padding: 5px 0;
        margin-top: -30px;
        margin-left: -30px;
        border-top: 0px solid;
        border-bottom: 0px solid;
        border: 0px solid;
        width: 100%;
    }

    #encabezado {
        padding: 5px 0;
        margin-left: -30px;
        border-top: 0px solid;
        border-bottom: 0px solid;
        border: 0px solid;
        width: 100%;
    }

    #encabezado1 {
        padding: 5px 0;
        margin-left: -30px;
        border-top: 0px solid;
        border-bottom: 0px solid;
        border: 0px solid;
        width: 100%;
    }


    #encabezado .fila #ref1 {
        width: 52%;
    }

    #encabezado .fila #ref2 {
        width: 50%;
    }

    #encabezadosup .filasup #col_1 {
        width: 55%;
    }

    #encabezadosup .filasup #col_2 {
        width: 113%;
    }

    #encabezado .fila #col_2 {
        width: 113%;
        height: 10px;
    }

    #encabezadosup .filasup #col_3 {
        width: 10%;
        height: 6%
    }
</style>
<page backtop="10mm" backbottom="10mm" backleft="10mm" backright="10mm" style="border: #00 1px solid;">
    <table style="margin-top: -10px;border: #00 1px solid;">
        <tr>
            <th style="color: #f3eded; background-color: #000;width:700px;  text-align:center;" COLSPAN=2>FORMATO DE AUTORIZACIÓN PARA VACACIONES</th>
        </tr>
        <tr>
            <td>
                <Label style="font-size:11">Nombre de la empresa : 
                    <u style="font-size:11">RENOVA CHATARRAS INDUSTRIALES S.A. DE C.V</u> &nbsp;&nbsp;
                </Label>
                &nbsp;&nbsp;&nbsp;&nbsp;
                <Label style="font-size:11">Departamento: <u><?= $oDepartamentos->nombre ?></u></Label>
            </td>
        </tr>
        <tr>
            <td>
                <Label style="font-size:11">No. De Empleado: 
                    <u style="font-size:11"><?= $oEmpleados->checador ?></u> &nbsp;&nbsp;&nbsp; 
                </Label>
                &nbsp;&nbsp;
                <Label style="font-size:11">&nbsp;&nbsp;Nombre:</Label> 
                <U style="font-size:11"><?= $oEmpleados->nombres . " " . $oEmpleados->ape_paterno . " " . $oEmpleados->ape_materno ?></U>
            </td>
        </tr>
        <tr>
            <td>
                <Label style="font-size:11">Fecha de ingreso: <u><?= $oEmpleados->fecha_ingreso ?></u></Label>&nbsp;&nbsp;
                &nbsp;&nbsp;
                <Label style="font-size:11">Años de Servicio: <U style="font-size:11"><?= $años[0]->años_transcurridos ?></U></Label>&nbsp;&nbsp;
                &nbsp;&nbsp;
                <Label style="font-size:11">Dias que corresponde: <u style="font-size:11"><?= $dias[0]->dias ?></u></Label>&nbsp;&nbsp;
                &nbsp;&nbsp;
                <label style="font-size:11">Dias a Disfrutar: <u style="font-size:11"><?= $oIncapacidades->dias_disfrutar ?></u></label>
            </td>
        </tr>
        <tr>
            <td>
                <Label style="font-size:11">Dias restantes: &nbsp;&nbsp;<u style="font-size:11"><?= $oIncapacidades->dias_restantes ?></u></Label>&nbsp;&nbsp;
            </td>
        </tr>
        <tr>
            <td>
                <Label style="font-size:11">Periodo a Disfrutar: del año: <u style="font-size:11"> <?= $oIncapacidades->periodo_inicio ?> </u></Label>&nbsp;
                <label> al año: <u style="font-size:11"> <?= $oIncapacidades->periodo_fin ?> </u> </label>
            </td>
        </tr>
        <tr>
            <td>
                <?php
                $fecha_inicio = Carbon::parse($oIncapacidades->inicio_vacaci);
                $mfecha = $fecha_inicio->month;
                $dfecha = $fecha_inicio->day;
                $afecha = $fecha_inicio->year;
                ?>
                <Label style="font-size:11">Días en que inicia sus incapacidades:&nbsp; del: &nbsp;<u style="font-size:11"> <?= $dfecha ?> </u></Label>
                <label style="font-size:11"><u> <?= $oIncapacidades->MESES[$mfecha] ?> </u>&nbsp; del </label>&nbsp;
                <u style="font-size:11"> <?= $afecha ?> </u>
            </td>
        </tr>
        <tr>
            <td>
                <?php
                $fecha_fin = Carbon::parse($oIncapacidades->fin_vacaci);
                $mfecha = $fecha_fin->month;
                $dfecha = $fecha_fin->day;
                $afecha = $fecha_fin->year;
                ?>
                <Label style="font-size:11">Días en que inicia sus incapacidades: &nbsp;del:&nbsp;  <u style="font-size:11"> <?= $dfecha ?> </u></Label>
                <label style="font-size:11"><u> <?= $oIncapacidades->MESES[$mfecha] ?> </u>&nbsp; del </label>&nbsp;
                <u style="font-size:11"> <?= $afecha ?> </u>
            </td>
        </tr>
        <tr>
            <td>
                <?php
                $fecha_fin = Carbon::parse($oIncapacidades->reingreso);
                $mfecha = $fecha_fin->month;
                $dfecha = $fecha_fin->day;
                $afecha = $fecha_fin->year;
                ?>
                <Label style="font-size:11">Fecha en que se debera presentar a trabajar: del: <u> <?= $dfecha ?> </u></Label>
                <label style="font-size:11"><u> <?= $oIncapacidades->MESES[$mfecha] ?> </u>&nbsp; del </label>&nbsp;
                <u style="font-size:11"><?= $afecha ?></u>
            </td>
        </tr>
        <tr>
            <td>
                <Label style="font-size:11">Pago de primas vacaionales: <u style="font-size:11"> <?= $oIncapacidades->pago_prima ?> </u></Label>&nbsp;&nbsp;
                <Label style="font-size:11">días de prima que se pagaron: <U> <?= $oIncapacidades->dias_pagados ?> </U></Label>&nbsp;&nbsp;
                <Label style="font-size:11">Fecha de pago de prima: <u> <?= $oIncapacidades->fecha_pago ?> </u></Label>&nbsp;&nbsp;
            </td>
        </tr>
        <tr>
            <td>&nbsp;</td>
        </tr>
        <tr>
            <td>
                <Label style="font-size:11">Observaciones: </Label>&nbsp;&nbsp;
            </td>
        </tr>
        <tr>
            <td><u style="font-size:11"><?= $oIncapacidades->observaciones ?></u></td>
        </tr>
    </table>
    <table style="margin-left:0px; width:719px; position:relative; margin-top: -0px; border: #00 1px solid;">
        <tr >
            <td COLSPAN=2 style="font-size:12;  text-align:center;">
                Por el presente expreso mi conformidad de solicitar y gozar mis incapacidades de acuerdo a lo que establece el									
                articulo <br />76 de la Ley Federal del Trabajo considerando los datos que se mencionan en esta autorización									
            </td>
        </tr>
        <tr >
            <td COLSPAN=2>
            </td>
        </tr>
        <tr>
            <?php
            $fecha_fin = Carbon::parse();
            $mfecha = $fecha_fin->month;
            $dfecha = $fecha_fin->day;
            $afecha = $fecha_fin->year;
            ?>
            <td >
                <label style="font-size:11"><u style="font-size:11"> TORREON COAH&nbsp;</u></label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                <label style="font-size:11"><u style="font-size:11">&nbsp;<?= $dfecha ?> de <?= $oIncapacidades->MESES[$mfecha] ?> de <?= $afecha ?> &nbsp;</u></label>
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                <label ><u>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                </u></label>
            </td>
        </tr>
        <tr>
            <td >
                <label style="font-size:11">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    Ciudad</label>
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                <label >Fecha</label>
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
               
            </td>
        </tr>
        <tr>
            <td>&nbsp;</td>
        </tr>
        <tr>
            <td >
                <label ><u>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                </u></label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                <label ><u>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                &nbsp;&nbsp;</u></label>
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                &nbsp;&nbsp;&nbsp;
                <label ><u>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                </u></label>
            </td>
        </tr>
        <tr>
            <td >
                <label style="font-size:7">
                FIRMA DE CONFORMIDAD DEL EMPLEADO</label>
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;

                <label style="font-size:7">FIRMA DE AUTORIZACION DEL GERENTE DE AREA</label>
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                <label style="font-size:7">Vo.Bo. RECURSOS HUMANOS</label>
               
            </td>
        </tr>
        <tr>
            <td style=" height: 40px;width:696px; text-align:center;"><Label><br></Label></td>
        </tr>
    </table>
    <!-- copia-->
    <table style="margin-top: 8px;border: #00 1px solid;">
    <tr>
            <th style="color: #f3eded; background-color: #000;width:700px;  text-align:center;" COLSPAN=2>FORMATO DE AUTORIZACIÓN PARA VACACIONES</th>
        </tr>
        <tr>
            <td>
                <Label style="font-size:11">Nombre de la empresa : 
                    <u style="font-size:11">RENOVA CHATARRAS INDUSTRIALES S.A. DE C.V</u> &nbsp;&nbsp;
                </Label>
                &nbsp;&nbsp;&nbsp;&nbsp;
                <Label style="font-size:11">Departamento: <u><?= $oDepartamentos->nombre ?></u></Label>
            </td>
        </tr>
        <tr>
            <td>
                <Label style="font-size:11">No. De Empleado: 
                    <u style="font-size:11"><?= $oEmpleados->checador ?></u> &nbsp;&nbsp;&nbsp; 
                </Label>
                &nbsp;&nbsp;
                <Label style="font-size:11">&nbsp;&nbsp;Nombre:</Label> 
                <U style="font-size:11"><?= $oEmpleados->nombres . " " . $oEmpleados->ape_paterno . " " . $oEmpleados->ape_materno ?></U>
            </td>
        </tr>
        <tr>
            <td>
                <Label style="font-size:11">Fecha de ingreso: <u><?= $oEmpleados->fecha_ingreso ?></u></Label>&nbsp;&nbsp;
                &nbsp;&nbsp;
                <Label style="font-size:11">Años de Servicio: <U style="font-size:11"><?= $años[0]->años_transcurridos ?></U></Label>&nbsp;&nbsp;
                &nbsp;&nbsp;
                <Label style="font-size:11">Dias que corresponde: <u style="font-size:11"><?= $dias[0]->dias ?></u></Label>&nbsp;&nbsp;
                &nbsp;&nbsp;
                <label style="font-size:11">Dias a Disfrutar: <u style="font-size:11"><?= $oIncapacidades->dias_disfrutar ?></u></label>
            </td>
        </tr>
        <tr>
            <td>
                <Label style="font-size:11">Dias restantes: &nbsp;&nbsp;<u style="font-size:11"><?= $oIncapacidades->dias_restantes ?></u></Label>&nbsp;&nbsp;
            </td>
        </tr>
        <tr>
            <td>
                <Label style="font-size:11">Periodo a Disfrutar: del año: <u style="font-size:11"> <?= $oIncapacidades->periodo_inicio ?> </u></Label>&nbsp;
                <label> al año: <u style="font-size:11"> <?= $oIncapacidades->periodo_fin ?> </u> </label>
            </td>
        </tr>
        <tr>
            <td>
                <?php
                $fecha_inicio = Carbon::parse($oIncapacidades->inicio_vacaci);
                $mfecha = $fecha_inicio->month;
                $dfecha = $fecha_inicio->day;
                $afecha = $fecha_inicio->year;
                ?>
                <Label style="font-size:11">Días en que inicia sus incapacidades:&nbsp; del: &nbsp;<u style="font-size:11"> <?= $dfecha ?> </u></Label>
                <label style="font-size:11"><u> <?= $oIncapacidades->MESES[$mfecha] ?> </u>&nbsp; del </label>&nbsp;
                <u style="font-size:11"> <?= $afecha ?> </u>
            </td>
        </tr>
        <tr>
            <td>
                <?php
                $fecha_fin = Carbon::parse($oIncapacidades->fin_vacaci);
                $mfecha = $fecha_fin->month;
                $dfecha = $fecha_fin->day;
                $afecha = $fecha_fin->year;
                ?>
                <Label style="font-size:11">Días en que inicia sus incapacidades: &nbsp;del:&nbsp;  <u style="font-size:11"> <?= $dfecha ?> </u></Label>
                <label style="font-size:11"><u> <?= $oIncapacidades->MESES[$mfecha] ?> </u>&nbsp; del </label>&nbsp;
                <u style="font-size:11"> <?= $afecha ?> </u>
            </td>
        </tr>
        <tr>
            <td>
                <?php
                $fecha_fin = Carbon::parse($oIncapacidades->reingreso);
                $mfecha = $fecha_fin->month;
                $dfecha = $fecha_fin->day;
                $afecha = $fecha_fin->year;
                ?>
                <Label style="font-size:11">Fecha en que se debera presentar a trabajar: del: <u> <?= $dfecha ?> </u></Label>
                <label style="font-size:11"><u> <?= $oIncapacidades->MESES[$mfecha] ?> </u>&nbsp; del </label>&nbsp;
                <u style="font-size:11"><?= $afecha ?></u>
            </td>
        </tr>
        <tr>
            <td>
                <Label style="font-size:11">Pago de primas vacaionales: <u style="font-size:11"> <?= $oIncapacidades->pago_prima ?> </u></Label>&nbsp;&nbsp;
                <Label style="font-size:11">días de prima que se pagaron: <U> <?= $oIncapacidades->dias_pagados ?> </U></Label>&nbsp;&nbsp;
                <Label style="font-size:11">Fecha de pago de prima: <u> <?= $oIncapacidades->fecha_pago ?> </u></Label>&nbsp;&nbsp;
            </td>
        </tr>
        <tr>
            <td>&nbsp;</td>
        </tr>
        <tr>
            <td>
                <Label style="font-size:11">Observaciones: </Label>&nbsp;&nbsp;
            </td>
        </tr>
        <tr>
            <td><u style="font-size:11"><?= $oIncapacidades->observaciones ?></u></td>
        </tr>
    </table>
    <table style="margin-left:0px; width:719px; position:relative; margin-top: -0px; border: #00 1px solid;">
        <tr >
            <td COLSPAN=2 style="font-size:12;  text-align:center;">
                Por el presente expreso mi conformidad de solicitar y gozar mis incapacidades de acuerdo a lo que establece el									
                articulo <br />76 de la Ley Federal del Trabajo considerando los datos que se mencionan en esta autorización									
            </td>
        </tr>
        <tr >
            <td COLSPAN=2>
            </td>
        </tr>
        <tr>
            <?php
            $fecha_fin = Carbon::parse();
            $mfecha = $fecha_fin->month;
            $dfecha = $fecha_fin->day;
            $afecha = $fecha_fin->year;
            ?>
            <td >
                <label style="font-size:11"><u style="font-size:11"> TORREON COAH&nbsp;</u></label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                <label style="font-size:11"><u style="font-size:11">&nbsp;<?= $dfecha ?> de <?= $oIncapacidades->MESES[$mfecha] ?> de <?= $afecha ?> &nbsp;</u></label>
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                <label ><u>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                </u></label>
            </td>
        </tr>
        <tr>
            <td >
                <label style="font-size:11">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    Ciudad</label>
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                <label >Fecha</label>
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
               
            </td>
        </tr>
        <tr>
            <td>&nbsp;</td>
        </tr>
        <tr>
            <td >
                <label ><u>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                </u></label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                <label ><u>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                &nbsp;&nbsp;</u></label>
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                &nbsp;&nbsp;&nbsp;
                <label ><u>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                </u></label>
            </td>
        </tr>
        <tr>
            <td >
                <label style="font-size:7">
                FIRMA DE CONFORMIDAD DEL EMPLEADO</label>
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;

                <label style="font-size:7">FIRMA DE AUTORIZACION DEL GERENTE DE AREA</label>
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                <label style="font-size:7">Vo.Bo. RECURSOS HUMANOS</label>
               
            </td>
        </tr>
        <tr>
            <td style=" height: 40px;width:696px; text-align:center;"><Label><br></Label></td>
        </tr>
    </table>
</page>