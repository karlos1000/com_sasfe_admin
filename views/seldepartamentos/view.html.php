<?php

defined('_JEXEC') or die;

class  SasfeViewSeldepartamentos extends JViewLegacy
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
            $this->depto_id = JRequest::getVar('depto_id');            
            $this->idFracc = JRequest::getVar('idFracc');            
            $this->idDatoGral = JRequest::getVar('idDatoGral');                        
            
            $this->arrDptsDisponibles = array();
            foreach($this->items as $item){               
                     $this->arrDptsDisponibles[] = (object)array("idDepartamento"=>$item->idDepartamento, "numero"=>$item->numero);               
            }
            
            //Obtener todos los fraccionamientos
            JViewLegacy::loadHelper('sasfehp');                    
            $this->Fracc = SasfehpHelper::obtTodosFraccionamientos();
            
            $this->addToolBar();                
            $this->setDocument();

            parent::display($tpl);                
	}
        
        protected function addToolBar() 
        {                     
            $document = JFactory::getDocument();            
            $document->addStyleDeclaration('.icon-48-asignardtps {background-image: url(../media/com_sasfe/images/catalogo.png);}');                
            JToolBarHelper::title(JText::_('Seleccione el departamento para asignarle los datos'), 'asignardtps');            
            
            //JToolBarHelper::spacer();
            JToolBarHelper::cancel('seldepartamentos.cancel');            
            //submenu
            //JSubMenuHelper::addEntry('Escritorio', 'index.php?option=com_sasfe', false); 
            //JSubMenuHelper::addEntry('Cat&aacute;logos', 'index.php?option=com_sasfe&view=catalogos', false);                
            //JSubMenuHelper::addEntry('Inventario de autos nuevos', 'index.php?option=com_sasfe&view=inventorycarsnew', true);          
        }
                
        protected function setDocument() 
        {
            jimport('joomla.environment.uri');        
            $document = JFactory::getDocument();                                
            $document->setTitle(JText::_('Listado departamentos para asignar'));                                
            $document->addStyleSheet(JURI::root().'media/com_sasfe/css/style.css');                            
            $document->addScript(JURI::root().'media/com_sasfe/js/jquery.js');     
            $document->addScript(JURI::root().'media/com_sasfe/js/jquery.validate.js');                 
            $document->addScript(JURI::root().'media/com_sasfe/js/function.js'); 
            $document->addScript(JURI::root().'administrator/components/com_sasfe/views/seldepartamentos/submitbutton.js'); 
        }
}
