<?php
/**
 * fecha: 19-11-13
 * company: company
 * @author Karlos
 */
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
jimport('joomla.application.component.modellist');

class SasfeModelExpdigitales extends JModelList{

    public function __construct($config = array())
    {
            $config['filter_fields'] = array(
                    // 'idDatoProspecto',
                    // 'nombre',
                    // 'celular',
                    // 'montoCredito',
                    // 'tipoCreditoId',
                    // 'comentario',
                    // 'agtVentasId',
                    // 'agtVentasId2',
                    // 'fechaAlta',
                    // 'RFC',
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

        //Busqueda por email
        $email = $this->getUserStateFromRequest($this->context.'.filter.email', 'filter_email');
        $this->setState('filter.email', $email);

        /*//Busqueda por rfc
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
        */

        $estatus = $this->getUserStateFromRequest($this->context.'.filter.opcionEstatus', 'filter_estatus');
        $this->setState('filter.opcionEstatus', $estatus);

        // Buscar por gerentes
        $gerente = $this->getUserStateFromRequest($this->context.'.filter.opcionGerentes', 'filter_gerentes');
        $this->setState('filter.opcionGerentes', $gerente);

        // Buscar por asesores
        $asesor = $this->getUserStateFromRequest($this->context.'.filter.opcionAsesores', 'filter_asesores');
        $this->setState('filter.opcionAsesores', $gerente);

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

            $db = JFactory::getDbo();
            $this->userC = JFactory::getUser();
            $this->groups = JAccess::getGroupsByUser($this->userC->id, false);
            // echo "<pre>";print_r($this->groups);echo "</pre>"; //Obtener registros por el grupo
            $ordering = $this->getState('list.ordering');
            $direction = $this->getState('list.direction');
            $limitstart = $this->getState('list.start');
	        $limit = $this->getState('list.limit');

            $search = $this->getState('filter.search'); //Buscar por nombre
            $apellidos = $this->getState('filter.apellidos'); //Buscar por apellidos
            $email = $this->getState('filter.email'); //Buscar por email
            $estatus = $this->getState('filter.opcionEstatus'); //Buscar por estatus
            $gerente = $this->getState('filter.opcionGerentes'); //Buscar por gerente, Imp. 23/08/21, Carlos
            $asesor = $this->getState('filter.opcionAsesores'); //Buscar por asesor, Imp. 24/08/21, Carlos

            // $idEstatus = $this->getState('filter.opcionEstatusProspecto'); //Buscar por el estatus personalizados
            $this->layout = JRequest::getVar('layout'); //obtiene el nombre del layout
            // $opcFiltro = false;
            // if( $search!="" || $apellidos!="" || $apellidos!="" || ($estatus!="" && $estatus>0) || ($gerente!="" && $gerente>0) || ($asesor!="" && $asesor>0) ){
            //     $opcFiltro = true;
            // }
            // echo "opcFiltro: ".$opcFiltro.'<br/>';

        /*

            //Solo ocurre para el gerente de ventas
            $queryIdAgtV = "";
            // if($this->layout=="repetidos"){
            //     $idAgtVentas = $this->getState('filter.opcionAgentesVenta'); //Buscar por el id del agente de ventas
            //     if($idAgtVentas!=""){
            //         $queryIdAgtV = " AND a.agtVentasId=".$idAgtVentas;
            //     }
            // }

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

            // Estatus
            // 1=Asignados, 2=Por asignar

            if($estatus!=""){
                if($estatus == '0'){
                    $tipoEstatus = "";
                }
                else if($estatus == '1'){ //Asignados
                    $tipoEstatus = " AND (a.agtVentasId IS NOT NULL AND a.departamentoId IS NULL) ";
                }else if($estatus == '2'){ //Por asignar
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

            // Imp. 24/08/21, Carlos => Inicia filtro de asesores
            $idAsesorVentas = "";
            if($asesor!=""){
                $idAsesorVentas = " AND a.agtVentasId=".$asesor." ";
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
                $searchQuery = implode(' OR ', $searches);

                // $searchesCRM[] = "b.nombre LIKE '%$search%' "; //Buscar por nombre prospectador
                // $searchQueryCRM = implode(' OR ', $searches);
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

            //Busqueda por email
            if($email){
                 //Compile the different search clauses.
                $email = str_replace("'","",$email);  //limpia el caracter ' de la cadena
                //si es fecha valida entonces entra
                if(preg_match('/^\d{1,2}\/\d{1,2}\/\d{4}$/', $email)) {
                    list($d,$m,$y) = explode('/', $email);
                    $email = $y.'-'.$m.'-'.$d;
                }
                $searches[] = "a.email LIKE '%$email%' "; //Buscar por email prospectador
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
            // echo "queryOpt: ".$queryOpt."<br/>";
        */

            $queryPro = array();
            $queryCRM = array();

            //Buscar por nombre
            if($search!=""){
                $search = str_replace("'","",$search);  //limpia el caracter ' de la cadena
                $queryPro[] = "a.nombre LIKE '%$search%' ";
                $queryCRM[] = "b.nombre LIKE '%$search%' ";
            }

            //Buscar por apellidos
            if($apellidos!=""){
                $apellidos = str_replace("'","",$apellidos);  //limpia el caracter ' de la cadena
                $queryPro[] = "( a.aPaterno LIKE '%$apellidos%' OR a.aManterno LIKE '%$apellidos%' )"; //Buscar por apellido paterno
                $queryCRM[] = "( b.aPaterno LIKE '%$apellidos%' OR b.aManterno LIKE '%$apellidos%' )"; //Buscar por apellido materno
            }

            //Buscar por email
            if($email){
                $email = str_replace("'","",$email);  //limpia el caracter ' de la cadena
                $queryPro[] = "a.email LIKE '%$email%' ";
                $queryCRM[] = "b.email LIKE '%$email%' ";
            }

            //Buscar por gerente
            if($gerente!="" && $gerente>0){
                $queryPro[] = "a.gteVentasId=".$gerente;
                $queryCRM[] = "c.usuarioIdJoomla=".$gerente;
            }

            //Buscar por agente o asesor
            if($asesor!="" && $asesor>0){
                $queryPro[] = "a.agtVentasId=".$asesor;
                $queryCRM[] = "d.usuarioIdJoomla=".$asesor;
            }

            // Buscar por estatus
            if($estatus!="" && $estatus>0){
                if($estatus==1 || $estatus==2){ //Asignados
                    if($estatus==1){
                        $queryPro[] = "(a.agtVentasId IS NOT NULL AND a.departamentoId IS NULL)";
                    }
                    if($estatus==2){
                        $queryPro[] = "a.agtVentasId IS NULL";
                    }
                    $queryCRM[] = "a.idEstatus=0";
                }else{
                    $queryPro[] = "a.departamentoId IS NOT NULL";
                    $queryCRM[] = "a.idEstatus=".$estatus;
                }
            }

            $searchPro = "";
            $searchCRM = "";
            if( count($queryPro)>0 ){
                $searchPro = " AND ". implode(' AND ', $queryPro);
            }
            if( count($queryPro)>0 ){
                $searchCRM = " AND ".implode(' AND ', $queryCRM);
            }
            // echo $searchPro."<br/>";
            // echo $searchCRM."<br/>";

            // echo "<pre>";
            // print_r($queryPro);
            // print_r($queryCRM);
            // echo "</pre>";


            $queryOpt = '';
            $soloProspectos = " "; // Mostrar solo los prospectos del estatus (Asignados y por asiganar)
            $soloCRM = " "; // Mostrar solo los CRM

            //Obtener registros por el id del usuario que lo creo
            if($queryOpt!=''){  //ACCIONA CUANDO SE REALIZA ALGUN TIPO DE FILTRO
                //Super usuario
                if(in_array("8", $this->groups) || in_array("10", $this->groups)){
                    // $queryOpt .= ' AND (a.gteVentasId IS NULL OR a.gteVentasId IS NOT NULL) '.$queryDuplicado;
                    // $queryOpt .= ' AND a.departamentoId IS NULL AND a.fechaDptoAsignado IS NULL ';
                    $soloProspectos .= ' AND a.departamentoId IS NULL AND a.fechaDptoAsignado IS NULL ';
                    $soloCRM .= ' AND a.esHistorico=0 AND a.esReasignado=0 AND a.obsoleto=0 ';
                }
                // //Gerente ventas
                //     elseif(in_array("11", $this->groups)){
                //         $queryOpt .= ' AND a.gteVentasId IN ('.$idUsuarioJoomla.') '.$queryDuplicado;
                //     }
                //     //Gerente de prospeccion
                //     elseif(in_array("19", $this->groups)){
                //         $queryOpt .= ' AND a.gteProspeccionId IN ('.$idUsuarioJoomla.') '.$queryDuplicado;
                //     }
                //     //Prospectador
                //     elseif(in_array("17", $this->groups)){
                //         //$queryOpt .= ' AND a.prospectadorId IN ('.$idUsuarioJoomla.') '.$queryDuplicado;
                //         $queryOpt .= ' AND a.altaProspectadorId IN ('.$idUsuarioJoomla.') '.$queryDuplicado;
                //     }
                //     //Direccion
                //     elseif(in_array("10", $this->groups)){
                //         // $queryOpt .= ' AND a.idRepDir=1 '.$queryDuplicado;
                //         $queryOpt .= ' AND (a.gteVentasId IS NULL OR a.gteVentasId IS NOT NULL) '.$queryDuplicado;
                //     }
                //     // Redes
                //     elseif(in_array("20", $this->groups)){
                //         // echo "REDES con Filtro";
                //         // $queryOpt .= ' AND a.idRepDir=1 '.$queryDuplicado;
                //         $queryOpt .= ' AND (a.gteVentasId IS NULL OR a.gteVentasId IS NOT NULL) '.$queryDuplicado;
                //     }
                //     //Agentes de venta
                //     else{
                //         $queryOpt .= ' AND a.agtVentasId IN ('.$idUsuarioJoomla.') '.$queryDuplicado;
                //     }
            }else{
                //ACCIONA CUANDO NO SE HACE NINGUN FILTRO
                //Super usuario
                if(in_array("8", $this->groups) || in_array("10", $this->groups)){
                    // $queryOpt .= ' WHERE (a.gteVentasId IS NULL OR a.gteVentasId IS NOT NULL) '.$queryDuplicado;
                    // $queryOpt .= ' WHERE a.departamentoId IS NULL AND a.fechaDptoAsignado IS NULL ';
                    $soloProspectos .= ' WHERE a.departamentoId IS NULL AND a.fechaDptoAsignado IS NULL ';
                    $soloCRM .= ' WHERE a.esHistorico=0 AND a.esReasignado=0 AND a.obsoleto=0 ';
                }
                  // //Gerente ventas
                  //   elseif(in_array("11", $this->groups)){
                  //       $queryOpt .= ' WHERE a.gteVentasId IN ('.$idUsuarioJoomla.') '.$queryDuplicado;
                  //   }
                  //   //Gerente de prospeccion
                  //   elseif(in_array("19", $this->groups)){
                  //       // $queryOpt .= ' WHERE a.agtVentasId IN ('.$idUsuarioJoomla.') '.$queryDuplicado;
                  //       $queryOpt .= ' WHERE a.gteProspeccionId IN ('.$idUsuarioJoomla.') '.$queryDuplicado;
                  //   }
                  //   //Prospectador
                  //   elseif(in_array("17", $this->groups)){
                  //       // $queryOpt .= ' WHERE a.prospectadorId IN ('.$idUsuarioJoomla.') '.$queryDuplicado;
                  //       $queryOpt .= ' WHERE a.altaProspectadorId IN ('.$idUsuarioJoomla.') '.$queryDuplicado;
                  //   }
                  //   //Direccion
                  //   elseif(in_array("10", $this->groups)){
                  //       if($this->layout=="repetidos"){
                  //           // $queryOpt .= ' WHERE a.idRepDir=1 '.$queryDuplicado;
                  //           $queryOpt .= ' WHERE (a.gteVentasId IS NULL OR a.gteVentasId IS NOT NULL) '.$queryDuplicado;
                  //       }else{
                  //           $queryOpt .= ' WHERE a.idRepDir=0 '.$queryDuplicado;
                  //       }
                  //   }
                  //   // Redes
                  //   elseif(in_array("20", $this->groups)){
                  //       // echo "REDES Sin Filtro";
                  //       // $queryOpt .= ' AND a.idRepDir=1 '.$queryDuplicado;
                  //       $queryOpt .= ' AND (a.gteVentasId IS NULL OR a.gteVentasId IS NOT NULL) '.$queryDuplicado;
                  //   }
                  //   //Agentes de venta
                  //   else{
                  //       $queryOpt .= ' WHERE a.agtVentasId IN ('.$idUsuarioJoomla.') '.$queryDuplicado;
                  //   }
            }

            // echo "queryOpt: ".$queryOpt.'<br/>';
            // echo "es: ".$tipoEstatus.'<br/>';
            /*$query = "
                    SELECT a.*, b.nombre as tipoCredito
                    FROM #__sasfe_datos_prospectos as a
                    LEFT JOIN #__sasfe_datos_catalogos as b ON b.idDato=a.tipoCreditoId
                    $queryOpt $tipoEstatus $idGerenteVentas $idAsesorVentas
                  ";*/

            // $queryOpt $soloProspectos  $tipoEstatus $idGerenteVentas $idAsesorVentas

            $query = "
                    SELECT a.idDatoProspecto, '0' AS idDatoGeneral, a.nombre, a.aPaterno, a.aManterno, a.email, a.departamentoId, a.fechaDptoAsignado,
                    a.agtVentasId, a.gteVentasId, a.gteProspeccionId, '0' AS consulta, (CASE WHEN a.agtVentasId!='' THEN 'Asignado' ELSE 'Por asignar' END) AS estatusNombre,
                    (CASE WHEN a.agtVentasId!='' THEN '1' ELSE '2' END) AS estatus
                    FROM #__sasfe_datos_prospectos as a
                    LEFT JOIN #__sasfe_datos_catalogos as b ON b.idDato=a.tipoCreditoId
                    $soloProspectos $searchPro
                  ";
            $queryCRM = "
                    SELECT (CASE WHEN a.datoProspectoId!='' THEN a.datoProspectoId ELSE '0' END) AS idDatoProspecto, a.idDatoGeneral, b.nombre, b.aPaterno, b.aManterno, b.email, a.departamentoId, a.fechaApartado AS fechaDptoAsignado,
                    d.usuarioIdJoomla AS agtVentasId, c.usuarioIdJoomla AS gteVentasId, '' AS gteProspeccionId, '1' AS consulta,
                    (CASE
                        WHEN a.idEstatus='86' THEN 'Disponible'
                        WHEN a.idEstatus='87' THEN 'Escriturado'
                        WHEN a.idEstatus='88' THEN 'Cancelado'
                        WHEN a.idEstatus='90' THEN 'Apartado'
                        WHEN a.idEstatus='91' THEN 'Incompleto'
                        WHEN a.idEstatus='92' THEN 'Diferencia'
                        WHEN a.idEstatus='93' THEN 'Inscrito'
                        WHEN a.idEstatus='94' THEN 'Aviso de retención'
                        WHEN a.idEstatus='95' THEN 'Con problema'
                        WHEN a.idEstatus='128' THEN 'Bloqueada'
                        WHEN a.idEstatus='387' THEN 'Corrección de datos'
                        WHEN a.idEstatus='388' THEN 'Folio'
                        WHEN a.idEstatus='389' THEN 'Ahorro voluntario'
                        WHEN a.idEstatus='390' THEN 'Instalación de acabados'
                        WHEN a.idEstatus='400' THEN 'Apartado definitivo'
                        WHEN a.idEstatus='401' THEN 'Apartado provisional'
                        WHEN a.idEstatus='402' THEN 'Regresar Asesor'
                        ELSE '0' END) AS estatusNombre,
                    a.idEstatus AS estatus

                    FROM #__sasfe_datos_generales AS a
                    LEFT JOIN #__sasfe_datos_clientes AS b ON b.datoGeneralId=a.idDatoGeneral
                    LEFT JOIN #__sasfe_datos_catalogos AS c ON c.idDato=a.idGerenteVentas
                    LEFT JOIN #__sasfe_datos_catalogos AS d ON d.idDato=a.idAsesor
                    $soloCRM $searchCRM
            ";
            // $query = $query;// ." UNION ". $queryCRM;
            $query = $query ." UNION ". $queryCRM;
            // echo $query;
            // echo self::cambiarPrefijo($query);
            $db->setQuery($query);
            $db->query();
            $rows = $db->loadObjectList();
            $this->items = $rows;

            $this->total = count($rows);
            // echo "<br/>total: ".$this->total."<br/>";
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

    private function cambiarPrefijo($query=""){
        $db = JFactory::getDbo();
        $prefijo = $db->getPrefix();

        return str_replace("#__", $prefijo, $query);
    }
}

?>
