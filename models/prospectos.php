<?php
/**
 * fecha: 19-11-13
 * company: company
 * @author Karlos
 */
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
jimport('joomla.application.component.modellist');

class SasfeModelProspectos extends JModelList{

        public function __construct($config = array())
        {
                $config['filter_fields'] = array(
                        'idDatoProspecto',
                        'nombre',
                        'celular',
                        'montoCredito',
                        'tipoCreditoId',
                        'comentario',
                        'agtVentasId',
                        'agtVentasId2',
                        'fechaAlta',
                        'RFC',
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
            //Busqueda por rfc
            $rfc = $this->getUserStateFromRequest($this->context.'.filter.rfc', 'filter_rfc');
            $this->setState('filter.rfc', $rfc);
            //Busqueda por celular
            $celular = $this->getUserStateFromRequest($this->context.'.filter.celular', 'filter_cel');
            $this->setState('filter.celular', $celular);

            //Rango de montos de credito
            $montocto1 = $this->getUserStateFromRequest($this->context.'.filter.montocto1', 'filter_montocto1');
            $this->setState('filter.montocto1', $montocto1);
            $montocto2 = $this->getUserStateFromRequest($this->context.'.filter.montocto2', 'filter_montocto2');
            $this->setState('filter.montocto2', $montocto2);

            $puntoshasta = $this->getUserStateFromRequest($this->context.'.filter.puntoshasta', 'filter_puntoshasta');
            $this->setState('filter.puntoshasta', $puntoshasta);

            $idTipoCto = $this->getUserStateFromRequest($this->context.'.filter.opcionTipoCreditos', 'filter_tipocto');
            $this->setState('filter.opcionTipoCreditos', $idTipoCto);

            $estatus = $this->getUserStateFromRequest($this->context.'.filter.opcionEstatus', 'filter_estatus');
            $this->setState('filter.opcionEstatus', $estatus);

            // Buscar por gerentes
            $gerente = $this->getUserStateFromRequest($this->context.'.filter.opcionGerentes', 'filter_gerentes');
            $this->setState('filter.opcionGerentes', $gerente);

	        // $idEstatus = $this->getUserStateFromRequest($this->context.'.filter.opcionEstatusProspecto', 'filter_estatus2');
            // $this->setState('filter.opcionEstatusProspecto', $idEstatus);

            $this->layout = JRequest::getVar('layout'); //obtiene el nombre del layout
            if($this->layout=="repetidos"){
                $idAgtVentas = $this->getUserStateFromRequest($this->context.'.filter.opcionAgentesVenta', 'filter_agtev');
                $this->setState('filter.opcionAgentesVenta', $idAgtVentas);
            }

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
                $rfc = $this->getState('filter.rfc'); //Buscar por apellidos
                $celular = $this->getState('filter.celular'); //Busqueda por celular
                $montocto1 = str_replace(",","",str_replace("$","", $this->getState('filter.montocto1'))); //Buscar por monto de credito monto 1
                $montocto2 = str_replace(",","",str_replace("$","", $this->getState('filter.montocto2'))); //Buscar por monto de credito monto 2
                $puntoshasta = $this->getState('filter.puntoshasta'); //Buscar por fecha puntos hasta
                $idTipoCto = $this->getState('filter.opcionTipoCreditos'); //Buscar por el tipo de credito
                $estatus = $this->getState('filter.opcionEstatus'); //Buscar por estatus
                $gerente = $this->getState('filter.opcionGerentes'); //Buscar por gerente, Imp. 23/08/21, Carlos

                // $idEstatus = $this->getState('filter.opcionEstatusProspecto'); //Buscar por el estatus personalizados
                $this->layout = JRequest::getVar('layout'); //obtiene el nombre del layout
                $opcFiltro = false;
                if( $search!="" || $apellidos!="" || $rfc!="" || $celular!="" || $montocto1!="" || $montocto2!="" || $puntoshasta!="" || $idTipoCto!="" || ($estatus!="" && $estatus>0) || ($gerente!="" && $gerente>0) ){
                    $opcFiltro = true;
                }
                // echo "opcFiltro: ".$opcFiltro.'<br/>';

                //Solo ocurre para el gerente de ventas
                $queryIdAgtV = "";
                if($this->layout=="repetidos"){
                    $idAgtVentas = $this->getState('filter.opcionAgentesVenta'); //Buscar por el id del agente de ventas
                    if($idAgtVentas!=""){
                        $queryIdAgtV = " AND a.agtVentasId=".$idAgtVentas;
                    }
                }


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

                if($estatus!=""){
                    if($estatus == '0'){
                        $tipoEstatus = "";
                    }
                    else if($estatus == '1'){
                        $tipoEstatus = " AND (a.agtVentasId IS NOT NULL AND a.departamentoId IS NULL) ";
                    }else if($estatus == '2'){
                        $tipoEstatus = " AND a.agtVentasId IS NULL ";
                    }else if($estatus == '3'){
                        $tipoEstatus = " AND a.departamentoId != '' AND a.fechaDptoAsignado IS NULL ";
                    }else if($estatus == '4'){
                        $tipoEstatus = " AND a.departamentoId != '' AND a.fechaDptoAsignado != '' ";
                    }
                }
                //Finaliza filtros para los estatus

                // Imp. 23/08/21, Carlos => Inicia filtro de gerentes
                $idGerenteVentas = "";
                if($gerente!=""){
                    $idGerenteVentas = " AND a.gteVentasId=".$gerente." ";
                }
                // Finaliza filtro de gerentes

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

                    $searches[] = "a.nombre LIKE '%$search%' "; //Buscar por nombre prospectador
                    // $searches[] = "a.aPaterno LIKE '%$search%' "; //Buscar por apellidos prospectador
                    // $searches[] = "a.aManterno LIKE '%$search%' "; //Buscar por apellidos prospectador
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
                    $searches[] = "a.aPaterno LIKE '%$apellidos%' "; //Buscar por apellido paterno prospectador
                    $searches[] = "a.aManterno LIKE '%$apellidos%' "; //Buscar por apellido materno prospectador

                    $searchQuery = implode(' OR ', $searches);
                }

                //Busqueda por RFC
                if($rfc){
                     //Compile the different search clauses.
                    $rfc = str_replace("'","",$rfc);  //limpia el caracter ' de la cadena
                    //si es fecha valida entonces entra
                    if(preg_match('/^\d{1,2}\/\d{1,2}\/\d{4}$/', $rfc)) {
                        list($d,$m,$y) = explode('/', $rfc);
                        $rfc = $y.'-'.$m.'-'.$d;
                    }

                    $searches[] = "a.RFC LIKE '%$rfc%' "; //Buscar por rfc prospectador
                    $searchQuery = implode(' OR ', $searches);
                }
                //Busqueda por celular
                if($celular){
                     //Compile the different search clauses.
                    $celular = str_replace("'","",$celular);  //limpia el caracter ' de la cadena
                    //si es fecha valida entonces entra
                    if(preg_match('/^\d{1,2}\/\d{1,2}\/\d{4}$/', $celular)) {
                        list($d,$m,$y) = explode('/', $celular);
                        $celular = $y.'-'.$m.'-'.$d;
                    }
                    $searches[] = "a.celular LIKE '%$celular%' "; //Buscar por telefono prospectador
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
                    // Redes
                    elseif(in_array("20", $this->groups)){
                        echo "REDES con Filtro";
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
                    // Redes
                    elseif(in_array("20", $this->groups)){
                        // echo "REDES Sin Filtro";
                        // $queryOpt .= ' AND a.idRepDir=1 '.$queryDuplicado;
                        $queryOpt .= ' AND (a.gteVentasId IS NULL OR a.gteVentasId IS NOT NULL) '.$queryDuplicado;
                    }
                    //Agentes de venta
                    else{
                        $queryOpt .= ' WHERE a.agtVentasId IN ('.$idUsuarioJoomla.') '.$queryDuplicado;
                    }
                }

                // echo $queryOpt.'<br/>';
                // echo "es: ".$tipoEstatus.'<br/>';
                $query = "
                        SELECT a.*, b.nombre as tipoCredito
                        FROM #__sasfe_datos_prospectos as a
                        LEFT JOIN #__sasfe_datos_catalogos as b ON b.idDato=a.tipoCreditoId
                        $queryOpt  $montosCtoQuery  $tipoCtoQuery  $fechaPHastaQuery $queryProcesar $queryIdAgtV $tipoEstatus $idGerenteVentas
                      ";
                echo $query;

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
