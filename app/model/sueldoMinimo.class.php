<?php
/*
 * Copyright 2021 - Felipe angel cerda contreras 
 * felipeangelcerdacontreras@gmail.com
 */
$_SITE_PATH = $_SERVER["DOCUMENT_ROOT"] . "/" . explode("/", $_SERVER["PHP_SELF"])[1] . "/";
require_once($_SITE_PATH . "/app/model/principal.class.php");

class sueldo extends AW
{

    var $id;
    var $id_;
    var $sueldo_minimo;

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
        $sql = "SELECT * FROM `sueldo_seguro`";

        return $this->Query($sql);
    }

    public function Informacion()
    {

        $sql = "select * from sueldo_seguro where  id='{$this->id}'";
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
        $sql = "select id from sueldo_seguro where id='{$this->id_}'";
        $res = $this->Query($sql);
        
        $bExiste = false;

        if (count($res) > 0) {
            $bExiste = true;
        }
        return $bExiste;
    }

    public function Actualizar()
    {
        $sql = "
        UPDATE `sueldo_seguro`
            SET
            `sueldo_minimo` = '{$this->sueldo_minimo}'
            WHERE `id` = '{$this->id_}'";

        return $this->NonQuery($sql);
    }

    public function Agregar()
    {
        $sql = "INSERT INTO `sueldo_seguro`
        (`id`, `sueldo_minimo`)
            VALUES
        ('0','{$this->sueldo_minimo}')";
        $bResultado = $this->NonQuery($sql);

        $sql1 = "select id from sueldo_seguro order by id desc limit 1";
        $res = $this->Query($sql1);

        $this->id = $res[0]->id;

        return $bResultado;
    }

    public function Guardar()
    {
        $bRes = false;
        if ($this->Existe() === true) {
            $bRes = $this->Actualizar();
        } else {
            $bRes = $this->Agregar();
        }

        return $bRes;
    }
}
