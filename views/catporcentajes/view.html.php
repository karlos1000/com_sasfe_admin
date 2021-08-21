<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
// import Joomla view library
jimport('joomla.application.component.view');

class SasfeViewCatporcentajes extends JViewLegacy
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
                
                $model = $this->getModel();  
                $this->asesorSinPrev = $model->obtPorcentajeAsesorProsSinPreventa(1,0); 
                $this->asesorConPrev = $model->obtPorcentajeAsesorProsSinPreventa(1,1);
                $this->prospSinPrev = $model->obtPorcentajeAsesorProsSinPreventa(2,0);
                $this->prospConPrev = $model->obtPorcentajeAsesorProsSinPreventa(2,1);
                                           
//                echo '<pre>';
//                    //print_r($this->asesorSinPrev);
//                    print_r($this->prospConPrev);
//                echo '</pre>';
                
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
            $document = JFactory::getDocument();                                
            $document->addStyleDeclaration('.icon-48-porcentajes {background-image: url(../media/com_sasfe/images/porcentajes.png);}');                                                    
            JToolBarHelper::title('Editar porcentajes', 'porcentajes');		            
            JToolBarHelper::cancel('catporcentajes.cancel', 'JTOOLBAR_CANCEL');
            JSubMenuHelper::addEntry('Escritorio', 'index.php?option=com_sasfe', false); 
            // JSubMenuHelper::addEntry('Fraccionamientos', 'index.php?option=com_sasfe&view=catfraccionamientos', false);                
            // JSubMenuHelper::addEntry('Departamentos', 'index.php?option=com_sasfe&view=catdepartamentos', false);              
            JSubMenuHelper::addEntry('Cat&aacute;logos', 'index.php?option=com_sasfe&view=catalogos', false); 
            JSubMenuHelper::addEntry('Reportes', 'index.php?option=com_sasfe&view=reportes', false);
            JSubMenuHelper::addEntry('Log de Accesos', 'index.php?option=com_sasfe&view=logaccesos', false);
	}
	
	protected function setDocument() 
	{		                
		$document = JFactory::getDocument();
		$document->setTitle('Editar porcentajes');		
                $document->addStyleSheet(JURI::root().'media/com_sasfe/css/style.css');                            
                $document->addScript(JURI::root().'media/com_sasfe/js/jquery.js');     
                $document->addScript(JURI::root().'media/com_sasfe/js/jquery.validate.js');                 
                $document->addScript(JURI::root().'media/com_sasfe/js/function.js');       
		$document->addScript(JURI::root() . "/administrator/components/com_sasfe/views/catporcentajes/submitbutton.js");
		JText::script('Error Inaceptable');
	}
}
