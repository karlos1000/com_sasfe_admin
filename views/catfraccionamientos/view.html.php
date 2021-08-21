<?php

defined('_JEXEC') or die;

class  SasfeViewCatfraccionamientos extends JViewLegacy
{
	protected $items;
	protected $pagination;
	protected $state;        

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

                $this->addToolBar();                
                $this->setDocument();
                
		parent::display($tpl);                
	}
        
        protected function addToolBar() 
        {                     
            $document = JFactory::getDocument();            
            $document->addStyleDeclaration('.icon-48-fraccionamiento {background-image: url(../media/com_sasfe/images/fraccionamientos.png);}');                
            JToolBarHelper::title(JText::_('Cat&aacute;logo fraccionamientos'), 'fraccionamiento');            
            JToolBarHelper::deleteList('', 'catfraccionamientos.delete');	
            JToolBarHelper::editList('catfraccionamiento.edit');                       
            JToolBarHelper::addNew('catfraccionamiento.add');              
            JToolBarHelper::spacer();
            JToolBarHelper::cancel('catfraccionamientos.cancel');            
            //submenu
            JSubMenuHelper::addEntry('Escritorio', 'index.php?option=com_sasfe', false); 
            JSubMenuHelper::addEntry('Cat&aacute;logos', 'index.php?option=com_sasfe&view=catalogos', false);
            JSubMenuHelper::addEntry('Reportes', 'index.php?option=com_sasfe&view=reportes', false);
            JSubMenuHelper::addEntry('Log de Accesos', 'index.php?option=com_sasfe&view=logaccesos', false);
            // //hijos
            // JSubMenuHelper::addEntry('  --- Fraccionamientos', 'index.php?option=com_sasfe&view=catfraccionamientos', true);                
            // JSubMenuHelper::addEntry('  --- Departamentos', 'index.php?option=com_sasfe&view=catdepartamentos', false);            
        }
                
        protected function setDocument() 
        {
            jimport('joomla.environment.uri');        
            $document = JFactory::getDocument();                                
            $document->setTitle(JText::_('Catálogo fraccionamientos'));                                
            $document->addStyleSheet(JURI::root().'media/com_sasfe/css/style.css');                            
            $document->addScript(JURI::root().'media/com_sasfe/js/jquery.js');     
            $document->addScript(JURI::root().'media/com_sasfe/js/jquery.validate.js');                 
            $document->addScript(JURI::root().'media/com_sasfe/js/function.js'); 
            $document->addScript(JURI::root().'administrator/components/com_sasfe/views/catfraccionamientos/submitbutton.js'); 
        }
}
