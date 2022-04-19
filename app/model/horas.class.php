<?php
/*
 * Copyright 2021 - Felipe angel cerda contreras 
 * felipeangelcerdacontreras@gmail.com
 */
$_SITE_PATH = $_SERVER["DOCUMENT_ROOT"] . "/" . explode("/", $_SERVER["PHP_SELF"])[1] . "/";
require_once($_SITE_PATH . "/app/model/principal.class.php");
require_once($_SITE_PATH . "vendor/autoload.php"); 

use Carbon\Carbon;

class horas extends AW {

    var $id;
    var $id_empleado;
    var $fecha_registro;
    var $horas_extras;
    var $id_usuario_creador;
    var $id_usuario_autorizador;
    var $estatus;
    var $motivo;
    var $user_id;
    //marcar dias 

    //busqueda 
    var $fecha_inicial;
    var $fecha_final;
    public function __construct($sesion = true, $datos = NULL) {
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

    public function Listado() {
        $sqlEstatus = "";
        if (!empty($this->estatus)) {
            $sqlEstatus = "and a.estatus = '{$this->estatus}'";
        }

        $sql = "SELECT a.*, CASE WHEN a.estatus = 0 THEN
            'NO AUTORIZADO' 
            WHEN a.estatus = 1 THEN
            'EN ESPERA DE AUTORIZACION' 
            WHEN a.estatus = 2 THEN
            'AUTORIZADA' 
            WHEN a.estatus = 3 THEN
            'PAGADA' ELSE 'OTRO' 
        END AS est,
        CONCAT(IFNULL(b.nombres, ''), ' ',IFNULL(b.ape_paterno, ''), ' ',IFNULL(b.ape_materno,'') ) AS empleado 
    FROM
        horas_extras AS a
        LEFT JOIN empleados AS b ON a.id_empleado = b.id
        where fecha_registro between '{$this->fecha_inicial}' and '{$this->fecha_final}' {$sqlEstatus} ORDER BY a.id ASC";
        //echo nl2br($sql);
        return $this->Query($sql);
        
    }

    public function Informacion() {

        if (!empty($this->id_empleado)){

            $sql = "SELECT sum(horas_extras) as horas_extras FROM horas_extras where estatus = '2' and id_empleado = '{$this->id_empleado}' and fecha_registro between date_add('{$this->Fecha}', INTERVAL -7 DAY) and '{$this->Fecha}'";
            $res = parent::Query($sql);

            if (!empty($res) && !($res === NULL)) {
                foreach ($res [0] as $idx => $valor) {
                    $this->{$idx} = $valor;
                }
            } else {
                $res = NULL;
            }

            return $res;
        } else {
            $sql = "select * from horas_extras where  id='{$this->id}'";
            $res = parent::Query($sql);

            if (!empty($res) && !($res === NULL)) {
                foreach ($res [0] as $idx => $valor) {
                    $this->{$idx} = $valor;
                }
            } else {
                $res = NULL;
            }

            return $res;
        }
    }

    public function Existe() {
        $sql = "select id from horas_extras where id='{$this->id}'";
        $res = $this->Query($sql);

        $bExiste = false;

        if (count($res) > 0) {
            $bExiste = true;
        }
        return $bExiste;
    }

    public function Actualizar() {
        $sql = "UPDATE `horas_extras`
        SET 
        `estatus` = '{$this->estatus}',
        `id_usuario_autorizador` = '{$this->id_usuario_autorizador}'
        WHERE `id` = '{$this->id}';";
        return $this->NonQuery($sql);
    }

    public function Autorizar() {

        $sql = "UPDATE `horas_extras`
        SET
        `estatus` = '{$this->estatus}',
        `id_usuario_autorizador` = '{$this->id_usuario_autorizador}'
        WHERE `id` = '{$this->id}';
        ";
                 // echo nl2br($sql);
        return $this->NonQuery($sql);
    }

    public function Agregar() {
        $sql = "INSERT INTO `horas_extras`
        (`id`, `id_empleado`,`fecha_registro`,`horas_extras`,`id_usuario_creador`, `estatus`,`motivo`)
        VALUES
        ('{$this->id}','{$this->id_empleado}','{$this->fecha_registro}','{$this->horas_extras}','{$this->user_id}','1','{$this->motivo}');
        ";
        $bResultado = $this->NonQuery($sql);
        
        $sql1 = "select id from horas_extras order by id desc limit 1";
        $res = $this->Query($sql1);
        
        $this->id = $res[0]->id;

        return $bResultado;
    }

    public function Calcular() {
        $fechaD = Carbon::parse($this->desde);
        $fechaH = Carbon::parse($this->hasta);

        $sqlDelete = "DELETE FROM `horas_extras`
        WHERE fecha_registro >= '{$this->desde}' and fecha_registro <= '{$this->hasta}' and estatus = '1'";
        $resDelete = $this->NonQuery($sqlDelete);
        if ($resDelete) {

            $diasDiferencia = $fechaD->diffInDays($fechaH);
            $diasDiferencia = $diasDiferencia + 1;
            $countRow = 0;
            $bResultado = false;

            if ($fechaD <= $fechaH) {
                for ($i = 0; $i <= $diasDiferencia; $i++) {
                    $sqlFecha = "SELECT DATE_FORMAT(DATE_ADD('{$fechaD}', INTERVAL $i DAY), '%Y-%m-%d') as fecha";
                    $resultFecha = parent::Query($sqlFecha);
    
                    $sqlAsistencia = "SELECT * FROM asistencia where fecha = '{$resultFecha[0]->fecha}'";
                    $resAsistencia = $this->Query($sqlAsistencia);
    
                    foreach ($resAsistencia as $idx => $campo) {
                        $sqlEmpleados= "select * from empleados where id = '{$campo->id_empleado}' order by id desc limit 1";
                        $resEmpleados = $this->Query($sqlEmpleados);
    
                        $sqlHorarios = "select * from horarios where id = '{$resEmpleados[0]->id_horario}' order by id desc limit 1";
                        $resHorarios = $this->Query($sqlHorarios);

                        if ($campo->hora_salida > $resHorarios[0]->salida && $resEmpleados[0]->extras == 1) {
                            //&& $resEmpleados[0]->extras == 1
                            $hora_horario = new DateTime($resHorarios[0]->salida);
                            $hora_salida = new DateTime($campo->hora_salida);
                            $intervalo = $hora_horario->diff($hora_salida);
    
                            $horasExtra  = '';
                            $porciones = explode(".", $intervalo->format('%H.%i'));
                            
                            if ($porciones[0] >= 01 || $porciones[1] >= 30) {
                                if ($porciones[1] >= 30 && $porciones[1] <= 49) {
                                    $porciones[1] = 5;
                                } else if ($porciones[1] >= 50 && $porciones[1] <= 59) {
                                    $porciones[0] = $porciones[0] + 1;
                                    $porciones[1] = 0;
                                } else {
                                    $porciones[1] = 0;
                                }
                                $horasExtra = $porciones[0] . "." . $porciones[1];

                                $sqlHorasE = "select * from horas_extras where fecha_registro = '{$resultFecha[0]->fecha}' and id_empleado = '{$resEmpleados[0]->id}' order by id desc limit 1";
                                $resHorasE = $this->Query($sqlHorasE);

                                if ($resHorasE) {
                                    $sql = "UPDATE `horas_extras` SET
                                    `horas_extras` = '{$horasExtra}'
                                    WHERE `id_empleado` = '{$resEmpleados[0]->id}' and `fecha_registro` = '{$resultFecha[0]->fecha}'";
                                    if ($this->NonQuery($sql)) {
                                        $bResultado = true;
                                    }
                                } else {
                                    $sql = "INSERT INTO `horas_extras`
                                    (`id_empleado`,`fecha_registro`,`horas_extras`,`estatus`)
                                    VALUES
                                    ('{$resEmpleados[0]->id}','{$resultFecha[0]->fecha}','{$horasExtra}','1')";
                                    if($this->NonQuery($sql)){
                                        $bResultado = true;
                                    }
                                }
                            }
                        }
                    }
                }
            }
            return $bResultado;
        }
    }

    public function Guardar() {
        $bRes = false;
        if ($this->Existe() === true) {
            $bRes = $this->Actualizar();
        } else {
            $bRes = $this->Agregar();
        }

        return $bRes;
    }
}