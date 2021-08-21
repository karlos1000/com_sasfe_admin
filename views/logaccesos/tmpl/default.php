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

$ordering	= $this->escape($this->state->get('list.ordering'));
$direction	= $this->escape($this->state->get('list.direction'));

?>
<form action="<?php echo JRoute::_('index.php?option=com_sasfe&view=logaccesos'); ?>" method="post" name="adminForm" id="adminForm">
    <div id="filter-bar" class="btn-toolbar">
            <!-- <div class="filter-search btn-group pull-left">
                    <label for="filter_search" class="element-invisible"><?php echo JText::_('Search');?></label>
                    <input type="text" name="filter_search" id="filter_search" placeholder="<?php echo JText::_('Search'); ?>" value="<?php echo $this->escape($this->state->get('filter.search')); ?>" class="hasTooltip" title="<?php echo JHtml::tooltipText('Search'); ?>" />
            </div> -->
           <!--  <div class="btn-group pull-left">
                    <button type="submit" class="btn hasTooltip" title="<?php echo JHtml::tooltipText('Search'); ?>"><i class="icon-search"></i></button>
                    <button type="button" class="btn hasTooltip" title="<?php echo JHtml::tooltipText('Clear'); ?>" onclick="document.getElementById('filter_search').value='';this.form.submit();"><i class="icon-remove"></i></button>
            </div> -->
            <div class="btn-group pull-right hidden-phone">
                    <label for="limit" class="element-invisible"><?php echo JText::_('JFIELD_PLG_SEARCH_SEARCHLIMIT_DESC');?></label>
                    <?php echo $this->pagination->getLimitBox(); ?>
            </div>
    </div>

    <table class="table table-striped">
        <thead>
            <tr>
                <th width="80" class="nowrap center">                    
                    <?php echo JHtml::_('grid.sort', 'ID', 'idLog', $direction, $ordering); ?>                    
                </th>                
                <th width="100" class="nowrap center">                    
                    <?php echo JHtml::_('grid.sort', 'Usuario', 'idUsuario', $direction, $ordering); ?>  
                </th>
                <th width="100" class="nowrap center">                    
                    <?php echo JHtml::_('grid.sort', 'Departamento', 'idDpt', $direction, $ordering); ?>  
                </th>                                
                <th width="100" class="nowrap center">                    
                    <?php echo JHtml::_('grid.sort', 'Fraccionamiento', 'idFracc', $direction, $ordering); ?>  
                </th>                                
                <th width="100" class="nowrap center">                    
                    <?php echo JHtml::_('grid.sort', 'Fecha', 'fechaHora', $direction, $ordering); ?>  
                </th>               
            </tr>
        </thead>
        <tbody>                        
            <?php foreach ($this->items as $i => $item): ?>                                                                                                              
                    <tr class="row<?php echo $i % 2; ?>">
                        <td>                            
                            <?php echo $item->idLog; ?>
                        </td>                   
                        <td class="center">
                            <?php echo $item->nombreUsuario; ?>
                        </td>                                        
                        <td class="center">
                            <?php echo $item->numero; ?>
                        </td>  
                        <td class="center">
                            <?php echo $item->fracc; ?>
                        </td>  
                        <td class="center">
                            <?php echo date("d/m/Y H:i:s", strtotime($item->fechaHora)); ?>
                        </td>                          
                </tr> 
            <?php endforeach; ?>                         
        </tbody>
        <tfoot>
            <tr>
                <td colspan="8"><?php echo $this->pagination->getListFooter(); ?></td>
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
