<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted Access');
jimport('joomla.application.component.controllerform');

class SasfeControllerSmsenviospromociones extends JControllerForm {
    
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
        // $nombreGteVenta = "";
        // if(isset($user->name)){
        //     $nombreGteVenta = $user->name;
        // }
        $grupoUsuarioId = end($groups);        
                
        // Check for request forgeries
        JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));                
        $fechaDel = (JRequest::getVar('filter_fechaDel')!='') ?SasfehpHelper::conversionFecha(JRequest::getVar('filter_fechaDel')) :''; 
        $fechaAl = (JRequest::getVar('filter_fechaAl')!='') ?SasfehpHelper::conversionFecha(JRequest::getVar('filter_fechaAl')) :'';         
        $idsProspectos = (JRequest::getVar('idsProspectos')!='') ? JRequest::getVar('idsProspectos') : '';
        $agtventas = (JRequest::getVar('agtventas')!='') ? JRequest::getVar('agtventas') : 0;
        $estatus = (JRequest::getVar('estatus')!='') ? JRequest::getVar('estatus') : 0;
        $nombreEstatus = (JRequest::getVar('nombreEstatus')!='') ? '('.JRequest::getVar('nombreEstatus').')' : '(Sin estatus)';
        $mensaje = (JRequest::getVar('mensaje')!='') ? JRequest::getVar('mensaje') : '';
        // $mensajeId = (JRequest::getVar('preconfMsnId')!='') ? JRequest::getVar('preconfMsnId') : ''; //No se ocupa       
        if($grupoUsuarioId==10){
            $nombreAgtVentas = $user->name;
            $tipoProceso = 2; //referidos y promociones
        }else{
        $datosAgtVentas = SasfehpHelper::obtSelectInactivoAP($agtventas);
        $nombreAgtVentas = "";
        if(isset($datosAgtVentas[0]->nombre)){
            $nombreAgtVentas = $datosAgtVentas[0]->nombre;
            }
            $tipoProceso = 1; //promociones
        }

        // echo "fechaDel: ".$fechaDel.'<br/>';
        // echo "fechaAl: ".$fechaAl.'<br/>';
        // echo "idsProspectos: ".$idsProspectos.'<br/>';
        // echo "agtventas: ".$agtventas.'<br/>';
        // echo "estatus: ".$estatus.'<br/>';
        // echo "nombreEstatus: ".$nombreEstatus.'<br/>';
        // echo "mensaje: ".$mensaje.'<br/>';
        // echo "nombreAgtVentas: ".$nombreAgtVentas.'<br/>';
        // echo "<br/>";
        // echo "grupoUsuarioId: ".$grupoUsuarioId.'<br/>';
        // echo "usuarioId: ".$usuarioId.'<br/>';

        // exit();        

        $msgRestarCreditos = "";
        $msgResp = "No fue posible enviar mensaje, intentar m&aacute;s tarde.";
        //Logica para enviar mensaje 
        $arrIdsProspectos = explode(",", $idsProspectos);
        if(count($arrIdsProspectos)>0){            
            $arrSMSEnviados = array();
            $arrSMSCorreos = array(); //Arreglo de correos
            
            //>>>Salvar historial del mensaje en la tabla de envios
            $comentario = "El usuario ".$nombreAgtVentas." env&iacute;o el mensaje a un total de (".count($arrIdsProspectos).") clientes, con el estatus ".$nombreEstatus." el d&iacute;a ".$fechaHora2;
            $mensajeHistId = SasfehpHelper::salvarHistorialSMS(2, $grupoUsuarioId, $usuarioId, $mensaje, $comentario, $fechaHora);

            foreach ($arrIdsProspectos as $elemP){
                $arrElemP = explode("|", $elemP);
                $celularObt = $arrElemP[1];                
                $datoClienteId = $arrElemP[0]; //Implementado

                $resSMS = SasfehpHelper::enviarSMS($mensaje, $celularObt);
                // echo $resSMS.'<br/>';
                if($resSMS==true){
                    $arrSMSEnviados[] = $celularObt;
                    //Agregar Historial por cada cliente que se le envio el mensaje                
                    SasfehpHelper::salvarHistorialClientesSMS($usuarioId, $agtventas, $datoClienteId, 2, $mensajeHistId, $fechaHora);
                    //Salvar correo del cliente
                    if(isset($arrElemP[3]) && $arrElemP[3]!=""){
                        $arrSMSCorreos[] = strtolower(trim($arrElemP[3])); //Implementado 14/08/18
                    }
                }                
                //Agregar Historial por cada cliente que se le envio el mensaje                
                // SasfehpHelper::salvarHistorialClientesSMS($usuarioId, $agtventas, $datoClienteId, 2, $mensajeHistId, $fechaHora);
                //Salvar correo del cliente
                // if(isset($arrElemP[3]) && $arrElemP[3]!=""){
                //     $arrSMSCorreos[] = strtolower(trim($arrElemP[3])); //Implementado 14/08/18
                // }
            }

            //Verificar que al menos mando un mensaje, para retornar mensaje
            if(count($arrSMSEnviados)>0){                
                //Restar creditos de la persona que envio el mensaje (agentes)
                $resRestarCreditos = SasfehpHelper::restarCreditoUsuariosSMS(count($arrIdsProspectos), $tipoProceso, $usuarioId, $fechaHora); //tipoProceso (1)= Promociones  //TODO: Verificar si funciona
                // $msgRestarCreditos = ($resRestarCreditos==true)?", Se restaron: ".count($arrIdsProspectos)." cr&eacute;ditos del asesor: ".$nombreAgtVentas:"";  
                $msgResp = "El mensaje fue enviado correctamente.";
                //Enviar correo
                if(count($arrSMSCorreos)>0){
                    $arrCorreos = array(implode(",",$arrSMSCorreos));
                    $body = '<div>'.$mensaje.'</div>';
                    $body .= '<br/><div style="font-size:13px;">Este correo es generado autom&aacute;ticamente, favor de no responder</div><br/>';
                    SasfehpHelper::notificarPorCorreo("Mensaje Esphabit", $arrCorreos, $body);
                }
            }
            
            //Restar creditos de la persona que envio el mensaje (agentes)
            // $resRestarCreditos = SasfehpHelper::restarCreditoUsuariosSMS(count($arrIdsProspectos), $tipoProceso, $usuarioId, $fechaHora); //tipoProceso (1)= Promociones  //TODO: Verificar si funciona
            // $msgRestarCreditos = ($resRestarCreditos==true)?", Se restaron: ".count($arrIdsProspectos)." cr&eacute;ditos del asesor: ".$nombreAgtVentas:"";  
            //Enviar correo
            // if(count($arrSMSCorreos)>0){
            //     $arrCorreos = array(implode(",",$arrSMSCorreos));
            //     $body = '<div>'.$mensaje.'</div>';
            //     $body .= '<br/><div style="font-size:13px;">Este correo es generado autom&aacute;ticamente, favor de no responder</div><br/>';
            //     SasfehpHelper::notificarPorCorreo("Cambio de estatus", $arrCorreos, $body);
            // }                

            // echo "<pre>";
            // print_r($arrIdsProspectos);
            // echo "</pre>";
        }

        // exit();
        //Regresar a la vista informando de lo sucedido        
        $this->setRedirect( 'index.php?option=com_sasfe&view=smsenviospromociones',$msgResp .$msgRestarCreditos);    
    }
    
    // Obtener los prospectos segun el filtro
    public function obtProspectosSMSSegunFiltro(){
        jimport('joomla.filesystem.file');
        require_once(JPATH_COMPONENT.'/helpers/sasfehp.php' );        
        $idAsesor = ($_POST['idAsesor']!='') ?($_POST['idAsesor']==0)?'':$_POST['idAsesor'] :'';
        $idEstatus = ($_POST['idEstatus']!='') ?$_POST['idEstatus'] :'';  //deshabilitado
        $fechaDel = ($_POST['fechaDel']!='') ?SasfehpHelper::conversionFecha($_POST['fechaDel']) :''; 
        $fechaAl = ($_POST['fechaAl']!='') ?SasfehpHelper::conversionFecha($_POST['fechaAl']) :'';

        $tipoProceso = 2; //promociones
        //Obtener datos del usuario asesor por su id tabla datos catalogo
        $datosAsesor = SasfehpHelper::obtSelectInactivoAP($idAsesor);
        $result = array("success"=>false);
        if(isset($datosAsesor[0]->usuarioIdJoomla) && $datosAsesor[0]->usuarioIdJoomla!=""){
            // $datos = SasfehpHelper::obtUsuariosSMSPorFiltro($idAsesor, $idEstatus, $fechaDel, $fechaAl, $tipoProceso);
            $idAsesor = $datosAsesor[0]->usuarioIdJoomla;
            $datos = SasfehpHelper::obtUsuariosSMSPorFiltroPromo($idAsesor, $fechaDel, $fechaAl, $tipoProceso);
            if(count($datos)>0){
        $result = array("success"=>true, "datos"=>$datos);
            }
        }
        // $datos = SasfehpHelper::obtUsuariosSMSPorFiltro($idAsesor, $idEstatus, $fechaDel, $fechaAl, $tipoProceso);        
        $this->retornaJson($result);
    }    

    private function retornaJson($re){
        JFactory::getDocument()->setMimeEncoding( 'application/json' );
        JResponse::setHeader('Content-Disposition','attachment;filename="progress-report-results.json"');    
        // echo $callBack.'(' . json_encode($re, JSON_UNESCAPED_UNICODE) . ');';  
        echo json_encode($re, JSON_UNESCAPED_UNICODE);
        JFactory::getApplication()->close();     
    }
   
}

?>
