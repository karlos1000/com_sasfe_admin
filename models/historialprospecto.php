<?php
/** 
 * fecha: 19-11-13
 * company: company
 * @author Karlos
 */
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
jimport('joomla.application.component.modellist');
 
class SasfeModelHistorialprospecto extends JModelList{
       
        public function __construct($config = array())
        {   
                $config['filter_fields'] = array(
                        'idHistPros',
                        'estatusId',
                        'comentario',                    
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
                        
            //Rango de montos de credito
            $montocto1 = $this->getUserStateFromRequest($this->context.'.filter.montocto1', 'filter_montocto1');
            $this->setState('filter.montocto1', $montocto1);
            $montocto2 = $this->getUserStateFromRequest($this->context.'.filter.montocto2', 'filter_montocto2');
            $this->setState('filter.montocto2', $montocto2);

            $puntoshasta = $this->getUserStateFromRequest($this->context.'.filter.puntoshasta', 'filter_puntoshasta');
            $this->setState('filter.puntoshasta', $puntoshasta);

            $idTipoCto = $this->getUserStateFromRequest($this->context.'.filter.opcionTipoCreditos', 'filter_tipocto');
            $this->setState('filter.opcionTipoCreditos', $idTipoCto);
                                                                                                                                                                                                              
            //Load the parameters.
            $params = JComponentHelper::getParams('com_sasfe');
            $this->setState('params', $params);

            // List state information.
            parent::populateState('idHistPros', 'asc');
        }
               
        public function getItems()
	    {
                  
            require_once(JPATH_COMPONENT.'/helpers/sasfehp.php' );
                        
            if (!isset($this->items))
            {   
                $db = JFactory::getDbo();
                $ordering = $this->getState('list.ordering');
                $direction = $this->getState('list.direction');
                $limitstart = $this->getState('list.start');                
		        $limit = $this->getState('list.limit');

                $search = $this->getState('filter.search'); //Buscar por nombre o apellidos
                $montocto1 = str_replace(",","",str_replace("$","", $this->getState('filter.montocto1'))); //Buscar por monto de credito monto 1                
                $montocto2 = str_replace(",","",str_replace("$","", $this->getState('filter.montocto2'))); //Buscar por monto de credito monto 2
                $puntoshasta = $this->getState('filter.puntoshasta'); //Buscar por fecha puntos hasta                
                $idTipoCto = $this->getState('filter.opcionTipoCreditos'); //Buscar por el tipo de credito

                $idProspectador = JRequest::getVar('id'); //obtiene el id del prospectador                

                /*
                //Filtro montos de credito
                $montosCtoQuery = "";
                if($montocto1!="" && $montocto2!=""){
                    $montocto1Exp = explode(".", $montocto1);
                    $montocto2Exp = explode(".", $montocto2);
                    $montocto1 = $montocto1Exp[0];
                    $montocto2 = $montocto2Exp[0];                    
                    $montosCtoQuery = " AND ( a.montoCredito>=".$montocto1." AND a.montoCredito<=".$montocto2." ) ";
                }                
                
                //Filtro puntos hasta
                $fechaPHastaQuery = "";
                if($puntoshasta!=""){
                    //si es fecha valida entonces entra
                    if(preg_match('/^\d{1,2}\/\d{1,2}\/\d{4}$/', $puntoshasta)) {                        
                        list($d,$m,$y) = explode('/', $puntoshasta);
                        $fechaPHasta = SasfehpHelper::conversionFecha($puntoshasta);
                        $fechaPHastaQuery = " AND ( DATE(a.puntosHasta)='".$fechaPHasta."' )";
                    }                                        
                }
                //Filtro tipo de credito
                $tipoCtoQuery = "";
                if($idTipoCto!=""){
                    $tipoCtoQuery = " AND a.tipoCreditoId=".$idTipoCto;
                }
                
                $this->userC = JFactory::getUser();
                $this->groups = JAccess::getGroupsByUser($this->userC->id, false);                
                // echo "<pre>";print_r($this->groups);echo "</pre>"; //Obtener registros por el grupo    

                //Si el usuario pertenece al grupo gerentes prospeccion y gerentes ventas                
                if(in_array("11", $this->groups) || in_array("19", $this->groups)){
                    // $idUsuarioJoomla = SasfehpHelper::obtIdsUsrDatosCatPorUsrIdGteJoomla($this->userC->id); //id gerente prospeccion o ventas
                    // $idUsuarioJoomla = ($idUsuarioJoomla!="") ?$idUsuarioJoomla :0;
                    $idUsuarioJoomla=$this->userC->id;
                }else{
                    //Filtrar los prospectos por el id del usuario que lo creo solo (agentes de venta)
                    $idUsuarioJoomla=$this->userC->id;
                }
                //echo $idUsuarioJoomla.'<br/>';
                                        

                if($search){                                        
                     //Compile the different search clauses.
                    $search = str_replace("'","",$search);  //limpia el caracter ' de la cadena                  
                    
                    $searches = array();                                         
                   
                    //si es fecha valida entonces entra
                    if(preg_match('/^\d{1,2}\/\d{1,2}\/\d{4}$/', $search)) {                        
                        list($d,$m,$y) = explode('/', $search);
                        $search = $y.'-'.$m.'-'.$d;
                    }

                    $searches[] = "a.nombre LIKE '%$search%' "; //Buscar por nombre prospectador
                    $searches[] = "a.aPaterno LIKE '%$search%' "; //Buscar por apellidos prospectador
                    $searches[] = "a.aManterno LIKE '%$search%' "; //Buscar por apellidos prospectador

                    // $searches[] = "a.montoCredito LIKE '%$search%' "; //Buscar por apellidos prospectador
                                        
                    $searchQuery = implode(' OR ', $searches);
                    //$searchQuery = ' WHERE ' .$searchQuery;                   
                }else{
                    $searchQuery = '';
                }
                                
                if($searchQuery){                    
                    $queryOpt = ' WHERE ' .' ( '.$searchQuery .' ) ';
                }else{
                    if($searchQuery){                        
                        $queryOpt = ' WHERE ' .' ( '.$searchQuery .' ) ';
                    }else{
                        $queryOpt = '';
                    }
                }

                //Consulta si son repetidos                
                $queryRep = " AND a.duplicado=0 "; 
                $this->layout = JRequest::getVar('layout'); //obtiene el nombre del layout  
                if($this->layout=="repetidos"){
                    $queryRep = " AND a.duplicado=1 ";
                }

                //Obtener registros por el id del usuario que lo creo
                if($queryOpt!=''){                    
                    //Gerente ventas
                    if(in_array("11", $this->groups)){
                        $queryOpt .= ' AND a.gteVentasId IN ('.$idUsuarioJoomla.') '.$queryRep;
                    }
                    //Gerente de prospeccion
                    elseif(in_array("19", $this->groups)){
                        // $queryOpt .= ' AND a.agtVentasId IN ('.$idUsuarioJoomla.') '.$queryRep;
                        $queryOpt .= ' AND a.gteProspeccionId IN ('.$idUsuarioJoomla.') '.$queryRep;
                    }
                    //Agentes de venta
                    else{
                        // $queryOpt .= ' AND a.usuarioId IN ('.$idUsuarioJoomla.') '.$queryRep;
                        $queryOpt .= ' AND a.agtVentasId IN ('.$idUsuarioJoomla.') '.$queryRep;                        
                    }
                }else{
                    //Gerente ventas
                    if(in_array("11", $this->groups)){
                        $queryOpt .= ' WHERE a.gteVentasId IN ('.$idUsuarioJoomla.') '.$queryRep;
                    }
                    //Gerente de prospeccion
                    elseif(in_array("19", $this->groups)){
                        // $queryOpt .= ' WHERE a.agtVentasId IN ('.$idUsuarioJoomla.') '.$queryRep;
                        $queryOpt .= ' WHERE a.gteProspeccionId IN ('.$idUsuarioJoomla.') '.$queryRep;
                    }
                    //Agentes de venta
                    else{
                        // $queryOpt .= ' WHERE a.usuarioId IN ('.$idUsuarioJoomla.') '.$queryRep;
                        $queryOpt .= ' WHERE a.agtVentasId IN ('.$idUsuarioJoomla.') '.$queryRep;
                    }
                }  
                */              
                // $queryOpt  $montosCtoQuery  $tipoCtoQuery  $fechaPHastaQuery
                
                $queryProsp = "";
                if($idProspectador!=""){
                    $queryProsp = " WHERE a.datoProspectoId=$idProspectador ";
                }            

                $query = "
                        SELECT a.*, b.*, a.comentario as comentHistPros, a.fechaCreacion as fechaHistPros, a.estatusId as estatusIdHistPros
                        FROM #__sasfe_historialprospecto AS a
                        LEFT JOIN #__sasfe_datos_prospectos AS b ON b.idDatoProspecto=a.datoProspectoId
                        $queryProsp 
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

    public function removerProspectoPorId($idsDatoProspecto){
        $db = JFactory::getDbo();                                                                                        
        
        foreach($idsDatoProspecto as $id):                                                                
            $query = "DELETE FROM #__sasfe_datos_prospectos WHERE idDatoProspecto=$id ";                              
            // echo $query."<br/>";
            $db->setQuery($query);
            $result[] = $db->query();                                                                    
        endforeach;
         
       return array("resultDel"=>$result);
         
    }          
}

?>
