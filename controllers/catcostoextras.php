<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted Access');
jimport('joomla.application.component.controllerform');

class SasfeControllerCatcostoextras extends JControllerForm {
      
    function cancel()
    {         
        $this->setRedirect( 'index.php?option=com_sasfe&view=catalogos');        
    }
    
    function delete(){                
         // Check for request forgeries
        JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));                
        $idsCatCE = JRequest::getVar('cid', array(), '', 'array');                
        // Sanitize the input
        JArrayHelper::toInteger($idsCatCE);        
        //print_r($idsCatGen);
        $id_cat = JRequest::getVar('id_cat');                                         
        
        $model = JModelLegacy::getInstance('Catcostoextras', 'SasfeModel'); 
        $result = $model->removerCEPorIdDato($idsCatCE); 
             
        //echo '<pre>'; print_r($result); echo '</pre>';
                                
       if(count($result['resultDel'])>0){                                             
            $msn = (count($result['resultDel'])>1) ? 'Registros eliminados' : 'Registro eliminado';
            $text = JText::sprintf($msn);                
            $this->setRedirect('index.php?option=com_sasfe&view=catcostoextras&id_cat='.$id_cat, $msn);                        
       }else{                    
            $text = JText::sprintf('Registro no eliminado');                
            $this->setRedirect('index.php?option=com_sasfe&view=catcostoextras&id_cat='.$id_cat, $text);                        
        }                        
                       
    }         
    
}

?>
