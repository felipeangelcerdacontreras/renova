<?php
/*
 * Copyright 2021 - Felipe angel cerda contreras 
 * felipeangelcerdacontreras@gmail.com
 */
$_SITE_PATH = $_SERVER["DOCUMENT_ROOT"] . "/" . explode("/", $_SERVER["PHP_SELF"])[1] . "/";
require_once($_SITE_PATH . "/app/model/principal.class.php");

class nominas extends AW
{

    var $id;
    var $fecha;
    var $estatus;
    var $user_id;
    var $id_empleado;

    var $id_nomina;
    var $nombre;
    var $asistencia;
    var $puntualidad;
    var $productividad;
    var $complemento;
    var $bono_viaje;
    var $semanal;
    var $faltas;
    var $asistencias;
    var $extras;
    var $total;
    var $comedor;
    var $ahorro;
    var $prestamos;
    var $fonacot;
    var $infonavit;
    var $otros;
    var $total_r;
    var $total_p;
    var $doce;
    var $diario;
    var $estatus_final_edit;

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
        $sql = "SELECT nominas.fecha, nominas.id,CONCAT(sum( nomina_detalle.total )) AS total_nomina,CASE WHEN nominas.estatus = 0 THEN 'NO PAGADA' WHEN nominas.estatus = 1 THEN
        'PAGADA' ELSE 'OTRO'END AS estatus, WEEK ( nominas.fecha ) AS semana FROM nominas LEFT JOIN nomina_detalle ON nominas.id = nomina_detalle.id_nomina 
        where fecha between '{$this->fecha_inicial}' and '{$this->fecha_final}' GROUP BY nominas.fecha, nominas.id 
        ORDER BY fecha ASC  ";

        return $this->Query($sql);
    }

    public function Listado_peticiones()
    {
        $sql = "SELECT a.*, concat(b.nombre_usuario) as solicitante FROM nomina_final_edit as a
            left join usuarios as b on a.id_usuario_p = b.id where a.estatus_final_edit = '1'";

        return $this->Query($sql);
    }
    public function AprovarDenegar()
    {
        $sql = "UPDATE `nomina_final_edit`
        SET 
        `estatus_final_edit` = '{$this->estatus}'
        WHERE `id` = '{$this->id}'";
        return $this->NonQuery($sql);
    }

    public function Listado_nomina()
    {
        $tabla = "nomina_final_edit";
        if (!empty($this->tabla)) {
            $tabla = "nomina_final";
        }

        $where = "id = '{$this->id}' and estatus_final_edit = '{$this->estatus}'";
        if (!empty($this->tabla)) {
            $where = "id_nomina = '{$this->id_nomina}' and id_empleado = '{$this->id_empleado}'";
        }


        $sql = "select * from {$tabla}  where {$where}";

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

    public function Listado_prenomina()
    {
        $sqlEmpleado = "";
        if (!empty($this->id_empleado)) {
            $sqlEmpleado = "and id_empleado = '{$this->id_empleado}'";
        }

        $sql = "SELECT a.*, b.* FROM nominas as a 
        inner join nomina_final as b on a.id = b.id_nomina where a.id = '{$this->id}' {$sqlEmpleado}";

        if (!empty($this->id_empleado)) {
            $res = parent::Query($sql);
            if (!empty($res) && !($res === NULL)) {
                foreach ($res[0] as $idx => $valor) {
                    $this->{$idx} = $valor;
                }
            } else {
                $res = NULL;
            }

            return $res;
        } else {
            return $this->Query($sql);
        }

        return $this->Query($sql);
    }

    public function Listado_nomina_final()
    {
        $sql = "SELECT a.*, b.* FROM nominas as a 
        inner join nomina_final_edit as b on a.id = b.id_nomina where b.id_nomina = '{$this->id}'";
        return $this->Query($sql);
    }

    public function Pagar()
    {
        $oNominas = new nominas();
        $oNominas->id = $this->id;
        $lstnominas = $oNominas->Listado_prenomina();

        if (count($lstnominas) > 0) {
            foreach ($lstnominas as $idx => $prenomina) {
                $oNominas_edit = new nominas();
                $oNominas_edit->id_nomina = $prenomina->id_nomina;
                $oNominas_edit->id_empleado = $prenomina->id_empleado;
                $oNominas_edit->Nomina_edit();
                if ($oNominas_edit->nombre != '' && $oNominas_edit->estatus_final_edit == "2") {

                    $id_nomina = $oNominas_edit->id_nomina;
                    $id_empleado = $oNominas_edit->id_empleado;

                    $total_r = $oNominas_edit->total_r;
                    $total_p = $oNominas_edit->total_p;
                    $fecha = $oNominas_edit->fecha;
                } else {
                    $id_nomina = $prenomina->id_nomina;
                    $id_empleado = $prenomina->id_empleado;

                    $total_r = $prenomina->total_r;
                    $total_p = $prenomina->total_p;
                    $fecha = $prenomina->fecha;
                }

                $retenciones = $total_r;
                $percepciones = $total_p;

                $sqlVacaciones = "select * from vacaciones_prima where id_empleado='{$id_empleado}' and estatus = '0' and fecha_pago between date_add('{$fecha}', INTERVAL -7 DAY) and '{$fecha}'";
                $resVacaciones = $this->Query($sqlVacaciones);

                if (!empty($resVacaciones) && !($resVacaciones === NULL)) {
                    foreach ($resVacaciones as $idx => $vacaciones) {
                        $sqlUpdatevacaciones = "UPDATE `vacaciones_prima`
                            SET
                            `estatus` = 1,
                            `fecha_pagada` = NOW() 
                            WHERE `id` = '{$vacaciones->id}'";
                        $this->NonQuery($sqlUpdatevacaciones);
                    }
                }

                $sqlOtros = "select * from otros where id_empleado='{$id_empleado}' and estatus = '1' and fecha_pago between date_add('{$fecha}', INTERVAL -7 DAY) and '{$fecha}'";
                $resOtros = $this->Query($sqlOtros);

                if (!empty($resOtros) && !($resOtros === NULL)) {
                    foreach ($resOtros as $idx => $otros) {
                        if ($otros->numero_semanas == $otros->semana_actual) {
                            $sqlUpdateOtros1 = "UPDATE `otros`
                                SET
                                `estatus` = '0',
                                `restante` = '" . ($otros->restante - $otros->monto_por_semana) . "' 
                                WHERE `id` = '{$otros->id}'";

                            $this->NonQuery($sqlUpdateOtros1);
                        } else {
                            $sqlUpdateOtros = "UPDATE `otros`
                                SET
                                `estatus` = '0',
                                `restante` = '" . ($otros->restante - $otros->monto_por_semana) . "' 
                                WHERE `id` = '{$otros->id}'";

                            $ressqlUpdateOtros = $this->NonQuery($sqlUpdateOtros);

                            if ($ressqlUpdateOtros) {
                                $sqlInsertOtros = "INSERT INTO `otros`
                                    (`id_empleado`,`numero_semanas`,`semana_actual`,`estatus`,`fecha_registro`,`fecha_pago`,
                                    `monto`,`monto_por_semana`,`monto_pagar`,`motivo`,`detalles`,`restante`)
                                    VALUES
                                    ('{$otros->id_empleado}','{$otros->numero_semanas}',
                                    '" . ($otros->semana_actual + 1) . "',
                                    '1','{$otros->fecha_registro}',
                                    date_add('{$otros->fecha_pago}', INTERVAL +7 DAY),
                                    '{$otros->monto}','{$otros->monto_por_semana}','{$otros->monto_pagar}','{$otros->motivo}','{$otros->detalles}'
                                    ,'" . ($otros->restante - $otros->monto_por_semana) . "')";

                                $this->NonQuery($sqlInsertOtros);
                            }
                        }
                    }
                }

                $sqlPrestamos = "select * from prestamos where id_empleado='{$id_empleado}' and fecha_pago between date_add('{$fecha}', INTERVAL -7 DAY) and '{$fecha}'";
                $resPrestamos = $this->Query($sqlPrestamos);

                if (!empty($resPrestamos) && !($resPrestamos === NULL)) {
                    foreach ($resPrestamos as $idx => $prestamos) {
                        if ($prestamos->numero_semanas == $prestamos->semana_actual && $prestamos->estatus == 1) {
                            $sqlUpdatePrestamos1 = "UPDATE `prestamos`
                                SET
                                `estatus` = 0,
                                `restante` = '" . ($prestamos->restante - $prestamos->monto_por_semana) . "' 
                                WHERE `id` = '{$prestamos->id}'";
                            $this->NonQuery($sqlUpdatePrestamos1);
                        } else if ($prestamos->estatus == 1) {
                            $sqlUpdatePrestamos = "UPDATE `prestamos`
                                SET 
                                `estatus` = 0,
                                `restante` = '" . ($prestamos->restante - $prestamos->monto_por_semana) . "'
                                WHERE `id` = '{$prestamos->id}'";
                            if ($this->NonQuery($sqlUpdatePrestamos)) {
                                $sqlInsertOtros = "INSERT INTO `prestamos`
                                    (`id_empleado`,`numero_semanas`, `estatus`,`fecha_registro`, `fecha_pago`, `monto`,`interes`, `monto_por_semana`,
                                    `monto_pagar`, `restante`, `semana_actual`)
                                    VALUES
                                    ('{$prestamos->id_empleado}','{$prestamos->numero_semanas}','1','{$prestamos->fecha_registro}',
                                    date_add('{$prestamos->fecha_pago}', INTERVAL +7 DAY),
                                    '{$prestamos->monto}','{$prestamos->interes}',
                                    '{$prestamos->monto_por_semana}','{$prestamos->monto_pagar}',
                                    '" . ($prestamos->restante - $prestamos->monto_por_semana) . "', 
                                    '" . ($prestamos->semana_actual + 1) . "')";
                                $this->NonQuery($sqlInsertOtros);
                            }
                        }
                    }
                }

                $sqlAhorros = "select * from ahorros where id_empleado='{$id_empleado}' and estatus = '1'";
                $resAhorros = $this->Query($sqlAhorros);

                if (!empty($resAhorros) && !($resAhorros === NULL)) {
                    foreach ($resAhorros as $idx => $ahorros) {
                        $sqlUpdateahorros = "UPDATE `ahorros`
                            SET
                            `frecuencia` = frecuencia + 1,
                            `acumulado` = `acumulado` + `monto` 
                            WHERE `id` = '{$ahorros->id}'";
                        $this->NonQuery($sqlUpdateahorros);
                    }
                }

                $sqlfonacot = "select * from fonacot where id_empleado='{$id_empleado}' and fecha_pago between date_add('{$fecha}', INTERVAL -7 DAY) and '{$fecha}'";
                $resfonacot = $this->Query($sqlfonacot);

                if (!empty($resfonacot) && !($resfonacot === NULL)) {
                    foreach ($resfonacot as $idx => $fonacot) {
                        if ($fonacot->estatus == "1") {
                            $sqlUpdatefonacot = "UPDATE `fonacot`
                            SET 
                            `estatus` = 0
                            WHERE `id` = '{$fonacot->id}'";

                            if ($this->NonQuery($sqlUpdatefonacot)) {
                                $sqlInsertOtros = "INSERT INTO `fonacot`
                                (`id_empleado`, `estatus`,`fecha_registro`, `fecha_pago`, `monto_por_semana`)
                                VALUES
                                ('{$fonacot->id_empleado}','1','{$fonacot->fecha_registro}',
                                date_add('{$fonacot->fecha_pago}', INTERVAL +7 DAY), '{$fonacot->monto_por_semana}')";
                                $this->NonQuery($sqlInsertOtros);
                            }
                        }
                    }
                }

                $sqlinfonavit = "select * from infonavit where id_empleado='{$id_empleado}' and fecha_pago between date_add('{$fecha}', INTERVAL -7 DAY) and '{$fecha}'";
                $resinfonavit = $this->Query($sqlinfonavit);

                if (!empty($resinfonavit) && !($resinfonavit === NULL)) {
                    foreach ($resinfonavit as $idx => $infonavit) {
                        if ($infonavit->estatus == "1") {
                            $sqlUpdateinfonavit = "UPDATE `infonavit`
                            SET 
                            `estatus` = 0
                            WHERE `id` = '{$infonavit->id}'";

                            if ($this->NonQuery($sqlUpdateinfonavit)) {
                                $sqlInsertOtros = "INSERT INTO `infonavit`
                                (`id_empleado`, `estatus`,`fecha_registro`, `fecha_pago`,  `monto_por_semana`)
                                VALUES
                                ('{$infonavit->id_empleado}','1','{$infonavit->fecha_registro}',
                                date_add('{$infonavit->fecha_pago}', INTERVAL +7 DAY),
                                '{$infonavit->monto_por_semana}')";
                                $this->NonQuery($sqlInsertOtros);
                            }
                        }
                    }
                }
                $sql1 = "INSERT INTO `nomina_detalle` (`id_nomina`,`id_empleado`,`percepciones`,`retenciones`, `total`)
                    VALUES
                ('{$id_nomina}','{$id_empleado}','{$percepciones}','{$retenciones}','" . ($percepciones + $retenciones) . "')";
                $this->NonQuery($sql1);
            }
        }

        $sql2 = "update nominas set estatus = '1', fecha_pago = now() where id='{$this->id}'";
        return $this->NonQuery($sql2);
    }

    public function Informacion()
    {

        $sql = "select * from nominas where  id='{$this->id}'";
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

    public function Nomina_edit()
    {

        $sql = "select * from nomina_final_edit where  id_nomina = '{$this->id_nomina}' and id_empleado = '{$this->id_empleado}' order by id desc limit 1";
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
        $sql = "select id from nominas where id='{$this->id}'";
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
                    nominas
                set
                nombre = '{$this->nombre}',
                placa = '{$this->placa}',
                ano = '{$this->ano}',
                marca='{$this->marca}',
                usuario_edicion = '{$this->user_id}',
                fecha_modificacion = now()
                where
                  id='{$this->id}'";
        return $this->NonQuery($sql);
    }

    public function DiasVacacion($num, $fecha, $inicio_vacaci, $fin_vacaci)
    {
        $total_dias = 0;
        $fecha_fin = date("Y-m-d", strtotime($fecha . "- 6 days"));

        if ($inicio_vacaci >= $fecha_fin  && $inicio_vacaci <= $fecha) {
            for ($i = 0; $i <= $num; $i++) {
                $sqlFecha = "SELECT DATE_FORMAT(DATE_ADD('{$inicio_vacaci}', INTERVAL $i DAY), '%Y-%m-%d') as fecha";
                $resultFecha = parent::Query($sqlFecha);

                $sqlFEstivos = "SELECT * FROM festivos where fecha = '{$resultFecha[0]->fecha}'";
                $res = $this->Query($sqlFEstivos);

                if (count($res) <= 0) {
                    if ($resultFecha[0]->fecha == $fecha) {
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
        } else if ($inicio_vacaci <= $fecha  && $fin_vacaci >= $fecha) {
            for ($i = 0; $i <= $num; $i++) {
                $sqlFecha = "SELECT DATE_FORMAT(DATE_ADD('{$fecha_fin}', INTERVAL $i DAY), '%Y-%m-%d') as fecha";
                $resultFecha = parent::Query($sqlFecha);

                $sqlFEstivos = "SELECT * FROM festivos where fecha = '{$resultFecha[0]->fecha}'";
                $res = $this->Query($sqlFEstivos);

                if (count($res) <= 0) {
                    if ($resultFecha[0]->fecha == $fecha) {
                        $sql = "SELECT if( DAYOFWEEK(DATE_FORMAT(DATE_ADD('{$fecha_fin}', INTERVAL $i DAY), '%Y-%m-%d')) < 2, 0, 1) as dia";
                        $result = parent::Query($sql);
                        $total_dias = $total_dias + $result[0]->dia;
                        break;
                    } else {
                        $sql = "SELECT if( DAYOFWEEK(DATE_FORMAT(DATE_ADD('{$fecha_fin}', INTERVAL $i DAY), '%Y-%m-%d')) < 2, 0, 1) as dia";
                        $result = parent::Query($sql);
                        $total_dias = $total_dias + $result[0]->dia;
                    }
                }
            }
        } else if ($fin_vacaci >= $fecha_fin && $fin_vacaci <= $fecha) {
            if ($fin_vacaci >= $fecha) {
                $fin_vacaci = $fecha;
            }
            for ($i = 0; $i <= $num; $i++) {
                $sqlFecha = "SELECT DATE_FORMAT(DATE_ADD('{$fin_vacaci}', INTERVAL -$i DAY), '%Y-%m-%d') as fecha";
                $resultFecha = parent::Query($sqlFecha);

                $sqlFEstivos = "SELECT * FROM festivos where fecha = '{$resultFecha[0]->fecha}'";
                $res = $this->Query($sqlFEstivos);

                if (count($res) <= 0) {
                    if ($resultFecha[0]->fecha == $fecha_fin) {
                        $sql = "SELECT if( DAYOFWEEK(DATE_FORMAT(DATE_ADD('{$fin_vacaci}', INTERVAL -$i DAY), '%Y-%m-%d')) < 0, 0, 1) as dia";
                        $result = parent::Query($sql);
                        $total_dias = $total_dias + $result[0]->dia;
                        break;
                    } else if ($resultFecha[0]->fecha >= $fecha_fin) {
                        $sql = "SELECT if( DAYOFWEEK(DATE_FORMAT(DATE_ADD('{$fin_vacaci}', INTERVAL -$i DAY), '%Y-%m-%d')) < 0, 0, 1) as dia";
                        $result = parent::Query($sql);
                        $total_dias = $total_dias + $result[0]->dia;
                    }
                }
            }
        }
        return $total_dias;
    }

    public function DiasIncapacidad($num, $fecha, $inicio_vacaci, $fin_vacaci)
    {
        $total_dias = 0;
        $fecha_fin = date("Y-m-d", strtotime($fecha . "- 6 days"));

        if ($inicio_vacaci >= $fecha_fin  && $inicio_vacaci <= $fecha) {
            for ($i = 0; $i <= $num; $i++) {
                $sqlFecha = "SELECT DATE_FORMAT(DATE_ADD('{$inicio_vacaci}', INTERVAL $i DAY), '%Y-%m-%d') as fecha";
                $resultFecha = parent::Query($sqlFecha);

                $sqlFEstivos = "SELECT * FROM festivos where fecha = '{$resultFecha[0]->fecha}'";
                $res = $this->Query($sqlFEstivos);

                if (count($res) <= 0) {
                    if ($resultFecha[0]->fecha == $fecha) {
                        $sql = "SELECT if( DAYOFWEEK(DATE_FORMAT(DATE_ADD('{$inicio_vacaci}', INTERVAL $i DAY), '%Y-%m-%d')) < 0, 0, 1) as dia";
                        print_r($sql);
                        $result = parent::Query($sql);
                        $total_dias = $total_dias + $result[0]->dia;
                        print_r("Total:". $total_dias );
                        break;
                    } else {
                        $sql = "SELECT if( DAYOFWEEK(DATE_FORMAT(DATE_ADD('{$inicio_vacaci}', INTERVAL $i DAY), '%Y-%m-%d')) < 0, 0, 1) as dia";
                        print_r($sql);
                        $result = parent::Query($sql);
                        $total_dias = $total_dias + $result[0]->dia;
                        print_r("Total:". $total_dias );
                    }
                }
            }
        } else if ($inicio_vacaci <= $fecha  && $fin_vacaci >= $fecha) {
            print_r("llaga else");
            for ($i = 0; $i <= $num; $i++) {
                $sqlFecha = "SELECT DATE_FORMAT(DATE_ADD('{$fecha_fin}', INTERVAL $i DAY), '%Y-%m-%d') as fecha";
                $resultFecha = parent::Query($sqlFecha);

                $sqlFEstivos = "SELECT * FROM festivos where fecha = '{$resultFecha[0]->fecha}'";
                $res = $this->Query($sqlFEstivos);

                if (count($res) <= 0) {
                    if ($resultFecha[0]->fecha == $fecha) {
                        $sql = "SELECT if( DAYOFWEEK(DATE_FORMAT(DATE_ADD('{$fecha_fin}', INTERVAL $i DAY), '%Y-%m-%d')) < 0, 0, 1) as dia";
                        $result = parent::Query($sql);
                        $total_dias = $total_dias + $result[0]->dia;
                        break;
                    } else {
                        $sql = "SELECT if( DAYOFWEEK(DATE_FORMAT(DATE_ADD('{$fecha_fin}', INTERVAL $i DAY), '%Y-%m-%d')) < 0, 0, 1) as dia";
                        $result = parent::Query($sql);
                        $total_dias = $total_dias + $result[0]->dia;
                    }
                }
            }
        } else if ($fin_vacaci >= $fecha_fin && $fin_vacaci <= $fecha) {
            print_r("llaga else 2");
            if ($fin_vacaci >= $fecha) {
                $fin_vacaci = $fecha;
            }
            for ($i = 0; $i <= $num; $i++) {
                $sqlFecha = "SELECT DATE_FORMAT(DATE_ADD('{$fin_vacaci}', INTERVAL -$i DAY), '%Y-%m-%d') as fecha";
                $resultFecha = parent::Query($sqlFecha);

                $sqlFEstivos = "SELECT * FROM festivos where fecha = '{$resultFecha[0]->fecha}'";
                $res = $this->Query($sqlFEstivos);

                if (count($res) <= 0) {
                    if ($resultFecha[0]->fecha == $fecha_fin) {
                        $sql = "SELECT if( DAYOFWEEK(DATE_FORMAT(DATE_ADD('{$fin_vacaci}', INTERVAL -$i DAY), '%Y-%m-%d')) < 0, 0, 1) as dia";
                        $result = parent::Query($sql);
                        $total_dias = $total_dias + $result[0]->dia;
                        break;
                    } else if ($resultFecha[0]->fecha >= $fecha_fin) {
                        $sql = "SELECT if( DAYOFWEEK(DATE_FORMAT(DATE_ADD('{$fin_vacaci}', INTERVAL -$i DAY), '%Y-%m-%d')) < 0, 0, 1) as dia";
                        $result = parent::Query($sql);
                        $total_dias = $total_dias + $result[0]->dia;
                    }
                }
            }
        }
        return $total_dias;
    }

    public function Nomina($id)
    {
        $sqlNomina = "SELECT 
            a.*,
            c.id AS id_empleado,
            c.ape_paterno,
            c.ape_materno,
            nombres,
            h.nombre AS puesto,
            i.nombre AS departamento,
            c.rfc,
            c.curp,
            c.fecha_ingreso,
            WEEK(a.fecha) AS semana,
            c.salario_semanal,
            c.salario_diario,
            c.salario_asistencia,
            c.salario_puntualidad,
            c.salario_productividad,
            c.complemento_sueldo,
            c.bono_doce,
            c.id_horario,
            (SELECT  COUNT(dia) FROM asistencia  WHERE id_empleado = c.id AND estatus_entrada = 1 AND 
            fecha BETWEEN DATE_ADD(a.fecha, INTERVAL - 6 DAY) AND a.fecha) AS dias_laborados,

            (SELECT  COUNT(dia) FROM asistencia  WHERE id_empleado = c.id AND estatus_entrada = 2 AND 
            fecha BETWEEN DATE_ADD(a.fecha, INTERVAL - 6 DAY) AND a.fecha) AS dias_laborados1,

            (SELECT COUNT(quitar_bonos) FROM  asistencia WHERE id_empleado = c.id
            and quitar_bonos > 0
            AND fecha BETWEEN DATE_ADD(a.fecha, INTERVAL - 6 DAY) AND a.fecha) AS quitar_bonos,
            
            ((SELECT SUM(horas_extras) FROM horas_extras WHERE id_empleado = c.id AND estatus = 2
            AND fecha_registro BETWEEN DATE_ADD(a.fecha, INTERVAL - 7 DAY) AND a.fecha) * (c.salario_diario / 8) * 2) AS horas_extras,
            
            ((SELECT COUNT(dia) + 1 FROM asistencia WHERE id_empleado = c.id AND estatus_entrada = 1 AND
            fecha BETWEEN DATE_ADD(a.fecha, INTERVAL - 6 DAY) AND a.fecha) * c.salario_diario) AS esperado,
            
            (SELECT  SUM(monto_por_semana) FROM  otros WHERE estatus = 1 and id_empleado = c.id
            AND fecha_pago BETWEEN DATE_ADD(a.fecha, INTERVAL - 6 DAY) AND a.fecha) AS otros_descuentos,
            
            (SELECT monto_por_semana FROM prestamos WHERE estatus = 1 and id_empleado = c.id AND 
            fecha_pago BETWEEN DATE_ADD(a.fecha, INTERVAL - 6 DAY) AND a.fecha) AS prestamos,
            
            (SELECT monto_por_semana FROM fonacot WHERE estatus = 1 and id_empleado = c.id AND 
            fecha_pago BETWEEN DATE_ADD(a.fecha, INTERVAL - 6 DAY) AND a.fecha) AS fonacot,
            
            (SELECT monto_por_semana FROM infonavit WHERE estatus = 1 and id_empleado = c.id AND 
            fecha_pago BETWEEN DATE_ADD(a.fecha, INTERVAL - 6 DAY) AND a.fecha) AS infonavit,

            j.monto,
            j.frecuencia,
            j.estatus AS estatusAhorro,
            ((SELECT SUM(precio_platillo) FROM comedor WHERE id_empleado = c.id AND 
            fecha BETWEEN DATE_ADD(a.fecha, INTERVAL - 6 DAY) AND a.fecha)) AS comedor,

            ((SELECT pago_prima FROM vacaciones WHERE id_empleado = c.id AND fecha_pago 
            BETWEEN DATE_ADD(a.fecha, INTERVAL - 6 DAY) AND a.fecha limit 1)) AS vacaciones,

            ((SELECT inicio_vacaci
            FROM vacaciones where id_empleado = c.id and
            (inicio_vacaci between DATE_ADD(a.fecha, INTERVAL - 6 DAY) and a.fecha or 
            fin_vacaci between DATE_ADD(a.fecha, INTERVAL - 6 DAY) and a.fecha) limit 1)) as inicio_vacaci,
            ((SELECT fin_vacaci
            FROM vacaciones where id_empleado = c.id and 
            (inicio_vacaci between DATE_ADD(a.fecha, INTERVAL - 6 DAY) and a.fecha or 
            fin_vacaci between DATE_ADD(a.fecha, INTERVAL - 6 DAY) and a.fecha) limit 1)) as fin_vacaci,
            ((SELECT DATEDIFF(fin_vacaci, inicio_vacaci) AS days
            FROM vacaciones where id_empleado = c.id and 
            (inicio_vacaci between DATE_ADD(a.fecha, INTERVAL - 6 DAY) and a.fecha or 
            fin_vacaci between DATE_ADD(a.fecha, INTERVAL - 6 DAY) and a.fecha) limit 1)) as daysVaca,
            ((SELECT inicio_incapacida
            FROM incapacidades where id_empleado = c.id and
            (inicio_incapacida between DATE_ADD(a.fecha, INTERVAL - 6 DAY) and a.fecha or 
            fin_incapacida between DATE_ADD(a.fecha, INTERVAL - 6 DAY) and a.fecha) limit 1)) as inicio_incapacida,
            ((SELECT fin_incapacida
            FROM incapacidades where id_empleado = c.id and 
            (inicio_incapacida between DATE_ADD(a.fecha, INTERVAL - 6 DAY) and a.fecha or 
            fin_incapacida between DATE_ADD(a.fecha, INTERVAL - 6 DAY) and a.fecha) limit 1)) as fin_incapacida,
            ((SELECT dias_autorizados
            FROM incapacidades where id_empleado = c.id and 
            (inicio_incapacida between DATE_ADD(a.fecha, INTERVAL - 6 DAY) and a.fecha or 
            fin_incapacida between DATE_ADD(a.fecha, INTERVAL - 6 DAY) and a.fecha) order by id desc limit 1)) as daysIncapa,
            ((SELECT monto_dia
            FROM incapacidades where id_empleado = c.id and 
            (inicio_incapacida between DATE_ADD(a.fecha, INTERVAL - 6 DAY) and a.fecha or 
            fin_incapacida between DATE_ADD(a.fecha, INTERVAL - 6 DAY) and a.fecha) order by id desc limit 1)) as monto_dia,
            ((SELECT count(id) FROM festivos where fecha 
            between DATE_ADD(a.fecha, INTERVAL - 6 DAY) and a.fecha limit 1))as festivos,
            0 as NominaAdministrativa
            FROM nominas a 
            LEFT JOIN empleados c ON c.id
            LEFT JOIN horas_extras AS d ON c.id = d.id_empleado
            LEFT JOIN (SELECT dia, id_empleado, fecha FROM asistencia) e ON c.id = e.id_empleado
            LEFT JOIN (SELECT * FROM otros) f ON c.id = f.id_empleado
            LEFT JOIN (SELECT * FROM prestamos) g ON c.id = g.id_empleado
            LEFT JOIN (SELECT * FROM puestos) h ON c.id_puesto = h.id
            LEFT JOIN (SELECT * FROM departamentos) i ON h.id_departamento = i.id
            LEFT JOIN (SELECT * FROM ahorros) j ON c.id = j.id_empleado
            LEFT JOIN (SELECT * FROM vacaciones) k ON c.id = k.id_empleado
            WHERE a.id ='{$id}' and c.estatus = '1' group by c.id";
        $resNomina = $this->Query($sqlNomina);

        $sqlAdminist = "SELECT 
            a.*,
            c.id AS id_empleado,
            c.ape_paterno,
            c.ape_materno,
            nombres,
            h.nombre AS puesto,
            i.nombre AS departamento,
            c.rfc,
            c.curp,
            c.fecha_ingreso,
            WEEK(a.fecha) AS semana,
            c.salario_semanal,
            c.salario_diario,
            c.salario_asistencia,
            c.salario_puntualidad,
            c.salario_productividad,
            c.complemento_sueldo,
            c.bono_doce,
            c.id_horario,
            1 AS dias_laborados,
            5 AS dias_laborados1,
            (SELECT COUNT(quitar_bonos) FROM  asistencia WHERE id_empleado = c.id
            and quitar_bonos > 0
            AND fecha BETWEEN DATE_ADD(a.fecha, INTERVAL - 6 DAY) AND a.fecha) AS quitar_bonos,
            
            ((SELECT SUM(horas_extras) FROM horas_extras WHERE id_empleado = c.id AND estatus = 2
            AND fecha_registro BETWEEN DATE_ADD(a.fecha, INTERVAL - 7 DAY) AND a.fecha) * (c.salario_diario / 8) * 2) AS horas_extras,
            
            ((SELECT COUNT(dia) + 1 FROM asistencia WHERE id_empleado = c.id AND estatus_entrada = 1 AND
            fecha BETWEEN DATE_ADD(a.fecha, INTERVAL - 6 DAY) AND a.fecha) * c.salario_diario) AS esperado,
            
            (SELECT  SUM(monto_por_semana) FROM  otros WHERE estatus = 1 and id_empleado = c.id
            AND fecha_pago BETWEEN DATE_ADD(a.fecha, INTERVAL - 6 DAY) AND a.fecha) AS otros_descuentos,
            
            (SELECT monto_por_semana FROM prestamos WHERE estatus = 1 and id_empleado = c.id AND 
            fecha_pago BETWEEN DATE_ADD(a.fecha, INTERVAL - 6 DAY) AND a.fecha) AS prestamos,
            
            (SELECT monto_por_semana FROM fonacot WHERE estatus = 1 and id_empleado = c.id AND 
            fecha_pago BETWEEN DATE_ADD(a.fecha, INTERVAL - 6 DAY) AND a.fecha) AS fonacot,
            
            (SELECT monto_por_semana FROM infonavit WHERE estatus = 1 and id_empleado = c.id AND 
            fecha_pago BETWEEN DATE_ADD(a.fecha, INTERVAL - 6 DAY) AND a.fecha) AS infonavit,
            j.monto,
            j.frecuencia,
            j.estatus AS estatusAhorro,
            ((SELECT SUM(precio_platillo) FROM comedor WHERE id_empleado = c.id AND 
            fecha BETWEEN DATE_ADD(a.fecha, INTERVAL - 6 DAY) AND a.fecha)) AS comedor,

            ((SELECT pago_prima FROM vacaciones WHERE id_empleado = c.id AND fecha_pago 
            BETWEEN DATE_ADD(a.fecha, INTERVAL - 6 DAY) AND a.fecha limit 1)) AS vacaciones,

            ((SELECT inicio_vacaci
            FROM vacaciones where id_empleado = c.id and
            (inicio_vacaci between DATE_ADD(a.fecha, INTERVAL - 6 DAY) and a.fecha or 
            fin_vacaci between DATE_ADD(a.fecha, INTERVAL - 6 DAY) and a.fecha) limit 1)) as inicio_vacaci,
            ((SELECT fin_vacaci
            FROM vacaciones where id_empleado = c.id and 
            (inicio_vacaci between DATE_ADD(a.fecha, INTERVAL - 6 DAY) and a.fecha or 
            fin_vacaci between DATE_ADD(a.fecha, INTERVAL - 6 DAY) and a.fecha) limit 1)) as fin_vacaci,
            ((SELECT DATEDIFF(fin_vacaci, inicio_vacaci) AS days
            FROM vacaciones where id_empleado = c.id and 
            (inicio_vacaci between DATE_ADD(a.fecha, INTERVAL - 6 DAY) and a.fecha or 
            fin_vacaci between DATE_ADD(a.fecha, INTERVAL - 6 DAY) and a.fecha) limit 1)) as daysVaca,
            ((SELECT inicio_incapacida
            FROM incapacidades where id_empleado = c.id and
            (inicio_incapacida between DATE_ADD(a.fecha, INTERVAL - 6 DAY) and a.fecha or 
            fin_incapacida between DATE_ADD(a.fecha, INTERVAL - 6 DAY) and a.fecha) limit 1)) as inicio_incapacida,
            ((SELECT fin_incapacida
            FROM incapacidades where id_empleado = c.id and 
            (inicio_incapacida between DATE_ADD(a.fecha, INTERVAL - 6 DAY) and a.fecha or 
            fin_incapacida between DATE_ADD(a.fecha, INTERVAL - 6 DAY) and a.fecha) limit 1)) as fin_incapacida,
            ((SELECT dias_autorizados
            FROM incapacidades where id_empleado = c.id and 
            (inicio_incapacida between DATE_ADD(a.fecha, INTERVAL - 6 DAY) and a.fecha or 
            fin_incapacida between DATE_ADD(a.fecha, INTERVAL - 6 DAY) and a.fecha) order by id desc limit 1)) as daysIncapa,
            ((SELECT monto_dia
            FROM incapacidades where id_empleado = c.id and 
            (inicio_incapacida between DATE_ADD(a.fecha, INTERVAL - 6 DAY) and a.fecha or 
            fin_incapacida between DATE_ADD(a.fecha, INTERVAL - 6 DAY) and a.fecha) order by id desc limit 1)) as monto_dia,
            ((SELECT count(id) FROM festivos where fecha 
            between DATE_ADD(a.fecha, INTERVAL - 6 DAY) and a.fecha limit 1))as festivos,
            1 as NominaAdministrativa
            FROM nominas a 
            LEFT JOIN empleados c ON c.id
            LEFT JOIN horas_extras AS d ON c.id = d.id_empleado
            LEFT JOIN (SELECT dia, id_empleado, fecha FROM asistencia) e ON c.id = e.id_empleado
            LEFT JOIN (SELECT * FROM otros) f ON c.id = f.id_empleado
            LEFT JOIN (SELECT * FROM prestamos) g ON c.id = g.id_empleado
            LEFT JOIN (SELECT * FROM puestos) h ON c.id_puesto = h.id
            LEFT JOIN (SELECT * FROM departamentos) i ON h.id_departamento = i.id
            LEFT JOIN (SELECT * FROM ahorros) j ON c.id = j.id_empleado
            LEFT JOIN (SELECT * FROM vacaciones) k ON c.id = k.id_empleado
            WHERE a.id ='{$this->id}' and c.id = '35' || c.id = '7' || c.id = '26' || c.id = '90' || c.id = '85' 
            and c.estatus = '1' group by c.id";
        $resAdmin = $this->Query($sqlAdminist);

        foreach ($resAdmin as $idx => $campo) {
            array_push($resNomina, $campo);
        }
        return $resNomina;
    }

    public function Solicitud()
    {
        $sqlNomina = "select * from nomina_final where id_nomina = '{$this->id_}' and id_empleado = '{$this->id_empleado_}'";
        $resNomina = $this->Query($sqlNomina);

        $update = "UPDATE `nomina_final` SET `estatus_final` = '1' WHERE `id_nomina` = '{$this->id_}' and id_empleado = '{$this->id_empleado_}'";

        if ($this->NonQuery($update)) {
            $sqlEmpleado = "select * from empleados where id = '{$this->id_empleado_}'";
            $resEmpleado = $this->Query($sqlEmpleado);

            if ($resNomina[0]) {

                $totalEsperado = 0;
                $totalRetenciones = 0;
                $total_incapacidades = 0;
                //vareables para insert
                $nombre = $resNomina[0]->nombre;

                //verificar si esta se
                if ($this->asistencia_ == '1') {
                    $asistencia = bcdiv($resEmpleado[0]->salario_asistencia, '1', 2);
                    $totalEsperado = $totalEsperado + $asistencia;
                } else {
                    $asistencia = bcdiv($resNomina[0]->asistencia, '1', 2);
                    $totalEsperado = $totalEsperado + $asistencia;
                }

                if ($this->puntualidad_ == '1') {
                    $puntualidad = bcdiv($resEmpleado[0]->salario_puntualidad, '1', 2);
                    $totalEsperado = $totalEsperado + $puntualidad;
                } else {
                    $puntualidad = bcdiv($resNomina[0]->puntualidad, '1', 2);
                    $totalEsperado = $totalEsperado + $puntualidad;
                }

                if ($this->laborados_ != $resNomina[0]->asistencias) {
                    $faltas = (7 - $this->laborados_);
                    $asistencias = $this->laborados_;
                } else {
                    $faltas = $resNomina[0]->faltas;
                    $asistencias = $resNomina[0]->asistencias;
                }

                $productividad = bcdiv($resEmpleado[0]->salario_productividad, '1', 2);

                $s_productividad = '';
                if ($asistencias < 7) {
                    if ($asistencias <= 1) {
                        $s_productividad = $productividad / 6 * (0);
                        $totalEsperado = $totalEsperado +  $s_productividad;
                    } else {
                        $s_productividad = $productividad / 6 * ($asistencias - 1);
                        $totalEsperado = $totalEsperado +  $s_productividad;
                    }
                    
                } else {
                    $s_productividad = $productividad;
                    $totalEsperado = $totalEsperado +  $s_productividad;
                }

                if ($resNomina[0]->complemento != $this->complemento_) {
                    $complemento = $this->complemento_;
                } else {
                    $complemento = bcdiv($resNomina[0]->complemento, '1', 2);
                }

                $s_doce = '';
                if ($asistencias < 7) {
                    if ($asistencias <= 1) { 
                        $s_doce = $resEmpleado[0]->bono_doce / 6 * (0);
                        $totalEsperado = $totalEsperado +  $s_doce;
                    } else {
                        $s_doce = $resEmpleado[0]->bono_doce / 6 * ($asistencias - 1);
                        $totalEsperado = $totalEsperado +  $s_doce;
                    }
                } else {
                    $s_doce = $resEmpleado[0]->bono_doce;
                    $totalEsperado = $totalEsperado +  $s_doce;
                }

                $diario = bcdiv($resNomina[0]->diario, '1', 2);
                $extras = bcdiv($resNomina[0]->extras, '1', 2);

                //total esperado
                if ($asistencias < 1) {
                    $totalEsperado = $totalEsperado + 0;
                } else {
                    $totalEsperado = $totalEsperado + $resEmpleado[0]->salario_diario * $asistencias;
                }

                $totalEsperado = $totalEsperado + $extras;
                $totalEsperado = $totalEsperado + $complemento;

                if ($resNomina[0]->dias_incapacida != "" && $resNomina[0]->dias_incapacida > 0 && $resNomina[0]->monto_incapacida != "0.00") {
                    if (!empty($resNomina[0]->monto_incapacida) && $resNomina[0]->monto_incapacida != "0.00") {
                        $total_incapacidades = ($resNomina[0]->dias_incapacida * $resNomina[0]->monto_incapacida);
                    }
                    $totalEsperado = $totalEsperado + $total_incapacidades;
                }

                $total = $totalEsperado;
                //correccion de comedor 
                if ($resNomina[0]->comedor != $this->comedor_) {
                    $comedor = $this->comedor_;
                    $totalRetenciones = $totalRetenciones + $comedor;
                } else {
                    $comedor = $resNomina[0]->comedor;
                    $totalRetenciones = $totalRetenciones + $comedor;
                }

                $ahorro = $resNomina[0]->ahorro;
                $totalRetenciones = $totalRetenciones + $ahorro;

                $prestamos = bcdiv($resNomina[0]->prestamos, '1', 2);
                $totalRetenciones = $totalRetenciones + $prestamos;

                $fonacot = bcdiv($resNomina[0]->fonacot, '1', 2);
                $totalRetenciones = $totalRetenciones + $fonacot;

                $infonavit = bcdiv($resNomina[0]->infonavit, '1', 2);
                $totalRetenciones = $totalRetenciones + $infonavit;

                $otros = bcdiv($resNomina[0]->otros, '1', 2);
                $totalRetenciones = $totalRetenciones + $otros;

                $total_r = bcdiv($totalRetenciones, '1', 2);
                $total_p = bcdiv($totalEsperado - $totalRetenciones, '1', 2);

                $sqlInserNominaEdit = "INSERT INTO `nomina_final_edit`
                    (`id_nomina`,`id_empleado`,`nombre`,`asistencia`,`puntualidad`,`productividad`,`doce`,`complemento`,`diario`,`faltas`,
                    `asistencias`,`extras`,`monto_incapacida`,`dias_incapacida`,`total`,`comedor`,`ahorro`,`prestamos`,`fonacot`,`infonavit`,
                    `otros`,`total_r`,`total_p`,`fecha`,`id_usuario_p`,`estatus_final_edit`)
                    VALUES
                    ('{$this->id_}','{$this->id_empleado_}','{$nombre}','{$asistencia}','{$puntualidad}','{$s_productividad}','{$s_doce}', 
                    '{$complemento}','{$diario}','{$faltas}','{$asistencias}','{$extras}','{$resNomina[0]->monto_dia}','{$resNomina[0]->dias_incapacidades}','{$total}','{$comedor}','{$ahorro}',
                    '{$prestamos}','{$fonacot}','{$infonavit}','{$otros}','{$total_r}','{$total_p}','{$resNomina[0]->fecha}','{$_SESSION[$this->NombreSesion]->id}','1')";
                $bResultado = $this->NonQuery($sqlInserNominaEdit);
            }
        }
        return $bResultado;
    }

    public function Agregar()
    {
        $bResultado = false;

        if (empty($this->recalcular)) {
            $this->BeginTransaction("START TRANSACTION;");

            $sql = "insert into nominas
                    (`id`,`fecha`,`estatus`)
                    values
                    ('0','{$this->fecha}','0')";
            $bResultado = $this->NonQuery($sql);

            $sql1 = "select id from nominas order by id desc limit 1";
            $res = $this->Query($sql1);

            $this->id = $res[0]->id;
        } else {
            $bResultado = true;
        }

        if ($bResultado && !empty($this->id)) {
            $resNomina = $this->Nomina($this->id);

            $countRow = 0;
            if (count($resNomina) > 0) {

                foreach ($resNomina as $idx => $campo) {
                    $totalEsperado = 0;
                    $totalRetenciones = 0;
                    $dias_vacaciones = 0;
                    $dias_incapacidades = 0;
                    $total_incapacidades = 0;

                    if ($campo->daysVaca != "" && $campo->inicio_vacaci != "" && $campo->fin_vacaci != "" && $campo->NominaAdministrativa == '0') {
                        $dias_vacaciones = $this->DiasVacacion($campo->daysVaca, $campo->fecha, $campo->inicio_vacaci, $campo->fin_vacaci);
                    }

                    if ($campo->id_horario == "16" && $campo->festivos <= 1) {
                        $campo->dias_laborados = $campo->dias_laborados + 1;
                    }

                    if ($campo->dias_laborados > 0 || $campo->dias_laborados1 > 0  ) {
                        $campo->dias_laborados = $campo->dias_laborados + $campo->dias_laborados1;
                        $campo->dias_laborados = $campo->dias_laborados + 1;
                    }


                    if ($dias_vacaciones > 0 && $campo->NominaAdministrativa == "0") {

                        $campo->dias_laborados = $campo->dias_laborados + $dias_vacaciones;
                        if ($campo->dias_laborados > 1 && $campo->dias_laborados <= 6) {
                            $campo->dias_laborados = $campo->dias_laborados + $campo->festivos;
                            if ($campo->dias_laborados < 7) {
                                if ($dias_vacaciones < 5 ){
                                    $campo->dias_laborados = $campo->dias_laborados + 1;
                                }
                            }
                        } else {
                            if ($campo->dias_laborados > 1) {
                                $campo->dias_laborados = $campo->dias_laborados + $campo->festivos;
                            }
                        }
                        if ($campo->dias_laborados > 5 && $campo->dias_laborados < 7 && $dias_vacaciones < 5 ) {
                            $campo->dias_laborados = $campo->dias_laborados + 1;
                        }
                    } else {
                        if ($campo->id_horario == "16" && $campo->dias_laborados >= 6 && $campo->NominaAdministrativa == "0") {
                        } else {
                            if ($campo->NominaAdministrativa == "0") {
                                $campo->dias_laborados = $campo->dias_laborados + $campo->festivos;
                            }
                        }
                    }
                    //vareables para insert
                    $nombre = ucwords($campo->ape_paterno . " " . $campo->ape_materno . " " . $campo->nombres);

                    $asistencia = "0.00";
                    if ($campo->dias_laborados > 6) {
                        $asistencia = bcdiv($campo->salario_asistencia, '1', 2);
                        $totalEsperado = $totalEsperado +  $asistencia;
                    }

                    if ($campo->quitar_bonos >= 1 || $campo->dias_laborados < 7) {
                        $puntualidad = "0.00";
                    } else {
                        $puntualidad = bcdiv($campo->salario_puntualidad, '1', 2);
                        $totalEsperado = $totalEsperado + $puntualidad;
                    }

                    $productividad = bcdiv($campo->salario_productividad, '1', 2);

                    $complemento = bcdiv($campo->complemento_sueldo, '1', 2);

                    $diario = bcdiv($campo->salario_diario, '1', 2);

                    $faltas = (7 - $campo->dias_laborados);

                    $asistencias = $campo->dias_laborados;

                    if ($asistencias <= 1) {
                        $totalEsperado = $totalEsperado + 0;
                        if ($dias_vacaciones > 0){
                            $asistencias = $dias_vacaciones + 1;
                            $faltas = (7 - $asistencias);
                            $totalEsperado = $totalEsperado + $diario * $asistencias;    
                        }
                    } else {
                        $totalEsperado = $totalEsperado + $diario * $asistencias;
                    }

                    $extras = bcdiv($campo->horas_extras, '1', 2);
                    //total esperado

                    $s_productividad = '';
                    if ($asistencias < 7) {
                        $s_productividad = $productividad / 6 * ($asistencias - 1);
                        $totalEsperado = $totalEsperado +  $s_productividad;
                    } else {
                        $s_productividad = $productividad;
                        $totalEsperado = $totalEsperado +  $s_productividad;
                    }

                    $s_doce = '';
                    if ($asistencias < 7) {
                        $s_doce = $campo->bono_doce / 6  * ($asistencias - 1);
                        $totalEsperado = $totalEsperado +  $s_doce;
                    } else {
                        $s_doce = $campo->bono_doce;
                        $totalEsperado = $totalEsperado +  $s_doce;
                    }

                    
                    $totalEsperado = $totalEsperado + $complemento;
                    $totalEsperado = $totalEsperado + $extras;

                    $vacaciones = 0.00;
                    if (!empty($campo->vacaciones)) {
                        $vacaciones = $campo->vacaciones;
                        $totalEsperado = $totalEsperado + $campo->vacaciones;
                    }

                    if ($campo->daysIncapa != "" && $campo->inicio_incapacida != "" && $campo->fin_incapacida != "" && $campo->NominaAdministrativa == '0') {
                        $dias_incapacidades = $this->DiasIncapacidad($campo->daysIncapa, $campo->fecha, $campo->inicio_incapacida, $campo->fin_incapacida);
                        if (!empty($campo->monto_dia) && $campo->monto_dia != "0.00") {
                            $total_incapacidades = ($dias_incapacidades * $campo->monto_dia);
                        }
                        $totalEsperado = $totalEsperado + $total_incapacidades;
                    }
                    
                    if ($total_incapacidades > 0 && $asistencias <= 1) {
                        $asistencia = $asistencia * 0;
                        $puntualidad = $puntualidad * 0;
                        $s_productividad = $s_productividad * 0;
                        $s_doce = $s_doce * 0;
                        $complemento = $complemento * 0;
                        $total = $dias_incapacidades * $campo->monto_dia;
                        $totalEsperado = $dias_incapacidades * $campo->monto_dia; 
                    } else  {
                        $total = $totalEsperado;
                    }
                

                    $comedor = "0.00";
                    if (!empty($campo->comedor)) {
                        $comedor = $campo->comedor;
                        $totalRetenciones = $totalRetenciones + $comedor;
                    }
                    
                    $ahorro = "0.00";
                    if ($campo->estatusAhorro == 1) {
                        $ahorro = $campo->monto;
                        $totalRetenciones = $totalRetenciones + $ahorro;
                    }

                    $prestamos = bcdiv($campo->prestamos, '1', 2);
                    $totalRetenciones = $totalRetenciones + $prestamos;

                    $fonacot = bcdiv($campo->fonacot, '1', 2);
                    $totalRetenciones = $totalRetenciones + $fonacot;

                    $infonavit = bcdiv($campo->infonavit, '1', 2);
                    $totalRetenciones = $totalRetenciones + $infonavit;

                    $otros = bcdiv($campo->otros_descuentos, '1', 2);
                    $totalRetenciones = $totalRetenciones + $otros;

                    $total_r = bcdiv($totalRetenciones, '1', 2);
                    $total_p = bcdiv($totalEsperado - $totalRetenciones, '1', 2);

                    if (!empty($this->recalcular)) {

                        $select = "SELECT * FROM nomina_final where id_nomina = '{$this->id}' and id_empleado = '{$campo->id_empleado}'";
                        $resSelect = $this->Query($select);
                        if ($total_incapacidades > 0 || $asistencias > 1 || $dias_vacaciones > 0) {
                            if (count($resSelect) > 0) {
                                $sql = "UPDATE `nomina_final`
                                SET
                                `asistencia` = '{$asistencia}', `puntualidad` = '{$puntualidad}',
                                `productividad` = '{$s_productividad}',`doce` = '{$s_doce}',
                                `complemento` = '{$complemento}',`diario` = '{$diario}', `faltas` = '{$faltas}',
                                `asistencias` = '{$asistencias}',`extras` = '{$extras}',`vacaciones` = '{$vacaciones}',
                                `monto_incapacida` = '{$campo->monto_dia}',`dias_incapacida` = '{$dias_incapacidades}',
                                `total` = '{$total}',`comedor` = '{$comedor}',`ahorro` = '{$ahorro}',
                                `prestamos` = '{$prestamos}',`fonacot` = '{$fonacot}',`infonavit` = '{$infonavit}',
                                `otros` = '{$otros}',`total_r` = '{$total_r}',`total_p` = '{$total_p}'
                                WHERE `id_nomina` = '{$this->id}' and id_empleado = '{$campo->id_empleado}'";
                                if ($this->NonQuery($sql)) {
                                    $countRow++;
                                } else {
                                    $this->BeginTransaction("ROLLBACK;");
                                }
                            } else {
                                try {
                                    $sqlInserNomina = "INSERT INTO `nomina_final`
                                    (`id_nomina`,`id_empleado`,`nombre`,`asistencia`,`puntualidad`,`productividad`,`doce`,`complemento`,`diario`,`faltas`,
                                    `asistencias`,`extras`,`vacaciones`,`monto_incapacida`,`dias_incapacida`,`total`,`comedor`,`ahorro`,`prestamos`,`fonacot`,`infonavit`,
                                    `otros`,`total_r`,`total_p`,`fecha`)
                                    VALUES
                                    ('{$this->id}','{$campo->id_empleado}','{$nombre}','{$asistencia}','{$puntualidad}','{$s_productividad}','{$s_doce}',
                                    '{$complemento}','{$diario}','{$faltas}','{$asistencias}','{$extras}','{$vacaciones}','{$campo->monto_dia}','{$dias_incapacidades}','{$total}','{$comedor}','{$ahorro}',
                                    '{$prestamos}','{$fonacot}','{$infonavit}','{$otros}','{$total_r}','{$total_p}','{$campo->fecha}')";
                                    if ($this->NonQuery($sqlInserNomina)) {
                                        $countRow++;
                                    } else {
                                        $this->BeginTransaction("ROLLBACK;");
                                    }
                                } catch (\Exception $e) {
                                    $this->BeginTransaction("ROLLBACK;");
                                }
                            }
                        } else {
                            $countRow++;
                        }
                    } else {
                        if ($total_incapacidades > 0 || $asistencias > 1 || $dias_vacaciones > 0) {
                            try {
                                $sqlInserNomina = "INSERT INTO `nomina_final`
                                (`id_nomina`,`id_empleado`,`nombre`,`asistencia`,`puntualidad`,`productividad`,`doce`,`complemento`,`diario`,`faltas`,
                                `asistencias`,`extras`,`vacaciones`,`monto_incapacida`,`dias_incapacida`,`total`,`comedor`,`ahorro`,`prestamos`,`fonacot`,`infonavit`,
                                `otros`,`total_r`,`total_p`,`fecha`)
                                VALUES
                                ('{$this->id}','{$campo->id_empleado}','{$nombre}','{$asistencia}','{$puntualidad}','{$s_productividad}','{$s_doce}',
                                '{$complemento}','{$diario}','{$faltas}','{$asistencias}','{$extras}','{$vacaciones}','{$campo->monto_dia}','{$dias_incapacidades}','{$total}','{$comedor}','{$ahorro}',
                                '{$prestamos}','{$fonacot}','{$infonavit}','{$otros}','{$total_r}','{$total_p}','{$campo->fecha}')";
                                if ($this->NonQuery($sqlInserNomina)) {
                                    $countRow++;
                                } else {
                                    $this->BeginTransaction("ROLLBACK;");
                                }
                            } catch (\Exception $e) {
                                $this->BeginTransaction("ROLLBACK;");
                            }
                        } else {
                            $countRow++;
                        }
                    }
                }
            }
            if (count($resNomina) == $countRow) {
                $this->BeginTransaction("COMMIT;");
                $bResultado = true;
            } else {
                $rest = $res[0]->id;
                $this->NonQuery("DELETE FROM `nominas` WHERE id = '{$res[0]->id}'");
                $this->NonQuery("ALTER TABLE `nominas` MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=$rest ");
                $this->BeginTransaction("ROLLBACK;");
                $bResultado = false;
            }
        }
        return $bResultado;
    }
    public function AddNomina()
    {
        $sqlSelect = "select id from nomina_final where id_nomina='{$this->id_nomina}' and id_empleado='{$this->id_empleado}'";
        $resSelect = $this->Query($sqlSelect);

        if (count($resSelect) > 0) {
            $sql = "UPDATE `nomina_final`
        SET
        `asistencia` = '{$this->asistencia}',
        `puntualidad` = '{$this->puntualidad}',
        `productividad` = '{$this->productividad}',
        `doce` = '{$this->doce}',
        `complemento` = '{$this->complemento}',
        `bono_viaje` = '{$this->bono_viaje}' ,
        `diario` = '{$this->diario}',
        `faltas` = '{$this->faltas}',
        `asistencias` = '{$this->asistencias}',
        `extras` = '{$this->extras}',
        `total` = '{$this->total}',
        `comedor` = '{$this->comedor}',
        `ahorro` = '{$this->ahorro}',
        `prestamos` = '{$this->prestamos}',
        `fonacot` = '{$this->fonacot}',
        `infonavit` = '{$this->infonavit}',
        `otros` = '{$this->otros}',
        `total_r` = '{$this->total_r}',
        `total_p` = '{$this->total_p}'

        WHERE `id_nomina` = '{$this->id_nomina}' and id_empleado = '{$this->id_empleado}'";
            $result = $this->NonQuery($sql);
            return $result;
        } else {
            $sqlInsert = "INSERT INTO `nomina_final`
            (`id_nomina`,`id_empleado`,`nombre`,`asistencia`, `puntualidad`, `productividad`,`doce`,
            `complemento`,`diario`,`faltas`,`asistencias`, `extras`,`total`,`comedor`,`ahorro`,`prestamos`,
            `fonacot`,`infonavit`, `otros`,`total_r`,`total_p`,`fecha`)
            VALUES
            (
            '{$this->id_nomina}','{$this->id_empleado}','{$this->nombre}','{$this->asistencia}','{$this->puntualidad}',
            '{$this->productividad}','{$this->doce}','{$this->complemento}','{$this->diario}','{$this->faltas}',
            '{$this->asistencias}','{$this->extras}','{$this->total}','{$this->comedor}','{$this->ahorro}',
            '{$this->prestamos}','{$this->fonacot}','{$this->infonavit}','{$this->otros}','{$this->total_r}',
            '{$this->total_p}','{$this->fecha}')";
            $bResultado = $this->NonQuery($sqlInsert);
            return $bResultado;
        }
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
