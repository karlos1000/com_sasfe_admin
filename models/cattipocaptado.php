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

class SasfeModelCattipocaptado extends JModelLegacy{
        
        public function insertarTipoCaptado($tipoCaptado, $activo){
           $db =& JFactory::getDBO();                                        
           $tbl_sasfe_tipo_captados = $db->getPrefix().'sasfe_tipo_captados';
           
           $query = "INSERT INTO $tbl_sasfe_tipo_captados (tipoCaptado, activo) 
                     VALUES ('$tipoCaptado', '$activo')";
           $db->setQuery($query);
           $db->query();
           $id = $db->insertid();
           
           return $id;                                             
       }
                              
       public function actualizarTipoCaptado($tipoCaptado, $activo, $idTipoCaptado){
           $db =& JFactory::getDBO(); 
           $tbl_sasfe_tipo_captados = $db->getPrefix().'sasfe_tipo_captados';
           
           $query = "UPDATE $tbl_sasfe_tipo_captados SET tipoCaptado='$tipoCaptado', activo='$activo' 
                     WHERE idTipoCaptado = $idTipoCaptado ";              
           $db->setQuery($query);                     
           $db->query();           
       }
       
       public function obtenerDatosTipoCaptado($idTipoCaptado){                                 
          if($idTipoCaptado>0){
            $db = JFactory::getDbo();
            $tbl_sasfe_tipo_captados = $db->getPrefix().'sasfe_tipo_captados';
            $query = "
                        SELECT a.*
                        FROM $tbl_sasfe_tipo_captados as a                        
                        WHERE a.idTipoCaptado =$idTipoCaptado
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
