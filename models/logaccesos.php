<?php
/** 
 * fecha: 19-11-13
 * company: company
 * @author Karlos
 */
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
jimport('joomla.application.component.modellist');
 
class SasfeModelLogaccesos extends JModelList{
       
        public function __construct($config = array())
        {   
                $config['filter_fields'] = array(
                        'idLog',
                        'idUsuario',
                        'idDpt',
                        'idFracc',
                        'fechaHora'
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
            require_once(JPATH_COMPONENT.'/helpers/sasfehp.php' );
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
                
                $db = JFactory::getDbo();                                                
                $this->groups = JAccess::getGroupsByUser($this->userC->id, false);                
                // echo "<pre>";print_r($this->groups);echo "</pre>"; //Obtener registros por el grupo                    
                //Si el usuario pertenece al grupo gerentes prospeccion y gerentes ventas
                //obtener todos los ids de usuarios que                 
                $filGrupos = "";
                if(in_array("11", $this->groups) || in_array("19", $this->groups)){                    
                    $idLogueado = $this->userC->id;                    
                    $ids = SasfehpHelper::obtIdsUsrDatosCatPorUsrIdGteJoomla($this->userC->id);
                    // echo $ids;
                    if($ids!=""){
                        $filGrupos = " WHERE a.idUsuario IN ($ids,$idLogueado) ";
                    }                    
                }
                
                /*
                if($search){                                                            
                    $search = str_replace("'","",$search);  //limpia el caracter ' de la cadena                                      
                    $searches = array();                                                                               
                                                                                
                    $searches[] = "c.nombre LIKE '%$search%' "; //Buscar por id del fraccionamiento
                    $searches[] = "a.nombre LIKE '%$search%' "; //Buscar por nombre de fraccionamiento                                       
                    
                    $searchQuery = implode(' OR ', $searches);
                    $searchQuery = ' AND ' .$searchQuery;
                }else{
                    $searchQuery = '';
                }
                */
                
                //ORDER BY a.idFraccionamiento               
                $query = "                        
                            SELECT a.*, b.nombre as fracc, c.numero ,d.name as nombreUsuario
                            FROM #__sasfe_log_accesos as a 
                            LEFT JOIN #__sasfe_fraccionamientos as b ON b.idFraccionamiento = a.idFracc
                            LEFT JOIN #__sasfe_departamentos as c ON c.idDepartamento = a.idDpt
                            LEFT JOIN #__users as d ON d.id = a.idUsuario                          
                            $filGrupos    
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
        
        
        /***
        * Metodo que salva cada acceso de los usuario
        */
       public function insLogAcceso($id_Usuario, $id_Dpt, $id_Fracc, $fAcceso){
           $db =& JFactory::getDBO();                                        
           $tbl_sasfe_log_accesos = $db->getPrefix().'sasfe_log_accesos';           
           
           $query = "INSERT INTO $tbl_sasfe_log_accesos (idUsuario, idDpt, idFracc, fechaHora)                                                             
                                                 VALUES ($id_Usuario, $id_Dpt, $id_Fracc, '$fAcceso' )";    
           $db->setQuery($query);                     
           $db->query();
           $id = $db->insertid();                
            
           //echo $query;
            
           return $id;                                            
       }        
}

?>
