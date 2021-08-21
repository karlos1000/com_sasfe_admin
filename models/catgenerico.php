<?php
/** 
 * fecha: 19-11-13
 * company: company
 * @author Karlos
 */
// No direct access to this file
defined('_JEXEC') or die('Restricted access'); 
// import Joomla modelform library
jimport('joomla.application.component.model');

class SasfeModelCatgenerico extends JModelLegacy{
        
        public function insertarDatosCatalogos($catalogoId, $nombre, $default, $activo, $usuarioIdJoomla, $usuarioIdGteJoomla){                                  
           $db =& JFactory::getDBO();                                        
           $tbl_sasfe_datos_catalogos = $db->getPrefix().'sasfe_datos_catalogos';
           
           $query = "INSERT INTO $tbl_sasfe_datos_catalogos (catalogoId, nombre, pordefault, activo, usuarioIdJoomla, usuarioIdGteJoomla) 
                     VALUES ($catalogoId, '$nombre', '$default', '$activo', $usuarioIdJoomla, $usuarioIdGteJoomla)";              
           // echo "es: ".$query.'<br/>';
           // exit();
           $db->setQuery($query);                     
           $db->query();
           $id = $db->insertid();                
           
           return $id;                                             
       }
                              
       public function actualizarDatosCatalogos($catalogoId, $nombre, $default, $activo, $usuarioIdJoomla, $usuarioIdGteJoomla, $idDato){
           $db =& JFactory::getDBO(); 
           $tbl_sasfe_datos_catalogos = $db->getPrefix().'sasfe_datos_catalogos';
           //catalogoId=$catalogoId
           
           $query = "UPDATE $tbl_sasfe_datos_catalogos SET nombre='$nombre', pordefault='$default', activo='$activo',
                            usuarioIdJoomla=$usuarioIdJoomla, usuarioIdGteJoomla=$usuarioIdGteJoomla
                     WHERE idDato = $idDato ";              
           $db->setQuery($query);                     
           $db->query();           
       }
       
        public function obtDatosCatalogo($idDato){                                            
            $db = JFactory::getDbo();
            $tbl_sasfe_datos_catalogos = $db->getPrefix().'sasfe_datos_catalogos';
            
            $query = "
                        SELECT *
                        FROM $tbl_sasfe_datos_catalogos
                        WHERE idDato =$idDato
                      ";            
            $db->setQuery($query);
            $db->query();
            $rows = $db->loadObjectList();                                                                                
                                           
          return $rows;
       }                     
              
}

?>
