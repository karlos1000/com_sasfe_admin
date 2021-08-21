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

class SasfeModelCatfraccionamiento extends JModelLegacy{
        
        public function insertarFracc($nombre, $imagen, $activo){                                  
           $db =& JFactory::getDBO();                                        
           $tbl_sasfe_fraccionamientos = $db->getPrefix().'sasfe_fraccionamientos';
           
           $query = "INSERT INTO $tbl_sasfe_fraccionamientos (nombre, imagen, activo) 
                     VALUES ('$nombre', '$imagen', '$activo')";              
           $db->setQuery($query);                     
           $db->query();
           $id = $db->insertid();                
           
           return $id;                                             
       }
                              
       public function actualizarFracc($nombre, $imagen, $activo, $idFracc){
           $db =& JFactory::getDBO(); 
           $tbl_sasfe_fraccionamientos = $db->getPrefix().'sasfe_fraccionamientos';
           
           $query = "UPDATE $tbl_sasfe_fraccionamientos SET nombre='$nombre', imagen='$imagen', activo='$activo' 
                     WHERE idFraccionamiento = $idFracc ";              
           $db->setQuery($query);                     
           $db->query();           
       }
       
        public function obtenerDatosFracc($idFracc){                      
           
           if($idFracc>0){            
            $db = JFactory::getDbo();
            $tbl_sasfe_fraccionamientos = $db->getPrefix().'sasfe_fraccionamientos';
            $query = "
                        SELECT a.*
                        FROM $tbl_sasfe_fraccionamientos as a                        
                        WHERE a.idFraccionamiento =$idFracc
                      ";            
            $db->setQuery($query);
            $db->query();
            $rows = $db->loadObjectList();                                                                                
                       
          }else{
                $rows = array();  
          }
                      
          return $rows;
       }                     
       
       public function ObtNombreImgFracc($idFracc){                                  
           $db =& JFactory::getDBO();                                        
           $tbl_sasfe_fraccionamientos = $db->getPrefix().'sasfe_fraccionamientos';
           
           $query = "
                SELECT imagen FROM $tbl_sasfe_fraccionamientos WHERE idFraccionamiento=$idFracc                                                                     
               ";
           $db->setQuery($query);                     
           $db->query();
           $nombreImg = $db->loadResult();
           
           return $nombreImg;                                             
       }
}

?>
