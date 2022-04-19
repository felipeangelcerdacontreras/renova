<?php
/*
 * Copyright 2021 - Felipe angel cerda contreras 
 * felipeangelcerdacontreras@gmail.com
 */
$_SITE_PATH = $_SERVER["DOCUMENT_ROOT"] . "/" . explode("/", $_SERVER["PHP_SELF"])[1] . "/";
require_once($_SITE_PATH . "/app/model/principal.class.php");

class contenedores extends AW {

    var $id;
    var $nombre;
    var $tara;
    var $tipo;
    var $capacidad;
    var $estatus;
    var $ubicacion;
    var $user_id;


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
        $sql = "SELECT * FROM contenedores  ";
        //echo nl2br($sql);
        return $this->Query($sql);
        
    }

    public function Informacion() {

        $sql = "select * from contenedores where  id='{$this->id}'";
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
        $sql = "select id from contenedores where id='{$this->id}'";
        $res = $this->Query($sql);

        $bExiste = false;

        if (count($res) > 0) {
            $bExiste = true;
        }
        return $bExiste;
    }

    public function Actualizar() {

        $sql = "update
                    contenedores
                set
                nombre = '{$this->nombre}',
                tara = '{$this->tara}',
                tipo = '{$this->tipo}',
                capacidad='{$this->capacidad}'
                where
                  id='{$this->id}'";
        return $this->NonQuery($sql);
    }

    public function Desactivar() {

        $sql = "update
                    contenedores
                set
                estatus = '{$this->estatus}'
                where
                  id='{$this->id}'";
                 // echo nl2br($sql);
        return $this->NonQuery($sql);
    }

    public function Agregar() {


        $sql = "insert into contenedores
                (`id`,`nombre`,`tara`,`tipo`,`capacidad`,`usuario_creacion`)
                values
                ('0','{$this->nombre}','{$this->tara}','{$this->tipo}','{$this->capacidad}','{$this->user_id}')";
        $bResultado = $this->NonQuery($sql);
        
        $sql1 = "select id from contenedores order by id desc limit 1";
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