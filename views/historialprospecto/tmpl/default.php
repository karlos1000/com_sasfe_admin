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
$timeZone = SasfehpHelper::obtDateTimeZone();

// echo "<pre>";
// print_r($this->items);
// echo "</pre>";

?>
<div class="notesUlMozi">      
</div>
<form action="<?php echo JRoute::_('index.php?option=com_sasfe&view=historialprospecto'); ?>" method="post" name="adminForm" id="adminForm">    
    <div id="filter-bar" class="btn-toolbar">
    <!--
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

            <div class="btn-group pull-left">
                <button type="submit" class="btn hasTooltip" title="<?php echo JHtml::tooltipText('Search'); ?>"><i class="icon-search"></i></button>
                <button type="button" id="limpiarFiltros" class="btn hasTooltip" title="<?php echo JHtml::tooltipText('Clear'); ?>" ><i class="icon-remove"></i></button>                
            </div>
    -->            
            <div class="btn-group pull-right hidden-phone">
                    <label for="limit" class="element-invisible"><?php echo JText::_('JFIELD_PLG_SEARCH_SEARCHLIMIT_DESC');?></label>
                    <?php echo $this->pagination->getLimitBox(); ?>
            </div>
    </div>
    <br/>    
    
    <table class="table table-striped">
        <thead>
            <tr>
                <th width="5" class="nowrap center">                    
                    <?php echo JHtml::_('grid.sort', 'ID', 'idHistPros', $direction, $ordering); ?>                    
                </th>
                <!--
                <th width="20" class="nowrap center">
                    <?php echo JText::_('Sel'); ?>                    
                </th>
                -->
                <th class="nowrap center">
                    <?php echo "Estatus"; //echo JHtml::_('grid.sort', 'Estatus', 'a.estatusId', $direction, $ordering); ?>
                </th>
                <th class="nowrap center">
                    <?php echo "Comentario"; //echo JHtml::_('grid.sort', 'Comentario', 'comentario', $direction, $ordering); ?>  
                </th>
                <th class="nowrap center">
                    <?php echo JHtml::_('grid.sort', 'Fecha', 'a.fechaCreacion', $direction, $ordering); ?>  
                </th>                
            </tr>
        </thead>
        <tbody>            
            <?php foreach ($this->items as $i => $item): ?>                
                <tr class="row<?php echo $i % 2; ?>">
                    <td>
                        <?php echo $item->idHistPros; ?>
                    </td>
                    <!--
                    <td>
                        <?php echo JHtml::_('grid.id', $i, $item->idHistPros); ?>
                    </td>
                    -->
                    <td class="center">
                        <?php 
                            $msgEstatus = "";
                            switch ($item->estatusIdHistPros) {
                                case 1: $msgEstatus = "Apartado provisional"; break;
                                case 2: $msgEstatus = "Liberada autom&aacute;ticamente"; break;
                                case 3: $msgEstatus = "Liberada manualmente"; break;
                                case 4: $msgEstatus = "Apartada por gerente"; break;
                                case 5: $msgEstatus = "Liberada por mesa de control"; break;
                                case 6: $msgEstatus = "Apartado definitivo"; break;
                                case 7: $msgEstatus = "Liberada por gerente de ventas"; break;
                                case 8: $msgEstatus = "Liberada por titulaci&oacute;n"; break;
                                case 9: $msgEstatus = "Liberada por administrador"; break;
                                case 10: $msgEstatus = "Liberada por direcci&oacute;n"; break;
                                case 15: $msgEstatus = "Alta prospecto"; break;
                                case 16: $msgEstatus = "Asignar/Reasignar asesor"; break;
                                case 17: $msgEstatus = "Asignar/Reasignar gerente"; break;
                            }
                            echo $msgEstatus;
                        ?>
                    </td> 
                    <td class="left" style="width:40%;">
                        <?php echo $item->comentHistPros; ?>                        
                    </td>
                    <td class="center">
                        <?php echo SasfehpHelper::conversionFechaF2($item->fechaHistPros); ?>                        
                    </td>
                </tr>                
            <?php endforeach; ?>
                         
        </tbody>
        <tfoot>
            <tr>                
                <td colspan="5"><?php echo $this->pagination->getListFooter(); ?></td>    
            </tr>
        </tfoot>        			
    </table>
    <div>        
        <input type="hidden" name="task" value="" />
        <input type="hidden" name="boxchecked" value="0" />        
        <input type="hidden" name="filter_order" value="<?php echo $ordering; ?>" />
        <input type="hidden" name="filter_order_Dir" value="<?php echo $direction; ?>" />
        <?php echo JHtml::_('form.token'); ?>
    </div> 
    
</form>