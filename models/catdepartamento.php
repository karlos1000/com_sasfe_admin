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

class SasfeModelCatdepartamento extends JModelLegacy{
        
        public function insertarDepto($idFracc, $numero, $precio){                                  
           $db =& JFactory::getDBO();                                        
           $tbl_sasfe_departamentos = $db->getPrefix().'sasfe_departamentos';
           
           $query = "INSERT INTO $tbl_sasfe_departamentos (fraccionamientoId, numero, precio) 
                     VALUES ($idFracc, '$numero', $precio)";              
           $db->setQuery($query);                     
           $db->query();
           $id = $db->insertid();                
           
           return $id;                                             
       }
                              
       public function actualizarDepto($idFracc, $numero, $precio, $idDepto){
           $db =& JFactory::getDBO(); 
           $tbl_sasfe_departamentos = $db->getPrefix().'sasfe_departamentos';
           
           $query = "UPDATE $tbl_sasfe_departamentos SET fraccionamientoId=$idFracc, numero='$numero', precio=$precio 
                     WHERE idDepartamento = $idDepto ";              
           $db->setQuery($query);                     
           $db->query();           
       }
       
        public function obtenerDatosDepto($idDepto){                      
           
           if($idDepto>0){            
            $db = JFactory::getDbo();
            $tbl_sasfe_departamentos = $db->getPrefix().'sasfe_departamentos';
            $query = "
                        SELECT a.*
                        FROM $tbl_sasfe_departamentos as a                        
                        WHERE a.idDepartamento = $idDepto
                      ";            
            $db->setQuery($query);
            $db->query();
            $rows = $db->loadObjectList();                                                                                
                       
          }else{
                $rows = array();  
          }
                      
          return $rows;
       }                     
              
}

?>
