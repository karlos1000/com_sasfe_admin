<?php
// no direct access
defined('_JEXEC') or die;

JHtml::addIncludePath(JPATH_COMPONENT.'/helpers/html');
require_once(JPATH_COMPONENT.'/helpers/sasfehp.php' );

JHtml::_('behavior.tooltip');
JHtml::_('behavior.multiselect');
JHtml::_('behavior.modal');
$timeZone = SasfehpHelper::obtDateTimeZone();

$ordering	= $this->escape($this->state->get('list.ordering'));
$direction	= $this->escape($this->state->get('list.direction'));
$opcionTipoEnvio    = $this->state->get('filter.opcionTipoEnvio');
$fechaDel = $this->escape($this->state->get('filter.fechaDel'));
$fechaAl = $this->escape($this->state->get('filter.fechaAl'));
$opcionAgentesVentaSMS = $this->state->get('filter.opcionAgentesVentaSMS');

include_once JPATH_ADMINISTRATOR . DIRECTORY_SEPARATOR . 'components' . DIRECTORY_SEPARATOR . 'com_sasfe' . DIRECTORY_SEPARATOR . 'common' . DIRECTORY_SEPARATOR . 'KoolControls' . DIRECTORY_SEPARATOR . 'KoolAjax' . DIRECTORY_SEPARATOR . 'koolajax.php';
include_once JPATH_ADMINISTRATOR . DIRECTORY_SEPARATOR . 'components' . DIRECTORY_SEPARATOR . 'com_sasfe' . DIRECTORY_SEPARATOR . 'common' . DIRECTORY_SEPARATOR . 'KoolControls' . DIRECTORY_SEPARATOR . 'KoolCalendar' . DIRECTORY_SEPARATOR . 'koolcalendar.php';
$calLangueaje = JPATH_SITE.'/administrator/components/com_sasfe/common/KoolControls/KoolCalendar/localization/es.xml';
// $fechaDel = ($fechaDel!="") ?$fechaDel :$timeZone->fechaF2;//define fecha del
// $fechaAl = ($fechaAl!="") ?$fechaAl :$timeZone->fechaF2;//define fecha al

//Fecha Del
if($fechaDel!=""){
   // $fechaDel = SasfehpHelper::conversionFecha($fechaDel);
   // $fechaDel = SasfehpHelper::diasPrevPos(30, $fechaDel, "prev");   
    $fechaDel = $fechaDel;
}else{
  $fechaDel = SasfehpHelper::diasPrevPos(30, $timeZone->fecha, "prev");  
}
$fechaAl = ($fechaAl!="") ?$fechaAl :$timeZone->fechaF2;//define fecha al
?>

<div class="alert alert-info">
  <strong>Nota:</strong>
  <div>1.- Por defecto se muestran los registros de hoy a 1 mes hac&iacute;a atr&aacute;s.</div>  
</div>
<form action="<?php echo JRoute::_('index.php?option=com_sasfe&view=historialsms'); ?>" method="post" name="adminForm" id="adminForm">
    
    <div id="filter-bar" class="btn-toolbar">
            <!-- <div class="filter-search btn-group pull-left">
                    <label for="filter_search" class="element-invisible"><?php echo JText::_('Search');?></label>
                    <input type="text" name="filter_search" id="filter_search" placeholder="<?php echo JText::_('Search'); ?>" value="<?php echo $this->escape($this->state->get('filter.search')); ?>" class="hasTooltip" title="<?php echo JHtml::tooltipText('Search'); ?>" />
            </div>
            <div class="btn-group pull-left">
                    <button type="submit" class="btn hasTooltip" title="<?php echo JHtml::tooltipText('Search'); ?>"><i class="icon-search"></i></button>
                    <button type="button" class="btn hasTooltip" title="<?php echo JHtml::tooltipText('Clear'); ?>" onclick="document.getElementById('filter_search').value='';this.form.submit();"><i class="icon-remove"></i></button>
            </div> -->
            <div class="btn-group pull-right hidden-phone">
                    <label for="limit" class="element-invisible"><?php echo JText::_('JFIELD_PLG_SEARCH_SEARCHLIMIT_DESC');?></label>
                    <?php echo $this->pagination->getLimitBox(); ?>
            </div>
    </div>
    <br/>

    
    <div class="filter-select fltrt">        
        <label for="filter_fechaDel" style="display: inline-block;">Del: </label>
        <input type="text" name="filter_fechaDel" id="filter_fechaDel" value="<?php echo $fechaDel; ?>" class="required" style="width:100px;" readonly/>        
        <span class="separaFiltro"></span>

        <label for="filter_fechaAl" style="display: inline-block;">Al: </label>
        <input type="text" name="filter_fechaAl" id="filter_fechaAl" value="<?php echo $fechaAl; ?>" class="required" style="width:100px;" readonly/>        
        <span class="separaFiltro"></span>

        <label for="filter_tipoenvio" style="display: inline-block;">Tipo: </label>
        <select name="filter_tipoenvio" id="filter_tipoenvio" class="inputbox" style="width:10%;">
           <option value=""><?php echo JText::_('-Todos-');?></option>
           <?php echo JHtml::_('select.options', JHtml::_('modules.opcionTipoEnvio'), 'value', 'text', $opcionTipoEnvio, true);?>
        </select>
        <label for="filter_agtev" style="display: inline-block;">Asesor: </label>
        <select name="filter_agtev" id="filter_agtev" class="hasTooltip" title="<?php echo JHtml::tooltipText('Agente Ventas'); ?>" style="width:170px;">
            <option value=""><?php echo JText::_('-Todos-');?></option>
            <?php echo JHtml::_('select.options', JHtml::_('modules.opcionAgentesVentaSMS'), 'value', 'text', $opcionAgentesVentaSMS, true);?>
        </select>
        

        <?php if(in_array("11", $this->groups)){ ?>        
        <!-- <label for="filter_atenderevento" style="display: inline-block;">Ver: </label>
        <select name="filter_agtev" id="filter_agtev" class="hasTooltip" title="<?php echo JHtml::tooltipText('Agente Ventas'); ?>" style="width:170px;">
            <option value=""><?php echo JText::_('-Todos Agt. Ventas-');?></option>
            <?php echo JHtml::_('select.options', JHtml::_('modules.opcionAgentesVenta'), 'value', 'text', $opcionAgentesVenta, true);?>
        </select>   -->
        <?php } ?>
        
        <span class="separaFiltro"></span>
        <button class="btn btn-small button-new inputbox" onchange="this.form.submit()" title="Filtrar" style="position:relative;top:-5px;">Filtrar</button>
        <button type="button" id="limpiarFiltros" class="btn hasTooltip" title="Clear" style="position:relative;top:-5px;"><i class="icon-remove"></i></button>


        <?php if(in_array("18", $this->groups) || in_array("11", $this->groups)){ ?>
            <!-- <button type="button" class="btn btn-small button-new inputbox" onclick="exportEventos();" title="Exportar" style="position:relative;top:-5px;">Exportar</button> -->
        <?php } ?>        
    </div>
        
    <table class="table table-striped">
        <thead>
            <tr>
                <!--
                <th width="5" class="nowrap center">                    
                    ?php echo JHtml::_('grid.sort', 'ID', 'idEvenCom', $direction, $ordering); ?>                    
                </th>
                <th width="20" class="nowrap center">
                    ?php echo JText::_('Sel'); ?>                    
                </th>
                -->                
                <th class="nowrap center">
                    <?php 
                        echo 'Fecha';
                        //echo JHtml::_('grid.sort', 'Fecha y Hora', 'fechaCreacion', $direction, $ordering);  
                    ?>
                </th>                
                <th class="nowrap center">
                    <?php 
                        //echo JHtml::_('grid.sort', 'Asesor', 'agtVentasId', $direction, $ordering); 
                        echo 'Asesor';
                    ?>  
                </th>
                <th class="nowrap center">
                    <?php 
                        //echo JHtml::_('grid.sort', 'Cliente', 'datoClienteId', $direction, $ordering); 
                        echo 'Cliente';
                    ?>  
                </th>
                <th class="nowrap center">
                    <?php 
                        //echo JHtml::_('grid.sort', 'Tipo', 'tipoEnvio', $direction, $ordering); 
                        echo 'Tipo';
                    ?>  
                </th>
                <th width="450" class="nowrap center">
                    <?php 
                        //echo JHtml::_('grid.sort', 'Mensaje', 'mensajeId', $direction, $ordering); 
                        echo 'Mensaje';
                    ?>  
                </th>                
            </tr>
        </thead>
        <tbody>            
            <?php foreach ($this->items as $i => $item): ?>                
                <tr class="row<?php echo $i % 2; ?>">
                    <!--
                    <td>
                        ?php echo $item->idEvenCom; ?>
                    </td>
                    <td>
                        ?php echo JHtml::_('grid.id', $i, $item->idEvenCom); ?>
                    </td>
                    -->                    
                    <td class="center">                        
                        <?php
                            // echo $item->fechaCreacion;
                            echo SasfehpHelper::conversionFechaF3($item->fechaCreacion);
                        ?>
                    </td>                    
                    <td class="center">
                        <?php echo $item->asesor; ?>
                    </td>
                    <td class="center">                        
                        <?php //echo $item->cliente; //$item->nombre." ".$item->aPaterno." ".$item->aManterno; ?>
                        <?php 
                            if($item->tipoEnvio==2){
                                $datosProspecto = SasfehpHelper::obtDatosProspectoPorId($item->datoClienteId);
                                if(isset($datosProspecto->idDatoProspecto)){
                                    echo $datosProspecto->nombre." ".$datosProspecto->aPaterno." ".$datosProspecto->aManterno;
                                }
                            }else{
                                $datosCliente = SasfehpHelper::obtDatosClientePorId($item->datoClienteId);
                               if(isset($datosCliente->idDatoCliente)){
                                    echo $datosCliente->nombre." ".$datosCliente->aPaterno." ".$datosCliente->aManterno;   
                               }
                            }
                        ?>
                    </td>
                    <td class="center">
                        <?php
                            switch ($item->tipoEnvio) {
                                case 1: echo "Mensaje"; break;
                                case 2: echo "Promoci&oacute;n"; break;
                                case 3: echo "Autom&aacute;tico"; break;
                            }
                        ?>
                    </td>                    
                    <td class="left">                            
                        <?php  
                            // echo $item->mensaje;
                            $lngComentario = strlen($item->mensaje);
                            if($lngComentario>=100){
                                echo substr($item->mensaje, 0, 100).'...<br/><a href="javascript:void(0);" data-toggle="tooltip" title="'.$item->mensaje.'">Ver más</a>';
                            }else{
                                echo $item->mensaje;
                            }
                        ?>
                    </td>

                </tr>                
            <?php endforeach; ?>
                         
        </tbody>
        <tfoot>
            <tr>
                <td colspan="10"><?php echo $this->pagination->getListFooter(); ?></td>
            </tr>
        </tfoot>        			
    </table>
    <div>        
        <input type="hidden" name="task" value="" />
        <input type="hidden" name="boxchecked" value="0" />        
        <input type="hidden" name="filter_order" value="<?php echo $ordering; ?>" />
        <input type="hidden" name="filter_order_Dir" value="<?php echo $direction; ?>" />
        <?php echo JHtml::_('form.token'); ?>

        <input type="hidden" id="rutaCalendario" value="<?php echo JURI::root().'media/com_sasfe/images/calendar.gif'; ?>" />
        <input type="hidden" id="fechaHoy" value="<?php echo $timeZone->fechaF2; ?>">

        <input type="hidden" name="idHidEvento" id="idHidEvento">
        <input type="hidden" id="loading_img" value="<?php echo $this->imgLoading; ?>" />     
        
        <?php $hidFechaDel = SasfehpHelper::diasPrevPos(30, $timeZone->fecha, "prev"); ?>
        <input type="hidden" id="hidFechaDel" value="<?php echo $hidFechaDel; ?>">
        <input type="hidden" id="hidFechaAl" value="<?php echo $fechaAl; ?>">
    </div> 
    
</form>