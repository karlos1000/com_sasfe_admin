<?php
// no direct access
defined('_JEXEC') or die;

JHtml::addIncludePath(JPATH_COMPONENT.'/helpers/html');
jimport('joomla.filesystem.file');
jimport('joomla.filesystem.folder');
JHtml::_('behavior.tooltip');
JHtml::_('behavior.multiselect');
JHtml::_('behavior.modal');
JHTML::_('behavior.formvalidation');
$timeZone = SasfehpHelper::obtDateTimeZone();
$colUsrGtesVentas = SasfehpHelper::obtUsuariosJoomlaPorGrupo(11); //Obtener gerente de ventas de la tabla de usuarios
$colAsesores = SasfehpHelper::obtUsuariosJoomlaPorGrupo(18); //Obtener agentes de venta de la tabla de usuarios
$colDireccion = SasfehpHelper::obtUsuariosJoomlaPorGrupo(10); //Obtener direccion de la tabla de usuarios
$datoBolsaCredito = SasfehpHelper::obtInfoCreditosBolsaAutomatico(1); //Datos de la bolsa de creditos 
$bolsaCreditos = (count($datoBolsaCredito)>0)?$datoBolsaCredito->creditos:0;
$datoAutomaticosCredito = SasfehpHelper::obtInfoCreditosBolsaAutomatico(2); //Datos credito de los automaticos
$automaticos = (count($datoAutomaticosCredito)>0)?$datoAutomaticosCredito->creditos:0;
$activoAutomaticos = (isset($datoAutomaticosCredito->activo))?$datoAutomaticosCredito->activo:0;

$sumaInactivos = 0;
$credInacGerentes = 0;
$credInacAsesores = 0;
$credInacDireccion = 0;
$arrIdsInacGerentes = array();
$arrIdsInacAsesores = array();
$arrIdsInacDireccion = array();
// $colUsrGtesVentas = SasfehpHelper::obtColElemPorIdCatalogo(1); //Obtener gerentes de la tabla datos_catalogos
// $gteVentasId = "";
// if(in_array("11", $this->groups)){
//     $gteVentasId = $this->user->id;
//     //obtener los asesores por el id del gerente de ventas     
//     $colAsesores = SasfehpHelper::obtColAsesoresAgtVentaXIdGteVentas($gteVentasId);
// }else{
//     //Obtener todos los asesores ya que esta como super usuario o direccion
//     $colAsesores = SasfehpHelper::obtColElemPorIdCatalogo(3); //Agentes de venta
// }

//Obtener la coleccion
// $colGerentesVenta = SasfehpHelper::obtMensajesSMSPorTipoId(1);

// echo "<pre>";
// print_r($datoAutomaticosCredito);
// print_r($colDireccion);
// echo "</pre>";
?>
<style type="text/css">  
    .header-fixed {
        width: 70% 
    }

    .header-fixed > thead,
    .header-fixed > tbody,
    .header-fixed > thead > tr,
    .header-fixed > tbody > tr,
    .header-fixed > thead > tr > th,
    .header-fixed > tbody > tr > td {
        display: block;
    }

    .header-fixed > tbody > tr:after,
    .header-fixed > thead > tr:after {
        content: ' ';
        display: block;
        visibility: hidden;
        clear: both;
    }

    .header-fixed > tbody {
        overflow-y: auto;
        height: 370px;
    }

    .header-fixed > tbody > tr > td,
    .header-fixed > thead > tr > th {
        width: 20%;
        float: left;
    }                                 
    table.table.table-striped {
        font-size: 11px;
    }
    .table th, .table td {
        padding: 3px;
    }
    .input_credito{
        width: 80px;
    }
    .tbl_referidos_alt{
        height: 270px !important;
    }
</style>
<form class="form-validate form-horizontal" action="<?php echo JRoute::_('index.php?option=com_sasfe&view=smsconfigcreditos'); ?>" method="post" name="adminForm" id="adminForm">
    
    <div class="alert alert-info">
      <strong>Nota:</strong> 
      <div>1.- Para salvar los cambios solo debe de escribir la cantidad de cr&eacute;ditos y dar click afuera del campo.</div>
      <div>2.- Es posible reducir cr&eacute;ditos poniendo el n&uacute;mero en negativo.</div>
    </div>
    <div>        
        <fieldset class="adminform">                
        <?php if(in_array("8", $this->groups) || in_array("10", $this->groups) || in_array("11", $this->groups)){ ?>
        <?php if(in_array("8", $this->groups) || in_array("10", $this->groups)){ ?><br/><br/><?php }?>
        <!-- <legend>Reporte productividad de prospectos</legend> -->

        <div style="margin-top:-40px;">
            <div class="control-group">    
                <div class="control-label" style="text-transform: uppercase;">                            
                    <label for="bolsaCreditos"><b>Bolsa de Cr&eacute;ditos:</b></label>
                </div>
                <div class="controls">
                    <input type="text" id="bolsaCreditos" value="<?php echo $bolsaCreditos;?>" class="input_credito" readonly>                
                    <input type="text" id="aumentarBolsaCreditos" idCA="1"  class="masCredBolsaAutomatico" placeholder="Aumentar Cr&eacute;ditos">
                </div>
            </div>
            <!--
            <br/>
            <div class="control-group">    
                <div class="control-label">                            
                    <label for="automaticos">Autom&aacute;ticos:</label>
                </div>
                <div class="controls">
                    <input type="text" id="automaticos" value="?php echo $automaticos;?>" class="input_credito" readonly> 
                    <input type="text" id="aumentarAutomaticos" idCA="2" class="masCredBolsaAutomatico" placeholder="Aumentar Autom&aacute;ticos">
                </div>
            </div>                    
            -->
        </div>
        <br/>        
        <!-- <div class="control-group col50">
            <div class="control-label">
                <button type="button" id="btn_buscarProsp" style="width:100px;">Buscar</button>
            </div>
            <div id="loading_btn_buscarProsp" style="display:none;"><img src="<?php echo JURI::root().'media/com_sasfe/images/loading_transparent.gif'; ?>" width="32"></div>
        </div>   -->
            
            
          <h2>Autom&aacute;ticos:</h2>
          <table class="table table-striped header-fixed">
            <thead>
              <tr>                
                <th>Cr&eacute;ditos Actuales</th>
                <th>Aumentar Cr&eacute;ditos</th>
                <th>Estatus</th>
              </tr>
            </thead>
            <tbody style="height:50px;">  
                <tr>
                    <td>
                        <input type="text" id="automaticos" value="<?php echo $automaticos;?>" class="input_credito" readonly> 
                    </td>
                    <td>
                        <input type="text" id="aumentarAutomaticos" idCA="2" class="masCredBolsaAutomatico" placeholder="Aumentar Autom&aacute;ticos">
                    </td>
                    <td style="text-align:center;">
                        <input type="checkbox" id="activo_automatico" class="activo_sms" value="<?php echo $activoAutomaticos; ?>" <?php echo ($activoAutomaticos==1)?'checked':''; ?> />                        
                    </td>
                </tr>
            </tbody>
          </table>    

        
          <h2>Referidos</h2>
          <table class="table table-striped header-fixed">
            <thead>
              <tr>
                <th>Nombre</th>
                <th>Cr&eacute;ditos Actuales</th>
                <th>Aumentar Cr&eacute;ditos</th>
                <th>Estatus</th>
              </tr>
            </thead>
            <tbody style="height:200px;">
             <?php  
             foreach ($colUsrGtesVentas as $elemGte){ 
                //Buscar si tiene creditos
                $datoCredUsuario = SasfehpHelper::checkCreditoPorUsuarioIdSMS($elemGte->id);
                $creditoActual = 0;
                $activoGte = 0;
                if(count($datoCredUsuario)>0){
                    $creditoActual = $datoCredUsuario->creditos;
                    $activoGte = $datoCredUsuario->activo;
                    if($activoGte==0 && $creditoActual>0){
                        $credInacGerentes += $creditoActual;
                        $arrIdsInacGerentes[] = $elemGte->id;
                    }
                }                
              ?>   
              <tr>
                <td><?php echo $elemGte->name; ?></td>
                <td>
                    <input type="text" id="creditoIdGte_<?php echo $elemGte->id;?>" value="<?php echo $creditoActual;?>" class="input_credito" readonly>
                </td>
                <td>
                    <input type="text" id="aumCreditoIdGte_<?php echo $elemGte->id;?>" idGte="<?php echo $elemGte->id;?>" placeholder="" class="input_credito masCredGerente">
                </td>
                <td style="text-align:center;">
                    <input type="checkbox" id="activoCreditoIdGte_<?php echo $elemGte->id;?>" idActivo="<?php echo $elemGte->id;?>" class="activo_sms" value="<?php echo $activoGte; ?>" <?php echo ($activoGte==1)?'checked':''; ?> />                        
                </td>
              </tr>              
              <?php } ?>
            </tbody>
          </table>
        
                
          <h2>Promociones</h2>
          <table class="table table-striped header-fixed">
            <thead>
              <tr>
                <th>Nombre</th>
                <th>Cr&eacute;ditos Actuales</th>
                <th>Aumentar Cr&eacute;ditos</th>
                <th>Estatus</th>
              </tr>
            </thead>
            <tbody>
             <?php  
             foreach ($colAsesores as $elemAsesor){ 
                //Buscar si tiene creditos
                $datoCredUsuario = SasfehpHelper::checkCreditoPorUsuarioIdSMS($elemAsesor->id);
                $creditoActual = 0;
                $activoAsesor = 0;
                if(count($datoCredUsuario)>0){
                    $creditoActual = $datoCredUsuario->creditos;
                    $activoAsesor = $datoCredUsuario->activo;
                    if($activoAsesor==0 && $creditoActual>0){
                        $credInacAsesores += $creditoActual;
                        $arrIdsInacAsesores[] = $elemAsesor->id;
                    }
                }
              ?>   
              <tr>
                <td><?php echo $elemAsesor->name; ?></td>
                <td>
                    <input type="text" id="creditoIdAsesor_<?php echo $elemAsesor->id;?>" value="<?php echo $creditoActual;?>" class="input_credito" readonly>
                </td>
                <td>
                    <input type="text" id="aumCreditoIdAsesor_<?php echo $elemAsesor->id;?>" idAsesor="<?php echo $elemAsesor->id;?>" placeholder="" class="input_credito masCredAsesor">
                </td>
                <td style="text-align:center;">
                    <input type="checkbox" id="activoCreditoIdAsesor_<?php echo $elemAsesor->id;?>" idActivo="<?php echo $elemAsesor->id;?>" class="activo_sms" value="<?php echo $activoAsesor; ?>" <?php echo ($activoAsesor==1)?'checked':''; ?> />                        
                </td>
              </tr>              
              <?php } ?>
            </tbody>
          </table>        

          <br/><br/>  
          <h2>Direcci&oacute;n (Referidos y Promociones)</h2>
          <table class="table table-striped header-fixed">
            <thead>
              <tr>
                <th>Nombre</th>
                <th>Cr&eacute;ditos Actuales</th>
                <th>Aumentar Cr&eacute;ditos</th>
                <th>Estatus</th>
              </tr>
            </thead>
            <tbody>
             <?php  
             foreach ($colDireccion as $elemDir){ 
                //Buscar si tiene creditos
                $datoCredUsuario = SasfehpHelper::checkCreditoPorUsuarioIdSMS($elemDir->id);
                $creditoActual = 0;
                $activoDir = 0;
                if(count($datoCredUsuario)>0){
                    $creditoActual = $datoCredUsuario->creditos;
                    $activoDir = $datoCredUsuario->activo;
                    if($activoDir==0 && $creditoActual>0){
                        $credInacDireccion += $creditoActual;
                        $arrIdsInacDireccion[] = $elemDir->id;
                    }
                }
              ?>   
              <tr>
                <td><?php echo $elemDir->name; ?></td>
                <td>
                    <input type="text" id="creditoIdDir_<?php echo $elemDir->id;?>" value="<?php echo $creditoActual;?>" class="input_credito" readonly>
                </td>
                <td>
                    <input type="text" id="aumCreditoIdDir_<?php echo $elemDir->id;?>" idDireccion="<?php echo $elemDir->id;?>" placeholder="" class="input_credito masCredDireccion">
                </td>
                <td style="text-align:center;">
                    <input type="checkbox" id="activoCreditoIdDir_<?php echo $elemDir->id;?>" idActivo="<?php echo $elemDir->id;?>" class="activo_sms" value="<?php echo $activoDir; ?>" <?php echo ($activoDir==1)?'checked':''; ?> />                        
                </td>
              </tr>              
              <?php } ?>
            </tbody>
          </table>        
        
        <?php } ?>
        </fieldset>
    </div>
    <?php 
        // echo "inactivos Gerentes: ".$credInacGerentes.'<br/>';
        // echo "inactivos Gerentes: ".$credInacAsesores.'<br/>';
        // echo "<pre>";
        // print_r($arrIdsInacGerentes);
        // print_r($arrIdsInacAsesores);
        // echo "</pre>";
    ?>
    
    <div>        
        <input type="hidden" name="task" value="" />
        <input type="hidden" name="boxchecked" value="0" />        
        <?php echo JHtml::_('form.token'); ?>
        <input type="hidden" id="rutaCalendario" value="<?php echo JURI::root().'media/com_sasfe/images/calendar.gif'; ?>" />
        <input type="hidden" name="usuarioIdGteVenta" value="<?php echo $gteVentasId; ?>" />                
        <input type="hidden" id="path" value="<?php echo $this->pathUrl;?>" />
        <!-- <input type="hidden" id="idsUsuariosGtes" value="?php echo implode(',', $arrIdsInacGerentes); ?>" /> -->
    </div>

</form>


<div style="background-color: #FFF;width: 100%;height: 100%;position: fixed;top: 0;left: 0;opacity: .5;z-index: 9999; display:none;" id="cont_loading">
<span style="display: block;text-align: center;line-height: 30px;font-weight: bold;background: #8eff92;font-size: medium;position: relative;top: 90px;">ESPERANDO PROCESO <img src="<?php echo JURI::root().'media/com_sasfe/images/loading_transparent.gif'; ?>" style="width:22px;" /></span>
</div>