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

class SasfeModelCattipoevento extends JModelLegacy{
        
        public function insertarTipoEvento($tipoEvento, $activo){
           $db =& JFactory::getDBO();                                        
           $tbl_sasfe_tipo_eventos = $db->getPrefix().'sasfe_tipo_eventos';
           
           $query = "INSERT INTO $tbl_sasfe_tipo_eventos (tipoEvento, activo) 
                     VALUES ('$tipoEvento', '$activo')";
           $db->setQuery($query);
           $db->query();
           $id = $db->insertid();
           
           return $id;                                             
       }
                              
       public function actualizarTipoEvento($tipoEvento, $activo, $idTipoEvento){
           $db =& JFactory::getDBO(); 
           $tbl_sasfe_tipo_eventos = $db->getPrefix().'sasfe_tipo_eventos';
           
           $query = "UPDATE $tbl_sasfe_tipo_eventos SET tipoEvento='$tipoEvento', activo='$activo' 
                     WHERE idTipoEvento = $idTipoEvento ";              
           $db->setQuery($query);                     
           $db->query();           
       }
       
       public function obtenerDatosTipoEvento($idTipoEvento){                                 
          if($idTipoEvento>0){
            $db = JFactory::getDbo();
            $tbl_sasfe_tipo_eventos = $db->getPrefix().'sasfe_tipo_eventos';
            $query = "
                        SELECT a.*
                        FROM $tbl_sasfe_tipo_eventos as a                        
                        WHERE a.idTipoEvento =$idTipoEvento
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
