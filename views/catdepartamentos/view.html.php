<?php

defined('_JEXEC') or die;

class  SasfeViewCatdepartamentos extends JViewLegacy
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
            $document->addStyleDeclaration('.icon-48-departamento {background-image: url(../media/com_sasfe/images/departamentos.png);}');                
            JToolBarHelper::title(JText::_('Cat&aacute;logo departamentos'), 'departamento');            
            JToolBarHelper::deleteList('', 'catdepartamentos.delete');	
            JToolBarHelper::editList('catdepartamento.edit');                       
            JToolBarHelper::addNew('catdepartamento.add');              
            JToolBarHelper::spacer();
            JToolBarHelper::cancel('catdepartamentos.cancel');            
            //submenu
            JSubMenuHelper::addEntry('Escritorio', 'index.php?option=com_sasfe', false); 
            JSubMenuHelper::addEntry('Cat&aacute;logos', 'index.php?option=com_sasfe&view=catalogos', false); 
            JSubMenuHelper::addEntry('Reportes', 'index.php?option=com_sasfe&view=reportes', false);
            JSubMenuHelper::addEntry('Log de Accesos', 'index.php?option=com_sasfe&view=logaccesos', false);
            // JSubMenuHelper::addEntry('Fraccionamientos', 'index.php?option=com_sasfe&view=catfraccionamientos', false);                
            // JSubMenuHelper::addEntry('Departamentos', 'index.php?option=com_sasfe&view=catdepartamentos', true);              
        }
                
        protected function setDocument() 
        {
            jimport('joomla.environment.uri');        
            $document = JFactory::getDocument();                                
            $document->setTitle(JText::_('Catálogo departamentos'));                                
            $document->addStyleSheet(JURI::root().'media/com_sasfe/css/style.css');                            
            $document->addScript(JURI::root().'media/com_sasfe/js/jquery.js');     
            $document->addScript(JURI::root().'media/com_sasfe/js/jquery.validate.js');                 
            $document->addScript(JURI::root().'media/com_sasfe/js/function.js'); 
            $document->addScript(JURI::root().'administrator/components/com_sasfe/views/catdepartamentos/submitbutton.js'); 
        }
}
