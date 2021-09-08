<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted Access');
jimport('joomla.application.component.controllerform');

class SasfeControllerGenerarpdfs extends JControllerForm {

    //Crea pdf para los depositos
    function pdfDepositos(){
        jimport('joomla.filesystem.file');
        jimport('joomla.filesystem.folder');
        //ob_start ();
        ob_end_clean();
        include_once JPATH_ADMINISTRATOR.DIRECTORY_SEPARATOR.'components'.DIRECTORY_SEPARATOR.'com_sasfe'.DIRECTORY_SEPARATOR.'common'.DIRECTORY_SEPARATOR.'KoolControls'.DIRECTORY_SEPARATOR.'KoolGrid'.DIRECTORY_SEPARATOR.'library'.DIRECTORY_SEPARATOR.'tcpdf'.DIRECTORY_SEPARATOR.'config'.DIRECTORY_SEPARATOR.'lang'.DIRECTORY_SEPARATOR.'eng.php';
        include_once JPATH_ADMINISTRATOR.DIRECTORY_SEPARATOR.'components'.DIRECTORY_SEPARATOR.'com_sasfe'.DIRECTORY_SEPARATOR.'common'.DIRECTORY_SEPARATOR.'KoolControls'.DIRECTORY_SEPARATOR.'KoolGrid'.DIRECTORY_SEPARATOR.'library'.DIRECTORY_SEPARATOR.'tcpdf'.DIRECTORY_SEPARATOR.'tcpdf.php';

        $getUser = JFactory::getUser();
        //obtener valores del formulario
        $id_Fracc = JRequest::getVar('id_Fracc'); //id fraccionamiento
        $id_Dpt = JRequest::getVar('id_Dpt'); //id del departamento
        $idsDptsPdf = JRequest::getVar('idsDptsPdf'); //ids depositos
        $ref = JRequest::getVar('ref_dg');  //referencia
        $aPaterno = JRequest::getVar('aPaternoC_dg');  //apellido paterno
        $aMaterno = JRequest::getVar('aMaternoC_dg');  //apellido materno
        $nombreC = JRequest::getVar('nombreC_dg');  //nombre de cliente
        $idAsesor = JRequest::getVar('idAsesorPdf');  //nombre de cliente
        $checkBotonesPdf = JRequest::getVar('checkBotonesPdf');  //Si es 0=Apartado, 1=A Cuenta
        $tipoDpt = ($checkBotonesPdf==1) ? 'A CUENTA' : 'APARTADO';
        $tipoTitleReport = ($checkBotonesPdf==1) ? 'Acuenta.pdf' : 'Apartado.pdf';


        $fracc = $this->obtNombreFraccionamientoPorId($id_Fracc);
        $numDpt = $this->obtNumeroDptPorId($id_Dpt);
        $totalDpts = $this->obtTotalDptsPorIdsDpt($idsDptsPdf);
        $fechas = $this->obtTodasFechasDptsPorIdsDpt($idsDptsPdf);
        $totalDptsEnLetra = $this->conventirCantidadEnTexto($totalDpts);
        $nombreAsesor = $this->obtNombreAsesorPorId($idAsesor);

        //$rutaLogo = JURI::root().'media/com_sasfe/images/sistema.png';
        //$logo = '<img src="'.$rutaLogo.'" >';
        $fechaActual = $this->fechaActual();
        $notaCant1 = 500;
        $notaCantL1 = $this->conventirCantidadEnTexto($notaCant1);
        $notaCant2 = 14;
        $notaCantL2 = 'CATORCE';
        $fechasElem = '';

        foreach($fechas as $fecha){
            $fechasElem .= date("d/m/Y", strtotime($fecha)).',';
        }
        $fechasSel = trim($fechasElem, ',');

        /*
         * Cabeceras para imprimir el pdf
         */
$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Esphabit');
$pdf->SetTitle('Depositos');
$pdf->SetSubject('Depositos');
$pdf->SetKeywords('Esphabit, Depositos, PDF');

// remove default header/footer
$pdf->setPrintHeader(false);
$pdf->setPrintFooter(false);

// set default header data
//$pdf->SetHeaderData(PDF_HEADER_LOGO, '20', PDF_HEADER_TITLE, FALSE);

// set header and footer fonts
$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

// set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

// set margins
$pdf->SetMargins(PDF_MARGIN_LEFT, 15, PDF_MARGIN_RIGHT);
$pdf->SetHeaderMargin(0);
$pdf->SetFooterMargin(false);

// set auto page breaks
$pdf->SetAutoPageBreak(TRUE, FALSE);

// set image scale factor
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

// set some language-dependent strings (optional)
if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
    require_once(dirname(__FILE__).'/lang/eng.php');
    $pdf->setLanguageArray($l);
}

$pdf->AddPage();

$img_file = K_PATH_IMAGES.'logo_esphabit.jpg';

$html0 = '
<table cellspacing="0" cellpadding="1" border="0">
    <tr>
        <td width="70"><img src="'.$img_file.'" height="80"  /></td>
        <td width="568"><div style="text-align:center;font-size:60px;"><strong>ESPHABIT, S.A. DE C.V</strong></div><div style="text-align:center;font-size:35px;"> PUEBLA, PUE. C.P. 72130 <br/> R.F.C. ESP91093023A</div> </td>
    </tr>
</table>
';
$pdf->writeHTML($html0, true, false, false, false, '');


// -----------------------------------------------------------------------------
$html1 = '
<table cellspacing="0" cellpadding="1" border="1" style="font-size:90px;">
    <tr>
        <td rowspan="3" style="text-align:center;"> "'.strtoupper($fracc->nombre).' " </td>
    </tr>
</table>
<br/>
';
$pdf->writeHTML($html1, true, false, false, false, '');

// -----------------------------------------------------------------------------
$pdf->SetFont('helvetica', '', 10);
$html2 = '
<table cellspacing="0" cellpadding="2" border="0">
    <tr>
        <td rowspan="6" width="300" style="border:1px solid black;"></td>
        <td width="138" style="font-size:32px;text-align:right;">Puebla, Pue a <br/></td>
        <td width="200" style="font-size:32px;text-align:right;">'.$fechaActual.'<br/></td>
    </tr>
    <tr>
        <td style="text-align:right;">DEPOSITO POR:</td>
        <td><div style="border:1px solid black;font-size:55px;text-align:center;padding:30px;"><strong>$'.number_format($totalDpts,2).'</strong></div></td>
    </tr>
    <tr>
       <td style="font-size:32px;text-align:right;">BANCO:</td>
       <td><div style="border:1px solid black;font-size:40px;text-align:center;"><strong>BBVA BANCOMER</strong></div></td>
    </tr>
    <tr>
       <td style="font-size:32px;text-align:right;">FECHA DE DEP&Oacute;SITO:</td>
       <td><div style="border:1px solid black;">'.$fechasSel.'</div></td>
    </tr>
    <tr>
       <td style="font-size:32px;text-align:right;">REFERENCIA:</td>
       <td><div style="border:1px solid black;font-size:50px;text-align:center;"><strong>'.$ref.'</strong></div></td>
    </tr>
    <tr>
       <td style="font-size:32px;text-align:right;">DEPARTAMENTO:</td>
       <td><div style="border:1px solid black;font-size:60px;text-align:center;"><strong>'.$numDpt.'</strong></div></td>
    </tr>
</table>
';

$pdf->writeHTML($html2, true, false, false, false, '');

// -----------------------------------------------------------------------------
$pdf->SetFont('helvetica', '', 8);
$html3 = '
<br/><br/>
<table cellspacing="0" cellpadding="2" border="1">
  <tr>
    <td style="text-align:center;">RECIBIMOS DEL SR(A).</td>
    <td colspan="2" style="text-align:center;font-size:40px;"><strong>'.$nombreC .' '.$aPaterno.' '.$aMaterno .'</strong></td>
  </tr>
  <tr>
    <td width="92">LA CANTIDAD</td>
    <td width="120"><strong>$'.number_format($totalDpts,2).'</strong></td>
    <td width="426" style="text-align:center;">'.$totalDptsEnLetra.'</td>
  </tr>
  <tr>
    <td colspan="3">POR CONCEPTO A CUENTA DE GASTOS DE ESCRITURACI&Oacute;N Y/O DIFERENCIA DE VIVIENDA</td>
  </tr>
</table>
';

$pdf->writeHTML($html3, true, false, false, false, '');

// -----------------------------------------------------------------------------
//Solo aplica para el fraccionamiento residencial las flores (id=13)
if($id_Fracc==13 && $checkBotonesPdf==0){
    $html4 = '
    <br/><br/><br/>
    <table cellspacing="0" cellpadding="2" border="0">
      <tr>
        <td><strong>SE ANEXA A LA PRESENTE COPIA DE LA FICHA DE DEPOSITO</strong></td>
      </tr>
      <tr>
        <td style="font-size:23px;">NOTA 1 : A PARTIR DE LA FECHA DE APARTADO DE SU VIVIENDA TIENE 15 (QUINCE) D&Iacute;AS NATURALES PARA COMPLETAR SU EXPEDIENTE Y SEA RESPETADA SU UBICACI&Oacute;N.</td>
      </tr>
      <tr>
        <td style="font-size:23px;">NOTA 2 : EN CASO DE CANCELACION UNA VEZ TRANSCURRIDO EL PLAZO SE RETENDRA LA PENA CONVENCIONAL LA CANTIDAD DE $'.number_format(4000,2).' (CUATRO MIL  PESOS) POR CONCEPTO DE GASTOS ADMINISTRATIVOS Y DE GESTI&Oacute;N. </td>
      </tr>
    </table>
    ';
}else{
$html4 = '
<br/><br/><br/>
<table cellspacing="0" cellpadding="2" border="0">
  <tr>
    <td><strong>SE ANEXA A LA PRESENTE COPIA DE LA FICHA DE DEPOSITO</strong></td>
  </tr>
  <tr>
    <td style="font-size:23px;">NOTA 1: EN CASO SE CANCELACI&Oacute;N SE COBRARAN  $'.number_format($notaCant1,2).' ('.$notaCantL1.') POR CONCEPTOS DE GASTOS ADMINISTRATIVOS Y DE GESTI&Oacute;N. </td>
  </tr>
  <tr>
    <td style="font-size:23px;">NOTA 2: A PARTIR DE LA FECHA DE APARTADO DE SU VIVIENDA TIENE '.$notaCant2.' ('.$notaCantL2.') D&Iacute;AS NATURALES PARA COMPLETAR SU EXPEDIENTE Y SEA RESPETADA SU UBICACI&Oacute;N </td>
  </tr>
</table>
';
}

$pdf->writeHTML($html4, true, false, false, false, '');

// -----------------------------------------------------------------------------

$html5 = '
<br/><br/><br/><br/><br/><br/><br/><br/>
<table cellspacing="0" cellpadding="2" border="0">
  <tr>
    <td style="text-align:center;"><strong>__________________________<br/>FIRMA DEL CLIENTE</strong></td>
  </tr>
</table>
';

$pdf->writeHTML($html5, true, false, false, false, '');

// -----------------------------------------------------------------------------

$html6 = '
<br/><br/><br/><br/>
<table cellspacing="0" cellpadding="2" border="0">
  <tr>
    <td style="text-align:center;">__________________________</td>
    <td style="text-align:center;border:1px solid black;">AUTORIZACION Y REGISTRO</td>
  </tr>
  <tr>
    <td style="text-align:center;">'.strtoupper($nombreAsesor).'</td>
    <td style="border:1px solid black;">'.$tipoDpt.'</td>
  </tr>
  <tr>
    <td></td>
    <td style="text-align:center;font-size:35px;border:1px solid black;"><strong> '.strtoupper($fracc->nombre).'</strong></td>
  </tr>

</table>
';

$pdf->writeHTML($html6, true, false, false, false, '');


//Close and output PDF document
$pdf->Output($tipoTitleReport, 'D');
//$pdf->Output('depositos.pdf', 'I');

        ob_flush();

    }


    //Crea pdf para las liquidaciones
    function pdfLiquidacion(){
        jimport('joomla.filesystem.file');
        jimport('joomla.filesystem.folder');
        //ob_start ();
        ob_end_clean();
        include_once JPATH_ADMINISTRATOR.DIRECTORY_SEPARATOR.'components'.DIRECTORY_SEPARATOR.'com_sasfe'.DIRECTORY_SEPARATOR.'common'.DIRECTORY_SEPARATOR.'KoolControls'.DIRECTORY_SEPARATOR.'KoolGrid'.DIRECTORY_SEPARATOR.'library'.DIRECTORY_SEPARATOR.'tcpdf'.DIRECTORY_SEPARATOR.'config'.DIRECTORY_SEPARATOR.'lang'.DIRECTORY_SEPARATOR.'eng.php';
        include_once JPATH_ADMINISTRATOR.DIRECTORY_SEPARATOR.'components'.DIRECTORY_SEPARATOR.'com_sasfe'.DIRECTORY_SEPARATOR.'common'.DIRECTORY_SEPARATOR.'KoolControls'.DIRECTORY_SEPARATOR.'KoolGrid'.DIRECTORY_SEPARATOR.'library'.DIRECTORY_SEPARATOR.'tcpdf'.DIRECTORY_SEPARATOR.'tcpdf.php';

        $getUser = JFactory::getUser();
        //obtener valores del formulario
        $id_Fracc = JRequest::getVar('id_Fracc'); //id fraccionamiento
        $id_Dpt = JRequest::getVar('id_Dpt'); //id del departamento
        $idsDptsPdf = JRequest::getVar('idsDptsPdf'); //ids depositos
        $ref = JRequest::getVar('ref_dg');  //referencia
        $aPaterno = JRequest::getVar('aPaternoC_dg');  //apellido paterno
        $aMaterno = JRequest::getVar('aMaternoC_dg');  //apellido materno
        $nombreC = JRequest::getVar('nombreC_dg');  //nombre de cliente
        $idAsesor = JRequest::getVar('idAsesorPdf');  //id del asesor
        $idDatoGral = JRequest::getVar('id_DatoGral');  //id de dato general
        $diferencia = JRequest::getVar('diferencia_dc');  //es la diferencia en datos credito
        $removerPesos = str_replace("$","",$diferencia);
        $removerComas = str_replace(",","",$removerPesos);
        //$removerPunto = str_replace(".","",$removerComas);
        $diferencia = $removerComas;

        $id_DatoCto = JRequest::getVar('id_DatoCto'); //Id de credito

//        echo $diferencia;

        $fracc = $this->obtNombreFraccionamientoPorId($id_Fracc);
        $numDpt = $this->obtNumeroDptPorId($id_Dpt);
        $totalDpts = $this->obtTotalDptsPorIdsDpt($idsDptsPdf);
        $fechas = $this->obtTodasFechasDptsPorIdsDpt($idsDptsPdf);
        //$totalDptsEnLetra = $this->conventirCantidadEnTexto($totalDpts);
        $nombreAsesor = $this->obtNombreAsesorPorId($idAsesor);
        $datosDepositos = $this->obtDatosDepositosPorIdsDpt($idsDptsPdf);
        $datosCredito = $this->obtDatoCtoPorIdDatoCto($idDatoGral); //Obtener datos del credito por idDatoGral
        $arrUltimoDpto = $this->obtUltimoDptPorIdCredito($id_DatoCto); //Ultimo monto como principal
        $ultimoDpto = $arrUltimoDpto[0]->deposito;
        $ultimaFecha = date("d/m/Y", strtotime($arrUltimoDpto[0]->fecha));

        $escrituracion = $datosCredito[0]->gEscrituracion;
        $ahorroVol = $datosCredito[0]->ahorroVol;
        $totalTrabajdor = $escrituracion+$ahorroVol+$diferencia;
        $saldo = $totalTrabajdor-$totalDpts;

        $totalDptsEnLetra = $this->conventirCantidadEnTexto($ultimoDpto);
//       echo '<pre>';
//            print_r($datosDepositos);
//            print_r($datosCredito);
//       print_r($arrUltimoDpto);
//       echo '</pre>';

        //$rutaLogo = JURI::root().'media/com_sasfe/images/sistema.png';
        //$logo = '<img src="'.$rutaLogo.'" >';
        $fechaActual = $this->fechaActual();
        $notaCant1 = 500;
        $notaCantL1 = $this->conventirCantidadEnTexto($notaCant1);
        $notaCant2 = 14;
        $notaCantL2 = 'CATORCE';
        $fechasElem = '';

        foreach($fechas as $fecha){
            $fechasElem .= date("d/m/Y", strtotime($fecha)).',';
        }
        $fechasSel = trim($fechasElem, ',');

        /*
         * Cabeceras para imprimir el pdf
         */
$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Esphabit');
$pdf->SetTitle('Liquidacion');
$pdf->SetSubject('Liquidacion');
$pdf->SetKeywords('Esphabit, Liquidacion, PDF');

// remove default header/footer
$pdf->setPrintHeader(false);
$pdf->setPrintFooter(false);

// set default header data
//$pdf->SetHeaderData(PDF_HEADER_LOGO, '20', PDF_HEADER_TITLE, FALSE);

// set header and footer fonts
$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

// set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

// set margins
$pdf->SetMargins(PDF_MARGIN_LEFT, 15, PDF_MARGIN_RIGHT);
$pdf->SetHeaderMargin(0);
$pdf->SetFooterMargin(false);

// set auto page breaks
$pdf->SetAutoPageBreak(TRUE, FALSE);

// set image scale factor
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

// set some language-dependent strings (optional)
if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
    require_once(dirname(__FILE__).'/lang/eng.php');
    $pdf->setLanguageArray($l);
}

$pdf->AddPage();

$img_file = K_PATH_IMAGES.'logo_esphabit.jpg';

$html0 = '
<table cellspacing="0" cellpadding="1" border="0">
    <tr>
        <td width="70"><img src="'.$img_file.'" height="80"  /></td>
        <td width="568"><div style="text-align:center;font-size:60px;"><strong>ESPHABIT, S.A. DE C.V</strong></div><div style="text-align:center;font-size:35px;">25 PONIENTE 4501-201 COL. AMPLIACION REFORMA <br/> PUEBLA, PUE. C.P. 72130 <br/> R.F.C. ESP91093023A</div> </td>
    </tr>
</table>
';
$pdf->writeHTML($html0, true, false, false, false, '');


// -----------------------------------------------------------------------------
$html1 = '
<table cellspacing="0" cellpadding="1" border="1" style="font-size:90px;">
    <tr>
        <td rowspan="3" style="text-align:center;"> " '.strtoupper($fracc->nombre).' " </td>
    </tr>
</table>
<br/>
';
$pdf->writeHTML($html1, true, false, false, false, '');

// -----------------------------------------------------------------------------
$pdf->SetFont('helvetica', '', 10);
$html2 = '
<table cellspacing="0" cellpadding="2" border="0">
    <tr>
        <td rowspan="6" width="300" style="border:1px solid black;"></td>
        <td width="138" style="font-size:32px;text-align:right;">Puebla, Pue a <br/></td>
        <td width="200" style="font-size:32px;text-align:right;">'.$fechaActual.'<br/></td>
    </tr>
    <tr>
        <td style="text-align:right;">DEPOSITO POR:</td>
        <td><div style="border:1px solid black;font-size:55px;text-align:center;padding:30px;"><strong>$'.number_format($ultimoDpto,2).'</strong></div></td>
    </tr>
    <tr>
       <td style="font-size:32px;text-align:right;">BANCO:</td>
       <td><div style="border:1px solid black;font-size:40px;text-align:center;"><strong>BBVA BANCOMER</strong></div></td>
    </tr>
    <tr>
       <td style="font-size:32px;text-align:right;">FECHA DE DEP&Oacute;SITO:</td>
       <td><div style="border:1px solid black;">'.$ultimaFecha.'</div></td>
    </tr>
    <tr>
       <td style="font-size:32px;text-align:right;">REFERENCIA:</td>
       <td><div style="border:1px solid black;font-size:50px;text-align:center;"><strong>'.$ref.'</strong></div></td>
    </tr>
    <tr>
       <td style="font-size:32px;text-align:right;">DEPARTAMENTO:</td>
       <td><div style="border:1px solid black;font-size:60px;text-align:center;"><strong>'.$numDpt.'</strong></div></td>
    </tr>
</table>
';

$pdf->writeHTML($html2, true, false, false, false, '');

// -----------------------------------------------------------------------------
$pdf->SetFont('helvetica', '', 8);
$html3 = '
<br/>
<table cellspacing="0" cellpadding="2" border="1">
  <tr>
    <td style="text-align:center;">RECIBIMOS DEL SR(A).</td>
    <td colspan="2" style="text-align:center;font-size:40px;"><strong>'.$nombreC .' '.$aPaterno.' '.$aMaterno .'</strong></td>
  </tr>
  <tr>
    <td width="92">LA CANTIDAD</td>
    <td width="120"><strong>$'.number_format($ultimoDpto,2).'</strong></td>
    <td width="426" style="text-align:center;">'.$totalDptsEnLetra.'</td>
  </tr>
  <tr>
    <td colspan="3">POR CONCEPTO DE LIQUIDACI&Oacute;N DE GASTOS DE ESCRITURACI&Oacute;N Y/O DIFERENCIA DE VIVIENDA</td>
  </tr>
</table>
';

$pdf->writeHTML($html3, true, false, false, false, '');

// -----------------------------------------------------------------------------

$html4 = '
<br/>
<table cellspacing="0" cellpadding="2" border="0">
  <tr>
    <td><strong>SE ANEXA A LA PRESENTE COPIA DE LA FICHA DE DEPOSITO</strong></td>
  </tr>
</table>
';

$pdf->writeHTML($html4, true, false, false, false, '');

// -----------------------------------------------------------------------------

$html5 = '
<br/><br/>
<table cellspacing="0" cellpadding="2" border="0">
  <tr>
    <td style="text-align:center;"><strong>__________________________<br/>FIRMA DEL CLIENTE</strong></td>
  </tr>
</table>
';

$pdf->writeHTML($html5, true, false, false, false, '');

// -----------------------------------------------------------------------------

$html6 = '
<br/>
<table cellspacing="0" cellpadding="2" border="0">
  <tr>
    <td style="text-align:center;">__________________________</td>
    <td style="text-align:center;border:1px solid black;">AUTORIZACI&Oacute;N Y REGISTRO</td>
  </tr>
  <tr>
    <td style="text-align:center;">'.strtoupper($nombreAsesor).'</td>
    <td style="border:1px solid black;">LIQUIDACI&Oacute;N</td>
  </tr>
  <tr>
    <td></td>
    <td style="text-align:center;font-size:35px;border:1px solid black;"><strong> '.strtoupper($fracc->nombre).'</strong></td>
  </tr>

</table>
';

$pdf->writeHTML($html6, true, false, false, false, '');

// -----------------------------------------------------------------------------

$html7 = '
<br/><br/>
<table cellspacing="0" cellpadding="2" border="1">
  <tr>
    <td colspan="3" width="340" style="text-align:center;">PAGOS DEL CLIENTE</td>
    <td colspan="2" width="298" style="text-align:center;">DESGLOSE DE GASTOS</td>
  </tr>
  <tr>
    <td width="60"><div style="font-size:25px;">FECHA</div></td>
    <td width="80"><div style="font-size:25px;">IMPORTE</div></td>
    <td width="200"><div style="font-size:25px;">CONCEPTO</div></td>
    <td width="218"><div style="font-size:23px;">ESCRITURACI&Oacute;N</div></td>
    <td width="80" style="text-align:right;">$'.number_format($escrituracion,2).'</td>
  </tr>
  <tr>
    <td colspan="3" rowspan="3">';
      foreach($datosDepositos as $item){
         $html7 .= '<div>'.$item->fecha.'  &nbsp;&nbsp;&nbsp;  $'.number_format($item->deposito,2).'  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;  '.$item->comentarios.' </div>';
      }
$html7 .= ' </td>
    <td width="218"><div style="font-size:23px;">AHORRO VOLUNTARIO(<label style="font-size:21px;"> PAGADO POR EL CLIENTE </label>)</div></td>
    <td width="80" style="text-align:right;">$'.number_format($ahorroVol,2).'</td>
  </tr>
  <tr>
    <td><div style="font-size:23px;">DIF. VIVIENDA</div></td>
    <td style="text-align:right;">$'.number_format($diferencia,2).'</td>
  </tr>
  <tr>
    <td colspan="2"></td>
  </tr>
  <tr>
    <td colspan="2"><strong>TOTAL DEL DEPOSITO</strong></td>
    <td><div style="text-align:right;">$'.number_format($totalDpts,2).'</div></td>
    <td><div style="font-size:24px;">TOTAL A CUBRIR POR EL TRABAJADOR</div></td>
    <td style="text-align:right;">$'.number_format($totalTrabajdor,2).'</td>
  </tr>
  <tr>
    <td colspan="2">&nbsp;</td>
    <td></td>
    <td><strong>SALDO</strong></td>
    <td style="text-align:right;">$'.number_format($saldo,2).'</td>
  </tr>
</table>
';

$pdf->writeHTML($html7, true, false, false, false, '');


//Close and output PDF document
$pdf->Output('Liquidacion.pdf', 'D');
//$pdf->Output('Liquidacion.pdf', 'I');

        ob_flush();

    }

    //Obtener Numero de departamento indicando su id
    public function obtNumeroDptPorId($idDpt){
        $model = JModelLegacy::getInstance('Globalmodelsbk', 'SasfeModel');
        $numDpt =  $model->obtNumDptPorIdDB($idDpt);

        return $numDpt;
    }

    //Obtener nombre de fraccionamiento indicando su id
    public function obtNombreFraccionamientoPorId($idFracc){
        $model = JModelLegacy::getInstance('Globalmodelsbk', 'SasfeModel');
        $fracc =  $model->obtDatosFraccPorIdDB($idFracc);

        return $fracc;
    }

    //Obtener total de los depositos
    public function obtTotalDptsPorIdsDpt($idsDpts){
        $model = JModelLegacy::getInstance('Globalmodelsbk', 'SasfeModel');
        $totalDpts =  $model->obtTotalDptsPorIdsDptDB($idsDpts);

        return $totalDpts;
    }

    //Obtener todas las fechas de los ids de depositos seleccionados
    public function obtTodasFechasDptsPorIdsDpt($idsDpts){
        $model = JModelLegacy::getInstance('Globalmodelsbk', 'SasfeModel');
        $fechasDpts =  $model->obtTodasFechasDptsPorIdsDptDB($idsDpts);

        return $fechasDpts;
    }

    //Obtener el nombre del asesor por su id
    public function obtNombreAsesorPorId($idAsesor){
        $model = JModelLegacy::getInstance('Globalmodelsbk', 'SasfeModel');
        $nombreAsesor =  $model->obtNombreAsesorPorIdDB($idAsesor);

        return $nombreAsesor;
    }

    //obtener todos los registros seleccionados (para las liquidaciones)
    public function obtDatosDepositosPorIdsDpt($idsDpts){
        $model = JModelLegacy::getInstance('Globalmodelsbk', 'SasfeModel');
        $datosDepositos =  $model->obtDatosDepositosPorIdsDptDB($idsDpts);

        return $datosDepositos;
    }

    //Obtener todos los datos del credito pasando el id de credito
    public function obtDatoCtoPorIdDatoCto($idGral){
        $model = JModelLegacy::getInstance('Globalmodelsbk', 'SasfeModel');
        $datosCto =  $model->obtDatoCtoPorIdDatoCtoDB($idGral);

        return $datosCto;
    }

    //Obtener el ultimo deposito por su id de credito
    public function obtUltimoDptPorIdCredito($idCto){
        $model = JModelLegacy::getInstance('Globalmodelsbk', 'SasfeModel');
        $ultimoDpt =  $model->obtUltimoDptPorIdCreditoDB($idCto);

        return $ultimoDpt;
    }



   //Metodo para convertir numeros en letras
   public function conventirCantidadEnTexto($xcifra)
   {
    $xarray = array(0 => "Cero",
        1 => "UN", "DOS", "TRES", "CUATRO", "CINCO", "SEIS", "SIETE", "OCHO", "NUEVE",
        "DIEZ", "ONCE", "DOCE", "TRECE", "CATORCE", "QUINCE", "DIECISEIS", "DIECISIETE", "DIECIOCHO", "DIECINUEVE",
        "VEINTI", 30 => "TREINTA", 40 => "CUARENTA", 50 => "CINCUENTA", 60 => "SESENTA", 70 => "SETENTA", 80 => "OCHENTA", 90 => "NOVENTA",
        100 => "CIENTO", 200 => "DOSCIENTOS", 300 => "TRESCIENTOS", 400 => "CUATROCIENTOS", 500 => "QUINIENTOS", 600 => "SEISCIENTOS", 700 => "SETECIENTOS", 800 => "OCHOCIENTOS", 900 => "NOVECIENTOS"
    );
//
    $xcifra = trim($xcifra);
    $xlength = strlen($xcifra);
    $xpos_punto = strpos($xcifra, ".");
    $xaux_int = $xcifra;
    $xdecimales = "00";
    if (!($xpos_punto === false)) {
        if ($xpos_punto == 0) {
            $xcifra = "0" . $xcifra;
            $xpos_punto = strpos($xcifra, ".");
        }
        $xaux_int = substr($xcifra, 0, $xpos_punto); // obtengo el entero de la cifra a covertir
        $xdecimales = substr($xcifra . "00", $xpos_punto + 1, 2); // obtengo los valores decimales
    }

    $XAUX = str_pad($xaux_int, 18, " ", STR_PAD_LEFT); // ajusto la longitud de la cifra, para que sea divisible por centenas de miles (grupos de 6)
    $xcadena = "";
    for ($xz = 0; $xz < 3; $xz++) {
        $xaux = substr($XAUX, $xz * 6, 6);
        $xi = 0;
        $xlimite = 6; // inicializo el contador de centenas xi y establezco el l?mite a 6 d?gitos en la parte entera
        $xexit = true; // bandera para controlar el ciclo del While
        while ($xexit) {
            if ($xi == $xlimite) { // si ya lleg? al l?mite m?ximo de enteros
                break; // termina el ciclo
            }

            $x3digitos = ($xlimite - $xi) * -1; // comienzo con los tres primeros digitos de la cifra, comenzando por la izquierda
            $xaux = substr($xaux, $x3digitos, abs($x3digitos)); // obtengo la centena (los tres d?gitos)
            for ($xy = 1; $xy < 4; $xy++) { // ciclo para revisar centenas, decenas y unidades, en ese orden
                switch ($xy) {
                    case 1: // checa las centenas
                        if (substr($xaux, 0, 3) < 100) { // si el grupo de tres d?gitos es menor a una centena ( < 99) no hace nada y pasa a revisar las decenas

                        } else {
                            $key = (int) substr($xaux, 0, 3);
                            if (TRUE === array_key_exists($key, $xarray)){  // busco si la centena es n?mero redondo (100, 200, 300, 400, etc..)
                                $xseek = $xarray[$key];
                                $xsub = $this->subfijo($xaux); // devuelve el subfijo correspondiente (Mill?n, Millones, Mil o nada)
                                if (substr($xaux, 0, 3) == 100)
                                    $xcadena = " " . $xcadena . " CIEN " . $xsub;
                                else
                                    $xcadena = " " . $xcadena . " " . $xseek . " " . $xsub;
                                $xy = 3; // la centena fue redonda, entonces termino el ciclo del for y ya no reviso decenas ni unidades
                            }
                            else { // entra aqu? si la centena no fue numero redondo (101, 253, 120, 980, etc.)
                                $key = (int) substr($xaux, 0, 1) * 100;
                                $xseek = $xarray[$key]; // toma el primer caracter de la centena y lo multiplica por cien y lo busca en el arreglo (para que busque 100,200,300, etc)
                                $xcadena = " " . $xcadena . " " . $xseek;
                            } // ENDIF ($xseek)
                        } // ENDIF (substr($xaux, 0, 3) < 100)
                        break;
                    case 2: // checa las decenas (con la misma l?gica que las centenas)
                        if (substr($xaux, 1, 2) < 10) {

                        } else {
                            $key = (int) substr($xaux, 1, 2);
                            if (TRUE === array_key_exists($key, $xarray)) {
                                $xseek = $xarray[$key];
                                $xsub = $this->subfijo($xaux);
                                if (substr($xaux, 1, 2) == 20)
                                    $xcadena = " " . $xcadena . " VEINTE " . $xsub;
                                else
                                    $xcadena = " " . $xcadena . " " . $xseek . " " . $xsub;
                                $xy = 3;
                            }
                            else {
                                $key = (int) substr($xaux, 1, 1) * 10;
                                $xseek = $xarray[$key];
                                if (20 == substr($xaux, 1, 1) * 10)
                                    $xcadena = " " . $xcadena . " " . $xseek;
                                else
                                    $xcadena = " " . $xcadena . " " . $xseek . " Y ";
                            } // ENDIF ($xseek)
                        } // ENDIF (substr($xaux, 1, 2) < 10)
                        break;
                    case 3: // checa las unidades
                        if (substr($xaux, 2, 1) < 1) { // si la unidad es cero, ya no hace nada

                        } else {
                            $key = (int) substr($xaux, 2, 1);
                            $xseek = $xarray[$key]; // obtengo directamente el valor de la unidad (del uno al nueve)
                            $xsub = $this->subfijo($xaux);
                            $xcadena = " " . $xcadena . " " . $xseek . " " . $xsub;
                        } // ENDIF (substr($xaux, 2, 1) < 1)
                        break;
                } // END SWITCH
            } // END FOR
            $xi = $xi + 3;
        } // ENDDO

        if (substr(trim($xcadena), -5, 5) == "ILLON") // si la cadena obtenida termina en MILLON o BILLON, entonces le agrega al final la conjuncion DE
            $xcadena.= " DE";

        if (substr(trim($xcadena), -7, 7) == "ILLONES") // si la cadena obtenida en MILLONES o BILLONES, entoncea le agrega al final la conjuncion DE
            $xcadena.= " DE";

        // ----------- esta l?nea la puedes cambiar de acuerdo a tus necesidades o a tu pa?s -------
        if (trim($xaux) != "") {
            switch ($xz) {
                case 0:
                    if (trim(substr($XAUX, $xz * 6, 6)) == "1")
                        $xcadena.= "UN BILLON ";
                    else
                        $xcadena.= " BILLONES ";
                    break;
                case 1:
                    if (trim(substr($XAUX, $xz * 6, 6)) == "1")
                        $xcadena.= "UN MILLON ";
                    else
                        $xcadena.= " MILLONES ";
                    break;
                case 2:
                    if ($xcifra < 1) {
                        $xcadena = "CERO PESOS $xdecimales/100 M.N.";
                    }
                    if ($xcifra >= 1 && $xcifra < 2) {
                        $xcadena = "UN PESO $xdecimales/100 M.N. ";
                    }
                    if ($xcifra >= 2) {
                        $xcadena.= " PESOS $xdecimales/100 M.N. "; //
                    }
                    break;
            } // endswitch ($xz)
        } // ENDIF (trim($xaux) != "")
        // ------------------      en este caso, para M?xico se usa esta leyenda     ----------------
        $xcadena = str_replace("VEINTI ", "VEINTI", $xcadena); // quito el espacio para el VEINTI, para que quede: VEINTICUATRO, VEINTIUN, VEINTIDOS, etc
        $xcadena = str_replace("  ", " ", $xcadena); // quito espacios dobles
        $xcadena = str_replace("UN UN", "UN", $xcadena); // quito la duplicidad
        $xcadena = str_replace("  ", " ", $xcadena); // quito espacios dobles
        $xcadena = str_replace("BILLON DE MILLONES", "BILLON DE", $xcadena); // corrigo la leyenda
        $xcadena = str_replace("BILLONES DE MILLONES", "BILLONES DE", $xcadena); // corrigo la leyenda
        $xcadena = str_replace("DE UN", "UN", $xcadena); // corrigo la leyenda
    } // ENDFOR ($xz)
    return trim($xcadena);

   }

   // esta funci?n regresa un subfijo para la cifra
   public function subfijo($xx)
   {
        $xx = trim($xx);
        $xstrlen = strlen($xx);
        if ($xstrlen == 1 || $xstrlen == 2 || $xstrlen == 3)
            $xsub = "";
        //
        if ($xstrlen == 4 || $xstrlen == 5 || $xstrlen == 6)
            $xsub = "MIL";

        return $xsub;
    }

   public function fechaActual(){
       //setlocale(LC_ALL,"es_ES");
       //$fechaActual = strftime("%A %d de %B del %Y");
       $diasSemana = array("1"=>"Lunes", "2"=>"Martes", "3"=>"Mi&eacute;rcoles", "4"=>"Jueves", "5"=>"Viernes", "6"=>"S&aacute;bado", "7"=>"Domingo");
       $meses = array("01"=>"Enero", "02"=>"Febrero", "03"=>"Marzo", "04"=>"Abril", "05"=>"Mayo", "06"=>"Junio",
                      "07"=>"Julio", "08"=>"Agosto", "09"=>"Septiembre", "10"=>"Octubre", "11"=>"Noviembre", "12"=>"Diciembre");

       $diaSemana = $diasSemana[date('w')];
       $numeroDia = date('d');
       $mes = $meses[date('m')];
       $anio = date('Y');

       $fechaActual = $diaSemana .', '. $numeroDia .' de ' .$mes .' de ' .$anio ;

       return $fechaActual;
   }
   //Crea pdf para el reporte de prospectos
    function pdfReporteProspectos($colDatosReporte, $nombreArchivo, $fechaDel, $fechaAl, $difDias){
        jimport('joomla.filesystem.file');
        jimport('joomla.filesystem.folder');
        require_once(JPATH_COMPONENT.'/helpers/sasfehp.php' );
        //ob_start ();
        ob_end_clean();
        include_once JPATH_ADMINISTRATOR.DIRECTORY_SEPARATOR.'components'.DIRECTORY_SEPARATOR.'com_sasfe'.DIRECTORY_SEPARATOR.'common'.DIRECTORY_SEPARATOR.'KoolControls'.DIRECTORY_SEPARATOR.'KoolGrid'.DIRECTORY_SEPARATOR.'library'.DIRECTORY_SEPARATOR.'tcpdf'.DIRECTORY_SEPARATOR.'config'.DIRECTORY_SEPARATOR.'lang'.DIRECTORY_SEPARATOR.'eng.php';
        include_once JPATH_ADMINISTRATOR.DIRECTORY_SEPARATOR.'components'.DIRECTORY_SEPARATOR.'com_sasfe'.DIRECTORY_SEPARATOR.'common'.DIRECTORY_SEPARATOR.'KoolControls'.DIRECTORY_SEPARATOR.'KoolGrid'.DIRECTORY_SEPARATOR.'library'.DIRECTORY_SEPARATOR.'tcpdf'.DIRECTORY_SEPARATOR.'tcpdf.php';
        $fechaActual = $this->fechaActual(); //La fecha actual
        /*
         * Cabeceras para imprimir el pdf
         */
        $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
        // set document information
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor('Esphabit');
        $pdf->SetTitle('Rpt Prospectos');
        $pdf->SetSubject('Rpt Prospectos');
        $pdf->SetKeywords('Esphabit, reporte, Prospectos, PDF');
        // remove default header/footer
        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);
        // set default header data
        //$pdf->SetHeaderData(PDF_HEADER_LOGO, '20', PDF_HEADER_TITLE, FALSE);
        // set header and footer fonts
        $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
        $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
        // set default monospaced font
        $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
        // set margins
        $pdf->SetMargins(PDF_MARGIN_LEFT, 15, PDF_MARGIN_RIGHT);
        $pdf->SetHeaderMargin(0);
        $pdf->SetFooterMargin(false);
        // set auto page breaks
        $pdf->SetAutoPageBreak(TRUE, FALSE);
        // set image scale factor
        $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
        // set some language-dependent strings (optional)
        if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
            require_once(dirname(__FILE__).'/lang/eng.php');
            $pdf->setLanguageArray($l);
        }
        $pdf->AddPage('L');
        // $pdf->AddPage();
        $img_file = K_PATH_IMAGES.'logo_esphabit.jpg';
        $html0 = '
        <table cellspacing="0" cellpadding="1" border="0">
            <tr>
                <td width="70"><img src="'.$img_file.'" height="80"  /></td>
                <td width="875"><div style="text-align:center;font-size:50px;"><strong>ESPHABIT, S.A. DE C.V</strong></div><div style="text-align:center;font-size:25px;">25 PONIENTE 4501-201 COL. AMPLIACION REFORMA <br/> PUEBLA, PUE. C.P. 72130 <br/> R.F.C. ESP91093023A</div> </td>
            </tr>
        </table>
        ';
        $pdf->writeHTML($html0, true, false, false, false, '');
        //Fuente y tamanio
        $pdf->SetFont('helvetica', '', 10);
        $html1 = '
        <table cellspacing="0" cellpadding="2" border="0">
          <tr>
            <td colspan="2">Indices de productividad</td>
          </tr>
          <tr>
            <td  style="text-align:left;">Del '.$fechaDel.' Al '.$fechaAl.', D'.utf8_encode("&iacute;").'as del periodo '.$difDias.'</td>
            <td  style="text-align:right;">Fecha: '.$fechaActual.'</td>
          </tr>
        </table>
        ';
        $pdf->writeHTML($html1, true, false, false, false, '');
        // -----------------------------------------------------------------------------
        $html2 = '
        <table cellspacing="0" cellpadding="2" border="1" width="945">
          <tr>
            <td width="139" rowspan="2"></td>
            <td width="248" colspan="4">Prospectos</td>
            <td width="186" colspan="3">Ventas</td>
            <td width="372" colspan="6">Seguimientos</td>
          </tr>
          <tr style="font-size:20px;">
            <td width="62"># Prospectos</td>
            <td width="62"># de Prospectos x d'.utf8_encode("&iacute;").'a</td>
            <td width="62"># de Prospectos rechazados</td>
            <td width="62">% de Prospectos rechazados</td>
            <td width="62"># de prospectos convertidos</td>
            <td width="62">% de conversion</td>
            <td width="62">Velocidad de conversion d'.utf8_encode("&iacute;").'as</td>
            <td width="62"># de eventos programados</td>
            <td width="62"># de eventos cumplidos</td>
            <td width="62">% de eventos cumplidos</td>
            <td width="62"># de eventos no cumplidos</td>
            <td width="62">% de eventos no cumplidos</td>
            <td width="62">Prom de eventos diarios</td>
          </tr>
        </table>';
        $pdf->writeHTML($html2, true, false, false, false, '');
        if(count($colDatosReporte)>0){
            // -----------------------------------------------------------------------------
            $html3 = '
                <table cellspacing="0" cellpadding="2" border="1" width="945">
            ';
            foreach($colDatosReporte as $elemDato) {
                // <td style="text-align:right;">'.$elemDato->prospEnProceso.'</td>
                $html3 .= '
                      <tr style="font-size:20px;text-align:right;">
                        <td width="139" style="text-align:left;">'.$elemDato->nombreAgenteV.'</td>
                        <td width="62" style="font-size:20px;">'.$elemDato->prospAdquiridos.'</td>
                        <td width="62" style="font-size:20px;">'.$elemDato->prospectosxdia.'</td>
                        <td width="62" style="font-size:20px;">'.$elemDato->prospNoprocedido.'</td>
                        <td width="62" style="font-size:20px;">'.$elemDato->ptcRechazados.'%</td>
                        <td width="62" style="font-size:20px;">'.$elemDato->prospConvertidos.'</td>
                        <td width="62" style="font-size:20px;">'.$elemDato->ptcConversion.'%</td>
                        <td width="62" style="font-size:20px;">'.$elemDato->velocidadConversionDias.'</td>
                        <td width="62" style="font-size:20px;">'.$elemDato->eventosProgramados.'</td>
                        <td width="62" style="font-size:20px;">'.$elemDato->eventosCumplidos.'</td>
                        <td width="62" style="font-size:20px;">'.$elemDato->ptcEventosCumplidos.'%</td>
                        <td width="62" style="font-size:20px;">'.$elemDato->eventosNoCumplidos.'</td>
                        <td width="62" style="font-size:20px;">'.$elemDato->ptcEventosNoCumplidos.'%</td>
                        <td width="62" style="font-size:20px;">'.$elemDato->eventosxdia.'</td>
                      </tr>
                      ';
            }
            $html3 .= '</table>';
            $pdf->writeHTML($html3, true, false, false, false, '');
        }
        //Detalle total
        // -----------------------------------------------------------------------------
        /*
        if(count($colDatosReporte)>1){
            $totalAdquiridos = 0;
            $totalConvertidos = 0;
            $totalNoprocedido = 0;
            $totalEnProceso = 0;
            foreach($colDatosReporte as $elemDato) {
                $totalAdquiridos += $elemDato->prospAdquiridos;
                $totalConvertidos += $elemDato->prospConvertidos;
                $totalNoprocedido += $elemDato->prospNoprocedido;
                $totalEnProceso += $elemDato->prospEnProceso;
            }
            $html4 = '
            <table cellspacing="0" cellpadding="2" border="1">
              <tr style="text-align:right;">
                <td width="150"><strong>Total:</strong></td>
                <td width="122"><strong>'.$totalAdquiridos.'</strong></td>
                <td width="122"><strong>'.$totalConvertidos.'</strong></td>
                <td width="122"><strong>'.$totalNoprocedido.'</strong></td>
                <td width="122"><strong>'.$totalEnProceso.'</strong></td>
              </tr>
            </table>
            ';
            $pdf->writeHTML($html4, true, false, false, false, '');
        }
        */
        //Close and output PDF document
        $pdf->Output($nombreArchivo.'.pdf', 'D');
        //$pdf->Output('Liquidacion.pdf', 'I');
        ob_flush();
    }
    //Crea pdf para el reporte de prospectos por FUENTE
    function pdfReporteProspectosPorFuente($colDatosReporte, $nombreArchivo, $fechaDel, $fechaAl, $difDias){
        //region
        jimport('joomla.filesystem.file');
        jimport('joomla.filesystem.folder');
        require_once(JPATH_COMPONENT.'/helpers/sasfehp.php' );
        //ob_start ();
        ob_end_clean();
        include_once JPATH_ADMINISTRATOR.DIRECTORY_SEPARATOR.'components'.DIRECTORY_SEPARATOR.'com_sasfe'.DIRECTORY_SEPARATOR.'common'.DIRECTORY_SEPARATOR.'KoolControls'.DIRECTORY_SEPARATOR.'KoolGrid'.DIRECTORY_SEPARATOR.'library'.DIRECTORY_SEPARATOR.'tcpdf'.DIRECTORY_SEPARATOR.'config'.DIRECTORY_SEPARATOR.'lang'.DIRECTORY_SEPARATOR.'eng.php';
        include_once JPATH_ADMINISTRATOR.DIRECTORY_SEPARATOR.'components'.DIRECTORY_SEPARATOR.'com_sasfe'.DIRECTORY_SEPARATOR.'common'.DIRECTORY_SEPARATOR.'KoolControls'.DIRECTORY_SEPARATOR.'KoolGrid'.DIRECTORY_SEPARATOR.'library'.DIRECTORY_SEPARATOR.'tcpdf'.DIRECTORY_SEPARATOR.'tcpdf.php';
        $fechaActual = $this->fechaActual(); //La fecha actual
        /*
         * Cabeceras para imprimir el pdf
         */
        $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
        // set document information
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor('Esphabit');
        $pdf->SetTitle('Rpt Prospectos');
        $pdf->SetSubject('Rpt Prospectos');
        $pdf->SetKeywords('Esphabit, reporte, Prospectos, PDF');
        // remove default header/footer
        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);
        // set default header data
        //$pdf->SetHeaderData(PDF_HEADER_LOGO, '20', PDF_HEADER_TITLE, FALSE);
        // set header and footer fonts
        $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
        $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
        // set default monospaced font
        $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
        // set margins
        $pdf->SetMargins(PDF_MARGIN_LEFT, 15, PDF_MARGIN_RIGHT);
        $pdf->SetHeaderMargin(0);
        $pdf->SetFooterMargin(false);
        // set auto page breaks
        $pdf->SetAutoPageBreak(TRUE, FALSE);
        // set image scale factor
        $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
        // set some language-dependent strings (optional)
        if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
            require_once(dirname(__FILE__).'/lang/eng.php');
            $pdf->setLanguageArray($l);
        }
        $pdf->AddPage('L');
        // $pdf->AddPage();
        $img_file = K_PATH_IMAGES.'logo_esphabit.jpg';
        $html0 = '
        <table cellspacing="0" cellpadding="1" border="0">
            <tr>
                <td width="70"><img src="'.$img_file.'" height="80"  /></td>
                <td width="875"><div style="text-align:center;font-size:50px;"><strong>ESPHABIT, S.A. DE C.V</strong></div><div style="text-align:center;font-size:25px;">25 PONIENTE 4501-201 COL. AMPLIACION REFORMA <br/> PUEBLA, PUE. C.P. 72130 <br/> R.F.C. ESP91093023A</div> </td>
            </tr>
        </table>
        ';
        $pdf->writeHTML($html0, true, false, false, false, '');
        //Fuente y tamanio
        $pdf->SetFont('helvetica', '', 10);
        $html1 = '
        <table cellspacing="0" cellpadding="2" border="0">
          <tr>
            <td colspan="2">Indices de productividad</td>
          </tr>
          <tr>
            <td  style="text-align:left;">Del '.$fechaDel.' Al '.$fechaAl.', D'.utf8_encode("&iacute;").'as del periodo '.$difDias.'</td>
            <td  style="text-align:right;">Fecha: '.$fechaActual.'</td>
          </tr>
        </table>
        ';
        $pdf->writeHTML($html1, true, false, false, false, '');
        // -----------------------------------------------------------------------------
        $html2 = '
        <table cellspacing="0" cellpadding="2" border="1" width="945">
          <tr>
            <td width="139" rowspan="2"></td>
            <td width="248" colspan="4">Prospectos</td>
            <td width="186" colspan="3">Ventas</td>
            <td width="372" colspan="6">Seguimientos</td>
          </tr>
          <tr style="font-size:20px;">
            <td width="62"># Prospectos</td>
            <td width="62"># de Prospectos x d'.utf8_encode("&iacute;").'a</td>
            <td width="62"># de Prospectos rechazados</td>
            <td width="62">% de Prospectos rechazados</td>
            <td width="62"># de prospectos convertidos</td>
            <td width="62">% de conversion</td>
            <td width="62">Velocidad de conversion d'.utf8_encode("&iacute;").'as</td>
            <td width="62"># de eventos programados</td>
            <td width="62"># de eventos cumplidos</td>
            <td width="62">% de eventos cumplidos</td>
            <td width="62"># de eventos no cumplidos</td>
            <td width="62">% de eventos no cumplidos</td>
            <td width="62">Prom de eventos diarios</td>
          </tr>
        </table>';
        $pdf->writeHTML($html2, true, false, false, false, '');
        //endregion
        //region
        if(count($colDatosReporte)>0){
            //Recorre el arreglo general
            foreach($colDatosReporte as $arrDato) {
            // -----------------------------------------------------------------------------
            $html3 = '
                <table cellspacing="0" cellpadding="2" border="1" width="945">
            ';
                    //Recorre el detalle
                    foreach($arrDato as $elemDato) {
                        $nombreAgenteV = $elemDato->nombreAgenteV;
                // <td style="text-align:right;">'.$elemDato->prospEnProceso.'</td>
                $html3 .= '
                      <tr style="font-size:20px;text-align:right;">
                                <td width="139" style="text-align:left;">'.$elemDato->tipoCaptado.'</td>
                        <td width="62" style="font-size:20px;">'.$elemDato->prospAdquiridos.'</td>
                        <td width="62" style="font-size:20px;">'.$elemDato->prospectosxdia.'</td>
                        <td width="62" style="font-size:20px;">'.$elemDato->prospNoprocedido.'</td>
                        <td width="62" style="font-size:20px;">'.$elemDato->ptcRechazados.'%</td>
                        <td width="62" style="font-size:20px;">'.$elemDato->prospConvertidos.'</td>
                        <td width="62" style="font-size:20px;">'.$elemDato->ptcConversion.'%</td>
                        <td width="62" style="font-size:20px;">'.$elemDato->velocidadConversionDias.'</td>
                        <td width="62" style="font-size:20px;">'.$elemDato->eventosProgramados.'</td>
                        <td width="62" style="font-size:20px;">'.$elemDato->eventosCumplidos.'</td>
                        <td width="62" style="font-size:20px;">'.$elemDato->ptcEventosCumplidos.'%</td>
                        <td width="62" style="font-size:20px;">'.$elemDato->eventosNoCumplidos.'</td>
                        <td width="62" style="font-size:20px;">'.$elemDato->ptcEventosNoCumplidos.'%</td>
                        <td width="62" style="font-size:20px;">'.$elemDato->eventosxdia.'</td>
                      </tr>
                      ';
            }
            $html3 .= '</table>';


        // -----------------------------------------------------------------------------
                $htmlNAgente = '<div style="font-size:30px;">'.$nombreAgenteV.'</div>'; //border:1px solid #000;

                $pdf->writeHTML($htmlNAgente, true, false, false, false, '');
                $pdf->writeHTML($html3, true, false, false, false, '');

                //Resetear variables
                $html3 = '';
                $htmlNAgente = '';
            }
        }
        //endregion

        //Close and output PDF document
        $pdf->Output($nombreArchivo.'.pdf', 'D');
        // $pdf->Output($nombreArchivo.'.pdf', 'I');
        ob_flush();
    }

    //Crea pdf para el reporte de prospectos
    function pdfReporteProspectosHtml($colDatosReporte, $nombreArchivo, $fechaDel, $fechaAl, $difDias){
        jimport('joomla.filesystem.file');
        jimport('joomla.filesystem.folder');
        require_once(JPATH_COMPONENT.'/helpers/sasfehp.php' );
        //ob_start ();
        // ob_end_clean();
        // include_once JPATH_ADMINISTRATOR.DIRECTORY_SEPARATOR.'components'.DIRECTORY_SEPARATOR.'com_sasfe'.DIRECTORY_SEPARATOR.'common'.DIRECTORY_SEPARATOR.'KoolControls'.DIRECTORY_SEPARATOR.'KoolGrid'.DIRECTORY_SEPARATOR.'library'.DIRECTORY_SEPARATOR.'tcpdf'.DIRECTORY_SEPARATOR.'config'.DIRECTORY_SEPARATOR.'lang'.DIRECTORY_SEPARATOR.'eng.php';
        // include_once JPATH_ADMINISTRATOR.DIRECTORY_SEPARATOR.'components'.DIRECTORY_SEPARATOR.'com_sasfe'.DIRECTORY_SEPARATOR.'common'.DIRECTORY_SEPARATOR.'KoolControls'.DIRECTORY_SEPARATOR.'KoolGrid'.DIRECTORY_SEPARATOR.'library'.DIRECTORY_SEPARATOR.'tcpdf'.DIRECTORY_SEPARATOR.'tcpdf.php';
        $fechaActual = $this->fechaActual(); //La fecha actual

        $html = '';
        // $img_file = K_PATH_IMAGES.'logo_esphabit.jpg';
        // $html .= '
        // <table cellspacing="0" cellpadding="1" border="0">
        //     <tr>
        //         <td width="70"><img src="'.$img_file.'" height="80"  /></td>
        //         <td width="875"><div style="text-align:center;font-size:50px;"><strong>ESPHABIT, S.A. DE C.V</strong></div><div style="text-align:center;font-size:25px;">25 PONIENTE 4501-201 COL. AMPLIACION REFORMA <br/> PUEBLA, PUE. C.P. 72130 <br/> R.F.C. ESP91093023A</div> </td>
        //     </tr>
        // </table>
        // ';
        // -----------------------------------------------------------------------------
        $html .= '
        <table cellspacing="0" cellpadding="2" border="0">
          <tr>
            <td colspan="2">Indices de productividad</td>
          </tr>
          <tr>
            <td  style="text-align:left;">Del '.$fechaDel.' Al '.$fechaAl.', D'.utf8_encode("&iacute;").'as del periodo '.$difDias.'</td>
            <td  style="text-align:right;">Fecha: '.$fechaActual.'</td>
          </tr>
        </table>
        ';
        // -----------------------------------------------------------------------------
        $html .= '
        <table cellspacing="0" cellpadding="2" border="1" width="945">
          <tr>
            <td width="139" rowspan="2"></td>
            <td width="248" colspan="4">Prospectos</td>
            <td width="186" colspan="3">Ventas</td>
            <td width="372" colspan="6">Seguimientos</td>
          </tr>
          <tr style="font-size:20px;">
            <td width="62"># Prospectos</td>
            <td width="62"># de Prospectos x d'.utf8_encode("&iacute;").'a</td>
            <td width="62"># de Prospectos rechazados</td>
            <td width="62">% de Prospectos rechazados</td>
            <td width="62"># de prospectos convertidos</td>
            <td width="62">% de conversion</td>
            <td width="62">Velocidad de conversion d'.utf8_encode("&iacute;").'as</td>
            <td width="62"># de eventos programados</td>
            <td width="62"># de eventos cumplidos</td>
            <td width="62">% de eventos cumplidos</td>
            <td width="62"># de eventos no cumplidos</td>
            <td width="62">% de eventos no cumplidos</td>
            <td width="62">Prom de eventos diarios</td>
          </tr>';
          if(count($colDatosReporte)>0){
            foreach($colDatosReporte as $elemDato) {
              $html .= '
                <tr style="font-size:20px;text-align:right;">
                  <td width="139" style="text-align:left;">'.$elemDato->nombreAgenteV.'</td>
                  <td width="62" style="font-size:20px;">'.$elemDato->prospAdquiridos.'</td>
                  <td width="62" style="font-size:20px;">'.$elemDato->prospectosxdia.'</td>
                  <td width="62" style="font-size:20px;">'.$elemDato->prospNoprocedido.'</td>
                  <td width="62" style="font-size:20px;">'.$elemDato->ptcRechazados.'%</td>
                  <td width="62" style="font-size:20px;">'.$elemDato->prospConvertidos.'</td>
                  <td width="62" style="font-size:20px;">'.$elemDato->ptcConversion.'%</td>
                  <td width="62" style="font-size:20px;">'.$elemDato->velocidadConversionDias.'</td>
                  <td width="62" style="font-size:20px;">'.$elemDato->eventosProgramados.'</td>
                  <td width="62" style="font-size:20px;">'.$elemDato->eventosCumplidos.'</td>
                  <td width="62" style="font-size:20px;">'.$elemDato->ptcEventosCumplidos.'%</td>
                  <td width="62" style="font-size:20px;">'.$elemDato->eventosNoCumplidos.'</td>
                  <td width="62" style="font-size:20px;">'.$elemDato->ptcEventosNoCumplidos.'%</td>
                  <td width="62" style="font-size:20px;">'.$elemDato->eventosxdia.'</td>
                </tr>';
              }
            }
        $html .= '
        </table>';

        // echo $html;
        return $html;
    }
}

?>
