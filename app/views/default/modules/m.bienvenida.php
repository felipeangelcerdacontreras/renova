<?php
$_SITE_PATH = $_SERVER["DOCUMENT_ROOT"] . "/" . explode("/", $_SERVER["PHP_SELF"])[1] . "/";
require_once($_SITE_PATH . "/Configuracion.class.php");


$oConfig = new Configuracion();
$sesion = $_SESSION[$oConfig->NombreSesion];


$horasaludo = date("H:i");

if ($horasaludo >= "00:00" && $horasaludo < "12:00"){
    $horasaludoF = "Buen día";
}

if ($horasaludo >= "12:00" && $horasaludo < "19:00"){
    $horasaludoF = "Buenas tardes";
}

if ($horasaludo >= "19:00" && $horasaludo < "24:00"){
    $horasaludoF = "Buenas noches";
}
set_time_limit(0);
date_default_timezone_set("America/Mexico_City");
$texto = strftime("%A %d de %B del %Y");

$fechaActual = date('Y-m-d');
$month = date('Y-m');
$aux         = date('Y-m-d', strtotime("{$month} + 1 month"));
$last_day = date('Y-m-d', strtotime("{$aux} - 1 day"));

?>
<?php require_once('app/views/default/script_h.html'); ?>
<!DOCTYPE html PUBLIC>
<html>
<title>Bienvenid@</title>
<?php require_once('app/views/default/link.html'); ?>
<?php require_once('app/views/default/head.html'); ?>
<!-- aqui empieza plantilla-->
<script type="text/javascript">
$(document).ready(function(e) {
    console.log("<?= $fechaActual ?>"+" == "+"<?= $last_day ?>");
    if ("<?= $fechaActual ?>" == "<?= $last_day ?>") {
        $('#myModal-').modal('toggle');
    }
});
</script>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">
        <?php require_once('app/views/default/menu.php'); ?>

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">
            <!-- Main Content -->
            <div id="content">
                <?php require_once('app/views/default/header.php'); ?>
                <!-- Begin Page Content -->
                <div class="container-fluid">
                    <!--contenido pagg-->
                    <!-- Content Row -->
                    <div class="container">
                        <!-- Illustrations -->
                        <div class="card shadow mb-4">
                            <div class="card-header py-3">
                                <h6 class="m-0 font-weight-bold text-primary">
                                    <?php print"<h1>$horasaludoF, $sesion->nombre_usuario</h1>";?></h6>
                            </div>
                            <strong style="text-align: right"><?=$texto;?>&nbsp&nbsp</strong>
                            <div class="card-body">
                                <div class="text-center">
                                    <img class="img-fluid px-3 px-sm-4 mt-3 mb-4" style="width: 22rem;" src="app/views/default/img/icon.png" alt=""><br>
                                    <strong>Somos una empresa familiar con poco mas de 14 años en el mercado del reciclaje. Pretendemos cada día con eficiencia, transparencia, productividad y calidad en el servicio generar en nuestros clientes y proveedores, confianza.</strong>
                                </div>
                            </div>
                        </div>
                        <?php $lstValuadores;?>

                        <!-- Approach -->
                    </div>

                </div>
                <!-- /.container-fluid -->

            </div>
            <!-- End of Main Content -->
            <!-- Logout Modal-->
            <div class="modal fade" id="myModal-" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">

                            <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <!-- contenido del modal-->
                            <h1>Verificar los montos de Infonavit y fonacot</h1>
                        </div>
                        <div class="modal-footer">
                            <button class="btn btn-outline-secondary" type="button" data-dismiss="modal">Ok</button>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Modal -->

            <!-- Footer -->
            <?php require_once('app/views/default/footer.php'); ?>
            <!-- End of Footer -->

        </div>
        <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>
</body>
<!-- aqui termina plantilla-->
<?php require_once('app/views/default/script_f.html'); ?>
</body>

</html>