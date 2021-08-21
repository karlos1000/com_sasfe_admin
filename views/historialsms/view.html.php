<?php

defined('_JEXEC') or die;

class  SasfeViewHistorialsms extends JViewLegacy
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

        $this->user = JFactory::getUser();                
        //obtiene grupo/s por id de usuario 
        $this->groups = JAccess::getGroupsByUser($this->user->id, true);
        // echo "<pre>"; print_r($this->groups); echo "</pre>";

        JViewLegacy::loadHelper('sasfehp');                    
        // $this->arrTipoEventos = SasfehpHelper::obtColTipoEvento(); //obtiene coleccion de tipos de evento
        // $this->arrTiempoRecordatorios = SasfehpHelper::obtColTiempoRecordatorios(); //obtiene coleccion de tiempos recordatorios
        // $this->ColAsesores = SasfehpHelper::obtColElemPorIdCatalogo(3);
        // echo "<pre>"; print_r($this->ColAsesores); echo "</pre>";        

        //Grupos permitidos
        // 10 = Dirección, 11=Gerentes Venta, 12=Mesa de Control, 13=Titulación, 14=Nominas, 15=Post venta, 16=Solo Lectura
        // $arrAdmnGrp = array(8,10,11,12,13,14,15,16);
        $arrAdmnGrp = array(8,10);
        $this->permiso = false;
        foreach ($this->groups as $group){            
            if(in_array($group, $arrAdmnGrp)){                
                $this->permiso = true;
            }
        }

        $img = JURI::root().'media/com_sasfe/images/loading_transparent.gif';
        $this->imgLoading = "<div><img alt='Loading...' src='$img' style='width:12px;'></div>";
                        
        $this->addToolBar();
        $this->setDocument();
		parent::display($tpl);
	}
        
        protected function addToolBar() 
        {                     
            $document = JFactory::getDocument();            
            $document->addStyleDeclaration('.icon-48-historialsms {background-image: url(../media/com_sasfe/images/historialsms.png);}');                
            JToolBarHelper::title(JText::_('Historial SMS'), 'historialsms');
            // JToolBarHelper::deleteList('', 'historialsms.delete');
            // JToolBarHelper::editList('prospecto.edit');
            // JToolBarHelper::addNew('prospecto.add');
            JToolBarHelper::spacer();
            JToolBarHelper::cancel('historialsms.cancel');
            //submenu
            JSubMenuHelper::addEntry('Escritorio', 'index.php?option=com_sasfe', false);            
            if($this->permiso==true){
                JSubMenuHelper::addEntry('Cat&aacute;logos', 'index.php?option=com_sasfe&view=catalogos', false);                            
                JSubMenuHelper::addEntry('Reportes', 'index.php?option=com_sasfe&view=reportes', false);
                // if(!in_array("12", $this->groups)){
                    JSubMenuHelper::addEntry('Log de Accesos', 'index.php?option=com_sasfe&view=logaccesos', false);
                // }
                JSubMenuHelper::addEntry('Historial SMS', 'index.php?option=com_sasfe&view=historialsms', true);
            }
        }
                
        protected function setDocument() 
        {
            jimport('joomla.environment.uri');        
            $document = JFactory::getDocument();                                            
            $document->setTitle(JText::_('Historial SMS'));
            $document->addStyleSheet(JURI::root().'media/com_sasfe/css/style.css');                            
            $document->addScript(JURI::root().'media/com_sasfe/js/jquery.js');     
            $document->addScript(JURI::root().'media/com_sasfe/js/jquery.validate.js');                         
            //jquery ui datepicker
            $document->addStyleSheet(JURI::root().'media/com_sasfe/css/jquery-ui.css');
            $document->addScript(JURI::root().'media/com_sasfe/js/jquery-ui.js');
            $document->addScript(JURI::root().'media/com_sasfe/js/spanish_datapicker.js');                    
            $document->addStyleSheet(JURI::root().'media/com_sasfe/css/jquery.timepicker.min.css');                            
            $document->addScript(JURI::root().'media/com_sasfe/js/jquery.timepicker.min.js');                 
            $document->addScript(JURI::root().'media/com_sasfe/js/function.js');                    
            $document->addScript(JURI::root().'administrator/components/com_sasfe/views/historialsms/submitbutton.js');                         
        }
}
