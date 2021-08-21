<?php
// no direct access
defined('_JEXEC') or die;

JHtml::addIncludePath(JPATH_COMPONENT.'/helpers/html');
JHtml::_('behavior.tooltip');
JHtml::_('behavior.multiselect');
JHtml::_('behavior.modal');

$ordering	= $this->escape($this->state->get('list.ordering'));
$direction	= $this->escape($this->state->get('list.direction'));
/*
echo '<pre>';
    print_r($this->catalogo);
    print_r($this->items);
echo '</pre>';
*/
?>
<div class="notesUlMozi">      
</div>
<form action="<?php echo JRoute::_('index.php?option=com_sasfe&view=catcostoextras&id_cat='.$this->idCatGen); ?>" method="post" name="adminForm" id="adminForm">
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
    <table class="table table-striped">
        <thead>
            <tr>
                <th width="5" class="nowrap center">                    
                    <?php echo JHtml::_('grid.sort', 'ID', 'idDatoCE', $direction, $ordering); ?>                    
                </th>
                <th width="20" class="nowrap center"> 
                    <?php echo JText::_('Sel'); ?>                    
                </th>			                
                <th width="100" class="nowrap center">                    
                    <?php echo JHtml::_('grid.sort', 'Fraccionamiento', 'fraccionamientoId', $direction, $ordering); ?>  
                </th>               
                <th width="100" class="nowrap center">                    
                    <?php echo JHtml::_('grid.sort', 'Nombre', 'nombre', $direction, $ordering); ?>  
                </th>               
                <th width="20" class="nowrap center">                    
                    <?php echo JHtml::_('grid.sort', 'Costo', 'costo', $direction, $ordering); ?>  
                </th>                               
                <th width="20" class="nowrap center">                    
                    <?php echo JHtml::_('grid.sort', 'Activo', 'activo', $direction, $ordering); ?>  
                </th>                
            </tr>
        </thead>
        <tbody>            
            <?php foreach ($this->items as $i => $item): ?>
            
                <tr class="row<?php echo $i % 2; ?>">
                    <td class="center">
                        <?php echo $item->idDatoCE; ?>
                    </td>
                    <td class="center">
                        <?php echo JHtml::_('grid.id', $i, $item->idDatoCE); ?>
                    </td>
                    <td class="center">
                        <?php echo $item->nombre_fracc; ?>
                    </td>
                    <td class="center">
                        <a href="<?php echo JRoute::_('index.php?option=com_sasfe&view=catcostoextra&layout=edit&id='.$item->idDatoCE.'&id_cat='.$this->idCatGen ); ?>">
                            <?php echo $item->nombre; ?>
                       </a>                        
                    </td>                    
                    <td class="center">
                        <?php  echo number_format($item->costo, 2); ?>                                               
                    </td>                                                            
                    <td class="center">
                        <?php                         
                        echo ($item->activo=='1') ? 'Si' :'No' ;?>
                    </td>                                                            
                </tr>                
            <?php endforeach; ?>
                         
        </tbody>
        <tfoot>
            <tr>
                <td colspan="6"><?php echo $this->pagination->getListFooter(); ?></td>
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
