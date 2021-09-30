<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted Access');
jimport('joomla.application.component.controllerform');

class SasfeControllerProspecto extends JControllerForm {

    function cancel($key=NULL)
    {
        $this->setRedirect( 'index.php?option=com_sasfe&view=prospectos');
    }

    //Cancelar y regresar al escritorio
    function cancelYEscritorio($key=NULL)
    {
        $this->setRedirect( 'index.php?option=com_sasfe');
    }

    //Cancelar y a las vista de repetidos
    function cancelYRepetidos($key=NULL)
    {
        $this->setRedirect( 'index.php?option=com_sasfe&view=prospectos&layout=repetidos');
    }

    function cancelYListarEventos()
    {
        $this->setRedirect( 'index.php?option=com_sasfe&view=listareventos&opc=0');
    }

    public function add(){
       $varid = JRequest::getVar('id');
       $id = ($varid!='') ? $varid : 0;

       $this->setRedirect( 'index.php?option=com_sasfe&view=prospecto&layout=edit&id='.$id.' ');
    }

    public function edit($key=NULL,$urlVar=NULL){
        $cid = JRequest::getVar('cid', array(0));
        JArrayHelper::toInteger($cid);
        $id = (JRequest::getVar('id')!='') ? JRequest::getVar('id'): $cid[0];

        $this->setRedirect( 'index.php?option=com_sasfe&view=prospecto&layout=edit&id='.$id.' ');
    }

    function apply(){
      $this->procesarProspecto();
    }
    function save($key=NULL,$urlVar=NULL)
    {
     $this->procesarProspecto();
    }
    function saveandnew(){
        $this->procesarProspecto();
    }
    //Aplica cuando se trata de un grupo prospectador
    function saveandclose(){
        $this->procesarProspecto();
    }

    public function procesarProspecto(){
        jimport('joomla.filesystem.file');
        require_once(JPATH_COMPONENT.'/helpers/sasfehp.php' );
        // Check for request forgeries
        JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));
        //leer el modelo correspondiente
        $model = JModelLegacy::getInstance('Prospecto', 'SasfeModel');
        $modelGM = JModelLegacy::getInstance('Globalmodelsbk', 'SasfeModel');
        $arrDateTime = SasfehpHelper::obtDateTimeZone();

        //Salvar
        $opcDatosProsp = JRequest::getVar('opcDatosProsp');

        $this->userC = JFactory::getUser();
        //preguntar si es un agente de vantas o prospectador
        $idUsrJoomla = JRequest::getVar('idUsrJoomla');
        $opcUsuario = JRequest::getVar('opcUsuario');
        if($opcUsuario=="prospectador"){
            $esAgenteVentas = false;
            $prospectadorId = $idUsrJoomla;
            $agtVentasId = "";
            $altaProspectadorId = $idUsrJoomla;
        }
        if($opcUsuario=="agenteventas"){
            $esAgenteVentas = true;
            $agtVentasId = $idUsrJoomla;
            $prospectadorId = "";
            $altaProspectadorId = "NULL";
        }
        $idGte = JRequest::getVar('idGte');
        $opcGerente = JRequest::getVar('opcGerente');
        if($opcGerente=="gteprospeccion"){
            $esGteVentas = false;
            $gteProspeccionId = $idGte;
            $gteVentasId = "";
        }
        if($opcGerente=="gteventas"){
            $esGteVentas = true;
            $gteVentasId = $idGte;
            $gteProspeccionId = "";
        }

        //Reglas de negocio
        //1.- Si el rfc esta repetido se activara el campo repetido=1, el campo estatus sera NULL, no se asignara a ningun agente de ventas
        //lo puede ver los gerentes de ventas o gerentes prospeccion
        $rfcDuplicado = JRequest::getVar('rfc_duplicado');

        //obtener valores del formulario
        $fechaAlta = SasfehpHelper::conversionFecha(JRequest::getVar('fechaAlta'))." ".$arrDateTime->hora;
        $nombre = JRequest::getVar('nombre');
        $aPaterno = JRequest::getVar('aPaterno');
        $aManterno = JRequest::getVar('aManterno');
        $RFC = JRequest::getVar('RFC');
        // $fechaNac = SasfehpHelper::conversionFecha(JRequest::getVar('fechaNac'));
        $fechaNac = JRequest::getVar('fechaNac');
        $edad = (JRequest::getVar('edad')!="") ?JRequest::getVar('edad') :"NULL";
        $telefono = JRequest::getVar('telefono');
        $celular = JRequest::getVar('celular');
        $genero = JRequest::getVar('genero');
        $NSS = JRequest::getVar('NSS');
        $montoCredito = (JRequest::getVar('montoCredito')!="") ?SasfehpHelper::limpiarFormatoMonto(JRequest::getVar('montoCredito')) :"NULL";
        $tipoCreditoId = JRequest::getVar('tipoCreditoId');
        $subsidio = (JRequest::getVar('subsidio')!="") ?SasfehpHelper::limpiarFormatoMonto(JRequest::getVar('subsidio')) :"NULL";
        $puntosHasta = (JRequest::getVar('puntosHasta')!="") ?"'".SasfehpHelper::conversionFecha(JRequest::getVar('puntosHasta'))."'" :"NULL";
        $comentario = JRequest::getVar('comentario');
        $empresa = JRequest::getVar('empresa');
        $captadoen = (JRequest::getVar('captadoen')!="") ?JRequest::getVar('captadoen') :"NULL";
        $email = JRequest::getVar('email');
        $desarrolloId = (JRequest::getVar('idFracc')!="") ?JRequest::getVar('idFracc'):"NULL";  // 04/10/12 id desarrollo solo es informativo
        //IMR
        $gerencias = JRequest::getVar('gtvId_rfc');
        // $fraccionamientoId = (JRequest::getVar('fraccionamientoId')!="") ?JRequest::getVar('fraccionamientoId') :"NULL";

        $usuarioId = ($this->userC->id!="") ?$this->userC->id :"NULL";
        $gteProspeccionId = ($gteProspeccionId!="") ?$gteProspeccionId :"NULL";
        $gteVentasId = ($gteVentasId!="") ?$gteVentasId :"NULL";

        $periodoAsignacion = ""; //Periodo default
        $fechaAsignacionAgt = ""; //fecha de asignacion del agente de ventas
        if($rfcDuplicado==1){
            $prospectadorId = "NULL";
            // $agtVentasId = "NULL"; //Tomar el de arriba
            $agtVentasId = ($agtVentasId!="") ?$agtVentasId :"NULL";
            $estatusId = "NULL";
        }else{
            $prospectadorId = ($prospectadorId!="") ?$prospectadorId :"NULL";
            $agtVentasId = ($agtVentasId!="") ?$agtVentasId :"NULL";

            //Si es un agente de ventas el que lo da de alta se le asigna en automatico el prospectador
            if($esAgenteVentas==true){
                $estatusId = 2; //2= asignado el prospectador en automatico
                //Se activa el periodo de 3 meses a partir de la fecha de creacion
                //08/07/21, Carlos, Cambio de periodo de 3 meses a 1
                $periodoAsignacion = SasfehpHelper::conversionFecha(SasfehpHelper::mesesPrevPos(1, SasfehpHelper::conversionFecha(JRequest::getVar('fechaAlta')), "pos"));
                $fechaAsignacionAgt = $arrDateTime->fecha;
            }else{
                $estatusId = 1; //1= sin asignar agente de ventas
            }
        }
        $idUrl = JRequest::getVar('check_un');
        $periodoAsignacion = ($periodoAsignacion!="") ?"'".$periodoAsignacion."'" :"NULL"; //comprobar regla del periodo de tres meses
        $fechaAsignacionAgt = ($fechaAsignacionAgt!="") ?"'".$fechaAsignacionAgt."'" :"NULL"; //Fecha de asugnacion de agente

        // echo "usuarioId: ".$usuarioId.'<br/>';
        // echo "prospectadorId: ".$prospectadorId.'<br/>';
        // echo "agtVentasId: ".$agtVentasId.'<br/>';
        // echo "gteProspeccionId: ".$gteProspeccionId.'<br/>';
        // echo "gteVentasId: ".$gteVentasId.'<br/>';
        // exit();

        //si $idUrl=0 se crea un nuevo nuevo registro de lo contrario se edita
        if($idUrl==0){
             $id = $model->insertarProspecto($fechaAlta, $nombre, $aPaterno, $aManterno, $RFC, $fechaNac, $edad, $telefono, $celular, $genero,
                                             $NSS, $montoCredito, $tipoCreditoId, $subsidio, $puntosHasta, $comentario, $empresa, $captadoen, $email,
                                             $usuarioId, $gteProspeccionId, $gteVentasId, $prospectadorId, $agtVentasId, $estatusId,
                                             $rfcDuplicado, $periodoAsignacion, $fechaAsignacionAgt, $gerencias, $altaProspectadorId, $desarrolloId);

            //Salvar el historial del prospecto
            $nombreProspecto = $nombre." ".$aPaterno." ".$aManterno;
            $comentarioHist = "El usuario ".$this->userC->name." dio de alta al prospecto ".$nombreProspecto." el d&iacute;a ".$arrDateTime->fechaF2;
            SasfehpHelper::salvarHistorialProspecto($id, 15, $comentarioHist, $arrDateTime->fechaHora);

            // Imp. 26/10/20
            // Si viene la informacion desde un contacto
            $idContacto = JRequest::getVar('idContacto');
            if($idContacto!=""){
                //Desactivar contacto
                $modelContacto = JModelLegacy::getInstance('Contacto', 'SasfeModel');
                $modelContacto->desactivaContacto($idContacto, $arrDateTime->fechaHora);
                $estatusIdCont = 5; //Se ha convertido en prospecto
                $modelContacto->actEstatusContacto($idContacto, $estatusIdCont, $arrDateTime->fechaHora, $usuarioId);
            }
        }else{
            $usuarioIdActualizacion = $usuarioId;
            $model->actualizarProspecto($nombre, $aPaterno, $aManterno, $RFC, $fechaNac, $edad, $telefono, $celular, $genero,
                                        $NSS, $montoCredito, $tipoCreditoId, $subsidio, $puntosHasta, $comentario, $empresa, $captadoen, $email,
                                        $usuarioIdActualizacion, $gteProspeccionId, $gteVentasId, $prospectadorId, $agtVentasId, $estatusId,
                                        $rfcDuplicado, $periodoAsignacion, $fechaAsignacionAgt, $gerencias, $desarrolloId, $idUrl);
        }

        //Salvar el acceso en db (como aun no tiene asociado un departamento y fraccionamiento pues esos datos iran en 0,0)
        $modelLog = JModelLegacy::getInstance('logaccesos', 'SasfeModel');
        //Salvar datos para el log de accesos
        $idLog = $modelLog->insLogAcceso($this->userC->id, 0, 0, $arrDateTime->fechaHora);

        // exit();
        $msg = JText::sprintf('Registro salvado correctamente.');
        $idoption = ($idUrl==0) ? $id: $idUrl;
        $jinput = JFactory::getApplication()->input;
        $task = $jinput->get('task');

        //Salir de la edicion porque se trata de un rfc repetido
        if($rfcDuplicado==1){
          $task = "saveandclose";

          //Notificarle al gerente correspondiente aun no se aplica la accion para notificarle al director
          $asunto = "Prospecto duplicado";
          $userMail = JFactory::getUser($idGte);
          $arrCorreos = array($userMail->email);

          $body = '<div>Estimado(a) <b>'.$userMail->name.'</b> tiene un prospecto duplicado:</div><br/>

                     <div>ID Prospecto: '.$idoption.'</div>
                     <div>Nombre: '.$nombre.' '.$aPaterno.' '.$aManterno.'</div>
                     <div>RFC: '.$RFC.'</div>

                     <br/><div style="font-size:13px;">Este correo es generado autom&aacute;ticamente, favor de no responder</div><br/>
                   ';

          SasfehpHelper::notificarPorCorreo($asunto, $arrCorreos, $body);
          $msg = "El prospecto ya existe en la base de datos, se enviar&aacute; a revisi&oacute;n y de confirmarse la duplicidad se dar&aacute; de baja.";
        }

	  if($gerencias == 1){
	        //Mandamos notificación via correo electronico a dirección IMR
	        $colUserMC = SasfehpHelper::obtInfoUsuariosJoomlaPorGrupo(10);
	        if(count($colUserMC)>0){
	            $arrCorreosMC = array();
	            foreach($colUserMC as $elemUser){
	                $arrCorreosMC[] = $elemUser->email;
	            }
	            $asunto = "RFC duplicado en diferentes gerencias";
	            $bodyMC = '<div>El RFC '. $RFC . 'proporcionado pertence a diferentes gerencias'.' :</div><br/>

	                        <div>ID Prospecto: '.$idoption.'</div>
	                        <div>Nombre: '.$nombre.' '.$aPaterno.' '.$aManterno.'</div>
	                        <div>RFC: '.$RFC.'</div>

	                        <br/><div style="font-size:13px;">Este correo es generado autom&aacute;ticamente, favor de no responder</div><br/>
	                    ';
	            SasfehpHelper::notificarPorCorreo($asunto, $arrCorreosMC, $bodyMC);
	        }
	     }

        switch ($task) {
            case "apply":
                if($opcDatosProsp!="" && $opcDatosProsp==3){
                    $this->setRedirect( 'index.php?option=com_sasfe&view=prospecto&layout=edit&id='.$idoption.'&opc=3', $msg);
                }else{
                    $idContacto = JRequest::getVar('idContacto');
                    if($idContacto!=""){
                        $this->setRedirect( 'index.php?option=com_sasfe&view=prospectos', $msg);
                    }else{
                        $this->setRedirect( 'index.php?option=com_sasfe&view=prospecto&layout=edit&id='.$idoption.' ', $msg);
                    }
                }
                break;
            case "save":
                if($opcDatosProsp!="" && $opcDatosProsp==3){
                    $this->setRedirect( 'index.php?option=com_sasfe&view=listareventos&opc=0', $msg);
                }else{
                    $this->setRedirect( 'index.php?option=com_sasfe&view=prospectos',$msg);
                }
                break;
            case "saveandnew":
                if($opcDatosProsp!="" && $opcDatosProsp==3){
                    $this->setRedirect( 'index.php?option=com_sasfe&view=prospecto&layout=edit&id=0&opc=3',$msg);
                }else{
                    $this->setRedirect( 'index.php?option=com_sasfe&view=prospecto&layout=edit&id=0',$msg);
                }
                break;
            case "saveandclose":
                if($opcDatosProsp!="" && $opcDatosProsp==3){
                    $this->setRedirect( 'index.php?option=com_sasfe&view=listareventos&opc=0', $msg);
                }else{
                    $this->setRedirect( 'index.php?option=com_sasfe',$msg);
                }
                break;
        }
    }

    public function addevento(){
        require_once(JPATH_COMPONENT.'/helpers/sasfehp.php' );
        $arrDateTime = SasfehpHelper::obtDateTimeZone();
        $model = JModelLegacy::getInstance('Prospecto', 'SasfeModel');
        $modelGM = JModelLegacy::getInstance('Globalmodelsbk', 'SasfeModel');
        //Usuario logueado
        $usuarioLog = JFactory::getUser();
        $usuarioId = $usuarioLog->id;

        $opcDatosProsp = JRequest::getVar('opcDatosProsp');

        $datoProspectoId = JRequest::getVar('ev_idPros');
        $ev_tipoevento = JRequest::getVar('ev_tipoevento');
        $ev_hora = JRequest::getVar('ev_hora');
        $ev_fechahora = SasfehpHelper::conversionFecha(JRequest::getVar('ev_fecha'))." ".$ev_hora;
        $ev_tiempo = (JRequest::getVar('ev_tiempo')!="") ?JRequest::getVar('ev_tiempo') :"NULL";
        $ev_comentario = htmlentities(JRequest::getVar('ev_comentario'));
        $opcionId = 1; //evento=1, comentario=2
        $fechaCreacion = $arrDateTime->fechaHora;
        $edit_evpros = (JRequest::getVar('edit_evpros')!="") ?JRequest::getVar('edit_evpros') :0;


        //Salvar
        $id = $model->insertarEvento($datoProspectoId, $ev_comentario, $opcionId, $ev_fechahora, $ev_tiempo, $ev_tipoevento, $usuarioId, $fechaCreacion);
        if($id>0){
            $msg = JText::sprintf('Registro salvado correctamente.');
        }else{
            $msg = JText::sprintf('Registro no salvado.');
        }

        //Salvar el acceso en db (como aun no tiene asociado un departamento y fraccionamiento pues esos datos iran en 0,0)
        $modelLog = JModelLegacy::getInstance('logaccesos', 'SasfeModel');
        //Salvar datos para el log de accesos
        $idLog = $modelLog->insLogAcceso($usuarioLog->id, 0, 0, $arrDateTime->fechaHora);


        if($edit_evpros==1){
            if($opcDatosProsp!="" && $opcDatosProsp==3){
                $this->setRedirect('index.php?option=com_sasfe&view=prospecto&layout=edit&id='.$datoProspectoId.'&opc=3',$msg);
            }
            elseif($opcDatosProsp!="" && $opcDatosProsp==1000){
                $this->setRedirect('index.php?option=com_sasfe&view=prospecto&layout=editsu&id='.$datoProspectoId,$msg);
            }
            else{
                $this->setRedirect('index.php?option=com_sasfe&view=prospecto&layout=edit&id='.$datoProspectoId,$msg);
            }
        }else{
            // if($opcDatosProsp!="" && $opcDatosProsp==3){
            //     $this->setRedirect( 'index.php?option=com_sasfe&view=listareventos&opc=0',$msg);
            // }else{
                $this->setRedirect( 'index.php?option=com_sasfe&view=prospectos',$msg);
            // }
        }
    }

    //Agregar el comentario
    public function addcomentario(){
        require_once(JPATH_COMPONENT.'/helpers/sasfehp.php' );
        $arrDateTime = SasfehpHelper::obtDateTimeZone();
        $model = JModelLegacy::getInstance('Prospecto', 'SasfeModel');
        $modelGM = JModelLegacy::getInstance('Globalmodelsbk', 'SasfeModel');
        //Usuario logueado
        $usuarioLog = JFactory::getUser();
        $usuarioId = $usuarioLog->id;

        $opcDatosProsp = JRequest::getVar('opcDatosProsp');

        $datoProspectoId = JRequest::getVar('com_idPros');
        $ev_tipoevento = "NULL";
        $ev_fechahora = $arrDateTime->fechaHora;
        $ev_tiempo = "NULL";
        $ev_comentario = htmlentities(JRequest::getVar('ev_comentario'));
        $opcionId = 2; //evento=1, comentario=2
        $fechaCreacion = $arrDateTime->fechaHora;

        //Salvar
        $id = $model->insertarEvento($datoProspectoId, $ev_comentario, $opcionId, $ev_fechahora, $ev_tiempo, $ev_tipoevento, $usuarioId, $fechaCreacion);
        if($id>0){
            $msg = JText::sprintf('Registro salvado correctamente.');
        }else{
            $msg = JText::sprintf('Registro no salvado.');
        }

        //Salvar el acceso en db (como aun no tiene asociado un departamento y fraccionamiento pues esos datos iran en 0,0)
        $modelLog = JModelLegacy::getInstance('logaccesos', 'SasfeModel');
        //Salvar datos para el log de accesos
        $idLog = $modelLog->insLogAcceso($usuarioLog->id, 0, 0, $arrDateTime->fechaHora);


        if($opcDatosProsp!="" && $opcDatosProsp==3){
            $this->setRedirect('index.php?option=com_sasfe&view=prospecto&layout=edit&id='.$datoProspectoId.'&opc=3',$msg);
        }
        elseif($opcDatosProsp!="" && $opcDatosProsp==1000){
            $this->setRedirect('index.php?option=com_sasfe&view=prospecto&layout=editsu&id='.$datoProspectoId,$msg);
        }else{
            $this->setRedirect('index.php?option=com_sasfe&view=prospecto&layout=edit&id='.$datoProspectoId,$msg);
        }
    }

    //Verificar el rfc en db del prospectador
    public function comprobarRFC(){
        $html = '';
        $model = JModelLegacy::getInstance('Prospecto', 'SasfeModel');
        $modelGM = JModelLegacy::getInstance('Globalmodelsbk', 'SasfeModel');
        $respDB = $model->comprobarRFCDB(trim($_POST['rfc']), trim($_POST['idGte']));

        if(count($respDB)>0){
            $arr = array("result"=>true, "gteigual"=>$respDB);
            //$arr = array("res"=>true, "parametro"=>count($respDB));
        }else{
            $arr = array("result"=>false);
        }
        //imprimir el resultado
        $html .= '<response>';
        $html .= json_encode($arr);
        $html .= '</response>';
        echo $html;
    }

    //Salvar logica para asignar un agente de venta de aquellos prospectadores seleccionados
    public function asignarprospecto(){
        require_once(JPATH_COMPONENT.'/helpers/sasfehp.php' );
        $arrDateTime = SasfehpHelper::obtDateTimeZone();
        $model = JModelLegacy::getInstance('Prospecto', 'SasfeModel');
        $modelGM = JModelLegacy::getInstance('Globalmodelsbk', 'SasfeModel');
        $usuarioLog = JFactory::getUser();  //Usuario que esta logueado

        $agtVentasId = JRequest::getVar('asig_agtventas');
        //buscar en la tabla de datos catalogo el id del agente usuarioIdJoomla
        //Para esto ya se debde de haber agregado el usuario de joomla correspondiente de lo contrario marcaria un error
        $agenteDatosCat = SasfehpHelper::obtSelectInactivoAP($agtVentasId);
        $agtVentasId = $agenteDatosCat[0]->usuarioIdJoomla;
        // echo "es: ".$agtVentasId.'<br/>';
        // echo "<pre>";
        // print_r($agenteDatosCat);
        // echo "</pre>";
        // exit();

        if($agtVentasId!="" && $agtVentasId>0){
        $strIdsPros = JRequest::getVar('arrIdPros');
        $comentarioAsignar = JRequest::getVar('asig_comentario');
        $estatusId = 3; //Asignar a un agente de ventas
        $usuarioIdActualizacion = $usuarioLog->id;
        $gteProspeccionId = "NULL";
        $duplicado = 0;

        //Se activa el periodo de 3 meses a partir de la fecha de asignacion
        //08/07/21, Carlos, Cambio de periodo de 3 meses a 1
        $periodoAsignacion = "'".SasfehpHelper::conversionFecha(SasfehpHelper::mesesPrevPos(1, $arrDateTime->fecha, "pos"))."'";
        $fechaAsignacionAgt = "'".$arrDateTime->fecha."'";

        //Actualizar la tabla de prospectadores
        $colIdsPros = explode(",", $strIdsPros);
        foreach($colIdsPros as $idDatoProspecto) {
            $model->agregarAgtVentasOAsesor($agtVentasId, $estatusId, $comentarioAsignar, $periodoAsignacion, $usuarioIdActualizacion, $gteProspeccionId, $duplicado, $fechaAsignacionAgt, $idDatoProspecto);
                //Salvar el historial del prospecto
                //obtener nombre prospecto
                $datosProspecto = $model->obtenerDatosProspecto($idDatoProspecto);
                $prospectoNombre = ( isset($datosProspecto[0]->nombre) ) ?$datosProspecto[0]->nombre :"";
                $prospectoAPaterno = ( isset($datosProspecto[0]->aPaterno) ) ?$datosProspecto[0]->aPaterno :"";
                $prospectoAMaterno = ( isset($datosProspecto[0]->aManterno) ) ?$datosProspecto[0]->aManterno :"";
                $nombreProspecto = $prospectoNombre." ".$prospectoAPaterno." ".$prospectoAMaterno;
                //obtener nombre de agente
                $usrJoomlaDatos = SasfehpHelper::obtInfoUsuariosJoomla($agtVentasId);
                $comentarioHist = "El usuario ".$usuarioLog->name." asigno el asesor ".$usrJoomlaDatos->name." al prospecto ".$nombreProspecto." el d&iacute;a ".$arrDateTime->fechaF2;
                SasfehpHelper::salvarHistorialProspecto($idDatoProspecto, 16, $comentarioHist, $arrDateTime->fechaHora);
        }

        //Salvar el acceso en db (como aun no tiene asociado un departamento y fraccionamiento pues esos datos iran en 0,0)
        $modelLog = JModelLegacy::getInstance('logaccesos', 'SasfeModel');
        //Salvar datos para el log de accesos
        $idLog = $modelLog->insLogAcceso($usuarioLog->id, 0, 0, $arrDateTime->fechaHora);

        // exit();
        if(count($colIdsPros)>1){
                $msg = JText::sprintf('Registros salvados correctamente.');
        }else{
            if(count($colIdsPros)>0){
                    $msg = JText::sprintf('Registro salvado correctamente.');
            }else{
                    $msg = JText::sprintf('Registro no salvado.');
            }
        }
        $this->setRedirect( 'index.php?option=com_sasfe&view=prospectos',$msg);
        }else{
            $msg = "No es posible asignar el agente de ventas seleccionado ya que aun no esta asociado a un usuario de joomla en el cat&aacute;logo de Agentes de venta.";
            $this->setRedirect( 'index.php?option=com_sasfe&view=prospectos',$msg);
        }
    }

    //Redireccionar a la vista de mis eventos y mostrar segun el usuario logueado
    public function miseventos(){
        $vista_eventos = JRequest::getVar('vista_eventos');
        switch ($vista_eventos) {
            case 0:
            echo "entre: ".$vista_eventos;
            $this->setRedirect( 'index.php?option=com_sasfe&view=listareventos&opc=0'); break;
            case 1:
            echo "entre2: ".$vista_eventos;
            $this->setRedirect( 'index.php?option=com_sasfe&view=listareventos&opc=1'); break;
        }
        // exit();
    }

    //Asignar agentes de ventas a los prospectos
    public function validarprospectorepetido(){
        require_once(JPATH_COMPONENT.'/helpers/sasfehp.php' );
        $arrDateTime = SasfehpHelper::obtDateTimeZone();
        $model = JModelLegacy::getInstance('Prospecto', 'SasfeModel');
        $modelGM = JModelLegacy::getInstance('Globalmodelsbk', 'SasfeModel');
        $usuarioLog = JFactory::getUser();  //Usuario que esta logueado

        //buscar en la tabla de datos catalogo el id del agente usuarioIdJoomla
        //Para esto ya se debe de haber agregado el usuario de joomla correspondiente de lo contrario marcaria un error
        $datosUsuariosComp = SasfehpHelper::obtSelectInactivoAP(JRequest::getVar('rep_usuario'));
        $usuarioIdJoomla = $datosUsuariosComp[0]->usuarioIdJoomla;
        // echo "<pre>";
        // print_r($datosUsuariosComp);
        // echo "</pre>";
        // echo $usuarioIdJoomla.'<br/>';
        // exit();

        $rep_comentario = JRequest::getVar('rep_comentario');
        $strIdsPros = JRequest::getVar('arrRepetidoId');
        $colIdsPros = explode(",", $strIdsPros);
        if($usuarioIdJoomla!="" && $usuarioIdJoomla>0){
            //Para asignar gerentes de ventas
            if(JRequest::getVar('idGteSel')==1){
                //columnas a modificar
                // usuarioIdActualizacion, gteProspeccionId, gteVentasId, estatusId, comentarioAsignar, duplicado, periodoAsignacion
                $usuarioIdActualizacion = $usuarioLog->id;
                $gteProspeccionId = "NULL";
                $gteVentasId = $usuarioIdJoomla;
                $duplicado = 0;
                $periodoAsignacion = "NULL";
                //Asigna prospectos a un gerente de ventas
                foreach($colIdsPros as $idDatoProspecto) {
                    $model->asignarGteVentaAProspectos($usuarioIdActualizacion, $gteProspeccionId, $gteVentasId, $duplicado, $periodoAsignacion, $rep_comentario, $idDatoProspecto);
                    //Salvar el historial del prospecto
                    //obtener nombre prospecto
                    $datosProspecto = $model->obtenerDatosProspecto($idDatoProspecto);
                    $prospectoNombre = ( isset($datosProspecto[0]->nombre) ) ?$datosProspecto[0]->nombre :"";
                    $prospectoAPaterno = ( isset($datosProspecto[0]->aPaterno) ) ?$datosProspecto[0]->aPaterno :"";
                    $prospectoAMaterno = ( isset($datosProspecto[0]->aManterno) ) ?$datosProspecto[0]->aManterno :"";
                    $nombreProspecto = $prospectoNombre." ".$prospectoAPaterno." ".$prospectoAMaterno;
                    //obtener nombre del gerente
                    $usrJoomlaDatos = SasfehpHelper::obtInfoUsuariosJoomla($gteVentasId);
                    $comentarioHist = "El usuario ".$usuarioLog->name." asigno el gerente de ventas ".$usrJoomlaDatos->name." al prospecto ".$nombreProspecto." el d&iacute;a ".$arrDateTime->fechaF2;
                    SasfehpHelper::salvarHistorialProspecto($idDatoProspecto, 17, $comentarioHist, $arrDateTime->fechaHora);
                }
            }else{
                //Para asignar agentes de venta
                $usuarioIdActualizacion = $usuarioLog->id;
                $gteProspeccionId = "NULL";
                $agtVentasId = $usuarioIdJoomla;
                $duplicado = 0;
                $estatusId = 3; //Asignar a un agente de ventas

                //Se activa el periodo de 3 meses a partir de la fecha de asignacion
                //08/07/21, Carlos, Cambio de periodo de 3 meses a 1
                $periodoAsignacion = "'".SasfehpHelper::conversionFecha(SasfehpHelper::mesesPrevPos(1, $arrDateTime->fecha, "pos"))."'";
                $fechaAsignacionAgt = "'".$arrDateTime->fecha."'";

                //Actualizar la tabla de prospectos
                foreach($colIdsPros as $idDatoProspecto){
                    $model->agregarAgtVentasOAsesor($agtVentasId, $estatusId, $rep_comentario, $periodoAsignacion, $usuarioIdActualizacion, $gteProspeccionId, $duplicado, $fechaAsignacionAgt, $idDatoProspecto);
                    //Salvar el historial del prospecto
                    //obtener nombre prospecto
                    $datosProspecto = $model->obtenerDatosProspecto($idDatoProspecto);
                    $prospectoNombre = ( isset($datosProspecto[0]->nombre) ) ?$datosProspecto[0]->nombre :"";
                    $prospectoAPaterno = ( isset($datosProspecto[0]->aPaterno) ) ?$datosProspecto[0]->aPaterno :"";
                    $prospectoAMaterno = ( isset($datosProspecto[0]->aManterno) ) ?$datosProspecto[0]->aManterno :"";
                    $nombreProspecto = $prospectoNombre." ".$prospectoAPaterno." ".$prospectoAMaterno;
                    //obtener nombre de agente
                    $usrJoomlaDatos = SasfehpHelper::obtInfoUsuariosJoomla($agtVentasId);
                    $comentarioHist = "El usuario ".$usuarioLog->name." asigno el asesor ".$usrJoomlaDatos->name." al prospecto ".$nombreProspecto." el d&iacute;a ".$arrDateTime->fechaF2;
                    SasfehpHelper::salvarHistorialProspecto($idDatoProspecto, 16, $comentarioHist, $arrDateTime->fechaHora);
                }
            }

            if(count($colIdsPros)>1){
                $msg = JText::sprintf('Registros salvados correctamente.');
            }else{
                if(count($colIdsPros)>0){
                    $msg = JText::sprintf('Registro salvado correctamente.');
                }else{
                    $msg = JText::sprintf('Registro no salvado.');
                }
            }

            //Salvar el acceso en db (como aun no tiene asociado un departamento y fraccionamiento pues esos datos iran en 0,0)
            $modelLog = JModelLegacy::getInstance('logaccesos', 'SasfeModel');
            //Salvar datos para el log de accesos
            $idLog = $modelLog->insLogAcceso($usuarioLog->id, 0, 0, $arrDateTime->fechaHora);

            $this->setRedirect( 'index.php?option=com_sasfe&view=prospectos&layout=repetidos',$msg);
        }else{
            if(JRequest::getVar('idGteSel')==1){
                $msg = "No es posible asignar el gerente de ventas seleccionado ya que aun no esta asociado a un usuario de joomla en el cat&aacute;logo de gerentes venta.";
            }
            if(JRequest::getVar('idGteSel')==0){
                $msg = "No es posible asignar el agente de ventas seleccionado ya que aun no esta asociado a un usuario de joomla en el cat&aacute;logo de Asesores (Agentes de venta).";
            }
            $this->setRedirect( 'index.php?option=com_sasfe&view=prospectos&layout=repetidos',$msg);
        }
    }

    //Asigna gerente de venta a una agente de venta(asesor)
    public function asignarprospectoagteventas(){
        require_once(JPATH_COMPONENT.'/helpers/sasfehp.php' );
        $arrDateTime = SasfehpHelper::obtDateTimeZone();
        $model = JModelLegacy::getInstance('Prospecto', 'SasfeModel');
        $modelGM = JModelLegacy::getInstance('Globalmodelsbk', 'SasfeModel');
        $usuarioLog = JFactory::getUser();  //Usuario que esta logueado

        //buscar en la tabla de datos catalogo el id del gerente usuarioIdJoomla
        //Para esto ya se debe de haber agregado el usuario de joomla correspondiente de lo contrario marcaria un error
        $datosUsuariosComp = SasfehpHelper::obtSelectInactivoAP(JRequest::getVar('asiggtev_gteventas'));
        $usuarioIdJoomla = $datosUsuariosComp[0]->usuarioIdJoomla;
        // echo "<pre>";
        // print_r($datosUsuariosComp);
        // echo "</pre>";
        // echo $usuarioIdJoomla.'<br/>';
        // exit();

        $rep_comentario = JRequest::getVar('asiggtev_comentario');
        $strIdsPros = JRequest::getVar('arrIdProsGteV');
        $colIdsPros = explode(",", $strIdsPros);
        if($usuarioIdJoomla!="" && $usuarioIdJoomla>0){
            //columnas a modificar
            // usuarioIdActualizacion, gteProspeccionId, gteVentasId, estatusId, comentarioAsignar, duplicado, periodoAsignacion
            $usuarioIdActualizacion = $usuarioLog->id;
            $gteProspeccionId = "NULL";
            $gteVentasId = $usuarioIdJoomla;
            $duplicado = 0;
            $periodoAsignacion = "NULL";

            //Asigna prospectos a un gerente de ventas
            foreach($colIdsPros as $idDatoProspecto) {
                $model->asignarGteVentaAProspectos($usuarioIdActualizacion, $gteProspeccionId, $gteVentasId, $duplicado, $periodoAsignacion, $rep_comentario, $idDatoProspecto);
                //Salvar el historial del prospecto
                //obtener nombre prospecto
                $datosProspecto = $model->obtenerDatosProspecto($idDatoProspecto);
                $prospectoNombre = ( isset($datosProspecto[0]->nombre) ) ?$datosProspecto[0]->nombre :"";
                $prospectoAPaterno = ( isset($datosProspecto[0]->aPaterno) ) ?$datosProspecto[0]->aPaterno :"";
                $prospectoAMaterno = ( isset($datosProspecto[0]->aManterno) ) ?$datosProspecto[0]->aManterno :"";
                $nombreProspecto = $prospectoNombre." ".$prospectoAPaterno." ".$prospectoAMaterno;
                //obtener nombre del gerente
                $usrJoomlaDatos = SasfehpHelper::obtInfoUsuariosJoomla($gteVentasId);
                $comentarioHist = "El usuario ".$usuarioLog->name." asigno el gerente de ventas ".$usrJoomlaDatos->name." al prospecto ".$nombreProspecto." el d&iacute;a ".$arrDateTime->fechaF2;
                SasfehpHelper::salvarHistorialProspecto($idDatoProspecto, 17, $comentarioHist, $arrDateTime->fechaHora);
            }

            if(count($colIdsPros)>1){
                $msg = JText::sprintf('Registros salvados correctamente.');
            }else{
                if(count($colIdsPros)>0){
                    $msg = JText::sprintf('Registro salvado correctamente.');
                }else{
                    $msg = JText::sprintf('Registro no salvado.');
                }
            }

            //Salvar el acceso en db (como aun no tiene asociado un departamento y fraccionamiento pues esos datos iran en 0,0)
            $modelLog = JModelLegacy::getInstance('logaccesos', 'SasfeModel');
            //Salvar datos para el log de accesos
            $idLog = $modelLog->insLogAcceso($usuarioLog->id, 0, 0, $arrDateTime->fechaHora);

            $this->setRedirect( 'index.php?option=com_sasfe&view=prospectos',$msg);
        }else{
            $msg = "No es posible asignar el gerente de ventas seleccionado ya que aun no esta asociado a un usuario de joomla en el cat&aacute;logo de gerentes venta.";
            $this->setRedirect( 'index.php?option=com_sasfe&view=prospectos',$msg);
        }
    }

    //Proteger prospecto
    public function protegerpros(){
        require_once(JPATH_COMPONENT.'/helpers/sasfehp.php' );
        $arrDateTime = SasfehpHelper::obtDateTimeZone();
        $model = JModelLegacy::getInstance('Prospecto', 'SasfeModel');
        $modelGM = JModelLegacy::getInstance('Globalmodelsbk', 'SasfeModel');
        $usuarioLog = JFactory::getUser();  //Usuario que esta logueado

        $idTiempoProteccion = JRequest::getVar('prot_tiempo');
        $colIdsPros = explode(",", JRequest::getVar('arrIdProsProt'));

        $meses=false;
        switch ($idTiempoProteccion) {
            case 1: $tiempo=7; break; //Una semana
            case 2: $tiempo=15; break; //15 dias
            case 3: $tiempo=1; $meses=true; break; //un mes
        }

        //Obtener la fecha por vencer
        foreach ($colIdsPros as $idDatoProspecto) {
            $datosProspecto = $model->obtenerDatosProspecto($idDatoProspecto);
            if($meses==true){
               $fechaProteccion = SasfehpHelper::conversionFecha(SasfehpHelper::mesesPrevPos($tiempo, $datosProspecto[0]->periodoAsignacion, "pos"));
            }else{
               $fechaProteccion = SasfehpHelper::conversionFecha(SasfehpHelper::diasPrevPos($tiempo, $datosProspecto[0]->periodoAsignacion, "pos"));
            }
            //Salvar la fecha e id de la proteccion
            $model->actFechaProteccionProspecto($idTiempoProteccion, $fechaProteccion, $idDatoProspecto);
        }

        // exit();
        if(count($colIdsPros)>1){
            $msg = JText::sprintf('Registros salvados correctamente.');
        }else{
            if(count($colIdsPros)>0){
                $msg = JText::sprintf('Registro salvado correctamente.');
            }else{
                $msg = JText::sprintf('Registro no salvado.');
            }
        }

        // //Salvar el acceso en db (como aun no tiene asociado un departamento y fraccionamiento pues esos datos iran en 0,0)
        // $modelLog = JModelLegacy::getInstance('logaccesos', 'SasfeModel');
        // //Salvar datos para el log de accesos
        // $idLog = $modelLog->insLogAcceso($usuarioLog->id, 0, 0, $arrDateTime->fechaHora);

        $this->setRedirect( 'index.php?option=com_sasfe&view=prospectos',$msg);
    }


    //>>>
    //>>Metodos referente a la reasignacion de casa
    //>>>
    //Obtener departamentos disponibles por id del fraccionamiento
    public function obtenerDepartamentosDisponibles($idFracc=0){
        $html = '';
        require_once(JPATH_COMPONENT.'/helpers/sasfehp.php');
        $arrDateTime = SasfehpHelper::obtDateTimeZone();
        $model = JModelLegacy::getInstance('Prospecto', 'SasfeModel');
        $modelGM = JModelLegacy::getInstance('Globalmodelsbk', 'SasfeModel');
        $usuarioLog = JFactory::getUser();  //Usuario que esta logueado
        // $arr = array("result"=>true, "idFracc"=>trim($_POST['idFracc']), "idProspectador"=>trim($_POST['idProspectador']));
        //echo $_POST['idFracc'];
        //exit();

        $respDB = SasfehpHelper::obtenerDepartamentosDisponibles(trim($_POST['idFracc']), trim($_POST['idProspectador']), trim($_POST['idGteV']));
        // echo "<pre>"; print_r($respDB); echo "</pre>";
        // echo "<pre>"; print_r($_POST); echo "</pre>";
        // exit();
        if(count($respDB)>0){
            foreach($respDB as $elemDpto) {
                $arrDptos[] = array("idDepartamento"=>$elemDpto->idDepartamento, "numero"=>$elemDpto->numero);
            }
            $arr = array("result"=>true, "arrDptos"=>$arrDptos);
        }else{
            $arr = array("result"=>false);
        }

        //imprimir el resultado
        $html .= '<response>';
        $html .= json_encode($arr);
        $html .= '</response>';
        echo $html;
    }
    //Apartar casa
    public function asignarcasa(){
        require_once(JPATH_COMPONENT.'/helpers/sasfehp.php');
        require_once(JPATH_COMPONENT.'/controllers/departamento.php');

        $arrDateTime = SasfehpHelper::obtDateTimeZone();
        $model = JModelLegacy::getInstance('Prospecto', 'SasfeModel');
        $modelGM = JModelLegacy::getInstance('Globalmodelsbk', 'SasfeModel');
        $usuarioLog = JFactory::getUser();  //Usuario que esta logueado
        $ctrDepartamento = new SasfeControllerDepartamento();

        $idFraccionamiento = JRequest::getVar('asigcasa_fraccionamiento');
        $departamentoId = JRequest::getVar('asigcasa_casadpto');
        $idDatoProspecto = JRequest::getVar('asigcasa_idPros');
        //08/07/21, Carlos, Cambio de 7 dias a 4 dias
        $fechaLimiteApartado = SasfehpHelper::conversionFecha(SasfehpHelper::diasPrevPos(4, $arrDateTime->fecha, "pos")); //7 dias para que mesa de control asigne de forma permanente la casa

         //Salvar
        $opcDatosProsp = JRequest::getVar('opcDatosProsp');

        //Actualizar el departamento
        if($departamentoId!=""){
            $model->apartarDepartamento($departamentoId, $fechaLimiteApartado, $idDatoProspecto);
            //obtener datos de la tabla #__sasfe_datos_prospectos  para setearlos y se salve correctamente un registro de dao general
            $datosProspecto = $model->obtenerDatosProspecto($idDatoProspecto);
            //Agregar ids de departamentos/casa asignados por prospectador
            $model->agregarIdsDptosPorIdProspecto($datosProspecto, $departamentoId, $idDatoProspecto);
            $_POST['datoProspectoId'] = $idDatoProspecto;
            $_POST['id_Fracc'] = $idFraccionamiento;
            $_POST['id_DatoGral'] = 0; //Es cero porque se creara
            $_POST['id_Dpt'] = $departamentoId;
            $_POST['dtu_dg'] = 0; //Por el momento dejar en 0
            $_POST['fApartado'] = SasfehpHelper::conversionFechaF2($arrDateTime->fecha);

            //obtener de la tabla datosCatalogo su id
            $gteVentasId = ""; //Gerente de ventas
            if($datosProspecto[0]->gteVentasId !=""){
                $datosGteJoomla = SasfehpHelper::obtUsuarioDatosCatalogoPorIdUsrJoomla($datosProspecto[0]->gteVentasId);
                $gteVentasId = $datosGteJoomla[0]->idDato;
            }
            $agtVentasId = ""; //Asesor o Agente de ventas
            if($datosProspecto[0]->agtVentasId !=""){
                $datosUserJoomla = SasfehpHelper::obtUsuarioDatosCatalogoPorIdUsrJoomla($datosProspecto[0]->agtVentasId);
                $agtVentasId = $datosUserJoomla[0]->idDato;
            }
            $_POST['gte_vtas_dg'] = $gteVentasId; //obtener de la tabla datosCatalogo
            $_POST['asesor_dg'] = $agtVentasId; //obtener de la tabla datosCatalogo
            $_POST['estatus_dg'] = 401;//Apartada provisional //90; //En validacion

            //Agregar al prospectador
            $prospectadorId = ""; //Asesor o Agente de ventas
            if($datosProspecto[0]->altaProspectadorId !=""){
                $datosProspectadorJoomla = SasfehpHelper::obtUsuarioDatosCatalogoPorIdUsrJoomla($datosProspecto[0]->altaProspectadorId);
                $prospectadorId = $datosProspectadorJoomla[0]->idDato;
            }
            $_POST['prospectador_dg'] = $prospectadorId; //obtener de la tabla datosCatalogo
            //Datos para el cliente
            $_POST['aPaternoC_dg'] = $datosProspecto[0]->aPaterno;
            $_POST['aMaternoC_dg'] = $datosProspecto[0]->aManterno;
            $_POST['nombreC_dg'] = $datosProspecto[0]->nombre;
            $_POST['nssC_dg'] = $datosProspecto[0]->NSS;
            $_POST['tipoCto_dg'] = $datosProspecto[0]->tipoCreditoId;
            $_POST['empresa_cl'] = $datosProspecto[0]->empresa;
            $_POST['fNacimiento'] = SasfehpHelper::conversionFechaF2($datosProspecto[0]->fechaNac);
            $_POST['generoC_dg'] = $datosProspecto[0]->genero;
            $_POST['emailC_dg'] = $datosProspecto[0]->email;

            //Datos credito
            $_POST['cInfonavit_dc'] = $datosProspecto[0]->montoCredito;
            $_POST['subFed_dc'] = $datosProspecto[0]->subsidio;

            //Para el log de acceso
            $_POST['id_Usuario'] = $usuarioLog->id;
            $_POST['fAcceso'] = $arrDateTime->fechaHora;

            //Telefonos del prospecto
            $_POST['telefono'] = $datosProspecto[0]->telefono;
            $_POST['celular'] = $datosProspecto[0]->celular;

            //Extra para saber a que pantalla redireccionar
            $_POST['opcRedireccion'] = $opcDatosProsp;

            // Imp. 29/09/21, Carlos => Obtener la fecha dtu por el id del departamento
            $datosDepto = SasfehpHelper::obtInfoDptPorId($departamentoId);
            if(isset($datosDepto->idDepartamento) && $datosDepto->idDepartamento>0){
                $fechaDTU = SasfehpHelper::conversionFechaF2($datosDepto->fechaDTU);
                $_POST['fdtu'] = $fechaDTU;
                $_POST['dtu_dg'] = 1;
            }

            // echo "<pre>";
            // print_r($datosProspecto);
            // print_r($datosGteJoomla);
            // print_r($datosUserJoomla);
            // print_r($_POST);
            // echo "</pre>";
            // exit();
            /*
            [fechaAlta] => 2017-09-06 15:11:14
            [RFC] => JRS545423
            [edad] => 30
            [NSS] => 656GYSHS3
            [puntosHasta] => 2017-09-30
            [comentario] => creando prospecto para Jose Luis pero esta duplicado su rfc debe de ir a la bandeja de ver repetidos
            [captadoen] => ----
            [usuarioId] => 316
            [usuarioIdActualizacion] => 317
            [gteProspeccionId] =>
            [gteVentasId] => 317
            [prospectadorId] =>
            [agtVentasId] => 316
            [estatusId] => 3
            [comentarioAsignar] => Para el agente o asesor roberto salazar
            [duplicado] => 0
            [periodoAsignacion] => 2017-12-07
            [idTiempoProteccion] =>
            [fechaProteccion] =>
            [fechaLimiteApartado] =>
            [fechaDptoAsignado] =>
            */

            // //Salvar el historial del prospecto
            // $comentarioHist = "Se aparta la propiedad el d&iacute;a ".$arrDateTime->fechaF2;//. " para el prospecto ".$datosProspecto[0]->nombre." ".$datosProspecto[0]->aPaterno." ".$datosProspecto[0]->aManterno;
            // SasfehpHelper::salvarHistorialProspecto($idDatoProspecto, 1, $comentarioHist, $arrDateTime->fechaHora);

            //Mardar post al metodo para crear registro del datos generales
            $ctrDepartamento->apartarDptoCasa();

            // $msg = JText::sprintf('Se aparto el departamento/casa correctamente.');
        }else{
            $msg = JText::sprintf('No fue posible asignar el departamento/casa porque no selecciono ninguno.');
        }

        //Salvar el acceso en db (como aun no tiene asociado un departamento y fraccionamiento pues esos datos iran en 0,0)
        $modelLog = JModelLegacy::getInstance('logaccesos', 'SasfeModel');
        //Salvar datos para el log de accesos
        $idLog = $modelLog->insLogAcceso($usuarioLog->id, 0, 0, $arrDateTime->fechaHora);


        if($opcDatosProsp!="" && $opcDatosProsp==3){
            $this->setRedirect('index.php?option=com_sasfe&view=prospecto&layout=edit&id='.$idDatoProspecto.'&opc=3',$msg);
        }
        //Redireccionar al layout solo lectura
        elseif($opcDatosProsp!="" && $opcDatosProsp==1){
            $this->setRedirect('index.php?option=com_sasfe&view=prospecto&layout=sololectura&id='.$idDatoProspecto.'&opc=1',$msg);
        }
        else{
            $this->setRedirect('index.php?option=com_sasfe&view=prospecto&layout=edit&id='.$idDatoProspecto,$msg);
        }

        // TODO: Preguntar por los campos (dtu_dg, estatus_dg)
    }

    //Liberar casa
    public function liberarcasa(){
        require_once(JPATH_COMPONENT.'/helpers/sasfehp.php');
        require_once(JPATH_COMPONENT.'/controllers/departamento.php');

        $arrDateTime = SasfehpHelper::obtDateTimeZone();
        $model = JModelLegacy::getInstance('Prospecto', 'SasfeModel');
        $modelGM = JModelLegacy::getInstance('Globalmodelsbk', 'SasfeModel');
        $modelDP = JModelLegacy::getInstance('Departamento', 'SasfeModel');
        $usuarioLog = JFactory::getUser();  //Usuario que esta logueado
        $ctrDepartamento = new SasfeControllerDepartamento();

        $opcDatosProsp = JRequest::getVar('opcDatosProsp');

        //$idFraccionamiento = JRequest::getVar('asigcasa_fraccionamiento');
        $departamentoId = JRequest::getVar('dptId');
        $idDatoProspecto = JRequest::getVar('check_un');
        $vistaCtr = JRequest::getVar('vistaCtr'); //Saber a que vista redireccionar

        if($departamentoId!="" && $departamentoId>0 && $idDatoProspecto!="" && $idDatoProspecto>0){
            $modelDP->updHistReasigObsoleto($departamentoId); //activar su historico, reasigancion, obsoleto en 1 = mandar a basura los datos anteriores
            $resReset = $model->resetProspectadorPorIdProspecto($idDatoProspecto); //Limpiar los campos departamentoId, fechaLimiteApartado por el id del prospectador

            if($resReset>0){
                //Salvar el historial del prospecto
                $datosDpto = $modelDP->obtDatosDptoPorId($departamentoId); //Obtener datos de la propiedad
                $comentarioHist = "Se libera manualmente la propiedad ".$datosDpto[0]->numero." ".$datosDpto[0]->nombrefracc." el d&iacute;a ".$arrDateTime->fechaF2;//. " para el prospecto ".$datosProspecto[0]->nombre." ".$datosProspecto[0]->aPaterno." ".$datosProspecto[0]->aManterno;
                SasfehpHelper::salvarHistorialProspecto($idDatoProspecto, 3, $comentarioHist, $arrDateTime->fechaHora);

                $msg = JText::sprintf('Se ha liberado correctamente el departamente/casa.');

                // Imp. 29/09/21, Carlos, Setear el departamento a disponible en tabla departamentos
                SasfehpHelper::actDepartamentoOcupado($departamentoId, 0);

                //Actualizar el estatus de la tabla sasfe_datos_generales por el id del prospecto
                SasfehpHelper::actEstatusPorDeptoProspecto($departamentoId, $idDatoProspecto);
            }else{
                $msg = JText::sprintf('No es posible liberar el departamente/casa, intentar de nuevo.');
            }
        }else{
            $msg = JText::sprintf('No es posible liberar el departamente/casa, intentar de nuevo.');
        }

        //Salvar el acceso en db (como aun no tiene asociado un departamento y fraccionamiento pues esos datos iran en 0,0)
        $modelLog = JModelLegacy::getInstance('logaccesos', 'SasfeModel');
        //Salvar datos para el log de accesos
        $idLog = $modelLog->insLogAcceso($usuarioLog->id, 0, 0, $arrDateTime->fechaHora);


        if($vistaCtr==1){
            if($opcDatosProsp!="" && $opcDatosProsp==3){
                $this->setRedirect('index.php?option=com_sasfe&view=prospecto&layout=edit&id='.$idDatoProspecto.'&opc=3',$msg);
            }else{
                $this->setRedirect('index.php?option=com_sasfe&view=prospecto&layout=edit&id='.$idDatoProspecto,$msg);
            }
        }
        if($vistaCtr==2){
            if($opcDatosProsp!="" && $opcDatosProsp==3){
                $this->setRedirect('index.php?option=com_sasfe&view=prospecto&layout=edit&id='.$idDatoProspecto.'&opc=3',$msg);
            }else{
                $this->setRedirect('index.php?option=com_sasfe&view=prospecto&layout=sololectura&id='.$idDatoProspecto.'&opc=1',$msg);
            }
        }
    }

    //No procede
    public function noprocede(){
        require_once(JPATH_COMPONENT.'/helpers/sasfehp.php');
        require_once(JPATH_COMPONENT.'/controllers/departamento.php');

        $arrDateTime = SasfehpHelper::obtDateTimeZone();
        $model = JModelLegacy::getInstance('Prospecto', 'SasfeModel');
        $modelGM = JModelLegacy::getInstance('Globalmodelsbk', 'SasfeModel');
        $usuarioLog = JFactory::getUser();  //Usuario que esta logueado
        $arrDateTime = SasfehpHelper::obtDateTimeZone();
        //echo $arrDateTime->fecha.'<br/>';

        $opcDatosProsp = JRequest::getVar('opcDatosProsp');

        $idDatoProspecto = JRequest::getVar('noprocede_idPros');
        $comentarioNoProcesados = JRequest::getVar('noprocede_comentario');
        if($idDatoProspecto!=""){
            $resNP = $model->noProcederAsigarDpto($comentarioNoProcesados, $arrDateTime->fecha, $idDatoProspecto);
            if($resNP>0){
                $msg = JText::sprintf('La acci&oacute;n de no proceder se ha realizado correctamente.');
            }else{
                $msg = JText::sprintf('No fue posible realizar la acci&oacute;n de no proceder, intentar mas tarde.');
            }
        }else{
            $msg = JText::sprintf('No fue posible realizar la acci&oacute;n de no proceder, intentar mas tarde.');
        }

        //Salvar el acceso en db (como aun no tiene asociado un departamento y fraccionamiento pues esos datos iran en 0,0)
        // $modelLog = JModelLegacy::getInstance('logaccesos', 'SasfeModel');
        // //Salvar datos para el log de accesos
        // $idLog = $modelLog->insLogAcceso($usuarioLog->id, 0, 0, $arrDateTime->fechaHora);


        if($opcDatosProsp!="" && $opcDatosProsp==3){
            $this->setRedirect('index.php?option=com_sasfe&view=listareventos&opc=0',$msg);
        }
        else{
            $this->setRedirect('index.php?option=com_sasfe&view=prospectos',$msg);
        }
    }
    //>>>
    //>>>Aplica para el super usuario/direccion
    ///>>>
    public function addSu(){
       $varid = JRequest::getVar('id');
       $id = ($varid!='') ? $varid : 0;
       $this->setRedirect( 'index.php?option=com_sasfe&view=prospecto&layout=editsu&id='.$id.' ');
    }
    public function editSu($key=NULL,$urlVar=NULL){
        $cid = JRequest::getVar('cid', array(0));
        JArrayHelper::toInteger($cid);
        $id = (JRequest::getVar('id')!='') ? JRequest::getVar('id'): $cid[0];
        $this->setRedirect( 'index.php?option=com_sasfe&view=prospecto&layout=editsu&id='.$id.' ');
    }
    function applyDos(){
      $this->procesarProspectoSU();
    }
    function saveDos($key=NULL,$urlVar=NULL)
    {
     $this->procesarProspectoSU();
    }
    function saveandnewDos(){
        $this->procesarProspectoSU();
    }
    //Aplica cuando se trata de un grupo prospectador
    function saveandcloseDos(){
        $this->procesarProspectoSU();
    }
    function cancelDos($key=NULL)
    {
        $this->setRedirect( 'index.php?option=com_sasfe&view=prospectos');
    }
    //Salvar el prospecto para el super usuario y direccion
    public function procesarProspectoSU(){
        jimport('joomla.filesystem.file');
        require_once(JPATH_COMPONENT.'/helpers/sasfehp.php' );
        // Check for request forgeries
        JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));
        //leer el modelo correspondiente
        $model = JModelLegacy::getInstance('Prospecto', 'SasfeModel');
        $modelGM = JModelLegacy::getInstance('Globalmodelsbk', 'SasfeModel');
        $arrDateTime = SasfehpHelper::obtDateTimeZone();
        // echo "<pre>";
        // print_r($_POST);
        // echo "</pre>";
        // exit();
        //Salvar
        $opcDatosProsp = JRequest::getVar('opcDatosProsp');
        $this->userC = JFactory::getUser();
        //preguntar si es un agente de vantas o prospectador
        $idUsrJoomla = JRequest::getVar('idUsrJoomla');
        $opcUsuario = JRequest::getVar('opcUsuario');
        if($opcUsuario=="prospectador"){
            $esAgenteVentas = false;
            $prospectadorId = $idUsrJoomla;
            $agtVentasId = "";
            $altaProspectadorId = $idUsrJoomla;
        }
        if($opcUsuario=="agenteventas"){
            $esAgenteVentas = true;
            $agtVentasId = $idUsrJoomla;
            $prospectadorId = "";
            $altaProspectadorId = "NULL";
        }
        $idGte = JRequest::getVar('idGte');
        $opcGerente = JRequest::getVar('opcGerente');
        if($opcGerente=="gteprospeccion"){
            $esGteVentas = false;
            $gteProspeccionId = $idGte;
            $gteVentasId = "";
        }
        if($opcGerente=="gteventas"){
            $esGteVentas = true;
            $gteVentasId = $idGte;
            $gteProspeccionId = "";
        }
        //Reglas de negocio
        //1.- Si el rfc esta repetido se activara el campo repetido=1, el campo estatus sera NULL, no se asignara a ningun agente de ventas
        //lo puede ver los gerentes de ventas o gerentes prospeccion
        $rfcDuplicado = JRequest::getVar('rfc_duplicado');
        //obtener valores del formulario
        $fechaAlta = SasfehpHelper::conversionFecha(JRequest::getVar('fechaAlta'))." ".$arrDateTime->hora;
        $nombre = JRequest::getVar('nombre');
        $aPaterno = JRequest::getVar('aPaterno');
        $aManterno = JRequest::getVar('aManterno');
        $RFC = JRequest::getVar('RFC');
        // $fechaNac = SasfehpHelper::conversionFecha(JRequest::getVar('fechaNac'));
        $fechaNac = JRequest::getVar('fechaNac');
        $edad = (JRequest::getVar('edad')!="") ?JRequest::getVar('edad') :"NULL";
        $telefono = JRequest::getVar('telefono');
        $celular = JRequest::getVar('celular');
        $genero = JRequest::getVar('genero');
        $NSS = JRequest::getVar('NSS');
        $montoCredito = (JRequest::getVar('montoCredito')!="") ?SasfehpHelper::limpiarFormatoMonto(JRequest::getVar('montoCredito')) :"NULL";
        $tipoCreditoId = (JRequest::getVar('tipoCreditoId') !="") ?JRequest::getVar('tipoCreditoId') :"NULL";
        $subsidio = (JRequest::getVar('subsidio')!="") ?SasfehpHelper::limpiarFormatoMonto(JRequest::getVar('subsidio')) :"NULL";
        $puntosHasta = (JRequest::getVar('puntosHasta')!="") ?"'".SasfehpHelper::conversionFecha(JRequest::getVar('puntosHasta'))."'" :"NULL";
        $comentario = JRequest::getVar('comentario');
        $empresa = JRequest::getVar('empresa');
        $captadoen = (JRequest::getVar('captadoen')!="") ?JRequest::getVar('captadoen') :"NULL";
        $email = JRequest::getVar('email');
        $desarrolloId = (JRequest::getVar('idFracc')!="") ?JRequest::getVar('idFracc'):"NULL";  // 04/10/12 id desarrollo solo es informativo
        //IMR
        $gerencias = JRequest::getVar('gtvId_rfc');
        // $fraccionamientoId = (JRequest::getVar('fraccionamientoId')!="") ?JRequest::getVar('fraccionamientoId') :"NULL";
        $usuarioId = ($this->userC->id!="") ?$this->userC->id :"NULL";
        $gteProspeccionId = ($gteProspeccionId!="") ?$gteProspeccionId :"NULL";
        $gteVentasId = ($gteVentasId!="") ?$gteVentasId :"NULL";
        $periodoAsignacion = ""; //Periodo default
        $fechaAsignacionAgt = ""; //fecha de asignacion del agente de ventas
        if($rfcDuplicado==1){
            $prospectadorId = "NULL";
            // $agtVentasId = "NULL"; //Tomar el de arriba
            $agtVentasId = ($agtVentasId!="") ?$agtVentasId :"NULL";
            $estatusId = "NULL";
        }else{
            $prospectadorId = ($prospectadorId!="") ?$prospectadorId :"NULL";
            $agtVentasId = ($agtVentasId!="") ?$agtVentasId :"NULL";
            //Si es un agente de ventas el que lo da de alta se le asigna en automatico el prospectador
            if($esAgenteVentas==true){
                $estatusId = 2; //2= asignado el prospectador en automatico
                //Se activa el periodo de 3 meses a partir de la fecha de creacion
                //08/07/21, Carlos, Cambio de periodo de 3 meses a 1
                $periodoAsignacion = SasfehpHelper::conversionFecha(SasfehpHelper::mesesPrevPos(1, SasfehpHelper::conversionFecha(JRequest::getVar('fechaAlta')), "pos"));
                $fechaAsignacionAgt = $arrDateTime->fecha;
            }else{
                $estatusId = 1; //1= sin asignar agente de ventas
            }
        }
        $idUrl = JRequest::getVar('check_un');
        $periodoAsignacion = ($periodoAsignacion!="") ?"'".$periodoAsignacion."'" :"NULL"; //comprobar regla del periodo de tres meses
        $fechaAsignacionAgt = ($fechaAsignacionAgt!="") ?"'".$fechaAsignacionAgt."'" :"NULL"; //Fecha de asugnacion de agente
        // echo "usuarioId: ".$usuarioId.'<br/>';
        // echo "prospectadorId: ".$prospectadorId.'<br/>';
        // echo "agtVentasId: ".$agtVentasId.'<br/>';
        // echo "gteProspeccionId: ".$gteProspeccionId.'<br/>';
        // echo "gteVentasId: ".$gteVentasId.'<br/>';
        // exit();
        //si $idUrl=0 se crea un nuevo nuevo registro de lo contrario se edita
        if($idUrl==0){
             $id = $model->insertarProspecto($fechaAlta, $nombre, $aPaterno, $aManterno, $RFC, $fechaNac, $edad, $telefono, $celular, $genero,
                                             $NSS, $montoCredito, $tipoCreditoId, $subsidio, $puntosHasta, $comentario, $empresa, $captadoen, $email,
                                             $usuarioId, $gteProspeccionId, $gteVentasId, $prospectadorId, $agtVentasId, $estatusId,
                                             $rfcDuplicado, $periodoAsignacion, $fechaAsignacionAgt, $gerencias, $altaProspectadorId, $desarrolloId);
            //Salvar el historial del prospecto
            $nombreProspecto = $nombre." ".$aPaterno." ".$aManterno;
            $comentarioHist = "El usuario ".$this->userC->name." dio de alta al prospecto ".$nombreProspecto." el d&iacute;a ".$arrDateTime->fechaF2;
            SasfehpHelper::salvarHistorialProspecto($id, 15, $comentarioHist, $arrDateTime->fechaHora);
        }else{
            $usuarioIdActualizacion = $usuarioId;
            $model->actualizarProspecto($nombre, $aPaterno, $aManterno, $RFC, $fechaNac, $edad, $telefono, $celular, $genero,
                                        $NSS, $montoCredito, $tipoCreditoId, $subsidio, $puntosHasta, $comentario, $empresa, $captadoen, $email,
                                        $usuarioIdActualizacion, $gteProspeccionId, $gteVentasId, $prospectadorId, $agtVentasId, $estatusId,
                                        $rfcDuplicado, $periodoAsignacion, $fechaAsignacionAgt, $gerencias, $desarrolloId, $idUrl);
        }
        //Salvar el acceso en db (como aun no tiene asociado un departamento y fraccionamiento pues esos datos iran en 0,0)
        $modelLog = JModelLegacy::getInstance('logaccesos', 'SasfeModel');
        //Salvar datos para el log de accesos
        $idLog = $modelLog->insLogAcceso($this->userC->id, 0, 0, $arrDateTime->fechaHora);
        // exit();
        $msg = JText::sprintf('Registro salvado correctamente.');
        $idoption = ($idUrl==0) ? $id: $idUrl;
        $jinput = JFactory::getApplication()->input;
        $task = $jinput->get('task');
        //Salir de la edicion porque se trata de un rfc repetido
        if($rfcDuplicado==1){
          $task = "saveandclose";
          //Notificarle al gerente correspondiente aun no se aplica la accion para notificarle al director
          $asunto = "Prospecto duplicado";
          $userMail = JFactory::getUser($idGte);
          $arrCorreos = array($userMail->email);
          $body = '<div>Estimado(a) <b>'.$userMail->name.'</b> tiene un prospecto duplicado:</div><br/>
                     <div>ID Prospecto: '.$idoption.'</div>
                     <div>Nombre: '.$nombre.' '.$aPaterno.' '.$aManterno.'</div>
                     <div>RFC: '.$RFC.'</div>
                     <br/><div style="font-size:13px;">Este correo es generado autom&aacute;ticamente, favor de no responder</div><br/>
                   ';
          // SasfehpHelper::notificarPorCorreo($asunto, $arrCorreos, $body);
          $msg = "El prospecto ya existe en la base de datos, se enviar&aacute; a revisi&oacute;n y de confirmarse la duplicidad se dar&aacute; de baja.";
        }
      if($gerencias == 1){
            //Mandamos notificación via correo electronico a dirección IMR
            $colUserMC = SasfehpHelper::obtInfoUsuariosJoomlaPorGrupo(10);
            if(count($colUserMC)>0){
                $arrCorreosMC = array();
                foreach($colUserMC as $elemUser){
                    $arrCorreosMC[] = $elemUser->email;
                }
                $asunto = "RFC duplicado en diferentes gerencias";
                $bodyMC = '<div>El RFC '. $RFC . 'proporcionado pertence a diferentes gerencias'.' :</div><br/>
                            <div>ID Prospecto: '.$idoption.'</div>
                            <div>Nombre: '.$nombre.' '.$aPaterno.' '.$aManterno.'</div>
                            <div>RFC: '.$RFC.'</div>
                            <br/><div style="font-size:13px;">Este correo es generado autom&aacute;ticamente, favor de no responder</div><br/>
                        ';
                // SasfehpHelper::notificarPorCorreo($asunto, $arrCorreosMC, $bodyMC);
            }
         }
        switch ($task) {
            case "applyDos":
                // if($opcDatosProsp!="" && $opcDatosProsp==3){
                //     $this->setRedirect( 'index.php?option=com_sasfe&view=prospecto&layout=editsu&id='.$idoption.'&opc=3', $msg);
                // }else{
                    $this->setRedirect( 'index.php?option=com_sasfe&view=prospecto&layout=editsu&id='.$idoption.' ', $msg);
                // }
                break;
            case "saveDos":
                // if($opcDatosProsp!="" && $opcDatosProsp==3){
                //     $this->setRedirect( 'index.php?option=com_sasfe&view=listareventos&opc=0', $msg);
                // }else{
            $this->setRedirect('index.php?option=com_sasfe&view=prospectos',$msg);
                // }
                break;
            case "saveandnewDos":
                // if($opcDatosProsp!="" && $opcDatosProsp==3){
                //     $this->setRedirect( 'index.php?option=com_sasfe&view=prospecto&layout=editsu&id=0&opc=3',$msg);
                // }else{
                    $this->setRedirect( 'index.php?option=com_sasfe&view=prospecto&layout=editsu&id=0',$msg);
                // }
                break;
            case "saveandclose":
                $this->setRedirect( 'index.php?option=com_sasfe&view=prospectos',$msg);
                // if($opcDatosProsp!="" && $opcDatosProsp==3){
                //     $this->setRedirect( 'index.php?option=com_sasfe&view=listareventos&opc=0', $msg);
                // }else{
            //         $this->setRedirect( 'index.php?option=com_sasfe',$msg);
                // }
                break;
        }
    }

}

?>
