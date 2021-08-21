<?php
/**
 * fecha: 06-10-20
 * company: company
 * @author Karlos
 */
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
// import Joomla modelform library
jimport('joomla.application.component.model');

class SasfeModelCatconfiguraciones extends JModelLegacy{

       public function actualizarDatosPorcentaje($pctUno, $pctDos, $idPorcentaje){
           $db =& JFactory::getDBO();
           $tbl_sasfe_porcentajes = $db->getPrefix().'sasfe_porcentajes';

           $query = "UPDATE $tbl_sasfe_porcentajes SET pctUno=$pctUno, pctDos=$pctDos
                     WHERE idPorcentaje = $idPorcentaje ";
           $db->setQuery($query);
           $db->query();
       }

        public function obtPorcentajeAsesorProsSinPreventa($asesProsp, $esPreventa){
            $db = JFactory::getDbo();
            $tbl_sasfe_porcentajes = $db->getPrefix().'sasfe_porcentajes';

            $query = "
                        SELECT *
                        FROM $tbl_sasfe_porcentajes
                        WHERE asesProsp = $asesProsp AND esPreventa = $esPreventa
                      ";
            $db->setQuery($query);
            $db->query();
            $rows = $db->loadObjectList();

          return $rows;
       }

       //obtener porcentajes de asesor sin preventa
       public function obtPorcentajeAsesorProsp($esAsesPros){
            $db = JFactory::getDbo();
            $tbl_sasfe_porcentajes_esp = $db->getPrefix().'sasfe_porcentajes_esp';

            //WHERE asesProsp = $asesProsp AND esPreventa = $esPreventa
            if($esAsesPros==1){
                $query = "
                        SELECT *
                        FROM $tbl_sasfe_porcentajes_esp
                        WHERE esAsesPros=$esAsesPros
                      ";
            }
            if($esAsesPros==2){
                $query = "
                        SELECT idPct, titulo, apartado, escritura, mult, esAsesPros
                        FROM $tbl_sasfe_porcentajes_esp
                        WHERE esAsesPros=$esAsesPros
                      ";
            }

            $db->setQuery($query);
            $db->query();
            $rows = $db->loadObjectList();

          return $rows;
       }

}

?>
