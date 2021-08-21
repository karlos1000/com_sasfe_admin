<?php

defined('_JEXEC') or die;

class  SasfeViewListadodeptos extends JViewLegacy
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
            $this->dpts		= $this->get('DataDpts');                        

            // Check for errors.
            if (count($errors = $this->get('Errors'))) {
                    JError::raiseError(500, implode("\n", $errors));
                    return false;
            }
            $this->idFracc = JRequest::getVar('idFracc');            
            JViewLegacy::loadHelper('sasfehp');                                
                
            $this->user = JFactory::getUser();                                
            $this->groups = JAccess::getGroupsByUser($this->user->id, true);                                        
            
            //Permitir al gerente de venta ver todo los sembrados pero solo como lectura (grupo = 11) 
            if(in_array("8", $this->groups) || in_array("10", $this->groups) || in_array("12", $this->groups) || in_array("13", $this->groups) || in_array("14", $this->groups) || in_array("15", $this->groups) || in_array("16", $this->groups) || in_array("11", $this->groups) ){
                $this->permiso = true;
            }else{
                $this->permiso = false;
            }    
            
            if(in_array("10", $this->groups) || in_array("8", $this->groups)){
                $this->permisoCat = true;
            }else{
                $this->permisoCat = false;
            }  
            //Obtener nombre, imagen del fraccionamiento            
            $this->datosFracc = SasfehpHelper::obtDatosFraccPorId($this->idFracc); 
                        
            $this->addToolBar();                
            $this->setDocument();

            parent::display($tpl);                
	}
        
        protected function addToolBar() 
        {          
            $img = ($this->datosFracc->imagen!='') ? $this->datosFracc->imagen : 'img_default.png';
            $document = JFactory::getDocument();            
            $document->addStyleDeclaration('.icon-48-listadodeptos {background-image: url(../media/com_sasfe/upload_files/'.$img.');background-size: 48px 48px; }');                
            JToolBarHelper::title(JText::_('Listado de departamentos del fraccionamiento: ' .$this->datosFracc->nombre), 'listadodeptos');            
            
            //JToolBarHelper::spacer();
            JToolBarHelper::cancel('listadodeptos.cancel');            
            //submenu
            JSubMenuHelper::addEntry('Escritorio', 'index.php?option=com_sasfe', false); 
            if($this->permisoCat==true){ 
                JSubMenuHelper::addEntry('Cat&aacute;logos', 'index.php?option=com_sasfe&view=catalogos', false);                
                JSubMenuHelper::addEntry('Actualizar fechas DTU', 'index.php?option=com_sasfe&view=fechasdtu&idFracc='.$this->idFracc, false);                
            }
            //JSubMenuHelper::addEntry('Inventario de autos nuevos', 'index.php?option=com_sasfe&view=inventorycarsnew', true);          
        }
                
        protected function setDocument() 
        {
            jimport('joomla.environment.uri');        
            $document = JFactory::getDocument();                                
            $document->setTitle(JText::_('Fraccionamientos'));                                
            $document->addStyleSheet(JURI::root().'media/com_sasfe/css/style.css');                            
            $document->addScript(JURI::root().'media/com_sasfe/js/jquery.js');     
            $document->addScript(JURI::root().'media/com_sasfe/js/jquery.validate.js');                 
            $document->addScript(JURI::root().'media/com_sasfe/js/function.js'); 
            $document->addScript(JURI::root().'administrator/components/com_sasfe/views/listadodeptos/submitbutton.js'); 
        }
}
