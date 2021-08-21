<?php
/** 
 * fecha: 11-08-14
 * company: Framelova
 * @author Carlos
 */
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
// import Joomla controlleradmin library
jimport('joomla.application.component.controlleradmin');
 
/*
 * Controlador Sasfe
 */
class SasfeControllerSasfe extends JControllerAdmin
{
    function cancel()
    {    
     $this->setRedirect( 'index.php?option=com_sasfe');
    }        
}

?>