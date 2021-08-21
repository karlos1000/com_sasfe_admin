<?php

defined('_JEXEC') or die;

class  SasfeViewCatcostoextras extends JViewLegacy
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
                $this->idCatGen = JRequest::getVar('id_cat');  
                JViewLegacy::loadHelper('sasfehp');                    
                $this->catalogo = SasfehpHelper::obtCatGenericoPorId($this->idCatGen);
                                                
                $this->addToolBar();                
                $this->setDocument();
                
                
		parent::display($tpl);                
	}
        
        protected function addToolBar() 
        {                     
            $document = JFactory::getDocument();            
            $document->addStyleDeclaration('.icon-48-generico {background-image: url(../media/com_sasfe/images/catalogo.png);}');                
            JToolBarHelper::title(JText::_($this->catalogo[0]->nombre), 'generico');            
            JToolBarHelper::deleteList('', 'catcostoextras.delete');	
            JToolBarHelper::editList('catcostoextra.edit');                       
            JToolBarHelper::addNew('catcostoextra.add');              
            JToolBarHelper::spacer();
            JToolBarHelper::cancel('catcostoextras.cancel');            
            //submenu
            JSubMenuHelper::addEntry('Escritorio', 'index.php?option=com_sasfe', false); 
            // JSubMenuHelper::addEntry('Fraccionamientos', 'index.php?option=com_sasfe&view=catfraccionamientos', false);                
            // JSubMenuHelper::addEntry('Departamentos', 'index.php?option=com_sasfe&view=catdepartamentos', false);              
            JSubMenuHelper::addEntry('Cat&aacute;logos', 'index.php?option=com_sasfe&view=catalogos', false); 
            JSubMenuHelper::addEntry('Reportes', 'index.php?option=com_sasfe&view=reportes', false);
            JSubMenuHelper::addEntry('Log de Accesos', 'index.php?option=com_sasfe&view=logaccesos', false);
        }
                
        protected function setDocument() 
        {
            jimport('joomla.environment.uri');        
            $document = JFactory::getDocument();                                
            $document->setTitle(JText::_($this->catalogo[0]->nombre));                                
            $document->addStyleSheet(JURI::root().'media/com_sasfe/css/style.css');                            
            $document->addScript(JURI::root().'media/com_sasfe/js/jquery.js');     
            $document->addScript(JURI::root().'media/com_sasfe/js/jquery.validate.js');                 
            $document->addScript(JURI::root().'media/com_sasfe/js/function.js'); 
            $document->addScript(JURI::root().'administrator/components/com_sasfe/views/catcostoextras/submitbutton.js'); 
        }
}
