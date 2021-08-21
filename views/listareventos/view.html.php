<?php

defined('_JEXEC') or die;

class  SasfeViewListareventos extends JViewLegacy
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
        $arrAdmnGrp = array(8,10,11,12,13,14,15,16);
        $this->permiso = false;
        foreach ($this->groups as $group){            
            if(in_array($group, $arrAdmnGrp)){                
                $this->permiso = true;
            }
        }

        $img = JURI::root().'media/com_sasfe/images/loading_transparent.gif';
        $this->imgLoading = "<div><img alt='Loading...' src='$img' style='width:12px;'></div>";
            
        //Revisar en que vista estamos
        $this->opc = JRequest::getVar('opc'); //obtiene la opcion correspondiente
                
        $this->addToolBar();
        $this->setDocument();
		parent::display($tpl);
	}
        
        protected function addToolBar() 
        {                     
            $document = JFactory::getDocument();            
            $document->addStyleDeclaration('.icon-48-listareventos {background-image: url(../media/com_sasfe/images/listareventos.png);}');                
            JToolBarHelper::title(JText::_('Lista de Eventos'), 'listareventos');
            // JToolBarHelper::deleteList('', 'listareventos.delete');
            // JToolBarHelper::editList('prospecto.edit');
            // JToolBarHelper::addNew('prospecto.add');
            JToolBarHelper::spacer();
            JToolBarHelper::cancel('listareventos.cancel');
            //submenu
            JSubMenuHelper::addEntry('Escritorio', 'index.php?option=com_sasfe', false);
            if(in_array("17", $this->groups) || in_array("18", $this->groups) || in_array("11", $this->groups) || in_array("19", $this->groups)){
               JSubMenuHelper::addEntry('Prospectos', 'index.php?option=com_sasfe&view=prospectos', false);
               if(in_array("11", $this->groups) || in_array("18", $this->groups)){
                JSubMenuHelper::addEntry('Eventos', 'index.php?option=com_sasfe&view=listareventos&opc=1', true);
               }
            }            

            // JSubMenuHelper::addEntry('Cat&aacute;logos', 'index.php?option=com_sasfe&view=catalogos', false); 
            // JSubMenuHelper::addEntry('Reportes', 'index.php?option=com_sasfe&view=reportes', false);
            // JSubMenuHelper::addEntry('Log de Accesos', 'index.php?option=com_sasfe&view=logaccesos', false);
            // JSubMenuHelper::addEntry('Fraccionamientos', 'index.php?option=com_sasfe&view=catfraccionamientos', false);                
            // JSubMenuHelper::addEntry('Departamentos', 'index.php?option=com_sasfe&view=catdepartamentos', true);
            // JSubMenuHelper::addEntry('Prospectos', 'index.php?option=com_sasfe&view=prospectos', false);
        }
                
        protected function setDocument() 
        {
            jimport('joomla.environment.uri');        
            $document = JFactory::getDocument();                                            
            $document->setTitle(JText::_('Lista de Eventos'));
            $document->addStyleSheet(JURI::root().'media/com_sasfe/css/style.css');                            
            $document->addScript(JURI::root().'media/com_sasfe/js/jquery.js');     
            $document->addScript(JURI::root().'media/com_sasfe/js/jquery.validate.js');                         

            //jquery ui datepicker
            // $document->addStyleSheet('http://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css');
            // $document->addScript("https://code.jquery.com/ui/1.12.1/jquery-ui.js");    
            $document->addStyleSheet(JURI::root().'media/com_sasfe/css/jquery-ui.css');
            $document->addScript(JURI::root().'media/com_sasfe/js/jquery-ui.js');

            $document->addScript(JURI::root().'media/com_sasfe/js/spanish_datapicker.js');            

            // $document->addStyleSheet('http://cdnjs.cloudflare.com/ajax/libs/timepicker/1.3.5/jquery.timepicker.min.css');
            // $document->addScript('http://cdnjs.cloudflare.com/ajax/libs/timepicker/1.3.5/jquery.timepicker.min.js');
            $document->addStyleSheet(JURI::root().'media/com_sasfe/css/jquery.timepicker.min.css');                            
            $document->addScript(JURI::root().'media/com_sasfe/js/jquery.timepicker.min.js');     
            
            $document->addScript(JURI::root().'media/com_sasfe/js/function.js');                    
            $document->addScript(JURI::root().'administrator/components/com_sasfe/views/listareventos/submitbutton.js');                         
        }
}
