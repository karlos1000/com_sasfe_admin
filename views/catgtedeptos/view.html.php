<?php

defined('_JEXEC') or die;

class  SasfeViewCatgtedeptos extends JViewLegacy
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

        JViewLegacy::loadHelper('sasfehp');
        
        $this->user = JFactory::getUser();                
        //obtiene grupo/s por id de usuario 
        $this->groups = JAccess::getGroupsByUser($this->user->id, true); 

        //Permiso para administradores y directores
        if(in_array("10", $this->groups) || in_array("8", $this->groups)){
            $this->permisoTotal = 1;
        }else{
            $this->permisoTotal = 0;
        }                                                
        
        //Permiso para usuarios nominas
        if(in_array("10", $this->groups) || in_array("8", $this->groups) || in_array("14", $this->groups)){
            $this->permisoNomina = 1;
        }else{
            $this->permisoNomina = 0;
        }
        
        $this->permisoGlobal = ($this->permisoTotal==$this->permisoNomina) ? true : false;
        
        $this->addToolBar();                
        $this->setDocument();
		parent::display($tpl);                
	}
        
        protected function addToolBar() 
        {
            $document = JFactory::getDocument();
            $document->addStyleDeclaration('.icon-48-catgtedeptos {background-image: url(../media/com_sasfe/images/catgtedeptos.png);}');                               
            JToolBarHelper::title(JText::_("Gerentes Ventas - Departamentos"), 'catgtedeptos');            
            // JToolBarHelper::deleteList('', 'catgenericos.delete');	
            JToolBarHelper::editList('catgtedepto.edit');                       
            JToolBarHelper::addNew('catgtedepto.add');              
            JToolBarHelper::spacer();
            JToolBarHelper::cancel('catgtedeptos.cancel');
            //submenu            
            JSubMenuHelper::addEntry('Escritorio', 'index.php?option=com_sasfe', false); 
            if($this->permisoGlobal==true){                
                JSubMenuHelper::addEntry('Cat&aacute;logos', 'index.php?option=com_sasfe&view=catalogos', false); 
                JSubMenuHelper::addEntry('Reportes', 'index.php?option=com_sasfe&view=reportes', false);
                JSubMenuHelper::addEntry('Log de Accesos', 'index.php?option=com_sasfe&view=logaccesos', false);
            }
        }
                
        protected function setDocument() 
        {
            jimport('joomla.environment.uri');        
            $document = JFactory::getDocument();                                
            $document->setTitle(JText::_("Gerentes Ventas - Departamentos"));
            $document->addStyleSheet(JURI::root().'media/com_sasfe/css/style.css');                            
            $document->addScript(JURI::root().'media/com_sasfe/js/jquery.js');     
            $document->addScript(JURI::root().'media/com_sasfe/js/jquery.validate.js');                 
            $document->addScript(JURI::root().'media/com_sasfe/js/function.js'); 
            $document->addScript(JURI::root().'administrator/components/com_sasfe/views/catgtedeptos/submitbutton.js'); 
        }
}
