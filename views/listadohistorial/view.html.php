<?php

defined('_JEXEC') or die;

class  SasfeViewListadohistorial extends JViewLegacy
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
            JViewLegacy::loadHelper('sasfehp');
            
            $this->idFracc = JRequest::getVar('idFracc');                                    
            $this->user = JFactory::getUser();                            
            $this->groups = JAccess::getGroupsByUser($this->user->id, true); //obtiene grupo/s por id de usuario            
                       
            if(in_array("10", $this->groups) || in_array("8", $this->groups)){
                $this->ColEstatus = SasfehpHelper::obtColElemPorIdCatalogo(5);
            }else{
                $this->ColEstatus = array();
            }   
            
            $this->imgLoading = JURI::root().'media/com_sasfe/images/loading_transparent.gif';
            
            $this->addToolBar();                
            $this->setDocument();
            parent::display($tpl);                
	}
        
        protected function addToolBar() 
        {                     
            $document = JFactory::getDocument();            
            $document->addStyleDeclaration('.icon-48-historialdtps {background-image: url(../media/com_sasfe/images/catalogo.png);}');                
            JToolBarHelper::title(JText::_('Seleccion&eacute; el elemento para mostrar su historial'), 'historialdtps');            
            
            //JToolBarHelper::spacer();
            JToolBarHelper::cancel('departamento.cancel');                        
        }
                
        protected function setDocument() 
        {
            jimport('joomla.environment.uri');        
            $document = JFactory::getDocument();                                
            $document->setTitle(JText::_('Listado departamentos historiales'));                                
            $document->addStyleSheet(JURI::root().'media/com_sasfe/css/style.css');                            
            $document->addScript(JURI::root().'media/com_sasfe/js/jquery.js');     
            $document->addScript(JURI::root().'media/com_sasfe/js/jquery.validate.js');                 
            $document->addScript(JURI::root().'media/com_sasfe/js/function.js'); 
            $document->addScript(JURI::root().'administrator/components/com_sasfe/views/listadohistorial/submitbutton.js'); 
        }
     
}
