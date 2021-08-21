<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
// import Joomla view library
jimport('joomla.application.component.view');

class SasfeViewCatdepartamento extends JViewLegacy
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
                                
                $this->id = JRequest::getVar('id'); //obtiene id de la url                                               
                
                $model = $this->getModel();  
                $this->data = $model->obtenerDatosDepto($this->id); //obtener datos de la tabla #__sasfe_departamentos
                //Obtener todos los fraccionamientos disponibles
                JViewLegacy::loadHelper('sasfehp');                    
                $this->Fracc = SasfehpHelper::obtTodosFraccionamientos();
                
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
                $document->addStyleDeclaration('.icon-48-departamento {background-image: url(../media/com_sasfe/images/departamentos.png);}');                                                    
		JToolBarHelper::title($isNew ? JText::_('Crear Elemento') : JText::_('Editar Elemento'), 'departamento');		
                JToolBarHelper::apply('catdepartamento.apply');
                JToolBarHelper::save('catdepartamento.save');
                JToolBarHelper::save2new('catdepartamento.saveandnew');
                JToolBarHelper::spacer();                                
		JToolBarHelper::cancel('catdepartamento.cancel', $isNew ? 'JTOOLBAR_CANCEL' : 'JTOOLBAR_CLOSE');
	}
	
	protected function setDocument() 
	{		
                $isNew = ($this->id == 0);
		$document = JFactory::getDocument();
		$document->setTitle($isNew ? JText::_('Crear Elemento') : JText::_('Editar Elemento'));		
                $document->addStyleSheet(JURI::root().'media/com_sasfe/css/style.css');                            
                $document->addScript(JURI::root().'media/com_sasfe/js/jquery.js');     
                $document->addScript(JURI::root().'media/com_sasfe/js/jquery.validate.js');                 
                $document->addScript(JURI::root().'media/com_sasfe/js/function.js');       
		$document->addScript(JURI::root() . "/administrator/components/com_sasfe/views/catdepartamento/submitbutton.js");
		JText::script('Error Inaceptable');
	}
}
