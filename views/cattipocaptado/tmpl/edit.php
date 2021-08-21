<?php
// No direct access
defined('_JEXEC') or die('Restricted access');
JHtml::_('behavior.tooltip');
jimport('joomla.filesystem.file');
jimport('joomla.filesystem.folder');
JHtml::_('behavior.tooltip');
JHTML::_('behavior.modal');
JHTML::_('behavior.formvalidation');

$idTipoCaptado = (isset($this->data[0]->idTipoCaptado))?$this->data[0]->idTipoCaptado:'';
$tipoCaptado = (isset($this->data[0]->tipoCaptado))?$this->data[0]->tipoCaptado:'';
$activo = (isset($this->data[0]->activo))?$this->data[0]->activo:'';

// echo '<pre>';
// print_r($this->data);
// echo '</pre>';

?>
<style>
.defaultKCD{ position: absolute;margin: 0px;padding: 0px;} label{ width: 170px; } 
.adminform label{
    min-width: 170px;
    padding: 0 5px 0 0; 
}    
</style>
<form class="form-validate form-horizontal" action="<?php echo JRoute::_('index.php?option=com_sasfe&task=cattipocaptado'); ?>" method="post" name="adminForm" id="adminForm" enctype="multipart/form-data">    
    <div class="notesUlEsphabit">        
    </div>
    
    <div style="">
        <fieldset class="adminform">
            <div class="control-group">
                <div class="control-label">
                    <label for="tipocaptado_nombre">Nombre: <span class="star">&nbsp;*</span></label>
                </div>
                <div class="controls">
                    <input type="text" name="tipocaptado_nombre" id="tipocaptado_nombre" value="<?php echo $tipoCaptado; ?>" required style="width:150px;"/>
                </div>
            </div>
            <div class="control-group">
                <div class="control-label">
                    <label for="tipocaptado_activo">Activo:</label>
                </div>
                <div class="controls">
                    <input type="checkbox" name="tipocaptado_activo" id="tipocaptado_activo" value="<?php echo $activo; ?>" <?php echo ($activo==1)?'checked':''; ?>  />
                </div>
            </div>
        </fieldset>
    </div> 
        
        <input type="hidden" name="task" value="cattipocaptado.edit" />
        <input type="hidden" name="check_un" value="<?php echo $this->id; ?>" />                        
        <?php echo JHtml::_('form.token'); ?>    
    
</form>
