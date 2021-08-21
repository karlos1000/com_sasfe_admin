<?php
// No direct access
defined('_JEXEC') or die('Restricted access');
JHtml::_('behavior.tooltip');
jimport('joomla.filesystem.file');
jimport('joomla.filesystem.folder');
JHtml::_('behavior.tooltip');
JHTML::_('behavior.modal');
JHTML::_('behavior.formvalidation');

$idDepto = (isset($this->data[0]->idDepartamento))?$this->data[0]->idDepartamento:'';
$idFracc = (isset($this->data[0]->fraccionamientoId))?$this->data[0]->fraccionamientoId:'';
$numero = (isset($this->data[0]->numero))?$this->data[0]->numero:'';
$precio = (isset($this->data[0]->precio))?$this->data[0]->precio:'';
	
//echo '<pre>';
//print_r($this->data);
//print_r($this->Fracc);
//echo '</pre>';

?>
<style>
.defaultKCD{ position: absolute;margin: 0px;padding: 0px;} label{ width: 170px; } 
.adminform label{
    min-width: 170px;
    padding: 0 5px 0 0; 
}    
</style>
<form class="form-validate form-horizontal" action="<?php echo JRoute::_('index.php?option=com_sasfe&task=catdepartamento'); ?>" method="post" name="adminForm" id="adminForm">    
    <div class="notesUlEsphabit">        
    </div>        
    
    <div style="">
        <fieldset class="adminform">        
            <div class="control-group">
                <div class="control-label">
                <label for="idFracc">Fraccionamiento: <span class="star">&nbsp;*</span></label>                
                </div>
                <div class="controls">
                <select id="idFracc" name="idFracc" class="required">                  
                    <option value="">--Seleccione--</option>
                    <?php
                    foreach ($this->Fracc as $item) {
                        if ($idFracc == $item->idFraccionamiento)
                            $sel = 'selected';
                        else
                            $sel = '';
                        echo '<option ' . $sel . ' value="' . $item->idFraccionamiento . '">' . $item->nombre . '</option>';                        
                    }
                    ?>                    
                </select>               
            </div>            
            </div>            
            <div class="control-group">
                <div class="control-label">
                <label for="depto_numero">N&uacute;mero depto: <span class="star">&nbsp;*</span></label>
                </div>
                <div class="controls">
                <input type="text" name="depto_numero" id="depto_numero" value="<?php echo $numero; ?>" class="required" style="width:150px;"/>                        
            </div>                                    
            </div>                                    
            <div class="control-group">
                <div class="control-label">
                <label for="depto_precio">Precio: <span class="star">&nbsp;*</span></label>
                </div>
                <div class="controls">
                <input type="text" name="depto_precio" id="depto_precio" value="<?php echo $precio; ?>" class="required number" style="width:80px;"/>                        
                </div>
            </div>                                    
        </fieldset>
    </div> 
        
        <input type="hidden" name="task" value="catdepartamento.edit" />
        <input type="hidden" name="check_un" value="<?php echo $this->id; ?>" />                        
        <?php echo JHtml::_('form.token'); ?>    
    
</form>
