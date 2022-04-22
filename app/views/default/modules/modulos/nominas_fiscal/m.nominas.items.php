<?php
session_start();
$_SITE_PATH = $_SERVER["DOCUMENT_ROOT"] . "/" . explode("/", $_SERVER["PHP_SELF"])[1] . "/";
require_once($_SITE_PATH . "app/model/nominas.class.php");
require_once($_SITE_PATH . "app/model/empleados.class.php");
require_once($_SITE_PATH . "app/model/puestos.class.php");
require_once($_SITE_PATH . "app/model/departamentos.class.php");
require_once($_SITE_PATH . "app/model/otros.class.php");
require_once($_SITE_PATH . "app/model/prestamos.class.php");
require_once($_SITE_PATH . "app/model/horas.class.php");
require_once($_SITE_PATH . "app/model/ahorros.class.php");


$oNominas = new nominas_fiscal();
$oNominas->id = empty($_GET['id']) ? "" : $_GET['id'];
$oNominas->id_empleado = empty($_GET['id_empleado']) ? "" : $_GET['id_empleado'];
$lstnominas = $oNominas->Listado_prenomina();

$oEmpleados = new empleados();
$oEmpleados->id = $oNominas->id_empleado;
$oEmpleados->Informacion();

$oPuestos = new puestos();
$oPuestos->id = $oEmpleados->id_puesto;
$oPuestos->Informacion();

$oDepartamentos = new departamentos();
$oDepartamentos->id = $oPuestos->id_departamento;
$oDepartamentos->Informacion();

$oNominas_edit = new nominas_fiscal();
$oNominas_edit->id_nomina = $oNominas->id_nomina;
$oNominas_edit->id_empleado = $oNominas->id_empleado;
$oNominas_edit->Nomina_edit();


    if ($oNominas_edit->nombre != '' && $oNominas_edit->estatus_final_edit == "2") {
        $id_nomina = $oNominas_edit->id_nomina;
        $id_empleado = $oNominas_edit->id_empleado;
        $nombre = $oNominas_edit->nombre;
        $diario = $oNominas_edit->diario;
        $faltas = $oNominas_edit->faltas;
        $asistencias = $oNominas_edit->asistencias;
        $total = $oNominas_edit->total;
        $total_p = $oNominas_edit->total_p;
        $fecha = $oNominas_edit->fecha;
    } else {
        $id_nomina = $oNominas->id_nomina;
        $id_empleado = $oNominas->id_empleado;
        $nombre = $oNominas->nombre;
        $diario = $oNominas->diario;
        $faltas = $oNominas->faltas;
        $asistencias = $oNominas->asistencias;
        $total = $oNominas->total;
        $total_p = $oNominas->total_p;
        $fecha = $oNominas->fecha;
    }
$totalaPagar = $total_p;
$totalaRetencion = $total_r;
?>
<style>

</style>
<page backtop="10mm" backbottom="10mm" backleft="10mm" backright="10mm" style="border: #00 1px solid;">
    <table style="margin-top: -10px;">
        <tr>
            <td>
                <label style="margin-left:150px; font-size:18px;">RENOVA CHATARRAS INDUSTRIALES S.A. DE C.V</label>
            </td>
        </tr>
        <br />
    </table>
    <table border="1" style="margin-left:10px; position:relative;">
        <thead>
            <tr>
                <h1>Recibo de nómina</h1>
            </tr>
        </thead>
    </table>
    <table style="margin-left:270px; position:relative; margin-top: -4px;">
        <thead>
            <tr>
                <td>RFC: RC112222255522</td>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>IMSS: A407855596</td>
            </tr>
        </tbody>
    </table>
    <table border="1" style="margin-left:460px; position:relative; margin-top: -30px; border-collapse: collapse; width: 310px;">
        <thead>
            <tr>
                <th style="color: #f3eded; background-color: #000;width:135px;">Frecuancia de pago</th>
                <th style="color: #f3eded; background-color: #000;width:135px;">Fecha</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>SEMANAL</td>
                <td><?= $fecha ?></td>
            </tr>
        </tbody>
    </table>
    <table style="margin-left:0px; position:relative; margin-top: 10px; border: #00 1px solid;">
        <tr>
            <th style="color: #f3eded; background-color: #000;width:450px;  text-align:center;" COLSPAN=2>Empleado</th>
        </tr>
        <tr>
            <td><Label>Nombre:</Label></td>
            <td><?= $nombre ?></td>
        </tr>
        <tr>
            <td><Label>Puesto:</Label></td>
            <td><?= $oPuestos->nombre ?></td>
        </tr>
        <tr>
            <td><Label>Departamento:</Label></td>
            <td><?= $oDepartamentos->nombre ?></td>
        </tr>
        <tr>
            <td><Label>RFC:</Label></td>
            <td><?= $oEmpleados->rfc ?></td>
        </tr>
        <tr>
            <td><Label>CURP:</Label></td>
            <td><?= $oEmpleados->curp ?></td>
        </tr>
    </table>
    <table style="margin-left:458px; position:relative; margin-top: -112px; border: #00 1px solid;">
        <tr>
            <th style="color: #f3eded; background-color: #000;width:275px; text-align:center;" COLSPAN=2>Seguridad Social</th>
        </tr>
        <tr>
            <td><Label>Registro:</Label></td>
            <td></td>
        </tr>
        <tr>
            <td><Label>Tipo de salario:</Label></td>
            <td>Fijo</td>
        </tr>
        <tr>
            <td><Label>Salario integrado:</Label></td>
            <td ><?= $oEmpleados->salario_diario ?> diario</td>
        </tr>
        <tr>
            <td><Label>Jornada:</Label></td>
            <td>8:00 horas</td>
        </tr>
        <tr>
            <td><Label>Fecha de ingreso:</Label></td>
            <td><?= $oNominas->fecha_ingreso ?></td>
        </tr>
    </table>
    <div style="border: #00 1px solid; position:relative; margin-top: -1px;">
        <table style="margin-left:0px; position:relative; margin-top: 0px; ">
            <tr>
                <th style="color: #f3eded; width:175px; background-color: #000; text-align:center;">Percepción</th>
                <th style="color: #f3eded; width:85px; background-color: #000; text-align:center;">Monto</th>
                <th style="color: #f3eded; width:60px; background-color: #000; text-align:center;">Unidades</th>
            </tr>
            <tr>
                <td><Label>Sueldo normal</Label></td>
                <td style="text-align:right"><?= $asistencias * $diario ?></td>
                <td style="text-align:right"><?= $asistencias ?> dias</td>
            </tr>
            <tr>
                <td><Label>&nbsp;</Label></td>
                <td>&nbsp;</td>
            </tr>
            <tr>
                <td><Label>&nbsp;</Label></td>
                <td>&nbsp;</td>
            </tr>
            <tr>
                <td><Label>&nbsp;</Label></td>
                <td>&nbsp;</td>
            </tr>
            <tr>
                <td><Label>&nbsp;</Label></td>
                <td>&nbsp;</td>
            </tr>
            <tr>
                <td><Label>&nbsp;</Label></td>
                <td>&nbsp;</td>
            </tr>
        </table>
        <!--segunda parte -->  
        <table style="margin-left:333px; position:absolute; margin-top: 1px; ">
            <tr>
                <th style="margin-left:10px; width:134px;color: #f3eded; background-color: #000; text-align:center;">Concepto</th>
                <th style="color: #f3eded; width:80px; background-color: #000;  text-align:center;">Monto</th>
                <th style="color: #f3eded; width:80px; background-color: #000; text-align:center;">Retención</th>
                <th style="color: #f3eded; width:100px; background-color: #000; text-align:center;">Saldo</th>
            </tr>
        </table>
    </div>
    <table style="margin-left:0px; position:relative; margin-top: -1px; border: #00 1px solid;">
        <tr>
            <td style="width:529px;"><Label style=" font-size:12px;">Recibí de esta empresa la cantidad que señala este recibo de pago, estando conforme con las<br>
                    percepciones y las retenciones descritas, por lo que certifico que no se me adeuda cantidad alguna<br>
                    por ningún concepto.</Label></td>
        </tr>
        <tr>
            <td style=" height: 40px;width:190px; text-align:center;"><Label>______________________________________<br>Firma del empleado</Label></td>
        </tr>
    </table>
    <table style="margin-left:538px; position:relative; margin-top: -93px; border: #00 1px solid; heig">
        <tr>
            <td style="font-size:14px;">Total de<br> percepciones: </td>
            <td style="width=101px; text-align:right;"><?= bcdiv($totalaPagar + $totalaRetencion , '1', 2) ?></td>
        </tr>
        <tr>
            <td style="font-size:15px;">Total de <br>retenciones: </td>
            <td style="text-align:right"><u>-<?= bcdiv($totalaRetencion, '1', 2)  ?>&nbsp;</u></td>
        </tr>
        <tr>
            <td >Pago: </td>
            <td style="text-align: right;"><?= bcdiv($totalaPagar, '1', 2) ?></td>
        </tr>

    </table>
    <table style="margin-left:0px; position:relative; margin-top: -1px; border: #00 1px solid;">
        <tr>
            <td><Label>&nbsp;</Label></td>
            <td><Label>&nbsp;</Label></td>
            <td><Label>&nbsp;</Label></td>
        </tr>
        <tr>
            <td><Label>&nbsp;</Label></td>
            <td><Label>&nbsp;</Label></td>
            <td><Label>&nbsp;</Label></td>
        </tr>
        <tr>
            <td><Label>&nbsp;</Label></td>
            <td><Label>&nbsp;</Label></td>
            <td><Label>&nbsp;</Label></td>
        </tr>
        <tr>
            <td style="width:730px; text-align:center; " colspan="3"><Label>Este pago de nómina no cuenta con un CFDI generado y certificado.</Label></td>
        </tr>
        <tr>
            <td><Label>&nbsp;</Label></td>
            <td><Label>&nbsp;</Label></td>
            <td><Label>&nbsp;</Label></td>
        </tr>
        <tr>
            <td><Label>&nbsp;</Label></td>
            <td><Label>&nbsp;</Label></td>
            <td><Label>&nbsp;</Label></td>
        </tr>
        <tr>
            <td><Label>&nbsp;</Label></td>
            <td><Label>&nbsp;</Label></td>
            <td><Label>&nbsp;</Label></td>
        </tr>
    </table>
</page>