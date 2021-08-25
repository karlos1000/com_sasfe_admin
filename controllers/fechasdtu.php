<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted Access');
jimport('joomla.application.component.controllerform');

class SasfeControllerFechasdtu extends JControllerForm {

    function cancel()
    {
        $this->setRedirect( 'index.php?option=com_sasfe');
    }


    function dtuFechasMasivo(){
         // Check for request forgeries
	    // JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));
    	// $model = JModelLegacy::getInstance('prospectos', 'SasfeModel');
    	// $idsProspectos = JRequest::getVar('cid', array(), '', 'array');

    	echo "<pre>";
    	print_r($_POST);
    	echo "</pre>";
    	exit();
    }
}

?>
