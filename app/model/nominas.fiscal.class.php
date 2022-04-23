<?php
/*
 * Copyright 2021 - Felipe angel cerda contreras 
 * felipeangelcerdacontreras@gmail.com
 */
$_SITE_PATH = $_SERVER["DOCUMENT_ROOT"] . "/" . explode("/", $_SERVER["PHP_SELF"])[1] . "/";
require_once($_SITE_PATH . "/app/model/principal.class.php");

class nominas_fiscal extends AW
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
        $sql = "SELECT nominas_fiscal.fecha, nominas_fiscal.id,CONCAT(sum( nomina_detalle_fiscal.total )) AS total_nomina,CASE WHEN nominas_fiscal.estatus = 0 THEN 'NO PAGADA' WHEN nominas_fiscal.estatus = 1 THEN
        'PAGADA' ELSE 'OTRO'END AS estatus, WEEK ( nominas_fiscal.fecha ) AS semana FROM nominas_fiscal LEFT JOIN nomina_detalle_fiscal ON nominas_fiscal.id = nomina_detalle_fiscal.id_nomina 
        where fecha between '{$this->fecha_inicial}' and '{$this->fecha_final}' GROUP BY nominas_fiscal.fecha, nominas_fiscal.id 
        ORDER BY fecha ASC  ";

        return $this->Query($sql);
    }

    public function Listado_peticiones()
    {
        $sql = "SELECT a.*, concat(b.nombre_usuario) as solicitante FROM nomina_final_edit_fiscal as a
            left join usuarios as b on a.id_usuario_p = b.id where a.estatus_final_edit = '1'";

        return $this->Query($sql);
    }

    public function AprovarDenegar()
    {
        $sql = "UPDATE `nomina_final_edit_fiscal`
        SET 
        `estatus_final_edit` = '{$this->estatus}'
        WHERE `id` = '{$this->id}'";
        return $this->NonQuery($sql);
    }

    public function Listado_nomina()
    {
        $tabla = "nomina_final_edit_fiscal";
        if (!empty($this->tabla)) {
            $tabla = "nomina_final_fiscal";
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

        $sql = "SELECT a.*, b.* FROM nominas_fiscal as a 
        inner join nomina_final_fiscal as b on a.id = b.id_nomina where a.id = '{$this->id}' {$sqlEmpleado}";

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

    public function Listado_nomina_final_fiscal()
    {
        $sql = "SELECT a.*, b.* FROM nominas_fiscal as a 
        inner join nomina_final_edit_fiscal as b on a.id = b.id_nomina where b.id_nomina = '{$this->id}'";
        return $this->Query($sql);
    }

    public function Pagar()
    {
        $oNominas = new nominas_fiscal();
        $oNominas->id = $this->id;
        $lstnominas = $oNominas->Listado_prenomina();

        if (count($lstnominas) > 0) {
            foreach ($lstnominas as $idx => $prenomina) {
                $oNominas_edit = new nominas_fiscal();
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

                $sql1 = "INSERT INTO `nomina_detalle_fiscal` (`id_nomina`,`id_empleado`,`percepciones`,`retenciones`, `total`)
                    VALUES
                ('{$id_nomina}','{$id_empleado}','{$percepciones}','{$retenciones}','" . ($percepciones + $retenciones) . "')";
                $this->NonQuery($sql1);
            }
        }

        $sql2 = "update nominas_fiscal set estatus = '1', fecha_pago = now() where id='{$this->id}'";
        return $this->NonQuery($sql2);
    }

    public function Informacion()
    {

        $sql = "select * from nominas_fiscal where  id='{$this->id}'";
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

        $sql = "select * from nomina_final_edit_fiscal where  id_nomina = '{$this->id_nomina}' and id_empleado = '{$this->id_empleado}' order by id desc limit 1";
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
        $sql = "select id from nominas_fiscal where id='{$this->id}'";
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
                    nominas_fiscal
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
                        $result = parent::Query($sql);
                        $total_dias = $total_dias + $result[0]->dia;
                        break;
                    } else {
                        $sql = "SELECT if( DAYOFWEEK(DATE_FORMAT(DATE_ADD('{$inicio_vacaci}', INTERVAL $i DAY), '%Y-%m-%d')) < 0, 0, 1) as dia";
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
            c.salario_diario_fiscal,
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
            
            
            ((SELECT COUNT(dia) + 1 FROM asistencia WHERE id_empleado = c.id AND estatus_entrada = 1 AND
            fecha BETWEEN DATE_ADD(a.fecha, INTERVAL - 6 DAY) AND a.fecha) * c.salario_diario_fiscal) AS esperado,

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
            FROM nominas_fiscal a 
            LEFT JOIN empleados c ON c.id
            LEFT JOIN horas_extras AS d ON c.id = d.id_empleado
            LEFT JOIN (SELECT dia, id_empleado, fecha FROM asistencia) e ON c.id = e.id_empleado
            LEFT JOIN (SELECT * FROM puestos) h ON c.id_puesto = h.id
            LEFT JOIN (SELECT * FROM vacaciones) k ON c.id = k.id_empleado
            LEFT JOIN (SELECT * FROM departamentos) i ON h.id_departamento = i.id
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
            c.salario_diario_fiscal,
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

            
            ((SELECT COUNT(dia) + 1 FROM asistencia WHERE id_empleado = c.id AND estatus_entrada = 1 AND
            fecha BETWEEN DATE_ADD(a.fecha, INTERVAL - 6 DAY) AND a.fecha) * c.salario_diario_fiscal) AS esperado,

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
            FROM nominas_fiscal a 
            LEFT JOIN empleados c ON c.id
            LEFT JOIN horas_extras AS d ON c.id = d.id_empleado
            LEFT JOIN (SELECT dia, id_empleado, fecha FROM asistencia) e ON c.id = e.id_empleado
            LEFT JOIN (SELECT * FROM puestos) h ON c.id_puesto = h.id
            LEFT JOIN (SELECT * FROM vacaciones) k ON c.id = k.id_empleado
            LEFT JOIN (SELECT * FROM departamentos) i ON h.id_departamento = i.id
            WHERE a.id ='{$this->id}' and c.id = '35' || c.id = '7' || c.id = '26' || c.id = '90' || c.id = '85' 
            and c.estatus = '1' group by c.id";
            print $sqlAdminist;
        $resAdmin = $this->Query($sqlAdminist);

        foreach ($resAdmin as $idx => $campo) {
            array_push($resNomina, $campo);
        }
        return $resNomina;
    }

    public function Solicitud()
    {
        $sqlNomina = "select * from nomina_final_fiscal where id_nomina = '{$this->id_}' and id_empleado = '{$this->id_empleado_}'";
        $resNomina = $this->Query($sqlNomina);

        $update = "UPDATE `nomina_final_fiscal` SET `estatus_final` = '1' WHERE `id_nomina` = '{$this->id_}' and id_empleado = '{$this->id_empleado_}'";

        if ($this->NonQuery($update)) {
            $sqlEmpleado = "select * from empleados where id = '{$this->id_empleado_}'";
            $resEmpleado = $this->Query($sqlEmpleado);

            if ($resNomina[0]) {

                $totalEsperado = 0;
                $totalRetenciones = 0;
                $total_incapacidades = 0;
                //vareables para insert
                $nombre = $resNomina[0]->nombre;


                if ($this->laborados_ != $resNomina[0]->asistencias) {
                    $faltas = (7 - $this->laborados_);
                    $asistencias = $this->laborados_;
                } else {
                    $faltas = $resNomina[0]->faltas;
                    $asistencias = $resNomina[0]->asistencias;
                }

                $diario = bcdiv($resNomina[0]->diario, '1', 2);

                //total esperado
                if ($asistencias < 1) {
                    $totalEsperado = $totalEsperado + 0;
                } else {
                    $totalEsperado = $totalEsperado + $resEmpleado[0]->salario_diario_fiscal * $asistencias;
                }

                $total = $totalEsperado;
                $total_p = $total;

                $sqlInserNominaEdit = "INSERT INTO `nomina_final_edit_fiscal`
                    (`id_nomina`,`id_empleado`,`nombre`,`diario`,`faltas`,
                    `asistencias`,`total`,`total_p`,`fecha`,`id_usuario_p`,`estatus_final_edit`)
                    VALUES
                    ('{$this->id_}','{$this->id_empleado_}','{$nombre}','{$diario}','{$faltas}','{$asistencias}',
                    '{$total}','{$total_p}','{$resNomina[0]->fecha}','{$_SESSION[$this->NombreSesion]->id}','1')";
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

            $sql = "insert into nominas_fiscal
                    (`id`,`fecha`,`estatus`)
                    values
                    ('0','{$this->fecha}','0')";
            $bResultado = $this->NonQuery($sql);

            $sql1 = "select id from nominas_fiscal order by id desc limit 1";
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

                    if ($campo->daysVaca != "" && $campo->inicio_vacaci != "" && $campo->fin_vacaci != "" && $campo->NominaAdministrativa == '0') {
                        $dias_vacaciones = $this->DiasVacacion($campo->daysVaca, $campo->fecha, $campo->inicio_vacaci, $campo->fin_vacaci);
                    }

                    if ($campo->id_horario == "16" && $campo->festivos <= 1) {
                        $campo->dias_laborados = $campo->dias_laborados + 1;
                    }

                    if ($campo->dias_laborados > 0 || $campo->dias_laborados1 > 0) {
                        $campo->dias_laborados = $campo->dias_laborados + $campo->dias_laborados1;
                        $campo->dias_laborados = $campo->dias_laborados + 1;
                    }


                    if ($dias_vacaciones > 0 && $campo->NominaAdministrativa == "0") {

                        $campo->dias_laborados = $campo->dias_laborados + $dias_vacaciones;
                        if ($campo->dias_laborados > 1 && $campo->dias_laborados <= 6) {
                            $campo->dias_laborados = $campo->dias_laborados + $campo->festivos;
                            if ($campo->dias_laborados < 7) {
                                    $campo->dias_laborados = $campo->dias_laborados + 1;
                            }
                        } else {
                            if ($campo->dias_laborados > 1) {
                                $campo->dias_laborados = $campo->dias_laborados + $campo->festivos;
                            }
                        }
                        if ($campo->dias_laborados > 5 && $campo->dias_laborados < 7) {
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

                    $diario = bcdiv($campo->salario_diario_fiscal, '1', 2);

                    $faltas = (7 - $campo->dias_laborados);

                    $asistencias = $campo->dias_laborados;

                    //total esperado

                    if ($asistencias < 1) {
                        $totalEsperado = $totalEsperado + 0;
                    } else {
                        $totalEsperado = $totalEsperado + $diario * $asistencias;
                    }
                    $total = $totalEsperado;

                    $total_p = bcdiv($totalEsperado, '1', 2);

                    if (!empty($this->recalcular)) {

                        $select = "SELECT * FROM nomina_final_fiscal where id_nomina = '{$this->id}' and id_empleado = '{$campo->id_empleado}'";
                        $resSelect = $this->Query($select);
                        if ($asistencias > 1) {
                            if (count($resSelect) > 0) {
                                $sql = "UPDATE `nomina_final_fiscal`
                                SET
                                `diario` = '{$diario}', `faltas` = '{$faltas}',
                                `asistencias` = '{$asistencias}',
                                `monto_incapacida` = '{$campo->monto_dia}',`dias_incapacida` = '{$dias_incapacidades}',
                                `total` = '{$total}',`total_p` = '{$total_p}'
                                WHERE `id_nomina` = '{$this->id}' and id_empleado = '{$campo->id_empleado}'";
                                if ($this->NonQuery($sql)) {
                                    $countRow++;
                                } else {
                                    $this->BeginTransaction("ROLLBACK;");
                                }
                            } else {
                                try {
                                    $sqlInserNomina = "INSERT INTO `nomina_final_fiscal`
                                    (`id_nomina`,`id_empleado`,`nombre`,`diario`,`faltas`,`asistencias`,`total`,`total_p`,`fecha`)
                                    VALUES
                                    ('{$this->id}','{$campo->id_empleado}','{$nombre}','{$diario}','{$faltas}','{$asistencias}','{$total}','{$total_p}','{$campo->fecha}')";
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
                        if ( $asistencias > 1 ) {
                            try {
                                $sqlInserNomina = "INSERT INTO `nomina_final_fiscal`
                                    (`id_nomina`,`id_empleado`,`nombre`,`diario`,`faltas`,`asistencias`,`total`,`total_p`,`fecha`)
                                    VALUES
                                    ('{$this->id}','{$campo->id_empleado}','{$nombre}','{$diario}','{$faltas}','{$asistencias}','{$total}','{$total_p}','{$campo->fecha}')";
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
                $this->NonQuery("DELETE FROM `nominas_fiscal` WHERE id = '{$res[0]->id}'");
                $this->NonQuery("ALTER TABLE `nominas_fiscal` MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=$rest ");
                $this->BeginTransaction("ROLLBACK;");
                $bResultado = false;
            }
        }
        return $bResultado;
    }
    public function AddNomina()
    {
        $sqlSelect = "select id from nomina_final_fiscal where id_nomina='{$this->id_nomina}' and id_empleado='{$this->id_empleado}'";
        $resSelect = $this->Query($sqlSelect);

        if (count($resSelect) > 0) {
            $sql = "UPDATE `nomina_final_fiscal`
        SET
        `diario` = '{$this->diario}',
        `faltas` = '{$this->faltas}',
        `asistencias` = '{$this->asistencias}',
        `total_p` = '{$this->total_p}'

        WHERE `id_nomina` = '{$this->id_nomina}' and id_empleado = '{$this->id_empleado}'";
            $result = $this->NonQuery($sql);
            return $result;
        } else {
            $sqlInsert = "INSERT INTO `nomina_final_fiscal`
            (`id_nomina`,`id_empleado`,`nombre`,`diario`,`faltas`,`asistencias`, `total`,`total_p`,`fecha`)
            VALUES
            (
            '{$this->id_nomina}','{$this->id_empleado}','{$this->nombre}','{$this->diario}','{$this->faltas}',
            '{$this->asistencias}','{$this->total}',
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
