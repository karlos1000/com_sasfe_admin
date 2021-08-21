<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted Access');
jimport('joomla.application.component.controllerform');

class SasfeControllerDepartamento extends JControllerForm {

    function cancel($key=NULL)
    {
        $id_fracc = JRequest::getVar('id_Fracc');
        $this->setRedirect( 'index.php?option=com_sasfe&view=listadodeptos&idFracc='.$id_fracc);
    }
    public function edit($key=NULL, $urlVar=NULL){
    }
    function apply(){
      $this->salvarDatosDpto();
    }
    function save($key=NULL, $urlVar=NULL)
    {
     $this->salvarDatosDpto();
    }
    function saveandnew(){
        $this->salvarDatosDpto();
    }

    public function salvarDatosDpto(){
        $this->salvar(0);
    }

    function salvarDatoHistorial(){
        $this->salvar(1);
    }

    function reallocate(){
        jimport('joomla.filesystem.file');
        JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));
        $model = JModelLegacy::getInstance('Departamento', 'SasfeModel');
        $modelGM = JModelLegacy::getInstance('globalmodelsbk', 'SasfeModel');
        $fechaEstatus = date("Y-m-d");

        $id_Fracc = JRequest::getVar('id_Fracc');
        $id_dato = JRequest::getVar('id_DatoGral');
        $id_Dpt = JRequest::getVar('id_Dpt');

        echo '$id_Fracc: ' .$id_Fracc;
        echo '$id_dato: ' .$id_dato;
        echo '$id_Dpt: ' .$id_Dpt;

        $this->setRedirect( 'index.php?option=com_sasfe&view=seldepartamentos&depto_id='.$id_Dpt.'&idFracc='.$id_Fracc.'&idDatoGral='.$id_dato);
    }


    public function salvar($param){
        jimport('joomla.filesystem.file');
        require_once(JPATH_COMPONENT.'/helpers/sasfehp.php');
        $arrDateTime = SasfehpHelper::obtDateTimeZone();
        JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));
        $model = JModelLegacy::getInstance('Departamento', 'SasfeModel');
        $modelGM = JModelLegacy::getInstance('globalmodelsbk', 'SasfeModel');
        $modelLog = JModelLegacy::getInstance('logaccesos', 'SasfeModel');
        $fechaEstatus = date("Y-m-d");
        //obtener valores del formulario
//        $arrForm = JRequest::get('post'); //lee todas las variables por post
//        echo '<pre>'; print_r($arrForm); echo '</pre>';

        //parametros para el log de acceso
        $id_Usuario = JRequest::getVar('id_Usuario');
        $fAcceso = JRequest::getVar('fAcceso');

        $id_Fracc = JRequest::getVar('id_Fracc');
        $id_dato = JRequest::getVar('id_DatoGral');
        $id_Dpt = JRequest::getVar('id_Dpt');
        $dtu_dg = JRequest::getVar('dtu_dg');
        $fApartado = (JRequest::getVar('fApartado')) ? "'". $modelGM->convertDateToMysql(JRequest::getVar('fApartado')) ."'" : 'NULL';
        $fInsc = (JRequest::getVar('fInsc')) ? "'". $modelGM->convertDateToMysql(JRequest::getVar('fInsc')) ."'" : 'NULL';
        $fCierre = (JRequest::getVar('fCierre')) ? "'". $modelGM->convertDateToMysql(JRequest::getVar('fCierre')) ."'" : 'NULL';
        $gte_vtas_dg = (JRequest::getVar('gte_vtas_dg')) ? JRequest::getVar('gte_vtas_dg') : 'NULL';
        $titulacion_dg = (JRequest::getVar('titulacion_dg')) ? JRequest::getVar('titulacion_dg') : 'NULL';
        $asesor_dg = (JRequest::getVar('asesor_dg')) ? JRequest::getVar('asesor_dg') : 'NULL';
        $prospectador_dg = (JRequest::getVar('prospectador_dg')) ? JRequest::getVar('prospectador_dg') : 'NULL';
        $estatus_dg = (JRequest::getVar('estatus_dg')) ? JRequest::getVar('estatus_dg') : 'NULL';
        $fEstatus = (JRequest::getVar('fEstatus')) ? $modelGM->convertDateToMysql(JRequest::getVar('fEstatus')) : $fechaEstatus;
        $motivo_cancel_dg = (JRequest::getVar('motivo_cancel_dg')) ? JRequest::getVar('motivo_cancel_dg') : 'NULL';
        $motivo_texto_dg = (JRequest::getVar('motivo_texto_dg')) ? JRequest::getVar('motivo_texto_dg') : NULL;
        $ref_dg = (JRequest::getVar('ref_dg')) ? JRequest::getVar('ref_dg') : NULL;
        $prom_dg = (JRequest::getVar('prom_dg')) ? JRequest::getVar('prom_dg') : NULL;
        $fEntrega = (JRequest::getVar('fEntrega')) ? "'". $modelGM->convertDateToMysql(JRequest::getVar('fEntrega')) ."'" : 'NULL';
        $fReprog = (JRequest::getVar('fReprog')) ? "'". $modelGM->convertDateToMysql(JRequest::getVar('fReprog')) ."'" : 'NULL';
        $fdtu = (JRequest::getVar('fdtu')) ? "'". $modelGM->convertDateToMysql(JRequest::getVar('fdtu')) ."'" : 'NULL';


        if($param==0){$historico = 0;}
        if($param==1){$historico = 1;}

        //Datos para los clientes
        $aPaternoC_dg = (JRequest::getVar('aPaternoC_dg')) ? JRequest::getVar('aPaternoC_dg') : NULL;
        $aMaternoC_dg = (JRequest::getVar('aMaternoC_dg')) ? JRequest::getVar('aMaternoC_dg') : NULL;
        $nombreC_dg = (JRequest::getVar('nombreC_dg')) ? JRequest::getVar('nombreC_dg') : NULL;
        $nssC_dg = (JRequest::getVar('nssC_dg')) ? JRequest::getVar('nssC_dg') : NULL;
        $tipoCto_dg = (JRequest::getVar('tipoCto_dg')) ? JRequest::getVar('tipoCto_dg') : 'NULL';

        $calle_cl = (JRequest::getVar('calle_cl')) ? JRequest::getVar('calle_cl') : NULL;
        $no_cl = (JRequest::getVar('no_cl')) ? JRequest::getVar('no_cl') : NULL;
        $col_cl = (JRequest::getVar('col_cl')) ? JRequest::getVar('col_cl') : NULL;
        $mpioLodad_cl = (JRequest::getVar('mpioLodad_cl')) ? JRequest::getVar('mpioLodad_cl') : NULL;
        $estado_cl = (JRequest::getVar('estado_cl')) ? JRequest::getVar('estado_cl') : 'NULL';
        $cp_cl = (JRequest::getVar('cp_cl')) ? JRequest::getVar('cp_cl') : NULL;
        $empresa_cl = (JRequest::getVar('empresa_cl')) ? JRequest::getVar('empresa_cl') : NULL;
        $fechaNac = (JRequest::getVar('fNacimiento')) ? "'". $modelGM->convertDateToMysql(JRequest::getVar('fNacimiento')) ."'" : 'NULL';
        $genero = (JRequest::getVar('generoC_dg')) ? JRequest::getVar('generoC_dg') : '0';
        $emailC_dg = (JRequest::getVar('emailC_dg')) ? JRequest::getVar('emailC_dg') : NULL;

        //Datos credito
        $numcdto_dc = (JRequest::getVar('numcdto_dc')) ? JRequest::getVar('numcdto_dc') : NULL;
        $valorv_dc = (JRequest::getVar('valorv_dc')) ? $this->limpiarFormatoMonto(JRequest::getVar('valorv_dc')) : 'NULL';
        $cInfonavit_dc = (JRequest::getVar('cInfonavit_dc')) ? $this->limpiarFormatoMonto(JRequest::getVar('cInfonavit_dc')) : 'NULL';
        $subFed_dc = (JRequest::getVar('subFed_dc')) ? $this->limpiarFormatoMonto(JRequest::getVar('subFed_dc')) : 'NULL';
        $gEscrituracion_dc = (JRequest::getVar('gEscrituracion_dc')) ? $this->limpiarFormatoMonto(JRequest::getVar('gEscrituracion_dc')) : 'NULL';
        $ahorroVol = (JRequest::getVar('ahorroVol_dc')) ? $this->limpiarFormatoMonto(JRequest::getVar('ahorroVol_dc')) : 'NULL';
        $seguros = (JRequest::getVar('seguros_dc')) ? $this->limpiarFormatoMonto(JRequest::getVar('seguros_dc')) : 'NULL';
        $seguros_resta = (JRequest::getVar('seguros_dc_resta')) ? $this->limpiarFormatoMonto(JRequest::getVar('seguros_dc_resta')) : 'NULL';

        //Datos nominas
        //comision, esPreventa, fechaPagApartado, fechaDescomicion, fechaPagEscritura, fechaPagLiquidacion,
        //esAsesor, esReferido, nombreReferido
        $ases_comision_nom = (JRequest::getVar('ases_comision_nom')) ? $this->limpiarFormatoMonto(JRequest::getVar('ases_comision_nom')) : 'NULL';
        $preventa_nom = '0';
        $fPagoApar = (JRequest::getVar('fPagoApar')) ? "'". $modelGM->convertDateToMysql(JRequest::getVar('fPagoApar')) ."'" : 'NULL';
        $fDescomision = (JRequest::getVar('fDescomision')) ? "'". $modelGM->convertDateToMysql(JRequest::getVar('fDescomision')) ."'" : 'NULL';
        $fPagoEsc = (JRequest::getVar('fPagoEsc')) ? "'". $modelGM->convertDateToMysql(JRequest::getVar('fPagoEsc')) ."'" : 'NULL';
        $fPagoLiq = (JRequest::getVar('fPagoLiq')) ? "'". $modelGM->convertDateToMysql(JRequest::getVar('fPagoLiq')) ."'" : 'NULL';
        $esAsesor = 0;

        $nombreReferido = (JRequest::getVar('nombreRef_dg')) ? JRequest::getVar('nombreRef_dg') : NULL;
        if($nombreReferido!=NULL){
            $esReferido = 1;
        }else{
            $esReferido = 0;
        }
        $pros_comision_nom = (JRequest::getVar('pros_comision_nom')) ? $this->limpiarFormatoMonto(JRequest::getVar('pros_comision_nom')) : 'NULL';
        $pros_preventa_nom = '0';
        $pros_fPagoApar_nom = (JRequest::getVar('fPagoAparPros')) ? "'". $modelGM->convertDateToMysql(JRequest::getVar('fPagoAparPros')) ."'" : 'NULL';
        $pros_fDesc_nom = (JRequest::getVar('fDescomisionPros')) ? "'". $modelGM->convertDateToMysql(JRequest::getVar('fDescomisionPros')) ."'" : 'NULL';
        $pros_fPagoEsc_nom = (JRequest::getVar('fPagoEscPros')) ? "'". $modelGM->convertDateToMysql(JRequest::getVar('fPagoEscPros')) ."'" : 'NULL';

        $idPctAsesor = JRequest::getVar('idPctAsesor');
        $idPctProspectador = JRequest::getVar('idPctProspectador');


        if($id_dato==0){
             $id = $model->insDatoGral($id_Dpt, $dtu_dg, $fApartado, $fInsc, $fCierre, $gte_vtas_dg, $titulacion_dg, $asesor_dg,
                                       $prospectador_dg, $estatus_dg, $fEstatus, $motivo_cancel_dg, $motivo_texto_dg, $ref_dg,
                                       $prom_dg, $fEntrega, $fReprog, $historico, $fdtu);
            //Insertar en tabla cliente
            if($id>0){
               $idCliente = $model->insDatoCliente($id, $aPaternoC_dg, $aMaternoC_dg, $nombreC_dg, $nssC_dg, $tipoCto_dg,
                                                   $calle_cl, $no_cl, $col_cl, $mpioLodad_cl, $estado_cl, $cp_cl, $empresa_cl, $fechaNac, $genero, $emailC_dg);

               $idCto = $model->insDatoCdto($id, $numcdto_dc, $valorv_dc, $cInfonavit_dc, $subFed_dc, $gEscrituracion_dc, $ahorroVol, $seguros, $seguros_resta);

               $idNomina = $model->insDatoNomina($id, $ases_comision_nom, $preventa_nom, $fPagoApar, $fDescomision, $fPagoEsc, $fPagoLiq, $esAsesor, $esReferido, $nombreReferido,
                                                $pros_comision_nom, $pros_preventa_nom, $pros_fPagoApar_nom, $pros_fDesc_nom, $pros_fPagoEsc_nom, $idPctAsesor, $idPctProspectador);

               echo 'Id Nomina: ' .$idNomina;

               //Salvar datos para el log de accesos
                $idLog = $modelLog->insLogAcceso($id_Usuario, $id_Dpt, $id_Fracc, $fAcceso);

            }
			$idDatoGral = $id;
        }else{
            $userLog = JFactory::getUser();
            $groupsLog = JAccess::getGroupsByUser($userLog->id, true);  //obtiene grupo/s por id de usuario logueado

            //>>Fin logica para asignar de forma permanente un departamento/casa
            $model->upDatoGral($id_Dpt, $dtu_dg, $fApartado, $fInsc, $fCierre, $gte_vtas_dg, $titulacion_dg, $asesor_dg,
                                       $prospectador_dg, $estatus_dg, $fEstatus, $motivo_cancel_dg, $motivo_texto_dg, $ref_dg,
                                       $prom_dg, $fEntrega, $fReprog, $historico, $fdtu, $id_dato);
            $idCliente = $model->upDatoCliente($aPaternoC_dg, $aMaternoC_dg, $nombreC_dg, $nssC_dg, $tipoCto_dg,
                                               $calle_cl, $no_cl, $col_cl, $mpioLodad_cl, $estado_cl, $cp_cl, $empresa_cl, $fechaNac, $genero, $emailC_dg, $id_dato);

            //Para el caso cuando el idGeneral no exista en la tabla de datos credito
            //Comprobar que exista el idDato General
            $existeDatoEnCto = $model->obtDatoGralTblCredito($id_dato);
            if($existeDatoEnCto==0){ //Entonces insertar el dato general en datos de credito
                $model->insDatoCdto($id_dato, $numcdto_dc, $valorv_dc, $cInfonavit_dc, $subFed_dc, $gEscrituracion_dc, $ahorroVol, $seguros, $seguros_resta);
            }

            $model->upDatoCdto($numcdto_dc, $valorv_dc, $cInfonavit_dc, $subFed_dc, $gEscrituracion_dc, $ahorroVol, $seguros, $seguros_resta, $id_dato);

            $model->upDatoNomina($ases_comision_nom, $preventa_nom, $fPagoApar, $fDescomision, $fPagoEsc, $fPagoLiq, $esAsesor, $esReferido, $nombreReferido,
                                 $pros_comision_nom, $pros_preventa_nom, $pros_fPagoApar_nom, $pros_fDesc_nom, $pros_fPagoEsc_nom, $idPctAsesor, $idPctProspectador, $id_dato);

			$idDatoGral = $id_dato;

            $idLog = $modelLog->insLogAcceso($id_Usuario, $id_Dpt, $id_Fracc, $fAcceso);

            //>>>Logica para asignar de forma permanente un departamento/casa al prospecto
            //comprueba que sea mesa de control
            //8=super usuario, 10=direccion, 11 = gte de ventas, 12=mesa de control, 13 = titulacion
            if(in_array("8", $groupsLog) || in_array("10", $groupsLog) || in_array("12", $groupsLog) || in_array("11", $groupsLog) || in_array("13", $groupsLog)){
                $modelProspecto = JModelLegacy::getInstance('Prospecto', 'SasfeModel');
                $datosGrales = $model->obtDatosByDpt($id_dato);
                $datoProspectoId = $datosGrales[0]->datoProspectoId;

                //Si el estutus es igual a candelado procede
                if($estatus_dg!="" && $estatus_dg==88){
                    $model->updCancelCustomer($idDatoGral);
                }

                //comprobar si tiene un prospecto y el estatus es 400 (Apartado definitivo)
                if($datoProspectoId!="" && $datoProspectoId>0 && $estatus_dg!="" && $estatus_dg==400){
                    //Asignar de forma permanete el departamento al prospecto
                    $resAsigPerm = $modelProspecto->asigarDptoPermanente($arrDateTime->fecha, $datoProspectoId);

                    //>>>Salvar que se ha asignado de forma permanente la propiedad
                    $datosDpto = $model->obtDatosDptoPorId($id_Dpt); //Obtener datos de la propiedad
                    $comentarioHist = "Se aparta definitivamente la propiedad ".$datosDpto[0]->numero." ".$datosDpto[0]->nombrefracc." el d&iacute;a ".$arrDateTime->fechaF2;
                    SasfehpHelper::salvarHistorialProspecto($datoProspectoId, 6, $comentarioHist, $arrDateTime->fechaHora);


                    //>>>Inicio de logica para notificar
                    //Obtener datos del prospecto por su id
                    $datosProspecto = $modelProspecto->obtenerDatosProspecto($datoProspectoId);
                    //Al asignar provisionalmente un departamento notificarle al gerente de ventas y mesa de control
                    $asunto = "Propiedad apartada";

                    //Verificar que existen datos del prospecto
                    if(count($datosProspecto)){
                        $idoption = $datoProspectoId;
                        $nombre = $datosProspecto[0]->nombre;
                        $aPaterno = $datosProspecto[0]->aPaterno;
                        $aManterno = $datosProspecto[0]->aManterno;
                        $RFC = $datosProspecto[0]->RFC;

                        //Verificar que existe gte ventas para notificarle
                        if($datosProspecto[0]->gteVentasId!=""){
                            $userAsesor = JFactory::getUser($datosProspecto[0]->agtVentasId);
                            $nombreAsesor = $userAsesor->name;//Nombre de asesor
                            $userMail = JFactory::getUser($datosProspecto[0]->gteVentasId);
                            $arrCorreos = array($userMail->email);
                            $body = '<div>Estimado(a) <b>'.$userMail->name.'</b> se aparto definitivamente la propiedad '.$datosDpto[0]->numero." ".$datosDpto[0]->nombrefracc.' :</div><br/>

                                        <div>ID Prospecto: '.$idoption.'</div>
                                        <div>Nombre: '.$nombre.' '.$aPaterno.' '.$aManterno.'</div>
                                        <div>RFC: '.$RFC.'</div>
                                        <div>Asesor: '.$nombreAsesor.'</div>

                                        <br/><div style="font-size:13px;">Este correo es generado autom&aacute;ticamente, favor de no responder</div><br/>
                                    ';
                            SasfehpHelper::notificarPorCorreo($asunto, $arrCorreos, $body);
                        }

                        //Verificar que existe agente ventas (asesor) para notificarle
                        if(count($datosProspecto)>0 && $datosProspecto[0]->agtVentasId!=""){
                            $userMail = JFactory::getUser($datosProspecto[0]->agtVentasId);
                            $nombreAsesor = $userMail->name;//Nombre de asesor
                            $arrCorreos = array($userMail->email);
                            $body = '<div>Estimado(a) <b>'.$userMail->name.'</b> se aparto definitivamente la propiedad '.$datosDpto[0]->numero." ".$datosDpto[0]->nombrefracc.' :</div><br/>

                                        <div>ID Prospecto: '.$idoption.'</div>
                                        <div>Nombre: '.$nombre.' '.$aPaterno.' '.$aManterno.'</div>
                                        <div>RFC: '.$RFC.'</div>
                                        <div>Asesor: '.$nombreAsesor.'</div>

                                        <br/><div style="font-size:13px;">Este correo es generado autom&aacute;ticamente, favor de no responder</div><br/>
                                    ';
                            SasfehpHelper::notificarPorCorreo($asunto, $arrCorreos, $body);
                            //>>Notificar a usuarios de direccion (id=10) al apartar definitivamente
                            $colUserDireccion = SasfehpHelper::obtInfoUsuariosJoomlaPorGrupo(10);
                            if(count($colUserDireccion)>0){
                                foreach($colUserDireccion as $elemUserDir){
                                    $arrCorreos = array($elemUserDir->email);
                                    $body = '<div>Estimado(a) <b>'.$elemUserDir->name.'</b> se aparto definitivamente la propiedad '.$datosDpto[0]->numero." ".$datosDpto[0]->nombrefracc.' :</div><br/>
                                        <div>ID Prospecto: '.$idoption.'</div>
                                        <div>Nombre: '.$nombre.' '.$aPaterno.' '.$aManterno.'</div>
                                        <div>RFC: '.$RFC.'</div>
                                        <div>Asesor: '.$nombreAsesor.'</div>
                                        <br/><div style="font-size:13px;">Este correo es generado autom&aacute;ticamente, favor de no responder</div><br/>
                                    ';
                                    SasfehpHelper::notificarPorCorreo($asunto, $arrCorreos, $body);
                                }
                            }
                        }
                    }
                    //>>>Fin de logica para notificar
                }

                //comprobar si tiene un prospecto y el estatus es 402 (Regresar Asesor), 88 (cancelado)
                if($datoProspectoId!="" && $datoProspectoId>0 && $estatus_dg!="" && ($estatus_dg==402 || $estatus_dg==88)){
                    $motivo_liberar_dpto = (JRequest::getVar('motivo_liberar_dpto')) ? JRequest::getVar('motivo_liberar_dpto') : "NULL";
                    $model->updHistReasigObsoleto($id_Dpt);
                    $resReset = $modelProspecto->resetProspectadorPorIdProspecto($datoProspectoId); //Limpiar los campos departamentoId, fechaLimiteApartado por el id del prospectador

                    //>>>Salvar el motivo de liberacion por mesa de control en el historial
                    //Obtener datos del departamento
                    $datosDpto = $model->obtDatosDptoPorId($id_Dpt); //Obtener datos de la propiedad
                    //Comprobar que grupo es
                    if(in_array("11", $groupsLog)){
                        $estatusHistId = 7; //Liberada por gerente de ventas
                    }
                    elseif(in_array("12", $groupsLog)) {
                        $estatusHistId = 5; //Liberada por mesa de control
                    }
                    elseif(in_array("13", $groupsLog)) {
                        $estatusHistId = 8; //Liberada por titulacion
                    }
                    elseif(in_array("8", $groupsLog)) {
                        $estatusHistId = 9; //Liberada por el administrador
                    }
                    elseif(in_array("10", $groupsLog)) {
                        $estatusHistId = 10; //Liberada por director
                    }
                    else{
                        $estatusHistId = 3; //Liberada manualmente
                    }
                    // $estatusHistId = (in_array("11", $groupsLog)) ?7 :5;
                    SasfehpHelper::salvarHistorialProspecto($datoProspectoId, $estatusHistId, $motivo_liberar_dpto, $arrDateTime->fechaHora);

                    //>>>Inicio de logica para notificar
                    //Obtener datos del prospecto por su id
                    $datosProspecto = $modelProspecto->obtenerDatosProspecto($datoProspectoId);
                    //Al asignar provisionalmente un departamento notificarle al gerente de ventas y mesa de control
                    $asunto = "Propiedad rechazada";

                    //Verificar que existen datos del prospecto
                    if(count($datosProspecto)){
                        $idoption = $datoProspectoId;
                        $nombre = $datosProspecto[0]->nombre;
                        $aPaterno = $datosProspecto[0]->aPaterno;
                        $aManterno = $datosProspecto[0]->aManterno;
                        $RFC = $datosProspecto[0]->RFC;

                        //Verificar que existe gte ventas para notificarle (HABILITAR EN CASO DE NOTIFICARLE AL GERENTE DE VENTAS)
                        // if($datosProspecto[0]->gteVentasId!=""){
                        //     $userMail = JFactory::getUser($datosProspecto[0]->gteVentasId);
                        //     $arrCorreos = array($userMail->email);
                        //     $body = '<div>Estimado(a) <b>'.$userMail->name.'</b> se aparto definitivamente la propiedad '.$datosDpto[0]->numero." ".$datosDpto[0]->nombrefracc.' :</div><br/>

                        //                 <div>ID Prospecto: '.$idoption.'</div>
                        //                 <div>Nombre: '.$nombre.' '.$aPaterno.' '.$aManterno.'</div>
                        //                 <div>RFC: '.$RFC.'</div>

                        //                 <br/><div style="font-size:13px;">Este correo es generado autom&aacute;ticamente, favor de no responder</div><br/>
                        //             ';
                        //     SasfehpHelper::notificarPorCorreo($asunto, $arrCorreos, $body);
                        // }

                        //Verificar que existe agente ventas (asesor) para notificarle
                        if(count($datosProspecto)>0 && $datosProspecto[0]->agtVentasId!=""){
                            $userMail = JFactory::getUser($datosProspecto[0]->agtVentasId);
                            $arrCorreos = array($userMail->email);
                            $body = '<div>Estimado(a) <b>'.$userMail->name.'</b> se rechazo la propiedad '.$datosDpto[0]->numero." ".$datosDpto[0]->nombrefracc.' :</div><br/>

                                        <div>ID Prospecto: '.$idoption.'</div>
                                        <div>Nombre: '.$nombre.' '.$aPaterno.' '.$aManterno.'</div>
                                        <div>RFC: '.$RFC.'</div>
                                        <div>Motivo: '.$motivo_liberar_dpto.'</div>

                                        <br/><div style="font-size:13px;">Este correo es generado autom&aacute;ticamente, favor de no responder</div><br/>
                                    ';
                            SasfehpHelper::notificarPorCorreo($asunto, $arrCorreos, $body);
                            //Aplica si cancelo el gerente de ventas
                            if((in_array("11", $groupsLog))){
                                $userAsesor = JFactory::getUser($datosProspecto[0]->agtVentasId);
                                $nombreAsesor = $userAsesor->name;//Nombre de asesor
                                $bodyEmail = '<div>Se rechazo la propiedad '.$datosDpto[0]->numero." ".$datosDpto[0]->nombrefracc.' :</div><br/>
                                            <div>ID Prospecto: '.$idoption.'</div>
                                            <div>Nombre: '.$nombre.' '.$aPaterno.' '.$aManterno.'</div>
                                            <div>RFC: '.$RFC.'</div>
                                            <div>Asesor: '.$nombreAsesor.'</div>
                                            <div>Motivo: '.$motivo_liberar_dpto.'</div>
                                            <br/><div style="font-size:13px;">Este correo es generado autom&aacute;ticamente, favor de no responder</div><br/>
                                        ';
                                //>>Notificar a usuarios de mesa de control (id=12)
                                $colUserMC = SasfehpHelper::obtInfoUsuariosJoomlaPorGrupo(12);
                                if(count($colUserMC)>0){
                                    $arrCorreosMC = array();
                                    foreach($colUserMC as $elemUser){
                                        $arrCorreosMC[] = $elemUser->email;
                                    }
                                    SasfehpHelper::notificarPorCorreo($asunto, $arrCorreosMC, $bodyEmail);
                                }
                                //>>Notificar a usuarios de titulacion (id=13)
                                $colUserTI = SasfehpHelper::obtInfoUsuariosJoomlaPorGrupo(13);
                                if(count($colUserTI)>0){
                                    $arrCorreosTI = array();
                                    foreach($colUserTI as $elemUser){
                                        $arrCorreosTI[] = $elemUser->email;
                                    }
                                    SasfehpHelper::notificarPorCorreo($asunto, $arrCorreosTI, $bodyEmail);
                                }
                                //>>Notificar a usuarios de nominas (id=14)
                                $colUserNO = SasfehpHelper::obtInfoUsuariosJoomlaPorGrupo(14);
                                if(count($colUserNO)>0){
                                    $arrCorreosNO = array();
                                    foreach($colUserNO as $elemUser){
                                        $arrCorreosNO[] = $elemUser->email;
                                    }
                                    SasfehpHelper::notificarPorCorreo($asunto, $arrCorreosNO, $bodyEmail);
                                }
                                //>>Notificar a usuarios de direccion (id=10)
                                $colUserDI = SasfehpHelper::obtInfoUsuariosJoomlaPorGrupo(10);
                                if(count($colUserDI)>0){
                                    $arrCorreosDI = array();
                                    foreach($colUserDI as $elemUser){
                                        $arrCorreosDI[] = $elemUser->email;
                                    }
                                    SasfehpHelper::notificarPorCorreo($asunto, $arrCorreosDI, $bodyEmail);
                                }
                            }
                        }
                    }
                    //>>>Fin de logica para notificar
                }
                //>>>>
                //>>10/07/2018
                //>>Modulo envio de SMS
                //>>>>
                // echo "fEntrega: ".$fEntrega.'<br/>';
                // echo "fCierre: ".$fCierre.'<br/>';
                // echo "estatus: ".$estatus_dg.'<br/>';
                    //Saber si esta habilitado el envio de los mensajes automaticos
                    $datosCreditoAut = SasfehpHelper::obtInfoCreditosBolsaAutomatico(2);
                    $activoAutomatico = (isset($datosCreditoAut->activo))?$datosCreditoAut->activo:0;
                    //Obtener el id del cliente
                    $datosCliente = SasfehpHelper::obtDatosClientePorIdGral($id_dato);
                    $datoClienteId = 'NULL';
                    if(isset($datosCliente->idDatoCliente)){
                        $datoClienteId = $datosCliente->idDatoCliente;
                    }
                $telAgencia = "2.30.39.49";
                    // if($datoProspectoId!="" && $datoProspectoId>0 && $estatus_dg!=""){
                    if($estatus_dg!="" && $activoAutomatico>0){
                        $dateByZone = new DateTime("now", new DateTimeZone('America/Mexico_City') );
                        $fechaHora = $dateByZone->format('Y-m-d H:i:s'); //fecha y hora
                        $fechaHora2 = $dateByZone->format('d/m/Y H:i:s'); //fecha y hora formato 2
                        $userSave = JFactory::getUser();
                        $groups = JAccess::getGroupsByUser($userSave->id, true);
                        $usuarioIdHist = $userSave->id;
                        $nombreUsuarioHist = "";
                        if(isset($userSave->name)){
                            $nombreUsuarioHist = $userSave->name;
                        }
                        $grupoUsuarioId = end($groups);
                        $clienteHist = $nombreC_dg." ".$aPaternoC_dg." ".$aMaternoC_dg;
                    $celularObt = SasfehpHelper::obtCelularCliente($id_dato);
                        $colDatosGral = SasfehpHelper::obtDatoGralPorIdSMS($idDatoGral);
                        $mensaje = "";
                        $enviadoSMS = 1;
                        $porActSMS = "";
                        $estatusHist = "";
                    if($celularObt!=""){
                        //obtener el mensaje
                            switch ($estatus_dg) {
                                //Apartado definitivo
                                case 400:
                        $mensaje = SasfehpHelper::obtMensajeSMSPorId(1);
                                    //Saber si ya se le envio
                                    $enviadoSMS = $colDatosGral->envioApD;
                                    $porActSMS = "envioApD";
                                    $estatusHist = "Apartado definitivo";
                                break;
                                //Aviso de retencion
                                case 94:
                                    $mensaje = SasfehpHelper::obtMensajeSMSPorId(2);
                                    $enviadoSMS = $colDatosGral->envioAvR;
                                    $porActSMS = "envioAvR";
                                    $estatusHist = "Aviso de retenci&oacute;n";
                                break;
                                //Escriturado
                                case 87:
                                    $mensaje = SasfehpHelper::obtMensajeSMSPorId(3);
                                    $enviadoSMS = $colDatosGral->envioEsc;
                                    $porActSMS = "envioEsc";
                                    $estatusHist = "Escriturado";
                                break;
                            }

                            //Para el envio de los 3 primeros estatus
                            if($mensaje!=""){
                        $resBuscar = strpos($mensaje, "{aPaternoCliente}");
                        if($resBuscar==true){
                           $mensaje = SasfehpHelper::reemplazarPlaceholderSMS($mensaje, "{aPaternoCliente}", $aPaternoC_dg);
                        }
                        $resBuscar = strpos($mensaje, "{telAgencia}");
                        if($resBuscar==true){
                           $mensaje = SasfehpHelper::reemplazarPlaceholderSMS($mensaje, "{telAgencia}", $telAgencia);
                        }
                                //Comprueba si es posible enviar el mensaje
                                if($enviadoSMS==0){
                                $resSMS = SasfehpHelper::enviarSMS($mensaje, $celularObt);
                        // echo $mensaje.' -1 <br/>';
                        // echo $resSMS;
                                // echo $celularObt.' <br/>';
                                    if($resSMS==true){
                                        $resActSMS = SasfehpHelper::actEnviosSMSDatoGralPorId($idDatoGral, $porActSMS, 1);
                                        $resRestar = SasfehpHelper::restarCreditoAutomaticosSMS(1, $fechaHora); //Restar el credito
                                        //>>>Salvar historial del mensaje en la tabla de envios
                                        $comentario = "Se env&iacute;o mensaje SMS autom&aacute;tico con el usuario ".$nombreUsuarioHist." al cliente ".$clienteHist." con el estatus ".$estatusHist." el d&iacute;a ".$fechaHora2;
                                        $mensajeHistId = SasfehpHelper::salvarHistorialSMS(3, $grupoUsuarioId, $usuarioIdHist, $mensaje, $comentario, $fechaHora);
                                        //Agregar Historial por cada cliente que se le envio el mensaje
                                        if($mensajeHistId>0){
                                            $agtventas = $asesor_dg;
                                            SasfehpHelper::salvarHistorialClientesSMS($usuarioIdHist, $agtventas, $datoClienteId, 3, $mensajeHistId, $fechaHora);
                                        }
                                        //Enviar correo
                                        $arrCorreos = array(strtolower(trim($emailC_dg)));
                                        $body = '<div>'.$mensaje.'</div>';
                                        $body .= '<br/><div style="font-size:13px;">Este correo es generado autom&aacute;ticamente, favor de no responder</div><br/>';
                                        SasfehpHelper::notificarPorCorreo("Cambio de estatus", $arrCorreos, $body);
                    }
                                    // //Enviar correo
                                    // $arrCorreos = array(strtolower(trim($emailC_dg)));
                                    // $body = '<div>'.$mensaje.'</div>';
                                    // $body .= '<br/><div style="font-size:13px;">Este correo es generado autom&aacute;ticamente, favor de no responder</div><br/>';
                                    // SasfehpHelper::notificarPorCorreo("Cambio de estatus", $arrCorreos, $body);
                                }
                            }
                            //Para el envio del estrega de vivienda
                            $mensaje = "";
                            $enviadoSMS = 1;
                            $estatusHist = "Fecha de entrega";
                            //Fecha de entrega
                            if($fEntrega!="" && $fEntrega!="NULL"){
                                $mensaje = SasfehpHelper::obtMensajeSMSPorId(4);
                                $enviadoSMS = $colDatosGral->envioFEn;
                                if($mensaje!=""){
                                    $resBuscar = strpos($mensaje, "{aPaternoCliente}");
                                    if($resBuscar==true){
                                       $mensaje = SasfehpHelper::reemplazarPlaceholderSMS($mensaje, "{aPaternoCliente}", $aPaternoC_dg);
                                    }
                                    $resBuscar = strpos($mensaje, "{telAgencia}");
                                    if($resBuscar==true){
                                       $mensaje = SasfehpHelper::reemplazarPlaceholderSMS($mensaje, "{telAgencia}", $telAgencia);
                                    }
                                    //Comprueba si es posible enviar el mensaje
                                    if($enviadoSMS==0){
                                        $resSMS = SasfehpHelper::enviarSMS($mensaje, $celularObt);
                                        // echo $mensaje.' -1 <br/>';
                                        // echo $resSMS;
                                        // echo $celularObt.' <br/>';
                                        if($resSMS==true){
                                            $resActSMS = SasfehpHelper::actEnviosSMSDatoGralPorId($idDatoGral, "envioFEn", 1);
                                            $resRestar = SasfehpHelper::restarCreditoAutomaticosSMS(1, $fechaHora); //Restar el credito
                                            //>>>Salvar historial del mensaje en la tabla de envios
                                            $comentario = "Se env&iacute;o mensaje SMS autom&aacute;tico con el usuario ".$nombreUsuarioHist." al cliente ".$clienteHist." con el estatus ".$estatusHist." el d&iacute;a ".$fechaHora2;
                                            $mensajeHistId = SasfehpHelper::salvarHistorialSMS(3, $grupoUsuarioId, $usuarioIdHist, $mensaje, $comentario, $fechaHora);
                                            //Agregar Historial por cada cliente que se le envio el mensaje
                                            if($mensajeHistId>0){
                                                $agtventas = $asesor_dg;
                                                SasfehpHelper::salvarHistorialClientesSMS($usuarioIdHist, $agtventas, $datoClienteId, 3, $mensajeHistId, $fechaHora);
                                            }
                                            //Enviar correo
                                            $arrCorreos = array(strtolower(trim($emailC_dg)));
                                            $body = '<div>'.$mensaje.'</div>';
                                            $body .= '<br/><div style="font-size:13px;">Este correo es generado autom&aacute;ticamente, favor de no responder</div><br/>';
                                            SasfehpHelper::notificarPorCorreo("Cambio de estatus", $arrCorreos, $body);
                                        }
                                        // //Enviar correo
                                        // $arrCorreos = array(strtolower(trim($emailC_dg)));
                                        // $body = '<div>'.$mensaje.'</div>';
                                        // $body .= '<br/><div style="font-size:13px;">Este correo es generado autom&aacute;ticamente, favor de no responder</div><br/>';
                                        // SasfehpHelper::notificarPorCorreo("Cambio de estatus", $arrCorreos, $body);
                                    }
                                }
                            }
                            // echo "mensaje: ".$mensaje.'<br/>';
                }
                    }

            }
        }
        // exit();

        if($param==0){
            $msg = JText::sprintf('Registro salvado correctamente.');
            $jinput = JFactory::getApplication()->input;
            $task = $jinput->get('task');

            switch ($task) {
                case "apply":
                    $this->setRedirect( 'index.php?option=com_sasfe&view=departamento&depto_id='.$id_Dpt.'&idFracc='.$id_Fracc.'&idDatoGral='.$idDatoGral.' ', $msg);
                    break;
                case "save":
                    $this->setRedirect( 'index.php?option=com_sasfe&view=listadodeptos&idFracc='.$id_Fracc,$msg);
                    break;
            }
        }

        if($param==1){
            //index.php?option=com_sasfe&view=listadodeptos&idFracc=1
            $this->setRedirect( 'index.php?option=com_sasfe&view=listadodeptos&idFracc='.$id_Fracc.' ', $msg);
        }

    }
    function limpiarFormatoMonto($monto){
        $resMonto = str_replace(",", "", $monto);
        $resMonto = str_replace("$", "", $resMonto);
        return $resMonto;
    }


    function totalDepositos(){
        $idDatoCto = $_POST['idDatoCredito'];
        $model = JModelLegacy::getInstance('Globalmodelsbk', 'SasfeModel');
        $sumaDepositos = $model->getSumaDepositosPorIdCto($idDatoCto);

        $html .= '<response>';
            $html .= $sumaDepositos;
        $html .= '</response>';
        echo $html;
    }

    function totalPagares(){
        $idDatoCto = $_POST['idDatoCredito'];
        $model = JModelLegacy::getInstance('Globalmodelsbk', 'SasfeModel');
        $sumaPagares = $model->getSumaPagaresPorIdCto($idDatoCto);

        $html .= '<response>';
            $html .= $sumaPagares;
        $html .= '</response>';
        echo $html;
    }

    function totalAcabados(){
        $idDatoGral = $_POST['idDatoGral'];
        $model = JModelLegacy::getInstance('Globalmodelsbk', 'SasfeModel');
        $sumaAcabados = $model->sumaTotalAcabadoDB($idDatoGral);

        $html .= '<response>';
            $html .= $sumaAcabados;
        $html .= '</response>';
        echo $html;
    }

    function totalServicios(){
        $idDatoGral = $_POST['idDatoGral'];
        $model = JModelLegacy::getInstance('Globalmodelsbk', 'SasfeModel');
        $sumaServicios = $model->sumaTotalServiciosDB($idDatoGral);

        $html .= '<response>';
            $html .= $sumaServicios;
        $html .= '</response>';
        echo $html;
    }

    function cambioEstatusDpt(){
        $idEstatus=  $_POST['idEstatus'];
        $id_Dpt=  $_POST['id_dpt'];
        $id_gral=  $_POST['id_gral'];
        $model = JModelLegacy::getInstance('Globalmodelsbk', 'SasfeModel');
        $cambioEst = $model->cambioEstatusDptDB($idEstatus, $id_Dpt, $id_gral);
        //$cambioEst = 1;

        $html .= '<response>';
            $html .= $cambioEst;
        $html .= '</response>';
        echo $html;
    }

    //Metodo para crear un registro en la tabla datos_generales
    //Estatus = apartar el dpto por 7 dias
    //si mesa de control no cambia nada se regresa a su estado disponible y queda como historial
    public function apartarDptoCasa(){
      $model = JModelLegacy::getInstance('Prospecto', 'SasfeModel');
      $idDatoProspecto = (JRequest::getVar('asigcasa_idPros')!="") ?JRequest::getVar('asigcasa_idPros') :"";
      $opcRedireccion = JRequest::getVar('opcRedireccion');

      //Verificar que existe un id de prospecto
      if($idDatoProspecto!=""){
        $this->salvarDptoCasaDesdeProspectador(0);
      }else{
        $msg = JText::sprintf('No fue posible apartar el departamento/casa.');
        if($opcRedireccion!="" && $opcRedireccion==3){
            $this->setRedirect('index.php?option=com_sasfe&view=prospecto&layout=edit&id='.$idDatoProspecto.'&opc=3',$msg);
        }
        //Redireccionar al layout solo lectura
        elseif($opcDatosProsp!="" && $opcDatosProsp==1){
            $this->setRedirect('index.php?option=com_sasfe&view=prospecto&layout=sololectura&id='.$idDatoProspecto.'&opc=1',$msg);
        }
        else{
            $this->setRedirect('index.php?option=com_sasfe&view=prospecto&layout=edit&id='.$idDatoProspecto,$msg);
        }
      }
    }

    //obtener valores del formulario desde el prospectador
    public function salvarDptoCasaDesdeProspectador($param){
        jimport('joomla.filesystem.file');
        $model = JModelLegacy::getInstance('Departamento', 'SasfeModel');
        $modelGM = JModelLegacy::getInstance('globalmodelsbk', 'SasfeModel');
        $modelLog = JModelLegacy::getInstance('logaccesos', 'SasfeModel');
        $fechaEstatus = date("Y-m-d");
        $jinput = JFactory::getApplication()->input;
        $app = JFactory::getApplication();

        //Setear datos para despues tomar el post
        $arrPost = $_POST;
        if(count($arrPost)>0){
            foreach($arrPost as $keyPost=>$elemPost){
                $jinput->set($keyPost, $elemPost);
            }

            // echo "<pre>";
            // print_r($_POST);
            // echo "</pre>";

            //parametros para el log de acceso
            $datoProspectoId = JRequest::getVar('datoProspectoId'); //Identificador del prospecto
            $id_Usuario = JRequest::getVar('id_Usuario');
            $fAcceso = JRequest::getVar('fAcceso');

            $id_Fracc = JRequest::getVar('id_Fracc');
            $id_dato = JRequest::getVar('id_DatoGral');
            $id_Dpt = JRequest::getVar('id_Dpt');
            $dtu_dg = JRequest::getVar('dtu_dg');
            $fApartado = (JRequest::getVar('fApartado')) ? "'". $modelGM->convertDateToMysql(JRequest::getVar('fApartado')) ."'" : 'NULL';
            $fInsc = (JRequest::getVar('fInsc')) ? "'". $modelGM->convertDateToMysql(JRequest::getVar('fInsc')) ."'" : 'NULL';
            $fCierre = (JRequest::getVar('fCierre')) ? "'". $modelGM->convertDateToMysql(JRequest::getVar('fCierre')) ."'" : 'NULL';
            $gte_vtas_dg = (JRequest::getVar('gte_vtas_dg')) ? JRequest::getVar('gte_vtas_dg') : 'NULL';
            $titulacion_dg = (JRequest::getVar('titulacion_dg')) ? JRequest::getVar('titulacion_dg') : 'NULL';
            $asesor_dg = (JRequest::getVar('asesor_dg')) ? JRequest::getVar('asesor_dg') : 'NULL';
            $prospectador_dg = (JRequest::getVar('prospectador_dg')) ? JRequest::getVar('prospectador_dg') : 'NULL';
            $estatus_dg = (JRequest::getVar('estatus_dg')) ? JRequest::getVar('estatus_dg') : 'NULL';
            $fEstatus = (JRequest::getVar('fEstatus')) ? $modelGM->convertDateToMysql(JRequest::getVar('fEstatus')) : $fechaEstatus;
            $motivo_cancel_dg = (JRequest::getVar('motivo_cancel_dg')) ? JRequest::getVar('motivo_cancel_dg') : 'NULL';
            $motivo_texto_dg = (JRequest::getVar('motivo_texto_dg')) ? JRequest::getVar('motivo_texto_dg') : NULL;
            $ref_dg = (JRequest::getVar('ref_dg')) ? JRequest::getVar('ref_dg') : NULL;
            $prom_dg = (JRequest::getVar('prom_dg')) ? JRequest::getVar('prom_dg') : NULL;
            $fEntrega = (JRequest::getVar('fEntrega')) ? "'". $modelGM->convertDateToMysql(JRequest::getVar('fEntrega')) ."'" : 'NULL';
            $fReprog = (JRequest::getVar('fReprog')) ? "'". $modelGM->convertDateToMysql(JRequest::getVar('fReprog')) ."'" : 'NULL';
            $fdtu = (JRequest::getVar('fdtu')) ? "'". $modelGM->convertDateToMysql(JRequest::getVar('fdtu')) ."'" : 'NULL';


            if($param==0){$historico = 0;}
            if($param==1){$historico = 1;}

            //Datos para los clientes
            $aPaternoC_dg = (JRequest::getVar('aPaternoC_dg')) ? JRequest::getVar('aPaternoC_dg') : NULL;
            $aMaternoC_dg = (JRequest::getVar('aMaternoC_dg')) ? JRequest::getVar('aMaternoC_dg') : NULL;
            $nombreC_dg = (JRequest::getVar('nombreC_dg')) ? JRequest::getVar('nombreC_dg') : NULL;
            $nssC_dg = (JRequest::getVar('nssC_dg')) ? JRequest::getVar('nssC_dg') : NULL;
            $tipoCto_dg = (JRequest::getVar('tipoCto_dg')) ? JRequest::getVar('tipoCto_dg') : 'NULL';

            $calle_cl = (JRequest::getVar('calle_cl')) ? JRequest::getVar('calle_cl') : NULL;
            $no_cl = (JRequest::getVar('no_cl')) ? JRequest::getVar('no_cl') : NULL;
            $col_cl = (JRequest::getVar('col_cl')) ? JRequest::getVar('col_cl') : NULL;
            $mpioLodad_cl = (JRequest::getVar('mpioLodad_cl')) ? JRequest::getVar('mpioLodad_cl') : NULL;
            $estado_cl = (JRequest::getVar('estado_cl')) ? JRequest::getVar('estado_cl') : 'NULL';
            $cp_cl = (JRequest::getVar('cp_cl')) ? JRequest::getVar('cp_cl') : NULL;
            $empresa_cl = (JRequest::getVar('empresa_cl')) ? JRequest::getVar('empresa_cl') : NULL;
            $fechaNac = (JRequest::getVar('fNacimiento')) ? "'". $modelGM->convertDateToMysql(JRequest::getVar('fNacimiento')) ."'" : 'NULL';
            $genero = (JRequest::getVar('generoC_dg')) ? JRequest::getVar('generoC_dg') : '0';
            $emailC_dg = (JRequest::getVar('emailC_dg')) ? JRequest::getVar('emailC_dg') : NULL;

            //Datos credito
            $numcdto_dc = (JRequest::getVar('numcdto_dc')) ? JRequest::getVar('numcdto_dc') : NULL;
            $valorv_dc = (JRequest::getVar('valorv_dc')) ? JRequest::getVar('valorv_dc') : 'NULL';
            $cInfonavit_dc = (JRequest::getVar('cInfonavit_dc')) ? JRequest::getVar('cInfonavit_dc') : 'NULL';
            $subFed_dc = (JRequest::getVar('subFed_dc')) ? JRequest::getVar('subFed_dc') : 'NULL';
            $gEscrituracion_dc = (JRequest::getVar('gEscrituracion_dc')) ? JRequest::getVar('gEscrituracion_dc') : 'NULL';
            $ahorroVol = (JRequest::getVar('ahorroVol_dc')) ? JRequest::getVar('ahorroVol_dc') : 'NULL';
            $seguros = (JRequest::getVar('seguros_dc')) ? JRequest::getVar('seguros_dc') : 'NULL';
            $seguros_resta = (JRequest::getVar('seguros_dc_resta')) ? JRequest::getVar('seguros_dc_resta') : 'NULL';

            //Datos nominas
            //comision, esPreventa, fechaPagApartado, fechaDescomicion, fechaPagEscritura, fechaPagLiquidacion,
            //esAsesor, esReferido, nombreReferido
            $ases_comision_nom = (JRequest::getVar('ases_comision_nom')) ? JRequest::getVar('ases_comision_nom') : 'NULL';
            $preventa_nom = '0';
            $fPagoApar = (JRequest::getVar('fPagoApar')) ? "'". $modelGM->convertDateToMysql(JRequest::getVar('fPagoApar')) ."'" : 'NULL';
            $fDescomision = (JRequest::getVar('fDescomision')) ? "'". $modelGM->convertDateToMysql(JRequest::getVar('fDescomision')) ."'" : 'NULL';
            $fPagoEsc = (JRequest::getVar('fPagoEsc')) ? "'". $modelGM->convertDateToMysql(JRequest::getVar('fPagoEsc')) ."'" : 'NULL';
            $fPagoLiq = (JRequest::getVar('fPagoLiq')) ? "'". $modelGM->convertDateToMysql(JRequest::getVar('fPagoLiq')) ."'" : 'NULL';
            $esAsesor = 0;

            $nombreReferido = (JRequest::getVar('nombreRef_dg')) ? JRequest::getVar('nombreRef_dg') : NULL;
            if($nombreReferido!=NULL){
                $esReferido = 1;
            }else{
                $esReferido = 0;
            }
            $pros_comision_nom = (JRequest::getVar('pros_comision_nom')) ? JRequest::getVar('pros_comision_nom') : 'NULL';
            $pros_preventa_nom = '0';
            $pros_fPagoApar_nom = (JRequest::getVar('fPagoAparPros')) ? "'". $modelGM->convertDateToMysql(JRequest::getVar('fPagoAparPros')) ."'" : 'NULL';
            $pros_fDesc_nom = (JRequest::getVar('fDescomisionPros')) ? "'". $modelGM->convertDateToMysql(JRequest::getVar('fDescomisionPros')) ."'" : 'NULL';
            $pros_fPagoEsc_nom = (JRequest::getVar('fPagoEscPros')) ? "'". $modelGM->convertDateToMysql(JRequest::getVar('fPagoEscPros')) ."'" : 'NULL';

            //Por defecto se setean en 1
            $idPctAsesor = 1; //JRequest::getVar('idPctAsesor');
            $idPctProspectador = 1; //JRequest::getVar('idPctProspectador');


            if($id_dato==0){
                 $id = $model->insDatoGral($id_Dpt, $dtu_dg, $fApartado, $fInsc, $fCierre, $gte_vtas_dg, $titulacion_dg, $asesor_dg,
                                           $prospectador_dg, $estatus_dg, $fEstatus, $motivo_cancel_dg, $motivo_texto_dg, $ref_dg,
                                           $prom_dg, $fEntrega, $fReprog, $historico, $fdtu);
                //Insertar en tabla cliente
                if($id>0){
                   //Salvar el id del prospecto (solo es actualizar por el id del dato general)
                   $model->updIdDatoProspecto($id, $datoProspectoId);

                   $idCliente = $model->insDatoCliente($id, $aPaternoC_dg, $aMaternoC_dg, $nombreC_dg, $nssC_dg, $tipoCto_dg,
                                                       $calle_cl, $no_cl, $col_cl, $mpioLodad_cl, $estado_cl, $cp_cl, $empresa_cl, $fechaNac, $genero, $emailC_dg);

                   $idCto = $model->insDatoCdto($id, $numcdto_dc, $valorv_dc, $cInfonavit_dc, $subFed_dc, $gEscrituracion_dc, $ahorroVol, $seguros, $seguros_resta);

                   $idNomina = $model->insDatoNomina($id, $ases_comision_nom, $preventa_nom, $fPagoApar, $fDescomision, $fPagoEsc, $fPagoLiq, $esAsesor, $esReferido, $nombreReferido,
                                                    $pros_comision_nom, $pros_preventa_nom, $pros_fPagoApar_nom, $pros_fDesc_nom, $pros_fPagoEsc_nom, $idPctAsesor, $idPctProspectador);

                   // echo 'Id Nomina: ' .$idNomina;

                   //Salvar datos para el log de accesos
                   // $idLog = $modelLog->insLogAcceso($id_Usuario, $id_Dpt, $id_Fracc, $fAcceso);

                   //Salvar telefonos
                   if($idCliente>0){
                       //Tipo 1 = casa, Tipo 2 = celular
                       if(JRequest::getVar('telefono')!=""){
                            $objTel = array((object)array('numero'=>JRequest::getVar('telefono'), 'tipoId'=>1));
                            $model->insTelefonos($idCliente, $objTel);
                       }
                       if(JRequest::getVar('celular')!=""){
                            $objCel = array((object)array('numero'=>JRequest::getVar('celular'), 'tipoId'=>2));
                            $model->insTelefonos($idCliente, $objCel);
                       }
                   }

                        require_once(JPATH_COMPONENT.'/helpers/sasfehp.php');
                        $modelProspecto = JModelLegacy::getInstance('Prospecto', 'SasfeModel');
                        $arrDateTime = SasfehpHelper::obtDateTimeZone();
                        //Salvar el historial del prospecto
                        $datosDpto = $model->obtDatosDptoPorId($id_Dpt); //Obtener datos de la propiedad
                        //obtener nombre del usuario que aparto la casa
                        $usrJoomlaDatos = SasfehpHelper::obtInfoUsuariosJoomla($id_Usuario);
                        $comentarioHist = "El usuario ".$usrJoomlaDatos->name." aparto la propiedad ".$datosDpto[0]->numero." ".$datosDpto[0]->nombrefracc." el d&iacute;a ".$arrDateTime->fechaF2;//. " para el prospecto ".$datosProspecto[0]->nombre." ".$datosProspecto[0]->aPaterno." ".$datosProspecto[0]->aManterno;
                        SasfehpHelper::salvarHistorialProspecto($datoProspectoId, 1, $comentarioHist, $arrDateTime->fechaHora);

                        //>>>Inicio de logica para notificar
                        //Obtener datos del prospecto por su id
                        $datosProspecto = $modelProspecto->obtenerDatosProspecto($datoProspectoId);
                        //Al asignar provisionalmente un departamento notificarle al gerente de ventas y mesa de control
                        $asunto = "Propiedad asignada provisionalmente";

                        //Verificar que existe gte ventas para notificarle
                        if(count($datosProspecto)>0 && $datosProspecto[0]->gteVentasId!=""){
                            $idGte = $datosProspecto[0]->gteVentasId;
                            $idoption = $datoProspectoId;
                            $nombre = $datosProspecto[0]->nombre;
                            $aPaterno = $datosProspecto[0]->aPaterno;
                            $aManterno = $datosProspecto[0]->aManterno;
                            $RFC = $datosProspecto[0]->RFC;

                            //Notificar al gerente de ventas
                            $userMail = JFactory::getUser($idGte);
                            $arrCorreos = array($userMail->email);
                            $body = '<div>Estimado(a) <b>'.$userMail->name.'</b> se aparto provisionalmente la propiedad '.$datosDpto[0]->numero." ".$datosDpto[0]->nombrefracc.' :</div><br/>

                                        <div>ID Prospecto: '.$idoption.'</div>
                                        <div>Nombre: '.$nombre.' '.$aPaterno.' '.$aManterno.'</div>
                                        <div>RFC: '.$RFC.'</div>
                                        <div>Fecha de apartado: '.$arrDateTime->fechaHoraF2.'</div>

                                        <br/><div style="font-size:13px;">Este correo es generado autom&aacute;ticamente, favor de no responder</div><br/>
                                    ';
                            SasfehpHelper::notificarPorCorreo($asunto, $arrCorreos, $body);

                            //>>Notificar a usuario de mesa de control
                            //obtener todos los usuarios de mesa de control (id=12)
                            $colUserMC = SasfehpHelper::obtInfoUsuariosJoomlaPorGrupo(12);
                            if(count($colUserMC)>0){
                                $arrCorreosMC = array();
                                foreach($colUserMC as $elemUser){
                                    $arrCorreosMC[] = $elemUser->email;
                                }
                                $bodyMC = '<div>Se aparto provisionalmente la propiedad '.$datosDpto[0]->numero." ".$datosDpto[0]->nombrefracc.' :</div><br/>

                                            <div>ID Prospecto: '.$idoption.'</div>
                                            <div>Nombre: '.$nombre.' '.$aPaterno.' '.$aManterno.'</div>
                                            <div>RFC: '.$RFC.'</div>
                                            <div>Fecha de apartado: '.$arrDateTime->fechaHoraF2.'</div>

                                            <br/><div style="font-size:13px;">Este correo es generado autom&aacute;ticamente, favor de no responder</div><br/>
                                        ';
                                SasfehpHelper::notificarPorCorreo($asunto, $arrCorreosMC, $bodyMC);
                            }
                        }
                        //>>>Fin de logica para notificar
                }
                $idDatoGral = $id;
            }

            $opcRedireccion = JRequest::getVar('opcRedireccion');
            $msg = JText::sprintf('Se aparto el departamento/casa correctamente.');

            if($opcRedireccion!="" && $opcRedireccion==3){
                $url = JRoute::_('index.php?option=com_sasfe&view=prospecto&layout=edit&id='.$datoProspectoId.'&opc=3', false);
            }
            //Redireccionar al layout solo lectura
            elseif($opcRedireccion!="" && $opcRedireccion==1){
                $url = JRoute::_('index.php?option=com_sasfe&view=prospecto&layout=sololectura&id='.$datoProspectoId.'&opc=1', false);
            }
            else{
                $url = JRoute::_('index.php?option=com_sasfe&view=prospecto&layout=edit&id='.$datoProspectoId, false);
            }
            $app->enqueueMessage($msg,'message');
            $app->redirect($url);
            // $this->setRedirect( 'index.php?option=com_sasfe&view=prospectos', $msg);
            // $this->setRedirect( 'index.php?option=com_sasfe&view=prospecto&layout=edit&id='.$datoProspectoId, $msg);
        }else{
            $opcRedireccion = JRequest::getVar('opcRedireccion');
            $msg = JText::sprintf('No fue posible apartar el departamento/casa correctamente.');

            if($opcRedireccion!="" && $opcRedireccion==3){
                $url = JRoute::_('index.php?option=com_sasfe&view=listareventos&opc=0', false);
            }else{
                $url = JRoute::_('index.php?option=com_sasfe&view=prospectos', false);
            }
            $app->enqueueMessage($msg,'message');
            $app->redirect($url);
            // $this->setRedirect( 'index.php?option=com_sasfe&view=prospectos', $msg);
        }
    }


}

?>
