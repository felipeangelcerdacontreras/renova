<?php
/*
 * Copyright 2021 - Felipe angel cerda contreras 
 * felipeangelcerdacontreras@gmail.com
 */
$_SITE_PATH = $_SERVER["DOCUMENT_ROOT"] . "/" . explode("/", $_SERVER["PHP_SELF"])[1] . "/";
require_once($_SITE_PATH . "/app/model/principal.class.php");

class infonavit extends AW
{

    var $id;
    var $id_empleado;
    var $numero_semanas;
    var $monto;
    var $fecha_registro;
    var $estatus;
    var $monto_por_semana;
    var $monto_pagar;
    var $motivo;
    var $detalles;
    var $fecha_pago;


    public function __construct($sesion = true, $datos = NULL)
    {
        parent::__construct($sesion);

        if (!($datos == NULL)) {
            if (count($datos) > 0) {
                foreach ($datos as $idx => $valor) {
                    if (gettype($valor) === "array") {
                        $this->{$idx} = $valor;
                    } else {
                        $this->{$idx} = addslashes($valor);
                    }
                }
            }
        }
    }

    public function Listado()
    {   
        $sqlEmpleado = "";
        if (!empty($this->id_empleado)) {
            $sqlEmpleado = "a.id_empleado='{$this->id_empleado}'";
        }

        $sqlFecha = "";
        if (!empty($this->fecha_pago)) {
            $sqlFecha = "  and a.fecha_pago='{$this->fecha_pago}'";
        } else {
            $sqlFecha = "fecha_pago between '{$this->fecha_inicial}' and '{$this->fecha_final}'";
        }
        $sql = "SELECT
            a.*, CASE
            WHEN a.estatus = 0 THEN
                'LIQUIDADO'
            WHEN a.estatus = 1 THEN
                'PAGANDO'
            ELSE
                'OTRO'
            END AS est,
            b.nombres, b.ape_paterno, b.ape_materno
            FROM
                infonavit AS a
            LEFT JOIN empleados AS b ON a.id_empleado = b.id
            where  {$sqlEmpleado} {$sqlFecha}
        ORDER BY
            a.id ASC";

            return $this->Query($sql);
    }

    public function Informacion()
    {
        $sqlFecha = "";
        if (!empty($this->fecha)){
            $sqlFecha = "id_empleado = '{$this->id_empleado}' and fecha_pago = '{$this->fecha}'";
        } else {
            $sqlFecha = "id='{$this->id}'";
        }

        $sql = "select * from infonavit where  {$sqlFecha}";
        $res = parent::Query($sql);

        if (!empty($res) && !($res === NULL)) {
            foreach ($res[0] as $idx => $valor) {
                $this->{$idx} = $valor;
            }
        } else {
            $res = NULL;
        }

        return $res;
    }

    public function Liquidar()
    {

        $sql = "update
                    infonavit
                set
                    estatus = '{$this->estatus}'
                where
                  id='{$this->id}'";
        return $this->NonQuery($sql);
    }

    public function Existe()
    {
        $sql = "select id from infonavit where id='{$this->id}'";
        //print_r($sql);
        $res = $this->Query($sql);

        $bExiste = false;

        if (count($res) > 0) {
            $bExiste = true;
        }
        return $bExiste;
    }

    public function Actualizar()
    {
        $sql = "UPDATE `infonavit`
        SET
        `fecha_pago` = '{$this->fecha_pago}',
        `monto_por_semana` = '{$this->monto_por_semana}'
        WHERE `id` = '{$this->id}';
         ";
         return $this->NonQuery($sql);
    }

    public function Agregar()
    {

        $sql = "insert into infonavit
                (`id`,`id_empleado`,`monto_por_semana`,`fecha_registro`,`fecha_pago`,`estatus`)
                values
                ('0','{$this->id_empleado}','{$this->monto_por_semana}',now(),'{$this->fecha_pago}','1')";
        //echo nl2br($sql);
        $bResultado = $this->NonQuery($sql);

        $sql1 = "select id from fonacot order by id desc limit 1";
        $res = $this->Query($sql1);

        $this->id = $res[0]->id;

        return $bResultado;
    }

    public function Guardar()
    {
        $bRes = false;
        if ($this->Existe() === true) {
            $bRes = $this->Actualizar();
        } else {
            $bRes = $this->Agregar();
        }

        return $bRes;
    }
}
