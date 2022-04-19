<?php
/*
 * Copyright 2021 - Felipe angel cerda contreras 
 * felipeangelcerdacontreras@gmail.com
 */
$_SITE_PATH = $_SERVER["DOCUMENT_ROOT"] . "/" . explode("/", $_SERVER["PHP_SELF"])[1] . "/";
require_once($_SITE_PATH . "/app/model/principal.class.php");

class comedor extends AW
{

    var $id;
    var $id_empleado;
    var $precio_platillo;
    var $fecha;
    var $usr;

    //busqueda 
    var $fecha_inicial;
    var $fecha_final;

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
        $sql = "SELECT a.nombres, a.ape_paterno, a.ape_materno, count(dia) + 1  as dia, id_empleado  FROM empleados as a 
        left join comedor as b on a.id = b.id_empleado where 1=1 and fecha between '{$this->fecha_inicial}' and '{$this->fecha_final}' group by nombres";

        return $this->Query($sql);
    }

    public function Listado_comedor()
    {
        $sqlEmpleado = "";
        if (!empty($this->id_empleado)) {
            $sqlEmpleado = "and id_empleado = '{$this->id_empleado}' order by a.fecha asc";
        } else {
            $sqlEmpleado = "order by a.id desc limit 5";
        }

        $sql = "SELECT 
            b.nombres,
            b.ape_paterno,
            b.ape_materno,
            a.precio_platillo
        FROM
            comedor AS a
                LEFT JOIN
            empleados AS b ON b.id = a.id_empleado
        WHERE
            1 = 1 and fecha between '{$this->fecha_inicial}' and '{$this->fecha_final}' {$sqlEmpleado} ";
        return $this->Query($sql);
    }

    public function Informacion()
    {

        $sql = "select * from comedor where  id='{$this->id}'";
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

    public function Existe()
    {
        $sql1 = "select * from empleados where checador = '{$this->usr}' and estatus = '1' order by id desc limit 1";
        $res1 = $this->Query($sql1);

        $bExiste = false;
        if (count($res1) > 0) {
            $this->id_empleado = $res1[0]->id;

            $sql = "SELECT * FROM comedor where fecha = '{$this->fecha_inicial}' and id_empleado = '{$this->id_empleado}' order by id desc limit 1";
            $res = $this->Query($sql);
            if ($res) {
                $bExiste = true;
            }
        }
        return $bExiste;
    }

    public function Actualizar()
    {
        
    }

    public function Agregar()
    {
        $bResultado = false;
        $sql1 = "select * from empleados where checador = '{$this->usr}' order by id desc limit 1";
        $res1 = $this->Query($sql1);

        if (count($res1) > 0) {
        $this->id_empleado = $res1[0]->id;
        
        $sql = "INSERT INTO `comedor`
            (`id_empleado`,`precio_platillo`,`fecha`)
                VALUES
            ('{$this->id_empleado}','38',now());";
        $bResultado = $this->NonQuery($sql);
        }

        return $bResultado;
    }

    public function Guardar()
    {
        if ($this->Existe()) {
            $bRes = true;
        } else {
            $bRes = $this->Agregar();
        }

        return $bRes;
    }
}
