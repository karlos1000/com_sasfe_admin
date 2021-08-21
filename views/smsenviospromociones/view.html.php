<?php

defined('_JEXEC') or die;

class  SasfeViewSmsenviospromociones extends JViewLegacy
{
	protected $items;
	protected $pagination;
	protected $state; 
    protected $dataDpts;        

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
            
            $this->pathUrl = 'index.php?option=com_sasfe&task=smsenviospromociones.';
            $this->user = JFactory::getUser();            
            $this->groups = JAccess::getGroupsByUser($this->user->id, true);
            
            if(in_array("10", $this->groups) || in_array("8", $this->groups) || in_array("14", $this->groups)){
                $this->permiso = true;
            }else{
                $this->permiso = false;
            }
            
            $arrDepartamentos = array();
            $arrDatosGrals = array();
            $arrTodosDatos = array();
                                    
            JViewLegacy::loadHelper('sasfehp');                                
            //Obtener todos los fraccionamientos disponibles
            $this->Fracc = SasfehpHelper::obtTodosFraccionamientos(); 
                                    
            $this->addToolBar();                
            $this->setDocument();

            parent::display($tpl);                
	}
        
        protected function addToolBar() 
        {                     
            $document = JFactory::getDocument();            
            $document->addStyleDeclaration('.icon-48-reportes {background-image: url(../media/com_sasfe/images/catalogos.png);}');                
            JToolBarHelper::title(JText::_('Envios de promociones'), 'smsenviospromociones');
            
            // JToolBarHelper::apply('smsenviospromociones.apply');
            JToolBarHelper::custom( 'smsenviospromociones.sendMessage', 'sendMessage', '', 'Enviar mensaje', false, false );
            //JToolBarHelper::spacer();
            JToolBarHelper::cancel('smsenviospromociones.cancel');            
            //submenu
            JSubMenuHelper::addEntry('Escritorio', 'index.php?option=com_sasfe', false); 
            if($this->permiso==true){
                JSubMenuHelper::addEntry('Cat&aacute;logos', 'index.php?option=com_sasfe&view=catalogos', false);                            
                JSubMenuHelper::addEntry('Reportes', 'index.php?option=com_sasfe&view=smsenviospromociones', false);
                if(!in_array("12", $this->groups)){
                    JSubMenuHelper::addEntry('Log de Accesos', 'index.php?option=com_sasfe&view=logaccesos', false);
                }
                JSubMenuHelper::addEntry('Envio de promociones', 'index.php?option=com_sasfe&view=smsenviospromociones', true);
            }
        }
                
        protected function setDocument() 
        {
            jimport('joomla.environment.uri');        
            JHtml::_('behavior.framework');       
            $document = JFactory::getDocument();                                
            $document->setTitle(JText::_('Envio de promociones'));
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
            $document->addScript(JURI::root().'administrator/components/com_sasfe/views/smsenviospromociones/submitbutton.js'); 
        }
}
