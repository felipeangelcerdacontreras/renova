<?php
/*
 * Copyright 2021 - Felipe angel cerda contreras 
 * felipeangelcerdacontreras@gmail.com
 */
$_SITE_PATH = $_SERVER["DOCUMENT_ROOT"] . "/" . explode("/", $_SERVER["PHP_SELF"])[1] . "/";
require_once($_SITE_PATH . "/app/model/principal.class.php");

class usuarios extends AW {

    var $id;
    var $nombre_usuario;
    var $usuario;
    var $correo;
    var $numero_economico;
    var $nvl_usuario;
    var $clave_usuario;
    var $user_id;
    var $estado;

    var $perfiles_id;

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
        $sql = "SELECT * FROM usuarios  ";
        //echo nl2br($sql);
        return $this->Query($sql);
        
    }

    public function Informacion() {

        $sql = "select * from usuarios where  id='{$this->id}'";
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
        $sql = "select id from usuarios where id='{$this->id}'";
        $res = $this->Query($sql);

        $bExiste = false;

        if (count($res) > 0) {
            $bExiste = true;
        }
        return $bExiste;
    }

    public function Actualizar() {
        $sPermisos = "";
        if (! empty($this->perfiles_id)) {
            foreach ($this->perfiles_id as $idx => $valor) {
                $sPermisos .= $valor . "@";
            }
        }

        $sqlPass = "";
        if (!empty($this->clave_usuario)) {
            $sqlPass = ", clave='{$this->Encripta($this->clave_usuario)}'";
        }

        $sql = "update
                    usuarios
                set
                    perfiles_id = '{$sPermisos}',
                    nombre_usuario = '{$this->nombre_usuario}',
                    correo = '{$this->correo}',
                    numero_economico = '{$this->numero_economico}',
                    nvl_usuario = '{$this->nvl_usuario}'
                    {$sqlPass}
                where
                  id='{$this->id}'";

        return $this->NonQuery($sql);
    }

    public function Desactivar() {

        $sql = "update
                    usuarios
                set
                    estado = '{$this->estado}'
                where
                  id='{$this->id}'";
                 // echo nl2br($sql);
        return $this->NonQuery($sql);
    }

    public function Agregar() {
        $sPermisos = "";
        if (!empty($this->perfiles_id)) {
            foreach ($this->perfiles_id as $idx => $valor) {
                $sPermisos .= $valor . "@";
            }
        }

        $sql = "insert into usuarios
                (`id`,`perfiles_id`,`nombre_usuario`,`correo`,`usuario`,`nvl_usuario`,`clave`,`numero_economico`,`estado`,`usuario_creacion`,`fecha_creacion`)
                values
                ('0','{$sPermisos}','{$this->nombre_usuario}','{$this->correo}','{$this->usuario}','{$this->nvl_usuario}','{$this->Encripta($this->clave_usuario)}','{$this->numero_economico}', '1', '{$this->user_id}', now())";
        $bResultado = $this->NonQuery($sql);
        
        $sql1 = "select id from usuarios order by id desc limit 1";
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