<?php
/*
 * Copyright 2021 - Felipe angel cerda contreras 
 * felipeangelcerdacontreras@gmail.com
 */
$_SITE_PATH = $_SERVER["DOCUMENT_ROOT"] . "/" . explode("/", $_SERVER["PHP_SELF"])[1] . "/";
require_once($_SITE_PATH . "/app/model/principal.class.php");

class ubicacion extends AW
{

    var $id;
    var $nombre;
    var $lat;
    var $lon;

    //marcar dias 

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
        $sql = "SELECT `ubicacion_checador`.`id`,
        `ubicacion_checador`.`nombre`,
        `ubicacion_checador`.`lat`,
        `ubicacion_checador`.`lon`
    FROM `ubicacion_checador`;
    ";
        //echo nl2br($sql);
        return $this->Query($sql);
    }

    public function Informacion()
    {

        $sql = "select * from ubicacion_checador where  id='{$this->id}'";
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
        $sql = "select id from ubicacion_checador where id='{$this->id}'";
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
        UPDATE `ubicacion_checador`
            SET
            `nombre` = '{$this->nombre}',
            `lat` = '{$this->lat}',
            `lon` = '{$this->lon}'
            WHERE `id` = '{$this->id}'";
        return $this->NonQuery($sql);
    }

    public function Autorizar()
    {

        $sql = "UPDATE `ubicacion_checador_extras`
        SET
        `estatus` = '{$this->estatus}',
        `id_usuario_autorizador` = '{$this->id_usuario_autorizador}'
        WHERE `id` = '{$this->id}';
        ";
        // echo nl2br($sql);
        return $this->NonQuery($sql);
    }

    public function Agregar()
    {
        $sql = "INSERT INTO `ubicacion_checador`
        (`id`, `nombre`, `lat`,`lon`)
            VALUES
        ('0','{$this->nombre}','{$this->lat}','{$this->lon}')";
        $bResultado = $this->NonQuery($sql);

        $sql1 = "select id from ubicacion_checador order by id desc limit 1";
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
