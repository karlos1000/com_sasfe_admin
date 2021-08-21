<?php

defined('_JEXEC') or die;

class  SasfeViewCatgenericos extends JViewLegacy
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
            switch ($this->idCatGen) {
                case 1: $img = 'gerentes_venta.png'; break;            
                case 2: $img = 'titulacion.png'; break;            
                case 3: $img = 'asesores.png'; break;            
                case 4: $img = 'prospectadores.png'; break;            
                case 5: $img = 'estatus_vivienda.png'; break;            
                case 6: $img = 'motivos_cancelacion.png'; break;            
                case 7: $img = 'tipos_credito.png'; break;                                    
            } 
                    
            $document = JFactory::getDocument();            
            $document->addStyleDeclaration('.icon-48-generico {background-image: url(../media/com_sasfe/images/'.$img.');}');                
            JToolBarHelper::title(JText::_($this->catalogo[0]->nombre), 'generico');            
            JToolBarHelper::deleteList('', 'catgenericos.delete');	
            JToolBarHelper::editList('catgenerico.edit');                       
            JToolBarHelper::addNew('catgenerico.add');              
            JToolBarHelper::spacer();
            JToolBarHelper::cancel('catgenericos.cancel');            
            //submenu            
            JSubMenuHelper::addEntry('Escritorio', 'index.php?option=com_sasfe', false); 
            if($this->permisoGlobal==true){
                // JSubMenuHelper::addEntry('Fraccionamientos', 'index.php?option=com_sasfe&view=catfraccionamientos', false);                
                // JSubMenuHelper::addEntry('Departamentos', 'index.php?option=com_sasfe&view=catdepartamentos', false);  
                JSubMenuHelper::addEntry('Cat&aacute;logos', 'index.php?option=com_sasfe&view=catalogos', false); 
                JSubMenuHelper::addEntry('Reportes', 'index.php?option=com_sasfe&view=reportes', false);
                JSubMenuHelper::addEntry('Log de Accesos', 'index.php?option=com_sasfe&view=logaccesos', false);
            }
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
            $document->addScript(JURI::root().'administrator/components/com_sasfe/views/catgenericos/submitbutton.js'); 
        }
}
