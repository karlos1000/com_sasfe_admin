<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted Access');
jimport('joomla.application.component.controllerform');

class SasfeControllerHistorialprospecto extends JControllerForm {
      
    function cancel($key=NULL)
    {
        $this->setRedirect( 'index.php?option=com_sasfe&view=prospectos');
    }
    
    /*
    function delete(){        
         // Check for request forgeries
        JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));
        $idsProspectos = JRequest::getVar('cid', array(), '', 'array');                
        // Sanitize the input
        JArrayHelper::toInteger($idsProspectos);        
        //print_r($idsProspectos);
        
        $model = JModelLegacy::getInstance('prospectos', 'SasfeModel'); 
        $result = $model->removerProspectoPorId($idsProspectos);
             
        // echo '<pre>'; print_r($result); echo '</pre>';
                                
       if(count($result['resultDel'])>0){                      
            $msn = (count($result['resultDel'])>1) ? 'Registros eliminados' : 'Registro eliminado';            
            $text = JText::sprintf($msn);                
            $this->setRedirect('index.php?option=com_sasfe&view=prospectos', $text);                        
        }
        else{            
            $text = JText::sprintf('Registro no eliminado');                
            $this->setRedirect('index.php?option=com_sasfe&view=prospectos', $text);                        
        }
    }
    */
        
}

?>
