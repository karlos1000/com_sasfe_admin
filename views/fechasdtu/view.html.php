<?php
/**
 * fecha: 19-11-13
 * company: company
 * @author Karlos
 */
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
// import Joomla view library
jimport('joomla.application.component.view');

class SasfeViewFechasdtu extends JViewLegacy{

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
                // Assign data to the view
                $this->items = $items;
                $this->pagination = $pagination;

                JViewLegacy::loadHelper('sasfehp');
                $this->idFracc = JRequest::getVar('idFracc');
                $this->datosFracc = SasfehpHelper::obtDatosFraccPorId($this->idFracc); //Obtener el nombre del fraccionamiento

                $this->addToolBar();
                parent::display($tpl);

                // Set the document
                $this->setDocument();
        }

        protected function addToolBar($total=null)
        {
            $img = ($this->datosFracc->imagen!='') ? $this->datosFracc->imagen : 'img_default.png';
            $document = JFactory::getDocument();
            $document->addStyleDeclaration('.icon-48-fechasdtu {background-image: url(../media/com_sasfe/upload_files/'.$img.');background-size: 48px 48px;}');
            JToolBarHelper::title(JText::_('Fechas DTU: '. $this->datosFracc->nombre), 'fechasdtu');
            JToolBarHelper::cancel('fechasdtu.cancel');
            //submenu
            JSubMenuHelper::addEntry('Escritorio', 'index.php?option=com_sasfe', false);
            JSubMenuHelper::addEntry('Cat&aacute;logos', 'index.php?option=com_sasfe&view=catalogos', false);
        }

        protected function setDocument()
        {
            $document = JFactory::getDocument();
            $document->setTitle(JText::_('Fechas DTU: ' .$this->datosFracc->nombre));

            $document->addStyleSheet(JURI::root().'media/com_sasfe/css/style.css');
            $document->addScript(JURI::root().'media/com_sasfe/js/jquery.js');
            $document->addScript(JURI::root().'media/com_sasfe/js/jquery.validate.js');
            $document->addStyleSheet(JURI::root().'media/com_sasfe/css/jquery-ui.css');
            $document->addScript(JURI::root().'media/com_sasfe/js/jquery-ui.js');
            $document->addScript(JURI::root().'media/com_sasfe/js/spanish_datapicker.js');
            $document->addStyleSheet(JURI::root().'media/com_sasfe/css/jquery.timepicker.min.css');
            $document->addScript(JURI::root().'media/com_sasfe/js/jquery.timepicker.min.js');
            $document->addScript(JURI::root().'media/com_sasfe/js/function.js');
            $document->addScript(JURI::root().'media/com_sasfe/js/accounting.min.js');
            $document->addScript(JURI::root().'administrator/components/com_sasfe/views/fechasdtu/submitbutton.js');
        }
}

?>
