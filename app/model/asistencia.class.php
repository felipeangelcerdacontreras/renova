<?php
/*
 * Copyright 2021 - Felipe angel cerda contreras 
 * felipeangelcerdacontreras@gmail.com
 */
$_SITE_PATH = $_SERVER["DOCUMENT_ROOT"] . "/" . explode("/", $_SERVER["PHP_SELF"])[1] . "/";
require_once($_SITE_PATH . "/app/model/principal.class.php");

class asistencia extends AW
{

    var $id;
    var $id_empleado;
    var $fecha;
    var $hora_entrada;
    var $hora_salida;
    var $estatus;
    var $dia;
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
        $sql = "SELECT a.nombres, a.ape_paterno, a.ape_materno, count(dia)   as dia, id_empleado  FROM empleados as a 
        left join asistencia as b on a.id = b.id_empleado where 1=1 and fecha between '{$this->fecha_inicial}' and '{$this->fecha_final}' group by a.id";

        return $this->Query($sql);
    }

    public function Listado_asistencia()
    {
        $sqlEmpleado = "";
        if (!empty($this->id_empleado)) {
            if (!empty($this->id_departamento) && $this->id_departamento >= 1) {
                $sqlEmpleado = " and d.id_departamento = '{$this->id_departamento}' order by a.fecha asc";
            } else {
                $sqlEmpleado = " order by a.fecha asc";
            }
        } else {
            $sqlEmpleado = "order by a.order desc limit 5";
        }

        $sql = "SELECT a.id,b.nombres, b.ape_paterno, b.ape_materno, a.fecha, a.hora_entrada,a.hora_salida,a.estatus_entrada,a.estatus_salida, 
            IF(a.dia = 0,'Domingo',
            IF(a.dia = 1,'Lunes',
            IF(a.dia = 2,'Martes',
            IF(a.dia = 3,'Miercoles',
            IF(a.dia = 4,'Jueves',
            IF(a.dia = 5,'Viernes',
            IF(a.dia = 6, 'Sabado', ''))))))) AS dia,
            c.tiempo_tolerancia,
            IF(estatus_entrada = 1,'A tiempo', 
            if(estatus_entrada = 2, 'Retraso', 
            if(estatus_entrada = 3, 'Falta', ''))) AS retraso,
            if(permiso_entrada = 1, 'Permiso entrada', if(permiso_salida = 1, 'Permiso salida', '')) as permiso,
            d.id_departamento
            FROM asistencia as a 
            left join empleados as b on b.id = a.id_empleado
            left join horarios as c on c.id = b.id_horario
            left join puestos as d on d.id = b.id_puesto
            where 1=1 and fecha between '{$this->fecha_inicial}' and '{$this->fecha_final}' {$sqlEmpleado} ";
        return $this->Query($sql);
    }

    public function Informacion()
    {

        $sql = "select * from asistencia where  id='{$this->id}'";
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
                    asistencia
                set
                    estatus = '{$this->estatus}'
                where
                  id='{$this->id}'";
        return $this->NonQuery($sql);
    }

    public function Existe()
    {
        $sql1 = "select * from empleados where checador = '{$this->usr}' order by id desc limit 1";
        $res1 = $this->Query($sql1);

        $bExiste = false;
        if (count($res1) > 0) {
            $this->id_empleado = $res1[0]->id;

            $sql = "SELECT * FROM asistencia where fecha = '{$this->fecha_inicial}' and id_empleado = '{$this->id_empleado}' order by id desc limit 1";
            $res = $this->Query($sql);

            if (count($res) > 0) {
                $bExiste = true;
            }
        }
        return $bExiste;
    }

    public function Actualizar()
    {
        $now = date("Y-m-d");
        $now2 = date("Y-m-d H:i:s");

        $bResultado = 0;
        $sqlActualizar = "SELECT id FROM asistencia where fecha = '{$this->fecha_inicial}' and id_empleado = '{$this->id_empleado}'";
        $resActualizar = $this->Query($sqlActualizar);

        $this->id = $resActualizar[0]->id;

        $sql1 = "select * from empleados where checador = '{$this->usr}' order by id desc limit 1";
        $res1 = $this->Query($sql1);

        $this->id_empleado = $res1[0]->id;

        $sql2 = "select * from horarios where id = '{$res1[0]->id_horario}' order by id desc limit 1";
        $res2 = $this->Query($sql2);

        $sql3 = "SELECT * FROM  `permisos` where fecha = '{$this->fecha_inicial}' and id_empleado = '{$this->id_empleado}' and salida_temprano is not null and salida_temprano != ''";
        $res3 = $this->Query($sql3);

        if (count($res3) > 0) {
            if ($res3[0]->salida_temprano == 1) {
                $minutosMenos = strtotime('-15 minute', strtotime($res3[0]->salida));
                $minutosMenos = date('H:i:s', $minutosMenos);

                $minutosMas = strtotime('+10 minute', strtotime($res3[0]->salida));
                $minutosMas = date('H:i:s', $minutosMas);

                if (($this->hora >= $minutosMenos) && ($this->hora <= $minutosMas) && $res3[0]->sin_sueldo == 1) {
                    $sql = "{$this->id_empleado}|UPDATE `asistencia`
                    SET
                    `hora_salida` = '{$this->hora}',
                    `order` = '{$now2}',
                    `permiso_salida` = '1'
                    WHERE `id` = '{$this->id}'";

                    $this->NonQuery($sql, false);
                    $bResultado = 2;
                } else {
                    $sql = "{$this->id_empleado}|UPDATE `asistencia`
                    SET
                    `hora_salida` = '{$this->hora}',
                    `order` = '{$now2}',
                    `estatus_salida` = '3'
                    WHERE `id` = '{$this->id}'";
                    $this->NonQuery($sql, false);
                    $bResultado = 2;
                }
            }
        } else if (($this->hora >= $res2[0]->salida) && ($this->hora <= $res2[0]->horas_extra)) {
            $sql = "{$this->id_empleado}|UPDATE `asistencia`
                SET
                `hora_salida` = '{$this->hora}',
                `order` = '{$now2}',
                `estatus_salida` = '1'
                WHERE `id` = '{$this->id}'";
            $this->NonQuery($sql, false);
            $bResultado = 2;
        } else if (($this->hora > $res2[0]->horas_extra)) {

            $sql = "{$this->id_empleado}|UPDATE `asistencia`
            SET
            `hora_salida` = '{$this->hora}',
            `order` = '{$now2}',
            `estatus_salida` = '2'
            WHERE `id` = '{$this->id}'";
            if ($this->NonQuery($sql, false)) {
                $bResultado = 2;
            }
        } else {
            $sql = "{$this->id_empleado}|UPDATE `asistencia`
            SET
            `hora_salida` = '{$this->hora}',
            `order` = '{$now2}',
            `estatus_salida` = '3'
            WHERE `id` = '{$this->id}'";
            $this->NonQuery($sql, false);
            $bResultado = 2;
        }

        return $bResultado;
    }

    public function Agregar()
    {
        $now = date("Y-m-d");
        $now2 = date("Y-m-d H:i:s");

        $bResultado = 0;
        $sql1 = "select * from empleados where checador = '{$this->usr}' order by id desc limit 1";
        $res1 = $this->Query($sql1);

        $this->id_empleado = $res1[0]->id;

        $sql2 = "select * from horarios where id = '{$res1[0]->id_horario}' order by id desc limit 1";
        $res2 = $this->Query($sql2);

        $sql3 = "SELECT * FROM  `permisos` where fecha = '{$this->fecha_inicial}' and id_empleado = '{$this->id_empleado}'  and llegada_tarde is not null and llegada_tarde != ''";
        $res3 = $this->Query($sql3);

        $minutosMas = strtotime('+10 minute', strtotime($res2[0]->tiempo_tolerancia));
        $minutosMas = date('H:i:s', $minutosMas);

        if (count($res3) > 0) {
            if ($res3[0]->llegada_tarde == 1) {

                $minutosMenos = strtotime('-15 minute', strtotime($res3[0]->entrada));
                $minutosMenos = date('H:i:s', $minutosMenos);

                $minutosMas = strtotime('+10 minute', strtotime($res3[0]->entrada));
                $minutosMas = date('H:i:s', $minutosMas);

                if (($this->hora >= $minutosMenos) && ($this->hora <= $minutosMas) && $res3[0]->sin_sueldo  == 'NULL') {
                    $sql = "{$this->id_empleado}|INSERT INTO 
                        `asistencia` (`id_empleado`,`fecha`,`hora_entrada`,`dia`,`order`,`permiso_entrada`,`quitar_bonos`)
                        VALUES
                        ('{$this->id_empleado}','{$now}','{$this->hora}','{$this->diaActual}','{$now2}','1','1');";
                    $this->NonQuery($sql, false);

                    $bResultado = 1;
                } else if (($this->hora >= $minutosMenos) && ($this->hora <= $minutosMas) && $res3[0]->sin_sueldo  == 1) {
                    $sql = "{$this->id_empleado}|INSERT INTO 
                        `asistencia` (`id_empleado`,`fecha`,`hora_entrada`,`dia`,`order`,`permiso_entrada`)
                        VALUES
                        ('{$this->id_empleado}','{$now}','{$this->hora}','{$this->diaActual}','{$now2}','1');";
                    $this->NonQuery($sql, false);

                    $bResultado = 1;
                } else {
                    $sql = "{$this->id_empleado}|INSERT INTO 
                        `asistencia` (`id_empleado`,`fecha`,`hora_entrada`,`dia`,`order`, `estatus_entrada`,`quitar_bonos`)
                        VALUES
                        ('{$this->id_empleado}','{$now}','{$this->hora}','{$this->diaActual}','{$now2}', '2','1')";
                    $this->NonQuery($sql, false);

                    $bResultado = 1;
                }
            }
        } else if (($this->hora <> $res2[0]->entrada) && ($this->hora < $res2[0]->tiempo_tolerancia)) {

            $sql = "{$this->id_empleado}|INSERT INTO 
                `asistencia` (`id_empleado`,`fecha`,`hora_entrada`,`dia`,`order`,`estatus_entrada`)
                VALUES
                ('{$this->id_empleado}','{$now}','{$this->hora}','{$this->diaActual}','{$now2}','1');";
            $this->NonQuery($sql, false);

            $bResultado = 1;
        } else if (($this->hora > $res2[0]->tiempo_tolerancia) && ($this->hora < $minutosMas)) {

            $sql = "{$this->id_empleado}|INSERT INTO 
                `asistencia` (`id_empleado`,`fecha`,`hora_entrada`,`dia`,`order`, `estatus_entrada`,`quitar_bonos`)
                VALUES
                ('{$this->id_empleado}','{$now}','{$this->hora}','{$this->diaActual}','{$now2}', '2','1')";
            $this->NonQuery($sql, false);

            $bResultado = 1;
        } else {
            $sql = "{$this->id_empleado}|INSERT INTO 
                `asistencia` (`id_empleado`,`fecha`,`hora_entrada`,`dia`,`order`, `estatus_entrada`,`quitar_bonos`)
                VALUES
                ('{$this->id_empleado}','{$now}','{$this->hora}','{$this->diaActual}','{$now2}', '2','1')";
            $this->NonQuery($sql, false);

            $bResultado = 1;
        }
        return $bResultado;
    }
    public function Existe_Sincronizar()
    {
        $sql = "SELECT * FROM asistencia where fecha = '{$this->fecha_}' and id_empleado = '{$this->id_empleado_}' order by id desc limit 1";
        $res = $this->Query($sql);
        $result = 0;
        if (count($res) > 0) {
            if ($this->update_ != "" && $this->update_ != null) {
                $this->update_ = str_replace("\'", "'", $this->update_);
                $update_new = explode("`id` =", $this->update_);
                $rs = $this->NonQuery($update_new[0] . "`id` = '{$res[0]->id}'");
                if ($rs) {
                    $result = 1;
                }
            }
        } else {
            $this->insert_ = str_replace("\'", "'", $this->insert_);
            $this->update_ = str_replace("\'", "'", $this->update_);

            $rs = $this->NonQuery($this->insert_);
            if ($rs > 0 && $this->update_ != "") {
                $sql = "SELECT * FROM asistencia where fecha = '{$this->fecha_}' and id_empleado = '{$this->id_empleado_}' order by id desc limit 1";
                $res = $this->Query($sql);
                if (count($res) > 0) {
                    $update_new = explode("`id` =", $this->update_);
                    $rs = $this->NonQuery($update_new[0] . "`id` = '{$res[0]->id}'");
                    if ($rs) {
                        $result = 1;
                    }
                }
            }
        }
        return $result;
    }

    public function AgregarAsis()
    {
        $sql = "SELECT * FROM asistencia where fecha = '{$this->fecha}' and id_empleado = '{$this->id_empleado}' order by id desc limit 1";
        $res = $this->Query($sql);

        $result = 0;
        if (count($res) > 0 && empty($this->hora_salida)) {
            $result = 1;
        } else {
            $sql = "INSERT INTO 
            `asistencia` (`id_empleado`,`fecha`,`hora_entrada`,`dia`, `estatus_entrada`,`quitar_bonos`)
            VALUES
            ('{$this->id_empleado}','{$this->fecha}','{$this->hora_entrada}','{$this->dia}', '{$this->AgregarAsis}','{$this->quitar_bonos}')";
            $rs = $this->NonQuery($this->sql);
            if ($rs && !empty($this->hora_salida)) {
                $sql = "UPDATE `asistencia`
               SET
               `hora_salida` = '{$this->hora_salida}',
               `estatus_salida` = '{$this->estatus_salida}',
               WHERE fecha = '{$this->fecha}' and id_empleado = '{$this->id_empleado}'";
                $rs = $this->NonQuery($this->sql);
            }
        }
        return $result;
    }

    public function Guardar()
    {

        $sql1 = "select * from empleados where checador = '{$this->usr}' and estatus = '1' order by id desc limit 1";
        $res1 = $this->Query($sql1);

        $bRes = 0;
        if (count($res1) > 0) {
            $existe = $this->Existe();
            if ($existe) {
                $bRes = $this->Actualizar();
            } else {
                $bRes = $this->Agregar();
            }
        } else {
            $bRes = 3;
        }
        return $bRes;
    }
}
