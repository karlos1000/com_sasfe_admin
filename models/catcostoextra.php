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

class SasfeModelCatcostoextra extends JModelLegacy{
        
        public function insertarDatosCatalogosCE($catalogoId, $idFracc, $nombre, $costo, $activo){                                  
           $db =& JFactory::getDBO();                                        
           $tbl_sasfe_catalogo_costoextra = $db->getPrefix().'sasfe_catalogo_costoextra';
           
           $query = "INSERT INTO $tbl_sasfe_catalogo_costoextra (catalogoId, fraccionamientoId, nombre, costo, activo) 
                     VALUES ($catalogoId, $idFracc, '$nombre', $costo, '$activo')";              
           $db->setQuery($query);                     
           $db->query();
           $id = $db->insertid();                
            
           return $id;                                             
       }
                              
       public function actualizarDatosCatalogosCE($catalogoId, $idFracc, $nombre, $costo, $activo, $idDato){
           $db =& JFactory::getDBO(); 
           $tbl_sasfe_catalogo_costoextra = $db->getPrefix().'sasfe_catalogo_costoextra';           
           
           $query = "UPDATE $tbl_sasfe_catalogo_costoextra SET fraccionamientoId=$idFracc, nombre='$nombre', costo=$costo, activo='$activo' 
                     WHERE idDatoCE = $idDato ";              
           $db->setQuery($query);                     
           $db->query();
           
           echo $query;
       }
       
        public function obtDatosCatalogoCE($idDato){                                            
            $db = JFactory::getDbo();
            $tbl_sasfe_catalogo_costoextra = $db->getPrefix().'sasfe_catalogo_costoextra';           
            
            $query = "
                        SELECT *
                        FROM $tbl_sasfe_catalogo_costoextra
                        WHERE idDatoCE = $idDato
                      ";            
            $db->setQuery($query);
            $db->query();
            $rows = $db->loadObjectList();                                                                                
                                           
          return $rows;
       }                     
              
}

?>
