<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted Access');
jimport('joomla.application.component.controllerform');

class SasfeControllerReportes extends JControllerForm {

    function cancel($key=NULL)
    {
        $this->setRedirect( 'index.php?option=com_sasfe');
    }

    function reporteGral(){
        // ini_set('memory_limit','128M');
        jimport('joomla.filesystem.file');
        jimport('joomla.filesystem.folder');
        set_include_path(get_include_path() . PATH_SEPARATOR . '../../../Classes/');
        include_once JPATH_ADMINISTRATOR.DIRECTORY_SEPARATOR.'components'.DIRECTORY_SEPARATOR.'com_sasfe'.DIRECTORY_SEPARATOR.'common'.DIRECTORY_SEPARATOR.'Classes'.DIRECTORY_SEPARATOR.'PHPExcel.php';
        require_once JPATH_COMPONENT . '/helpers/sasfehp.php';

        $arrDepartamentos = array();
        $arrDatosGrals = array();
        $arrTodosDatos = array();
        $arrDatosExportar = array();
        $arrPorcentajes = array();

        $user = JFactory::getUser();
        $idFracc = JRequest::getVar('fracc'); //Obtiene id de fraccionamiento seleccionado

        //Obtener fraccionamiento/s seleccionados
        $this->Fracc = SasfehpHelper::obtTodosFraccionamientosPorId($idFracc);
        $this->TotalesTablas = SasfehpHelper::obtFilasTotalesTablasPorIdFracc($idFracc);
        //Obtener todos los departamentos por id de fraccionamiento
        if(count($this->Fracc) > 0){

            foreach($this->Fracc as $elemFracc){
                 $arrDepartamentos = (object)SasfehpHelper::obtTodosDepartamentosPorIdFracc($elemFracc->idFraccionamiento);

                 $arrTodosDatos[] = (object)array("idFraccionamiento"=>$elemFracc->idFraccionamiento, "nombre"=>$elemFracc->nombre,
                                          "datosDpts"=>$arrDepartamentos );
            }
        }
        // echo "<pre>";print_r($arrTodosDatos);echo "</pre>";
        // exit();

        $desarrollo = '';
        $nivel = '';
        $dtu = '';

        if(count($arrTodosDatos) > 0){
            foreach($arrTodosDatos as $elem){
                $desarrollo = $elem->nombre;

                foreach($elem->datosDpts as $datoDpt){

                    $nivel = $datoDpt->nivel;
                    $prototipo = $datoDpt->prototipo;
                    $numeroDpt = $datoDpt->numero;
                    $dtu = ($datoDpt->datosGrales->dtu==1) ? 'SI' : 'NO';

                    if($datoDpt->datosGrales->fechaCierre != '0000-00-00' && $datoDpt->datosGrales->fechaCierre !=''){
                        $fechaCierre = date("d/m/Y", strtotime($datoDpt->datosGrales->fechaCierre) );
                    }else{
                        $fechaCierre = '';
                    }

                    if($datoDpt->datosGrales->fechaApartado != '0000-00-00' && $datoDpt->datosGrales->fechaApartado !=''){
                        $fechaApartado = date("d/m/Y", strtotime($datoDpt->datosGrales->fechaApartado) );
                    }else{
                        $fechaApartado = '';
                    }

                    $referencia = $datoDpt->datosGrales->referencia;
                    $cliente = $datoDpt->datosGrales->datosClientes->nombre .' ' .$datoDpt->datosGrales->datosClientes->aPaterno .' ' .$datoDpt->datosGrales->datosClientes->aManterno;
                    $seguro = $datoDpt->datosGrales->datosClientes->NSS;
                    $tipoCredito = $datoDpt->datosGrales->datosClientes->tipoCredito;
                    $direccion = strtoupper($datoDpt->datosGrales->datosClientes->calle .' ' .$datoDpt->datosGrales->datosClientes->numero .' ' .$datoDpt->datosGrales->datosClientes->colonia .' '. $datoDpt->datosGrales->datosClientes->municipio );
                    $email = $datoDpt->datosGrales->datosClientes->email;//Implementado
                    $empresa = $datoDpt->datosGrales->datosClientes->empresa;
                    $estatus = $datoDpt->datosGrales->estatus;
                    $observaciones = $datoDpt->datosGrales->observaciones;
                    if($datoDpt->datosGrales->fechaEstatus != '0000-00-00' && $datoDpt->datosGrales->fechaEstatus !=''){
                        $fechaEstatus = date("d/m/Y", strtotime($datoDpt->datosGrales->fechaEstatus) );
                    }else{
                        $fechaEstatus = '';
                    }
                    $gerenteVentas = $datoDpt->datosGrales->gerenteVentas;
                    $titulacion = $datoDpt->datosGrales->titulacion;
                    $promocion = $datoDpt->datosGrales->promocion;
                    /*
                     * Datos de credito
                     */
                    $numeroCredito = $datoDpt->datosGrales->datosCredito->numeroCredito;
                    $valorVivienda = $datoDpt->datosGrales->datosCredito->valorVivienda;
                    $acabados = $datoDpt->datosGrales->acabados;
                    $serviciosMun = $datoDpt->datosGrales->serviciosMun;
                    $valorTotalViv = $valorVivienda+$acabados+$serviciosMun;
                    $cInfonavit = $datoDpt->datosGrales->datosCredito->cInfonavit;
                    $sFederal = $datoDpt->datosGrales->datosCredito->sFederal;
                    $ctoMasSub = $cInfonavit+$sFederal;
                    $diferencia = $valorTotalViv-$ctoMasSub;
                    $gEscrituracion = $datoDpt->datosGrales->datosCredito->gEscrituracion;
                    $ahorroVol = $datoDpt->datosGrales->datosCredito->ahorroVol;
                    $diferenciaTotal = $diferencia+$gEscrituracion+$ahorroVol;
                    $asesor = $datoDpt->datosGrales->asesor;
                    $comision = $datoDpt->datosGrales->datosNominas->comision;

                    $pctIdAses = $datoDpt->datosGrales->datosNominas->pctIdAses;
                    if($pctIdAses!=''){
                        //Tomar valores de porcentajes desde db para el calculo del asesor
                        $arrPorcentajes = SasfehpHelper::obtDatosPorcentajePorId($pctIdAses);

                        $apartado = ($comision*$arrPorcentajes->apartado)*$arrPorcentajes->mult;
                        $escritura = ($comision*$arrPorcentajes->escritura)*$arrPorcentajes->mult;
                        $liquidacion = ($comision*$arrPorcentajes->liquidacion)*$arrPorcentajes->mult;
                    }else{
                        //Calculo para el asesor por default
                        $apartado = ($comision*0.3)*0.92;
                        $escritura = ($comision*0.5)*0.92;
                        $liquidacion = ($comision*0.2)*0.92;
                    }

                    if($datoDpt->datosGrales->datosNominas->fechaPagApartado != '0000-00-00' && $datoDpt->datosGrales->datosNominas->fechaPagApartado!=''){
                        $fechaPagApartado = date("d/m/Y", strtotime($datoDpt->datosGrales->datosNominas->fechaPagApartado) );
                    }else{
                        $fechaPagApartado = '';
                        //$apartado = 0;
                    }

                    if($datoDpt->datosGrales->datosNominas->fechaDescomicion != '0000-00-00' && $datoDpt->datosGrales->datosNominas->fechaDescomicion!=''){
                        $fechaDescomicion = date("d/m/Y", strtotime($datoDpt->datosGrales->datosNominas->fechaDescomicion) );
                    }else{
                        $fechaDescomicion = '';
                    }

                    if($datoDpt->datosGrales->datosNominas->fechaPagEscritura != '0000-00-00' && $datoDpt->datosGrales->datosNominas->fechaPagEscritura!=''){
                        $fechaPagEscritura = date("d/m/Y", strtotime($datoDpt->datosGrales->datosNominas->fechaPagEscritura) );
                    }else{
                        $fechaPagEscritura = '';
                        //$escritura = 0;
                    }

                    if($datoDpt->datosGrales->datosNominas->fechaPagLiquidacion != '0000-00-00' && $datoDpt->datosGrales->datosNominas->fechaPagLiquidacion!=''){
                        $fechaPagLiquidacion = date("d/m/Y", strtotime($datoDpt->datosGrales->datosNominas->fechaPagLiquidacion) );
                    }else{
                        $fechaPagLiquidacion = '';
                        //$liquidacion = 0;
                    }

                    $totalAsesor = $apartado+$escritura+$liquidacion;

                    //Para prospectador nominas
                    $prospectador = $datoDpt->datosGrales->prospectador;
                    $comisionPros = $datoDpt->datosGrales->datosNominas->comisionPros;

                    $pctIdProsp = $datoDpt->datosGrales->datosNominas->pctIdProsp;

                    if($pctIdProsp!=''){
                        //Tomar valores de porcentajes desde db para el calculo del asesor
                        $arrPorcentajesProsp = SasfehpHelper::obtDatosPorcentajePorId($pctIdProsp);

                        $apartadoProsp = ($comisionPros*$arrPorcentajesProsp->apartado)*$arrPorcentajesProsp->mult;
                        $escrituraProsp = ($comisionPros*$arrPorcentajesProsp->escritura)*$arrPorcentajesProsp->mult;
                    }else{
                        //Calculo para el asesor por default
                        $apartadoProsp = ($comisionPros*0.40)*0.92;
                        $escrituraProsp = ($comisionPros*0.60)*0.92;
                    }


                    if($datoDpt->datosGrales->datosNominas->fPagoApartadoPros != '0000-00-00' && $datoDpt->datosGrales->datosNominas->fPagoApartadoPros!=''){
                        $fechaPagApartadoProsp = date("d/m/Y", strtotime($datoDpt->datosGrales->datosNominas->fPagoApartadoPros) );
                    }else{
                        $fechaPagApartadoProsp = '';
                        //$apartadoProsp = 0;
                    }

                    if($datoDpt->datosGrales->datosNominas->fPagoDescomisionPros != '0000-00-00' && $datoDpt->datosGrales->datosNominas->fPagoDescomisionPros!=''){
                        $fPagoDescomisionPros = date("d/m/Y", strtotime($datoDpt->datosGrales->datosNominas->fPagoDescomisionPros) );
                    }else{
                        $fPagoDescomisionPros = '';
                    }

                    if($datoDpt->datosGrales->datosNominas->fPagoEscrituraPros != '0000-00-00' && $datoDpt->datosGrales->datosNominas->fPagoEscrituraPros!=''){
                        $fPagoEscrituraPros = date("d/m/Y", strtotime($datoDpt->datosGrales->datosNominas->fPagoEscrituraPros) );
                    }else{
                        $fPagoEscrituraPros = '';
                        //$escrituraProsp = 0;
                    }

                    $totalProspectador = $apartadoProsp+$escrituraProsp;


                    //Fecha de entrega de vivienda
                    if( $datoDpt->datosGrales->fechaEntrega != '0000-00-00' && $datoDpt->datosGrales->fechaEntrega !=''){
                        $fechaEntregaViv = date("d/m/Y", strtotime($datoDpt->datosGrales->fechaEntrega) );
                    }else{
                        $fechaEntregaViv = '';

                    }
                    $reprogramacion = $datoDpt->datosGrales->reprogramacion;

                    //Referencias
                    //$referenciasTel = $datoDpt->datosGrales->referencias;
                    $idCliente = ($datoDpt->datosGrales->datosClientes->idDatoCliente!='') ? $datoDpt->datosGrales->datosClientes->idDatoCliente : '0';
                    $idCredito = ($datoDpt->datosGrales->datosCredito->idDatoCredito!='') ? $datoDpt->datosGrales->datosCredito->idDatoCredito : '0';

                    $arrDatosExportar[] = (object)array("nivel"=>$nivel, "prototipo"=>$prototipo, "numeroDpt"=>$numeroDpt, "desarrollo"=>$desarrollo, "dtu"=>$dtu,
                                                        "fechaCierre"=>$fechaCierre, "fechaApartado"=>$fechaApartado, "referencia"=>$referencia, "cliente"=>$cliente,
                                                        "seguro"=>$seguro, "tipoCredito"=>$tipoCredito, "direccion"=>$direccion, "email"=>$email, "empresa"=>$empresa, "estatus"=>$estatus,
                                                        "observaciones"=>$observaciones, "fechaEstatus"=>$fechaEstatus, "gerenteVentas"=>$gerenteVentas, "titulacion"=>$titulacion,
                                                        "promocion"=>$promocion, "numeroCredito"=>$numeroCredito, "valorVivienda"=>($valorVivienda!= '') ? '$ ' .number_format($valorVivienda,2):'', "acabados"=>($acabados!='') ? '$ ' .number_format($acabados,2):'' ,
                                                        "serviciosMun"=>($serviciosMun!='') ? '$ ' .number_format($serviciosMun,2):'', "valorTotalViv"=>($valorTotalViv!='') ? '$ ' .number_format($valorTotalViv,2):'', "cInfonavit"=>($cInfonavit!='') ?'$ ' .number_format($cInfonavit,2):'', "sFederal"=>($sFederal!='') ? '$ ' .number_format($sFederal,2):'',
                                                        "ctoMasSub"=>($ctoMasSub!='')? '$ ' .number_format($ctoMasSub,2):'', "diferencia"=>($diferencia!='')? '$ ' .number_format($diferencia,2):'', "gEscrituracion"=>($gEscrituracion!='')? '$ ' .number_format($gEscrituracion,2):'', "ahorroVol"=>($ahorroVol!='') ? '$ ' .number_format($ahorroVol,2):'',

                                                        "diferenciaTotal"=>($diferenciaTotal!='')? '$ ' .number_format($diferenciaTotal,2):'', "asesor"=>$asesor, "comisionAses"=>($comision!='')? '$ ' .number_format($comision,2):'', "apartadoAses"=>($apartado!='')? '$ ' .number_format($apartado,2):'', "fechaPagApartadoAses"=>$fechaPagApartado,
                                                        "fechaDescomicionAses"=>$fechaDescomicion, "escrituraAses"=>($escritura!='')? '$ ' .number_format($escritura,2):'', "fechaPagEscrituraAses"=>$fechaPagEscritura,  "liquidacionAses"=>($liquidacion!='')? '$ ' .number_format($liquidacion,2):'',
                                                        "fechaPagLiquidacionAses"=>$fechaPagLiquidacion, "totalAsesor"=>($totalAsesor!='')? '$ ' .number_format($totalAsesor,2):'' , "prospectador"=>$prospectador, "comisionPros"=>($comisionPros!='')? '$ ' .number_format($comisionPros,2):'',
                                                        "apartadoProsp"=>($apartadoProsp)? '$ ' .number_format($apartadoProsp,2):'', "fechaPagApartadoProsp"=>$fechaPagApartadoProsp, "fPagoDescomisionPros"=>$fPagoDescomisionPros,
                                                        "escrituraProsp"=>($escrituraProsp)? '$ ' .number_format($escrituraProsp,2):'', "fPagoEscrituraPros"=>$fPagoEscrituraPros, "totalProspectador"=>($totalProspectador!='')? '$ ' .number_format($totalProspectador,2):'', "fechaEntregaViv"=>$fechaEntregaViv,
                                                        "reprogramacion"=>$reprogramacion, "idDatoCliente"=>$idCliente, "idCredito"=>$idCredito,
                                                        "idDatoGral"=>$datoDpt->datosGrales->idDatoGeneral,
                        );


                }
            }

        }


//              echo '<pre>';
//                      print_r($arrDatosExportar);
//                    print_r($arrTodosDatos);
//              echo '</pre>';


        //$modelReporte = $this->getModel('reportes', 'SasfeModel');
        //$nameSubdivisionByIdSd = $model->getNameSubdivisionByIdSubdivision($idsd);


/*Lineas que crean el xls*/

set_time_limit(0);
        date_default_timezone_set('America/Mexico_City');

        if (PHP_SAPI == 'cli')
                die('Este archivo solo se puede ver desde un navegador web');

        // Se crea el objeto PHPExcel
        $objPHPExcel = new PHPExcel();

        // Se asignan las propiedades del libro
        $objPHPExcel->getProperties()->setCreator("Framelova") //Autor
                                    ->setLastModifiedBy("Framelova") //Ultimo usuario que lo modifico
                                    ->setTitle("Reporte Excel")
                                    ->setSubject("Reporte Excel")
                                    ->setDescription("Reporte por fraccionamientos")
                                    ->setKeywords("reporte")
                                    ->setCategory("Reporte excel");

        $titulosColumnas = array('NIVEL', 'PROTOTIPO', 'DEPARTAMENTO', 'DESARROLLO', 'DTU', 'FECHA CIERRE', 'FECHA DE APARTADO',
                                 'REFERENCIA', "CLIENTE", "NSS", "TIPO DE CREDITO", "DIRECCION", "EMAIL",  "EMPRESA", "STATUS", "OBSERVACIONES",
                                 'FECHA DE STATUS', 'GERENTE VENTAS', 'TITULACION', 'PROMOCION', 'No. DE CREDITO', 'VALOR DE VIVIENDA',
                                 'ACABADOS', 'SERV. MUN.', 'VALOR TOTAL DE LA VIVIENDA', 'CREDITO INFONAVIT', 'SUBSIDIO FEDERAL',
                                 'CREDITO + SUBSIDIO', 'DIFERENCIA', 'GASTOS DE ESCRITURACION', 'AHORRO VOLUNTARIO', 'DIFERENCIA TOTAL',
                                 'ASESOR', 'COMISION', 'APARTADO', 'FECHA DE PAGO', 'FECHA  DESCOMISION', 'ESCRITURA ', 'FECHA DE PAGO',
                                 'LIQUIDACION', 'FECHA DE PAGO', 'TOTAL', 'PROSPECTADOR', 'COMISION', 'APARTADO', 'FECHA DE PAGO',
                                 'FECHA  DESCOMISION', 'ESCRITURA', 'FECHA DE PAGO', 'TOTAL', 'FECHA ENTREGA DE VIVIENDA', 'REPROGRAMACION'
                );

        $totalTituloCol = count($titulosColumnas);
        $colA = 'A';
        $FilaTitulo = 1;
        for($i=1; $i<=$totalTituloCol; $i++){
            $objPHPExcel->getActiveSheet()->setCellValue($colA.$FilaTitulo, $titulosColumnas[$i-1]);
            $colA++;
        }
        $contLetra = "$colA"."1";

        //$objPHPExcel->setActiveSheetIndex(0)->setCellValue($contLetra, 'Hola');

        $i=2;

        $columnInicioFija = 'AY';
        $columnTitulos = 'AZ';

        $columTelefonos = 'AZ';
        $columReferencias = '';
        $columDepositos = '';
        $columPagares = '';
        $columAcabados = '';
        $columServicios = '';
        $columPostVenta = '';

        for($a=0; $a<$this->TotalesTablas->totalTelefonos; $a++){
            $columnInicioFija++;
            $columReferencias = $columnInicioFija;
            $columReferencias++;
        }
        $columRefGral = $columReferencias;

        for($b=0; $b<$this->TotalesTablas->totalReferencias; $b++){
            $columnInicioFija++;
            $columDepositos = $columnInicioFija;
            $columDepositos++;
        }
        $columDepGral = $columDepositos;

        for($b=0; $b<$this->TotalesTablas->totalDepositos; $b++){
            $columnInicioFija++;
            $columPagares = $columnInicioFija;
            $columPagares++;
        }
        $columPagaresGral = $columPagares;

        for($c=0; $c<$this->TotalesTablas->totalPagares; $c++){
            $columnInicioFija++;
            $columAcabados = $columnInicioFija;
            $columAcabados++;
        }
        $columAcabadosGral = $columAcabados;

        for($d=0; $d<$this->TotalesTablas->totalAcabados; $d++){
            $columnInicioFija++;
            $columServicios = $columnInicioFija;
            $columServicios++;
        }
        $columServiciosGral = $columServicios;

        for($e=0; $e<$this->TotalesTablas->totalServicios; $e++){
            $columnInicioFija++;
            $columPostVenta = $columnInicioFija;
            $columPostVenta++;
        }
        $columPostVentaGral = $columPostVenta;
/*
        for($f=0; $f<=$this->TotalesTablas->totalPostVenta; $f++){
            $columPostVenta = $columnInicioFija;
            $columnInicioFija++;
        }
        $columPostVentaGral = $columPostVenta;
        */

        //Para el arreglo de los titulos dinamicos
        $contGral = 1;
        $titulosColumnasD = array();
        for($zt=0; $zt<$this->TotalesTablas->totalTelefonos; $zt++){
            $titulosColumnasD[] = 'TELEFONO ' .$contGral;
            $contGral++;
        }

        $contGral = 1;
        for($yt=0; $yt<$this->TotalesTablas->totalReferencias; $yt++){
            $titulosColumnasD[] = 'REFERENCIA ' .$contGral;
            $contGral++;
        }

        $contGral = 1;
        for($wt=0; $wt<$this->TotalesTablas->totalDepositos; $wt++){
            $titulosColumnasD[] = 'DEPOSITO ' .$contGral;
            $contGral++;
        }

        $contGral = 1;
        for($tt=0; $tt<$this->TotalesTablas->totalPagares; $tt++){
            $titulosColumnasD[] = 'PAGARE ' .$contGral;
            $contGral++;
        }

        $contGral = 1;
        for($st=0; $st<$this->TotalesTablas->totalAcabados; $st++){
            $titulosColumnasD[] = 'ACABADO ' .$contGral;
            $contGral++;
        }

        $contGral = 1;
        for($pt=0; $pt<$this->TotalesTablas->totalServicios; $pt++){
            $titulosColumnasD[] = 'SERVICIO ' .$contGral;
            $contGral++;
        }

        $contGral = 1;
        for($rt=0; $rt<$this->TotalesTablas->totalPostVenta; $rt++){
            $titulosColumnasD[] = 'POST VENTA ' .$contGral;
            $contGral++;
        }

        for($tc=1; $tc<=count($titulosColumnasD); $tc++){
            $objPHPExcel->getActiveSheet()->setCellValue($columnTitulos.'1', $titulosColumnasD[$tc-1]);
            $columnTitulos++;
        }

        $contRows = 2;
        $filados = 2;
        foreach($arrDatosExportar as $fila){
             $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A'.$i,  $fila->nivel)
                    ->setCellValue('B'.$i,  $fila->prototipo)
                    ->setCellValue('C'.$i,  $fila->numeroDpt)
                    ->setCellValue('D'.$i,  $fila->desarrollo)
                    ->setCellValue('E'.$i,  $fila->dtu)
                    ->setCellValue('F'.$i,  $fila->fechaCierre)
                    ->setCellValue('G'.$i,  $fila->fechaApartado)
                    ->setCellValue('H'.$i,  $fila->referencia)
                    ->setCellValue('I'.$i,  $fila->cliente)
                    ->setCellValue('J'.$i,  $fila->seguro)

                    ->setCellValue('K'.$i,  $fila->tipoCredito)
                    ->setCellValue('L'.$i,  $fila->direccion)
                    ->setCellValue('M'.$i,  $fila->email) //Implementado
                    ->setCellValue('N'.$i,  $fila->empresa)
                    ->setCellValue('O'.$i,  $fila->estatus)
                    ->setCellValue('P'.$i,  $fila->observaciones)
                    ->setCellValue('Q'.$i,  $fila->fechaEstatus)
                    ->setCellValue('R'.$i,  $fila->gerenteVentas)
                    ->setCellValue('S'.$i,  $fila->titulacion)
                    ->setCellValue('T'.$i,  $fila->promocion)
                    ->setCellValue('U'.$i,  $fila->numeroCredito)
                    ->setCellValue('V'.$i,  $fila->valorVivienda)
                    ->setCellValue('W'.$i,  $fila->acabados)
                    ->setCellValue('X'.$i,  $fila->serviciosMun)
                    ->setCellValue('Y'.$i,  $fila->valorTotalViv)
                    ->setCellValue('Z'.$i,  $fila->cInfonavit)
                    ->setCellValue('AA'.$i,  $fila->sFederal)
                    ->setCellValue('AB'.$i,  $fila->ctoMasSub)
                    ->setCellValue('AC'.$i,  $fila->diferencia)
                    ->setCellValue('AD'.$i,  $fila->gEscrituracion)
                    ->setCellValue('AE'.$i,  $fila->ahorroVol)

                    ->setCellValue('AF'.$i,  $fila->diferenciaTotal)
                    ->setCellValue('AG'.$i,  $fila->asesor)
                    ->setCellValue('AH'.$i,  $fila->comisionAses)
                    ->setCellValue('AI'.$i,  $fila->apartadoAses)
                    ->setCellValue('AJ'.$i,  $fila->fechaPagApartadoAses)
                    ->setCellValue('AK'.$i,  $fila->fechaDescomicionAses)
                    ->setCellValue('AL'.$i,  $fila->escrituraAses)
                    ->setCellValue('AM'.$i,  $fila->fechaPagEscrituraAses)
                    ->setCellValue('AN'.$i,  $fila->liquidacionAses)
                    ->setCellValue('AO'.$i,  $fila->fechaPagLiquidacionAses)
                    ->setCellValue('AP'.$i,  $fila->totalAsesor)
                    ->setCellValue('AQ'.$i,  $fila->prospectador)
                    ->setCellValue('AR'.$i,  $fila->comisionPros)
                    ->setCellValue('AS'.$i,  $fila->apartadoProsp)
                    ->setCellValue('AT'.$i,  $fila->fechaPagApartadoProsp)
                    ->setCellValue('AU'.$i,  $fila->fPagoDescomisionPros)
                    ->setCellValue('AV'.$i,  $fila->escrituraProsp)
                    ->setCellValue('AW'.$i,  $fila->fPagoEscrituraPros)
                    ->setCellValue('AX'.$i,  $fila->totalProspectador)
                    ->setCellValue('AY'.$i,  $fila->fechaEntregaViv)
                    ->setCellValue('AZ'.$i,  $fila->reprogramacion)
                ;

                //Telefonos
                if($fila->idDatoCliente>0){
                    $arrTelefonos = SasfehpHelper::obtColTelefonosPorIdCliente($fila->idDatoCliente);

                    if(count($arrTelefonos)>0){
                        foreach($arrTelefonos as $listaTel){
                            $objPHPExcel->getActiveSheet()->setCellValue($columTelefonos.$filados, $listaTel->numero);
                            $columTelefonos++;
                        }
                    }
                }

                //Referencias
                if($fila->idDatoCliente>0){
                    $arrReferencias = SasfehpHelper::obtColReferenciasPorIdCliente($fila->idDatoCliente);

                    if(count($arrReferencias)>0){
                        foreach($arrReferencias as $listaRef){
                            $objPHPExcel->getActiveSheet()->setCellValue($columReferencias.$filados, $listaRef->nombreReferencia .' - '. $listaRef->telefono);
                            $columReferencias++;
                        }
                    }
                }

                //Depositos
                if($fila->idCredito>0){
                    $arrDepositos = SasfehpHelper::obtColDepositoPorIdCredito($fila->idCredito);

                    if(count($arrDepositos)>0){
                        foreach($arrDepositos as $dep){
                            $objPHPExcel->getActiveSheet()->setCellValue($columDepositos.$filados, '$ '.number_format($dep->deposito) .' - ' .$dep->fecha);
                            $columDepositos++;
                        }
                    }
                }

                //Pagares
                if($fila->idCredito>0){
                    $arrPagares = SasfehpHelper::obtColPagaresPorIdCliente($fila->idCredito);

                    if(count($arrPagares)>0){
                        foreach($arrPagares as $pag){
                            $objPHPExcel->getActiveSheet()->setCellValue($columPagares.$filados, $pag->cantidad);
                            $columPagares++;
                        }
                    }
                }

                //Acabados
                if($fila->idDatoGral > 0){
                    $arrAcabados = SasfehpHelper::obtColAcabadosPorIdDatoGral($fila->idDatoGral);

                    if(count($arrAcabados)>0){
                        foreach($arrAcabados as $aca){
                            $objPHPExcel->getActiveSheet()->setCellValue($columAcabados.$filados, $aca->nombreCatalogo .' - $'. number_format($aca->precio) );
                            $columAcabados++;
                        }
                    }
                }

                //Servicios
                if($fila->idDatoGral > 0){
                    $arrServicios = SasfehpHelper::obtColServiciosPorIdDatoGral($fila->idDatoGral);

                    if(count($arrServicios)>0){
                        foreach($arrServicios as $serv){
                            $objPHPExcel->getActiveSheet()->setCellValue($columServicios.$filados, $serv->nombreCatalogo .' - $'. number_format($serv->monto));
                            $columServicios++;
                        }
                    }
                }

                //Post venta
                if($fila->idDatoGral > 0){
                    $arrPostVenta = SasfehpHelper::obtColPostVentaPorIdDatoGral($fila->idDatoGral);

                    if(count($arrPostVenta)>0){
                        foreach($arrPostVenta as $postv){
                            $objPHPExcel->getActiveSheet()->setCellValue($columPostVenta.$filados, $postv->dato);
                            $columPostVenta++;
                        }
                    }
                }


          $i++;
          $filados++;
          $columTelefonos = 'AZ';
          $columReferencias = $columRefGral;
          $columDepositos = $columDepGral;
          $columAcabados = $columAcabadosGral;
          $columPagares = $columPagaresGral;
          $columServicios = $columServiciosGral;
          $columPostVenta = $columPostVentaGral;

          $contRows++; //Cuenta el total de las filas
        }


        $estiloTituloColumnas = array(
                'font' => array(
                    'name'=> 'Arial',
                    'size'=>8,
                    'bold'=> false,
                    'color'=> array(
                        'rgb' => '000000'
                    )
                ),
                'alignment' =>  array(
                        'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                        'vertical'   => PHPExcel_Style_Alignment::VERTICAL_CENTER,
                        'wrap'          => FALSE
        ));

        $estiloInformacion = new PHPExcel_Style();
        $estiloInformacion->applyFromArray(
        array(
                'font' => array(
                    'name'=>'Arial',
                    'size'=>8,
                    'color'=> array(
                        'rgb' => '000000'
                )

            )
        ));

        $objPHPExcel->getActiveSheet()->getStyle('A1:IV1')->applyFromArray($estiloTituloColumnas);
        $objPHPExcel->getActiveSheet()->setSharedStyle($estiloInformacion, 'A2:'.$columPostVentaGral.($i-1));
        $arrColum = array("5","6","15","34","35","37","39","44","45","47","49");

        foreach ($arrColum as $itemDate){
            for($rF1 = 2; $rF1 <$contRows; $rF1++){
                $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($itemDate,$rF1)->getNumberFormat()->setFormatCode('dd/mm/yyyy');
            }
        }
        //$objPHPExcel->getActiveSheet()->setSharedStyle($estiloInformacion, "A2:J2");

        for($j = 'A'; $j <=$contLetra; $j++){
                $objPHPExcel->setActiveSheetIndex(0)
                        ->getColumnDimension($j)->setAutoSize(FALSE);
        }

        // Se asigna el nombre a la hoja
        $objPHPExcel->getActiveSheet()->setTitle('Reporte');

        // Se activa la hoja para que sea la que se muestre cuando el archivo se abre
        $objPHPExcel->setActiveSheetIndex(0);
        // Inmovilizar paneles
        //$objPHPExcel->getActiveSheet(0)->freezePane('A4');
        $objPHPExcel->getActiveSheet(0)->freezePaneByColumnAndRow(0,2);
        //Setear nombre del archivo
        $nombreFracc = ($this->Fracc[0]->nombre!="") ?"_".str_replace(" ", "-", $this->Fracc[0]->nombre) :"";
        $fechaDescarga = "_".date("d-m-Y");
        $nombreArchivo = "Reporte".$nombreFracc.$fechaDescarga.".xls";

        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="'.$nombreArchivo.'"');
        header('Cache-Control: max-age=0');

        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        $objWriter->save('php://output');
        exit;


/*Fin de lineas que crean el xls*/


    }

    //Crear el reporte de prospectos
    public function reporteProspecto(){
        jimport('joomla.filesystem.file');
        require_once(JPATH_COMPONENT.'/helpers/sasfehp.php' );
        require_once(JPATH_COMPONENT.'/controllers/generarpdfs.php');
        $ctrGralPdf = new SasfeControllerGenerarpdfs();
        $user = JFactory::getUser();
        $groups = JAccess::getGroupsByUser($user->id, true);
        // Check for request forgeries
        JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));
        //leer el modelo correspondiente
        $model = JModelLegacy::getInstance('Prospecto', 'SasfeModel');
        $modelGM = JModelLegacy::getInstance('Globalmodelsbk', 'SasfeModel');
        $arrDateTime = SasfehpHelper::obtDateTimeZone();
        $fechaDel = SasfehpHelper::conversionFecha(JRequest::getVar('filter_fechaDel'));
        $fechaAl = SasfehpHelper::conversionFecha(JRequest::getVar('filter_fechaAl'));
        //Calcular la diferencia de dias del periodo
        $fecha1 = new DateTime($fechaDel);
        $fecha2 = new DateTime($fechaAl);
        $interval = $fecha1->diff($fecha2);
        $difDias = $interval->format('%a');
        $usuarioIdGteVenta = JRequest::getVar('usuarioIdGteVenta');
        $agtVentasId = JRequest::getVar('asig_agtventas');
        $colDatosReporte = array();
        $campofuente = (JRequest::getVar('asig_fuente')!="") ?JRequest::getVar('asig_fuente'): 0; //asig_fuente = captado en
        //buscar en la tabla de datos catalogo el id del agente usuarioIdJoomla
        //Para esto ya se debde de haber agregado el usuario de joomla correspondiente de lo contrario marcaria un error
        if($agtVentasId!=0){
            $agenteDatosCat = SasfehpHelper::obtSelectInactivoAP($agtVentasId);
            $agtVentasId = $agenteDatosCat[0]->usuarioIdJoomla;
            if($agtVentasId!=""){
                $resEncontrados = false;
                //Verificar si es reporte se genera por fuente o normal
                if($campofuente==1){
                    $resDatosRep = SasfehpHelper::obtDatosParaReportesProspectosPorFuente($agtVentasId, $fechaDel, $fechaAl, $difDias);
                    if(count($resDatosRep)>0 && isset($resDatosRep[0]->agtVentasId)){
                        $resEncontrados = true;
                    }
                }else{
                $resDatosRep = (object)SasfehpHelper::obtDatosParaReportesProspectos($agtVentasId, $fechaDel, $fechaAl, $difDias);
                if(isset($resDatosRep->agtVentasId)){
                        $resEncontrados = true;
                    }
                }
                //Si existen resultados continua
                if($resEncontrados==true){
                    $colDatosReporte[] = $resDatosRep;
                }
                else{
                    $msg = "No es posible exportar el reporte ya que el agente de ventas seleccionado no cuenta con estad&iacute;stica suficiente.";
                    $this->setRedirect( 'index.php?option=com_sasfe&view=reportes',$msg);
                }
            }else{
                $msg = "No es posible exportar el reporte ya que el agente de ventas seleccionado aun no esta asociado a un usuario de joomla en el cat&aacute;logo de Agentes de venta.";
                $this->setRedirect( 'index.php?option=com_sasfe&view=reportes',$msg);
            }
        }else{
            if(in_array("8", $groups) || in_array("10", $groups)){
                $colAsesores = SasfehpHelper::obtColElemPorIdCatalogo(3); //Agentes de venta (todos)
            }else{
                //obtener los asesores por el id del gerente de ventas
                $colAsesores = SasfehpHelper::obtColAsesoresAgtVentaXIdGteVentas($usuarioIdGteVenta);
            }
            foreach ($colAsesores as $elemAsesor) {
                $idDato = $elemAsesor->idDato;
                if($idDato!=""){
                    $agenteDatosCat = SasfehpHelper::obtSelectInactivoAP($idDato);
                    $agtVentasId = $agenteDatosCat[0]->usuarioIdJoomla;
                    if($agtVentasId!=""){
                        $resEncontrados = false;
                        //Verificar si es reporte se genera por fuente o normal
                        if($campofuente==1){
                            $resDatosRep = SasfehpHelper::obtDatosParaReportesProspectosPorFuente($agtVentasId, $fechaDel, $fechaAl, $difDias);
                            if(count($resDatosRep)>0 && isset($resDatosRep[0]->agtVentasId)){
                                $resEncontrados = true;
                            }
                        }else{
                        $resDatosRep = (object)SasfehpHelper::obtDatosParaReportesProspectos($agtVentasId, $fechaDel, $fechaAl, $difDias);
                        if(isset($resDatosRep->agtVentasId)){
                                $resEncontrados = true;
                            }
                            // $resDatosRep = (object)SasfehpHelper::obtDatosParaReportesProspectos($agtVentasId, $fechaDel, $fechaAl, $difDias);
                            // if(isset($resDatosRep->agtVentasId)){
                            //     $colDatosReporte[] = $resDatosRep;
                            // }
                        }
                        //Si existen resultados continua
                        if($resEncontrados==true){
                            $colDatosReporte[] = $resDatosRep;
                        }
                    }
                }
            }
        }
        if(count($colDatosReporte)>0){
           $nombreArchivo = "rptProspectos_".$arrDateTime->fecha."_".$arrDateTime->hora;
           if($campofuente==1){
             $ctrGralPdf->pdfReporteProspectosPorFuente($colDatosReporte, $nombreArchivo, JRequest::getVar('filter_fechaDel'), JRequest::getVar('filter_fechaAl'), $difDias);
           }else{
           $ctrGralPdf->pdfReporteProspectos($colDatosReporte, $nombreArchivo, JRequest::getVar('filter_fechaDel'), JRequest::getVar('filter_fechaAl'), $difDias);
           }
        }else{
            $msg = "No es posible exportar el reporte ya que el agente de ventas seleccionado no cuenta con estad&iacute;stica suficiente.";
            $this->setRedirect( 'index.php?option=com_sasfe&view=reportes',$msg);
        }
        // echo "<pre>";
        // // print_r($agenteDatosCat);
        // print_r($colDatosReporte);
        // echo "</pre>";
    }

    //Imp. 08/09/21, Carlos, Mostrar en pantalla el reporte de productividad de prospectos
    public function reporteProspectoPantalla(){
        jimport('joomla.filesystem.file');
        require_once(JPATH_COMPONENT.'/helpers/sasfehp.php' );
        require_once(JPATH_COMPONENT.'/controllers/generarpdfs.php');
        $ctrGralPdf = new SasfeControllerGenerarpdfs();
        $user = JFactory::getUser();
        $groups = JAccess::getGroupsByUser($user->id, true);
        // Check for request forgeries
        // JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));
        //leer el modelo correspondiente
        $model = JModelLegacy::getInstance('Prospecto', 'SasfeModel');
        $modelGM = JModelLegacy::getInstance('Globalmodelsbk', 'SasfeModel');
        $arrDateTime = SasfehpHelper::obtDateTimeZone();
        $fechaDel = SasfehpHelper::conversionFecha($_POST['filter_fechaDel']);
        $fechaAl = SasfehpHelper::conversionFecha($_POST['filter_fechaAl']);
        //Calcular la diferencia de dias del periodo
        $fecha1 = new DateTime($fechaDel);
        $fecha2 = new DateTime($fechaAl);
        $interval = $fecha1->diff($fecha2);
        $difDias = $interval->format('%a');
        $usuarioIdGteVenta = (isset($_POST['usuarioIdGteVenta']) && $_POST['usuarioIdGteVenta']!="") ?$_POST['usuarioIdGteVenta']: 0;
        $agtVentasId = $_POST['asig_agtventas'];
        $colDatosReporte = array();
        $campofuente = (isset($_POST['asig_fuente']) && $_POST['asig_fuente']!="") ?$_POST['asig_fuente']: 0; //asig_fuente = captado en
        $msg = "";
        $resp = false;

        //buscar en la tabla de datos catalogo el id del agente usuarioIdJoomla
        //Para esto ya se debde de haber agregado el usuario de joomla correspondiente de lo contrario marcaria un error
        if($agtVentasId!=0){
            $agenteDatosCat = SasfehpHelper::obtSelectInactivoAP($agtVentasId);
            $agtVentasId = $agenteDatosCat[0]->usuarioIdJoomla;
            if($agtVentasId!=""){
                $resEncontrados = false;
                //Verificar si es reporte se genera por fuente o normal
                if($campofuente==1){
                    $resDatosRep = SasfehpHelper::obtDatosParaReportesProspectosPorFuente($agtVentasId, $fechaDel, $fechaAl, $difDias);
                    if(count($resDatosRep)>0 && isset($resDatosRep[0]->agtVentasId)){
                        $resEncontrados = true;
                    }
                }else{
                    $resDatosRep = (object)SasfehpHelper::obtDatosParaReportesProspectos($agtVentasId, $fechaDel, $fechaAl, $difDias);
                    if(isset($resDatosRep->agtVentasId)){
                        $resEncontrados = true;
                    }
                }
                //Si existen resultados continua
                if($resEncontrados==true){
                    $colDatosReporte[] = $resDatosRep;
                }
                else{
                    $msg = "No es posible exportar el reporte ya que el agente de ventas seleccionado no cuenta con estadistica suficiente.";
                    // $this->setRedirect( 'index.php?option=com_sasfe&view=reportes',$msg);
                }
            }else{
                $msg = "No es posible exportar el reporte ya que el agente de ventas seleccionado aun no esta asociado a un usuario de joomla en el catalogo de Agentes de venta.";
                // $this->setRedirect( 'index.php?option=com_sasfe&view=reportes',$msg);
            }
        }else{
            if(in_array("8", $groups) || in_array("10", $groups)){
                $colAsesores = SasfehpHelper::obtColElemPorIdCatalogo(3); //Agentes de venta (todos)
            }else{
                //obtener los asesores por el id del gerente de ventas
                $colAsesores = SasfehpHelper::obtColAsesoresAgtVentaXIdGteVentas($usuarioIdGteVenta);
            }
            foreach ($colAsesores as $elemAsesor) {
                $idDato = $elemAsesor->idDato;
                if($idDato!=""){
                    $agenteDatosCat = SasfehpHelper::obtSelectInactivoAP($idDato);
                    $agtVentasId = $agenteDatosCat[0]->usuarioIdJoomla;
                    if($agtVentasId!=""){
                        $resEncontrados = false;
                        //Verificar si es reporte se genera por fuente o normal
                        if($campofuente==1){
                            $resDatosRep = SasfehpHelper::obtDatosParaReportesProspectosPorFuente($agtVentasId, $fechaDel, $fechaAl, $difDias);
                            if(count($resDatosRep)>0 && isset($resDatosRep[0]->agtVentasId)){
                                $resEncontrados = true;
                            }
                        }else{
                        $resDatosRep = (object)SasfehpHelper::obtDatosParaReportesProspectos($agtVentasId, $fechaDel, $fechaAl, $difDias);
                        if(isset($resDatosRep->agtVentasId)){
                                $resEncontrados = true;
                            }
                            // $resDatosRep = (object)SasfehpHelper::obtDatosParaReportesProspectos($agtVentasId, $fechaDel, $fechaAl, $difDias);
                            // if(isset($resDatosRep->agtVentasId)){
                            //     $colDatosReporte[] = $resDatosRep;
                            // }
                        }
                        //Si existen resultados continua
                        if($resEncontrados==true){
                            $colDatosReporte[] = $resDatosRep;
                        }
                    }
                }
            }
        }

        // echo "<pre>";
        // print_r($colDatosReporte);
        // // print_r($_POST);
        // echo "</pre>";
        // exit();

        // Limpiar NAN
        if(count($colDatosReporte)>0){
            $resp = true;

            if($campofuente==1){
                foreach($colDatosReporte as $arrDato) {
                    foreach($arrDato as $key=>$elem) {
                        $arrDato[$key]->prospAdquiridos = self::checkIsNAN($elem->prospAdquiridos);
                        $arrDato[$key]->prospectosxdia = self::checkIsNAN($elem->prospectosxdia);
                        $arrDato[$key]->prospNoprocedido = self::checkIsNAN($elem->prospNoprocedido);
                        $arrDato[$key]->ptcRechazados = self::checkIsNAN($elem->ptcRechazados);
                        $arrDato[$key]->prospConvertidos = self::checkIsNAN($elem->prospConvertidos);
                        $arrDato[$key]->ptcConversion = self::checkIsNAN($elem->ptcConversion);
                        $arrDato[$key]->velocidadConversionDias = self::checkIsNAN($elem->velocidadConversionDias);
                        $arrDato[$key]->eventosProgramados = self::checkIsNAN($elem->eventosProgramados);
                        $arrDato[$key]->eventosCumplidos = self::checkIsNAN($elem->eventosCumplidos);
                        $arrDato[$key]->ptcEventosCumplidos = self::checkIsNAN($elem->ptcEventosCumplidos);
                        $arrDato[$key]->eventosNoCumplidos = self::checkIsNAN($elem->eventosNoCumplidos);
                        $arrDato[$key]->ptcEventosNoCumplidos = self::checkIsNAN($elem->ptcEventosNoCumplidos);
                        $arrDato[$key]->eventosxdia = self::checkIsNAN($elem->eventosxdia);
                    }
                }
            }else{
                foreach ($colDatosReporte as $key=>$elem) {
                    $colDatosReporte[$key]->prospAdquiridos = self::checkIsNAN($elem->prospAdquiridos);
                    $colDatosReporte[$key]->prospConvertidos = self::checkIsNAN($elem->prospConvertidos);
                    $colDatosReporte[$key]->prospNoprocedido = self::checkIsNAN($elem->prospNoprocedido);
                    $colDatosReporte[$key]->ptcRechazados = self::checkIsNAN($elem->ptcRechazados);
                    $colDatosReporte[$key]->ptcConversion = self::checkIsNAN($elem->ptcConversion);
                    $colDatosReporte[$key]->prospectosxdia = self::checkIsNAN($elem->prospectosxdia);
                    $colDatosReporte[$key]->velocidadConversionDias = self::checkIsNAN($elem->velocidadConversionDias);
                    $colDatosReporte[$key]->prospEnProceso = self::checkIsNAN($elem->prospEnProceso);
                    $colDatosReporte[$key]->prosPrompordia = self::checkIsNAN($elem->prosPrompordia);
                    $colDatosReporte[$key]->eventosProgramados = self::checkIsNAN($elem->eventosProgramados);
                    $colDatosReporte[$key]->eventosCumplidos = self::checkIsNAN($elem->eventosCumplidos);
                    $colDatosReporte[$key]->eventosNoCumplidos = self::checkIsNAN($elem->eventosNoCumplidos);
                    $colDatosReporte[$key]->ptcEventosCumplidos = self::checkIsNAN($elem->ptcEventosCumplidos);
                    $colDatosReporte[$key]->ptcEventosNoCumplidos = self::checkIsNAN($elem->ptcEventosNoCumplidos);
                    $colDatosReporte[$key]->eventosxdia = self::checkIsNAN($elem->eventosxdia);
                }
            }
        }

        $arr = array("result"=>$resp, "fechaDel"=>$_POST['filter_fechaDel'], "fechaAl"=>$_POST['filter_fechaAl'], "difDias"=>$difDias,
                     "fechaActual"=>$ctrGralPdf->fechaActual(), "colDatosReporte"=>$colDatosReporte, "msg"=>$msg
                 );
        ob_end_clean();
        header('Content-Type: application/json');
        echo json_encode($arr);
        exit();
    }

    // Imp. 09/09/21, Carlos, Ver detalles de los eventos por prospecto
    public function detalleEvento(){
        jimport('joomla.filesystem.file');
        require_once(JPATH_COMPONENT.'/helpers/sasfehp.php' );
        require_once(JPATH_COMPONENT.'/controllers/generarpdfs.php');
        $ctrGralPdf = new SasfeControllerGenerarpdfs();
        //leer el modelo correspondiente
        $model = JModelLegacy::getInstance('Prospecto', 'SasfeModel');
        $modelGM = JModelLegacy::getInstance('Globalmodelsbk', 'SasfeModel');
        $arrDateTime = SasfehpHelper::obtDateTimeZone();
        $fechaDel = SasfehpHelper::conversionFecha($_POST['fechaDel']);
        $fechaAl = SasfehpHelper::conversionFecha($_POST['fechaAl']);
        $idsDatosProspectos = (isset($_POST['idsDatosProspectos']) && $_POST['idsDatosProspectos']!="") ?$_POST['idsDatosProspectos']: "";
        $tipoEvento = (isset($_POST['tipoEvento'])) ?$_POST['tipoEvento']: 0;
        $resp = false;
        $msg = "No existen detalles de eventos.";
        // print_r($_POST);
        // exit();

        $colDetalles = $modelGM->detalleEventoProspectosDB($tipoEvento, $fechaDel, $fechaAl, $idsDatosProspectos);
        if(count($colDetalles)>0){
            $resp = true;
            $msg = "";
        }
        $arr = array("result"=>$resp, "colDetalles"=>$colDetalles, "msg"=>$msg);

        ob_end_clean();
        header('Content-Type: application/json');
        echo json_encode($arr);
        exit();
    }

    private function checkIsNAN($elem){
       return is_nan($elem)?0:$elem;
    }

    /**
    * Descarga de prospectos en un excel por rango de fechas
    */
    public function descargaProspecto(){
        // ini_set('memory_limit','128M');
        jimport('joomla.filesystem.file');
        jimport('joomla.filesystem.folder');
        set_include_path(get_include_path() . PATH_SEPARATOR . '../../../Classes/');
        include_once JPATH_ADMINISTRATOR.DIRECTORY_SEPARATOR.'components'.DIRECTORY_SEPARATOR.'com_sasfe'.DIRECTORY_SEPARATOR.'common'.DIRECTORY_SEPARATOR.'Classes'.DIRECTORY_SEPARATOR.'PHPExcel.php';
        require_once JPATH_COMPONENT . '/helpers/sasfehp.php';
        $user = JFactory::getUser();
        $fechaDel = JRequest::getVar('descargaProsp_del'); //Fecha desde
        $fechaHasta = JRequest::getVar('descargaProsp_hasta'); //Fecha hasta
        $arrDatosExportar = SasfehpHelper::obtDatosProspectosPorFechas(SasfehpHelper::conversionFecha($fechaDel), SasfehpHelper::conversionFecha($fechaHasta)); //Obtener informacion de los prospectos
        //Lineas que crean el xls
        set_time_limit(0);
        date_default_timezone_set('America/Mexico_City');
        if (PHP_SAPI == 'cli')
                die('Este archivo solo se puede ver desde un navegador web');
        // Se crea el objeto PHPExcel
        $objPHPExcel = new PHPExcel();
        // Se asignan las propiedades del libro
        $objPHPExcel->getProperties()->setCreator("Framelova") //Autor
                                    ->setLastModifiedBy("Framelova") //Ultimo usuario que lo modifico
                                    ->setTitle("Reporte Prospectos")
                                    ->setSubject("Reporte Prospectos")
                                    ->setDescription("Reporte Prospectos")
                                    ->setKeywords("reporte")
                                    ->setCategory("Reporte Prospectos");
        //Inicio imprimir cabecera
        $titulosColumnas = array('FECHA ALTA', 'GTE PROSPECCION', 'GTE VENTAS', 'PROSPECTADOR', 'ASESOR', 'PROSPECTO', 'FECHA NACIMIENTO', 'EDAD', 'RFC', 'TELEFONO',
                                 'CELULAR', 'GENERO', 'NSS', 'MONTO CREDITO', 'TIPO CREDITO', 'SUBSIDIO', 'PUNTOS HASTA', 'COMENTARIO', 'EMPRESA DONDE LABORA', 'CAPTADO EN',
                                 'E-MAIL'
                                );
        $totalTituloCol = count($titulosColumnas);
        $colA = 'A';
        $FilaTitulo = 1;
        for($i=1; $i<=$totalTituloCol; $i++){
            $objPHPExcel->getActiveSheet()->setCellValue($colA.$FilaTitulo, $titulosColumnas[$i-1]);
            $colA++;
        }
        $contLetra = "$colA"."1";
        //Fin imprimir cabecera
        //Inicio recorrido de informacion para imprimirla en cada celda
        if(count($arrDatosExportar)>0){
            $i=2;
            foreach($arrDatosExportar as $fila){
                 $datosGteProspeccion = ($fila->gteProspeccionId!="") ?(array)SasfehpHelper::obtInfoUsuariosJoomla($fila->gteProspeccionId) :array();
                 $datosGteVentas = ($fila->gteVentasId!="") ?(array)SasfehpHelper::obtInfoUsuariosJoomla($fila->gteVentasId) :array();
                 $datosProspectador = ($fila->altaProspectadorId!="") ?(array)SasfehpHelper::obtInfoUsuariosJoomla($fila->altaProspectadorId) :array();
                 $datosAsesor = ($fila->agtVentasId!="") ?(array)SasfehpHelper::obtInfoUsuariosJoomla($fila->agtVentasId) :array();
                 $objPHPExcel->setActiveSheetIndex(0)
                        ->setCellValue('A'.$i,  SasfehpHelper::conversionFechaF2($fila->fechaAlta))
                        ->setCellValue('B'.$i,  (count($datosGteProspeccion)>0) ?$datosGteProspeccion['name'] :"")
                        ->setCellValue('C'.$i,  (count($datosGteVentas)>0) ?$datosGteVentas['name'] :"")
                        ->setCellValue('D'.$i,  (count($datosProspectador)>0) ?$datosProspectador['name'] :"")
                        ->setCellValue('E'.$i,  (count($datosAsesor)>0) ?$datosAsesor['name'] :"")
                        ->setCellValue('F'.$i,  $fila->nombre.' '.$fila->aPaterno.' '.$fila->aManterno)
                        ->setCellValue('G'.$i,  ($fila->fechaNac!="") ?SasfehpHelper::conversionFechaF2($fila->fechaNac) :"")
                        ->setCellValue('H'.$i,  $fila->edad)
                        ->setCellValue('I'.$i,  $fila->RFC)
                        ->setCellValue('J'.$i,  $fila->telefono)
                        ->setCellValue('K'.$i,  $fila->celular)
                        ->setCellValue('L'.$i,  ($fila->genero==1) ?"M":"F")
                        ->setCellValue('M'.$i,  $fila->NSS)
                        ->setCellValue('N'.$i,  ($fila->montoCredito!="") ?number_format($fila->montoCredito,2) :"0.00")
                        ->setCellValue('O'.$i,  $fila->tipoCredito)
                        ->setCellValue('P'.$i,  ($fila->subsidio!="") ?number_format($fila->subsidio,2) :"0.00")
                        ->setCellValue('Q'.$i,  ($fila->puntosHasta!="") ?SasfehpHelper::conversionFechaF2($fila->puntosHasta) :"")
                        ->setCellValue('R'.$i,  $fila->comentario)
                        ->setCellValue('S'.$i,  $fila->empresa)
                        ->setCellValue('T'.$i,  $fila->tipoCaptado)
                        ->setCellValue('U'.$i,  $fila->email)
                    ;
                $i++;
            }
        }
        //Fin recorrido de informacion para imprimirla en cada celda
        //Inicio estilos
        $estiloTituloColumnas = array(
                'font' => array(
                    'name'=> 'Arial',
                    'size'=>8,
                    'bold'=> false,
                    'color'=> array(
                        'rgb' => '000000'
                    )
                ),
                'alignment' =>  array(
                        'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                        'vertical'   => PHPExcel_Style_Alignment::VERTICAL_CENTER,
                        'wrap'          => FALSE
        ));
        $estiloInformacion = new PHPExcel_Style();
        $estiloInformacion->applyFromArray(
        array(
                'font' => array(
                    'name'=>'Arial',
                    'size'=>8,
                    'color'=> array(
                        'rgb' => '000000'
                )
            )
        ));
        //Fin de estilos
        $objPHPExcel->getActiveSheet()->getStyle('A1:IV1')->applyFromArray($estiloTituloColumnas);
        // $objPHPExcel->getActiveSheet()->setSharedStyle($estiloInformacion, 'A2:'.$columPostVentaGral.($i-1));
        // $arrColum = array("5","6","15","34","35","37","39","44","45","47","49");
        // foreach ($arrColum as $itemDate){
        //     for($rF1 = 2; $rF1 <$contRows; $rF1++){
        //         $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($itemDate,$rF1)->getNumberFormat()->setFormatCode('dd/mm/yyyy');
        //     }
        // }
        $objPHPExcel->getActiveSheet()->setSharedStyle($estiloInformacion, "A2:IV1");
        for($j = 'A'; $j <=$contLetra; $j++){
                $objPHPExcel->setActiveSheetIndex(0)
                        ->getColumnDimension($j)->setAutoSize(TRUE);
        }
        // Se asigna el nombre a la hoja
        $objPHPExcel->getActiveSheet()->setTitle('Reporte Prospectos');
        // Se activa la hoja para que sea la que se muestre cuando el archivo se abre
        $objPHPExcel->setActiveSheetIndex(0);
        // Inmovilizar paneles
        //$objPHPExcel->getActiveSheet(0)->freezePane('A4');
        $objPHPExcel->getActiveSheet(0)->freezePaneByColumnAndRow(0,2);
        //Setear nombre del archivo
        $fechaDescarga = "_".date("d-m-Y");
        $nombreArchivo = "ReporteProspectos".$fechaDescarga.".xls";
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="'.$nombreArchivo.'"');
        header('Cache-Control: max-age=0');
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        $objWriter->save('php://output');
        exit;
        /*Fin de lineas que crean el xls*/
        // echo "<pre>";
        // print_r($_POST);
        // echo "</pre>";
    }
    /**
    * Descarga reporte de sembrado solo datos basicos
    */
    public function descargaSembradoDesdeListadoDptos(){
        // echo "<pre>";
        // print_r($_POST);
        // echo "</pre>";
        // ini_set('memory_limit','128M');
        jimport('joomla.filesystem.file');
        jimport('joomla.filesystem.folder');
        set_include_path(get_include_path() . PATH_SEPARATOR . '../../../Classes/');
        include_once JPATH_ADMINISTRATOR.DIRECTORY_SEPARATOR.'components'.DIRECTORY_SEPARATOR.'com_sasfe'.DIRECTORY_SEPARATOR.'common'.DIRECTORY_SEPARATOR.'Classes'.DIRECTORY_SEPARATOR.'PHPExcel.php';
        require_once JPATH_COMPONENT . '/helpers/sasfehp.php';
        $arrDepartamentos = array();
        $arrDatosGrals = array();
        $arrTodosDatos = array();
        $arrDatosExportar = array();
        $arrPorcentajes = array();
        $user = JFactory::getUser();
        $idFracc = JRequest::getVar('hid_idFracc'); //Obtiene id de fraccionamiento seleccionado
        //Obtener fraccionamiento/s seleccionados
        $this->Fracc = SasfehpHelper::obtTodosFraccionamientosPorId($idFracc);
        $this->TotalesTablas = SasfehpHelper::obtFilasTotalesTablasPorIdFracc($idFracc);
        //Obtener todos los departamentos por id de fraccionamiento
        if(count($this->Fracc) > 0){
            foreach($this->Fracc as $elemFracc){
                 $arrDepartamentos = (object)SasfehpHelper::obtTodosDepartamentosPorIdFracc($elemFracc->idFraccionamiento);
                 $arrTodosDatos[] = (object)array("idFraccionamiento"=>$elemFracc->idFraccionamiento, "nombre"=>$elemFracc->nombre, "datosDpts"=>$arrDepartamentos );
            }
        }
        // echo "<pre>";print_r($arrTodosDatos);echo "</pre>";
        // exit();
        $desarrollo = '';
        $nivel = '';
        $dtu = '';
        if(count($arrTodosDatos) > 0){
            foreach($arrTodosDatos as $elem){
                $desarrollo = $elem->nombre;
                foreach($elem->datosDpts as $datoDpt){
                    $nivel = $datoDpt->nivel;
                    $prototipo = $datoDpt->prototipo;
                    $numeroDpt = $datoDpt->numero;
                    $dtu = ($datoDpt->datosGrales->dtu==1) ? 'SI' : 'NO';
                    if($datoDpt->datosGrales->fechaCierre != '0000-00-00' && $datoDpt->datosGrales->fechaCierre !=''){
                        $fechaCierre = date("d/m/Y", strtotime($datoDpt->datosGrales->fechaCierre) );
                    }else{
                        $fechaCierre = '';
                    }
                    if($datoDpt->datosGrales->fechaApartado != '0000-00-00' && $datoDpt->datosGrales->fechaApartado !=''){
                        $fechaApartado = date("d/m/Y", strtotime($datoDpt->datosGrales->fechaApartado) );
                    }else{
                        $fechaApartado = '';
                    }
                    $referencia = $datoDpt->datosGrales->referencia;
                    $cliente = $datoDpt->datosGrales->datosClientes->nombre .' ' .$datoDpt->datosGrales->datosClientes->aPaterno .' ' .$datoDpt->datosGrales->datosClientes->aManterno;
                    $seguro = $datoDpt->datosGrales->datosClientes->NSS;
                    $tipoCredito = $datoDpt->datosGrales->datosClientes->tipoCredito;
                    $direccion = strtoupper($datoDpt->datosGrales->datosClientes->calle .' ' .$datoDpt->datosGrales->datosClientes->numero .' ' .$datoDpt->datosGrales->datosClientes->colonia .' '. $datoDpt->datosGrales->datosClientes->municipio );
                    $codigoP = $datoDpt->datosGrales->datosClientes->cp; //04/01/19
                    $email = $datoDpt->datosGrales->datosClientes->email;//Implementado
                    $empresa = $datoDpt->datosGrales->datosClientes->empresa;
                    $estatus = $datoDpt->datosGrales->estatus;
                    $observaciones = $datoDpt->datosGrales->observaciones;
                    if($datoDpt->datosGrales->fechaEstatus != '0000-00-00' && $datoDpt->datosGrales->fechaEstatus !=''){
                        $fechaEstatus = date("d/m/Y", strtotime($datoDpt->datosGrales->fechaEstatus) );
                    }else{
                        $fechaEstatus = '';
                    }
                    $gerenteVentas = $datoDpt->datosGrales->gerenteVentas;
                    $titulacion = $datoDpt->datosGrales->titulacion;
                    $promocion = $datoDpt->datosGrales->promocion;
                    /*
                     * Datos de credito
                     */
                    $numeroCredito = $datoDpt->datosGrales->datosCredito->numeroCredito;
                    $valorVivienda = $datoDpt->datosGrales->datosCredito->valorVivienda;
                    $acabados = $datoDpt->datosGrales->acabados;
                    $serviciosMun = $datoDpt->datosGrales->serviciosMun;
                    $valorTotalViv = $valorVivienda+$acabados+$serviciosMun;
                    $cInfonavit = $datoDpt->datosGrales->datosCredito->cInfonavit;
                    $sFederal = $datoDpt->datosGrales->datosCredito->sFederal;
                    $ctoMasSub = $cInfonavit+$sFederal;
                    $diferencia = $valorTotalViv-$ctoMasSub;
                    $gEscrituracion = $datoDpt->datosGrales->datosCredito->gEscrituracion;
                    $ahorroVol = $datoDpt->datosGrales->datosCredito->ahorroVol;
                    $diferenciaTotal = $diferencia+$gEscrituracion+$ahorroVol;
                    $asesor = $datoDpt->datosGrales->asesor;
                    $comision = $datoDpt->datosGrales->datosNominas->comision;
                    $pctIdAses = $datoDpt->datosGrales->datosNominas->pctIdAses;
                    if($pctIdAses!=''){
                        //Tomar valores de porcentajes desde db para el calculo del asesor
                        $arrPorcentajes = SasfehpHelper::obtDatosPorcentajePorId($pctIdAses);
                        $apartado = ($comision*$arrPorcentajes->apartado)*$arrPorcentajes->mult;
                        $escritura = ($comision*$arrPorcentajes->escritura)*$arrPorcentajes->mult;
                        $liquidacion = ($comision*$arrPorcentajes->liquidacion)*$arrPorcentajes->mult;
                    }else{
                        //Calculo para el asesor por default
                        $apartado = ($comision*0.3)*0.92;
                        $escritura = ($comision*0.5)*0.92;
                        $liquidacion = ($comision*0.2)*0.92;
                    }
                    if($datoDpt->datosGrales->datosNominas->fechaPagApartado != '0000-00-00' && $datoDpt->datosGrales->datosNominas->fechaPagApartado!=''){
                        $fechaPagApartado = date("d/m/Y", strtotime($datoDpt->datosGrales->datosNominas->fechaPagApartado) );
                    }else{
                        $fechaPagApartado = '';
                        //$apartado = 0;
                    }
                    if($datoDpt->datosGrales->datosNominas->fechaDescomicion != '0000-00-00' && $datoDpt->datosGrales->datosNominas->fechaDescomicion!=''){
                        $fechaDescomicion = date("d/m/Y", strtotime($datoDpt->datosGrales->datosNominas->fechaDescomicion) );
                    }else{
                        $fechaDescomicion = '';
                    }
                    if($datoDpt->datosGrales->datosNominas->fechaPagEscritura != '0000-00-00' && $datoDpt->datosGrales->datosNominas->fechaPagEscritura!=''){
                        $fechaPagEscritura = date("d/m/Y", strtotime($datoDpt->datosGrales->datosNominas->fechaPagEscritura) );
                    }else{
                        $fechaPagEscritura = '';
                        //$escritura = 0;
                    }
                    if($datoDpt->datosGrales->datosNominas->fechaPagLiquidacion != '0000-00-00' && $datoDpt->datosGrales->datosNominas->fechaPagLiquidacion!=''){
                        $fechaPagLiquidacion = date("d/m/Y", strtotime($datoDpt->datosGrales->datosNominas->fechaPagLiquidacion) );
                    }else{
                        $fechaPagLiquidacion = '';
                        //$liquidacion = 0;
                    }
                    $totalAsesor = $apartado+$escritura+$liquidacion;
                    //Para prospectador nominas
                    $prospectador = $datoDpt->datosGrales->prospectador;
                    $comisionPros = $datoDpt->datosGrales->datosNominas->comisionPros;
                    $pctIdProsp = $datoDpt->datosGrales->datosNominas->pctIdProsp;
                    if($pctIdProsp!=''){
                        //Tomar valores de porcentajes desde db para el calculo del asesor
                        $arrPorcentajesProsp = SasfehpHelper::obtDatosPorcentajePorId($pctIdProsp);
                        $apartadoProsp = ($comisionPros*$arrPorcentajesProsp->apartado)*$arrPorcentajesProsp->mult;
                        $escrituraProsp = ($comisionPros*$arrPorcentajesProsp->escritura)*$arrPorcentajesProsp->mult;
                    }else{
                        //Calculo para el asesor por default
                        $apartadoProsp = ($comisionPros*0.40)*0.92;
                        $escrituraProsp = ($comisionPros*0.60)*0.92;
                    }
                    if($datoDpt->datosGrales->datosNominas->fPagoApartadoPros != '0000-00-00' && $datoDpt->datosGrales->datosNominas->fPagoApartadoPros!=''){
                        $fechaPagApartadoProsp = date("d/m/Y", strtotime($datoDpt->datosGrales->datosNominas->fPagoApartadoPros) );
                    }else{
                        $fechaPagApartadoProsp = '';
                        //$apartadoProsp = 0;
                    }
                    if($datoDpt->datosGrales->datosNominas->fPagoDescomisionPros != '0000-00-00' && $datoDpt->datosGrales->datosNominas->fPagoDescomisionPros!=''){
                        $fPagoDescomisionPros = date("d/m/Y", strtotime($datoDpt->datosGrales->datosNominas->fPagoDescomisionPros) );
                    }else{
                        $fPagoDescomisionPros = '';
                    }
                    if($datoDpt->datosGrales->datosNominas->fPagoEscrituraPros != '0000-00-00' && $datoDpt->datosGrales->datosNominas->fPagoEscrituraPros!=''){
                        $fPagoEscrituraPros = date("d/m/Y", strtotime($datoDpt->datosGrales->datosNominas->fPagoEscrituraPros) );
                    }else{
                        $fPagoEscrituraPros = '';
                        //$escrituraProsp = 0;
                    }
                    $totalProspectador = $apartadoProsp+$escrituraProsp;
                    //Fecha de entrega de vivienda
                    if( $datoDpt->datosGrales->fechaEntrega != '0000-00-00' && $datoDpt->datosGrales->fechaEntrega !=''){
                        $fechaEntregaViv = date("d/m/Y", strtotime($datoDpt->datosGrales->fechaEntrega) );
                    }else{
                        $fechaEntregaViv = '';
                    }
                    $reprogramacion = $datoDpt->datosGrales->reprogramacion;
                    //Referencias
                    //$referenciasTel = $datoDpt->datosGrales->referencias;
                    $idCliente = ($datoDpt->datosGrales->datosClientes->idDatoCliente!='') ? $datoDpt->datosGrales->datosClientes->idDatoCliente : '0';
                    $idCredito = ($datoDpt->datosGrales->datosCredito->idDatoCredito!='') ? $datoDpt->datosGrales->datosCredito->idDatoCredito : '0';
                    $arrDatosExportar[] = (object)array("nivel"=>$nivel, "prototipo"=>$prototipo, "numeroDpt"=>$numeroDpt, "desarrollo"=>$desarrollo, "dtu"=>$dtu,
                                                        "fechaCierre"=>$fechaCierre, "fechaApartado"=>$fechaApartado, "referencia"=>$referencia, "cliente"=>$cliente,
                                                        "seguro"=>$seguro, "tipoCredito"=>$tipoCredito, "direccion"=>$direccion, "email"=>$email, "empresa"=>$empresa, "estatus"=>$estatus,
                                                        "observaciones"=>$observaciones, "fechaEstatus"=>$fechaEstatus, "gerenteVentas"=>$gerenteVentas, "titulacion"=>$titulacion,
                                                        "promocion"=>$promocion, "numeroCredito"=>$numeroCredito, "valorVivienda"=>($valorVivienda!= '') ? '$ ' .number_format($valorVivienda,2):'', "acabados"=>($acabados!='') ? '$ ' .number_format($acabados,2):'' ,
                                                        "serviciosMun"=>($serviciosMun!='') ? '$ ' .number_format($serviciosMun,2):'', "valorTotalViv"=>($valorTotalViv!='') ? '$ ' .number_format($valorTotalViv,2):'', "cInfonavit"=>($cInfonavit!='') ?'$ ' .number_format($cInfonavit,2):'', "sFederal"=>($sFederal!='') ? '$ ' .number_format($sFederal,2):'',
                                                        "ctoMasSub"=>($ctoMasSub!='')? '$ ' .number_format($ctoMasSub,2):'', "diferencia"=>($diferencia!='')? '$ ' .number_format($diferencia,2):'', "gEscrituracion"=>($gEscrituracion!='')? '$ ' .number_format($gEscrituracion,2):'', "ahorroVol"=>($ahorroVol!='') ? '$ ' .number_format($ahorroVol,2):'',
                                                        "diferenciaTotal"=>($diferenciaTotal!='')? '$ ' .number_format($diferenciaTotal,2):'', "asesor"=>$asesor, "comisionAses"=>($comision!='')? '$ ' .number_format($comision,2):'', "apartadoAses"=>($apartado!='')? '$ ' .number_format($apartado,2):'', "fechaPagApartadoAses"=>$fechaPagApartado,
                                                        "fechaDescomicionAses"=>$fechaDescomicion, "escrituraAses"=>($escritura!='')? '$ ' .number_format($escritura,2):'', "fechaPagEscrituraAses"=>$fechaPagEscritura,  "liquidacionAses"=>($liquidacion!='')? '$ ' .number_format($liquidacion,2):'',
                                                        "fechaPagLiquidacionAses"=>$fechaPagLiquidacion, "totalAsesor"=>($totalAsesor!='')? '$ ' .number_format($totalAsesor,2):'' , "prospectador"=>$prospectador, "comisionPros"=>($comisionPros!='')? '$ ' .number_format($comisionPros,2):'',
                                                        "apartadoProsp"=>($apartadoProsp)? '$ ' .number_format($apartadoProsp,2):'', "fechaPagApartadoProsp"=>$fechaPagApartadoProsp, "fPagoDescomisionPros"=>$fPagoDescomisionPros,
                                                        "escrituraProsp"=>($escrituraProsp)? '$ ' .number_format($escrituraProsp,2):'', "fPagoEscrituraPros"=>$fPagoEscrituraPros, "totalProspectador"=>($totalProspectador!='')? '$ ' .number_format($totalProspectador,2):'', "fechaEntregaViv"=>$fechaEntregaViv,
                                                        "reprogramacion"=>$reprogramacion, "idDatoCliente"=>$idCliente, "idCredito"=>$idCredito,
                                                        "idDatoGral"=>$datoDpt->datosGrales->idDatoGeneral,
                                                        "codigoP"=>$codigoP
                        );
                }
            }
        }
        //              echo '<pre>';
        //                      print_r($arrDatosExportar);
        //                    print_r($arrTodosDatos);
        //              echo '</pre>';
        //$modelReporte = $this->getModel('reportes', 'SasfeModel');
        //$nameSubdivisionByIdSd = $model->getNameSubdivisionByIdSubdivision($idsd);
        /*Lineas que crean el xls*/
        set_time_limit(0);
        date_default_timezone_set('America/Mexico_City');
        if (PHP_SAPI == 'cli')
                die('Este archivo solo se puede ver desde un navegador web');
        // Se crea el objeto PHPExcel
        $objPHPExcel = new PHPExcel();
        // Se asignan las propiedades del libro
        $objPHPExcel->getProperties()->setCreator("Framelova") //Autor
                                    ->setLastModifiedBy("Framelova") //Ultimo usuario que lo modifico
                                    ->setTitle("Reporte Excel")
                                    ->setSubject("Reporte Excel")
                                    ->setDescription("Reporte por fraccionamientos")
                                    ->setKeywords("reporte")
                                    ->setCategory("Reporte excel");
        // $titulosColumnas = array('NIVEL', 'PROTOTIPO', 'DEPARTAMENTO', 'DESARROLLO', 'DTU', 'FECHA CIERRE', 'FECHA DE APARTADO',
        //                          'REFERENCIA', "CLIENTE", "NSS", "TIPO DE CREDITO", "DIRECCION", "EMPRESA", "STATUS", "OBSERVACIONES",
        //                          'FECHA DE STATUS', 'GERENTE VENTAS', 'TITULACION', 'PROMOCION', 'No. DE CREDITO', 'VALOR DE VIVIENDA',
        //                          'ACABADOS', 'SERV. MUN.', 'VALOR TOTAL DE LA VIVIENDA', 'CREDITO INFONAVIT', 'SUBSIDIO FEDERAL',
        //                          'CREDITO + SUBSIDIO', 'DIFERENCIA', 'GASTOS DE ESCRITURACION', 'AHORRO VOLUNTARIO', 'DIFERENCIA TOTAL',
        //                          'ASESOR', 'COMISION', 'APARTADO', 'FECHA DE PAGO', 'FECHA  DESCOMISION', 'ESCRITURA ', 'FECHA DE PAGO',
        //                          'LIQUIDACION', 'FECHA DE PAGO', 'TOTAL', 'PROSPECTADOR', 'COMISION', 'APARTADO', 'FECHA DE PAGO',
        //                          'FECHA  DESCOMISION', 'ESCRITURA', 'FECHA DE PAGO', 'TOTAL', 'FECHA ENTREGA DE VIVIENDA', 'REPROGRAMACION'
        //         );
        $titulosColumnas = array('NIVEL', 'PROTOTIPO', 'DEPARTAMENTO', 'DESARROLLO', 'DTU', 'FECHA CIERRE', 'FECHA DE APARTADO',
                                 'REFERENCIA', "CLIENTE", "NSS", "TIPO DE CREDITO", "DIRECCION", "EMAIL", "EMPRESA", "ESTATUS", "OBSERVACIONES",
                                 'FECHA DE STATUS', 'GERENTE VENTAS', 'TITULACION', 'No. DE CREDITO',
                                 'ASESOR', 'PROSPECTADOR', 'CP',
                );
        $totalTituloCol = count($titulosColumnas);
        $colA = 'A';
        $FilaTitulo = 1;
        for($i=1; $i<=$totalTituloCol; $i++){
            $objPHPExcel->getActiveSheet()->setCellValue($colA.$FilaTitulo, $titulosColumnas[$i-1]);
            $colA++;
        }
        $contLetra = "$colA"."1";
        //$objPHPExcel->setActiveSheetIndex(0)->setCellValue($contLetra, 'Hola');
        $i=2;
        $contRows = 2;
        $filados = 2;
        foreach($arrDatosExportar as $fila){
             $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A'.$i,  $fila->nivel)
                    ->setCellValue('B'.$i,  $fila->prototipo)
                    ->setCellValue('C'.$i,  $fila->numeroDpt)
                    ->setCellValue('D'.$i,  $fila->desarrollo)
                    ->setCellValue('E'.$i,  $fila->dtu)
                    ->setCellValue('F'.$i,  $fila->fechaCierre)
                    ->setCellValue('G'.$i,  $fila->fechaApartado)
                    ->setCellValue('H'.$i,  $fila->referencia)
                    ->setCellValue('I'.$i,  $fila->cliente)
                    ->setCellValue('J'.$i,  $fila->seguro)
                    ->setCellValue('K'.$i,  $fila->tipoCredito)
                    ->setCellValue('L'.$i,  $fila->direccion)
                    ->setCellValue('M'.$i,  $fila->email)
                    ->setCellValue('N'.$i,  $fila->empresa)
                    ->setCellValue('O'.$i,  $fila->estatus)
                    ->setCellValue('P'.$i,  $fila->observaciones)
                    ->setCellValue('Q'.$i,  $fila->fechaEstatus)
                    ->setCellValue('R'.$i,  $fila->gerenteVentas)
                    ->setCellValue('S'.$i,  $fila->titulacion)
                    ->setCellValue('T'.$i,  $fila->numeroCredito)
                    ->setCellValue('U'.$i,  $fila->asesor)
                    ->setCellValue('V'.$i,  $fila->prospectador)
                    ->setCellValue('W'.$i,  $fila->codigoP)
                ;
          $i++;
          $filados++;
          $contRows++; //Cuenta el total de las filas
        }
        $estiloTituloColumnas = array(
                'font' => array(
                    'name'=> 'Arial',
                    'size'=>8,
                    'bold'=> false,
                    'color'=> array(
                        'rgb' => '000000'
                    )
                ),
                'alignment' =>  array(
                        'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                        'vertical'   => PHPExcel_Style_Alignment::VERTICAL_CENTER,
                        'wrap'          => FALSE
        ));
        $estiloInformacion = new PHPExcel_Style();
        $estiloInformacion->applyFromArray(
        array(
                'font' => array(
                    'name'=>'Arial',
                    'size'=>8,
                    'color'=> array(
                        'rgb' => '000000'
                )
            )
        ));
        $objPHPExcel->getActiveSheet()->getStyle('A1:IV1')->applyFromArray($estiloTituloColumnas);
        $letraInicioEst = 'A';
        $letraFinEst = 'X';
        for($ii=2; $ii<=$contRows; $ii++){
            $objPHPExcel->getActiveSheet()->setSharedStyle($estiloInformacion, $letraInicioEst.$ii.":".$letraFinEst.$ii);
        }
        for($j = 'A'; $j <=$contLetra; $j++){
                $objPHPExcel->setActiveSheetIndex(0)
                        ->getColumnDimension($j)->setAutoSize(TRUE);
        }
        //Se asigna el nombre a la hoja
        $objPHPExcel->getActiveSheet()->setTitle('Reporte Sembrado');
        // Se activa la hoja para que sea la que se muestre cuando el archivo se abre
        $objPHPExcel->setActiveSheetIndex(0);
        // Inmovilizar paneles
        //$objPHPExcel->getActiveSheet(0)->freezePane('A4');
        $objPHPExcel->getActiveSheet(0)->freezePaneByColumnAndRow(0,2);
        //Agregar imagen
        // $objDrawing = new PHPExcel_Worksheet_Drawing();
        // $objDrawing->setName('test_img');
        // $objDrawing->setDescription('test_img');
        // $objDrawing->setPath('../images/boton_admin.png');
        // $objDrawing->setCoordinates('W1');
        // //setOffsetX works properly
        // $objDrawing->setOffsetX(5);
        // $objDrawing->setOffsetY(5);
        // //set width, height
        // $objDrawing->setWidth(100);
        // $objDrawing->setHeight(35);
        // $objDrawing->setWorksheet($objPHPExcel->getActiveSheet());
        //Setear nombre del archivo
        $nombreFracc = ($this->Fracc[0]->nombre!="") ?"_".str_replace(" ", "-", $this->Fracc[0]->nombre) :"";
        $fechaDescarga = "_".date("d-m-Y");
        $nombreArchivo = "Reporte".$nombreFracc.$fechaDescarga.".xls";
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="'.$nombreArchivo.'"');
        header('Cache-Control: max-age=0');
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        $objWriter->save('php://output');
        exit;
        /*Fin de lineas que crean el xls*/
    }


    /**
    * Imp. 30/09/20
    * Descarga reporte de contactos clasificados por fuente
    */
    public function contactosFuente(){
        jimport('joomla.filesystem.file');
        jimport('joomla.filesystem.folder');
        $app = JFactory::getApplication();
        set_include_path(get_include_path() . PATH_SEPARATOR . '../../../Classes/');
        include_once JPATH_ADMINISTRATOR.DIRECTORY_SEPARATOR.'components'.DIRECTORY_SEPARATOR.'com_sasfe'.DIRECTORY_SEPARATOR.'common'.DIRECTORY_SEPARATOR.'Classes'.DIRECTORY_SEPARATOR.'PHPExcel.php';
        require_once JPATH_COMPONENT . '/helpers/sasfehp.php';
        // require_once(JPATH_COMPONENT.'/controllers/generarpdfs.php');
        // $ctrGralPdf = new SasfeControllerGenerarpdfs();
        $user = JFactory::getUser();
        $groups = JAccess::getGroupsByUser($user->id, true);
        JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));
        //leer el modelo correspondiente
        $model = JModelLegacy::getInstance('Contacto', 'SasfeModel');
        $modelGM = JModelLegacy::getInstance('Globalmodelsbk', 'SasfeModel');
        $arrDateTime = SasfehpHelper::obtDateTimeZone();
        $fechaDel = SasfehpHelper::conversionFecha(JRequest::getVar('filter_fechaDelCF'));
        $fechaAl = SasfehpHelper::conversionFecha(JRequest::getVar('filter_fechaAlCF'));
        //Obtener los registros
        $arrDatosExportar = SasfehpHelper::obtDatosParaReportesContactosPorFuente($fechaDel, $fechaAl);

        if(count($arrDatosExportar)>0){
           //Inicio de la creacion del reporte xls
            set_time_limit(0);
            date_default_timezone_set('America/Mexico_City');
            if (PHP_SAPI == 'cli')
                    die('Este archivo solo se puede ver desde un navegador web');
            // Se crea el objeto PHPExcel
            $objPHPExcel = new PHPExcel();
            // Se asignan las propiedades del libro
            $objPHPExcel->getProperties()->setCreator("Framelova") //Autor
                                        ->setLastModifiedBy("Framelova") //Ultimo usuario que lo modifico
                                        ->setTitle("Reporte Contactos")
                                        ->setSubject("Reporte Contactos")
                                        ->setDescription("Reporte Contactos")
                                        ->setKeywords("reporte")
                                        ->setCategory("Reporte Contactos");
            //Inicio imprimir cabecera
            $titulosColumnas = array('FECHA ALTA', 'GTE VENTAS', 'ASESOR', 'CONTACTO', 'E-MAIL', 'TELEFONO',
                                     'FUENTE', 'ESTATUS', 'DESARROLLO', 'FECHA CONTACTO', 'ACTIVO'
                                    );
            $totalTituloCol = count($titulosColumnas);
            $colA = 'A';
            $FilaTitulo = 1;
            for($i=1; $i<=$totalTituloCol; $i++){
                $objPHPExcel->getActiveSheet()->setCellValue($colA.$FilaTitulo, $titulosColumnas[$i-1]);
                $colA++;
            }
            $contLetra = "$colA"."1";
            //Fin imprimir cabecera
            //Inicio recorrido de informacion para imprimirla en cada celda
            if(count($arrDatosExportar)>0){
                $i=2;
                foreach($arrDatosExportar as $fila){
                     // $datosGteProspeccion = ($fila->gteProspeccionId!="") ?(array)SasfehpHelper::obtInfoUsuariosJoomla($fila->gteProspeccionId) :array();
                     // $datosGteVentas = ($fila->gteVentasId!="") ?(array)SasfehpHelper::obtInfoUsuariosJoomla($fila->gteVentasId) :array();
                     // $datosProspectador = ($fila->altaProspectadorId!="") ?(array)SasfehpHelper::obtInfoUsuariosJoomla($fila->altaProspectadorId) :array();
                     // $datosAsesor = ($fila->agtVentasId!="") ?(array)SasfehpHelper::obtInfoUsuariosJoomla($fila->agtVentasId) :array();

                     $objPHPExcel->setActiveSheetIndex(0)
                            ->setCellValue('A'.$i,  SasfehpHelper::conversionFechaF2($fila->fechaAlta))
                            ->setCellValue('B'.$i,  $fila->gteVentas)
                            ->setCellValue('C'.$i,  $fila->agtVentas)
                            ->setCellValue('D'.$i,  $fila->nombre.' '.$fila->aPaterno.' '.$fila->aMaterno)
                            ->setCellValue('E'.$i,  $fila->email)
                            ->setCellValue('F'.$i,  $fila->telefono)
                            ->setCellValue('G'.$i,  $fila->fuente)
                            ->setCellValue('H'.$i,  $fila->estatus)
                            ->setCellValue('I'.$i,  $fila->fraccionamiento)
                            ->setCellValue('J'.$i,  ($fila->fechaContacto)?SasfehpHelper::conversionFechaF2($fila->fechaContacto):"")
                            ->setCellValue('K'.$i,  ($fila->activo==1) ?"Si":"No")
                        ;
                    $i++;
                }
            }
            //Fin recorrido de informacion para imprimirla en cada celda
            //Inicio estilos
            $estiloTituloColumnas = array(
                    'font' => array(
                        'name'=> 'Arial',
                        'size'=>8,
                        'bold'=> false,
                        'color'=> array(
                            'rgb' => '000000'
                        )
                    ),
                    'alignment' =>  array(
                            'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                            'vertical'   => PHPExcel_Style_Alignment::VERTICAL_CENTER,
                            'wrap'          => FALSE
            ));
            $estiloInformacion = new PHPExcel_Style();
            $estiloInformacion->applyFromArray(
            array(
                    'font' => array(
                        'name'=>'Arial',
                        'size'=>8,
                        'color'=> array(
                            'rgb' => '000000'
                    )
                )
            ));
            //Fin de estilos
            $objPHPExcel->getActiveSheet()->getStyle('A1:L1')->applyFromArray($estiloTituloColumnas);
            $objPHPExcel->getActiveSheet()->setSharedStyle($estiloInformacion, "A2:L1");
            for($j = 'A'; $j <=$contLetra; $j++){
                    $objPHPExcel->setActiveSheetIndex(0)
                            ->getColumnDimension($j)->setAutoSize(TRUE);
            }
            // Se asigna el nombre a la hoja
            $objPHPExcel->getActiveSheet()->setTitle('Reporte Contactos');
            // Se activa la hoja para que sea la que se muestre cuando el archivo se abre
            $objPHPExcel->setActiveSheetIndex(0);
            // Inmovilizar paneles
            //$objPHPExcel->getActiveSheet(0)->freezePane('A4');
            $objPHPExcel->getActiveSheet(0)->freezePaneByColumnAndRow(0,2);
            //Setear nombre del archivo
            $fechaDescarga = "_".date("d-m-Y")."_".time();
            $nombreArchivo = "ReporteContactos".$fechaDescarga.".xls";
            header('Content-Type: application/vnd.ms-excel');
            header('Content-Disposition: attachment;filename="'.$nombreArchivo.'"');
            header('Cache-Control: max-age=0');
            $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
            $objWriter->save('php://output');
            exit;
        }else{
            $msg = "El reporte no cuenta con registros.";
            $app->redirect('index.php?option=com_sasfe&view=reportes', $msg);
            $app->close();
        }

        // echo "fechaDel: ".$fechaDel."<br/>";
        // echo "fechaAl: ".$fechaAl."<br/>";
        // echo "<pre>";
        // // print_r($_POST);
        // print_r($colRegistros);
        // echo "</pre>";
        exit();
    }

    /**
    * Imp. 30/09/20
    * Descarga reporte de detalles de acciones por contacto
    */
    public function detalleAccionesContactos(){
        jimport('joomla.filesystem.file');
        jimport('joomla.filesystem.folder');
        $app = JFactory::getApplication();
        set_include_path(get_include_path() . PATH_SEPARATOR . '../../../Classes/');
        include_once JPATH_ADMINISTRATOR.DIRECTORY_SEPARATOR.'components'.DIRECTORY_SEPARATOR.'com_sasfe'.DIRECTORY_SEPARATOR.'common'.DIRECTORY_SEPARATOR.'Classes'.DIRECTORY_SEPARATOR.'PHPExcel.php';
        require_once JPATH_COMPONENT . '/helpers/sasfehp.php';
        // require_once(JPATH_COMPONENT.'/controllers/generarpdfs.php');
        // $ctrGralPdf = new SasfeControllerGenerarpdfs();
        $user = JFactory::getUser();
        $groups = JAccess::getGroupsByUser($user->id, true);
        JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));
        //leer el modelo correspondiente
        $model = JModelLegacy::getInstance('Contacto', 'SasfeModel');
        $modelGM = JModelLegacy::getInstance('Globalmodelsbk', 'SasfeModel');
        $arrDateTime = SasfehpHelper::obtDateTimeZone();
        $fechaDel = SasfehpHelper::conversionFecha(JRequest::getVar('filter_fechaDelAC'));
        $fechaAl = SasfehpHelper::conversionFecha(JRequest::getVar('filter_fechaAlAC'));
        $idGerente = (JRequest::getVar('nombreGteJoomlaVentas')>0)?JRequest::getVar('nombreGteJoomlaVentas'):-1;
        $idAgente = (JRequest::getVar('asig_agtventas2')>0)?JRequest::getVar('asig_agtventas2'):-1;
        //Obtener los registros
        $arrDatosExportar = SasfehpHelper::obtDetalleAccionesContactos($fechaDel, $fechaAl, $idGerente, $idAgente);

        /*echo "idGerente: ".$idGerente."<br/>";
        echo "idAgente: ".$idAgente."<br/>";
        echo "<pre>";
        // print_r($arrDatosExportar);
        print_r($_POST);
        echo "</pre>";
        exit();*/

        if(count($arrDatosExportar)>0){
           //Inicio de la creacion del reporte xls
            set_time_limit(0);
            date_default_timezone_set('America/Mexico_City');
            if (PHP_SAPI == 'cli')
                    die('Este archivo solo se puede ver desde un navegador web');
            // Se crea el objeto PHPExcel
            $objPHPExcel = new PHPExcel();
            // Se asignan las propiedades del libro
            $objPHPExcel->getProperties()->setCreator("Framelova") //Autor
                                        ->setLastModifiedBy("Framelova") //Ultimo usuario que lo modifico
                                        ->setTitle("Reporte Contactos")
                                        ->setSubject("Reporte Contactos")
                                        ->setDescription("Reporte Contactos")
                                        ->setKeywords("reporte")
                                        ->setCategory("Reporte Contactos");
            //Inicio imprimir cabecera
            $titulosColumnas = array('FECHA ALTA', 'GTE VENTAS', 'ASESOR', 'CONTACTO', 'ACCION', 'COMENTARIO');
            $totalTituloCol = count($titulosColumnas);
            $colA = 'A';
            $FilaTitulo = 1;
            for($i=1; $i<=$totalTituloCol; $i++){
                $objPHPExcel->getActiveSheet()->setCellValue($colA.$FilaTitulo, $titulosColumnas[$i-1]);
                $colA++;
            }
            $contLetra = "$colA"."1";
            //Fin imprimir cabecera
            //Inicio recorrido de informacion para imprimirla en cada celda
            if(count($arrDatosExportar)>0){
                $i=2;
                foreach($arrDatosExportar as $fila){
                     // $datosGteProspeccion = ($fila->gteProspeccionId!="") ?(array)SasfehpHelper::obtInfoUsuariosJoomla($fila->gteProspeccionId) :array();
                     // $datosGteVentas = ($fila->gteVentasId!="") ?(array)SasfehpHelper::obtInfoUsuariosJoomla($fila->gteVentasId) :array();
                     // $datosProspectador = ($fila->altaProspectadorId!="") ?(array)SasfehpHelper::obtInfoUsuariosJoomla($fila->altaProspectadorId) :array();
                     // $datosAsesor = ($fila->agtVentasId!="") ?(array)SasfehpHelper::obtInfoUsuariosJoomla($fila->agtVentasId) :array();

                     $objPHPExcel->setActiveSheetIndex(0)
                            ->setCellValue('A'.$i,  SasfehpHelper::conversionFechaF2($fila->fechaAlta))
                            ->setCellValue('B'.$i,  $fila->gteVentas)
                            ->setCellValue('C'.$i,  $fila->agtVentas)
                            ->setCellValue('D'.$i,  $fila->contacto)
                            ->setCellValue('E'.$i,  $fila->accion)
                            ->setCellValue('F'.$i,  $fila->comentario)
                        ;
                    $i++;
                }
            }
            //Fin recorrido de informacion para imprimirla en cada celda
            //Inicio estilos
            $estiloTituloColumnas = array(
                    'font' => array(
                        'name'=> 'Arial',
                        'size'=>8,
                        'bold'=> false,
                        'color'=> array(
                            'rgb' => '000000'
                        )
                    ),
                    'alignment' =>  array(
                            'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                            'vertical'   => PHPExcel_Style_Alignment::VERTICAL_CENTER,
                            'wrap'          => FALSE
            ));
            $estiloInformacion = new PHPExcel_Style();
            $estiloInformacion->applyFromArray(
            array(
                    'font' => array(
                        'name'=>'Arial',
                        'size'=>8,
                        'color'=> array(
                            'rgb' => '000000'
                    )
                )
            ));
            //Fin de estilos
            $objPHPExcel->getActiveSheet()->getStyle('A1:G1')->applyFromArray($estiloTituloColumnas);
            $objPHPExcel->getActiveSheet()->setSharedStyle($estiloInformacion, "A2:G1");
            for($j = 'A'; $j <=$contLetra; $j++){
                    $objPHPExcel->setActiveSheetIndex(0)
                            ->getColumnDimension($j)->setAutoSize(TRUE);
            }
            // Se asigna el nombre a la hoja
            $objPHPExcel->getActiveSheet()->setTitle('Reporte Acciones Contactos');
            // Se activa la hoja para que sea la que se muestre cuando el archivo se abre
            $objPHPExcel->setActiveSheetIndex(0);
            // Inmovilizar paneles
            //$objPHPExcel->getActiveSheet(0)->freezePane('A4');
            $objPHPExcel->getActiveSheet(0)->freezePaneByColumnAndRow(0,2);
            //Setear nombre del archivo
            $fechaDescarga = "_".date("d-m-Y")."_".time();
            $nombreArchivo = "ReporteAccionesContactos".$fechaDescarga.".xls";
            header('Content-Type: application/vnd.ms-excel');
            header('Content-Disposition: attachment;filename="'.$nombreArchivo.'"');
            header('Cache-Control: max-age=0');
            $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
            $objWriter->save('php://output');
            exit;
        }else{
            $msg = "El reporte no cuenta con registros.";
            $app->redirect('index.php?option=com_sasfe&view=reportes', $msg);
            $app->close();
        }

        // echo "fechaDel: ".$fechaDel."<br/>";
        // echo "fechaAl: ".$fechaAl."<br/>";
        // echo "<pre>";
        // // print_r($_POST);
        // print_r($colRegistros);
        // echo "</pre>";
        exit();
    }
}

?>
