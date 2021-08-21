<?php
// no direct access
defined('_JEXEC') or die;

JHtml::addIncludePath(JPATH_COMPONENT.'/helpers/html');
JHtml::_('behavior.tooltip');
$function	= JRequest::getCmd('function', 'jSelectCustomerlp');
$ordering	= $this->escape($this->state->get('list.ordering'));
$direction	= $this->escape($this->state->get('list.direction'));
/*
echo '<pre>';
    print_r($this->items);
echo '</pre>';
*/
?>
<form action="<?php echo JRoute::_('index.php?option=com_sasfe&view=seldepartamentos&layout=modal&tmpl=component&function='.$function);?>" method="post" name="adminForm" id="adminForm">
	<fieldset class="filter clearfix">
		<div class="left">
			<label for="filter_search">
				<?php echo JText::_('JSearch_Filter_Label'); ?>
			</label>
			<input type="text" name="filter_search" id="filter_search" value="<?php echo $this->escape($this->state->get('filter.search')); ?>" size="30" title="<?php echo JText::_('Filtro'); ?>" />

			<button type="submit">
				<?php echo JText::_('JSEARCH_FILTER_SUBMIT'); ?></button>
			<button type="button" onclick="document.id('filter_search').value='';this.form.submit();">
				<?php echo JText::_('JSEARCH_FILTER_CLEAR'); ?></button>
		</div>
	</fieldset>

	<table class="table table-striped">
		<thead>
			<tr>
				<th class="title" width="20%">
					<?php echo JHtml::_('grid.sort', 'ID', 'idDepartamento', $direction, $ordering); ?>
				</th>                                
				<th>
					<?php echo JHtml::_('grid.sort', 'No. Departamento', 'numero', $direction, $ordering); ?>
				</th>                                
			</tr>
		</thead>
		<tfoot>
                    <tr>
                        <td colspan="2">
                            <?php echo $this->pagination->getListFooter(); ?>
                        </td>
                    </tr>
		</tfoot>                
                <!--obtener los nombres de los usuarios de joomla, su usuario, idUser-->                
                <tbody>               
                 <?php $i=1; foreach ($this->items as $item) : ?>
			<tr class="row<?php echo $i=1-$i;?>">
				<td>
					<a class="pointer" onclick="if (window.parent) window.parent.<?php echo $function;?>
                                                ('<?php echo $item->idDepartamento; ?>','<?php echo $item->fraccionamientoId; ?>','<?php echo $item->numero; ?>' );">
                                                 <?php echo $this->escape($item->idDepartamento); ?>                                                 
                                        </a>
				</td>
				<td>
                                        <a class="pointer" onclick="if (window.parent) window.parent.<?php echo $function;?>
                                                ('<?php echo $item->idDepartamento; ?>','<?php echo $item->fraccionamientoId; ?>','<?php echo $item->numero; ?>' );">
                                                 <?php echo $this->escape($item->numero); ?>                                                 
                                        </a>
				</td>                               
			</tr>
                 <?php endforeach; ?>       
		</tbody>
                                
	</table>

	<div>
		<input type="hidden" name="task" value="" />
		<input type="hidden" name="boxchecked" value="0" />
		<input type="hidden" name="filter_order" value="<?php echo $ordering; ?>" />
		<input type="hidden" name="filter_order_Dir" value="<?php echo $direction; ?>" />                                
                
		<?php echo JHtml::_('form.token'); ?>
	</div>
</form>
