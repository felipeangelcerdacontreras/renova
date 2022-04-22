<?php
$_SITE_PATH = $_SERVER["DOCUMENT_ROOT"] . "/" . explode("/", $_SERVER["PHP_SELF"])[1] . "/";
// get the HTML
ob_start();
error_reporting(E_ALL & ~E_NOTICE);
ini_set('display_errors', 0);
ini_set('log_errors', 1);
include('m.nominas.items.php');
$content = ob_get_clean();
// convert in PDF
require_once($_SITE_PATH . "app/model/html2pdf/html2pdf.class.php");
try {
    $pdf = new HTML2PDF('P', 'letter', 'es', true, 'UTF-8', 3);
    //$pdf->pdf->SetDisplayMode('fullpage');
    $pdf->writeHTML($content);
    
    $pdf->Output('Nomina' . $oNominas->id . '.pdf');
} catch (HTML2PDF_exception $e) {
    echo $e;
    exit;
}
?>