<?php
/** 
 * fecha: 19-11-13
 * company: company
 * @author Karlos
 */
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
// import Joomla view library
jimport('joomla.application.component.view');

class SasfeViewCatalogos extends JViewLegacy{
   
        /**
         * HolaMundo view display method
         * @return void
         */
        function display($tpl = null) 
        {                              
                // Get data from the model
                $items = $this->get('State');
                $items = $this->get('Items');
                $pagination = $this->get('Pagination');
 
                // Check for errors.
                if (count($errors = $this->get('Errors'))) 
                {
                        JError::raiseError(500, implode('<br />', $errors));
                        return false;
                }                
                // Assign data to the view
                $this->items = $items;
                $this->pagination = $pagination;
                JViewLegacy::loadHelper('sasfehp');                    
                $this->CatalogosGen = SasfehpHelper::obtTodosCatGenericos();
                
                $this->user = JFactory::getUser();                
                //obtiene grupo/s por id de usuario 
                $this->groups = JAccess::getGroupsByUser($this->user->id, true);  
                                                
                // Set the toolbar              
                $this->addToolBar();
                // Display the template
                parent::display($tpl);
                
                // Set the document
                $this->setDocument();
        }
        
        /**
         * Setting the toolbar
         */
        protected function addToolBar($total=null) 
        {
            jimport('joomla.environment.uri');        
            $document = JFactory::getDocument();        
            $document->addStyleDeclaration('.icon-48-catalogos {background-image: url(../media/com_sasfe/images/catalogos.png);}');                
            $document->addStyleSheet(JURI::root().'media/com_sasfe/css/style.css');
            $document->addScript(JURI::root().'media/com_sasfe/js/jquery.js');                                     
            $document->addScript(JURI::root().'media/com_sasfe/js/jquery.validate.js');     
            $document->addScript(JURI::root().'media/com_sasfe/js/function.js');  
            JToolBarHelper::title(JText::_('Cat&aacute;logos'),'catalogos');                                          
            JToolBarHelper::cancel('sasfe.cancel');                    
            //submenu
            if(in_array("12", $this->groups)){
            JSubMenuHelper::addEntry('Escritorio', 'index.php?option=com_sasfe', false);
		JSubMenuHelper::addEntry('Cat&aacute;logos', 'index.php?option=com_sasfe&view=catalogos', false);
                JSubMenuHelper::addEntry('Reportes', 'index.php?option=com_sasfe&view=reportes', false);                
            }else{
                JSubMenuHelper::addEntry('Escritorio', 'index.php?option=com_sasfe', false);
            JSubMenuHelper::addEntry('Cat&aacute;logos', 'index.php?option=com_sasfe&view=catalogos', true);                            
            JSubMenuHelper::addEntry('Reportes', 'index.php?option=com_sasfe&view=reportes', false);
            JSubMenuHelper::addEntry('Log de Accesos', 'index.php?option=com_sasfe&view=logaccesos', false);
            }
        }
        
        /**
         * Method to set up the document properties
         *
         * @return void
         */
        protected function setDocument() 
        {
                $document = JFactory::getDocument();
                $document->setTitle(JText::_('Catálogos'));
        }
}

?>
