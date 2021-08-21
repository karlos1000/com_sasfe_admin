<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
// import Joomla view library
jimport('joomla.application.component.view');

class SasfeViewCatgenerico extends JViewLegacy
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
                $this->idCat = JRequest::getVar('id_cat'); //obtiene id del catalogo
                
                $model = $this->getModel();  
                $this->data = $model->obtDatosCatalogo($this->id); //obtener datos de la tabla #__sasfe_datos_catalogos
                                                                               
                // Set the toolbar
		$this->addToolBar();
		// Display the template
		parent::display($tpl);
		// Set the document
		$this->setDocument();
	}
	
	protected function addToolBar() 
	{
            switch ($this->idCat) {
                case 1: $img = 'gerentes_venta.png'; break;            
                case 2: $img = 'titulacion.png'; break;            
                case 3: $img = 'asesores.png'; break;            
                case 4: $img = 'prospectadores.png'; break;            
                case 5: $img = 'estatus_vivienda.png'; break;            
                case 6: $img = 'motivos_cancelacion.png'; break;            
                case 7: $img = 'tipos_credito.png'; break;                                    
            } 
            
		JRequest::setVar('hidemainmenu', true);	                
                $isNew = ($this->id == 0);
                $document = JFactory::getDocument();                                
                $document->addStyleDeclaration('.icon-48-generico {background-image: url(../media/com_sasfe/images/'.$img.');}');                                                    
		JToolBarHelper::title($isNew ? JText::_('Crear Elemento') : JText::_('Editar Elemento'), 'generico');		
                JToolBarHelper::apply('catgenerico.apply');
                JToolBarHelper::save('catgenerico.save');
                JToolBarHelper::save2new('catgenerico.saveandnew');
                JToolBarHelper::spacer();                                
		JToolBarHelper::cancel('catgenerico.cancel', $isNew ? 'JTOOLBAR_CANCEL' : 'JTOOLBAR_CLOSE');
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
		$document->addScript(JURI::root() . "administrator/components/com_sasfe/views/catgenerico/submitbutton.js");
		JText::script('Error Inaceptable');
		
	}
}
