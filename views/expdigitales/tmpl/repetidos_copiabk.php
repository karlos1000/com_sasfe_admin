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
//$fraccionamientos    = $this->state->get('filter.sasfeFraccionamientos');
$timeZone = SasfehpHelper::obtDateTimeZone();
?>
<div class="notesUlMozi">      
</div>
<form action="<?php echo JRoute::_('index.php?option=com_sasfe&view=prospectos&layout=repetidos'); ?>" method="post" name="adminForm" id="adminForm">
    
    <div id="filter-bar" class="btn-toolbar">
            <div class="filter-search btn-group pull-left">
                    <label for="filter_search" class="element-invisible"><?php echo JText::_('Search');?></label>
                    <input type="text" name="filter_search" id="filter_search" placeholder="<?php echo JText::_('Search'); ?>" value="<?php echo $this->escape($this->state->get('filter.search')); ?>" class="hasTooltip" title="<?php echo JHtml::tooltipText('Search'); ?>" />
            </div>
            <div class="btn-group pull-left">
                    <button type="submit" class="btn hasTooltip" title="<?php echo JHtml::tooltipText('Search'); ?>"><i class="icon-search"></i></button>
                    <button type="button" class="btn hasTooltip" title="<?php echo JHtml::tooltipText('Clear'); ?>" onclick="document.getElementById('filter_search').value='';this.form.submit();"><i class="icon-remove"></i></button>
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
                <th class="nowrap center">
                    <?php echo "Acciones"; ?>
                </th>
            </tr>
        </thead>
        <tbody>            
            <?php foreach ($this->items as $i => $item): ?>
                <?php 
                    $link = JRoute::_('index.php?option=com_sasfe&view=prospecto&layout=sololectura&id=' . $item->idDatoProspecto. '&opc=1');
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
                    <td class="center">                        
                        <a href="<?php echo $link; ?>">Ver detalle</a>
                        <!-- <a href="#" data-toggle="modal" data-target="#popup_agregarevento" class="selProspecto" idPros="<?php echo $item->idDatoProspecto;?>">Evento</a> -->
                    </td>
                </tr>                
            <?php endforeach; ?>
                         
        </tbody>
        <tfoot>
            <tr>
                <td colspan="9"><?php echo $this->pagination->getListFooter(); ?></td>
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
    </div> 
    
</form>

<!-- Modal para validar un prospectador repetido desde un gerente de prospeccion-->
<div class="modal fade" id="popup_validar_prospecto_repetido" role="dialog" style="width:400px;position:relative !important;">
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

                <button type="submit" class="btn btn-small button-new btn-success">Aceptar</button>
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

                <button type="submit" class="btn btn-small button-new btn-success">Aceptar</button>
                <input type="hidden" name="arrRepetidoId" id="arrRepetidoId" value="" />
                <input type="hidden" name="idGteSel" id="idGteSel" value="0" />
            </form>
          </div> 
          <?php } ?>
      </div>
    </div>
</div>