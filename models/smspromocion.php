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

class SasfeModelSmspromocion extends JModelLegacy{
        
        public function insertarPromocion($titulo, $texto, $activo, $fechaCreacion){
           $db =& JFactory::getDBO();                                        
           $tbl_sasfe_sms_promociones = $db->getPrefix().'sasfe_sms_promociones';
           
           $query = 'INSERT INTO '.$tbl_sasfe_sms_promociones.' (titulo, texto, activo, fechaCreacion) 
                     VALUES ("'.$titulo.'", "'.$texto.'", "'.$activo.'", "'.$fechaCreacion.'")';
           $db->setQuery($query);
           $db->query();
           $id = $db->insertid();
           
           return $id;                                             
       }
                              
       public function actualizarPromocion($titulo, $texto, $activo, $idPromo){
           $db =& JFactory::getDBO();
           $tbl_sasfe_sms_promociones = $db->getPrefix().'sasfe_sms_promociones';
           
           $query = 'UPDATE '.$tbl_sasfe_sms_promociones.' SET titulo="'.$titulo.'", texto="'.$texto.'", activo="'.$activo.'" 
                     WHERE idPromo = '.$idPromo.' ';              
           $db->setQuery($query);                     
           $db->query();           
       }
       
       public function obtenerDatosPromocion($idPromo){
          if($idPromo>0){
            $db = JFactory::getDbo();
            $tbl_sasfe_sms_promociones = $db->getPrefix().'sasfe_sms_promociones';
            $query = "
                        SELECT a.*
                        FROM $tbl_sasfe_sms_promociones as a                        
                        WHERE a.idPromo =$idPromo
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
