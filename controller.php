<?php

/** 
 * fecha: 11-08-14
 * company: Framelova
 * @author Carlos
 */

// No direct access to this file
defined('_JEXEC') or die('Restricted access');
 
// import Joomla controller library
jimport('joomla.application.component.controller');
 
/**
 * Controlador general del componente Sasfe
 */

class SasfeController extends JControllerLegacy{
     
        function display($cachable = false, $urlparams = false) 
        {
                // set default view if not set
                $input = JFactory::getApplication()->input;
                $input->set('view', $input->getCmd('view', 'Sasfe'));
 
                // call parent behavior
                parent::display($cachable);
        }        
}

?>
