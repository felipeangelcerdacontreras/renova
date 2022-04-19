<?php
/*
 * Copyright 2021 - Felipe angel cerda contreras 
 * felipeangelcerdacontreras@gmail.com
 */
$_SITE_PATH = $_SERVER["DOCUMENT_ROOT"] . "/" . explode("/", $_SERVER["PHP_SELF"])[1] . "/";
require_once($_SITE_PATH . "/app/model/principal.class.php");
require_once($_SITE_PATH . "vendor/autoload.php"); 

use Carbon\Carbon;

date_default_timezone_set('America/Mexico_City');

class incapacidades extends AW
{
    var $id;
    var $id_empleado;
    var $tipo_incapacidad;
    var $folio;
    var $dias_autorizados;
    var $inicio_incapacida;
    var $fin_incapacida;
    var $reingreso;
    var $ramo_seguro;
    var $expedido;
    var $riesgo;
    var $monto_incapacidad;
    var $monto_dia;
    
    var $observaciones;
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
        $sql = "SELECT a.nombres, a.ape_paterno, a.ape_materno, b.* 
        from empleados as a inner join incapacidades as b on a.id = b.id_empleado WHERE
        inicio_incapacida between '{$this->fecha_inicial}' and '{$this->fecha_final}' or
        fin_incapacida between '{$this->fecha_inicial}' and '{$this->fecha_final}'  or  
        reingreso between '{$this->fecha_inicial}' and '{$this->fecha_final}'
        ORDER BY
            b.reingreso ASC";
        return $this->Query($sql);
    }

    public function Informacion()
    {

        $sql = "select * from incapacidades where  id='{$this->id}'";
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
        $sql = "select id from incapacidades where id = '{$this->id}' or  
        id_empleado = '{$this->id_empleado}' and inicio_incapacida = '{$this->inicio_incapacida}'";
        $res = $this->Query($sql);

        $bExiste = false;

        if (count($res) > 0) {
            $bExiste = true;
        }
        return $bExiste;
    }

    public function Actualizar()
    {   

        $sql = " UPDATE `incapacidades`
        SET
        `tipo_incapacidad` = '{$this->tipo_incapacidad }',
        `folio` = '{$this->folio }',
        `dias_autorizados` = '{$this->dias_autorizados }',
        `inicio_incapacida` = '{$this->inicio_incapacida }',
        `fin_incapacida` = '{$this->fin_incapacida }',
        `reingreso` = '{$this->reingreso }',
        `ramo_seguro` = '{$this->ramo_seguro }',
        `expedido` = '{$this->expedido }',
        `riesgo` = '{$this->riesgo }'
        WHERE `id` = '{$this->id}';";

        //print_r($sql);
        $bResultado = $this->NonQuery($sql);

        return $bResultado;
    }

    public function Agregar()
    {   

        $sql = "INSERT INTO `incapacidades`
        (`id`,`id_empleado`,`tipo_incapacidad`,`folio`,`dias_autorizados`,`inicio_incapacida`,`fin_incapacida`,
        `reingreso`,`ramo_seguro`,`expedido`,`riesgo`,`observaciones`,`monto_dia`,`user_id`)
        VALUES
        ('{$this->id}','{$this->id_empleado}','{$this->tipo_incapacidad}','{$this->folio}','{$this->dias_autorizados}',
        '{$this->inicio_incapacida}','{$this->fin_incapacida}','{$this->reingreso}','{$this->ramo_seguro}',
        '{$this->expedido}','{$this->riesgo}','{$this->observaciones}','{$this->monto_dia}','{$this->user_id}')";

        $bResultado = $this->NonQuery($sql);

        $sql1 = "select id from incapacidades order by id desc limit 1";
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
