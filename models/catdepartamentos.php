<?php
/** 
 * fecha: 19-11-13
 * company: company
 * @author Karlos
 */
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
jimport('joomla.application.component.modellist');
 
class SasfeModelCatdepartamentos extends JModelList{
       
        public function __construct($config = array())
        {   
                $config['filter_fields'] = array(
                        'idDepartamento',
                        'fraccionamientoId',                        
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
            
            $idFracc = $this->getUserStateFromRequest($this->context.'.filter.sasfeFraccionamientos', 'filter_fracc');
            $this->setState('filter.sasfeFraccionamientos', $idFracc);
                                                                                                                                                                                                              
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
                $idFracc = $this->getState('filter.sasfeFraccionamientos');                                      
                
                $this->userC = JFactory::getUser();
                $this->groups = JAccess::getGroupsByUser($this->userC->id, false);                                            
                $this->strgroupsCurrent = implode(",", $this->groups); 
            
                $db = JFactory::getDbo();                                                
                
                if($search){                                        
                     //Compile the different search clauses.
                    $search = str_replace("'","",$search);  //limpia el caracter ' de la cadena                  
                    
                   $searches = array();                                         
                   
                    //si es fecha valida entonces entra
                    if(preg_match('/^\d{1,2}\/\d{1,2}\/\d{4}$/', $search)) {                        
                        list($d,$m,$y) = explode('/', $search);
                        $search = $y.'-'.$m.'-'.$d;
                    }
                                                                                
                    //$searches[] = "a.idDepartamento LIKE '%$search%' "; //Buscar por id de depto
                    $searches[] = "b.nombre LIKE '%$search%' "; //Buscar por nombre de fraccionamiento                                       
                    $searches[] = "a.numero LIKE '%$search%' "; //Buscar por numero de depto                                                           
                    
                    $searchQuery = implode(' OR ', $searches);
                    //$searchQuery = ' WHERE ' .$searchQuery;                   
                }else{
                    $searchQuery = '';
                }
                
                if($idFracc){ 
                    $fraccQuery = ' b.idFraccionamiento = ' .$idFracc;
                }else{
                    $fraccQuery = '';
                }
                
                if($searchQuery && $fraccQuery){                    
                    $queryOpt = ' WHERE ' .$searchQuery . ' AND ' .$fraccQuery;                  
                }else{
                    if($searchQuery){                        
                        $queryOpt = ' WHERE ' .$searchQuery;
                    }else{
                        $queryOpt = '';
                    }

                    if($fraccQuery){                       
                       $queryOpt = ' WHERE ' .$fraccQuery;
                    }
                }                                                
                
                
                //ORDER BY a.idFraccionamiento                
                    $query = "
                            SELECT a.*, b.nombre as nombre_fracc
                            FROM #__sasfe_departamentos as a                            
                            LEFT JOIN #__sasfe_fraccionamientos as b ON b.idFraccionamiento = a.fraccionamientoId
                            $queryOpt
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
                
        public function removerDeptoPorIds($idsDeptos){
            $db = JFactory::getDbo();                                                                                        
            
            foreach($idsDeptos as $id):                                                                
                $query = "DELETE FROM #__sasfe_departamentos WHERE idDepartamento=$id ";                              
                $db->setQuery($query);
                $result[] = $db->query();                                                                    
            endforeach;                          
             
           return array("resultDel"=>$result);
             
        }          
}

?>
