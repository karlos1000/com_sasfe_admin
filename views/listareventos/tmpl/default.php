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
$opcionTipoEventos    = $this->state->get('filter.opcionTipoEventos');
$fechaDel = $this->escape($this->state->get('filter.fechaDel'));
$fechaAl = $this->escape($this->state->get('filter.fechaAl'));
$opcionAtenderEvento    = $this->state->get('filter.opcionAtenderEvento');
$opcionAgentesVenta = $this->state->get('filter.opcionAgentesVenta');

include_once JPATH_ADMINISTRATOR . DIRECTORY_SEPARATOR . 'components' . DIRECTORY_SEPARATOR . 'com_sasfe' . DIRECTORY_SEPARATOR . 'common' . DIRECTORY_SEPARATOR . 'KoolControls' . DIRECTORY_SEPARATOR . 'KoolAjax' . DIRECTORY_SEPARATOR . 'koolajax.php';
include_once JPATH_ADMINISTRATOR . DIRECTORY_SEPARATOR . 'components' . DIRECTORY_SEPARATOR . 'com_sasfe' . DIRECTORY_SEPARATOR . 'common' . DIRECTORY_SEPARATOR . 'KoolControls' . DIRECTORY_SEPARATOR . 'KoolCalendar' . DIRECTORY_SEPARATOR . 'koolcalendar.php';
$calLangueaje = JPATH_SITE.'/administrator/components/com_sasfe/common/KoolControls/KoolCalendar/localization/es.xml';
$fechaDel = ($fechaDel!="") ?$fechaDel :$timeZone->fechaF2;//define fecha del
$fechaAl = ($fechaAl!="") ?$fechaAl :$timeZone->fechaF2;//define fecha al


//Reglas
//1.- Por defecto listar los eventos del dia YA
//2.- Agregar filtros de rango de fechas YA
//3.- Agregar al campo de fecha la hora YA
?>
<div class="notesUlMozi">      
</div>
<form action="<?php echo JRoute::_('index.php?option=com_sasfe&view=listareventos&opc='.$this->opc); ?>" method="post" name="adminForm" id="adminForm">
    
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

        <label for="filter_tipoevento" style="display: inline-block;">Ver: </label>
        <select name="filter_tipoevento" id="filter_tipoevento" class="inputbox" style="width:10%;">
           <option value=""><?php echo JText::_('-Todos-');?></option>
           <?php echo JHtml::_('select.options', JHtml::_('modules.opcionTipoEventos'), 'value', 'text', $opcionTipoEventos, true);?>
        </select>

        <label for="filter_atenderevento" style="display: inline-block;">Ver: </label>
        <select name="filter_atenderevento" id="filter_atenderevento" class="inputbox" style="width:10%;">
           <!-- <option value=""><?php echo JText::_('-Todos-');?></option> -->
           <?php echo JHtml::_('select.options', JHtml::_('modules.opcionAtenderEvento'), 'value', 'text', $opcionAtenderEvento, true);?>
        </select>

        <?php if(in_array("11", $this->groups)){ ?>        
        <label for="filter_atenderevento" style="display: inline-block;">Ver: </label>
        <select name="filter_agtev" id="filter_agtev" class="hasTooltip" title="<?php echo JHtml::tooltipText('Agente Ventas'); ?>" style="width:170px;">
            <option value=""><?php echo JText::_('-Todos Agt. Ventas-');?></option>
            <?php echo JHtml::_('select.options', JHtml::_('modules.opcionAgentesVenta'), 'value', 'text', $opcionAgentesVenta, true);?>
        </select>        
        <?php } ?>
        
        <span class="separaFiltro"></span>
        <button class="btn btn-small button-new inputbox" onchange="this.form.submit()" title="Filtrar" style="position:relative;top:-5px;">Filtrar</button>
        <button type="button" id="limpiarFiltros" class="btn hasTooltip" title="Clear" style="position:relative;top:-5px;"><i class="icon-remove"></i></button>
        <?php if(in_array("18", $this->groups) || in_array("11", $this->groups)){ ?>
            <!-- <a href="javascript:void(0);" onclick="exportEventos();">Exportar</a>       -->
            <button type="button" class="btn btn-small button-new inputbox" onclick="exportEventos();" title="Exportar" style="position:relative;top:-5px;">Exportar</button>
        <?php } ?>        
    </div>
        
    <table class="table table-striped">
        <thead>
            <tr>
                <!--
                <th width="5" class="nowrap center">                    
                    <?php echo JHtml::_('grid.sort', 'ID', 'idEvenCom', $direction, $ordering); ?>                    
                </th>
                <th width="20" class="nowrap center">
                    <?php echo JText::_('Sel'); ?>                    
                </th>
                -->
                <?php if(!in_array("11", $this->groups)){ ?>
                <th class="nowrap center">
                    Descargas
                </th>
                <?php } ?>
                <th class="nowrap center">
                    <?php
                        echo 'Fecha Creaci&oacute;n';
                    ?>
                </th>
                <th class="nowrap center">
                    <?php 
                        //echo JHtml::_('grid.sort', 'Fecha y Hora', 'fechaHora', $direction, $ordering); 
                        echo 'Fecha Evento';
                    ?>  
                </th>
                <th class="nowrap center">
                    <?php 
                        //echo JHtml::_('grid.sort', 'Usuario', 'nombre', $direction, $ordering); 
                        echo 'Prospecto';
                    ?>  
                </th>
                <th class="nowrap center">
                    <?php 
                        //echo JHtml::_('grid.sort', 'Tipo', 'opcionId', $direction, $ordering); 
                        echo 'Tipo';
                    ?>  
                </th>
                <th width="250" class="nowrap center">
                    <?php 
                        //echo JHtml::_('grid.sort', 'Comentario', 'comentario', $direction, $ordering); 
                        echo 'Comentario Evento';
                    ?>  
                </th>
                <?php if(!in_array("11", $this->groups)){ ?>
                <th class="nowrap center">
                    <?php echo "Acci&oacute;n"; ?>
                </th>
                <?php } ?>
                <th class="nowrap center">
                    <?php echo "Atendido"; ?>
                </th>
                <th width="250" class="nowrap center">
                    <?php                     
                        echo 'Comentario Atendido';
                    ?>  
                </th>
                <?php if(in_array("11", $this->groups) || in_array("8", $this->groups) || in_array("10", $this->groups)){ ?>
                <th class="nowrap center">
                    <?php 
                        // echo JHtml::_('grid.sort', 'Agente', 'agtVentasId', $direction, $ordering); 
                        echo 'Agente';
                    ?>
                </th>
                <?php } ?>
            </tr>
        </thead>
        <tbody>            
            <?php foreach ($this->items as $i => $item): ?>
                <?php 
                    //Para los agentes de venta ir a la parte de edicion
                    if(in_array("18", $this->groups)){
                        $link = JRoute::_('index.php?option=com_sasfe&view=prospecto&layout=edit&id=' . $item->datoProspectoId.'&opc=3');
                    }else{
                        $link = JRoute::_('index.php?option=com_sasfe&view=prospecto&layout=sololectura&id=' . $item->datoProspectoId);    
                    }
                ?>
                <tr class="row<?php echo $i % 2; ?>">
                    <!--
                    <td>
                        <?php echo $item->idEvenCom; ?>
                    </td>
                    <td>
                        <?php echo JHtml::_('grid.id', $i, $item->idEvenCom); ?>
                    </td>
                    -->
                    <?php if(!in_array("11", $this->groups)){ ?>                    
                    <td class="center">
                        <?php if($item->tiempoRecordatorioId!=""){ ?>
                        <a href="javascript:void(0);" idHidEv="<?php echo $item->idMovPros;?>" class="selIdHidEv">Outlook</a>
                        <?php } ?>
                    </td>
                    <?php } ?>
                    <td class="center">
                        <?php echo SasfehpHelper::conversionFechaF3($item->fechaCreacion); ?>
                    </td>
                    <td class="center">
                        <?php 
                            if($item->opcionId==1){
                                echo SasfehpHelper::conversionFechaF3($item->fechaHora);     
                            }
                            if($item->opcionId==2){
                                echo SasfehpHelper::conversionFechaF3($item->fechaCreacion);     
                            }
                        ?>
                    </td>
                    <td class="center">
                        <a href="<?php echo $link; ?>">
                            <?php echo $item->nombre." ".$item->aPaterno." ".$item->aManterno; ?>
                        </a>
                    </td>
                    <td class="center">
                        <?php 
                            echo $item->tipoEvento;
                        ?>
                    </td>                    
                    <td class="left">                            
                        <?php  
                            // echo $item->comentarioevcom;   
                            $lngComentarioEvcom = strlen($item->comentarioevcom);
                            if($lngComentarioEvcom>=30){
                                echo substr($item->comentarioevcom, 0, 30).'...<br/><a href="javascript:void(0);" data-toggle="tooltip" title="'.$item->comentarioevcom.'">Ver más</a>';
                            }else{
                            echo $item->comentarioevcom;                           
                            }
                        ?>
                    </td>
                    <?php if(!in_array("11", $this->groups)){ ?>
                    <td class="center">
                        <?php 
                        if($item->atendido!=1){ ?>
                        <a href="#" data-toggle="modal" data-target="#popup_atenderevento" idMovPros="<?php echo $item->idMovPros;?>" class="selAtenderEvento">Atender</a>
                        <?php } ?>
                    </td>
                    <?php } ?>
                    <td class="center">                                                
                        <?php $atendido = ($item->atendido==1) ?"checked" :""; ?>
                        <input type="checkbox" value="<?php echo $item->atendido;?>" <?php echo $atendido;?> disabled>
                    </td>
                    <td class="left">                            
                        <?php  
                            // echo $item->comentarioAtendido;
                            $lngComentarioAtendido = strlen($item->comentarioAtendido);
                            if($lngComentarioAtendido>=30){
                                echo substr($item->comentarioAtendido, 0, 30).'...<br/><a href="javascript:void(0);" data-toggle="tooltip" title="'.$item->comentarioAtendido.'">Ver más</a>';
                            }else{
                            echo $item->comentarioAtendido;
                            }
                        ?>
                    </td>
                    <?php if(in_array("11", $this->groups) || in_array("8", $this->groups) || in_array("10", $this->groups)){ ?>
                    <td class="left">                                                    
                        <?php 
                        if($item->agtVentasId!=""){
                            $datosUsrJoomla = SasfehpHelper::obtInfoUsuariosJoomla($item->agtVentasId);
                            echo $datosUsrJoomla->name;
                        }
                        ?>                        
                    </td>
                    <?php } ?>
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
    </div> 
    
</form>

 <!-- Modal -->
<div class="modal fade" id="popup_atenderevento" role="dialog" style="width:400px;position:relative !important;">
    <div class="modal-dialog">          
      <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">Marcar como atendido</h4>
          </div>                          
          <div class="modal-body cont_form_popup">            
            <form id="form_atenderevento" class="form-horizontal" action="<?php echo JRoute::_('index.php?option=com_sasfe&view=listareventos&task=listareventos.atenderevento'); ?>" method="post">                
                <div class="control-group ctrgr_popup">
                    <div class="control-label">                            
                        <label for="ate_fechareg">Registro:</label>
                    </div>
                    <div class="controls">
                        <input type="text" name="ate_fechareg" id="ate_fechareg" class="required" readonly>
                    </div>                    
                </div>
                <div class="control-group ctrgr_popup">
                    <div class="control-label">
                        <label for="ate_comentario">Comentario:</label>
                    </div>
                    <div class="controls">
                        <textarea name="ate_comentario" id="ate_comentario" class="form-control required" style="min-width:90%;"></textarea>
                    </div>
                </div>
                
                <div>
                    <button type="submit" class="btn btn-small button-new btn-success" id="btn_atenderevento">Aceptar</button>
                </div>
                <input type="hidden" name="idMovPros" id="idMovPros" value="0" />

                <?php echo JHtml::_('form.token'); ?>
                <input type="hidden" name="hiddopc" value="<?php echo $this->opc; ?>">
            </form>
          </div>              
      </div>
    </div>
</div>

<script>
    //setInterval(function(){console.log("hola");}, 1000);
/*
    // Pedimos permiso (el navegador nos preguntara)
    var notification = window.Notification || window.mozNotification || window.webkitNotification;    

    if ('undefined' === typeof notification){
      // alert('Tu navegador no soporta notificaciones');
    }else{      
      // Pedimos permiso (el navegador nos preguntará)
      notification.requestPermission();
      
      notActual = Notification? Notification.permission || window.webkitNotifications.checkPermission() : "No soportado";

      //if(notActual=="granted"){
       //var title = "Xitrus"
       //var extra = {
           //         icon: "http://xitrus.es/imgs/logo_claro.png",
          //          body: "Probando"
          //         }        
        //new Notification(title,extra);
      //}
    }

    function startTime() {
        var today = new Date();
        var h = today.getHours();
        var m = today.getMinutes();
        var s = today.getSeconds();
        m = checkTime(m);
        s = checkTime(s);
        tiempo = h + ":" + m + ":" + s;
        //tiempo = h + ":" + m;
        var t = setTimeout(startTime, 500);

        if(tiempo=="18:19:00"){
            if(notActual=="granted"){
               var title = "Xitrus"
               var extra = {
                            icon: "http://xitrus.es/imgs/logo_claro.png",
                            body: "Probando"
                           }        
                new Notification(title,extra);
              }
        }
        //console.log(tiempo);
    }
    function checkTime(i) {
        if (i < 10) {i = "0" + i};  // add zero in front of numbers < 10
        return i;
    }
    startTime();
*/    
</script>