<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted Access');
jimport('joomla.application.component.controllerform');

class SasfeControllerMotivos extends JControllerForm {

    function cancel($key=NULL)
    {
        $this->setRedirect( 'index.php?option=com_sasfe&view=catalogos');
    }

    function delete(){
         // Check for request forgeries
        JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));
        $idsMotivos = JRequest::getVar('cid', array(), '', 'array');
        // Sanitize the input
        JArrayHelper::toInteger($idsMotivos);

        $model = JModelLegacy::getInstance('motivos', 'SasfeModel');
        $result = $model->removerPorIdMotivo($idsMotivos);
        // echo '<pre>'; print_r($result); echo '</pre>';
        // exit();
       if(count($result['resultDel'])>0){
            $msn = (count($result['resultDel'])>1) ? 'Registros eliminados' : 'Registro eliminado';
            $msn .= " id/s (".implode(',', $result['resultDel']).")";

            if(count($result['noBorrados'])>0){
               $msn .= "<br/>Registro/s no eliminado/s id/s (".implode(',', $result['noBorrados']).")";
            }
            $text = JText::sprintf($msn);
            $this->setRedirect('index.php?option=com_sasfe&view=motivos', $msn);
        }
        else{
            $text = JText::sprintf('Registro/s no eliminado');
            if(count($result['noBorrados'])>0){
               $text .= " id/s (".implode(',', $result['noBorrados']).")";
            }
            $this->setRedirect('index.php?option=com_sasfe&view=motivos', $text);
        }

    }

}

?>
