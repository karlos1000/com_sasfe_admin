<?php
/** 
 * fecha: 11-08-14
 * company: Framelova
 * @author Carlos
 */
// No direct access to this file
defined('_JEXEC') or die;
// import the list field type
jimport('joomla.form.helper');
JFormHelper::loadFieldClass('list');
 
/**
 * Clase de campo de formulario para el componente Sasfe
 */

class JFormFieldSasfe extends JFormFieldList{
     /**
         * The field type.
         *
         * @var         string
         */
        protected $type = 'Sasfe';
 
        /**
         * Method to get a list of options for a list input.
         *
         * @return      array           An array of JHtml options.
         */
        protected function getOptions() 
        {
                $db = JFactory::getDBO();
                $query = $db->getQuery(true);
                $query->select('id,greeting');
                $query->from('#__sasfe');
                $db->setQuery((string)$query);
                $messages = $db->loadObjectList();
                $options = array();
                if ($messages)
                {
                        foreach($messages as $message) 
                        {
                                $options[] = JHtml::_('select.option', $message->id, $message->greeting);
                        }
                }
                $options = array_merge(parent::getOptions(), $options);
                return $options;
        }
}

?>
