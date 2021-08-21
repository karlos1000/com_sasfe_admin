<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
// import Joomla view library
jimport('joomla.application.component.view');

class SasfeViewContacto extends JViewLegacy
{
        protected $id;
        public function display($tpl = null)
	{
		// get the Data
		$form = $this->get('Form');
		$item = $this->get('Item');

		// Check for errors.
		if (count($errors = $this->get('Errors')))
		{
			JError::raiseError(500, implode('<br />', $errors));
			return false;
		}
		// Assign the Data
		$this->form = $form;
		$this->item = $item;

		$this->user = JFactory::getUser();
        $this->groups = JAccess::getGroupsByUser($this->user->id, true);  //obtiene grupo/s por id de usuario
        $this->groupsOrig = $this->groups;
        // echo "<pre>"; print_r($this->groups); echo "</pre>";

        $this->id = JRequest::getVar('id'); //obtiene id de la url

        $model = $this->getModel();
        $this->data = $model->obtenerDatosContacto($this->id); //obtener datos de la tabla #__sasfe_datos_contactos
        //Obtener todos los fraccionamientos disponibles
        JViewLegacy::loadHelper('sasfehp');
        $this->arrDateTime = SasfehpHelper::obtDateTimeZone();
        $this->ColTiposCto = SasfehpHelper::obtColElemPorIdCatalogo(7); //tipos de credito
        $this->arrTipoEventos = SasfehpHelper::obtColTipoEvento(); //obtiene coleccion de tipos de evento
        // $this->arrTiempoRecordatorios = SasfehpHelper::obtColTiempoRecordatorios(); //obtiene coleccion de tiempos recordatorios
        $this->arrTiposCaptados = SasfehpHelper::obtColTipoCaptados(); //obtiene coleccion de tipos de captados

        $this->ColAsesores = SasfehpHelper::obtColElemPorIdCatalogo(3); //Agentes de venta
        $this->ColGteVentas = SasfehpHelper::obtColElemPorIdCatalogo(1);//Coleccion gerentes de venta
        // echo "<pre>"; print_r($this->ColGteVentas); echo "</pre>";*/

        $this->pathUrl = 'index.php?option=com_sasfe&task=prospecto.';
        $img = JURI::root().'media/com_sasfe/images/loading_transparent.gif';
        $this->imgLoading = "<div><img alt='Loading...' src='$img' style='width:12px;'></div>";

        $this->layout = JRequest::getVar('layout'); //obtiene el nombre del layout
        // $this->prosp = JRequest::getVar('prosp'); //saber si es un prospectador
        // $this->opc = JRequest::getVar('opc'); //saber que opcion tiene
        // $this->Fracc = SasfehpHelper::obtTodosFraccionamientos(); //col de fraccionamientos

        // Set the toolbar
		$this->addToolBar();
		// Display the template
		parent::display($tpl);
		// Set the document
		$this->setDocument();
	}

	protected function addToolBar()
	{
		JRequest::setVar('hidemainmenu', true);
        $isNew = ($this->id == 0);
        $document = JFactory::getDocument();
        $document->addStyleDeclaration('.icon-48-contacto {background-image: url(../media/com_sasfe/images/contacto.png);}');

        /*if($this->layout=="sololectura"){
        	JToolBarHelper::title('Contacto','contacto');
            //Ver detalle solo lectura
            if($this->opc==1){
                JToolBarHelper::cancel('contacto.cancel');
            }
            //ver detalle solo lectura vista repetidos
            elseif($this->opc==2){
                JToolBarHelper::cancel('contacto.cancelYRepetidos');
            }
            else{
                JToolBarHelper::cancel('listareventos.cancelSololectura');
            }
        }
        elseif($this->prosp){
            //echo "ENTRE 1";
        	JToolBarHelper::title($isNew ? JText::_('Crear Elemento') : JText::_('Editar Elemento'), 'contacto');
	        JToolBarHelper::apply('contacto.saveandclose');
			JToolBarHelper::cancel('contacto.cancelYEscritorio', $isNew ? 'JTOOLBAR_CANCEL' : 'JTOOLBAR_CLOSE');
        }
        else{*/
        	JToolBarHelper::title($isNew ? JText::_('Crear Elemento') : JText::_('Editar Elemento'), 'contacto');
            JToolBarHelper::apply('contacto.apply');
            // JToolBarHelper::save('contacto.save');
            // JToolBarHelper::save2new('contacto.saveandnew');
            JToolBarHelper::spacer();
            JToolBarHelper::cancel('contacto.cancel', $isNew ? 'JTOOLBAR_CANCEL' : 'JTOOLBAR_CLOSE');

            /*if($this->id==0){
                if($this->layout=="editsu"){
                    JToolBarHelper::apply('contacto.applyDos');
                    JToolBarHelper::save('contacto.saveDos');
                    JToolBarHelper::save2new('contacto.saveandnewDos');
                    JToolBarHelper::spacer();
                }else{
                    JToolBarHelper::apply('contacto.apply');
                    JToolBarHelper::save('contacto.save');
                    JToolBarHelper::save2new('contacto.saveandnew');
                    JToolBarHelper::spacer();
                }
            }else{
                if($this->layout=="editsu"){
                    JToolBarHelper::apply('contacto.applyDos');
                    JToolBarHelper::save('contacto.saveDos');
                    JToolBarHelper::save2new('contacto.saveandnewDos');
                    JToolBarHelper::spacer();
                }
                elseif($this->data[0]->fechaDptoAsignado==""){
                    JToolBarHelper::apply('contacto.apply');
                    JToolBarHelper::save('contacto.save');
                    JToolBarHelper::save2new('contacto.saveandnew');
                    JToolBarHelper::spacer();
                }
            }
            if($this->opc==3){
                JToolBarHelper::cancel('contacto.cancelYListarEventos', $isNew ? 'JTOOLBAR_CANCEL' : 'JTOOLBAR_CLOSE');
            }else{
                if($this->layout=="editsu"){
                    JToolBarHelper::cancel('contacto.cancelDos', $isNew ? 'JTOOLBAR_CANCEL' : 'JTOOLBAR_CLOSE');
                }else{
                JToolBarHelper::cancel('contacto.cancel', $isNew ? 'JTOOLBAR_CANCEL' : 'JTOOLBAR_CLOSE');
                }
            }*/
        // }
	}

	protected function setDocument()
	{
        $isNew = ($this->id == 0);
		$document = JFactory::getDocument();
		// if($this->layout=="sololectura"){
		// 	JToolBarHelper::title('Contacto','contacto');
		// }else{
		// 	$document->setTitle($isNew ? JText::_('Crear Elemento') : JText::_('Editar Elemento'));
		// }
        $document->setTitle($isNew ? JText::_('Crear Elemento') : JText::_('Editar Elemento'));
        $document->addStyleSheet(JURI::root().'media/com_sasfe/css/style.css');
        $document->addScript(JURI::root().'media/com_sasfe/js/jquery.js');
        $document->addScript(JURI::root().'media/com_sasfe/js/jquery.validate.js');

        // $document->addStyleSheet('http://cdnjs.cloudflare.com/ajax/libs/timepicker/1.3.5/jquery.timepicker.min.css');
        // $document->addScript('http://cdnjs.cloudflare.com/ajax/libs/timepicker/1.3.5/jquery.timepicker.min.js');
        $document->addStyleSheet(JURI::root().'media/com_sasfe/css/jquery.timepicker.min.css');
        $document->addScript(JURI::root().'media/com_sasfe/js/jquery.timepicker.min.js');

        $document->addScript(JURI::root().'media/com_sasfe/js/function.js');

        //jquery ui
		// $document->addStyleSheet('http://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css');
		// $document->addScript("https://code.jquery.com/ui/1.12.1/jquery-ui.js");
        $document->addStyleSheet(JURI::root().'media/com_sasfe/css/jquery-ui.css');
        $document->addScript(JURI::root().'media/com_sasfe/js/jquery-ui.js');
        //Currency
        $document->addScript(JURI::root().'media/com_sasfe/js/jquery.formatCurrency-1.4.0.min.js');
		$document->addScript(JURI::root() . "/administrator/components/com_sasfe/views/contacto/submitbutton.js?v=1");
		$document->addScript(JURI::root().'media/com_sasfe/js/spanish_datapicker.js');

        $document->addStyleSheet(JURI::root().'media/com_sasfe/css/jquery-customselect.css');
        $document->addScript(JURI::root().'media/com_sasfe/js/jquery-customselect.js');

		JText::script('Error Inaceptable');
	}
}
