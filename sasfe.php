<?php
/** 
 * fecha: 11-08-14
 * company: Framelova
 * @author Carlos
 */

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

// Access check.
if (!JFactory::getUser()->authorise('core.manage', 'com_sasfe')) {
	return JError::raiseWarning(404, JText::_('JERROR_ALERTNOAUTHOR'));
}

// import joomla controller library
jimport('joomla.application.component.controller');

// Set some global property
$document = JFactory::getDocument();
$document->addStyleSheet('../media/com_sasfe/css/style.css');
$document->addStyleDeclaration('.icon-48-sasfe {background-image: url(../media/com_sasfe/images/sistema.png);}');

// Get an instance of the controller prefixed by Sasfe
$controller = JControllerLegacy::getInstance('Sasfe');
 
// Get the task
$jinput = JFactory::getApplication()->input;
$task = $jinput->get('task', "", 'STR' );
 
// Perform the Request task
$controller->execute($task);

// Redirect if set by the controller
$controller->redirect();

?>