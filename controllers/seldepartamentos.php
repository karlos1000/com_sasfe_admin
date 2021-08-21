<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted Access');
jimport('joomla.application.component.controllerform');

class SasfeControllerSeldepartamentos extends JControllerForm {
    
    function cancel()
    {        
        $depto_id = JRequest::getVar('depto_id');            
        $idFracc = JRequest::getVar('idFracc');            
        $idDatoGral = JRequest::getVar('idDatoGral');            
        
        $this->setRedirect('index.php?option=com_sasfe&view=departamento&depto_id='.$depto_id.'&idFracc='.$idFracc.'&idDatoGral='.$idDatoGral );                
    }
        
    function reasignar(){
        jimport('joomla.filesystem.file');       
        JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));        
        $model = JModelLegacy::getInstance('Seldepartamentos', 'SasfeModel');   
        $modelDP = JModelLegacy::getInstance('Departamento', 'SasfeModel');           
        $fechaEstatus = date("Y-m-d");  
        
        $depto_id = JRequest::getVar('depto_id');            
        $idFracc = JRequest::getVar('idFracc');            
        $idDatoGral = JRequest::getVar('idDatoGral');   
                
        //datos del nuevo departamento
        $idDptoSel = JRequest::getVar('dptoNew');                    
        $modelDP->updHistReasigObsoleto($idDptoSel); //activar su historico, reasigancion, obsoleto en 1 = mandar a basura los datos anteriores
                
        //$idDatoGralNew = JRequest::getVar('idDatoGralNew');                            
        
        echo '$depto_id: ' .$depto_id .'<br/>';
        echo '$idFracc: ' .$idFracc .'<br/>';
        echo '$idDatoGral: ' .$idDatoGral .'<br/>';
        echo '$idDptoSel: ' .$idDptoSel .'<br/>';
                
/**
 * Obtener el idDatoGeneral por el id del departamento
 */                   
        //setear en 1 en esHistorico al $depto_id
        $model->historicoPrendido($depto_id, $idDatoGral); 
        //setear que fue reasignado
        $model->reasignadoPrendido($depto_id, $idDatoGral);                 
        $model->obsoletoPrendido($idDatoGral); //Poner en obsoleto los datos anteriores        
               
         
        $datosGrales = $modelDP->obtDatosByDpt($idDatoGral); //obtener todos los datos generales 
        $datosCliente = $modelDP->obtDatosClientePorIdDatoGral($idDatoGral); //obtener datos cliente
        $datosCto = $modelDP->obtDatosCdtoPorIdDatoGral($idDatoGral); //obtener datos credito
        $datosNominas = $modelDP->obtDatosNominaPorIdDatoGral($idDatoGral); //obtener datos nominas
        
        echo '<pre>';
        print_r($datosGrales);
        print_r($datosCliente);
        print_r($datosCto);
        print_r($datosNominas);                
        echo '</pre>';
                                                                                                                                                                                  
        //Para datos generales
        $dtu_dg = ($datosGrales[0]->DTU>0) ? $datosGrales[0]->DTU : '0';
        $fApartado = ($datosGrales[0]->fechaApartado) ? "'". $datosGrales[0]->fechaApartado ."'" : 'NULL';             
        $fInsc = ($datosGrales[0]->fechaInscripcion) ? "'". $datosGrales[0]->fechaInscripcion ."'" : 'NULL';               
        $fCierre = ($datosGrales[0]->fechaCierre) ? "'". $datosGrales[0]->fechaCierre ."'" : 'NULL';            
        $gte_vtas_dg = ($datosGrales[0]->idGerenteVentas) ? $datosGrales[0]->idGerenteVentas : 'NULL';           
        $titulacion_dg = ($datosGrales[0]->idTitulacion) ? $datosGrales[0]->idTitulacion : 'NULL';            
        $asesor_dg = ($datosGrales[0]->idAsesor) ? $datosGrales[0]->idAsesor : 'NULL';                
        $prospectador_dg = ($datosGrales[0]->idPropectador) ? $datosGrales[0]->idPropectador : 'NULL';                
        $estatus_dg = ($datosGrales[0]->idEstatus) ? $datosGrales[0]->idEstatus : 'NULL';                
        $fEstatus = ($datosGrales[0]->fechaEstatus) ? $datosGrales[0]->fechaEstatus : $fechaEstatus;                
        $motivo_cancel_dg = ($datosGrales[0]->idCancelacion) ? $datosGrales[0]->idCancelacion : 'NULL';                
        $motivo_texto_dg = ($datosGrales[0]->observaciones) ? $datosGrales[0]->observaciones : NULL;               
        $ref_dg = ($datosGrales[0]->referencia) ? $datosGrales[0]->referencia : NULL;              
        $prom_dg = ($datosGrales[0]->promocion) ? $datosGrales[0]->promocion : NULL;              
        $fEntrega = ($datosGrales[0]->fechaEntrega) ? "'". $datosGrales[0]->fechaEntrega ."'" : 'NULL';               
        $fReprog = ($datosGrales[0]->reprogramacion) ? "'". $datosGrales[0]->reprogramacion ."'" : 'NULL';              
        $historico = 0;
        $fdtu = ($datosGrales[0]->fechaDTU) ? "'". $datosGrales[0]->fechaDTU ."'" : 'NULL'; 
                                                                                                                                
        //Datos para los clientes
        $idDatoCliente = ($datosCliente[0]->idDatoCliente) ? $datosCliente[0]->idDatoCliente : NULL;   
        $aPaternoC_dg = ($datosCliente[0]->aPaterno) ? $datosCliente[0]->aPaterno : NULL;   
        $aMaternoC_dg = ($datosCliente[0]->aManterno) ? $datosCliente[0]->aManterno : NULL;   
        $nombreC_dg = ($datosCliente[0]->nombre) ? $datosCliente[0]->nombre : NULL;   
        $nssC_dg = ($datosCliente[0]->NSS) ? $datosCliente[0]->NSS : NULL;   
        $tipoCto_dg = ($datosCliente[0]->tipoCreditoId) ? $datosCliente[0]->tipoCreditoId : 'NULL'; 
        
        $calle_cl = ($datosCliente[0]->calle) ? $datosCliente[0]->calle : NULL; 
        $no_cl = ($datosCliente[0]->numero) ? $datosCliente[0]->numero : NULL; 
        $col_cl = ($datosCliente[0]->colonia) ? $datosCliente[0]->colonia : NULL; 
        $mpioLodad_cl = ($datosCliente[0]->municipio) ? $datosCliente[0]->municipio : NULL; 
        $estado_cl = ($datosCliente[0]->estadoId) ? $datosCliente[0]->estadoId : 'NULL'; 
        $cp_cl = ($datosCliente[0]->cp) ? $datosCliente[0]->cp : NULL; 
        $empresa_cl = ($datosCliente[0]->empresa) ? $datosCliente[0]->empresa : NULL; 
        $fechaNac = ($datosCliente[0]->fechaNac) ? "'". $datosCliente[0]->fechaNac ."'" : 'NULL'; 
        $genero = ($datosCliente[0]->genero!='') ? $datosCliente[0]->genero : '0'; 
        $emailC_dg = ($datosCliente[0]->email) ? $datosCliente[0]->email : NULL;   
        
        //Datos credito        
        $idDatoCredito = ($datosCto[0]->idDatoCredito) ? $datosCto[0]->idDatoCredito : NULL; 
        $numcdto_dc = ($datosCto[0]->numeroCredito) ? $datosCto[0]->numeroCredito : NULL; 
        $valorv_dc = ($datosCto[0]->valorVivienda) ? $datosCto[0]->valorVivienda : 'NULL';
        $cInfonavit_dc = ($datosCto[0]->cInfonavit) ? $datosCto[0]->cInfonavit : 'NULL';
        $subFed_dc = ($datosCto[0]->sFederal) ? $datosCto[0]->sFederal : 'NULL';
        $gEscrituracion_dc = ($datosCto[0]->gEscrituracion) ? $datosCto[0]->gEscrituracion : 'NULL';
        $ahorroVol = ($datosCto[0]->ahorroVol) ? $datosCto[0]->ahorroVol : 'NULL';                        
        $seguros = ($datosCto[0]->seguros) ? $datosCto[0]->seguros : 'NULL';  
        $seguros_resta = ($datosCto[0]->seguros_resta) ? $datosCto[0]->seguros_resta : 'NULL';
        
       //nominas                                                       
        $ases_comision_nom = ($datosNominas[0]->comision) ? $datosNominas[0]->comision : 'NULL'; 
        $preventa_nom = ($datosNominas[0]->esPreventa!='') ? $datosNominas[0]->esPreventa : '0';    
        $fPagoApar = ($datosNominas[0]->fechaPagApartado) ? "'". $datosNominas[0]->fechaPagApartado ."'" : 'NULL'; 
        $fDescomision = ($datosNominas[0]->fechaDescomicion) ? "'". $datosNominas[0]->fechaDescomicion ."'" : 'NULL'; 
        $fPagoEsc = ($datosNominas[0]->fechaPagEscritura) ? "'". $datosNominas[0]->fechaPagEscritura ."'" : 'NULL'; 
        $fPagoLiq = ($datosNominas[0]->fechaPagLiquidacion) ? "'". $datosNominas[0]->fechaPagLiquidacion ."'" : 'NULL';          
        $esAsesor = ($datosNominas[0]->esAsesor!='') ? $datosNominas[0]->esAsesor : '0';    
                        
        $nombreReferido = ($datosNominas[0]->nombreReferido) ? $datosNominas[0]->nombreReferido : NULL;         
        if($nombreReferido!=NULL){
            $esReferido = 1;       
        }else{
            $esReferido = 0;       
        }
        
        $pros_comision_nom = ($datosNominas[0]->comisionPros) ? $datosNominas[0]->comisionPros : 'NULL'; 
        $pros_preventa_nom = ($datosNominas[0]->esPreventaPros!='') ? $datosNominas[0]->esPreventaPros : '0';    
        $pros_fPagoApar_nom = ($datosNominas[0]->fPagoApartadoPros) ? "'". $datosNominas[0]->fPagoApartadoPros ."'" : 'NULL'; 
        $pros_fDesc_nom = ($datosNominas[0]->fPagoDescomisionPros) ? "'". $datosNominas[0]->fPagoDescomisionPros ."'" : 'NULL'; 
        $pros_fPagoEsc_nom = ($datosNominas[0]->fPagoEscrituraPros) ? "'". $datosNominas[0]->fPagoEscrituraPros ."'" : 'NULL'; 
        $idPctAsesor = ($datosNominas[0]->pctIdAses) ? $datosNominas[0]->pctIdAses : NULL; 
        $idPctProspectador = ($datosNominas[0]->pctIdProsp) ? $datosNominas[0]->pctIdProsp : NULL; 
        
        
        //Obtener los datos de los telefonos        
        $datosTelefonos = $modelDP->obtTelefonosPorIdCliente($idDatoCliente); //obtener datos telefonos
        $datosReferencias = $modelDP->obtReferenciasPorIdCliente($idDatoCliente); //obtener datos referencias
        $datosDepositos = $modelDP->obtDepositosPorIdCto($idDatoCredito); //obtener datos depositos
        $datosPagares = $modelDP->obtPagaresPorIdCto($idDatoCredito); //obtener datos pagares
        $datosAcabados = $modelDP->obtAcabadosPorIdDatoGral($idDatoGral); //obtener datos acabados
        $datosServicios = $modelDP->obtServiciosPorIdDatoGral($idDatoGral); //obtener datos servicios
        $datosPostVenta = $modelDP->obtPostVentaPorIdDatoGral($idDatoGral); //obtener datos postventa
                              
/*
 * Hacer update al departamento seleccionado
 */                
      /*  
      //Actualizar departamento seleccionado                
      $modelDP->upDatoGralReasig($dtu_dg, $fApartado, $fInsc, $fCierre, $gte_vtas_dg, $titulacion_dg, $asesor_dg,
                                       $prospectador_dg, $estatus_dg, $fEstatus, $motivo_cancel_dg, $motivo_texto_dg, $ref_dg,
                                       $prom_dg, $fEntrega, $fReprog, 0, 0, $fdtu, $idDatoGralNew);                    
      */
                   
        $id = $modelDP->insDatoGral($idDptoSel, $dtu_dg, $fApartado, $fInsc, $fCierre, $gte_vtas_dg, $titulacion_dg, $asesor_dg,
                                       $prospectador_dg, $estatus_dg, $fEstatus, $motivo_cancel_dg, $motivo_texto_dg, $ref_dg,
                                       $prom_dg, $fEntrega, $fReprog, $historico, $fdtu);            
        //Insertar en tabla cliente
        echo 'Es: '.$id;
        
        if($id>0){
            
           $idCliente = $modelDP->insDatoCliente($id, $aPaternoC_dg, $aMaternoC_dg, $nombreC_dg, $nssC_dg, $tipoCto_dg,
                                               $calle_cl, $no_cl, $col_cl, $mpioLodad_cl, $estado_cl, $cp_cl, $empresa_cl, $fechaNac, $genero, $emailC_dg);            

           $idCto = $modelDP->insDatoCdto($id, $numcdto_dc, $valorv_dc, $cInfonavit_dc, $subFed_dc, $gEscrituracion_dc, $ahorroVol, $seguros, $seguros_resta);

           $idNomina = $modelDP->insDatoNomina($id, $ases_comision_nom, $preventa_nom, $fPagoApar, $fDescomision, $fPagoEsc, $fPagoLiq, $esAsesor, $esReferido, $nombreReferido,
                                            $pros_comision_nom, $pros_preventa_nom, $pros_fPagoApar_nom, $pros_fDesc_nom, $pros_fPagoEsc_nom, $idPctAsesor, $idPctProspectador);

            //nuevo dato general = $id
            if(count($datosTelefonos)>0){
                $modelDP->insTelefonos($idCliente, $datosTelefonos);
            }

            if(count($datosReferencias)>0){
                $modelDP->insReferencias($idCliente, $datosReferencias);
            }

            if(count($datosDepositos)>0){
                $modelDP->insDepositos($idCto, $datosDepositos);
            }

            if(count($datosPagares)>0){
                $modelDP->insPagares($idCto, $datosPagares);
            }

            if(count($datosAcabados)>0){
                $modelDP->insAcabados($id, $datosAcabados);                
            }

            if(count($datosServicios)>0){
                $modelDP->insServicios($id, $datosServicios);
            }

            if(count($datosPostVenta)>0){
                $modelDP->insPostVenta($id, $datosPostVenta);
            }
           
           echo 'Id $idCliente: ' .$idCliente .'<br/>';
           echo 'Id $idCto: ' .$idCto .'<br/>';
           echo 'Id Nomina: ' .$idNomina .'<br/>';           
        }        
        
        $msg = JText::sprintf('Registro salvado correctamente.');                                                          
        $this->setRedirect( 'index.php?option=com_sasfe&view=listadodeptos&idFracc='.$idFracc,$msg);                          
        
        
    }
    
        
    public function salvar($param){
        jimport('joomla.filesystem.file');       
        JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));        
        $model = JModelLegacy::getInstance('Departamento', 'SasfeModel');   
        $modelGM = JModelLegacy::getInstance('globalmodelsbk', 'SasfeModel');            
        $fechaEstatus = date("Y-m-d");  
        //obtener valores del formulario       
//        $arrForm = JRequest::get('post'); //lee todas las variables por post        
//        echo '<pre>'; print_r($arrForm); echo '</pre>';                    
                
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
        $preventa_nom = (JRequest::getVar('preventa_nom')!='') ? JRequest::getVar('preventa_nom') : '0';    
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
        $pros_preventa_nom = (JRequest::getVar('pros_preventa_nom')!='') ? JRequest::getVar('pros_preventa_nom') : '0';    
        $pros_fPagoApar_nom = (JRequest::getVar('fPagoAparPros')) ? "'". $modelGM->convertDateToMysql(JRequest::getVar('fPagoAparPros')) ."'" : 'NULL'; 
        $pros_fDesc_nom = (JRequest::getVar('fDescomisionPros')) ? "'". $modelGM->convertDateToMysql(JRequest::getVar('fDescomisionPros')) ."'" : 'NULL'; 
        $pros_fPagoEsc_nom = (JRequest::getVar('fPagoEscPros')) ? "'". $modelGM->convertDateToMysql(JRequest::getVar('fPagoEscPros')) ."'" : 'NULL'; 
        
        $idPctAsesor = JRequest::getVar('idPctAsesor');
        $idPctProspectador = JRequest::getVar('idPctProspectador');  
        
        if($id_dato==0){                                              
             $id = $model->insDatoGral($id_Dpt, $dtu_dg, $fApartado, $fInsc, $fCierre, $gte_vtas_dg, $titulacion_dg, $asesor_dg,
                                       $prospectador_dg, $estatus_dg, $fEstatus, $motivo_cancel_dg, $motivo_texto_dg, $ref_dg,
                                       $prom_dg, $fEntrega, $fReprog, $historico);            
            //Insertar en tabla cliente
            if($id>0){
               $idCliente = $model->insDatoCliente($id, $aPaternoC_dg, $aMaternoC_dg, $nombreC_dg, $nssC_dg, $tipoCto_dg,
                                                   $calle_cl, $no_cl, $col_cl, $mpioLodad_cl, $estado_cl, $cp_cl, $empresa_cl);            
               
               $idCto = $model->insDatoCdto($id, $numcdto_dc, $valorv_dc, $cInfonavit_dc, $subFed_dc, $gEscrituracion_dc, $ahorroVol, $seguros, $seguros_resta);
               
               $idNomina = $model->insDatoNomina($id, $ases_comision_nom, $preventa_nom, $fPagoApar, $fDescomision, $fPagoEsc, $fPagoLiq, $esAsesor, $esReferido, $nombreReferido,
                                                $pros_comision_nom, $pros_preventa_nom, $pros_fPagoApar_nom, $pros_fDesc_nom, $pros_fPagoEsc_nom, $idPctAsesor, $idPctProspectador);
               
               echo 'Id Nomina: ' .$idNomina;
            }        
			$idDatoGral = $id;
        }else{                                    
            $model->upDatoGral($id_Dpt, $dtu_dg, $fApartado, $fInsc, $fCierre, $gte_vtas_dg, $titulacion_dg, $asesor_dg,
                                       $prospectador_dg, $estatus_dg, $fEstatus, $motivo_cancel_dg, $motivo_texto_dg, $ref_dg,
                                       $prom_dg, $fEntrega, $fReprog, $historico, $id_dato);
            $idCliente = $model->upDatoCliente($aPaternoC_dg, $aMaternoC_dg, $nombreC_dg, $nssC_dg, $tipoCto_dg, 
                                               $calle_cl, $no_cl, $col_cl, $mpioLodad_cl, $estado_cl, $cp_cl, $empresa_cl, $id_dato); 
            
            $model->upDatoCdto($numcdto_dc, $valorv_dc, $cInfonavit_dc, $subFed_dc, $gEscrituracion_dc, $ahorroVol, $seguros, $seguros_resta, $id_dato);
            
            $model->upDatoNomina($ases_comision_nom, $preventa_nom, $fPagoApar, $fDescomision, $fPagoEsc, $fPagoLiq, $esAsesor, $esReferido, $nombreReferido, 
                                 $pros_comision_nom, $pros_preventa_nom, $pros_fPagoApar_nom, $pros_fDesc_nom, $pros_fPagoEsc_nom, $idPctAsesor, $idPctProspectador, $id_dato);
								 
			$idDatoGral = $id_dato;
        }
        
       
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
    
}

?>
