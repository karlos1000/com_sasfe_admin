<?php

/**
 * fecha: 11-08-14
 * company: Framelova
 * @author Carlos
 */
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
// import Joomla view library
jimport('joomla.application.component.view');

/**
 * Vista Sasfe
 */

class SasfeViewSasfe extends JViewLegacy{

        function display($tpl = null)
        {
                // Get data from the model
                $items = $this->get('State');
                $items = $this->get('Items');
                $pagination = $this->get('Pagination');

                // Check for errors.
                if (count($errors = $this->get('Errors')))
                {
                        JError::raiseError(500, implode('<br />', $errors));
                        return false;
                }

                $this->user = JFactory::getUser();
                //obtiene grupo/s por id de usuario
                $this->groups = JAccess::getGroupsByUser($this->user->id, true);
                //Obtener todos los fraccionamientos disponibles
                JViewLegacy::loadHelper('sasfehp');
                $this->Fracc = SasfehpHelper::obtTodosFraccionamientos();

                //8 = super usuario
                //10 = direccion
                //11 = gerente de ventas
                //12 = mesa de control
                //13 = titulacion
                //14 = nominas
                //15 = post venta
                //16 = solo lectura
                //17 = prospectadores
                //18 = agentes de ventas
                //19 = gerente prospeccion
                //20 = Redes
                if(in_array("10", $this->groups) || in_array("8", $this->groups) || in_array("14", $this->groups)){
                    $this->permiso = true;
                }else{
                    $this->permiso = false;
                }
                //Si es un agente de ventas verificar si tiene eventos programados para el dia actual corriendo
                $this->colEventosHoy = array();
                if(in_array("18", $this->groups)){
                    $timeZone = SasfehpHelper::obtDateTimeZone();
                    $this->colEventosHoy = SasfehpHelper::checkEventosHoy($this->user->id, $timeZone->fecha);
                }

                // Assign data to the view
                $this->items = $items;
                $this->pagination = $pagination;

                 // Set the toolbar and number of found items
                $this->addToolBar($this->pagination->total);

                // Display the template
                parent::display($tpl);

                // Set the document
                $this->setDocument();
        }

        /**
         * Setting the toolbar
         */
        protected function addToolBar($total=null)
        {
                JToolBarHelper::title(JText::_('COM_SASFE_ADMIN'),'sasfe');
                // Options button.
                if (JFactory::getUser()->authorise('core.admin', 'com_sasfe')) {
                        JToolBarHelper::preferences('com_sasfe');
                }
                //submenu
            if(JFactory::getUser()->authorise('core.manage', 'com_sasfe')):
                //Si es grupo prospectador mostrar
                if(in_array("17", $this->groups)){
                    // JSubMenuHelper::addEntry('Alta Prospecto', 'index.php?option=com_sasfe&view=prospecto&layout=edit&id=0&prosp=1', false);
                }

                //Para el resto de usuarios
                    if($this->permiso==true){
                        JSubMenuHelper::addEntry('Cat&aacute;logos', 'index.php?option=com_sasfe&view=catalogos', false);
                        JSubMenuHelper::addEntry('Reportes', 'index.php?option=com_sasfe&view=reportes', false);
                        if(!in_array("12", $this->groups)){
                          JSubMenuHelper::addEntry('Log de Accesos', 'index.php?option=com_sasfe&view=logaccesos', false);
                          JSubMenuHelper::addEntry('Prospectos', 'index.php?option=com_sasfe&view=prospectos', false);
                      }
                  }
                  endif;
        }

        /**
         * Method to set up the document properties
         *
         * @return void
         */
        protected function setDocument()
        {
                $document = JFactory::getDocument();
                $document->setTitle(JText::_('Esphabit'));
        }
}

?>
