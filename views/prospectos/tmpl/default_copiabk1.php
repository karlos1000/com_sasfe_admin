<?php
// no direct access
defined('_JEXEC') or die;

JHtml::addIncludePath(JPATH_COMPONENT.'/helpers/html');
require_once(JPATH_COMPONENT.'/helpers/sasfehp.php' );

JHtml::_('behavior.tooltip');
JHtml::_('behavior.multiselect');
JHtml::_('behavior.modal');

$ordering	= $this->escape($this->state->get('list.ordering'));
$direction	= $this->escape($this->state->get('list.direction'));
$opcionTipoCreditos = $this->state->get('filter.opcionTipoCreditos');
$opcionEstatusProspecto = $this->state->get('filter.opcionEstatusProspecto');
$timeZone = SasfehpHelper::obtDateTimeZone();

// echo "<pre>";
// print_r($this->items);
// echo "</pre>";

?>
<div class="notesUlMozi">      
</div>
<form action="<?php echo JRoute::_('index.php?option=com_sasfe&view=prospectos'); ?>" method="post" name="adminForm" id="adminForm">
    
    <div id="filter-bar" class="btn-toolbar">
            <div class="filter-search btn-group pull-left">
                <label for="filter_search" class="element-invisible"><?php echo JText::_('Nombre|Apellidos');?></label>
                <input type="text" name="filter_search" id="filter_search" placeholder="<?php echo JText::_('Nombre|Apellidos'); ?>" value="<?php echo $this->escape($this->state->get('filter.search')); ?>" class="hasTooltip" title="<?php echo JHtml::tooltipText('Nombre|Apellidos'); ?>" style="width:170px;" />
            </div>
            <div class="filter-search btn-group pull-left">
                <label for="filter_montocto1" class="element-invisible"><?php echo JText::_('$ Monto cto. 1');?></label>
                <?php $montocto1 = ($this->escape($this->state->get('filter.montocto1')) != "") ?$this->escape($this->state->get('filter.montocto1')) :""; ?>
                <input type="text" name="filter_montocto1" id="filter_montocto1" placeholder="<?php echo JText::_('$ Monto cto. 1'); ?>" value="<?php echo $montocto1; ?>" class="hasTooltip" title="<?php echo JHtml::tooltipText('$ Monto cto. 1'); ?>" style="width:72px;" />
            </div>
            <div class="filter-search btn-group pull-left">
                <?php $montocto2 = ($this->escape($this->state->get('filter.montocto2')) != "") ?$this->escape($this->state->get('filter.montocto2')) :""; ?>
                <label for="filter_montocto2" class="element-invisible"><?php echo JText::_('$ Monto cto. 2');?></label>
                <input type="text" name="filter_montocto2" id="filter_montocto2" placeholder="<?php echo JText::_('$ Monto cto. 2'); ?>" value="<?php echo $montocto2; ?>" class="hasTooltip" title="<?php echo JHtml::tooltipText('$ Monto cto. 2'); ?>" style="width:72px;" />
            </div>
            <div class="filter-search btn-group pull-left">                
                <label for="filter_puntoshasta" class="element-invisible"><?php echo JText::_('Puntos Hasta');?></label>
                <input type="text" name="filter_puntoshasta" id="filter_puntoshasta" placeholder="<?php echo JText::_('Puntos Hasta'); ?>" value="<?php echo $this->escape($this->state->get('filter.puntoshasta')); ?>" class="hasTooltip" title="<?php echo JHtml::tooltipText('Puntos Hasta'); ?>" style="width:80px;"/>
            </div>
            <div class="filter-search btn-group pull-left">                
                <label for="filter_tipocto" class="element-invisible"><?php echo JText::_('Tipo Cr&eacute;dito');?></label>
                <select name="filter_tipocto" id="filter_tipocto" class="hasTooltip" title="<?php echo JHtml::tooltipText('Tipo Crédito'); ?>" style="width:170px;">
                    <option value=""><?php echo JText::_('-Tipo Cr&eacute;dito-');?></option>
                    <?php echo JHtml::_('select.options', JHtml::_('modules.opcionTipoCreditos'), 'value', 'text', $opcionTipoCreditos, true);?>
                </select>
            </div>
            <!--
            <div class="filter-search btn-group pull-left">                
                <label for="filter_estatus" class="element-invisible"><?php echo JText::_('Estatus');?></label>
                <select name="filter_estatus" id="filter_estatus" class="hasTooltip" title="<?php echo JHtml::tooltipText('Estatus'); ?>" style="width:170px;">               
                    <?php echo JHtml::_('select.options', JHtml::_('modules.opcionEstatusProspecto'), 'value', 'text', $opcionEstatusProspecto, true);?>
                </select>
            </div>
            -->            
	    <div class="filter-search btn-group pull-left">                
                <label for="filter_estatus" class="element-invisible"><?php echo JText::_('Estatus');?></label>
                <select name="filter_estatus" id="filter_estatus" class="hasTooltip" title="<?php echo JHtml::tooltipText('Estatus'); ?>" style="width:170px;">
                    <option value=""><?php echo JText::_('-Estatus-');?></option>
                    <option value="1"><?php echo JText::_('Asignados');?></option>
                    <option value="2"><?php echo JText::_('Por Asignar');?></option>                    
                </select>
            </div>         

            

            <div class="btn-group pull-left">
                <button type="submit" class="btn hasTooltip" title="<?php echo JHtml::tooltipText('Search'); ?>"><i class="icon-search"></i></button>
                <button type="button" id="limpiarFiltros" class="btn hasTooltip" title="<?php echo JHtml::tooltipText('Clear'); ?>" ><i class="icon-remove"></i></button>
                <!-- <button type="button" class="btn hasTooltip" title="<?php echo JHtml::tooltipText('Clear'); ?>" onclick="document.getElementById('filter_search').value='';this.form.submit();"><i class="icon-remove"></i></button> -->                
            </div>
            <div class="btn-group pull-right hidden-phone">
                    <label for="limit" class="element-invisible"><?php echo JText::_('JFIELD_PLG_SEARCH_SEARCHLIMIT_DESC');?></label>
                    <?php echo $this->pagination->getLimitBox(); ?>
            </div>
    </div>
    <br/>
    <!-- <div class="filter-select fltrt">
        <label for="filter_state">
                <?php echo 'Filtro por: ' ?>
        </label>		
         <select name="filter_fracc" class="inputbox" onchange="this.form.submit()">
                <option value=""><?php echo JText::_('-Fraccionamiento-');?></option>
                <?php echo JHtml::_('select.options', JHtml::_('modules.sasfeFraccionamientos'), 'value', 'text', $fraccionamientos, true);?>
         </select>        		
    </div> -->
    
    <div class="filter-select fltrt">     
        <?php if(in_array("18", $this->groups)){ ?>   
        <!-- Solo lo puede ver el agente de ventas(asesores) -->
        <button onclick="Joomla.submitbutton('prospecto.miseventos');" class="btn btn-small button-new">
            </span>Ver mis eventos
        </button>
        <button onclick="Joomla.submitbutton('prospectos.noprocesados');" class="btn btn-small button-new">
            </span>Sin proceder
        </button>        
        <?php } ?>
        
        <!-- Para los gerentes de venta -->
        <?php if(in_array("11", $this->groups)){ ?>
        <button type="button" data-toggle="modal" data-target="#popup_asignar" class="btn btn-small button-new selAsignar">
            </span>Re/Asignar
        </button>
        <button type="button" class="btn btn-small button-new" onclick="Joomla.submitbutton('prospectos.repetidos');">
            </span>Revisar repetidos
        </button>
        <button type="button" data-toggle="modal" data-target="#popup_protegerpros" class="btn btn-small button-new selProtegerPros">
            </span>Proteger
        </button>
        <button onclick="Joomla.submitbutton('prospecto.miseventos');" class="btn btn-small button-new">
            </span>Ver eventos
        </button>         
        <?php } ?>

        <!-- Para los gerentes de prospeccion -->
        <?php if(in_array("19", $this->groups)){ ?>
        <button type="button" data-toggle="modal" data-target="#popup_asignargteventas" class="btn btn-small button-new selAsignarGteVentas">
            </span>Re/Asignar
        </button>
        <button type="button" class="btn btn-small button-new" onclick="Joomla.submitbutton('prospectos.repetidos');">
            </span>Revisar repetidos
        </button>
        <?php } ?>
    </div>
    <br/>
    <table class="table table-striped">
        <thead>
            <tr>
                <th width="5" class="nowrap center">                    
                    <?php echo JHtml::_('grid.sort', 'ID', 'idDatoProspecto', $direction, $ordering); ?>                    
                </th>
                <th width="20" class="nowrap center">
                    <?php echo JText::_('Sel'); ?>                    
                </th>			
                <th class="nowrap center">
                    <?php echo JHtml::_('grid.sort', 'Fecha Alta', 'fechaAlta', $direction, $ordering); ?>  
                </th>
                <th class="nowrap center">
                    <?php echo JHtml::_('grid.sort', 'Nombre', 'nombre', $direction, $ordering); ?>  
                </th>
                <th class="nowrap center">
                    <?php echo JHtml::_('grid.sort', 'Celular', 'celular', $direction, $ordering); ?>  
                </th>
                <th class="nowrap center">
                    <?php echo JHtml::_('grid.sort', 'Monto Cr&eacute;dito', 'montoCredito', $direction, $ordering); ?>  
                </th>
                <th class="nowrap center">
                    <?php echo JHtml::_('grid.sort', 'Tipo Cr&eacute;dito', 'tipoCreditoId', $direction, $ordering); ?>  
                </th>
                <th class="nowrap center">
                    <?php echo "Vence el"; ?>
                </th>
                <th class="nowrap center">
                    <?php echo "T. protegido"; ?>
                </th>
                <th width="300" class="nowrap center">
                    <?php echo JHtml::_('grid.sort', 'Comentario', 'comentario', $direction, $ordering); ?>  
                </th>
                <?php if(in_array("19", $this->groups) || in_array("11", $this->groups) || in_array("17", $this->groups)){ ?>
                <th class="nowrap center">
                    <?php echo JHtml::_('grid.sort', 'Estatus', 'agtVentasId2', $direction, $ordering); ?>  
                    <?php //echo "Estatus"; ?>
                </th>
                <?php } ?>                
                <th class="nowrap center">
                    <?php //echo "Agente"; ?>
                    <?php echo JHtml::_('grid.sort', 'Agente', 'agtVentasId', $direction, $ordering); ?>  
                </th>
                <th class="nowrap center">
                    <?php echo "Acciones"; ?>
                </th>
            </tr>
        </thead>
        <tbody>            
            <?php foreach ($this->items as $i => $item): ?>
                <?php 
                    $link = JRoute::_('index.php?option=com_sasfe&view=prospecto&layout=edit&id=' . $item->idDatoProspecto);
                    $linksl = JRoute::_('index.php?option=com_sasfe&view=prospecto&layout=sololectura&id=' . $item->idDatoProspecto. '&opc=1');
                    $links2 = JRoute::_('index.php?option=com_sasfe&view=historialprospecto&id=' . $item->idDatoProspecto);
                    // Obtener el historial de prospecto por su id
                    $colHistPros = SasfehpHelper::obtHistorialProspecto($item->idDatoProspecto);
                ?>
                <tr class="row<?php echo $i % 2; ?>">
                    <td>
                        <?php echo $item->idDatoProspecto; ?>
                    </td>
                    <td>
                        <?php echo JHtml::_('grid.id', $i, $item->idDatoProspecto); ?>
                    </td>
                    <td class="center">
                        <?php echo SasfehpHelper::conversionFechaF2($item->fechaAlta); ?>                        
                    </td>
                    <td class="center">                        
                        <?php echo $item->nombre." ".$item->aPaterno." ".$item->aManterno; ?>
                        <!-- <a href="<?php echo $link; ?>">
                            <?php echo $item->nombre." ".$item->aPaterno." ".$item->aManterno; ?>
                        </a> -->
                    </td>
                    <td class="center">
                        <?php echo $item->celular; ?>                        
                    </td>
                    <td class="center">
                        <?php echo "$ ". number_format($item->montoCredito,2); ?>
                    </td>                    
                    <td class="center">
                        <?php echo $item->tipoCredito; ?>                        
                    </td>
                    <td class="center fechaAsig">
                        <?php echo ($item->periodoAsignacion!="") ?SasfehpHelper::conversionFechaF2($item->periodoAsignacion) :""; ?>
                    </td>
                    <td class="center">
                        <?php 
                            if($item->idTiempoProteccion!=""){
                                switch ($item->idTiempoProteccion) {            
                                    case 1: echo "1 semana"; break; //Una semana
                                    case 2: echo "15 d&iacute;as"; break; //15 dias
                                    case 3: echo "1 mes"; break; //un mes
                                }
                            }
                        ?>
                    </td>
                    <td class="left">                            
                        <?php                             
                            $lngComentario = strlen($item->comentario);
                            if($lngComentario>=200){
                                echo substr($item->comentario, 0, 180).'...<a href="'.$link.'">Ver m&aacute;s</a>';
                            }else{
                                echo $item->comentario;
                            }                            
                        ?>
                    </td>
                    <!-- gerentes de prospeccion y gerentes ventas -->
                    <?php if(in_array("19", $this->groups) || in_array("11", $this->groups) || in_array("17", $this->groups)){ ?>
                    <!-- style="font-size:11px;" -->
                    <td class="center">
                        <?php 
                            if($item->departamentoId!="" && $item->fechaDptoAsignado==""){
                                $asigText = "Apartado prov.";
                            }
                            elseif($item->departamentoId!="" && $item->fechaDptoAsignado!=""){
                                $asigText = "Apartado definitivo";
                            }
                            else{
                                $asigText = ($item->agtVentasId!="") ?"Agt. Asignado" :"Por Asignar Agt.";
                            }
                            echo $asigText;
                        ?>
                    </td>
                    <?php } ?>
                    <td class="center">
                        <?php 
                            if($item->agtVentasId!=""){
                                $datosUsrJoomla = SasfehpHelper::obtInfoUsuariosJoomla($item->agtVentasId);
                                echo $datosUsrJoomla->name;
                            }
                        ?>
                    </td>
                    <td class="center">
                        <!-- gerentes de prospeccion y gerentes ventas -->
                        <?php if(in_array("19", $this->groups) || in_array("11", $this->groups)){ ?>
                            <a href="<?php echo $linksl; ?>">Ver detalle</a> 
                            <?php if(in_array("11", $this->groups) && ($item->departamentoId=="" && $item->fechaDptoAsignado=="") ){ ?>
                                  | <a href="#" data-toggle="modal" data-target="#popup_asignar" idPros="<?php echo $item->idDatoProspecto;?>" class="selAsigAgteVentasLink">Re/Asignar</a>  
                            <?php } ?>
                            <?php if(in_array("19", $this->groups)){ ?>
                                  <a href="#" data-toggle="modal" data-target="#popup_asignargteventas" idPros="<?php echo $item->idDatoProspecto;?>" class="selAsigGteVentasLink">Re/Asignar</a>
                            <?php } ?>
                        <?php }else{ ?>
                            <?php if($item->fechaDptoAsignado!=""){ ?>    
                                <a href="<?php echo $link; ?>">Ver</a>
                            <?php }else{ ?>
                                <?php if(in_array("17", $this->groups)){ ?>                                
                                <a href="<?php echo $linksl; ?>">Ver detalle</a>
                                <?php }else{ ?> 
                                <a href="<?php echo $link; ?>">Editar</a>
                                | <a href="#" data-toggle="modal" data-target="#popup_agregarevento" class="selProspecto" idPros="<?php echo $item->idDatoProspecto;?>">Evento</a>
                                <?php } ?>
                            <?php } ?>                            
                        <?php } ?>
                        <?php if(count($colHistPros)>0){ ?>
                            | 
                                <a href="<?php echo $links2; ?>">Historial</a>
                        <?php } ?>
                        <!-- | <a href="#" data-toggle="modal" data-target="#popup_comentario" class="selProspectoCom" idPros="<?php echo $item->idDatoProspecto;?>">Comentario</a>                         -->
                    </td>
                </tr>                
            <?php endforeach; ?>
                         
        </tbody>
        <tfoot>
            <tr>
                <?php if(in_array("19", $this->groups) || in_array("11", $this->groups) || in_array("17", $this->groups)){ ?>
                <td colspan="13"><?php echo $this->pagination->getListFooter(); ?></td>    
                <?php }else{ ?>
                <td colspan="12"><?php echo $this->pagination->getListFooter(); ?></td>
                <?php } ?>
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
        <input type="hidden" id="idPorVencer" value="0" />
        <?php if(in_array("18", $this->groups)){ ?>
        <input type="hidden" name="vista_eventos" value="0" />
        <?php }elseif(in_array("11", $this->groups)){ ?>
        <input type="hidden" name="vista_eventos" value="1" />
        <?php } ?>
    </div> 
    
    <input type="hidden" id="loading_img" value="<?php echo $this->imgLoading; ?>" />
</form>

<!-- Modal -->
<div class="modal fade" id="popup_agregarevento" role="dialog" style="width:500px;height:600px;position:relative !important;">
    <div class="modal-dialog">          
      <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal">&times;</button>
              <h4 class="modal-title">Evento para prospecto</h4>
          </div>
          <div class="modal-body cont_form_popup">
            <form id="form_agregar_evento" class="form-horizontal" action="<?php echo JRoute::_('index.php?option=com_sasfe&view=prospectos&task=prospecto.addevento'); ?>" method="post">
                <div class="control-group ctrgr_popup">
                    <div class="control-label">                            
                        <label for="ev_tipoevento">Evento:</label>
                    </div>
                    <div class="controls">
                        <select name="ev_tipoevento" id="ev_tipoevento" class="required">
                            <option value="">--Seleccionar--</option>
                            <?php
                            foreach ($this->arrTipoEventos as $itemTE) {                                    
                                echo '<option value="' . $itemTE->idTipoEvento . '">' . $itemTE->tipoEvento . '</option>';                        
                            }
                            ?>
                        </select>                            
                    </div>
                </div>

                

                <div class="control-group ctrgr_popup">
                    <div class="control-label">                            
                        <label for="ev_fecha">Fecha y hora:</label>
                    </div>
                    <div class="controls">
                        <!-- <input type="text" name="ev_fecha" id="ev_fecha" class="required" style="width:100px;" readonly/> -->
                        <div id="ev_fecha_linea" style="display:inline-block;"></div>
                        <input type="hidden" name="ev_fecha" id="ev_fecha" value="<?php echo $timeZone->fechaF2; ?>" />
                        <div style="display:inline-block;"><input class="timepicker timepicker-with-dropdown text-center" name="ev_hora" id="ev_hora" value="7:00" style="width:50px;cursor:pointer;" readonly></div>
                    </div>
                </div>                                        
               
                <div class="control-group ctrgr_popup">
                    <div class="control-label">                            
                        <label for="ev_optrecordatorio">Recordatorio:</label>
                    </div>
                    <div class="controls">
                        <input type="checkbox" name="ev_optrecordatorio" id="ev_optrecordatorio" class="" value="0">
                    </div>
                </div>
                <div class="control-group ctrgr_popup" style="display:none;" id="cont_ev_tiempo">
                    <div class="control-label">                            
                        <label for="ev_tiempo">Tiempo:</label>
                    </div>
                    <div class="controls">
                        <select name="ev_tiempo" id="ev_tiempo" class="">
                            <option value="">--Selecciona--</option>
                            <?php
                            foreach ($this->arrTiempoRecordatorios as $itemTR) {                                    
                                echo '<option value="' . $itemTR->idTiempoRecordatorio . '">' . $itemTR->texto . '</option>';                        
                            }
                            ?>                                
                        </select>                            
                    </div>
                </div>                    
                <div class="control-group ctrgr_popup">
                    <div class="control-label">
                        <label for="ev_comentario">Comentario:</label>
                    </div>
                    <div class="controls">
                        <textarea name="ev_comentario" id="ev_comentario" class="form-control required" style="min-width:90%;"></textarea>
                    </div>
                </div> 
                
                <div>
                    <button type="submit" class="btn btn-small button-new btn-success" id="btn_agregarevento">Agregar evento</button>
                </div>

                <input type="hidden" name="ev_idPros" id="ev_idPros" value="0" />
                <input type="hidden" name="edit_evpros" value="0" />
            </form>
          </div>              
      </div>
    </div>
</div>


 <!-- Modal gerente de ventas (asignar a otros agentes de venta) -->
<div class="modal fade" id="popup_asignar" role="dialog" style="width:400px;position:relative !important;">
    <div class="modal-dialog">          
      <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <!-- <h4 class="modal-title">Asignar prospecto(s)</h4> -->
            <h4 class="modal-title">Asignar agente(s) de venta(s) a prospecto(s)</h4>
          </div>                
          <!-- <div style="padding:10px 0 0 40px;">Asignar a prospecto(s) seleccionado(s)</div> -->
          <div class="modal-body cont_form_popup">            
            <form id="form_asignarprospecto" class="form-horizontal" action="<?php echo JRoute::_('index.php?option=com_sasfe&view=prospectos&task=prospecto.asignarprospecto'); ?>" method="post">                
                <div class="control-group ctrgr_popup">
                    <div class="control-label">                            
                        <label for="asig_agtventas">Agente Venta:</label>
                    </div>
                    <div class="controls">
                        <select name="asig_agtventas" id="asig_agtventas" class="required">
                            <option value="">--Seleccionar--</option>                            
                            <?php
                            foreach ($this->ColAsesores as $itemAse) {                                    
                                echo '<option value="' . $itemAse->idDato . '">' . $itemAse->nombre . '</option>';                        
                            }
                            ?>
                        </select>                            
                    </div>                    
                </div>
                <div class="control-group ctrgr_popup">
                    <div class="control-label">
                        <label for="asig_comentario">Comentario:</label>
                    </div>
                    <div class="controls">
                        <textarea name="asig_comentario" id="asig_comentario" class="form-control required" style="min-width:90%;"></textarea>
                    </div>
                </div>
                
                <div>
                    <button type="submit" class="btn btn-small button-new btn-success" id="btn_asignar">Aceptar</button>
                </div>

                <input type="hidden" name="arrIdPros" id="arrIdPros" value="" />
            </form>
          </div>              
      </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="popup_comentario" role="dialog" style="width:500px;position:relative !important;">
    <div class="modal-dialog">          
      <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal">&times;</button>
              <h4 class="modal-title">Comentario para prospecto</h4>
          </div>
          <div class="modal-body cont_form_popup">
            <form id="form_agregar_comentario" class="form-horizontal" action="<?php echo JRoute::_('index.php?option=com_sasfe&view=prospectos&task=prospecto.addcomentario'); ?>" method="post">
                <div class="control-group ctrgr_popup">
                    <div class="control-label">
                        <label for="ev_comentario">Comentario:</label>
                    </div>
                    <div class="controls">
                        <textarea name="ev_comentario" id="ev_comentario" class="form-control required" style="min-width:90%;"></textarea>
                    </div>
                </div> 

                <button type="submit" class="btn btn-small button-new btn-success">Agregar comentario</button>

                <input type="hidden" name="com_idPros" id="com_idPros" value="0" />
            </form>
          </div>              
      </div>
    </div>
</div>

 <!-- Modal gerente prospeccion -->
<div class="modal fade" id="popup_asignargteventas" role="dialog" style="width:400px;position:relative !important;">
    <div class="modal-dialog">          
      <div class="modal-content">                  
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>            
            <h4 class="modal-title">Asignar gerente(s) de venta(s) a prospecto(s)</h4>
          </div>                          
          <div class="modal-body cont_form_popup">            
            <form id="form_asignargteventas" class="form-horizontal" action="<?php echo JRoute::_('index.php?option=com_sasfe&view=prospectos&task=prospecto.asignarprospectoagteventas'); ?>" method="post">                
                <div class="control-group ctrgr_popup">
                    <div class="control-label">                            
                        <label for="asiggtev_gteventas">Gerente Venta:</label>
                    </div>
                    <div class="controls">
                        <select name="asiggtev_gteventas" id="asiggtev_gteventas" class="required">
                            <option value="">--Seleccionar--</option>                            
                            <?php
                            foreach ($this->ColGteVentas as $itemGteV) {                                    
                                echo '<option value="' . $itemGteV->idDato . '">' . $itemGteV->nombre . '</option>';                        
                            }
                            ?>
                        </select>                            
                    </div>                    
                </div>
                <div class="control-group ctrgr_popup">
                    <div class="control-label">
                        <label for="asiggtev_comentario">Comentario:</label>
                    </div>
                    <div class="controls">
                        <textarea name="asiggtev_comentario" id="asiggtev_comentario" class="form-control required" style="min-width:90%;"></textarea>
                    </div>
                </div>
                
                <div>    
                <button type="submit" class="btn btn-small button-new btn-success" id="btn_asignargteventas">Aceptar</button>
                </div>    
                <input type="hidden" name="arrIdProsGteV" id="arrIdProsGteV" value="" />
            </form>
          </div>              
      </div>
    </div>
</div>


 <!-- Modal proteger prospecto (solo getentes de venta) -->
<div class="modal fade" id="popup_protegerpros" role="dialog" style="width:400px;position:relative !important;display:none;">
    <div class="modal-dialog">          
      <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">Proteger prospecto(s)</h4>
          </div>
          <div class="modal-body cont_form_popup">            
            <form id="form_protegerpros" class="form-horizontal" action="<?php echo JRoute::_('index.php?option=com_sasfe&view=prospectos&task=prospecto.protegerpros'); ?>" method="post">
                <div class="control-group ctrgr_popup">
                    <div class="control-label">                            
                        <label for="prot_tiempo">Tiempo protecci&oacute;n:</label>
                    </div>
                    <div class="controls">
                        <select name="prot_tiempo" id="prot_tiempo" class="required">
                            <option value="">--Seleccionar--</option>
                            <option value="1">1 semana</option>
                            <option value="2">15 días</option>
                            <option value="3">1 mes</option>
                        </select>                            
                    </div>
                </div>
                
                <div>
                    <button type="submit" class="btn btn-small button-new btn-success" id="btn_protegerpros">Aceptar</button>
                </div>

                <input type="hidden" name="arrIdProsProt" id="arrIdProsProt" value="" />
            </form>
          </div>              
      </div>
    </div>
</div>