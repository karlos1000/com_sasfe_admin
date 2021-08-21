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

class SasfeModelSmsmensaje extends JModelLegacy{
        
        public function insertarMensaje($titulo, $texto, $activo, $tipoId, $fechaHora){
           $db =& JFactory::getDBO();                                        
           $tbl_sasfe_sms_mensajes = $db->getPrefix().'sasfe_sms_mensajes';
           
           $query = 'INSERT INTO '.$tbl_sasfe_sms_mensajes.' (titulo, tipoId, texto, activo, fechaCreacion) 
                     VALUES ("'.$titulo.'", '.$tipoId.', "'.$texto.'", "'.$activo.'", "'.$fechaHora.'")';
           // echo $query.'<br/>';
           // exit();
           $db->setQuery($query);
           $db->query();
           $id = $db->insertid();
           
           return $id;                                             
       }
                              
       public function actualizarMensaje($titulo, $texto, $activo, $tipoId, $idMensaje){
           $db =& JFactory::getDBO();
           $tbl_sasfe_sms_mensajes = $db->getPrefix().'sasfe_sms_mensajes';
           
           $query = 'UPDATE '.$tbl_sasfe_sms_mensajes.' SET titulo="'.$titulo.'", tipoId='.$tipoId.', texto="'.$texto.'", activo="'.$activo.'"
                     WHERE idMensaje = '.$idMensaje.' ';
           $db->setQuery($query);                     
           $db->query();           
       }
       
       public function obtenerDatosMensaje($idMensaje){
          if($idMensaje>0){
            $db = JFactory::getDbo();
            $tbl_sasfe_sms_mensajes = $db->getPrefix().'sasfe_sms_mensajes';
            $query = "
                        SELECT a.*
                        FROM $tbl_sasfe_sms_mensajes as a                        
                        WHERE a.idMensaje =$idMensaje
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
