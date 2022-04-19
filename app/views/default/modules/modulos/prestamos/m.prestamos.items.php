<?php
session_start();
$_SITE_PATH = $_SERVER["DOCUMENT_ROOT"] . "/" . explode("/", $_SERVER["PHP_SELF"])[1] . "/";
require_once($_SITE_PATH . "app/model/Prestamos.class.php");
require_once($_SITE_PATH . "app/model/empleados.class.php");
require_once($_SITE_PATH . "app/model/puestos.class.php");
require_once($_SITE_PATH . "app/model/departamentos.class.php");

$oPrestamos = new Prestamos(true, $_POST);
$oPrestamos->id = empty($_GET['id']) ? "" : $_GET['id'];
$oPrestamos->id_empleado = empty($_GET['id_empleado']) ? "" : $_GET['id_empleado'];
$oPrestamos->fecha_registro = empty($_GET['fecha_registro']) ? "" : $_GET['fecha_registro'];
$lstprestamos = $oPrestamos->listado();

$oPrestamos2 = new Prestamos(true, $_POST);
$oPrestamos2->id = empty($_GET['id']) ? "" : $_GET['id'];
$oPrestamos2->Informacion();

$oEmpleados = new empleados();
$oEmpleados->id = $oPrestamos->id_empleado;
$oEmpleados->Informacion();

//puestos 

$oPuestos = new puestos();
$oPuestos->id = $oEmpleados->id_puesto;
$oPuestos->Informacion();//id_departamento

$oDepartamentos = new departamentos();
$oDepartamentos->id = $oPuestos->id_departamento;
$oDepartamentos->Informacion();//id_departamento
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
                <label style="font-size:18px; margin-left:290px;"><strong>PRESTAMO</strong></label>
            </tr>
        </thead>
    </table>
    <table style="margin-left:270px; position:relative; margin-top: -4px;">
        <thead>
            <tr>
                <td>&nbsp;&nbsp;&nbsp;</td>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>&nbsp;&nbsp;&nbsp;&nbsp;</td>
            </tr>
        </tbody>
    </table>
    <table style="margin-left:460px; position:relative; margin-top: -30px; border-collapse: collapse; width: 310px;">
        <thead>
            <tr>
                <th >&nbsp;</th>
                <th>&nbsp;&nbsp;</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>&nbsp;</td>
                <td></td>
            </tr>
        </tbody>
    </table>
    <table style="margin-left:0px; position:relative; margin-top: 10px; border: #00 1px solid;">
        <tr>
            <th style="color: #f3eded; background-color: #000;width:704px;  text-align:center;" COLSPAN=2>Empleado</th>
        </tr>
        <tr>
            <td><Label>Nombre:</Label></td>
            <td><?= $oEmpleados->nombres . " " . $oEmpleados->ape_paterno . " " . $oEmpleados->ape_materno?></td>
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
            <td><?= $oEmpleados->rfc?></td>
        </tr>
        <tr>
            <td><Label>CURP:</Label></td>
            <td><?= $oEmpleados->curp?></td>
        </tr>
    </table>
    <table style="margin-left:0px; position:relative; margin-top: -0px; border: #00 1px solid;">
        <tr>
            <th style="color: #f3eded; background-color: #000;width:704px;  text-align:center;" COLSPAN=26>Detalle del prestamo</th>
        </tr>
        <tr>
            <td>Fecha de registro: <?= $oPrestamos2->fecha_registro ?> </td>
        </tr>
        <tr>
            <td> Monto solicitado: $<?= $oPrestamos2->monto ?></td>
        </tr>
        <tr>
            <td> Monto a pagar: $<?= $oPrestamos2->monto_pagar ?></td>
        </tr>
        <tr>
        <td>Numero de semanas: <?= $oPrestamos2->numero_semanas ?></td>
        </tr>
        <tr>
            <th style="color: #f3eded; background-color: #000;width:704px;  text-align:center;" COLSPAN=26>Pagos realizados</th>
        </tr>
        <tr>
            <td style="color: #f3eded; background-color: #000;">Fecha del pago</td>
            <td style="color: #f3eded; background-color: #000;">Monto a pagar</td>
            <td style="color: #f3eded; background-color: #000;">Semana en curso</td>
            <td style="color: #f3eded; background-color: #000;">Estatus</td>
        </tr>
        <tr>

            <?php
                    if (count($lstprestamos) > 0) {
                        foreach ($lstprestamos as $idx => $campo) {
                    ?>  
                    <tr>
                                <td><?= $campo->fecha_pago ?></td>
                                <td style="text-align:right;"><?= $campo->monto_por_semana ?></td>
                                <td style="text-align:right;"><?= $campo->semana_actual ?></td>
                                <td><?= $campo->est ?><br></td>
                    </tr>
                    <?php
                        }
                    }
                    ?>
        </tr>
    </table>
    <table style="margin-left:0px; position:relative; margin-top: -0px; border: #00 1px solid;">
    <tr>
                <td>&nbsp;</td>
                <td></td>
            </tr>
            <tr>
                <td>&nbsp;</td>
                <td></td>
            </tr>
        <tr>
            <td style=" height: 40px;width:696px; text-align:center;"><Label>______________________________________<br>Firma de autorización</Label></td>
        </tr>
    </table>
</page>    