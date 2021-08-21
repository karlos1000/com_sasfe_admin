<?php
/** 
 * fecha: 19-11-13
 * company: company
 * @author Karlos
 */
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
jimport('joomla.application.component.modellist');
 
class SasfeModelListareventos extends JModelList{
       
        public function __construct($config = array())
        {   
                $config['filter_fields'] = array(
                        'idEvenCom',
                        'comentario',
                        'fechaHora',
                        'opcionId',
                        'agtVentasId',
                );
                parent::__construct($config);                               
        }
        
        protected function populateState($ordering = null, $direction = null) {
            // Initialise variables.
            $app = JFactory::getApplication('administrator');
                
            // Adjust the context to support modal layouts.
            if ($layout = JRequest::getVar('layout', 'default'))
            {
                    $this->context .= '.'.$layout;
            }
            
            //Filtros   
            $search = $this->getUserStateFromRequest($this->context.'.filter.search', 'filter_search');
            $this->setState('filter.search', $search);
                
            $idOpcion = $this->getUserStateFromRequest($this->context.'.filter.opcionTipoEventos', 'filter_tipoevento');
            $this->setState('filter.opcionTipoEventos', $idOpcion);

            //Fecha del
            $fechaDel = $this->getUserStateFromRequest($this->context.'.filter.fechaDel', 'filter_fechaDel');
            $this->setState('filter.fechaDel', $fechaDel);
            //Fecha al
            $fechaDel = $this->getUserStateFromRequest($this->context.'.filter.fechaAl', 'filter_fechaAl');
            $this->setState('filter.fechaAl', $fechaDel); 

            $idAtender = $this->getUserStateFromRequest($this->context.'.filter.opcionAtenderEvento', 'filter_atenderevento');
            $this->setState('filter.opcionAtenderEvento', $idAtender);
            
            $idAgtVentas = $this->getUserStateFromRequest($this->context.'.filter.opcionAgentesVenta', 'filter_agtev');
            $this->setState('filter.opcionAgentesVenta', $idAgtVentas);
                                                                                                                                                                                                              
            //Load the parameters.
            $params = JComponentHelper::getParams('com_sasfe');
            $this->setState('params', $params);

            // List state information.
            parent::populateState('idDatoProspecto', 'asc');
        }
               
        public function getItems()
	    {
            require_once(JPATH_COMPONENT.'/helpers/sasfehp.php' );

            if (!isset($this->items))
            {
                $search	= $this->getState('filter.search');		
                $ordering = $this->getState('list.ordering');
                $direction = $this->getState('list.direction');
                $limitstart = $this->getState('list.start');                
		        $limit = $this->getState('list.limit');
                $idTipoEvento = $this->getState('filter.opcionTipoEventos');
                $fechaDel = $this->getState('filter.fechaDel');
                $fechaAl = $this->getState('filter.fechaAl');  
                $idAtender = $this->getState('filter.opcionAtenderEvento');           

                // /Solo ocurre para el gerente de ventas
                $queryIdAgtV = "";                
                $idAgtVentas = $this->getState('filter.opcionAgentesVenta'); //Buscar por el id del agente de ventas
                if($idAgtVentas!=""){
                    $queryIdAgtV = " AND b.agtVentasId=".$idAgtVentas;
                }
                
                $this->userC = JFactory::getUser();
                $this->groups = JAccess::getGroupsByUser($this->userC->id, false);                                            
                $this->strgroupsCurrent = implode(",", $this->groups); 
                $idUsuarioJoomla = $this->userC->id;
                $db = JFactory::getDbo();                                                
                
                //Buscar ids de agentes de ventas (asesores) para que se muestren los eventos a su gte de ventas como solo lectura
                if(in_array("11", $this->groups)){                    
                   $idUsuarioJoomla = SasfehpHelper::obtIdsUsrDatosCatPorUsrIdGteJoomla($idUsuarioJoomla);
                }
                $usuarioIdQuery = ' AND a.usuarioId IN ('.$idUsuarioJoomla.') ';
                //Aplica en caso de ser administrador o direccion
                if(in_array("8", $this->groups) || in_array("10", $this->groups)){
                    $usuarioIdQuery = '';
                }            

                //NO APLICA
                // if($search){                                        
                //      //Compile the different search clauses.
                //     $search = str_replace("'","",$search);  //limpia el caracter ' de la cadena                  
                    
                //    $searches = array();                                         
                   
                //     //si es fecha valida entonces entra
                //     if(preg_match('/^\d{1,2}\/\d{1,2}\/\d{4}$/', $search)) {                        
                //         list($d,$m,$y) = explode('/', $search);
                //         $search = $y.'-'.$m.'-'.$d;
                //     }

                //     $searches[] = "a.nombre LIKE '%$search%' "; //Buscar por nombre prospectador
                //     $searches[] = "a.aPaterno LIKE '%$search%' "; //Buscar por apellidos prospectador
                //     $searches[] = "a.aManterno LIKE '%$search%' "; //Buscar por apellidos prospectador
                    
                //     $searchQuery = implode(' OR ', $searches);
                //     //$searchQuery = ' WHERE ' .$searchQuery;                   
                // }else{
                //     $searchQuery = '';
                // }
                                

                // if($searchQuery){                    
                //     $queryOpt = ' WHERE ' .$searchQuery;
                // }else{
                //     if($searchQuery){                        
                //         $queryOpt = ' WHERE ' .$searchQuery;
                //     }else{
                //         $queryOpt = '';
                //     }
                // }

                //Filtro rango de fechas
                if($fechaDel){
                    $fechaDel = SasfehpHelper::conversionFecha($fechaDel);
                    $fechaAl = SasfehpHelper::conversionFecha($fechaAl);
                    $fechasQuery = " AND ( DATE(a.fechaHora)>='".$fechaDel."' AND DATE(a.fechaHora)<='".$fechaAl."' )";
                }else{
                    $timeZone = SasfehpHelper::obtDateTimeZone();
                    $fechasQuery = " AND ( DATE(a.fechaHora)>='".$timeZone->fecha."' AND DATE(a.fechaHora)<='".$timeZone->fecha."' )";
                }                

                if($idTipoEvento){ 
                    $opcQuery = ' AND c.idTipoEvento = ' .$idTipoEvento;
                }else{
                    $opcQuery = '';
                }
                
                if($idAtender=="" || $idAtender==1){
                    $atendidoQuery = " AND (a.atendido IS NULL OR a.atendido='') ";
                }else{
                    //$atendidoQuery = " AND a.atendido=1 ";
                    $atendidoQuery = "";
                }
                // $atendidoQuery = "";

                // $atendidoQuery = " AND (a.atendido IS NULL OR a.atendido='') ";                
                // if($idAtender!="" && $idAtender>0){
                //     $atendidoQuery = " AND a.atendido=1 ";
                // }
                // echo "<br/>".$atendidoQuery."<br/>";


                //$queryOpt                
                $query = "
                        SELECT a.*, a.comentario as comentarioevcom, b.*, c.tipoEvento 
                        FROM #__sasfe_movimientosprospecto AS a 
                        LEFT JOIN #__sasfe_datos_prospectos AS b ON b.idDatoProspecto=a.datoProspectoId 
                        LEFT JOIN #__sasfe_tipo_eventos AS c ON c.idTipoEvento=a.tipoEventoId
                        WHERE a.opcionId=1 $atendidoQuery $usuarioIdQuery $fechasQuery  $opcQuery $queryIdAgtV
                      ";
                // echo $query;
                                
                $db->setQuery($query);
                $db->query();    
                $rows = $db->loadObjectList();                                                                  
                $this->items = $rows;
                
                $this->total = count($rows);
                if ($limitstart >= $this->total) {
                        $limitstart = $limitstart < $limit ? 0 : $limitstart - $limit;
                        $this->setState('list.start', $limitstart);
                }
                
                if ($ordering) {
                        if ($direction == 'asc') {
                                ksort($rows);                                    
                        }
                        else {
                                krsort($rows);
                        }
                }
                else {
                    if ($direction == 'asc') {
                            asort($rows);
                    }
                    else {
                            arsort($rows);
                    }
                }
                
                
                $this->items = array_slice($rows, $limitstart, $limit ? $limit : null);
                
            }
            return $this->items;                                  								                
	}

	/**
	 * Method to get the total number of items.
	 *
	 * @return	int	The total number of items.
	 * @since	1.6
	 */
	public function getTotal()
	{
		if (!isset($this->total))
		{
			$this->getItems();
		}
		return $this->total;             
	}        

    //Marcar un evento como atendido
    public function marcarAtendido($fechaAtendido, $comentarioAtendido, $idMovPros){
       $db = JFactory::getDBO();                                        
       $tbl_sasfe_movimientosprospecto = $db->getPrefix().'sasfe_movimientosprospecto';
       
       $query = "UPDATE $tbl_sasfe_movimientosprospecto SET atendido='1', fechaAtendido='$fechaAtendido', comentarioAtendido='$comentarioAtendido' 
                 WHERE idMovPros=$idMovPros ";
       // echo $query;
       $db->setQuery($query);
       $res = $db->query();
       return $db->getAffectedRows($res);
   }

   /**
    * Obtener datos del evento por su id
    */
   public function obtEventoPorId($idMovPros){
      $db = JFactory::getDbo();         
      $tbl_sasfe_movimientosprospecto = $db->getPrefix().'sasfe_movimientosprospecto';
      $tbl_sasfe_tiempo_recordatorios = $db->getPrefix().'sasfe_tiempo_recordatorios';

      $query = "
                SELECT a.*, b.tiempo, b.texto 
                FROM $tbl_sasfe_movimientosprospecto AS a
                LEFT JOIN $tbl_sasfe_tiempo_recordatorios AS b ON b.idTiempoRecordatorio=a.tiempoRecordatorioId
                WHERE a.idMovPros=$idMovPros
               ";
      // echo $query.'<br/>';
      $db->setQuery($query);
      $db->query();    
      $row = $db->loadObject();
      
      return $row;
   }   


   public function listaEventosFilter ($fechaFDel, $fechaFAl){
        $db = JFactory::getDbo();
        //Filtro rango de fechas
        $fechasQuery = "";
        if($fechaFDel){
            $fechasQuery = " AND ( DATE(a.fechaHora)>='".$fechaFDel."' AND DATE(a.fechaHora)<='".$fechaFAl."' )";
        }
        $query = "
                SELECT a.*, a.comentario as comentarioevcom, b.*, c.tipoEvento 
                FROM #__sasfe_movimientosprospecto AS a 
                LEFT JOIN #__sasfe_datos_prospectos AS b ON b.idDatoProspecto=a.datoProspectoId 
                LEFT JOIN #__sasfe_tipo_eventos AS c ON c.idTipoEvento=a.tipoEventoId
                WHERE a.opcionId=1  $fechasQuery
              ";
        // echo  $query;     
        $db->setQuery($query);
        $db->query();
        $result = $db->loadObjectList();    
        return $result;
   }


    // public function removerProspectoPorId($idsDatoProspecto){
    //     $db = JFactory::getDbo();                                                                                        
        
    //     foreach($idsDeptos as $id):                                                                
    //         $query = "DELETE FROM #__sasfe_datos_prospectos WHERE idDatoProspecto=$id ";                              
    //         $db->setQuery($query);
    //         $result[] = $db->query();                                                                    
    //     endforeach;
         
    //    return array("resultDel"=>$result);
         
    // }          
}

?>
