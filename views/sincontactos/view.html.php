<?php

defined('_JEXEC') or die;

class  SasfeViewSincontactos extends JViewLegacy
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

            $this->pathUrl = 'index.php?option=com_sasfe&task=sincontactos.';
            $this->user = JFactory::getUser();
            $this->groups = JAccess::getGroupsByUser($this->user->id, true);

            if(in_array("10", $this->groups) || in_array("8", $this->groups) || in_array("20", $this->groups)){
                $this->permiso = true;
            }else{
                $this->permiso = false;
            }

            $arrDepartamentos = array();
            $arrDatosGrals = array();
            $arrTodosDatos = array();

            JViewLegacy::loadHelper('sasfehp');
            //Obtener todos los fraccionamientos disponibles
            // $this->Fracc = SasfehpHelper::obtTodosFraccionamientos();

            $this->addToolBar();
            $this->setDocument();
            parent::display($tpl);
	}

        protected function addToolBar()
        {
            $document = JFactory::getDocument();
            $document->addStyleDeclaration('.icon-48-reportes {background-image: url(../media/com_sasfe/images/catalogos.png);}');
            JToolBarHelper::title(JText::_('Sincronizar contactos'), 'sincontactos');

            // JToolBarHelper::apply('sincontactos.apply');
            // JToolBarHelper::custom( 'sincontactos.sendMessage', '', '', 'Enviar mensaje', false, false );
            //JToolBarHelper::spacer();
            JToolBarHelper::cancel('sincontactos.cancel');
            //submenu
            JSubMenuHelper::addEntry('Escritorio', 'index.php?option=com_sasfe', false);
            if($this->permiso==true){
                if(in_array("20", $this->groups)){
                    JSubMenuHelper::addEntry('Sincronizar contactos', 'index.php?option=com_sasfe&view=sincontactos', true);
                    JSubMenuHelper::addEntry('Contactos', 'index.php?option=com_sasfe&view=contactos', false);
                }else{
                    JSubMenuHelper::addEntry('Cat&aacute;logos', 'index.php?option=com_sasfe&view=catalogos', false);
                    JSubMenuHelper::addEntry('Sincronizar contactos', 'index.php?option=com_sasfe&view=sincontactos', false);
                    if(!in_array("12", $this->groups)){
                        JSubMenuHelper::addEntry('Log de Accesos', 'index.php?option=com_sasfe&view=logaccesos', false);
                    }
                    JSubMenuHelper::addEntry('Envios SMS', 'index.php?option=com_sasfe&view=smsenviosreferidos', false);
                    JSubMenuHelper::addEntry('Sincronizar contactos', 'index.php?option=com_sasfe&view=sincontactos', true);
                    JSubMenuHelper::addEntry('Contactos', 'index.php?option=com_sasfe&view=contactos', false);
                }
            }
        }

        protected function setDocument()
        {
            jimport('joomla.environment.uri');
            $document = JFactory::getDocument();
            $document->setTitle(JText::_('Sincronizar contactos'));
            $document->addStyleSheet(JURI::root().'media/com_sasfe/css/style.css');
            $document->addScript(JURI::root().'media/com_sasfe/js/jquery.js');
            $document->addScript(JURI::root().'media/com_sasfe/js/jquery.validate.js');

            //boostrap duallistbox
            $document->addStyleSheet(JURI::root().'media/com_sasfe/css/bootstrap-glyphicons.css');
            $document->addStyleSheet(JURI::root().'media/com_sasfe/css/bootstrap-duallistbox.css');
            $document->addScript(JURI::root().'media/com_sasfe/js/jquery.bootstrap-duallistbox.js');

            $document->addStyleSheet(JURI::root().'media/com_sasfe/css/jquery-ui.css');
            $document->addScript(JURI::root().'media/com_sasfe/js/jquery-ui.js');
            $document->addScript(JURI::root().'media/com_sasfe/js/spanish_datapicker.js');

            $document->addScript(JURI::root().'media/com_sasfe/js/function.js');
            //accounting
            $document->addScript(JURI::root().'media/com_sasfe/js/accounting.min.js');
            $document->addScript(JURI::root().'administrator/components/com_sasfe/views/sincontactos/submitbutton.js');
        }
}
