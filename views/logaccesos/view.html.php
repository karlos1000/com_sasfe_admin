<?php

defined('_JEXEC') or die;

class  SasfeViewLogaccesos extends JViewLegacy
{
	protected $items;
	protected $pagination;
	protected $state; 
        protected $dataDpts;        

	/**
	 * Display the view
	 */
	public function display($tpl = null)
	{                        
            $this->items		= $this->get('Items');
            $this->pagination	= $this->get('Pagination');
            $this->state		= $this->get('State');                                            
                
            // Check for errors.
            if (count($errors = $this->get('Errors'))) {
                    JError::raiseError(500, implode("\n", $errors));
                    return false;
            }  
            
            $this->user = JFactory::getUser();            
            $this->groups = JAccess::getGroupsByUser($this->user->id, true);
            
            $this->addToolBar();                
            $this->setDocument();

            parent::display($tpl);                
	}
        
        protected function addToolBar() 
        {                     
            $document = JFactory::getDocument();            
            $document->addStyleDeclaration('.icon-48-logaccesos {background-image: url(../media/com_sasfe/images/log_accesos.png);}');                
            JToolBarHelper::title(JText::_('Log de accesos'), 'logaccesos');            
            
            //JToolBarHelper::spacer();
            JToolBarHelper::cancel('logaccesos.cancel');            
            //submenu
            JSubMenuHelper::addEntry('Escritorio', 'index.php?option=com_sasfe', false); 
            if(!in_array("11", $this->groups) && !in_array("19", $this->groups)){
            JSubMenuHelper::addEntry('Cat&aacute;logos', 'index.php?option=com_sasfe&view=catalogos', false);                            
                JSubMenuHelper::addEntry('Reportes', 'index.php?option=com_sasfe&view=reportes', false);
                JSubMenuHelper::addEntry('Log de Accesos', 'index.php?option=com_sasfe&view=logaccesos', true);
            }else{                                
                JSubMenuHelper::addEntry('Log de Accesos', 'index.php?option=com_sasfe&view=logaccesos', true);
                JSubMenuHelper::addEntry('Prospectos', 'index.php?option=com_sasfe&view=prospectos', false);
            }            
        }
                
        protected function setDocument() 
        {
            jimport('joomla.environment.uri');        
            $document = JFactory::getDocument();                                
            $document->setTitle(JText::_('Log de accesos'));                                
            $document->addStyleSheet(JURI::root().'media/com_sasfe/css/style.css');                            
            $document->addScript(JURI::root().'media/com_sasfe/js/jquery.js');     
            $document->addScript(JURI::root().'media/com_sasfe/js/jquery.validate.js');                 
            $document->addScript(JURI::root().'media/com_sasfe/js/function.js'); 
            $document->addScript(JURI::root().'administrator/components/com_sasfe/views/logaccesos/submitbutton.js'); 
        }
}
