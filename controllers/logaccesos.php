<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted Access');
jimport('joomla.application.component.controllerform');

class SasfeControllerLogaccesos extends JControllerForm {
    
    function cancel()
    {         
        $this->setRedirect( 'index.php?option=com_sasfe');        
    }
    
}

?>
