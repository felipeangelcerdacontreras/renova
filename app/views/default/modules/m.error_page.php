<?php

$_SITE_PATH = $_SERVER["DOCUMENT_ROOT"] . "/" . explode("/", $_SERVER["PHP_SELF"])[1] . "/";
$_SITE_HTTP = "http://" . $_SERVER["SERVER_NAME"] . "/" . explode("/", $_SERVER["PHP_SELF"])[1] . "/";

?>
<?php require_once('app/views/default/script_h.html'); ?>
<!DOCTYPE html PUBLIC>
<html>
<title>Error</title>
<?php require_once('app/views/default/link.html'); ?>
<?php require_once('app/views/default/head.html'); ?>
<!-- aqui empieza plantilla-->

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
                    <div class="container">
                        <center>
                            <div style="text-align: center; font-family: Impact"><a></a>
                                <h1 style="text-align: center;">El comando o la página solicitada no se encuentra en el
                                    sistema.</h1>
                                <img src="<?=$_SITE_HTTP?>app/views/default/img/homer2.png" height="50%" width="40%" />
                                <h1 style="text-align: center;">Si piensa que esto es un error, comuniquese con el
                                    encargado de desarrollo.</h1>
                            </div>
                        </center>
                    </div>
                    <!--cierra pag-->
                </div>
                <!-- /.container-fluid -->

            </div>
            <!-- End of Main Content -->

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