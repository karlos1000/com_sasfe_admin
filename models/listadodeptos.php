<?php
/** 
 * fecha: 19-11-13
 * company: company
 * @author Karlos
 */
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
jimport('joomla.application.component.modellist');
 
class SasfeModelListadodeptos extends JModelList{
       
        public function __construct($config = array())
        {   
                $config['filter_fields'] = array(
                        'nombre',
                        'numero',                                                
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
               
            $search = $this->getUserStateFromRequest($this->context.'.filter.search', 'filter_search');
            $this->setState('filter.search', $search);
                                                                                                                                                                                                              
            //Load the parameters.
            $params = JComponentHelper::getParams('com_sasfe');
            $this->setState('params', $params);

            // List state information.
            parent::populateState('idDepartamento', 'asc');
        }
               
        public function getItems()
	{
                        
            if (!isset($this->items))
            {
                $search	= $this->getState('filter.search');		
                $ordering = $this->getState('list.ordering');
                $direction = $this->getState('list.direction');
                $limitstart = $this->getState('list.start');                
		$limit = $this->getState('list.limit');                                    
                
                $this->userC = JFactory::getUser();
                $this->groups = JAccess::getGroupsByUser($this->userC->id, false);                                            
                $this->strgroupsCurrent = implode(",", $this->groups); 
                $this->idFracc = JRequest::getVar('idFracc');                                
                $idFracc = $this->idFracc;
                
                $db = JFactory::getDbo();                                                
                
                
                if($search){                                        
                     //Compile the different search clauses.
                    $search = str_replace("'","",$search);  //limpia el caracter ' de la cadena                                      
                    $searches = array();                                                                                                                                                                                   
                    
                    $searches[] = "( a.numero LIKE '%$search%' "; //Buscar por nombre de cliente
                    $searches[] = "CONCAT(c.nombre, ' ', c.aPaterno, ' ', c.aManterno) LIKE '%$search%' )"; //Buscar por nombre de cliente                    
                    
                    $searchQuery = implode(' OR ', $searches);
                    $searchQuery = ' AND ' .$searchQuery;
                    
                    //$andHistorico = ' AND esHistorico=0'; //Parametro para no mostrar campos en blanco
                }else{
                    $searchQuery = '';
                    //$andHistorico = '';
                }                                
                                                               
                //$orderBy = " ORDER BY b.idDatoGeneral DESC LIMIT 0,1 "; //Implementado el 20/09/17
                $orderBy = "";
                if($search){
                    $query = "                        
                            SELECT DISTINCT a.*, b.idDatoGeneral, b.esHistorico
                            FROM #__sasfe_departamentos AS a
                            LEFT JOIN #__sasfe_datos_generales AS b ON b.departamentoId = a.idDepartamento
                            LEFT JOIN #__sasfe_datos_clientes AS c ON c.datoGeneralId = b.idDatoGeneral
                            LEFT JOIN #__sasfe_datos_catalogos AS d ON d.idDato = b.idEstatus
                            WHERE a.fraccionamientoId IN ($idFracc)  
                            $searchQuery                           
                            $orderBy                         
                          ";         
                }else{
                    $query = " SELECT * FROM #__sasfe_departamentos WHERE fraccionamientoId IN ($idFracc) ";                                      
                }
                //echo $query;
                //echo '<br/><br/>';
                                
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
        
        public function getDataDpts()
	{
                        
            if (!isset($this->dpts))
            {
                $search	= $this->getState('filter.search');		
                $ordering = $this->getState('list.ordering');
                $direction = $this->getState('list.direction');
                $limitstart = $this->getState('list.start');                
		$limit = $this->getState('list.limit');    
                                                      
                $this->userC = JFactory::getUser();
                $this->groups = JAccess::getGroupsByUser($this->userC->id, false);                                            
                $this->strgroupsCurrent = implode(",", $this->groups); 
                $this->idFracc = JRequest::getVar('idFracc');                                
                $idFracc = $this->idFracc;
                
                $db = JFactory::getDbo();                                                
                                
                if($search){                                        
                     //Compile the different search clauses.
                    $search = str_replace("'","",$search);  //limpia el caracter ' de la cadena                                      
                    $searches = array();                                                                               
                                                                                
                    $searches[] = "( a.numero LIKE '%$search%' "; //Buscar por nombre de cliente
                    $searches[] = " CONCAT(c.nombre, ' ', c.aPaterno, ' ', c.aManterno) LIKE '%$search%' )"; //Buscar por nombre de cliente
                    
                    $searchQuery = implode(' OR ', $searches);
                    $searchQuery = ' AND ' .$searchQuery;
                    
                    //$andHistorico = ' AND esHistorico=0';
                }else{
                    $searchQuery = '';
                    //$andHistorico = '';
                }
                                               
                
                    $query = "                        
                            SELECT a.*, b.fechaCierre, b.fechaApartado, CONCAT(c.nombre, ' ', c.aPaterno, ' ', c.aManterno) as nombreC, c.tipoCreditoId, d.nombre as estatus, b.esHistorico, b.idEstatus, b.idDatoGeneral, (select count(esHistorico) from #__sasfe_datos_generales where departamentoId = a.idDepartamento and esHistorico = 1) counter, (select nombre from #__sasfe_datos_catalogos where idDato=c.tipoCreditoId) as tipoCredito, (select nombre from #__sasfe_datos_catalogos where idDato=b.idEstatus) as estatus, b.fechaDTU,
                            b.idAsesor, (select nombre from #__sasfe_datos_catalogos where idDato=b.idAsesor) as nAsesor,
                            b.idGerenteVentas, (select nombre from #__sasfe_datos_catalogos where idDato=b.idGerenteVentas) as nGteVentas,
                            b.idPropectador, (select nombre from #__sasfe_datos_catalogos where idDato=b.idPropectador) as nProspectador 
                            FROM #__sasfe_departamentos AS a 
                            LEFT JOIN #__sasfe_datos_generales AS b ON b.departamentoId = a.idDepartamento 
                            LEFT JOIN #__sasfe_datos_clientes AS c ON c.datoGeneralId = b.idDatoGeneral 
                            LEFT JOIN #__sasfe_datos_catalogos AS d ON d.idDato = b.idEstatus 
                            WHERE a.fraccionamientoId IN ($idFracc) $searchQuery
                            ORDER BY b.idDatoGeneral DESC
                          ";                   
                 //echo $query;
                                
                $db->setQuery($query);
                $db->query();    
                $this->dpts = $db->loadObjectList();                                                                   
          }                  
            return $this->dpts;                           								                
	 
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
                        $this->getDataDpts();                        
		}
		return $this->total;             
	}        
        
}

?>
