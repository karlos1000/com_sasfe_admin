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
<form action="<?php echo JRoute::_('index.php?option=com_sasfe&view=listadohistorial&depto_id=' .$depto_id.'&idFracc=' .$idFracc);?>" method="post" name="adminForm" id="adminForm">    
    
    <div id="estatusMsgHist" style="display: none;">        
        <div class="loading_estatus"><img src="<?php echo $this->imgLoading?>" /> </div>                
        <div id="msgHist" style="display: none;">Se ha cambiado correctamente el estatus del departamento, solo presion&eacute; el bot&oacute;n de cancelar para ver los cambios en la vista anterior.</div>
    </div>            
        
	<table class="table table-striped">
            <thead>
                <tr> 
                    <th width="80" class="nowrap center">                    
                        <?php echo JHtml::_('grid.sort', 'Depto', 'numero', $direction, $ordering); ?>                    
                    </th>   
                    <th width="100" class="nowrap center">                    
                        <?php echo 'Fecha Cierre'; ?>  
                    </th>
                    <th width="100" class="nowrap center">                    
                        <?php echo 'Fecha Apartado'; ?>  
                    </th>                                                                        
                    <th width="100" class="nowrap center">                    
                        <?php echo 'Cliente'; ?>  
                    </th> 
                    <th width="150" class="nowrap center">                    
                        <?php echo 'Reasignado/cancelado'; ?>  
                    </th> 
                    <?php if(count($this->ColEstatus)>0){?>                
                    <th width="100" class="nowrap center">                    
                        <?php echo 'Cambiar estatus'; ?>
                    </th>
                    <?php } ?>
                </tr>
            </thead>
            <tbody>            
            <?php foreach ($this->items as $i => $item): ?>
                
                <tr class="row<?php echo $i % 2; ?>">
                    <td>
                        <a href="<?php echo JRoute::_('index.php?option=com_sasfe&view=departamento&depto_id=' . $item->idDepartamento.'&idFracc='.$idFracc.'&idDatoGral='.$item->idDatoGeneral); ?>">
                            <?php echo $item->numero; ?>
                        </a>                                                
                    </td>                   
                    <td class="center">
                        <?php echo $item->fechaCierre; ?>
                    </td>                                        
                    <td class="center">
                        <?php echo $item->fechaApartado; ?>
                    </td>  
                    <td class="center">
                        <?php echo $item->nombreC; ?>
                    </td>  
                    <td class="center">
                        <?php 
                            if($item->esReasignado==1 && $item->esHistorico==1 && $item->obsoleto==1 && $item->datoProspectoId>0){
                               echo 'Regresado al prospecto';
                            }
                            elseif($item->esReasignado==1){
                               echo 'Reasignado';     
                            }else{
                               echo 'Cancelado';
                            }
                        ?>
                    </td>
                    <td class="center">
                        <?php if(count($this->ColEstatus)>0){ 
                                if($item->esReasignado==0){ ?>                                 
                                                        
                                   <!-- <select id="estatus_sel" name="estatus_sel" id_gral="<?php echo $item->idDatoGeneral;?>" id_dpt="<?php echo $item->idDepartamento;?>" >                                                  
                                        <option value="">--Seleccione--</option>
                                        <?php
                                       foreach ($this->ColEstatus as $itemCol) {
                                           if($itemCol->idDato!=88){                                            
                                            echo '<option value="' . $itemCol->idDato . '">' . $itemCol->nombre . '</option>';                        
                                           }
                                       }
                                       ?>   
                                   </select> -->                         
                                
                            <?php }else{ 
                                //index.php?option=com_sasfe&view=seldepartamentos&depto_id=4&idFracc=1&idDatoGral=7
                                $id_Dpt = $item->idDepartamento;
                                $id_Fracc = $this->idFracc;
                                $id_dato = $item->idDatoGeneral;
                                   $url = 'index.php?option=com_sasfe&view=seldepartamentos&depto_id='.$id_Dpt.'&idFracc='.$id_Fracc.'&idDatoGral='.$id_dato; 
                                ?>
                                    
                        <!--index.php?option=com_sasfe&view=seldepartamentos&depto_id='.$id_Dpt.'&idFracc='.$id_Fracc.'&idDatoGral='.$id_dato-->
                                    <?php echo ''; ?>
                                    <!--
                                    <a href=' <?php echo JRoute::_($url); ?> '>
                                        <?php echo 'Reasignar'; ?>
                                    </a>
                                    -->    
                                                           
                        <?php 
                            }  
                             } ?>
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
                <input type="hidden" name="id_Fracc" value="<?php echo $this->idFracc;?>" />
                
		<?php echo JHtml::_('form.token'); ?>
	</div>
</form>
