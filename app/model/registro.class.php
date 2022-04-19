<?php
/*
 * Copyright 2021 - Felipe angel cerda contreras 
 * felipeangelcerdacontreras@gmail.com
 */
$_SITE_PATH = $_SERVER["DOCUMENT_ROOT"] . "/" . explode("/", $_SERVER["PHP_SELF"])[1] . "/";
require_once($_SITE_PATH . "/app/model/principal.class.php");

class registro extends AW {

    var $reg_id;
    var $reg_cliente;
    var $reg_lineataxi;
    var $reg_marca;

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
        $sql = "SELECT a.*, b.mar_nombre FROM prueba.registro as a join  marcas as b on a.reg_marca = b.mar_id";
        return $this->Query($sql);  
    }

    public function Informacion() {

        $sql = "select * from registro where  reg_id='{$this->reg_id}'";
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
        $sql = "select reg_id from registro where reg_id='{$this->reg_id}'";
        $res = $this->Query($sql);

        $bExiste = false;

        if (count($res) > 0) {
            $bExiste = true;
        }
        return $bExiste;
    }

    public function Actualizar() {
        $sql = "update
                    registro
                set
                  reg_cliente ='{$this->reg_cliente}',
                  reg_lineataxi = '{$this->reg_lineataxi}',
                  reg_marca = '{$this->reg_marca}'
                where
                  reg_id='{$this->reg_id}'";
        return $this->NonQuery($sql);
    }

    public function Agregar() {

        $sql = "insert into registro
                (reg_id,reg_cliente,reg_lineataxi,reg_marca)
                values
                ('0','{$this->reg_cliente}','{$this->reg_lineataxi}','{$this->reg_marca}')";

        $bResultado = $this->NonQuery($sql);

        $sql1 = "select reg_id from registro order by reg_id desc limit 1";
        $res = $this->Query($sql1);

        $this->reg_id = $res[0]->reg_id;

        return $bResultado;
    }

    public function like($reg_cliente){
        $sql = "select cli_lineataxi from clientes where cli_nombre like '%".$reg_cliente."%' ORDER BY cli_id ASC LIMIT 1";
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