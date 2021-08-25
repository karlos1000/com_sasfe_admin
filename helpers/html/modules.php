<?php
/**
 * @copyright	Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die;

/**
 * @package		Joomla.Administrator
 * @subpackage	com_modules
 * @since		1.6
 */
abstract class JHtmlModules
{

    static public function sasfeFraccionamientos($id=0){
            $options = array();
            $db = JFactory::getDbo();
            $query = "
                    SELECT * FROM #__sasfe_fraccionamientos
                ";
            $db->setQuery($query);
            $db->query();
            $rows = $db->loadObjectList();

            foreach($rows as $item){
                $options[] = JHtml::_('select.option', $item->idFraccionamiento, "$item->nombre");
            }

            return $options;
       }
    static public function opcionEventoComentario(){
        $options = array();
        $options[] = JHtml::_('select.option', '1', "Evento");
        $options[] = JHtml::_('select.option', '2', "Comentario");
        return $options;
    }

    static public function opcionTipoEventos($id=0){
        $options = array();
        $db = JFactory::getDbo();
        $query = "
                SELECT * FROM #__sasfe_tipo_eventos
            ";
        $db->setQuery($query);
        $db->query();
        $rows = $db->loadObjectList();

        foreach($rows as $item){
            $options[] = JHtml::_('select.option', $item->idTipoEvento, "$item->tipoEvento");
        }

        return $options;
    }

    //Obtener todos los tipos de credito
    static public function opcionTipoCreditos(){
        $options = array();
        $db = JFactory::getDbo();
        $query = "
                SELECT * FROM #__sasfe_datos_catalogos WHERE catalogoId=7 AND activo='1' AND nombre !=''
                ";
        $db->setQuery($query);
        $db->query();
        $rows = $db->loadObjectList();

        foreach($rows as $item){
            $options[] = JHtml::_('select.option', $item->idDato, "$item->nombre");
        }

        return $options;
    }

    //Para los eventos (Atendidos/Sin Atender/Todos)
    static public function opcionAtenderEvento(){
        $options = array();
        $options[] = JHtml::_('select.option', '1', "Sin Atender");
        $options[] = JHtml::_('select.option', '2', "Todos");
        return $options;
    }

    //Para el filtro de estatus en la vista de prospectos
    static public function opcionEstatusProspecto(){
        $options = array();
        // $options[] = JHtml::_('select.option', '1', "Por asignar agt.");
        // $options[] = JHtml::_('select.option', '2', "Agt. asignado");
        // $options[] = JHtml::_('select.option', '3', "Apartado prov.");
        // $options[] = JHtml::_('select.option', '4', "Apartado definitivo");

        $options[] = JHtml::_('select.option', '1', "Pendientes");
        $options[] = JHtml::_('select.option', '2', "Finalizados");

        return $options;
    }

    //Para el filtro de mostrar agentes de ventas (solo ocurre en el caso del gte ventas)
    /*
    static public function opcionAgentesVenta(){
        //obter todos los agentes de ventas por el usuario logueado
        $userC = JFactory::getUser();
        $this->groups = JAccess::getGroupsByUser($userC->id, false);
        echo "es: ".$userC->id.'<br/>';

        // //Si el usuario pertenece al grupo gerentes prospeccion y gerentes ventas
        // if(in_array("11", $this->groups) || in_array("19", $this->groups)){
        //     // $idUsuarioJoomla = SasfehpHelper::obtIdsUsrDatosCatPorUsrIdGteJoomla($this->userC->id); //id gerente prospeccion o ventas
        //     // $idUsuarioJoomla = ($idUsuarioJoomla!="") ?$idUsuarioJoomla :0;
        //     $idUsuarioJoomla=$this->userC->id;
        // }else{
        //     //Filtrar los prospectos por el id del usuario que lo creo solo (agentes de venta)
        //     $idUsuarioJoomla=$this->userC->id;
        // }
        // //echo $idUsuarioJoomla.'<br/>';
        // echo "es: ";

        $options = array();
        $options[] = JHtml::_('select.option', '1', "Pendientes");
        $options[] = JHtml::_('select.option', '2', "Finalizados");

        return $options;
    }
    */

    //Para el filtro de mostrar agentes de ventas (solo ocurre en el caso del gte ventas)
    static public function opcionAgentesVenta(){
        $userC = JFactory::getUser();
        // $groups = JAccess::getGroupsByUser($userC->id, false);

        $options = array();
        $db = JFactory::getDbo();
        $idGteVentas = $userC->id;

        if($idGteVentas!=""){
            $query = "SELECT DISTINCT agtVentasId FROM #__sasfe_datos_prospectos WHERE gteVentasId=$idGteVentas ";
            // $query = "SELECT a.*, b.nombre as tipoCredito
            //       FROM #__sasfe_datos_prospectos as a
            //       LEFT JOIN #__sasfe_datos_catalogos as b ON b.idDato=a.tipoCreditoId
            //       WHERE a.gteVentasId IN ($idGteVentas) AND a.duplicado=1 AND ( a.idNoProcesados IS NULL OR a.idNoProcesados!=1 )
            //      ";
            $db->setQuery($query);
            $db->query();
            $rowsGteV = $db->loadObjectList();

            foreach($rowsGteV as $itemgtv){
                if($itemgtv->agtVentasId!=""){
                  $queryUser = "SELECT * FROM #__users WHERE id=".$itemgtv->agtVentasId;
                  $db->setQuery($queryUser);
                  $db->query();
                  $row = $db->loadObject();

                  $options[] = JHtml::_('select.option', $itemgtv->agtVentasId, $row->name);
                }
            }
        }

        return $options;
    }
     static public function opcionEstatus(){
        $options = array();
        $userC = JFactory::getUser();
        $groups = JAccess::getGroupsByUser($userC->id, false);

        //Todos los filtros
        if(in_array("8", $groups) || in_array("10", $groups) || in_array("19", $groups) || in_array("11", $groups) || in_array("17", $groups)){
            $options[] = JHtml::_('select.option', '0', "Todos");
            $options[] = JHtml::_('select.option', '1', "Asignados");
            $options[] = JHtml::_('select.option', '2', "Por asignar");
            $options[] = JHtml::_('select.option', '3', "Apartado provisional");
            $options[] = JHtml::_('select.option', '4', "Apartado definitivo");
        }

        //Si el usuario pertenece al grupo agentes o asesor
        if(in_array("18", $groups)){
            //$options[] = JHtml::_('select.option', '1', "Todos");
            $options[] = JHtml::_('select.option', '0', "Todos");
            $options[] = JHtml::_('select.option', '1', "Asignados");
            $options[] = JHtml::_('select.option', '3', "Apartado provisional");
            $options[] = JHtml::_('select.option', '4', "Apartado definitivo");
        }


        return $options;
    }
    //>>>>Para los SMS
    //tipo de envio
    static public function opcionTipoEnvio(){
        $options = array();
        $options[] = JHtml::_('select.option', '1', "Mensaje");
        $options[] = JHtml::_('select.option', '2', "Promoci&oacute;n");
        $options[] = JHtml::_('select.option', '3', "Autom&aacute;tico");
        return $options;
    }
    //Para el filtro de mostrar agentes de ventas
    static public function opcionAgentesVentaSMS(){
        $userC = JFactory::getUser();
        $options = array();
        $db = JFactory::getDbo();
        // $idGteVentas = $userC->id;
        // if($idGteVentas!=""){
            // $query = "SELECT DISTINCT agtVentasId FROM #__sasfe_sms_historial_envios_clientes WHERE agtVentasId=$idGteVentas ";
            $query = "SELECT DISTINCT agtVentasId FROM #__sasfe_sms_historial_envios_clientes ";
            $db->setQuery($query);
            $db->query();
            $rows = $db->loadObjectList();
            foreach($rows as $itemAsesor){
                if($itemAsesor->agtVentasId!=""){
                  $queryUser = "SELECT * FROM #__sasfe_datos_catalogos WHERE idDato=".$itemAsesor->agtVentasId;
                  $db->setQuery($queryUser);
                  $db->query();
                  $row = $db->loadObject();
                  $options[] = JHtml::_('select.option', $itemAsesor->agtVentasId, $row->nombre);
                }
            }
        // }
        return $options;
    }

    // Opcion estatus contacto
    static public function opcionEstatusContacto(){
        $options = array();
        $userC = JFactory::getUser();
        $groups = JAccess::getGroupsByUser($userC->id, false);

        /*//Todos los filtros
        if(in_array("8", $groups) || in_array("10", $groups) || in_array("19", $groups) || in_array("11", $groups) || in_array("17", $groups)){
            $options[] = JHtml::_('select.option', '0', "Todos");
            $options[] = JHtml::_('select.option', '1', "Asignados");
            $options[] = JHtml::_('select.option', '2', "Por asignar");
            $options[] = JHtml::_('select.option', '3', "Apartado provisional");
            $options[] = JHtml::_('select.option', '4', "Apartado definitivo");
        }

        //Si el usuario pertenece al grupo agentes o asesor
        if(in_array("18", $groups)){
            //$options[] = JHtml::_('select.option', '1', "Todos");
            $options[] = JHtml::_('select.option', '0', "Todos");
            $options[] = JHtml::_('select.option', '1', "Asignados");
            $options[] = JHtml::_('select.option', '3', "Apartado provisional");
            $options[] = JHtml::_('select.option', '4', "Apartado definitivo");
        }*/

        $options[] = JHtml::_('select.option', '0', "Todos");
        $options[] = JHtml::_('select.option', '1', "Asignado");
        $options[] = JHtml::_('select.option', '2', "Seguimiento");
        $options[] = JHtml::_('select.option', '3', "Contactado");
        $options[] = JHtml::_('select.option', '4', "Descartado");
        $options[] = JHtml::_('select.option', '5', "Prospecto");
        $options[] = JHtml::_('select.option', '6', "Reasignado");

        return $options;
    }

    // Imp. 16/10/20
    // Opcion fuentes contacto
    static public function opcionFuentesContacto(){
        $userC = JFactory::getUser();
        $options = array();
        $db = JFactory::getDbo();

        // $query = "SELECT DISTINCT fuente FROM #__sasfe_datos_contactos ORDER BY fuente ASC ";
        $query = "SELECT DISTINCT(b.tipoCaptado) AS tipoCaptado, b.idTipoCaptado
                  FROM #__sasfe_datos_contactos AS a
                  LEFT JOIN #__sasfe_tipo_captados AS b ON b.idTipoCaptado=a.fuente
                  WHERE b.tipoCaptado IS NOT NULL
                  ORDER BY b.tipoCaptado ASC ";
        $db->setQuery($query);
        $db->query();
        $rows = $db->loadObjectList();

        $options[] = JHtml::_('select.option', '', "Todos");
        foreach($rows as $item){
            $options[] = JHtml::_('select.option', $item->idTipoCaptado, $item->tipoCaptado);
        }

        return $options;
    }

    // Imp. 19/10/20
    //Para el filtro de mostrar gerentes de ventas (solo ocurre en el caso direccion y redes)
    static public function opcionGerentesCont(){
        $userC = JFactory::getUser();
        // $groups = JAccess::getGroupsByUser($userC->id, false);

        $options = array();
        $db = JFactory::getDbo();
        $idGteVentas = $userC->id;
        $options[] = JHtml::_('select.option', '', "Gerentes (Todos)");

        $query = "SELECT DISTINCT gteVentasId FROM #__sasfe_datos_contactos ";
        // echo $query;
        $db->setQuery($query);
        $db->query();
        $rows = $db->loadObjectList();

        foreach($rows as $item){
            if($item->gteVentasId!=""){
              $queryUser = "SELECT * FROM #__users WHERE id=".$item->gteVentasId;
              $db->setQuery($queryUser);
              $db->query();
              $row = $db->loadObject();

              if($item->gteVentasId>0){
                $options[] = JHtml::_('select.option', $item->gteVentasId, $row->name);
              }
            }
        }

        return $options;
    }

    // Imp. 19/10/20
    //Para el filtro de mostrar agentes de ventas (solo ocurre en el caso dirección y Redes [ve todo], gerencias solo ven sus asesores)
    static public function opcionAsesoresCont(){
        $userC = JFactory::getUser();
        $groups = JAccess::getGroupsByUser($userC->id, false);

        // Obtener solo los asesores segun el rol gerencia
        $whereGteVentas = "";
        if(in_array("11", $groups)){
            $whereGteVentas = " WHERE gteVentasId=".$userC->id;
        }

        $options = array();
        $db = JFactory::getDbo();
        $idGteVentas = $userC->id;
        $options[] = JHtml::_('select.option', '', "Asesores (Todos)");

        $query = "SELECT DISTINCT agtVentasId FROM #__sasfe_datos_contactos ".$whereGteVentas;
        // echo $query;
        $db->setQuery($query);
        $db->query();
        $rows = $db->loadObjectList();

        foreach($rows as $item){
            if($item->agtVentasId!=""){
              $queryUser = "SELECT * FROM #__users WHERE id=".$item->agtVentasId;
              $db->setQuery($queryUser);
              $db->query();
              $row = $db->loadObject();

              if($item->agtVentasId>0){
                $options[] = JHtml::_('select.option', $item->agtVentasId, $row->name);
              }
            }
        }

        /*if($idGteVentas!=""){
            $query = "SELECT DISTINCT agtVentasId FROM #__sasfe_datos_contactos WHERE gteVentasId=$idGteVentas ";
            $db->setQuery($query);
            $db->query();
            $rowsGteV = $db->loadObjectList();

            foreach($rowsGteV as $itemgtv){
                if($itemgtv->agtVentasId!=""){
                  $queryUser = "SELECT * FROM #__users WHERE id=".$itemgtv->agtVentasId;
                  $db->setQuery($queryUser);
                  $db->query();
                  $row = $db->loadObject();

                  $options[] = JHtml::_('select.option', $itemgtv->agtVentasId, $row->name);
                }
            }
        }*/

        return $options;
    }


    // Imp. 23/08/21, Carlos, Para el filtro de mostrar gerentes de ventas en prospectos (solo ocurre en el caso Super Admin, direccion, redes)
    static public function opcionGerentesProspectos(){
        $userC = JFactory::getUser();
        $options = array();
        $db = JFactory::getDbo();
        // $idGteVentas = $userC->id;
        $options[] = JHtml::_('select.option', '', "Gerentes (Todos)");

        $tbl_users = $db->getPrefix().'users';
        $tbl_user_usergroup_map = $db->getPrefix().'user_usergroup_map';

        $query = "
                 SELECT a.* FROM $tbl_users AS a
                 LEFT JOIN $tbl_user_usergroup_map AS b ON b.user_id=a.id
                 WHERE b.group_id=11
                 ORDER BY name ASC
               ";

        // $query = "SELECT DISTINCT gteVentasId FROM #__sasfe_datos_contactos ";
        // echo $query;
        // exit();
        $db->setQuery($query);
        $db->query();
        $rows = $db->loadObjectList();

        foreach($rows as $item){
            $options[] = JHtml::_('select.option', $item->id, $item->name);
        }

        // foreach($rows as $item){
        //     if($item->gteVentasId!=""){
        //       $queryUser = "SELECT * FROM #__users WHERE id=".$item->gteVentasId;
        //       $db->setQuery($queryUser);
        //       $db->query();
        //       $row = $db->loadObject();

        //       if($item->gteVentasId>0){
        //         $options[] = JHtml::_('select.option', $item->gteVentasId, $row->name);
        //       }
        //     }
        // }

        return $options;
    }


    // Imp. 23/08/21, Carlos, Para el filtro de mostrar Asesores de ventas en prospectos (solo ocurre en el caso Super Admin, direccion, redes)
    static public function opcionAsesoresProspectos(){
        $userC = JFactory::getUser();
        $groups = JAccess::getGroupsByUser($userC->id, false);
        $options = array();
        $db = JFactory::getDbo();
        $tbl_users = $db->getPrefix().'users';
        $tbl_user_usergroup_map = $db->getPrefix().'user_usergroup_map';
        $tbl_sasfe_datos_catalogos = $db->getPrefix().'sasfe_datos_catalogos';

        $queryIdGteVentas = "";
        // if(in_array("19", $groups) || in_array("11", $groups)){
        if(in_array("11", $groups)){
            $queryIdGteVentas = ' AND usuarioIdGteJoomla= '.$userC->id;
        }

        $options[] = JHtml::_('select.option', '', "Agentes (Todos)");
        // Solo obtener aquellos asesores del propio gerente
        if($queryIdGteVentas!=""){
            $query = "
                SELECT * FROM $tbl_sasfe_datos_catalogos
                WHERE catalogoId=3 AND activo='1' AND nombre !='' AND usuarioIdJoomla IS NOT NULL $queryIdGteVentas
                ORDER BY nombre ASC
               ";
            // echo $query;
            // exit();
            $db->setQuery($query);
            $db->query();
            $rows = $db->loadObjectList();

            foreach($rows as $item){
                $options[] = JHtml::_('select.option', $item->usuarioIdJoomla, $item->nombre);
            }
        }

        return $options;
    }

}
