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

class SasfeModelMotivo extends JModelLegacy{

        public function insertarMotivo($titulo, $texto, $activo, $fechaCreacion){
           $db =& JFactory::getDBO();
           $tbl_sasfe_motivos = $db->getPrefix().'sasfe_motivos';

           $query = 'INSERT INTO '.$tbl_sasfe_motivos.' (titulo, texto, activo, fechaCreacion)
                     VALUES ("'.$titulo.'", "'.$texto.'", "'.$activo.'", "'.$fechaCreacion.'")';
           $db->setQuery($query);
           $db->query();
           $id = $db->insertid();

           return $id;
       }

       public function actualizarMotivo($titulo, $texto, $activo, $idMotivo){
           $db =& JFactory::getDBO();
           $tbl_sasfe_motivos = $db->getPrefix().'sasfe_motivos';

           $query = 'UPDATE '.$tbl_sasfe_motivos.' SET titulo="'.$titulo.'", texto="'.$texto.'", activo="'.$activo.'"
                     WHERE idMotivo = '.$idMotivo.' ';
           $db->setQuery($query);
           $db->query();
       }

       public function obtenerDatosMotivo($idMotivo){
          if($idMotivo>0){
            $db = JFactory::getDbo();
            $tbl_sasfe_motivos = $db->getPrefix().'sasfe_motivos';
            $query = "
                        SELECT a.*
                        FROM $tbl_sasfe_motivos as a
                        WHERE a.idMotivo =$idMotivo
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
