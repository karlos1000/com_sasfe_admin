<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted Access');
jimport('joomla.application.component.controllerform');

class SasfeControllerExpdigitales extends JControllerForm {

    function cancel($key=NULL)
    {
        $this->setRedirect( 'index.php?option=com_sasfe');
    }

    function delete(){
         // Check for request forgeries
        JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));
        $idsProspectos = JRequest::getVar('cid', array(), '', 'array');
        // Sanitize the input
        JArrayHelper::toInteger($idsProspectos);
        //print_r($idsProspectos);

        $model = JModelLegacy::getInstance('expdigitales', 'SasfeModel');
        $result = $model->removerProspectoPorId($idsProspectos);

        // echo '<pre>'; print_r($result); echo '</pre>';

       if(count($result['resultDel'])>0){
            $msn = (count($result['resultDel'])>1) ? 'Registros eliminados' : 'Registro eliminado';
            $text = JText::sprintf($msn);
            $this->setRedirect('index.php?option=com_sasfe&view=expdigitales', $text);
        }
        else{
            $text = JText::sprintf('Registro no eliminado');
            $this->setRedirect('index.php?option=com_sasfe&view=expdigitales', $text);
        }
    }

    //Borrar expdigitales repetidos
    function borrarProsRepetidos(){
         // Check for request forgeries
        JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));
        $model = JModelLegacy::getInstance('expdigitales', 'SasfeModel');
        $idsProspectos = JRequest::getVar('cid', array(), '', 'array');
        $borrar_prosp = JRequest::getVar('borrar_prosp');

        //Borrar varios expdigitales
        if($borrar_prosp==0){
            // Sanitize the input
            JArrayHelper::toInteger($idsProspectos);
            $result = $model->removerProspectoPorId($idsProspectos);
        }else{
            //Borrar uno por uno
            $result = $model->removerProspectoPorId(array($borrar_prosp));
        }

        if(count($result['resultDel'])>0){
            $msn = (count($result['resultDel'])>1) ? 'Registros eliminados' : 'Registro eliminado';
            $text = JText::sprintf($msn);
            $this->setRedirect('index.php?option=com_sasfe&view=expdigitales&layout=repetidos', $text);
        }
        else{
            $text = JText::sprintf('Registro no eliminado');
            $this->setRedirect('index.php?option=com_sasfe&view=expdigitales&layout=repetidos', $text);
        }
    }


    function cancelrepetidos()
    {
        $this->setRedirect( 'index.php?option=com_sasfe&view=expdigitales');
    }

    //Ir a la vista de expdigitales repetidos
    public function repetidos(){
        $this->setRedirect( 'index.php?option=com_sasfe&view=expdigitales&layout=repetidos');
    }

    //Ir a la vista de expdigitales no procesados
    public function noprocesados(){
        $this->setRedirect( 'index.php?option=com_sasfe&view=expdigitales&layout=noprocesados');
    }

}

?>
