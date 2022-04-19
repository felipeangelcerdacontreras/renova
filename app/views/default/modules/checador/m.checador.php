<?php
$_SITE_PATH = $_SERVER["DOCUMENT_ROOT"] . "/" . explode("/", $_SERVER["PHP_SELF"])[1] . "/";

?>
<!DOCTYPE html>
<html lang="en">
<?php require_once('app/views/default/link.html'); ?>

<head>
    <title>Renova</title>
    <meta http-equiv="refresh" content="600">

    <?php require_once('app/views/default/head.html'); ?>
    <?php require_once('app/views/default/script_h.html'); ?>
    <script>

       /* var Geolocalizacion = navigator.geolocation || (window.google && google.gears && google.gears.factory.create('beta.geolocation'));
        if (Geolocalizacion) Geolocalizacion.getCurrentPosition(MuestraLocalizacion, Excepciones);

        function MuestraLocalizacion(posicion) {
            console.log(posicion.coords.latitude);
            $("#lat").text(posicion.coords.latitude);
            console.log(posicion.coords.longitude);
            $("#lon").text(posicion.coords.longitude);
            console.log(posicion.coords.accuracy);
        }

        function Excepciones(error) {
            switch (error.code) {
                case error.PERMISSION_DENIED:
                    alert('Activa permisos de geolocalizacion');
                    break;
                case error.POSITION_UNAVAILABLE:
                    alert('Activa localizacion por GPS o Redes .');
                    break;
                default:
                    alert('ERROR: ' + error.code);
            }
        }*/
    </script>
    <script type="text/javascript">
        /*if (navigator.geolocation) {
            var lat1 = "";
            var lon1 = "";
            var unit = "K";

            if (' echo $_GET["token"]; ?>' == "vLvEvk1634059456218") {
                console.log("checador renova");
                var lon1 = "25.597914";
                var lat1 = "-103.3842344";

                //var lat1 = "-103.3841886";,
                //var lon1 = "25.5978008";
            } else if ('< echo $_GET["token"]; ?>' == "Aasda451a55sw2as2") {
                var lon1 = "25.5979906";
                var lat1 = "-103.3843008";
            }

            var success = function(position) {
                distance(lat1, lon1, position.coords.longitude, position.coords.latitude, unit);
                console.log((lat1 + ", " + lon1 + ", " + position.coords.longitude + ", " + position.coords.latitude + ", " + unit));
            }
            navigator.geolocation.getCurrentPosition(success, function(msg) {
                console.error(msg);
            });

            function distance(lat1, lon1, lat2, lon2, unit) {
                if ((lat1 == lat2) && (lon1 == lon2)) {
                    console.log("ubicacion exac")
                    return 0;
                } else {
                    var radlat1 = Math.PI * lat1 / 180;
                    var radlat2 = Math.PI * lat2 / 180;
                    var theta = lon1 - lon2;
                    var radtheta = Math.PI * theta / 180;
                    var dist = Math.sin(radlat1) * Math.sin(radlat2) + Math.cos(radlat1) * Math.cos(radlat2) * Math.cos(radtheta);
                    if (dist > 1) {
                        dist = 1;
                    }
                    dist = Math.acos(dist);
                    dist = dist * 180 / Math.PI;
                    dist = dist * 60 * 1.1515;
                    if (unit == "K") {
                        dist = dist * 1.609344
                    }
                    if (unit == "N") {
                        dist = dist * 0.8684
                    }
                    $("#distancia").text(" distancia = " + dist);
                    console.log(dist + " distancia");

                    var str = dist.toString();
                    var antesDecimal = str.split(".")[0];
                    var despuesDecimal = str.split(".")[1];
                    console.log(antesDecimal + " " + despuesDecimal + " dista");
                    despues = despuesDecimal[0];
                    if (antesDecimal <= '2' && despues <= '6') {
                        $("#inputChecador").show();
                        console.log('estas dentro');
                    } else {
                        $("#inputChecador").hide();
                        Alert("Error", "Para checar debes estar dentro de la ubicacion", "warning", 1400, false);
                    }
                }
            }
        } else {
            Alert("Error", "noooo", "warning", 1100, false);
        }*/
        $(document).ready(function(e) {
            $("#token").val('<?php echo $_GET["token"]; ?>');
            $("#id").val('');
            $("#usr").val('');
            Listado();
            opcionChecador()
            //$("#usr").focus();
            $("#usr").change(function() {
                var value = $("#usr").val();
                $("#id").val(value);
            });
            $("#usr").keyup(function(event) {
                var tecla = event.keyCode;
                if (tecla == 13) {
                    Guardar();
                    //$("#frmFormulario").submit();
                }
            });
        });

        function Guardar() {
            $('#frmFormulario').submit(function(e) {
                e.preventDefault();
                $.ajax({
                    type: "POST",
                    url: $(this).attr('action'),
                    data: "usr=" + $("#id").val(),
                    success: function(response) {
                        var str = response;
                        var datos0 = str.split("@")[0];
                        var datos1 = str.split("@")[1];
                        var datos2 = str.split("@")[2];
                        if ((datos3 = str.split("@")[3]) === undefined) {
                            datos3 = "";
                        } else {
                            datos3 = str.split("@")[3];
                        }
                        Alert(datos0, datos1 + "" + datos3, datos2, 1100, false);
                        Listado();
                        $("#usr").val("");
                        $("#usr").focus();
                    }
                });
            });
        }

        function Listado() {
            $("#id").val('');
            $("#usr").val('');
            var jsonDatos = {};
            $.ajax({
                data: jsonDatos,
                type: "POST",
                url: "app/views/default/modules/checador/checador.listado.php",
                beforeSend: function() {
                    $("#divListado").html(
                        '<div class="container"><center><img src="app/views/default/img/loading.gif" border="0"/><br />Leyendo información de la Base de Datos, espere un momento por favor...</center></div>'
                    );
                },
                success: function(datos) {
                    $("#divListado").html(datos);
                }
            });
            //$("#id").focus();
        }

        function cargar_push() {
            $.ajax({
                async: true,
                type: "POST",
                url: "app/sensor/httpush.php",
                data: "&&timestamp=" + timestamp + "&token=" + $("#token").val(),
                dataType: "json",
                success: function(data) {
                    $("#usr").val('');
                    //$("#id").val('');
                    var json = "";
                    json = JSON.parse(JSON.stringify(data));
                    timestamp = json["timestamp"];
                    imageHuella = json["imgHuella"];
                    tipo = json["tipo"];
                    id = json["id"];
                    $("#" + id + "_status").text(json["statusPlantilla"]);
                    $("#" + id + "_texto").text(json["texto"]);
                    if (imageHuella !== null) {
                        $("#" + id).attr("src", "data:image/png;base64," + imageHuella);
                        if (tipo === "leer") {
                            if (json["statusPlantilla"] == "El usuario no existe") {
                                Alert("", json["statusPlantilla"], "warning", 900, false);
                            } else if (json["statusPlantilla"] == "Usuario Verificado"){
                                $("#usr").val(json["documento"]);
                                $.ajax({
                                    type: "POST",
                                    url: "app/views/default/modules/checador/m.checador_procesa.php",
                                    data: "accion=CHECAR&usr=" + $("#usr").val() + "&fecha_inicial=" + $("#fecha_").val() +
                                        "&fecha_final=" + $("#fecha_").val() + "&hora=" + $("#hora").val() + "&diaActual=" + $("#diaActual").val(),
                                    success: function(response) {
                                        var str = response;
                                        var datos0 = str.split("@")[0];
                                        var datos1 = str.split("@")[1];
                                        var datos2 = str.split("@")[2];
                                        if ((datos3 = str.split("@")[3]) === undefined) {
                                            datos3 = "";
                                        } else {
                                            datos3 = str.split("@")[3];
                                        }
                                        Alert(datos0, datos1 + "" + datos3, datos2, 1100, false);
                                        Listado();
                                    }
                                });
                            }
                        }
                    }
                    setTimeout("cargar_push()", 1000);
                }
            });
        }

        function opcionChecador() {
            //disparar el evento del checador_procesa
            $.ajax({
                data: "token=<?php echo $_GET["token"]; ?>",
                type: "POST",
                url: "app/sensor/ActivarSensorReader.php",
                beforeSend: function() {},
                success: function(datos) {}
            });
            cargar_push();
        }

        function mueveReloj() {
            var today = new Date();
            var hr = today.getHours();
            var min = today.getMinutes();
            var sec = today.getSeconds();
            $("#hora").val(checkTime(hr) + ":" + checkTime(min) + ":" + checkTime(sec));
            ap = (hr < 12) ? "AM" : "PM";
            hr = (hr == 0) ? 12 : hr;
            hr = (hr > 12) ? hr - 12 : hr;
            //Add a zero in front of numbers<10
            hr = checkTime(hr);
            min = checkTime(min);
            sec = checkTime(sec);

            var months = ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];
            var days = ['Dom', 'Lun', 'Mar', 'Mie', 'Jue', 'Vie', 'Sab'];
            var curWeekDay = days[today.getDay()];
            var curDay = today.getDate();
            var curMonth = months[today.getMonth()];
            var curYear = today.getFullYear();
            var date = curWeekDay + ", " + curDay + " " + curMonth + " " + curYear;
            $("#diaActual").val(today.getDay());

            $("#horalocal").text(date + " " + hr + ":" + min + ":" + sec + " " + ap);

            setTimeout("mueveReloj()", 1000)
        }

        function checkTime(i) {
            if (i < 10) {
                i = "0" + i;
            }
            return i;
        }
    </script>

<body class="bg-gradient-danger" onload="mueveReloj()">

    <div class="container">
        <!-- Outer Row -->
        <div class="row justify-content-center">
            <div class="col-xl-10 col-lg-5 col-md-9">
                <label class="control-label" id="lat"></label>
                <label class="control-label" id="lon"></label>
                <label class="control-label" id="distancia"></label>
                <div class="card o-hidden border-0 shadow-lg my-5">
                    <div class="card-body p-0">
                        <!-- Nested Row within Card Body -->
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="p-5">
                                    <form class="user" id="frmFormulario" name="frmFormulario" action="app\views\default\modules\checador\m.checador_procesa.php" enctype="multipart/form-data" method="post" target="_self" class="">
                                        <div class="form-group" id="inputChecador">
                                            <h1 id="horalocal" class="text-center"></h1>
                                            <input type="text" class="form-control form-control-user" aria-describedby="emailHelp" autocomplete="off" id="usr" placeholder="" required="required">
                                        </div>
                                        <input type="hidden" name="accion" value="CHECAR">
                                        <input type="hidden" name="usr" id="id" value="">
                                        <input type="hidden" name="fecha_inicial" id="fecha_" value="<?= date('Y-m-d') ?>">
                                        <input type="hidden" name="fecha_final" value="<?= date('Y-m-d') ?>">
                                        <input type="hidden" name="hora" id="hora" value="">
                                        <input type="hidden" name="diaActual" id="diaActual" value="">
                                        <input type="hidden" name="" id="token" value="">
                                    </form>
                                </div>
                                <div id="divListado"></div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="navbar-default navbar-fixed-bottom">
            <div class="text-center footer" style="color:#000;">Copyright © <script>
                    document.write(new Date().getFullYear());
                </script> Angel Contreras. All Right Reserved.</div>
        </div>
    </div>
    <?php require_once('app/views/default/script_f.html'); ?>
</body>

</html>