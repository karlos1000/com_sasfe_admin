<?php

defined('_JEXEC') or die;

class  SasfeViewReportes extends JViewLegacy
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
            
            if(in_array("10", $this->groups) || in_array("8", $this->groups) || in_array("14", $this->groups)){
                $this->permiso = true;
            }else{
                $this->permiso = false;
            }
            
            $arrDepartamentos = array();
            $arrDatosGrals = array();
            $arrTodosDatos = array();
                                    
            JViewLegacy::loadHelper('sasfehp');                                
            //Obtener todos los fraccionamientos disponibles
            $this->Fracc = SasfehpHelper::obtTodosFraccionamientos(); 
                                    
            $this->addToolBar();                
            $this->setDocument();

            parent::display($tpl);                
	}
        
        protected function addToolBar() 
        {                     
            $document = JFactory::getDocument();            
            $document->addStyleDeclaration('.icon-48-reportes {background-image: url(../media/com_sasfe/images/catalogos.png);}');                
            JToolBarHelper::title(JText::_('Reportes'), 'reportes');            
            
            //JToolBarHelper::spacer();
            JToolBarHelper::cancel('reportes.cancel');            
            //submenu
            JSubMenuHelper::addEntry('Escritorio', 'index.php?option=com_sasfe', false); 
            if($this->permiso==true){
                JSubMenuHelper::addEntry('Cat&aacute;logos', 'index.php?option=com_sasfe&view=catalogos', false);                            
                JSubMenuHelper::addEntry('Reportes', 'index.php?option=com_sasfe&view=reportes', true);
                if(!in_array("12", $this->groups)){
                    JSubMenuHelper::addEntry('Log de Accesos', 'index.php?option=com_sasfe&view=logaccesos', false);
                }
            }
        }
                
        protected function setDocument() 
        {
            jimport('joomla.environment.uri');        
            $document = JFactory::getDocument();                                
            $document->setTitle(JText::_('Reporte'));                                
            $document->addStyleSheet(JURI::root().'media/com_sasfe/css/style.css');                            
            $document->addScript(JURI::root().'media/com_sasfe/js/jquery.js');     
            $document->addScript(JURI::root().'media/com_sasfe/js/jquery.validate.js');                 
            $document->addStyleSheet(JURI::root().'media/com_sasfe/css/jquery-ui.css');
            $document->addScript(JURI::root().'media/com_sasfe/js/jquery-ui.js');
            $document->addScript(JURI::root().'media/com_sasfe/js/spanish_datapicker.js'); 
            $document->addScript(JURI::root().'media/com_sasfe/js/function.js'); 
            $document->addScript(JURI::root().'administrator/components/com_sasfe/views/reportes/submitbutton.js'); 
        }
}
