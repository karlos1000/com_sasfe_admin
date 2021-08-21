<?php
/** 
 * fecha: 19-11-13
 * company: company
 * @author Karlos
 */
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
jimport('joomla.application.component.modellist');
 
class SasfeModelSeldepartamentos extends JModelList{
       
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
                
                $this->depto_id = JRequest::getVar('depto_id');                                
                $idDpt = $this->depto_id;
                
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
                
                $queryDpts = "SELECT idDepartamento
                              FROM #__sasfe_departamentos 
                              WHERE fraccionamientoId IN ($idFracc)
                             ";                        
                $db->setQuery($queryDpts);
                $db->query();    
                $rowsDpts = $db->loadColumn(); 
                
//echo '<pre>';                
//    print_r($rowsDpts);
//echo '</pre>';
                                                
                $idsAllDpts = implode(',', $rowsDpts);
                // echo $idsAllDpts.'<br/>';
                $queryInfoGral = "
                          SELECT DISTINCT departamentoId, esReasignado, obsoleto
                          FROM #__sasfe_datos_generales 
                          WHERE departamentoId IN ($idsAllDpts) AND esHistorico=0  
                         ";                
                $db->setQuery($queryInfoGral);
                $db->query();    
                $rowsInfo_Gral = $db->loadObjectList();
                // echo '<pre>';print_r($rowsInfo_Gral);echo '</pre>';
                $arrDptsFound = array();                                
                //Quitar todos los departamentos que existan en la primera lista
                // y que obsoleto sea 0
                foreach($rowsInfo_Gral as $item){                   
                    if($item->obsoleto==0){
                        $arrDptsFound[] = $item->departamentoId;    
                    }                    
                }
                // echo '<pre>';print_r($arrDptsFound);echo '</pre>'; echo "total: ".count($arrDptsFound)."<br>";
                                                
                if(count($arrDptsFound)>0){                
                    foreach($arrDptsFound as $item){                   
                         foreach($rowsDpts as $itemInt){                
                             if($itemInt == $item){                             
                                 $index = array_search($itemInt, $rowsDpts);
                                 // echo $index.'<br/>';
                                 unset($rowsDpts[$index]);   
                                 break;
                             }                         
                         }
                    }
                }
                // echo '<pre>';print_r($rowsDpts);echo '</pre>'; echo "total: ".count($rowsDpts)."<br>";
                
                $idsDptCurrent = implode(',', $rowsDpts);               
                //Comprobar si tiene ids de los departamentos
                if($idsDptCurrent!=""){
                $query = "SELECT *
                          FROM #__sasfe_departamentos 
                          WHERE fraccionamientoId = $idFracc AND idDepartamento IN ($idsDptCurrent)
                          ";                                                   
                // echo "<br/>";
                // echo $idsDptCurrent.'<br/>';                                                    
                $db->setQuery($query);
                $db->query();    
                $rows = $db->loadObjectList(); 
                //echo $query;                                
                }else{
                  $rows = array();
                }                                                            
                
//echo '<pre>';
//print_r($rowsDpts);                
//print_r($rowsInfo_Gral);                
//print_r($rows);                
//echo '</pre>';
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
       
        
       public function historicoPrendido($depto_id, $idDatoGral)
       {                                  
           $db =& JFactory::getDBO();                                        
           $tbl_sasfe_datos_generales = $db->getPrefix().'sasfe_datos_generales';           
           
           $query = "UPDATE $tbl_sasfe_datos_generales SET esHistorico='1'       
                     WHERE idDatoGeneral = $idDatoGral AND departamentoId = $depto_id ";              
           $db->setQuery($query);                     
           $db->query();                      
       }            
       
       public function reasignadoPrendido($depto_id, $idDatoGral)
       {                                  
           $db =& JFactory::getDBO();                                        
           $tbl_sasfe_datos_generales = $db->getPrefix().'sasfe_datos_generales';           
           
           $query = "UPDATE $tbl_sasfe_datos_generales SET esReasignado='1'       
                     WHERE idDatoGeneral = $idDatoGral AND departamentoId = $depto_id ";              
           $db->setQuery($query);                     
           $db->query();                      
       } 
                   
       public function obsoletoPrendido($idDatoGral)
       {                                  
           $db =& JFactory::getDBO();                                        
           $tbl_sasfe_datos_generales = $db->getPrefix().'sasfe_datos_generales';           
           
           $query = "UPDATE $tbl_sasfe_datos_generales SET obsoleto='1'       
                     WHERE idDatoGeneral = $idDatoGral";              
           $db->setQuery($query);                     
           $db->query();                      
       }
       
}

?>
