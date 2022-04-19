<?php
/*
 * Copyright 2021 - Felipe angel cerda contreras 
 * felipeangelcerdacontreras@gmail.com
 */
$_SITE_PATH = $_SERVER["DOCUMENT_ROOT"] . "/" . explode("/", $_SERVER["PHP_SELF"])[1] . "/";
require_once($_SITE_PATH . "/app/model/principal.class.php");

class empleados extends AW
{

    var $id;
    var $nombres;
    var $ape_paterno;
    var $ape_materno;
    var $fecha_nacimiento;
    var $estatus;
    var $fecha_ingreso;
    var $id_puesto;
    var $id_jefe;
    var $usuario_edicion;
    var $fecha_modificacion;
    var $usuario_creacion;
    var $salario_diario;
    var $salario_asistencia;
    var $salario_puntualidad;
    var $salario_productividad;
    var $salario_semanal;
    var $complemento_sueldo;
    var $bono_doce;
    var $nivel_estudios;
    var $direccion;
    var $estado_civil;
    var $rfc;
    var $curp;
    var $nss;
    var $checador;
    var $id_horario;
    var $foto;
    var $ext;
    var $empleado_cuenta;
    var $empleado_clabe;
    var $empleado_tarjeta;
    var $extras;

    var $user_id;
    var $token;
    var $nombre_dedo;

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
        $sqlPueso = "";
        if (!empty($this->id_puesto)) {
            $sqlPueso = "Where id_puesto = '{$this->id_puesto}'";
        }
        $sqlEstatus = "";
        if (!empty($this->estatus)) {
            $sqlEstatus = "Where empleados.estatus = '{$this->estatus}'";
        }

        $sql = "SELECT
        empleados.id,
        empleados.nombres,
        empleados.ape_paterno,
        empleados.ape_materno,
        empleados.fecha_ingreso,
        puestos.nombre as puesto,
        departamentos.nombre as departamento,
    CASE
            
            WHEN empleados.estatus = 1 THEN
            'ACTIVO' 
            WHEN empleados.estatus = 0 THEN
            'BAJA' ELSE 'OTRO' 
        END AS estatus 
    FROM
        empleados 
        join puestos on empleados.id_puesto=puestos.id
        join departamentos on puestos.id_departamento=departamentos.id
        {$sqlPueso} {$sqlEstatus}
    ORDER BY
        empleados.nombres ASC";
        return $this->Query($sql);
    }

    public function Informacion()
    {

        $sql = "select * from empleados where  id='{$this->id}'";
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

    public function jefes()
    {
        $sql = "SELECT a.id, concat(ifnull( a.nombres, ''), ' ',ifnull(a.ape_paterno, ''), ' ', ifnull(ape_materno, '')) as empleado FROM empleados as a 
        join puestos as b on b.id = id_puesto
        where b.nombre like '%JEFE%' or b.nombre like '%Geren%'";

        return $this->Query($sql);
    }

    public function huellas()
    {
        $sql = "SELECT * FROM huellas WHERE id_empleado = {$this->id}";
        $result = $this->Query($sql);
        if ($result) {
            return $result;
        } else {
        }
    }

    public function Existe()
    {
        $sql = "select id from empleados where id='{$this->id}'";
        $res = $this->Query($sql);

        $bExiste = false;

        if (count($res) > 0) {
            $bExiste = true;
        }
        return $bExiste;
    }

    public function Desactivar() {

        $sql = "UPDATE `empleados`
        SET
        `estatus` = '{$this->estatus}'
        WHERE `id` = '{$this->id}';
        ";
                 // echo nl2br($sql);
        return $this->NonQuery($sql);
    }

    public function Actualizar()
    {
        $sqlSalario = "";
        if (!empty($this->salario_diario)) {
            $sqlSalario = " salario_diario='{$this->salario_diario}',
            salario_semanal = '" . $this->salario_diario * 7 . "',";
        }

        $sql = "update
                    empleados
                set
                nombres ='{$this->nombres}',
                ape_paterno = '{$this->ape_paterno}',
                ape_materno = '{$this->ape_materno}',
                fecha_nacimiento =  '{$this->fecha_nacimiento}',
                fecha_ingreso = '{$this->fecha_ingreso}',
                direccion   = '{$this->direccion}',
                estado_civil = '{$this->estado_civil}',
                rfc = '{$this->rfc}',
                curp = '{$this->curp}',
                nss = '{$this->nss}',
                nivel_estudios = '{$this->nivel_estudios}',
                id_puesto = '{$this->id_puesto}',
                id_jefe = '{$this->id_jefe}',
                {$sqlSalario}
                salario_asistencia = '{$this->salario_asistencia}',
                salario_puntualidad = '{$this->salario_puntualidad}',
                salario_productividad = '{$this->salario_productividad}',
                complemento_sueldo = '{$this->complemento_sueldo}',
                bono_doce = '{$this->bono_doce}',
                checador = '{$this->checador}',
                id_horario = '{$this->id_horario}',
                empleado_cuenta = '{$this->empleado_cuenta}',
                empleado_clabe = '{$this->empleado_clabe}',
                empleado_tarjeta = '{$this->empleado_tarjeta}',
                extras = '{$this->extras}',
                usuario_edicion = '{$this->user_id}'
                where
                  id='{$this->id}'";
        $result = $this->NonQuery($sql);

        if (!empty($this->nombre_dedo)) {

            $existeHuella = "SELECT * FROM huellas WHERE id_empleado = {$this->id} AND nombre_dedo = '{$this->nombre_dedo}'";
            $res = $this->Query($existeHuella);

            if (count($res) > 0) {
                $updateHuella = "UPDATE `huellas`
            SET
            `huella` = (select huella from huellas_temp where pc_serial = '{$this->token}'),
            `imgHuella` =(select imgHuella from huellas_temp where pc_serial = '{$this->token}')
            WHERE `id` = '{$res[0]->id}'";

                $this->NonQuery($updateHuella);

                $delete = "delete from huellas_temp where pc_serial = '{$this->token}'";
                $this->NonQuery($delete);
            } else {
                $insertHuella = "insert into huellas (id_empleado, nombre_dedo, huella, imgHuella) "
                    . "values ('{$this->id}', '{$this->nombre_dedo}',"
                    . " (select huella from huellas_temp where pc_serial = '{$this->token}'), "
                    . "(select imgHuella from huellas_temp where pc_serial = '{$this->token}'))";
                $this->NonQuery($insertHuella);

                $delete = "delete from huellas_temp where pc_serial = '{$this->token}'";
                $this->NonQuery($delete);
            }
        }

        return $result;
    }

    public function Agregar()
    {

        $sql = "insert into empleados
                (id,nombres, ape_paterno,ape_materno, fecha_nacimiento, direccion, estado_civil,
                 rfc, curp, nss, nivel_estudios, id_puesto, id_jefe, salario_diario,salario_asistencia,salario_puntualidad,
                 salario_productividad, salario_semanal,complemento_sueldo, bono_doce, fecha_ingreso, checador,id_horario,
                 empleado_cuenta,empleado_clabe,empleado_tarjeta,extras,usuario_creacion, estatus)
                values
                ('0','".ucwords(strtolower($this->nombres))."', '".ucwords(strtolower($this->ape_paterno))."', '".ucwords(strtolower($this->ape_materno))."','" . $this->fecha_nacimiento . "', '".ucwords(strtolower($this->direccion))."', '{$this->estado_civil}',
                 '{$this->rfc}', '".strtoupper($this->curp)."', '".strtoupper($this->nss)."', '{$this->nivel_estudios}', '{$this->id_puesto}', '{$this->id_jefe}','{$this->salario_diario}',
                 '{$this->salario_asistencia}','{$this->salario_puntualidad}','{$this->salario_productividad}','{$this->complemento_sueldo}','{$this->bono_doce}','" . $this->salario_diario * 7 . "', '{$this->fecha_ingreso}',
                 '{$this->checador}','{$this->id_horario}',
                 '{$this->empleado_cuenta}','{$this->empleado_clabe}','{$this->empleado_tarjeta}','{$this->extras}','{$this->user_id}','1')";
        $bResultado = $this->NonQuery($sql);

        $sql1 = "select id from empleados order by id desc limit 1";
        $res = $this->Query($sql1);

        $this->id = $res[0]->id;

        if (!empty($this->nombre_dedo)) {
            $existeHuella = "SELECT * FROM huellas WHERE id_empleado = {$this->id} AND nombre_dedo = '{$this->nombre_dedo}'";
            $res1 = $this->Query($existeHuella);

            if (count($res1) > 0) {
                $updateHuella = "UPDATE `huellas`
            SET
            `huella` = (select huella from huellas_temp where pc_serial = '{$this->token}'),
            `imgHuella` =(select imgHuella from huellas_temp where pc_serial = '{$this->token}')
            WHERE `id` = '{$res[0]->id}'";

                $this->NonQuery($updateHuella);

                $delete = "delete from huellas_temp where pc_serial = '{$this->token}'";
                $this->NonQuery($delete);
            } else {
                $insertHuella = "insert into huellas (id_empleado, nombre_dedo, huella, imgHuella) "
                    . "values ('{$this->id}', '{$this->nombre_dedo}',"
                    . " (select huella from huellas_temp where pc_serial = '{$this->token}'), "
                    . "(select imgHuella from huellas_temp where pc_serial = '{$this->token}'))";
                $this->NonQuery($insertHuella);

                $delete = "delete from huellas_temp where pc_serial = '{$this->token}'";
                $this->NonQuery($delete);
            }
        }
        return $bResultado;
    }

    public function ActivarSensor()
    {

        $delete = "delete from huellas_temp where pc_serial = '{$this->token}'";
        $this->NonQuery($delete);

        $insert = "insert into huellas_temp (pc_serial, texto, statusPlantilla, opc) "
            . "values ('" . $_POST['token'] . "', 'El sensor de huella dactilar esta activado', 'Muestras Restantes: 4', 'capturar')";
        $row = $this->NonQuery($insert);

        return $row;
    }

    public function CargaPush()
    {
        $ext = "jpg";
        if (isset($this->id) && !empty($this->id)) {
            $rs = "SELECT foto, ext from empleados WHERE documento = '{$this->id}'";
            $rs2 = $this->Query($rs);

            if (count($rs2) > 0) {
                $foto = $rs2[0]->foto;
                if ($rs2[0]['ext'] != '') {
                    $ext = $rs2[0]['ext'];
                }
                header("Content-type: image/" . $ext);
                if ($foto != "") {
                    echo $foto;
                }
            } else {
                $img = "app/views/default/img/default.gif";
                $dat = file_get_contents($img);
                echo $dat;
            }
        } else {
            header("Content-type: image/" . $ext);
            $img = "../imagenes/default.png";
            $dat = file_get_contents($img);
            echo $dat;
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
