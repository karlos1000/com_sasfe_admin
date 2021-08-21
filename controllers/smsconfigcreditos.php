<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted Access');
jimport('joomla.application.component.controllerform');

class SasfeControllerSmsconfigcreditos extends JControllerForm {
    
    function cancel($key=NULL)
    {         
        $this->setRedirect( 'index.php?option=com_sasfe');        
    }

    //Aumentar creditos usuarios 
    public function salvarCreditos(){
        jimport('joomla.filesystem.file');
        require_once(JPATH_COMPONENT.'/helpers/sasfehp.php' );
        $dateByZone = new DateTime("now", new DateTimeZone('America/Mexico_City') );
        $fechaHora = $dateByZone->format('Y-m-d H:i:s'); //fecha y hora

        $totalCredito = $_POST['totalCredito'];
        $tipoProceso = $_POST['tipoProceso'];
        $usuarioId = $_POST['usuarioId'];
        $creditoRestar = $_POST['creditoRestar'];
        $fechaCreacion = $fechaHora;
        $fechaActualizacion = $fechaHora;        
        // $datos = array("totalCredito"=>$totalCredito, "tipoProceso"=>$tipoProceso, "usuarioId"=>$usuarioId);

        //Verificar si ese usuario ya existe en la tabla si es asi solo actualiza de lo contrario crea el credito
        $result = array("success"=>false);
        $datoUsuario = SasfehpHelper::checkCreditoPorUsuarioIdSMS($usuarioId);
        if(count($datoUsuario)>0){ //Actualiza
            // salvarCreditoSMS($totalCredito, $tipoProceso, $usuarioId, $fechaCreacion);      
            $resp = SasfehpHelper::actualizarCreditoSMS($totalCredito, $tipoProceso, $usuarioId, $fechaActualizacion);  
            if($resp>0){
                $resRestar = SasfehpHelper::restarCreditoBolsaSMS($creditoRestar, $fechaActualizacion);
                $result = array("success"=>true, "creditosReales"=>$resRestar);
            }
        }else{ //Crea
            $resp = SasfehpHelper::salvarCreditoSMS($totalCredito, $tipoProceso, $usuarioId, $fechaCreacion);              
            if($resp>0){
                $resRestar = SasfehpHelper::restarCreditoBolsaSMS($creditoRestar, $fechaActualizacion);
                $result = array("success"=>true, "creditosReales"=>$resRestar);
            }
        }
                
        $this->retornaJson($result);
    }   

    //Salvar creditos de bolsa y automaticos
    public function salvarCreditosBolsaAutomaticos(){
        jimport('joomla.filesystem.file');
        require_once(JPATH_COMPONENT.'/helpers/sasfehp.php' );
        $dateByZone = new DateTime("now", new DateTimeZone('America/Mexico_City') );
        $fechaHora = $dateByZone->format('Y-m-d H:i:s'); //fecha y hora

        $totalCredito = $_POST['totalCredito'];
        $tipoProceso = $_POST['tipoProceso'];
        $idCredito = $_POST['idCredito'];
        $creditoRestar = $_POST['creditoRestar'];
        $fechaCreacion = $fechaHora;
        $fechaActualizacion = $fechaHora;
        // $datos = array("totalCredito"=>$totalCredito, "tipoProceso"=>$tipoProceso, "idCredito"=>$idCredito);

        $result = array("success"=>false);
        //Actualizar el credito
        $resp = SasfehpHelper::actualizarCreditoBolsaAutomaticosSMS($totalCredito, $tipoProceso, $idCredito, $fechaActualizacion);
        if($resp>0){
            //Preguntar si se trata de un automatico
            if($tipoProceso>0){
                $resRestar = SasfehpHelper::restarCreditoBolsaSMS($creditoRestar, $fechaActualizacion);
                $result = array("success"=>true, "creditosReales"=>$resRestar);
            }else{
                $result = array("success"=>true);
            }            
        }
        // echo $resp.'<br/>';        
        $this->retornaJson($result);
    }   

    //actualizar estatus activo/inactivo  por su id y tipo proceso
    public function actualizarActivoCredito(){
        jimport('joomla.filesystem.file');
        require_once(JPATH_COMPONENT.'/helpers/sasfehp.php' );
        $dateByZone = new DateTime("now", new DateTimeZone('America/Mexico_City') );
        $fechaHora = $dateByZone->format('Y-m-d H:i:s'); //fecha y hora        
        $tipoProceso = $_POST['tipoProceso'];
        $valor = $_POST['valor'];
        $idCredito = $_POST['idCredito'];
        $result = array("success"=>false);
        //Actualizar el credito
        $resp = SasfehpHelper::actualizarActivoCreditoSMS($tipoProceso, $valor, $idCredito);
        if($resp>0){
            $result = array("success"=>true);
        }
        // echo $resp.'<br/>';        
        $this->retornaJson($result);
    }   
    
    //Retornar solo json
    private function retornaJson($re){
        JFactory::getDocument()->setMimeEncoding( 'application/json' );
        JResponse::setHeader('Content-Disposition','attachment;filename="progress-report-results.json"');            
        echo json_encode($re, JSON_UNESCAPED_UNICODE);
        JFactory::getApplication()->close(); 
    }
}

?>
