<?php
// No direct access.
defined('_JEXEC') or die;
jimport('joomla.application.component.modellist');

class SasfeModelListadohistorial extends JModelList
{
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
            //obtiene valor de variable depto_id, idFracc
            $depto_id = JRequest::getInt('depto_id');                                
            $this->setState('filter.depto_id', $depto_id);                 
            
            $idFracc = JRequest::getInt('idFracc');                                
            $this->setState('filter.idFracc', $idFracc);                 
                
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
                $depto_id = $this->getState('filter.depto_id');
                $idFracc = $this->getState('filter.idFracc');
                
                $ordering = $this->getState('list.ordering');
                $direction = $this->getState('list.direction');
                $limitstart = $this->getState('list.start');                
		$limit = $this->getState('list.limit');    
                                                      
                $this->userC = JFactory::getUser();
                $this->groups = JAccess::getGroupsByUser($this->userC->id, false);                                            
                $this->strgroupsCurrent = implode(",", $this->groups); 
                
                $db = JFactory::getDbo();                                                
                
                if($search){                                        
                     //Compile the different search clauses.
                    $search = str_replace("'","",$search);  //limpia el caracter ' de la cadena                                      
                    $searches = array();                                                                               
                                                                                
                    $searches[] = "c.nombre LIKE '%$search%' "; //Buscar por id del fraccionamiento
                    $searches[] = "a.nombre LIKE '%$search%' "; //Buscar por nombre de fraccionamiento                                       
                    
                    $searchQuery = implode(' OR ', $searches);
                    $searchQuery = ' AND ' .$searchQuery;
                }else{
                    $searchQuery = '';
                }                              
                
                    $query = "                        
                            SELECT a.*, b.fechaCierre, b.fechaApartado, CONCAT(c.nombre,' ', c.aPaterno, ' ', c.aManterno) as nombreC , c.tipoCreditoId, d.nombre as estatus, b.esHistorico, b.idEstatus, b.idDatoGeneral, b.esReasignado,
                            b.obsoleto, b.datoProspectoId 
                            FROM #__sasfe_departamentos AS a
                            LEFT JOIN #__sasfe_datos_generales AS b ON b.departamentoId = a.idDepartamento
                            LEFT JOIN #__sasfe_datos_clientes AS c ON c.datoGeneralId = b.idDatoGeneral
                            LEFT JOIN #__sasfe_datos_catalogos AS d ON d.idDato = b.idEstatus
                            WHERE a.fraccionamientoId IN ($idFracc) AND esHistorico=1 AND b.departamentoId IN ($depto_id)
                            ORDER BY b.idDatoGeneral
                            $searchQuery                            
                          ";                                       
                   //echo $query;
                                
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
}
