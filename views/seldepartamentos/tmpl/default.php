<?php
// no direct access
defined('_JEXEC') or die;

JHtml::addIncludePath(JPATH_COMPONENT.'/helpers/html');
JHtml::_('behavior.tooltip');
JHtml::_('behavior.multiselect');
JHtml::_('behavior.modal');
JHTML::_('behavior.formvalidation');

$ordering	= $this->escape($this->state->get('list.ordering'));
$direction	= $this->escape($this->state->get('list.direction'));

$path_url = JRoute::_('index.php?option=com_sasfe&view=seldepartamentos');

//echo '<pre>';
//    print_r($this->items);
//print_r($this->Fracc);
//echo '</pre>';
?>
<div class="notesUlMozi">      
</div>
<form action="<?php echo JRoute::_('index.php?option=com_sasfe&view=seldepartamentos&id_fracc='.$this->idFracc); ?>" method="post" name="adminForm" id="adminForm">
    <!--
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
    -->
    
    <div>
        <label for="idFraccSel">Fraccionamiento: <span class="star">&nbsp;*</span></label>                
        <select id="idFraccSel" name="idFraccSel" class="required">                          
            <?php
            foreach ($this->Fracc as $item) {
                if ($this->idFracc == $item->idFraccionamiento)
                    $sel = 'selected';
                else
                    $sel = '';
                echo '<option ' . $sel . ' value="' . $item->idFraccionamiento . '">' . $item->nombre . '</option>';
            }
            ?>                    
        </select>               
    </div>
    
    
    <table class="table table-striped">
        <thead>
            <tr>
                <th width="80">                    
                    <?php echo JHtml::_('grid.sort', 'No. de Departamento', 'numero', $direction, $ordering); ?>                    
                </th>                                
            </tr>
        </thead>
        <tbody>            
            <?php foreach ($this->arrDptsDisponibles as $i => $item): ?>                
                <tr class="row<?php echo $i % 2; ?>">                    
                    <td class="center">                       
                        <a href="javascript:void(0)" onclick="reasigarFuct(<?php echo $item->idDepartamento;?>)">
                            <?php echo $item->numero; ?>
                       </a>                                              
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
        
        <input type="hidden" name="depto_id" id="depto_id" value="<?php echo $this->depto_id;?>" />        
        <input type="hidden" name="idFracc" id="idFracc" value="<?php echo $this->idFracc;?>" />        
        <input type="hidden" name="idDatoGral" id="idDatoGral" value="<?php echo $this->idDatoGral;?>" />
        
        <input type="hidden" name="dptoNew" id="dptoNew" value="" />
        <!--<input type="hidden" name="idFraccNew" id="idFraccNew" value="" />
        <input type="hidden" name="idDatoGralNew" id="idDatoGralNew" value="" />-->
        
        <input type="hidden" name="path_url" id="path_url" value="<?php echo $path_url;?>" />                
        
    </div>
</form>
