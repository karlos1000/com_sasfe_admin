<?php
/** 
 * fecha: 11-08-14
 * company: Framelova
 * @author Carlos
 */
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
// import the Joomla modellist library
jimport('joomla.application.component.modellist');

class SasfeModelSasfe extends JModelList{
    
        protected function getListQuery()
        {
                // Create a new query object.           
                $db = JFactory::getDBO();
                $query = $db->getQuery(true);
                // Select some fields
                $query->select('id,greeting');
                // From the sasfe table
                $query->from('#__sasfe');
                return $query;
        }               
}

?>
