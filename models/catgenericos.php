<?php
/** 
 * fecha: 19-11-13
 * company: company
 * @author Karlos
 */
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
jimport('joomla.application.component.modellist');
 
class SasfeModelCatgenericos extends JModelList{
       
        public function __construct($config = array())
        {   
                $config['filter_fields'] = array(
                        'idDato',
                        'catalogoId',                        
                        'nombre',                        
                        'activo',                        
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
            parent::populateState('idDato', 'asc');
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
                $this->idCat = JRequest::getVar('id_cat');                                
                $idCat = $this->idCat;
                
                $db = JFactory::getDbo();                                                                
                if($search){                    
                     //Compile the different search clauses.
                    $search = str_replace("'","",$search);  //limpia el caracter ' de la cadena                  
                    
                   $searches = array();                                         
                                                                                                   
                    $searches[] = "idDato LIKE '%$search%' "; //Buscar por id del dato                    
                    $searches[] = "nombre LIKE '%$search%' "; //Busqueda por nombre                    
                    
                    $searchQuery = implode(' OR ', $searches);
                    $searchQuery = ' AND ' .' ('.$searchQuery.')';
                }else{
                    $searchQuery = '';
                }                
                //ORDER BY a.idFraccionamiento                                
                    $query = "
                            SELECT *
                            FROM #__sasfe_datos_catalogos  WHERE catalogoId IN ($idCat)                                                        
                            $searchQuery                            
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
                
        public function removerPorIdDato($idsDato){
            $db = JFactory::getDbo();                                                                                        
                       
            foreach($idsDato as $id):                                                                                                                
                $query = "DELETE FROM #__sasfe_datos_catalogos WHERE idDato=$id ";                              
                $db->setQuery($query);
                $result[] = $db->query();                                                                    
            endforeach;                          
             
             return array("resultDel"=>$result);            
        }          
}

?>
