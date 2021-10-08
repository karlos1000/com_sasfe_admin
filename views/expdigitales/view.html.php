<?php

defined('_JEXEC') or die;

class  SasfeViewExpdigitales extends JViewLegacy
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
        $this->arrTipoEventos = SasfehpHelper::obtColTipoEvento(); //obtiene coleccion de tipos de evento
        $this->arrTiempoRecordatorios = SasfehpHelper::obtColTiempoRecordatorios(); //obtiene coleccion de tiempos recordatorios
        $this->ColAsesores = SasfehpHelper::obtColElemPorIdCatalogo(3); //Agentes de venta
        $this->ColGteVentas = SasfehpHelper::obtColElemPorIdCatalogo(1);//Coleccion gerentes de venta
        // echo "<pre>"; print_r($this->ColGteVentas); echo "</pre>";

        $img = JURI::root().'media/com_sasfe/images/loading_transparent.gif';
        $this->imgLoading = "<div><img alt='Loading...' src='$img' style='width:12px;'></div>";

        //Revisar en que vista estamos
        $this->layout = JRequest::getVar('layout'); //obtiene el nombre del layout

        $this->addToolBar();
        $this->setDocument();
		parent::display($tpl);
	}

        protected function addToolBar()
        {
            $document = JFactory::getDocument();
            $document->addStyleDeclaration('.icon-48-expdigital {background-image: url(../media/com_sasfe/images/expdigital.png);}');

            JToolBarHelper::title(JText::_('Expedientes Digitales'), 'expdigital');
            JToolBarHelper::cancel('expdigitales.cancel');
            /*if($this->layout!="repetidos"){

                //gerentes de prospeccion y gerentes de venta
                if(in_array("19", $this->groups) || in_array("11", $this->groups)){
                    // JToolBarHelper::editList('expdigital.ver');
                    // JToolBarHelper::addNew('expdigital.add');
                    JToolBarHelper::editList('expdigital.editSu');
                    JToolBarHelper::addNew('expdigital.addSu');
                    JToolBarHelper::spacer();
                    JToolBarHelper::cancel('expdigitales.cancel');
                }
                elseif(in_array("17", $this->groups)){
                    JToolBarHelper::addNew('expdigital.add');
                    JToolBarHelper::spacer();
                    JToolBarHelper::cancel('expdigitales.cancel');
                }
		        elseif(in_array("10", $this->groups)){
                    JToolBarHelper::editList('expdigital.editSu');
                    JToolBarHelper::addNew('expdigital.addSu');
                    JToolBarHelper::spacer();
                    JToolBarHelper::cancel('expdigitales.cancel');
                    // JToolBarHelper::cancel('expdigitales.cancelrepetidos');
                }
                elseif(in_array("8", $this->groups)){
                    JToolBarHelper::editList('expdigital.editSu');
                    JToolBarHelper::addNew('expdigital.addSu');
                    JToolBarHelper::spacer();
                    JToolBarHelper::cancel('expdigitales.cancel');
                }
                elseif(in_array("20", $this->groups)){  //Imp. 16/08/21, Carlos (Solo lectura)
                    // JToolBarHelper::editList('expdigital.editSu');
                    // JToolBarHelper::addNew('expdigital.addSu');
                    JToolBarHelper::spacer();
                    JToolBarHelper::cancel('expdigitales.cancel');
                }
                else{
                    if($this->layout=="noprocesados"){
                        JToolBarHelper::cancel('expdigitales.cancelrepetidos');
                    }else{
                        // JToolBarHelper::deleteList('', 'expdigitales.delete');
                        JToolBarHelper::editList('expdigital.edit');
                        JToolBarHelper::addNew('expdigital.add');
                        JToolBarHelper::spacer();
                        JToolBarHelper::cancel('expdigitales.cancel');
                    }
                }
            }
            else{
                JToolBarHelper::title(JText::_('Expedientes Digitales'), 'expdigital');
                // JToolBarHelper::deleteList('', 'expdigitales.borrarProsRepetidos');
                JToolBarHelper::cancel('expdigitales.cancelrepetidos');
            }*/



            //submenu
            JSubMenuHelper::addEntry('Escritorio', 'index.php?option=com_sasfe', false);
            // if(in_array("8", $this->groups) || in_array("10", $this->groups) || in_array("17", $this->groups) || in_array("18", $this->groups) || in_array("11", $this->groups) || in_array("19", $this->groups)){
            if(in_array("8", $this->groups) || in_array("10", $this->groups)){
               JSubMenuHelper::addEntry('Expedientes Digitales', 'index.php?option=com_sasfe&view=expdigitales', true);
            }
        }

        protected function setDocument()
        {
            jimport('joomla.environment.uri');
            $document = JFactory::getDocument();
            $document->setTitle(JText::_('Expedientes Digitales'));
            $document->addStyleSheet(JURI::root().'media/com_sasfe/css/style.css');
            $document->addScript(JURI::root().'media/com_sasfe/js/jquery.js');
            $document->addScript(JURI::root().'media/com_sasfe/js/jquery.validate.js');
            $document->addStyleSheet(JURI::root().'media/com_sasfe/css/alertify.min.css');
            $document->addScript(JURI::root().'media/com_sasfe/js/alertify.js');

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
            $document->addScript(JURI::root().'media/com_sasfe/js/accounting.min.js');
            $document->addScript(JURI::root().'administrator/components/com_sasfe/views/expdigitales/submitbutton.js');
        }
}
