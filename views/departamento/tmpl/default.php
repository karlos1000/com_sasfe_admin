<?php
// No direct access
defined('_JEXEC') or die('Restricted access');
JHtml::_('behavior.tooltip');
jimport('joomla.filesystem.file');
jimport('joomla.filesystem.folder');
JHtml::_('behavior.tooltip');
JHTML::_('behavior.modal');
JHTML::_('behavior.formvalidation');


include_once JPATH_ADMINISTRATOR . DIRECTORY_SEPARATOR . 'components' . DIRECTORY_SEPARATOR . 'com_sasfe' . DIRECTORY_SEPARATOR . 'common' . DIRECTORY_SEPARATOR . 'KoolControls' . DIRECTORY_SEPARATOR . 'KoolAjax' . DIRECTORY_SEPARATOR . 'koolajax.php';                                                      
include_once JPATH_ADMINISTRATOR . DIRECTORY_SEPARATOR . 'components' . DIRECTORY_SEPARATOR . 'com_sasfe' . DIRECTORY_SEPARATOR . 'common' . DIRECTORY_SEPARATOR . 'KoolControls' . DIRECTORY_SEPARATOR . 'KoolGrid' . DIRECTORY_SEPARATOR . 'koolgrid.php';            
include_once JPATH_ADMINISTRATOR . DIRECTORY_SEPARATOR . 'components' . DIRECTORY_SEPARATOR . 'com_sasfe' . DIRECTORY_SEPARATOR . 'common' . DIRECTORY_SEPARATOR . 'KoolControls' . DIRECTORY_SEPARATOR . 'KoolTabs' . DIRECTORY_SEPARATOR . 'kooltabs.php';
include_once JPATH_ADMINISTRATOR . DIRECTORY_SEPARATOR . 'components' . DIRECTORY_SEPARATOR . 'com_sasfe' . DIRECTORY_SEPARATOR . 'common' . DIRECTORY_SEPARATOR . 'KoolControls' . DIRECTORY_SEPARATOR . 'KoolCalendar' . DIRECTORY_SEPARATOR . 'koolcalendar.php';        
include_once JPATH_ADMINISTRATOR . DIRECTORY_SEPARATOR . 'components' . DIRECTORY_SEPARATOR . 'com_sasfe' . DIRECTORY_SEPARATOR . 'common' . DIRECTORY_SEPARATOR . 'KoolControls' . DIRECTORY_SEPARATOR . 'KoolGrid' . DIRECTORY_SEPARATOR . 'ext'. DIRECTORY_SEPARATOR .'datasources'. DIRECTORY_SEPARATOR .'MySQLiDataSource.php';
$base = JPATH_SITE.'/administrator/components/com_sasfe/common/KoolControls/KoolGrid/localization/es.xml';     
$calLangueaje = JPATH_SITE.'/administrator/components/com_sasfe/common/KoolControls/KoolCalendar/localization/es.xml';     
$dateC = date("d/m/Y"); //fecha actual
JViewLegacy::loadHelper('sasfehp');                

$idDatoGeneral = (isset($this->data[0]->idDatoGeneral))?$this->data[0]->idDatoGeneral:'';
$departamentoId = (isset($this->data[0]->departamentoId))?$this->data[0]->departamentoId:'';
$DTU = (isset($this->data[0]->DTU))?$this->data[0]->DTU:'';
$fechaApartado = (isset($this->data[0]->fechaApartado))? date("d/m/Y", strtotime($this->data[0]->fechaApartado)):'';
$fechaInscripcion = (isset($this->data[0]->fechaInscripcion))? date("d/m/Y", strtotime($this->data[0]->fechaInscripcion)):'';
$fechaCierre = (isset($this->data[0]->fechaCierre))? date("d/m/Y", strtotime($this->data[0]->fechaCierre)):'';
$idGerenteVentas = (isset($this->data[0]->idGerenteVentas))?$this->data[0]->idGerenteVentas:'';
//Imp. 01/10/20
//Revisar que exista el gerente de ventas en el bucle en caso contrario agregarlo por default
$elemInacGte = 1;
if($idGerenteVentas!=''){
    $gerenteInac = SasfehpHelper::obtSelectInactivoAP($idGerenteVentas);
    $elemInacGte = ($gerenteInac[0]->activo==0) ? 0 : 1;
    $opctionInacGte =  '<option selected  value="' . $gerenteInac[0]->idDato . '">' . $gerenteInac[0]->nombre . ' (Inactivo) '. '</option>';
    // $opctionInacGte =  '<option selected  value="' . $gerenteInac[0]->idDato . '">' . $gerenteInac[0]->nombre . '</option>';
}

$idTitulacion = (isset($this->data[0]->idTitulacion))?$this->data[0]->idTitulacion:'';
$idAsesor = (isset($this->data[0]->idAsesor))?$this->data[0]->idAsesor:'';
$idPropectador = (isset($this->data[0]->idPropectador))?$this->data[0]->idPropectador:'';
$idEstatus = (isset($this->data[0]->idEstatus))?$this->data[0]->idEstatus:'';
$fechaEstatus = (isset($this->data[0]->fechaEstatus))? date("d/m/Y", strtotime($this->data[0]->fechaEstatus)):'';
$idCancelacion = (isset($this->data[0]->idCancelacion))?$this->data[0]->idCancelacion:'';
$observaciones = (isset($this->data[0]->observaciones))?$this->data[0]->observaciones:'';
$referencia = (isset($this->data[0]->referencia))?$this->data[0]->referencia:'';
$promocion = (isset($this->data[0]->promocion))?$this->data[0]->promocion:'';
$fechaEntrega = (isset($this->data[0]->fechaEntrega))? date("d/m/Y", strtotime($this->data[0]->fechaEntrega)):'';
$reprogramacion = (isset($this->data[0]->reprogramacion))? date("d/m/Y", strtotime($this->data[0]->reprogramacion)):'';
$esHistorico = (isset($this->data[0]->esHistorico))?$this->data[0]->esHistorico:'';
$esReasignado = (isset($this->data[0]->esReasignado))?$this->data[0]->esReasignado:'';
$fechaDTU = (isset($this->data[0]->fechaDTU))? date("d/m/Y", strtotime($this->data[0]->fechaDTU)):'';

$elemInacAses = 1;
$elemInacPros = 1;
if($idAsesor!=''){
    $asesorInac = SasfehpHelper::obtSelectInactivoAP($idAsesor);
    $elemInacAses = ($asesorInac[0]->activo==0) ? 0 : 1;
    $opctionInacA =  '<option selected  value="' . $asesorInac[0]->idDato . '">' . $asesorInac[0]->nombre . ' (Inactivo) '. '</option>';                        
}
if($idPropectador!=''){
    $prosInac = SasfehpHelper::obtSelectInactivoAP($idPropectador);
    $elemInacPros = ($prosInac[0]->activo==0) ? 0 : 1;
    $opctionInacP =  '<option selected  value="' . $prosInac[0]->idDato . '">' . $prosInac[0]->nombre . ' (Inactivo) '. '</option>';                    
}

//Datos del cliente
$idDatoCliente = (isset($this->dataCustomer[0]->idDatoCliente))?$this->dataCustomer[0]->idDatoCliente:'0';
$datoGeneralId = (isset($this->dataCustomer[0]->datoGeneralId))?$this->dataCustomer[0]->datoGeneralId:'';
$aPaterno = (isset($this->dataCustomer[0]->aPaterno))?$this->dataCustomer[0]->aPaterno:'';
$aManterno = (isset($this->dataCustomer[0]->aManterno))?$this->dataCustomer[0]->aManterno:'';
$nombre = (isset($this->dataCustomer[0]->nombre))?$this->dataCustomer[0]->nombre:'';
$NSS = (isset($this->dataCustomer[0]->NSS))?$this->dataCustomer[0]->NSS:'';
$tipoCreditoId = (isset($this->dataCustomer[0]->tipoCreditoId))?$this->dataCustomer[0]->tipoCreditoId:'';
$calle = (isset($this->dataCustomer[0]->calle))?$this->dataCustomer[0]->calle:'';
$numero = (isset($this->dataCustomer[0]->numero))?$this->dataCustomer[0]->numero:'';
$colonia = (isset($this->dataCustomer[0]->colonia))?$this->dataCustomer[0]->colonia:'';
$municipio = (isset($this->dataCustomer[0]->municipio))?$this->dataCustomer[0]->municipio:'';
$estadoId = (isset($this->dataCustomer[0]->estadoId))?$this->dataCustomer[0]->estadoId:'';
$cp = (isset($this->dataCustomer[0]->cp))?$this->dataCustomer[0]->cp:'';
$empresa = (isset($this->dataCustomer[0]->empresa))?$this->dataCustomer[0]->empresa:'';
$fechaNac = (isset($this->dataCustomer[0]->fechaNac))? date("d/m/Y", strtotime($this->dataCustomer[0]->fechaNac)) :'';
$genero = (isset($this->dataCustomer[0]->genero))? $this->dataCustomer[0]->genero:'0';
$emailC = (isset($this->dataCustomer[0]->email))? $this->dataCustomer[0]->email:'';

//Datos credito
$idDatoCredito = (isset($this->dataCredit[0]->idDatoCredito))?$this->dataCredit[0]->idDatoCredito:'0';
//$datoGeneralId = (isset($this->dataCredit[0]->datoGeneralId))?$this->dataCredit[0]->datoGeneralId:'';
$numeroCredito = (isset($this->dataCredit[0]->numeroCredito))?$this->dataCredit[0]->numeroCredito:'';
$valorVivienda = (isset($this->dataCredit[0]->valorVivienda))?$this->dataCredit[0]->valorVivienda:'0';
$cInfonavit = (isset($this->dataCredit[0]->cInfonavit))?$this->dataCredit[0]->cInfonavit:'0.00';
$sFederal = (isset($this->dataCredit[0]->sFederal))?$this->dataCredit[0]->sFederal:'0.00';
$gEscrituracion = (isset($this->dataCredit[0]->gEscrituracion))?$this->dataCredit[0]->gEscrituracion:'0.00';
$ahorroVol = (isset($this->dataCredit[0]->ahorroVol))?$this->dataCredit[0]->ahorroVol:'0.00';
$seguros = (isset($this->dataCredit[0]->seguros))?$this->dataCredit[0]->seguros:'0.00';
$segurosResta = (isset($this->dataCredit[0]->seguros_resta))?$this->dataCredit[0]->seguros_resta:'0.00';

//Datos nomina
$idNomina = (isset($this->dataNomina[0]->idNomina))?$this->dataNomina[0]->idNomina:'';
$comision = (isset($this->dataNomina[0]->comision))?$this->dataNomina[0]->comision:'0.00';
$esPreventa = (isset($this->dataNomina[0]->esPreventa))?$this->dataNomina[0]->esPreventa:'';
$fechaPagApartado = (isset($this->dataNomina[0]->fechaPagApartado))? date("d/m/Y", strtotime($this->dataNomina[0]->fechaPagApartado)) :'';
$fechaDescomicion = (isset($this->dataNomina[0]->fechaDescomicion))? date("d/m/Y", strtotime($this->dataNomina[0]->fechaDescomicion)) :'';
$fechaPagEscritura = (isset($this->dataNomina[0]->fechaPagEscritura))? date("d/m/Y", strtotime($this->dataNomina[0]->fechaPagEscritura)) :'';
$fechaPagLiquidacion = (isset($this->dataNomina[0]->fechaPagLiquidacion))? date("d/m/Y", strtotime($this->dataNomina[0]->fechaPagLiquidacion)) :'';
$esAsesor = (isset($this->dataNomina[0]->esAsesor))?$this->dataNomina[0]->esAsesor:'';
$esReferido = (isset($this->dataNomina[0]->esReferido))?$this->dataNomina[0]->esReferido:'';
$nombreReferido = (isset($this->dataNomina[0]->nombreReferido))?$this->dataNomina[0]->nombreReferido:'';

$comisionPros = (isset($this->dataNomina[0]->comisionPros))?$this->dataNomina[0]->comisionPros:'0.00';
$esPreventaPros = (isset($this->dataNomina[0]->esPreventaPros))?$this->dataNomina[0]->esPreventaPros:'';
$fPagoApartadoPros = (isset($this->dataNomina[0]->fPagoApartadoPros))? date("d/m/Y", strtotime($this->dataNomina[0]->fPagoApartadoPros)) :'';
$fPagoDescomisionPros = (isset($this->dataNomina[0]->fPagoDescomisionPros))? date("d/m/Y", strtotime($this->dataNomina[0]->fPagoDescomisionPros)) :'';
$fPagoEscrituraPros = (isset($this->dataNomina[0]->fPagoEscrituraPros))? date("d/m/Y", strtotime($this->dataNomina[0]->fPagoEscrituraPros)) :'';

$pctIdAses = (isset($this->dataNomina[0]->pctIdAses))?$this->dataNomina[0]->pctIdAses:'0';
$pctIdProsp = (isset($this->dataNomina[0]->pctIdProsp))?$this->dataNomina[0]->pctIdProsp:'0';

//Obtener el celular del cliente
$celularCliente = "";
if($idDatoGeneral>0){
   $celularCliente = SasfehpHelper::obtCelularCliente($idDatoGeneral);   
}
//>>Obtener todos los SMS desde la db
//Apartado definitivo
$SMS1 = SasfehpHelper::obtMensajeSMSPorId(1); 
$SMS1 = SasfehpHelper::reemplazarPlaceholderSMS($SMS1, "{aPaternoCliente}", $aPaterno);
$SMS1 = SasfehpHelper::reemplazarPlaceholderSMS($SMS1, "{telAgencia}", "2.30.39.49");
//Aviso de retencion
$SMS2 = SasfehpHelper::obtMensajeSMSPorId(2); 
$SMS2 = SasfehpHelper::reemplazarPlaceholderSMS($SMS2, "{aPaternoCliente}", $aPaterno);
$SMS2 = SasfehpHelper::reemplazarPlaceholderSMS($SMS2, "{telAgencia}", "2.30.39.49");
//Escriturado
$SMS3 = SasfehpHelper::obtMensajeSMSPorId(3); 
$SMS3 = SasfehpHelper::reemplazarPlaceholderSMS($SMS3, "{aPaternoCliente}", $aPaterno);
$SMS3 = SasfehpHelper::reemplazarPlaceholderSMS($SMS3, "{telAgencia}", "2.30.39.49");
//Fecha de entrega
$SMS4 = SasfehpHelper::obtMensajeSMSPorId(4); 
$SMS4 = SasfehpHelper::reemplazarPlaceholderSMS($SMS4, "{aPaternoCliente}", $aPaterno);
$SMS4 = SasfehpHelper::reemplazarPlaceholderSMS($SMS4, "{telAgencia}", "2.30.39.49");
//echo '$pctIdAses: ' .$pctIdAses .'<br/>';
//echo '$pctIdProsp: ' .$pctIdProsp .'<br/>';
//echo '<pre>';
//    print_r($this->tmpGroup);
//echo '</pre>';

//direccion = 10
//gerente de ventas = 11
//mesa de control = 12
//titulacion = 13
//nominas = 14
//post venta 15

$arrsGp = array();
$arrFinal = array();
$arrPermisosGrids = array();

$arrPermisosGrids['ObtTelsPorClienteGrid'] = true;
$arrPermisosGrids['ObtRefsPorClienteGrid'] = true;
$arrPermisosGrids['ObtDepositosPorDptGrid'] = true;
$arrPermisosGrids['ObtPagaresPorDptGrid'] = true;
$arrPermisosGrids['ObtAcabadosPorDptGrid'] = true;
$arrPermisosGrids['ObtServiciosPorDptGrid'] = true;
$arrPermisosGrids['ObtPostVentaPorDptGrid'] = true;



foreach($this->tmpGroup as $itemGr){    

switch ($itemGr) {                
                case 10:
                    $this->permisosTemp = SasfehpHelper::obtCrtPermisos($itemGr, 'dirV','dirE');    
                    $arrPermisosGrids['ObtTelsPorClienteGrid'] = $arrPermisosGrids['ObtTelsPorClienteGrid'] && false;
                    $arrPermisosGrids['ObtRefsPorClienteGrid'] = $arrPermisosGrids['ObtRefsPorClienteGrid'] && false;
                    $arrPermisosGrids['ObtDepositosPorDptGrid'] = $arrPermisosGrids['ObtDepositosPorDptGrid'] && false;
                    $arrPermisosGrids['ObtPagaresPorDptGrid'] = $arrPermisosGrids['ObtPagaresPorDptGrid'] && false;
                    $arrPermisosGrids['ObtAcabadosPorDptGrid'] = $arrPermisosGrids['ObtAcabadosPorDptGrid'] && false;
                    $arrPermisosGrids['ObtServiciosPorDptGrid'] = $arrPermisosGrids['ObtServiciosPorDptGrid'] && false;
                    $arrPermisosGrids['ObtPostVentaPorDptGrid'] = $arrPermisosGrids['ObtPostVentaPorDptGrid'] && false;                                        
                    break;
                case 11:
                    $this->permisosTemp = SasfehpHelper::obtCrtPermisos($itemGr, 'gVtaV','gVtaE');                        
                    $arrPermisosGrids['ObtTelsPorClienteGrid'] = $arrPermisosGrids['ObtTelsPorClienteGrid'] && true;
                    $arrPermisosGrids['ObtRefsPorClienteGrid'] = $arrPermisosGrids['ObtRefsPorClienteGrid'] && true;
                    $arrPermisosGrids['ObtDepositosPorDptGrid'] = $arrPermisosGrids['ObtDepositosPorDptGrid'] && true;
                    $arrPermisosGrids['ObtPagaresPorDptGrid'] = $arrPermisosGrids['ObtPagaresPorDptGrid'] && true;
                    $arrPermisosGrids['ObtAcabadosPorDptGrid'] = $arrPermisosGrids['ObtAcabadosPorDptGrid'] && true;
                    $arrPermisosGrids['ObtServiciosPorDptGrid'] = $arrPermisosGrids['ObtServiciosPorDptGrid'] && true;
                    $arrPermisosGrids['ObtPostVentaPorDptGrid'] = $arrPermisosGrids['ObtPostVentaPorDptGrid'] && true;                   
                    break;                
                case 12:
                    $this->permisosTemp = SasfehpHelper::obtCrtPermisos($itemGr, 'mCtrV','mCtrE');                        
                    $arrPermisosGrids['ObtTelsPorClienteGrid'] = $arrPermisosGrids['ObtTelsPorClienteGrid'] && false;
                    $arrPermisosGrids['ObtRefsPorClienteGrid'] = $arrPermisosGrids['ObtRefsPorClienteGrid'] && false;
                    $arrPermisosGrids['ObtDepositosPorDptGrid'] = $arrPermisosGrids['ObtDepositosPorDptGrid'] && true;
                    $arrPermisosGrids['ObtPagaresPorDptGrid'] = $arrPermisosGrids['ObtPagaresPorDptGrid'] && true;
                    $arrPermisosGrids['ObtAcabadosPorDptGrid'] = $arrPermisosGrids['ObtAcabadosPorDptGrid'] && true;
                    $arrPermisosGrids['ObtServiciosPorDptGrid'] = $arrPermisosGrids['ObtServiciosPorDptGrid'] && true;
                    $arrPermisosGrids['ObtPostVentaPorDptGrid'] = $arrPermisosGrids['ObtPostVentaPorDptGrid'] && true;                     
                    break;                
                case 13:
                    $this->permisosTemp = SasfehpHelper::obtCrtPermisos($itemGr, 'titV','titE');                     
                    $arrPermisosGrids['ObtTelsPorClienteGrid'] = $arrPermisosGrids['ObtTelsPorClienteGrid'] && false;
                    $arrPermisosGrids['ObtRefsPorClienteGrid'] = $arrPermisosGrids['ObtRefsPorClienteGrid'] && false;
                    $arrPermisosGrids['ObtDepositosPorDptGrid'] = $arrPermisosGrids['ObtDepositosPorDptGrid'] && false;
                    $arrPermisosGrids['ObtPagaresPorDptGrid'] = $arrPermisosGrids['ObtPagaresPorDptGrid'] && true;
                    $arrPermisosGrids['ObtAcabadosPorDptGrid'] = $arrPermisosGrids['ObtAcabadosPorDptGrid'] && false;
                    $arrPermisosGrids['ObtServiciosPorDptGrid'] = $arrPermisosGrids['ObtServiciosPorDptGrid'] && false;
                    $arrPermisosGrids['ObtPostVentaPorDptGrid'] = $arrPermisosGrids['ObtPostVentaPorDptGrid'] && true;                                                             
                    break;                
                case 14:
                    $this->permisosTemp = SasfehpHelper::obtCrtPermisos($itemGr, 'nominaV','nominaE');                     
                    $arrPermisosGrids['ObtTelsPorClienteGrid'] = $arrPermisosGrids['ObtTelsPorClienteGrid'] && true;
                    $arrPermisosGrids['ObtRefsPorClienteGrid'] = $arrPermisosGrids['ObtRefsPorClienteGrid'] && true;
                    $arrPermisosGrids['ObtDepositosPorDptGrid'] = $arrPermisosGrids['ObtDepositosPorDptGrid'] && false;                    
                    $arrPermisosGrids['ObtPagaresPorDptGrid'] = $arrPermisosGrids['ObtPagaresPorDptGrid'] && false;
                    $arrPermisosGrids['ObtAcabadosPorDptGrid'] = $arrPermisosGrids['ObtAcabadosPorDptGrid'] && true;
                    $arrPermisosGrids['ObtServiciosPorDptGrid'] = $arrPermisosGrids['ObtServiciosPorDptGrid'] && true;
                    $arrPermisosGrids['ObtPostVentaPorDptGrid'] = $arrPermisosGrids['ObtPostVentaPorDptGrid'] && true;                                          
                    break;                
                case 15:
                    $this->permisosTemp = SasfehpHelper::obtCrtPermisos($itemGr, 'postVtaV','postVtaE');                        
                    $arrPermisosGrids['ObtTelsPorClienteGrid'] = $arrPermisosGrids['ObtTelsPorClienteGrid'] && true;
                    $arrPermisosGrids['ObtRefsPorClienteGrid'] = $arrPermisosGrids['ObtRefsPorClienteGrid'] && true;                    
                    $arrPermisosGrids['ObtDepositosPorDptGrid'] = $arrPermisosGrids['ObtDepositosPorDptGrid'] && true;                    
                    $arrPermisosGrids['ObtPagaresPorDptGrid'] = $arrPermisosGrids['ObtPagaresPorDptGrid'] && true;
                    $arrPermisosGrids['ObtAcabadosPorDptGrid'] = $arrPermisosGrids['ObtAcabadosPorDptGrid'] && true;
                    $arrPermisosGrids['ObtServiciosPorDptGrid'] = $arrPermisosGrids['ObtServiciosPorDptGrid'] && true;
                    $arrPermisosGrids['ObtPostVentaPorDptGrid'] = $arrPermisosGrids['ObtPostVentaPorDptGrid'] && false;                                                           
                    break;                
                case 16:
                    $this->permisosTemp = SasfehpHelper::obtCrtPermisos($itemGr, 'readV','readE');                        
                    $arrPermisosGrids['ObtTelsPorClienteGrid'] = $arrPermisosGrids['ObtTelsPorClienteGrid'] && true;
                    $arrPermisosGrids['ObtRefsPorClienteGrid'] = $arrPermisosGrids['ObtRefsPorClienteGrid'] && true;
                    $arrPermisosGrids['ObtDepositosPorDptGrid'] = $arrPermisosGrids['ObtDepositosPorDptGrid'] && true;
                    $arrPermisosGrids['ObtPagaresPorDptGrid'] = $arrPermisosGrids['ObtPagaresPorDptGrid'] && true;
                    $arrPermisosGrids['ObtAcabadosPorDptGrid'] = $arrPermisosGrids['ObtAcabadosPorDptGrid'] && true;
                    $arrPermisosGrids['ObtServiciosPorDptGrid'] = $arrPermisosGrids['ObtServiciosPorDptGrid'] && true;
                    $arrPermisosGrids['ObtPostVentaPorDptGrid'] = $arrPermisosGrids['ObtPostVentaPorDptGrid'] && true;                   
                    break;                
            }       
           
 $arrsGp[$itemGr] = $this->permisosTemp;                         
}

$grid = SasfehpHelper::ObtTelsPorClienteGrid($idDatoCliente, $this->historico, $arrPermisosGrids['ObtTelsPorClienteGrid']);
$gridRefCliente = SasfehpHelper::ObtRefsPorClienteGrid($idDatoCliente, $this->historico, $arrPermisosGrids['ObtRefsPorClienteGrid']);
$gridDepositosDC = SasfehpHelper::ObtDepositosPorDptGrid($idDatoCredito, $this->historico, $arrPermisosGrids['ObtDepositosPorDptGrid']);
$gridPagares = SasfehpHelper::ObtPagaresPorDptGrid($idDatoCredito, $this->historico, $arrPermisosGrids['ObtPagaresPorDptGrid']);
$gridAcabados = SasfehpHelper::ObtAcabadosPorDptGrid($this->idDatoGral, $this->historico, $arrPermisosGrids['ObtAcabadosPorDptGrid'], $this->idFracc);
$gridServicios = SasfehpHelper::ObtServiciosPorDptGrid($this->idDatoGral, $this->historico, $arrPermisosGrids['ObtServiciosPorDptGrid'], $this->idFracc);
$gridPostVenta = SasfehpHelper::ObtPostVentaPorDptGrid($this->idDatoGral, $this->historico, $arrPermisosGrids['ObtPostVentaPorDptGrid']);                     


if(count($this->tmpGroup)>1){
    
$arrAncla = $arrsGp[$this->tmpGroup[0]];
$gruposResto = array_shift($this->tmpGroup); 
        
foreach ($arrAncla as $keyA => $item){
    foreach($this->tmpGroup  as $key){
        
        $arrFinal[$keyA] = array('ver'=>0, 'edit'=>0);
        
        if($arrAncla[$keyA]['ver'] == $arrsGp[$key][$keyA]['ver'] ){
            //echo 'Ver :'. $arrFinal[$keyA]['ver'] = $arrAncla[$keyA]['ver'] .'<br/>';
            $arrFinal[$keyA]['ver'] = $arrAncla[$keyA]['ver'];                                                    
        }else{            
            //echo 'son diferentes' .'<br/>';
            if( $arrAncla[$keyA]['ver']==1 || $arrsGp[$key][$keyA]['ver']==1 ){
                $arrFinal[$keyA]['ver'] = 1;
                //echo 'Ver : 1' .'<br/>';
            }else{
                $arrFinal[$keyA]['ver'] = 0;
                //echo 'Ver : 0' .'<br/>';
            }
        }        
        
        if($arrAncla[$keyA]['edit'] == $arrsGp[$key][$keyA]['edit'] ){
            //echo 'Editar :'. $arrFinal[$keyA]['edit'] = $arrAncla[$keyA]['edit'] .'<br/>';
            $arrFinal[$keyA]['edit'] = $arrAncla[$keyA]['edit'];            
            //echo $arrFinal[$keyA]['edit'] = 1;
            //echo 'son iguales';
        }else{
            if( $arrAncla[$keyA]['edit']==1 || $arrsGp[$key][$keyA]['edit']==1 ){
                $arrFinal[$keyA]['edit'] = 1;
                //echo 'Editar : 1' .'<br/>';
            }else{
                $arrFinal[$keyA]['edit'] = 0;
                //echo 'Editar : 0' .'<br/>';
            }
            //echo 'son diferentes';
        }        
    }       
}

echo '<pre>';
    //print_r($arrFinal);
echo '</pre>';

$this->permisos = $arrFinal;

}else{
    $this->permisos = $this->permisosTemp;
}

$totalDepositos = SasfehpHelper::sumaTotalDepositos($idDatoCredito); //Suma total de los depositos
$totalPagares = SasfehpHelper::sumaTotalPagares($idDatoCredito); //Suma total de los pagares
$totalAcabados = SasfehpHelper::sumaTotalAcabado($this->idDatoGral); //Suma total de los acabados
$totalServicios = SasfehpHelper::sumaTotalServicios($this->idDatoGral); //Suma total de los servicios
$totalVivienda = $this->precioViv+$totalAcabados+$totalServicios;//Total de la vivienda
$porPagarPagares = SasfehpHelper::porPagarPagares($idDatoCredito); //Total de pagares - total pagares marcados como pagados


$kts = new KoolTabs("kts");
$kts->scriptFolder = JURI::root().'administrator/components/com_sasfe/common/KoolControls/KoolTabs';
$kts->styleFolder="silver";

//Fecha para DTU
$fdtu = new KoolDatePicker("fdtu"); 
$fdtu->styleFolder="default";
$fdtu->DateFormat = "d/m/Y";
$fdtu->Width="100px";
$fdtu->id="fdtu";
$fdtu->Localization->Load($calLangueaje);
$fdtu->Init();
$fdtu->Value = ($fechaDTU!='') ? $fechaDTU : '';
$fdtu->ClientEvents["OnSelect"] = "dtuOnSelect"; //Imp. 07/09/21, Carlos


//Fecha de nacimiento
$fNacimiento = new KoolDatePicker("fNacimiento"); 
$fNacimiento->styleFolder="default";
$fNacimiento->DateFormat = "d/m/Y";
$fNacimiento->Width="100px";
$fNacimiento->id="fNacimiento";
$fNacimiento->Localization->Load($calLangueaje);
$fNacimiento->Init();
$fNacimiento->Value = ($fechaNac!='') ? $fechaNac : '';

//Fecha de apartado
$fApartado = new KoolDatePicker("fApartado"); 
$fApartado->styleFolder="default";
$fApartado->DateFormat = "d/m/Y";
$fApartado->Width="100px";
$fApartado->id="fApartado";
$fApartado->Localization->Load($calLangueaje);
$fApartado->Init();
$fApartado->Value = $fechaApartado;

//Fecha de inscripcion
$fInsc = new KoolDatePicker("fInsc"); 
$fInsc->styleFolder="default";
$fInsc->DateFormat = "d/m/Y";
$fInsc->Width="100px";
$fInsc->id="fInsc";
$fInsc->Localization->Load($calLangueaje);
$fInsc->Init();
$fInsc->Value = $fechaInscripcion;

//Fecha de cierre
$fCierre = new KoolDatePicker("fCierre"); 
$fCierre->styleFolder="default";
$fCierre->DateFormat = "d/m/Y";
$fCierre->Width="100px";
$fCierre->id="fCierre";
$fCierre->Localization->Load($calLangueaje);
$fCierre->Init();
$fCierre->Value = ($fechaCierre!='') ? $fechaCierre : ''; 

//Fecha de Estatus
$fEstatus = new KoolDatePicker("fEstatus"); 
$fEstatus->styleFolder="default";
$fEstatus->DateFormat = "d/m/Y";
$fEstatus->Width="100px";
$fEstatus->id="fEstatus";
$fEstatus->Localization->Load($calLangueaje);
$fEstatus->Init();
$fEstatus->Value = $fechaEstatus;

//Fecha de entrega
$fEntrega = new KoolDatePicker("fEntrega"); 
$fEntrega->styleFolder="default";
$fEntrega->DateFormat = "d/m/Y";
$fEntrega->Width="100px";
$fEntrega->id="fEntrega";
$fEntrega->Localization->Load($calLangueaje);
$fEntrega->Init();
$fEntrega->Value = $fechaEntrega;
$fEntrega->ClientEvents["OnSelect"] = "event_fechaentrega";

//Reprogramacion
$fReprog = new KoolDatePicker("fReprog"); 
$fReprog->styleFolder="default";
$fReprog->DateFormat = "d/m/Y";
$fReprog->Width="100px";
$fReprog->id="fReprog";
$fReprog->Localization->Load($calLangueaje);
$fReprog->Init();
$fReprog->Value = $reprogramacion;

/*
 * Nominas - Asesor
 */
//Fecha de pago apartado
$fPagoApar = new KoolDatePicker("fPagoApar"); 
$fPagoApar->styleFolder="default";
$fPagoApar->DateFormat = "d/m/Y";
$fPagoApar->Width="100px";
$fPagoApar->id="fPagoApar";
$fPagoApar->Localization->Load($calLangueaje);
$fPagoApar->Init();
$fPagoApar->Value = ($fechaPagApartado!='') ? $fechaPagApartado : ''; 
//$fPagoApar->ClientEvents["OnSelect"] = "Handle_OnSelect";

//Fecha descomision
$fDescomision = new KoolDatePicker("fDescomision"); 
$fDescomision->styleFolder="default";
$fDescomision->DateFormat = "d/m/Y";
$fDescomision->Width="100px";
$fDescomision->id="fDescomision";
$fDescomision->Localization->Load($calLangueaje);
$fDescomision->Init();
$fDescomision->Value = ($fechaDescomicion!='') ? $fechaDescomicion : ''; 

//Fecha de pago escritura
$fPagoEsc = new KoolDatePicker("fPagoEsc"); 
$fPagoEsc->styleFolder="default";
$fPagoEsc->DateFormat = "d/m/Y";
$fPagoEsc->Width="100px";
$fPagoEsc->id="fPagoEsc";
$fPagoEsc->Localization->Load($calLangueaje);
$fPagoEsc->Init();
$fPagoEsc->Value = ($fechaPagEscritura!='') ? $fechaPagEscritura : ''; 

//Fecha de pago liquidacion
$fPagoLiq = new KoolDatePicker("fPagoLiq"); 
$fPagoLiq->styleFolder="default";
$fPagoLiq->DateFormat = "d/m/Y";
$fPagoLiq->Width="100px";
$fPagoLiq->id="fPagoLiq";
$fPagoLiq->Localization->Load($calLangueaje);
$fPagoLiq->Init();
$fPagoLiq->Value = ($fechaPagLiquidacion!='') ? $fechaPagLiquidacion : ''; 

/*
 * Nominas - Prospectador
 */
//Fecha de pago apartado
$fPagoAparPros = new KoolDatePicker("fPagoAparPros"); 
$fPagoAparPros->styleFolder="default";
$fPagoAparPros->DateFormat = "d/m/Y";
$fPagoAparPros->Width="100px";
$fPagoAparPros->id="fPagoAparPros";
$fPagoAparPros->Localization->Load($calLangueaje);
$fPagoAparPros->AjaxEnabled = true;
$fPagoAparPros->Init();
$fPagoAparPros->Value = ($fPagoApartadoPros!='') ? $fPagoApartadoPros : '';

//Fecha descomision
$fDescomisionPros = new KoolDatePicker("fDescomisionPros"); 
$fDescomisionPros->styleFolder="default";
$fDescomisionPros->DateFormat = "d/m/Y";
$fDescomisionPros->Width="100px";
$fDescomisionPros->id="fDescomisionPros";
$fDescomisionPros->Localization->Load($calLangueaje);
$fDescomisionPros->Init();
$fDescomisionPros->Value = ($fPagoDescomisionPros!='') ? $fPagoDescomisionPros : ''; 

//Fecha de pago de escritura
$fPagoEscPros = new KoolDatePicker("fPagoEscPros"); 
$fPagoEscPros->styleFolder="default";
$fPagoEscPros->DateFormat = "d/m/Y";
$fPagoEscPros->Width="100px";
$fPagoEscPros->id="fPagoEscPros";
$fPagoEscPros->Localization->Load($calLangueaje);
$fPagoEscPros->Init();
$fPagoEscPros->Value = ($fPagoEscrituraPros!='') ? $fPagoEscrituraPros : '';


$kts->addTab("root","datosgenerales","Datos Generales","javascript:showPage(\"pag_datogenerales\")",true);//Make node selection	
$kts->addTab("root","cliente","Cliente","javascript:showPage(\"pag_cliente\")");
$kts->addTab("root","datoscredito","Datos credito","javascript:showPage(\"pag_datoscredito\")");
$kts->addTab("root","pagares","Pagares","javascript:showPage(\"pag_pagares\")");
$kts->addTab("root","nominas","Nominas","javascript:showPage(\"pag_nominas\")");
$kts->addTab("root","acabados","Acabados","javascript:showPage(\"pag_acabados\")");
$kts->addTab("root","servicios","Servicios","javascript:showPage(\"pag_servicios\")");
$kts->addTab("root","postventa","Post Venta","javascript:showPage(\"pag_postventa\")");


?>
<style>    
#avisoHistorial{
    background: rgb(184, 78, 78);
    color: white;
    font-size: 12px;
    font-weight: bold;
    width: 100%;
    padding: 5px;
}
</style>
<form class="form-validate form-horizontal" action="<?php echo JRoute::_('index.php?option=com_sasfe&task=departamento'); ?>" method="post" name="adminForm" id="adminForm">    
    
    <div class="container">
        <div class="tabs">
            <?php echo $kts->Render(); ?>
        </div>
        <div class="multipages">
            <div id="pag_datogenerales" style="display:block;">
                <div class="col50">                    
                    <fieldset class="adminform"> 
                        <legend>Vivienda:</legend>
                        <div class="control-group">
                            <div class="control-label">
                            <label for="depto_dg">Depto:</label>
                            </div>
                            <div class="controls">
                            <input type="text" name="depto_dg" id="depto_dg" value="<?php echo $this->NumDpt;?>" style="width:100px;" readonly=""/>                                                        
                            </div>
                        </div>
                        
                        <div class="control-group" <?php echo($this->permisos["dg_dtu"]["ver"]==1) ? '' : 'style="display:none;" ' ?> >
                            <div class="control-label">
                            <label for="dtu_dg">DTU:</label>                
                            </div>
                            <div class="controls">
                                <!-- <select id="dtu_dg" name="dtu_dg" <?php echo ($this->permisos["dg_dtu"]["edit"]==1) ? '' : 'disabled'; ?> style="width:25%;">                   -->
                                    <select id="dtu_dg" name="dtu_dg" <?php echo ($this->permisos["dg_dtu"]["edit"]==1) ? 'disabled' : 'disabled'; ?> style="width:25%;">                  
                                <option <?php echo ($DTU==1)?'selected':''; ?> value="1">Si</option>                                
                                <option <?php echo ($DTU==0)?'selected':''; ?> value="0">No</option>                                
                            </select>               
                            </div>
                        </div>
                        
                        <div class="control-group" <?php echo($this->permisos["dg_fdtu"]["ver"]==1) ? '' : 'style="display:none;" ' ?> >
                            <div class="control-label" style="clear: both;">
                                <label for="">Fecha dtu: </label>                                     
                            </div>
                            <div class="controls">
                                <?php echo $fdtu->Render();?>
                            </div>
                        </div>                         
                                                
                        <div class="control-group" <?php echo($this->permisos["dg_fApart"]["ver"]==1) ? '' : 'style="display:none;" ' ?> >                                                            
                            <div class="control-label" style="clear: both;" id="ctrFApartado">
                                <label for="">Fecha de apartado:</label>                                     
                            </div>                                  
                            <div class="controls">
                                <?php echo $fApartado->Render();?>
                            </div>
                            
                        </div>
                                                                        
                        <div class="control-group" <?php echo($this->permisos["dg_fInsc"]["ver"]==1) ? '' : 'style="display:none;" ' ?>>
                            <div class="control-label" style="clear: both;">
                                <label for="">Fecha de inscripci&oacute;n:</label>                                     
                            </div>                                   
                            <div class="controls">
                                <?php echo $fInsc->Render();?>
                            </div>
                        </div>
                                               
                        <div class="control-group" <?php echo($this->permisos["dg_fCierre"]["ver"]==1) ? '' : 'style="display:none;" ' ?> >
                            <div class="control-label" style="clear: both;">
                                <label for="">Fecha de cierre: <span class="star">&nbsp;*</span> </label>                                     
                            </div>
                            <div class="controls">
                                <?php echo $fCierre->Render();?>
                            </div>
                        </div>                        
                        
                        <div class="control-group" <?php echo($this->permisos["dg_gteVtas"]["ver"]==1) ? '' : 'style="display:none;" ' ?> >
                            <div class="control-label">
                            <label for="gte_vtas_dg">Gerente Vtas: <span class="star">&nbsp;*</span></label>                
                            </div>
                            <div class="controls">
                            <select id="gte_vtas_dg" name="gte_vtas_dg" <?php echo ($this->permisos["dg_gteVtas"]["edit"]==1) ? 'class="required"' : 'class="" disabled'; ?> >                  
                                <option value="">--Seleccione--</option> 
                                <?php
                                if($elemInacGte==0){
                                    echo $opctionInacGte;
                                }
                                foreach ($this->ColGteVtas as $item) {
                                    if ($idGerenteVentas == $item->idDato)
                                        $sel = 'selected';
                                    else
                                        $sel = '';
                                    echo '<option ' . $sel . ' value="' . $item->idDato . '">' . $item->nombre . '</option>';                        
                                }
                                ?>                    
                            </select>               
                            <?php if($this->permisos["dg_gteVtas"]["edit"]==0){ ?>
                            <input type="hidden" name="gte_vtas_dg" value="<?php echo $idGerenteVentas; ?>" >
                            <?php } ?>
                        </div>
                        </div>
                        
                        <div class="control-group" <?php echo($this->permisos["dg_titulacion"]["ver"]==1) ? '' : 'style="display:none;" ' ?> >
                            <div class="control-label">
                            <label for="titulacion_dg">Titulación:</label>                
                            </div>
                            <div class="controls">
                            <select id="titulacion_dg" name="titulacion_dg" <?php echo ($this->permisos["dg_titulacion"]["edit"]==1) ? '' : 'disabled'; ?>>                  
                                <option value="">--Seleccione--</option>                                
                                <?php
                                foreach ($this->ColTitulacion as $item) {
                                    if ($idTitulacion == $item->idDato)
                                        $sel = 'selected';
                                    else
                                        $sel = '';
                                    echo '<option ' . $sel . ' value="' . $item->idDato . '">' . $item->nombre . '</option>';                        
                                }
                                ?>   
                            </select>
                            <?php if($this->permisos["dg_titulacion"]["edit"]==0){ ?>
                            <input type="hidden" name="titulacion_dg" value="<?php echo $idTitulacion; ?>" >
                            <?php } ?>
                        </div>                         
                        </div>  
                        <div class="control-group" <?php echo($this->permisos["dg_asesor"]["ver"]==1) ? '' : 'style="display:none;" ' ?>>
                            <div class="control-label">
                            <label for="asesor_dg">Asesor: <span class="star">&nbsp;*</span></label>                
                            </div> 
                            <div class="controls">              
                            <select id="asesor_dg" name="asesor_dg" <?php echo ($this->permisos["dg_asesor"]["edit"]==1) ? 'class="required"' : 'class="" disabled'; ?> >                  
                                <option value="">--Seleccione--</option>                                
                                <?php
                                if($elemInacAses==0){
                                    echo $opctionInacA;
                                }
                                foreach ($this->ColAsesores as $item) {
                                    if ($idAsesor == $item->idDato)
                                        $sel = 'selected';
                                    else
                                        $sel = '';
                                    echo '<option ' . $sel . ' value="' . $item->idDato . '">' . $item->nombre . '</option>';                        
                                }
                                ?>   
                            </select>
                            <?php if($this->permisos["dg_asesor"]["edit"]==0){ ?>
                            <input type="hidden" name="asesor_dg" value="<?php echo $idAsesor; ?>" >
                            <?php } ?>
                        </div>                         
                        </div>                         
                        
                        <div class="control-group" <?php echo($this->permisos["dg_prospec"]["ver"]==1) ? '' : 'style="display:none;" ' ?> >
                            <div class="control-label">
                            <label for="prospectador_dg">Prospectador:</label>                
                            </div>
                            <div class="controls">
                            <select id="prospectador_dg" name="prospectador_dg" <?php echo ($this->permisos["dg_prospec"]["edit"]==1) ? '' : 'disabled'; ?> >                  
                                <option value="">--Seleccione--</option>                                                                
                                <?php
                                if($elemInacPros==0){
                                    echo $opctionInacP;
                                }
                                foreach ($this->ColProspectadores as $item) {
                                    if ($idPropectador == $item->idDato)
                                        $sel = 'selected';
                                    else
                                        $sel = '';
                                   echo '<option ' . $sel . ' value="' . $item->idDato . '">' . $item->nombre . '</option>';                        
                                }
                                ?>                                   
                            </select>
                            <?php if($this->permisos["dg_prospec"]["edit"]==0){ ?>
                            <input type="hidden" name="prospectador_dg" value="<?php echo $idPropectador; ?>" >
                            <?php } ?>
                        </div>
                        </div>
                        
                        <div class="control-group" id="campoNombreRef" style="display:none;">
                            <div class="control-label">
                            <label for="nombreRef_dg">Nombre de referido:</label>
                            </div>
                            <div class="controls">
                            <input type="text" name="nombreRef_dg" id="nombreRef_dg" value="<?php echo $nombreReferido; ?>" style="width:150px;" />                                                        
                            </div>
                        </div>
                        
                    </fieldset>
                </div>
                
                <div class="col50">                    
                    <fieldset class="adminform"> 
                        <legend>Estatus:</legend>                                            
                        <div class="control-group" <?php echo($this->permisos["dg_estatus"]["ver"]==1) ? '' : 'style="display:none;" ' ?> >                            
                            <div class="control-label">
                            <label for="estatus_dg">Estatus: <span class="star">&nbsp;*</span></label>
                            </div>
                            <div class="controls">
                            <?php if($this->historico!=1){ ?>
                            <select id="estatus_dg" name="estatus_dg" <?php echo ($this->permisos["dg_estatus"]["edit"]==1) ? ' class="required" ' : 'disabled class="" '; ?> >                                                  
                            <?php }else{ ?>   
                            <select id="estatus_dg" name="estatus_dg" <?php echo ($this->permisos["dg_estatus"]["edit"]==1) ? ' disabled ' : ' disabled '; ?> >                                                      
                            <?php } ?>    
                                 <?php
                                if($this->gruopID==11){ //Solo los gerentes de venta                                    
                                    echo '<option  value="">--Seleccionar--</option>';
                                foreach ($this->ColEstatus as $item) {
                                        if($item->idDato==88 || $item->idDato==401 || $item->idDato==402){
                                    if ($idEstatus == $item->idDato)
                                        $sel = 'selected';
                                    else
                                        $sel = '';
                                    echo '<option ' . $sel . ' value="' . $item->idDato . '">' . $item->nombre . '</option>';                        
                                        }
                                    }
                                }else{
                                    foreach ($this->ColEstatus as $item) {
                                        if ($idEstatus == $item->idDato)
                                            $sel = 'selected';
                                        else
                                            $sel = '';
                                        echo '<option ' . $sel . ' value="' . $item->idDato . '">' . $item->nombre . '</option>';                        
                                    }
                                }
                                ?>   
                            </select>                            
                            <?php if($this->permisos["dg_estatus"]["edit"]==0){ ?>
                            <input type="hidden" name="estatus_dg" value="<?php echo $idEstatus; ?>" >
                            <?php } ?>
                        </div>
                        </div>
                        <div>
                            <div id="avisoHistorial" style="display: none;">Has seleccionado la opci&oacute;n de cancelado, si salva los datos se pasar&aacute;n ha historial, si contin&uacute;a este proceso es irreversible.</div>
                        </div>
                        
                        <div class="control-group" <?php echo($this->permisos["dg_fEstatus"]["ver"]==1) ? '' : 'style="display:none;" ' ?> >                            
                            <div class="control-label" style="clear: both;">
                                <label for="">Fecha de estatus:</label>                                     
                            </div>                                   
                            <div class="controls">
                                <?php echo $fEstatus->Render();?>
                            </div>
                        </div>
                        
                        <div class="control-group" <?php echo($this->permisos["dg_motCancel"]["ver"]==1) ? '' : 'style="display:none;" ' ?> >
                            <div class="control-label">
                            <label for="motivo_cancel_dg">Motivo cancelación:</label>                
                            </div>
                            <div class="controls">
                            <select id="motivo_cancel_dg" name="motivo_cancel_dg" <?php echo ($this->permisos["dg_motCancel"]["edit"]==1) ? '' : 'disabled '; ?> >                  
                                <option value="">--Seleccione--</option>                                
                                 <?php
                                foreach ($this->ColMotCancel as $item) {
                                    if ($idCancelacion == $item->idDato)
                                        $sel = 'selected';
                                    else
                                        $sel = '';
                                    echo '<option ' . $sel . ' value="' . $item->idDato . '">' . $item->nombre . '</option>';                        
                                }
                                ?>   
                            </select>
                            <?php if($this->permisos["dg_motCancel"]["edit"]==0){ ?>
                            <input type="hidden" name="motivo_cancel_dg" value="<?php echo $idCancelacion; ?>" >
                            <?php } ?>
                        </div>    
                        </div>    
                        
                        <div class="control-group" <?php echo($this->permisos["dg_observ"]["ver"]==1) ? '' : 'style="display:none;" ' ?> >
                            <div class="control-label">
                            <label for="motivo_texto_dg">Observaciones:</label>                
                            </div>
                            <div class="controls">
                            <textarea rows="4" cols="50" id="motivo_texto_dg" name="motivo_texto_dg" <?php echo ($this->permisos["dg_observ"]["edit"]==1) ? '' : 'readonly '; ?>  ><?php echo $observaciones;?></textarea>
                            </div>
                        </div>    

                        <div class="control-group" style="display:none;" id="cont_motivo_liberar_dpto">
                            <div class="control-label">
                            <label for="motivo_liberar_dpto">Motivo regresar al asesor:</label>
                            </div>
                            <div class="controls">
                            <textarea rows="4" cols="50" id="motivo_liberar_dpto" name="motivo_liberar_dpto"></textarea>
                            </div>
                        </div>    
                        
                    </fieldset>
                </div>
                
               <div class="col50">                    
                    <fieldset class="adminform"> 
                        <legend>Datos Cliente:</legend>                        
                        
                        <div class="control-group" <?php echo($this->permisos["dg_aPaternoC"]["ver"]==1) ? '' : 'style="display:none;" ' ?>>
                            <div class="control-label">
                            <label for="aPaternoC_dg">A. Paterno: <span class="star">&nbsp;*</span></label>
                            </div>
                            <div class="controls">
                                <input type="text" name="aPaternoC_dg" id="aPaternoC_dg" value="<?php echo $aPaterno;?>" style="width:40%;" <?php echo ($this->permisos["dg_aPaternoC"]["edit"]==1) ? 'class="required"' : 'class="" readonly'; ?>  />                                                        
                            </div>
                        </div>
                         
                        <div class="control-group" <?php echo($this->permisos["dg_aMaternoC"]["ver"]==1) ? '' : 'style="display:none;" ' ?> >
                            <div class="control-label">
                            <label for="aMaternoC_dg">A. Materno: <span class="star">&nbsp;*</span></label>
                            </div>
                            <div class="controls">
                                <input type="text" name="aMaternoC_dg" id="aMaternoC_dg" value="<?php echo $aManterno;?>" style="width:40%;" <?php echo ($this->permisos["dg_aMaternoC"]["edit"]==1) ? 'class="required"' : 'class="" readonly'; ?>  />                                                        
                            </div>
                        </div>
                        
                        <div class="control-group" <?php echo($this->permisos["dg_nombreC"]["ver"]==1) ? '' : 'style="display:none;" ' ?>>
                            <div class="control-label">
                            <label for="nombreC_dg">Nombre: <span class="star">&nbsp;*</span></label>
                            </div>
                            <div class="controls">
                                <input type="text" name="nombreC_dg" id="nombreC_dg" value="<?php echo $nombre;?>" style="width:60%;" <?php echo ($this->permisos["dg_nombreC"]["edit"]==1) ? 'class="required"' : 'class="" readonly'; ?>  />                                                        
                            </div>
                        </div>
                        
                        <div class="control-group" <?php echo($this->permisos["dg_nssC"]["ver"]==1) ? '' : 'style="display:none;" ' ?>>
                            <div class="control-label">
                            <label for="nssC_dg">NSS:</label>
                            </div>
                            <div class="controls">
                                <input type="text" name="nssC_dg" id="nssC_dg" value="<?php echo $NSS;?>" style="width:40%;" <?php echo ($this->permisos["dg_nssC"]["edit"]==1) ? '' : 'readonly'; ?> />                                                        
                            </div>
                        </div>
                                                
                        <div class="control-group" <?php echo($this->permisos["dg_tipo_credito"]["ver"]==1) ? '' : 'style="display:none;" ' ?> >
                            <div class="control-label">
                            <label for="tipoCto_dg">Tipo de Cr&eacute;dito: <span class="star">&nbsp;*</span></label>                
                            </div>
                            <div class="controls">
                            <select id="tipoCto_dg" name="tipoCto_dg"  <?php echo ($this->permisos["dg_tipo_credito"]["edit"]==1) ? 'class="required"' : 'class="" disabled'; ?>  >                  
                                <option value="">--Seleccione--</option>                                
                                 <?php
                                foreach ($this->ColTiposCto as $item) {
                                    if ($tipoCreditoId == $item->idDato)
                                        $sel = 'selected';
                                    else
                                        $sel = '';
                                    echo '<option ' . $sel . ' value="' . $item->idDato . '">' . $item->nombre . '</option>';                        
                                }
                                ?>   
                            </select>
                            <?php if($this->permisos["dg_tipo_credito"]["edit"]==0){ ?>
                            <input type="hidden" name="tipoCto_dg" value="<?php echo $tipoCreditoId; ?>" >
                            <?php } ?>
                        </div>
                        </div>
                        <!--faltan agregarles sus permisos-->
                        <div class="control-group" <?php echo($this->permisos["dg_fNac"]["ver"]==1) ? '' : 'style="display:none;" ' ?> >                            
                            <div class="control-label" style="clear: both;">
                                <label for="">Fecha de Nacimiento:</label>                                     
                            </div>                                    
                            <div class="controls">
                                <?php echo $fNacimiento->Render();?>
                            </div>
                        </div>
                        
                        <div class="control-group" <?php echo($this->permisos["dg_email"]["ver"]==1) ? '' : 'style="display:none;" ' ?>>
                            <div class="control-label">
                            <label for="emailC_dg">Email: </label>
                            </div>
                            <div class="controls">
                            <input type="text" name="emailC_dg" id="emailC_dg" value="<?php echo $emailC;?>" style="width:155px;" <?php echo ($this->permisos["dg_email"]["edit"]==1) ? '' : 'class="" readonly'; ?>  />
                            </div>
                        </div>
                                                
                        <div class="control-group" <?php echo($this->permisos["dg_genero"]["ver"]==1) ? '' : 'style="display:none;" ' ?>>
                            <div class="control-label">
                            <label for="generoC_dg">Genero:</label>                        
                            </div>
                            <div class="controls">
                                <div style="clear:both;">                             
                                    <div><label for="generoC_dg_m" style="display:inline-block;"> M </label> <input type="radio" name="generoC_dg" id="generoC_dg_m" value="1" <?php echo ($this->permisos["dg_genero"]["edit"]==1) ? '' : 'disabled'; ?>  <?php echo ($genero==1) ? 'checked': '' ?>  ></div><br/>                            
                                    <div><label for="generoC_dg_f" style="display:inline-block;"> F </label> <input type="radio" name="generoC_dg" id="generoC_dg_f" value="2" <?php echo ($this->permisos["dg_genero"]["edit"]==1) ? '' : 'disabled'; ?>  <?php echo ($genero==2) ? 'checked': '' ?>></div>
                            </div>  
                            
                            <?php if($this->permisos["dg_genero"]["edit"]==0){ ?>
                            <input type="hidden" name="generoC_dg" value="<?php echo $genero; ?>" >
                            <?php } ?>
                            </div> 
                        </div>
                        
                    </fieldset>
                </div>
                <div class="col50">                    
                    <fieldset class="adminform"> 
                        <legend>Otros Datos:</legend>
                        <div class="control-group" <?php echo($this->permisos["dg_ref"]["ver"]==1) ? '' : 'style="display:none;" ' ?>>
                            <div class="control-label">
                            <label for="ref_dg">Referencia:</label>
                            </div>
                            <div class="controls">
                            <input type="text" name="ref_dg" id="ref_dg" value="<?php echo $referencia;?>" style="width:100px;" <?php echo ($this->permisos["dg_ref"]["edit"]==1) ? '' : 'readonly'; ?> />                                                        
                            </div>
                        </div>                                                                                 
                        
                        <div class="control-group" <?php echo($this->permisos["dg_prom"]["ver"]==1) ? '' : 'style="display:none;" ' ?>>
                            <div class="control-label">
                            <label for="prom_dg">Promoci&oacute;n:</label>
                            </div>  
                            <div class="controls">
                            <input type="text" name="prom_dg" id="prom_dg" value="<?php echo $promocion;?>" style="width:100px;" <?php echo ($this->permisos["dg_prom"]["edit"]==1) ? '' : 'readonly'; ?>/>                                                        
                            </div>
                        </div>
                        <div class="control-group" <?php echo($this->permisos["dg_fEntrega"]["ver"]==1) ? '' : 'style="display:none;" ' ?> >                            
                            <div class="control-label" style="clear: both;">
                                <label for="">Fecha de entrega:</label>                                     
                            </div>                                    
                            <div class="controls">
                                <?php echo $fEntrega->Render();?>
                            </div>
                        </div>
                        <div class="control-group" <?php echo($this->permisos["dg_reprog"]["ver"]==1) ? '' : 'style="display:none;" ' ?>>                            
                            <div class="control-label" style="clear: both;">
                                <label for="">Reprogramaci&oacute;n:</label>                                     
                            </div>
                            <div class="controls">
                                <?php echo $fReprog->Render();?>
                            </div>
                        </div>
                    </fieldset>
                </div>
            </div>
            
            
            <!--
            --- Muestra los campos referentes al tal cliente
            -->
            <div id="pag_cliente" style="display:none;" >
                 <div class="col50">                    
                    <fieldset class="adminform">                         
                        <div class="control-group" <?php echo($this->permisos["cl_calle"]["ver"]==1) ? '' : 'style="display:none;" ' ?>>
                            <div class="control-label">
                            <label for="calle_cl">Calle:</label>
                            </div>
                            <div class="controls">
                            <input type="text" name="calle_cl" id="calle_cl" value="<?php echo $calle;?>" <?php echo ($this->permisos["cl_calle"]["edit"]==1) ? '' : 'readonly'; ?> />                                                        
                            </div>
                        </div>
                        <div class="control-group" <?php echo($this->permisos["cl_col"]["ver"]==1) ? '' : 'style="display:none;" ' ?>>
                            <div class="control-label">
                            <label for="col_cl">Col:</label>
                            </div>
                            <div class="controls">
                            <input type="text" name="col_cl" id="col_cl" value="<?php echo $colonia;?>" <?php echo ($this->permisos["cl_col"]["edit"]==1) ? '' : 'readonly'; ?>/>                                                        
                            </div>
                        </div>                        
                        <div class="control-group" <?php echo($this->permisos["cl_estado"]["ver"]==1) ? '' : 'style="display:none;" ' ?>>
                            <div class="control-label">
                            <label for="estado_cl">Estado:</label>                
                            </div>
                            <div class="controls">
                            <select id="estado_cl" name="estado_cl" <?php echo ($this->permisos["cl_estado"]["edit"]==1) ? '' : 'disabled'; ?>>                  
                                <option value="">--Seleccione--</option>
                                 <?php
                                foreach ($this->ColEstados as $item) {
                                    if ($estadoId == $item->idEstado)
                                        $sel = 'selected';
                                    else
                                        $sel = '';
                                    echo '<option ' . $sel . ' value="' . $item->idEstado . '">' . $item->estado . '</option>';                        
                                }
                                ?>   
                            </select>
                            <?php if($this->permisos["cl_estado"]["edit"]==0){ ?>
                            <input type="hidden" name="estado_cl" value="<?php echo $estadoId; ?>" >
                            <?php } ?>
                        </div>                                                     
                        </div>                                                     
                        <div class="control-group" <?php echo($this->permisos["cl_empresa"]["ver"]==1) ? '' : 'style="display:none;" ' ?>>
                            <div class="control-label">
                            <label for="empresa_cl">Empresa:</label>
                            </div>
                            <div class="controls">
                            <input type="text" name="empresa_cl" id="empresa_cl" value="<?php echo $empresa;?>" <?php echo ($this->permisos["cl_empresa"]["edit"]==1) ? '' : 'readonly'; ?>/>                                                        
                            </div>
                        </div>                        
                    </fieldset>
                </div>
                <div class="col50">                    
                    <fieldset class="adminform">                         
                        <div class="control-group" <?php echo($this->permisos["cl_no"]["ver"]==1) ? '' : 'style="display:none;" ' ?>>
                            <div class="control-label">
                            <label for="no_cl">No:</label>
                            </div>
                            <div class="controls">
                            <input type="text" name="no_cl" id="no_cl" value="<?php echo $numero;?>" <?php echo ($this->permisos["cl_no"]["edit"]==1) ? '' : 'readonly'; ?> />                                                        
                            </div>
                        </div>
                        <div class="control-group" <?php echo($this->permisos["cl_mpioLodad"]["ver"]==1) ? '' : 'style="display:none;" ' ?>>
                            <div class="control-label">
                            <label for="mpioLodad_cl">Municipio/Localidad:</label>
                            </div>
                            <div class="controls">
                            <input type="text" name="mpioLodad_cl" id="mpioLodad_cl" value="<?php echo $municipio ;?>" <?php echo ($this->permisos["cl_mpioLodad"]["edit"]==1) ? '' : 'readonly'; ?> />                                                        
                            </div>
                        </div>                        
                        <div class="control-group" <?php echo($this->permisos["cl_cp"]["ver"]==1) ? '' : 'style="display:none;" ' ?>>
                            <div class="control-label">
                            <label for="cp_cl">CP:</label>
                            </div>
                            <div class="controls">
                            <input type="text" name="cp_cl" id="cp_cl" value="<?php echo $cp ;?>" <?php echo ($this->permisos["cl_cp"]["edit"]==1) ? '' : 'readonly'; ?> />                                                        
                            </div>
                        </div>                                                
                    </fieldset>
                </div>
                <br/>
                <div>
                    <div id="grid_tels" <?php echo($this->permisos["cl_telefonos"]["ver"]==1) ? '' : 'style="display:none;" ' ?> class="col50">
                        <div class="margin10"><strong>Nota: </strong>Para salvar los datos del grid de tel&eacute;fonos es necesario antes salvar los datos del cliente.</div>
						<div class="margin10">
                        <label>Telefonos</label>				
                    <?php                                
                        echo $koolajax->Render();
                        echo $grid->Render();                                         
                    ?></div>
                    </div>
                    <div id="grid_refs" <?php echo($this->permisos["cl_ref"]["ver"]==1) ? '' : 'style="display:none;" ' ?> class="col50">
                        <div><strong>Nota: </strong>Para salvar los datos del grid de referencias es necesario antes salvar los datos del cliente.</div>
						<div class="margin10">
                        <label>Referencias</label>
                    <?php                                
                        echo $koolajax->Render();
                        echo $gridRefCliente->Render();                                         
                    ?></div>
                    </div>
                </div>   
                
                
            </div>
                            
            
            <!--
            --- Muestra los campos al numero de credito
            -->              
            <div id="pag_datoscredito" style="display:none;">
                   <div class="col50 precios">                    
                    <fieldset class="adminform">                         
                        <div class="control-group" <?php echo($this->permisos["dc_numcdto"]["ver"]==1) ? '' : 'style="display:none;" ' ?> >
                            <div class="control-label">
                            <label for="numcdto_dc">N&uacute;mero de cr&eacute;dito:</label>
                            </div>
                            <div class="controls">
                            <input type="text" name="numcdto_dc" id="numcdto_dc" value="<?php echo $numeroCredito;?>" <?php echo ($this->permisos["dc_numcdto"]["edit"]==1) ? '' : 'readonly'; ?> maxlength="10"/>                                                        
                            </div>
                        </div>
                        <div class="control-group" <?php echo($this->permisos["dc_valorvivienda"]["ver"]==1) ? '' : 'style="display:none;" ' ?>>
                            <div class="control-label">
                            <label for="valorVivienda_dc">Valor de la vivienda:</label>
                            </div>
                            <div class="controls">  
                                <input type="text" name="valorv_dc" id="valorv_dc" value="<?php echo ($valorVivienda!=0) ? '$'.number_format($valorVivienda,2) : '$'.number_format($this->precioViv,2); ?>" onchange="totalVivienda();" class="fmonto" />                                                        
                            </div>
                        </div>                        
                        <div class="control-group" <?php echo($this->permisos["dc_acabados"]["ver"]==1) ? '' : 'style="display:none;" ' ?> >
                            <div class="control-label">
                            <label for="acabados_dc">Acabados:</label>
                            </div>
                            <div class="controls">  
                                <input type="text" name=acabados_dc id="acabados_dc" value="<?php echo '$'.number_format($totalAcabados,2);?>" readonly="readonly"/>                                                        
                            </div>
                        </div>
                        <div class="control-group" <?php echo($this->permisos["dc_servicios"]["ver"]==1) ? '' : 'style="display:none;" ' ?> >
                            <div class="control-label">
                            <label for="servicios_dc">Servicios:</label>
                            </div>
                            <div class="controls">  
                                <input type="text" name="servicios_dc" id="servicios_dc" value="<?php echo '$'.number_format($totalServicios,2);?>" readonly="readonly"/>                                                        
                            </div>
                        </div>
                        <div class="control-group" <?php echo($this->permisos["dc_seguros"]["ver"]==1) ? '' : 'style="display:none;" ' ?>>
                            <div class="control-label">
                            <label for="seguros_dc">Seguro:</label>
                            </div>
                            <div class="controls">  
                                <input type="text" name="seguros_dc" id="seguros_dc" value="<?php echo '$'.number_format($seguros,2); ?>" onchange="ctoMasSub();"  <?php echo ($this->permisos["dc_seguros"]["edit"]==1) ? '' : 'readonly'; ?>  class="fmonto"/>                                                        
                            </div>
                        </div>
                        <div class="control-group" <?php echo($this->permisos["dc_totalViv"]["ver"]==1) ? '' : 'style="display:none;" ' ?> >
                            <div class="control-label">
                            <label for="totalViv_dc">Total vivienda:</label>
                            </div>
                            <div class="controls">  
                                <input type="text" name="totalViv_dc" id="totalViv_dc" value="<?php echo '$'.number_format($totalVivienda,2); ?>" readonly="readonly"/>                                                        
                            </div>
                        </div>
                        <div class="control-group" <?php echo($this->permisos["dc_cInfonavit"]["ver"]==1) ? '' : 'style="display:none;" ' ?> >
                            <div class="control-label">
                            <label for="cInfonavit_dc">C. Infonavit:</label>
                            </div>
                            <div class="controls">  
                                <input type="text" name="cInfonavit_dc" id="cInfonavit_dc" value="<?php echo '$'.number_format($cInfonavit,2); ?>" onchange="ctoMasSub();" <?php echo ($this->permisos["dc_cInfonavit"]["edit"]==1) ? '' : 'readonly'; ?> class="fmonto"/>                                                        
                            </div>
                        </div>
                        <div class="control-group" <?php echo($this->permisos["dc_seguros"]["ver"]==1) ? '' : 'style="display:none;" ' ?>>
                            <div class="control-label">
                            <label for="seguros_dc_resta">Seguro:</label>
                            </div>
                            <div class="controls">  
                                <input type="text" name="seguros_dc_resta" id="seguros_dc_resta" value="<?php echo '$'.number_format($segurosResta,2); ?>" onchange="ctoMasSub();"  <?php echo ($this->permisos["dc_seguros"]["edit"]==1) ? '' : 'readonly'; ?> readonly />                                                        
                            </div>
                        </div>
                        <div class="control-group" <?php echo($this->permisos["dc_subFed"]["ver"]==1) ? '' : 'style="display:none;" ' ?> >
                            <div class="control-label">
                            <label for="subFed_dc">Subsidio Federal:</label>
                            </div>
                            <div class="controls">  
                                <input type="text" name="subFed_dc" id="subFed_dc" value="<?php echo '$'.number_format($sFederal,2); ?>" onchange="ctoMasSub();" <?php echo ($this->permisos["dc_subFed"]["edit"]==1) ? '' : 'readonly'; ?> class="fmonto"/>                                                        
                            </div>
                        </div>
                        <div class="control-group" <?php echo($this->permisos["dc_cdtoSub"]["ver"]==1) ? '' : 'style="display:none;" ' ?>>
                            <div class="control-label">
                            <label for="cdtoSub_dc">Cr&eacute;dito + Subsidio:</label>
                            </div>
                            <div class="controls">  
                            <input type="text" name="cdtoSub_dc" id="cdtoSub_dc" value="" <?php //echo ($this->permisos["dc_cdtoSub"]["edit"]==1) ? '' : 'readonly'; ?> readonly="readonly" />                                                        
                            </div>
                        </div>
                        <div class="control-group" <?php echo($this->permisos["dc_diferencia"]["ver"]==1) ? '' : 'style="display:none;" ' ?>>
                            <div class="control-label">
                            <label for="diferencia_dc">Diferencia:</label>
                            </div>
                            <div class="controls">  
                            <input type="text" name="diferencia_dc" id="diferencia_dc" value="" readonly="readonly" />                                                        
                            </div>
                        </div>
                        <div class="control-group" <?php echo($this->permisos["dc_gEscrituracion"]["ver"]==1) ? '' : 'style="display:none;" ' ?>>
                            <div class="control-label">
                            <label for="gEscrituracion_dc">G. de Escrituraci&oacute;n:</label>
                            </div>
                            <div class="controls">
                                <input type="text" name="gEscrituracion_dc" id="gEscrituracion_dc" value="<?php echo '$'.number_format($gEscrituracion,2); ?>" onchange="difTotal();" <?php echo ($this->permisos["dc_gEscrituracion"]["edit"]==1) ? '' : 'readonly'; ?> class="fmonto" />                                                        
                            </div>
                        </div>
                        <div class="control-group" <?php echo($this->permisos["dc_ahorroVol"]["ver"]==1) ? '' : 'style="display:none;" ' ?>>
                            <div class="control-label">
                            <label for="ahorroVol_dc">Ahorro voluntario:</label>
                            </div>
                            <div class="controls">  
                                <input type="text" name="ahorroVol_dc" id="ahorroVol_dc" value="<?php echo '$'.number_format($ahorroVol,2); ?>" onchange="difTotal();" <?php echo ($this->permisos["dc_ahorroVol"]["edit"]==1) ? '' : 'readonly'; ?> class="fmonto" />                                                        
                            </div>
                        </div>
                        <div class="control-group" <?php echo($this->permisos["dc_difTotal"]["ver"]==1) ? '' : 'style="display:none;" ' ?> >
                            <div class="control-label">
                            <label for="difTotal_dc">Diferencia total:</label>
                            </div>
                            <div class="controls">  
                            <input type="text" name="difTotal_dc" id="difTotal_dc" value="" readonly="readonly" />                                                        
                            </div>
                        </div>                                                
                    </fieldset>
                </div>
                <div class="col50 precios">
				<div>
                    <fieldset class="adminform" style="width:40%;">                         
                        <div class="control-group" <?php echo($this->permisos["dc_totalDep"]["ver"]==1) ? '' : 'style="display:none;" ' ?>>
                            <div class="control-label">
                            <label for="totalDep_dc">Total depositos:</label>
                            </div>
                            <div class="controls">
                                <input type="text" name="totalDep_dc" id="totalDep_dc" value="<?php echo '$'.number_format($totalDepositos,2); ?>" readonly="readonly"/>                                                        
                            </div>
                        </div>
                        <div class="control-group" <?php echo($this->permisos["dc_difPend"]["ver"]==1) ? '' : 'style="display:none;" ' ?>>
                            <div class="control-label">
                            <label for="difPend_dc">Diferencia pendiente:</label>
                            </div>
                            <div class="controls">
                            <input type="text" name="difPend_dc" id="difPend_dc" value="" readonly="readonly" />                                                        
                            </div>
                        </div>                                                
                    </fieldset>
                </div>
                <br/>
                <div style="text-align:left; margin: 10px;">                    
                    <div id="grid_depositos" <?php echo($this->permisos["grid_depositos"]["ver"]==1) ? '' : 'style="display:none;" ' ?> >
                        <div><strong>Nota: </strong>Para salvar los datos del grid de depositos es necesario antes salvar los datos de cr&eacute;dito.</div>
                        <label>Depositos</label>
                    <?php                                
                        echo $koolajax->Render();
                        echo $gridDepositosDC->Render();                                         
                    ?>    
                    </div>
                </div>
                <div id="grid_depositos" <?php echo($this->permisos["grid_depositos"]["ver"]==1) ? '' : 'style="display:none;" ' ?> >
                    <button id="btnPdfApartados" type="button" style="" <?php echo($this->permisos["grid_depositos"]["edit"]==1) ? '' : 'disabled' ?>>Recibo Apartados</button>                  
                    <button id="btnPdfAcuenta" type="button" style="" <?php echo($this->permisos["grid_depositos"]["edit"]==1) ? '' : 'disabled' ?>>Recibo A Cuenta</button>                  
                    <button id="btnPdfLiq" type="button" style="" <?php echo($this->permisos["grid_depositos"]["edit"]==1) ? '' : 'disabled' ?>>Recibo Liquidaci&oacute;n </button> 
                </div>                 
                
				</div>                   
            </div>	
            
            <!--
            --- Muestra los campos de los pagares
            --> 
            <div id="pag_pagares" style="display:none;">
                <div class="col50">
                    <fieldset class="adminform">                         
                        <div class="control-group" <?php echo($this->permisos["pag_difPend"]["ver"]==1) ? '' : 'style="display:none;" ' ?>>
                            <div class="control-label">
                            <label for="difPend_Pag">Diferencia pendiente:</label>
                            </div>
                            <div class="controls">
                            <input type="text" name="difPend_Pag" id="difPend_Pag" value="" readonly="readonly"/>                                                        
                            </div>
                        </div>                        
                        <div class="control-group" <?php echo($this->permisos["pag_totalPagado"]["ver"]==1) ? '' : 'style="display:none;" ' ?>>
                            <div class="control-label">
                            <label for="totalPagado_Pag">Total pagado:</label>
                            </div>
                            <div class="controls">
                            <input type="text" name="totalPagado_Pag" id="totalPagado_Pag" value="" readonly="readonly"/>                                                        
                            </div>
                        </div>                                                
                    </fieldset>
                </div>
                <div class="col50">
                    <fieldset class="adminform">                         
                        <div class="control-group" <?php echo($this->permisos["pag_suma"]["ver"]==1) ? '' : 'style="display:none;" ' ?>>
                            <div class="control-label">
                            <label for="suma_Pag">Suma pagares:</label>
                            </div>
                            <div class="controls">
                                <input type="text" name="suma_Pag" id="suma_Pag" value="<?php echo '$'.number_format($totalPagares,2);?>" readonly="readonly"/>                                                        
                            </div>
                        </div>                        
                        <div class="control-group" <?php echo($this->permisos["pag_porPagar"]["ver"]==1) ? '' : 'style="display:none;" ' ?>>
                            <div class="control-label">
                            <label for="porPagar_Pag">Por pagar pagares:</label>
                            </div>
                            <div class="controls">
                                <input type="text" name="porPagar_Pag" id="porPagar_Pag" value="<?php echo '$'.number_format($porPagarPagares,2);?>" readonly="readonly"/>                                                        
                            </div>
                        </div>                                                
                    </fieldset>
                </div>
               <div style="text-align:left; margin: 10px;">  
                    <div id="grid_pagares" <?php echo($this->permisos["grid_pagares"]["ver"]==1) ? '' : 'style="display:none;" ' ?> >
                        <div><strong>Nota: </strong>Para salvar los datos del grid de pagares es necesario antes salvar los datos de cr&eacute;dito.</div>
                        <label><strong>Pagares</strong></label><br><br>
                    <?php                         
                        echo $koolajax->Render();
                        echo $gridPagares->Render();                                         
                    ?>    
                    </div>
               </div>    
            </div>		
            
            <!--
            --- Muestra los campos al numero de credito
            -->
            <div id="pag_nominas" style="display:none;">
                <div>
                    <fieldset class="adminform">                         
                        <legend>Asesor</legend>
                        <div <?php echo($this->permisos["nom_ases_asesor"]["ver"]==1) ? '' : 'style="display:none;" ' ?> class="col30">
                            <label for="ases_asesor_nom">Asesor:</label>
                            <input type="text" name="ases_asesor_nom" id="ases_asesor_nom" value="<?php 
                                                            foreach($this->ColAsesores as $item){
                                                            echo ($item->idDato==$idAsesor) ? $item->nombre : '';
                                                        } ?>" readonly="readonly" style="width:150px;"/>                                                        
                        </div>                        
                        
                        <div <?php echo($this->permisos["nom_ases_comision"]["ver"]==1) ? '' : 'style="display:none;" ' ?> class="col30">
                            <label for="ases_comision_nom" class="comiNegrita">Comisi&oacute;n:</label>
                            <input type="text" name="ases_comision_nom" id="ases_comision_nom" value="<?php echo '$'.number_format($comision,2);?>" onchange="apartEscLiqNomAsesor();" <?php echo ($this->permisos["nom_ases_comision"]["edit"]==1) ? '' : 'readonly'; ?>  class="fmonto"/>                                                        
                        </div>                                                
                           
                        <!--porcentajes asesores-->
                        <div id="porcentajesAsesores" class="col30">
                            <div <?php echo($this->permisos["nom_ases_comision"]["ver"]==1) ? '' : 'style="display:none;" ' ?> class="col30">
                                <label for="porcentajesAses_cl">Porcentaje:</label>                
                                <select id="porcentajesAses_cl" name="porcentajesAses_cl" <?php echo ($this->permisos["nom_ases_comision"]["edit"]==1) ? '' : ''; ?>>                                                  
                                     <?php
                                    foreach ($this->pctAsesores as $item) {                                        
                                        if ($pctIdAses == $item->idPct)
                                            $sel = 'selected';
                                        else
                                            $sel = '';                                                                                
                                        echo '<option ' . $sel . ' value="' . $item->idPct . '">' . $item->titulo . '</option>';                        
                                    }
                                    ?>   
                                </select>
                            </div> 
                        </div>  
                        
                        <div <?php echo($this->permisos["nom_ases_apartado"]["ver"]==1) ? '' : 'style="display:none;" ' ?> class="col30">
                            <label for="ases_apartado_nom">Apartado:</label>
                            <input type="text" name="ases_apartado_nom" id="ases_apartado_nom" value="" readonly="readonly"/>                                                        
                        </div>                                                                        
                        <div <?php echo($this->permisos["nom_ases_fPagoApar"]["ver"]==1) ? '' : 'style="display:none;" ' ?> class="col30">                            
                            <div style="clear: both;">
                                <label for="">Fecha de pago:</label>                                     
                                <?php echo $fPagoApar->Render();?>
                            </div>
                        </div>
                                                                                                                      
						<br/>
                        <div <?php echo($this->permisos["nom_ases_fDescomision"]["ver"]==1) ? '' : 'style="display:none;" ' ?> class="col30">
                            <div style="clear: both;">
                                <label for="">Descomisi&oacute;n:</label>                                     
                                <?php echo $fDescomision->Render();?>
                            </div>
                        </div>
						<br/>
                        <div <?php echo($this->permisos["nom_ases_escritura"]["ver"]==1) ? '' : 'style="display:none;" ' ?> class="col30">
                            <label for="ases_escritura_nom">Escritura:</label>
                            <input type="text" name="ases_escritura_nom" id="ases_escritura_nom" value="" readonly="readonly"/>                                                        
                        </div>  
                        <div <?php echo($this->permisos["nom_ases_fPagoEsc"]["ver"]==1) ? '' : 'style="display:none;" ' ?> class="col30">                            
                            <div style="clear: both;">
                                <label for="">Fecha de pago:</label>                                     
                                <?php echo $fPagoEsc->Render();?>
                            </div>
                        </div>
						<br/>
                        <div <?php echo($this->permisos["nom_ases_liquidacion"]["ver"]==1) ? '' : 'style="display:none;" ' ?> class="col30">
                            <label for="ases_liquidacion_nom">Liquidaci&oacute;n:</label>
                            <input type="text" name="ases_liquidacion_nom" id="ases_liquidacion_nom" value="" readonly="readonly"/>                                                        
                        </div>  
                        <div <?php echo($this->permisos["nom_ases_fPagoLiq"]["ver"]==1) ? '' : 'style="display:none;" ' ?> class="col30">                            
                            <div style="clear: both;">
                                <label for="">Fecha de pago:</label>                                     
                                <?php echo $fPagoLiq->Render();?>
                            </div>
                        </div>
						<br/>
                        <div <?php echo($this->permisos["nom_ases_total"]["ver"]==1) ? '' : 'style="display:none;" ' ?> class="col30">
                            <label for="ases_total_nom">Total pagado:</label>
                            <input type="text" name="ases_total_nom" id="ases_total_nom" value="" readonly="readonly"/>                                                        
                        </div>                         
                    </fieldset>
                </div>
                <div>
                    <fieldset class="adminform">                         
                        <legend>Prospectador</legend>
                        <div <?php echo($this->permisos["nom_pros_prospectador"]["ver"]==1) ? '' : 'style="display:none;" ' ?> class="col30">
                            <label for="pros_prospectador_nom">Prospectador:</label>
                            <input type="text" name="pros_prospectador_nom" id="pros_prospectador_nom" value="<?php 
                                foreach($this->ColProspectadores as $item){
                                        echo ($item->idDato==$idPropectador) ? $item->nombre : '';
                                    }
                                ?>" readonly="readonly" style="width:150px;"/>                                                        
                        </div>                        
                        <div <?php echo($this->permisos["nom_pros_comision"]["ver"]==1) ? '' : 'style="display:none;" ' ?> class="col30">
                            <label for="pros_comision_nom" class="comiNegrita">Comisi&oacute;n:</label>
                            <input type="text" name="pros_comision_nom" id="pros_comision_nom" value="<?php echo '$'.number_format($comisionPros,2); ?>" onchange="apartEscLiqNomPros();" <?php echo ($this->permisos["nom_pros_comision"]["edit"]==1) ? '' : 'readonly'; ?>  class="fmonto"/>                                                        
                        </div>
                         
                        <!--porcentajes prospectadores -->
                        <div id="porcentajesProspectadores" class="col30">
                            <div <?php echo($this->permisos["nom_pros_comision"]["ver"]==1) ? '' : 'style="display:none;" ' ?> class="col30">
                                <label for="porcentajesProsp_cl">Porcentaje:</label>                
                                <select id="porcentajesProsp_cl" name="porcentajesProsp_cl" <?php echo ($this->permisos["nom_pros_comision"]["edit"]==1) ? '' : ''; ?>>                                                  
                                     <?php
                                    foreach ($this->pctProspectadores as $item) {                                        
                                        if ($pctIdProsp == $item->idPct)
                                            $sel = 'selected';
                                        else
                                            $sel = '';
                                        
                                        echo '<option ' . $sel . ' value="' . $item->idPct . '">' . $item->titulo . '</option>';                        
                                    }
                                    ?>   
                                </select>
                            </div> 
                        </div>  
                        
						<br/>
                        <div <?php echo($this->permisos["nom_pros_apartado"]["ver"]==1) ? '' : 'style="display:none;" ' ?> class="col30">
                            <label for="pros_apartado_nom">Apartado:</label>
                            <input type="text" name="pros_apartado_nom" id="pros_apartado_nom" value="" readonly="readonly"/>                                                        
                        </div>                                                                        
                        <div <?php echo($this->permisos["nom_pros_fPagoApar"]["ver"]==1) ? '' : 'style="display:none;" ' ?> class="col30">                            
                            <div style="clear: both;">
                                <label for="">Fecha de pago:</label>                                     
                                <?php echo $fPagoAparPros->Render();?>
                            </div>
                        </div>                                                                                                                                              
						<br/>
                        <div <?php echo($this->permisos["nom_pros_fDescomision"]["ver"]==1) ? '' : 'style="display:none;" ' ?> class="col30">                            
                            <div style="clear: both;">
                                <label for="">Descomisi&oacute;n:</label>                                     
                                <?php echo $fDescomisionPros->Render();?>
                            </div>
                        </div>
						<br/>
                        <div <?php echo($this->permisos["nom_pros_escritura"]["ver"]==1) ? '' : 'style="display:none;" ' ?> class="col30">
                            <label for="pros_escritura_nom">Escritura:</label>
                            <input type="text" name="pros_escritura_nom" id="pros_escritura_nom" value="" readonly="readonly"/>                                                        
                        </div>  
                        <div <?php echo($this->permisos["nom_pros_fPagoEsc"]["ver"]==1) ? '' : 'style="display:none;" ' ?> class="col30">                            
                            <div style="clear: both;">
                                <label for="">Fecha de pago:</label>                                     
                                <?php echo $fPagoEscPros->Render();?>
                            </div>
                        </div>
						<br/>
                        <div <?php echo($this->permisos["nom_pros_total"]["ver"]==1) ? '' : 'style="display:none;" ' ?> class="col30">
                            <label for="pros_total_nom">Total pagado:</label>
                            <input type="text" name="pros_total_nom" id="pros_total_nom" value="" readonly="readonly"/>                                                        
                        </div>                         
                    </fieldset>
                </div>
            </div>
            
            <!--Acabados-->
            
            <div id="pag_acabados" style="display:none;">
              <div style="text-align:left; margin: 10px;">
                    <div class="control-group" <?php echo($this->permisos["total_acabados"]["ver"]==1) ? '' : 'style="display:none;" ' ?> >
                        <div class="control-label">
                        <label for="total_acabados">Total de acabados:</label>
                        </div>
                        <div class="controls">
                            <input type="text" name="total_acabados" id="total_acabados" value="<?php echo '$'.number_format($totalAcabados,2); ?>" readonly="readonly"/>                                                        
                        </div>
                    </div> 
                    <br/>
                    
                    <div id="grid_acabados" <?php echo($this->permisos["grid_acabados"]["ver"]==1) ? '' : 'style="display:none;"' ?> >                    
                        <div><strong>Nota: </strong>Para salvar los datos del grid de acabados es necesario antes salvar los datos generales.</div>
                        <?php                         
                            echo $koolajax->Render();
                            echo $gridAcabados->Render();                                         
                        ?>    
                    </div>                
              </div>
            </div>			
            <div id="pag_servicios" style="display:none;">                                
                <div style="text-align:left; margin: 10px;"> 
                    <div class="control-group" <?php echo($this->permisos["total_servicios"]["ver"]==1) ? ' ' : 'style="display:none;"' ?> >
                        <div class="control-label">
                        <label for="total_servicios">Total de servicios:</label>
                        </div>
                        <div class="controls">
                            <input type="text" name="total_servicios" id="total_servicios" value="<?php echo '$'.number_format($totalServicios,2); ?>" readonly="readonly"/>                                                        
                        </div>
                    </div>
                    <br/>
                    <div id="grid_servicios" <?php echo($this->permisos["grid_servicios"]["ver"]==1) ? ' ' : 'style="display:none;"' ?> >                    
                        <div><strong>Nota: </strong>Para salvar los datos del grid de acabados es necesario antes salvar los datos generales.</div>
                    <?php                         
                        echo $koolajax->Render();
                        echo $gridServicios->Render();                                         
                    ?>    
                    </div>
                </div>
            </div>
            <div id="pag_postventa" style="display:none;">                                
                <div style="text-align:left; margin: 10px;">                     
                    <br/>
                    <div id="grid_postventa" <?php echo($this->permisos["grid_postventa"]["ver"]==1) ? ' ' : 'style="display:none;"' ?> >                    
                        <div><strong>Nota: </strong>Para salvar los datos del grid de acabados es necesario antes salvar los datos generales.</div>
                    <?php                         
                        echo $koolajax->Render();
                        echo $gridPostVenta->Render();                                         
                    ?>    
                    </div>
                </div>
            </div>
            
        </div>
    </div>
        
    <script type="text/javascript">
            function showPage(_pageid)
            {
                document.getElementById("pag_datogenerales").style.display = "none";
                document.getElementById("pag_cliente").style.display = "none";
                document.getElementById("pag_datoscredito").style.display = "none";
                document.getElementById("pag_pagares").style.display = "none";
                document.getElementById("pag_nominas").style.display = "none";
                document.getElementById("pag_acabados").style.display = "none";
                document.getElementById("pag_servicios").style.display = "none";
                document.getElementById("pag_postventa").style.display = "none";

                document.getElementById(_pageid).style.display = "block";
            }		
 
    </script>               
                
        <input type="hidden" name="task" value="departamento.edit" />        
        <input type="hidden" name="id_Dpt" value="<?php echo $this->id_dpt;?>" />        
        <input type="hidden" name="id_Fracc" value="<?php echo $this->idFracc;?>" />        
        <input type="hidden" name="id_DatoGral" id="id_DatoGral" value="<?php echo $this->idDatoGral;?>" />                 
        
        <input type="hidden" name="id_Usuario" value="<?php echo $this->idUsuario;?>" />        
        <input type="hidden" name="fAcceso" value="<?php echo $this->fechaAcceso;?>" />
        
        <input type="hidden" id="price_viv" value="<?php echo $this->precioViv;?>" /><!--Es el valor de la vivienda-->                         
        <input type="hidden" name="id_DatoCto" id="id_DatoCto" value="<?php echo $idDatoCredito;?>" /><!--Es el idCredito-->                         
        <input type="hidden" id="oculto_nombre_Ref" value="<?php echo $idPropectador;?>" /><!--Si se tiene seleccionados referidos mostrar campo referido-->                         
        <input type="hidden" id="total_viv" value="<?php echo $totalVivienda;?>" /><!--Es el valor de la vivienda-->                         
                
        <input type="hidden" id="dg_fApart" value="<?php echo $this->permisos["dg_fApart"]["edit"]; ?>" />
        <input type="hidden" id="dg_fInsc" value="<?php echo $this->permisos["dg_fInsc"]["edit"]; ?>" />
        <input type="hidden" id="dg_fCierre" value="<?php echo $this->permisos["dg_fCierre"]["edit"]; ?>" />
        <input type="hidden" id="dg_fEstatus" value="<?php echo $this->permisos["dg_fEstatus"]["edit"]; ?>" />
        <input type="hidden" id="dg_fEntrega" value="<?php echo $this->permisos["dg_fEntrega"]["edit"]; ?>" />
        <input type="hidden" id="dg_reprog" value="<?php echo $this->permisos["dg_reprog"]["edit"]; ?>" />
        <input type="hidden" id="nom_ases_fPagoApar" value="<?php echo $this->permisos["nom_ases_fPagoApar"]["edit"]; ?>" />
        <input type="hidden" id="nom_ases_fDescomision" value="<?php echo $this->permisos["nom_ases_fDescomision"]["edit"]; ?>" />
        <input type="hidden" id="nom_ases_fPagoEsc" value="<?php echo $this->permisos["nom_ases_fPagoEsc"]["edit"]; ?>" />
        <input type="hidden" id="nom_ases_fPagoLiq" value="<?php echo $this->permisos["nom_ases_fPagoLiq"]["edit"]; ?>" />
        <input type="hidden" id="nom_pros_fPagoApar" value="<?php echo $this->permisos["nom_pros_fPagoApar"]["edit"]; ?>" />
        <input type="hidden" id="nom_pros_fDescomision" value="<?php echo $this->permisos["nom_pros_fDescomision"]["edit"]; ?>" />
        <input type="hidden" id="nom_pros_fPagoEsc" value="<?php echo $this->permisos["nom_pros_fPagoEsc"]["edit"]; ?>" />
        <input type="hidden" id="dg_fdtu" value="<?php echo $this->permisos["dg_fdtu"]["edit"]; ?>" />
        <input type="hidden" id="dg_fNac" value="<?php echo $this->permisos["dg_fNac"]["edit"]; ?>" />
        <input type="hidden" id="es_historico" value="<?php echo $this->historico; ?>" />
        
        <input type="hidden" id="es_preventa_ases" value="<?php echo $esPreventa; ?>" />
        <input type="hidden" id="es_preventa_prosp" value="<?php echo $esPreventaPros; ?>" />                
        
        <input type="hidden" id="idPctAsesor" name="idPctAsesor" value="" />                
        <input type="hidden" id="idPctProspectador" name="idPctProspectador" value="" /> 
        <input type="hidden" id="idsDptsPdf" name="idsDptsPdf" value="" />
        <input type="hidden" id="idAsesorPdf" name="idAsesorPdf" value="<?php echo $idAsesor; ?>" />
        <input type="hidden" id="checkBotonesPdf" name="checkBotonesPdf" value="" />
        <input type="hidden" id="celularCliente" value="<?php echo $celularCliente;?>" />
        
        
        <?php echo JHtml::_('form.token'); ?>                  
</form>

<script>    
     var arrAcabados = {};    
     var arrServicios = {};    
      fPagoApar.registerEvent("OnSelect",apartEscLiqNomAsesor);            
      fPagoEsc.registerEvent("OnSelect",apartEscLiqNomAsesor);
      fPagoLiq.registerEvent("OnSelect",apartEscLiqNomAsesor); 
      fDescomision.registerEvent("OnSelect",apartEscLiqNomAsesor); 
      
      fPagoAparPros.registerEvent("OnSelect",apartEscLiqNomPros);  
      fPagoEscPros.registerEvent("OnSelect",apartEscLiqNomPros); 
      fDescomisionPros.registerEvent("OnSelect",apartEscLiqNomPros);       
                             
        <?php foreach($this->colAcabados as $elm): ?>
            arrAcabados['<?php echo $elm->idDatoCE; ?>'] = '<?php echo $elm->costo; ?>';       
        <?php endforeach; ?>  
      
        <?php foreach($this->colServicios as $elm): ?>
            arrServicios['<?php echo $elm->idDatoCE; ?>'] = '<?php echo $elm->costo; ?>';       
        <?php endforeach; ?> 
            
      function Handle_startInsert(){        
        JQ("#acabadosDptGrid_mt_nr_acabadosDptGrid_mt_c2_input").attr("onchange", "setCostoInput(1)");                
        setCostoInput(1);
      }
      
      function Handle_startInsertServicios(){        
          JQ("#serviciosDptGrid_mt_nr_serviciosDptGrid_mt_c2_input").attr("onchange", "setCostoInput(2)");                    
          setCostoInput(2);
      }
      /*
      function Handle_OnRowStartEditServ(){                 
          JQ("#serviciosDptGrid_mt_r0_serviciosDptGrid_mt_c2_input").attr("onchange", "setCostoInput(4)");                              
           console.log('entre');
      }
      */
      
      function setCostoInput(val){
          //console.log(val);
          var costo = '0';
          if(val==1){
            var idAcabado = document.getElementById("acabadosDptGrid_mt_nr_acabadosDptGrid_mt_c2_input").value;
            costo = arrAcabados[idAcabado];          
            JQ("#acabadosDptGrid_mt_nr_acabadosDptGrid_mt_c3_input").val(costo);          
          }
          if(val==2){
            var idServicio = document.getElementById("serviciosDptGrid_mt_nr_serviciosDptGrid_mt_c2_input").value;
            costo = arrServicios[idServicio];          
            JQ("#serviciosDptGrid_mt_nr_serviciosDptGrid_mt_c3_input").val(costo);             
          }
          /*
          if(val==4){
            var idServicio = document.getElementById("serviciosDptGrid_mt_r0_serviciosDptGrid_mt_c2_input").value;
            costo = arrServicios[idServicio];          
            JQ("#serviciosDptGrid_mt_r0_serviciosDptGrid_mt_c3_input").val(costo); 
            console.log(costo);
          }
          */
      }
      
</script>

<script>               
    var colPctAses = {};
    var colPctProsp = {};               
                    
    <?php foreach($this->pctAsesores as $elm): ?>
        colPctAses[<?php echo $elm->idPct; ?>] = "<?php echo $elm->apartado .',' .$elm->escritura .',' .$elm->liquidacion .',' .$elm->mult;  ?>";       
    <?php endforeach; ?>          
    
    <?php foreach($this->pctProspectadores as $elm): ?>
        colPctProsp[<?php echo $elm->idPct; ?>] = "<?php echo $elm->apartado.','.$elm->escritura.','.$elm->mult; ?>";       
    <?php endforeach; ?>              
    
    //Mensajes
    var SMSEsphabit = [];
    SMSEsphabit[1] = '<?php echo $SMS1; ?>';
    SMSEsphabit[2] = '<?php echo $SMS2; ?>';
    SMSEsphabit[3] = '<?php echo $SMS3; ?>';
    SMSEsphabit[4] = '<?php echo $SMS4; ?>';
</script>    