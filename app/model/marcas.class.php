<?php
/*
 * Copyright 2021 - Felipe angel cerda contreras 
 * felipeangelcerdacontreras@gmail.com
 */
$_SITE_PATH = $_SERVER["DOCUMENT_ROOT"] . "/" . explode("/", $_SERVER["PHP_SELF"])[1] . "/";
require_once($_SITE_PATH . "/app/model/principal.class.php");

class marcas extends AW {

    var $mar_id;
    var $mar_nombre;

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
        $sql = "SELECT * FROM marcas";
        return $this->Query($sql);  
    }

    public function Informacion() {

        $sql = "select * from marcas where  mar_id='{$this->mar_id}'";
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
        $sql = "select mar_id from marcas where mar_id='{$this->mar_id}'";
        $res = $this->Query($sql);

        $bExiste = false;

        if (count($res) > 0) {
            $bExiste = true;
        }
        return $bExiste;
    }

    public function Actualizar() {
        $sql = "update
                    marcas
                set
                  mar_nombre ='{$this->mar_nombre}'
                where
                  mar_id='{$this->mar_id}'";
        return $this->NonQuery($sql);
    }

    public function Agregar() {

        $sql = "insert into marcas
                (mar_id,mar_nombre)
                values
                ('0','{$this->mar_nombre}')";

        $bResultado = $this->NonQuery($sql);

        $sql1 = "select mar_id from marcas order by mar_id desc limit 1";
        $res = $this->Query($sql1);

        $this->mar_id = $res[0]->mar_id;

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