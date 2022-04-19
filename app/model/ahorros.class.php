<?php
/*
 * Copyright 2021 - Felipe angel cerda contreras 
 * felipeangelcerdacontreras@gmail.com
 */
$_SITE_PATH = $_SERVER["DOCUMENT_ROOT"] . "/" . explode("/", $_SERVER["PHP_SELF"])[1] . "/";
require_once($_SITE_PATH . "/app/model/principal.class.php");

class ahorros extends AW
{

    var $id;
    var $id_empleado;
    var $frecuencia;
    var $monto;
    var $fecha_registro;
    var $estatus;
    var $acumulado;

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
        } else {
            $sqlFecha = "fecha_registro between '{$this->fecha_inicial}' and '{$this->fecha_final}'";
        }

        $sql = "SELECT a.*, CASE WHEN a.estatus = 0 THEN 'AHORRO DETENIDO' WHEN a.estatus = 1 THEN 'AHORRANDO'
        ELSE 'OTRO' END AS est,
        b.nombres, b.ape_paterno, b.ape_materno
        FROM ahorros AS a LEFT JOIN empleados AS b ON a.id_empleado = b.id
        where {$sqlEmpleado} {$sqlFecha}
        ORDER BY a.id ASC";
        return $this->Query($sql);
    }

    public function Informacion()
    {   
        $sqlEstatus = "";
        if (!empty($this->estatus)){
            $sqlEstatus = "id_empleado = '{$this->id_empleado}' and estatus = '{$this->estatus}'";
        } else {
            $sqlEstatus = "id='{$this->id}'";
        }

        $sql = "select * from ahorros where   {$sqlEstatus}";
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

    public function Detener()
    {

        $sql = "update
                    ahorros
                set
                    estatus = '{$this->estatus}'
                where
                  id='{$this->id}'";
        return $this->NonQuery($sql);
    }

    public function Existe()
    {
        $sql = "select id from ahorros where estatus='1' and id_empleado='{$this->id_empleado}'";

        $res = $this->Query($sql);

        $bExiste = false;

        if (count($res) > 0) {
            $bExiste = true;
        }
        return $bExiste;
    }
    public function AhorroActivo()
    {
        $sql = "select id from ahorros where estatus='0' and id_empleado='{$this->id_empleado}' and 
        fecha_registro between concat(year(now()),'-01-01') and concat(year(now()),'-12-31') ";
        $res = $this->Query($sql);

        $bExiste = false;

        if (count($res) > 0) {
            $bExiste = true;
        }
        return $bExiste;
    }

    public function Actualizar()
    {
        return "Cuenta con un ahorro activo";
    }

    public function Desactivar()
    {

        $sql = "update
                    ahorros
                set
                estatus = '{$this->estatus}'
                where
                  id='{$this->id}'";
        // echo nl2br($sql);
        return $this->NonQuery($sql);
    }

    public function Agregar()
    {

        $sql = "insert into ahorros
                (`id`,`id_empleado`,`monto`,`fecha_registro`,`estatus`)
                values
                ('0','{$this->id_empleado}','{$this->monto}',now(),'1')";
        $bResultado = $this->NonQuery($sql);

        $sql1 = "select id from ahorros order by id desc limit 1";
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
