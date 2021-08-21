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
$opcionAgentesVenta = $this->state->get('filter.opcionAgentesVenta');
$timeZone = SasfehpHelper::obtDateTimeZone();
?>
<div class="notesUlMozi">      
</div>
<form action="<?php echo JRoute::_('index.php?option=com_sasfe&view=prospectos&layout=repetidos'); ?>" method="post" name="adminForm" id="adminForm">
    
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
            <?php if(in_array("11", $this->groups)){ ?>  
            <div class="filter-search btn-group pull-left">                
                <label for="filter_agtev" class="element-invisible"><?php echo JText::_('Agente Ventas');?></label>
                <select name="filter_agtev" id="filter_agtev" class="hasTooltip" title="<?php echo JHtml::tooltipText('Agente Ventas'); ?>" style="width:170px;">
                    <option value=""><?php echo JText::_('-Todos Agt. Ventas-');?></option>
                    <?php echo JHtml::_('select.options', JHtml::_('modules.opcionAgentesVenta'), 'value', 'text', $opcionAgentesVenta, true);?>
                </select>
            </div>
            <?php } ?>

            <div class="btn-group pull-left">                
                <button type="submit" class="btn hasTooltip" title="<?php echo JHtml::tooltipText('Search'); ?>"><i class="icon-search"></i></button>
                <button type="button" id="limpiarFiltros" class="btn hasTooltip" title="<?php echo JHtml::tooltipText('Clear'); ?>" ><i class="icon-remove"></i></button>
                <!-- <button type="submit" class="btn hasTooltip" title="<?php echo JHtml::tooltipText('Search'); ?>"><i class="icon-search"></i></button>-->
            </div>
            <div class="btn-group pull-right hidden-phone">
                    <label for="limit" class="element-invisible"><?php echo JText::_('JFIELD_PLG_SEARCH_SEARCHLIMIT_DESC');?></label>
                    <?php echo $this->pagination->getLimitBox(); ?>
            </div>
    </div>
    <br/> 

    <div class="filter-select fltrt">
        <!-- Para los gerentes de prospeccion y gerentes de ventas -->
        <?php if(in_array("19", $this->groups) || in_array("11", $this->groups)){ ?>
        <button type="button" data-toggle="modal" data-target="#popup_validar_prospecto_repetido" class="btn btn-small button-new selValidarProspectoRepetido">
            </span>Validar prospecto
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
                <th width="300" class="nowrap center">
                    <?php echo JHtml::_('grid.sort', 'Comentario', 'comentario', $direction, $ordering); ?>  
                </th>
                <?php if(in_array("11", $this->groups)){ ?> 
                <th class="nowrap center">
                    <?php echo JHtml::_('grid.sort', 'Agente', 'agtVentasId', $direction, $ordering); ?>  
                </th>                
                <?php } ?>
                <th class="nowrap center">
                    <?php echo "Acciones"; ?>
                </th>
            </tr>
        </thead>
        <tbody>            
            <?php foreach ($this->items as $i => $item): ?>
                <?php 
                    $link = JRoute::_('index.php?option=com_sasfe&view=prospecto&layout=sololectura&id=' . $item->idDatoProspecto. '&opc=2');
                ?>
                <tr class="row<?php echo $i % 2; ?>">
                    <td>
                        <?php echo $item->idDatoProspecto; ?>
                    </td>
                    <td>
                        <?php
                            //Se trata 
                            if($item->agtVentasId==""){
                                echo JHtml::_('grid.id', $i, $item->idDatoProspecto); 
                            }
                        ?>
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
                    <td class="left">
                        <?php
                            $lngComentario = strlen($item->comentario);
                            if($lngComentario>=200){
                                echo substr($item->comentario, 0, 180);//.'...<a href="'.$link.'">Ver m&aacute;s</a>';
                            }else{
                                echo $item->comentario;
                            }                            
                        ?>
                    </td>                    
                    <?php if(in_array("11", $this->groups)){ ?> 
                    <td class="center">
                        <?php 
                            if($item->agtVentasId!=""){
                                $datosUsrJoomla = SasfehpHelper::obtInfoUsuariosJoomla($item->agtVentasId);
                                echo $datosUsrJoomla->name;
                            }
                        ?>
                    </td>
                    <?php } ?>
                    <td class="center">                        
                        <a href="<?php echo $link; ?>">Ver detalle</a> | 
                        <a href="javascript:void(0);" idPros="<?php echo $item->idDatoProspecto;?>" class="borrar_pros">Borrar</a> | 
                        <!-- obtener el idDato del catalogo datos -->
                        <?php if($item->agtVentasId!=""){ ?>
                            <?php $datosUserJoomla = SasfehpHelper::obtUsuarioDatosCatalogoPorIdUsrJoomla($item->agtVentasId); ?>
                            <a href="#" data-toggle="modal" data-target="#popup_validar_prospecto_repetido" idPros="<?php echo $item->idDatoProspecto;?>" idAgteVenta="<?php echo $datosUserJoomla[0]->idDato; ?>" class="selValidarProspectoRepetidoLink">Validar</a>    
                        <?php }else{ ?>
                            <a href="#" data-toggle="modal" data-target="#popup_validar_prospecto_repetido" idPros="<?php echo $item->idDatoProspecto;?>" idAgteVenta="0" class="selValidarProspectoRepetidoLink">Validar</a>
                        <?php } ?>                        
                        <!-- | <a href="javascript:void(0);" data-toggle="modal" data-target="#popup_validar_prospecto_repetido" idPros="<?php echo $item->idDatoProspecto;?>" class="selValidarProspectoRepetidoLink">Validar</a> -->
                        <!-- <button type="button" idPros="<?php echo $item->idDatoProspecto;?>" class="btn btn-danger btn_small_del borrar_pros">Borrar</button> -->
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
        <input type="hidden" name="borrar_prosp" id="borrar_prosp" value="0" />
    </div> 
    
    <input type="hidden" id="loading_img" value="<?php echo $this->imgLoading; ?>" />
</form>

<!-- Modal para validar un prospectador repetido desde un gerente de prospeccion-->
<div class="modal fade" id="popup_validar_prospecto_repetido" role="dialog" style="width:400px;position:relative !important;display:none;">
    <div class="modal-dialog">          
      <div class="modal-content">
          <?php //Para gerentes de prospeccion ?>
          <?php if(in_array("19", $this->groups)){ ?>
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">Asignar gerente(s) de venta(s) a prospecto(s)</h4>
          </div>          
          <div class="modal-body cont_form_popup">            
            <form id="form_rep_prospecto_repetido" class="form-horizontal" action="<?php echo JRoute::_('index.php?option=com_sasfe&view=prospectos&task=prospecto.validarprospectorepetido'); ?>" method="post">                
                <div class="control-group ctrgr_popup">
                    <div class="control-label">                            
                        <label for="rep_usuario">Gerente Venta:</label>
                    </div>
                    <div class="controls">
                        <select name="rep_usuario" id="rep_usuario" class="required">
                            <option value="">--Seleccionar--</option>                            
                            <?php
                            foreach ($this->ColGteVentas as $itemRep) {                                    
                                echo '<option value="' . $itemRep->idDato . '">' . $itemRep->nombre . '</option>';                        
                            }
                            ?>
                        </select>                            
                    </div>                    
                </div>
                <div class="control-group ctrgr_popup">
                    <div class="control-label">
                        <label for="rep_comentario">Comentario:</label>
                    </div>
                    <div class="controls">
                        <textarea name="rep_comentario" id="rep_comentario" class="form-control required" style="min-width:90%;"></textarea>
                    </div>
                </div>
                
                <div>
                    <button type="submit" class="btn btn-small button-new btn-success" id="btn_validar_prospecto_repetido">Aceptar</button>
                </div>
                <input type="hidden" name="arrRepetidoId" id="arrRepetidoId" value="" />
                <input type="hidden" name="idGteSel" id="idGteSel" value="1" />
            </form>
          </div> 
          <?php } ?>

          <?php //Para gerentes de ventas ?>
          <?php if(in_array("11", $this->groups)){ ?>
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">Asignar agente(s) de venta(s) a prospecto(s)</h4>
          </div>          
          <div class="modal-body cont_form_popup">            
            <form id="form_rep_prospecto_repetido" class="form-horizontal" action="<?php echo JRoute::_('index.php?option=com_sasfe&view=prospectos&task=prospecto.validarprospectorepetido'); ?>" method="post">                
                <div class="control-group ctrgr_popup">
                    <div class="control-label">                            
                        <label for="rep_usuario">Agente Venta:</label>
                    </div>
                    <div class="controls">
                        <select name="rep_usuario" id="rep_usuario" class="required">
                            <option value="">--Seleccionar--</option>                            
                            <?php
                            foreach ($this->ColAsesores as $itemRep) {                                    
                                echo '<option value="' . $itemRep->idDato . '">' . $itemRep->nombre . '</option>';                        
                            }
                            ?>
                        </select>                            
                    </div>
                </div>
                <div class="control-group ctrgr_popup">
                    <div class="control-label">
                        <label for="rep_comentario">Comentario:</label>
                    </div>
                    <div class="controls">
                        <textarea name="rep_comentario" id="rep_comentario" class="form-control required" style="min-width:90%;"></textarea>
                    </div>
                </div>
                
                <div>
                    <button type="submit" class="btn btn-small button-new btn-success" id="btn_validar_prospecto_repetido">Aceptar</button>
                </div>

                <input type="hidden" name="arrRepetidoId" id="arrRepetidoId" value="" />
                <input type="hidden" name="idGteSel" id="idGteSel" value="0" />
                <div id="addHiddenUser"></div>
            </form>
          </div> 
          <?php } ?>
      </div>
    </div>
</div>