<?php
/*
 * Copyright 2021 - Felipe angel cerda contreras 
 * felipeangelcerdacontreras@gmail.com
 */
$_SITE_PATH = $_SERVER["DOCUMENT_ROOT"] . "/" . explode("/", $_SERVER["PHP_SELF"])[1] . "/";
require_once($_SITE_PATH . "/app/model/principal.class.php");

class comedor_nominas extends AW
{

    var $id;
    var $id_empleado;
    var $fecha;
    var $sumar;
    var $precio_platillo;
    var $user_id;

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

        $sql = "SELECT a.nombres, a.ape_paterno, a.ape_materno, b.id, b.id_empleado, b.precio_platillo, b.fecha FROM empleados as a 
        inner join comedor as b on a.id = b.id_empleado where 1=1 and b.fecha between '{$this->fecha_inicial}' and '{$this->fecha_final}' order by b.fecha";
        return $this->Query($sql);
    }

    public function Listado1()
    {   

        $sql = "SELECT a.nombres, a.ape_paterno, a.ape_materno, b.id, b.id_empleado, sum(b.precio_platillo) as precio_platillo, b.fecha FROM empleados as a 
        inner join comedor as b on a.id = b.id_empleado where 1=1 and b.fecha between '{$this->fecha_inicial}' and '{$this->fecha_final}' group by a.nombres order by b.fecha desc";
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
        $sql = "select * from comedor where id = '{$this->id}'";
        $res = $this->Query($sql);

        $bExiste = false;

        if (count($res) > 0) {
            $bExiste = true;
        }
        return $bExiste;
    }

    public function Actualizar()
    {
        $sql = "update
                    comedor
                set
                precio_platillo =  precio_platillo + '{$this->sumar}',
                usuario_edicion = '{$this->user_id}',
                fecha_modificacion = now()
                where
                  id='{$this->id}'";
        return $this->NonQuery($sql);
    }

    public function Agregar()
    {
        $sql = "INSERT INTO `comedor`
            (`id_empleado`,`precio_platillo`,`fecha`)
                VALUES
            ('{$this->id_empleado}','{$this->sumar}','{$this->fecha}')";
        $bResultado = $this->NonQuery($sql);

        return $bResultado;
    }

    public function Guardar()
    {
        if ($this->Existe()) {
            $bRes = $this->Actualizar();
        } else {
            $bRes = $this->Agregar();
        }

        return $bRes;
    }
}
