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

class SasfeModelCatgtedepto extends JModelLegacy{
        
        public function insertarGteDepto($gteVentasId, $departamentosId){
           $db =& JFactory::getDBO();                                        
           $tbl_sasfe_gerente_deptos = $db->getPrefix().'sasfe_gerente_deptos';
           
           $query = "INSERT INTO $tbl_sasfe_gerente_deptos (gteVentasId, departamentosId) 
                     VALUES ($gteVentasId, '$departamentosId')";
           $db->setQuery($query);                     
           $db->query();
           $id = $db->insertid();                
           
           return $id;                                             
       }
                              
       public function actualizarGteDepto($gteVentasId, $departamentosId, $idGteDepto){
           $db =& JFactory::getDBO(); 
           $tbl_sasfe_gerente_deptos = $db->getPrefix().'sasfe_gerente_deptos';           
           
           $query = "UPDATE $tbl_sasfe_gerente_deptos SET gteVentasId=$gteVentasId, departamentosId='$departamentosId'
                     WHERE idGteDepto=$idGteDepto ";
           $db->setQuery($query);                     
           $db->query();           
       }
       
        public function obtDatosCatgtedepto($idGteDepto){                                            
            $db = JFactory::getDbo();
            $tbl_sasfe_gerente_deptos = $db->getPrefix().'sasfe_gerente_deptos';
            
            $query = "
                        SELECT *
                        FROM $tbl_sasfe_gerente_deptos
                        WHERE idGteDepto=$idGteDepto
                      ";            
            $db->setQuery($query);
            $db->query();
            $rows = $db->loadObjectList();                                                                                
                                           
          return $rows;
       }                     
              
}

?>
