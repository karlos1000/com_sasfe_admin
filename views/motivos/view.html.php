<?php

defined('_JEXEC') or die;

class  SasfeViewMotivos extends JViewLegacy
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
        $this->groups = JAccess::getGroupsByUser($this->user->id, true);

        $this->addToolBar();
        $this->setDocument();
		parent::display($tpl);
	}

        protected function addToolBar()
        {
            $document = JFactory::getDocument();
            $document->addStyleDeclaration('.icon-48-motivos {background-image: url(../media/com_sasfe/images/motivos.png);}');
            JToolBarHelper::title(JText::_('Cat&aacute;logo Motivos'), 'motivos');
            JToolBarHelper::deleteList('', 'motivos.delete');
            JToolBarHelper::editList('motivo.edit');
            JToolBarHelper::addNew('motivo.add');
            JToolBarHelper::spacer();
            JToolBarHelper::cancel('motivos.cancel');
            //submenu
            JSubMenuHelper::addEntry('Escritorio', 'index.php?option=com_sasfe', false);
            JSubMenuHelper::addEntry('Cat&aacute;logos', 'index.php?option=com_sasfe&view=catalogos', false);
            JSubMenuHelper::addEntry('Reportes', 'index.php?option=com_sasfe&view=reportes', false);
            if(!in_array("12", $this->groups)){
                JSubMenuHelper::addEntry('Log de Accesos', 'index.php?option=com_sasfe&view=logaccesos', false);
            }
        }

        protected function setDocument()
        {
            jimport('joomla.environment.uri');
            $document = JFactory::getDocument();
            $document->setTitle(JText::_('Catalogo Motivos'));
            $document->addStyleSheet(JURI::root().'media/com_sasfe/css/style.css');
            $document->addScript(JURI::root().'media/com_sasfe/js/jquery.js');
            $document->addScript(JURI::root().'media/com_sasfe/js/jquery.validate.js');
            $document->addScript(JURI::root().'media/com_sasfe/js/function.js');
            $document->addScript(JURI::root().'administrator/components/com_sasfe/views/motivos/submitbutton.js');
        }
}
