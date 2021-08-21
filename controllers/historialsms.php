<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted Access');
jimport('joomla.application.component.controllerform');

class SasfeControllerHistorialsms extends JControllerForm {
      
    function cancel($key=NULL)
    {         
        $this->setRedirect( 'index.php?option=com_sasfe');
    }

    // function cancelSololectura()
    // {         
    //     $this->setRedirect( 'index.php?option=com_sasfe&view=historialsms');
    // }

    // public function atenderevento(){
    //     jimport('joomla.filesystem.file');
    //     require_once(JPATH_COMPONENT.'/helpers/sasfehp.php' );
    //     $arrDateTime = SasfehpHelper::obtDateTimeZone();
    //     // Check for request forgeries
    //     JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));
    //     //leer el modelo correspondiente
    //     $model = JModelLegacy::getInstance('Listareventos', 'SasfeModel');
        
    //     $ate_fechareg = JRequest::getVar('ate_fechareg');
    //     $fechaAtendido = SasfehpHelper::conversionFechaF4($ate_fechareg);
    //     $ate_comentario = JRequest::getVar('ate_comentario');   
    //     $idMovPros = JRequest::getVar('idMovPros');
    //     $opc = JRequest::getVar('hiddopc');
                        
    //     $resp = $model->marcarAtendido($fechaAtendido, $ate_comentario, $idMovPros);        
    //     $msg = JText::sprintf('Registro salvado correctamente.');
    //     $this->setRedirect('index.php?option=com_sasfe&view=listareventos&opc='.$opc, $msg);
    // }

    // function delete(){
    //      // Check for request forgeries
    //     JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));                
    //     $idsProspectos = JRequest::getVar('cid', array(), '', 'array');                
    //     // Sanitize the input
    //     // JArrayHelper::toInteger($idsProspectos);        
    //     //print_r($idsProspectos);
        
    //     $model = JModelLegacy::getInstance('listareventos', 'SasfeModel'); 
    //     $result = $model->removerProspectoPorId($idsProspectos);
             
    //     // echo '<pre>'; print_r($result); echo '</pre>';
                                
    //    if(count($result['resultDel'])>0){                      
    //         $msn = (count($result['resultDel'])>1) ? 'Registros eliminados' : 'Registro eliminado';            
    //         $text = JText::sprintf($msn);                
    //         $this->setRedirect('index.php?option=com_sasfe&view=listareventos', $text);                        
    //     }
    //     else{            
    //         $text = JText::sprintf('Registro no eliminado');                
    //         $this->setRedirect('index.php?option=com_sasfe&view=listareventos', $text);                        
    //     }
    // }
    
}

?>
