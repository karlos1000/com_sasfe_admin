<?php
/** 
 * fecha: 11-08-14
 * company: Framelova
 * @author Carlos
 */
// No direct access
defined('_JEXEC') or die('Restricted access');
// import Joomla table library
jimport('joomla.database.table');
 
class SasfeTableSasfe extends JTable {
    
        /**
         * Constructor
         *
         * @param object Database connector object
         */
        function __construct(&$db) 
        {
				//cambiar el nombre de la tabla por el nombre del componente principal
                parent::__construct('#__sasfe', 'id', $db);
        }
}

?>
