<?php
/**
 * fecha: 19-11-13
 * company: company
 * @author Karlos
 */
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
jimport('joomla.application.component.modellist');

class SasfeModelContactos extends JModelList{

        public function __construct($config = array())
        {
                $config['filter_fields'] = array(
                        'idDatoContacto',
                        'nombre',
                        'telefono',
                        'email',
                        'estatusId',
                        'fechaAlta',
                        'fechaContacto',
                        'fechaActualizacion',
                        'gteVentasId',
                        'agtVentasId',
                        'fuente',
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
            //Busqueda por apellido
            $apellidos = $this->getUserStateFromRequest($this->context.'.filter.apellidos', 'filter_apellidos');
            $this->setState('filter.apellidos', $apellidos);
            //Busqueda por telefono
            $telefono = $this->getUserStateFromRequest($this->context.'.filter.telefono', 'filter_tel');
            $this->setState('filter.telefono', $telefono);
            //Busqueda por email
            $email = $this->getUserStateFromRequest($this->context.'.filter.email', 'filter_email');
            $this->setState('filter.email', $email);
            // Buscar por estatus
            $estatus = $this->getUserStateFromRequest($this->context.'.filter.opcionEstatus', 'filter_estatus');
            $this->setState('filter.opcionEstatus', $estatus);
            // Buscar por fuentes
            $fuentes = $this->getUserStateFromRequest($this->context.'.filter.opcionFuentes', 'filter_fuentes');
            $this->setState('filter.opcionFuentes', $fuentes);
            // Buscar por gerentes
            $gerente = $this->getUserStateFromRequest($this->context.'.filter.opcionGerentes', 'filter_gerentes');
            $this->setState('filter.opcionGerentes', $gerente);
            // Buscar por asesores
            $asesor = $this->getUserStateFromRequest($this->context.'.filter.opcionAsesores', 'filter_asesores');
            $this->setState('filter.opcionAsesores', $asesor);

            $this->layout = JRequest::getVar('layout'); //obtiene el nombre del layout
            // if($this->layout=="repetidos"){
            //     $idAgtVentas = $this->getUserStateFromRequest($this->context.'.filter.opcionAgentesVenta', 'filter_agtev');
            //     $this->setState('filter.opcionAgentesVenta', $idAgtVentas);
            // }

            //Load the parameters.
            $params = JComponentHelper::getParams('com_sasfe');
            $this->setState('params', $params);

            // List state information.
            parent::populateState('idDatoContacto', 'asc');
        }

        public function getItems()
	    {

            require_once(JPATH_COMPONENT.'/helpers/sasfehp.php' );

            if (!isset($this->items))
            {
                $db = JFactory::getDbo();
                $this->userC = JFactory::getUser();
                $this->groups = JAccess::getGroupsByUser($this->userC->id, false);
                // echo "<pre>";print_r($this->groups);echo "</pre>"; //Obtener registros por el grupo
                $ordering = $this->getState('list.ordering');
                $direction = $this->getState('list.direction');
                $limitstart = $this->getState('list.start');
		        $limit = $this->getState('list.limit');

                $search = $this->getState('filter.search'); //Buscar por nombre o apellidos
                $apellidos = $this->getState('filter.apellidos'); //Buscar por apellidos
                $telefono = $this->getState('filter.telefono'); //Busqueda por telefono
                $email = $this->getState('filter.email'); //Buscar por email
                $estatus = $this->getState('filter.opcionEstatus'); //Buscar por estatus
                $fuentes = $this->getState('filter.opcionFuentes'); //Buscar por fuentes
                $gerente = $this->getState('filter.opcionGerentes'); //Buscar por gerente
                $asesor = $this->getState('filter.opcionAsesores'); //Buscar por asesor

                $this->layout = JRequest::getVar('layout'); //obtiene el nombre del layout
                $opcFiltro = false;
                if( $search!="" || $apellidos!="" || $telefono!="" || $email!="" || ($estatus!="" && $estatus>0) || $fuentes!="" || ($gerente!="" && $gerente>0) || ($asesor!="" && $asesor>0) ){
                    $opcFiltro = true;
                }
                // echo "opcFiltro: ".$opcFiltro.'<br/>';


                //Solo ocurre para el gerente de ventas
                // $queryIdAgtV = "";
                // if($this->layout=="repetidos"){
                //     $idAgtVentas = $this->getState('filter.opcionAgentesVenta'); //Buscar por el id del agente de ventas
                //     if($idAgtVentas!=""){
                //         $queryIdAgtV = " AND a.agtVentasId=".$idAgtVentas;
                //     }
                // }

            /*
                $tipoEstatus = "";
                //Si el usuario pertenece al grupo gerentes prospeccion y gerentes ventas
                if(in_array("11", $this->groups) || in_array("19", $this->groups) || in_array("17", $this->groups)){
                    $idUsuarioJoomla=$this->userC->id;
                    if(!in_array("11", $this->groups)){
                    $tipoEstatus = "AND a.agtVentasId IS NULL";
                    }
                }else if(in_array("18", $this->groups)){
                    $idUsuarioJoomla=$this->userC->id;
                    // $tipoEstatus = "AND a.agtVentasId != '' ";
                    $tipoEstatus = " AND (a.agtVentasId IS NOT NULL AND a.departamentoId IS NULL) ";
                }else if(in_array("10", $this->groups)){
                    $idUsuarioJoomla=$this->userC->id;
                    //$tipoEstatus = "AND (a.agtVentasId IS NOT NULL AND a.departamentoId IS NULL) ";
                }
                // elseif(in_array("10", $this->groups)){
                //     $idUsuarioJoomla=$this->userC->id;
                //     $tipoEstatus = "AND (a.agtVentasId IS NOT NULL AND a.departamentoId IS NULL) ";
                 // }
                else{
                    //Filtrar los prospectos por el id del usuario que lo creo solo (agentes de venta)
                    $idUsuarioJoomla=$this->userC->id;
                }
                //echo $idUsuarioJoomla.'<br/>';
                 //Filtro por estatus
                // echo "estatus: ".$estatus.'<br/>';
            */

                //Finaliza filtros para los estatus

                //Inicializa arreglo
                $searches = array();
                $searchQuery = '';
                // Busqueda por nombre
                if($search){
                     //Compile the different search clauses.
                    $search = str_replace("'","",$search);  //limpia el caracter ' de la cadena

                    //si es fecha valida entonces entra
                    if(preg_match('/^\d{1,2}\/\d{1,2}\/\d{4}$/', $search)) {
                        list($d,$m,$y) = explode('/', $search);
                        $search = $y.'-'.$m.'-'.$d;
                    }

                    $searches[] = "a.nombre LIKE '%$search%' "; //Buscar por nombre contacto
                    $searchQuery = implode(' OR ', $searches);
                }

                //Busqueda por apellidos
                if($apellidos){
                     //Compile the different search clauses.
                    $apellidos = str_replace("'","",$apellidos);  //limpia el caracter ' de la cadena
                    //si es fecha valida entonces entra
                    if(preg_match('/^\d{1,2}\/\d{1,2}\/\d{4}$/', $apellidos)) {
                        list($d,$m,$y) = explode('/', $apellidos);
                        $apellidos = $y.'-'.$m.'-'.$d;
                    }
                    $searches[] = "a.aPaterno LIKE '%$apellidos%' "; //Buscar por apellido paterno contacto
                    $searches[] = "a.aMaterno LIKE '%$apellidos%' "; //Buscar por apellido materno contacto

                    $searchQuery = implode(' OR ', $searches);
                }

                //Busqueda por telefono
                if($telefono){
                     //Compile the different search clauses.
                    $telefono = str_replace("'","",$telefono);  //limpia el caracter ' de la cadena
                    //si es fecha valida entonces entra
                    if(preg_match('/^\d{1,2}\/\d{1,2}\/\d{4}$/', $telefono)) {
                        list($d,$m,$y) = explode('/', $telefono);
                        $telefono = $y.'-'.$m.'-'.$d;
                    }
                    $searches[] = "a.telefono LIKE '%$telefono%' "; //Buscar por telefono contacto
                    $searchQuery = implode(' OR ', $searches);
                }

                //Busqueda por email
                if($email){
                     //Compile the different search clauses.
                    $email = str_replace("'","",$email);  //limpia el caracter ' de la cadena
                    //si es fecha valida entonces entra
                    if(preg_match('/^\d{1,2}\/\d{1,2}\/\d{4}$/', $email)) {
                        list($d,$m,$y) = explode('/', $email);
                        $email = $y.'-'.$m.'-'.$d;
                    }

                    $searches[] = "a.email LIKE '%$email%' "; //Buscar por email contacto
                    $searchQuery = implode(' OR ', $searches);
                }

                // Imp. 16/10/20
                //Busqueda por fuentes
                if($fuentes){
                     //Compile the different search clauses.
                    $fuentes = str_replace("'","",$fuentes);  //limpia el caracter ' de la cadena
                    //si es fecha valida entonces entra
                    if(preg_match('/^\d{1,2}\/\d{1,2}\/\d{4}$/', $fuentes)) {
                        list($d,$m,$y) = explode('/', $fuentes);
                        $fuentes = $y.'-'.$m.'-'.$d;
                    }

                    // $searches[] = "a.fuente LIKE '%$fuentes%' "; //Buscar por fuentes contacto
                    $searches[] = "a.fuente=$fuentes "; //Buscar por fuentes contacto //Imp. 23/11/20
                    $searchQuery = implode(' OR ', $searches);
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

                // Busqueda por estatus
                $tipoEstatusDP = "";
                $tipoEstatus = "";
                if($estatus!=""){
                    if($estatus == '0'){
                        $tipoEstatus = "";

                        // Para la regla
                        // Por default en el grid los estatus de Descartado y Prospecto no se mostrarán, serán visibles si se seleccionan en el filtro de búsqueda.
                        $buscarWhere = strpos($queryOpt, "WHERE");
                        if($buscarWhere==false){
                            $tipoEstatusDP = ' WHERE a.estatusId NOT IN (4,5) AND activo=1 ';
                        }else{
                            $tipoEstatusDP = ' AND a.estatusId NOT IN (4,5) AND activo=1 ';
                        }
                    }else{
                        if($queryOpt==""){
                            $tipoEstatus = " WHERE a.estatusId=$estatus AND activo=1 ";
                        }else{
                            $tipoEstatus = " AND a.estatusId=$estatus AND activo=1 ";
                        }
                    }
                }else{
                    // Por default en el grid los estatus de Descartado y Prospecto no se mostrarán, serán visibles si se seleccionan en el filtro de búsqueda.
                    $buscarWhere = strpos($queryOpt, "WHERE");
                    if($buscarWhere==false){
                        $tipoEstatusDP = ' WHERE a.estatusId NOT IN (4,5) AND activo=1 ';
                    }else{
                        $tipoEstatusDP = ' AND a.estatusId NOT IN (4,5) AND activo=1 ';
                    }
                }

                //Busqueda por gerente
                $paramGerente = "";
                if($gerente!=""){
                    // echo "param gerente: ".$gerente."<br/>";                    
                    $paramGerente = " AND a.gteVentasId=$gerente ";
                }
                // echo $paramGerente."<br/>";

                //Busqueda por gerente
                $paramAsesor = "";
                if($asesor!=""){
                    // echo "param asesor: ".$asesor."<br/>";
                    $paramAsesor = " AND a.agtVentasId=$asesor ";
                }
                // echo $paramAsesor."<br/>";


                // filtrar contactos segun rol
                $idUsuarioJoomla = "";
                if(in_array("8", $this->groups) || in_array("10", $this->groups) || in_array("17", $this->groups) || in_array("19", $this->groups)){
                }else{
                    if(in_array("11", $this->groups) || in_array("18", $this->groups)){
                        $idUsuarioJoomla = $this->userC->id;
                    }
                }


                //Obtener registros por el id del usuario que lo creo
                $queryPerm = "";
                if(in_array("11", $this->groups)){
                    $queryPerm .= ' AND (a.gteVentasId='.$idUsuarioJoomla.' ) ';
                }
                if(in_array("18", $this->groups)){
                    $queryPerm .= ' AND (a.agtVentasId='.$idUsuarioJoomla.' ) ';
                }
                // echo $queryPerm;

            /*
                //Consulta si son repetidos
                $queryDuplicado = " AND a.duplicado=0 ";
                if($this->layout=="repetidos"){
                    $queryDuplicado = " AND a.duplicado=1 ";
                }

                //Consulta por defecto los que estan en espera de procesar
                $queryProcesar = " AND ( a.idNoProcesados IS NULL OR a.idNoProcesados!=1 ) ";
                if($this->layout=="noprocesados"){
                   $queryProcesar = " AND a.idNoProcesados=1 ";
                }

                //Obtener registros por el id del usuario que lo creo
                if($queryOpt!=''){  //ACCIONA CUANDO SE REALIZA ALGUN TIPO DE FILTRO
                    //Super usuario
                    if(in_array("8", $this->groups)){
                        $queryOpt .= ' AND (a.gteVentasId IS NULL OR a.gteVentasId IS NOT NULL) '.$queryDuplicado;
                    }
                    //Gerente ventas
                    elseif(in_array("11", $this->groups)){
                        $queryOpt .= ' AND a.gteVentasId IN ('.$idUsuarioJoomla.') '.$queryDuplicado;
                    }
                    //Gerente de prospeccion
                    elseif(in_array("19", $this->groups)){
                        $queryOpt .= ' AND a.gteProspeccionId IN ('.$idUsuarioJoomla.') '.$queryDuplicado;
                    }
                    //Prospectador
                    elseif(in_array("17", $this->groups)){
                        //$queryOpt .= ' AND a.prospectadorId IN ('.$idUsuarioJoomla.') '.$queryDuplicado;
                        $queryOpt .= ' AND a.altaProspectadorId IN ('.$idUsuarioJoomla.') '.$queryDuplicado;
                    }
                    //Direccion
                    elseif(in_array("10", $this->groups)){
                        // $queryOpt .= ' AND a.idRepDir=1 '.$queryDuplicado;
                        $queryOpt .= ' AND (a.gteVentasId IS NULL OR a.gteVentasId IS NOT NULL) '.$queryDuplicado;
                    }
                    //Agentes de venta
                    else{
                        $queryOpt .= ' AND a.agtVentasId IN ('.$idUsuarioJoomla.') '.$queryDuplicado;
                    }
                }else{
                    //ACCIONA CUANDO NO SE HACE NINGUN FILTRO
                    //Super usuario
                    if(in_array("8", $this->groups)){
                        $queryOpt .= ' WHERE (a.gteVentasId IS NULL OR a.gteVentasId IS NOT NULL) '.$queryDuplicado;
                    }
                    //Gerente ventas
                    elseif(in_array("11", $this->groups)){
                        $queryOpt .= ' WHERE a.gteVentasId IN ('.$idUsuarioJoomla.') '.$queryDuplicado;
                    }
                    //Gerente de prospeccion
                    elseif(in_array("19", $this->groups)){
                        // $queryOpt .= ' WHERE a.agtVentasId IN ('.$idUsuarioJoomla.') '.$queryDuplicado;
                        $queryOpt .= ' WHERE a.gteProspeccionId IN ('.$idUsuarioJoomla.') '.$queryDuplicado;
                    }
                    //Prospectador
                    elseif(in_array("17", $this->groups)){
                        // $queryOpt .= ' WHERE a.prospectadorId IN ('.$idUsuarioJoomla.') '.$queryDuplicado;
                        $queryOpt .= ' WHERE a.altaProspectadorId IN ('.$idUsuarioJoomla.') '.$queryDuplicado;
                    }
                    //Direccion
                    elseif(in_array("10", $this->groups)){
                        if($this->layout=="repetidos"){
                            // $queryOpt .= ' WHERE a.idRepDir=1 '.$queryDuplicado;
                            $queryOpt .= ' WHERE (a.gteVentasId IS NULL OR a.gteVentasId IS NOT NULL) '.$queryDuplicado;
                        }else{
                            $queryOpt .= ' WHERE a.idRepDir=0 '.$queryDuplicado;
                        }
                    }
                    //Agentes de venta
                    else{
                        $queryOpt .= ' WHERE a.agtVentasId IN ('.$idUsuarioJoomla.') '.$queryDuplicado;
                    }
                }
            */


                // echo $queryOpt.'<br/>';
                // echo "es: ".$tipoEstatus.'<br/>';
                // echo "estatus: ".$estatus.'<br/>';
                // echo "es: ".$tipoEstatusDP.'<br/>';
                // exit();
                // $query = "
                //         SELECT a.*, b.nombre as tipoCredito
                //         FROM #__sasfe_datos_prospectos as a
                //         LEFT JOIN #__sasfe_datos_catalogos as b ON b.idDato=a.tipoCreditoId
                //         $queryOpt  $montosCtoQuery  $tipoCtoQuery  $fechaPHastaQuery $queryProcesar $queryIdAgtV $tipoEstatus
                //       ";
                // // echo $query;

                $descartadoProspecto = "  ";
                $orderBy = " ORDER BY a.idDatoContacto DESC ";
                $query = "
                        SELECT a.*
                        FROM #__sasfe_datos_contactos as a
                        $queryOpt $tipoEstatus $tipoEstatusDP $paramGerente $paramAsesor $queryPerm
                        $orderBy
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
