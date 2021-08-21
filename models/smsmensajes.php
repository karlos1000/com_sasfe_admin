<?php
/** 
 * fecha: 19-11-13
 * company: company
 * @author Karlos
 */
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
jimport('joomla.application.component.modellist');
 
class SasfeModelSmsmensajes extends JModelList{
       
        public function __construct($config = array())
        {   
                $config['filter_fields'] = array(
                        'idMensaje',
                        'texto',
                        'activo',
                        'titulo',
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
            parent::populateState('idMensaje', 'asc');
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
                                                                                
                    $searches[] = "a.idMensaje LIKE '%$search%' "; //Buscar por id del mensaje
                    $searches[] = "a.texto LIKE '%$search%' "; //Buscar por nombre del mensaje
                    
                    $searchQuery = implode(' OR ', $searches);
                    $searchQuery = ' WHERE ' .$searchQuery;
                }else{
                    $searchQuery = '';
                }
                
                //ORDER BY a.idMensaje
                
                $query = "
                        SELECT a.*
                        FROM #__sasfe_sms_mensajes as a 
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
                
        public function removerPorIdMensaje($idsMensaje){
            $db = JFactory::getDbo();                                                                                        
            $noBorrados = array();
            $result = array();
            
            foreach($idsMensaje as $id):
                //Antes de remover verificar que ninguno de este tipo de eventos este en uso
                // $queryTipo = "SELECT * FROM #__sasfe_sms_mensajes WHERE idMensaje IN ($id)";
                // $db->setQuery($queryTipo);
                // $rowsTipoCaptado = $db->loadObjectList(); 

                // if(count($rowsTipoCaptado)>0){
                //     $noBorrados[] = $id;
                // }else{
                    $query = "DELETE FROM #__sasfe_sms_mensajes WHERE idMensaje=$id ";
                    $db->setQuery($query);
                    $db->query();
                    $result[] = $id;
                // }
            endforeach;
             
            return array("resultDel"=>$result, "noBorrados"=>$noBorrados);
        }
}

?>
