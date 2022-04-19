<?php
/*
 * Copyright 2021 - Felipe angel cerda contreras 
 * felipeangelcerdacontreras@gmail.com
 */
$_SITE_PATH = $_SERVER["DOCUMENT_ROOT"] . "/" . explode("/", $_SERVER["PHP_SELF"])[1] . "/";
require_once($_SITE_PATH . "/app/model/principal.class.php");

class clientes extends AW {

    var $cli_id;
    var $cli_nombre;
    var $cli_direccion;
    var $cli_telefono;
    var $cli_lineataxi;

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
        $sql = "SELECT * FROM clientes";
        return $this->Query($sql);  
    }

    public function Informacion() {

        $sql = "select * from clientes where  cli_id='{$this->cli_id}'";
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
        $sql = "select cli_id from clientes where cli_id='{$this->cli_id}'";
        $res = $this->Query($sql);

        $bExiste = false;

        if (count($res) > 0) {
            $bExiste = true;
        }
        return $bExiste;
    }

    public function Actualizar() {
        $sql = "update
                    clientes
                set
                  cli_nombre ='{$this->cli_nombre}',
                  cli_direccion = '{$this->cli_direccion}',
                  cli_telefono =  '{$this->cli_telefono}',
                  cli_lineataxi = '{$this->cli_lineataxi}'
                where
                  cli_id='{$this->cli_id}'";
        return $this->NonQuery($sql);
    }

    public function Agregar() {

        $sql = "insert into clientes
                (cli_id,cli_nombre, cli_direccion, cli_telefono, cli_lineataxi)
                values
                ('0','{$this->cli_nombre}', '{$this->cli_direccion}', '{$this->cli_telefono}', '{$this->cli_lineataxi}')";

        $bResultado = $this->NonQuery($sql);

        $sql1 = "select cli_id from clientes order by cli_id desc limit 1";
        $res = $this->Query($sql1);

        $this->cli_id = $res[0]->cli_id;

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