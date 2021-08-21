<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted Access');
jimport('joomla.application.component.controllerform');

class SasfeControllerCattipoeventos extends JControllerForm {
      
    function cancel($key=NULL)
    {         
        $this->setRedirect( 'index.php?option=com_sasfe&view=catalogos');        
    }
    
    function delete(){        
         // Check for request forgeries
        JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));                
        $idsTipoEvento = JRequest::getVar('cid', array(), '', 'array');                
        // Sanitize the input
        JArrayHelper::toInteger($idsTipoEvento);
        
        // $pathImgs = JPATH_SITE.'/media/com_sasfe/upload_files';  
        $model = JModelLegacy::getInstance('catTipoeventos', 'SasfeModel'); 
        $result = $model->removerPorIdEvento($idsTipoEvento);             
        //echo '<pre>'; print_r($result); echo '</pre>';
                                
       if(count($result['resultDel'])>0){                                 
            // //borra la imagen pricipal del fraccionamiento
            // foreach($result['arrImgs'] as $itemImg){                
            //     if(file_exists($pathImgs.'/'.$itemImg)){
            //             unlink ($pathImgs.'/'.$itemImg); 
            //             //echo 'se ha eliminado: ' . $pathImgs.'/'.$itemImg . '</br>';
            //     }
            // }
            $msn = (count($result['resultDel'])>1) ? 'Registros eliminados' : 'Registro eliminado';
            $msn .= " id/s (".implode(',', $result['resultDel']).")";

            if(count($result['noBorrados'])>0){
               $msn .= "<br/>Registro/s no eliminado/s id/s (".implode(',', $result['noBorrados']).")";
            }
            $text = JText::sprintf($msn);                
            $this->setRedirect('index.php?option=com_sasfe&view=catTipoeventos', $msn);
        }
        else{                    
            $text = JText::sprintf('Registro/s no eliminado');
            if(count($result['noBorrados'])>0){
               $text .= " id/s (".implode(',', $result['noBorrados']).")";
            }            
            $this->setRedirect('index.php?option=com_sasfe&view=catTipoeventos', $text);
        }
                       
    }         
    
}

?>
