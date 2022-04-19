<?php
/*
 * Copyright 2021 - Felipe angel cerda contreras 
 * felipeangelcerdacontreras@gmail.com
 */
$_SITE_PATH = $_SERVER["DOCUMENT_ROOT"] . "/" . explode("/", $_SERVER["PHP_SELF"])[1] . "/";
require_once($_SITE_PATH . "/app/model/principal.class.php");

class horarios extends AW {

    var $id;
    var $nombre;
    var $estatus;
    var $user_id;
    //marcar dias 
    var $A;
    var $B;
    var $C;
    var $D;
    var $E;
    var $F;
    var $G;
    //entradas
    var $entrada;
    //entradas
    var $salida;
    //comida
    var $comida_1;
    var $comida_2;

    //tolerancias
    var $horas_cumplir;
    var $tiempo_tolerancia;
    var $horas_extra;

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
        $sql = "SELECT * FROM horarios  ";
        //echo nl2br($sql);
        return $this->Query($sql);
        
    }

    public function Informacion() {

        $sql = "select * from horarios where  id='{$this->id}'";
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

    public function Existe() {
        $sql = "select id from horarios where id='{$this->id}'";
        $res = $this->Query($sql);

        $bExiste = false;

        if (count($res) > 0) {
            $bExiste = true;
        }
        return $bExiste;
    }

    public function Actualizar() {

        $sql = "UPDATE `horarios`
        SET 
        `nombre` = '{$this->nombre}',
        `A` = '{$this->A}',
        `B` = '{$this->B}',
        `C` = '{$this->C}',
        `D` = '{$this->D}',
        `E` = '{$this->E}',
        `F` = '{$this->F}',
        `G` = '{$this->G}',
        `entrada` = '{$this->entrada}',
        `salida` = '{$this->salida}',
        `comida_1` = '{$this->comida_1}',
        `comida_2` = '{$this->comida_2}',
        `horas_cumplir` = '{$this->horas_cumplir}',
        `tiempo_tolerancia` = '{$this->tiempo_tolerancia}',
        `horas_extra` = '{$this->horas_extra}'
        WHERE `id` = '{$this->id}';";
        return $this->NonQuery($sql);
    }

    public function Desactivar() {

        $sql = "UPDATE `horarios`
        SET
        `estatus` = '{$this->estatus}'
        WHERE `id` = '{$this->id}';
        ";
                 // echo nl2br($sql);
        return $this->NonQuery($sql);
    }

    public function Agregar() {


        $sql = "INSERT INTO `horarios`
        (`id`,`nombre`,
        `A`,`B`,`C`,`D`,`E`,`F`,`G`,`entrada`,`salida`,`comida_1`,`comida_2`,`tiempo_tolerancia`,`horas_extra`,`horas_cumplir`,`estatus`)        
        VALUES        ('{$this->id}','{$this->nombre}',
        '{$this->A}','{$this->B}','{$this->C}','{$this->D}','{$this->E}','{$this->F}','{$this->G}',
        '{$this->entrada}','{$this->salida}',
        '{$this->comida_1}', '{$this->comida_2}',
        '{$this->horas_cumplir}','{$this->tiempo_tolerancia}', '{$this->horas_extra}',
        '1')";
        $bResultado = $this->NonQuery($sql);
        
        $sql1 = "select id from horarios order by id desc limit 1";
        $res = $this->Query($sql1);
        
        $this->id = $res[0]->id;

        return $bResultado;
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