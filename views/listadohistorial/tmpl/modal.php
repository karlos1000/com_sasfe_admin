<?php
// no direct access
defined('_JEXEC') or die;

JHtml::addIncludePath(JPATH_COMPONENT.'/helpers/html');
JHtml::_('behavior.tooltip');
$function	= JRequest::getCmd('function', 'jSelectHistorial');
$ordering	= $this->escape($this->state->get('list.ordering'));
$direction	= $this->escape($this->state->get('list.direction'));
$depto_id	= $this->state->get('filter.depto_id');
$idFracc	= $this->state->get('filter.idFracc');

?>
<form action="<?php echo JRoute::_('index.php?option=com_sasfe&view=listadohistorial&layout=modal&tmpl=component&function='.$function.'&depto_id=' .$depto_id.'&idFracc=' .$idFracc);?>" method="post" name="adminForm" id="adminForm">

	<table class="table table-striped">
            <thead>
                <tr> 
                    <th width="80">                    
                        <?php echo JHtml::_('grid.sort', 'Depto', 'numero', $direction, $ordering); ?>                    
                    </th>   
                    <th width="100">                    
                        <?php echo 'Fecha Cierre'; ?>  
                    </th>
                    <th width="100">                    
                        <?php echo 'Fecha Apartado'; ?>  
                    </th>                                                                        
                </tr>
            </thead>
            <tbody>            
            <?php foreach ($this->items as $i => $item): ?>
                
                <tr class="row<?php echo $i % 2; ?>">
                    <td>
                        <a class="pointer" onclick="if (window.parent) window.parent.<?php echo $function;?>
                                ('<?php echo $item->idDepartamento; ?>','<?php echo $idFracc; ?>','<?php echo $item->idDatoGeneral; ?>' );">
                                 <?php echo $this->escape($item->numero); ?>                                                 
                        </a>
                    </td>
                    <!--
                    <td class="center">                        
                        <a href="?php echo JRoute::_('index.php?option=com_sasfe&view=departamento&depto_id=' . $item->idDepartamento.'&idFracc='.$idFracc.'&idDatoGral='.$item->idDatoGeneral); ?>" class="closePopop" >
                                ?php echo $item->numero; ?>
                        </a>                        
                    </td>
                    -->
                    <td class="center">
                        <?php echo $item->fechaCierre; ?>
                    </td>                                        
                    <td class="center">
                        <?php echo $item->fechaApartado; ?>
                    </td>                                                                                                    
                </tr>                
            <?php endforeach; ?>
                         
        </tbody>
		<tfoot>
			<tr>
				<td colspan="15">
					<?php echo $this->pagination->getListFooter(); ?>
				</td>
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
