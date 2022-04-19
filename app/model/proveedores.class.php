<?php
/*
 * Copyright 2021 - Felipe angel cerda contreras 
 * felipeangelcerdacontreras@gmail.com
 */
$_SITE_PATH = $_SERVER["DOCUMENT_ROOT"] . "/" . explode("/", $_SERVER["PHP_SELF"])[1] . "/";
require_once($_SITE_PATH . "/app/model/principal.class.php");

class proveedores extends AW
{

    var $id;
    var $alias;
    var $nombre;
    var $estatus;
    var $Calle;
    var $observaciones;
    var $Numero;
    var $Colonia;
    var $Municipio;
    var $Estado;
    var $CP;
    var $RFC;
    var $Telefono;
    var $Fecha_Nac;

    var $user_id;

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
        $sql = "SELECT * FROM proveedores ORDER BY proveedores.nombre ASC";
        return $this->Query($sql);
    }

    public function Informacion()
    {
        $sql = "select * from proveedores where  id='{$this->id}'";
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
        $sql = "select id from proveedores where id='{$this->id}'";
        $res = $this->Query($sql);

        $bExiste = false;

        if (count($res) > 0) {
            $bExiste = true;
        }
        return $bExiste;
    }

    public function Actualizar()
    {

        $sql = "update
                    proveedores
                set
                nombre ='{$this->nombre}',
                Calle = '{$this->Calle}',
                observaciones =  '{$this->observaciones}',
                Numero   = '{$this->Numero}',
                Colonia = '{$this->Colonia}',
                Municipio = '{$this->Municipio}',
                Estado = '{$this->Estado}',
                CP = '{$this->CP}',
                RFC = '{$this->RFC}',
                Telefono = '{$this->Telefono}',
                Fecha_Nac = '{$this->Fecha_Nac}',
                alias='{$this->alias}',
                usuario_edicion = '{$this->user_id}'
                where
                  id='{$this->id}'";
        return $this->NonQuery($sql);
    }

    public function Desactivar() {

        $sql = "UPDATE `proveedores`
        SET
        `estatus_cliente` = '{$this->estatus}'
        WHERE `id` = '{$this->id}';
        ";
                 // echo nl2br($sql);
        return $this->NonQuery($sql);
    }

    public function Agregar()
    {

        $sql = "insert into proveedores
                (id,nombre, estatus_cliente,Calle, observaciones, Numero, Colonia,
                Municipio, Estado, CP, RFC, Telefono, Fecha_Nac, alias,usuario_creacion)
                values
                ('0','{$this->nombre}', '1', '{$this->Calle}','{$this->observaciones}', '{$this->Numero}' ,'{$this->Colonia}',
                 '{$this->Municipio}', '{$this->Estado}', '{$this->CP}', '{$this->RFC}', '{$this->Telefono}', '{$this->Fecha_Nac}','{$this->alias}','{$this->user_id}')";

        $bResultado = $this->NonQuery($sql);

        $sql1 = "select id from proveedores order by id desc limit 1";
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
