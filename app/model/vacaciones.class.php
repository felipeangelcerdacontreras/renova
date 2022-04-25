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

class vacaciones extends AW
{

    var $id;
    var $id_empleado;
    var $dias_correspondientes;
    var $dias_disfrutar;
    var $dias_restantes;
    var $periodo_inicio;
    var $periodo_fin;
    var $inicio_vacaci;
    var $fin_vacaci;
    var $pago_prima;
    var $dias_pagados;
    var $fecha_pago;
    var $observaciones;
    var $fecha;
    var $fecha_final;
    var $ano;

    var $pagar_dias;
    var $pagar_total;
    var $pagar_concepto;

    var $vacacionesInput;

    var $user_id;

    //busqueda 
    var $fecha1;
    var $pagar;
    var $fecha_genera;
    var $fecha_ingreso; 


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
        $sqlfecha = "";
        if ($this->fecha1 != '') {
            $sqlfecha = "{$this->fecha1}";
        }

        $sql = "SELECT a.nombres, a.ape_paterno, a.ape_materno, b.* 
        from empleados as a inner join vacaciones as b on a.id = b.id_empleado WHERE
        fecha_final between '{$this->fecha_inicial}' and '{$this->fecha_final}' or
        fecha between '{$this->fecha_inicial}' and '{$this->fecha_final}' 
        ORDER BY
            b.fecha ASC";
        return $this->Query($sql);
    }

    public function Informacion()
    {

        $sql = "select * from vacaciones where  id='{$this->id}'";
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

    public function Listado_vacaciones()
    {

        $sql = "SELECT
            TIMESTAMPDIFF(YEAR, fecha_ingreso, '{$this->fecha_genera}') AS ano,
            b.dias,
            '{$this->fecha_genera}' as fecha_nomina,
            a.*
        FROM
            empleados AS a 
            left join anos_servicio as b on  anos = TIMESTAMPDIFF(YEAR,fecha_ingreso,'{$this->fecha_genera}')
        WHERE
            DATE_FORMAT(fecha_ingreso,'%m-%d') between DATE_FORMAT('2022-01-01','%m-%d') 
            and DATE_FORMAT('{$this->fecha_genera}','%m-%d')
            and TIMESTAMPDIFF(YEAR, fecha_ingreso, '{$this->fecha_genera}') > 0
            and a.estatus = 1";
      return $this->Query($sql);

    }

    public function Listado_vacaciones_registradas()
    {
        $sql = "SELECT c.id as id_vac,b.fecha_ingreso, b.nombres, b.ape_paterno, b.ape_materno, b.salario_diario,c.pago_prima,c.dias_correspondientes as dias, a.* FROM vacaciones_prima as a
        left join empleados as b on b.id = a.id_empleado
        left join vacaciones as c on a.id_vacaciones = c.id
        where a.ano = TIMESTAMPDIFF(YEAR, fecha_ingreso, '{$this->fecha_genera}')
        and a.estatus = 0
        and b.estatus = 1
        or a.fecha_generada <= '{$this->fecha_genera}'
        AND a.estatus = 0";
      return $this->Query($sql);

    }

    public function VerificarPrima()
    {
        $sql = "select * from vacaciones_prima 
        where  id_empleado = '{$this->id_empleado}' 
        and ano = '{$this->ano}' 
        and periodo_inicio = '{$this->periodo_inicio}' order by id desc limit 1";
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

    public function ObtenerAños($fecha_ingreso)
    {
        $sql = "SELECT TIMESTAMPDIFF(YEAR, '{$fecha_ingreso}', now()) AS años_transcurridos";
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

    public function ObtenerDias($anos, $id_empleado)
    {
        $sql = "(SELECT dias FROM anos_servicio
        where anos = '{$anos}')
        union all
        (select dias_restantes from  vacaciones
        where ano = '{$anos}' and id_empleado = '{$id_empleado}' order by id desc limit 1)";
        return $this->Query($sql);
    }

    public function DiasVacacion($num, $inicio_vacaci, $fin_vacaci)
    {
        $total_dias = 0;
        for ($i = 0; $i <= $num; $i++) {
            $sqlFecha = "SELECT DATE_FORMAT(DATE_ADD('{$inicio_vacaci}', INTERVAL $i DAY), '%Y-%m-%d') as fecha";
            $resultFecha = parent::Query($sqlFecha);

            $sqlFEstivos = "SELECT * FROM festivos where fecha = '{$resultFecha[0]->fecha}'";
            $res = $this->Query($sqlFEstivos);

            if (count($res) <= 0) {
                if ($resultFecha[0]->fecha == $fin_vacaci) {
                    $sql = "SELECT if( DAYOFWEEK(DATE_FORMAT(DATE_ADD('{$inicio_vacaci}', INTERVAL $i DAY), '%Y-%m-%d')) < 2, 0, 1) as dia";
                    $result = parent::Query($sql);
                    $total_dias = $total_dias + $result[0]->dia;
                    break;
                } else {
                    $sql = "SELECT if( DAYOFWEEK(DATE_FORMAT(DATE_ADD('{$inicio_vacaci}', INTERVAL $i DAY), '%Y-%m-%d')) < 2, 0, 1) as dia";
                    $result = parent::Query($sql);
                    $total_dias = $total_dias + $result[0]->dia;
                }
            }
        }
        return $total_dias;
    }

    public function Existe()
    {
        $sql = "select id from vacaciones where id = '{$this->id}'";
        $res = $this->Query($sql);

        $bExiste = false;

        if (count($res) > 0) {
            $bExiste = true;
        }
        return $bExiste;
    }

    public function Actualizar()
    {
        $separateDate = explode(" ", $this->vacacionesInput);
        $day = $separateDate[1];
        $month = $separateDate[2];
        $year = $separateDate[3];

        $MESES = array(1 => "Enero", 2 => "Febrero", 3 => "Marzo", 4 => "Abril", 5 => "Mayo", 6 => "Junio", 7 => "Julio", 8 => "Agosto", 9 => "Septiembre", 10 => "Octubre", 11 => "Noviembre", 12 => "Diciembre");

        $clave = array_search($month, $MESES);
        if ($clave <= 9) {
            $clave = "0" . $clave;
        }

        if ($day <= 9) {
            $day = "0" . $day;
        }
        $fechaFinal = $year . "-" . $clave . "-" . $day;

        $sqlPagar = "";
        if (!empty($this->pagar)) {
            $sqlPagar = ",
            pagar_dias='{$this->pagar_dias}',
            pagar_total = '{$this->pagar_total}',
            pagar_concepto = '{$this->pagar_concepto}'";
        }

        $sql = " UPDATE `vacaciones`
                 SET
                `dias_disfrutar` = '{$this->dias_disfrutar}',
                `dias_restantes` = '{$this->dias_restantes}',
                `inicio_vacaci` = '{$this->inicio_vacaci}',
                `fin_vacaci` = '{$this->fin_vacaci}'
                {$sqlPagar}
                ,`observaciones` = '{$this->observaciones}'
        WHERE `id` = '{$this->id}';";

        $bResultado = $this->NonQuery($sql);

        if ($bResultado) {
            if ($this->dias_restantes > 0) {
                $fechaActual = date('Y-m-d');
                if ($fechaActual <= $fechaFinal) {
                    $sqlSiguiente = "INSERT INTO `vacaciones`
                    (`id_empleado`, `dias_correspondientes`,`dias_restantes`,`periodo_inicio`,`periodo_fin`,
                    `pago_prima`,`dias_pagados`,`fecha_pago`,`fecha`,`fecha_final`,`ano`)
                    VALUES
                    ('{$this->id_empleado}','{$this->dias_correspondientes}','{$this->dias_restantes}','{$this->periodo_inicio}',
                    '{$this->periodo_fin}','{$this->pago_prima}','{$this->dias_pagados}','{$this->fecha_pago}',now(),'{$fechaFinal}','{$this->ano}')";
                    return $bResultado = $this->NonQuery($sqlSiguiente);
                }
            }
        }
    }

    public function Agregar()
    {
        $sqlPagar = "";
        $sqlPagarValues = "";
        if (!empty($this->pagar)) {
            $sqlPagar = ",pagar_dias, pagar_total, pagar_concepto";
            $sqlPagarValues = ",'{$this->pagar_dias}',
            '{$this->pagar_total}',
            '{$this->pagar_concepto}'";
        }

        $separateDate = explode(" ", $this->vacacionesInput);
        $day = $separateDate[1];
        $month = $separateDate[2];
        $year = $separateDate[3];

        $MESES = array(1 => "Enero", 2 => "Febrero", 3 => "Marzo", 4 => "Abril", 5 => "Mayo", 6 => "Junio", 7 => "Julio", 8 => "Agosto", 9 => "Septiembre", 10 => "Octubre", 11 => "Noviembre", 12 => "Diciembre");

        $clave = array_search($month, $MESES);
        if ($clave <= 9) {
            $clave = "0" . $clave;
        }

        if ($day <= 9) {
            $day = "0" . $day;
        }
        $fechaFinal = $year . "-" . $clave . "-" . $day;

        $sql = "INSERT INTO `vacaciones`
        (`id_empleado`,
        `dias_correspondientes`,`dias_disfrutar`,`dias_restantes`,`periodo_inicio`,`periodo_fin`,`inicio_vacaci`,
        `fin_vacaci`,`pago_prima`,`dias_pagados`,`fecha_pago`,`observaciones`,`fecha`,`fecha_final`,`ano`{$sqlPagar})
        VALUES
        ('{$this->id_empleado}','{$this->dias_correspondientes}','{$this->dias_disfrutar}','{$this->dias_restantes}','{$this->periodo_inicio}',
        '{$this->periodo_fin}','{$this->inicio_vacaci}','{$this->fin_vacaci}','{$this->pago_prima}','{$this->dias_pagados}','{$this->fecha_pago}',
        '{$this->observaciones}',now(),'{$fechaFinal}','{$this->ano}'{$sqlPagarValues})";

        $bResultado = $this->NonQuery($sql);
        if ($bResultado) {
            $sql1 = "select id from vacaciones order by id desc limit 1";
            $res = $this->Query($sql1);

            $this->id = $res[0]->id;

            $sql = 
            "INSERT INTO `vacaciones_prima`
            (`id_empleado`, `id_vacaciones`, `fecha_pago`,`periodo_inicio`,`periodo_fin`, `estatus`, `ano`, `fecha_generada`)
            VALUES
            ('{$this->id_empleado}', '{$this->id}', '{$this->fecha_pago}', '{$this->periodo_inicio}',
            '{$this->periodo_fin}','0', '{$this->ano}', now());";
            $bResultado = $this->NonQuery($sql);
        }
        if ($bResultado) {
            if ($this->dias_restantes) {
                $fechaActual = date('Y-m-d');
                if ($fechaActual <= $fechaFinal) {
                    $sqlSiguiente = "INSERT INTO `vacaciones`
                    (`id_empleado`, `dias_correspondientes`,`dias_restantes`,`periodo_inicio`,`periodo_fin`,
                    `pago_prima`,`dias_pagados`,`fecha_pago`,`fecha`,`fecha_final`,`ano`)
                    VALUES
                    ('{$this->id_empleado}','{$this->dias_correspondientes}','{$this->dias_restantes}','{$this->periodo_inicio}',
                    '{$this->periodo_fin}','{$this->pago_prima}','{$this->dias_pagados}','{$this->fecha_pago}',now(),'{$fechaFinal}','{$this->ano}')";

                    $bResultado = $this->NonQuery($sqlSiguiente);
                }
            }
        }
        $sql1 = "select id from vacaciones order by id desc limit 1";
        $res = $this->Query($sql1);

        $this->id = $res[0]->id;

        return $bResultado;
    }

    public function VacacionesGeneradas()
    {   
        $bResultado = false;
        $accion = "";

            if (!empty($this->id_vacaciones)) {
                $sql = "UPDATE `vacaciones_prima`
                SET
                `fecha_pago` = '{$this->fecha_pago}'
                WHERE `id` = '{$this->id_vac}'";
                $bResultado = $this->NonQuery($sql);
                
                if ($bResultado) {

                    $sql = "UPDATE `vacaciones`
                    SET
                    `fecha_pago` = '{$this->fecha_pago}'
                    WHERE id_empleado = '{$this->id_empleado}' 
                    and periodo_inicio = '{$this->periodo_inicio}' and ano = '{$this->ano}'  ";
                    $bResultado = $this->NonQuery($sql);
                }
                
            }  else {
                $sqlV = "select id from vacaciones where 
                id_empleado = '{$this->id_empleado}' and periodo_inicio = '{$this->periodo_inicio}'
                and ano = '{$this->ano}'";
    
                $res = $this->Query($sqlV);
                if (count($res) <= 0  ) {
                        if ($this->id_empleado != "") {
                        $sql = "INSERT INTO `vacaciones`
                        (`id_empleado`,`dias_correspondientes`,`dias_restantes`,`periodo_inicio`,
                        `periodo_fin`,`pago_prima`,`dias_pagados`,`fecha_pago`,`fecha`,`fecha_final`,`ano`)
                        VALUES
                        ('{$this->id_empleado}','{$this->dias}','{$this->dias}',
                        '{$this->periodo_inicio}','{$this->periodo_fin}','{$this->pago_prima}',
                        '{$this->dias}','{$this->fecha_pago}',now(),'{$this->fecha_final}','{$this->ano}')";
                        $bResultado = $this->NonQuery($sql);
                        if($bResultado){
                            $sql1 = "select id from vacaciones order by id desc limit 1";
                            $res = $this->Query($sql1);
    
                            $this->id = $res[0]->id;
    
                                $sql = 
                                "INSERT INTO `vacaciones_prima`
                                (`id_empleado`, `id_vacaciones`, `fecha_pago`,`periodo_inicio`,`periodo_fin`, `estatus`, `ano`, `fecha_generada`)
                                VALUES
                                ('{$this->id_empleado}', '{$this->id}', '{$this->fecha_pago}', '{$this->periodo_inicio}',
                                '{$this->periodo_fin}','0', '{$this->ano}', 'NOW()');";
                                $bResultado = $this->NonQuery($sql);
                        }
                    }
                } else {
                    $bResultado = false;
                }
            }
        return $bResultado;
    }

    public function VerificarR () {
        $bResultado = false;

        $sqlV = "select id from vacaciones where 
            id_empleado = '{$this->id_empleado}' and periodo_inicio = '{$this->periodo_inicio}'
            and ano = '{$this->ano}'";
            $res = $this->Query($sqlV);
            

        if (count($res) > 0 ) {
            $bResultado = true;
        } else {
            $bResultado = false;
        }

        return $bResultado;
    }
    public function VerificarNomina () {
        $sql = "select count(id) as id from nominas where 
            fecha = '{$this->fecha_genera}' and estatus = 0 and fecha_pago is null 
        union all 
            select count(id) as id from nominas where 
            fecha = '{$this->fecha_genera}' and estatus = 1 and fecha_pago is not null";
        return $this->Query($sql);
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
