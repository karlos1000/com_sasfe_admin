<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted Access');
jimport('joomla.application.component.controllerform');

class SasfeControllerCatfraccionamientos extends JControllerForm {
      
    function cancel()
    {         
        $this->setRedirect( 'index.php?option=com_sasfe&view=catalogos');        
    }
    
    function delete(){        
         // Check for request forgeries
        JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));                
        $idsFracc = JRequest::getVar('cid', array(), '', 'array');                
        // Sanitize the input
        JArrayHelper::toInteger($idsFracc);        
        //print_r($idsCars);
        
        $pathImgs = JPATH_SITE.'/media/com_sasfe/upload_files';  
        $model = JModelLegacy::getInstance('catfraccionamientos', 'SasfeModel'); 
        $result = $model->removerPorIdFracc($idsFracc); 
             
        //echo '<pre>'; print_r($result); echo '</pre>';
                                
       if(count($result['resultDel'])>0){                                 
            //borra la imagen pricipal del fraccionamiento
            foreach($result['arrImgs'] as $itemImg){                
                if(file_exists($pathImgs.'/'.$itemImg)){
                        unlink ($pathImgs.'/'.$itemImg); 
                        //echo 'se ha eliminado: ' . $pathImgs.'/'.$itemImg . '</br>';
                }
            }                      
            $msn = (count($result['resultDel'])>1) ? 'Registros eliminados' : 'Registro eliminado';            
            $text = JText::sprintf($msn);                
            $this->setRedirect('index.php?option=com_sasfe&view=catfraccionamientos', $msn);                        
        }
        else{            
            $text = JText::sprintf('Registro no eliminado');
            $this->setRedirect('index.php?option=com_sasfe&view=catfraccionamientos', $text);                        
        }                        
                       
    }         
    
}

?>
