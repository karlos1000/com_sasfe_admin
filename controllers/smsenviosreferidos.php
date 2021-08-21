<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted Access');
jimport('joomla.application.component.controllerform');

class SasfeControllerSmsenviosreferidos extends JControllerForm {
    
    function cancel($key=NULL)
    {         
        $this->setRedirect( 'index.php?option=com_sasfe');        
    }

    function sendMessage(){
        jimport('joomla.filesystem.file');
        require_once(JPATH_COMPONENT.'/helpers/sasfehp.php' );
        // require_once(JPATH_COMPONENT.'/controllers/generarpdfs.php');
        // $ctrGralPdf = new SasfeControllerGenerarpdfs();  

        $dateByZone = new DateTime("now", new DateTimeZone('America/Mexico_City') );
        $fechaHora = $dateByZone->format('Y-m-d H:i:s'); //fecha y hora
        $fechaHora2 = $dateByZone->format('d/m/Y H:i:s'); //fecha y hora formato 2
        
        $user = JFactory::getUser();            
        $groups = JAccess::getGroupsByUser($user->id, true);
        $usuarioId = $user->id;
        $nombreGteVenta = "";
        if(isset($user->name)){
            $nombreGteVenta = $user->name;
        }
        $grupoUsuarioId = end($groups);        
                
        // Check for request forgeries
        JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));
        //leer el modelo correspondiente
        // $model = JModelLegacy::getInstance('Prospecto', 'SasfeModel');
        // $modelGM = JModelLegacy::getInstance('Globalmodelsbk', 'SasfeModel');   
        // $arrDateTime = SasfehpHelper::obtDateTimeZone();
        
        $fechaDel = (JRequest::getVar('filter_fechaDel')!='') ?SasfehpHelper::conversionFecha(JRequest::getVar('filter_fechaDel')) :''; 
        $fechaAl = (JRequest::getVar('filter_fechaAl')!='') ?SasfehpHelper::conversionFecha(JRequest::getVar('filter_fechaAl')) :'';         
        $idsProspectos = (JRequest::getVar('idsProspectos')!='') ? JRequest::getVar('idsProspectos') : '';
        $agtventas = (JRequest::getVar('agtventas')!='') ? JRequest::getVar('agtventas') : 0;
        $estatus = (JRequest::getVar('estatus')!='') ? JRequest::getVar('estatus') : 0;
        $nombreEstatus = (JRequest::getVar('nombreEstatus')!='') ? '('.JRequest::getVar('nombreEstatus').')' : '(Sin estatus)';
        $mensaje = (JRequest::getVar('mensaje')!='') ? JRequest::getVar('mensaje') : '';        
        // $mensajeId = (JRequest::getVar('preconfMsnId')!='') ? JRequest::getVar('preconfMsnId') : ''; //No se ocupa       
        $datosAgtVentas = SasfehpHelper::obtSelectInactivoAP($agtventas);
        $nombreAgtVentas = "";
        if(isset($datosAgtVentas[0]->nombre)){
            $nombreAgtVentas = $datosAgtVentas[0]->nombre;
        }        
        //En caso de que envie direccion
        if($grupoUsuarioId==10){
            $tipoProceso = 2; //referidos y promociones
        }else{
            $tipoProceso = 0; //referidos
        }

        $msgRestarCreditos = "";
        $msgResp = "No fue posible enviar mensaje, intentar m&aacute;s tarde.";
        //Logica para enviar mensaje 
        $arrIdsProspectos = explode(",", $idsProspectos);
        if(count($arrIdsProspectos)>0){            
            $arrSMSEnviados = array();
            $arrSMSCorreos = array(); //Arreglo de correos
            
            //>>>Salvar historial del mensaje en la tabla de envios
            $comentario = "El usuario ".$nombreGteVenta." env&iacute;o el mensaje a un total de (".count($arrIdsProspectos).") clientes del asesor ".$nombreAgtVentas." con el estatus ".$nombreEstatus." el d&iacute;a ".$fechaHora2;
            $mensajeHistId = SasfehpHelper::salvarHistorialSMS(1, $grupoUsuarioId, $usuarioId, $mensaje, $comentario, $fechaHora);

            foreach ($arrIdsProspectos as $elemP){
                $arrElemP = explode("|", $elemP);
                $celularObt = $arrElemP[1];                
                $datoClienteId = $arrElemP[0]; //Implementado
                $resSMS = SasfehpHelper::enviarSMS($mensaje, $celularObt);
                // echo $resSMS.'<br/>';
                if($resSMS==true){
                    $arrSMSEnviados[] = $celularObt;
                    //Agregar Historial por cada cliente que se le envio el mensaje                
                    SasfehpHelper::salvarHistorialClientesSMS($usuarioId, $agtventas, $datoClienteId, 1, $mensajeHistId, $fechaHora);
                    //Salvar correo del cliente
                    if(isset($arrElemP[3]) && $arrElemP[3]!=""){
                        $arrSMSCorreos[] = $arrElemP[3]; //Implementado 15/08/18
                }                
                }
                //Agregar Historial por cada cliente que se le envio el mensaje                
                // SasfehpHelper::salvarHistorialClientesSMS($usuarioId, $agtventas, $datoClienteId, 1, $mensajeHistId, $fechaHora);
                //Salvar correo del cliente
                // if(isset($arrElemP[3]) && $arrElemP[3]!=""){
                //     $arrSMSCorreos[] = $arrElemP[3]; //Implementado 15/08/18
                // }
            }

            //Verificar que al menos mando un mensaje, para retornar mensaje
            if(count($arrSMSEnviados)>0){                
                //Restar creditos de la persona que envio el mensaje (Gerentes)
                $resRestarCreditos = SasfehpHelper::restarCreditoUsuariosSMS(count($arrIdsProspectos), $tipoProceso, $usuarioId, $fechaHora); //tipoProceso (0)= Referidos  //TODO: Verificar si funciona
                // $msgRestarCreditos = ($resRestarCreditos==true)?", Se restaron: ".count($arrIdsProspectos)." cr&eacute;ditos del gte: ".$nombreGteVenta:"";  

                // $msgResp = "El mensaje fue enviado correctamente a los n&acute;meros ". implode(",", $arrSMSEnviados);            
                $msgResp = "El mensaje fue enviado correctamente.";
                //Enviar correo
                if(count($arrSMSCorreos)>0){
                    $arrCorreos = array(implode(",",$arrSMSCorreos));
                    $body = '<div>'.$mensaje.'</div>';
                    $body .= '<br/><div style="font-size:13px;">Este correo es generado autom&aacute;ticamente, favor de no responder</div><br/>';
                    SasfehpHelper::notificarPorCorreo("Mensaje Esphabit", $arrCorreos, $body);
                }
            }
            
            //Restar creditos de la persona que envio el mensaje (Gerentes)
            // $resRestarCreditos = SasfehpHelper::restarCreditoUsuariosSMS(count($arrIdsProspectos), $tipoProceso, $usuarioId, $fechaHora); //tipoProceso (0)= Referidos  //TODO: Verificar si funciona
            // $msgRestarCreditos = ($resRestarCreditos==true)?", Se restaron: ".count($arrIdsProspectos)." cr&eacute;ditos del gte: ".$nombreGteVenta:"";  
            //Enviar correo
            // if(count($arrSMSCorreos)>0){
            //     $arrCorreos = array(implode(",",$arrSMSCorreos));
            //     $body = '<div>'.$mensaje.'</div>';
            //     $body .= '<br/><div style="font-size:13px;">Este correo es generado autom&aacute;ticamente, favor de no responder</div><br/>';
            //     SasfehpHelper::notificarPorCorreo("Cambio de estatus", $arrCorreos, $body);
            // }

            // echo "<pre>";
            // print_r($arrIdsProspectos);
            // print_r($arrSMSCorreos);
            // echo "</pre>";
        }

        // exit();
        //Regresar a la vista informando de lo sucedido        
        $this->setRedirect( 'index.php?option=com_sasfe&view=smsenviosreferidos',$msgResp .$msgRestarCreditos);

        // echo "fechaDel: ".$fechaDel .'<br/>';
        // echo "fechaAl: ".$fechaAl .'<br/>';
        // echo "idsProspectos: ".$idsProspectos .'<br/>';
        // echo "agtventas: ".$agtventas .'<br/>';
        // echo "estatus: ".$estatus .'<br/>';
        // echo "mensaje: ".$mensaje .'<br/>';
    }
    
    // Obtener los prospectos segun el filtro
    public function obtProspectosSMSSegunFiltro(){
        jimport('joomla.filesystem.file');
        require_once(JPATH_COMPONENT.'/helpers/sasfehp.php' );        
        $idAsesor = ($_POST['idAsesor']!='') ?($_POST['idAsesor']==0)?'':$_POST['idAsesor'] :'';
        $idEstatus = ($_POST['idEstatus']!='') ?$_POST['idEstatus'] :'';
        $fechaDel = ($_POST['fechaDel']!='') ?SasfehpHelper::conversionFecha($_POST['fechaDel']) :''; 
        $fechaAl = ($_POST['fechaAl']!='') ?SasfehpHelper::conversionFecha($_POST['fechaAl']) :'';
        $idFracc = ($_POST['idFracc']!='') ?$_POST['idFracc'] :'';

        $tipoProceso = 1; //referido
        $datos = SasfehpHelper::obtUsuariosSMSPorFiltro($idAsesor, $idEstatus, $fechaDel, $fechaAl, $tipoProceso, $idFracc);
        // $datos = SasfehpHelper::obtUsuariosSMSPorFiltro("40", "87", "2015-03-09", "2015-04-10");

        $result = array("success"=>true, "datos"=>$datos);
        $this->retornaJson($result);
    }    

    private function retornaJson($re){
        JFactory::getDocument()->setMimeEncoding( 'application/json' );
        JResponse::setHeader('Content-Disposition','attachment;filename="progress-report-results.json"');    
        // echo $callBack.'(' . json_encode($re, JSON_UNESCAPED_UNICODE) . ');';  
        echo json_encode($re, JSON_UNESCAPED_UNICODE);
        JFactory::getApplication()->close(); 

        // $app = JFactory::getApplication();
        // echo new JResponseJson($result);        
        // $app->close();
    }

    /*
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
    */
}

?>
