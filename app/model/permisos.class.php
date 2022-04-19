<?php
/*
 * Copyright 2021 - Felipe angel cerda contreras 
 * felipeangelcerdacontreras@gmail.com
 */
$_SITE_PATH = $_SERVER["DOCUMENT_ROOT"] . "/" . explode("/", $_SERVER["PHP_SELF"])[1] . "/";
require_once($_SITE_PATH . "/app/model/principal.class.php");

class permisos extends AW
{

    var $id;
    var $id_empleado;
    var $entrada;
    var $salida;
    var $dia;
    var $fecha;
    var $llegada_tarde;
    var $salida_temprano;
    var $dia_completo;
    var $sin_sueldo;
   
    var $user_id;

    //busqueda 
    var $fecha1;

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
        $sqlfecha = "";
        if ($this->fecha1 != '') {
            $sqlfecha = "{$this->fecha1}";
        }

        $sql = "SELECT 
        a.*,
        b.nombres,
        b.ape_paterno,
        b.ape_materno
    FROM
        permisos AS a
            LEFT JOIN
        empleados AS b ON a.id_empleado = b.id
    WHERE fecha between '{$this->fecha_inicial}' and '{$this->fecha_final}'
        ORDER BY
            a.id ASC";
        return $this->Query($sql);
    }

    public function Informacion()
    {

        $sql = "select * from permisos where  id='{$this->id}'";
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
        $sql = "select id from permisos where fecha='1' and id_empleado='{$this->id_empleado}'";
        //print_r($sql);
        $res = $this->Query($sql);

        $bExiste = false;

        if (count($res) > 0) {
            $bExiste = true;
        }
        return $bExiste;
    }

    public function Actualizar($id_prestamo,$Semanas)
    {
        $sql = "update
                    permisos
                set
                fecha ='0',
                restante = '0',
                semana_actual =  '{$Semanas}'
                where
                  id='{$id_prestamo}'";
        //print_r($sql);
        return $this->NonQuery($sql);
    }

    public function Agregar()
    {

        if ($this->fecha <= date("Y-m-d")) {
            $sqlPermiso = "";
            if ($this->llegada_tarde == 1) {
                $sqlPermiso = "permiso_entrada = 1, estatus_entrada = ''";
            } else if ($this->salida_temprano == 1) {
                $sqlPermiso = "permiso_salida = 1, estatus_salida = ''";
            }
            $sqlUpdate = "UPDATE `asistencia`
                SET
                {$sqlPermiso}
                WHERE id_empleado = '{$this->id_empleado}' and fecha = '{$this->fecha}'";
            $this->NonQuery($sqlUpdate);
        }
        $sql = "insert into permisos
                (`id`,`id_empleado`,`entrada`,`salida`,`dia`,`fecha`,`llegada_tarde`,`salida_temprano`,`dia_completo`,`id_usuario`,`fecha_registro`,`estatus`,`sin_sueldo`)
                values
                ('0','{$this->id_empleado}','{$this->entrada}','{$this->salida}','{$this->dia}','{$this->fecha}','{$this->llegada_tarde}','{$this->salida_temprano}',
                '{$this->dia_completo}', '{$this->user_id}', 'now()','1', {$this->sin_sueldo})";
        $bResultado = $this->NonQuery($sql);

        $sql1 = "select id from permisos order by id desc limit 1";
        $res = $this->Query($sql1);

        $this->id = $res[0]->id;

        return $bResultado;
    }

    public function Guardar()
    {
        $bRes = false;
        if ($bRes = $this->Agregar()) {
            $bRes = true;
        }

        return $bRes;
    }
}
